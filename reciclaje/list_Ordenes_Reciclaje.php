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

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

$query ="SELECT * FROM orden_reciclaje"; 

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}


$table_ordenes = create_Table($connection, $result);
print_HTML($table_ordenes);



function create_Table($connection, $result)
{
    $table_ordenes = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $table_row = "<tr>";
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
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

        //Recuperar
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../reciclaje/restaurar.php'> 
            <input type='hidden' name='Restaurar_Orden' value='$orden_ID'>" .
            <<<_END
                <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres restaurar la orden y sus dependientes?')">
            _END
        . "</form> ". "</td>";

         //BORRAR
         $table_row = $table_row . "<td>" . 
         "<form method='post' action='../reciclaje/borrar_permanente.php'> 
             <input type='hidden' name='Borrar_Orden' value='$orden_ID'>" .
             <<<_END
                 <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres borrar permanentemente?')">
             _END
         . "</form> ". "</td>";
    
        
        $table_row = $table_row . "</tr>";
        $table_ordenes = $table_ordenes . $table_row;
    }

    return $table_ordenes;
}

function print_HTML($table_ordenes)
{
    $nav = print_Restuarar_Navbar();
    echo <<<_END
    <html>
    <head>
            <link rel="stylesheet" href="../list/List_style.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    
            <link rel="stylesheet" type="text/css" href="../DataTables/datatables.css">
            <script type="text/javascript" charset="utf8" src="../DataTables/datatables.js"></script>
            <script src="../list/Table_Config.js"></script>

            <title>Ordenes Reciclje</title> 
    </head>
    <body>
        $nav
        
        <h2> Ordenes </h2>
        <table class="table table-bordered table-hover" id="MainTable">
            <thead>
                <tr>
                    <th>Orden ID</th>
                    <th>Fecha Pedido</th>
                    <th>Fecha Entrega</th>
                    <th>Total</th>
                    <th>Prioridad</th>
                    <th>Restaurar</th>
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