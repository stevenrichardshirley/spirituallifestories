<?
include_once('header_classes.php');

$itens = new CItensSite();
$itens->iCodNode = $codsel;
$itens->LoadFromDB();
$item = $itens->mItems[0];

$primeiro_nome = ($item->CodigoAcao == 27);

$idiomas = new CIdiomas();
$idiomas->iCodSite = $item->CodigoSite;
$idiomas->LoadFromDB();

$itens_galeria = new CItensSite();
$itens_galeria->iCodSite = $item->CodigoSite;

$acoes = new CAcoes();
$acoes->iCodAcao = $item->CodigoAcao;
$acoes->LoadFromDB();

$GLOBALS['acoes'] = $acoes;

$objacao = $acoes->mItems[0];

if ($objacao->IsOrdemInversa == 1)
{
    $itens_galeria->bOrdemInversa = true;
    if ($acao == 'movedown')
        $acao = 'moveup';
    else if ($acao == 'moveup')
        $acao = 'movedown';
}

// pegando acao filha
$acoesfilha = new CAcoes();
$acoesfilha->iCodAcao = $objacao->CodigoAcaoFilha;
$acoesfilha->bMostrarTodas = true;
$acoesfilha->LoadFromDB();
$acaofilha = $acoesfilha->mItems[0];        

$sites = new CSites();
$sites->iCodSite = $item->CodigoSite;
$sites->LoadFromDB();
$site = $sites->mItems[0];

if ($codidioma <= 0)
    $codidioma = $site->CodigoIdiomaPadrao>0?$site->CodigoIdiomaPadrao:$idiomas->mItems[0]->CodigoIdioma;


if ($acao == 'criar')
{
    if ( ($objacao->CodigoAcao == acao_galeria_fotos || $objacao->IsGaleriaFotos == 1) && $coditemsel <= 0)
    {          
        //print_r($objacao);
        // criação de multiplos itens em batch - um para cada foto da galeria
        if (is_array($_FILES['campoarq']))
        {
            foreach ($_FILES['campoarq']['name'] as $codcampoacao=>$lista_arquivos)
            {
                for ($i=0;$i<count($lista_arquivos);$i++)
                {
                    // para cada arquivo, criar um item em batch
                    $coditempai = $item->CodigoNode;
                    $codsitearq    = $item->CodigoSite;
                    $proxordem  = mysql_fetch_row(mysql_query("SELECT max(Ordem) FROM Sites_Nodes WHERE CodigoSite=$codsitearq AND CodigoParentNode=$coditempai"));
                    $inf->Ordem = $proxordem[0]+1;
                    $inf->CodigoSite = $codsitearq;
                    $inf->CodigoParentNode = $coditempai;
                    $inf->CodigoAcao = $objacao->CodigoAcaoFilha;
                    $inf->SEO_Titulo = mysql_real_escape_string($seo_titulo);
                    $inf->SEO_Descricao = mysql_real_escape_string($seo_descricao);
                    $inf->SEO_Chaves = mysql_real_escape_string($seo_chave);
                    
                    $codnovo = $itens->SaveToDB($inf);

                    // nome do arquivo: coditem.xxx
                    $extensao = explode('.',$_FILES['campoarq']['name'][$codcampoacao][$i]);
                    $extensao = strtolower($extensao[count($extensao)-1]);
                    $nomearqdest = $codnovo.'_'.$codcampoacao.'.'.$extensao;
                    move_uploaded_file($_FILES['campoarq']['tmp_name'][$codcampoacao][$i], 'site_files/'.$nomearqdest);
                    // salvando campos acao
                    mysql_query("DELETE FROM Nodes_Campos_Acao WHERE CodigoNode=$codnovo");
                    mysql_query("INSERT INTO Nodes_Campos_Acao (CodigoNode, CodigoCampoAcao, Valor) VALUES ($codnovo, $codcampoacao, '$nomearqdest')");
                }
            }
        }
        $acao = 'novo';
    }
    else
    {
        // criação comum de itens
        
        $coditempai = $item->CodigoNode;
        $codsiteitem    = $item->CodigoSite;
        if ($coditemsel <= 0)
        {
            $proxordem  = mysql_fetch_row(mysql_query("SELECT max(Ordem) FROM Sites_Nodes WHERE CodigoSite=$codsiteitem AND CodigoParentNode=$coditempai"));
            $inf->Ordem = $proxordem[0]+1;
        }
        $inf->CodigoSite = $codsiteitem;
        $inf->CodigoParentNode = $coditempai;
        $inf->CodigoAcao = $objacao->CodigoAcaoFilha;
        $inf->SEO_Titulo = mysql_real_escape_string($seo_titulo);
        $inf->SEO_Descricao = mysql_real_escape_string($seo_descricao);
        $inf->SEO_Chaves = mysql_real_escape_string($seo_chave);

		$codnovo = $itens->SaveToDB($inf, $coditemsel);
        
        // salvando idiomas
        mysql_query("DELETE FROM Nodes_Nomes WHERE CodigoNode = $codnovo");
        mysql_query("DELETE FROM Nodes_Textos WHERE CodigoNode = $codnovo");
        for ($i=0;$i<count($idiomas->mItems);$i++)
        {
            $itens->SaveNomeIdioma($codnovo, $idiomas->mItems[$i]->CodigoIdioma, $nome[$idiomas->mItems[$i]->CodigoIdioma]);
            $itens->SaveTextoIdioma($codnovo, $idiomas->mItems[$i]->CodigoIdioma, $texto[$idiomas->mItems[$i]->CodigoIdioma]);
            //print $texto[$idiomas->mItems[$i]->CodigoIdioma].'<hr>';
        }
            
        // salvando campos acao
        mysql_query("DELETE FROM Nodes_Campos_Acao WHERE CodigoNode=$codnovo AND 
                        NOT EXISTS(SELECT AC.CodigoCampoAcao FROM Acoes_Campos AC WHERE AC.CodigoCampoAcao=Nodes_Campos_Acao.CodigoCampoAcao AND
                                            AC.CodigoTipoCampo IN (".campos_tipo_arquivo."))") or die(mysql_error());
        if (is_array($_POST['campoacao']))
        {
            foreach ($_POST['campoacao'] as $codcampoacao=>$valor)
            {
                if (is_array($valor))
                {
                    foreach ($valor as $ci=>$novovalor)
                        mysql_query("INSERT INTO Nodes_Campos_Acao (CodigoNode, CodigoCampoAcao, CodigoIdioma, Valor) VALUES ($codnovo, $codcampoacao, $ci, '$novovalor')");
                        
                }
                else
                    mysql_query("INSERT INTO Nodes_Campos_Acao (CodigoNode, CodigoCampoAcao, Valor) VALUES ($codnovo, $codcampoacao, '$valor')");
            }
        }
        if (is_array($_FILES['campoarq']))
        {
            foreach ($_FILES['campoarq']['name'] as $codcampoacao=>$objarquivo)
            {
                //print_r($_FILES['campoarq']);
                if ( strlen($_FILES['campoarq']['tmp_name'][$codcampoacao]) > 0 )
                {
                    //print $codcampoacao.'<br>';
                    // nome do arquivo: coditem.xxx
                    $extensao = explode('.',$_FILES['campoarq']['name'][$codcampoacao]);
                    $extensao = strtolower($extensao[count($extensao)-1]);
                    $nomearqdest = $codnovo.'_'.$codcampoacao.'.'.$extensao;
                    move_uploaded_file($_FILES['campoarq']['tmp_name'][$codcampoacao], 'site_files/'.$nomearqdest);

                    // auto-resize
                    $objcampo = $acoesfilha->GetCampoAcao($acaofilha, $codcampoacao);
                    if ($objcampo->CodigoTipoCampo == tipo_campo_arqimagem && strlen($objcampo->LarguraAlturaAutoCrop) > 0 )
                    {
                        include_once('classes/class.m2brimagem.php');
                        list($larg,$alt) = explode('|', $objcampo->LarguraAlturaAutoCrop);
                        $im = new m2brimagem('site_files/'.$nomearqdest);
                        $im->redimensiona($larg,$alt,'crop');
                        $im->grava('site_files/'.$nomearqdest, true, 100);
                    }


                    // salvando campos acao
                    mysql_query("DELETE FROM Nodes_Campos_Acao WHERE CodigoNode=$codnovo AND CodigoCampoAcao=$codcampoacao");
                    mysql_query("INSERT INTO Nodes_Campos_Acao (CodigoNode, CodigoCampoAcao, Valor) VALUES ($codnovo, $codcampoacao, '$nomearqdest')");
                }
            }
        }  
        
        if ($coditemsel <= 0)
        {
            // autofilhos
            $inf->CodigoParentNode = $codnovo;
                
            for ($i=0;$i<count($acaofilha->AutoFilhos); $i++)
            {
                $inf->CodigoAcao = $acaofilha->AutoFilhos[$i]->CodigoAcaoFilho;
                $inf->Ordem = $i+1;
                $codnovofilho = $itens->SaveToDB($inf, $coditemsel);
                for ($j=0;$j<count($idiomas->mItems);$j++)
                    $itens->SaveNomeIdioma($codnovofilho, $idiomas->mItems[$j]->CodigoIdioma, $acaofilha->AutoFilhos[$i]->Nome);
            }
        }
        
        // autoorder
        for ($i=0;$i<count($acaofilha->Campos);$i++)
            if ($acaofilha->Campos[$i]->OrdenadorItens == 1)
            {
                $itens->AutoReOrder($acaofilha->Campos[$i]->CodigoCampoAcao, $codsel);
                break;
                
            }
        
        $coditemsel = $codnovo;
        $acao = 'editar';
        $msg = 'Item gravado com sucesso!';
    }
} else if ($acao == 'delitem')
{
    $itens_galeria->DeleteFromDB($coditemdel);
    $acao = 'novo';
} 
else if ($acao == 'gravar_removendo_arquivo')
{
    $arqtodel = base64_decode($arqtodel);
    // vendo se ele existe
    if (file_exists('site_files/'.$arqtodel))
    {
        print $arqtodel;
        // removendo
        unlink('site_files/'.$arqtodel);   
    }
    $acao = 'editar';
} else if ($acao == 'gravar_nomes')
{
    if (is_array($nomeitem))
    {
        foreach ($nomeitem as $coditem=>$cods_idiomas)
        {
            mysql_query("DELETE FROM Nodes_Nomes WHERE CodigoNode=$coditem");
            foreach ($cods_idiomas as $ci=>$nome_item)
            {
                mysql_query("INSERT INTO Nodes_Nomes (CodigoNode, CodigoIdioma, Nome) VALUES ($coditem, $ci, '$nome_item')");
            }
        }
    }
} else if ($acao == 'moveup')
{
    $itens_galeria->MoveItemUp($coditemmove);
} else if ($acao == 'movedown')
{
    $itens_galeria->MoveItemDown($coditemmove);
} else if ($acao == 'traduzir_nome')
{
    $texto_origem = str_replace(' ', '%20', $nome[$trad_to_idioma]);
    $idioma_from = $idiomas->GetItem($trad_from_idioma);
    $idioma_to   = $idiomas->GetItem($trad_to_idioma);
    
    ini_set("allow_url_fopen", 1); //função habilitada 
    $ss = file_get_contents('http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q='.$texto_origem.'&langpair='.$idioma_from->Sigla.'|'.$idioma_to->Sigla);
    $ss = json_decode($ss);
    $rr = $ss->responseData;
    $nome[$trad_to_idioma] = $rr->translatedText;
} else if ($acao == 'traduzir_texto')
{
    $texto_origem = str_replace(' ', '%20', $texto[$trad_to_idioma]);
    $idioma_from = $idiomas->GetItem($trad_from_idioma);
    $idioma_to   = $idiomas->GetItem($trad_to_idioma);
    
    ini_set("allow_url_fopen", 1); //função habilitada 
    $ss = file_get_contents('http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q='.$texto_origem.'&langpair='.$idioma_from->Sigla.'|'.$idioma_to->Sigla);
    $ss = json_decode($ss);
    $rr = $ss->responseData;
    $texto[$trad_to_idioma] = $rr->translatedText;
}

$itens_galeria->LoadFromDB();

if ($acao == 'editar')
{
    $inf = $itens_galeria->GetItem($coditemsel);
    
    unset($campoacao);
    for ($i=0;$i<count($inf->CamposAcao);$i++)
        if ($inf->CamposAcao[$i]->CodigoIdioma > 0)
            $campoacao[$inf->CamposAcao[$i]->CodigoCampoAcao][$inf->CamposAcao[$i]->CodigoIdioma] = $inf->CamposAcao[$i]->Valor;
        else
            $campoacao[$inf->CamposAcao[$i]->CodigoCampoAcao] = $inf->CamposAcao[$i]->Valor;
    unset($nome, $texto);
    for ($i=0;$i<count($idiomas->mItems);$i++)
    {
        $nome[$idiomas->mItems[$i]->CodigoIdioma] = $inf->Idiomas[$idiomas->mItems[$i]->CodigoIdioma]->Nome;
        $texto[$idiomas->mItems[$i]->CodigoIdioma] = $inf->Idiomas[$idiomas->mItems[$i]->CodigoIdioma]->Texto;
    }
    
    $seo_titulo    = $inf->SEO_Titulo;
    $seo_descricao = $inf->SEO_Descricao;
    $seo_chave     = $inf->SEO_Chaves;
    
} 
    


if ($acao == 'novo')
    unset($nome, $texto, $campoacao, $codacao, $coditemsel);

    
    
function testeFiltros($filtros, $lista, $idx)
{
    if (is_array($filtros))
    {
        foreach ($filtros as $codcampo=>$filtro)
        {
           if ( strlen($filtro)>0 && strcmp($lista->GetCampoValueByCod($idx, $codcampo), $filtro) != 0)
           {
               return false;
           }
        }
        return true;
    }
    else
        return true;
    
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Gallery Items</title>
<link rel="stylesheet" type="text/css" href="visual.css">
<link rel="stylesheet" href="includes/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="includes/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="includes/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="popup/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="popup/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="popup/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" type="text/css" href="popup/jquery.fancybox-1.3.4.css" media="screen" />

<script>
$(document).ready(function()
{
    $('.fancybox_aviso').fancybox({
        height        : 380,
        width : 500,
        autoSize    : false,
        closeClick    : false,
        titlePosition: 'inside',
        topRatio: 0.1,
        type:'inline'
    });
    <?if ($msg > ''){?>
        $('#amsgaviso').trigger('click');
        setTimeout('$.fancybox.close()',1000);
    <?}?>
    $('#link_produtos').fancybox({type:'iframe'});
});

function onselrelacionados()
{
    $('body').css('overflow', 'hidden');
    window.scrollTo(0,-9999999);
    
    var lista = document.getElementsByName('chrelacionados[]');
    var lvalor = $('#campoRelacionados').val().split(',');
    for (var i=0;i<lista.length;i++)
        lista[i].checked = (lvalor.indexOf(lista[i].value) > -1);
    
    $('#divFundo').show();
    $('#divSeletor').show();
}                                
function oncancelar_relacionados()
{
    $('body').css('overflow', 'auto');
    $('#divFundo').hide();
    $('#divSeletor').hide();
}

function onconfirmar_relacionados()
{
    $('body').css('overflow', 'auto');
    var lista = document.getElementsByName('chrelacionados[]');
    var lvalor=[];
    var ldisplay=[];
    for (var i=0;i<lista.length;i++)
    {
        if (lista[i].checked)
        {
            lvalor.push(lista[i].value);
            ldisplay.push(lista[i].getAttribute('texto'));
        }
    }
    
    var sss= '';
    if (ldisplay.length > 0)
        sss += ' ';
    sss += '<a href="javascript: onselrelacionados()">[editar]</a>';

    $('#campoRelacionados').val(lvalor.toString());    
    $('#divRelacionados').html(ldisplay.toString()+sss);    
    
    $('#divFundo').hide();
    $('#divSeletor').hide();
}



function teste_extensao(lista, extensao)
{
    if(lista.length == 0)
        return true;
    for (var i=0;i<lista.length;i++)
    {
        if (lista[i].toLowerCase() == extensao.toLowerCase())
            return true;
    }
    return false;
}

function onshowarqs(obj, divobj, extensoes)
{
    var d = document.getElementById(divobj);
    d.innerHTML = '';
    var algumerro = false;
    for (var i=0;i<obj.files.length;i++)
    {
        pp = obj.files[i].name.split('.');
        
        extok = teste_extensao(extensoes, pp[pp.length-1]);
        
        if (obj.multiple)
            nomearq = (i+1)+ ". " + obj.files[i].name;
        else
            nomearq = "Selecionado: "+obj.files[i].name;
        
        if (extok)
            d.innerHTML = d.innerHTML + nomearq;
        else
        {
            d.innerHTML = d.innerHTML + "<font color='red'>" + nomearq + '*</font>';
            algumerro = true;
        }
            
        d.innerHTML = d.innerHTML + '<br>';
    }
    document.ff.btnGravar.disabled = algumerro;
    if (algumerro)
        d.innerHTML = d.innerHTML + '<br><font color=red size=1>*Remova os itens em vermelho';
}

function doremoverarq(idobj, idx)
{
    var ff = document.getElementById(idobj);
    ff.files[idx] = null;
}

function oncriartextos()
{
    qtde = parseInt(document.ff.qtde.value);
    dd = document.getElementById('pcampostexto');
    dd.innerHTML = '';
    
    var lb = "<?=$acaofilha->Campos[0]->CampoDisplay?> ";
    
    if (qtde > 0)
    {
        for (var i=0;i<qtde;i++)
        {
            dd.innerHTML = dd.innerHTML + lb+(i+1)+'<br><input type="text" name="campotexto[]" style="width:100%"><bR>';
        }
        dd.innerHTML = dd.innerHTML + '<br><br><input type="submit" value="Criar itens" onclick="return oncriar()">';
        
        return false;
    }
    else
        return false;
}

function oneditgaleria(cod)
{
    document.ff.codsel.value = cod;
    document.ff.coditemsel.value = '';

    // zerando nomes
    for (var i=0;i<document.ff.elements.length;i++)
        if ( document.ff.elements[i].name.substring(0,5) == 'nome[' )
            document.ff.elements[i].value = '';

    document.ff.submit();
}

function oneditar(cod)
{
    document.ff.acao.value = 'editar';
    document.ff.coditemsel.value = cod;
    document.ff.submit();
}


function oncriar()
{
    document.ff.enctype = "multipart/form-data";
    document.ff.acao.value = "criar";
    return true;
}

function ondeleteitem(cod)
{
    if (confirm('Remover o item selecionado? ESSA AÇÃO NÃO PODERÁ SER DESFEITA!'))
    {
        document.ff.acao.value = 'delitem';
        document.ff.coditemdel.value = cod;
        document.ff.submit();
    }
}
function onnovo()
{
    document.ff.acao.value = 'novo';
    return true;
}

function ongravarremovendo(nomearq)
{
    if (confirm('Are you sure?'))
    {
        document.ff.acao.value = 'gravar_removendo_arquivo';
        document.ff.arqtodel.value = nomearq;
        document.ff.submit();
    }
    
}

function ongravarnomes()
{
    document.ff.acao.value = 'gravar_nomes';
    return true;
}

function onmoveup(cod)
{
    document.ff.acao.value = 'moveup';
    document.ff.coditemmove.value = cod;
    document.ff.submit();
}

function onmovedown(cod)
{
    document.ff.acao.value = 'movedown';
    document.ff.coditemmove.value = cod;
    document.ff.submit();
}


function ontradnome(from_idioma, to_idioma)
{
    document.ff.acao.value = 'traduzir_nome';
    document.ff.trad_from_idioma.value = from_idioma;
    document.ff.trad_to_idioma.value   = to_idioma;
    document.ff.submit();
}

function ontradtexto(from_idioma, to_idioma)
{
    document.ff.acao.value = 'traduzir_texto';
    document.ff.trad_from_idioma.value = from_idioma;
    document.ff.trad_to_idioma.value   = to_idioma;
    document.ff.submit();
}
function dosetoutrosnomes(ednome)
{
    for (var i=0;i<document.ff.elements.length;i++)
        if (document.ff.elements[i].name.substr(0,4) == 'nome' &&
               document.ff.elements[i].name != ednome.name && 
               document.ff.elements[i].value.length == 0)
        {
            document.ff.elements[i].value = ednome.value;
        }
}

function dosetoutrostextos(edtexto)
{
    for (var i=0;i<document.ff.elements.length;i++)
        if (document.ff.elements[i].name.substr(0,5) == 'texto' &&
               document.ff.elements[i].name != edtexto.name && 
               document.ff.elements[i].value.length == 0)
        {
            document.ff.elements[i].value = edtexto.value;
        }
}


function onbackestrutura()
{
    document.ff.action = "index2.php?acessar=construir.php";
    document.ff.acao.value = 'editar';
    document.ff.submit();
}
function doshowHide(id)
{
    var dd = document.getElementById(id);
    if (dd.style.display == 'block')
        dd.style.display = 'none';  
    else
        dd.style.display = 'block';  
    
}
CKEDITOR.config.removePlugins = 'scayt';
CKEDITOR.config.disableNativeSpellChecker = true;
CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
CKEDITOR.config.extraPlugins = 'parag';


</script>
</head>
<body>
<form name="ff" method="post">
<input type="hidden" name="acao">
<input type="hidden" name="coditemdel">
<input type="hidden" name="coditemmove">
<input type="hidden" name="codsel" value="<?=$codsel?>">
<input type="hidden" name="coditemsel" value="<?=$coditemsel?>">
<input type="hidden" name="codsite" value="<?=$codsite?>">
<input type="hidden" name="arqtodel">

<input type="hidden" name="trad_from_idioma">
<input type="hidden" name="trad_to_idioma">
    

<table width="100%">
    <tr>
        <td class="cad_title"><?=($objLogin->iTipo == tipo_login_cliente)?'Edit Websites':'Edit Websites'?></td>
    </tr>
    <tr>
        <td class="common">

        > <a href="javascript:onbackestrutura()">Website Structure</a>&nbsp;
        
        <?
        $codpai = $item->CodigoParentNode;
        while ($codpai > 0)
        {
            $itempai = $itens_galeria->GetItem($codpai);
            $codpai = $itempai->CodigoParentNode;
            if ($itempai->CodigoTipoAcao == tipo_acao_galeria)
                print '> <a href="javascript:oneditgaleria('.$itempai->CodigoNode.')">'.$itempai->Idiomas[$codidioma]->Nome.'</a>';
        }
        ?>
        
        > <?=$item->Idiomas[1]->Nome?><br><br>
        
        <span class="titulo">Site: <?=$site->Nome?></span>
        <br><br>
        <?//<span class="common">Nome do item: <b><?=$item->Idiomas[1]->Nome</b> ($objacao->Nome)<br><br>?>
        
        <table width="100%">
            <tr valign="top" >
             <td width="55%" valign="top" >
                
                <fieldset>
                    <legend class="group_label">Gallery Items</legend>
                    <?
                    for ($i=0;$i<count($acaofilha->Campos);$i++){
                        if ($acaofilha->Campos[$i]->IsFiltravel == 1)
                        {
                        ?>
                        <select name="filtroItens[<?=$acaofilha->Campos[$i]->CodigoCampoAcao?>]" onchange="document.ff.submit()"><option value=""><?=$acaofilha->Campos[$i]->CampoDisplay?></option>
                            <?
                            $qr = mysql_query("SELECT DISTINCT(Valor) FROM nodes_campos_acao WHERE CodigoCampoAcao={$acaofilha->Campos[$i]->CodigoCampoAcao} ORDER BY 1");
                            while (list($dd) = mysql_fetch_row($qr))
                                print '<option value="'.$dd.'" '.($filtroItens[$acaofilha->Campos[$i]->CodigoCampoAcao] == $dd?'selected':'').'>'.$dd.'</option>';
                            ?>
                        </select>
                        <?
                        }
                    }
                    ?>
                    
                    <div align="right"><select name="codidioma" onchange="document.ff.submit()"><?$idiomas->PrintCombo($codidioma);?></select></div>
                    
                    <?
                    $lista_galeria = $itens_galeria->GetItensDoPai($codsel);
                    
                    if(count($lista_galeria) == 0)
                    {
                    ?>
                        <div class="aviso_light" align="center">(nenhum item existente)</div>
                    <?}
                    else
                    {
                    ?>
                        <table width="100%">
                        <?
                        if ($objacao->CodigoAcao == acao_galeria_fotos || $objacao->IsGaleriaFotos == 1)
                        {
                            $p=1;
                          for ($i=0;$i<count($itens_galeria->mItems);$i++)
                            if ($itens_galeria->mItems[$i]->CodigoParentNode == $codsel && testeFiltros($filtroItens, $itens_galeria, $i) )
                            {
                                for ($j=0;$j<count($itens_galeria->mItems[$i]->CamposAcao);$j++)
                                    if ($itens_galeria->mItems[$i]->CamposAcao[$j]->CodigoTipoCampo==5)
                                        break;

                                $nomearqitem = $itens_galeria->mItems[$i]->CamposAcao[$j]->Valor;
                            ?>
                                <tr>
                                <td width="1%"><a href="site_files/<?=$nomearqitem?>" target="_blank"><img src="site_files/<?=$nomearqitem?>?rand=<?=rand(0,9999)?>" height=40 width=50 border="0"></td>
                                <td width="99%" class="common">
                                    <a href="javascript:oneditar(<?=$itens_galeria->mItems[$i]->CodigoNode?>);"><b><?=(strlen($itens_galeria->mItems[$i]->Idiomas[$codidioma]->Nome)>0?$itens_galeria->mItems[$i]->Idiomas[$codidioma]->Nome:'(no-name)')?></b></a>
                                    <br>
                                        <?if($i>0){?><a href="javascript:onmoveup(<?=$itens_galeria->mItems[$i]->CodigoNode?>)"><img src="images/up.jpg" height=13  border="0"></a><?}?>
                                        <?if($i<count($itens_galeria->mItems)-1){?><a href="javascript:onmovedown(<?=$itens_galeria->mItems[$i]->CodigoNode?>)"><img src="images/down.jpg" border="0" height=13 ></a><?}?>
                                        <a href="javascript: oneditar(<?=$itens_galeria->mItems[$i]->CodigoNode?>);"><img src="images/edit.gif" height=13 border=0></a>
                                        <a href="javascript:ondeleteitem(<?=$itens_galeria->mItems[$i]->CodigoNode?>)"><img src="images/delete.jpg" height=13  border="0"></a>
                                                
                                    </td>
                                </tr>
                            <?
                            }
                        }
                        else
                        {
                           // geral
                           $p=1;
                           for ($i=0;$i<count($itens_galeria->mItems);$i++)
                              if ($itens_galeria->mItems[$i]->CodigoParentNode == $codsel && testeFiltros($filtroItens, $itens_galeria, $i) )
                              {
                                $filhos = $itens_galeria->GetItensDoPai($itens_galeria->mItems[$i]->CodigoNode);
                            ?>
                                <tr valign="top">
                                <td width="1%" nowrap align="right" class="common"><b><?=($p++)?>.</b></td>
                                <td width="99%" class="common">
                                    <a href="javascript: oneditar(<?=$itens_galeria->mItems[$i]->CodigoNode?>);"><b><?=(strlen($itens_galeria->mItems[$i]->Idiomas[$codidioma]->Nome)>0?$itens_galeria->mItems[$i]->Idiomas[$codidioma]->Nome:'(no-name)')?></b></a>
                                    
                                        <?if($i>0){?><a href="javascript:onmoveup(<?=$itens_galeria->mItems[$i]->CodigoNode?>)"><img src="images/up.jpg" height=13  border="0"></a><?}?>
                                        <?if($i<count($itens_galeria->mItems)-1){?><a href="javascript:onmovedown(<?=$itens_galeria->mItems[$i]->CodigoNode?>)"><img src="images/down.jpg" border="0" height=13 ></a><?}?>
                                        <a href="javascript: oneditar(<?=$itens_galeria->mItems[$i]->CodigoNode?>);"><img src="images/edit.gif" height=13 border=0></a>
                                        <a href="javascript:ondeleteitem(<?=$itens_galeria->mItems[$i]->CodigoNode?>)"><img src="images/delete.jpg" height=13  border="0"></a>
                                        
                                        <?if(count($filhos)>0)
                                        {
                                            for ($j=0;$j<count($filhos);$j++)
                                            {
                                            ?>
                                                <br><a href="javascript: oneditgaleria(<?=$filhos[$j]->CodigoNode?>)">> <?=$filhos[$j]->Idiomas[$codidioma]->Nome?>: <?=$itens_galeria->CountItensDoPai($filhos[$j]->CodigoNode)?></a>
                                            <?
                                            }
                                        }?>
                                    </td>
                                </tr>
                            <?
                            }
                        }
                    ?>
                    </table>
                    <?
                    }?>
                </fieldset>
                
            </td>
            <td valign="top" width="45%">
                <fieldset>
                    <legend class="group_label"><?=($coditemsel>0?'Edit Item':'Add a new Item')?></legend>

                        <?if(!$primeiro_nome){?>    
                            <span class="common">
                            <?if (strlen($acaofilha->Dica) > 0){?><span class="aviso_light"><?=$acaofilha->Dica?></span><br><?}?>
                            <?if($acaofilha->QtdeCamposSemIdioma > 0){?><br><div style="padding:5px; border:1px solid #AAAAAA;line-height:150%" align="left"><?=render_campos($campoacao, $itens_galeria, $acaofilha, $objacao, $coditemsel, false);?></div><?}?>
                            <br>        
                        <?}?>
                    
                        <?
                        if ($objacao->CodigoAcao != acao_galeria_fotos || $coditemsel > 0)
                        {
                            $dica_texto_acao = $acaofilha->LabelCampoTexto;
                            $dica_texto_acao = strlen($dica_texto_acao)>0?'<br><span class="aviso_light">'.$dica_texto_acao.'</span>':'';
                            for ($i=0;$i<count($idiomas->mItems);$i++)
                            {
                                if ($i>0) print '<br>';
                                ?>
                                <div style="padding:5px; border:1px solid #AAAAAA">
                                    <div class="common" onclick="doshowHide('divIdioma<?=$i?>')" style="cursor:pointer;background-color:#DDDDDD; border:1px solid #AAAAAA;margin-bottom:5px; padding:5px" align="left"><b><?=$idiomas->mItems[$i]->Idioma?></b></div>
                                        <span id="divIdioma<?=$i?>" class="common" style="display:<?=$primeiro_nome?($i==0?'block':'none'):'block'?>"><?=(strlen($acaofilha->LabelNomeItem)>0?$acaofilha->LabelNomeItem:'Item Name')?> (In <?=$idiomas->mItems[$i]->Idioma?>)<br>
                                        <input type="text" name="nome[<?=$idiomas->mItems[$i]->CodigoIdioma?>]" style="width:100%" onblur="dosetoutrosnomes(this)" value="<?=$nome[$idiomas->mItems[$i]->CodigoIdioma]?>"><br>
                                        <?
                                        if ( (count($acaofilha->Campos)-$acaofilha->QtdeCamposSemIdioma) > 0 )
                                        {
                                        ?>
                                            <div style="padding:5px; border:1px solid #AAAAAA" align="left">
                                            <? 
                                                print render_campos($campoacao, $itens_galeria, $acaofilha, $objacao, $coditemsel, true, $idiomas->mItems[$i]->CodigoIdioma, $idiomas->mItems[$i]->Idioma);
                                            ?>
                                            </div>
                                        <?
                                        }
                                        if ($acaofilha->UtilizaTexto == 1)
                                        {
                                        ?>
                                            
                                        Texto (em <?=$idiomas->mItems[$i]->Idioma?>)<?=$dica_texto_acao?> <br>
                                            <textarea <?if($acaofilha->EditorHTML==1){?>class="ckeditor"<?}?> name="texto[<?=$idiomas->mItems[$i]->CodigoIdioma?>]" style="width:100%" rows=5 onblur="dosetoutrostextos(this);"><?=$texto[$idiomas->mItems[$i]->CodigoIdioma]?></textarea>
                                            
                                        <?}?>
                                        </span>
                                    </div>           
                                </div>
                            <?}
                        }?>
                            
                        
                        <br>
                        <span class="common"><font size="1"><label id="parquivos<?=$i?>"></label></font></span>
                        
                        <?if($primeiro_nome){?>    
                            <span class="common">
                            <?if (strlen($acaofilha->Dica) > 0){?><span class="aviso_light"><?=$acaofilha->Dica?></span><br><?}?>
                            <?if($acaofilha->QtdeCamposSemIdioma > 0){?><br><div style="padding:5px; border:1px solid #AAAAAA;line-height:150%" align="left"><?=render_campos($campoacao, $itens_galeria, $acaofilha, $objacao, $coditemsel, false);?></div><?}?>
                            <br>        
                        <?}?>
                        
                        <br /><br />
                        <fieldset>
                            <legend class="group_label">SEO</legend> 
							Page Title (Leave it blank to use default title)<br />
                            <input type="text" name="seo_titulo" value="<?=$seo_titulo?>" style="width:100%;margin-bottom:5px">
                            <br />
                            Description<br />
                            <textarea name="seo_descricao" style="width:100%;height:50px;resize:none"><?=$seo_descricao?></textarea>
                            <br />
                            KeyWords (Comma separated)<br />
                            <input type="text" name="seo_chave" value="<?=$seo_chave?>" style="width:100%;margin-bottom:5px">
                        </fieldset>
                        
                        <br><br>
                        <center><input name="btnGravar" type="submit" value="<?if($coditemsel>0){?>Save Changes<?}else{?>Create Item<?}?>"  onclick="return oncriar()">
                        <?if($coditemsel>0){?>
                            <input type="submit" value="New Item" onclick="return onnovo()">
                            
                        <?}?>
                        </center>
                        
                    </fieldset>    
                </td>
            </tr>
            
        </table>
        
    </td>
    </tr>
</table>
</form>  
<a href="#msgaviso-div" class="fancybox_aviso" id="amsgaviso" style="display:none">&nnsp;</a>
<div style="display: none">
    <div id="msgaviso-div" class="common"><?=$msg?></div>
</div>

<div id="divFundo" style="display:none;position:absolute;left:0px;top:0px;width:100%;height:100%;background:rgba(0,0,0,0.7)"></div>
<div id="divSeletor" style="display:none;position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-250px;width:500px;height:500px;box-shadow:2px 2px 6px #000;border:1px solid #ddd;background-color:white;overflow:auto">
    <div style="padding:10px;line-height:140%" class="common">
        <font size=3><B>Selecione a lista de produtos relacionados a este:</b></font><br /><br />
        
        <?
        $filtro_rel = 'N.CodigoAcao=26';
        if ($coditemsel > 0)
            $filtro_rel .= " AND N.CodigoNode<>$coditemsel";
        $qr = mysql_query("SELECT I.Nome, I.CodigoNode FROM Nodes_Nomes I INNER JOIN Sites_Nodes N ON I.CodigoNode=N.CodigoNode WHERE I.CodigoIdioma=1 AND $filtro_rel ORDER BY 1") or die(mysql_error());
        $i=0;
        while ($obj = mysql_fetch_object($qr))
        {?>
            <input type="checkbox" id="chrelacionados<?=$i?>" name="chrelacionados[]" texto="<?=$obj->Nome?>" value="<?=$obj->CodigoNode?>"><label for="chrelacionados<?=$i?>"><?=$obj->Nome?></label><br />
        <?$i++;}?>
        
        <br />
        <center>
            <input type="button" value="Cancelar" onclick="oncancelar_relacionados()">&nbsp;
            <input type="button" value="Confirmar" onclick="onconfirmar_relacionados()">
        </center>
        
    </div>
</div>

</body>
</html>

<?
function render_campos($campoacao, $itens_galeria, $acaofilha, $objacao, $coditemsel, $varia_idioma, $codidioma='', $nomeidioma='')
{
    $acoes = $GLOBALS['acoes'];
    $html = $stridioma = '';
    if ($codidioma)
        $stridioma = "[$codidioma]";
    $nomeidioma = strlen($nomeidioma)>0?" (em $nomeidioma)":'';
    for ($i=0;$i<count($acaofilha->Campos);$i++)
        if ( (!$varia_idioma && $acaofilha->Campos[$i]->VariaIdioma == 0) || ($varia_idioma && $acaofilha->Campos[$i]->VariaIdioma == 1) )
        {
            $lista_extensoes = '';
            // montando lista de extensoes (se for o caso - somente para campos tipo ARQUIVO)
            if (strlen($acaofilha->Campos[$i]->Extensoes) > 0)
            {
                $extensoes = explode(';',$acaofilha->Campos[$i]->Extensoes);
                for ($x=0;$x<count($extensoes);$x++)
                    $extensoes[$x] = "'".$extensoes[$x]."'";
                if (is_array($extensoes))
                    $lista_extensoes = implode(',',$extensoes);
            }
            // pegando valor default se for o caso
            if ($coditemsel <= 0) // somente para novos itens
                $campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao] = $acaofilha->Campos[$i]->ValorDefault;
            
            if ($acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_checkbox)
            {
                $html .= '<input type="checkbox" style="margin-left:0px" name="campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']" '.($campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao]=='on'?'checked':'').'>'.$acaofilha->Campos[$i]->CampoDisplay.'<br>';
            }
            else
            {
                if ($acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_arqimagem && ($objacao->CodigoAcao == acao_galeria_fotos || $objacao->IsGaleriaFotos == 1) && $coditemsel > 0)
                {
                    $valor = $campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao];
                    if ( strlen($valor)>0 && file_exists('site_files/'.$valor) )
                    {
                        $html .= '<center>
                                    <a href="site_files/'.$valor.'" target="_blank">
                                        <img src="site_files/'.$valor.'?rand='.rand(0,9999).'" width=150 border=0></a>
                                        <br><a href="javascript:ongravarremovendo(\''.base64_encode($valor).'\')">Remove Image</a></center>';
                    }
                    else
                    {
                        $html .= $acaofilha->Campos[$i]->CampoDisplay.$nomeidioma.'<br>';
                        $html .= '<input type="file" name="campoarq['.$acaofilha->Campos[$i]->CodigoCampoAcao.']" 
                                    onchange="onshowarqs(this, \'parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'\', new Array('.$lista_extensoes.'));">
                                        <font size="1"><div id="parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'" class="common"></div></font>';                                                    
                    }
                }
                else
                {
                    $html .= $acaofilha->Campos[$i]->CampoDisplay.$nomeidioma;
                    if ($acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_arqimagem && ($objacao->CodigoAcao == acao_galeria_fotos || $objacao->IsGaleriaFotos == 1) )
                    {
                        if ($coditemsel <= 0)
                        {
                            $html .= ' <font size=1 color="#999999">seleção múltipla</font><br>';
                            $html .= '<input type="file" multiple id="campoarq_fotos" name="campoarq['.$acaofilha->Campos[$i]->CodigoCampoAcao.'][]" 
                                            onchange="onshowarqs(this, \'parquivos\', new Array('.$lista_extensoes.'));">
                                            <br><font size="1"><div id="parquivos" class="common"></div></font>';
                        }
                    }
                    else
                    {
                        $html .= '<br>';
                        if (count($acaofilha->Campos[$i]->ListaValores) > 0)
                        {
                            $html .= '<select name="campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']" style="width:100%">
                                        '.$acoes->PrintComboCampo($acaofilha->Campos[$i], $campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao], true).'</select>';
                        }
                        else if ($acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_multilinha)
                        {
                            if (is_array($campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao]))
                                $valor = $campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao][$codidioma];
                            else
                                $valor = $campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao];
                            $classeeditor='';
                            if($acaofilha->Campos[$i]->EditorHTML==1)
                                $classeeditor = 'class="ckeditor"';
                            $html .= '<textarea style="width:100%" '.$classeeditor.' rows=4 name="campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']'.$stridioma.'">'.$valor.'</textarea>';
                        }
                        else if ($acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_arqmusica ||
                                 $acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_arqimagem ||
                                 $acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_arqgeral )
                        {
                            if ($coditemsel <= 0)
                            {
                                $html .= '<input type="file" name="campoarq['.$acaofilha->Campos[$i]->CodigoCampoAcao.']" 
                                                onchange="onshowarqs(this, \'parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'\', new Array('.$lista_extensoes.'));">
                                                <font size="1"><div id="parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'" class="common"></div></font>';                                                    
                            }
                            else
                            {
                                if ($acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_arqimagem)
                                {
                                    if (strlen($campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao])>0 && file_exists('site_files/'.$campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao]))
                                    {
                                        $html .= '<center>
                                                    <div style="border:1px solid #AAAAAA;margin:5px; padding:5px">
                                                        <a href="site_files/'.$campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao].'" target="_blank"><img src="site_files/'.$campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao].'?r='.rand(0,9999).'" border="0" width=150></a><br>
                                                            <center><a href="javascript:ongravarremovendo(\''.base64_encode($campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao]).'\')">Remove Image</a></center>
                                                    </div></center>'; 
                                    }
                                    else
                                    {
                                        $html .= '<input type="file" name="campoarq['.$acaofilha->Campos[$i]->CodigoCampoAcao.']" 
                                                    onchange="onshowarqs(this, \'parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'\', new Array('.$lista_extensoes.'));">
                                                        <font size="1"><div id="parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'" class="common"></div></font>';                                                    
                                    }
                                }
                                else
                                {   
                                    if (strlen($campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao])>0 && 
                                            file_exists('site_files/'.$campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao]))
                                    {
                                        $html .= '<a href="site_files/'.$campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao].'" target="_blank">Open File</a>
                                                        | <a href="javascript:ongravarremovendo(\''.base64_encode($campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao]).'\')">Remove File</a>';
                                    }
                                    else
                                    {
                                        $html .= '<input type="file" name="campoarq['.$acaofilha->Campos[$i]->CodigoCampoAcao.']" 
                                                        onchange="onshowarqs(this, \'parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'\', new Array('.$lista_extensoes.'));">
                                                            <font size="1"><div id="parquivos'.$acaofilha->Campos[$i]->CodigoCampoAcao.'" class="common"></div></font>';
                                    }
                                    $html .= '<br>';
                                }
                            }
                        }
                        else 
                        {
                            if (is_array($campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao]))
                                $valor = $campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao][$codidioma];
                            else
                                $valor = $campoacao[$acaofilha->Campos[$i]->CodigoCampoAcao];
                            if ($acaofilha->Campos[$i]->CodigoTipoCampo == tipo_campo_data) 
                            {
                                $valor = strlen($valor)==0?date('d/m/Y'):$valor;
                                $html .= '<input type="text" size=10 maxlength=10 name="campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']'.$stridioma.'" id="campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']'.$stridioma.'" value="'.$valor.'">';
                                $html .= '<input name="button" type="button" class="formulario" onClick="displayCalendar(document.getElementById(\'campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']'.$stridioma.'\'),\'dd/mm/yyyy\',this)" value="...">';
                            }
                            else
                            {
                                if ($acaofilha->Campos[$i]->CodigoCampoAcao == 148)
                                {
                                    $valor_display = $valor;
                                    if (strlen($valor_display) > 0)
                                    {
                                        $filtro_rel = 'N.CodigoAcao=26';
                                        $qr = mysql_query("SELECT I.Nome, I.CodigoNode FROM Nodes_Nomes I INNER JOIN Sites_Nodes N ON I.CodigoNode=N.CodigoNode 
                                                                    WHERE I.CodigoIdioma=1 AND I.CodigoNode IN ($valor_display) AND $filtro_rel ORDER BY 1") or die(mysql_error());
                                        $valor_display = '';
                                        while ($obj=mysql_fetch_object($qr))
                                        {
                                            if (strlen($valor_display) > 0)
                                                $valor_display .= ',';
                                            $valor_display .= $obj->Nome;
                                        }
                                        $valor_display .= '&nbsp;';
                                    }
                                    $valor_display .= '<a href="javascript: onselrelacionados()">[editar]</a>';
                                    $html .= '<div id="divRelacionados" style="padding:3px;background-color:#EEE;border:1px solid #DDD;font-family:Arial;font-size:12px;min-height:17px">'.$valor_display.'</div>';
                                    $html .= '<input type="hidden" id="campoRelacionados" name="campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']'.$stridioma.'" style="width:100%" value="'.$valor.'">';
                                }
                                else
                                    $html .= '<input type="text" name="campoacao['.$acaofilha->Campos[$i]->CodigoCampoAcao.']'.$stridioma.'" style="width:100%" value="'.$valor.'">';
                            }
                        }
                    }
                    $html .= '<br>';
                }
            }
        }
    return $html;
}
?>
