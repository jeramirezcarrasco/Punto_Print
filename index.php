<?php
session_start();

$host='localhost:3306';
$user='root';
$password='mysql';
$database='temp';
$connection = mysqli_connect($host, $user, $password, $database);


if(!$connection)
{
    die('Could not connect: '. mysqli_error($connection));
}
else{echo "CONNECTED";}

$query="SELECT * FROM test1";
$result = $connection->query($query);

if(!$result) {
    die('Could not query: '. mysqli_error($connection));
}
$rows = $result->num_rows;
echo $rows;
for ($j = 0 ; $j < $rows ; ++$j)
{
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    echo 'aaaazzzzaaa: ' . $row['aaaaaaa'] . '<br>';
    echo 'Lorem: ' . $row['lorem'] . '<br>';

    echo "--------------------- <br>";
}
$error = "ASDASDASDASD";

if (isset($_POST['Username']) &&
    isset($_POST['pw']))
{
    $query="SELECT * FROM test1";
    $input1 = $_POST['Username'];
    $input2 = $_POST['pw'];
    $newQuery = "INSERT INTO test1 VALUES (" . $input1 . "," . $input2 . ")";  
    $Newresult = $connection->query($newQuery);
    if(!$result) {
        die('Could not query: '. mysqli_error($connection));
    }

}
else{
    echo "DIDNT WORK";
}

    
echo <<<_END
<html>
<head>
        <title>Admin</title>
</head>
<body>
        <pre>
                $error
                <h1> LOGIN </h1>
                <form method="post" action="index.php"> 
                        Username <input type="text" name="Username" size="10">
                        Password <input type="text" name="pw" size="10">
                        <input type="submit" value="login">
                </form>
        </pre>
</body>
</html>
_END;


