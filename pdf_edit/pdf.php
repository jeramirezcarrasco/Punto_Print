<?php
require __DIR__ . '/vendor/autoload.php';
//($tpl [, $x = 0 [, $y = 0 [, $width = null [, $height = null [, $adjustPageSize = false ]]]]]): array
use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();
$pdf->AddPage('L'); // adds page in landscape
$pdf->setSourceFile("punto_print.pdf");
$tplId = $pdf->importPage(1);
$pdf->useTemplate($tplId, 0, 0, $width = 413, $height = 534 , $adjustPageSize= True );


$host='localhost:3306';
$user='root';
$password='mysql';
$database='puntoprint';
$connection = mysqli_connect($host, $user, $password, $database);
if(!$connection){
    die('Could not connect: '. mysqli_error($connection));
}

if (isset($_POST['Orden_ID']))  
{
    $Orden_ID = $_POST['Orden_ID'];     
}

// $Orden_ID = 53;

$temp = orden_print($connection,$pdf,$Orden_ID);
$Cliente_ID = $temp[0];
$Orden_ID = $temp[1];
///////////////////////////////////////////////////////////////////////////////
cliente_print($connection, $pdf, $Cliente_ID);
//////////////////////////////////////////////////////////////////////////////
producto_separacion($connection, $Orden_ID, $Cliente_ID, $pdf);
//////////////////////////////////////////////////////////////////////////////
$pdf->Output();

function cliente_print($connection,$pdf,$Cliente_ID)
{
    $query ="SELECT * FROM cliente WHERE Cliente_ID = '$Cliente_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $Nombre = [91, 50, 22];
    $Email = [120,240,22];
    $Empresa = [105, 57, 22];
    $Tel = [120.5,32,20];
    $Cel = [120.5,128,20];
    /////////////
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $nombre = $row['Nombre'];
    $correo = $row['Cliente_Correo'];
    $empresa = $row['Empresa'];
    $telefono = $row['Telefono'];
    $celular = $row['Celular'];

    //Nombre
    $pdf->SetFont('Arial','',$Nombre[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Nombre[1], $Nombre[0]);
    $pdf->Write(0, $nombre);
    //Correo
    $pdf->SetFont('Arial','',$Email[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Email[1], $Email[0]);
    $pdf->Write(0, $correo);
    //Empresa
    $pdf->SetFont('Arial','',$Empresa[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Empresa[1], $Empresa[0]);
    $pdf->Write(0, $empresa);
    //Telefono
    $pdf->SetFont('Arial','',$Tel[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Tel[1], $Tel[0]);
    $pdf->Write(0, $telefono);
    //Celular
    $pdf->SetFont('Arial','',$Cel[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Cel[1], $Cel[0]);
    $pdf->Write(0, $celular);

}

function orden_print($connection,$pdf,$Orden_ID)
{
    $query ="SELECT * FROM orden WHERE Orden_ID = '$Orden_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

    $Orden_Trabajo = [34, 332, 22];
    $Fecha_ano = [66,382,22];
    $Fecha_mes = [66,348,18];
    $Fecha_dia = [66,332,22];
    $Entrega = [91, 262, 22];
    $Area_Production = [105, 280, 22];
    $Total = [364.5,350,22];
    /////////////
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $Orden_ID = $row["Orden_ID"];
    $Cliente_ID = $row["Cliente_ID"];
    $fecha_Pedido = $row['Fecha_Pedido'];
    $fecha_Entrega = $row['Fecha_Entrega'];
    $total = $row['Total'];


    //Orden de Trabajo
    $pdf->SetFont('Arial','',$Orden_Trabajo[2]);
    $pdf->SetTextColor(231,105,110); // RGB
    $pdf->SetXY($Orden_Trabajo[1], $Orden_Trabajo[0]);
    $pdf->Write(0, $Orden_ID);
    //Fecha
    $Fechas_separada = explode("-",$fecha_Pedido);
    //Año
    $pdf->SetFont('Arial','',$Fecha_ano[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Fecha_ano[1], $Fecha_ano[0]);
    $pdf->Write(0, $Fechas_separada[0]);
    //Mes
    $pdf->SetFont('Arial','',$Fecha_mes[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Fecha_mes[1], $Fecha_mes[0]);
    $pdf->Write(0, DateTime::createFromFormat('!m', $Fechas_separada[1])->format('F'));
    //Dia
    $pdf->SetFont('Arial','',$Fecha_dia[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Fecha_dia[1], $Fecha_dia[0]);
    $pdf->Write(0, $Fechas_separada[2]);
    //Entrega
    $pdf->SetFont('Arial','',$Entrega[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Entrega[1], $Entrega[0]);
    $pdf->Write(0, $fecha_Entrega);
    //Total
    $pdf->SetFont('Arial','B',$Total[2]);
    $pdf->SetTextColor(0,0,0); // RGB
    $pdf->SetXY($Total[1], $Total[0]);
    $pdf->Write(0, "$".$total);

    return [$Cliente_ID, $Orden_ID];
}

function producto_separacion($connection, $Orden_ID, $Cliente_ID, $pdf)
{
    $query ="SELECT * FROM producto WHERE Orden_ID = '$Orden_ID' "; 
    $result = $connection->query($query);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }
    $rows = $result->num_rows;
    $count = 0;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        if($count >=13)
        {
            
            $pdf->AddPage();
            $tplId = $pdf->importPage(1);
            $pdf->useTemplate($tplId, 0, 0, $width = 413, $height = 534 , $adjustPageSize= True );
            cliente_print($connection,$pdf,$Cliente_ID);
            orden_print($connection,$pdf,$Orden_ID);
            $count = 0;
        }
        
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        producto_print($row,$count, $pdf);
        $count+=1;
    }
}

function producto_print($row,$index, $pdf)
{
    //Cantidad, Descripcion, P. Unit, Import
    $Productos_rows = [
        [[177, 13],[177, 54],[177, 297],[177, 349]],
        [[190, 13],[190, 54],[190, 297],[190, 349]],
        [[202, 13],[202, 54],[202, 297],[202, 349]],
        [[214, 13],[214, 54],[214, 297],[214, 349]],
        [[227, 13],[227, 54],[227, 297],[227, 349]],
        [[239, 13],[239, 54],[239, 297],[239, 349]],
        [[250, 13],[250, 54],[250, 297],[250, 349]],
        [[263, 13],[263, 54],[263, 297],[263, 349]],
        [[275, 13],[275, 54],[275, 297],[275, 349]],
        [[287, 13],[287, 54],[287, 297],[287, 349]],
        [[299, 13],[299, 54],[299, 297],[299, 349]],
        [[311, 13],[311, 54],[311, 297],[311, 349]],
        [[325, 13],[325, 54],[325, 297],[325, 349]]
    
    ];
    ///////
    $cantidad = $row['Cantidad'];
    $precio_Unidad = $row['Precio_Unidad'];
    $importe = $row['Importe'];
    $descripcion = $row['Descripcion'];
    $area_Produccion = $row['Area_Produccion'];
    $statuss = $row['statuss'];
    ///////    

    $pdf->SetFont('Arial','',20);
    $pdf->SetTextColor(0,0,0); 
    //Cantidad
    $p = $Productos_rows[$index][0];
    $pdf->SetXY($p[1], $p[0]);
    $pdf->Write(0, $cantidad);
    //P. unit
    $p = $Productos_rows[$index][2];
    $pdf->SetXY($p[1], $p[0]);
    $pdf->Write(0, $precio_Unidad);
    //Import
    $p = $Productos_rows[$index][3];
    $pdf->SetXY($p[1], $p[0]);
    $pdf->Write(0, $importe);
    //Descripcion
    $p = $Productos_rows[$index][1];
    // $Descripcion = $Productos_rows["Descripcion"];
    $Descripcion = $descripcion;
    if(strlen($Descripcion) > 80)
    {
        $pdf->SetFont('Arial','',17);
        $Descripcion = explode(" ",$Descripcion);
        $count = 0;
        $p_index = 0;
        $Y = $p[0] - 5;
        $part1= "";
        while($count + strlen($Descripcion[$p_index]) + 1 < 90)
        {
            $count += strlen($Descripcion[$p_index]) + 1;
            $part1 .= $Descripcion[$p_index] . " ";
            $p_index+=1;
        }
        $pdf->SetXY($p[1], $Y);
        $pdf->Write(0, $part1);
        $part2= implode(array_slice($Descripcion,$p_index   )," ");
        $pdf->SetXY($p[1], $p[0]);
        $pdf->Write(0, $part2);

    }
    else{
        $pdf->SetXY($p[1], $p[0]);
        $pdf->Write(0, $Descripcion);//72

    }
}


?>