<?php
if(isset($_SESSION['adminUser']) OR isset($_SESSION['adminUserNew'])){
//echo $_SESSION['adminUser'];
    $modelUser = new models\User();
    $modelInventory = new models\Inventory();
    $array = $modelUser->inner();
    $arrayInv = $modelInventory->arrayCreate();
	$rowInv = $modelInventory->row();
	$rowPreFinal = $rowInv+1;
	$rowFinal = $rowPreFinal*1000;

    $modelContabilidad = new models\Contabilidad();
    $idcodigo = $_GET['id'];
    $modelContabilidad->set("idcodigo", $idcodigo);
    $arrayTC = $modelContabilidad->set("comprobante", $idcodigo);
    $arrayVCod = $modelContabilidad->arrayVCod();
    $datosTC= mysqli_fetch_array($arrayVCod);
											
}else{
    header("location:" . URL);
}


?>

<div id="page-wrapper">
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Contabilidad</h4> </div>

        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#">Panel</a></li>
            <li><a href="#">Contabilidad</a></li>
            <li class="active">Lista</li>
        </ol>
    </div>
        <!-- /.col-lg-12 -->
    </div>
    <button class="btn m-btn--pill btn-badge" type="submit"><a href="<?php echo URL; ?>contabilidad/crear?tipo=codigo" class="">Crear codigo</a></button>
    <button class="btn m-btn--pill btn-badge"><a href="<?php echo URL; ?>contabilidad/puc">PUC</a></button>
    <br>
    <br>

                	
				<div class="white-box">
					<center><h3 class="box-title">Editar cuenta contable</h3></center>
					<div class="row"><br>
					

						<div class="col-sm-12">
							<div id="slimtest1">
								<div class="m-grid__item m-grid__item--fluid m-wrapper">


					
					

										<form method="POST" enctype="multipart/form-data" id="formulario" onsubmit="return checkSubmit();">
											<input type="hidden" name="iduser" value=" <?php echo $array['idusers']; ?> ">
											<input type="hidden" name="nameUser" value=" <?php echo $array['nameUser']; ?> ">
											<input type="hidden" name="codeCurrent" value=" <?php echo $rowFinal; ?> ">
											
											<input type="hidden" name="iduser" value="">
											<div class="m-portlet__body">
												<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
													<div class="m-form__actions m-form__actions--solid">
														<div class="row">
															
															<div class="col-lg-12">
																<?php if (isset($_GET['success'])) {
																	echo "<div class='m-alert m-alert--icon m-alert--air m-alert--square alert alert-success alert-dismissible fade show' role='alert' id='alertabien'>
																		<div class='m-alert__icon'>
																		<i class='flaticon-rocket'></i>
																		</div>
																		<div class='m-alert__text'>
																		<center>
																		<strong>
																		Genial !
																		</strong>
																		Los datos se han editado correctamente</a>
	
																		</center>
																		
																		</div>
																		<div class='m-alert__close'>
																		<button type='button' class='close' data-dismiss='alert' aria-label='Close'></button>
																		</div>
																		</div>";
																}elseif (isset($_GET['error'])){
																	if ($_GET['error'] == 2) {
																		echo "<div class='m-alert m-alert--icon m-alert--air m-alert--square alert alert-danger alert-dismissible fade show' role='alert' id='alertabien'>
																			<div class='m-alert__icon'>
																			<i class='flaticon-rocket'></i>
																			</div>
																			<div class='m-alert__text'>
																			<center>
																				<strong>
																				Lo sentimos !
																				</strong>
																				Ya has agregado los 4 inventarios permitidos en tu paquete actual recuerda que puedes ampliar tu plan de inventarios por medio de nuestro  <a target='_blank' href=' " .URL_SITIO . "'>nuestro sitio web.</a>
																			</center>
																			
																			</div>
																			<div class='m-alert__close'>
																			<button type='button' class='close' data-dismiss='alert' aria-label='Close'></button>
																			</div>
																			</div>";
																	}else{
																		echo "<div class='m-alert m-alert--icon m-alert--air m-alert--square alert alert-danger alert-dismissible fade show' role='alert' id='alertabien'>
																			<div class='m-alert__icon'>
																			<i class='flaticon-rocket'></i>
																			</div>
																			<div class='m-alert__text'>
																			<center>
																			<strong>
																			Lo sentimos !
																			</strong>
																			El codigo ingresado ya existe.</a>
																			</center>
																			</div>
																			<div class='m-alert__close'>
																			<button type='button' class='close' data-dismiss='alert' aria-label='Close'></button>
																			</div>
																			</div>";
																		}
																	} 
																?>
																</div>
															</div>
														</div>
													</div>

													<div class="form-group m-form__group row" id="">
														<div class="col-lg-3">
																<b>Codigo *</b><br>	
															<br>
															<div class="m-input-icon" id="input1">

																<input value="<?php echo $datosTC['codigo'];?>" disabled type="text" class="form-control m-input m-input--air m-input--pill" placeholder="Codigo" name="codigoPuc" id="codigoPuc">
																<input value="<?php echo $datosTC['idpuc'];?>" type="hidden" class="form-control m-input m-input--air m-input--pill" placeholder="Codigo" name="idPuc" id="codigoPuc">
															</div>
														</div>

														<div class="col-lg-3">
															<b>Nombre *</b>
																<br>
																<br>
															<div class="m-input-icon" id="input2">
																<input autofocus="on" value="<?php echo $datosTC['nombre'];?>" type="text" class="form-control m-input m-input--air m-input--pill" placeholder="Nombre" name="nombrePuc" id="nombrePuc">
															</div>
														</div>

														<div class="col-lg-3">
															<b>Detalle</b>
																<br>
																<br>
															<div class="m-input-icon" id="input3">
																<input type="text" value="<?php echo $datosTC['detalle'];?>" class="form-control m-input m-input--air m-input--pill" placeholder="Detalle" name="detalle" id="detalle">
															</div>
														</div>

														<div class="col-lg-3">
															<b>Base</b>
																<br>
																<br>
															<div class="m-input-icon" id="input3">
																<input type="text" value="<?php echo $datosTC['base'];?>" class="form-control m-input m-input--air m-input--pill" placeholder="Base" name="base" id="base">
															</div>
														</div>

													</div>

													<div class="col-lg-12">
														<b>Otros</b>
															<br>
															<br>
															<?php if($datosTC['tercero']==""){ ?>
														<div class="m-input-icon" id="input3">
														<input type="checkbox" id="coding" name="tercero" value="1">
														<label for="coding">Tercero</label>
														<?php }else{ ?>
														<div class="m-input-icon" id="input3">
														<input checked type="checkbox" id="coding" name="tercero" value="1">
														<label for="coding">Tercero</label>
														<?php } ?>

														<?php if($datosTC['exogena']==""){ ?>
														<input  type="checkbox" id="music" name="exogena" value="1">
    													<label for="music">Exogena</label>
														</div>
														<?php }else{ ?>
														<input checked type="checkbox" id="music" name="exogena" value="1">
    													<label for="music">Exogena</label>
														</div>
														<?php } ?>

														
													</div>

													<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
														<div class="m-form__actions m-form__actions--solid">
															<div class="row">
																<div class="col-lg-12">

																	<center><button type="button" class="btn btn-block m-btn--square  btn-warning m-btn m-btn--custom m-btn--bolder m-btn--uppercase" id="botonCrear">
																		<h4 class="text-white">Editar codigo</h4>
																	</button></center><br>
																</div>

															</div>
														</div>
													</div>

													<div id="respuesta" class="hiddenDIV">
														<div class="m-alert m-alert--icon m-alert--air alert alert-warning alert-dismissible fade show" role="alert" id="alertJS">
															<div class="m-alert__icon">
																<i class="la la-warning"></i>
															</div>
															<div class="m-alert__text">
																<strong>
																	Lo sentimos,
																</strong>
																<span id="answerJS"></span>
															</div>
															<div class="m-alert__close">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
															</div>
														</div>
													</div>

                                                </div>
                                                
                                            </form>
                                   
                            </div>

										</div>
									</div>
							</div>
						</div>
					</div>
				</div>
            </div>
            
            

										<script>
											$('#formulario').keyup(function(e) {
											    if(e.keyCode == 13) {
											    	var answer = $('#answerJS');
													var respuesta = $('#respuesta');
													var alertJS = $('#alertJS');
													var datos = $("#formulario").serialize();
													$.ajax({
														type: "POST",
														url: "../../irocket/controllers/ajax/ajax_validationInventory.php",
														data: datos,
														success:function (data) {
															if(data.indexOf('1') != -1){
																respuesta.removeClass('hiddenDIV');
																answer.html("debe agregar un nombre y una descripcion para su inventario.");
															}else{
																$("#formulario").submit();
															}
														}
													});	
											 	}
											});
											$("#botonCrear").click(function () {

												var answer = $('#answerJS');
												var respuesta = $('#respuesta');
												var alertJS = $('#alertJS');
												var datos = $("#formulario").serialize();
												$.ajax({
													type: "POST",
													url: "../../irocket/controllers/ajax/ajax_validationInventory.php",
													data: datos,
													success:function (data) {
														if(data.indexOf('1') != -1){
															respuesta.removeClass('hiddenDIV');
															answer.html("debe agregar un nombre y una descripcion para su inventario.");
														}else{
															$("#formulario").submit();
														}
													}
												});			
											})
										</script>

										<script>

											$("#botonDelete").click(function () {
												var lengthInputs = $("#input1 input").length;

												if (lengthInputs == 2) {
													$("#botonDelete").toggleClass("hiddenDIV");
												}else{
													//alert(lengthInputs);
													}
													var input1 = $("#input1 input:last-child");
													var input2 = $("#input2 input:last-child");
													
													input1.remove();
													input2.remove();

													
												})

											$("#botonPlus").click(function () {

												var lengthInputs = $("#input1 input").length;


												if (lengthInputs >= 1 && lengthInputs <=30) {
													$("#botonDelete").removeClass("hiddenDIV");

													var dom = document.createElement("input");
													var br = document.createElement("br");
													dom.setAttribute("class",'form-control m-input m-input--air m-input--pill');
													dom.setAttribute("placeholder",'Nombre inventario');
													dom.setAttribute("name",'nameInventary[]');
													dom.setAttribute("id",'nameInventary');
													insert = $("#input1").append(dom);	
													if (insert) {
														var dom = document.createElement("input");
														dom.setAttribute("class",'form-control m-input m-input--air m-input--pill');
														dom.setAttribute("placeholder",'Descripcion inventario');
														dom.setAttribute("name",'descriptionInventary[]');
														dom.setAttribute("id",'descriptionInventary');
														insert = $("#input2").append(dom);	
														if (insert) {

														}
													}
												}else{
													var answer = $('#answerJS');
													var respuesta = $('#respuesta');
													var alertJS = $('#alertJS');
													respuesta.removeClass('hiddenDIV');
													alertJS.addClass('fade show');
													answer.html("no puede agregar mas de 4 inventarios si quiere ampliar su paquete de inventarios puede <b><a  target='_blank' href='" + URL_SITIO + "'>nuestro sitio web.</a></b>");

												}



											})




										</script>

										<script>
											
											$(document).ready(function () {
											//$("#alertabien").slideUp(5000).delay(5000);

												$('#alertabien').delay(8000).slideToggle(1000, function () {
													$('#alertabien').removeClass("show");
												});
												return false;
											});

										</script>
<?php if(isset($_GET['error']) OR isset($_GET['success'])){
?>												
<script>
$(document.body).keyup(function(e) {
           // console.log(e.which)
	if (e.which == 27) {
		window.history.go(-2);
	}
})
</script>
<?php }else{ ?>		
<script>
$(document.body).keyup(function(e) {
           // console.log(e.which)
	if (e.which == 27) {
		window.history.go(-1);
	}
})
</script>										
<?php } ?>													
                         
<script>


    var statSend = false;
function checkSubmit() {
    if (!statSend) {
        statSend = true;
        return true;
    } else {
        
        return false;
    }
}
</script>



<script src="<?php echo (URL); ?>views/plugins/js/jquery-1.2.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/datatables.min.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/datatablebuttons.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/buttons.flash.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/jszip.min.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/pdfmake.min.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/vfs.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/buttonshtml5.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/button.print.js"></script>


