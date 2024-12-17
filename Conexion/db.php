<?php


$servername = "localhost";
$username = "root";
$password = "Colon-1289";
$database = "ProyectoWebQ3";

$conn = new mysqli($servername, $username, $password, $database);

if($conn->connect_error){
    die("Conexio fallida");
} 