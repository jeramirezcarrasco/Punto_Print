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

if (isset($_POST['Orden_Edit']))
{
    $orden_ID = $_POST['Orden_Edit'];
    $query ="SELECT * FROM orden WHERE Orden_ID = '$orden_ID' "; 
}

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}

$result->data_seek(0);
$row = $result->fetch_array(MYSQLI_ASSOC);
$fecha_Pedido = $row['Fecha_Pedido'];
$fecha_Entrega = $row['Fecha_Entrega'];
$total = $row['Total'];
$prioridad = $row['Prioridad'];


$empleados_lista = extraer_empleados_lista($connection);
$producto_html_row = producto_HTML($connection,$orden_ID, $empleados_lista);
$empleados = extraer_empleados($connection);

print_HTML($empleados, $orden_ID, $fecha_Pedido, $fecha_Entrega, $total, $prioridad, $producto_html_row);

function producto_HTML($connection,$orden_ID, $empleados_lista)
{
    $html_row = "" ; 

    $query ="SELECT * FROM producto WHERE Orden_ID = '$orden_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $producto_ID = $row["Producto_ID"];
        $cantidad = $row['Cantidad'];
        $precio_Unidad = $row['Precio_Unidad'];
        $importe = $row['Importe'];
        $descripcion = $row['Descripcion'];
        $area_produccion = $row['Area_Produccion'];
        $statuss = $row['statuss'];
        $selected_Status = "";
        $options = ["Diseño", "Produccion", "Espera", "Finalizado"];
        for ($i = 0 ; $i < 4 ; ++$i)
        {
            
            
            if($statuss == $options[$i])
            {
                
                $selected_Status .= "<option value='".$options[$i]."'  selected='selected'>".$options[$i]."</option>";
            }
            else
            {   
                $selected_Status .= "<option value='".$options[$i]."'>".$options[$i]."</option>";
            }
        }
        $selected_Area_Production = "";
        for ($i = 0 ; $i < 4 ; ++$i)
        {
            
            
            if($area_produccion == $empleados_lista[$i])
            {
                
                $selected_Area_Production .= "<option value='".$empleados_lista[$i]."'  selected='selected'>".$empleados_lista[$i]."</option>";
            }
            else
            {   
                $selected_Area_Production .= "<option value='".$empleados_lista[$i]."'>".$empleados_lista[$i]."</option>";
            }
        }

        $html_row = $html_row . 
        "<div class='Productos' id = Editar_Producto_$j>
            <p >Descripcion <span class='reqq'>*</p></span>
            <textarea name='Editar_Descripcion_$j' cols=40 rows=4 required>$descripcion</textarea>
            <br>
            <p >Area de produccion <span class='reqq'>*</p></span>
            <select name='Editar_produccion_$j' required>
                $selected_Area_Production
            </select>

            <p>Cantidad <span class='reqq'>*</span></p>
            <input type='number' name='Editar_cantidad_$j' value=$cantidad required>

            <br><br>

            <label for='Punit'>P. Unit <span class='reqq'>*</span></label>
            <input type='number' name='Editar_Punit_$j' value=$precio_Unidad required step='.01'>

            <label for='importe'>Importe <span class='reqq'>*</span></label>
            <input type='number' name='Editar_importe_$j' value=$importe required step='.01'>
            
            <br><br>

            <label for='statuss'>Status <span class='reqq'>*</span></label>
            <select name='Editar_statuss_$j' required>
                $selected_Status
            </select>

            <br><br>
            <p >Referencias </p>
            <input type='file' name='Editar_file_Ref".$j."[]' multiple>
            <br><br>
            <p >Disenos </p>
            <input type='file' name='Editar_file_Dis".$j."[]' multiple>

        </div> ";

    }

    return $html_row;
}

function extraer_empleados($connection)
{
    $query ="SELECT * FROM empleados ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $lista_empleados = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $cliente_ID = $row['Nombre'];
        $lista_empleados .= "<p class='empleados' hidden>$cliente_ID";
    }
    return $lista_empleados;
}

function extraer_empleados_lista($connection)
{
    $query ="SELECT * FROM empleados ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $lista_empleados = [];
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Nombre = $row['Nombre'];
        $lista_empleados[] = $Nombre;
    }
    return $lista_empleados;
}

function print_HTML($empleados, $orden_ID, $fecha_Pedido, $fecha_Entrega, $total, $prioridad, $producto_html_row)
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
            
            <title>Editar Orden</title>
        </head>
        <body>
            $nav
            $empleados
            <form method="post" action="../editar/editar_orden_submition.php" enctype='multipart/form-data'>
                <button type='submit' name='Finalizard_Orden' value='$orden_ID'>Finlizar Orden</button>
            </form> 
            <form class="main_body" method="post" action="../editar/editar_orden_submition.php" enctype='multipart/form-data'> 
            <div class="orden_Tab section" >

                <h2 class="section_Header">Editar Orden</h2>

                <label for="S_date">Fecha Pedido <span class="reqq">*</span> </label>
                <input type="date" name="fecha_pedido" value=$fecha_Pedido required>

                <label for="E_date">Fecha Entrega <span class="reqq">*</span></label>
                <input type="date" name="fecha_entrega" value=$fecha_Entrega required>
                <br><br>
                <label for="prioridad" >Prioridad </label>
                <input type=number name="prioridad" value=$prioridad>
                <br><br>

                <label for="total" >Total<span class="reqq">*</span></label>
                <input type=number name="total" step=".01" value=$total required>
                <br><br>
            </div>     
            <div class='producto_Tab input_fields_wrap section'>
                <h2 class='section_Header'>Editar Productos</h2>
                <input class='add_field_button' type='button' value='Anadir producto'> 
                $producto_html_row   
            </div>

            <input type="hidden" name="orden_edit_ID" value='$orden_ID'>
            <input type="submit" value="Confirmar">

            </form> 
        <script src="../editar/add_Product.js"></script>
        </body>
    </html>
    _END;
}