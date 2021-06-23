<?php 
define('DB', 'u254469571_titan');
define('HOSTDB', 'mysql.hostinger.co');
define('USERDB', 'u254469571_titanroot');
define('PASSDB', 'Root1234');

$server = HOSTDB;
$user = USERDB;
$password = PASSDB;//poner tu propia contraseña, si tienes una.
$bd = DB;
	// $conexion = new models\Conexion();
	$conexion = mysqli_connect($server, $user, $password, $bd);
	if (!$conexion){ 
		die('Error de Conexión: ' . mysqli_connect_errno());	
	}	
