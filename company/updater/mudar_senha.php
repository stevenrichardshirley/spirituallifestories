<?include_once('header_classes.php');if ($acao == 'gravar'){    if ($atual == $objLogin->szSenha)    {        if ($objLogin->iTipo == tipo_login_usuario)            mysql_query("UPDATE Usuarios SET Senha='$nova' WHERE CodigoUsuario=$objLogin->iCodigo");        else if ($objLogin->iTipo == tipo_login_cliente)            mysql_query("UPDATE Usuarios_Clientes SET Senha='$nova' WHERE CodigoCliente=$objLogin->iCodCliente");                $msg = 'Senha alterada com sucesso!';    }    else        $msg = 'Senha de acesso inválida. Tente novamente';}?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html><head><link rel="stylesheet" type="text/css" href="visual.css"><script>function ongravar(){    // relação de campos obrigatórios    if (document.ff.atual.value.length == 0)    {        alert('Por favor, preencha o campo obrigatório!');        document.ff.atual.focus();        return false;    }    else if (document.ff.nova.value.length == 0)    {        alert('Por favor, preencha o campo obrigatório!');        document.ff.nova.focus();        return false;    }    else if (document.ff.nova2.value.length == 0)    {        alert('Por favor, preencha o campo obrigatório!');        document.ff.nova2.focus();        return false;    }    else if (document.ff.nova2.value != document.ff.nova.value)    {        alert('Redigite sua senha corretamente.');        document.ff.nova2.focus();        return false;    }    else    {        document.ff.acao.value = 'gravar';        return true;    }}</script></head><body><form name="ff" method="post"><input type="hidden" name="acao"><table width="100%">    <tr>        <td class="cad_title">Modificação de Senha de acesso</td>    </tr>    <tr>        <td >            <Table width="200px" align="left" cellpadding=0 cellspacing=0>                <tr valign="top">                    <td width="50%" >                        <table width="100%">                            <tr>                                <td class="field_caption">Senha atual</td>                            </tr>                            <tr>                                <td class="field_caption"><input type="password" name="atual" style="width:100%"></td>                            </tr>                            <tr>                                <td class="field_caption">Nova senha</td>                            </tr>                            <tr>                                <td class="field_caption"><input type="password" name="nova" style="width:100%"></td>                            </tr>                            <tr>                                <td class="field_caption">Redigite sua nova senha</td>                            </tr>                            <tr>                                <td class="field_caption"><input type="password" name="nova2" style="width:100%"></td>                            </tr>                            <tr>                                <td align="center">                                    <input type="submit" value="Alterar senha" onclick="return ongravar()">                                </td>                            </tr>                        </table>                    </td>                </tr>            </table>        </td>    </tr></table><p align="center" class="aviso"><?=$msg?></p></form></body></html>