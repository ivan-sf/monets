<?php 
$modelInventory = new models\Bills();
$con = new models\Conexion();
if(isset($_GET['pos'])){
    $arrayInventory = $modelInventory->arrayBuyPos();
}elseif(isset($_GET['electronica'])){
    $arrayInventory = $modelInventory->arrayBuyElec();
}elseif(isset($_GET['remision'])){
    $arrayInventory = $modelInventory->arrayBuyRem();
}
?>
<div id="page-wrapper" style="min-height: 541px;">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Facturas </h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Caja</a></li>
                            <li><a href="#">Facturas</a></li>
                            <li class="active">Todas</li>
                        </ol>
                    </div>
                </div>
                <div class="row el-element-overlay m-b-40">

                    <div class="col-lg-12">
                        <form>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese el numero de la factura" aria-label="" aria-describedby="basic-addon1">
                                 <div class="input-group-prepend">
                                    <button class="btn btn-primary" type="submit">Buscar!</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php while($datos = mysqli_fetch_array($arrayInventory)) {
                        
                        $set = $modelInventory->set("idbill",$datos['idbills']);
                        $viewBills = $modelInventory->view();
                        $total = 0;
                        while($datos2 = mysqli_fetch_array($viewBills)) {
                            $total = $total + $datos2['total'];
                        }

                     ?> 
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="white-box">
                            <div class="el-card-item">
                                <div class="el-card-avatar el-overlay-1">
                                    <img src="<?php echo URL; ?>views/plugins/images/invoice.png">
                                    <div class="el-overlay scrl-up">
                                        <ul class="el-info">
                                            <li>
                                                <a class="btn default btn-outline image-popup-vertical-fit" href="<?php echo URL; ?>facturas/detalles?id=<?php echo $datos['idbills']; ?>&detalles">
                                                    <i class="icon-magnifier"></i>
                                                </a>
                                            </li>
                                           
                                        </ul>
                                    </div>
                                </div>
                                <div class="el-card-content">
                                    <h4 class="">
                                        <b>
                                            <?php if($datos['typeBill'] == 4){
                                                echo "Factura de compra POS";
                                            }elseif($datos['typeBill'] == 5){
                                                echo "Factura de compra Remision";
                                            }elseif($datos['typeBill'] == 6){
                                                echo "Factura compra Electronica";
                                            }?>
                                        </b>
                                     </h4>
                                     <?php echo $datos['cliente']; ?>


                                    <small><h5>Factura #<?php echo $datos['numeroFactura']; ?></h5></small>
                                    <small><h5><?php echo $datos['dateRegister']; ?></h5></small>
                                    <small><h5><?php echo $total; ?></h5></small>

                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                </div>
            </div>
