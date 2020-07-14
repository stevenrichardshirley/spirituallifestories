<?
include_once('header_classes.php');

$produto = new CItensSite();
$produto->iCodNode = $id;
$produto->LoadFromDB();

$info = new CItensSite();
$info->iCodParentNode = $id;
$info->LoadFromDB();


print_r($info->mItems);
exit;

$f = new CFuncoes();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>HTH</title>

    <script type="text/javascript" src="popupnovo/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="popupnovo/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="popupnovo/jquery.fancybox.js?v=2.1.4"></script>
    <link rel="stylesheet" type="text/css" href="popupnovo/jquery.fancybox.css?v=2.1.4" media="screen" />    

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="funcoes.js?r=<?=rand(0,9999)?>" charset="iso-8859-1"></script>

<style>
.esqueci{font-family:arial;font-size:11px;color:#AAA;}
</style>

<script>
$(document).ready(function(){
});
</script>

</head>
<body style="margin:0px;padding:0px;">


<?
include_once('header.php');
?>

<div style="position:relative;height:10px">

</div>

<?
include_once('header_barra.php');
?>

<div style="position:relative;background:url('images/produto_gomo.jpg') repeat-x;background-position:left top;border-bottom:1px solid #CCC">

    <div style="position:relative;width:948px;margin-left:-474px;left:50%;height:363px;">
                                                         
        <div style="position:absolute;left:20px;top:80px">
            <div style="margin-bottom:10px;font-family:arial;letter-spacing:-1px; font-size:25px;font-weight:bold;color:#444"><?=$produto->mItems[0]->Idiomas[1]->Nome?></div>
            <div style="margin-bottom:10px;font-family:arial;letter-spacing:0px;font-size:15px;color:#aaa"><?=$produto->mItems[0]->Idiomas[1]->Texto?></div>
            <div style="margin-bottom:10px;margin-left:-6px">
                <?if($produto->GetCampoValue(0,'residencia')=='on'){?>
                    <img src="images/produto_residencias.png"><br>
                <?}?>
                <?if($produto->GetCampoValue(0,'clubes')=='on'){?>
                    <img src="images/produto_clubes.png">
                <?}?>
            </div> 
        </div>

        <div style="position:absolute;top:207px;left:-4px;"><img src="images/produto_<?=$produto->GetCampoValue(0,'fase')?>.png"></div>

        <div style="position:absolute;bottom:-9px;left:15px;"><a href="updater/site_files/<?=$produto->GetCampoValue(0,'pdf')?>" target="_blank"><img src="images/produto_pdf.png" border=0></a></div>

        <div style="position:absolute;bottom:0px;right:15px;"><img src="updater/site_files/<?=$produto->GetCampoValue(0,'imagem')?>" border=0></div>

        
    </div>
    
</div>

<?for($i=0;$i<count($info->mItems);$i++){?>

    <div style="position:relative;background:url('images/home_gomo.jpg') repeat-x;background-position:left top;">

        <div style="position:relative;width:948px;margin-left:-474px;left:50%;height:auto;overflow:hidden;padding-bottom:50px">

            <div style="float:left;margin-left:12px;margin-top:50px;font-family:arial;line-height:150%;color:#555;width:440px;">
                <font style="font-size:30px"><?=$info->mItems[$i]->Idiomas[1]->Nome?></font><br><Br>
                
                <font style="font-size:12px;">
                    <?=$info->mItems[$i]->Idiomas[1]->Texto?>
                </font>
            </div>

            <div style="float:left;margin-left:30px;margin-top:60px;width:464px">
                <?
                $vimg = $info->GetCampoValue($i, 'imagem');
                $img = 'updater/site_files/'.$vimg;
                if (strlen($vimg) > 0 && file_exists($img)){?>
                    <img src="<?=$img?>" width=464>
                <?}?>
            </div>
        
        
        </div>
        
    </div>
<?}?>

</body>
</html>
