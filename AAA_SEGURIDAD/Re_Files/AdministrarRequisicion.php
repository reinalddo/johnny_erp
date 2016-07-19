<?php
error_reporting(E_ALL);
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("CMysqlConnection.php");
include_once("Folio.php");
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
$arrCellar = $objFolio->getFolios();
$table = "";
if (count($arrCellar) > 0) {
    foreach ($arrCellar as $data) {
        $table .= "<tr><td>{$data['fol_Descripcion']}</td><td>{$data['fol_FechaInicio']}</td><td>{$data['fol_FechaFin']}</td><td>{$data['fol_FechaCreacion']}</td><td><img src='../Images/edit.png' onClick='window.open(\"AdministrarDetalleRequisicion.php?id={$data['fol_Id']}\")' /></td></tr>";
    }
}
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
           
            <div class="row">
                <div class ="col-lg-12">
                    
                        <table id="example">
                            <thead>
                                </tr>
                                <tr>
                                    <th>Descripci&oacute;n</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Fecha Creaci&oacute;n</th>
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
