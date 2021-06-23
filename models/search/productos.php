<?php

/////// CONEXIÓN A LA BASE DE DATOS /////////

include"../../config.php";
$conexion = new mysqli(HOSTDB,USERDB,PASSDB,DB);




//////////////// VALORES INICIALES ///////////////////////
$tabla="";
$query="SELECT * FROM products 
    INNER JOIN productdetails 
    ON products.idproducts=productdetails.products_idproducts
    INNER JOIN inventory
    ON products.inventory_idinventory=inventory.idinventory
    INNER JOIN inventorydetails
    ON products.inventory_idinventory=inventorydetails.inventory_idinventory
    WHERE products.stateBD = 1 OR products.stateBD = 2
    ORDER BY products.idproducts desc LIMIT 20";

///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
if(isset($_POST['alumnos']))
{
    $q=$conexion->real_escape_string($_POST['alumnos']);
    $query="SELECT * FROM products 
            INNER JOIN productdetails 
            ON products.idproducts=productdetails.products_idproducts
            WHERE products.stateBD = 1 AND products.codeProduct LIKE '%$q%'
            OR products.stateBD = 1 AND productdetails.nameProduct LIKE '%$q%'
            OR products.stateBD = 2 AND products.codeProduct LIKE '%$q%'
            OR products.stateBD = 2 AND productdetails.nameProduct LIKE '%$q%'
            limit 20";
}

$buscarAlumnos=$conexion->query($query);
if ($buscarAlumnos->num_rows > 0)
{

 while($r=$buscarAlumnos->fetch_object()):

    $tabla?> 

 <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
        <div class="white-box">
            <div class="product-img">
                <img  height="80" width="80" src="<?php echo $r->ruta; ?>">
                <div class="pro-img-overlay">
                    <a href="<?php echo URL; ?>productos/detalles?id=<?php echo $r->idproducts; ?>&configurar" class="bg-warning"><i class="ti-marker-alt"></i></a> 
                </div>
            </div>
            <div class="product-text">
                <span class="badge-success"><b><?php echo $r->totalItem; ?></b></span>
                <h3 class="box-title m-b-0"><?php echo $r->nameProduct; ?></h3>
                <small class="text-muted db">

                <label for="exampleInputEmail1">
                    <b><?php $inventario = $r->inventory_idinventory;
                    $sql = "SELECT * FROM inventory INNER JOIN inventorydetails ON idinventory=inventory_idinventory WHERE idinventory = $inventario";
                    $query=$conexion->query($sql);

                    $dataI = mysqli_fetch_array($query); 
                    echo ucfirst($dataI['nameInventory']);?></b>
                </label>
                    <small><a href="<?php echo URL; ?>productos/detalles?id=<?php echo $r->idproducts; ?>&configurar&inventario"> cambiar inventario</a></small>
                </small>
                    
                    <span class="badge badge-success badge-pill"><small><b>Venta C/U</b></small></span>
                    <span class="text-info"><small>$<?php echo number_format($r->precio); ?></small></span><br>
                    <span class="badge badge-danger badge-pill"><small><b>Compra C/U</b></small></span>
                    <span class="text-info"><small>$<?php echo number_format($r->price_buy); ?></small></span>
                    <br>
            </div>
        </div>
    </div>             
<?php endwhile;


} else
    {
        $tabla="No se encontraron coincidencias con sus criterios de búsqueda.";
    }


echo $tabla;
?>
