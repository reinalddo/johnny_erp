<?php
/*   Reporte de Cajas Embarcadas por Semana y Cliente
 */
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}
//include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);

$pEmbarque = fGetparam("pEmbarque",false);


/*para consultar los detalles*/
$sSql = "SELECT  c.tar_NumTarja AS Tarja
                /**************** EMBARQUE ****************/
               ,c.tac_RefOperativa AS CodEmbarque
	       ,e.emb_NumViaje as Viaje
	       ,DATE_FORMAT(e.emb_FecZarpe, '%d/%m/%Y') AS FechaSalida
	       ,e.emb_CodVapor
	       ,upper(b.buq_Descripcion) AS Vapor
	       ,e.emb_Consignatario AS CodCliente
	       ,CONCAT(cli.per_Apellidos,' ',cli.per_Nombres) AS Cliente
	       ,upper(pdest.pue_Descripcion)as PtoDestino   
	       ,upper(pembc.pue_Descripcion) AS PtoEmbarque
	       ,IFNULL(e.emb_Descripcion1,' ') AS AgenciaNaviera
               /*-----------------------------------------*/
	       ,c.tac_Semana AS Semana
	       ,DATE_FORMAT(c.tac_Fecha, '%d-%m-%y') AS FechaTarja
               ,c.tac_Corte AS Corte
               ,c.tac_Embarcador AS CodProductor
               ,CONCAT(embarc.per_Apellidos,' ',embarc.per_Nombres) AS Productor
	       ,c.tac_CodEvaluador AS CodEvaluador
               ,CONCAT(eval.per_Apellidos,' ',eval.per_Nombres) AS Evaluador
               ,c.tac_ResCalidad AS Calidad
               ,c.tac_Contenedor AS Contenedor
	       ,c.tac_Bodega as Bodega
	       ,c.tac_Piso as Piso
	       ,CASE 
		WHEN c.tac_Contenedor IS NOT NULL THEN CONCAT('Container: ',c.tac_Contenedor)
		WHEN c.tac_Bodega IS NOT NULL THEN CONCAT('Hatch: ',c.tac_Bodega)
		WHEN c.tac_Piso IS NOT NULL THEN CONCAT('Floor: ',c.tac_Piso)
		ELSE 'Not Defined'
		END AS Ubicacion
               ,c.tac_UniProduccion AS Hacienda
	       ,CONCAT(hac.per_Apellidos,' ',hac.per_Nombres ) AS HaciendaNombre
               ,hac.per_CodAnterior AS HaciendaCodAnterior
               ,d.tad_CodProducto AS CodProducto
               ,CONCAT(act_Descripcion1) AS Producto
               ,d.tad_CodMarca AS CodMarca
               ,mrc.par_Descripcion AS Marca
               ,GROUP_CONCAT(DISTINCT(mrc.par_Descripcion)) AS TodasMarcas
               ,d.tad_CodCaja AS CodCaja
               ,cj.caj_Descripcion AS Empaque
	       ,cj.caj_TipoCaja as TipoCaja
               ,SUM(d.tad_CantDespachada) AS Despachada
               ,SUM(d.tad_CantRecibida) AS Recibida
               ,SUM(d.tad_CantRechazada) AS Rechazada
               ,SUM(d.tad_CantCaidas) AS Caidas
               ,SUM(IFNULL(d.tad_CantRecibida,0)-IFNULL(d.tad_CantRechazada,0)-IFNULL(tad_CantCaidas,0)) AS Embarcadas
	       ,SUM(IFNULL(tad_CantRecibida,0)-IFNULL(tad_CantRechazada,0)-IFNULL(tad_CantCaidas,0)) AS CajasTotales
               ,d.tad_CodCompon1 AS Carton
               ,d.tad_CodCompon2 AS Plastico
               ,d.tad_CodCompon3 AS Material
               ,d.tad_CodCompon4 AS Etiqueta
               ,d.tad_ValUnitario AS VUnitario
               ,d.tad_DifUnitario AS VDifUnitario
	       
	       
	       
	       /* ,ROUND(AVG(IFNULL(d.tad_Peso,0)),2) AS PesoNeto*/ /*Columna que guarda el peso de la fruta */
	       /* ,ROUND(AVG(IFNULL(d.tad_Largo,0)),2) AS PesoEmpq */ /*Columna Calibre que guarda el peso del empaque */
	       /* ,(ROUND(AVG(IFNULL(d.tad_Peso,0)),2) + ROUND(AVG(IFNULL(d.tad_Largo,0)),2)) AS PesoTotal*/ /*Peso total de la fruta y el empaque */
               /* ,d.tad_NumDedos AS Dedos */
               
               
		/* Nuevas columnas*/
	       ,ROUND(AVG(IFNULL(d.tad_Peso,0)),2) AS PesoNeto /*Columna que guarda el peso de la fruta */
	       ,ROUND(AVG(IFNULL(d.tad_Largo,0)),2) AS Calibre /*Columna Calibre que guarda el peso del empaque */
	       /* ,(ROUND(AVG(IFNULL(d.tad_Peso,0)),2) + ROUND(AVG(IFNULL(d.tad_ClusCaja,0)),2)) AS PesoTotal */ /*Peso total de la fruta y el empaque */
               ,ROUND(AVG(IFNULL(d.tad_NumDedos,0)),2) AS Dedos
               ,ROUND(AVG(IFNULL(d.tad_ClusCaja,0)),2) AS PesoTotal
	       
	       
               /* *********** DATOS GENERALES DE LA EMPRESA******** */
               ,CONCAT(IFNULL(eDir.par_Descripcion,''),'',IFNULL(eDir.par_Valor1,''),IFNULL(eDir.par_Valor2,''),IFNULL(eDir.par_Valor3,''),IFNULL(eDir.par_Valor4,'')) AS EDir
               ,IFNULL(eTel.par_Descripcion,'') AS ETel
               ,IFNULL(ePai.par_Descripcion,'') AS EPai
               ,IFNULL(eCiu.par_Descripcion,'') AS ECiu
               /*----------------------------------------- */
               ,DATE_FORMAT(SYSDATE(),'%M %d,%Y') AS FechaImp
	       /* ,'FRUTIBONI S.A' AS Shipper*/
	       ,concat(upper('".$_SESSION["g_empr"]."'),' ','S.A.') AS Shipper
	       ,'../Images/InformeCliente/Logo_84x72.jpg' as Imagen1
	       ,'../Images/InformeCliente/Logo_84x72.jpg' as Imagen2
	       ,'../Images/InformeCliente/Logo_84x72.jpg' as Imagen3
	       ,'../Images/InformeCliente/Logo_84x72.jpg' as Imagen4
	       ,'../Images/InformeCliente/Logo_84x72.jpg' as Imagen5
	       ,'../Images/InformeCliente/Logo_84x72.jpg' as Imagen6
        FROM liqtarjacabec c
        JOIN liqtarjadetal d ON d.tad_NumTarja = c.tar_NumTarja
        JOIN liqembarques e ON e.emb_RefOperativa = c.tac_RefOperativa /*EMBARQUE*/
        LEFT JOIN conpersonas embarc ON embarc.per_CodAuxiliar = tac_Embarcador
	LEFT JOIN conpersonas eval ON eval.per_CodAuxiliar = c.tac_CodEvaluador
        LEFT JOIN conactivos ON act_CodAuxiliar = tad_CodProducto
        LEFT JOIN genparametros mrc ON mrc.par_Clave = 'IMARCA' AND mrc.par_Secuencia = tad_CodMarca
        LEFT JOIN genparametros eDir ON eDir.par_Clave = 'EGDIR' 
        LEFT JOIN genparametros eTel ON eTel.par_Clave = 'EGTELE' 
        LEFT JOIN genparametros ePai ON ePai.par_Clave = 'EGPAIS'
        LEFT JOIN genparametros eCiu ON eCiu.par_Clave = 'EGCIU'
        LEFT JOIN liqbuques b ON b.buq_CodBuque = e.emb_CodVapor
        LEFT JOIN conpersonas cli ON cli.per_CodAuxiliar = emb_Consignatario
        LEFT JOIN genpuertos pdest ON pdest.pue_CodPuerto = e.emb_Destino
        LEFT JOIN genpuertos pembc ON pembc.pue_CodPuerto = e.emb_CodPuerto
	LEFT JOIN conpersonas hac ON hac.per_CodAuxiliar = tac_UniProduccion
        LEFT JOIN liqcajas cj ON cj.caj_CodCaja = d.tad_CodCaja
        WHERE e.emb_RefOperativa = ".$pEmbarque."
        /* and c.tac_Contenedor = 'GESU9438287' */
        ";

$sSql .= ($pSemana ? " and tac_Semana =". $pSemana : "  " );
$sSql .= ($pCliente ? " and emb_Consignatario =". $pCliente  : "  " );
$sSql .= ($pVapor ? " and e.emb_CodVapor = ". $pVapor : "  " );
$sSql .= " GROUP BY c.tac_Contenedor, c.tac_UniProduccion, d.tad_CodMarca, d.tad_CodCaja   order by 1 ";


$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){
    fErrorPage('','NO HAY INFORMACION PARA GENERAR EL REPORTE', true,false);
}else{
   
   $rsIMG = $rs;
   /* Funcion para comprobar la existencia de una imagen en el servidor */
   function COMPRUEBA($mimagen){
	 //if(!file_exists($_SERVER['DOCUMENT_ROOT'].$mimagen)){
	 if(!file_exists($mimagen)){
	    fErrorPage('','NO EXISTE LA IMAGEN: '.$mimagen, true,false);
	 }
   }

    while ($r = $rsIMG->fetchRow()){
       COMPRUEBA($r['Imagen1']);
       COMPRUEBA($r['Imagen2']);
       COMPRUEBA($r['Imagen3']);
       COMPRUEBA($r['Imagen4']);
       COMPRUEBA($r['Imagen5']);
       COMPRUEBA($r['Imagen6']);
    }
      
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    /* if (!$Tpl->is_cached('../Li_Files/LiCiRp_ReporteCalidad.tpl')) {
            } hasta el 14-nov */ 
      //$Tpl->display('../Li_Files/LiCiRp_ReporteCarga.tpl');
      // $html = $Tpl->fetch('../Li_Files/LiCiRp_ReporteCalidad.tpl'); hasta el 14-nov
	

      if (!$Tpl->is_cached('../Li_Files/LiCiRp_ReporteCalidad2.tpl')) {
            } 
      $html = $Tpl->fetch('../Li_Files/LiCiRp_ReporteCalidad2.tpl');

      $html = utf8_encode($html);
        //echo($html);
      
      
   //================= CREAR UN PDF USANDO LIBRERIA TCPDF ======================+
   require_once('../LibPhp/tcpdf/config/lang/eng.php');
   require_once('../LibPhp/tcpdf/tcpdf.php');

// create new PDF document

//REDUCIR EL MARGEN DEL HEADER:


// Extend the TCPDF class to create custom Header and Footer
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Smart Net');
$pdf->SetTitle('REPORT');
$pdf->SetSubject('');
$pdf->SetKeywords('');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);


$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set font
$pdf->SetFont('dejavusans', '', 6);
// add a page
$pdf->AddPage();
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// reset pointer to the last page
$pdf->lastPage();
// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('Report.pdf', 'I');
      
     }
     ;
?>
