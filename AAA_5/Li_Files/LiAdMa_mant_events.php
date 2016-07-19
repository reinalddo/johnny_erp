<?php
//BindEvents Method @1-E862F58B
function BindEvents()
{
    global $genparametros;
    $genparametros->Button_Insert->CCSEvents["OnClick"] = "genparametros_Button_Insert_OnClick";
    $genparametros->CCSEvents["BeforeShow"] = "genparametros_BeforeShow";
    $genparametros->CCSEvents["BeforeInsert"] = "genparametros_BeforeInsert";
    $genparametros->ds->CCSEvents["AfterExecuteInsert"] = "genparametros_ds_AfterExecuteInsert";
}
//End BindEvents Method

//genparametros_Button_Insert_OnClick @3-0283448E
function genparametros_Button_Insert_OnClick()
{
    $genparametros_Button_Insert_OnClick = true;
//End genparametros_Button_Insert_OnClick

//Custom Code @29-F5FFE3C4
// -------------------------
/*    global $genparametros;
	$ilSecuencia = CCDLookUp("max(par_secuencia) + 1","genparametros","par_Clave='IMARCA'", $DBDatos);
	$genparametros->par_Secuencia->SetValue($ilSecuencia);
*/
// -------------------------
//End Custom Code

//Close genparametros_Button_Insert_OnClick @3-AD0E0E33
    return $genparametros_Button_Insert_OnClick;
}
//End Close genparametros_Button_Insert_OnClick

//genparametros_BeforeShow @2-CD9D1845
function genparametros_BeforeShow()
{
    $genparametros_BeforeShow = true;
//End genparametros_BeforeShow

//Custom Code @26-F5FFE3C4
// -------------------------
    global $genparametros;
	if ($genparametros->EditMode) $genparametros->lbTitulo->SetValue("MODIFICACION DE MARCAS");
// -------------------------
//End Custom Code

//Close genparametros_BeforeShow @2-CE7767EC
    return $genparametros_BeforeShow;
}
//End Close genparametros_BeforeShow

//genparametros_BeforeInsert @2-B5CB1023
function genparametros_BeforeInsert()
{
    $genparametros_BeforeInsert = true;
//End genparametros_BeforeInsert

//Custom Code @30-F5FFE3C4
// -------------------------
    global $genparametros;
	global $DBdatos;
	$ilSecuencia = CCDLookUp("max(par_secuencia) + 1","genparametros","par_Categoria = 30 AND par_Clave='IMARCA' AND par_Secuencia < 9900", $DBdatos);
	$genparametros->par_Secuencia->SetValue($ilSecuencia);
// -------------------------
//End Custom Code

//Close genparametros_BeforeInsert @2-7B9044C9
    return $genparametros_BeforeInsert;
}
//End Close genparametros_BeforeInsert

//genparametros_ds_AfterExecuteInsert @2-5DC506D4
function genparametros_ds_AfterExecuteInsert()
{
    $genparametros_ds_AfterExecuteInsert = true;
//End genparametros_ds_AfterExecuteInsert

//Custom Code @43-F5FFE3C4
// -------------------------
    global $genparametros;
	$genparametros->Errors->AddError("Marca Agregada Exitosamente" );
// -------------------------
//End Custom Code

//Close genparametros_ds_AfterExecuteInsert @2-101226C8
    return $genparametros_ds_AfterExecuteInsert;
}
//End Close genparametros_ds_AfterExecuteInsert


?>
