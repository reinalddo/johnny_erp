    <?php
error_reporting(E_ALL);
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("CMysqlConnection.php");
include_once("Folio.class.php");
extract($_REQUEST);
//var_dump($_REQUEST);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$objFolio = new Folio();
if ($action == "g") {
    $total = count($detid);
    $separator = "";
    for ($x = 0; $x < $total; $x++) {
        $tmp = "del_" . $detid[$x];

        if ($$tmp == "d") {
            $objFolio->deleteDetalleBodegaFolio($detid[$x]);
            $objFolio->deleteDetalleFolio($detid[$x]);
        } else {
            //$objFolio->updateSimpleDetalleFolio($detid[$x], $val6[$x], $val9[$x]);
            $query .= $separator."UPDATE reqdetallefolio "
                . " SET dfo_Suggested = '{$val6[$x]}', dfo_TotalCost = '{$val9[$x]}'"
                . " WHERE dfo_Id = '{$detid[$x]}'";
            $separator = ";";
        }
    }
    //echo $query;    exit();
    $objFolio->rawMultiQuery($query);
}


/**
 * Description of Folio
 *
 * @author antonio
 */
$total = 0;
$cabecera = "";
$esPrimeraVez = true;
$dataFolio = $objFolio->getRecordDataById($id);
$arrCellar = $objFolio->getRecordsDetalle($id);
$arrCellarDesc = $objFolio->getRecordsDescripcionBodega($id);

$total = 0;
$totalBodegas = 0;
$table = "";
$separador ="";
if (count($arrCellar) > 0) {
    foreach ($arrCellar as $data) {

        $bod = "";
        $arrBodegas = $objFolio->getRecordsBodegaByItem($data['dfo_FolioId'],$data['dfo_CodItem']);
        $cont = 1;
        foreach ($arrBodegas as $bodega) {
            //echo $bodega['dbf_Bodega']." - ".$arrCellarDesc[$bodega['dbf_Bodega']]."</br>";
            if ($esPrimeraVez) {
                foreach ($arrCellarDesc as $descripcion){
                    $cabecera .= "<th>" . $descripcion['dbo_Descripcion'] . "</th>";
                    $totalBodegas++;
                    $cab .= $separador.$descripcion['dbo_Descripcion'];
                    $separador = "|";
                }
                $esPrimeraVez = false;
                
            }
            $tmpEntero = intval($bodega['dbf_Valor']);
            $bod .= "<td><input name='cell{$cont}[]' type='text' size='5' value='{$tmpEntero}'/></td>";
            //echo $bod;
            $cont++;
        }
        if ($esPrimeraVez) {
            $esPrimeraVez = false;
        }

        $prec = round($data['pre_PreUnitario'], 2);
        $table .= "<tr id='tr_" . $data['dfo_Id'] . "'>"
                . "<td><input size='15' name='brandDesc[]' type='hidden' readOnly value='" . $data['par_Descripcion'] .
                "'/>" . $data['par_Descripcion'] ."</td><td><input name='detid[]' type='hidden' value='" . $data['dfo_Id'] . "'/><input id='del_" . $data['dfo_Id'] . "' name='del_" . $data['dfo_Id'] . "' type='hidden' value=''/><input size='5' name='cod[]' type='hidden' readOnly value='" . $data['act_CodAuxiliar'] . "'/>{$data['act_CodAuxiliar']}</td>"
                . "<td><input size='13' name='cod2[]' type='hidden' readOnly value='" . $data['act_CodAnterior'] . "'/>{$data['act_CodAnterior']}</td>"
                . "<td><input size='50' name='desc[]' type='hidden' readOnly value='" . $data['act_Descripcion'] . "'/>{$data['act_Descripcion']}</td>"
                . $bod . "<td><input size='8' name='val1[]' type='text' readOnly value='" . $data['dfo_Stock'] . "'/></td>"
                . "<td><input type='hidden'name='val2[]' type='text' readOnly value='" . intVal($data['dfo_Transito']) . "'/><a onClick='showDetail({$data['act_CodAuxiliar']})' >".intVal($data['dfo_Transito'])."</a></td>"
                . "<td><input size='8' name='val3[]' type='text' readOnly value='" . $data['dfo_Subtotal'] . "'/></td>"
                . "<td><input size='8' name='val4[]' type='text' readOnly value='" . $data['dfo_Avarage'] . "'/></td>"
                . "<td><input size='8' name='val5[]' type='text' readOnly value='" . $data['dfo_CompleteUsed'] . "'/></td>"
                . "<td><input size='8' name='val6[]' style='background-color : #009688;'  type='text' value='" . $data['dfo_Suggested'] . "' onChange='calcularTotal(\"{$data['act_CodAuxiliar']}\",this)'/></td>"
                . "<td><input size='8' name='val7[]' type='text' readOnly value='" . $data['dfo_UnitCost'] . "'/></td>"
                . "<td><input size='8' name='val8[]' id='pre" . $data['act_CodAuxiliar'] . "' type='hidden' readOnly value='{$data['dfo_UnitCost']}'/><input size='8' id='tot" . $data['act_CodAuxiliar'] . "' name='val9[]' type='text' readOnly value='" . $data['dfo_TotalCost'] . "'/></td>"
                . "<td><img id='img_" . $data['dfo_Id'] . "' src='../Images/delete.png' onclick='changeImg(" . $data['dfo_Id'] . ")'/></td>"
                . "</tr>";
        $total = $total + $data['dfo_TotalCost'];
    }
}

//echo $table; exit;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Requisici&oacute;n</title>
        <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/jquery-ui.min.css"/>
        <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/jquery.dataTables.css"/>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery-ui.min.js"></script>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.jquery.multiple.select.js"></script>
        <script>
            var total = "<?php echo "$total"; ?>";
            var tabla;
            function calcularTotal(id, element) {
                if ($.isNumeric($("#tot" + id).val())) {
                    total = parseFloat(total) - parseFloat($("#tot" + id).val());
                }
                var tmptotal = $("#pre" + id).val() * element.value;
                $("#tot" + id).val(tmptotal);
                total = parseFloat(total) + parseFloat($("#tot" + id).val());
                $("#total").val(total.toFixed(2));
            }
            function changeImg(id) {

                $("#del_" + id).val("d");
                $("#tr_" + id).hide();
            }
            
            $(document).ready(function() {
                /*tabla = $("#example").DataTable({
                    aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
                    iDisplayLength: -1
                });*/
                $("#btnexcel").click(function() {
                    $("#desde").val($("#txtFechaDesde").val());
                    $("#hasta").val($("#txtFechaHasta").val());
                    $("#excel").attr("action", "ReporteExcel.php");
                    $("#excel").submit();
                });
                $("#btnimprimir").click(function() {
                //alert("test");
                $("#accion").val("i");
                $("#excel").attr('target', '_blank');
                $("#excel").attr("action", "ReporteDetalleRequisicion.php");
                $("#excel").submit();
            });
            $("#btnguardar").click(function() {
                $("#accion").val("g");
                $("#excel").attr("action", "AdministrarDetalleRequisicion.php");
                $("#excel").submit();

            });
            });
function showDetail(item){
                window.open("ReporteTransito.php?cellar=" + item + "&fini=" + $("#txtFechaDesde").val() 
                        + "&ffin=" + $("#txtFechaHasta").val(),"_blank", "width=600, height=300");
            }
        </script>
    </head>
    <body>
        <div class="container">
            </br>
            </br>
            <div class="panel panel-default">
                <div class="panel-heading">Cabecera</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">        

                            <label class="control-label col-sm-1" for="txtFechaDesde">Fecha desde</label>
                            <div class="col-sm-3"><input class="form-control" type="text" id="txtFechaDesde" name="txtFechaDesde" value="<?php echo $dataFolio[0]['fol_FechaInicio'] ?>"></div>                
                            <label class="control-label col-sm-1" for="txtFechaHasta">Fecha hasta</label>
                            <div class="col-sm-3"><input class="form-control" type="text" id="txtFechaHasta" name="txtFechaHasta" value="<?php echo $dataFolio[0]['fol_FechaFin'] ?>"></div>                
                            <label class="control-label col-sm-1" for="txtTiempo">Intervalo</label>
                            <div class="col-sm-3"><input class="form-control" type="text" id="txtTiempo" name="txtTiempo" value="<?php echo $dataFolio[0]['fol_Intervalo'] ?>"></div>                
                        </div>
                        <div class="form-group">                                  
                            <div class="col-sm-12 text-right"><input class="btn btn-primary" type="button" id="btnimprimir" name="imprimir" value="Imprimir">                                
                                &nbsp;&nbsp;<input class="btn btn-primary" type="button" id="btnexcel" name="btnexcel" value="Excel">
                                &nbsp;&nbsp;<input class="btn btn-primary" type="button" id="btnguardar" name="btnguardar" value="Guardar"></div>                
                        </div>
                    </form>
                </div>
            </div>
            <form id = "excel" action="AdministrarDetalleRequisicion.php" method="post">                
                <input type="hidden" name="action" value="g">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="hidden" name="cellarsCount" value="<?php echo $totalBodegas ?>">
                <input type="hidden" name="cab" value="<?php echo $cab ?>">
                <input type="hidden" id="desde" name="desde" value="<?php echo $dataFolio[0]['fol_FechaInicio'] ?>">
                <input type="hidden" id="hasta" name="hasta" value="<?php echo $dataFolio[0]['fol_FechaFin'] ?>">
                <input type="hidden" id="intervalo" name="intervalo" value="<?php echo $dataFolio[0]['fol_Intervalo'] ?>">
                <input type="hidden" id="descripcion" name="descripcion" value="<?php echo $dataFolio[0]['fol_Descripcion'] ?>">
                <div class="row">
                    <div class ="col-lg-12">

                        <table id="example">
                            <thead>
                                <tr>
                                    <?php $cls = 13 + $totalBodegas; ?>
                                    <th colspan="<?php echo $cls; ?>" style="text-align:right">Total: <input type="text" id="total" name="total" value="<?php echo $total; ?>"/></th>
                                </tr>
                                <tr>
                                    <th>Marca</th>
                                    <th>Cod. Item</th>
                                    <th>Cod. Alterno</th>
                                    <th>Descripci&oacute;n</th>
                                    <?php echo $cabecera; ?>
                                    <th>Stock</th>
                                    <th>Transit</th>
                                    <th>Subtotal</th>
                                    <th>Average</th>
                                    <th>Complete Used</th>
                                    <th>Suggested</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $table; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </form>
        </div>
    </body>

</html> 
