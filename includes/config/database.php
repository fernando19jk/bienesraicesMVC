<?php

function conectarDB($db_variable) : mysqli {

    $db = new mysqli($db_variable['DB_HOST'], $db_variable['DB_USER'],$db_variable['DB_PASSWORD'], $db_variable['BD_NAME']);

    if (!$db) {
        echo "Error no se pudo conectar";
        exit;
    }

    return $db;

}