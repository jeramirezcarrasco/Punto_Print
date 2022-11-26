<?
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
if (isset($_POST['Restaurar_Producto'])){
    $Producto_ID = $_POST['Restaurar_Producto'];
    Restaurar_Producto($Producto_ID, $connection);
    header("Location: ../list/list_Productos.php");
}
if (isset($_POST['Restaurar_Orden'])){
    $Orden_ID = $_POST['Restaurar_Orden'];
    Restaurar_Orden($Orden_ID, $connection);
    header("Location: ../list/list_Ordenes.php");
}
if (isset($_POST['Restaurar_Cliente'])){
    $Clinte_ID = $_POST['Restaurar_Cliente'];
    Restaurar_Cliente($Clinte_ID, $connection);
    header("Location: ../list/list_Clientes.php");
}
if (isset($_POST['Restaurar_Referencia'])){
    $Referencia_ID = $_POST['Restaurar_Referencia'];
    Restaurar_Referencia($Referencia_ID, $connection);
    header("Location: ../list/list_Productos.php");
}
if (isset($_POST['Restaurar_Diseno'])){
    $Diseno_ID = $_POST['Restaurar_Diseno'];
    Restaurar_Disenos($Diseno_ID, $connection);
    header("Location: ../list/list_Productos.php");
}

function Restaurar_Cliente($Cliente_ID, $connection)
{
    $query_copy = "INSERT INTO cliente  (Cliente_ID, Cliente_Correo, Nombre, Empresa, Telefono, Celular)
    SELECT Cliente_ID, Cliente_Correo, Nombre, Empresa, Telefono, Celular FROM cliente_reciclaje WHERE Cliente_ID = '$Cliente_ID'";

    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM cliente_reciclaje WHERE Cliente_ID  = '$Cliente_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
        
}

function Restaurar_Orden($Orden_ID, $connection)
{
    // Informacion de la Orden
    $query_Orden = "SELECT * FROM orden_reciclaje WHERE Orden_ID = '$Orden_ID'";
    $result = $connection->query($query_Orden);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Cliente_ID = $row["Cliente_ID"];

    //Checar si el Parent Client tambien esta borrado
    $query_find_cliente = "SELECT Cliente_ID FROM cliente_reciclaje WHERE Cliente_ID = '$Cliente_ID'";
    $result = $connection->query($query_find_cliente);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    if($rows == 1) // Restaurar Cliente
    {
        Restaurar_Cliente($Cliente_ID, $connection);
    }
    // Restuarar Orden

    $query_copy = "INSERT INTO orden (Orden_ID, Fecha_Pedido, Fecha_Entrega, Total, Prioridad, Cliente_ID, statuss)
    SELECT Orden_ID, Fecha_Pedido, Fecha_Entrega, Total, Prioridad, Cliente_ID, statuss FROM orden_reciclaje WHERE Orden_ID = '$Orden_ID'";

    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM orden_reciclaje WHERE Orden_ID  = '$Orden_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    
}

function Restaurar_Producto($Producto_ID, $connection)
{
    // Informacion del proucto
    $query_Producto = "SELECT * FROM producto_reciclaje WHERE Producto_ID = '$Producto_ID'";
    $result = $connection->query($query_Producto);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Orden_ID = $row["Orden_ID"];

    //Checar si el Parent Orden tambien esta borrado
    $query_find_orden = "SELECT Orden_ID FROM orden_reciclaje WHERE Orden_ID = '$Orden_ID'";
    $result = $connection->query($query_find_orden);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    if($rows == 1) // Restaurar Orden
    {
        Restaurar_Orden($Orden_ID, $connection);
    } 
    //Restaurar producto

    $query_copy = "INSERT INTO producto (Producto_ID, Cantidad, Precio_Unidad, Importe, Descripcion, Orden_ID, Area_Produccion, statuss)
            SELECT Producto_ID, Cantidad, Precio_Unidad, Importe, Descripcion, Orden_ID, Area_Produccion, statuss FROM producto_reciclaje WHERE Producto_ID = '$Producto_ID'";

    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    //Borrar Producto 
    $query_delete = "DELETE FROM producto_reciclaje WHERE Producto_ID  = '$Producto_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

}

function Restaurar_Referencia($Referencia_ID, $connection)
{
    //ID Producto 
    $query_referencias = "SELECT Producto_ID FROM referencias_reciclaje WHERE Referencia_ID = '$Referencia_ID'";
    $result = $connection->query($query_referencias);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Producto_ID = $row["Producto_ID"];
    ///Checar si producto esta borrado
    $query_find_producto = "SELECT Producto_ID FROM producto_reciclaje WHERE Producto_ID = '$Producto_ID'";
    $result = $connection->query($query_find_producto);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    echo $rows;
    if($rows == 1) // Restaurar Producto
    {
        Restaurar_Producto($Producto_ID, $connection, false);
    } 
    
    $query_copy = "INSERT INTO referencias (Referencia_ID, file_path, Producto_ID, Image_name)
            SELECT Referencia_ID, file_path, Producto_ID, Image_name FROM  referencias_reciclaje WHERE Referencia_ID = '$Referencia_ID'";
    
    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM referencias_reciclaje WHERE Referencia_ID  = '$Referencia_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}
function Restaurar_Disenos($Diseno_ID, $connection)
{
    
    //ID Producto 
    $query_disenos = "SELECT Producto_ID FROM disenos_reciclaje WHERE Diseno_ID = '$Diseno_ID'";
    $result = $connection->query($query_disenos);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Producto_ID = $row["Producto_ID"];
    ///Checar si producto esta borrado
    $query_find_producto = "SELECT Producto_ID FROM producto_reciclaje WHERE Producto_ID = '$Producto_ID'";
    $result = $connection->query($query_find_producto);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    echo $rows;
    if($rows == 1) // Restaurar Producto
    {
        Restaurar_Producto($Producto_ID, $connection, false);
    } 
    
    $query_copy = "INSERT INTO disenos (Diseno_ID, file_path, Producto_ID, Image_name)
            SELECT Diseno_ID, file_path, Producto_ID, Image_name FROM  disenos_reciclaje WHERE Diseno_ID = '$Diseno_ID'";
    
    $result = $connection->query($query_copy);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $query_delete = "DELETE FROM disenos_reciclaje WHERE Diseno_ID  = '$Diseno_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}