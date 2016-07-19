<?php
//BindEvents Method @1-37B210B0
/**
*    Eventos de servidor para CoAdAc_mant.php. 
*    @abstract   Se habilita o inhabilita, para cada usuario, los comandos de
*		 grabacion / modificacion /Eliminacion de acuerdo a sus perfiles.
*    Mantenimiento de  la tabla de Auxiliares "no-personas", es decir Activos y conceptos basicos.
*    @abstract   Todos los auxiliares que no correspondan a nu concepto de persona natural o juridica,
*		 deben tener un registro en la tabla conactivos, de cuyo mantenimiento se encarga este script.
*		 La plantilla de captura es generica, cierta informacion debe ajustarse al contexto de
*		 la naturaleza del auxiliar.
*    		 Generado por CCS
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdAc
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*/
//
function BindEvents()
{
    global $CoAdAc_mant;
    $CoAdAc_mant->Button_Insert->CCSEvents["OnClick"] = "CoAdAc_mant_Button_Insert_OnClick";
    $CoAdAc_mant->btNuevo->CCSEvents["BeforeShow"] = "CoAdAc_mant_btNuevo_BeforeShow";
    $CoAdAc_mant->CCSEvents["BeforeShow"] = "CoAdAc_mant_BeforeShow";
    $CoAdAc_mant->CCSEvents["BeforeInsert"] = "CoAdAc_mant_BeforeInsert";
    $CoAdAc_mant->CCSEvents["AfterInsert"] = "CoAdAc_mant_AfterInsert";
}
//End BindEvents Method

//CoAdAc_mant_Button_Insert_OnClick @115-0FD395FE
function CoAdAc_mant_Button_Insert_OnClick()
{
    $CoAdAc_mant_Button_Insert_OnClick = true;
//End CoAdAc_mant_Button_Insert_OnClick

//Custom Code @175-B92BB06F
// -------------------------
    global $CoAdAc_mant;
// -------------------------
//End Custom Code

//Close CoAdAc_mant_Button_Insert_OnClick @115-EB2A51E2
    return $CoAdAc_mant_Button_Insert_OnClick;
}
//End Close CoAdAc_mant_Button_Insert_OnClick

//CoAdAc_mant_btNuevo_BeforeShow @351-E1CE7921
function CoAdAc_mant_btNuevo_BeforeShow()
{
    $CoAdAc_mant_btNuevo_BeforeShow = true;
//End CoAdAc_mant_btNuevo_BeforeShow

//Custom Code @352-B92BB06F
// -------------------------
    global $CoAdAc_mant;
   $CoAdAc_mant->btNuevo->Visible = $CoAdAc_mant->EditMode;
// -------------------------
//End Custom Code

//Close CoAdAc_mant_btNuevo_BeforeShow @351-4B72183F
    return $CoAdAc_mant_btNuevo_BeforeShow;
}
//End Close CoAdAc_mant_btNuevo_BeforeShow

//CoAdAc_mant_BeforeShow @99-AB9A6EE4
function CoAdAc_mant_BeforeShow()
{
    $CoAdAc_mant_BeforeShow = true;
//End CoAdAc_mant_BeforeShow

//Custom Code @118-B92BB06F
// -------------------------
    global $CoAdAc_mant;
	global $CoAdAc_cate;

	if (!$CoAdAc_mant->EditMode) {
		$CoAdAc_mant->lbTitulo->SetValue("AGREGAR AUXILIAR NUEVO");
		$CoAdAc_cate->Visible = False;
		}
	else {
			$CoAdAc_mant->lbCategAux->Visible = false;
		}

	// -------------------------
//End Custom Code

//Close CoAdAc_mant_BeforeShow @99-2C7EB46A
    return $CoAdAc_mant_BeforeShow;
}
//End Close CoAdAc_mant_BeforeShow

//CoAdAc_mant_BeforeInsert @99-5ED775CC
function CoAdAc_mant_BeforeInsert()
{
    $CoAdAc_mant_BeforeInsert = true;
//End CoAdAc_mant_BeforeInsert

//Custom Code @177-B92BB06F
// -------------------------
    global $CoAdAc_mant;
	if (!fValidAcceso("","","ADD")) {
    	fMensaje ("UD NO TIENE ACCESO A ESTA FUNCION", 1);
        die(); }
   $ilNvoAux =fPxmoAuxiliar($CoAdAc_mant->lbCategAux->GetValue(), "conactivos", "act_CodAuxiliar");
    if ($ilNvoAux < 0 ) {
	    $CoAdAc_mant->act_CodAuxiliar->Errors->addError("Secuencia de la Categoria Agotada");
	    return false;
	}
    $CoAdAc_mant->act_CodAuxiliar->SetValue($ilNvoAux);
// -------------------------
//End Custom Code

//Close CoAdAc_mant_BeforeInsert @99-09931A63
    return $CoAdAc_mant_BeforeInsert;
}
//End Close CoAdAc_mant_BeforeInsert

//CoAdAc_mant_AfterInsert @99-9320803C
function CoAdAc_mant_AfterInsert()
{
    $CoAdAc_mant_AfterInsert = true;
//End CoAdAc_mant_AfterInsert

//Custom Code @185-B92BB06F
// -------------------------
    global $CoAdAc_mant;
	if (!$CoAdAc_mant->EditMode) {
        $clDB = New clsDBdatos;
		$tlSql ="INSERT IGNORE INTO concategorias VALUES ( " . $CoAdAc_mant->lbCategAux->Value . " , " .
				$CoAdAc_mant->act_CodAuxiliar->Value  . ", '" . date("Y-m-d"). "', 1)";
		$clDB->Query($tlSql);
		}// -------------------------
//End Custom Code

//Close CoAdAc_mant_AfterInsert @99-D4E6CC3D
    return $CoAdAc_mant_AfterInsert;
}
//End Close CoAdAc_mant_AfterInsert

?>
