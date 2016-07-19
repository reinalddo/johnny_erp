<?php
//BindEvents Method @1-652F07DE
function BindEvents()
{
    global $perfil_modu;
    global $perfil_subm;
    $perfil_modu->Button_Submit->CCSEvents["OnClick"] = "perfil_modu_Button_Submit_OnClick";
    $perfil_modu->CCSEvents["BeforeShow"] = "perfil_modu_BeforeShow";
    $perfil_subm->Button_Submit->CCSEvents["OnClick"] = "perfil_subm_Button_Submit_OnClick";
    $perfil_subm->CCSEvents["BeforeShow"] = "perfil_subm_BeforeShow";
}
//End BindEvents Method

//perfil_modu_Button_Submit_OnClick @12-01DE7D0C
function perfil_modu_Button_Submit_OnClick()
{
    $perfil_modu_Button_Submit_OnClick = true;
//End perfil_modu_Button_Submit_OnClick

//Custom Code @13-F2E143FF
// -------------------------
    global $perfil_modu;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close perfil_modu_Button_Submit_OnClick @12-E68C5AEA
    return $perfil_modu_Button_Submit_OnClick;
}
//End Close perfil_modu_Button_Submit_OnClick

//perfil_modu_BeforeShow @3-332EBAA8
function perfil_modu_BeforeShow()
{
    $perfil_modu_BeforeShow = true;
//End perfil_modu_BeforeShow

//Custom Code @14-F2E143FF
// -------------------------
    global $perfil_modu;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close perfil_modu_BeforeShow @3-FA1C02C9
    return $perfil_modu_BeforeShow;
}
//End Close perfil_modu_BeforeShow

//perfil_subm_Button_Submit_OnClick @86-6D36BDAB
function perfil_subm_Button_Submit_OnClick()
{
    $perfil_subm_Button_Submit_OnClick = true;
//End perfil_subm_Button_Submit_OnClick

//Custom Code @87-A34D5D99
// -------------------------
    global $perfil_subm;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close perfil_subm_Button_Submit_OnClick @86-FFEC4FA9
    return $perfil_subm_Button_Submit_OnClick;
}
//End Close perfil_subm_Button_Submit_OnClick

//perfil_subm_BeforeShow @77-D44666D8
function perfil_subm_BeforeShow()
{
    $perfil_subm_BeforeShow = true;
//End perfil_subm_BeforeShow

//Custom Code @88-A34D5D99
// -------------------------
    global $perfil_subm;
	if ($perfil_subm->ds->RecordsCount < 1 ) $perfil_subm->Visible = False;
// -------------------------
//End Custom Code

//Close perfil_subm_BeforeShow @77-C9EC1138
    return $perfil_subm_BeforeShow;
}
//End Close perfil_subm_BeforeShow

?>
