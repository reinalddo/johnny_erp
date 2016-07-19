<?
/**
*    RESUMEN POR AÑO y MES
**/

if (!isset ($_SESSION)) session_start();
/**
**/
include("GenUti.inc.php");
$FileName=basename($_SERVER["PHP_SELF"], ".php"); //                          Definicion obligatoria para que funciones Seglib
$HtmlFile = "InTrTr_resprecios" .".html";
$gf = fopen ($HtmlFile, "r");           //    Cargar Plantilla HTML
$gPaginaHtml = fread ($gf, filesize ($HtmlFile));
fclose ($gf);
if (strpos($gPaginaHtml, "{Cabecera}") >= 0){
    include_once "../De_Files/DeGeGe_cabecera.php";
    $olCabecera = new clsCabecera();
    $gPaginaHtml= str_replace("{Cabecera}",  $olCabecera->getHtmlOut(), $gPaginaHtml) ;
    }
/**
$sqltext=  "select year(com_feccontab) as AÑO,
	month(com_feccontab) as MES,
	concat(per_apellidos, ' ', per_nombres) as PROVEEDOR,
	act_codauxiliar as CODIGO,
	concat(act_descripcion , ' ', act_descripcion1) as ITEM,
	sum(round(det_cantequivale,2)) as CANTIDAD,
	sum(round(det_costotal,2)) as VALOR,
	round(sum(round(det_costotal,2))  / sum(round(det_cantequivale,2)),2) AS 'CTO/UNIT'
from concomprobantes
	join invdetalle on det_regnumero = com_regnumero
	left join conactivos on act_codauxiliar = det_coditem
	left join conpersonas on per_codauxiliar = com_codreceptor
where com_tipocomp = 'IB'
group by 1, 2, 3, 4, 5
order by 3,1,2
";
**/
$sqltext=  "select year(com_feccontab),
	month(com_feccontab) ,
	concat(per_apellidos, ' ', per_nombres) ,
	act_codauxiliar ,
	concat(act_descripcion , ' ', act_descripcion1) ,
	sum(round(det_cantequivale,2)) ,
	sum(round(det_valtotal,2)) ,
	round(sum(round(det_valtotal,2))  / sum(round(det_cantequivale,2)),2) 
from concomprobantes
	join invdetalle on det_regnumero = com_regnumero
	left join conactivos on act_codauxiliar = det_coditem
	left join conpersonas on per_codauxiliar = com_codreceptor
where com_tipocomp = '" . fGetParam('pTrans', 'IB') . "'
group by 1, 2, 3, 4, 5
order by 3,1,2
";



//$sqltext="select OrderID,CustomerID,ShipName,ShipCity,ShipCountry,OrderDate,ShippedDate from nworders";
$_SESSION['oPreciosProv']=$sqltext;
$slTramite=$_GET["pText"];
$gPaginaHtml= str_replace("{lblTramite}",  $slTramite, $gPaginaHtml) ;
header('Pragma: no-cache ');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");// always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
echo $gPaginaHtml;
require "../LibPhp/chklang.php";
?>

