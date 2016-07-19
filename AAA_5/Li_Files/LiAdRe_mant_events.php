<?php
//BindEvents Method @1-908A10A8
function BindEvents()
{
    global $dosis_mant;
    global $CCSEvents;
    $dosis_mant->ds->CCSEvents["BeforeExecuteInsert"] = "dosis_mant_ds_BeforeExecuteInsert";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method

//dosis_mant_ds_BeforeExecuteInsert @121-56B17BC4
function dosis_mant_ds_BeforeExecuteInsert()
{
    $dosis_mant_ds_BeforeExecuteInsert = true;
//End dosis_mant_ds_BeforeExecuteInsert

//Custom Code @283-8B2441F4
// -------------------------
    global $dosis_mant;
	$dosis_mant->ds->SQL .= " ON DUPLICATE KEY UPDATE dos_cantidad = VALUES(dos_cantidad) ";
// -------------------------
//End Custom Code

//Close dosis_mant_ds_BeforeExecuteInsert @121-4E5EFDD6
    return $dosis_mant_ds_BeforeExecuteInsert;
}
//End Close dosis_mant_ds_BeforeExecuteInsert

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @279-913DB462
// -------------------------
    global $LiAdRe_mant;
    global $dosis_mant;
	global $DBdatos;
	$sltext = " ";
	$sltext = CCDLookUp("concat(cte_referencia, ' - ', cte_descripcion)", "liqcomponent", "cte_Codigo = " . CCGetParam("dos_CodComponente", "-1"), $DBdatos);
	if (strlen($sltext) > 1 )  {
		$dosis_mant->lbTitulo->SetValue( $sltext);
		}
	else {
	    $ltext ="NO SE HA ESPECIFICADO UN COMPONENTE VALIDO " . CCGetParam("cte_Codigo", "-1");
		fMensaje ($ltext);
    };// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

//Page_OnInitializeView @1-493DD3AA
function Page_OnInitializeView()
{
    $Page_OnInitializeView = true;
//End Page_OnInitializeView

//Custom Code @280-913DB462
// -------------------------
    global $LiAdRe_mant;
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

//Custom Code @281-913DB462
// -------------------------
    global $LiAdRe_mant;
   	include_once (RelativePath . "/LibPhp/SegLib.php") ;
	if (!fValidAcceso("","","")) {
    	fMensaje ("UD. NO TIENE ACCESO A ESTE MODULO", 1);
        die();
		}
// -------------------------
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize


?>
