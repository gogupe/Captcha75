<?php
$token = $_POST['captcha_token'] ?? '';

if (!$token)
	{
	die('❌ Token vacío o no generado.');
	}

// Datos de Yolanda (clave pública y privada)
$site_key = '30fc47c0edc42dfd228da4fa';
$secret_key = '928a72523740dd6d1a58b6ed';

// Montamos los datos POST para enviar al servidor CAPTCHA
$post_data = http_build_query([
	'token' => $token,
	'site_key' => $site_key,
	'secret_key' => $secret_key
]);

// Configuramos la petición HTTP (file_get_contents)
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

if ($response === false)
	{
	die('❌ Error al conectar con el sistema CAPTCHA.');
	}

$res = json_decode($response, true);

if ($res['success'])
	{
	echo "<p>✅ CAPTCHA verificado correctamente.<br>Nombre recibido: <strong>" . htmlspecialchars($_POST['nombre']) . "</strong></p>";
	}
else
	{
	echo "<p>❌ CAPTCHA fallido: " . htmlspecialchars($res['error'] ?? 'Error desconocido') . "</p>";
	}
?>
