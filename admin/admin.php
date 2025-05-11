<?php
session_name();
session_start();
include $_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/control_login.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Captcha Creativos 75</title>
<link rel="icon" href="/favicon.png" type="image/png" sizes="64x64">
<link rel="apple-touch-icon" href="/favicon.png" sizes="64x64">


<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="/vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">

<link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.css">

<link rel="stylesheet" href="/estilo/style_app.css">
<link rel="stylesheet" href="/dist/styles.css">
<link rel="stylesheet" href="/estilo/tailwind_icon_menu.css">


</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<ul class="navbar-nav">
			<li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i class="fas fa-bars"></i></a></li>
		</ul>
		<ul class="navbar-nav ml-auto"></ul>
	</nav>

	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/php/barra_izda.php"; ?>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-12"><h1 class="m-0"></h1></div>
				</div>
			</div>
		</div>

		<div class="content">
			<div class="container-fluid">
				<div class="row" style="display:table;margin:0 auto !important;">
					<div class="col-12">
						<p><img src="/graf/logo/logo.png" style="max-width:600px;width:98%;"/></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	include $_SERVER['DOCUMENT_ROOT']."/admin/php/form_desconectar.php";
	?>
	<aside class="control-sidebar control-sidebar-dark"></aside>

	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/php/copy.php"; ?>
</div>

<script src="/vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/dist/js/adminlte.js"></script>


</body>
</html>
