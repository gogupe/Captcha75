<?php
/*
Uso

1- Envío por ajax
La recogida mediante ajax (Sin submit ni form se recoge de la siguiente forma)
var captcha = window.captcha_creativos_token;
La recogida del archivo ajax es la misma que el puno 2

2. POST mediante submit
Ejemplo hecho abajo

*/



$site_key = '30fc47c0edc42dfd228da4fa';
$secret_key = '928a72523740dd6d1a58b6ed';

if (isset($_POST['sb_enviar']))
	{
		$nombre=$_POST['nombre'];
		$token = $_POST['captcha_token'] ?? '';



		// Montamos los datos POST para enviar al servidor CAPTCHA
		$post_data = http_build_query([
			'token' => $token,
			'site_key' => $site_key,
			'secret_key' => $secret_key
		]);

		$options = [
			'http' => [
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => $post_data,
				'timeout' => 5
			]
		];

		$context  = stream_context_create($options);
		$response = file_get_contents('https://captcha.creativos75.es/captcha/api/verificar_token.php', false, $context);

		$res = json_decode($response, true);

		if ($res['success'])
			{
			echo "<p>✅ CAPTCHA verificado correctamente.<br>Nombre recibido: <strong>" . htmlspecialchars($_POST['nombre']) . "</strong></p>";
			}
		else
			{
			echo "<p>❌ CAPTCHA fallido: " . htmlspecialchars($res['error'] ?? 'Error desconocido') . "</p>";
			}		

	}
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Prueba CAPTCHA Yolanda</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 40px;
		}
		form {
			border: 1px solid #ccc;
			padding: 20px;
			border-radius: 6px;
			width: 400px;
		}
	</style>
</head>
<body>

<h2>Formulario de prueba con CAPTCHA</h2>

<form  method="POST">
	<label for="nombre">Nombre:</label><br>
	<input type="text" id="nombre" name="nombre" required><br><br>

	<!-- Aquí se insertará el CAPTCHA -->
	<div id="captcha-creativos"></div>

	<br>
	<button type="submit" name="sb_enviar">Enviar</button>
</form>


<script>
	// ✅ Se ejecuta cuando se resuelve correctamente
	function onComplete() {
		//alert("✅ CAPTCHA resuelto correctamente.");
	}

	// ⚠️ Se ejecuta si pasan 30 segundos sin enviar
	function onExpired() {
		//alert("⚠️ El CAPTCHA ha expirado y se ha reiniciado.");
	}
</script>

<!-- Script del sistema CAPTCHA alojado en el subdominio -->
<script src="https://captcha.creativos75.es/public/script.js" data-sitekey="<?php echo $site_key;?>"></script>

</body>
</html>
