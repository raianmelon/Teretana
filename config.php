<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "teretana");
if(!$conn) {
    die("Neuspjesna konekcija");
}