<?
if (!class_exists("CAcoes"))
{
    class CAcoes extends CDataClass
    {
        var $iCodAcao;
        var $bMostrarTodas;
        var $iCodTipo;
        var $iCodCliente;
        var $iCodClienteOrNull;
        
        function CAcoes()
        {
            $this->szChave   = 'CodigoAcao';
            $this->szDisplay = 'Nome';
            $this->szTableName = 'Acoes';
            $this->szOrderBy = 'A.CodigoAcao';
            $this->bMostrarTodas = false;
        }    
        
        function GetSQL()
        {
            $filtro = '0=0';
            if (!$this->bMostrarTodas)
                $filtro = "A.CodigoTipoAcao<>".tipo_acao_itemgaleria;
            if ($this->iCodAcao > 0)
                $filtro .= " AND A.CodigoAcao=$this->iCodAcao";
            if ($this->iCodTipo > 0)
                $filtro .= " AND A.CodigoTipoAcao=$this->iCodTipo";
            if ($this->iCodCliente == -1)
                $filtro .= " AND A.CodigoCliente IS NULL";
            if ($this->iCodCliente > 0)
                $filtro .= " AND A.CodigoCliente=$this->iCodCliente";

            if ($this->iCodClienteOrNull > 0)
                $filtro .= " AND (A.CodigoCliente=$this->iCodClienteOrNull OR A.CodigoCliente IS NULL)";
            
                
            $ordem = $this->szOrderBy;
            
            return "SELECT A.*, AF.Nome as NomeFilha FROM Acoes A LEFT JOIN Acoes AF ON A.CodigoAcaoFilha=AF.CodigoAcao WHERE $filtro ORDER BY $ordem";
        }
        
        function LoadFromDB()
        {
            $this->mItems=null;           
            $qr = mysql_query($this->GetSQL()) or die(mysql_error());
            while ($obj = mysql_fetch_object($qr))
            {
                $obj->Campos = $this->LoadCampos($obj->CodigoAcao);
                $obj->QtdeCamposSemIdioma = $this->QtdeCamposAcaoSemIdioma($obj);
                $obj->AutoFilhos = $this->LoadAutoFilhos($obj->CodigoAcao);
                $this->mItems[ count($this->mItems) ] = $obj;
            }
        }
        
        function GetCampoAcao($objacao, $codcampo)
        {
            for ($i=0;$i<count($objacao->Campos);$i++)
                if ($objacao->Campos[$i]->CodigoCampoAcao == $codcampo)
                    return $objacao->Campos[$i];
        }
        
        
        function LoadAutoFilhos($cod)
        {
            $r = null;
            $qr = mysql_query("SELECT AF.*, A.Nome as Acao FROM Acoes_AutoFilhos AF INNER JOIN Acoes A ON AF.CodigoAcaoFilho=A.CodigoAcao WHERE AF.CodigoAcao=$cod");
            while ($obj = mysql_fetch_object($qr))
                $r[] = $obj;
            return $r;
        }

        function QtdeCamposAcaoSemIdioma($obj)
        {
            $r = 0;
            for ($i=0;$i<count($obj->Campos);$i++)
                if ($obj->Campos[$i]->VariaIdioma == 0)
                    $r++;
            return $r;
        }
        
        function LoadCampos($codacao)
        {
            $r = null;
            $qr = mysql_query("SELECT * FROM Acoes_Campos WHERE CodigoAcao=$codacao ORDER BY CodigoCampoAcao");
            while ($obj = mysql_fetch_object($qr))
            {
                $codcampo = $obj->CodigoCampoAcao;
                $qr2 = mysql_query("SELECT * FROM Campos_ListaValores WHERE CodigoCampoAcao=$codcampo");
                while ($o2 = mysql_fetch_object($qr2))
                    $obj->ListaValores[]=$o2;
                $r[] = $obj;
            }
            return $r;
        }
        
        function PrintComboCampo($objcampo, $sel, $retornar=false)
        {
            $html = '';
            for ($i=0;$i<count($objcampo->ListaValores);$i++)
            {
                $s = strcmp($objcampo->ListaValores[$i]->Valor,$sel)==0?'selected':'';
                $html .= '<option value="'.$objcampo->ListaValores[$i]->Valor.'" '.$s.'>'.$objcampo->ListaValores[$i]->ValorDisplay.'</option>';
            }
            if ($retornar)
                return $html;
            else
                print $html;
        }
        
        function RemoverNulosSeHouverEspecificos()
        {
            $especificos = false;
            $r = null;
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->CodigoCliente > 0)
                    $r[] = $this->mItems[$i];
            if (count($r) > 0)
                $this->mItems = $r;
        }
        
    }
}

?>
