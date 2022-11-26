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

$dropdown = Client_dropdown($connection);
$empleados = extraer_empleados($connection);
print_HTML($dropdown, $empleados );

function Client_dropdown($connection)
{
    $query ="SELECT * FROM cliente ";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;

    $dropdown = "<select name=prev_Client class=prev_Clientes>
        <option value=-1>--Please choose an option--</option>";
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $cliente_ID = $row['Cliente_ID'];
        $nombre = $row['Nombre'];
        $correo = $row['Cliente_Correo'];
        $dropdown .= "<option value=$cliente_ID>$nombre \n $correo</option>";
    }
    $dropdown .= "</select>";
    return $dropdown;
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
        $Nombre = $row['Nombre'];
        $lista_empleados .= "<p class='empleados' hidden>$Nombre";
    }
    return $lista_empleados;
}

function print_HTML($dropdown, $empleados )
{
    $nav = print_Navbar();

    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <link rel="stylesheet" href="../editar/formulario_style.css">
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
            <title>Formulario</title>
        </head>
        <body>
            $nav
            $empleados
            <form class="main_body" method="post" action="../editar/formulario_submition.php" enctype='multipart/form-data'> 

                <div id="client_Tab" class="section cliente_fields_wrap" >
                    <h2 class="section_Header">Cliente</h2>
                    $dropdown
                    
                    <input class="add_field_button_cliente" type="button" value="Anadir nuevo cliente">
                    
                    <!--<br><br>
                    
                     <p style="display: inline-block;">Nombre <span class="reqq">*</span></p>
                    <input type="text" name="nombre" required>

                    <br>

                    <p style="display: inline-block;">Correo <span class="reqq">*</span></p>
                    <input type="text" name="correo" required>

                    <p>Empresa</p>
                    <input type="text" name="empresa" >

                    <br><br>

                    <label for="Telefono" >Telefono </label>
                    <input type="text" name="telefono">

                    <label for="Celular" >Celular </label>
                    <input type="text" name="celular">

                    <br><br> -->

                </div>

                <div class="orden_Tab section" >

                    <h2 class="section_Header">Orden</h2>

                    <!--<label for="S_date">Fecha Pedido <span class="reqq">*</span> </label>
                    <input type="date" name="fecha_pedido" required>-->

                    <label for="E_date">Fecha Entrega <span class="reqq">*</span></label>
                    <input type="date" name="fecha_entrega" required>
                    <br><br>
                    <label for="prioridad" >Prioridad </label>
                    <input type=number name="prioridad" value="0">
                    <br><br>

                    <label for="total" >Total<span class="reqq">*</span></label>
                    <input type=number name="total" step=".01" required >

                </div>            

                <div class="producto_Tab input_fields_wrap section">

                    <h2 class="section_Header">Productos</h2>
        
                    <input class="add_field_button" type="button" value="Anadir producto">

                    <!-- <div class="Productos">

                        <p >Descripcion <span class="reqq">*</p></span>
                        <input type="text" name="Descripcion0">
                        <br>
                        <p >Area de produccion <span class="reqq">*</p></span>
                        <input type=text name="produccion0" >

                        <p>Cantidad <span class="reqq">*</span></p>
                        <input type="number" name="cantidad0" required>

                        <br><br>

                        <label for="Punit">P. Unit <span class="reqq">*</span></label>
                        <input type="number" name="Punit0" required step=".01">

                        <label for="importe">Importe <span class="reqq">*</span></label>
                        <input type="number" name="importe0" required step=".01">
                        
                        <br><br>

                        <label for="statuss">Status <span class="reqq">*</span></label>
                        <input type="text" name="statuss0" required >

                        <br><br>
                        <p >Referencias </p>
                        <input type="file" name="file0[]" multiple>

                    </div>   -->
                    
                </div>
                <input type="submit" value="Confirmar">
            </form> 
        <script src="../editar/add_Product.js"></script>
        <script src="../editar/add_Cliente.js"></script>
        </body>
    </html>
    _END;
}

