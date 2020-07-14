<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>Life Stories - Maintenance Mode</title>
    <meta name="description" content="Life Stories. Your Legacy Will Inspire and Enrich Generations to Come.">
    <meta name="author" content="BigBadCollab">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php echo theme::load('style'); ?>
    <link rel="shortcut icon" href="<?php echo utils::url('home'); ?>favicon.ico" />
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script src="js/libs/modernizr-2.0.6.min.js"></script>
    
    <script type="text/javascript" src="/summit/ckeditor/ckeditor.js"></script>
    
</head>
<body>

<div id="container">
    <header>
        <div class="width_setter">
            <div id="logo"><h1><a href="<?php echo utils::url('home'); ?>">Life Stories</a></h1></div>
        </div>
    </header>

    <div id="main">
    
    <div class="width_setter">
    
    <h2>Maintenance Mode</h2>
    <p>Currently updating our site. Check back soon for the full site.</p>
    
    </div> <!-- width_setter -->
</div> <!-- main -->

