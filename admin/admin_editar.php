<?php
require __DIR__ . '../../editar/borrar.php';
require __DIR__ . '../../utility.php';
require __DIR__ . '../../reciclaje/restaurar.php';

$target_dir_referencias = "../referencias/";
$target_dir_disenos = "../disenos/";
$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['anadir_usuario']))
{
    anadir_empleado($connection);
    header("Location: ../admin/administracion_main.php");
}
//Editar Empleado Submition
else if(isset($_POST['edit_submition_Username_ID']))
{
    editar_empleado($connection, $_POST['edit_submition_Username_ID']);
    header("Location: ../admin/administracion_main.php");
}
//Editar Empledo
else if(isset($_POST['editar_empleado_Username_ID']))
{
    print_empleado_edit($connection, $_POST['editar_empleado_Username_ID']);
}
//Borrar Empleado
else if(isset($_POST['borrar_empleado_Username_ID']))
{
    borrar_empleado($connection, $_POST['borrar_empleado_Username_ID']);
    header("Location: ../admin/administracion_main.php");
}
else if(isset($_POST['limpiar_papelera']))
{
    limpiar_papelera($connection);
    header("Location: ../admin/administracion_main.php");
}
//MOVER A PAPELERA SOLO FINALIZADO
else if(isset($_POST['borrar_finalizadas']))
{
    borrar_finalizados($connection);
    header("Location: ../admin/administracion_main.php");
}
//MOVER TODO A PAPELERA
else if(isset($_POST['mover_todo_papelera']))
{
    Todo_a_papelere($connection);
    header("Location: ../admin/administracion_main.php");
}
//RECUPERAR TODO DE PAPELERA
else if(isset($_POST['recuperar_papelera']))
{
    Recuperar_papelera($connection);
    header("Location: ../admin/administracion_main.php");
}

function Recuperar_papelera($connection)
{
    // Cliente
    $query = "SELECT * FROM cliente_reciclaje";
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
        $Cliente_ID = $row["Cliente_ID"];
        Restaurar_Cliente($Cliente_ID, $connection);
    }
    // Orden
    $query = "SELECT * FROM orden_reciclaje";
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
        $Orden_ID = $row["Orden_ID"];
        Restaurar_Orden($Orden_ID, $connection);
    }
    // Producto
    $query = "SELECT * FROM producto_reciclaje";
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
        $Producto_ID = $row["Producto_ID"];
        Restaurar_Producto($Producto_ID, $connection);
    }
    // Referencia
    $query = "SELECT * FROM referencias_reciclaje";
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
        $Referencia_ID = $row["Referencia_ID"];
        Restaurar_Referencia($Referencia_ID, $connection);
    }
    // Diseno
    $query = "SELECT * FROM disenos_reciclaje";
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
        $Diseno_ID = $row["Diseno_ID"];
        Restaurar_Disenos($Diseno_ID, $connection);
    }
}

function Todo_a_papelere($connection)
{
    $query = "SELECT * FROM cliente";
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
        $Cliente_ID = $row["Cliente_ID"];
        Borrar_Cliente($Cliente_ID, $connection);
    }
}

function borrar_finalizados($connection)
{
    $query = "SELECT * FROM orden WHERE statuss = 'Finalizado'";
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
        $Orden_ID = $row["Orden_ID"];
        Borrar_Orden($Orden_ID, $connection);
    }
}

function limpiar_papelera($connection)
{
    $query = "DELETE FROM disenos_reciclaje";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    
    $query = "DELETE FROM referencias_reciclaje";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $query = "DELETE FROM producto_reciclaje";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $query = "DELETE FROM orden_reciclaje";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $query = "DELETE FROM cliente_reciclaje";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

}

function borrar_empleado($connection,$Username_ID)
{
    $query = "DELETE FROM empleados WHERE Username = '$Username_ID'";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}

function anadir_empleado($connection)
{
    $Username = $_POST['anadir_usuario'];
    $Permiso = $_POST['anadir_permiso'];
    $Nombre = $_POST['anadir_nombre_empleado'];
    $Correo = $_POST['anadir_correo_empleado'];
    $Contrasena = password_hash($_POST['anadir_contrasena'], PASSWORD_DEFAULT);

    $query = "INSERT INTO empleados (Username, Password, Permiso, Nombre, Correo)". " VALUES 
        ('$Username','$Contrasena', '$Permiso', '$Nombre', '$Correo')";

    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}

function editar_empleado($connection, $Username_ID)
{
    $Username = $_POST['editar_usuario'];
    $Permiso = $_POST['editar_permiso'];
    $Nombre = $_POST['editar_nombre_empleado'];
    $Correo = $_POST['editar_correo_empleado'];
    if($_POST['editar_contrasena'] == "")
    {
        $query = "UPDATE empleados SET Username = '$Username', Permiso = '$Permiso', 
        Nombre = '$Nombre', Correo = '$Correo' WHERE Username = '$Username_ID' ";
    }
    else
    {
        $Contrasena = password_hash($_POST['editar_contrasena'], PASSWORD_DEFAULT);
        $query = "UPDATE empleados SET Username = '$Username', Password = '$Contrasena', Permiso = '$Permiso', 
        Nombre = '$Nombre', Correo = '$Correo' WHERE Username = '$Username_ID' ";
    }
    

    

    $result = $connection->query($query);

    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
}

function print_empleado_edit($connection, $Username_ID)
{
    $query ="SELECT * FROM empleados WHERE Username = '$Username_ID'";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Nombre = $row["Nombre"];
    $Username = $row["Username"];
    $Correo = $row["Correo"];
    $Permiso = $row["Permiso"];

    $options = ["1", "2", "3", "4"];
    $selected_Status = "";
    for ($i = 0 ; $i < 4 ; ++$i)
        {
            
            
            if($Permiso == $options[$i])
            {
                $selected_Status .= "<option value='".$options[$i]."'  selected='selected'>".$options[$i]."</option>";
            }
            else
            {   
                $selected_Status .= "<option value='".$options[$i]."'>".$options[$i]."</option>";
            }
        }

    $form = <<<_END
            <form class="main_body" method="post" action="../admin/admin_editar.php" enctype='multipart/form-data'> 
                    <h5>Nombre de empleado</h5>
                    <input name="editar_nombre_empleado" type="text" value=$Nombre>
                    <h5>Correo de empleado</h5>
                    <input name="editar_correo_empleado" type="text" value=$Correo>
                    <h5>Nombre de usuario</h5>
                    <input name="editar_usuario" type="text" value=$Username>
                    <h5>Contraseña</h5>
                    <p>Dejar vacio para no cambiar contraseña</p>
                    <input name="editar_contrasena" type="text" >
                    </br>
                    <h5>Permiso</h5>
                    <select name="editar_permiso">
                        $selected_Status
                    </br></br>
                    
                    <input type="hidden" name="edit_submition_Username_ID" value='$Username_ID'>
                    <input type="submit" value="Confirmar">
                    </br></br>
                </form>

            _END;


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
                        $form
                    </body>
                </html>
            _END;
}