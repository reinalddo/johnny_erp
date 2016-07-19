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
$objCompany = $objFolio->getCompanyRecord();
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
                $rows = count($cod);
                for ($x = 0; $x < $rows; $x++) {
                    if ($val6[$x] != "0") {
                        $body .= "<tr><td>{$cod[$x]}</td><td>{$cod2[$x]}</td><td>{$desc[$x]}</td>";
                        $body .= "<td style='text-align:right'>{$val5[$x]}</td>";
                        $body .= "<td style='text-align:right'>{$val6[$x]}</td>";                        
                        $tmp1 = $val7[$x];
                        if($tmp1=="0"){
                            $tmp1 = "0.00";
                        }else{
                            $tmp1 = number_format($tmp1, 2);
                        }
                        $body .= "<td style='text-align:right'>$".$tmp1."</td>";
                        //$oper = $val6[$x]*$val7[$x];                        
                        $tmp2 = $val8[$x];
                        if($tmp2=="0"){
                            $tmp2 = "0.00";
                        }else{
                            $tmp2 = number_format($tmp2, 2);
                        }
                        $body .= "<td style='text-align:right'>$".$tmp2."</td>";
                        $body .= "</tr>";
                        $total = $total + $val8[$x];
                    }
                }
                $sumarized = "<thead><th colspan='4'>&nbsp;</th><th><b>Total</b></th><th style='text-align:right'>$". number_format($total, 2)."</th></tr></thead>";
                
?>

<html>
    <head>
        <style>
        .anra {
    border: 0;
    outline: 0;
    background: transparent;
    border-bottom: 2px solid black;
    width: 200px;
}
</style>
    </head>
    <body>        
    <center>
        <table id="example" border="0">
            <thead><th colspan="7"><?php echo $objCompany['EGNOM'] ?></th></thead>
            <thead><th colspan="7"><?php echo $objCompany['EGRUC'] ?></th></thead>
            <thead><th colspan="7"><?php echo $objCompany['EGDIR'] ?></th></thead>                     
            <thead><td colspan="7" align="center">De <?php echo $desde; ?> a <?php echo $hasta; ?></td></thead>
            <thead><td colspan="1" align="center">No. de Orden</td><td colspan="2" align="center"><input type="text" size="30"></td><td>&nbsp;</td>
        <td colspan="1" align="center">Proveedor</td><td colspan="2" align="center"><input type="text" size="30"></td></thead>
        <?php echo $sumarized; ?>
            <thead>
            <th>Cod SMART</th>
            <th>Cod Alterno</th>
            <th>Descripci&oacute;n</th>                       
            <th>Completed Used</th>                       
            <th>Suggested</th>            
            <th>Costo U</th>            
            <th>Costo Total</th>            
            </thead>
            <tbody>               
<?php echo $body; ?>
            </tbody>
        </table>

        <table>
            <tr><td><input class="anra" type="text" size="30"></td><td><input class="anra" type="text" size="30"></td><td><input class="anra" type="text" size="30"></td></tr>
            <tr><td align="center">Emitido por</td><td align="center">Revisado por</td><td align="center">Autorizado por</td></tr>
        </table>
        
        <p style="line-height:0.5em;"><strong>Usuario: </strong><?php echo $_SESSION['g_user']; ?></p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong><?php echo date('Y-m-d H:m:s'); ?></p>
    </center>
</body>
<script>
    window.print();
</script>
</html> 