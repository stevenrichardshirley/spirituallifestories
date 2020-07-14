<?
include_once('header_classes.php');
if (!isset($_SESSION['objLoginAdmin']))
{
    header('location: index.php');
    exit;
}                                                                         
require "KoolSlideMenu/koolslidemenu.php";


$menu = new admin_menu();

$g = $menu->AddGrupo('Questions');
$menu->AddOpcao($g, 'Categories', 'categories.php');
$menu->AddOpcao($g, 'Sub-Categories', 'subcategories.php');
$menu->AddOpcao($g, 'Questions', 'questions.php');

$g = $menu->AddGrupo('Users');
$menu->AddOpcao($g, 'Users', 'cad_associacoes.php');
$menu->AddOpcao($g, 'Books', 'con_associacoes.php');

$g = $menu->AddGrupo('Options');
$menu->AddOpcao($g, 'Logout'   , 'exit');

// carregadno menu loco
$ksm = new KoolSlideMenu("ksm");
$ksm->scriptFolder =  "KoolSlideMenu";   

for ($i=0;$i<count($menu->mGrupos);$i++)
{
    $ksm->addParent("root",$i,$menu->mGrupos[$i]->Grupo,null,true);
    for ($j=0;$j<count($menu->mGrupos[$i]->mItems);$j++)
    {
        $ksm->addChild($i, ($i).'-'.($j), $menu->mGrupos[$i]->mItems[$j]->Botao, 
            'javascript:acessar("'.$menu->mGrupos[$i]->mItems[$j]->Pagina.'")');
        if ($menu->mGrupos[$i]->mItems[$j]->Pagina == $acessar)
            $doautosel = "'".($i).'-'.($j)."','$i'";
    }
}
    
$ksm->singleExpand = false;
$ksm->width="200px";
$ksm->styleFolder = "KoolSlideMenu/styles/apple";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Spiritual Life Stories - Administration</title>
<link rel="stylesheet" type="text/css" href="visual.css">

<script src="includes/jquery-1.10.2.js"></script>
<script src="includes/jquery.mask.min.js"></script>

<script>
$(document).ready(function(){
    $('.datas').mask('99/99/9999');
});
function expand( id ){
    if (ksm.getItem( id ).isExpanded())
    {
         ksm.getItem( id ).collapse();
    }else{
        ksm.getItem( id ).expand();
    }    
}
function main_select( id  , parent ){
    ksm.getItem( parent ).expand();
    ksm.getItem( id ).select();
}

function onsug()
{
    var p = document.getElementById('psugestoes');
    p.style.display = 'block';  
}
function onout(t)
{
    t.style.backgroundColor="#D5DDFF";
}

function onin(t)
{
    t.style.backgroundColor="#FFFFCC";
}

function acessar(p)
{
    if (p == 'exit')
        document.location.href = "index.php?exit=1";
    else if (p == 'cad_ficha.php')
        window.open('cad_ficha.php');
    else
        document.location.href = "index2.php?acessar="+p;
}

</script>
</head>
<body bgcolor="#EEEEEE">

<Table width="100%" style="padding:0px" cellpadding=5 cellspacing=5 bgcolor="#FFF">
    <tr>
        <td><img src="../theme/images/slslogo.png" height=40></td>
        <td align="left" style="color:#555" class="titulo">&nbsp;
        <td align="right" style="color:#888" class="field_caption" valign="middle">Administrator</td>
    </tr>
</table>
<table width="100%" >
    <tr valign="top">
        <td width="10%">
            <?=$ksm->Render()?>
        </td>
        <td width="90%">
            <?
            if ($_GET['acessar'] > '') include($_GET['acessar']); 
            ?>
        </td>
    </tr>
</table>
</body>
<script>
main_select(<?=$doautosel?>);
</script>

</html>