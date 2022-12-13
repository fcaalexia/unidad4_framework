<?php
$host="localhost";
$bd="";
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


$sentenciaSQL = $conexion->prepare("SELECT * FROM planta");
$sentenciaSQL ->execute();
$listaplantas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="nosotros.php">Nosotros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="plantas.php">Tipos de Plantas</a>
            </li>
        </ul>
    </nav>
    <br>
    <?php foreach($listaplantas as $plantas){ ?>
    <div class="col-md-3">
        <div class="card">
            <img class="card-img-top" src="./img/<?php echo $plantas['imagen']; ?>" alt="">
            <div class="card-body">
                <h4 class="card-title"><?php echo $plantas['nombre']; ?></h4>
                <p class="card-text">Especie: <?php echo $plantas['especie']; ?></p>
                <p class="card-text">Ubicacion: <?php echo $plantas['ubicacion']; ?></p>
                <p class="card-text">Riesgo: <?php echo $plantas['riesgo']; ?></p>
            </div>
        </div>
    </div>
    <?php }?>
</body>
</html>