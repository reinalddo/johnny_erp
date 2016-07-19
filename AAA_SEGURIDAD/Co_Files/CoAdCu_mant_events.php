<?php
//BindEvents Method @1-2898ED70
function BindEvents()
{
    global $CoAdCu_mant;
    $CoAdCu_mant->Cue_CodCuenta->CCSEvents["OnValidate"] = "CoAdCu_mant_Cue_CodCuenta_OnValidate";
    $CoAdCu_mant->Cue_CodCuenta->CCSEvents["BeforeShow"] = "CoAdCu_mant_Cue_CodCuenta_BeforeShow";
    $CoAdCu_mant->CCSEvents["BeforeExecuteDelete"] = "CoAdCu_mant_BeforeExecuteDelete";
    $CoAdCu_mant->CCSEvents["BeforeShow"] = "CoAdCu_mant_BeforeShow";
    $CoAdCu_mant->CCSEvents["AfterInsert"] = "CoAdCu_mant_AfterInsert";
}
//End BindEvents Method

//CoAdCu_mant_Cue_CodCuenta_OnValidate @42-6C9CD6B2
function CoAdCu_mant_Cue_CodCuenta_OnValidate()
{
    $CoAdCu_mant_Cue_CodCuenta_OnValidate = true;
//End CoAdCu_mant_Cue_CodCuenta_OnValidate

//Custom Code @169-2E876210
// -------------------------
    global $CoAdCu_mant;
	global $DBdatos;
	$slCodCuenta = $CoAdCu_mant->Cue_CodCuenta->GetValue();
	if (!$CoAdCu_mant->EditMode) {
		$slSql = "SELECT * FROM concuentas WHERE cue_CodCuenta= '" . slCodCuenta . "'";
		$DBdatos->Query($slSql);
		if ($DBdatos->next_record())	fMensaje ("El Código de la Cuenta ya Existe");
	}
	$ilj = strlen($slCodCuenta);

// -------------------------
//End Custom Code

//Close CoAdCu_mant_Cue_CodCuenta_OnValidate @42-A6523FAA
    return $CoAdCu_mant_Cue_CodCuenta_OnValidate;
}
//End Close CoAdCu_mant_Cue_CodCuenta_OnValidate

//CoAdCu_mant_Cue_CodCuenta_BeforeShow @42-F791D67F
function CoAdCu_mant_Cue_CodCuenta_BeforeShow()
{
    $CoAdCu_mant_Cue_CodCuenta_BeforeShow = true;
//End CoAdCu_mant_Cue_CodCuenta_BeforeShow

//Custom Code @174-2E876210
// -------------------------
   global $CoAdCu_mant;
	if ($CoAdCu_mant->EditMode)	{
		$CoAdCu_mant->Cue_CodCuenta->Visible = False; }
 /*	else {
		$CoAdCu_mant->lbCodCuenta->Visible = False; }
*/// -------------------------
//End Custom Code

//Close CoAdCu_mant_Cue_CodCuenta_BeforeShow @42-99A95B23
    return $CoAdCu_mant_Cue_CodCuenta_BeforeShow;
}
//End Close CoAdCu_mant_Cue_CodCuenta_BeforeShow

//CoAdCu_mant_BeforeShow @41-408751EB
function CoAdCu_mant_BeforeShow()
{
    $CoAdCu_mant_BeforeShow = true;
//End CoAdCu_mant_BeforeShow

//Custom Code @173-2E876210
// -------------------------
    global $CoAdCu_mant;
	if ($CoAdCu_mant->EditMode) $CoAdCu_mant->lbTitulo->SetValue("MODIFICAR CUENTA");

// -------------------------
//End Custom Code

//Close CoAdCu_mant_BeforeShow @41-682EF2FF
    return $CoAdCu_mant_BeforeShow;
}

/*
*   Proceso para definir la estructura de una nueva cuenta
*/
function CoAdCu_mant_AfterInsert()
{ 
    global $CoAdCu_mant;
    $ignivel  = 0;
    $p_mode   = "R";
    $igCuenta = $CoAdCu_mant->hdCueId->GetValue();
    if (!igCuenta){
        $slSql="cue_codcuenta = '" . $CoAdCu_mant->Cue_CodCuenta->GetValue() . "'";
        $igCuenta=CCDLookUp("cue_id", "concuentas", $slSql, $DBdatos);
    }
    $igPosic  = 0;
    fGeneraEstructura($igCuenta,"n", false); // Define el verdadero nivel de la cuenta
    fGeneraEstructura($igCuenta,"R", false);
//    fRegistroBitacora("CUE", $igCuenta, 'fah', $CoAdCu_mant->Cue_CodCuenta->GetValue()); @todo
 }
/*
*   Proceso para Evitar la eliminacion de cuentas con movimiento.
*/
function CoAdCu_mant_BeforeExecuteDelete()
{
    global $CoAdCu_mant;
    global $DBdatos;
    $slSql="det_codcuenta = '" . $CoAdCu_mant->Cue_CodCuenta->GetValue() . "'";
    $ilNum=NZ(CCDLookUp("count(*)", "condetalle", $slSql, $DBdatos),0);
    $slMen=$ilNum ? "NO PUEDE ELIMINAR UNA CUENTA CON MOVIMIENTOS ! ! ! !" : " CUENTA '" . $CoAdCu_mant->Cue_CodCuenta->GetValue() . "' ELIMINADA";
    $CoAdCu_mant->Errors->AddError($slMen);
    if ($ilNum >0 )return false;
    return true;
 }
?>
