<?php
require __DIR__ . '../../utility.php';

$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['Cliente_Edit']))
{
    $client_ID = $_POST['Cliente_Edit'];
    $query ="SELECT * FROM cliente WHERE Cliente_ID = '$client_ID' "; 
}

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}

$rows = $result->num_rows;
for ($j = 0 ; $j < $rows ; ++$j)
{
    $table_row = "<tr>";
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $nombre = $row['Nombre'];
    $correo = $row['Cliente_Correo'];
    $empresa = $row['Empresa'];
    $telefono = $row['Telefono'];
    $celular = $row['Celular'];


}
print_HTML($client_ID, $nombre, $correo, $empresa, $telefono, $celular);



function print_HTML($client_ID, $nombre, $correo, $empresa, $telefono, $celular)
{
    $nav = print_Navbar();
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <link rel="stylesheet" href="../editar/editar_style.css">
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
            
            <title>Editar Cliente</title>
        </head>
        <body>
            $nav
            <form class="main_body" method="post" action="../editar/editar_cliente_submition.php" enctype='multipart/form-data'> 
                <div id="client_Tab" class="section">
                    <h2 class="section_Header">Editar Cliente</h2>

                    <p style="display: inline-block;">Nombre <span class="reqq">*</span></p>
                    <input type="text" name="nombre" value=$nombre required>

                    <br>

                    <p style="display: inline-block;">Correo <span class="reqq">*</span></p>
                    <input type="text" name="correo" value=$correo required>

                    <p>Empresa</p>
                    <input type="text" name="empresa" value=$empresa >

                    <br><br>

                    <label for="Telefono" >Telefono </label>
                    <input type="text" name="telefono" value=$telefono>

                    <label for="Celular" >Celular </label>
                    <input type="text" name="celular" value=$celular>

                    <br><br>

                </div>

                <input type="hidden" name="client_edit_ID" value='$client_ID'>

                <input type="submit" value="Confirmar">
            </form> 
            <script src="../editar/add_Product.js"></script>
        </body>
    </html>
    _END;
}