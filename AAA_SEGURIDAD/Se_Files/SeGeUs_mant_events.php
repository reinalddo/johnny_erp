<?php
//BindEvents Method @1-28FFA3C9
function BindEvents()
{
    global $SeGeUs_mant;
    global $SeGeUs_perf;
    $SeGeUs_mant->Button_Insert->CCSEvents["OnClick"] = "SeGeUs_mant_Button_Insert_OnClick";
    $SeGeUs_mant->Button_Update->CCSEvents["OnClick"] = "SeGeUs_mant_Button_Update_OnClick";
    $SeGeUs_mant->Button_Delete->CCSEvents["OnClick"] = "SeGeUs_mant_Button_Delete_OnClick";
    $SeGeUs_mant->CCSEvents["BeforeShow"] = "SeGeUs_mant_BeforeShow";
    $SeGeUs_perf->Button_Submit->CCSEvents["OnClick"] = "SeGeUs_perf_Button_Submit_OnClick";
}
//End BindEvents Method

//SeGeUs_mant_Button_Insert_OnClick @13-9F45909E
function SeGeUs_mant_Button_Insert_OnClick()
{
    $SeGeUs_mant_Button_Insert_OnClick = true;
//End SeGeUs_mant_Button_Insert_OnClick

//Custom Code @88-CC89B50E
// -------------------------
    global $SeGeUs_mant;
	if (!fValidAcceso("","ADD","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        }
// -------------------------
//End Custom Code

//Close SeGeUs_mant_Button_Insert_OnClick @13-F04F4DE2
    return $SeGeUs_mant_Button_Insert_OnClick;
}
//End Close SeGeUs_mant_Button_Insert_OnClick

//SeGeUs_mant_Button_Update_OnClick @14-57F040C2
function SeGeUs_mant_Button_Update_OnClick()
{
    $SeGeUs_mant_Button_Update_OnClick = true;
//End SeGeUs_mant_Button_Update_OnClick

//Custom Code @89-CC89B50E
// -------------------------
    global $SeGeUs_mant;
	if (!fValidAcceso("","UPD","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        }
// -------------------------
//End Custom Code

//Close SeGeUs_mant_Button_Update_OnClick @14-5BD8655F
    return $SeGeUs_mant_Button_Update_OnClick;
}
//End Close SeGeUs_mant_Button_Update_OnClick

//SeGeUs_mant_Button_Delete_OnClick @15-76AD4214
function SeGeUs_mant_Button_Delete_OnClick()
{
    $SeGeUs_mant_Button_Delete_OnClick = true;
//End SeGeUs_mant_Button_Delete_OnClick

//Custom Code @90-CC89B50E
// -------------------------
    global $SeGeUs_mant;
	if (!fValidAcceso("","DEL","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        }
// -------------------------
//End Custom Code

//Close SeGeUs_mant_Button_Delete_OnClick @15-97929F34
    return $SeGeUs_mant_Button_Delete_OnClick;
}
//End Close SeGeUs_mant_Button_Delete_OnClick

//SeGeUs_mant_BeforeShow @12-2BF8FE1E
function SeGeUs_mant_BeforeShow()
{
    $SeGeUs_mant_BeforeShow = true;
//End SeGeUs_mant_BeforeShow

//Custom Code @93-CC89B50E
// -------------------------
    global $SeGeUs_mant;
	global $SeGeUs_perf;
	if ($SeGeUs_mant->EditMode) $SeGeUs_mant->lbTitulo->SetValue("MODIFICACION DE USUARIO");
	else {
		$SeGeUs_mant->lbTitulo->SetValue("AGREGAR UN USUARIO");
		$SeGeUs_perf->Visible = False;
		}
// -------------------------
//End Custom Code

//Close SeGeUs_mant_BeforeShow @12-EE78F9F0
    return $SeGeUs_mant_BeforeShow;
}
//End Close SeGeUs_mant_BeforeShow

//SeGeUs_perf_Button_Submit_OnClick @52-272A449F
function SeGeUs_perf_Button_Submit_OnClick()
{
    $SeGeUs_perf_Button_Submit_OnClick = true;
//End SeGeUs_perf_Button_Submit_OnClick

//Custom Code @91-FF4EBAD0
// -------------------------
    global $SeGeUs_perf;
	if (!fValidAcceso("","UPD","")) {
    	fMensaje ("UD NO PUEDE EJECUTAR ESTA FUNCION", 0);
        }
// -------------------------
//End Custom Code

//Close SeGeUs_perf_Button_Submit_OnClick @52-C9340D59
    return $SeGeUs_perf_Button_Submit_OnClick;
}
//End Close SeGeUs_perf_Button_Submit_OnClick


?>
