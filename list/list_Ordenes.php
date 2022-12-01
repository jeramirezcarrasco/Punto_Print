<?php
require_once  __DIR__ . '../../utility.php';

session_start();

if(!Is_Session_Active())
{
    header("Location: ../login/login.php");
}
else if($_SESSION['permiso'] < 2)
{
    header("Location: ../list_pantalla/list_pantalla_Productos.php");
}

$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);
$PermitirFinalizados = false;
if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['Cliente_ID']))
{
    $Cliente_ID = $_POST['Cliente_ID'];
    $query ="SELECT * FROM orden WHERE Cliente_ID = '$Cliente_ID' "; 
    $PermitirFinalizados = true;
}
elseif (isset($_POST['Orden_ID'])){
    $Orden_ID = $_POST['Orden_ID'];
    $query ="SELECT * FROM orden WHERE Orden_ID = '$Orden_ID' "; 
}
else
{
    $query ="SELECT * FROM orden"; 
}

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}


$table_ordenes = create_Table($connection, $result, $PermitirFinalizados );
$table_Score = empleados_Score($connection);
print_HTML($table_ordenes, $table_Score);

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
    $table_ordenes = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $table_row = "<tr>";
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
        
        $cliente_ID = $row['Cliente_ID'];
        $orden_ID = $row['Orden_ID'];
        $table_row = $table_row . "<td>" .$orden_ID . "</td>";
        $fecha_Pedido = $row['Fecha_Pedido'];
        $table_row = $table_row . "<td>" .$fecha_Pedido . "</td>";
        $fecha_Entrega = $row['Fecha_Entrega'];
        $table_row = $table_row . "<td>" .$fecha_Entrega . "</td>";
        $total = $row['Total'];
        $table_row = $table_row . "<td>" .$total . "</td>";
        $prioridad = $row['Prioridad'];
        $table_row = $table_row . "<td>" .$prioridad . "</td>";
        
        
    
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../list/list_Clientes.php'> 
            <button type='submit' name='Cliente_ID' value='$cliente_ID'>-</button>
        </form> ". "</td>";
    
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../list/list_Productos.php'> 
            <button type='submit' name='Orden_ID' value='$orden_ID'>-</button>
        </form> ". "</td>";

        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../pdf_edit/pdf.php'> 
            <button type='submit' name='Orden_ID' value='$orden_ID'>-</button>
        </form> ". "</td>";

        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../editar/editar_orden.php'> 
            <button type='submit' name='Orden_Edit' value='$orden_ID'>-</button>
        </form> ". "</td>";


        //BORRAR
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../editar/borrar.php'> 
            <input type='hidden' name='Borrar_Orden' value='$orden_ID'>" .
            <<<_END
                <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres borrar?')">
            _END
        . "</form> ". "</td>";
    
        
        $table_row = $table_row . "</tr>";
        $table_ordenes = $table_ordenes . $table_row;
    }

    return $table_ordenes;
}

function print_HTML($table_ordenes, $table_Score)
{
    $nav = print_Navbar();
    echo <<<_END
    <html>
    <head>
            
        <link rel="stylesheet" href="../list/List_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        

        <link rel="stylesheet" type="text/css" href="../DataTables/datatables.css">
        <script type="text/javascript" charset="utf8" src="../DataTables/datatables.js"></script>
        <script src="../list/Table_Config.js"></script>

        <title>Ordenes</title>
    </head>
    <body>
        $nav

        <table class="table table-bordered table-hover display text-center" style="width:50%; margin: 0 auto;"   >
            $table_Score
        </table>
        
        <h2> Ordenes </h2>
        <table class="table table-bordered table-hover display" id="MainTable" style="width:100%">
            <thead>
                <tr>
                    <th>Orden ID</th>
                    <th>Fecha Pedido</th>
                    <th>Fecha Entrega</th>
                    <th>Total</th>
                    <th>Prioridad</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>PDF</th>
                    <th>Editar</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody>
                $table_ordenes
            </tbody>            
        </table> 
        
        
    </body>
    </html>
    _END;

}