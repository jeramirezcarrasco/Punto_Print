<?php


$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['client_edit_ID']))
{
    $client_ID = $_POST['client_edit_ID'];
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $empresa = $_POST["empresa"];
    $telefono = $_POST["telefono"];
    $celular = $_POST["celular"];

    $query = "UPDATE cliente SET Cliente_Correo = '$correo', Nombre = '$nombre', Empresa = '$empresa', 
    Telefono = '$telefono', Celular = '$celular' WHERE Cliente_ID = '$client_ID' ";

    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    echo($client_ID);

    print_HTML($client_ID);
}
else{
    header("Location: ../list/list_Clientes.php");
}
function print_HTML($client_ID)
{
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
        </head>
        <body>
            <form id="myForm" class="main_body" method="post" action="../list/list_Clientes.php" enctype='multipart/form-data'> 
                <input type="hidden" name="Cliente_ID" value='$client_ID'>
            </form> 
            <script type="text/javascript">
                document.getElementById('myForm').submit();
            </script>
        </body>
    </html>
    _END;
}
