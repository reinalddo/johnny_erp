<?php
//BindEvents Method @1-0DF5160E
$giRowsDeleted = 0;
$gbRowAlt=1;
function BindEvents()
{
    global $CoTrTr_detalle;
    global $CCSEvents;
    $CoTrTr_detalle->condetalle_concuentas_con_TotalRecords->CCSEvents["BeforeShow"] = "CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow";
    $CoTrTr_detalle->tot_ValDebito->CCSEvents["BeforeShow"] = "CoTrTr_detalle_tot_ValDebito_BeforeShow";
    $CoTrTr_detalle->txt_RefOperativa->CCSEvents["BeforeShow"] = "CoTrTr_detalle_txt_RefOperativa_BeforeShow";
    $CoTrTr_detalle->CCSEvents["BeforeShow"] = "CoTrTr_detalle_BeforeShow";
    $CoTrTr_detalle->CCSEvents["BeforeShowRow"] = "CoTrTr_detalle_BeforeShowRow";
    $CoTrTr_detalle->CCSEvents["AfterSubmit"] = "CoTrTr_detalle_bitacora";
    $CoTrTr_detalle->CCSEvents["AfterExecuteDelete"] = "CoTrTr_detalle_AfterExecuteDelete";
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
    $CCSEvents["BeforeUnload"] = "Page_BeforeUnload";
}
//End BindEvents Method
function CoTrTr_detalle_BeforeShowRow()
{
    global $Tpl;
    global $gbRowAlt;
     if ($gbRowAlt ==1 )
        $Tpl->SetVar("rowClass", "CobaltDataTD");
     else
        $Tpl->SetVar("rowClass", "CobaltAltDataTD");
     $gbRowAlt = $gbRowAlt * (-1);

}
//CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow @76-4A9AEDCB
function CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow()
{
    $CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow = true;
//End CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow

//Retrieve number of records @77-9DA0665D
    global $CoTrTr_detalle;
    $CoTrTr_detalle->condetalle_concuentas_con_TotalRecords->SetValue($CoTrTr_detalle->ds->RecordsCount);
//End Retrieve number of records

//Close CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow @76-BFF6CEE3
    return $CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow;
}
//End Close CoTrTr_detalle_condetalle_concuentas_con_TotalRecords_BeforeShow


//CoTrTr_detalle_tot_ValDebito_BeforeShow @173-00667688
function CoTrTr_detalle_tot_ValDebito_BeforeShow()
{
    $CoTrTr_detalle_tot_ValDebito_BeforeShow = true;
//End CoTrTr_detalle_tot_ValDebito_BeforeShow

//Custom Code @205-257850C6
// -------------------------
    global $CoTrTr_detalle;
	global $DBdatos;

	$ilValor = CCDLookUp("sum(det_ValDebito)", "condetalle", "det_Regnumero = " . CCGetParam("com_RegNumero"), $DBdatos);
	if (is_null($ilValor) ) $ilValor = 0;
	if (!isset($ilValor) ) $ilValor = 0;

	$CoTrTr_detalle->tot_ValDebito->SetValue($ilValor);

	$ilValor = 0;
	$ilValor = CCDLookUp("sum(det_ValCredito)", "condetalle", "det_Regnumero = " . CCGetParam("com_RegNumero"), $DBdatos);
	if (is_null($ilValor)) $ilValor = 0;
	$CoTrTr_detalle->tot_ValCredito->SetValue($ilValor);
// -------------------------
//End Custom Code

//Close CoTrTr_detalle_tot_ValDebito_BeforeShow @173-456DA56B
    return $CoTrTr_detalle_tot_ValDebito_BeforeShow;
}
//End Close CoTrTr_detalle_tot_ValDebito_BeforeShow

//CoTrTr_detalle_tot_ValDebito_BeforeShow @173-00667688
function CoTrTr_detalle_txt_RefOperativa_BeforeShow()
{
    $CoTrTr_detalle_det_RefOperativa_BeforeShow = true;
//End CoTrTr_detalle_tot_ValDebito_BeforeShow
//Custom Code @205-257850C6
// -------------------------
    global $CoTrTr_detalle;
	global $DBdatos;
	global $Tpl;
//	$Tpl->SetVar("txt_RefOperativa", $CoTrTr_detalle->ds->txt_RefOperativa->GetValue());
//	$CoTrTr_detalle->ds->det_RefOperativa->SetValue($CoTrTr_detalle->det_RefOperativa->GetValue());
// -------------------------
//End Custom Code
//Close CoTrTr_detalle_tot_ValDebito_BeforeShow @173-456DA56B
    return $CoTrTr_detalle_det_RefOperativa_BeforeShow;
}

//CoTrTr_detalle_BeforeShow @11-C6B97FBC
function CoTrTr_detalle_BeforeShow()
{
    $CoTrTr_detalle_BeforeShow = true;
//End CoTrTr_detalle_BeforeShow

//Custom Code @314-257850C6
// -------------------------
	global $CoTrTr_detalle;
	if (!CCGetParam("com_RegNumero")) $CoTrTr_detalle->Visible = false;
// -------------------------
//End Custom Code

//Close CoTrTr_detalle_BeforeShow @11-A22BAF0B
    return $CoTrTr_detalle_BeforeShow;
}
//End Close CoTrTr_detalle_BeforeShow
/**
*   Luego de Eliminar un registro del detalle
**/
function CoTrTr_detalle_AfterExecuteDelete()
{
    $CoTrTr_detalle_AfterExecuteDelete = true;
	global $giRowsDeleted;
	$giRowsDeleted +=1;
    return $CoTrTr_detalle_AfterExecuteDelete;
}

//Page_OnInitializeView @1-493DD3AA
function Page_OnInitializeView()
{
    $Page_OnInitializeView = true;
//End Page_OnInitializeView

//Custom Code @166-128506E2
// -------------------------
    global $CoTrTr_deta;
    global $DBdatos;
  	setcookie("coSelect", "");  			//Query aejecutar
	setcookie("coWhere", "");  				//Query aejecutar
	setcookie("coDestino", "");			//Arreglo con nombres de los campos de destino
	setcookie("coOrigen", "");			//Arreglo con los nombres de campos de origen ( en el dataset)
	setcookie("coCompon", "");				    //Nombre del contenedor en la pagina de destino
	setcookie("coSearchPage", "");				//Nombre del Pagina de Busqueda
	setcookie("coErrMensaje", "");				//Mensaje de error si no existe coincidencia
	setcookie("coSufijo", "");				//Sufijo de los componentes en la pagina origen
	setcookie("coMensj", "");				//Mensaje si existe multiple coincidencia (pantalla de seleccion)
	if (!isset($_COOKIE["coEstado"])) {
		$slEstado = 0;
		setcookie("coEstado", 0);	}			//Estado de Grabación del Comprobante
    else {
		switch ($_COOKIE["coEstado"]) {
		   	case 0: 
		       	break; 
		 	default:
				$ilRegNum = CCGetParam("com_RegNumero", -1); 
				if ($ilRegNum > 0) {
				    $sSql = "UPDATE concomprobantes " .
					 		"SET com_estProceso = " . $_COOKIE["coEstado"] .
				        	" where com_RegNumero = " . $ilRegNum ;
				    $DBdatos->Query($sSql);
				    $rst = $DBdatos->next_record();
					$_COOKIE["coEstado"] = 0;		// Define como no grabado
					unset($rst);
					unset($Seg);
				}	
				break;
		}
	}

//End Custom Code

//Close Page_OnInitializeView @1-81DF8332
    return $Page_OnInitializeView;
}
//End Close Page_OnInitializeView

//Page_AfterInitialize @1-7910B30B
function Page_AfterInitialize()
{
    $Page_AfterInitialize = true;
//End Page_AfterInitialize

//Custom Code @200-128506E2
// -------------------------
    global $CoTrTr_deta;
  	include_once (RelativePath . "/LibPhp/SegLib.php") ;
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize
/**
*   Actualiza la cabecera del comprobante, si al eliminar lineas de detalle, resulta descuadrado
*   @access public
*   @return void
**/
function Page_BeforeUnload(){
	global $DBdatos;
	global $giRowsDeleted;
	if ($giRowsDeleted > 0) {
		$ilEstado = -1;
		$ilRegNum = CCGetParam("com_RegNumero", -1);
		$ilValor = CCDLookUp("sum(det_ValDebito - det_ValCredito)", "condetalle", "det_Regnumero = " . $ilRegNum, $DBdatos);
		if ($ilValor == 0){
			 $ilEstado = 5;   // Si el comprobante esta cuadrado
			 setcookie("coEstado", 5);
/**		    $sSql = "UPDATE concomprobantes " .
			 		"SET com_estProceso = " . $ilEstado .
		        	" where com_RegNumero = " . $ilRegNum ;
		    $DBdatos->Query($sSql);  //     El evento Page_initialize actualiza la cabecera
**/
		}
        else 	setcookie("coEstado", -1);
	}
}

function fApruebaRechaza($slOpcode){
	if (!fValidAcceso("",$slOpcode,"")) {
    	fMensaje ("UD. NO ESTA AUTORIZADO A MODIFICAR / ELIMINAR DETALLES DE COMPROBANTES (" . $slOpcode . ")", 1);
        }
}
/**
*   Entrada en la bitacora de seguridad, anotando las condiciones de grabacion
**/
function CoTrTr_detalle_bitacora(){
    global $gfValTotal;
    global $gfCosTotal;
    global $gfCanTotal;
    global $gfCanElim;
    global $gfCanInser;
    global $gfCanModif;
    $pAnot= "Detalles Modificados / Añadidos: " .$gfCanElim. ($gfCanElim?', Eliminados: ' . $gfCanElim:'');
    $pAnot= "Detalles " . ($gfCanInser?' Agregados: ' . $gfCanInser:'') .
            ($gfCanModif?' Modificados: ' . $gfCanModif:'') .
			($gfCanElim?', Eliminados: ' . $gfCanElim:'');
    fRegistroBitacora(CCGetParam('pTipoComp', 'XX'), // Tipo Comprobante
                      CCGetParam('pNumComp', '-1'), // Numero Comprobante
                      $SESSION['g_user'], // Usuario
                      $pAnot,
                      $gfCanTotal,
                      $gfCosTotal,
                      $gfValTotal,
                      $pAuto = " " ,
                      $pEsta = 0,
                      $pCodi = 0);

}
?>
