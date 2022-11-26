<?
class OrdenParametros{
    public $Fecha_Pedido;
    public $Fecha_Entrega;
    public $Total;
    public $Prioridad;
    public $Cliente_ID;
    public $Statuss;
}
class ProductoParametros
{
    public $Cantidad;
    public $Precio_Unidad;
    public $Importe;
    public $Descripcion;
    public $Orden_ID;   
    public $Area_Production;   
    public $Statuss;   
}
class ClienteParametros
{
    $Cliente_Correo;
    $Nombre;
    $Empresa;
    $Telefono;
    $Celular;
}
function ObtenerOrdenesFinalizadosPorOrden($connection, $Orden_ID)
{
    $query ="SELECT * FROM orden WHERE Orden_ID = '$Orden_ID' AND statuss = 'Finalizado' ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    return $result;
}
function ObtenerOrdenesFinalizados($connection)
{
    $query ="SELECT * FROM orden WHERE statuss = 'Finalizado' ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    return $result;
}

function ObtenerProductosFinalizadosPorOrden($connection, $Orden_ID)
{
    $query ="SELECT * FROM producto WHERE Orden_ID = '$Orden_ID' AND statuss = 'Finalizado' ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    return $result;
}
function ObtenerProductosFinalizadosPorProducto($connection, $Producto_ID)
{
    $query ="SELECT * FROM producto WHERE Producto_ID = '$Producto_ID' AND statuss = 'Finalizado' ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    return $result;
}

function ObtenerProductosFinalizados($connection)
{
    $query ="SELECT * FROM producto WHERE statuss = 'Finalizado' ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    return $result;
}
function ObtenerProductos($connection)
{
    $query ="SELECT * FROM producto";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    return $result;
}
function ObtenerProductosPorProducto($connection, $Producto_ID)
{
    $query ="SELECT * FROM producto WHERE Producto_ID = '$Producto_ID' ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    return $result;
}
function EditarProducto()