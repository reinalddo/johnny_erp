<?php
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
$gbTrans = false;
$db = Null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg", 0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->debug = fGetparam("pAdoDbg", false);

$ref = fGetParam("ref", 0);
$ref2 = fGetParam("ref2", 0);
$contenedor = fGetParam("contenedor", 0);
$sSql = "SELECT CONCAT(SUBSTRING(  mer_year, -2), mer_week) AS mer_refOperat,  mer_equipment, mer_workorder, mer_total_cost_supplied, FORMAT(mer_total_labour_cost,2) AS mer_total_labour_cost,FORMAT(mer_total_to_paid,2) AS mer_total_to_paid
FROM merca WHERE mer_invoice IS NULL  AND CONCAT(SUBSTRING(  mer_year, -2), mer_week) = '$ref' AND mer_equipment = '$contenedor'";
//echo $sSql;exit;
$rs = $db->execute($sSql);
if ($rs->EOF) {
    fErrorPage('', 'NO SE ENCONTRARON REGISTROS PARA REALIZAR EL CRUCE', true, false);
} else {
    $content = "";
    while ($r = $rs->fetchRow()) {

        $content.="<tr><td>{$r['mer_refOperat']}</td><td>{$r['mer_equipment']}</td><td>{$r['mer_workorder']}</td><td>{$r['mer_total_cost_supplied']}</td><td>{$r['mer_total_labour_cost']}</td><td>{$r['mer_total_to_paid']}</td></tr>";
    }
}

$sSql = "SELECT LEFT(com_TipoComp,3) AS tipo,  com_Numcomp AS numeroComp, com_RegNumero as numero, CONCAT(LEFT(com_TipoComp,3), ' ', com_Numcomp) AS comprobante, com_refOperat, com_Contenedor, invdetalle.`det_WorkOrder` as det_WorkOrder, FORMAT(invdetalle.det_cosTotal,2) AS costo_interno, '' AS mano_obra
	FROM concomprobantes   
	JOIN  invdetalle ON invdetalle.det_RegNumero = concomprobantes.com_RegNumero AND com_TipoComp = 'EG' AND com_refOperat = '$ref' AND com_Contenedor = '$contenedor'";
//echo $sSql;exit;
$rs = $db->execute($sSql);

$content2 = "";
while ($r = $rs->fetchRow()) {

    $content2.="<tr><td><a href='InTrTr_Cabe.php?s_com_NumComp={$r['numeroComp']}&pMod=IN&com_RegNumero={$r['numero']}&pTipoComp={$r['tipo']}'>{$r['comprobante']}</a></td><td>{$r['com_refOperat']}</td><td>{$r['com_Contenedor']}</td><td>{$r['det_WorkOrder']}</td><td>{$r['costo_interno']}</td><td></td><td>{$r['costo_interno']}</td></tr>";
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Detalle de Cobros</title>
        <link href="../Themes/Cobalt/Style.css" type="text/css" rel="stylesheet" />

        <script>           
        </script>
    </head>
    <body bgcolor="#fffff7" align="center" >
    <center>
        <br/><br/>
        <form action="InTrTr_excel_3.php" method="post" enctype="multipart/form-data">
            <center>Contenido archivo Merca</center>
            <table border="2">
                <tr><td>Semana</td><td>Contenedor</td><td>Orden de Trabajo</td><td>Costo de Materiales</td><td>Mano de Obra</td><td>Costo Total;</td></tr>
                <?php echo $content; ?>
            </table>
            </br></br>
            <center>Contenido Smart</center>
            <table border="2">
                <tr><td>Comprobante</td><td>Semana</td><td>Contenedor</td><td>Orden de Trabajo</td><td>Costo de Materiales</td><td>Mano de Obra</td><td>Costo Total;</td></tr>
                <?php echo $content2; ?>
            </table>
        </form>
    </center>
</body>
</html>

