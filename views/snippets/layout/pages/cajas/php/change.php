<?php 
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

if ($row == 0) {
	$q1 = $con->query("insert into bills(users_idusers,cash_idcash,cliente,fecha,codeBar,typeBill,dateRegister) value($user,$cash,'$cliente',NOW(),'$codeBar',$typeBill,NOW())");
}else{
	$q1 = $con->query("insert into bills(users_idusers,cash_idcash,cliente,fecha,codeBar,dateRegister) value($user,$cash,'$cliente',NOW(),'',$typeBill,NOW())");
}

if($q1){
$cart_id = $con->insert_id;
$total = 0;
$ganancia = 0;

foreach($_SESSION["cart"] as $c){
	$total = $c['q'] * $c['pu'];
	$ganancia = $c['pu'] - $c['pc'];

	$q1 = $con->query("INSERT INTO `billdetails` (`idbillDetails`, `nombre`, `precio_c-u`, `precio_total`, `cantidad`, `bills_idbills`, `products_idproducts`, `precio_compra`, `precio_c-u_prom`, `dateRegister`, `ganancia_c-u`) VALUES ('','$c[nombreProducto]','$c[pu]','$total','$c[q]','$cart_id','$c[product_id]','$c[pc]', '$c[pp]',NOW(),'$ganancia')");
}

$sql = $con->query("SELECT * FROM bills INNER JOIN billdetails ON idbills=bills_idbills WHERE idbills='$cart_id'");
$sql = $con->query("SELECT * FROM products INNER JOIN productdetails ON idproducts=products_idproducts WHERE idproducts='$cart_id'");
$total = 0;
foreach($_SESSION["cart"] as $c){
	$total += $c['q'] * $c['pu'];
}

$sql = $con->query("UPDATE `irocket`.`bills` SET `precio_total`='$total', `precio_compras`='1', `impuesto`='1', `total_cancelado`='1', `saldo`='1', `cambio`='1' WHERE `idbills`='$cart_id'");

unset($_SESSION["cart"]);
unset($_SESSION["client"]);
}
}








require __DIR__ . '/../autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta l??nea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
date_default_timezone_set('America/Bogota');

/*
	Este ejemplo imprime un
	ticket de venta desde una impresora t??rmica
*/


/*
	Una peque??a clase para
	trabajar mejor con
	los productos
	Nota: esta clase no es requerida, puedes
	imprimir usando puro texto de la forma
	que t?? quieras
*/

/*
	Aqu??, en lugar de "POS-58" (que es el nombre de mi impresora)
	escribe el nombre de la tuya. Recuerda que debes compartirla
	desde el panel de control
*/

$nombre_impresora = "POS-58"; 


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);


/*
	Vamos a imprimir un logotipo
	opcional. Recuerda que esto
	no funcionar?? en todas las
	impresoras

	Peque??a nota: Es recomendable que la imagen no sea
	transparente (aunque sea png hay que quitar el canal alfa)
	y que tenga una resoluci??n baja. En mi caso
	la imagen que uso es de 250 x 250
*/

# Vamos a alinear al centro lo pr??ximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/
############################################################try{
############################################################	$logo = EscposImage::load("../logo.png", false);
############################################################    $printer->bitImage($logo);
############################################################}catch(Exception $e){/*No hacemos nada si hay error*/}

/*
	Ahora vamos a imprimir un encabezado
*/

$array= $con->query("SELECT * FROM company");
$datos = mysqli_fetch_array($array);

############################################################$printer->text(ucfirst($datos['nameCompany']) . "\n");
#La fecha tambi??n
############################################################$printer->setJustification(Printer::JUSTIFY_CENTER);

	$array= $con->query("SELECT * FROM bills INNER JOIN billdetails ON idbills=bills_idbills WHERE idbills='$cart_id'");
	$data = mysqli_fetch_array($array);
############################################################$printer->text("Cliente: " . $data['cliente'] . "\n\n");

############################################################$printer->text(date("Y-m-d H:i:s") . "\n\n");


/*
	Ahora vamos a imprimir los
	productos
*/
	$array= $con->query("SELECT * FROM bills INNER JOIN billdetails ON idbills=bills_idbills WHERE idbills='$cart_id'");

# Para mostrar el total
$total = 0;
	while ($datos = mysqli_fetch_array($array)) {
		$total += $datos['cantidad'] *$datos['precio_c-u'];
		$printer->setJustification(Printer::JUSTIFY_LEFT);
    	$printer->text($datos['cantidad'] . "x " . $datos['nombre'] . "\n");

    	$printer->setJustification(Printer::JUSTIFY_RIGHT);
    	$printer->text(' $' . number_format($datos['cantidad']*$datos['precio_c-u']) . "\n");
	}


/*
	Terminamos de imprimir
	los productos, ahora va el total
*/
$printer->text("--------\n");
$printer->text("TOTAL: $". number_format($total) ."\n");


/*
	Podemos poner tambi??n un pie de p??gina
*/
$printer->setJustification(Printer::JUSTIFY_CENTER);

try{
	$logo = EscposImage::load("barras_prueba.png.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){/*No hacemos nada si hay error*/}

############################################################$printer->text("\nMuchas gracias por su compra\n\n");


/*Alimentamos el papel 3 veces*/
$printer->feed(3);

/*
	Cortamos el papel. Si nuestra impresora
	no tiene soporte para ello, no generar??
	ning??n error
*/
$printer->cut();

/*
	Por medio de la impresora mandamos un pulso.
	Esto es ??til cuando la tenemos conectada
	por ejemplo a un caj??n
*/
$printer->pulse();

/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexi??n con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();









if (isset($_POST['typeBill'])) {
	if ($_POST['typeBill'] == '1') {
		print "<script>window.location='".  URL ."cajas?caja=ventas';</script>";
	}elseif ($_POST['typeBill'] == '2') {
		print "<script>window.location='".  URL ."cajas?caja=compras';</script>";
	}elseif ($_POST['typeBill'] == '3') {
		print "<script>window.location='".  URL ."cajas?caja=cambios';</script>";
	}elseif ($_POST['typeBill'] == '4') {
		print "<script>window.location='".  URL ."cajas?caja=devoluciones';</script>";
	}else{
		print "<script>window.location='".  URL ."cajas';</script>";
	}
}


?>