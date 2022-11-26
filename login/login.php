<?php
require __DIR__ . '../../utility.php';

session_start();
// If Session is not active
if(!Is_Session_Active())
{
    $status_message = "";
    if(isset($_POST['username']))
    {
        $host='localhost:3306';
        $user='root';
        $password='mysql';
        $database='puntoprint';
        $connection = mysqli_connect($host, $user, $password, $database);

        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = $connection->query("SELECT * FROM empleados WHERE Username = '$username'");
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }
        if($result->num_rows == 0) {
            $status_message = "Nombre de usuario no existe";
            // die('Could not query: '. mysqli_error($connection));
        }
        else
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if(password_verify($password,$row["Password"]))
            {
                $_SESSION['username'] = $row["Username"];
                $_SESSION['permiso'] = $row["Permiso"];
                $_SESSION["loggedin"] = True;
                $_SESSION["timeout"] = time();
                // header("Location: ../list/list_Productos.php");
                $status_message = "User has LOGIN";
                if($row["Permiso"] < 2)
                {
                    header("Location: ../list_pantalla/list_pantalla_Ordenes.php");
                }
                else if($row["Permiso"] > 1)
                {
                    header("Location: ../list/list_Ordenes.php");
                }
            }
            else{
                $status_message = "Contrasena incorrecta";
            }
        }   
    }
    print_Login_Page($status_message);   

}
else
{
    // header("Location: ../list/list_Productos.php");
    if(isset($_POST['LogOut']))
    {
        setcookie(session_name(), '', 100);
        session_unset();
        session_destroy();
        $_SESSION = array();
        $status_message = "User has logOut";
    }
    else
    {
        $status_message = "User is LOGIN";
    }
    print_Login_Page($status_message);
}

function print_Login_Page($status_message)
{
    $nav = print_Navbar();
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <link rel="stylesheet" href="../editar/editar_style.css">
            <link rel="stylesheet" href="../login/login_style.css">
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
            
            <title>Inventariario</title>
        </head>
        <body>
            $nav
            <div class="main_body">
                <div class="login_div">
                    <h4>$status_message</h4>
                    <form  method="post" action="" enctype='multipart/form-data'> 
                        <p> Nombre de usuario </p>
                        <input type="text" name="username" required>
                        <p> Contraseña </p>
                        <input type="password" name="password" required>
                        <br/>
                        <br/>
                        <input type="submit" value="Confirmar">
                    </form>
                    <form method='post' action='' > 
                        <input type='hidden' name='LogOut'>
                        <input type='submit' value='LogOut'>
                    </form>
                    <br/>
                </div>
            </div>

        </body>
    </html>
    _END;

}


function print_Login_requerido()
{
    
}