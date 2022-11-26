<?php
require __DIR__ . '../../utility.php';

session_start();

if(!Is_Session_Active())
{
    header("Location: ../login/login.php");
}
else if($_SESSION['permiso'] < 3)
{
    header("Location: ../list_pantalla/list_pantalla_Productos.php");
}


$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);

$table_Reporte = Reporte_Table($connection);
$table_Empleado = Empleado_Table($connection);

print_HTML($table_Reporte, $table_Empleado);

function Reporte_Table($connection)
{
    $timepo = ["semana" => 7, "Mes" => 30, "3meses" => 90 , "ano" => 360, "centena" => 99999];
    $final_Table= "";
    foreach ($timepo as $key => $value) 
    {
        $table_div= <<<_END
                    <div id="reporte_$key" style=display:none;>
                        <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Ordenes Finalizadas</th>
                                <th>Ordene Pendientes</th>
                                <th>Productos Finalizados</th>
                                <th>Productos Pendientes</th>
                                <th>Ventas Total</th>
                                <th>Ventas Pendientes</th>
                            </tr>
                        </thead>
                        <tbody>
                _END;  
        $date = date('Y-m-d',(strtotime ( '-'.$value. 'day' , strtotime ( date('Y-m-d H:i:s')) ) ));
        $query ="SELECT pro.statuss as pro_statuss, pro.Importe, pro.Producto_ID  
                FROM orden ord 
                INNER JOIN producto pro on ord.Orden_ID = pro.Orden_ID 
                WHERE ord.Fecha_Entrega > '$date' " ;

        $result = $connection->query($query);
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }

        $fecha = $date;
        $Ord_Finalizadas = 0;
        $Ord_Pendientes = 0;
        $Pro_Finalizadas = 0;
        $Pro_Pendientes = 0;
        $Ventas_Total = 0;
        $Ventas_Pendientes = 0;

        $rows = $result->num_rows;
        for ($j = 0 ; $j < $rows ; ++$j)
        {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);
           
            $Producto_ID = $row['Producto_ID'];
            $pro_statuss = $row['pro_statuss'];
            if($pro_statuss != "Finalizado")
            {
                $Pro_Pendientes += 1;
            }
            else
            {
                $Pro_Finalizadas += 1;
            }
        }

        $query ="SELECT Orden_ID, statuss, Total  
                FROM orden ord 
                WHERE ord.Fecha_Entrega > '$date' " ;

        $result = $connection->query($query);
        if(!$result) {
            die('Could not query: '. mysqli_error($connection));
        }

        $rows = $result->num_rows;
        for ($j = 0 ; $j < $rows ; ++$j)
        {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);
           
            $Orden_ID = $row['Orden_ID'];
            $ord_statuss = $row['statuss'];
            $Total = $row['Total'];
            if($ord_statuss != "Finalizado")
            {
                $Ord_Pendientes += 1;
                $Ventas_Pendientes = $Total;
            }
            else
            {
                $Ord_Finalizadas += 1;
                $Ventas_Total += $Total;
            }
        }
        $today = date('Y-m-d H:i:s');
        $table_div .= <<<_END
                    <tr>
                        <td>$date /// $today</td>
                        <td>$Ord_Finalizadas</td>
                        <td>$Ord_Pendientes</td>
                        <td>$Pro_Finalizadas</td>
                        <td>$Pro_Pendientes</td>
                        <td>$ $Ventas_Total</td>
                        <td>$ $Ventas_Pendientes</td>
                        <td><form method='post' action='../excel/excel_reporte.php'> 
                                <button type='submit' name='Fecha' value='$date'>Reporte Excel</button>
                            </form>
                        </td>
                    <tr>
                    _END; 


        $table_div .= "</tbody></table></div>";

        $final_Table .= $table_div;
        
    }
    
    
    return $final_Table;
    
    
    
}

function Empleado_Table($connection)
{
    $query ="SELECT * FROM empleados";
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $table_empleados = "";
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $Nombre = $row["Nombre"];
        $Username = $row["Username"];
        $Correo = $row["Correo"];
        $Permiso = $row["Permiso"];

        $table_row = "<tr>";
        $table_row.= "<td>$Nombre</td>";
        $table_row.= "<td>$Username</td>";
        $table_row.= "<td>$Correo</td>";
        $table_row.= "<td>$Permiso</td>";
        $table_row.= "<td>" . 
        "<form method='post' action='../admin/admin_editar.php'> 
            <button type='submit' name='editar_empleado_Username_ID' value='$Username'>-</button>
        </form> ". "</td>";
        $table_row.= "<td>" . 
        "<form method='post' action='../admin/admin_editar.php'> 
            <button type='submit' name='borrar_empleado_Username_ID' value='$Username'>-</button>
        </form> ". "</td>";
        $table_row .= "</tr>";
        $table_empleados .=$table_row ;
    }

    return $table_empleados;
}

function print_HTML($table_Reporte, $table_Empleado)
{
    $nav = print_Navbar();
    echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        
        
        <link rel="stylesheet" href="../list/List_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../DataTables/datatables.css">

        <link rel="stylesheet" href="../admin/administracion_style.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>        
        <script src="../admin/reporte_cambiar.js"></script>
        <script type="text/javascript" charset="utf8" src="../DataTables/datatables.js"></script>
        <script src="../list/Table_Config.js"></script>
        
        <title>Document</title>
    </head>
    <body>
        $nav
        <div class="section">
            <h3 class="titulo">REPORTE</h3>
            <div id="reporte_Div">
                <select name="reporte" id="reporte">
                    <option value="0">Escojer rango de fecha</option>
                    <option value="1">Semana</option>
                    <option value="2">Mes</option>
                    <option value="3">3 Mes</option>
                    <option value="4">Año</option>
                    <option value="5">Centena</option>
                </select>
                $table_Reporte
            </div>
        </div>
        
        <div class="section">
            </br>
            <h3 class="titulo">Añadir Empleado</h3>
            <form class="main_body" method="post" action="../admin/admin_editar.php" enctype='multipart/form-data'> 
                <h5>Nombre de empleado</h5>
                <input name="anadir_nombre_empleado" type="text">
                <h5>Correo de empleado</h5>
                <input name="anadir_correo_empleado" type="text">
                <h5>Nombre de usuario</h5>
                <input name="anadir_usuario" type="text">
                <h5>Contraseña</h5>
                <input name="anadir_contrasena" type="text">
                </br></br>
                <h5>Permiso</h5>
                <select name="anadir_permiso">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                </br></br>
                <input type="submit" value="Confirmar">
            </form>
        </div>
        <div class="section">
            </br>
            <h3 class="titulo">Lista Empleados</h3>
            </br>
            <table class="table table-bordered table-hover" id="MainTable">
                <thead>
                    <tr>
                        <th>Nombre ID</th>
                        <th>Username</th>
                        <th>Correo</th>
                        <th>Permiso</th>
                        <th>Editar Empleado</th>
                        <th>Borrar Empleado</th>
                    </tr>
                </thead>
                <tbody>
                    $table_Empleado
                </tbody>
            </table> 
        </div>
        <div class="section">
            </br>
            <h3 class="titulo"> Otro </h3>
            </br>
            <h5> LIMPIAR PAPELERA </h5>
            <form method='post' action='../admin/admin_editar.php'> 
                <button type='submit' name='limpiar_papelera' value=''>LIMPIEAR PAPELERA</button>
            </form> 
            </br>

            <h5> BORRAR ORDENES FINALIZADAS (mover a papelera) </h5>
            <form method='post' action='../admin/admin_editar.php'> 
                <button type='submit' name='borrar_finalizadas' value=''>BORRAR FINALIZADO</button>
            </form> 
            </br>

            <h5> MOVER TODO A PAPELERA </h5>
            <form method='post' action='../admin/admin_editar.php'> 
                <button type='submit' name='mover_todo_papelera' value=''>MOVER TODO A PAPELERA</button>
            </form> 
            </br>

            <h5> RECUPERAR TODO DE PAPELERA </h5>
            <form method='post' action='../admin/admin_editar.php'> 
                <button type='submit' name='recuperar_papelera' value=''>RECUPERAR DE PAPELERA</button>
            </form> 
            </br>

            <h5> !! BORRAR TODO !!</h5>
            <form method='post' action='../admin/admin_editar.php'> 
                <button type='submit' name='nuclear_option' value='' class="btn btn-danger">OPCION NUCLEAR</button>
            </form> 
        </div>
        <script src="../admin/reporte_cambiar.js"></script>


    </body>
    </html>
    _END;
}


// <div id="reporte_Semana">
//                 <p>Hello</p>
//             </div>
//             <div id="reporte_Mes" style=display:none;>
//                 <p>TO</p>
//             </div>
//             <div id="reporte_Ano" style=display:none;>
//                 <p>THIS</p>
//             </div>
//             <div id="reporte_Centena" style=display:none;>
//                 <p>PLASE</p>
//             </div>