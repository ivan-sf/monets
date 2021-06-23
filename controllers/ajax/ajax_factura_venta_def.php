<?php namespace models\ajax;

include 'conexion.php';


$codigo=$_POST['codigo'];
$query = "SELECT * FROM products 
			INNER JOIN productdefect 
			ON products.idproducts=productdefect.products_idproducts
			INNER JOIN productdetails 
			ON products.idproducts=productdetails.products_idproducts
			WHERE stateBD=1 AND codeProduct_promotion='$codigo'
			OR stateBD=1 AND codeProduct_promotion2='$codigo'
			OR stateBD=1 AND code='$codigo'";
$result = mysqli_query($conexion, $query);
$row = mysqli_num_rows($result);  

while ($data = mysqli_fetch_assoc($result)) {
	$arreglo["data"][]= $data;
}
echo json_encode($arreglo);

