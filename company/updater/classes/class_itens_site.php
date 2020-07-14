<?
if (!class_exists("CItensSite"))
{
    class CItensSite extends CDataClass
    {
        var $iCodSite;
        var $iCodNode;
        var $iCodParentNode;
        var $iCodAcao;
        var $mCodigoPartindo_Exceto;
        var $iLimite;
        var $iCodigoNode_Pai;
        var $bOrdemInversa;
        var $iCodExceto;
        var $mCodigos;
        var $bLoadInativos;
        
        function CItensSite()
        {
            $this->szChave   = 'CodigoNode';
            $this->szDisplay = 'Nome';
            $this->szTableName = 'Sites_Nodes';
        }    
        
        function GetSQL()
        {
            $filtro = '0=0';
            if ($this->iCodSite > 0 )
                $filtro = "N.CodigoSite=$this->iCodSite";
            if (strlen($this->mCodigos) > 0)
                $filtro .= " AND N.CodigoNode IN ($this->mCodigos)";
            if ($this->iCodExceto > 0)
                $filtro .= " AND N.CodigoNode <> $this->iCodExceto";
            if ($this->iCodNode > 0 )
                $filtro .= " AND N.CodigoNode=$this->iCodNode";
            if ($this->iCodParentNode > 0)
                $filtro .= " AND N.CodigoParentNode=$this->iCodParentNode";
            if (!$this->bLoadInativos)
                $filtro .= " AND N.IsAtivo=1";
                
            if (is_array($this->mCodigoPartindo_Exceto))
            {
                list($partindo,$exceto) = $this->mCodigoPartindo_Exceto;
                $filtro .= " AND (N.CodigoNode>$partindo OR N.CodigoNode=$exceto)";
            }
            if ($this->iCodigoNode_Pai>0)
                $filtro .= " AND (N.CodigoNode=$this->iCodigoNode_Pai OR N.CodigoParentNode=$this->iCodigoNode_Pai)";
                
            if ($this->iCodAcao > 0)
                $filtro .= " AND N.CodigoAcao=".$this->iCodAcao;
            
            $limitador = $this->iLimite>0?" LIMIT $this->iLimite":'';

            $inversa = $this->bOrdemInversa?'DESC':'';
               
            return  "SELECT N.*, S.CodigoIdiomaPadrao, A.Nome as Acao, A.CodigoTipoAcao FROM Sites_Nodes N INNER JOIN Usuarios_Sites S ON N.CodigoSite=S.CodigoSite 
                            INNER JOIN Acoes A ON N.CodigoAcao=A.CodigoAcao
                            WHERE $filtro ORDER BY N.Ordem $inversa $limitador";
        }
        
        function LoadFromDB()
        {
            $this->mItems=null;           
            $qr = mysql_query($this->GetSQL()) or die(mysql_error());
            while ($obj = mysql_fetch_object($qr))
            { 
                //$obj->Idiomas = $this->LoadIdiomas($obj->CodigoNode);
                //$obj->CamposAcao = $this->LoadCamposAcao($obj->CodigoNode);
                $this->mItems[ count($this->mItems) ] = $obj;
            }
            //$this->CalcLevels();
            $this->LoadIdiomas();
            $this->LoadCamposAcao();
        }        
        
        function CalcLevels()
        {
            for ($i=0;$i<count($this->mItems);$i++)
            {
                if ($this->mItems[$i]->CodigoParentNode > 0)
                {
                    $level = 0;
                    $item = $this->GetItem($this->mItems[$i]->CodigoParentNode);
                    while ($item != 0)
                    {
                        $level++;
                        if ($item->CodigoParentNode > 0)
                            $item = $this->GetItem($item->CodigoParentNode);
                        else 
                            $item = 0;
                    }
                    $this->mItems[$i]->Level = $level;
                }
                else
                {
                    $this->mItems[$i]->Level = 0;
                }
            }
        }
        
        function LoadCamposAcao()
        {
            $codigos = null;
            for ($i=0;$i<count($this->mItems);$i++)
                $codigos[] = $this->mItems[$i]->CodigoNode;

            if (is_array($codigos) && count($codigos) > 0)
            {
                $codigos = implode(',', $codigos);
                $qr = mysql_query("SELECT NCA.*, AC.Campo, AC.CodigoTipoCampo FROM Nodes_Campos_Acao NCA INNER JOIN Acoes_Campos AC ON NCA.CodigoCampoAcao=AC.CodigoCampoAcao WHERE NCA.CodigoNode IN ($codigos) ORDER BY NCA.CodigoNode");
                while ($obj = mysql_fetch_object($qr))
                {
                    $indexof = $this->IndexOf($obj->CodigoNode);
                    $this->mItems[$indexof]->CamposAcao[] = $obj;
                }
                    
            }
        }
        
        function IndexOf($cod)
        {
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->CodigoNode == $cod)
                    return $i;
            return -1;
        }
        
        function LoadIdiomas()
        {
            $codigos = null;
            for ($i=0;$i<count($this->mItems);$i++)
                $codigos[] = $this->mItems[$i]->CodigoNode;

            if (is_array($codigos) && count($codigos) > 0)
            {
                $codigos = implode(',', $codigos);
                $qr = mysql_query("SELECT T.CodigoNode, T.CodigoIdioma, T.Texto, '' as Nome FROM Nodes_Textos T WHERE T.CodigoNode IN ($codigos)     
                                    UNION
                                   SELECT N.CodigoNode, N.CodigoIdioma, '' as Texto, N.Nome FROM Nodes_Nomes N WHERE N.CodigoNode IN ($codigos) ORDER BY 1");
                while ($obj = mysql_fetch_object($qr))
                {
                    $indexof = $this->IndexOf($obj->CodigoNode);
                    
                    if (strlen(trim($obj->Nome)) == 0 && strlen(trim($obj->Texto))>0)
                        $this->mItems[$indexof]->Idiomas[$obj->CodigoIdioma]->Texto = $obj->Texto;
                    else if (strlen(trim($obj->Nome)) > 0 && strlen(trim($obj->Texto))==0)

                        $this->mItems[$indexof]->Idiomas[$obj->CodigoIdioma]->Nome = $obj->Nome;
                }
            }
        }
        
        function GetMaxOrdemPai($codpai)
        {
            $res = 0;
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->CodigoParentNode == $codpai &&
                    $this->mItems[$i]->Ordem > $res)
                    $res = $this->mItems[$i]->Ordem;
                    
            return $res;
        }
        
        function GetCampoValue($idx, $nomecampo)
        {
            for ($i=0;$i<count($this->mItems[$idx]->CamposAcao);$i++)
                if ( strcmp($this->mItems[$idx]->CamposAcao[$i]->Campo, $nomecampo) == 0 )
                    return $this->mItems[$idx]->CamposAcao[$i]->Valor;
            return '';
        }

        function GetCampoValueByCod($idx, $codcampo)
        {
            for ($i=0;$i<count($this->mItems[$idx]->CamposAcao);$i++)
                if ( $this->mItems[$idx]->CamposAcao[$i]->CodigoCampoAcao == $codcampo )
                    return $this->mItems[$idx]->CamposAcao[$i]->Valor;
            return '';
        }

        
        function GetRootNode()
        {
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->CodigoParentNode == 0)
                    return $this->mItems[$i];
            return null;
        }
        
        function slugify($string)
        { 
             $string = trim($string);
             $table = array(
                    ':'=>'','&'=>'e','Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 
                    'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                    'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
                    'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
                    'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
                    'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
                    'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
                    'ÿ'=>'y', '/' => '-', ' ' => '-'
            );
            $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
            return strtolower(strtr($string, $table));
        }
        
        function GetSLUGFromItem($idx)
        {
            if (strlen($this->mItems[$idx]->SLUG) == 0)
            {
                $slugs = $this->slugify( $this->mItems[$idx]->Idiomas[1]->Nome );
                // varrendo pais
                if ($this->mItems[$idx]->CodigoParentNode > 0)
                {
                    list($slug_pai, $codpai, $codacao) = mysql_fetch_row(mysql_query("SELECT SLUG, CodigoParentNode, CodigoAcao FROM Sites_Nodes WHERE CodigoNode=".$this->mItems[$idx]->CodigoParentNode));
                    if ($codacao == 27)
                        list($slug_pai, $codpai, $codacao) = mysql_fetch_row(mysql_query("SELECT SLUG, CodigoParentNode, CodigoAcao FROM Sites_Nodes WHERE CodigoNode=$codpai"));
                    
                    if (strlen($slug_pai) > 0)
                        $slugs = $slug_pai.'/'.$slugs;
                }
                mysql_query("UPDATE Sites_Nodes SET SLUG='$slugs' WHERE CodigoNode=".$this->mItems[$idx]->CodigoNode);
                return $slugs;
            }
            else
                return $this->mItems[$idx]->SLUG;
        }
        
        function SaveNomeIdioma($coditem, $codidioma, $nome)
        {
            mysql_query("INSERT INTO Nodes_Nomes (CodigoIdioma, CodigoNode, Nome) VALUES ($codidioma, $coditem, '$nome')");
        }
        
        function SaveTextoIdioma($coditem, $codidioma, $texto)
        {
            mysql_query("INSERT INTO Nodes_Textos (CodigoIdioma, CodigoNode, Texto) VALUES ($codidioma, $coditem, '$texto')");
        }


        function PrintCombo($cod=0, $filtrar_itens_galeria = false, $codexceto=0, $filtrar_galeria=false, $ordem_alfa=false)
        {
            $lista = null;
            for ($i=0;$i<count($this->mItems);$i++)
             if ( ($codexceto != $this->mItems[$i]->CodigoNode) && (!$filtrar_itens_galeria || $this->mItems[$i]->CodigoTipoAcao != tipo_acao_itemgaleria) &&
                   (!$filtrar_galeria || $this->mItems[$i]->CodigoTipoAcao != tipo_acao_galeria))
                {
                    if ($this->mItems[$i]->CodigoParentNode == 0)
                        $text = $this->mItems[$i]->Nome;
                    else
                        $text = $this->mItems[$i]->Idiomas[($this->mItems[$i]->CodigoIdiomaPadrao>0?$this->mItems[$i]->CodigoIdiomaPadrao:1)]->Nome;
                  
                    $lista[] = $text.'%%'.$this->mItems[$i]->CodigoNode;
                }
             if(is_array($lista))
             {
                if ($ordem_alfa)
                    sort($lista);
                for ($i=0;$i<count($lista);$i++)
                {
                    list($iitem,$icod) = explode('%%', $lista[$i]);
                    $sel = '';
                    if ($icod == $cod)
                        $sel = 'selected';
                    print '<option value="'.$icod.'" '.$sel.' >'.$iitem.'</option>';
                }
             }
        }
        
        function MoveItemUp($coditem)
        {
            $dados     = mysql_fetch_object(mysql_query("SELECT Ordem, CodigoParentNode FROM Sites_Nodes WHERE CodigoNode=$coditem"));
            $ordem     = $dados->Ordem;
            $codparent = $dados->CodigoParentNode;
            $dados_troca = mysql_fetch_object(mysql_query("SELECT CodigoNode, Ordem FROM Sites_Nodes WHERE CodigoParentNode=$codparent AND Ordem<$ordem ORDER BY Ordem DESC LIMIT 1"));
            $coditem_troca = $dados_troca->CodigoNode;
            $ordem_troca   = $dados_troca->Ordem;
            mysql_query("UPDATE Sites_Nodes SET Ordem=$ordem WHERE CodigoNode=$coditem_troca");
            mysql_query("UPDATE Sites_Nodes SET Ordem=$ordem_troca WHERE CodigoNode=$coditem");
        }

        function MoveItemDown($coditem)
        {
            $dados     = mysql_fetch_object(mysql_query("SELECT Ordem, CodigoParentNode FROM Sites_Nodes WHERE CodigoNode=$coditem"));
            $ordem     = $dados->Ordem;
            $codparent = $dados->CodigoParentNode;
            $dados_troca = mysql_fetch_object(mysql_query("SELECT CodigoNode, Ordem FROM Sites_Nodes WHERE CodigoParentNode=$codparent AND Ordem>$ordem ORDER BY Ordem LIMIT 1"));
            $coditem_troca = $dados_troca->CodigoNode;
            $ordem_troca   = $dados_troca->Ordem;
            mysql_query("UPDATE Sites_Nodes SET Ordem=$ordem WHERE CodigoNode=$coditem_troca");
            mysql_query("UPDATE Sites_Nodes SET Ordem=$ordem_troca WHERE CodigoNode=$coditem");
        }
        
        function DeleteFromDB($cod)
        {
            // antes de excluir um item, ver se ele tem algum campo tipo arquivo e remover o arquivo
            $qr = mysql_query("SELECT NCA.Valor FROM Nodes_Campos_Acao NCA INNER JOIN Acoes_Campos AC ON NCA.CodigoCampoAcao=AC.CodigoCampoAcao
                                    WHERE AC.CodigoTipoCampo IN (".campos_tipo_arquivo.") AND NCA.CodigoNode=$cod");
            while ($rr = mysql_fetch_row($qr))
                if (file_exists('site_files/'.$rr[0]))
                    unlink('site_files/'.$rr[0]);
            
            mysql_query("DELETE FROM $this->szTableName WHERE $this->szChave=$cod") or die(mysql_error());
            $this->AfterDelete($cod);
        }
        
        function GetItensDoPai($codpai)
        {
            $r = null;
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->CodigoParentNode == $codpai)
                    $r[] = $this->mItems[$i];
            
            return $r;    
        }

        function CountItensDoPai($codpai)
        {
            $r = 0;
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->CodigoParentNode == $codpai)
                    $r++;
            return $r;    
        }
        
        function AutoReOrder($codcampo, $codpai)
        {
            // vendo o tipo do campo
            $tipo = mysql_fetch_row(mysql_query("SELECT CodigoTipoCampo FROM Acoes_Campos WHERE CodigoCampoAcao=$codcampo"));
            $tipo = $tipo[0];
            if ($tipo == tipo_campo_data)
            {
                // somente implementado para o tipo data por enquanto
                $qr = mysql_query("SELECT N.CodigoNode, N.Ordem, NCA.Valor FROM Nodes_Campos_Acao NCA INNER JOIN Sites_Nodes N ON NCA.CodigoNode=N.CodigoNode 
                                        WHERE NCA.CodigoCampoAcao=$codcampo AND N.CodigoParentNode=$codpai ORDER BY N.Ordem");
                $r = null;
                while ($obj=mysql_fetch_object($qr))
                    $r[]=$obj;
                    
                if (is_array($r) && count($r)>0)
                {
                    usort($r, "cmpautoreorder_dates");
                    // salvando
                    $novaordem=1;
                    for ($i=0;$i<count($r);$i++)
                    {
                        if ($novaordem != $r[$i]->Ordem)
                            mysql_query("UPDATE Sites_Nodes SET Ordem=$novaordem WHERE CodigoNode=".$r[$i]->CodigoNode);
                        $novaordem++;
                    }
                }
            }
            
        }
        
        function RenderJS_Array($campo, $isfile=false)
        {
            $lista = array();
            $prefile = $isfile?'/updater/site_files/':'';
            for ($i=0;$i<count($this->mItems);$i++)
            {
                $v = $this->GetCampoValue($i,$campo);
                $lista[] = "'".$prefile.$v."'";
            }
            return implode(',', $lista);
        }
        
    }
}

function cmpautoreorder_dates($a,$b)
{
    if ($a->Valor==$b->Valor)
        return 0;
    else
    {
        $dta=$dtb=0;
        if (strlen($a->Valor)>6)
        {
            list($dd,$mm,$aa) = explode('/', $a->Valor);
            $dd=trim($dd);
            $mm=trim($mm);
            $aa=trim($aa);
            $aa=strlen($aa)==2?'20'.$aa:$aa;
            $dta=mktime(0,0,0,$mm,$dd,$aa);
        }
        if (strlen($b->Valor)>6)
        {
            list($dd,$mm,$aa) = explode('/', $b->Valor);
            $dd=trim($dd);
            $mm=trim($mm);
            $aa=trim($aa);
            $aa=strlen($aa)==2?'20'.$aa:$aa;
            $dtb=mktime(0,0,0,$mm,$dd,$aa);
        }
        if ($dta > $dtb)
            return 1;
        else
            return -1;
    }       
    
}

?>
