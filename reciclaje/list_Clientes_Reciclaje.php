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

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

$query ="SELECT * FROM cliente_reciclaje"; 

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}


$table_clientes = create_Table($connection, $result);
print_HTML($table_clientes);



function create_Table($connection, $result)
{
    $table_clientes = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $table_row = "<tr>";
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $cliente_ID = $row['Cliente_ID'];
        $table_row = $table_row . "<td>" .$cliente_ID . "</td>";
        $nombre = $row['Nombre'];
        $table_row = $table_row . "<td>" .$nombre . "</td>";
        $correo = $row['Cliente_Correo'];
        $table_row = $table_row . "<td>" .$correo . "</td>";
        $empresa = $row['Empresa'];
        $table_row = $table_row . "<td>" .$empresa . "</td>";
        $telefono = $row['Telefono'];
        $table_row = $table_row . "<td>" .$telefono . "</td>";
        $celular = $row['Celular'];
        $table_row = $table_row . "<td>" .$celular . "</td>";

        //Recuperar
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../reciclaje/restaurar.php'> 
            <input type='hidden' name='Restaurar_Cliente' value='$cliente_ID'>" .
            <<<_END
                <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres restaurar el cliente ?')">
            _END
        . "</form> ". "</td>";

        //BORRAR
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../reciclaje/borrar_permanente.php'> 
            <input type='hidden' name='Borrar_Cliente' value='$cliente_ID'>" .
            <<<_END
                <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres borrar permanentemente?')">
            _END
        . "</form> ". "</td>";

        
        $table_row = $table_row . "</tr>";
        $table_clientes = $table_clientes . $table_row;
    }

    return $table_clientes;
}

function print_HTML($table_clientes)
{
    $nav = print_Restuarar_Navbar();
    echo <<<_END
    <html>
    <head>
            <link rel="stylesheet" href="../list/list_style.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
             
            <link rel="stylesheet" type="text/css" href="../DataTables/datatables.css">
            <script type="text/javascript" charset="utf8" src="../DataTables/datatables.js"></script>
            <script src="../list/Table_Config.js"></script>

            <title>Cleintes Reciclaje</title>
    </head>
    <body>
        $nav

        <h2> Clientes </h2>
        <table class="table table-bordered table-hover" id="MainTable">
            <thead>
                <tr>
                    <th>Cliente ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Empresa</th>
                    <th>Telefono</th>
                    <th>Celular</th>
                    <th>Restaurar</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody>
                $table_clientes
            </tbody>
        </table>
    </body>
    </html>
    _END;

}