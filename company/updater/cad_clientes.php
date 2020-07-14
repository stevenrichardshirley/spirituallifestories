<?
include_once('header_classes.php');

$clientes = new CClientes();
if ($acao == 'excluir')
{
    $clientes->DeleteFromDB($codsel);
    $acao = 'novo';
}
else if ($acao == 'gravar')
{
    $inf->CodigoUsuario = $objLogin->iCodigo;
    $inf->Nome  = $nome;
    $inf->Email = $email;
    $inf->Senha = $senha;
    $clientes->SaveToDB($inf, $codsel);
    $acao = 'novo';
}

$clientes->iCodUsuario = $objLogin->iCodigo;
$clientes->LoadFromDB();

if ($acao == 'novo')
    unset($nome, $email, $senha, $codsel);
else if ($acao == 'editar')
{
    $inf = $clientes->GetItem($codsel);
    $nome = $inf->Nome;
    $email = $inf->Email;
    $senha = $inf->Senha;
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
</script>
</head>
<body>
<form name="ff" method="post">
<input type="hidden" name="acao">
<table width="100%">
    <tr>
        <td class="cad_title">Cadastramento de clientes</td>
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
                                    <td class="field_caption">Nome do cliente<br>
                                        <input type="text" name="nome" style="width:100%" value="<?=$nome?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">E-mail<br>
                                        <input type="text" name="email" style="width:100%" value="<?=$email?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption">Senha de acesso<br>
                                        <input type="text" name="senha" style="width:100%" value="<?=$senha?>"></td>
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
                                $clientes->PrintCombo($codsel);
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