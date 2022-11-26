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

if (isset($_POST['Producto_Edit']))
{
    $producto_ID = $_POST['Producto_Edit'];
    $query ="SELECT * FROM producto WHERE Producto_ID = '$producto_ID' "; 
}

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}

$empleados_lista = extraer_empleados_lista($connection);

$result->data_seek(0);
$row = $result->fetch_array(MYSQLI_ASSOC);
$cantidad = $row['Cantidad'];
$precio_Unidad = $row['Precio_Unidad'];
$importe = $row['Importe'];
$descripcion = $row['Descripcion'];
$area_Produccion = $row['Area_Produccion'];
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
    if($area_Produccion == $empleados_lista[$i])
    {
      
        $selected_Area_Production .= "<option value='".$empleados_lista[$i]."'  selected='selected'>".$empleados_lista[$i]."</option>";
    }
    else
    {   
        $selected_Area_Production .= "<option value='".$empleados_lista[$i]."'>".$empleados_lista[$i]."</option>";
    }
}


$references_table = referencias_HTML($connection,$producto_ID, );
$disenos_table = disenos_HTML($connection,$producto_ID);
print_HTML($producto_ID, $cantidad, $precio_Unidad, $descripcion, $importe, $area_Produccion,$statuss, $references_table, $disenos_table, $selected_Status, $selected_Area_Production);

function referencias_HTML($connection,$producto_ID)
{
    
    $query ="SELECT * FROM referencias WHERE Producto_ID = '$producto_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;


    $html_row = "
    <h5>Referencias </h5>
    <table class='table table-bordered table-hover table-dark images_table'>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Borrar</th>
            </tr>
        </thead>
        <tbody>"; 
          
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $html_row .= "<tr>";
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $referencia_ID = $row["Referencia_ID"];
        $image_name = $row["Image_name"];
        $html_row = $html_row ."<td>" . $image_name . "</td>";
        $html_row = $html_row ."<td>" . "<input type='checkbox' name='ref$referencia_ID' >" . "</td>";
        $html_row .= "</tr>";
    }

    $html_row .= "
        </tbody>
    </table>
    <input type='file' name='file_REF_[]' multiple>";

    return $html_row;
}

function disenos_HTML($connection,$producto_ID)
{
    
    $query ="SELECT * FROM disenos WHERE Producto_ID = '$producto_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;


    $html_row = "
    <h5>Disenos </h5>
    <table class='table table-bordered table-hover table-dark images_table'>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Borrar</th>
            </tr>
        </thead>
        <tbody>"; 
          
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $html_row .= "<tr>";
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Diseno_ID = $row["Diseno_ID"];
        $image_name = $row["Image_name"];
        $html_row = $html_row ."<td>" . $image_name . "</td>";
        $html_row = $html_row ."<td>" . "<input type='checkbox' name='dis$Diseno_ID' >" . "</td>";
        $html_row .= "</tr>";
    }

    $html_row .= "
        </tbody>
    </table>
    <input type='file' name='file_DIS_[]' multiple>";

    return $html_row;
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

function print_HTML($producto_ID, $cantidad, $precio_Unidad, $descripcion, $importe, $area_produccion, $statuss, $references_table, $disenos_table, $selected_Status, $selected_Area_Production)
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
            
            <title>Editar Productos</title>
        </head>
        <body>
            $nav
            <form method="post" action="../editar/editar_producto_submition.php" enctype='multipart/form-data'>
                <button type='submit' name='Finalizard_Producto' value='$producto_ID'>Finlizar Producto</button>
            </form> 
            <form class="main_body" method="post" action="../editar/editar_producto_submition.php" enctype='multipart/form-data'> 
            <div class='section' id = producto_tab>
                <h2 class="section_Header">Editar Producto</h2>

                <p >Descripcion <span class='reqq'>*</p></span>
                <textarea name="Descripcion" cols=40 rows=4 required>$descripcion</textarea>
                <br>
                <p >Area de produccion <span class='reqq'>*</p></span>
                <select name='produccion' required>
                    $selected_Area_Production
                </select>

                <p>Cantidad <span class='reqq'>*</span></p>
                <input type='number' name='cantidad' value=$cantidad required>

                <br><br>

                <label for='Punit'>P. Unit <span class='reqq'>*</span></label>
                <input type='number' name='Punit' value=$precio_Unidad required step='.01'>

                <label for='importe'>Importe <span class='reqq'>*</span></label>
                <input type='number' name='importe' value=$importe required step='.01'>
                
                <br><br>

                <label for='statuss'>Status <span class='reqq'>*</span></label>
                <select name='statuss' required>
                    $selected_Status
                </select>

                <br><br>
                
                $references_table   
                    
                <br><br>

                $disenos_table

                <br><br>
            </div>
            
            <input type="hidden" name="producto_edit_ID" value='$producto_ID'>
            <input type="submit" value="Confirmar">

            </form> 
        <script src="../editar/add_Product.js"></script>
        </body>
    </html>
    _END;
}