<?php

require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

//Conectarnos a la db
$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/../'));
$dotenv->load();
// echo '<pre>'.var_dump( $_ENV['BD_NAME']).'</pre>';
// exit;
$db = conectarDB($_ENV);
use Model\ActiveRecord;

ActiveRecord::setDB($db);