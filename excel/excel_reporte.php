<?php
require __DIR__ . '/master/src/SimpleXLSXGen.php';

$Fecha = $_POST['Fecha'];

$query ="SELECT ord.Orden_ID, ord.statuss, ord.Total  
                FROM orden ord 
                INNER JOIN cliente cli on ord.ClienteID = cli.ClienteID
                WHERE ord.Fecha_Entrega > '$date' " ;


$query ="SELECT pro.statuss as pro_statuss, pro.Importe, pro.Producto_ID  
                FROM orden ord 
                INNER JOIN producto pro on ord.Orden_ID = pro.Orden_ID 
                INNER JOIN cliente cli on ord.ClienteID = cli.ClienteID
                WHERE ord.Fecha_Entrega > '$date' " ;

$books = [
    ['ISBN', 'title', 'author', 'publisher', 'ctry' ],
    [618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
    [908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ']
];
$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $books );
$xlsx->downloadAs('books.xlsx');