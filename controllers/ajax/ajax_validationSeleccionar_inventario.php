<?php

namespace models\ajax;


include 'conexion.php';


$idInventary = $_POST['idInventarySelection'];
if(isset($idInventary)){
	echo "2"; //SI
}else{
	echo "1"; //NO
}


