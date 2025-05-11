<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php";
#include $_SERVER['DOCUMENT_ROOT']."/admin/config/config.php";
include $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";



if ($_SESSION['logged_admin'])
	{
		header ("location:/admin/admin.php");
		exit;
	}

if (isset($_POST['sb_acceso']))
	{
		$usuario=escapa($_POST['usuario']);
		$password=escapa($_POST['password']);

		if ($usuario=="admin" and $password=="Creativos75!")
			{
				$_SESSION['admin']="1";

				header("location:/admin/admin.php");
				exit;
			}

	}
?>


<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Administrador pistas deportivas</title>
<link rel="icon" href="/favicon.png" type="image/png" sizes="64x64">
<link rel="apple-touch-icon" href="/favicon.png" sizes="64x64">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="/vendor/almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="/vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.css">
<link rel="stylesheet" href="/estilo/style_app.css">
</head>
<body class="hold-transition login-page">
<form method="post">
<div style="margin-top:20px;" >
	<div class="login-logo"><img src="/graf/logo/logo_recorte.png" title="Captcha"  class="imng-fluid" style="width:98%;max-width:250px;"/></div>
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">Introduzca sus datos de acceso</p>

				<div class="input-group mb-3">
					<input type="text" class="form-control" name="usuario" placeholder="Email" required autofocus>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-user"></span></div>
					</div>
				</div>

				<div class="input-group mb-3"><input type="password" name="password" class="form-control" placeholder="Contraseña" required>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-lock"></span></div>
					</div>
				</div>

				<?php
				if ($_SESSION['captcha'])
					{
						$sw_submit_disable="disabled";
				?>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<div class="g-recaptcha" data-callback="onCompleted" data-sitekey="<?php echo $clave;?>" data-expired-callback="noCompleted" ></div>
							</div>
						</div>
				<?php
					}
				else
					{
						$sw_submit_disable="";
					}
				?>


				<div class="col-12"><button type="submit" name="sb_acceso" id="sb_acceso" class="btn btn-primary btn-block" <?php echo $sw_submit_disable;?>>Acceder</button></div>
			</div>
		</div>
	</div>
</div>

</form>
<script src="/vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
onCompleted = function()
	{
		$("#sb_acceso").removeClass("btn-secondary");
		$("#sb_acceso").addClass("btn-success");
		document.getElementById("sb_acceso").disabled=false;
	}

noCompleted = function()
	{
		$("#sb_acceso").addClass("btn-secondary");
		$("#sb_acceso").removeClass("btn-success");
		document.getElementById("sb_acceso").disabled=true;
	}
</script>

</body>
</html>
