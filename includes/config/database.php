<?php

function conectarDB() : mysqli {

    $db = new mysqli($_ENV['BD_NAME']['DB_HOST'], $_ENV['BD_NAME']['DB_USER'],$_ENV['BD_NAME']['DB_PASSWORD'], $_ENV['BD_NAME']['BD_NAME']);

    if (!$db) {
        echo "Error no se pudo conectar";
        exit;
    }

    return $db;

}