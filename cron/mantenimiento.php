<?php
include "conexion/conexion.php";

mysqli_query($link, "DELETE FROM tokens WHERE DATE(fecha_creacion) < DATE(NOW())");



