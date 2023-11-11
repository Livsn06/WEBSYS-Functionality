
<?php

$server = "localhost";
$uid = "root";
$pass = "";
$dbase = "blog_database";

$conn = mysqli_connect($server,$uid,$pass,$dbase);

if(!$conn){
    echo "<script>alert('no connection');</script>";
}else{
    // echo "<script>alert('Connected');</script>";
}

?>