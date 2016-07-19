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

if (!isset($_POST['semana'])) {
    $semana = "";
}else{
    $semana = $_POST['semana'];
}

if (isset($_POST['action'])) {
    $update_arr = $_POST['checksid'];
    $update_invoice = $_POST['invoicevalues'];
    $update_hidden = $_POST['hiddenvalue'];

    $total = count($update_arr);
    //echo "Aqui: ". $total."<br>";
    for ($x = 0; $x < $total; $x++) {

        if ($update_hidden[$x] == "S") {
            $arr = explode("_", $update_arr[$x]);
            $weak = substr($arr[0], 2, 2);
            $year = substr($arr[0], 0, 2);
            $sSql = "UPDATE merca SET mer_invoice = '{$update_invoice[$x]}' WHERE mer_week= '$weak' AND SUBSTRING(  mer_year, -2) = '$year' AND mer_equipment = '{$arr[1]}'";

            //echo $sSql; //exit;
            $rs = $db->execute($sSql);
        }
    }
    // exit;
}


$sSql = "SELECT * FROM ( SELECT * FROM (SELECT CONCAT(SUBSTRING(  mer_year, -2), mer_week) AS mer_refOperat, CONCAT(SUBSTRING(  YEAR(mer_date), -2), mer_week) AS mer_refOperat2, mer_equipment,  SUM(mer_total_to_paid) AS costo_externo, mer_workorder
FROM merca WHERE mer_invoice IS NULL 
GROUP BY mer_refOperat, mer_workorder) AS C
LEFT JOIN ( SELECT * FROM (
	SELECT com_refOperat, com_Contenedor, SUM(det_CosTotal) AS costo_interno
	FROM concomprobantes   
	JOIN  invdetalle ON invdetalle.det_RegNumero = concomprobantes.com_RegNumero AND com_TipoComp = 'EG' 
	GROUP BY com_refOperat, com_Contenedor) AS B) AS A  ON  C.mer_refOperat =A.com_refOperat  AND A.com_Contenedor = C.mer_equipment 
UNION
SELECT * FROM (
SELECT com_refOperat, com_Contenedor, SUM(det_CosTotal) AS costo_interno
	FROM concomprobantes   
	JOIN  invdetalle ON invdetalle.det_RegNumero = concomprobantes.com_RegNumero AND com_TipoComp = 'EG' 
	GROUP BY com_refOperat, com_Contenedor) AS A   
LEFT JOIN	
( 
SELECT * FROM (SELECT CONCAT(SUBSTRING(  mer_year, -2), mer_week) AS mer_refOperat, CONCAT(SUBSTRING(  YEAR(mer_date), -2), mer_week) AS mer_refOperat2, mer_equipment,  SUM(mer_total_to_paid) AS costo_externo, mer_workorder
FROM merca WHERE mer_invoice IS NULL 
GROUP BY mer_refOperat, mer_workorder) AS B ) AS C  ON  C.mer_refOperat =A.com_refOperat  AND A.com_Contenedor = C.mer_equipment "
        . ") AS S WHERE mer_refOperat =  '$semana' ";
//echo $sSql ; exit;
$rs = $db->execute($sSql);
/*if ($rs->EOF) {
    fErrorPage('', 'NO SE ENCONTRARON REGISTROS PARA REALIZAR EL CRUCE', true, false);
} else {*/
    $content = "";
    while ($r = $rs->fetchRow()) {
        $rentabilidad = $r['costo_externo'] - $r['costo_interno'];
        $content.="<tr><td><input onchange='validate(this,\"{$r['mer_refOperat']}_{$r['mer_equipment']}\")' type='checkbox' name='checkid[]' value='{$r['mer_refOperat']}_{$r['mer_equipment']}'/><input type='hidden' name='checksid[]' value='{$r['mer_refOperat']}_{$r['mer_equipment']}'/></td><td><input type='text' name='invoicevalues[]' /><input type='hidden' id='{$r['mer_refOperat']}_{$r['mer_equipment']}' name='hiddenvalue[]'></td><td>{$r['mer_refOperat']}</td><td><a onclick='openDetail(\"{$r['mer_refOperat']}\",\"{$r['mer_refOperat2']}\",\"{$r['mer_equipment']}\")'>{$r['mer_equipment']}</a></td><td>{$r['costo_interno']}</td><td>{$r['costo_externo']}</td><td>$rentabilidad</td><td>{$r['mer_workorder']}</td></tr>";
    }
//}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Cruce de Cobros</title>
        <link href="../Themes/Cobalt/Style.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/jquery.dataTables.css">
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('#example').DataTable({"language": {
                        "url": "../LibJs/DataTables/media/dataTables.spanish.lang"
                    }});
            });
            function validate(element, id) {
                if (element.checked == true) {
                    document.getElementById(id).value = "S";
                } else {
                    element.value = "";
                }
            }
            function openDetail(ref, ref2, contenedor) {
                window.open("InTrTr_excel_3.php?ref=" + ref + "&ref2=" + ref2 + "&contenedor=" + contenedor, "detalle");
            }
        </script>
    </head>
    <body bgcolor="#fffff7" align="center" >
    <center>
         
        <form action="InTrTr_excel_2.php" method="post" enctype="multipart/form-data">
            Especifique la semana: <input type="text"  name="semana" value=""><input type="submit" value="Buscar">
            <input type="hidden"  name="action" value="process">
            <table id ="example">
                <thead>
                <tr><th>Opci&oacute;n</th><th>Factura</th><th>Semana</th><th>Contenedor</th><th>Costo Local</th><th>Costo Externo</th><th>Rentabilidad</th><th>Orden de trabajo</th></tr>
                </thead>
                <tbody>
                    <?php echo $content; ?>
                    </tbody>
            </table>

            <input type="submit" value="Procesar">
            </br></br></br>
        </form>
    </center>
</body>
</html>

