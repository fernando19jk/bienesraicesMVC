<?php

function conectarDB() : mysqli {

    $db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'],$_ENV['DB_PASSWORD'], $_ENV['BD_NAME'], $_ENV['PORT']);

    if (!$db) {
        echo "Error no se pudo conectar";
        exit;
    }

    return $db;

}