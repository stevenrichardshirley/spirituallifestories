<?
include_once('header_classes.php');
if (!isset($_SESSION['objLoginAdmin']))
{
    header('location: index.php');
    exit;
}                                                                         

$colecao = new admin_data();
$colecao->SetUp('question_categories','id','title');
$colecao->szOrder = 'order';


$acao = $_POST['acao'];
$codsel = intval($_POST['codsel']);

$colecao->LoadFromDB();

if ($acao == 'novo')
{
    foreach ($_POST as $var=>$vv)
        unset($$var);
}
else if ($acao == 'editar')
{
    if ($codsel > 0)
    {
        $inf = $colecao->GetItem($codsel);
        // DEFINICAO DOS CAMPOS
        // ***********************************
        $title = $inf->title;
        $slug = $inf->slug;
        $order = $inf->order;
        $cover = $inf->image_url;
        
        // ***********************************
    }
    else
        $acao = 'novo';
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
    if (document.ff.title.value.length == 0)
    {
        alert('Please, fill the required field');
        document.ff.title.focus();
        return false;
    }
    else if (document.ff.slug.value.length == 0)
    {
        alert('Please, fill the required field');
        document.ff.slug.focus();
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
    if (confirm('Are you sure?'))
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
<form name="ff" method="post" enctype="multipart/form-data">
<input type="hidden" name="acao" >
<table width="100%" cellpadding=0 cellspacing=0>
    <tr>
        <td class="cad_title">Manage Users</td>
    </tr>
    <tr>
        <td class="cad_area">
            <Table width="100%" cellpadding=0 cellspacing=0>
                <tr valign="top">
                    <td width="30%" valign="middle" class="table_title_light">Name</td>
                    <td width="30%" valign="middle" class="table_title_light">E-mail</td>
                    <td width="10%" valign="middle" class="table_title_light"><center>Answered<br />questions</center></td>
                    <td width="10%" valign="middle" class="table_title_light"><center>Last login</center></td>
                    <td width="10%" valign="middle" class="table_title_light"><center>Registration<br />date</center></td>
                    <td width="10%" valign="middle" class="table_title_light"><center>Status</center></td>
                    <td width="1%" valign="middle" class="table_title_light">Actions</td>
                    
                </tr>
                <?
                
                $forder = isset($_GET['forder'])?$_GET['forder']:'u.first_name, u.last_name';
                $qr = mysql_query("SELECT u.*, (select count(a.id) from question_answers a where a.user_id=u.user_id) as answered FROM users u ORDER BY $forder");
                
                $qt = $ii = 0;
                while ($obj = mysql_fetch_object($qr))
                {
                    if (strlen($obj->activation_limit) == 10)
                    {
                        
                    }
                    else
                    {
                        list($y,$m,$d) = explode('-', $obj->free_limit);
                        $diff = round( (mktime(12,0,0,$m,$d,$y)-mktime(12,0,0))/86400,0);
                        if ($diff > 0)
                            $status = $diff." day(s) to test";
                        else
                            $status = '<font color="red">Tested expired</font>';
                    }
                ?>
                    <tr>
                        <Td class="table_item<?=$ii?>"><?=$obj->first_name?> <?=$obj->last_name?></td>
                        <Td class="table_item<?=$ii?>"><a href="mailto:<?=$obj->email?>" target="_blank"><?=$obj->email?></a></td>
                        <Td class="table_item<?=$ii?>" style="text-align:center"><?=$obj->answered?></td>
                        <Td class="table_item<?=$ii?>" style="text-align:center"><?=substr($obj->last_login,0,10)?><br /><Font size=1><?=substr($obj->last_login,11,5)?></td>
                        <Td class="table_item<?=$ii?>" style="text-align:center"><?=$obj->date?></td>
                        <Td class="table_item<?=$ii?>" style="text-align:center"><?=$status?></td>
                        <Td class="table_item<?=$ii?>" ><select><option>Actions</option>
                            <option>Remove</option>
                            <option>Activate</option>
                            <option>Reset password</option>
                        </select></td>
                    </tr>
                <?
                    $ii = $ii==1?0:1;
                    $qt++;
                }
                ?>
            </table>
            <br /><br /><b class="common">Registered users: <?=$qt?></b>
        </td>
    </tr>
</table>
</form>  
</body>
</html>