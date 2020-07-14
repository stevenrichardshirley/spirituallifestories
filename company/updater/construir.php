<?

include_once('header_classes.php');

require 'KoolTreeView/kooltreeview.php';



$bArvoreVelha = false;

$sites = new CSites();

$sites->iCodUsuario = $objLogin->iCodigo;

if ($objLogin->iTipo == tipo_login_cliente)

{

    $sites->iCodCliente = $objLogin->iCodCliente;    

    $sites->bSomenteAtivos = true;

}



$sites->LoadFromDB();


if ($codsite > 0)

{

    $idiomas = new CIdiomas();

    $idiomas->iCodSite = $codsite;

    $idiomas->LoadFromDB();

    $codidioma = $codidioma>0?$codidioma:$idiomas->mItems[0]->CodigoIdioma;



    $site =$sites->GetItem($codsite);



    $acoes = new CAcoes();

    $acoes->szOrderBy = 'A.Nome';

    $acoes->iCodClienteOrNull = $site->CodigoCliente;

    $acoes->LoadFromDB();

    $acoes->RemoverNulosSeHouverEspecificos();



    

    // teste de tentativa de acessar o site de outro cliente/usuario

    if ($objLogin->iTipo != tipo_login_master && $objLogin->iCodigo != $site->CodigoUsuario)

    {

        header('location: index2.php');

        exit;

    }

    

    if ($codsel > 0)

    {

        // teste para ver se este item selecionado PODE ser selecionado por este usuario

        $codusuarionode = mysql_fetch_row(mysql_query("SELECT S.CodigoUsuario FROM Sites_Nodes N INNER JOIN Usuarios_Sites S ON N.CodigoSite=S.CodigoSite WHERE N.CodigoNode=$codsel"));

        if ($objLogin->iTipo != tipo_login_master && $objLogin->iCodigo != $codusuarionode[0])

        {

            header('location: index2.php');

            exit;

        }

    }

    

    $nomesite = $site->Nome;

    

    $itens = new CItensSite();





    if ($acao == 'autofilho')

    {

        // criando auto filho - pegando prox ordem no pai

        unset($inf);

        $proxordem = mysql_fetch_row(mysql_query("SELECT max(Ordem) FROM Sites_Nodes WHERE CodigoSite=$codsite AND CodigoParentNode=$codpaiautofilho"));

        $inf->Ordem = $proxordem[0]+1;

        $inf->IsAtivo = 1;

        $inf->CodigoSite = $codsite;

        $inf->Nome = mysql_real_escape_string($nomeautofilho);

        $inf->CodigoAcao = 10;

        $inf->IsUserNode = 1;

        $inf->IsUserEditable = 1;

        $inf->CodigoParentNode = $codpaiautofilho;

        $codnovo = $itens->SaveToDB($inf);

        for ($i=0;$i<count($idiomas->mItems);$i++)

            $itens->SaveNomeIdioma($codnovo, $idiomas->mItems[$i]->CodigoIdioma, mysql_real_escape_string($nomeautofilho));

    }

    



    $itens->bLoadInativos = true;

    $itens->iCodSite = $codsite;

    

    if ($acao == 'gravar')

    {

        // gravando item

        $inf->Nome = $nome;

        $inf->Texto = $texto;

        $inf->CodigoSite = $codsite;

        $inf->SEO_Titulo = mysql_real_escape_string($seo_titulo);

        $inf->SEO_Descricao = mysql_real_escape_string($seo_descricao);

        $inf->SEO_Chaves = mysql_real_escape_string($seo_chave);



        

        if ($codacao > 0)

            $inf->CodigoAcao = $codacao;

        if ($coditempai > 0)

            $inf->CodigoParentNode= $coditempai;

            

        if ($codsel <= 0)

        {

            $proxordem = mysql_fetch_row(mysql_query("SELECT max(Ordem) FROM Sites_Nodes WHERE CodigoSite=$codsite AND CodigoParentNode=$coditempai"));

            $inf->Ordem = $proxordem[0]+1;

        }

        $inf->IsAtivo = ($cbativo=='on'?1:0);

        if ($site->IsEstruturaFixa==0)

            $inf->IsUserEditable = ($cbuseredit=='on'?1:0);

        $coditem = $itens->SaveToDB($inf, $codsel);

        // salvando idiomas

        mysql_query("DELETE FROM Nodes_Nomes WHERE CodigoNode = $coditem");

        mysql_query("DELETE FROM Nodes_Textos WHERE CodigoNode = $coditem");

        for ($i=0;$i<count($idiomas->mItems);$i++)

        {

            $itens->SaveNomeIdioma($coditem, $idiomas->mItems[$i]->CodigoIdioma, $nome[$idiomas->mItems[$i]->CodigoIdioma]);

            $itens->SaveTextoIdioma($coditem, $idiomas->mItems[$i]->CodigoIdioma, $texto[$idiomas->mItems[$i]->CodigoIdioma]);

        }

        

        // salvando campos acao

        mysql_query("DELETE FROM Nodes_Campos_Acao WHERE CodigoNode=$coditem AND 

                        NOT EXISTS(SELECT AC.CodigoCampoAcao FROM Acoes_Campos AC WHERE AC.CodigoCampoAcao=Nodes_Campos_Acao.CodigoCampoAcao AND

                                            AC.CodigoTipoCampo IN (".campos_tipo_arquivo."))") or die(mysql_error());

        

        if (is_array($campoacao))

        {

            foreach ($campoacao as $codcampoacao=>$valor)

                mysql_query("INSERT INTO Nodes_Campos_Acao (CodigoNode, CodigoCampoAcao, Valor) VALUES ($coditem, $codcampoacao, '$valor')");

        }

        

        if (is_array($_FILES['arqacao']))

        {
			
            foreach ($_FILES['arqacao']['name'] as $codcampoacao=>$objarquivo)

                if ( strlen($_FILES['arqacao']['tmp_name'][$codcampoacao]) > 0 )

                {

                    // nome do arquivo: coditem.xxx

                    $extensao = explode('.',$_FILES['arqacao']['name'][$codcampoacao]);

                    $extensao = strtolower($extensao[count($extensao)-1]);

                    $nomearqdest = $coditem.'_'.$codcampoacao.'.'.$extensao;

                    move_uploaded_file($_FILES['arqacao']['tmp_name'][$codcampoacao], 'site_files/'.$nomearqdest);

                    // salvando campos acao

                    mysql_query("DELETE FROM Nodes_Campos_Acao WHERE CodigoNode=$coditem AND CodigoCampoAcao=$codcampoacao");

                    mysql_query("INSERT INTO Nodes_Campos_Acao (CodigoNode, CodigoCampoAcao, Valor) VALUES ($coditem, $codcampoacao, '$nomearqdest')");

                }

        }  

        $msg = 'Item gravado com sucesso!';

        $codsel = $coditem;

        $acao = 'editar';

        

    } else if ($acao == 'remover')

    {

        $itens->DeleteFromDB($codsel);

        $acao = 'novo';

    } else if ($acao == 'moveup')

    {

        $itens->MoveItemUp($codsel);

    } else if ($acao == 'movedown')

    {

        $itens->MoveItemDown($codsel);

    } 

    else if ($acao == 'gravar_removendo_arquivo')

    {

        $arqtodel = base64_decode($arqtodel);

        // vendo se ele existe

        if (file_exists('site_files/'.$arqtodel))

        {

            // removendo

            unlink('site_files/'.$arqtodel);   

        }

        $acao = 'editar';

    }

    

    $itens->LoadFromDB();

      

    if ($acao == 'traduzir_nome')

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

    } else if ($acao == 'editar')

    {

        $inf = $itens->GetItem($codsel);

        $coditempai = $inf->CodigoParentNode;

        $cbativo = $inf->IsAtivo==1?'on':'';

        $cbuseredit =  $inf->IsUserEditable==1?'on':'';



        $isusernode = ($inf->IsUserNode==1);



        $seo_titulo    = $inf->SEO_Titulo;

        $seo_descricao = $inf->SEO_Descricao;

        $seo_chave     = $inf->SEO_Chaves;



        



        $codacao = $inf->CodigoAcao;



        unset($campoacao);

        for ($i=0;$i<count($inf->CamposAcao);$i++)

            $campoacao[$inf->CamposAcao[$i]->CodigoCampoAcao] = $inf->CamposAcao[$i]->Valor;

        unset($nome, $texto);

        for ($i=0;$i<count($idiomas->mItems);$i++)

        {

            $nome[$idiomas->mItems[$i]->CodigoIdioma] = $inf->Idiomas[$idiomas->mItems[$i]->CodigoIdioma]->Nome;

            $texto[$idiomas->mItems[$i]->CodigoIdioma] = $inf->Idiomas[$idiomas->mItems[$i]->CodigoIdioma]->Texto;

        }

    } 

    

    if ($acao == 'novo')

        unset($nome, $codacao, $texto, $codacao, $codsel);





    if ($codacao > 0)

        $objacao = $acoes->GetItem($codacao);

        

}



// montando objeto da arvore

$arvore = new KoolTreeView("arvore");

$arvore->scriptFolder = 'KoolTreeView';

$arvore->imageFolder="TreeImages";

$arvore->styleFolder="default";

$arvore->showLines = true;

$arvore->DragAndDropEnable=false;

$arvore->selectEnable = true;

    



function alocar_item($arvore, $codparent, $colecao, $nivel, $codidioma, $nomenopai, $movedelete=true)

{

    for ($i=0;$i<count($colecao->mItems);$i++)

        if ($colecao->mItems[$i]->CodigoParentNode == $codparent && 

            $colecao->mItems[$i]->CodigoTipoAcao != tipo_acao_itemgaleria)

        {

            $s = $colecao->mItems[$i]->Nome;

            if ($nivel > 0)

            {

                $s = $colecao->mItems[$i]->Idiomas[$codidioma]->Nome;

                $s = strlen(trim($s))==0?'(sem nome)':$s;

                $cordes = $colecao->mItems[$i]->IsAtivo==0?'style="color:#999"':'';

            

                $lfilho = '';

                if ($colecao->mItems[$i]->IsUserEditable == 1)

                    $lfilho = '<a href="javascript: oncriarfilho('.$colecao->mItems[$i]->CodigoNode.')" class="link_mini_criar">+ filho</a>';



                $s = '<a href="javascript:oneditar('.$colecao->mItems[$i]->CodigoNode.');" '.$cordes.'>'.$s.'</a>&nbsp;'.$lfilho;

                //if ($colecao->mItems[$i]->CodigoAcao != acao_sem_acao)

                  //  $s .= '<span class="aviso_light">'.$colecao->mItems[$i]->Acao.'</span>&nbsp;';



                if ($movedelete)

                {

                    if ($colecao->mItems[$i]->Ordem > 1)

                        $s .= '<a href="javascript: onmoveup('.$colecao->mItems[$i]->CodigoNode.');"><img src="images/up.gif" border=0 height=13></a>';

                    

                    if ($colecao->mItems[$i]->Ordem < $colecao->GetMaxOrdemPai($codparent))

                       $s .= '<a href="javascript: onmovedown('.$colecao->mItems[$i]->CodigoNode.');"><img src="images/down.gif" border=0 height=13></a>';

                    

                    $s .= '&nbsp;<a href="javascript: ondelete('.$colecao->mItems[$i]->CodigoNode.');"><img src="images/delete.gif" border=0 height=13></a>';

                }

                

                //$s .= ' '.$colecao->mItems[$i]->CodigoNode;

                

                $arvore->Add($nomenopai,$colecao->mItems[$i]->CodigoNode,$s,true,"");

                alocar_item($arvore, $colecao->mItems[$i]->CodigoNode, $colecao, $nivel+1, $codidioma, $colecao->mItems[$i]->CodigoNode, $movedelete);

            }

            else

            {

                $root = $arvore->getRootNode();

                $root->text = "<font color=blue><b>Website</b></font>";

                $root->expand=true;

                $root->image='';//"1PersonalFolders.gif";

                alocar_item($arvore, $colecao->mItems[$i]->CodigoNode, $colecao, $nivel+1, $codidioma, 'root', $movedelete);

            }



            

        }

}

alocar_item($arvore, 0,$itens, 0, $codidioma, '', ($site->IsEstruturaFixa==0));

if ($codsel >0 )

    $arvore->selectedIds = $codsel;







?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">

<link rel="stylesheet" type="text/css" href="visual.css">

<script src="includes/ckeditor/ckeditor.js"></script>



<script type="text/javascript" src="popup/jquery-1.7.1.min.js"></script>

<script type="text/javascript" src="popup/jquery.mousewheel-3.0.6.pack.js"></script>

<script type="text/javascript" src="popup/jquery.fancybox-1.3.4.js"></script>

<link rel="stylesheet" type="text/css" href="popup/jquery.fancybox-1.3.4.css" media="screen" />

<style>

.link_mini_criar,.link_mini_criar:visited{font-size:10px;color:#DDD;text-decoration:none;font-family:arial}

.link_mini_criar:hover{font-size:10px;color:#333;text-decoration:underline;font-family:arial}</style>

<script>

$(document).ready(function(){



    $('.fancybox_dados').fancybox({

        height        : 600,

        width : 500,

        autoSize    : false,

        titlePosition: 'inside',

        closeClick    : false,

        topRatio: 0.1,

        type:'iframe'           

    });

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



});







function oncriarfilho(codpai)

{

    var s = prompt('Nome do filho', '');

    if (s && s.length > 0)

    {

        document.ff.acao.value = 'autofilho';

        document.ff.codpaiautofilho.value = codpai;

        document.ff.nomeautofilho.value = s;

        document.ff.submit();

    }

}





function ongaleria(oldcod)

{

    document.ff.action = "index2.php?acessar=itens_galeria.php";

    // zerando nomes

    for (var i=0;i<document.ff.elements.length;i++)

        if ( document.ff.elements[i].name.substring(0,5) == 'nome[' )

            document.ff.elements[i].value = '';

            

    document.ff.submit();

}



function doshowdados(cod)

{

    $('#adadosnode').attr('href','dados_node.php?codsel='+cod);

    $('#adadosnode').trigger('click');

}



function teste_extensao(lista, extensao)

{

    for (var i=0;i<lista.length;i++)

    {

        if (lista[i].toLowerCase() == extensao.toLowerCase())

            return true;

    }

    return false;

}





function ongravarremovendo(nomearq)

{

    if (confirm('Remover o arquivo selecionado?'))

    {

        document.ff.acao.value = 'gravar_removendo_arquivo';

        document.ff.arqtodel.value = nomearq;

        document.ff.submit();

    }

    

}



function onshowarqs(obj, divobj, extensoes)

{

    var d = document.getElementById(divobj);

    d.innerHTML = '';

    for (var i=0;i<obj.files.length;i++)

    {

        pp = obj.files[i].name.split('.');

        

        extok = teste_extensao(extensoes, pp[pp.length-1]);

        

        if (extok)

            d.innerHTML = d.innerHTML + (i+1)+ ". " + obj.files[i].name;

        else

            d.innerHTML = d.innerHTML + "<font color='red'>" + (i+1)+ ". " + obj.files[i].name + '</font>';

            

        d.innerHTML = d.innerHTML + '<a href="javascript:doremoverarq('+"'"+obj.id+"',"+i+')">[x]</a><br>';

    }

}



function doremoverarq(idobj, idx)

{

    var ff = document.getElementById(idobj);

    ff.files[idx] = null;

}



function onnovo()

{

    document.ff.acao.value = 'novo';

    document.ff.set_coditempai.value = document.ff.codsel.value;

    return true;

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



function onsel()

{

    if (document.ff.codsite.value > 0)

    {

        document.ff.acao.value = 'selecionar';

        return true;

    }

    else

        return false;

}



function oneditar(cod)

{

    document.ff.acao.value = 'editar';

    document.ff.codsel.value = cod;

    document.ff.submit();

}



function onmoveup(cod)

{

    document.ff.acao.value = 'moveup';

    document.ff.codsel.value = cod;

    document.ff.submit();

}

function onmovedown(cod)

{

    document.ff.acao.value = 'movedown';

    document.ff.codsel.value = cod;

    document.ff.submit();

}





function ondelete(cod)

{

    if (confirm('Remover o item selecionado? ESSA AÇÃO NÃO PODERÁ SER DESFEITA!'))

    {

        document.ff.acao.value = 'remover';

        document.ff.codsel.value = cod;

        document.ff.submit();

    }

}



function ongravar()

{

    var ok = false;

    <?

    if ($site->IsEstruturaFixa == 1)

    {

    ?>

        ok = true;

    <?}else{?>

       

       if (document.ff.codacao.value  >0)

         ok = true;

    <?}?>



    if (ok)

    {

        document.ff.acao.value = 'gravar';

        document.ff.enctype = "multipart/form-data";

        return true;

    }

    else

    {

        alert('Antes de gravar, selecione o comportamento do item');

        return false;

    }

}



function do_autosel()

{

    document.ff.codsite.value = "<?=$sites->mItems[0]->CodigoSite?>";

    document.ff.acao.value = 'selecionar';

    document.ff.submit();

}



CKEDITOR.config.removePlugins = 'scayt';

CKEDITOR.config.disableNativeSpellChecker = true;

CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;

CKEDITOR.config.extraPlugins = 'parag';



</script>

</head>

<body <?if(count($sites->mItems)==1 && $codsite <= 0){?>onload="do_autosel()"<?}?>>

<form name="ff" method="post">

<input type="hidden" name="acao">

<input type="hidden" name="set_coditempai">

<input type="hidden" name="codpaiautofilho">

<input type="hidden" name="nomeautofilho">

<input type="hidden" name="codsel" value="<?=$codsel?>">

<input type="hidden" name="trad_from_idioma">

<input type="hidden" name="trad_to_idioma">

<input type="hidden" name="arqtodel">

<table width="100%">

    <tr>

        <td class="cad_title"><?=($objLogin->iTipo == tipo_login_cliente)?'Edit Websites':'Edit Websites'?></td>

    </tr>

    <tr>

        <td class="common">

        

            <?

            if ($codsite <=0 )

            {

            ?>

            Selecione o site desejado: <select name="codsite">

                <option value=0></option>

                <?$sites->PrintCombo($codsite);?>

            </select>

            

            <input type="submit" value="Selecionar" onclick="return onsel()">

        

            <?}

            else

            {

            ?>

            

                <input type="hidden" name="codsite" value="<?=$codsite?>">

            

                > Website Structure

                <br><br>

                <span class="titulo">WebSite: <?=$nomesite?></span>

                

                <br><bR>

                

                <table width="100%">

                

                    <tr valign="top">

                    

                        <td width="50%">

                        

                            <fieldset>

                                <legend class="group_label">WebSite Structure</legend>

                                    

                                    <div align="right" class="common"><select name="codidioma" onchange="document.ff.submit()">

                                        <?

                                        $idiomas->PrintCombo($codidioma);

                                        ?>

                                    </select></div>

                                    

                                    <span class="common">



                                    <?

                                    if (!$bArvoreVelha)

                                    {

                                        print $arvore->Render();

                                    }

                                    else

                                    {

                                    ?>

                                        <font size="2" color="black">

                                        <?

                                        

                                        function print_item($codparent, $colecao, $nivel, $codidioma)

                                        {

                                            for ($i=0;$i<count($colecao->mItems);$i++)

                                                if ($colecao->mItems[$i]->CodigoParentNode == $codparent && 

                                                    $colecao->mItems[$i]->CodigoTipoAcao != tipo_acao_itemgaleria)

                                                {

                                                    $espaco_nivel = str_repeat('&nbsp;', $nivel*5);

                                                    if ($nivel == 0)

                                                        print $espaco_nivel.'<B>> '.$colecao->mItems[$i]->Nome.'</b>&nbsp;';

                                                    else

                                                    {

                                                        $s = $colecao->mItems[$i]->Idiomas[$codidioma]->Nome;

                                                        $s = strlen(trim($s))==0?'(sem nome)':$s;

                                                        $cordes = $colecao->mItems[$i]->IsAtivo==0?'style="color:#999"':'';

                                                        print $espaco_nivel.'<b>> <a href="javascript:oneditar('.$colecao->mItems[$i]->CodigoNode.');" '.$cordes.'>'.$s.'</a>&nbsp;</b>';

                                                        //if ($colecao->mItems[$i]->CodigoAcao != acao_sem_acao)

                                                          //  print '<span class="aviso_light">'.$colecao->mItems[$i]->Acao.'</span>&nbsp;';

                                                    }

                                                    

                                                    if ($nivel > 0)

                                                    {

                                                        if ($colecao->mItems[$i]->Ordem > 1)

                                                            print '<a href="javascript: onmoveup('.$colecao->mItems[$i]->CodigoNode.');"><img src="images/up.jpg" border=0 height=13></a>';

                                                        

                                                        if ($colecao->mItems[$i]->Ordem < $colecao->GetMaxOrdemPai($codparent))

                                                            print '<a href="javascript: onmovedown('.$colecao->mItems[$i]->CodigoNode.');"><img src="images/down.jpg" border=0 height=13></a>';

                                                        

                                                        print '&nbsp;<a href="javascript:oneditar('.$colecao->mItems[$i]->CodigoNode.');"><img src="images/edit.gif" border=0 height=13></a>'.

                                                              '&nbsp;<a href="javascript: ondelete('.$colecao->mItems[$i]->CodigoNode.');"><img src="images/delete.jpg" border=0 height=13></a>';

                                                    }

                                                    print '<br>';

                                                    print_item($colecao->mItems[$i]->CodigoNode, $colecao, $nivel+1, $codidioma);

                                                }

                                        }

                                        print_item(0,$itens, 0, $codidioma);

                                    }

                                    ?>

                                    

                            </fieldset>

                        </td>



                        <td width="50%">

                            <?

                            if ($codsel <= 0 && $site->IsEstruturaFixa==1)

                            {

                            ?>

                                &nbsp;

                            <?

                            }

                            else

                            {

                            ?>

                                <fieldset>

                                    <legend class="group_label"><?=($codsel>0?'Edit Item':'New Item')?></legend>

                                    

                                    <?if($codsel>0){

                                        $qt = mysql_fetch_row(mysql_query("SELECT count(*) FROM Nodes_Data WHERE CodigoNode=$codsel"));

                                        if ($qt[0] > 0)

                                        {

                                        ?>

                                            <div style="border:1px solid #DDDDDD;padding:5px; background-color:#FFFFBB;color:#333333;margin-bottom:10px;cursor:pointer" onclick="doshowdados(<?=$codsel?>)" class="common">Este item possui <?=$qt[0]?> dados de usuário. Clique aqui para vê-los.</div>

                                        <?

                                        }

                                    }?>

                                    

                                    <span class="common">

                                    <?

                                    if ($site->IsEstruturaFixa != 1)

                                    {

                                    ?>

                                        Ação (comportamento esperado)<br>

                                        <select name="codacao" style="width:100%" onchange="document.ff.submit()" <?if($site->IsEstruturaFixa==1){?>disabled<?}?>>

                                            <?if($codacao <=0){?><option value=0>(selecione)</option><?}?>

                                            <?$acoes->PrintCombo($codacao);?>

                                        </select>

                                        <?if($codacao > 0){?>

                                            <span class="aviso_light"><?=$objacao->Dica?></span>

                                        <?}?>

                                    

                                    <br>



                                    <?}?>

                                    

                                    <?if ($codacao > 0 && $objacao->CodigoTipoAcao == tipo_acao_galeria && $codsel > 0){?>

                                        <div style="margin-top:5px"><a href="javascript: ongaleria('<?=base64_encode($codsel)?>')">Edit Gallery(<?=$objacao->NomeFilha?>)</a></div>

                                    <?}?>

                                    

                                    

                                    <?if($codacao > 0 && $objacao->QtdeCamposSemIdioma > 0){?>

                                    <br><div style="padding:5px; border:1px solid #AAAAAA" align="left">

                                    

                                    <? 

                                    for ($i=0;$i<count($objacao->Campos);$i++)

                                        if ($objacao->Campos[$i]->VariaIdioma == 0 &&

                                            ( $objacao->Campos[$i]->VisivelUsuario == 1 || $objLogin->iTipo != tipo_login_cliente) )

                                        {

                                            // pegando valor default se for o caso

                                            if ($codsel <= 0) // somente para novos itens

                                            {

                                                $campoacao[$objacao->Campos[$i]->CodigoCampoAcao] = $objacao->Campos[$i]->ValorDefault;

                                            }

                                            if (strlen($objacao->Campos[$i]->Extensoes) > 0)

                                            {

                                                $extensoes = explode(';',$objacao->Campos[$i]->Extensoes);

                                                for ($x=0;$x<count($extensoes);$x++)

                                                    $extensoes[$x] = "'".$extensoes[$x]."'";

                                                if (is_array($extensoes))

                                                    $lista_extensoes = implode(',',$extensoes);

                                            }

                                                                                    

                                            if ($objacao->Campos[$i]->CodigoTipoCampo == tipo_campo_checkbox)

                                            {

                                            ?>

                                                <input type="checkbox" style="margin-left:0px" name="campoacao[<?=$objacao->Campos[$i]->CodigoCampoAcao?>]" <?if($campoacao[$objacao->Campos[$i]->CodigoCampoAcao] == 'on'){?>checked<?}?>>

                                                    <?=$objacao->Campos[$i]->CampoDisplay?><br>

                                            <?

                                            }

                                            else

                                            {

                                            ?>

                                                <?=$objacao->Campos[$i]->CampoDisplay?><br>

                                                <?

                                                if (count($objacao->Campos[$i]->ListaValores) > 0)

                                                {

                                                ?>

                                                    <select name="campoacao[<?=$objacao->Campos[$i]->CodigoCampoAcao?>]" style="width:100%">

                                                        <?$acoes->PrintComboCampo($objacao->Campos[$i], $campoacao[$objacao->Campos[$i]->CodigoCampoAcao])?>

                                                    </select>

                                                <?

                                                }

                                                else if ($objacao->Campos[$i]->CodigoTipoCampo == tipo_campo_multilinha)

                                                {

                                                ?>

                                                    <textarea <?if($objacao->Campos[$i]->EditorHTML==1){?>class="ckeditor"<?}?> style="width:100%;font-family:arial;font-size:12px" rows=8 name="campoacao[<?=$objacao->Campos[$i]->CodigoCampoAcao?>]"><?=$campoacao[$objacao->Campos[$i]->CodigoCampoAcao]?></textarea>

                                                <?

                                                }

                                                else if ($objacao->Campos[$i]->CodigoTipoCampo == tipo_campo_arqimagem || 

                                                         $objacao->Campos[$i]->CodigoTipoCampo == tipo_campo_arqmusica ||

                                                         $objacao->Campos[$i]->CodigoTipoCampo == tipo_campo_arqgeral)

                                                {

                                                    

                                                    if ($codsel > 0 && strlen($campoacao[$objacao->Campos[$i]->CodigoCampoAcao])>0 && file_exists('site_files/'.$campoacao[$objacao->Campos[$i]->CodigoCampoAcao]))

                                                    {

                                                        if ($objacao->Campos[$i]->CodigoTipoCampo == tipo_campo_arqimagem)

                                                        {

                                                        ?>

                                                            <center>

                                                                <div style="border:1px solid #AAAAAA;margin:5px; padding:5px">

                                                                    <a href="site_files/<?=$campoacao[$objacao->Campos[$i]->CodigoCampoAcao]?>" target="_blank"><img src="site_files/<?=$campoacao[$objacao->Campos[$i]->CodigoCampoAcao]?>?rand=<?=rand(0,9999)?>" border="0" width=150></a><br>

                                                                </div>

                                                        <?

                                                        }

                                                        else

                                                        {

                                                    ?>

                                                            <a href="sites_files/<?=$campoacao[$objacao->Campos[$i]->CodigoCampoAcao]?>" target="_blank">Open File</a> |&nbsp;

                                                    <?   }?>

                                                        <a href="javascript:ongravarremovendo('<?=base64_encode($campoacao[$objacao->Campos[$i]->CodigoCampoAcao])?>')">Remove File</a></center>

                                                    <?

                                                    }

                                                    else

                                                    {

                                                ?>

                                                        <input type="file" name="arqacao[<?=$objacao->Campos[$i]->CodigoCampoAcao?>]" style="width:100%">

                                                <?

                                                    }

                                                }

                                                else

                                                {

                                                ?>

                                                    <input type="text" name="campoacao[<?=$objacao->Campos[$i]->CodigoCampoAcao?>]" style="width:100%;margin-bottom:5px" value="<?=htmlentities($campoacao[$objacao->Campos[$i]->CodigoCampoAcao])?>">

                                                <?

                                                }

                                                ?>    

                                                <br>

                                        <?

                                            }

                                        }

                                        ?>

                                    </div>

                                    <?}?>



                                    

                                    <br>

                                        

                                    <?

                                    $dica_texto_acao = $objacao->LabelCampoTexto;

                                    $dica_texto_acao = strlen($dica_texto_acao)>0?'<br><span class="aviso_light">'.$dica_texto_acao.'</span>':'';

                                    for ($i=0;$i<count($idiomas->mItems);$i++)

                                    {

                                        if ($i>0) print '<br>';

                                    ?>

                                        <div style="padding:5px; border:1px solid #AAAAAA">

                                        

                                        <div class="common" style="background-color:#DDDDDD; border:1px solid #AAAAAA;margin-bottom:5px; padding:5px" align="left"><b><?=$idiomas->mItems[$i]->Idioma?></b></div>

                                        <span class="common">Item Name (In <?=$idiomas->mItems[$i]->Idioma?>)<br>

                                            <input type="text" name="nome[<?=$idiomas->mItems[$i]->CodigoIdioma?>]" style="width:100%" onblur="dosetoutrosnomes(this)" value="<?=$nome[$idiomas->mItems[$i]->CodigoIdioma]?>">

                                            <br>

                                    

                                        <?

                                        if ($codacao > 0 && $objacao->UtilizaTexto == 1)

                                        {

                                        ?>

                                            Texto (em <?=$idiomas->mItems[$i]->Idioma?>)<?=$dica_texto_acao?> <br>

                                                <textarea <?if($objacao->EditorHTML==1){?>class="ckeditor"<?}?> name="texto[<?=$idiomas->mItems[$i]->CodigoIdioma?>]" style="width:100%" rows=5 onblur="dosetoutrostextos(this);"><?=$texto[$idiomas->mItems[$i]->CodigoIdioma]?></textarea>

                                        <?

                                        }

                                        ?>

                                        

                                        </div>           

                                            

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

                                    

                                    

                                    <hr>

                                    

                                    <?

                                    if ($site->IsEstruturaFixa != 1)

                                    {

                                        if ($set_coditempai > 0)

                                            $coditempai = $set_coditempai;

                                    ?>

                                        

                                        Parent item

                                            <select name="coditempai">

                                                <?$itens->PrintCombo($coditempai, true, $codsel, true, true)?>

                                            </select>

                                    <?}?>

                                    

                                    <br><br>

                                    <input type="submit" value="<?if($codsel>0){?>Save Changes<?}else{?>New item<?}?>" onclick="return ongravar()">

                                    <?if($codsel>0 && $site->IsEstruturaFixa == 0){?>

                                        <input type="submit" value="New item" onclick="return onnovo()">

                                    <?}?>

                                    

                                    &nbsp;&nbsp;&nbsp;<input type="checkbox" name="cbativo" id="cbativo" <?if($codsel <= 0 || $cbativo=='on'){?>checked<?}?>><label for="cbativo" class="common">Enabled</label>

                                    

                                    <?if($site->IsEstruturaFixa == 0){?>

                                    &nbsp;<input type="checkbox" name="cbuseredit" id="cbuseredit" <?if($cbuseredit=='on'){?>checked<?}?>><label for="cbuseredit" class="common">Criação de filhos</label>

                                    <?}?>

                                    

                                    <?if($codsel > 0 && $isusernode){?>

                                        <div style="float:right"><input type="submit" value="Remover item" onclick="return ondelete(<?=$codsel?>)"></div>

                                    <?}?>

                                    

                                        

                                </fieldset>

                            <?}?>

                        </td>

                    

                    </tr>

                

                </table>

                

            

            <?

            }?>

        

        </td>

    </tr>

</table>

</form>  

<a href="dados_node.php" id="adadosnode" class="fancybox_dados" style="display;none"></a>

<a href="#msgaviso-div" class="fancybox_aviso" id="amsgaviso" style="display:none">&nnsp;</a>

<div style="display: none">

    <div id="msgaviso-div" class="common"><?=$msg?></div>

</div>



</body>

<script>



arvore.registerEvent("OnBeforeDrop",function(sender,arg){

            var parentid = arvore.getNode(arg.NodeId).getParentId();

            var childids = arvore.getNode(parentid).getChildIds();

            var posdrag=-1;posdrop = -1;

            for(var i=0;i<childids.length;i++)

            {

                if (childids[i]==arg.NodeId)

                {

                    posdrop = i;

                }

                if (childids[i]==arg.DragNodeId)

                {

                    posdrag = i;

                }    

            }

            //alert(posdrag);

            //alert(posdrop);

            if (posdrag != -1 && posdrop != -1)

            {

                if (posdrag<posdrop)

                {

                    //Drag from above node to below node

                    arvore.getNode(arg.DragNodeId).moveToBelow(arg.NodeId);

                    alert('desce');

                }

                else

                {

                    //Drag from below node to node above

                    arvore.getNode(arg.DragNodeId).moveToAbove(arg.NodeId);

                    alert(arg.NodeId);

                }

            }



            return false;//Cancel default attaching node behavior

        });



</script>

</html>