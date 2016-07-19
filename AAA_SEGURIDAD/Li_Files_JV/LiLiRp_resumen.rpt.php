<?php
/*  -------------------------- NO ESTA EN USO;
*   LiliRp_resumen.php: Emite un resumen general de liquidaciones, en formato pdf
*   @author     Fausto Astudillo
*   @param      integer		pPro    Número de proceso que asocia las liquidaciones
*   @output     contenido pdf del reporte.
*/
error_reporting(E_ALL);
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include('class.ezpdf.php');
//$pdf = & new Cezpdf(array(35,27.8),'landscape');
$slFontName = 'Helvetica';
$pdf = & new Cezpdf(array(35,10.8),'landscape', $slFontName);
$pdf->ezStartPageNumbers(700,18,10,'','Pag.{PAGENUM} de {TOTALPAGENUM}',1);
$pdf->ezSetCmMargins(1,1,0.8,0.3);
//
$ilNumProces   = fGetParam('pPro', false);
$db = NewADOConnection("mysql");
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
//--------
//inicializa array
$data = array();
//Aqui se coloca el header de la Tabla
$cols = array(  'C1' =>'NOMBRE',
                'C2' =>'CAJAS',
                'C3' =>'FRUTA',
                'C4' =>'BONO',
                'C5' =>'EMPAQUE PAGADO',
                'C6' =>'OTROS INGRES',
                'C7' =>'ADELANTO',
                'C8' =>'TRANS PORTE',
                'C9' => 'EMPAQUE COBRADO',
                'C10'=>'MATERIAL HCDAS',
                'C11'=>'PREST CIA',
                'C12'=> 'FERTILIZ',
                'C13'=> 'P PXMO. EMB',
                'C14'=> 'FUMIGACION',
                'C15'=> 'OTROS DESC.',
                'C16'=> 'LIQ. NEGAT',
                'C17'=> ' ',
                'C18'=> 'OTROS',
                'C19'=> 'NETO A PAGAR'
                );
//
IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liquidaciones")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liqtarjadetal")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
IF(!$db->Execute( "CREATE TEMPORARY TABLE tmp_liquidaciones
                 SELECT liq_numliquida, liq_codrubro, sum(liq_valtotal) as tmp_valtotal
                 FROM liqliquidaciones
                 WHERE liq_Numproceso = "  .$ilNumProces .
                 " GROUP BY 1, 2")) fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (1)', true,false);
                 
IF(!$db->Execute("CREATE TEMPORARY TABLE tmp_liqtarjadetal
                         SELECT tad_liqnumero, concat(left(per_Apellidos,18), ' ', left(per_Nombres,8)) AS tmp_productor,
                                sum(tad_cantrecibida - tad_cantrechazada  ) AS tmp_cantembarcada
                         FROM (liqtarjadetal JOIN liqtarjacabec ON tar_NUmTarja = tad_NumTarja)
                                            JOIN conpersonas   ON per_Codauxiliar = tac_Embarcador
                         WHERE tad_liqproceso = "  .$ilNumProces .
                         " GROUP by 1, 2"))fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2)', true,false);
//
// Generacion de tabla de referencias cruzadas, en base al detalle de liquidaciones requeridas
$slSql ="SELECT tmp_productor AS 'C1',
                tmp_cantembarcada AS 'C2',
                SUM(CASE WHEN  1 <= RUB_POSORDINAL and RUB_POSORDINAL <= 1 THEN tmp_valtotal ELSE 0 END)  AS 'C3',
                SUM(CASE WHEN  2 <= RUB_POSORDINAL and RUB_POSORDINAL <= 2 THEN tmp_valtotal ELSE 0 END)  AS 'C4',
                SUM(CASE WHEN  3 <= RUB_POSORDINAL and RUB_POSORDINAL <= 3 THEN tmp_valtotal ELSE 0 END)  AS 'C5',
                SUM(CASE WHEN  4 <= RUB_POSORDINAL and RUB_POSORDINAL <= 4 THEN tmp_valtotal ELSE 0 END)  AS 'C6',
                SUM(CASE WHEN  5 <= RUB_POSORDINAL and RUB_POSORDINAL <= 5 THEN tmp_valtotal ELSE 0 END)  AS 'C7',
                SUM(CASE WHEN  6 <= RUB_POSORDINAL and RUB_POSORDINAL <= 6 THEN tmp_valtotal ELSE 0 END)  AS 'C8',
                SUM(CASE WHEN  7  <= RUB_POSORDINAL and RUB_POSORDINAL <= 7 THEN tmp_valtotal ELSE 0 END) AS 'C9',
                SUM(CASE WHEN  8 <= RUB_POSORDINAL and RUB_POSORDINAL <= 8 THEN tmp_valtotal ELSE 0 END)  AS 'C10',
                SUM(CASE WHEN  9 <= RUB_POSORDINAL and RUB_POSORDINAL <= 9 THEN tmp_valtotal ELSE 0 END)  AS 'C11',
                SUM(CASE WHEN 10 <= RUB_POSORDINAL and RUB_POSORDINAL <= 10 THEN tmp_valtotal ELSE 0 END) AS 'C12',
                SUM(CASE WHEN 11 <= RUB_POSORDINAL and RUB_POSORDINAL <= 11 THEN tmp_valtotal ELSE 0 END) AS 'C13',
                SUM(CASE WHEN 12 <= RUB_POSORDINAL and RUB_POSORDINAL <= 12 THEN tmp_valtotal ELSE 0 END) AS 'C14',
                SUM(CASE WHEN 13 <= RUB_POSORDINAL and RUB_POSORDINAL <= 13 THEN tmp_valtotal ELSE 0 END) AS 'C15',
                SUM(CASE WHEN 14 <= RUB_POSORDINAL and RUB_POSORDINAL <= 14 THEN tmp_valtotal ELSE 0 END) AS 'C16',
                SUM(CASE WHEN 15 <= RUB_POSORDINAL and RUB_POSORDINAL <= 15 THEN tmp_valtotal ELSE 0 END) AS 'C17',
                SUM(CASE WHEN  RUB_POSORDINAL IN (11,12,13,14,15,16) THEN tmp_valtotal ELSE 0 END)        AS 'C18',
                SUM(tmp_valtotal)  AS 'C19'
        FROM (tmp_liquidaciones l JOIN tmp_liqtarjadetal t on   liq_numliquida = tad_liqnumero)
                JOIN liqrubros on rub_codrubro = liq_codrubro
        GROUP BY 1, 2 ";
$rsLiq = $db->Execute($slSql);
IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE LIQUIDACION', true,false);
//
// Define la cabecera de pagina
$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0,0,0,1);
//--$pdf->addText(20,770,10,'---JORCORP S.A.--- ');
//--$pdf->addText(500,770,40,'RESUMEN DE LIQUIDACIONES');
$pdf->addText(5,280,10,'---JORCORP S.A.--- ');
$pdf->addText(100,280,10,'RESUMEN DE LIQUIDACIONES');
$data[]=$cols;

$pdf->ezTable($data,$cols,'',array('fontSize'=>6, 'titleFontSize' => 6, 'showHeadings'=>1,'shaded'=>1,
                                   'showLines'=>2, 'maxWidth'=>990, 'innerLineThickness'=>5,
                                   'cols'=>array(
                                                'C1' =>array('justification'=>'center','width'=>90),
                                                'C2' =>array('justification'=>'center','width'=>48),
                                                'C3' =>array('justification'=>'center','width'=>48),
                                                'C4' =>array('justification'=>'center','width'=>48),
                                                'C5' =>array('justification'=>'center','width'=>48),
                                                'C6' =>array('justification'=>'center','width'=>48),
                                                'C7' =>array('justification'=>'center','width'=>48),
                                                'C8' =>array('justification'=>'center','width'=>48),
                                                'C9' =>array('justification'=>'center','width'=>48),
                                                'C10'=>array('justification'=>'center','width'=>48),
                                                'C11'=>array('justification'=>'center','width'=>48),
                                                'C12'=>array('justification'=>'center','width'=>48),
                                                'C13'=>array('justification'=>'center','width'=>48),
                                                'C14'=>array('justification'=>'center','width'=>48),
                                                'C15'=>array('justification'=>'center','width'=>48),
                                                'C16'=>array('justification'=>'center','width'=>48),
                                                'C17'=>array('justification'=>'center','width'=>48),
                                                'C18'=>array('justification'=>'center','width'=>48),
                                                'C19'=>array('justification'=>'center','width'=>48),
                                                ),
                                    ));
//$pdf->line(20,760,800,760);
//$pdf->line(20,15,800,15);
$pdf->ezSetDy(-20,"makeSpace");
$pdf->restoreState();
$pdf->closeObject();
// termina las lineas
$pdf->addObject($all,'all');
//--------
$alTot = array(0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
$alSum = array('C1' =>0,
               'C2' =>0,
               'C3' =>0,
               'C4' =>0,
               'C5' =>0,
               'C6' =>0,
               'C7' =>0,
               'C8' =>0,
               'C9' =>0,
               'C10'=>0,
               'C11'=>0,
               'C12'=>0,
               'C13'=>0,
               'C14'=>0,
               'C15'=>0,
               'C16'=>0,
               'C17'=>0,
               'C18'=>0,
               'C19'=>0);
$ind = 0;  //    indice actual del arreglo
while (!$rsLiq->EOF) {
    $data[] = $rsLiq->GetRowAssoc();
    $j=count($alTot);
    for ($i=1; $i<$j;$i++) {
        $key = 'C' . ($i+1);
        if ($alTot[$i]) {
            $alSum[$key] = $alSum[$key] + $data[$ind][$key];
            if (NZ($data[$ind][$key]) == 0 || empty($data[$ind][$key]))  {
                $data[$ind][$key] ='';
            }
            else {
                $data[$ind][$key]= number_format($data[$ind][$key],2,".",",");
            }
        }
   }
    $rsLiq->MoveNext();
    $ind++;
}
echo "3";
// inserto un registro en blanco para generar una fila entre los datos y el total
    $data[] = array('C1' =>'',
                    'C2' =>'',
                    'C3' =>'',
                    'C4' =>'',
                    'C5' =>'',
                    'C6' =>'',
                    'C7' =>'',
                    'C8' =>'',
                    'C9' =>'',
                    'C10'=>'',
                    'C11'=>'',
                    'C12'=>'',
                    'C13'=>'',
                    'C14'=>'',
                    'C15'=>'',
                    'C16'=>'',
                    'C17'=>'',
                    'C18'=>'',
                    'C19'=>'');
$j=count($alTot);
for ($i=1; $i<$j;$i++) {
    if ($alTot[$i]) {
        $key = 'C' . ($i+1);
        $alSum[$key] = " ". number_format($alSum[$key],2,".",",") . "";  // Total de cada columna
    }
}
echo "4";
$nreg = 'Numero de Registros : ' ;
// En esta fila agrego el numero de registros y el total de importe y anticipo
    $data[] = $alSum;
echo "5";

$pdf->ezTable($data,$cols,'',array('fontSize'=>6, 'titleFontSize' => 6,
                                   'showHeadings'=>1,'shaded'=>0,
                                   'showLines'=>2, 'maxWidth'=>990,
                                   'cols'=>array(
                                                'C1' =>array('justification'=>'left' ,'width'=>90),
                                                'C2' =>array('justification'=>'right','width'=>48, 'outerLineThickness'=>5),
                                                'C3' =>array('justification'=>'right','width'=>48),
                                                'C4' =>array('justification'=>'right','width'=>48),
                                                'C5' =>array('justification'=>'right','width'=>48),
                                                'C6' =>array('justification'=>'right','width'=>48),
                                                'C7' =>array('justification'=>'right','width'=>48),
                                                'C8' =>array('justification'=>'right','width'=>48),
                                                'C9' =>array('justification'=>'right','width'=>48),
                                                'C10'=>array('justification'=>'right','width'=>48),
                                                'C11'=>array('justification'=>'right','width'=>48),
                                                'C12'=>array('justification'=>'right','width'=>48),
                                                'C13'=>array('justification'=>'right','width'=>48),
                                                'C14'=>array('justification'=>'right','width'=>48),
                                                'C15'=>array('justification'=>'right','width'=>48),
                                                'C16'=>array('justification'=>'right','width'=>48),
                                                'C17'=>array('justification'=>'right','width'=>48),
                                                'C18'=>array('justification'=>'right','width'=>48),
                                                'C19'=>array('justification'=>'right','width'=>48),
                                                )
            ));// salida
//
//
//if (isset($d) && $d){
//header("Content-type: application/pdf");
//header("Content-Disposition: inline; filename=foo.pdf");
//$pdf->ezStream();  //Necesario para que funcione ezStartPageNumbers
$pdfcode = $pdf->ezOutput();
$dir = '../pdf_files';
//save the file
$w = 0 ;if (!file_exists($dir)){
mkdir ($dir,0777);
}
$fname = '../pdf_files/PDF_LIQUIDA.pdf';
$fp = fopen($fname,'w');
fwrite($fp,$pdfcode);
fclose($fp);
echo '<html>
<head>
<SCRIPT LANGUAGE="JavaScript"><!--
function go_now () { window.open("'.$fname.'","w" ,"status=yes,titlebar=no,toolbar=no,menubar=no,scrollbars=yes, alwaysRaised=1,dependant"); }
//--></SCRIPT>
</head>
<body onLoad="go_now()"; >
<a href="'.$fname.'">HAGA CLICK AQUI</a> SI NO RECIBE EL DOCUMENTO EN PANTALLA".
</body>';

?>

