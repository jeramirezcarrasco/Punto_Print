<?php
require __DIR__ . '../../utility.php';

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

if(!$connection){
    die('Could not connect: '. mysqli_error($connection));
}

$query ="SELECT * FROM producto_reciclaje"; 

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}


$table_productos = create_Table($connection, $result);
print_HTML($table_productos);



function create_Table($connection, $result)
{
    $table_productos = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $table_row = "<tr>";
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $producto_ID = $row["Producto_ID"];
        $table_row = $table_row . "<td>" .$producto_ID . "</td>";
        $cantidad = $row['Cantidad'];
        $table_row = $table_row . "<td>" .$cantidad . "</td>";
        $precio_Unidad = $row['Precio_Unidad'];
        $table_row = $table_row . "<td>" .$precio_Unidad . "</td>";
        $importe = $row['Importe'];
        $table_row = $table_row . "<td>" .$importe . "</td>";
        $statuss = $row['statuss'];
        $table_row = $table_row . "<td>" .$statuss . "</td>";
        $Area_Produccion = $row['Area_Produccion'];
        $table_row = $table_row . "<td>" .$Area_Produccion . "</td>";
        $descripcion = $row['Descripcion'];
        $table_row = $table_row . "<td>" .$descripcion . "</td>";
        $orden_ID = $row['Orden_ID'];
        //REFERENCIA
        // $table_row = $table_row . "<td>" . 
        // "<form method='post' action='../list/ref&disenos.php'> 
        //     <input type='hidden' name='Producto_ID' value='$producto_ID'>
        //     <input type='submit' value=' - '>
        // </form> ". "</td>";

        //Recuperar
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../reciclaje/restaurar.php'> 
            <input type='hidden' name='Restaurar_Producto' value='$producto_ID'>" .
            <<<_END
                <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres Recuperar producto y sus dependientes?')">
            _END
        . "</form> ". "</td>";

        //BORRAR
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../reciclaje/borrar_permanente.php'> 
            <input type='hidden' name='Borrar_Producto' value='$producto_ID'>" .
            <<<_END
                <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres borrar permanentemente?')">
            _END
        . "</form> ". "</td>";

        $table_row = $table_row . "</tr> ";
        $table_productos = $table_productos . $table_row;
    }

    return $table_productos;
}

function print_HTML($table_productos)
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

            <title>Productos Reciclaje</title>
    </head>
    <body>
        $nav

        <h2> Productos </h2>

        <table class="table table-bordered table-hover" id="MainTable">
            <thead>
                <tr>
                    <th>Producto ID</th>
                    <th>Cantidad</th>
                    <th>Precio_Unidad</th>
                    <th>Importe</th>
                    <th>Status</th>
                    <th>Descripcion</th>
                    <th>Restaurar</th>
                    <th>Borrar</th>
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