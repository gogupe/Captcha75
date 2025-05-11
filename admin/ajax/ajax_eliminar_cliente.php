<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/control_login.php";



if (isset($_POST['id']) && is_numeric($_POST['id'])) 
	{
		$id = intval($_POST['id']);

		// Preparar la consulta para eliminar el cliente
		$sql_delete = "DELETE FROM clientes WHERE id = $id";
        mysqli_query($link, $sql_delete);
	}

mysqli_close($link);