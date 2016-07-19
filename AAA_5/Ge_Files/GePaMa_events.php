<?php
//BindEvents Method @1-AAD62E7D
function BindEvents()
{
    global $gecapa_mant;
    global $CCSEvents;
    $gecapa_mant->cat_CodPadre->ds->CCSEvents["BeforeBuildSelect"] = "gecapa_mant_cat_CodPadre_ds_BeforeBuildSelect";
    $gecapa_mant->Button_Insert->CCSEvents["OnClick"] = "gecapa_mant_Button_Insert_OnClick";
    $gecapa_mant->Button_Update->CCSEvents["OnClick"] = "gecapa_mant_Button_Update_OnClick";
    $gecapa_mant->Button_Delete->CCSEvents["OnClick"] = "gecapa_mant_Button_Delete_OnClick";
    $gecapa_mant->CCSEvents["BeforeShow"] = "gecapa_mant_BeforeShow";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
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
      if (!fValidAcceso("","DEL","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        return false; }
		$slWhere = "par_categoria = " . $gecapa_mant->cat_Codigo->Value();
      	if (CCLookup("count(*)", "genparametros", $lsWhere, DBdatos ) > 0 ) {
    		fMensaje ("NO PUEDE ELIMINAR UNA CATEGORIA QUE CONTIENE DATOS");
        	return false; }

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
		$gecapa_mant->cat_CodPadre->SetValue($liCatPadre);
	}
    if (!isset($_GET["pPadre"]) AND !isset($_GET["cat_Codigo"])) $gecapa_mant->Visible = False;
    if (isset($_GET["pPadre"])) {
		$gecapa_mant->tbTitulo1->SetValue("Agregar Nueva Categoria");  } 
    if (isset($_GET["cat_Codigo"])) {
		$gecapa_mant->tbTitulo1->SetValue("Modificar Categoria");  } 
// -------------------------
//End Custom Code

//Close gecapa_mant_BeforeShow @288-96845E7F
    return $gecapa_mant_BeforeShow;
}
//End Close gecapa_mant_BeforeShow

//DEL  // -------------------------
//DEL      global $gecapa_mant;
//DEL  	$liCatPadre = $gecapa_mant->cat_CodPadre->GetValue();
//DEL  	if (!isset($liCatPadre) or    is_null($liCatPadre) or empty ($liCatPadre)) $gecapa_mant->cat_CodPadre->SetValue(1);
//DEL      fMensaje (" VALOR", $liCatPadre);
//DEL  	
//DEL  // -------------------------

//DEL  // -------------------------
//DEL    global $gecapa_det;
//DEL    echo $gecapa_deta->ds->RecordsCount ;
//DEL    if ($gecapa_deta->ds->RecordsCount < 1) {
//DEL       $gecapa_deta->Visible = False;
//DEL  	 $gecapa_deta->EditAllowed = False;
//DEL  	 $gecapa_deta->InsertAllowed = False;
//DEL  	 $gecapa_deta->DeleteAllowed = False; }
//DEL  // -------------------------

//DEL  // -------------------------
//DEL    global $gecapa_deta;
//DEL  //  if ($gecapa_deta->ds->RecordsCount < 1) $gecapa_deta->Visible = False;
//DEL  // -------------------------

//DEL  global $form_categories; 	
//DEL    
//DEL    //Set the value of the hidden category_id_parent field to the value of the URL parameter sent by the "Edit" link.
//DEL   /* if(!CCGetFromGet("pPadre","0")) {
//DEL      $form_categories->category_id_parent->SetValue(CCGetFromGet("pPadre","0"));
//DEL    } else {
//DEL      $form_categories->category_id_parent->SetValue(CCGetFromGet("cat_CodigoPadre","0"));
//DEL    }
//DEL  */
//DEL    $form_categories->category_id_parent->SetValue(CCGetFromGet("pPadre","0"));

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @86-5A56B9E8
// -------------------------
  global $DirectoryMaintenance;
  $iRoot = CCGetFromGEt("edit_id",-1);
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow
?>
