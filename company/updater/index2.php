<?

include_once('header_classes.php');

require "KoolSlideMenu/koolslidemenu.php";

if (!isset($_SESSION['objLogin']))
{
    print '1';
    exit;
    print "<script>document.location.href='index.php';</script>";
}                                                                         

if (strlen($acessar) == 0)
    $acessar = 'construir.php';

$menu = new CMenu();



if ($objLogin->iTipo == tipo_login_master || $objLogin->iTipo == tipo_login_usuario)

{

    $g = $menu->AddGrupo('Actions');

    $menu->AddOpcao($g, 'Manage Actions',   'cad_acoes.php');

    $menu->AddOpcao($g, 'Manage Action Fields',  'cad_campos_acoes.php');

}



if ($objLogin->iTipo == tipo_login_usuario)

{
    $g = $menu->AddGrupo('Customers');
   $menu->AddOpcao($g, 'Manage Customers',   'cad_clientes.php');

    $g = $menu->AddGrupo('WebSites');

    $menu->AddOpcao($g, 'Manage WebSites',   'cad_sites.php');

    $menu->AddOpcao($g, 'Edit WebSites',   'construir.php');

}

else if ($objLogin->iTipo == tipo_login_cliente)

{

    $g = $menu->AddGrupo('WebSites');

    $menu->AddOpcao($g, 'Edit My WebSites',   'construir.php');

}





if ($objLogin->iTipo != tipo_login_master)

{

    $g = $menu->AddGrupo('Security');

    $menu->AddOpcao($g, 'Change Password'   , 'mudar_senha.php');

}





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

<meta charset="UTF-8"/>

<title>Updater OnLine</title>
<link rel="stylesheet" type="text/css" href="visual.css">

<script>

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

    t.style.backgroundColor="#EEEEEE";

}



function onin(t)

{

    t.style.backgroundColor="#FFFFCC";

}



function acessar(p)

{

    if (p == 'acesso_sgd.php' || p == 'acesso_mail.php' || p == 'acesso_phoenix.php' || p.substring(0,4)=='http')

        window.open(p);

    else

        document.location.href = "index2.php?acessar="+p;

}



</script>
</head>

<body>

<table width="100%">

    <tr>

        <Td valign="bottom" width="60%">

            <span class="titulo">Updater OnLine</b></span> <br>

            <span class="common">Logged User: <b><?=$objLogin->szNome?>
&nbsp;<a href="index.php?sair=1">[Exit]</a>

        </td>

        <Td valign="middle" width="40%" align="right">&nbsp;</td>

    </tr>

</table>

<table width="100%">

    <tr valign="top">

        <td width="10%">

            <?=$ksm->Render()?>
  

        </td>

        <td width="90%">

            <?

            if ($acessar > '') include($acessar); else print '&nbsp;';

            ?>


        </td>

    </tr>

</table>



</body>

<script>

main_select(<?=$doautosel?>
);

</script>

</html>