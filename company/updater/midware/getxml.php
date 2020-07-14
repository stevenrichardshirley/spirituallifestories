<?
include_once('../header_classes.php');

// recebendo $siteid (codigo do site) e $nodeid (codigo do nó)  e $languageid
// recebendo startId e ammount (ambos opcionais) e somente utilizados quando vier $nodeid

function getLinkValue($v)
{
    if (strlen(trim($v)) > 0)
    {
        if (strpos($v,'http://') === false && strpos($v,'https://') === false)
            $v = 'http://'.$v;
    }
    return $v;
}

if ($siteid > 0)
{
    $itens = new CItensSite();
    
    $itens->iCodSite = $siteid;
    
    $idiomas = new CIdiomas();
    $idiomas->iCodSite = $siteid;
    $idiomas->LoadFromDB();
    
    $sites = new CSites();
    $sites->iCodSite = $siteid;
    $sites->LoadFromDB();
    $site = $sites->mItems[0];
    
    print '<updater>';
    
    if ($nodeid > 0)
    {
        $languageid = ($languageid > 0)?$languageid:$site->CodigoIdiomaPadrao;

        // pedindo um nó especifico do site
        //$itens->iCodNode = $nodeid;
        if (isset($startId))
            $itens->mCodigoPartindo_Exceto = array($startId, $nodeid);
        if (isset($ammount))
        {
            $itens->iCodigoNode_Pai = $nodeid;
            $itens->iLimite = $ammount+1;
        }
        if ($inverso==1)
            $itens->bOrdemInversa = true;
    
        $itens->LoadFromDB();      
        //$item = $itens->mItems[0];
        
        //print '<site id="'.$siteid.'" client_id="'.$site->CodigoUsuario.'" active="'.($site->Ativo==1?'true':'false').'" authorized_domain="'.$site->URL.'">';
         
        // pedindo o nó principal do site
        $item = $itens->GetItem($nodeid);
        if ($item)
        {
            print '<button name="'.$item->Idiomas[$languageid]->Nome.'" id="'.$siteid.'" action="'.$item->Acao.'">';
            print '<xml_data>';
            print '<text>'.$item->Idiomas[$languageid]->Texto.'</text>';
            for ($i=0;$i<count($item->CamposAcao);$i++)
                if ($languageid > 0 && ($item->CamposAcao[$i]->CodigoIdioma == $languageid || $item->CamposAcao[$i]->CodigoIdioma<=0))
                {
                    $valorcampo = $item->CamposAcao[$i]->Valor;
                    if ($item->CamposAcao[$i]->CodigoTipoCampo==tipo_campo_checkbox)
                        $valor = $valorcampo=='on'?'true':'false';
                    else if ($item->CamposAcao[$i]->CodigoTipoCampo==tipo_campo_link)
                        $valor = getLinkValue($valorcampo);
                    else
                        $valor = $valorcampo;
                        
                    print '<'.$item->CamposAcao[$i]->Campo.'>'.$valor;
                    print '</'.$item->CamposAcao[$i]->Campo.'>';
                }

            
            function printxml_item($codpai, $colecao, $codidioma)
            {
                for ($i=0;$i<count($colecao->mItems);$i++)
                    if ($colecao->mItems[$i]->CodigoParentNode == $codpai)
                    {
                        print '<button nome="'.$colecao->mItems[$i]->Idiomas[$codidioma]->Nome.'" id="'.$colecao->mItems[$i]->CodigoNode.'" action="'.$colecao->mItems[$i]->Acao.'">';
                        
                        // dados do botao
                        print '<xml_data>';
                        print '<text>'.$colecao->mItems[$i]->Idiomas[$codidioma]->Texto.'</text>';


                        for ($j=0;$j<count($colecao->mItems[$i]->CamposAcao);$j++)
                            if ($codidioma > 0 && ($colecao->mItems[$i]->CamposAcao[$j]->CodigoIdioma == $codidioma || $colecao->mItems[$i]->CamposAcao[$j]->CodigoIdioma<=0))
                            {
                                if ($colecao->mItems[$i]->CamposAcao[$j]->CodigoTipoCampo==tipo_campo_checkbox)
                                    $valor = $colecao->mItems[$i]->CamposAcao[$j]->Valor=='on'?'true':'false';
                                else if ($colecao->mItems[$i]->CamposAcao[$j]->CodigoTipoCampo==tipo_campo_link)
                                    $valor = getLinkValue($colecao->mItems[$i]->CamposAcao[$j]->Valor);
                                else
                                    $valor = $colecao->mItems[$i]->CamposAcao[$j]->Valor;
                                    
                                print '<'.$colecao->mItems[$i]->CamposAcao[$j]->Campo.'>'.$valor;
                                print '</'.$colecao->mItems[$i]->CamposAcao[$j]->Campo.'>';
                                
                                // autosize file geral
                                if ($colecao->mItems[$i]->CamposAcao[$j]->CodigoTipoCampo == tipo_campo_arqgeral && 
                                        file_exists('../site_files/'.$valor))
                                {
                                    print '<'.$colecao->mItems[$i]->CamposAcao[$j]->Campo.'_autosize>'.filesize('../site_files/'.$valor);
                                    print '</'.$colecao->mItems[$i]->CamposAcao[$j]->Campo.'_autosize>';
                                    
                                }
                            }

                        print '</xml_data>';
                        
                        printxml_item($colecao->mItems[$i]->CodigoNode, $colecao, $codidioma);
                        print '</button>';
                    }
            
            }
            printxml_item($nodeid, $itens, $languageid);
            
            print '</xml_data>';
            print '</button>';
        }
        else
            print 'error-node-not-found';
            
        
        /*
        print '<button name="'.$item->Idiomas[$languageid]->Nome.'" id="'.$siteid.'" action="'.$item->Acao.'">';
        print '<xml_data>';
        print '<text>'.$item->Idiomas[$languageid]->Texto.'</text>';
        for ($i=0;$i<count($item->CamposAcao);$i++)
        {
            if ($item->CamposAcao[$i]->CodigoTipoCampo==tipo_campo_checkbox)
                $valor = $item->CamposAcao[$i]->Valor=='on'?'true':'false';
            else
                $valor = $item->CamposAcao[$i]->Valor;
                
            print '<'.$item->CamposAcao[$i]->Campo.'>'.$valor;
            print '</'.$item->CamposAcao[$i]->Campo.'>';
        }
        print '</xml_data>';
        print '</button>';
        */
        
        
         
    }
    else
    {
        $itens->LoadFromDB();
        
        print '<site id="'.$siteid.'" client_id="'.$site->CodigoUsuario.'" active="'.($site->Ativo==1?'true':'false').'" authorized_domain="'.$site->URL.'">';
         
        $languageid = ($languageid > 0)?$languageid:$site->CodigoIdiomaPadrao;
                    
        // printando todos os possiveis idiomas
        print '<languages selected="'.$languageid.'">';
        for ($i=0;$i<count($idiomas->mItems);$i++)
            print '<language id="'.$idiomas->mItems[$i]->CodigoIdioma.'">'.$idiomas->mItems[$i]->Idioma.'</language>';
        print '</languages>';

        // pedindo o nó principal do site
        $root = $itens->GetRootNode();
        if ($root)
        {
            print '<menu nome="'.$root->Nome.'">';
            
            function printxml_item($codpai, $colecao, $codidioma)
            {
                for ($i=0;$i<count($colecao->mItems);$i++)
                    if ($colecao->mItems[$i]->CodigoParentNode == $codpai && $colecao->mItems[$i]->CodigoTipoAcao != tipo_acao_itemgaleria)
                    {
                        print '<button nome="'.$colecao->mItems[$i]->Idiomas[$codidioma]->Nome.'" id="'.$colecao->mItems[$i]->CodigoNode.'" action="'.$colecao->mItems[$i]->Acao.'">';
                        printxml_item($colecao->mItems[$i]->CodigoNode, $colecao, $codidioma);
                        print '</button>';
                    }
            
            }
            printxml_item($root->CodigoNode, $itens, $languageid);
            
            print '</menu>';
        }
        else
            print 'error-without-rootnode';
            
        print '</site>';
    }
    
    print '</updater>';
    
} else
    print 'error';


  
?>

