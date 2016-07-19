<?php
/*
*   CoRtTr_archxml_REOC: Generacion de Archivo XML REOC
*   @author     Erika Suarez
*   @param      string		pQryLiq  Condición de búsqueda
*/
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/ComExCCS.php");
ini_set("max_execution_time", 120);
//include("../LibPhp/GenCifras.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineCompras(&$db, $pQry=false){
	global $pAnio;
    $ilNumProceso= fGetParam('pro_ID', 0);
	$slAutCond = "";
	if ($pAnio == 2006  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008
		$slAutCond = " and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) " ;
	}
    
    if ($pAnio >= 2008  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008
		$slAutCond = " and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) and aut.aut_IDauxiliar = idprovfact " ;
	}
    
    
    /*@todo:    Preocesar correctamente la relacion fiscompra - genaut sobre cada tipo de documento. */
    $alSql =
	"SELECT 	tid.par_Valor1 AS tpIdProv,
			per_Ruc as idProv,
			tipoComprobante as tipoComp,
			autorizacion as aut,
			establecimiento as estab,
			puntoEmision as ptoEmi,
			secuencial as sec,
			DATE_FORMAT(fechaEmision ,'%d/%m/%Y') AS fechaEmiCom,
			ifnull(ai1.tab_txtData,'000') as codRetAir,
			/* ai1.tab_txtData as codRetAir, */
			porcentajeAir as porcentaje,
			
			/*baseImponible*/ 0 as base0,
			/*baseImpGrav*/ baseImpAir as baseGrav,
			0 as baseNoGrav,
			
			valRetAir,
			autRetencion1 as autRet,
			estabRetencion1 as estabRet,
			puntoEmiRetencion1 AS ptoEmiRet,
			ifnull(secRetencion1,0) as secRet, 
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet,
			
			baseImpAir, 
			ifnull(ai2.tab_txtData,'000')  as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ifnull(ai3.tab_txtData,'000')  as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			
			/*Si no hay 2 retencion no agregar*/
			case ifnull(ai2.tab_txtData,'000') when '000' then 0 /*No agregar*/ else 0 end as base0Air2,
			case ifnull(ai2.tab_txtData,'000') when '000' then 0 /*No agregar*/ else baseImpAir2 end as baseGravAir2,
			case ifnull(ai2.tab_txtData,'000') when '000' then 0 /*No agregar*/ else 0 end as baseNoGravAir2,
			
			/*Si no hay 3 retencion no agregar*/
			case ifnull(ai3.tab_txtData,'000') when '000' then '0' /*No agregar*/ else 0 end as base0Air3,
			case ifnull(ai3.tab_txtData,'000') when '000' then '0' /*No agregar*/ else baseImpAir3 end as baseGravAir3,
			case ifnull(ai3.tab_txtData,'000') when '000' then '0' /*No agregar*/ else 0 end as baseNoGravAir3,

			
			ID, tipoTransac, codSustento, devIva,
			DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			if(tipoComprobante = 3, DATE_FORMAT(liq.aut_FecVigencia,'%d/%m/%Y'), 
			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') ) AS fechaCaducidad,
			porcentajeIva, montoIva,
			baseImpIce, IFNULL(ice.tab_codsecuencial,0) as porcentajeIce, 
			montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios,
			porRetServicios,
			CASE ris.tab_TxtData	WHEN 100 THEN 0	ELSE valorRetServicios	END AS valorRetServicios,
			CASE ris.tab_TxtData	WHEN 100 THEN valorRetServicios ELSE 0	END AS valRetServ100,
			docModificado,
			DATE_FORMAT(fechaEmiModificado ,'%d/%m/%Y') AS fechaEmiModificado, estabModificado,
			ptoEmiModificado, secModificado,  autModificado,
			contratoPartidoPolitico, montoTituloOneroso, montoTituloGratuito
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_ID = autorizacion  " . $slAutCond . "  
            LEFT JOIN genautsri   liq    ON liq.aut_ID = autorizacion  AND liq.aut_tipoDocum = 3 AND liq.aut_IDauxiliar = -99
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ice   ON tab_codtabla = 6 and tab_codigo = porcentajeIce
			/* porcentajes de rte. iva: 5 para servicios, 5A para bienes */
			LEFT JOIN fistablassri ris   ON ris.tab_codtabla = '5' AND ris.tab_codigo = porRetServicios
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 1
			/*AND secRetencion1 != 0 */ " ;
// LEFT JOIN genautsri   aut    ON aut.aut_ID = autorizacion  AND aut.aut_tipodocum = tipocomprobante " . $slAutCond . "			
   if (strlen($pQry) > 0 ) $alSql .= " AND  "  . $pQry ;
//    WHERE " . $pQry . " ";
//  echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}


/*
*   Recorre el registro de datos para generar cada campo en formato XML
*   @param  $alRec      referencia al Recordset dedatos
*   @param  $alCam      referencia al Recordset de estructura XML
*/
function fProcesaDatos($alRec, $alCam){
	global $detair, $airFl, $air;
	$detair=false;
	$air = false;
	$airFl = false;
	reset($alCam);
	foreach ($alCam as $olDato) {
	      if (!array_key_exists($olDato["xml_campo"], $alRec)){
	          echo " NO EXISTE EL CAMPO " . $olDato["xml_campo"] . " = " . $olDato["xml_campo"] . " <br>";
	          }
	      else {
               
	       /**
		*  VALIDAR QUE CUANDO NO SE USE NUMERO DE REFERENDO NO AGREGE EN EL NODO LOS ELEMENTOS DEL REFERENDO
		*  Si es que no usa numero de referendo
	       */
	       if($alRec["secRet"] <= 0) {
			if ($olDato["xml_campo"] == "secRet"){
				//No agregar al nodo porque no es requerido si el campo esta vacio
			 }
			 else{
				fProcesaCampo($alRec, $olDato);
			 }
	       }
	       else {
			fProcesaCampo($alRec, $olDato);
	       }
	       /**
		*/
	      }
	}
}
/*
*   Analiza cada campo de datos frente ala estructura asociada y genera el objeto XML
*   @param  $alRec      referencia al Recordset de datos
*   @param  $olDato      referencia al Objeto que define la estructura del campo
*/
function fProcesaCampo($alRec, $olDato){
    global $det, $air, $doc, $airFl, $detco, $detair,$banderaAir2,$banderaAir3;
   
    $slK = $olDato["xml_campo"];
    $slV = $alRec[$slK];
    $slK1 = $slK;
    $slSuf="";
    $idx = strpos($slK, "Air");
	if ($idx >0) {
	    if (strlen($slK) > $idx + 3) {
	    	$slK1 = substr($slK,0, strlen($slK) - 1);
		$slSuf= substr($slK,strlen($slK) - 1);
	    }
	}
	
	switch($olDato["xml_formato"]) {
		    case ('N'):
		    case ('n'):
		    case ('D'):
		    case ('d'):
		    case ('E'):
		        $ilEnteros = NZ($olDato["xml_longMax"]) - NZ($olDato["xml_numDecim"]);
				$slV=fNumFormateado($slV, $ilEnteros, $olDato["xml_numDecim"], $olDato["xml_longMin"]);
				break;
			case ('dd/mm'):
			case ('dd/mm/aaaa'):
			case ('mm/aaaa'):
			case ('t/mm/aaaa'):
				$slFmt = str_replace("mm", "m", $olDato["xml_formato"]);
				$slFmt = str_replace("dd", "d", $slFmt);
				$slFmt = str_replace("aaaa", "Y", $slFmt);
				$slFmt = str_replace("aa", "y", $slFmt);

				if ($slV == "0000/00/00"
                    or $slV == "00/00/0000"
                    or $slV == "31/12/1969"
                    or is_null($slV) or strlen($slV ) < 1
                    or str2date($slV,"dmy") < str2date("2001/01/01","ymd")
                    or !date($slFmt, str2date($slV, "dmy")) ) {
					if ( $olDato["xml_requerido"] == "ob") {
						echo "<br> La Transaccion " . $alRec["ID"] . " Tipo: " . $alRec["tipoComprobante"] .
							 " Comp. Numero: " . $alRec["secuencial"] . " tiene el campo " . $slK .
						 	" un valor invalido " . $slV;
						$slV="00/00/0000";
						}
					else {
						//La fecha de emision del comprobante de retencion no acepta 00/00/0000
						if ($slK == "fechaEmiRet") $slV="01/01/1900"; 
						else	$slV="00/00/0000";
						}
					}
				else 	$slV= date($slFmt, str2date($slV, "dmy"));
				break;
			case ('C'):
			case ('c'):
			    break;
			default:
			    break;
		}
	    if (FALSE !== $idx){
			if(!$airFl ){		//Solo en primer air
				$detco = $doc->createElement('air');
				$detair= $det->appendChild($detco);
				$airFl = true;
			}
		//Quitar la validacion si es valor es mayor a 0 porque puede que no tenga retencion pero si iva
    		//if (($alRec["codRetAir" . $slSuf] > 0 && $alRec["baseImpAir" . $slSuf] > 0) ) { // Si el porcentaje o valor retenido es >0
//		    if (!$air){ 		// Nuevo detalle air
		    if ($slK == "codRetAir" || $slK == "codRetAir2" || $slK == "codRetAir3" ){ // Nuevo detalle air
			//echo('******valor: '.$slV.' columna:'.$slK);
			    if ($slV > 0  || $slK == "codRetAir" ){    //Solo la primera linea de retencion debe ir siempre 
				$detco = $doc->createElement('detalleAir');
				$air   = $detair->appendChild($detco);
				
			    }
			}
			
				
				
				 
				    
			// cuando no haya segunda o tercera retencion no agregar porcentaje ni valor 
			if ($slK == "codRetAir2" || $slK == "codRetAir3" || $slK == "porcentajeAir2" || $slK == "porcentajeAir3" || $slK == "valRetAir2" || $slK == "valRetAir3" || $slK == 'base0Air2' ||$slK == 'baseGravAir2' ||$slK == 'baseNoGravAir2'|| $slK == 'base0Air3' ||$slK == 'baseGravAir3' ||$slK == 'baseNoGravAir3'){
				if ($slK == "codRetAir2"){ // Guardar bandera para saber si se creo detalle Air2 y 3
					if ($slV > 0){    //Bandera para segunda retencion
					    $banderaAir2 =  1;
					}else{
					    $banderaAir2 = 0;
					}
				}
				
				if ($slK == "codRetAir3" ){ // Guardar bandera para saber si se creo detalle Air2 y 3
					if ($slV > 0){   //Bandera para tercera retencion 
					    $banderaAir3 = 1;
					 //   echo(' BANDERA AIR 3:');
					}else{
					    $banderaAir3 = 0;
					}
				    }
				    
				    
				//echo('<br>BANDERA 2: '.$banderaAir2.' CAMPO: '.$slK.' VALOR: '.$slV.'<br>');
				//echo('<br>'.$banderaAir3.'<br>');
				if ($slV > 0){
					
					$slK2 = "";
					
					if ($slK == "porcentajeAir2" || $slK == "porcentajeAir3" || $slK == 'base0Air2' ||$slK == 'baseGravAir2' ||$slK == 'baseNoGravAir2'|| $slK == 'base0Air3' ||$slK == 'baseGravAir3' ||$slK == 'baseNoGravAir3' || $slK == 'base0Air2' ||$slK == 'baseGravAir2' ||$slK == 'baseNoGravAir2'|| $slK == 'base0Air3' ||$slK == 'baseGravAir3' ||$slK == 'baseNoGravAir3'){
						// Agregar los dos nodos que no se van a agregar porque estan con valor 0:
						if ($slK == 'baseGravAir2' || $slK == 'baseGravAir3'){
							fAgregarElemTxt($air, "base0", "0.00");
							$slK2 = $slK;
						}
						
						$slK = str_replace("Air2", "", $slK); //Solo porcentaje
						$slK = str_replace("Air3", "", $slK);
						
					}
					else{
						$slK = str_replace("Air2", "Air", $slK); //Siempre debe terminar en Air
						$slK = str_replace("Air3", "Air", $slK);
					}
					fAgregarElemTxt($air, $slK, $slV);
					
						
						if ($slK2 == 'baseGravAir3' ||$slK2 == 'baseGravAir2'){
							// Agregar los dos nodos que no se van a agregar porque estan con valor 0:
							fAgregarElemTxt($air, "baseNoGrav", "0.00");
							$slK2 = "";
						}
					
				}else{
					//echo('<br>'.$banderaAir2.'<br>');
					//echo('<br>'.$banderaAir3.'<br>');
				
					//Si se ingresaron codigos para las otras retenciones se debe agregar el detalle aunq esté en 0
					if ($slK == "porcentajeAir2" || $slK == 'valRetAir2' || $slK == 'baseGravAir2'){
						if ($banderaAir2 == 1){
							if ($slK == "porcentajeAir2"){
								$slK = str_replace("Air2", "", $slK); //Solo porcentaje
								fAgregarElemTxt($air, $slK, $slV);
							}
							if ($slK == 'valRetAir2'){
								$slK = str_replace("2", "", $slK); //Solo porcentaje
								fAgregarElemTxt($air, $slK, $slV);
							}
							if ($slK == 'baseGravAir2'){ //Para los casos que ponen el codigo de retencion pero no la base
								$slK = str_replace("Air2", "", $slK); //Solo porcentaje
								fAgregarElemTxt($air, "base0", "0.00");
								fAgregarElemTxt($air, $slK, $slV);
								fAgregarElemTxt($air, "baseNoGrav", "0.00");
							}
							
						}
					}
					elseif ($slK == "porcentajeAir3" || $slK == 'valRetAir3'|| $slK == 'baseGravAir3'){
						if ($banderaAir3 == 1){
							if ($slK == "porcentajeAir3"){
								$slK = str_replace("Air3", "", $slK); //Solo porcentaje
								fAgregarElemTxt($air, $slK, $slV);
							}
							if ($slK == 'valRetAir3'){
								$slK = str_replace("3", "", $slK); //Solo porcentaje
								fAgregarElemTxt($air, $slK, $slV);
							}
							
							if ($slK == 'baseGravAir3'){ //Para los casos que ponen el codigo de retencion pero no la base
								$slK = str_replace("Air3", "", $slK); //Solo porcentaje
								fAgregarElemTxt($air, "base0", "0.00");
								fAgregarElemTxt($air, $slK, $slV);
								fAgregarElemTxt($air, "baseNoGrav", "0.00");
							}
							
						}
					}
				}
				/*else{
					if ($slK == 'base0Air2' ||$slK == 'baseGravAir2' ||$slK == 'baseNoGravAir2'|| $slK == 'base0Air3' ||$slK == 'baseGravAir3' ||$slK == 'baseNoGravAir3'){
						if ($slV != '9'){
							print("Aqui agrego".$slV.$slK);
							$slK = str_replace("Air2", "", $slK); //Solo porcentaje
							$slK = str_replace("Air3", "", $slK);
							fAgregarElemTxt($air, $slK, $slV);
						}
					}
				}*/
			}
			else{
				fAgregarElemTxt($air, $slK, $slV);
			}
			
    		//}
	    }
	    else{
		// hay campos que no terminan en Air pero deben ser incluidos en ese nodo: porcentaje,base0,baseGrav,baseNoGrav
		if ($slK == 'porcentaje' || $slK == 'base0' ||$slK == 'baseGrav' ||$slK == 'baseNoGrav'){
			fAgregarElemTxt($air, $slK, $slV);
			
		}
		else{
			//$air = false; // no corresponde a retencion en la fuente air //No permitia que se agregue posteriormente elemento air!
			fAgregarElemTxt($det, $slK, $slV);
			
		}
		
	    }
}

function fAgregarElemPar(&$pCont, $pNom, $pVal){
	global $doc, $db;
	$slVal = fDBValor($db, 'genparametros', 'par_Descripcion', "par_clave = '$pVal'");
	fAgregarElemTxt($pCont, $pNom, $slVal);
}
function fAgregarElemTxt(&$pCont, $pNom, $pVal){
	global $doc, $db;
//	echo "$pNom -- $pVal <br>";
	$outer = $doc->createElement($pNom);
	$outer = $pCont->appendChild($outer);
	$valor = $doc->createTextNode($pVal);
	$valor = $outer->appendChild($valor);
}

function fNumFormateado($pVal, $pEnt, $pDec, $pLMin=false)
{
	$alFmtoEnt = array();
	$alFmtoDec = array();
	$alLong = 1;
	$pVal = number_format($pVal, $pDec, ".","");
	for ($i= 1;$i<=$pDec; $i++){
	    if (($alLong <= $pLMin)) $alFmtoDec[]="0";
	    else $alFmtoDec[]="#";
	    ++$alLong;
	}
	for ($i= 1;$i<=$pEnt; $i++){
	    if ($alLong <= $pLMin ) $alFmtoEnt[]="0";
	    else $alFmtoEnt[]="#";
	    ++$alLong;
	}
	$slNumF = CCFormatNumber($pVal, Array(True, $pDec, ".", "", False, $alFmtoEnt, $alFmtoDec, 1, True, ""));
// echo "E:$pEnt  ---  D: $pDec    ----  M: $pLMin //  $pVal // $slNumF";
	return $slNumF;
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
ob_start();
$det= NULL;
$air= false;
$doc= NULL;
$detco= NULL;
$detair= NULL;
$airFl= false;
$doc = new DomDocument('1.0', 'UTF-8');
$root = $doc->createElement('reoc');
$root = $doc->appendChild($root);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> No hay acceso al servidor de BD");
$db->debug=fGetParam('pAdoDbg', 0);
$pAnio = fGetParam('s_Anio', false);
$pMes = fGetParam('s_Mes', false);
if (!$pAnio || !$pMes) echo "DEBE ELEGIR UN AÑO Y MES PARA PROCESAR";
echo "AÑO DE PROCESO: " . $pAnio . "<br>";
echo "MES DE PROCESO: " . $pMes . "<br>";
fAgregarElemPar($root, 'numeroRuc', 'EGRUC');
fAgregarElemTxt($root, 'anio', $pAnio);
fAgregarElemTxt($root, 'mes', CCFormatNumber($pMes, Array(True, 0, ".", ",", False, array("0","0"), array(), 1, True, "")));

$cmp = $doc->createElement('compras');
$root->appendChild($cmp);
$pQry= fGetParam('pQry', false);
/**
*   Procesar registros decompras
*   xml_tiporeg = 'R_COM' Estructura de compras para el REOC
**/
echo "<br> REOC -PROCESO DE COMPRAS ---------------------------------------------------";

$rs = fDefineCompras($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'R_COM' "; 
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
$ilCount = 0;

if ($rs)
	while ($alRec = $rs->FetchRow()) {
	    $airFl = false;
		$detco = $doc->createElement('detalleCompras');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br>$ilCount Registros de Compras procesados.<br>";




$xml_string = $doc->saveXML();
$xml_string = str_replace("><", ">\r\n<", $xml_string);
$slArch= "REOC_" . DBNAME . "_" . $pAnio . "_" . $pMes . "";
$slRutaArch="../pdf_files/" . $slArch;
if($file = fopen($slRutaArch,"w")) {
 	fwrite($file, $xml_string);
 	fclose($file);
	}
//$doc->dump_file($slRutaArch, false, true);
$host  = "http://" .$_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/\\');
$extra = "pdf_files/$slArch";
$slFullPath=$host . $uri . "/".$extra ;

$slRef= $host . $uri . "/LibPhp/bajar.php?pOrig=" . $slRutaArch . "&pDest=$slArch.xml";
echo  "<a href=" . $slRef . "> Descargar el Archivo generado </a>";
ob_end_flush();
?>
