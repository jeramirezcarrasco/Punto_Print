<?
function print_pantalla_Navbar()
{
    return <<<_END
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="position: static; ">
        <a class="navbar-brand" href="#">Punto Print</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../list_pantalla/list_pantalla_Ordenes.php">Ordenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../list_pantalla/list_pantalla_Productos.php">Productos</a>
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="../login/login.php"><button type="button" class="btn btn-primary btn-small btn-nav">LOGIN/LOGOUT</button></a>
            </li>
        </ul>
    </nav>
    
    
    _END;
}
function print_Navbar()
{
    return <<<_END
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="position: static; ">
        <a class="navbar-brand" href="#">Punto Print</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="../editar/formulario.php">Formulario</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="../list/list_Clientes.php">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../list/list_Ordenes.php">Ordenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../list/list_Productos.php">Productos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Navegacion
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="dropdown-item" href="../list/list_Ordenes.php">Pagina Principal</a></li>
                        <li><a class="dropdown-item" href="../finalizado/list_Ordenes_Finalizado.php">Finalizado</a></li>
                        <li><a class="dropdown-item" href="../Reciclaje/list_Ordenes_Reciclaje.php">Reciclaje</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <li>
            <a href="../login/login.php"><button type="button" class="btn btn-primary btn-small btn-nav">LOGIN/LOGOUT</button></a>
        </li>
    </nav>
    
    
    _END;
}

function print_Restuarar_Navbar()
{
    return <<<_END
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top lined" style="position: static; background-color: #746B6A;">
        <a class="navbar-brand" href="#">Punto Print</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="../reciclaje/list_Clientes_Reciclaje.php">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../reciclaje/list_Ordenes_Reciclaje.php">Ordenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../reciclaje/list_Productos_Reciclaje.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../reciclaje/list_Referencias_Reciclaje.php">Referencias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../reciclaje/list_Disenos_Reciclaje.php">Diseños</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Navegacion
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="dropdown-item" href="../list/list_Ordenes.php">Pagina Principal</a></li>
                        <li><a class="dropdown-item" href="../finalizado/list_Ordenes_Finalizado.php">Finalizado</a></li>
                        <li><a class="dropdown-item" href="../Reciclaje/list_Ordenes_Reciclaje.php">Reciclaje</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <li>
            <a href="../login/login.php"><button type="button" class="btn btn-primary btn-small btn-nav">LOGIN/LOGOUT</button></a>
        </li>
    </nav>
    
    
    _END;
}

function print_Finalizado_Navbar()
{
    return <<<_END
    <nav class="navbar navbar-expand-lg navbar-light  fixed-top lined" style="position: static; background-color: #f0ad4e; font-size: 20px ;">
        <a class="navbar-brand" href="#">Punto Print</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../finalizado/list_Ordenes_Finalizado.php">Ordenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../finalizado/list_Productos_Finalizado.php">Productos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Navegacion
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="dropdown-item" href="../list/list_Ordenes.php">Pagina Principal</a></li>
                        <li><a class="dropdown-item" href="../finalizado/list_Ordenes_Finalizado.php">Finalizado</a></li>
                        <li><a class="dropdown-item" href="../Reciclaje/list_Ordenes_Reciclaje.php">Reciclaje</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <li>
            <a href="../login/login.php"><button type="button" class="btn btn-primary btn-small btn-nav">LOGIN/LOGOUT</button></a>
        </li>
    </nav>
    
    
    _END;
}

function Is_Session_Active()
{
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
    {
        if(time() - $_SESSION['timeout'] > 6000)
        {
            echo "TIMEOUT";
            setcookie(session_name(), '', 100);
            session_unset();
            session_destroy();
            $_SESSION = array();
            return false;
        }
        else
        {
           return true;
        }
    }
    return false;
}

function file_naming($file_path,$file_extension)
{
    if(! file_exists($file_path))
    {
        return $file_path;
    }
    else
    {
        $file_name = str_replace(".".$file_extension, "" , $file_path);

        $index = 1;
        while(file_exists($file_name ."(". $index .").". $file_extension))
        {
            $index ++;
        }

        return $file_name ."(". $index .").". $file_extension;
    }
    
}