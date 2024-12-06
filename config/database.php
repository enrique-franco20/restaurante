<?php
require_once __DIR__ . '/../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb+srv://76543740:76543740@hotelera.eehq8.mongodb.net/?retryWrites=true&w=majority&appName=hotelera");
$database = $mongoClient->selectDatabase('restaurante');
$tasksCollection = $database->tareas;

?>
<?php
require 'vendor/autoload.php'; // Asegúrate de cargar las dependencias

$client = new MongoDB\Client('mongodb+srv://76543740:76543740@hotelera.eehq8.mongodb.net/?retryWrites=true&w=majority&appName=hotelera');
$db = $client->selectDatabase('restaurante'); // Reemplaza con el nombre de tu base de datos
