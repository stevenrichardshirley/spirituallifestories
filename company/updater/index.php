<?

include_once('header_classes.php');


if ($sair == 1)
    unset($_SESSION["objLogin"]);

if ($acao == 'logar')

{

    // tentando logar

    if (isset($_SESSION["objLogin"]))
        unset($_SESSION["objLogin"]);

    $objLogin = new CLogin();

    if ($objLogin->DoLogin($email, $senha))
    {
        
        $_SESSION["objLogin"] = $objLogin;

        header('location: index2.php');
        exit;

    }

    else

        $msg = "Username and/or Password invalid(s)!";

}

else if ($acao == 'cadastrar')

{   

    // cadastrando usuário

    $qt = mysql_fetch_row(mysql_query("SELECT count(*) FROM Usuarios WHERE Email='$cademail'"));

    if ($qt[0] > 0)

        $msgcad = 'Erro: Endereço de e-mail já cadastrado';

    else

    {

        $inf->Nome  = $nome;

        $inf->Email = $cademail;

        $inf->Senha = $cadsenha;

        $inf->CadastroEm = date('Y-m-d H:i:s');

        $usuarios = new CUsuarios();

        $usuarios->SaveToDB($inf);

        

        // login

        if (isset($_SESSION["objLogin"]))
            unset($_SESSION["objLogin"]);

        $objLogin = new CLogin();

        $objLogin->DoLogin($cademail, $cadsenha);

        $_SESSION["objLogin"] = $objLogin;

        header('Location: index2.php');

        

    }

}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Updater OnLine</title>

<link rel="stylesheet" type="text/css" href="visual.css">

<script>

    function onAcessar()

    {

        document.ff.acao.value = 'logar';

        return true;

    }

    function onCadastrar()

    {

        if (document.ff.nome.value.length > 0 &&

            document.ff.cademail.value.length > 0 &&

            document.ff.cadsenha.value.length > 0)

        {

            document.ff.acao.value = 'cadastrar';

            document.ff.submit();

        }    

        else

            return false;

    }

</script

</head>

<body onload="document.ff.usuario.focus()" bgcolor="#DDDDDD">

<form name="ff" method="post">

    <input type="hidden" name="acao">

    

    <center>

    <div style="text-align:left; width:500px">

    



    <br><br><span class="titulo">Updater OnLine</span><bR>

    <span class="field_caption">

    CMS on-line

    <br><br><br><br>

    

    <table width="100%" style="border:1px solid #999999;background-color:#FFFFFF;padding:10px" bgcolor="white">

        <tr>

            <tD class="field_caption">Login</td>

        </tr>

        <tr>

            <td><input type="text" name="email" style="width:100%" value="<?=$email?>"></td>

        </tr>

        <tr>

            <tD class="field_caption">Password</td>

        </tr>

        <tr>

            <td><input type="password" name="senha" style="width:100%" value="<?=$senha?>"></td>

        </tr>

        <tr>

            <td align="right"><input type="submit" value="Confirm" onclick="return onAcessar();"></td>

        </tr>

    </table>

    <p class="aviso"><?=$msg?></p>

    

        </div></center>



    

</form>



</body>

</html>