<?php
//BindEvents Method @1-AC61B497
function BindEvents()
{
    global $dosis_mant;
    global $CCSEvents;
    $dosis_mant->CCSEvents["BeforeShow"] = "dosis_mant_BeforeShow";
    $dosis_mant->CCSEvents["OnValidate"] = "dosis_mant_OnValidate";
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
}
//End BindEvents Method

//dosis_mant_BeforeShow @2-5B265A0B
function dosis_mant_BeforeShow()
{
    $dosis_mant_BeforeShow = true;
//End dosis_mant_BeforeShow

//Custom Code @90-8B2441F4
// -------------------------
    global $dosis_mant;
	global $DBdatos;
	$sltext = " ";
	$sltext = CCDLookUp("concat(cte_referencia, ' - ', cte_descripcion)", "liqcomponent", "cte_Codigo = " . CCGetParam("cte_Codigo", "-1"), $DBdatos);
	if (strlen($sltext) > 1 )  {
		$dosis_mant->lbTitulo->SetValue( $sltext);
		}
	else {
	    $ltext ="NO SE HA ESPECIFICADO UN COMPONENTE VALIDO " . CCGetParam("cte_Codigo", "-1");
		fMensaje ($ltext);
    };
//	if (strlen($sltext) > 1 )  {
//		$dosis_mant->Visible = false;
//		}
// -------------------------
//End Custom Code

//Close dosis_mant_BeforeShow @2-F59D6410
    return $dosis_mant_BeforeShow;
}
//End Close dosis_mant_BeforeShow

//dosis_mant_OnValidate @2-BF5AE42A
function dosis_mant_OnValidate()
{
    $dosis_mant_OnValidate = true;
//End dosis_mant_OnValidate

//Custom Code @241-8B2441F4
// -------------------------
    global $dosis_mant;
    global $DBdatos;
/*   --- No se aplica correctamente en grids
	$slWhere = "dos_codComponente = " . CCGetparam("cte_Codigo", -1) . 
				" AND dos_codItem=" . $dosis_mant->dos_codItem->GetValue();

//			   " AND dos_codItem=" . $dosis_mant->ds->ToSQL($dosis_mant->ds->dos_codItem->GetDBValue(), $dosis_mant->ds->dos_codItem->DataType) ;
											  
    if (CCDLookUp("COUNT(*)", "liqdosis", $slWhere, $DBdatos) > 0)     $dosis_mant->dos_codItem->Errors->addError("Cada Item debe constar solo una vez");
*/
// -------------------------
//End Custom Code

//Close dosis_mant_OnValidate @2-CA660099
    return $dosis_mant_OnValidate;
}
//End Close dosis_mant_OnValidate

//Page_OnInitializeView @1-493DD3AA
function Page_OnInitializeView()
{
    $Page_OnInitializeView = true;
//End Page_OnInitializeView

//Custom Code @92-AC13F71A
// -------------------------
    global $LiAdRe;
	
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


?>
