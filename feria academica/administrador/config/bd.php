<?php
$host="localhost";
$bd="plantas";
$user="root";
$pass="";
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location:./catalogo/administrador/index.php");
}else{
    if($_SESSION['usuario']=="ok"){
        $nombreUsuario=$_SESSION["$nombreUsuario"];
    }
}

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd",$user,$pass);
    if($conexion){
        echo "conexion exitosa";
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

?>