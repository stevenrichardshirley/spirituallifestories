<?
include_once('header_classes.php');

$acoes = new CAcoes();
$acoes->bMostrarTodas = true;
$acoes->szOrderBy = 'A.Nome';


$clientes = new CClientes();
$clientes->LoadFromDB();


$tipos = new CTiposAcoes();
$tipos->LoadFromDB();

if ($acao == 'excluir')
{
    $acoes->DeleteFromDB($codsel);
    $acao = 'novo';
}
else if ($acao == 'gravar')
{
    $inf->Nome = $nome;
    $inf->CodigoAcaoFilha = $codacaofilha==0?'(nulo)':$codacaofilha;
    $inf->CodigoTipoAcao = $codtipoacao;
    $inf->LabelCampoTexto = $labeltexto;
    $inf->LabelNomeItem = $labelnome;
    $inf->EditorHTML = $cbhtml=='on'?1:0;
    $inf->Dica = $dica;
    $inf->UtilizaTexto = $cbtexto=='on'?1:0;
    $inf->IsGaleriaFotos = $cbfotos=='on'?1:0;
    if ($codcliente2 == -1)
        $inf->CodigoCliente = '(nulo)';
    else if ($codcliente2 > 0)
        $inf->CodigoCliente = $codcliente2;
    else
    {
        if ($codcliente == -1)
            $inf->CodigoCliente = '(nulo)';
        else
            $inf->CodigoCliente = $codcliente;
    }
    

    $codgrav = $acoes->SaveToDB($inf, $codsel);
    if ($codsel <=0 )
        print '<script>document.location.href="index2.php?acessar=cad_campos_acoes.php&codacao='.$codgrav.'";</script>';
       
    $acao = 'novo';
} else if ($acao == 'gravar_filho')
{
    $codauto = NextCode('Acoes_AutoFilhos', 'CodigoAutoFilho');
    mysql_query("INSERT INTO Acoes_AutoFilhos (CodigoAutoFilho, CodigoAcao, Nome, CodigoAcaoFilho) VALUES ($codauto, $codsel, '$filho_nome', $filho_codacao)");
    unset($filho_acao,$filho_nome);
}


$acoesfilhos = new CAcoes();
$acoesfilhos->iCodTipo = tipo_acao_galeria;
$acoesfilhos->iCodCliente = $codcliente;
$acoesfilhos->LoadFromDB();

$acoes->iCodCliente = $codcliente;
$acoes->LoadFromDB();

if ($acao == 'novo')
    unset($codacaofilha, $cbfotos, $labelnome, $cbhtml, $codtipoacao, $nome, $labeltexto, $dica, $cbtexto, $codsel);
else if ($acao == 'editar')
{
    $inf = $acoes->GetItem($codsel);
    $nome = $inf->Nome;
    $codacaofilha = $inf->CodigoAcaoFilha;
    $codtipoacao = $inf->CodigoTipoAcao;
    $labeltexto = $inf->LabelCampoTexto;
    $labelnome = $inf->LabelNomeItem;
    $dica = $inf->Dica;
    $cbtexto = $inf->UtilizaTexto == 1?'on':'';
    $cbhtml = $inf->EditorHTML == 1?'on':'';
    $cbfotos = $inf->IsGaleriaFotos == 1?'on':'';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="visual.css">
<script>
function ongravarfilho()
{
    document.ff.acao.value = 'gravar_filho';
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
function ongravar()
{
    // relação de campos obrigatórios
    if (document.ff.nome.value.length == 0)
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
function onclientechange()
{
    document.ff.acao.value = 'novo';
    document.ff.submit();
}
</script>
</head>
<body>
<form name="ff" method="post">
<input type="hidden" name="acao">
<table width="100%">
    <tr>
        <td class="cad_title">Cadastramento de ações/comportamentos</td>
    </tr>
    <tr>
        <td >
            <fieldset>
                <legend class="group_label">Cliente</legend>
                    <select name="codcliente" style="width:100%" onchange="onclientechange()">
                        <?if($codcliente == 0){?>
                            <option value="0" selected>(selecione o cliente)</option>
                        <?}?>
                        <option value="-1" <?if($codcliente==-1){?>selected<?}?>>(ações sem cliente agrupador)</option>
                        <?$clientes->PrintCombo($codcliente);?>
                        
                    </select>
            </fieldset>

            <?
            if ($codcliente != 0)
            {
            ?>
            
            <Table width="100%" cellpadding=0 cellspacing=0>
                <tr valign="top">
                    <td width="50%" >
                        <fieldset>
                            <legend class="group_label">Cadastro/edição</legend>
                            <table width="100%">
                                <tr>
                                    <td class="field_caption">Nome da ação<br>
                                        <input type="text" name="nome" style="width:100%" value="<?=$nome?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Label nome do item<br>
                                        <input type="text" name="labelnome" style="width:100%" value="<?=$labelnome?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Tipo da ação<br>
                                        <select name="codtipoacao" style="width:100%">
                                            <?$tipos->PrintCombo($codtipoacao);?>
                                        </select>
                                        </td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Ação filha (somente para ações tipo GALERIA)<br>
                                        <select name="codacaofilha" style="width:100%">
                                            <option value="0"></option>
                                            <?$acoes->PrintCombo($codacaofilha);?>
                                        </select>
                                        </td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Dica<br>
                                        <textarea name="dica" style="width:100%" rows=5><?=$dica?></textarea></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">
                                        <fieldset>
                                            <legend class="group_label">Campo texto</legend>
                                                <span class="field_caption">
                                                    Label do campo texto<br>
                                                        <input type="text" name="labeltexto" style="width:100%" value="<?=$labeltexto?>">
                                                        <br>
                                                        <input type="checkbox" name="cbtexto" id="cbtexto" <?if($cbtexto=='on'){?>checked<?}?>><label for="cbtexto">Utiliza campo texto</label><br>
                                                        <input type="checkbox" name="cbhtml" id="cbhtml" <?if($cbhtml=='on'){?>checked<?}?>><label for="cbhtml">Campo texto em HTML (habilitar editor HTML)</label><br>
                                                        <input type="checkbox" name="cbfotos" id="cbfotos" <?if($cbfotos=='on'){?>checked<?}?>><label for="cbfotos">Esta ação contém apenas itens com fotos (é uma galeria de fotos)</label><br>
                                                        
                                        </fieldset>
                                        <br><br>
                                        
                                        <?if($codsel > 0){?>
                                        
                                        <fieldset>
                                            <legend class="group_label">Galerias automáticas</legend>
                                            
                                            <table width="100%">
                                                <tr>
                                                    <td width="50%" class="table_title_light">Nome do item</td>
                                                    <td width="50%" class="table_title_light">Ação</td>
                                                </tr>
                                                <?
                                                $acaosel = $acoes->GetItem($codsel);
                                                $autofilhossel = $acaosel->AutoFilhos;
                                                for ($i=0;$i<count($autofilhossel);$i++)
                                                {
                                                ?>
                                                    <tr>
                                                        <td class="table_item0"><?=$autofilhossel[$i]->Nome?></td>
                                                        <td class="table_item0"><?=$autofilhossel[$i]->Acao?></td>
                                                    </tr>
                                                <?
                                                }
                                                ?>
                                                
                                                <tr>
                                                    <td><input type="text" name="filho_nome" style="width:100%"></td>
                                                    <td><select name="filho_codacao" style="width:100%">
                                                        <?$acoesfilhos->PrintCombo(0)?>
                                                    </select>
                                                    </td>
                                                    <td><a href="javascript:ongravarfilho()">[ok]</a></td>
                                                </tr>
                                            
                                            </table>
                                        
                                        </fieldset>
                                        <br><br>
                                        
                                        <a href="index2.php?acessar=cad_campos_acoes.php&codacao=<?=$codsel?>">[Campos desta ação]</a><br><br>
                                        <?}?>
                                        </td>
                                </tr>
                                <?if($codsel>0){?>
                                    <tr>
                                        <td class="field_caption">Mover ação para outro cliente<br>
                                            <select name="codcliente2" style="width:100%">
                                                <option value="-1">(sem cliente agrupador)</option>
                                                <?$clientes->PrintCombo($codcliente);?>
                                            </select>
                                            <br><br>
                                        </td>
                                    </tr>
                                <?}?>
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
                                $acoes->PrintCombo($codsel);
                                ?>
                                </select>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <?}?>
        </td>
    </tr>
</table>
</form>  
</body>
</html>