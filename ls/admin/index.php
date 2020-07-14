<?
include_once('header_classes.php');

if ($_GET['exit'] == 1)
    unset($_SESSION['objLoginAdmin']);
  
if (isset($_POST['user']))
{
    unset($_SESSION['objLoginAdmin']);
    $objLoginAdmin = new admin_login();
    if ($objLoginAdmin->DoLogin($_POST['user'], $_POST['pass']))
    {
        $_SESSION['objLoginAdmin'] = $objLoginAdmin;
        header('Location: index2.php');
        exit;
    }
    else
        $msg = "Error: invalid user or password";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Life Stories - Administration</title>
<link rel="stylesheet" type="text/css" href="visual.css?r=<?=rand(0,9999)?>">
</head>
<body onload="document.ff.user.focus()" bgcolor="#FFF">
<form name="ff" method="post">
    <center><img src="../theme/images/life_stories_logo.png"><Br><br>
    <table width="250px" style="border:1px solid #999999;background-color:white">
        <tr>
            <td align="center" class="titulo_caixa">Administration Area</td>
        </tr>
        <tr>
            <tD class="field_caption">Username</td>
        </tr>
        <tr>
            <td><input type="text" name="user" style="width:100%" value="<?=$user?>"></td>
        </tr>
        <tr>
            <tD class="field_caption">Password</td>
        </tr>
        <tr>
            <td><input type="password" name="pass" style="width:100%" value="<?=$pass?>"></td>
        </tr>
        <tr>
            <td align="right"><input type="submit" value="Confirm"></td>
        </tr>
    </table>
    <p class="common" style="color:#FF0000"><?=$msg?></p>
</form>

</body>
</html>