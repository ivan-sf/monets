



<div id="page-wrapper" style="min-height: 953px;">

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
    <button class="btn m-btn--pill btn-badge" type="submit"><a href="<?php echo URL; ?>contabilidad/crear?documento" class="">Crear documento</a></button>
    <button class="btn m-btn--pill btn-badge"><a href="<?php echo URL; ?>contabilidad/crear?tipo=codigo">Crear codigo</a></button>
    <button class="btn m-btn--pill btn-badge"><a href="<?php echo URL; ?>contabilidad/puc/favoritos">Favoritos</a></button>
    <br>
    <br>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
             


                
                <div class="m-grid__item m-grid__item--fluid m-wrapper">

<!-- END: Subheader -->

<div class="m-content">
<div class="col-lg-12">
<div class="m-portlet">

    <div class="m-portlet__head ">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title col-lg-12">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
           
                    <?php if (isset($_GET['success_update'])) {
                                echo "
                                <div class='m-alert m-alert--icon m-alert--air m-alert--square alert alert-success alert-dismissible fade show' role='alert' id='alertabien'>
                                <div class='m-alert__icon'>
                                <i class='flaticon-rocket'></i>
                                </div>
                                <div class='m-alert__text'>
                                <center>
                                    <strong>
                                Perfecto !
                                </strong>
                                Se editaron los datos correctamente puedes ver el resultado en la tabla.

                                </center>
                                </div>
                                <div class='m-alert__close'>
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'></button>
                                </div>
                                </div>";
                            }elseif(isset($_GET['success_delete'])){
                                echo "
                                <div class='m-alert m-alert--icon m-alert--air m-alert--square alert alert-success alert-dismissible fade show' role='alert' id='alertabien'>
                                <div class='m-alert__icon'>
                                <i class='flaticon-rocket'></i>
                                </div>
                                <div class='m-alert__text'>
                                <center>
                     
                                <strong>
                                Perfecto !
                                </strong>
                                Se eliminaron los datos correctamente en adelante no tendras disponible el empleado en tus datos.
                                </center>
                                </div>
                                <div class='m-alert__close'>
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'></button>
                                </div>
                                </div>";
                            }elseif (isset($_GET['error'])) {
                                if ($_GET['error'] == 'delete') {
                                    echo "
                                    <div class='m-alert m-alert--icon m-alert--air m-alert--square alert alert-danger alert-dismissible fade show' role='alert' id='alertabien'>
                                    <div class='m-alert__icon'>
                                    <i class='flaticon-rocket'></i>
                                    </div>
                                    <div class='m-alert__text'>
                                    <center>
                                       
                              
                                <strong>
                                    Lo sentimos !
                                </strong>
                                    El empleado al que intentas acceder ha sido eliminado
                                </center>
                                    </div>
                                    <div class='m-alert__close'>
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'></button>
                                    </div>
                                    </div>";
                                }
                            } ?>
                </div>
            </div>
        </div>

        <!--begin::Form-->

                            <div class="table-responsive">
                                <br>
                                <table id="dt_contabilidad" class="table table-striped dataTable no-footer" role="grid" aria-describedby="myTable_info">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Tercero</th>
                                            <th>Debito</th>
                                            <th>Credito</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                                
                            </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo (URL); ?>views/plugins/js/jquery-1.2.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/datatables.min.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/datatablebuttons.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/buttons.flash.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/jszip.min.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/pdfmake.min.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/vfs.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/buttonshtml5.js"></script>
<script src="<?php echo (URL); ?>views/plugins/js/button.print.js"></script>




<script>        
function listar() {
var table = $("#dt_contabilidad").DataTable({
    "ajax":{
        "method":"POST",
        "url": "../../irocket/views/tables/contabilidad/pruebas.php"
    },
    dom:"Bfrtlip",
    columns:[
        
        {"data":"notaContable"},
        {"data":"codigo"},
        {"data":"nombre"},
        {"data":"terceroNombre"},
        {"data":"debito"},
        {"data":"credito"}
        
    ],
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'

    ],

    language: idioma
});

}
var idioma = {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ning??n dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "BUSCAR:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "??ltimo",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
listar();
</script>


            </div>
        </div>
    </div>





<script>
                                
$(document).ready(function () {
//$("#alertabien").slideUp(5000).delay(5000);

$('#alertabien').delay(8000).slideToggle(1000, function () {
    $('#alertabien').removeClass("show");
});
return false;
});

</script>


