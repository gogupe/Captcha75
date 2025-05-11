<?php
$conexion_servidor="localhost";
$conexion_usuario="captcha";
$conexion_bd="captcha";
$conexion_password="WxsMm&276o~eguHh";

$link=mysqli_connect($conexion_servidor,$conexion_usuario,$conexion_password,$conexion_bd);

mysqli_query($link,"SET NAMES 'utf8'");
mysqli_set_charset($link,'utf8');

include ($_SERVER['DOCUMENT_ROOT']."/function/escape.php");


