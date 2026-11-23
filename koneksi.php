<?php
$host     = "sql213.infinityfree.com";
$user     = "if0_43512059";
$password = "XuzrgzckDX"; 
$database = "if0_43512059_db_MPTI";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>