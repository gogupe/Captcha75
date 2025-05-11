<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'].'/conexion/conexion.php';

$token = $_POST['token'] ?? '';
$site_key = $_POST['site_key'] ?? '';
$secret_key = $_POST['secret_key'] ?? '';

// Escapar valores
$token_esc = escapa($token);
$site_key_esc = escapa($site_key);
$secret_key_esc = escapa($secret_key);

// Validar cliente
$sql_cliente = "SELECT * FROM clientes WHERE site_key = '$site_key_esc' AND secret_key = '$secret_key_esc' AND activo = 1 LIMIT 1";
$result_cliente = mysqli_query($link, $sql_cliente);
$cuanto_cliente = mysqli_num_rows($result_cliente);
$row_cliente = mysqli_fetch_assoc($result_cliente);
mysqli_free_result($result_cliente);

if ($cuanto_cliente === 0)
	{
	// Registrar fallo en la tabla clientes
	mysqli_query($link, "UPDATE clientes SET fallos = fallos + 1 WHERE site_key = '$site_key_esc'");
	echo json_encode(['success' => false, 'error' => 'Credenciales inválidas']);
	exit;
	}

// Buscar token válido
$sql_token = "SELECT * FROM tokens WHERE token = '$token_esc' AND site_key = '$site_key_esc' AND usado = 0 LIMIT 1";
$result_token = mysqli_query($link, $sql_token);
$cuanto_token = mysqli_num_rows($result_token);
$row_token = mysqli_fetch_assoc($result_token);
mysqli_free_result($result_token);

if ($cuanto_token === 0)
	{
		// Registrar fallo en la tabla clientes
		mysqli_query($link, "UPDATE clientes SET fallos = fallos + 1 WHERE site_key = '$site_key_esc'");
		echo json_encode(['success' => false, 'error' => 'Token inválido o ya usado']);
		exit;
	}

// Verificar caducidad (300 segundos = 5 min)
$fecha_creacion = strtotime($row_token['fecha_creacion']);
if (time() - $fecha_creacion > 300)
	{
		// Registrar fallo en la tabla clientes
		mysqli_query($link, "UPDATE clientes SET fallos = fallos + 1 WHERE site_key = '$site_key_esc'");
		
		// Eliminar token caducado
		mysqli_query($link, "DELETE FROM tokens WHERE id = '".$row_token['id']."' LIMIT 1");

		echo json_encode(['success' => false, 'error' => 'Token expirado']);
		exit;
	}

// Marcar token como usado y luego eliminar
$sql_usar = "DELETE FROM tokens WHERE id = '".$row_token['id']."' LIMIT 1";
mysqli_query($link, $sql_usar);

// Registrar acierto en la tabla clientes
mysqli_query($link, "UPDATE clientes SET aciertos = aciertos + 1 WHERE site_key = '$site_key_esc'");

// Éxito
echo json_encode(['success' => true]);
