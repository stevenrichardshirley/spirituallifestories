<?
include_once('header_classes.php');

$acoes = new CAcoes();
$acoes->bMostrarTodas = true;
$acoes->szOrderBy = 'Nome';
$acoes->LoadFromDB();

$tipos = new CDataClass();
$tipos->SetUp('Campos_Tipos', 'CodigoTipoCampo', 'TipoCampo', 'TipoCampo');
$tipos->LoadFromDB();

if ($codacao > 0)
{
    $campos = new CCamposAcoes();
    $campos->iCodAcao = $codacao;

    $valores = new CValoresCampos();

    if ($acao == 'excluir')
    {
        $campos->DeleteFromDB($codsel);
        $acao = 'novo';
    }
    else if ($acao == 'delvalor')
    {
        $valores->DeleteFromDB($codvalordel);
    }
    else if ($acao == 'gravar')
    {
        $inf->Campo = $nome;
        $inf->CampoDisplay = $label;
        $inf->CodigoTipoCampo = $codtipocampo;
        $inf->ValorDefault = $default;
        $inf->Extensoes = $extensoes;
        
        $inf->LarguraAlturaAutoCrop = $largalt;
        
        $inf->VisivelUsuario = $cbvisivel=='on'?1:0;
        $inf->EditorHTML = $cbhtml=='on'?1:0;
        $inf->CodigoAcao = $codacao;
        $inf->VariaIdioma = $cbidioma=='on'?1:0;
        $inf->OrdenadorItens = $cborder=='on'?1:0;
        $inf->IsFiltravel = $cbfiltravel=='on'?1:0;
        
        $campos->SaveToDB($inf, $codsel);
        $acao = 'novo';
    } else if ($acao == 'gravvalor')
    {
        $inf->CodigoCampoAcao = $codsel;
        $inf->Valor = $valor; 
        $inf->ValorDisplay = $valordisplay;
        $valores->SaveToDB($inf);
    }

    $campos->LoadFromDB();

    if ($acao == 'editar')
    {
        $inf = $campos->GetItem($codsel);
        
        $nome = $inf->Campo;
        $label = $inf->CampoDisplay;
        $codtipocampo = $inf->CodigoTipoCampo;
        $default = $inf->ValorDefault;
        $extensoes = $inf->Extensoes;
        $cbvisivel = $inf->VisivelUsuario == 1?'on':'';
        $cbhtml = $inf->EditorHTML == 1?'on':'';
        $cborder = $inf->OrdenadorItens ==1?'on':'';
        $cbidioma = $inf->VariaIdioma ==1?'on':'';
        $cbfiltravel = $inf->IsFiltravel == 1?'on':'';

        $largalt = $inf->LarguraAlturaAutoCrop;
    }
}
if ($acao == 'novo')
    unset($cborder, $cbidioma, $cbhtml, $nome, $label, $codtipoacampo, $default, $extensoes, $cbvisivel, $codsel, $largalt, $cbfiltravel);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="visual.css">
<script>
function ongravarvalor()
{
    document.ff.acao.value = 'gravvalor';
    document.ff.submit();
}

function donovo()
{
    document.ff.acao.value = 'novo';
    document.ff.submit();
}

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
function ondelvalor(cod)
{
    if (confirm('Deseja remover o valor selecionado?'))
    {
        document.ff.acao.value = 'delvalor';
        document.ff.codvalordel.value = cod;
        document.ff.submit();
    }
}

function ongravar()
{
    // relação de campos obrigatórios
    if (document.ff.nome.value.length == 0)
    {
        alert('Por favor, preencha o campo obrigatório!');
        document.ff.nome.focus();
        return false;
    }
    else if (document.ff.label.value.length == 0)
    {
        alert('Por favor, preencha o campo obrigatório!');
        document.ff.label.focus();
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
<input type="hidden" name="codvalordel">
<table width="100%">
    <tr>
        <td class="cad_title">Cadastramento de campos das ações</td>
    </tr>
    <tr>
        <td class="field_caption">
            Selecione a ação desejada<br>
                <select name="codacao" style="width:100%" onchange="donovo()">
                    <option value="0">(selecione...)</option>
                    <?$acoes->PrintCombo($codacao);?>
                </select>
        </td>
    </tr>
    <?
    if ($codacao > 0)
    {
        
    ?>
    <tr>
        <td >
            <Table width="100%" cellpadding=0 cellspacing=0>
                <tr valign="top">
                    <td width="50%" >
                        <fieldset>
                            <legend class="group_label">Cadastro/edição</legend>
                            <table width="100%">
                                <tr>
                                    <td class="field_caption">Nome do campo<br>
                                        <input type="text" name="nome" style="width:100%" value="<?=$nome?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Label do campo<br>
                                        <input type="text" name="label" style="width:100%" value="<?=$label?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Tipo do campo<br>
                                        <select name="codtipocampo" style="width:100%">
                                            <?$tipos->PrintCombo($codtipocampo);?>
                                        </select>
                                        </td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Lista de extensões<br><span class="aviso_light">somente utilizado para campos tipo arquivo ou imagem, exemplo: jpg;gif;png<br>
                                        <input type="text" name="extensoes" style="width:100%" value="<?=$extensoes?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Largura|altura auto-crop (somente para tipo imagem)<br>
                                        <input type="text" name="largalt" style="width:100%" value="<?=$largalt?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Valor default<br>
                                        <input type="text" name="default" style="width:100%" value="<?=$default?>">
                                        <br><br>
                                        <input type="checkbox" name="cbvisivel" id="cbvisivel" <?if($cbvisivel=='on'){?>checked<?}?>><label for="cbvisivel">Campo visível ao usuário</label><br>
                                        <input type="checkbox" name="cbhtml" id="cbhtml" <?if($cbhtml=='on'){?>checked<?}?>><label for="cbhtml">Editor HTML</label><br>
                                        <input type="checkbox" name="cbidioma" id="cbidioma" <?if($cbidioma=='on'){?>checked<?}?>><label for="cbidioma">O conteúdo deste campo varia de idioma</label><br>
                                        <input type="checkbox" name="cborder" id="cborder" <?if($cborder=='on'){?>checked<?}?>><label for="cborder">Auto-ordenar itens pelo conteúdo deste campo</label><br>
                                        <input type="checkbox" name="cbfiltravel" id="cbfiltravel" <?if($cbfiltravel=='on'){?>checked<?}?>><label for="cbfiltravel">Componente de filtro na lista de itens</label><br>
                                        
                                        <br><bR>
                                        </td>
                                </tr>
                                
                                
                                <?
                                if ($codsel  >0)
                                {
                                ?>
                                <tR>
                                    <td class="field_caption">
                                        <fieldset>
                                            <legend class="group_label">Lista de valores para preenchimento</legend>
                                            
                                            <table width="100%">
                                                <tr>
                                                    <td class="table_title_light" width="50%">Valor</td>
                                                    <td class="table_title_light" width="50%">Display</td>
                                                </tr>
                                            <?
                                            $valores->iCodCampo = $codsel;
                                            $valores->LoadFromDB();
                                            for ($i=0;$i<count($valores->mItems);$i++)
                                            {
                                            ?>
                                                <tr>
                                                    <Td class="table_item0"><?=$valores->mItems[$i]->Valor?></td>
                                                    <Td class="table_item0"><?=$valores->mItems[$i]->ValorDisplay?></td>
                                                    <td><a href="javascript:ondelvalor(<?=$valores->mItems[$i]->CodigoValor?>)">[x]</a></td>
                                                </tr>
                                            <?                                            
                                            }
                                            ?>    
                                                <tr>
                                                    <Td class="table_item0"><input type="text" name="valor" style="width:100%"></td>
                                                    <Td class="table_item0"><input type="text" name="valordisplay" style="width:100%"></td>
                                                    <td><a href="javascript: ongravarvalor()">[ok]</a></td>
                                                </tr>
                                            
                                            </table>
                                            
                                        </fieldset>
                                         <br><Br>
                                    </td>
                                </tr>
                                
                                <?
                                }
                                ?>
                                
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
                                $campos->PrintCombo($codsel);
                                ?>
                                </select>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?}?>
</table>
</form>  
</body>
</html>