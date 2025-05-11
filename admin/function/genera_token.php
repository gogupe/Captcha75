<?php
function generarClave($longitud = 24) {
    return bin2hex(random_bytes($longitud / 2));
}

// Verificar que las claves sean únicas
function generarClavesUnicas($link) {
    do {
        $site_key = generarClave();
        $secret_key = generarClave();

        // Verificar si existen ya en la base de datos
        $sql_verificar = "SELECT id FROM clientes WHERE site_key = '$site_key' OR secret_key = '$secret_key' LIMIT 1";
        $result_verificar = mysqli_query($link, $sql_verificar);
        $existe = mysqli_num_rows($result_verificar);
        mysqli_free_result($result_verificar);
    } while ($existe > 0); // Repetir hasta que sean únicas

    return [$site_key, $secret_key];
}
