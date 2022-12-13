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
        echo "";
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtnombre=(isset($_POST['txtnombre']))?$_POST['txtnombre']:"";
$txtespecie=(isset($_POST['txtespecie']))?$_POST['txtespecie']:"";
$txtubicacion=(isset($_POST['txtubicacion']))?$_POST['txtubicacion']:"";
$txtriesgo=(isset($_POST['txtriesgo']))?$_POST['txtriesgo']:"";
$txtimg=(isset($_FILES['txtimg']['name']))?$_FILES['txtimg']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

switch ($accion) {
    case "Agregar":
        $sentenciaSQL =  $conexion->prepare("INSERT INTO planta (nombre,especie,ubicacion,riesgo,img ) VALUES (:nombre,:especie,:ubicacion,:riesgo,:img);");
        $sentenciaSQL->bindParam(':nombre',$txtnombre);
        $sentenciaSQL->bindParam(':especie',$txtespecie);
        $sentenciaSQL->bindParam(':ubicacion',$txtubicacion);
        $sentenciaSQL->bindParam(':riesgo',$txtriesgo);
        $fecha = new DataTime();
        $nombreArchivo = ($txtimg!="")?$fecha->getTimestamp()."_".$_FILES["txtimg"]["name"]:"imagen.jpg";
        $tmpimagen = $_FILES["txtimg"]["tmp_name"];
        if($tmpimagen!=""){

                move_uploaded_file($tmpimagen,"catalogo/img/".$nombreArchivo);
        }
        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->execute();
        header("Location:plantas.php");
        break;
    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE planta SET nombre=:nombre WHERE ID=:ID");
        $sentenciaSQL->bindParam(':nombre',$txtnombre);
        $sentenciaSQL->bindParam(':ID',$txtID);
        $sentenciaSQL->execute();

        if($txtimg!=""){

            $fecha = new DataTime();
            $nombreArchivo = ($txtimg!="")?$fecha->getTimestamp()."_".$_FILES["txtimg"]["name"]:"imagen.jpg";
            $tmpimagen = $_FILES["txtimg"]["tmp_name"];

            move_uploaded_file($tmpimagen,"catalogo/img/".$nombreArchivo);
            $sentenciaSQL =  $conexion->prepare("SELECT imagen FROM planta WHERE ID=:ID");
            $sentenciaSQL->bindParam(':ID',$txtID);
            $sentenciaSQL->execute();
            $listaplanta = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
    
            if(isset($listaplanta["imagen"]) && ($listaplanta["imagen"]!="imagen.jpg")){
                if (file_exists("catalogo/img/".$listaplanta["imagen"])) {
                    
                    unlink("./catalogo/img/".$listaplanta["imagen"]);
    
                }
            }

            $sentenciaSQL = $conexion->prepare("UPDATE planta SET imagen=:imagen WHERE ID=:ID");
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->bindParam(':ID',$txtID);
            $sentenciaSQL->execute();
        }
        header("Location:plantas.php");
        break;
    case "Cancelar":
        header("Location:plantas.php");
        break;
    case "Seleccionar":
        $sentenciaSQL =  $conexion->prepare("SELECT * FROM planta WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID',$txtID);
        $sentenciaSQL->execute();
        $listaplanta = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtnombre = $listaplanta['nombre'];
        $txtespecie = $listaplanta['especie'];
        $txtubicacion = $listaplanta['ubicacion'];
        $txtriesgo = $listaplanta['riesgo'];
        $txtimg = $listaplanta['imagen'];
        break;
    case "Borrar":
        $sentenciaSQL =  $conexion->prepare("SELECT imagen FROM planta WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID',$txtID);
        $sentenciaSQL->execute();
        $listaplanta = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($planta["imagen"]) && ($planta["imagen"]!="imagen.jpg")){
            if (file_exists("./catalogo/img/".$planta["imagen"])) {
                
                unlink("./catalogo/img/".$planta["imagen"]);

            }
        }

        $sentenciaSQL = $conexion->prepare("SELECT * FROM planta WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID',$txtID);
        $sentenciaSQL->execute();
        header("Location:plantas.php");
        break;
}

$sentenciaSQL = $conexion->prepare("SELECT * FROM planta");
$sentenciaSQL ->execute();
$listaplantas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="en">
  <head>
    <title>Inicio Administrador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <?php 
        $url="http://".$_SERVER['HTTP_HOST']."/catalogo"
    ?>
    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="#">Administrados del sitio web <span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="<?php echo $url;?>./administrador/inicio.php">Inicio</a>
            <a class="nav-item nav-link" href="<?php echo $url;?>./administrador/seccion/plantas.php">Tipos de plantas</a>
            <a class="nav-item nav-link" href="<?php echo $url;?>./administrador/seccion/cerrar.php">Cerrar</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>">Ver sitio web</a>
        </div>
    </nav>
    <br/>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                Datos
            </div>

            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class = "form-group">
                    <label for="txtID">ID:</label>
                    <input type="text" required readonly class="form-control" value="<?php echo $txtID ?>" name="txtID" placeholder="ID">
       
                    <div class = "form-group">
                    <label for="txtnombre">Nombre:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtnombre ?>" name="txtnombre" placeholder="Nombre">

                    <div class = "form-group">
                    <label for="txtespecie">Especie:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtespecie ?>" name="txtespecie" placeholder="Especie">

                    <div class = "form-group">
                    <label for="txtubicacion">Ubicacion:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtubicacion ?>" name="txtubicacion" placeholder="Ubicacion">

                    <div class = "form-group">
                    <label for="txtriesgon">Riesgo:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtriesgo ?>" name="txtriesgo" placeholder="Riesgo">

                    <div class = "form-group">
                    <label for="txtimg">Imagen:</label>
                    <br/>
                    <?php echo $txtimg ?>
                    <?php if ($txtimg!="") { ?>
                    <img class="img-thumbnail rounded" src="../../img/<?php echo $txtimg; ?>" width="50" alt="" srcset="">
                    <?php } ?>
                    <input type="File" class="form-control" name="txtimg" id="txtimg" placeholder="Imagen">
                    <br>

       

                    <div class="btn-group" role="group" aria-label="">
                        <button type="button" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
                        <button type="button" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
                        <button type="button" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Cancelar" class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <br/><br/> 
    <div class="col-md-7">
        <table class="table table-bordered" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Ubicacion</th>
                    <th>Riesgo</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($listaplantas as $planta) { ?>
                <tr>
                    <td><?php echo $planta['ID']; ?></td>
                    <td><?php echo $planta['nombre']; ?></td>
                    <td><?php echo $planta['especie']; ?></td>
                    <td><?php echo $planta['ubicacion']; ?></td>
                    <td><?php echo $planta['riesgo']; ?></td>
                    <td>
                        <img class="img-thumbnail rounded" src="./catalogo/img/<?php echo $planta['imagen']; ?>" width="50" alt="" srcset="">
                        
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="txtID" id="txtID" value="<?php echo $planta['ID']; ?>"/>
                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>