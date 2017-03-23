<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?=$page_title?></title>
	<link href="<?= base_url().'assets/jquery-ui/themes/excite-bike/jquery-ui.min.css';?>" rel="stylesheet">
	<!-- Bootstrap -->
	<link href="<?= base_url().'assets/bootstrap/dist/css/bootstrap.min.css';?>" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="<?= base_url().'assets/html5/html5shiv.min.js';?>"></script>
	<script src="<?= base_url().'assets/respond/respond.min.js';?>"></script>
	<![endif]-->

	<script type="text/javascript">
		window.base_url = <?php echo json_encode(base_url()); ?>;
	</script>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="<?= base_url().'assets/jquery/dist/jquery.min.js';?>"></script>
	<script src="<?= base_url().'assets/vue/vue.js';?>"></script>
	<script src="<?= base_url().'assets/jquery-ui/jquery-ui.min.js';?>"></script>
	<style>
		body{
			font-size: small;
		}
	</style>
</head>
<body>

<div class="container">
	<div id="loading" style="align-self:center; position:fixed; top:45%;left:45%;display: none; z-index: 9999">
		<h5><span><img src="<?= base_url()?>assets/img/loading/squares.svg" style="width: 64px; height: auto"><p>Loading ...</p></span></h5>
	</div>
	<div id="error" style="position:fixed; top:45%;left:45%;display: none; z-index: 9999"><h3>Error...</h3></div>
	<h4 style="text-align: center; font: "Helvetica Neue", Helvetica, Arial, sans-serif">Building Compliance Survey</h4>
	<?php if(isset($sub_nav)):?>
		<?= $this->load->view('bcs/partials/sub_nav',['sub_nav'=>$sub_nav]); ?>
	<?php endif;?>
	<?= $this->load->view($content_view,$content_view_data); ?>
</div>
</body>
</html>