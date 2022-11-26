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

$query ="SELECT * FROM disenos_reciclaje"; 

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
        $Diseno_ID = $row["Diseno_ID"];
        $table_row = $table_row . "<td>" .$Diseno_ID . "</td>";
        $Image_name = $row['Image_name'];
        $table_row = $table_row . "<td>" .$Image_name . "</td>";
        $file_path = $row['file_path'];
        $file_path = str_replace(" ","%20",$file_path);

        //Image

        $table_row = $table_row . "<td>" . 
        <<<_END
        <div>
            <button onclick=hide_image("ref$j") class='show_reference'>+</button>
            <img src= ../$file_path id=ref$j class='images' hidden >
        </div>
        _END   
            . "</td>";
        //Recuperar
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../reciclaje/restaurar.php'> 
            <input type='hidden' name='Restaurar_Diseno' value='$Diseno_ID'>" .
            <<<_END
                <input type='submit' value=' - ' onclick="return confirm('Seguro que quieres Recuperar producto y sus dependientes?')">
            _END
        . "</form> ". "</td>";

        //BORRAR
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='../reciclaje/borrar_permanente.php'> 
            <input type='hidden' name='Borrar_Diseno' value='$Diseno_ID'>" .
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

            <title>Disenos Reciclaje</title>
    </head>
    <body>
        $nav

        <h2> Diseños </h2>

        <table class="table table-bordered table-hover" id="MainTable">
            <thead>
                <tr>
                    <th>Diseño ID</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Restaurar</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody>
                $table_productos
            </tbody>
        </table>               
        <script src="../reciclaje/hide_image.js"></script> 
    </body>
    </html>
    _END;

}