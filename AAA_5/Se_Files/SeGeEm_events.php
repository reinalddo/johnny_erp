<?php
//BindEvents Method @1-78B59B7A
function BindEvents()
{
    global $SeGeEm_mant;
    $SeGeEm_mant->Button_Insert->CCSEvents["OnClick"] = "SeGeEm_mant_Button_Insert_OnClick";
    $SeGeEm_mant->Button_Update->CCSEvents["OnClick"] = "SeGeEm_mant_Button_Update_OnClick";
    $SeGeEm_mant->Button_Delete->CCSEvents["OnClick"] = "SeGeEm_mant_Button_Delete_OnClick";
    $SeGeEm_mant->CCSEvents["BeforeShow"] = "SeGeEm_mant_BeforeShow";
}
//End BindEvents Method

//SeGeEm_mant_Button_Insert_OnClick @26-29F74E41
function SeGeEm_mant_Button_Insert_OnClick()
{
    $SeGeEm_mant_Button_Insert_OnClick = true;
//End SeGeEm_mant_Button_Insert_OnClick

//Custom Code @33-7CAB07C1
// -------------------------
    global $SeGeEm_mant;
	if (!fValidAcceso("","ADD","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        }
// -------------------------
//End Custom Code

//Close SeGeEm_mant_Button_Insert_OnClick @26-DFE9A8C1
    return $SeGeEm_mant_Button_Insert_OnClick;
}
//End Close SeGeEm_mant_Button_Insert_OnClick

//SeGeEm_mant_Button_Update_OnClick @27-E1429E1D
function SeGeEm_mant_Button_Update_OnClick()
{
    $SeGeEm_mant_Button_Update_OnClick = true;
//End SeGeEm_mant_Button_Update_OnClick

//Custom Code @34-7CAB07C1
// -------------------------
    global $SeGeEm_mant;
	if (!fValidAcceso("","UPD","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        }
// -------------------------
//End Custom Code

//Close SeGeEm_mant_Button_Update_OnClick @27-747E807C
    return $SeGeEm_mant_Button_Update_OnClick;
}
//End Close SeGeEm_mant_Button_Update_OnClick

//SeGeEm_mant_Button_Delete_OnClick @28-C01F9CCB
function SeGeEm_mant_Button_Delete_OnClick()
{
    $SeGeEm_mant_Button_Delete_OnClick = true;
//End SeGeEm_mant_Button_Delete_OnClick

//Custom Code @35-7CAB07C1
// -------------------------
    global $SeGeEm_mant;
	if (!fValidAcceso("","DEL","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        }
// -------------------------
//End Custom Code

//Close SeGeEm_mant_Button_Delete_OnClick @28-B8347A17
    return $SeGeEm_mant_Button_Delete_OnClick;
}
//End Close SeGeEm_mant_Button_Delete_OnClick

//SeGeEm_mant_BeforeShow @21-3AD4DBE5
function SeGeEm_mant_BeforeShow()
{
    $SeGeEm_mant_BeforeShow = true;
//End SeGeEm_mant_BeforeShow

//Custom Code @39-7CAB07C1
// -------------------------
    global $SeGeEm_mant;
	if (CCGetParam("pOpCode", "") == "") $SeGeEm_mant->Visible = False;
	else {
	 	if (CCGetParam("pOpCode") == "ADD") $SeGeEm_mant->lbTitulo->SetValue("AGREGAR EMPRESA");
		else $SeGeEm_mant->lbTitulo->SetValue("MODIFICAR EMPRESA");
		}
 // -------------------------
//End Custom Code

//Close SeGeEm_mant_BeforeShow @21-89817F49
    return $SeGeEm_mant_BeforeShow;
}
//End Close SeGeEm_mant_BeforeShow

?>
