<?php
//BindEvents Method @1-9C766F95
function BindEvents()
{
    global $perfil_mant;
    $perfil_mant->Button_Update->CCSEvents["OnClick"] = "perfil_mant_Button_Update_OnClick";
    $perfil_mant->bt_GenAtrib->CCSEvents["OnClick"] = "perfil_mant_bt_GenAtrib_OnClick";
    $perfil_mant->CCSEvents["BeforeShow"] = "perfil_mant_BeforeShow";
    $perfil_mant->CCSEvents["AfterInsert"] = "perfil_mant_AfterInsert";
}
//End BindEvents Method

//perfil_mant_Button_Update_OnClick @8-41604CE2
function perfil_mant_Button_Update_OnClick()
{
    $perfil_mant_Button_Update_OnClick = true;
//End perfil_mant_Button_Update_OnClick

//Custom Code @9-2E4BE9FE
// -------------------------
    global $perfil_mant;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close perfil_mant_Button_Update_OnClick @8-821EF541
    return $perfil_mant_Button_Update_OnClick;
}
//End Close perfil_mant_Button_Update_OnClick

//perfil_mant_bt_GenAtrib_OnClick @13-25554556
function perfil_mant_bt_GenAtrib_OnClick()
{
    $perfil_mant_bt_GenAtrib_OnClick = true;
//End perfil_mant_bt_GenAtrib_OnClick

//Custom Code @15-2E4BE9FE
// -------------------------
    global $perfil_mant;
    $db = new clsDBseguridad();
	$sql = " INSERT IGNORE into segatributos Select '" . $perfil_mant->pfl_IDperfil->GetValue() . "',  mod_codigo, 0 " . " FROM segmodulos ";
	$db->query($sql);
	$db->Close;
// -------------------------
//End Custom Code

//Close perfil_mant_bt_GenAtrib_OnClick @13-76192C18
    return $perfil_mant_bt_GenAtrib_OnClick;
}
//End Close perfil_mant_bt_GenAtrib_OnClick

//perfil_mant_BeforeShow @2-8168EC77
function perfil_mant_BeforeShow()
{
    $perfil_mant_BeforeShow = true;
//End perfil_mant_BeforeShow

//Custom Code @16-2E4BE9FE
// -------------------------
    global $perfil_mant;
    if ($perfil_mant->EditMode)  $perfil_mant->txt_Titulo->SetValue("MODIFICAR UN PERFIL EXISTENTE"); 
// -------------------------
//End Custom Code

//Close perfil_mant_BeforeShow @2-209E6802
    return $perfil_mant_BeforeShow;
}
//End Close perfil_mant_BeforeShow

//perfil_mant_AfterInsert @2-7DAA9329
function perfil_mant_AfterInsert()
{
    $perfil_mant_AfterInsert = true;
//End perfil_mant_AfterInsert

//Custom Code @19-2E4BE9FE
// -------------------------
    global $perfil_mant;
	perfil_mant_bt_GenAtrib_OnClick();
//End Custom Code

//Close perfil_mant_AfterInsert @2-9783C58B
    return $perfil_mant_AfterInsert;
}
//End Close perfil_mant_AfterInsert

?>
