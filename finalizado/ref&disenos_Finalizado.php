<?php
require __DIR__ . '../../utility.php';

$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);
if(!$connection){
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['Producto_ID'])){
    $producto_ID = $_POST['Producto_ID'];
    $query ="SELECT * FROM producto WHERE Producto_ID = '$producto_ID' "; 
}

$result = $connection->query($query);
if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}

$table_productos = create_Table($connection, $result);
$ref_image = Reference_Images($connection, $producto_ID);
$dis_image = Diseno_Images($connection, $producto_ID);
print_HTML($table_productos, $ref_image, $dis_image);

function create_Table($connection, $result)
{
    $table_productos = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $table_row = "<tr>";
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Producto_ID = $row['Producto_ID'];
        $table_row = $table_row . "<td>" .$Producto_ID . "</td>";
        $cantidad = $row['Cantidad'];
        $table_row = $table_row . "<td>" .$cantidad . "</td>";
        $precio_Unidad = $row['Precio_Unidad'];
        $table_row = $table_row . "<td>" .$precio_Unidad . "</td>";
        $importe = $row['Importe'];
        $table_row = $table_row . "<td>" .$importe . "</td>";
        $descripcion = $row['Descripcion'];
        $table_row = $table_row . "<td>" .$descripcion . "</td>";
        $orden_ID = $row['Orden_ID'];

        $table_row = $table_row . "<td>" . 
        "<form method='post' action='list_Clientes.php'> 
            <input type='hidden' name='Cliente_Orden_ID' value='$orden_ID'>
            <input type='submit' value=' - '>
        </form> ". "</td>";
        
        
        $table_row = $table_row . "<td>" . 
        "<form method='post' action='list_Ordenes.php'> 
            <input type='hidden' name='Orden_ID' value='$orden_ID'>
            <input type='submit' value=' - '>
        </form> ". "</td>";

        
        $table_row = $table_row . "</tr> ";
        $table_productos = $table_productos . $table_row;
    }

    return $table_productos;
}

function Reference_Images($connection, $producto_ID)
{
    $query ="SELECT * FROM referencias WHERE Producto_ID = '$producto_ID' ";
    $result = $connection->query($query);
    $rows = $result->num_rows;

    $html_ref_images = "";
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $file_path = $row['file_path'];
        $file_name = $row['Image_name'];
        $Referencia_ID = $row['Referencia_ID'];
        $file_path = str_replace(" ","%20",$file_path);
        if($j == 0)
        {   
            $html_ref_images = $html_ref_images . 
            "<div id = 'ref$j' class = ref_class >
                <h5 class= file_name> $file_name </h5>
                <img name='ref".$j."' src= ../$file_path  class='images'>
                <form method='post' action='../editar/borrar.php'> 
                    <input type='hidden' name='Borrar_Referencia' value='$Referencia_ID'>" .
                    <<<_END
                        <input type='submit' value=' Borrar Imagen ' onclick="return confirm('Seguro que quieres borrar?')">
                    _END
                . "</form>
            </div>";
        }
        else
        {    
            $html_ref_images = $html_ref_images . 
            "<div hidden id = 'ref" . $j ."' class = ref_class>
                <h5 class= file_name> $file_name </h5> 
                <img name='ref".$j."' src= ../$file_path class='images'>
                <form method='post' action='../editar/borrar.php'> 
                    <input type='hidden' name='Borrar_Referencia' value='$Referencia_ID'>" .
                    <<<_END
                        <input type='submit' value=' Borrar Imagen ' onclick="return confirm('Seguro que quieres borrar?')">
                    _END
                . "</form>
            </div>";
                
        }

    }
    

    return $html_ref_images;
}

function Diseno_Images($connection, $producto_ID)
{
    $query ="SELECT * FROM disenos WHERE Producto_ID = '$producto_ID' ";
    $result = $connection->query($query);
    $rows = $result->num_rows;

    $html_dis_images = "";
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $file_path = $row['file_path'];
        $file_name = $row['Image_name'];
        $Diseno_ID = $row['Diseno_ID'];
        $file_path = str_replace(" ","%20",$file_path);
        if($j == 0)
        {   
            $html_dis_images = $html_dis_images . 
            "<div id = 'dis" . $j ."' class = dis_class>
                <h5 class= file_name> $file_name </h5>
                <img name='dis".$j."' src= ../$file_path  class='images'>
                <form method='post' action='../editar/borrar.php'> 
                    <input type='hidden' name='Borrar_Diseno' value='$Diseno_ID'>" .
                    <<<_END
                        <input type='submit' value=' Borrar Imagen ' onclick="return confirm('Seguro que quieres borrar?')">
                    _END
                . "</form>
            </div>";
        }
        else
        {    
            $html_dis_images = $html_dis_images . 
            "<div hidden id = 'dis" . $j ."' class = dis_class>
                <h5 class= file_name> $file_name </h5> 
                <img name='dis".$j."' src= ../$file_path class='images'>
                <form method='post' action='../editar/borrar.php'> 
                    <input type='hidden' name='Borrar_Diseno' value='$Diseno_ID'>" .
                    <<<_END
                        <input type='submit' value=' Borrar Imagen ' onclick="return confirm('Seguro que quieres borrar?')">
                    _END
                . "</form>
            </div>";
                
        }

    }
   

    return $html_dis_images;
}

function print_HTML($table_productos, $ref_image, $dis_image)
{
    $nav = print_Navbar();
    echo <<<_END
    <html>
    <head>
            <link rel="stylesheet" href="../list/list_style.css">
            <link rel="stylesheet" href="../list/images_style.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
            <title>Referencias y Diseños</title>
    
    </head>
    <body>
        $nav

        <br><br>
        <h2> Productos </h2>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Producto ID</th>
                    <th>Cantidad</th>
                    <th>Precio_Unidad</th>
                    <th>Importe</th>
                    <th>Descripcion</th>
                    <th>Cliente</th>
                    <th>Orden</th>
                </tr>
            </thead>
            <tbody>
            $table_productos
            </tbody>
        </table>         

        <h2 style="display: inline; margin-right: 30px;"> Referencias </h2> 
        <button onclick="hide_References()" class="hide_References" id=hide_referencia> + </button>

        <div class='referencias' style=display:none;>
            <button onclick="changeImagesBackward_Ref()" class="move_button" >Click me</button>
            $ref_image
            <button onclick="changeImagesFoward_Ref()" class="move_button">Click me</button>
        </div>

        <br><br>

        <h2 style="display: inline; margin-right: 30px;"> Disenos </h2> 
        <button onclick="hide_Disenos()" class="hide_Disenos" id=hide_diseno> + </button>
        <div class='disenos' style=display:none;>
            <button onclick="changeImagesBackward_Dis()" class="move_button" >Click me</button>
            $dis_image
            <button onclick="changeImagesFoward_Dis()" class="move_button">Click me</button>
        </div>
    <script src="../list/images_script.js"></script>
    </body>
    </html>
    _END;

}