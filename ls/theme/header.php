<?php
    $theme = new theme();
    $user = user::identify();
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?3gHTjGRdbQJT7bKeDXQhebWkq1i9bzq7";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zopim Live Chat Script-->


    <meta charset="utf-8">
    
    
    
    <meta property="og:site_name" content="Life Stories" />
    <meta property="og:image" content="http://<?=$_SERVER['HTTP_HOST']?><?=ROOT_DIRECTORY?>/theme/images/fblogo.png" />    
    <?
    $fb_title = isset($GLOBALS['fb_title'])?$GLOBALS['fb_title']:'Life Stories';
    $fb_description = isset($GLOBALS['fb_description'])?$GLOBALS['fb_description']:'Life Stories. Your Legacy Will Inspire and Enrich Generations to Come.';
    ?>
    <meta property="og:title" content="<?=$fb_title?>" />                         
    <meta property="og:description" content="<?=$fb_description?>" />    
    <?/*<meta property="fb:app_id" content="[FB_APP_ID]" />*/?>
    
    
    <title>Life Stories</title>
    <meta name="description" content="Life Stories. Your Legacy Will Inspire and Enrich Generations to Come.">
    <meta name="author" content="Life Stories">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php echo theme::load('style'); ?>
    <link rel="shortcut icon" href="<?php echo utils::url('home'); ?>favicon.png" />
    <link rel="stylesheet" href="<?php echo utils::url('theme'); ?>css/redactor.css" />
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif|Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
    
    <?/*<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>*/?>

    <link rel="stylesheet" href="<?php echo utils::url('theme'); ?>js/popup/jquery.fancybox.css" />
    <script src="<?php echo utils::url('theme'); ?>js/popup/jquery-1.9.0.min.js"></script>
    <script src="<?php echo utils::url('theme'); ?>js/popup/jquery.fancybox.js"></script>
    
    
    <script src="<?php echo utils::url('theme'); ?>js/redactor.min.js"></script>
    
    
    <script type="text/javascript">
        $(document).ready(
            function()
            {
                /*$('#content').redactor({
                    keyupCallback: function(e){
                        onwaitAutoSave();
                    },
                    buttons: ['bold', 'italic', 'deleted', '|', 'unorderedlist', 'orderedlist'],
                });
                
                $('#contrib').redactor({
                    buttons: ['bold', 'italic', 'deleted', '|', 'unorderedlist', 'orderedlist']
                });
                */
            }
        );
    </script>
  
</head>
<body>