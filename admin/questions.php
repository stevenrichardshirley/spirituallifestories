<?
include_once('header_classes.php');
if (!isset($_SESSION['objLoginAdmin']))
{
    header('location: index.php');
    exit;
}                                                                         

$categories = new admin_data();
$categories->SetUp('question_categories','id','title');
$categories->szOrder = 'order';
$categories->LoadFromDB();

$colecao = new admin_data();
$colecao->SetUp('questions','id','content');
$colecao->szOrder = 'order';


$acao = $_POST['acao'];
$codsel = intval($_POST['codsel']);

$codcat = intval($_POST['codcat']);
$codsubcat = intval($_POST['codsubcat']);

if ($acao == 'changecat')
{
    unset($codsubcat);
}
else if ($acao == 'moveup')
{
    list($curorder, $curcat, $cursubcat) = mysql_fetch_row(mysql_query("SELECT `order`, category, subcategory  FROM questions WHERE id=$codsel"));
    $subfilter = $cursubcat>0?"subcategory={$cursubcat}":'0=0';
    list($otherid, $otherorder) = mysql_fetch_row(mysql_query("SELECT id, `order` FROM questions WHERE `order`<$curorder AND id<>$codsel AND category=$curcat AND $subfilter ORDER BY `order` DESC LIMIT 1"));
    mysql_query("UPDATE questions SET `order`=$curorder WHERE id=$otherid");
    mysql_query("UPDATE questions SET `order`=$otherorder WHERE id=$codsel");
    $acao = 'editar';
}
else if ($acao == 'movedown')
{
    list($curorder, $curcat, $cursubcat) = mysql_fetch_row(mysql_query("SELECT `order`, category, subcategory  FROM questions WHERE id=$codsel"));
    $subfilter = $cursubcat>0?"subcategory={$cursubcat}":'0=0';
    list($otherid, $otherorder) = mysql_fetch_row(mysql_query("SELECT id, `order` FROM questions WHERE `order`>$curorder AND id<>$codsel AND category=$curcat AND $subfilter ORDER BY `order` LIMIT 1"));
    mysql_query("UPDATE questions SET `order`=$curorder WHERE id=$otherid");
    mysql_query("UPDATE questions SET `order`=$otherorder WHERE id=$codsel");
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
    $inf->category = $codcat;
    $inf->subcategory = $codsubcat>0?$codsubcat:0;
    $inf->content  = mysql_real_escape_string($_POST['content']);
    if ($codsel == 0)
    {
        $subfilter = $codsubcat>0?"subcategory={$codsubcat}":'0=0';
        list($nextorder) = mysql_fetch_row(mysql_query("SELECT max(`order`) FROM questions WHERE category=$codcat AND $subfilter"));
        $inf->order = $nextorder+1;
    }
    // ***********************************
    
    $colecao->SaveToDB($inf, $codsel);
    $acao = 'novo';
}

if ($codcat > 0)
{
    $colecao->szFilters = "category=$codcat";
    if ($codsubcat > 0)
        $colecao->szFilters .= " AND subcategory=$codsubcat";
    $colecao->LoadFromDB();
}

if ($acao == 'novo')
{
    foreach ($_POST as $var=>$vv)
        if ($var != 'codcat' && $var != 'codsubcat')
            unset($$var);
}
else if ($acao == 'editar')
{
    if ($codsel > 0)
    {
        $inf = $colecao->GetItem($codsel);
        // DEFINICAO DOS CAMPOS
        // ***********************************
        $content = $inf->content;
        $order = $inf->order;
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
    if (document.ff.content.value.length == 0)
    {
        alert('Please, fill the required field');
        document.ff.content.focus();
        return false;
    }
    else
    {
        document.ff.acao.value = 'gravar';
        return true;
    }
}
function onchangecat()
{
    document.ff.acao.value = 'changecat';
    document.ff.submit();
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
        <td class="cad_title">Manage Questions</td>
    </tr>
    <tr>
        <td class="cad_area">
            
            <div style="padding:10px;background-color:#FFD" class="common">
                Please, select a category: 
                    <select name="codcat" onchange="onchangecat()">
                        <option value=0></option>
                        <?$categories->PrintCombo($codcat);?>
                    </select>
                    <?if ($codcat > 0){?>
                        <select name="codsubcat" onchange="document.ff.submit()">
                            <?
                            $subcategories= new admin_data();
                            $subcategories->SetUp('question_subcategories','id','title');
                            $subcategories->szOrder = 'order';
                            $subcategories->szFilters = "parent={$codcat}";
                            $subcategories->LoadFromDB();   
                            if (count($subcategories->mItems) == 0)
                            {
                                $codsubcat = -1;
                                print '<option value="-1" selected>(no subcategory)</option>';
                            }
                            else
                            {
                                print '<option value=0></option>';
                                $subcategories->PrintCombo($codsubcat);
                            }
                            ?>
                        </select>
                    <?} ?>
                    
            </div>
            <?
            if ($codsubcat != 0){
            ?>
                <Table width="100%" cellpadding=0 cellspacing=0>
                    <tr valign="top">
                        <td width="50%" >
                            <fieldset>
                                <legend class="group_label"><?=$codsel>0?'Editing':'New'?></legend>
                                <table width="100%" style="line-height:140%">
                                    <tr>
                                        <td class="field_caption" >Content<br />
                                            <textarea name="content" style="width:100%;resize:none;font-family:arial" rows=10><?=$content?></textarea></td>
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
                                <legend class="group_label">Questions</legend>
                                    <select name="codsel" onchange="onsel()" style="width:100%;" size=20>
                                    <?
                                    $colecao->PrintCombo($codsel);
                                    ?>
                                    </select>
                                    <?
                                    if($codsel>0)
                                    {
                                        $subfilter = $codsubcat>0?"subcategory={$codsubcat}":"0=0";
                                        list($prior) = mysql_fetch_row(mysql_query("SELECT id FROM questions WHERE id<>$codsel AND `order`<$order AND category=$codcat AND $subfilter"));
                                        list($next) = mysql_fetch_row(mysql_query("SELECT id FROM questions WHERE id<>$codsel AND `order`>$order AND category=$codcat AND $subfilter"));
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
            <?}?>
        </td>
    </tr>
</table>
</form>  
</body>
</html>