<?php
require __DIR__ . '../../utility.php';

session_start();

if(!Is_Session_Active())
{
    header("Location: ../login/login.php");
}
else if($_SESSION['permiso'] < 1)
{
    header("Location: ../list_pantalla/list_pantalla_Productos.php");
}


$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);
$PermitirFinalizados = false;
if(!$connection){
    die('Could not connect: '. mysqli_error($connection));
}
if (isset($_POST['pantalla_Orden_ID'])){
    $Orden_ID = $_POST['pantalla_Orden_ID'];
    $query ="SELECT * FROM producto WHERE Orden_ID = '$Orden_ID' and ( statuss = 'Produccion' OR statuss = 'Espera' )";
    $PermitirFinalizados = true;
}
else{
    $query ="SELECT * FROM producto WHERE statuss = 'Produccion' or statuss = 'Espera'"; 
}

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}


$table_productos = create_Table($connection, $result, $PermitirFinalizados);
$table_Score = empleados_Score($connection);

print_HTML($table_productos, $table_Score);

function empleados_Score($connection)
{
    $query ="SELECT * FROM empleados WHERE Permiso < 4"; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $table = "<thead><tr>";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $table .= "<td>" . $row['Nombre'] ."</td>";
    }
    $table .= "</tr></thead><tbody><tr>";
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $name =  $row['Nombre'];
        $query_numero_productos = "SELECT Area_Produccion FROM producto WHERE Area_Produccion = '$name'";
        $productos_result = $connection->query($query_numero_productos);
        $count = $productos_result->num_rows;
        if($count < 1)
        {
           $table .= "<td>  $count </td>" ;
        }
        else if ($count < 2)
        {
            $table .= "<td class='table-primary'>  $count </td>" ;
        }
        else if ($count < 3)
        {
            $table .= "<td class='table-success'>  $count </td>" ;
        }
        else
        {
            $table .= "<td class='table-danger'>  $count </td>" ;
        }
        
    }
    $table .= "</tr></tbody>";
    return $table;
}

function create_Table($connection, $result, $PermitirFinalizados)
{
    $table_productos = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $statuss = $row['statuss'];
        if($statuss == "Finalizado" and !$PermitirFinalizados)
        {
            continue;
        }
        else if($statuss == "Finalizado")
        {
            $table_row = "<tr class='table-warning'>";    
        }
        else{$table_row = "<tr>";}
        $producto_ID = $row["Producto_ID"];
        $table_row = $table_row . "<td>" .$producto_ID . "</td>";
        $Orden_ID = $row["Orden_ID"];
        $table_row = $table_row . "<td>" .$Orden_ID . "</td>";
        $cantidad = $row['Cantidad'];
        $table_row = $table_row . "<td>" .$cantidad . "</td>";
        $precio_Unidad = $row['Precio_Unidad'];
        $table_row = $table_row . "<td>" .$precio_Unidad . "</td>";
        $importe = $row['Importe'];
        $table_row = $table_row . "<td>" .$importe . "</td>";
        
        $table_row = $table_row . "<td>" .$statuss . "</td>";
        $descripcion = $row['Descripcion'];
        $table_row = $table_row . "<td>" .$descripcion . "</td>";
        $orden_ID = $row['Orden_ID'];
        //REFERENCIA / DISENOS
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../list_pantalla/pantalla_ref&disenos.php'> 
            <button type='submit' name='pantalla_Producto_ID' value='$producto_ID'>-</button>
        </form> ". "</td>";

       
        
        $table_row = $table_row . "</tr> ";
        $table_productos = $table_productos . $table_row;
    }

    return $table_productos;
}

function print_HTML($table_productos, $table_Score)
{
    $nav = print_pantalla_Navbar();
    echo <<<_END
    <html>
    <head>
        <meta http-equiv="refresh" content="120" > 
        <link rel="stylesheet" href="../list/List_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" type="text/css" href="../DataTables/datatables.css">
        <script type="text/javascript" charset="utf8" src="../DataTables/datatables.js"></script>
        <script src="../list/Table_Config.js"></script>

        <title>Productos</title> 
    </head>
    <body>
        $nav

        <table class="table table-bordered table-hover display text-center" style="width:50%; margin: 0 auto;"   >
            $table_Score
        </table>

        <h2> Productos </h2>

        <table class="table table-bordered table-hover" id="MainTable">
            <thead>
                <tr>
                    <th>Producto ID</th>
                    <th>Orden ID</th>
                    <th>Cantidad</th>
                    <th>Precio_Unidad</th>
                    <th>Importe</th>
                    <th>Status</th>
                    <th>Descripcion</th>
                    <th>Referencias/Diseño</th>
                </tr>
            </thead>
            <tbody>
                $table_productos
            </tbody>
        </table>               
            
    </body>
    </html>
    _END;

}