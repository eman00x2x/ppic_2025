<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo CDN; ?>vendor/bootstrap-5.3.3/css/bootstrap.min.css" >
	<link href="<?php echo CDN; ?>css/old.style.css" rel="stylesheet">
	<link href="<?php echo CDN; ?>css/site.style.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo CDN; ?>vendor/bootstrap-5.3.3/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo CDN; ?>vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>

    <script type="text/javascript">
        var DOMAIN = '<?php echo ADMIN; ?>';
        var CDN = '<?php echo CDN; ?>';
    </script>

    <?php
        $document = \Library\Factory::getDocument();
        echo \Library\DocumentRenderer::fetchHead($document);
    ?>

</head>
<body>

    <div class="navigation">
		<div class="container">
			<div class="row">
				<div class="col-md-3 col">
					<div class="logo">
						<!-- <a href="<?php echo WEBDOMAIN; ?>"><img src="<?php echo CDN;?>images/philproperties-logo.png" alt="Philproperties Logo" style="width:200px;" /></a> -->
					</div>
				</div>
				
				<div class="col-md-9 col">
					<div class="row">
						<div class="col-md-10 col-sm-10 d-md-block d-none">
							<div class="menu">
								<ul>
									<li class='px-1 py-2'><a href="<?php echo url("web.buy"); ?>" class=''>For Sale</a></li>
									<li class='px-1 py-2'><a href="<?php echo url("web.rent"); ?>" class=''>For Rent</a></li>
									<!--<li class='px-1 py-2'><a href="<?php echo url("developments"); ?>" style="font-weight:bold;" class=''>New Communities</a></li>-->
									<li class='px-1 py-2'><a href="<?php echo url("articles",["id" => 2, "name" => "careers-at-philproperties"]); ?>" style="font-weight:bold;" class=''>Be Our Agent</a></li>
									<li class='border-primary px-1 py-2 bg-primary'><a href="<?php echo url("send-property-details"); ?>" style="font-weight:bold; font-size:14px;" class='text-white' >Sell Your Property</a></li>
								</ul>
							</div>
						</div>
						
						<div class="col-md-2 col">
							<div class="float-right">								
								<!--<span class="btn btn-default pull-right menu-btn" data-toggle="modal" data-target="#menuModal"><span class="glyphicon glyphicon-menu-hamburger"></span> Menu</span>-->
								<span class="btn btn-default pull-right menu-btn" data-toggle="modal" data-target="#menuModal"><img src="<?php echo CDN;?>images/website/appdrawer5.png" style="width:26px;" /> Menu</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <?php echo $content; ?>

    <div class="main-footer-wrap py-4">
        <div class="footer-wrap">
            <div class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="boxes resources">
                                <span class="footer-box-title">PHILPROPERTIES INT'L CORP</span>
                                <ul>
                                    <li><a href="<?php echo url("web.about"); ?>">About Philproperties</a></li>
                                    <li><a href="<?php echo url("web.members"); ?>">Our Team</a></li>
                                    <li><a href="<?php echo url("web.careers"); ?>">Careers</a></li>
                                    <li><a href="<?php echo url("web.contact"); ?>">Contact Us</a></li>
                                    <li><a href="<?php echo url("web.buyer-info"); ?>">Buyer Information Form</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="boxes resources">
                                <span  class="footer-box-title">USEFUL RESOURCES</span>
                                <ul>
                                    <li><a href="<?php echo url("articles", ["id" => 1, "name" => "reservation-procedure"]); ?>"><span>Reservation Procedure</span></a></li>
                                    <li><a href="<?php echo url("articles", ["id" => 17, "name" => "processes-in-investing-on-a-real-estate-property"]); ?>"><span>Processes in Investing on a Real Estate Property</span></a></li>
                                    <li><a href="<?php echo url("articles", ["id" => 16, "name" => "practical-tips-for-buying-a-piece-of-real-estate"]); ?>"><span>Practical Tips For Buying A Piece of Real Estate</span></a></li>
                                    <li><a href="<?php echo url("articles", ["id" => 10, "name" => "be-a-successful-real-estate-practitioner"]); ?>"><span>Be A Successful Real Estate Practitioner</span></a></li>
                                    <li><a href="<?php echo url("articles"); ?>">All Articles</a></li>	
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="boxes info">
                                <span class="send-msg-btn"><a href="<?php echo url("contact-us"); ?>">SEND MESSAGE</a></span>
                                <ul>
                                    <li><a href="tel:0272112774"><span>(02) 7211 2774 &nbsp; &nbsp; &nbsp; OFFICE</span></a></li>
                                    <li><a href="tel:639171198992"><span>0917 119 8992 &nbsp; &nbsp; SUN</span></a></li>
                                </ul>
                                <span class="btn-chat">Find us on &nbsp;<a href="https://www.facebook.com/philproperties.ph"><img src='<?php echo CDN; ?>images/facebook-icon.png' /> &nbsp;Facebook</a></span>
                            </div>
                        </div>
                    </div>
                    <div class='main-info pt-2 d-print-none' style="color:#FFF;border-top:1px solid #4c6eb6;">
                        <p class="address">
                            <span>OFFICE ADDRESS</span><br/>
                            <?php echo "address"; ?>
                        </p>
                        <p class='text-center pb-4 m-0'>Copyright &copy; 2006 - <?php echo date("Y"); ?>. <a href='<?php echo url(null); ?>' style="color:#FFF;">Philproperties International Corporation</a>. All rights reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>