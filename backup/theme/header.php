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
    <meta charset="utf-8">

    <title>Spiritual Life Stories</title>
    <meta name="description" content="Spiritual Life Stories. Your Legacy Will Inspire and Enrich Generations to Come.">
    <meta name="author" content="Ben Jordan">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php echo theme::load('style'); ?>
    <link rel="shortcut icon" href="<?php echo utils::url('home'); ?>favicon.ico" />
    <link rel="stylesheet" href="<?php echo utils::url('theme'); ?>css/redactor.css" />
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif|Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="<?php echo utils::url('theme'); ?>js/redactor.min.js"></script>
    <script type="text/javascript">
        $(document).ready(
            function()
            {
                $('#content').redactor({
                    buttons: ['bold', 'italic', 'deleted', '|', 'unorderedlist', 'orderedlist'],
                });
                
                $('#contrib').redactor({
                    buttons: ['bold', 'italic', 'deleted', '|', 'unorderedlist', 'orderedlist']
                });
            }
        );
    </script>
  
</head>
<body>