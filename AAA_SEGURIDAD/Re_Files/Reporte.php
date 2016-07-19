<?php
error_reporting(E_ALL);
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("CMysqlConnection.php");
include_once("Reporte.class.php");
extract($_REQUEST);
//echo DBSRVR." ". DBUSER." ". DBPASS." ". DBNAME;exit;
//echo(date("Y-m-d h:i:s")); 
//var_dump($_REQUEST);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reporte
 *
 * @author antonio
 */

//echo $txtFechaDesde." ".$txtFechaHasta; 

$objReporte = new Reporte();

/*
 * Se inicializan variables de los filtros, fecha desde y hasta si no existen a hoy, intervalo 1
 **/
if ($txtFechaDesde == "") {
    $arrFecha = $objReporte->getInitialDate();
    if (count($arrFecha) > 0) {
        $txtFechaDesde = $arrFecha[0]['par_Valor1'];
    } else {
        $txtFechaDesde = date("Y-m-d");
    }
}
if ($txtFechaHasta == "") {
    $txtFechaHasta = date("Y-m-d");
}


$arrMarcas = $objReporte->getBrands();

$multiplier = 1;
$label = "";
$tmpTiempo = "";
$arrMultiplier = $objReporte->getMultiplier();
if (count($arrMultiplier) > 0) {
    $tmpTiempo = $arrMultiplier[0]['par_Valor3'];
    $multiplier = $arrMultiplier[0]['par_Valor1'];
    $label = $arrMultiplier[0]['par_valor2'];
}
if ($txtTiempo == "") {
    $txtTiempo = $tmpTiempo;
}
$arrTransito = $objReporte->getTransito();
if (count($arrTransito) > 0) {
    $transito = $arrTransito[0]['par_Secuencia'];
}

$days = 1;
$arrDiffDate = $objReporte->getDiffDate($txtFechaDesde, $txtFechaHasta);
if (count($arrDiffDate) > 0) {
    $days = $arrDiffDate[0]['DiffDate'];
}

$brands = "";
if (isset($spnMarcas)) {
    $brands = implode(",", $spnMarcas);
}
//var_dump($brands); exit;

$arrData = $objReporte->getSpecialItems($brands);
//echo "Total de registros: ".count($arrData);exit();
$arrCellar = $objReporte->getCellars();
$arrCustomCellar = array();
$cab = "";
if (count($arrCellar) > 0) {
    foreach ($arrCellar as $dataCellar) {
        $cab .= $dataCellar['per_Apellidos'] . "(" . $dataCellar['par_Valor1'] . ")|";
        $arrCustomCellar[$dataCellar['par_Valor1']]['base'] = $dataCellar['par_Valor3'];
        $arrCustomCellar[$dataCellar['par_Valor1']]['data'] = $objReporte->getItemsByCellar($txtFechaDesde, $txtFechaHasta, $dataCellar['par_Valor3'], $dataCellar['par_Valor1']);
        $arrCustomCellar[$dataCellar['par_Valor1']]['data_comp'] = $objReporte->getMovementsByCellar($txtFechaDesde, $txtFechaHasta, $dataCellar['par_Valor3'], $dataCellar['par_Valor1']);
    }
}


$arrCi = $objReporte->getCiItems($txtFechaDesde, $txtFechaHasta, "", $transito);
$table = "";
$arrSumatorias = array();
$arrComprobantes = array();
if (count($arrData) > 0) {
    foreach ($arrData as $data) {

        foreach ($arrCellar as $dataCellar) {
            if (!isset($arrSumatorias[$data['act_CodAuxiliar']])) {
                $arrSumatorias[$data['act_CodAuxiliar']] = 0;
            }
            if (!isset($arrComprobantes[$data['act_CodAuxiliar']])) {
                $arrComprobantes[$data['act_CodAuxiliar']] = 0;
            }
            $arrSumatorias[$data['act_CodAuxiliar']] = $arrSumatorias[$data['act_CodAuxiliar']] + $arrCustomCellar[$dataCellar['par_Valor1']]['data'][$data['act_CodAuxiliar']];
            $arrComprobantes[$data['act_CodAuxiliar']] = $arrComprobantes[$data['act_CodAuxiliar']] + $arrCustomCellar[$dataCellar['par_Valor1']]['data_comp'][$data['act_CodAuxiliar']];
        }
    }

    $total = 0;
    $cellarsCount = count($arrCellar);
    foreach ($arrData as $data) {
        $table .= "<tr><td><input name='brand[]' type='hidden' readOnly value='" . $data['marca'] .
                "'/><input size='15' name='brandDesc[]' type='hidden' readOnly value='" . $data['marca_Descripcion'] .
                "'/>{$data['marca_Descripcion']}</td><td><input size='5' name='cod[]' type='hidden' readOnly value='" . $data['act_CodAuxiliar'] .
                "'/>" . $data['act_CodAuxiliar'] .
                "</td><td><input size='13' name='cod2[]' type='hidden' readOnly value='" . $data['act_CodAnterior'] . "'/>" . $data['act_CodAnterior'] . "</td><td><input size='50' name='desc[]' type='hidden' readOnly value='" .
                $data['act_Descripcion'] . "'/>".$data['act_Descripcion'] . "</td>";
        $tmp = 0;
        foreach ($arrCellar as $dataCellar) {
            $tmp ++;
            $tmpEntero = intval($arrCustomCellar[$dataCellar['par_Valor1']]['data'][$data['act_CodAuxiliar']]);
            $table .= "<td><input size='8' name='cell{$tmp}[]' type='text' readOnly value='{$tmpEntero}'/></td>";
        }
        $table.= "<td><input size='8' name='val1[]' type='text' readOnly value='{$arrSumatorias[$data['act_CodAuxiliar']]}'/></td>";
        $transitVal = round($arrCi[$data['act_CodAuxiliar']], 2);
        $tmpEntero = intval($arrCi[$data['act_CodAuxiliar']]); 
        $table.="<td><input type='hidden'name='val2[]' type='text' readOnly value='" . $tmpEntero . "'/><a onClick='showDetail({$data['act_CodAuxiliar']})' >{$tmpEntero}</a></td>";        
        $operation = $arrSumatorias[$data['act_CodAuxiliar']] + $arrCi[$data['act_CodAuxiliar']];
        $tmp1 = $operation;
        $table.= "<td><input size='8' name='val3[]' type='text' readOnly value='$operation'/></td>";
        $operation = ($arrComprobantes[$data['act_CodAuxiliar']] / $days) * $multiplier;
        $operation = round($operation);
        $table.= "<td><input size='8' name='val4[]' type='text' readOnly value='$operation'/></td>";
        $operation2 = $operation * $txtTiempo;
        $operation2 = round($operation2);
        $table.= "<td><input size='8' name='val5[]' type='text' readOnly value='$operation2'/></td>";
        $tmp3 = $operation2;
        $tmp2 = $operation2 - $tmp1;
        if($tmp2<0){
            $tmp2 = 0;
        }
        $table.= "<td><input style='background-color : #009688;' size='8' name='val6[]' type='text' value='$tmp2' onChange='calcularTotal(\"{$data['act_CodAuxiliar']}\",this)'></td>";
        $prec = round($data['pre_PreUnitario'], 2);
        $table.= "<td><input size='8' name='val7[]' id='pre{$data['act_CodAuxiliar']}' type='text' readOnly value='{$prec}'/></td>";
        $operation3 = $tmp2 * $prec;
        $table.= "<td><input size='8' name='val8[]' id='tot{$data['act_CodAuxiliar']}' type='text' value='{$operation3}'></td>";
        $table .= "</tr>";
        $total = $total + $operation3;
    }
} //echo(date("Y-m-d h:i:s")); exit;
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
            var tabla;
            var total = "<?php echo "$total"; ?>";
            $(document).ready(function() {
                /*tabla = $("#example").DataTable({
                    aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
                    iDisplayLength: -1,
                    "bProcessing": true,
                    "bDeferRender": true
                });*/
                $("#txtFechaDesde").datepicker({dateFormat: "yy-mm-dd"});
                $("#txtFechaHasta").datepicker({dateFormat: "yy-mm-dd"});

                /*$("#filtrar").click(function() {
                 
                 //filtrar()
                 });*/
                $("#txtTiempo").keypress(function(e) {
                    //if the letter is not digit then display error and don't type anything
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        //display error message
                        $("#errmsg").html("S&oacute;lo n&uacute;meros").show().fadeOut("slow");
                        return false;
                    }
                });
                $("#btnexcel").click(function() {
                    $("#desde").val($("#txtFechaDesde").val());
                    $("#hasta").val($("#txtFechaHasta").val());
                    $("#excel").attr("action", "ReporteExcel.php");
                    $("#excel").submit();
                });
                $("#btnimprimir").click(function() {
                    $("#desde").val($("#txtFechaDesde").val());
                    $("#hasta").val($("#txtFechaHasta").val());
                    $("#excel").attr("action", "ReporteRequisicion.php");
                    $("#excel").submit();
                });
                $("#btnguardar").click(function() {
                    var desc = prompt("Ingrese una descripci\xf3n", "");
                    switch (desc) {
                        case "":
                            $("#descripcion").val("");
                            break;
                        default:
                            $.post("Consultas.php", {txtDescripcion: desc})
                                    .done(function(data) {
                                        if (data == "0") {
                                            $("#accion").val("g");
                                            $("#desde").val($("#txtFechaDesde").val());
                                            $("#hasta").val($("#txtFechaHasta").val());
                                            $("#intervalo").val($("#txtTiempo").val());
                                            $("#descripcion").val(desc);
                                            $("#excel").attr("action", "GuardarRequisicion.php");
                                            $("#excel").submit();
                                        } else {
                                            alert("Descripci\xf3n ingresada previamente");
                                        }
                                    });

                    }
                });
            });



            function calcularTotal(id, element) {
                if ($.isNumeric($("#tot" + id).val())) {
                    total = parseFloat(total) - parseFloat($("#tot" + id).val());
                }
                var tmptotal = $("#pre" + id).val() * element.value;
                $("#tot" + id).val(tmptotal);
                total = parseFloat(total) + parseFloat($("#tot" + id).val());
                $("#total").val(total.toFixed(2));
            }
            
            function showDetail(item){
                window.open("ReporteTransito.php?cellar=" + item + "&fini=" + $("#txtFechaDesde").val() 
                        + "&ffin=" + $("#txtFechaHasta").val(),"_blank", "width=600, height=300");
            }
            
        </script>
    </head>

    <body>
        <div class="container">
            </br>
            <div class="panel panel-default">
                <div class="panel-heading">Filtros de B&uacute;squeda</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">        

                            <label class="control-label col-sm-1" for="txtFechaDesde">Fecha desde</label>
                            <div class="col-sm-3"><input class="form-control" type="text" id="txtFechaDesde" name="txtFechaDesde" value="<?php echo $txtFechaDesde ?>"></div>                
                            <label class="control-label col-sm-1" for="txtFechaHasta">Fecha hasta</label>
                            <div class="col-sm-3"><input class="form-control" type="text" id="txtFechaHasta" name="txtFechaHasta" value="<?php echo $txtFechaHasta ?>"></div>                
                            <label class="control-label col-sm-1" for="txtTiempo">Intervalo (<?php echo $label ?>)</label>
                            <div class="col-sm-3"><input class="form-control" type="text" id="txtTiempo" name="txtTiempo" value="<?php echo $txtTiempo ?>"></div>                
                        </div>
                        <div class="form-group">                                  
                            <label class="control-label col-sm-1" for="spnMarcas">Marcas</label>
                            <select id="spnMarcas" name="spnMarcas[]" multiple>                                
<?php
foreach ($arrMarcas as $marca) {
    echo "<option value='{$marca['par_Secuencia']}'>{$marca['par_Descripcion']}</option>";
}
?>
                            </select>
                        </div>
                        <div class="form-group">                                  
                            <div class="col-sm-12 text-right"><input class="btn btn-primary" type="submit" id="filtrar" name="filtrar" value="Buscar">&nbsp;&nbsp;<input class="btn btn-primary" type="button" id="btnimprimir" name="imprimir" value="Imprimir">
                                &nbsp;&nbsp;<input class="btn btn-primary" type="button" id="btnexcel" name="btnexcel" value="Excel">
                                &nbsp;&nbsp;<input class="btn btn-primary" type="button" id="btnguardar" name="btnguardar" value="Guardar"></div>                
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class ="col-lg-12">
                    <form id="excel" action="ReporteExcel.php" method="post" target="_self" >
                        <input type="hidden" id="accion" name="accion" value ="" >
                        <input type="hidden" name="cellarsCount" value="<?php echo $cellarsCount ?>">
                        <input type="hidden" name="cab" value="<?php echo $cab ?>">
                        <input type="hidden" id="desde" name="desde" >
                        <input type="hidden" id="hasta" name="hasta" >
                        <input type="hidden" id="intervalo" name="intervalo" >
                        <input type="hidden" id="descripcion" name="descripcion" >
                        <!--<b>Total</b> <input type="text" id="total" name="total" value="<?php //echo $total;  ?>"></br></br>-->
                        <table id="example">
                            <thead>
                                <tr>
<?php $cls = 11 + count($arrCellar); ?>
                                    <th colspan="<?php echo $cls; ?>" style="text-align:right">Total: <input type="text" id="total" name="total" value="<?php echo $total; ?>"/></th>
                                </tr>
                                <tr>
                                    <th>Marca</th>
                                    <th>Cod Item</th>
                                    <th>Cod Alterno</th>
                                    <th>Descripci&oacute;n</th>
<?php
if (count($arrCellar) > 0) {
    foreach ($arrCellar as $dataCellar) {
        echo "<th>{$dataCellar['per_Apellidos']} ({$dataCellar['par_Valor1']})</th>";
    }
}
?>
                                    <th>Stock</th>
                                    <th>Transit</th>
                                    <th>Subtotal</th>
                                    <th>Average</th>
                                    <th>Complete Used</th>
                                    <th>Suggested</th>
                                    <th>Costo U</th>       
                                    <th>Costo Total</th>       
                                </tr>
                            </thead>
                            <tbody>
                                    <?php echo $table; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </body>

</html> 