<?php
require __DIR__ . '/master/src/SimpleXLSXGen.php';
$books = [
    ['ISBN', 'title', 'author', 'publisher', 'ctry' ],
    [618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
    [908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ']
];
$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $books );
$xlsx->downloadAs('books.xlsx');