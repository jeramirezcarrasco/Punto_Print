<?php
require __DIR__ . '../../utility.php';

$target_dir_referencias = "../referencias/";
$target_dir_disenos = "../disenos/";
$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['producto_edit_ID']))
{
    
    $producto_ID = edit_Producto($connection);
    edit_Referencias($connection, $producto_ID, $target_dir_referencias);
    edit_Disenos($connection, $producto_ID, $target_dir_disenos);
    

    print_HTML($producto_ID );
}
else if(isset($_POST['Finalizard_Producto']))
{
    // $Producto_ID = $_POST['Finalizard_Producto'];
    finalizard_Producto($connection);
    // print_HTML_Finalizado($Producto_ID );
    header("Location: ../finalizado/list_Productos_Finalizado.php");
}
else if(isset($_POST['Restablecer_Producto_Status']))
{
    
    restablecer_Producto_status($connection);
    header("Location: ../list/list_Productos.php");
}
else{
    header("Location: ../list/list_Productos.php");
}

function finalizard_Producto($connection)
{
    $Producto_ID = $_POST['Finalizard_Producto'];
    $query = "UPDATE producto SET statuss = 'Finalizado' WHERE Producto_ID = '$Producto_ID' ";
    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    
}
function restablecer_Producto_status($connection)
{
    $Producto_ID = $_POST['Restablecer_Producto_Status'];
    $query = "UPDATE producto SET statuss = 'Produccion' WHERE Producto_ID = '$Producto_ID' ";
    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $Orden_ID = $_POST['Orden_ID_Restablecer'];
    $query = "UPDATE orden SET statuss = 'Pendiente' WHERE Orden_ID = '$Orden_ID' ";
    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    
}

function edit_Producto($connection)
{
    $producto_ID = $_POST['producto_edit_ID'];
    $descripcion = $_POST["Descripcion"];
    $produccion = $_POST["produccion"];
    $cantidad = $_POST["cantidad"];
    $punit = $_POST["Punit"];
    $importe = $_POST["importe"];
    $statuss = $_POST["statuss"];

    $query = "UPDATE producto SET Cantidad = '$cantidad', Precio_Unidad = '$punit', Importe = '$importe', 
    Descripcion = '$descripcion', Area_Produccion = '$produccion',  statuss = '$statuss' WHERE Producto_ID = '$producto_ID' ";

    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    return $producto_ID;
}
function edit_Disenos($connection, $producto_ID, $target_dir)
{
    $query = "SELECT * FROM disenos WHERE Producto_ID = '$producto_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = $rows  ; $j > -1 ; --$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Diseno_ID = $row['Diseno_ID'];
        if(isset($_POST['dis'.$Diseno_ID]))
        {
            $query_copy = "INSERT INTO disenos_reciclaje (Diseno_ID, file_path, Producto_ID, Image_name)
            SELECT Diseno_ID, file_path, Producto_ID, Image_name FROM disenos WHERE Diseno_ID = '$Diseno_ID'";
            $copy_result = $connection->query($query_copy);
            if(!$copy_result) {
                die('Could not query: '. mysqli_error($connection));
            }

            $delete_query = "DELETE FROM disenos WHERE Diseno_ID  = '$Diseno_ID'";
            $delete_result = $connection->query($delete_query);
            if(!$delete_result) {
                die('Could not query: '. mysqli_error($connection));
            }
        }
    }

    ///////////////////////////////////////////////////////////////////

    $file = "file_DIS_";

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

function edit_Referencias($connection, $producto_ID, $target_dir)
{
    
    $query = "SELECT * FROM referencias WHERE Producto_ID = '$producto_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = $rows  ; $j > -1 ; --$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Referencia_ID = $row['Referencia_ID'];
        if(isset($_POST['ref'.$Referencia_ID]))
        {
            $query_copy = "INSERT INTO referencias_reciclaje (Referencia_ID, file_path, Producto_ID, Image_name)
            SELECT Referencia_ID, file_path, Producto_ID, Image_name FROM referencias WHERE Referencia_ID = '$Referencia_ID'";
            $copy_result = $connection->query($query_copy);
            if(!$copy_result) {
                die('Could not query: '. mysqli_error($connection));
            }

            $delete_query = "DELETE FROM referencias WHERE Referencia_ID  = '$Referencia_ID'";
            $delete_result = $connection->query($delete_query);
            if(!$delete_result) {
                die('Could not query: '. mysqli_error($connection));
            }
        }
    }

    ///////////////////////////////////////////////////////////////////

    $file = "file_REF_";

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

function print_HTML($producto_ID )
{
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
        </head>
        <body>
            <form id="myForm" class="main_body" method="post" action="../list/list_Productos.php" enctype='multipart/form-data'> 
                <input type="hidden" name="Producto_ID" value='$producto_ID'>
            </form> 
            <script type="text/javascript">
                document.getElementById('myForm').submit();
            </script>
        </body>
    </html>
    _END;
}

function print_HTML_Finalizado($Producto_ID )
{
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
        </head>
        <body>
            <form id="myForm" class="main_body" method="post" action="../finalizado/list_Productos_Finalizado.php" enctype='multipart/form-data'> 
                <input type="hidden" name="Producto_ID" value='$Producto_ID'>
            </form> 
            <script type="text/javascript">
                document.getElementById('myForm').submit();
            </script>
        </body>
    </html>
    _END;
}