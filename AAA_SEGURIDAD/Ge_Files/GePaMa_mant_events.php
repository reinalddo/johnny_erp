<?php
//BindEvents Method @1-B273BBE2
function BindEvents()
{
    global $gecapa_mant;
    global $gecapa_deta;
    global $CCSEvents;
    $gecapa_mant->cat_CodPadre->ds->CCSEvents["BeforeBuildSelect"] = "gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect";
    $gecapa_mant->Button_Insert->CCSEvents["OnClick"] = "gecapa_mant_Button_Insert_OnClick";
    $gecapa_mant->Button_Update->CCSEvents["OnClick"] = "gecapa_mant_Button_Update_OnClick";
    $gecapa_mant->Button_Delete->CCSEvents["OnClick"] = "gecapa_mant_Button_Delete_OnClick";
    $gecapa_mant->CCSEvents["BeforeShow"] = "gecapa_mant_BeforeShow";
    $gecapa_deta->CCSEvents["BeforeShow"] = "gecapa_deta_BeforeShow";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method

//gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect @293-A4029023
function gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect()
{
    $gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect = true;
//End gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect

//Custom Code @408-8D83BE6B
// -------------------------
    global $gecapa_mant;
/**	$liCodPadre = $gecapa_mant->cat_CodPadre->GetValue();
	echo $liCodPadre;
	echo $gecapa_mant->cat_CodPadre->ds->SQL; 
    if (is_null($liCodPadre)) $gecapa_mant->cat_CodPadre->SetValue(0);
	$gecapa_mant->cat_CodPadre->ds->SQL = "Select 0 as par_secuencia, 'INICIO' as par_descripcion " .
											"UNION select par_secuencia, par_descripcion " .
    										"from genparametros ";
    echo $gecapa_mant->cat_CodPadre->ds->SQL; 
**/
// -------------------------
//End Custom Code

//Close gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect @293-E50A2BF3
    return $gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect;
}
//End Close gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect

//gecapa_mant_Button_Insert_OnClick @294-1EB4F989
function gecapa_mant_Button_Insert_OnClick()
{
    $gecapa_mant_Button_Insert_OnClick = true;
//End gecapa_mant_Button_Insert_OnClick

//Custom Code @411-8D83BE6B
// -------------------------
    global $gecapa_mant;
    if (!fValidAcceso("","ADD","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION");
        return false; }
// -------------------------
//End Custom Code

//Close gecapa_mant_Button_Insert_OnClick @294-50A527B8
    return $gecapa_mant_Button_Insert_OnClick;
}
//End Close gecapa_mant_Button_Insert_OnClick

//gecapa_mant_Button_Update_OnClick @295-D60129D5
function gecapa_mant_Button_Update_OnClick()
{
    $gecapa_mant_Button_Update_OnClick = true;
//End gecapa_mant_Button_Update_OnClick

//Custom Code @412-8D83BE6B
// -------------------------
    global $gecapa_mant;
      if (!fValidAcceso("","UPD","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION");
        return false; }
// -------------------------
//End Custom Code

//Close gecapa_mant_Button_Update_OnClick @295-FB320F05
    return $gecapa_mant_Button_Update_OnClick;
}
//End Close gecapa_mant_Button_Update_OnClick

//gecapa_mant_Button_Delete_OnClick @296-F75C2B03
function gecapa_mant_Button_Delete_OnClick()
{
    $gecapa_mant_Button_Delete_OnClick = true;
//End gecapa_mant_Button_Delete_OnClick

//Custom Code @413-8D83BE6B
// -------------------------
    global $gecapa_mant;
	global $gecapa_deta;
	global $DBdatos;
      if (!fValidAcceso("","DEL","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        return false; 
		}
    if ($gecapa_deta->ds->RecordsCount > 0) {
	    fMensaje ("NO PUEDE ELIMINAR UNA CATEGORIA QUE CONTIENE DATOS");
    	return false; 
		}
/*	$slWhere = "par_categoria = " . $gecapa_mant->cat_Codigo->GetValue();
  	if (CCDLookUp("count(*)", "genparametros", $slWhere, $DBdatos ) > 0 ) {
		fMensaje ("NO PUEDE ELIMINAR UNA CATEGORIA QUE CONTIENE DATOS");
    	return false; 
		}
*/
//End Custom Code

//Close gecapa_mant_Button_Delete_OnClick @296-3778F56E
    return $gecapa_mant_Button_Delete_OnClick;
}
//End Close gecapa_mant_Button_Delete_OnClick

//DEL  // -------------------------
//DEL    global $gecapa_mant;
//DEL    if ($gecapa_mant->EditMode)
//DEL    	 $gecapa_mant->cat_CodPadre->SetValue(CCGetFromGet("cat_Codigo","0"));
//DEL    else $gecapa_mant->cat_CodPadre->SetValue(CCGetFromGet("pPadre","0"));
//DEL  // -------------------------

//gecapa_mant_BeforeShow @288-C2B45096
function gecapa_mant_BeforeShow()
{
    $gecapa_mant_BeforeShow = true;
//End gecapa_mant_BeforeShow

//Custom Code @300-8D83BE6B
// ------------------------- Si agrega una categorìa, tomar la categoria padre del QueryString
    global $gecapa_mant;
	
	IF (!$gecapa_mant->EditMode) {
		$liCatPadre = CCGetParam("pPadre", 0);
		if (is_null($liCatPadre) or empty ($liCatPadre)) $liCatPadre = 0;
		$gecapa_mant->lkNuevo->Visible = False;
		$gecapa_mant->cat_CodPadre->SetValue($liCatPadre);
	}
    if (!isset($_GET["pPadre"]) AND !isset($_GET["cat_Codigo"])) $gecapa_mant->Visible = False;
    if (isset($_GET["pPadre"])) {
		$gecapa_mant->tbTitulo1->SetValue("NUEVA CATEGORIA");  } 
    if (isset($_GET["cat_Codigo"])) {
		$gecapa_mant->tbTitulo1->SetValue("MODIFICACION DE CATEGORIA");  } 
// -------------------------
//End Custom Code

//Close gecapa_mant_BeforeShow @288-96845E7F
    return $gecapa_mant_BeforeShow;
}
//End Close gecapa_mant_BeforeShow

//gecapa_deta_BeforeShow @374-D694E95D
function gecapa_deta_BeforeShow()
{
    $gecapa_deta_BeforeShow = true;
//End gecapa_deta_BeforeShow

//Custom Code @419-60571393
// -------------------------
  global $gecapa_deta;
  $iPar = CCGetParam("pParCate",-1);

  if ($gecapa_deta->ds->RecordsCount < 1 )   $gecapa_deta->Visible = false;
  if ($iPar > 0) $gecapa_deta->Visible = true;
// -------------------------
//End Custom Code

//Close gecapa_deta_BeforeShow @374-E27F267D
    return $gecapa_deta_BeforeShow;
}
//End Close gecapa_deta_BeforeShow

//DEL  // -------------------------
//DEL  // -------------------------

//Page_AfterInitialize @1-7910B30B
function Page_AfterInitialize()
{
    $Page_AfterInitialize = true;
//End Page_AfterInitialize

//Custom Code @418-93AB6436
// -------------------------

// -------------------------
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize
?>
