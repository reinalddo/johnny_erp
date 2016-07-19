<?php
//BindEvents Method @1-92858B6C
function BindEvents()
{
    global $InTrTr_detalle;
    global $CCSEvents;
    $InTrTr_detalle->invdetalle_conactivos_gen_TotalRecords->CCSEvents["BeforeShow"] = "InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow";
    $InTrTr_detalle->hdMulti->CCSEvents["BeforeShow"] = "InTrTr_detalle_hdMulti_BeforeShow";
    $InTrTr_detalle->lbCosTotal->CCSEvents["BeforeShow"] = "InTrTr_detalle_lbCosTotal_BeforeShow";
    $InTrTr_detalle->CCSEvents["BeforeShow"] = "InTrTr_detalle_BeforeShow";
    $InTrTr_detalle->CCSEvents["AfterSubmit"] = "InTrTr_detalle_AfterSubmit";
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method
//include_once("InTrTr_contab.php");
include_once("InAdPr_contabapli.pro.php");
//InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow @41-A5014229
function InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow()
{
    $InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow = true;
//End InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow

//Retrieve number of records @42-E203AB1E
    global $InTrTr_detalle;
    $InTrTr_detalle->invdetalle_conactivos_gen_TotalRecords->SetValue($InTrTr_detalle->ds->RecordsCount);
//End Retrieve number of records

//Close InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow @41-88D94578
    return $InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow;
}
//End Close InTrTr_detalle_invdetalle_conactivos_gen_TotalRecords_BeforeShow

//InTrTr_detalle_hdMulti_BeforeShow @510-01818252
function InTrTr_detalle_hdMulti_BeforeShow()
{
    $InTrTr_detalle_hdMulti_BeforeShow = true;
//End InTrTr_detalle_hdMulti_BeforeShow

//Custom Code @511-AC9C5C68
// -------------------------
    global $InTrTr_detalle;
    global $DBdatos;
 // -------------------------
//End Custom Code

//Close InTrTr_detalle_hdMulti_BeforeShow @510-E91B8CD4
    return $InTrTr_detalle_hdMulti_BeforeShow;
}
//End Close InTrTr_detalle_hdMulti_BeforeShow

//InTrTr_detalle_lbCosTotal_BeforeShow @514-41CED386
function InTrTr_detalle_lbCosTotal_BeforeShow()
{
    $InTrTr_detalle_lbCosTotal_BeforeShow = true;
//End InTrTr_detalle_lbCosTotal_BeforeShow

//Custom Code @518-AC9C5C68
// -------------------------
    global $InTrTr_detalle;
	global $DBdatos;
	$ilRegNum = CCGetParam("com_RegNumero", -1);
	if ($ilRegNum > 0 ) {
		$ilValor = CCDLookUp("sum(det_Valtotal)", "invdetalle", "det_Regnumero = " . $ilRegNum, $DBdatos);
		$InTrTr_detalle->lbValTotal->SetValue($ilValor);
		$ilCosto = CCDLookUp("sum(det_Costotal)", "invdetalle", "det_Regnumero = " . $ilRegNum, $DBdatos);
		$InTrTr_detalle->lbCosTotal->SetValue($ilCosto);
	}
// -------------------------
//End Custom Code

//Close InTrTr_detalle_lbCosTotal_BeforeShow @514-1F26182D
    return $InTrTr_detalle_lbCosTotal_BeforeShow;
}
//End Close InTrTr_detalle_lbCosTotal_BeforeShow

//DEL  // -------------------------
//DEL  
//DEL  // -------------------------

//InTrTr_detalle_BeforeShow @2-579A7E0D
function InTrTr_detalle_BeforeShow()
{
    $InTrTr_detalle_BeforeShow = true;
//End InTrTr_detalle_BeforeShow

//Custom Code @138-F3D6EABD
// -------------------------
    global $InTrTr_detalle;

	if (!CCGetParam("com_RegNumero")) $InTrTr_detalle->Visible = false;
	else  {
		$slMensaje = "";
		if (!fValidAcceso("","ADD","")) {
		        $slMensaje = "No Puede ";
				$slMensaje .= "AGREGAR ";
				$InTrTr_detalle->EmptyRows = 0;
			}
		if (!fValidAcceso("","UPD","")) {
				$slMensaje .= " MODIFICAR ";
				$InTrTr_detalle->EmptyRows = 0;
			}
		if (!fValidAcceso("","DEL","")) {
				$slMensaje .= " ELIMINAR ";
				$InTrTr_detalle->DeleteAllowed = False;
			}
	//	$slMensaje .= " Detalles de este Comprobante ";
		if (strlen($slMensaje) > 9)		$InTrTr_detalle->lbTitulo->SetValue($slMensaje);
	}
// -------------------------
//End Custom Code

//Close InTrTr_detalle_BeforeShow @2-5AACE994
    return $InTrTr_detalle_BeforeShow;
}
//End Close InTrTr_detalle_BeforeShow

//Page_OnInitializeView @1-493DD3AA
function Page_OnInitializeView()
{
    $Page_OnInitializeView = true;
//End Page_OnInitializeView

//Custom Code @241-F3D6EABD
// -------------------------
    global $InTrTr_Deta;
	if (!setcookie("coSelect", 		"", time()+1800,"/")) {  		//Query aejecutar
//		echo "SE REQUIERE COOKIES HABILITADAS PARA EJECUTAR" ;  			
//		die();
		}
	if (isset ($_COOKIE["coSelect"]))  	$_COOKIE["coSelect"] = ""  ;
		else  setcookie("coSelect", 		"", time()+1800,"/");  			//Query aejecutar
	if (isset ($_COOKIE["coWhere"])) 	$_COOKIE["coWhere"] = "";
	   else	  setcookie("coWhere", 		"", time()+1800,"/");  				//Query aejecutar
	if (isset ($_COOKIE["coOrigen"])) 	$_COOKIE["coOrigen"] = "";
		else  setcookie("coOrigen", 		"", time()+1800,"/");			//Arreglo con los nombres de campos de origen ( en el dataset)
	if (isset ($_COOKIE["coDestino"])) 	$_COOKIE["coDestino"] = "";
	   else   setcookie("coDestino", 		"", time()+1800,"/");			//Arreglo con nombres de los campos de destino
	if (isset ($_COOKIE["coCompon"])) 	$_COOKIE["coCompon"] = "";
	   else   setcookie("coCompon", 		"", time()+1800,"/");				//Nombre del contenedor en la pagina de destino
	if (isset ($_COOKIE["coFocus"])) 	$_COOKIE["coFocus"] = "";
	   else   setcookie("coFocus", 		"", time()+1800,"/");				//Nombre del campo en el que desea tener focus luego de la consulta
    if (isset ($_COOKIE["coSearchPage"])) 	$_COOKIE["coSearchPage"] = "";
		else  setcookie("coSearchPage", 	"", time()+1800,"/");				//Nombre del Pagina de Busqueda
	if (isset ($_COOKIE["coEstado"])) 	$_COOKIE["coEstado"] = "";
		else  setcookie("coEstado", 	"", time()+1800,"/");	
    if (isset ($_COOKIE["coSufijo"])) 	$_COOKIE["coSufijo"] = ""; 
		else  setcookie("coSufijo", 	"", time()+1800,"/");			  
// -------------------------
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

//Custom Code @520-68869D3B
// -------------------------
    global $InTrTr_deta;
	if (!fValidAcceso("","","")) {
    	fMensaje ("UD. NO TIENE ACCESO A ESTE MODULO", 1);
        die(); }// -------------------------
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize

//InTrTr_detalle_AfterSubmit @fah
function InTrTr_detalle_AfterSubmit()
{
    global $InTrTr_detalle;
    global $DBdatos;
    fContabInv($_GET['com_RegNumero']);
    InTrTr_detalle_bitacora();
}
//End Close InTrTr_detalle_AfterSubmit
/**
*   Entrada en la bitacora de seguridad, anotando las condiciones de grabacion
**/
function InTrTr_detalle_bitacora(){
        global $gfValTotal;
        global $gfCosTotal;
        global $gfCanTotal;

    fRegistroBitacora(CCGetParam('pTipoComp', 'XX'), // Tipo Comprobante
                      CCGetParam('pNumComp', '-1'), // Numero Comprobante
                      $SESSION['g_user'], // Usuario
                      $pAnot = "Detalle modificado",
                      $gfCanTotal,
                      $gfValTotal,
                      $gfCosTotal,
                      $pAuto = " " ,
                      $pEsta = 0,
                      $pCodi = 0);

}

?>
