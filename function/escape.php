<?php
if (!function_exists('escapa'))
	{
		function escapa($theValue)
		{
			if ($theValue === null)
				{
					return 'NULL'; // para usar directamente en SQL si lo necesitas
				}

			global $link;
			if (!isset($link))
				{
					include($_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php");
				}

			$variable = mysqli_real_escape_string($link, trim(strip_tags($theValue)));
			return $variable;
		}
	}
