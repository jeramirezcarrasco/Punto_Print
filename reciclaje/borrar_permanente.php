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
if (isset($_POST['Borrar_Diseno'])){
    $Diseno_ID = $_POST['Borrar_Diseno'];
    Borrar_Diseno($Diseno_ID, $connection);
    header("Location: ../reciclaje/list_Disenos_Reciclaje.php");
}
if (isset($_POST['Borrar_Referencia'])){
    $Referencia_ID = $_POST['Borrar_Referencia'];
    Borrar_Refernecia($Referencia_ID, $connection);
    header("Location: ../reciclaje/list_Referencias_Reciclaje.php");
}
if (isset($_POST['Borrar_Producto'])){
    $Producto_ID = $_POST['Borrar_Producto'];
    Borrar_Producto($Producto_ID, $connection);
    header("Location: ../reciclaje/list_Productos_Reciclaje.php");
}
if (isset($_POST['Borrar_Orden'])){
    $Orden_ID = $_POST['Borrar_Orden'];
    Borrar_Orden($Orden_ID, $connection);
    header("Location: ../reciclaje/list_Ordenes_Reciclaje.php");
}
if (isset($_POST['Borrar_Cliente'])){
    $Clinte_ID = $_POST['Borrar_Cliente'];
    Borrar_Cliente($Clinte_ID, $connection);
    header("Location: ../reciclaje/list_Clientes_Reciclaje.php");
}

function Borrar_Diseno($Diseno_ID, $connection){
    $query_delete = "DELETE FROM disenos_reciclaje WHERE Diseno_ID  = '$Diseno_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}
function Borrar_Refernecia($Referencia_ID, $connection){
    $query_delete = "DELETE FROM referencias_reciclaje WHERE Referencia_ID  = '$Referencia_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}
function Borrar_Producto($Producto_ID, $connection){
    ///Borrar Referencias
    $query_delete_referencias = "SELECT Referencia_ID from referencias_reciclaje WHERE Producto_ID = '$Producto_ID'" ;
    $result = $connection->query($query_delete_referencias);
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Referencia_ID = $row['Referencia_ID'];
        Borrar_Refernecia($Referencia_ID, $connection);
    }
    //Borrar Disenos
    $query_delete_disenos = "SELECT Diseno_ID from disenos_reciclaje WHERE Producto_ID = '$Producto_ID'" ;
    $result = $connection->query($query_delete_disenos);
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Diseno_ID = $row['Diseno_ID'];
        Borrar_Diseno($Diseno_ID, $connection);
    }
    ///Borrar Producto
    $query_delete = "DELETE FROM producto_reciclaje WHERE Producto_ID  = '$Producto_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}
function Borrar_Orden($Orden_ID, $connection){
    ///Borrar Productos
    $query_delete_productos = "SELECT Producto_ID from producto_reciclaje WHERE Orden_ID = '$Orden_ID'" ;
    $result = $connection->query($query_delete_productos);
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Producto_ID = $row['Producto_ID'];
        Borrar_Producto($Producto_ID, $connection);
    }
    ///Borrar Orden
    $query_delete = "DELETE FROM orden_reciclaje WHERE Orden_ID  = '$Orden_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}
function Borrar_Cliente($Cliente_ID, $connection){
    ///Borrar Ordenes
    $query_delete_ordenes = "SELECT Orden_ID from orden_reciclaje WHERE Cliente_ID = '$Cliente_ID'" ;
    $result = $connection->query($query_delete_ordenes);
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Orden_ID = $row['Orden_ID'];
        Borrar_Orden($Orden_ID, $connection);
    }
    ///Borrar Cliente
    $query_delete = "DELETE FROM cliente_reciclaje WHERE Cliente_ID  = '$Cliente_ID'";
    $result = $connection->query($query_delete);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}
