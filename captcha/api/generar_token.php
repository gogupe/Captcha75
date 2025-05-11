<?php
// Cabeceras CORS dinámicas
$origen = $_SERVER['HTTP_ORIGIN'] ?? '*';
header("Access-Control-Allow-Origin: $origen");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Vary: Origin");
header('Content-Type: application/json');

session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/conexion/conexion.php';


$site_key = $_POST['site_key'] ?? '';
$dominio_manual = $_POST['dominio'] ?? '';
$dominio_actual = $dominio_manual ?: parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);


// Escapar
$site_key_esc = escapa($site_key);
$dominio_actual_esc = escapa($dominio_actual);

// Buscar cliente activo
$sql_cliente = "SELECT * FROM clientes WHERE site_key = '$site_key_esc' AND activo = 1 LIMIT 1";
$result_cliente = mysqli_query($link, $sql_cliente);
$cuanto_cliente = mysqli_num_rows($result_cliente);
$row_cliente = mysqli_fetch_assoc($result_cliente);
mysqli_free_result($result_cliente);

if ($cuanto_cliente === 0)
	{
	echo json_encode(['success' => false, 'error' => 'Site key inválida']);
	exit;
	}

// Comprobar si el dominio está autorizado
$dominios_permitidos = explode(',', $row_cliente['dominios_permitidos']);
$dominio_valido = false;

// Normalizar dominio actual (eliminar "www." si existe)
$dominio_actual = str_ireplace('www.', '', $dominio_actual);

foreach ($dominios_permitidos as $dom) 
	{
	// Normalizar el dominio permitido
	$dom = str_ireplace('www.', '', trim($dom));
	
	// Comprobar si el dominio actual coincide o es subdominio permitido
	if ($dom === $dominio_actual || substr($dominio_actual, -strlen($dom) - 1) === '.' . $dom) 
		{
		$dominio_valido = true;
		break;
		}
	}

if (!$dominio_valido) 
	{
	echo json_encode([
		'success' => false, 
		'error' => 'Dominio no autorizado. Permitidos: ' . implode(', ', $dominios_permitidos) . '. Actual: ' . $dominio_actual
	]);
	exit;
	}

// Detectar IP y agente
$ip_usuario = escapa($_SERVER['REMOTE_ADDR']);
$user_agent = escapa($_SERVER['HTTP_USER_AGENT']);
$fecha_creacion = date('Y-m-d H:i:s');

// Contar peticiones desde la misma IP en los últimos 15 minutos
$sql_contador = "SELECT COUNT(*) AS total FROM tokens WHERE ip_usuario = '$ip_usuario' AND site_key = '$site_key_esc' AND fecha_creacion > NOW() - INTERVAL 15 MINUTE";
$result_contador = mysqli_query($link, $sql_contador);
$row_contador = mysqli_fetch_assoc($result_contador);
mysqli_free_result($result_contador);

$requiere_imagen = ($row_contador['total'] >= 5) ? true : false;

// Generar token
$token = bin2hex(random_bytes(32));
$token_esc = escapa($token);

// Insertar token en la base de datos
$sql_insert = "INSERT INTO tokens (token, site_key, fecha_creacion, ip_usuario, user_agent) VALUES ('$token_esc', '$site_key_esc', '$fecha_creacion', '$ip_usuario', '$user_agent')";
mysqli_query($link, $sql_insert);

// Devolver respuesta
echo json_encode([
	'success' => true,
	'token' => $token,
	'requiere_imagen' => $requiere_imagen
]);
