<?php 
date_default_timezone_set('America/Bogota');

session_start();
if (isset($_SESSION['adminUserNew'])) {
	$user = $_SESSION['adminUserNew'];
}elseif (isset($_SESSION['UserNew'])) {
	$user = $_SESSION['UserNew'];
}
if (isset($_SESSION['cash'])) {
	$caja = $_SESSION['cash'];
	$sql = $con->query("SELECT * FROM cashdetails WHERE nameCash='$caja'");
	$array = mysqli_fetch_array($sql);
	$cash = $array['cash_idcash'];
}
if(!empty($_POST)){
	if (isset($_POST['codigocliente'])) {
		$clienteDocumento = $_POST['codigocliente'];
		$sql = $con->query("SELECT * FROM userdetails WHERE documentUser='$clienteDocumento'");
		$array = mysqli_fetch_array($sql);
		if ($array['range'] == 2) {
			$cliente = $array['users_idusers'];
		}else{
			$cliente = $_POST['codigocliente'];
		}
	}

$codeBar = date('s') . rand(0,1000) . date('d');
$sql = $con->query("SELECT * FROM bills WHERE codeBar='$codeBar'");
$array = mysqli_fetch_array($sql);
$row = mysqli_num_rows($sql);
$typeBill = $_POST['typeBill'];
$pago = $_POST['pagoForm'];
$descuento = $_POST['descuento2'];
$total = 0;
foreach($_SESSION["cart"] as $b){
	$total += $b['q'] * $b['pu']; 	 	
}
$total;
if ($total-$descuento<=$pago) {
	$stateBill = 1;
}else{
	$stateBill = 2;
}
$dateTime = date("Y-m-d");

if ($row == 0) {

	$q1 = $con->query("insert into bills(users_idusers,cash_idcash,cliente,fecha,codeBar,typeBill,dateRegister,pago,stateBill,pos) value($user,$cash,'$cliente',NOW(),'$codeBar',$typeBill,NOW(),$pago,$stateBill,$descuento)");
	

	
}else{
	$q1 = $con->query("insert into bills(users_idusers,cash_idcash,cliente,fecha,codeBar,dateRegister,pago,stateBill,pos) value($user,$cash,'$cliente',NOW(),'',$typeBill,NOW(),$pago,$stateBill,$descuento)");

	
}


if($q1){
$cart_id = $con->insert_id;
$total = 0;
$ganancia = 0;


foreach($_SESSION["cart"] as $c){
	$total = $c['q'] * $c['pu'];
	$ganancia = $c['pu'] - $c['pc'];

	$q1 = $con->query("INSERT INTO `billdetails` (`idbillDetails`, `nombre`, `precio_c-u`, `precio_total`, `cantidad`, `bills_idbills`, `products_idproducts`, `precio_compra`, `precio_c-u_prom`, `dateRegister`, `ganancia_c-u`, stateBillDetail) VALUES ('','$c[nombreProducto]','$c[pu]','$total','$c[q]','$cart_id','$c[product_id]','$c[pc]', '$c[pp]',NOW(),'$ganancia', 1)");

	$sql = $con->query("SELECT * FROM `products` INNER JOIN productdetails ON idproducts=products_idproducts WHERE `idproducts`='$c[product_id]'");
	$array = mysqli_fetch_array($sql);
	$quantity = $array['quantityProduct'];
	$totalInventory = $array['totalItemsInventory'];
	$sale = $array['totalSales'];
	$saleProm = $array['totalSales_prom'];
	$item = $array['totalItem'];
	$precioBD = $array['precio'];
	$precioPromBD = $array['precio_promotion'];

	$quantityProduct = $quantity - $c['q'];
	$totalItemsInventory = $totalInventory - $c['q'];
	$totalSales = $sale + $c['q'];
	$totalSalesProm = $saleProm + $c['q'];
	$totalItem = $item - $c['q'];
	if ($c['pu'] == $precioBD) {
		$sql = $con->query("UPDATE `products` SET `quantityProduct`='$quantityProduct' WHERE `idproducts`='$c[product_id]'");
		$sql = $con->query("UPDATE `productdetails` SET `totalItemsInventory`='$totalItemsInventory', `totalSales`='$totalSales', `totalItem`='$totalItem' WHERE `idproductDetails`='$c[product_id]'");
	}else{
		$sql = $con->query("UPDATE `products` SET `quantityProduct`='$quantityProduct' WHERE `idproducts`='$c[product_id]'");
		$sql = $con->query("UPDATE `productdetails` SET `totalItemsInventory`='$totalItemsInventory', `totalSales_prom`='$totalSalesProm', `totalItem`='$totalItem' WHERE `idproductDetails`='$c[product_id]'");



		$sql = $con->query("SELECT * FROM `productdefect` WHERE `precio_defect` = $c[pu]");
		$array = mysqli_fetch_array($sql);
		$quantity = $array['cantidadDef'];
		$cc=$quantity-$c['q'];	
		
		$sql = $con->query("UPDATE `productdefect` SET `cantidadDef` = '$cc' WHERE `productdefect`.`precio_defect` = $c[pu]");

		$sql = $con->query("SELECT * FROM `productdefect` WHERE `precio_defect` = $c[pu]");
		$array = mysqli_fetch_array($sql);
		$quantity = $array['cantidadDef'];
		$stateNot = $array['stateNot'];
		$products_idproducts = $array['products_idproducts'];
		if ($quantity<0&&$stateNot==0) {
			$sql = $con->query("UPDATE `productdefect` SET `stateNot` = '1' WHERE `productdefect`.`precio_defect` = $c[pu]");
			$iduser = 1;
			$datetimeNot = 	date("Y-m-d G:i:s A");
			$mensaje = "Se ha excedido el limite de venta en un producto defectuoso.";
			$sql = $con->query("INSERT INTO `notifications` (`users_idusers`, `products_idproducts`, `typeNotification`, `message`, `date_register`) VALUES ('$iduser', '$products_idproducts', '96', '$mensaje', '$datetimeNot')");
		}
		if ($quantity>0&&$stateNot==0) {
			$sql = $con->query("UPDATE `productdefect` SET `stateNot` = '0' WHERE `productdefect`.`precio_defect` = $c[pu]");
		}

		
	}

	

	$sql = $con->query("SELECT * FROM depositaccount INNER JOIN depositaccountdetails ON iddepositAccounts=depositAccount_iddepositAccounts");
	$array = mysqli_fetch_array($sql);
	$assets = $array['currentAssets'];
	$buy = $array['total_sales'];

	$total_buy = $buy + $c['q'];
}


$sql = $con->query("SELECT * FROM bills INNER JOIN billdetails ON idbills=bills_idbills WHERE idbills='$cart_id'");
$sql = $con->query("SELECT * FROM products INNER JOIN productdetails ON idproducts=products_idproducts WHERE idproducts='$cart_id'");
$total = 0;
foreach($_SESSION["cart"] as $c){
	$total += $c['q'] * $c['pu'];
}

if ($pago>=$total) {
	$currentAssets = $assets + $total;
	$sql = $con->query("UPDATE `depositaccountdetails` SET `currentAssets`='$currentAssets', `total_buy`='$total_buy' WHERE `iddepositAccountDetails`='1'");
}else{
	$currentAssets = $assets + $pago;
	$sql = $con->query("UPDATE `depositaccountdetails` SET `currentAssets`='$currentAssets', `total_buy`='$total_buy' WHERE `iddepositAccountDetails`='1'");
}

$sql = $con->query("UPDATE `bills` SET `precio_total`='$total', `precio_compras`='1', `impuesto`='1', `total_cancelado`='1', `saldo`='1', `cambio`='1' WHERE `idbills`='$cart_id'");

if (isset($_POST['codigocliente'])) {
		$clienteDocumento = $_POST['codigocliente'];
		$sql = $con->query("SELECT * FROM userdetails WHERE documentUser='$clienteDocumento'");
		$array = mysqli_fetch_array($sql);
		if ($array['range'] == 2) {
			$cliente = $array['nameUser'];
		}else{
			$cliente = $_POST['codigocliente'];
		}
	}

if ($pago>=$total) {
	$cambio = $pago - $total;
	$q2 = $con->query("INSERT INTO `movementdepositaccount` (`bills_idbills`, `cash_idcash`, `users_idusers`, `typeMovement`, `totalMoney`, `dataRegister`, `pago`, `saldo`, `typeDeposit`) VALUES ('$cart_id', '$cash', '1', '6', '$total', '$dateTime', '$pago', '$cambio', '$cliente')");
}else{
	$saldo = $total - $pago;
	$q2 = $con->query("INSERT INTO `movementdepositaccount` (`bills_idbills`, `cash_idcash`, `users_idusers`, `typeMovement`, `totalMoney`, `dataRegister`, `pago`, `saldo`, `typeDeposit`) VALUES ('$cart_id', '$cash', '1', '7', '$pago', '$dateTime', '$pago', '$saldo', '$cliente')");
}


$saldo = $total - $pago;
if ($pago>=$total) {
	$saldo = 0;
}
if (isset($_POST['codigocliente'])) {
	$clienteDocumento = $_POST['codigocliente'];
	$sql = $con->query("SELECT * FROM userdetails WHERE documentUser='$clienteDocumento'");
	$array = mysqli_fetch_array($sql);
	if ($array['range'] == 2) {
		$cliente = ucwords($array['nameUser'] . " " . $array['lastnameUser']);
	}else{
		if ($_POST['codigocliente'] != '') {
			$cliente = $_POST['codigocliente'];
		}else{
			$cliente = 'N/A';
		}
	}
}

if (isset($_SESSION['cash'])) {
	$caja = $_SESSION['cash'];
	$sql = $con->query("SELECT * FROM cashdetails WHERE nameCash='$caja'");
	$array = mysqli_fetch_array($sql);
	$cash = ucfirst($array['nameCash']);
}

$q2 = $con->query("INSERT INTO `billreports` (`bills_idbills`, `total`, `pago`, `saldo`, `cliente`, `empleado`, `caja`, `fecha`, `estado`, `typeBill`) VALUES ('$cart_id', '$total', '$pago', '$saldo', '$cliente', '1', '$cash', '$dateTime', '1', 'Venta')");



unset($_SESSION["cart"]);
unset($_SESSION["client"]);
}
}




require __DIR__ . '/../autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
date_default_timezone_set('America/Bogota');

/*
	Este ejemplo imprime un
	ticket de venta desde una impresora térmica
*/


/*
	Una pequeña clase para
	trabajar mejor con
	los productos
	Nota: esta clase no es requerida, puedes
	imprimir usando puro texto de la forma
	que tú quieras
*/

/*
	Aquí, en lugar de "POS-58" (que es el nombre de mi impresora)
	escribe el nombre de la tuya. Recuerda que debes compartirla
	desde el panel de control
*/
$nombre_impresora = "CAJA2"; 


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);


/*
	Vamos a imprimir un logotipo
	opcional. Recuerda que esto
	no funcionará en todas las
	impresoras

	Pequeña nota: Es recomendable que la imagen no sea
	transparente (aunque sea png hay que quitar el canal alfa)
	y que tenga una resolución baja. En mi caso
	la imagen que uso es de 250 x 250
*/

# Vamos a alinear al centro lo próximo que imprimamos

$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo

*/

try{
	$array= $con->query("SELECT * FROM company INNER JOIN companydetails ON idcompany=company_idcompany");
	$data = mysqli_fetch_array($array);
	$dataR = $data['rutaLogoCompany'];
	$logo = EscposImage::load("../../../../../../" . $dataR, false);
    //$printer->bitImage($logo);
}catch(Exception $e){/*No hacemos nada si hay error*/}

/*
	Ahora vamos a imprimir un encabezado
*/

$array= $con->query("SELECT * FROM company");
$datos = mysqli_fetch_array($array);

//$printer->text(ucfirst($datos['nameCompany']) . "\n");
#La fecha también
//$printer->setJustification(Printer::JUSTIFY_CENTER);

	$array= $con->query("SELECT * FROM bills INNER JOIN billdetails ON idbills=bills_idbills WHERE idbills='$cart_id'");
	$data = mysqli_fetch_array($array);
	$datadoc = $data['cliente'];
	if ($datadoc != '') {
		$sql= $con->query("SELECT * FROM users INNER JOIN userdetails 
						ON idusers=users_idusers 
						WHERE idusers='$datadoc'");
		$data1 = mysqli_fetch_array($sql);
		$datar = mysqli_num_rows($sql);
		if ($datar > 0) {
			//$printer->text("Cliente: " . ucwords($data1['userName']) . " " . ucfirst($data1['lastnameUser']) . "\n");
			//$printer->text("Documento: " . $data1['documentUser'] . "\n");
		}else{
			//$printer->text("Cliente: " . $data['cliente'] . "\n");
		}
	}

//$printer->text(date("Y-m-d H:i:s") . "\n\n");


/*
	Ahora vamos a imprimir los
	productos
*/
	$array= $con->query("SELECT * FROM bills INNER JOIN billdetails ON idbills=bills_idbills WHERE idbills='$cart_id'");

# Para mostrar el total
$total = 0;
	while ($datos = mysqli_fetch_array($array)) {
		$total += $datos['cantidad'] *$datos['precio_c-u'];
		$pago = $datos['pago'];
		//$printer->setJustification(Printer::JUSTIFY_LEFT);
    	//$printer->text($datos['cantidad'] . "x " . $datos['nombre'] . "\n");

    	//$printer->setJustification(Printer::JUSTIFY_RIGHT);
    	//$printer->text(' $' . number_format($datos['cantidad']*$datos['precio_c-u']) . "\n");
	}


/*
	Terminamos de imprimir
	los productos, ahora va el total
*/
//$printer->text("--------\n");
//$printer->text("PAGO: $". number_format($pago) ."\n");
if ($pago > $total) {
	//$printer->text("CAMBIO: $". number_format($pago-$total) ."\n");
}else{
	//$printer->text("SALDO: $". number_format($pago-$total) ."\n");
}
//$printer->text("TOTAL: $". number_format($total) ."\n");


/*
	Podemos poner también un pie de página
*/
//$printer->setJustification(Printer::JUSTIFY_CENTER);

try{
	$logo = EscposImage::load("barras_prueba.png", false);
    //$printer->bitImage($logo);
}catch(Exception $e){/*No hacemos nada si hay error*/}

//$printer->text("\nMuchas gracias por su compra\n\n");


/*Alimentamos el papel 3 veces*/

//$printer->feed(3);

/*
	Cortamos el papel. Si nuestra impresora
	no tiene soporte para ello, no generará
	ningún error
*/
//$printer->cut();

/*
	Por medio de la impresora mandamos un pulso.
	Esto es útil cuando la tenemos conectada
	por ejemplo a un cajón
*/

//$printer->pulse();

/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/

$printer->close();







$array= $con->query("SELECT * FROM bills INNER JOIN billdetails ON idbills=bills_idbills WHERE idbills='$cart_id'");
while ($datos = mysqli_fetch_array($array)) {
	$id = $datos['idbills'];
}    	


if (isset($_POST['typeBill'])) {
	if ($_POST['typeBill'] == '1') {
		print "<script>window.location='".  URL ."cajas?caja=ventas&proceso=exitoso&id=".$id."';</script>";
	}elseif ($_POST['typeBill'] == '2') {
		print "<script>window.location='".  URL ."cajas?caja=compras&proceso=exitoso';</script>";
	}elseif ($_POST['typeBill'] == '3') {
		print "<script>window.location='".  URL ."cajas?caja=cambios&proceso=exitoso';</script>";
	}elseif ($_POST['typeBill'] == '4') {
		print "<script>window.location='".  URL ."cajas?caja=devoluciones&proceso=exitoso';</script>";
	}else{
		print "<script>window.location='".  URL ."cajas?proceso=exitoso';</script>";
	}
}






?>