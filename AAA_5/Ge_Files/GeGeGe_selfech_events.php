<?php
//BindEvents Method @1-F589A071
function BindEvents()
{
    global $Form_1;
    $Form_1->CCSEvents["BeforeShow"] = "Form_1_BeforeShow";
}
//End BindEvents Method

//Form_1_BeforeShow @2-172ABAB9
function Form_1_BeforeShow()
{
    $Form_1_BeforeShow = true;
//End Form_1_BeforeShow

//Custom Code @18-48257B49
// -------------------------
    global $Form_1;
	global $Tpl;
	$Tpl->Setvar("pTitul",CCGetParam("pTitul",'DEFINA PARAMETROS'));
	$slTipo = CCGetParam("pTipo",'F');
	switch($slTipo) {
		case 'F': //						Se requiere una fecha
			$Form_1->DatePicker2->Visible = false;
			$Form_1->txt_Hasta->Visible = false;
			$Form_1->sel_Hasta->Visible = false;
			$Form_1->sel_Desde->Visible = false;
			break;
		case 'FF': //						Se requiere dos fechas fecha
			$Form_1->sel_Hasta->Visible = false;
			$Form_1->sel_Desde->Visible = false;
			break;
		case 'P': //						Se requiere un periodo
			$Form_1->DatePicker1->Visible = false;
			$Form_1->DatePicker2->Visible = false;
			$Form_1->txt_Desde->Visible = false;
			$Form_1->txt_Hasta->Visible = false;
			$Form_1->sel_Hasta->Visible = false;
			break;
		case 'PP': //						Se requiere dos periodos
			$Form_1->DatePicker1->Visible = false;
			$Form_1->DatePicker2->Visible = false;
			$Form_1->txt_Desde->Visible = false;
			$Form_1->txt_Hasta->Visible = false;
			break;
	}
// -------------------------
//End Custom Code

//Close Form_1_BeforeShow @2-692D3D33
    return $Form_1_BeforeShow;
}
//End Close Form_1_BeforeShow


?>
