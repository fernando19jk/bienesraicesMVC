<?php

function conectarDB() : mysqli {
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_USER = 'root';
    $DB_NAME = 'bienesraices_crud';
    $DB_PORT = '';
    $db = new mysqli($DB_HOST, $DB_USER,$DB_USER, $DB_NAME);

    if (!$db) {
        echo "Error no se pudo conectar";
        exit;
    }

    return $db;

}