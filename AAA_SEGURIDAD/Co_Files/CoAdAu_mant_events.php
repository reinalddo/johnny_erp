<?php
/**
*    Eventos de servidor para CoAdAu_mant
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdAu
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*    @see	 CoAdAu_mant.php
*/
//BindEvents Method @1-A75CF7F9
function BindEvents()
{
    global $CoAdAu_mant;
    global $CoAdAu_cate;
    $CoAdAu_mant->lbTitulo->CCSEvents["BeforeShow"] = "CoAdAu_mant_lbTitulo_BeforeShow";
    $CoAdAu_mant->Button_Insert->CCSEvents["OnClick"] = "CoAdAu_mant_Button_Insert_OnClick";
    $CoAdAu_mant->Button_Update->CCSEvents["OnClick"] = "CoAdAu_mant_Button_Update_OnClick";
    $CoAdAu_mant->Button_Delete->CCSEvents["OnClick"] = "CoAdAu_mant_Button_Delete_OnClick";
    $CoAdAu_mant->CCSEvents["BeforeInsert"] = "CoAdAu_mant_BeforeInsert";
    $CoAdAu_mant->CCSEvents["BeforeShow"] = "CoAdAu_mant_BeforeShow";
    $CoAdAu_mant->CCSEvents["AfterInsert"] = "CoAdAu_mant_AfterInsert";
    $CoAdAu_mant->CCSEvents["AfterDelete"] = "CoAdAu_mant_AfterDelete";
    $CoAdAu_cate->CCSEvents["BeforeShow"] = "CoAdAu_cate_BeforeShow";
}
//End BindEvents Method

//CoAdAu_mant_lbTitulo_BeforeShow @331-A24FA689
function CoAdAu_mant_lbTitulo_BeforeShow()
{
    $CoAdAu_mant_lbTitulo_BeforeShow = true;
//End CoAdAu_mant_lbTitulo_BeforeShow

//Custom Code @332-BB097D36
// -------------------------
    global $CoAdAu_mant;
	if  (!$CoAdAu_mant->EditMode) $CoAdAu_mant->lbTitulo->SetValue("INGRESO DE NUEVO AUXILIAR");
	else {
		$CoAdAu_mant->lbTitulo->SetValue("MODIFICACION DE AUXILIAR");
		$CoAdAu_mant->lbCategAux->Visible = false;
		}
// -------------------------
//End Custom Code

//Close CoAdAu_mant_lbTitulo_BeforeShow @331-306FE9D7
    return $CoAdAu_mant_lbTitulo_BeforeShow;
}
//End Close CoAdAu_mant_lbTitulo_BeforeShow

//CoAdAu_mant_Button_Insert_OnClick @257-8E987650
function CoAdAu_mant_Button_Insert_OnClick()
{
    $CoAdAu_mant_Button_Insert_OnClick = true;
//End CoAdAu_mant_Button_Insert_OnClick

//Custom Code @258-BB097D36
// -------------------------
    global $CoAdAu_mant;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close CoAdAu_mant_Button_Insert_OnClick @257-128427D0
    return $CoAdAu_mant_Button_Insert_OnClick;
}
//End Close CoAdAu_mant_Button_Insert_OnClick

//CoAdAu_mant_Button_Update_OnClick @259-462DA60C
function CoAdAu_mant_Button_Update_OnClick()
{
    $CoAdAu_mant_Button_Update_OnClick = true;
//End CoAdAu_mant_Button_Update_OnClick

//Custom Code @260-BB097D36
// -------------------------
    global $CoAdAu_mant;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close CoAdAu_mant_Button_Update_OnClick @259-B9130F6D
    return $CoAdAu_mant_Button_Update_OnClick;
}
//End Close CoAdAu_mant_Button_Update_OnClick

//CoAdAu_mant_Button_Delete_OnClick @261-6770A4DA
function CoAdAu_mant_Button_Delete_OnClick()
{
    $CoAdAu_mant_Button_Delete_OnClick = true;
//End CoAdAu_mant_Button_Delete_OnClick

//Custom Code @263-BB097D36
// -------------------------
    global $CoAdAu_mant;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close CoAdAu_mant_Button_Delete_OnClick @261-7559F506
    return $CoAdAu_mant_Button_Delete_OnClick;
}
//End Close CoAdAu_mant_Button_Delete_OnClick

//CoAdAu_mant_BeforeInsert @212-30F684D7
function CoAdAu_mant_BeforeInsert()
{
    $CoAdAu_mant_BeforeInsert = true;
//End CoAdAu_mant_BeforeInsert

//Custom Code @265-BB097D36
// -------------------------
   global $CoAdAu_mant;
//   include_once (RelativePath . "/LibPhp/SegLib.php") ;
	if (!fValidAcceso("","","ADD")) {
    	fMensaje ("UD NO TIENE PERMISO PARA ESTA FUNCION", 1);
        die(); }
	if (!$CoAdAu_mant->EditMode && !$CoAdAu_mant->lbCategAux->GetValue()) {
		$CoAdAu_mant_BeforeInsert = false;
		fMensaje ("ESPECIFIQUE LA CATEGORIA INICIAL", 1);
		}
	else	{ 
		$ilNvoAux =fPxmoAuxiliar($CoAdAu_mant->lbCategAux->GetValue(), "conpersonas", "per_CodAuxiliar");
		$CoAdAu_mant->per_CodAuxiliar->SetValue($ilNvoAux);
		}

// -------------------------
//End Custom Code

//Close CoAdAu_mant_BeforeInsert @212-B44A0D49
    return $CoAdAu_mant_BeforeInsert;
}
//End Close CoAdAu_mant_BeforeInsert

//CoAdAu_mant_BeforeShow @212-4AAFA847
function CoAdAu_mant_BeforeShow()
{
    $CoAdAu_mant_BeforeShow = true;
//End CoAdAu_mant_BeforeShow

//Custom Code @266-BB097D36
// -------------------------
    global $CoAdAu_mant;
	global $CoAdAu_cate;
	global $lbCategAux;
    
// -------------------------
//End Custom Code

//Close CoAdAu_mant_BeforeShow @212-A2E08473
    return $CoAdAu_mant_BeforeShow;
}
//End Close CoAdAu_mant_BeforeShow

//CoAdAu_mant_AfterInsert @212-98978F03
function CoAdAu_mant_AfterInsert()
{
    $CoAdAu_mant_AfterInsert = true;
//End CoAdAu_mant_AfterInsert

//Custom Code @267-BB097D36
// -------------------------
    global $CoAdAu_mant;
	if (!$CoAdAu_mant->EditMode) {
        $clDB = New clsDBdatos;
		$tlSql ="INSERT IGNORE INTO concategorias VALUES ( " . $CoAdAu_mant->lbCategAux->Value . " , " .
				$CoAdAu_mant->per_CodAuxiliar->Value  . ", '" . date("Y-m-d"). "', 1)";
		$clDB->Query($tlSql);
		unset($clDB);
		}
// -------------------------
//End Custom Code

//Close CoAdAu_mant_AfterInsert @212-B003FACD
    return $CoAdAu_mant_AfterInsert;
}
//End Close CoAdAu_mant_AfterInsert

//CoAdAu_mant_AfterDelete @212-FFBF69FD
function CoAdAu_mant_AfterDelete()
{
    $CoAdAu_mant_AfterDelete = true;
//End CoAdAu_mant_AfterDelete

//Custom Code @325-BB097D36
// -------------------------
    global $CoAdAu_mant;
    $clDB = New clsDBdatos;
	$tlSql ="DELETE FROM concategorias WHERE cat_codauxiliar = " . $CoAdAu_mant->per_CodAuxiliar->GetValue();
	$clDB->Query($tlSql);
	unset($clDB);
// -------------------------
//End Custom Code

//Close CoAdAu_mant_AfterDelete @212-E30E9D33
    return $CoAdAu_mant_AfterDelete;
}
//End Close CoAdAu_mant_AfterDelete

//CoAdAu_cate_BeforeShow @334-3BC41474
function CoAdAu_cate_BeforeShow()
{
    $CoAdAu_cate_BeforeShow = true;
//End CoAdAu_cate_BeforeShow

//Custom Code @352-C2039913
// -------------------------
    global $CoAdAu_cate;
	if (!(CCGetParam("per_CodAuxiliar"))) { 
		$CoAdAu_cate->Visible = False;
		}// -------------------------
//End Custom Code

//Close CoAdAu_cate_BeforeShow @334-3B1443A6
    return $CoAdAu_cate_BeforeShow;
}
//End Close CoAdAu_cate_BeforeShow

?>
