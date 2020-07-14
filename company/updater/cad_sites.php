<?

include_once('header_classes.php');



$clientes = new CClientes();

$clientes->iCodUsuario = $objLogin->iCodigo;

$clientes->LoadFromDB();



$idiomas = new CIdiomas();

$idiomas->LoadFromDB();



$sites = new CSites();

if ($acao == 'excluir')

{

    $sites->DeleteFromDB($codsel);

    $acao = 'novo';

}

else if ($acao == 'gravar')

{

    $inf->CodigoUsuario = $objLogin->iCodigo;

    $inf->Nome = $nome;

    $inf->Detalhes      = $detalhes;

    $inf->URL           = $url;

    $inf->CodigoCliente = $codcliente;

    $inf->Ativo = $cbativo=='on'?1:0;

    $inf->IsEstruturaFixa = $cbfixa=='on'?1:0;

    $inf->CodigoIdiomaPadrao = $codidiomapadrao;

    

    $novo = ($codsel <= 0);

    

    $codsite = $sites->SaveToDB($inf, $codsel);

    

    if ($novo)

    {

        // criando item ROOT

        $itens = new CItensSite();

        $infitem->CodigoSite = $codsite;

        $infitem->Nome = 'Raiz do site';

        $infitem->CodigoAcao = acao_sem_acao;

        $infitem->Ordem = 1;

        $codroot = $itens->SaveToDB($infitem);

        // criando menu principal

        $infitem->Nome = 'Menu principal';

        $infitem->CodigoAcao = acao_sem_acao;

        $infitem->CodigoParentNode = $codroot;

        $codmenu = $itens->SaveToDB($infitem);

        if ($codidiomapadrao > 0)

            mysql_query("INSERT INTO Nodes_Nomes (CodigoNode, CodigoIdioma, Nome) VALUES ($codmenu, $codidiomapadrao, 'Menu principal')");

    }

    // idiomas

    mysql_query("DELETE FROM Sites_Idiomas WHERE CodigoSite=$codsite");

    for ($i=0;$i<count($cbidioma);$i++)

        mysql_query("INSERT INTO Sites_Idiomas (CodigoIdioma, CodigoSite) VALUES (".$cbidioma[$i].", $codsite)");

    

    $acao = 'novo';

}



$sites->iCodUsuario = $objLogin->iCodigo;

$sites->LoadFromDB();



if ($acao == 'novo')

    unset($codcliente, $cbidioma, $codidiomapadrao, $cbativo, $url, $nome, $detalhes, $cbfixa, $codsel);

else if ($acao == 'editar')

{

    $inf = $sites->GetItem($codsel);

    $nome = $inf->Nome;

    $detalhes = $inf->Detalhes;

    $codcliente = $inf->CodigoCliente;

    $cbativo = $inf->Ativo==1?'on':'';

    $cbfixa  = $inf->IsEstruturaFixa==1?'on':'';



    $url = $inf->URL;

    $codidiomapadrao = $inf->CodigoIdiomaPadrao;

    // idiomas

    $qr = mysql_query("SELECT CodigoIdioma FROM Sites_Idiomas WHERE CodigoSite=$codsel");

    $cbidioma = null;

    while ($r=mysql_fetch_row($qr))

        $cbidioma[] = $r[0];

}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>

<link rel="stylesheet" type="text/css" href="visual.css">

<script>

function onnovo()

{

    document.ff.acao.value = 'novo';

    return true;

}

function onsel()

{

    document.ff.acao.value = 'editar';

    document.ff.submit();

}



function algum_idioma()
{
    return true;
    /*
    var cc = document.getElementsByName('cbidioma[]');
    for (var i=0;i<cc.length-1;i++)
    {
        alert(1);
        if (cc[i].checked)
            return true;
    }
    return false;*/
}



function ongravar()
{
    // relação de campos obrigatórios
    if (!algum_idioma())
    {
        alert('Por favor, selecione ao menos um idioma para este site');
        return false;
    }
    else if (document.ff.nome.value.length == 0)
    {
        alert('Por favor, preencha o campo obrigatório!');
        document.ff.nome.focus();
        return false;
    }
    else
    {
        document.ff.acao.value = 'gravar';
        return true;
    }
}

function onexcluir()

{

    if (confirm('Deseja remover o registro selecionado?'))

    {

        document.ff.acao.value = 'excluir';

        return true;

    }

    else

        return false;

}

</script>

</head>

<body>

<form name="ff" method="post">

<input type="hidden" name="acao">

<table width="100%">

    <tr>

        <td class="cad_title">Cadastramento de sites</td>

    </tr>

    <tr>

        <td >

            <Table width="100%" cellpadding=0 cellspacing=0>

                <tr valign="top">

                    <td width="50%" >

                        <fieldset>

                            <legend class="group_label">Cadastro/edição</legend>

                            <table width="100%">

                                <tr>

                                    <td class="field_caption"><?if($codsel>0){?><div align="right" class="common"><b>Id deste site: <?=$codsel?></div><?}?>Nome do site <br>

                                        <input type="text" name="nome" style="width:100%" value="<?=$nome?>"></td>

                                </tr>

                                <tr>

                                    <td class="field_caption">Detalhes<br>

                                        <textarea name="detalhes" style="width:100%" rows=5><?=$detalhes?></textarea></td>

                                </tr>

                                <tr>

                                    <td class="field_caption">URL do site<br>

                                        <input type="text" name="url" style="width:100%" value="<?=$url?>"></td>

                                </tr>

                                <tr>

                                    <td class="field_caption">Idioma(s) do site<br>

                                    

                                        <div style="margin-top:5px;padding:5px;border:1px solid #999999" class="common">

                                        <?

                                        for ($i=0;$i<count($idiomas->mItems);$i++)

                                        {

                                        ?>

                                        

                                            <input type="checkbox" name="cbidioma[]" <?if(is_array($cbidioma) && in_array($idiomas->mItems[$i]->CodigoIdioma,$cbidioma)){?>checked<?}?> value="<?=$idiomas->mItems[$i]->CodigoIdioma?>"><?=
                                            $idiomas->mItems[$i]->Idioma?><br>

                                        <?

                                        }

                                        ?>

                                        </div>

                                    

                                </tr>

                                <tr>

                                    <td class="field_caption">Idioma padrão<br>

                                        <select name="codidiomapadrao" style="width:100%">

                                            <?$idiomas->PrintCombo($codidiomapadrao);?>

                                        </select>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="field_caption">Cliente<br>

                                        <select name="codcliente" style="width:100%">

                                            <option value="0"></option>

                                            <?$clientes->PrintCombo($codcliente)?>

                                        </select>

                                        <br><br>

                                        <input type="checkbox" name="cbativo" <?if($cbativo=='on'){?>checked<?}?>>Site ativo

                                        <input type="checkbox" name="cbfixa" <?if($cbfixa=='on'){?>checked<?}?>>Estrutura fixa

                                        <br><br><br>

                                        </td>

                                </tr>

                                <tr>

                                    <td>

                                        <table width="100%" cellpadding=0 cellspacing=0>

                                            <tr>

                                                <td><input type="submit" value="Gravar" onclick="return ongravar()"></td>

                                                <td align="right"><?if($codsel>0){?><input type="submit" value="Excluir" onclick="return onexcluir();"><input type="submit" value="Novo" onclick="return onnovo()"><?}?></td>

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                            </table>

                        </fieldset>

                    </td>

                    <td><img src="images/spacer.gif" width=5></td>

                    <td width="50%">

                        <fieldset>

                            <legend class="group_label">Listagem</legend>

                                <select name="codsel" onchange="onsel()" style="width:100%;" size=20>

                                <?

                                $sites->PrintComboClientes($codsel);

                                ?>

                                </select>

                        </fieldset>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

</form>  

</body>

</html>