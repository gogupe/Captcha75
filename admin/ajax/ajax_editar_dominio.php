<?php
// Conectar a la base de datos
require_once $_SERVER['DOCUMENT_ROOT'].'/conexion/conexion.php';

// Verificar si se recibió la petición por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar los datos recibidos
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    $nuevoDominio = trim($_POST['nuevoDominio']);
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;

    // Verificar que el cliente exista
    $sql = "SELECT dominios_permitidos FROM clientes WHERE id = $id_cliente LIMIT 1";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) === 0) {
        echo json_encode(['success' => false, 'message' => '❌ Cliente no encontrado.']);
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    // Convertir dominios en array
    $dominios = explode(',', $row['dominios_permitidos']);

    // Verificar que el índice sea válido
    if ($index < 0 || $index >= count($dominios)) {
        echo json_encode(['success' => false, 'message' => '❌ Índice de dominio inválido.']);
        exit;
    }

    // Actualizar el dominio específico
    $dominios[$index] = $nuevoDominio;
    $nuevosDominios = implode(',', array_map('trim', $dominios));

    // Actualizar en la base de datos
    $sql_update = "UPDATE clientes SET dominios_permitidos = '$nuevosDominios' WHERE id = $id_cliente";
    if (mysqli_query($link, $sql_update)) {
        echo json_encode(['success' => true, 'message' => '✅ Dominio actualizado.', 'nuevoDominio' => $nuevoDominio]);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Error al actualizar el dominio.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '❌ Petición inválida.']);
}
mysqli_close($link);