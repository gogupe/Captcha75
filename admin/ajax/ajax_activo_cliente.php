<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/control_login.php";

if (isset($_POST['id']) && isset($_POST['activo'])) {
	$id = intval($_POST['id']);
	$activo = intval($_POST['activo']);

	$sql_update = "UPDATE clientes SET activo = $activo WHERE id = $id";
	mysqli_query($link, $sql_update);

}
?>
