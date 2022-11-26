<?

$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

if(!$connection){
    die('Could not connect: '. mysqli_error($connection));
}
if (isset($_POST['Borrar_Producto'])){
    $Producto_ID = $_POST['Borrar_Producto'];
    Borrar_Producto($Producto_ID, $connection);
    header("Location: ../list/list_Productos.php");
}
if (isset($_POST['Borrar_Orden'])){
    $Orden_ID = $_POST['Borrar_Orden'];
    Borrar_Orden($Orden_ID, $connection);
    header("Location: ../list/list_Ordenes.php");
}
if (isset($_POST['Borrar_Cliente'])){
    $Clinte_ID = $_POST['Borrar_Cliente'];
    Borrar_Cliente($Clinte_ID, $connection);
    header("Location: ../list/list_Clientes.php");
}
if (isset($_POST['Borrar_Referencia'])){
    $Referencia_ID = $_POST['Borrar_Referencia'];
    $Producto_ID = Borrar_Referencia($Referencia_ID, $connection);
    Return_To_Images($Producto_ID);
}

function Borrar_Cliente($Cliente_ID, $connection)
{
    $query_ordenes = "SELECT Orden_ID FROM orden WHERE Cliente_ID = '$Cliente_ID'";
    $result = $connection->query($query_ordenes);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Orden_ID = $row["Orden_ID"];
        Borrar_Orden($Orden_ID, $connection);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $query_copy = "INSERT INTO cliente_reciclaje (Cliente_ID, Cliente_Correo, Nombre, Empresa, Telefono, Celular)
    SELECT Cliente_ID, Cliente_Correo, Nombre, Empresa, Telefono, Celular FROM cliente WHERE Cliente_ID = '$Cliente_ID'";

    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM cliente WHERE Cliente_ID  = '$Cliente_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}

function Borrar_Orden($Orden_ID, $connection)
{
    // Borrar todos los children de la orden
    $query_productos = "SELECT Producto_ID FROM producto WHERE Orden_ID = '$Orden_ID'";
    $result = $connection->query($query_productos);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Producto_ID = $row["Producto_ID"];
        Borrar_Producto($Producto_ID, $connection);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $query_copy = "INSERT INTO orden_reciclaje (Orden_ID, Fecha_Pedido, Fecha_Entrega, Total, Prioridad, Cliente_ID, statuss)
    SELECT Orden_ID, Fecha_Pedido, Fecha_Entrega, Total, Prioridad, Cliente_ID, statuss FROM orden WHERE Orden_ID = '$Orden_ID'";

    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM orden WHERE Orden_ID  = '$Orden_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}

function Borrar_Producto($Producto_ID, $connection)
{
    $query_referencias = "SELECT Referencia_ID FROM referencias WHERE Producto_ID = '$Producto_ID'";
    $result = $connection->query($query_referencias);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Referencia_ID = $row["Referencia_ID"];
        Borrar_Referencia($Referencia_ID, $connection);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $query_disenos = "SELECT Diseno_ID FROM disenos WHERE Producto_ID = '$Producto_ID'";
    $result = $connection->query($query_disenos);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Diseno_ID = $row["Diseno_ID"];
        echo("HERE");
        Borrar_Diseno($Diseno_ID, $connection);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $query_copy = "INSERT INTO producto_reciclaje (Producto_ID, Cantidad, Precio_Unidad, Importe, Descripcion, Orden_ID, Area_Produccion, statuss)
            SELECT Producto_ID, Cantidad, Precio_Unidad, Importe, Descripcion, Orden_ID, Area_Produccion, statuss FROM producto WHERE Producto_ID = '$Producto_ID'";
    
    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM producto WHERE Producto_ID  = '$Producto_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}

function Borrar_Referencia($Referencia_ID, $connection)
{

    $query_producto_id = "SELECT Producto_ID FROM referencias WHERE Referencia_ID = '$Referencia_ID'";
    $result = $connection->query($query_producto_id);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Producto_ID = $row["Producto_ID"];

    $query_copy = "INSERT INTO referencias_reciclaje (Referencia_ID, file_path, Producto_ID, Image_name)
            SELECT Referencia_ID, file_path, Producto_ID, Image_name FROM referencias WHERE Referencia_ID = '$Referencia_ID'";
    
    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM referencias WHERE Referencia_ID  = '$Referencia_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    return $Producto_ID;
}
function Borrar_Diseno($Diseno_ID, $connection)
{

    $query_producto_id = "SELECT Producto_ID FROM disenos WHERE Diseno_ID = '$Diseno_ID'";
    $result = $connection->query($query_producto_id);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Producto_ID = $row["Producto_ID"];

    $query_copy = "INSERT INTO disenos_reciclaje (Diseno_ID, file_path, Producto_ID, Image_name)
            SELECT Diseno_ID, file_path, Producto_ID, Image_name FROM disenos WHERE Diseno_ID = '$Diseno_ID'";
    
    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM disenos WHERE Diseno_ID  = '$Diseno_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    return $Producto_ID;
}

function Return_To_Images($Producto_ID )
{
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
        </head>
        <body>
            <form id="myForm" class="main_body" method="post" action="../list/ref&disenos.php" enctype='multipart/form-data'> 
                <input type="hidden" name="Producto_ID" value='$Producto_ID'>
            </form> 
            <script type="text/javascript">
                document.getElementById('myForm').submit();
            </script>
        </body>
    </html>
    _END;
}