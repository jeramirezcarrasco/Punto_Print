<?php
require __DIR__ . '../../utility.php';
date_default_timezone_set('America/Chihuahua');

$target_dir = ["../referencias/", "../disenos/"];
$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

$Cliente_ID = subir_Cliente($connection);
$Orden_ID = subir_Orden($Cliente_ID, $connection);
$new_Productos_indexes = new_Productos_indexes();
$productos_ids = subir_Productos($connection, $Orden_ID, $target_dir, $new_Productos_indexes);
print_HTML($Orden_ID);


function subir_Cliente($connection)
{
    
    if(isset($_POST["nombre"]))
    {
        $nombre = str_replace(array("\n", "\r"), '', $_POST["nombre"]);
        $correo = $_POST["correo"];
        $telefono = $_POST["telefono"];
        $celular = $_POST["celular"];
        $empresa = $_POST["empresa"];
        $query = "INSERT INTO cliente (Cliente_Correo, Nombre, Empresa, Telefono, Celular)". " VALUES 
        ('$correo','$nombre', '$empresa', '$telefono', '$celular')";
        $result = $connection->query($query);
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }

        $Cliente_ID = $connection->insert_id;
    }
    else
    {
        $Cliente_ID = $_POST["prev_Client"];
    }
    
    return $Cliente_ID;
}

function subir_Orden($Cliente_ID, $connection)
{
    $prioridad = $_POST["prioridad"];
    $total = $_POST["total"];
    // $fecha_pedido = $_POST["fecha_pedido"];
    $fecha_pedido = date("Y-m-d H:i:s"); 
    echo($fecha_pedido);
    $fecha_entrega = $_POST["fecha_entrega"];
    $query = "INSERT INTO orden (Fecha_Pedido, Fecha_Entrega, Total, Prioridad, Cliente_ID)". " VALUES 
    ('$fecha_pedido','$fecha_entrega', '$total', '$prioridad', '$Cliente_ID')";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $Orden_ID = $connection->insert_id;
    return $Orden_ID;
}

function subir_Productos($connection, $Orden_ID, $target_dir, $new_Productos_indexes)
{
    $productos_ids = [];

    foreach($new_Productos_indexes as $index)
    {
        $Descripcion = $_POST["Descripcion_".$index];
        $Produccion = $_POST["produccion_".$index];
        $cantidad = $_POST["cantidad_".$index];
        $Punit = $_POST["Punit_".$index];
        $Importe = $_POST["importe_".$index];
        $Statuss = $_POST["statuss_".$index];
        $query = "INSERT INTO producto (Cantidad, Precio_Unidad, Importe, Descripcion, Orden_ID, Area_Produccion, statuss)". " VALUES 
        ('$cantidad','$Punit', '$Importe', '$Descripcion', '$Orden_ID','$Produccion', '$Statuss')";

        $result = $connection->query($query);
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }

        $producto_ID = $connection->insert_id;
        $productos_ids[] = $producto_ID;

        $file = "file_REF_".$index;
        subir_Referencia($connection, $producto_ID, $file, $target_dir[0]);
        $file = "file_DIS_".$index;
        subir_Diseno($connection, $producto_ID, $file, $target_dir[1]);
        $index++;
    }

    return $productos_ids;
}

function subir_Referencia($connection, $producto_ID, $file, $target_dir)
{
    $size = count($_FILES[$file]['name']);
    for ($i=0; $i < $size; $i++) 
    { 
        $name = basename($_FILES[$file]["name"][$i]);
        if($name == "")
        {
            continue;
        }
        $target_file = file_naming($target_dir . $name, pathinfo($name,PATHINFO_EXTENSION));
        $file_result  = move_uploaded_file($_FILES[$file]['tmp_name'][$i], $target_file);
        $query = "INSERT INTO referencias (file_path, Producto_ID, Image_name)". " VALUES 
        ('$target_file', '$producto_ID', '$name')";

        $result = $connection->query($query);
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }
    }
}

function subir_Diseno($connection, $producto_ID, $file, $target_dir)
{
    $size = count($_FILES[$file]['name']);
    for ($i=0; $i < $size; $i++) 
    { 
        $name = basename($_FILES[$file]["name"][$i]);
        if($name == "")
        {
            continue;
        }
        $target_file = file_naming($target_dir . $name, pathinfo($name,PATHINFO_EXTENSION));
        $file_result  = move_uploaded_file($_FILES[$file]['tmp_name'][$i], $target_file);
        $query = "INSERT INTO disenos (file_path, Producto_ID, Image_name)". " VALUES 
        ('$target_file', '$producto_ID', '$name')";

        $result = $connection->query($query);
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }
    }
}

function new_Productos_indexes()
{
    $result = [];
    $startWith = 'Descripcion';
    foreach($_POST as $key => $value){
        $exp_key = explode('_', $key);
        if($exp_key[0] == $startWith){
            $result[] = $exp_key[1];
        }
    }
  
    return $result;
}



function print_HTML($Orden_ID)
{
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
        </head>
        <body>
            <form id="myForm" class="main_body" method="post" action="../list/list_Ordenes.php" enctype='multipart/form-data'> 
                <input type="hidden" name="Orden_ID" value='$Orden_ID'>
            </form> 
            <script type="text/javascript">
                document.getElementById('myForm').submit();
            </script>
        </body>
    </html>
    _END;
}

// function print_HTML()
// {
//     $nav = print_Navbar();

//     echo <<<_END
//     <!DOCTYPE html>
//     <html lang="en">
//         <head>
//             <link rel="stylesheet" href="../editar/formulario_style.css">
//             <meta charset="UTF-8">
//             <meta http-equiv="X-UA-Compatible" content="IE=edge">
//             <meta name="viewport" content="width=device-width, initial-scale=1.0">
//             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
//             <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
//             <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
//             <title>Formulario</title>
//         </head>
//         <body>
//             $nav
//             <div id="Cliente_result">
//                 <h1> Cliente </h1>
//                 <h5>Nombre</h5>
//                 <p>Jorge Ramirez Carrasco</p>
//                 <h6>Empresa</h6>
//                 <p>fhnrkhjsgbj</p>
//                 <h6>Telefono/Celular</h6>
//                 <p>Tel: 6564229929 <br/>
//                    Cel: 6564229929
//                 </p>
//                 <h6>Correo<h6>
//             </div>


//         </body>
//     </html>
//     _END;
// }