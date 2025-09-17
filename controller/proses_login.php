<?php


require_once '../config/db.php';
require_once '../classes/Auth.php';

$database = new Database("localhost", "root", "", "absensi");


$auth = new Auth($database);

$username = $_POST['username'];
$password = md5($_POST['password']);


$auth->login($username, $password);