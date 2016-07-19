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
//var_dump($_REQUEST);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$objReporte = new Reporte();
$arrReporte = $objReporte->getCiItemsDetail($fini, $ffin, $cellar);
//var_dump($arrReporte);
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
$body = "";
$total = 0;
$count = count($arrReporte);
for ($x = 0; $x < $count; $x++) {    
        $body .= "<tr>";
        $body .= "<td style='text-align:right'>{$arrReporte[$x]['com_Contenedor']}</td>";
        $body .= "<td style='text-align:right'>{$arrReporte[$x]['ciudad']}</td>";
        $body .= "<td style='text-align:right'>".round($arrReporte[$x]['det_CantEquivale'])."</td>";        
        $body .= "<td style='text-align:right'>{$arrReporte[$x]['com_FecVencim']}</td>";
        $body .= "</tr>";            
}
?>

<html>
    <body>        
    <center>
        <table id="example" border="0">
            <thead>
            <th>Orden</th>
            <th>Origen</th>                       
            <th>Qty</th>                       
            <th>ETA</th>            
            </thead>
            <tbody>               
<?php echo $body; ?>
            </tbody>
        </table>
    </center>
</body>
</html> 