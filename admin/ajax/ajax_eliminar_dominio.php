<?php
// Verificar si es una solicitud AJAX
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
	exit;
}

require_once $_SERVER['DOCUMENT_ROOT'].'/conexion/conexion.php';

// Obtener datos enviados por POST
$id_cliente = intval($_POST['id_cliente']);
$index = intval($_POST['index']);

// Verificar que los datos sean válidos
if (!$id_cliente || $index < 0) {
	echo json_encode(['success' => false, 'message' => '❌ Datos inválidos.']);
	exit;
}

// Obtener los dominios del cliente
$sql = "SELECT dominios_permitidos FROM clientes WHERE id = $id_cliente LIMIT 1";
$result = mysqli_query($link, $sql);
$cuantos = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);
mysqli_free_result($result);

// Verificar si el cliente existe
if ($cuantos == 0) {
	echo json_encode(['success' => false, 'message' => '❌ Cliente no encontrado.']);
	exit;
}

// Convertir los dominios en un array
$dominios = explode(',', $row['dominios_permitidos']);

// Verificar que el índice sea válido
if (!isset($dominios[$index])) {
	echo json_encode(['success' => false, 'message' => '❌ Dominio no encontrado.']);
	exit;
}

// Eliminar el dominio del array
unset($dominios[$index]);

// Reindexar y convertir de nuevo a cadena
$dominios = array_values($dominios);
$dominios_actualizados = implode(',', $dominios);

// Actualizar los dominios en la base de datos
$sql_update = "UPDATE clientes SET dominios_permitidos = '$dominios_actualizados' WHERE id = $id_cliente";
mysqli_query($link, $sql_update);

// Respuesta de éxito
echo json_encode(['success' => true, 'message' => '✅ Dominio eliminado correctamente.']);

mysqli_close($link);