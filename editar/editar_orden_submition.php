<?php
require __DIR__ . '../../utility.php';

$target_dir = ["../referencias/","../disenos/"];
$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['orden_edit_ID']))
{
    
    $orden_ID = edit_Orden($connection);
    edit_Productos($connection, $orden_ID,$target_dir);
    $new_Productos_indexes = new_Productos_indexes();
    if(count($new_Productos_indexes) > 0)
    {
        add_Productos($connection, $orden_ID, $target_dir, $new_Productos_indexes);
    }
    
    print_HTML($orden_ID );
}
else if(isset($_POST['Finalizard_Orden']))
{
    // $orden_ID = $_POST['Finalizard_Orden'];
    finalizard_Orden($connection);
    // print_HTML_Finalizado($orden_ID );
    header("Location: ../finalizado/list_Ordenes_Finalizado.php");
}
else if(isset($_POST['Restablecer_Orden_Status']))
{
    restablecer_Orden_status($connection);
    header("Location: ../list/list_Ordenes.php");
}
else{
    header("Location: ../list/list_Ordenes.php");
}

function restablecer_Orden_status($connection)
{
    $Orden_ID = $_POST['Restablecer_Orden_Status'];
    $query = "UPDATE orden SET statuss = 'Produccion' WHERE Orden_ID = '$Orden_ID' ";
    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    
}
function finalizard_Orden($connection)
{
    $orden_ID = $_POST['Finalizard_Orden'];
    $query = "UPDATE orden SET statuss = 'Finalizado' WHERE Orden_ID = '$orden_ID' ";
    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query = "UPDATE producto SET statuss = 'Finalizado' WHERE Orden_ID = '$orden_ID' ";
    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    
}

function edit_Orden($connection)
{
    $orden_ID = $_POST['orden_edit_ID'];
    $fecha_pedido = $_POST["fecha_pedido"];
    $fecha_entrega = $_POST["fecha_entrega"];
    $prioridad = $_POST["prioridad"];
    $total = $_POST["total"];

    $query = "UPDATE orden SET Fecha_Pedido = '$fecha_pedido', Fecha_Entrega = '$fecha_entrega', Total = '$total', 
    Prioridad = '$prioridad' WHERE Orden_ID = '$orden_ID' ";

    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    return $orden_ID;
}

function edit_Productos($connection, $orden_ID, $target_dir)
{
    
    $query ="SELECT * FROM producto WHERE Orden_ID = '$orden_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $producto_ID = $row["Producto_ID"];
        $Editar_cantidad = $_POST['Editar_cantidad_'.$j];
        $Editar_Punit = $_POST['Editar_Punit_'.$j];
        $Editar_importe = $_POST['Editar_importe_'.$j];
        $Editar_Descriptcion = $_POST['Editar_Descripcion_'.$j];
        $Editar_Produccion = $_POST['Editar_produccion_'.$j];
        $Editar_statuss = $_POST['Editar_statuss_'.$j];
        
        $query = "UPDATE producto SET Cantidad = '$Editar_cantidad', Precio_Unidad = '$Editar_Punit', Importe = '$Editar_importe', 
        Descripcion = '$Editar_Descriptcion', Area_Produccion = '$Editar_Produccion' , statuss = '$Editar_statuss' WHERE Producto_ID = '$producto_ID' ";

        $Edit_result = $connection->query($query);

        if(!$Edit_result) {
            die('Could not query: '. mysqli_error($connection));
        }

        $file = "Editar_file_Ref".$j;

        $size = count($_FILES[$file]['name']);
        for ($i=0; $i < $size; $i++) 
        { 
            $name = basename($_FILES[$file]["name"][$i]);
            if($name == "")
            {
                continue;
            }
            $target_file = file_naming($target_dir[0] . $name, pathinfo($name,PATHINFO_EXTENSION));
            $file_result  = move_uploaded_file($_FILES[$file]['tmp_name'][$i], $target_file);
            $query = "INSERT INTO referencias (file_path, Producto_ID, Image_name)". " VALUES 
            ('$target_file', '$producto_ID', '$name')";

            $file_result = $connection->query($query);
            if(!$file_result) {
                die('Could not query: '. mysqli_error($connection));
            }
        }

        $file = "Editar_file_Dis".$j;

        $size = count($_FILES[$file]['name']);
        for ($i=0; $i < $size; $i++) 
        { 
            $name = basename($_FILES[$file]["name"][$i]);
            if($name == "")
            {
                continue;
            }
            $target_file = file_naming($target_dir[1] . $name, pathinfo($name,PATHINFO_EXTENSION));
            $file_result  = move_uploaded_file($_FILES[$file]['tmp_name'][$i], $target_file);
            echo "or HERE";
            $query = "INSERT INTO disenos (file_path, Producto_ID, Image_name)". " VALUES 
            ('$target_file', '$producto_ID', '$name')";

            $file_result = $connection->query($query);
            if(!$file_result) {
                die('Could not query: '. mysqli_error($connection));
            }
        }
    }
    
}

function add_Productos($connection, $orden_ID, $target_dir, $new_Productos_indexes)
{
    
    foreach($new_Productos_indexes as $index)
    {
        $Descripcion = $_POST["Descripcion_".$index];
        $Produccion = $_POST["produccion_".$index];
        $cantidad = $_POST["cantidad_".$index];
        $Punit = $_POST["Punit_".$index];
        $Importe = $_POST["importe_".$index];
        $Statuss = $_POST["statuss_".$index];
        $query = "INSERT INTO producto (Cantidad, Precio_Unidad, Importe, Descripcion, Orden_ID, Area_Produccion, statuss)". " VALUES 
        ('$cantidad','$Punit', '$Importe', '$Descripcion', '$orden_ID','$Produccion', '$Statuss')";

        $result = $connection->query($query);
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }

        $file = "file_REF_".$index;
        $producto_ID = $connection->insert_id;

        $size = count($_FILES[$file]['name']);
        for ($i=0; $i < $size; $i++) 
        { 
            $name = basename($_FILES[$file]["name"][$i]);
            if($name == "")
            {
                continue;
            }
            $target_file = file_naming($target_dir[0] . $name, pathinfo($name,PATHINFO_EXTENSION));
            $file_result  = move_uploaded_file($_FILES[$file]['tmp_name'][$i], $target_file);
            $query = "INSERT INTO referencias (file_path, Producto_ID, Image_name)". " VALUES 
            ('$target_file', '$producto_ID', '$name')";

            $result = $connection->query($query);
            if(!$result) {
                die('Could not query: '. mysqli_error($connection));
            }
        }
        $file = "file_DIS_".$index;

        $size = count($_FILES[$file]['name']);
        for ($i=0; $i < $size; $i++) 
        { 
            $name = basename($_FILES[$file]["name"][$i]);
            if($name == "")
            {
                continue;
            }
            $target_file = file_naming($target_dir[1] . $name, pathinfo($name,PATHINFO_EXTENSION));
            $file_result  = move_uploaded_file($_FILES[$file]['tmp_name'][$i], $target_file);
            echo("HERE");
            $query = "INSERT INTO disenos (file_path, Producto_ID, Image_name)". " VALUES 
            ('$target_file', '$producto_ID', '$name')";

            $result = $connection->query($query);
            if(!$result) {
                die('Could not query: '. mysqli_error($connection));
            }
        }

        $index++;
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

function print_HTML($orden_ID )
{
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
        </head>
        <body>
            <form id="myForm" class="main_body" method="post" action="../list/list_Ordenes.php" enctype='multipart/form-data'> 
                <input type="hidden" name="Orden_ID" value='$orden_ID'>
            </form> 
            <script type="text/javascript">
                document.getElementById('myForm').submit();
            </script>
        </body>
    </html>
    _END;
}


function print_HTML_Finalizado($orden_ID )
{
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
        </head>
        <body>
            <form id="myForm" class="main_body" method="post" action="../finalizado/list_Ordenes_Finalizado.php" enctype='multipart/form-data'> 
                <input type="hidden" name="Orden_ID" value='$orden_ID'>
            </form> 
            <script type="text/javascript">
                document.getElementById('myForm').submit();
            </script>
        </body>
    </html>
    _END;
}