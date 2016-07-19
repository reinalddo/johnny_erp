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

/**
 * Description of Folio
 *
 * @author antonio
 */
$objFolio = new Folio();

if ($accion == "g") {

    $objFolio->insertFolio($descripcion, $desde, $hasta, $intervalo);
    $idFolio = $objFolio->insertId();
    $total = 0;
    $rows = count($cod);


    $arrCab = explode("|", $cab);
    if (count($arrCab) > 0) {
        for ($x = 0; $x < $cellarsCount; $x++) {
            $tmp = $arrCab[$x];
            $arrCab[$x] = str_replace(")", "", substr($arrCab[$x], strrpos($arrCab[$x], "(") + 1, strrpos($arrCab[$x], ")")));
            //ECHO $arrCab[$x]."</br>";
            $objFolio->insertDescripcionBodega($idFolio, $arrCab[$x], $tmp);
        }
    }

    $separator = "";
    $separator2 = "";
    $queryDetalle = "INSERT INTO reqdetallefolio (dfo_FolioId, dfo_CodItem, dfo_Marca, dfo_Stock, dfo_Transito, dfo_Subtotal, dfo_Avarage, dfo_CompleteUsed, dfo_Suggested, dfo_UnitCost, dfo_TotalCost) VALUES ";
    $queryBodega = "INSERT INTO reqdetallebodega (dbf_Bodega, dbf_Valor, dbf_CodItem, dbf_FolioId) VALUES ";
    for ($x = 0; $x < $rows; $x++) {
        //if ($val5[$x] != "" && $val5[$x] != "0") {           
        $queryDetalleBody .= $separator . "('$idFolio', '$cod[$x]', '$dfo_Marca', '$val1[$x]', '$val2[$x]', '$val3[$x]', '$val4[$x]', '$val5[$x]', '$val6[$x]', '$val7[$x]', '$val8[$x]')";
        for ($y = 1; $y <= $cellarsCount; $y++) {
            $name = "cell" . $y;
            $z = $y - 1;
            $queryBodegaBody .= $separator2 . "('{$arrCab[$z]}', '{${$name}[$x]}', '$cod[$x]', '$idFolio')";
            $separator2 = ", ";
        }
        $separator = ", ";
    }
    //echo $queryBodega.$queryBodegaBody; exit;
    $objFolio->rawQuery($queryDetalle.$queryDetalleBody);
    $objFolio->rawQuery($queryBodega.$queryBodegaBody);
    
}
if (!isset($txtDescripcion)) {
    $txtDescripcion = "";
}
$arrRercords = $objFolio->getRecords($txtDescripcion);
$table = "";
if (count($arrRercords) > 0) {
    foreach ($arrRercords as $arrRercord) {
        $table.= "<tr><td>{$arrRercord['fol_Descripcion']}</td><td>{$arrRercord['fol_FechaInicio']}</td><td>{$arrRercord['fol_FechaInicio']}</td><td>{$arrRercord['fol_Intervalo']}</td><td>&nbsp;</td><td><a href='AdministrarDetalleRequisicion.php?id={$arrRercord['fol_Id']}'><img src='../Images/edit.png'/></a></td></tr>";
    }
}
//echo $table; 
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

            $(document).ready(function() {
                tabla = $("#example").DataTable({
                    aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
                    iDisplayLength: -1
                });
            });

        </script>
    </head>
    <body>
        <div class="container">
            </br>
            <div class="container">
                </br>
                <div class="panel panel-default">
                    <div class="panel-heading">Filtros de B&uacute;squeda</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">                                    
                                <label class="control-label col-sm-1" for="txtDescripcion">Descripci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="col-sm-3"><input class="form-control" type="text" id="txtDescripcion" name="txtDescripcion" value="<?php echo $txtDescripcion ?>"></div>                
                            </div>                        
                            <div class="form-group">                                  
                                <div class="col-sm-12 text-right"><input class="btn btn-primary" type="submit" id="filtrar" name="filtrar" value="Buscar">&nbsp;&nbsp;</div>                
                            </div>
                        </form>
                    </div>
                </div>
                </br></br>
                <div class="row">
                    <div class ="col-lg-12">

                        <table id="example">
                            <thead>
                                </tr>
                                <tr>
                                    <th>Descripci&oacute;n</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Intervalo</th>
                                    <th>Opciones</th>       
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
