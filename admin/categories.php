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

if ($acao == 'moveup')
{
    list($curorder) = mysql_fetch_row(mysql_query("SELECT `order` FROM question_categories WHERE id=$codsel"));
    list($otherid, $otherorder) = mysql_fetch_row(mysql_query("SELECT id, `order` FROM question_categories WHERE `order`<$curorder AND id<>$codsel ORDER BY `order` DESC LIMIT 1"));
    mysql_query("UPDATE question_categories SET `order`=$curorder WHERE id=$otherid");
    mysql_query("UPDATE question_categories SET `order`=$otherorder WHERE id=$codsel");
    $acao = 'editar';
}
else if ($acao == 'movedown')
{
    list($curorder) = mysql_fetch_row(mysql_query("SELECT `order` FROM question_categories WHERE id=$codsel"));
    list($otherid, $otherorder) = mysql_fetch_row(mysql_query("SELECT id, `order` FROM question_categories WHERE `order`>$curorder AND id<>$codsel ORDER BY `order` LIMIT 1"));
    mysql_query("UPDATE question_categories SET `order`=$curorder WHERE id=$otherid");
    mysql_query("UPDATE question_categories SET `order`=$otherorder WHERE id=$codsel");
    $acao = 'editar';
}
else if ($acao == 'excluir')
{
    $colecao->DeleteFromDB($codsel);
    $acao = 'novo';
}
else if ($acao == 'gravar')
{
    // DEFINICAO DOS CAMPOS
    // ***********************************
    $inf->title  = mysql_real_escape_string($_POST['title']);
    $inf->slug = mysql_real_escape_string($_POST['slug']);
    if ($codsel == 0)
    {
        list($nextorder) = mysql_fetch_row(mysql_query("SELECT max(`order`) FROM question_categories"));
        $inf->order = $nextorder+1;
    }
    if (isset($_FILES) && is_file($_FILES['cover']['tmp_name']) && strtolower(substr($_FILES['cover']['name'],-3)) == 'jpg')
    {
        $inf->image_url = mysql_real_escape_string($_FILES['cover']['name']);
        copy($_FILES['cover']['tmp_name'], '../theme/images/categories/'.$_FILES['cover']['name']);
    }
    // ***********************************
    
    $colecao->SaveToDB($inf, $codsel);
    $acao = 'novo';
}

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
function onmoveup()
{
    document.ff.acao.value = 'moveup';
    document.ff.submit();
}
function onmovedown()
{
    document.ff.acao.value = 'movedown';
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
        <td class="cad_title">Manage Categories</td>
    </tr>
    <tr>
        <td class="cad_area">
            <Table width="100%" cellpadding=0 cellspacing=0>
                <tr valign="top">
                    <td width="50%" >
                        <fieldset>
                            <legend class="group_label"><?=$codsel>0?'Editing':'New'?></legend>
                            <table width="100%" style="line-height:140%">
                                <tr>
                                    <td class="field_caption" >Title<br />
                                        <input type="text" name="title" style="width:100%" value="<?=$title?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption" >Slug<br />
                                        <input type="text" name="slug" style="width:100%" value="<?=$slug?>"></td>
                                </tr>
                                <tr>
                                    <td class="field_caption" >Cover (212px x 114px)<br />
                                        <input type="file" name="cover" style="width:100%">
                                    <?if($codsel>0 && strlen($cover)>0){?>    
                                        <img src="../theme/images/categories/<?=$cover?>" style="margin-top:10px">
                                    <?}?>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" cellpadding=0 cellspacing=0 style="margin-top:10px">
                                <tr>
                                    <td><input type="submit" value="Save" onclick="return ongravar()"></td>
                                    <td align="right"><?if($codsel>0){?><input type="submit" value="Remove" onclick="return onexcluir();"><input type="submit" value="New" onclick="return onnovo()"><?}?></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                    <td><div style="width:5px;"></div></td>
                    <td width="50%">
                        <fieldset>
                            <legend class="group_label">Categories</legend>
                                <select name="codsel" onchange="onsel()" style="width:100%;" size=20>
                                <?
                                $colecao->PrintCombo($codsel);
                                ?>
                                </select>
                                <?
                                if($codsel>0)
                                {
                                    list($prior) = mysql_fetch_row(mysql_query("SELECT id FROM question_categories WHERE id<>$codsel AND `order`<$order"));
                                    list($next) = mysql_fetch_row(mysql_query("SELECT id FROM question_categories WHERE id<>$codsel AND `order`>$order"));
                                    if ($prior>0){?>
                                        <a href="javascript: onmoveup()">move up</a>
                                    <?}
                                    if ($next>0){?>
                                        <a href="javascript: onmovedown()">move down</a>
                                    <?}
                                }?>
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