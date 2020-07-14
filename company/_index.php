<?
	include("updater/header_classes.php");
	require_once 'mobilePHP/Mobile_Detect.php';
	$detect = new Mobile_Detect;
	
	//Page
	$page = new CItensSite();
	$page->iCodNode = 1130;
	$page->LoadFromDB();
	
	//otherSites
	$splashes = new CItensSite();
	$splashes->iCodParentNode = 1157;
	$splashes->LoadFromDB();
	
	//process
	$process = new CItensSite();
	$process->iCodNode = 1131;
	$process->LoadFromDB();
	
	//service
	$service = new CItensSite();
	$service->iCodParentNode = 1132;
	$service->LoadFromDB();
	
	//Portfolio
	//Memoirs
	$portfolioMemoirs = new CItensSite();
	$portfolioMemoirs->iCodParentNode = 1137;
	$portfolioMemoirs->LoadFromDB();
	
	//Family
	$portfolioFamily = new CItensSite();
	$portfolioFamily->iCodParentNode = 1138;
	$portfolioFamily->LoadFromDB();
	
	//Brochure
	$brochure = new CItensSite();
	$brochure->iCodNode = 1139;
	$brochure->LoadFromDB();
	
	//about Us
	$about = new CItensSite();
	$about->iCodNode = 1140;
	$about->LoadFromDB();
	
	//About Us Team
	$aboutTeam = new CItensSite();
	$aboutTeam->iCodParentNode = 1140;
	$aboutTeam->LoadFromDB();
	
	//otherSites
	$otherSites = new CItensSite();
	$otherSites->iCodParentNode = 1143;
	$otherSites->LoadFromDB();
	
	if($_POST){
		$to = $page->GetCampoValue(0,'form_email');
		$email_subject = "Contact from LifeStoriesCompany Website";
		$email_body = "You have received a new message from the LifeStoriesCompany.com contact form: <br /><br />Name: $name <br />Email: <a href='mailto:$email'>$email</a> <br />Telephone: $telephone <br />Message: $message";
        require_once 'phpmailer/class.phpmailer.php';
        $mm = new PHPMailer();
        $mm->IsSMTP();
        $mm->AddAddress($to);
        $mm->Subject = $email_subject;
        $mm->MsgHTML($email_body);
        $mm->Send();
		echo "<script>alert('Message sent successfully!');</script>";
	}
	//echo "<pre>";
	//	print_r($aboutTeam->mItems);
	//echo "</pre>";
?>
<!DOCTYPE html>
<html>
    <head>
		<meta name="viewport" content="width=device-width">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
        <meta charset="UTF-8">
		<link rel="icon" href="images/iconTitle.png" type="image/png">
		<title>LifeStoriesCompany</title>
		
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!--fancybox-->
        <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
        <link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
        <script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
        <link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
        <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
        <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
        <link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
        <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
				$( ".splashButton" ).each(function(index, data) {
						var idA = $(this).attr('href').split('javascript:')[1];
						var idButton = "#"+$(this).attr('id');
						$(idButton).attr('data-toggle', $(idA).attr('data-toggle'));
						$(idButton).attr('data-target', $(idA).attr('data-target'));
						console.log("$("+idButton+").attr('data-target', $("+idA+").attr('data-target'))");
						var idButton = '';
						var idA = '';
				});
                $(".fancybox").fancybox({
                    openEffect: 'none',
                    closeEffect: 'none'
				});
                $(".scroll").click(function (event) {
					$('.navbar-collapse').css('height', '1px');
					$('.navbar-collapse').attr('aria-expanded', 'false');
					$('.navbar-collapse').removeClass('in');
					$('.menu').addClass('navbar navbar-default navbar-fixed-top');
                    event.preventDefault();
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
						$('html,body').animate({scrollTop: $(this.hash).offset().top-$('#menu-mobile').height()}, 1000);
					else
						$('html,body').animate({scrollTop: $(this.hash).offset().top-$('#menu').height()}, 1000);
				});
                $(window).bind('scroll', function () {
                    if ($(window).scrollTop() > 50) {
                        $('.menu').addClass('navbar navbar-default navbar-fixed-top');
						} else {
                        $('.menu').removeClass('navbar navbar-default navbar-fixed-top');
					}
				});
			});
            function houverFunctionAboutOn(key) {
                $('.about_brief_text_' + key).css('color', 'white');
				$('.img_' + key).css('border', '5px solid white');
				
			}
            function houverFunctionAboutOf(key) {
                $('.about_brief_text_' + key).css('color', '#9ac8e5');
				$('.img_' + key).css('border', '5px solid #9ac8e5');
			}
		</script>
		<style>
			.bg-img::before {
				content: '';
				position: absolute;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				background-image: -webkit-radial-gradient(rgba(0, 0, 0, 0),rgb(0, 0, 0));
				opacity: .6;
			}
		</style>
	</head>
    <body>
		<nav class="navbar navbar-default navbar-fixed-top" style="border: 0px solid white;" id="menu-mobile">
			<div class="container">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-6" aria-expanded="false" style="margin-left: 0px;float: left;">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<div class="header-mobile" style="position: relative;float: right;padding: 0px 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;">
							<p id="telephone-header-mobile" style="color: white;"><?= $page->GetCampoValue(0, 'telephone') ?></p>
							<a href="mailto:<?= $page->GetCampoValue(0, 'contact_email') ?>"><p id="email-header-mobile" style="margin-top: -10px;"><?= $page->GetCampoValue(0, 'contact_email') ?></p></a>
						</div>
					</div>
					<div id="overflow" style="overflow: auto;width: 100%;max-height: 100%; position: fixed; background-color:#2C8DCE;margin: 0px 0px 0px -30px">
						<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-6" aria-expanded="false">
							<ul class="nav navbar-nav">
								<li><a href="#process" class="scroll">Process</a></li>
								<li><a href="#services" class="scroll">Services</a></li>
								<li><a href="#portfolio" class="scroll">Portfolio</a></li>
								<li><a href="#brochure" class="scroll">Brochure</a></li>
								<li><a href="#about" class="scroll">About Us</a></li>
								<li><a href="#contact" class="scroll">Contact</a></li>
								<li><a href="#sites" class="scroll">Our Other Sites</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<div class="container header">
            <center><img class="picture" src="images/logo.png" alt=""></center>
            <div class="header-information">
                <p class="telephone-header"><?= $page->GetCampoValue(0, 'telephone') ?></p>
                <a tabindex="-1" href="mailto:<?= $page->GetCampoValue(0, 'contact_email') ?>"><p class="email-header"><?= $page->GetCampoValue(0, 'contact_email') ?></p></a>
			</div>
		</div>
		<div id="menu" class="menu" style="border: 0px solid white;">
			<div class="container">
				<ul class="list-inline nav nav-pills">
					<li><a href="#process" class="scroll">Process</a></li>
					<li><a href="#services" class="scroll">Services</a></li>
					<li><a href="#testimonials" class="scroll">Testimonials</a></li>
					<li><a href="#portfolio" class="scroll">Portfolio</a></li>
					<li><a href="#brochure" class="scroll">Brochure</a></li>
					<li><a href="#about" class="scroll">About Us</a></li>
					<li><a href="#contact" class="scroll">Contact</a></li>
					<li><a href="#sites" class="scroll">Our Other Sites</a></li>
				</ul>
			</div>
		</div>
		<div id="FirstSlide" class="carousel slide" data-ride="carousel" data-interval="10000">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?$cont = 1;
					foreach($splashes->mItems as $key => $val){?>
					<li data-target="#FirstSlide" data-slide-to="<?= $key ?>" class="<?= ($key < 1 ? 'active' : '') ?> exception"></li>
				<?}?>
			</ol>
            <div class="carousel-inner" role="listbox" width="10px" height="10px">
                <?
					$cont = 0;
					foreach($splashes->mItems as $key => $val){
					?>
					<div class="item <?= ($key < 1 ? 'active' : '') ?>" height="500px">
						<?if($splashes->GetCampoValue($key, 'type') == 'one-photo'){?>
							<div class="col-md-12 bg-img" style="position: relative; top: 30%;background-image: url('updater/site_files/<?= $splashes->GetCampoValue($key, 'image1') ?>');background-position: center center; border-right: 2px solid #137FC8;opacity: 1;background-size: auto 100%;">
								<div class="carousel-indicators" style="position: relative; top: 30%;">
									<h1 style="position: relative; top: 30%;"><?= str_replace(chr(13), "<br>", $splashes->GetCampoValue($key, 'wording1')) ?></h1>
									<?if($splashes->GetCampoValue($key, 'button1_text')){?>
										<a class="btn btn-lg btn-info <?=(strpos($splashes->GetCampoValue($key, 'button1_link'), 'javascript')===false?'scroll':'')?>" href="<?=$splashes->GetCampoValue($key, 'button1_link')?>"><?= $splashes->GetCampoValue($key, 'button1_text') ?></a>
									<?}?>
								</div>
							</div>
							<?}else{?>
							<div class="col-md-6 bg-img bg-img-two" style="background-image: url('updater/site_files/<?= $splashes->GetCampoValue($key, 'image1') ?>');background-position: center center; border-right: 2px solid #137FC8;background-size: auto 100%;">
								<div class="carousel-indicators" style="position: relative; top: 30%;">
									<h1><?= str_replace(chr(13), "<br>", $splashes->GetCampoValue($key, 'wording1')) ?></h1>
								</div>
								<div class="carousel-indicators" style="position: relative;top: 30%;">
									<?if($splashes->GetCampoValue($key, 'button1_text')){?>
										<a class="btn btn-lg btn-info <?=(strpos($splashes->GetCampoValue($key, 'button1_link'), 'javascript')===false?'scroll':'splashButton" id="button_'.$cont++)?>"  style="background-color: #308E2B; border: 0px solid #37B131;" href="<?= $splashes->GetCampoValue($key, 'button1_link') ?>"><?= $splashes->GetCampoValue($key, 'button1_text') ?></a>
									<?}?>
								</div>
							</div>
							<div class="col-md-6 bg-img bg-img-two" style="background: url('updater/site_files/<?= $splashes->GetCampoValue($key, 'image2') ?>') center center no-repeat;background-size: auto 100%;" id="teste">
								<div class="carousel-indicators" style="position: relative; top: 30%;">
									<h1><?= str_replace(chr(13), "<br>", $splashes->GetCampoValue($key, 'wording2')) ?></h1>
								</div>
								<div class="carousel-indicators" style="position: relative;top: 30%;">
									<?if($splashes->GetCampoValue($key, 'button2_text')){?>
										<a class="btn btn-lg btn-info <?=(strpos($splashes->GetCampoValue($key, 'button2_link'), 'javascript')===false?'scroll':'splashButton" id="button_'.$cont++)?>" style="background-color: #308E2B; border: 0px solid #37B131;" href="<?= $splashes->GetCampoValue($key, 'button2_link') ?>"><?= $splashes->GetCampoValue($key, 'button2_text') ?></a>
									<?}?>
								</div>
							</div>
						<?}?>
					</div>
				<?}?>
			</div>
			<!-- Left and right controls -->
            <a class="left carousel-control" href="#FirstSlide" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
			</a>
            <a class="right carousel-control" href="#FirstSlide" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
			</a>
		</div>
        <div id="process">
            <div class="container">
                <h1><?= $process->GetCampoValue(0, 'title') ?></h1>
                <img src="updater/site_files/<?= $process->GetCampoValue(0, 'image') ?>" alt="" width="300" class="picture">
                <p><?= $process->mItems[0]->Idiomas[1]->Texto ?></p>
			</div>
		</div>
		<div id="services">
			<div class="container">
				<h1>Services</h1>
				<p align="right" class="p">-- Click on icons to see more</p>
				<div class="row">
					<?php $idx = 0;
                    foreach ($service->mItems as $key => $value) {?>
						<div class="modal fade modal-<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<?= $service->GetCampoValue($key, 'content') ?>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<a href="#" data-toggle="modal" data-target=".modal-<?= $key ?>" id="service_link_<?=$idx?>">
								<img src="updater/site_files/<?= $service->GetCampoValue($key, 'icon') ?>" alt="">
							</a>
							<h3><?= $service->mItems[$key]->Idiomas[1]->Nome ?></h3>
						</div>
					<?php
                    $idx++;
                    } ?>
				</div>
			</div>
		</div>
		<div id="testimonials">
			<div class="container">
				<h1>Testimonials</h1>
				<img src="images/abreaspas.png" alt="" id="aspasLeft">
				<div id="SecondSlide" class="carousel slide" data-ride="carousel" data-interval="false">
					<ol class="carousel-indicators indicators-second-slide" id="indicators-second-slide">
						<?
							$cont = 0;
							foreach ($portfolioMemoirs->mItems as $key => $value) {
							?>
							<li data-target="#SecondSlide" data-slide-to="<?= $cont ?>" <?= ($cont < 1 ? 'class="active"' : '') ?>>
								<?$cont++;?>
								<?= $cont ?>
							</li>
							<?
							}
						?>
					</ol>
					<div class="carousel-inner second-slide-items" role="listbox">
						<?
							$cont = 0;
							foreach ($portfolioMemoirs->mItems as $key => $value) {
							?>
							<div class="item <?= ($cont < 1 ? 'active' : '') ?>">
								<div class="col-md-4" style="padding: 0px 0px 0px 0px;">
									<img class="picture" src="updater/site_files/<?= $portfolioMemoirs->GetCampoValue($key, 'image') ?>" alt="Slide" align="left" width="300px">
								</div>
								<div class="col-md-8">
									<h4>Book Title: </h4>
									<h3 style="text-align:left;"><?= $portfolioMemoirs->GetCampoValue($key, 'title') ?></h3>
									<p><?= $portfolioMemoirs->GetCampoValue($key, 'description') ?></p>
								</div>
							</div>
							<?
								$cont++;
							}
						?>
					</div>
					<!-- Left and right controls -->
					<a class="left carousel-control" href="#SecondSlide" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#SecondSlide" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
					<img src="images/aspasfecha.png" alt="" align="right" id="aspasRight">
				</div>
			</div>
		</div>
		<div id="portfolio">
			<div class="container">
				<h1>Portfolio</h1>
				<p align="right" class="p">-- Click on cover to read letter of commendation</p>
				<h4>Memoirs</h4>
				<hr width="90%" align="right">
				<?
					$cont=1;
					$contli=0;
					$contslides=0;
					$liMemoirs='';
					$contMemoirs='';
					$modal='';
					foreach ($portfolioMemoirs->mItems as $key => $value){
						if($detect->isMobile()){
							$liMemoirs.= '<li data-target="#slide-portfolio-memoirs" data-slide-to="'.$contslides.'" '.($contslides < 1 ? 'class="active"' : '').'>';
							$contslides++;
							$liMemoirs.=$contslides.'</li>';
							$contli = 0;
							$modal.='
							<div class="modal fade modal-memoirs-'.$cont.'" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3>'.$portfolioMemoirs->GetCampoValue($key, 'title').'</h3>
										<p>'.$portfolioMemoirs->GetCampoValue($key, 'description').'</p>
									</div>
								</div>
							</div>';
							
							$contMemoirs.= ($key > 0 ? '<div class="item">' : '').'
								<div class="col-md-12">
									<a data-toggle="modal" data-target=".modal-memoirs-'.$cont.'">
										<img href="#" class="images-portfolio" src="updater/site_files/'.$portfolioMemoirs->GetCampoValue($key, 'image') .'" alt="" width="90%" height="100%" style="cursor: pointer;">
									</a>
								</div>
							</div>
							';
							$cont++;
							$contli++;
						}
						else{
							$itemdiv = '';
							if($cont == count($portfolioMemoirs->mItems)){
								$liMemoirs.= '<li data-target="#slide-portfolio-memoirs" data-slide-to="'.$contslides.'" '.($contslides < 1 ? 'class="active"' : '').'>';
								$contslides++;
								$liMemoirs.=$contslides.'</li>';
							}
							if($contli > 5){
								$liMemoirs.= '<li data-target="#slide-portfolio-memoirs" data-slide-to="'.$contslides.'" '.($contslides < 1 ? 'class="active"' : '').'>';
								$contslides++;
								$liMemoirs.= $contslides.' </li>';
								$itemdiv = '</div><div class="item">';
								$contli = 0;
							}
							$modal.='
							<div class="modal fade modal-memoirs-'.$cont.'" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3>'.$portfolioMemoirs->GetCampoValue($key, 'title').'</h3>
										<p>'.$portfolioMemoirs->GetCampoValue($key, 'description').'</p>
									</div>
								</div>
							</div>';
								
								$contMemoirs.= $itemdiv.'
							<div class="col-md-2">
								<a data-toggle="modal" data-target=".modal-memoirs-'.$cont.'">
								<img href="#" class="images-portfolio" src="updater/site_files/'.$portfolioMemoirs->GetCampoValue($key, 'image') .'" alt="" width="150" height="200" style="cursor: pointer;">
								</a>
							</div>'.(count($portfolioMemoirs->mItems) < 5 && $cont == count($portfolioMemoirs->mItems) ? '</div>' : ($cont == count($portfolioMemoirs->mItems) ? '</div>' : ''));
							$cont++;
							$contli++;
						}
					}
					//die(htmlentities($contMemoirs));
				?>                             
				<?= $modal ?>
				<div id="slide-portfolio-memoirs" class="carousel slide" data-ride="carousel" data-interval="false">
					<ol class="carousel-indicators">
						<?=$liMemoirs?>
					</ol>
					<div class="carousel-inner" role="listbox">
						<div class="item active"><?=$contMemoirs?>
						</div>
						<a class="left carousel-control" href="#slide-portfolio-memoirs" data-slide="prev"  style="width: 0;">
							<span class="glyphicon glyphicon-chevron-left" style="margin: -40px 0 0px -14px;text-shadow: 0 1px 2px rgb(19, 127, 200);color: #9AC8E5;"></span>
						</a>
						<a class="right carousel-control" href="#slide-portfolio-memoirs" data-slide="next" style="width: 0;">
							<span class="glyphicon glyphicon-chevron-right" style="margin: -40px 0 0px 0px;text-shadow: 0 1px 2px rgb(19, 127, 200);color: #9AC8E5;"></span>
						</a>
					</div>
					<h4>Company and Family History</h4><hr width="65%" align="right">
						<?php
							$cont = 0;
							$cont1 = 1;
							
							foreach ($portfolioFamily->mItems as $key => $value) {
							?>
							<div class="modal fade modal-family-<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
                                        <?if(strlen($portfolioFamily->GetCampoValue($key, 'content_image')) > 0){?>
                                            <img src="updater/site_files/<?=$portfolioFamily->GetCampoValue($key, 'content_image')?>" style="max-width:800px">
                                        <?}else{?>
										    <h3><?= $portfolioFamily->GetCampoValue($key, 'title') ?></h3>
										    <p><?= $portfolioFamily->GetCampoValue($key, 'description') ?></p>
                                        <?}?>
									</div>
								</div>
							</div>
							<a data-toggle="modal" data-target=".modal-family-<?= $key ?>" style="cursor:pointer;">
								<img href="#" class="images-portfolio" src="updater/site_files/<?= $portfolioFamily->GetCampoValue($key, 'image') ?>" alt="" height="200">
							</a>
							<?php
							}
                            ?>
				</div>
			</div>
		</div>
		<div id="brochure">
			<div class="container">
				<h1>Brochure</h1>
				<p align="right" class="p">--Click on image to enlarge</p>
				<a href="updater/site_files/<?= $brochure->GetCampoValue(0, 'bigimage1') ?>" class="fancybox" rel="gallery1">
					<img src="updater/site_files/<?= $brochure->GetCampoValue(0, 'first_image') ?>" alt="Slide" align="center" height="400" class="picture">
				</a>
				<a href="updater/site_files/<?= $brochure->GetCampoValue(0, 'bigimage2') ?>" class="fancybox" rel="gallery1">
					<img src="updater/site_files/<?= $brochure->GetCampoValue(0, 'second_image') ?>" alt="Slide" align="center" height="400" class="picture">
				</a>
				<center style="padding: 20px 0 0 0px;"><a href="updater/site_files/<?= $brochure->GetCampoValue(0, 'pdf_file') ?>" target="_blank"><button class="btn btn-lg btn-primary btn-custom" href="" style="margin: auto;">Click Here to save it</button></a></center>
			</div>
		</div>
		<div id="about">
			<div class="container">
				<div class="col-md-5">
					<h1>About Us</h1>
					<p><?= $about->mItems[0]->Idiomas[1]->Texto ?></p>
					<div style="text-align:left">
						<?
							$cont = 0;
							foreach($aboutTeam->mItems AS $key=>$value){
							?>
							<img onmouseover="houverFunctionAboutOn(<?= $key ?>)" onmouseout="houverFunctionAboutOf(<?= $key ?>)" src="updater/site_files/<?= $aboutTeam->GetCampoValue($key, 'image') ?>" class="img_<?= $key ?> img-circle houver" id="img_about_<?= $cont ?>" style="width: 85px; height: 85px;">
							<?
								$cont++;
							}
						?>
					</div>
				</div>
				<div class="col-md-1"></div>
				<div class="col-md-4" style="padding-top: 70px">
					<?
						$cont = 0;
						foreach($aboutTeam->mItems AS $key=>$value){
						?>
						<?if($aboutTeam->mItems[$key]->Idiomas[1]->Texto != ''):?>
						<div class="modal fade modal-read-more-<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<?= $aboutTeam->mItems[$key]->Idiomas[1]->Texto ?>
								</div>
							</div>
						</div>
						<?endif;?>
						<div onmouseover="houverFunctionAboutOn(<?= $key ?>)" onmouseout="houverFunctionAboutOf(<?= $key ?>)" class="about_<?= $key ?>">
							<p class="about_brief_text_<?= $cont ?> text-about"><?= $aboutTeam->mItems[$key]->Idiomas[1]->Nome ?>&nbsp;&nbsp;<?=($aboutTeam->mItems[$key]->Idiomas[1]->Texto != '' ? '<a href="#" style="cursor: pointer;font-weight: normal;" class="about_brief_text about_brief_text_'.$cont.'" id="read_more_'.$cont.'" data-toggle="modal" data-target=".modal-read-more-'.$key.'" align="right">(Read More)</a>' : '')?></p>
							<p class="about_brief_text about_brief_text_<?= $cont ?>"> "<?= $aboutTeam->GetCampoValue($key, 'motto') ?>"</p>
						</div>
						<?
							
							$cont++;
						}
					?>
					
				</div>
			</div>
		</div>
		<div id="contact">
			<div class="container">
				<h1>Contact</h1>
				<form method="post" action="">
					<div class="row">
						<div class="col-md-4"  style="padding-bottom: 10px;">
							<label for="name">Name</label>
							<input type="text" id="name" class="form-control" name="name" tabindex="1" required>
						</div>
						<div class="col-md-4" style="padding-bottom: 10px;">
							<label for="email">Email</label>
							<input type="email" id="email" class="form-control" name="email" tabindex="2" required>
						</div>
						<div class="col-md-4"  style="padding-bottom: 10px;">
							<label for="telephone">Telephone</label>
							<input type="text" id="telephone" class="form-control" name="telephone" tabindex="3" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="padding-bottom: 10px;">
							<label for="message">Message</label>
							<textarea name="message" class="form-control" rows="5" id="message" tabindex="4"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">&nbsp;</div>
						<div class="col-md-4"><center><input type="submit" class="btn btn-lg btn-primary btn-custom" tabindex="5" value="Send" required></center></div>
						<div class="col-md-4">&nbsp;</div>
					</div>
				</form>
			</div>
		</div>
		<div id="sites">
			<div class="container">
				<h1>Our other site</h1>
				<?foreach($otherSites->mItems AS $key=>$value){
				?>
				<div class="col-md-4" style="padding-bottom: 20px;">
					<img src="updater/site_files/<?= $otherSites->GetCampoValue($key, 'image') ?>">
					<p style="color: black;"><?= $otherSites->mItems[$key]->Idiomas[1]->Texto ?></p>
					<a href="<?= $otherSites->GetCampoValue($key, 'url') ?>" target="_blank" style="padding">
						<button class="btn btn-lg btn-primary btn-custom" tabindex="-1"><?= $otherSites->GetCampoValue($key, 'button_text') ?></button>
					</a>
				</div>
				<?}?>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				<center><h6><?= $page->GetCampoValue(0, 'footer') ?></h6></center>
			</div>
		</div>
	</body>
</html>