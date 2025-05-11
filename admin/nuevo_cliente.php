<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/control_login.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/genera_token.php";

$active="nuevo_cliente";



if (isset($_POST['sb_ins_ur']))
	{
		$nombre = escapa($_POST['nombre']);

		$sql_comp="SELECT * FROM clientes WHERE nombre='$nombre'";
		$result_comp=mysqli_query($link,$sql_comp);
		$cuanto=mysqli_num_rows($result_comp);
		mysqli_free_result($result_comp);

		if ($cuanto>1)
			{
				header ("location:/admin/nuevo_cliente.php?error=01");
				exit;
			}
		else
			{
				list($site_key, $secret_key) = generarClavesUnicas($link);
				$sql_ins="INSERT INTO clientes SET
					nombre='$nombre',
					site_key='$site_key',
					secret_key='$secret_key',
					dominios_permitidos='',
					activo='0'";
				mysqli_query($link,$sql_ins);

				header ("location:/admin/url_permitidas.php?token=$site_key");
				exit;

			}
}


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
				<form method="post">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label for="nombre">Nombre identificativo</label>
							<input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" reqruired>
						</div>						
					</div>


					<div class="col-sm-2">
						<label>&nbsp;</label>
						<input type="submit" name="sb_ins_ur" class="btn btn-success btn-block" value="Siguiente" />
					</div>				
				</div>
				</form>
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

<?php
mysqli_close($link);