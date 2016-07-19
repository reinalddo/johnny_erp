<?php
//BindEvents Method @1-A5838F90
function BindEvents()
{
    global $autsri_mant;
    $autsri_mant->aut_Usuario->CCSEvents["BeforeShow"] = "autsri_mant_aut_Usuario_BeforeShow";
}
//End BindEvents Method

//autsri_mant_aut_Usuario_BeforeShow @57-06E28EC7
function autsri_mant_aut_Usuario_BeforeShow()
{
    $autsri_mant_aut_Usuario_BeforeShow = true;
//End autsri_mant_aut_Usuario_BeforeShow

//Custom Code @92-9C968DD9
// -------------------------
    global $autsri_mant;
    if (!$autsri_mant->aut_Usuario->GetValue()) $autsri_mant->aut_Usuario->SetValue($_SESSION['g_user']);
//	if (!$autsri_mant->aut_FecRegistro->GetValue()) $autsri_mant->aut_FecRegistro->SetValue(CCFormatDate(time(), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss")));
	if(!is_array($autsri_mant->aut_FecRegistro->Value) && !strlen($autsri_mant->aut_FecRegistro->Value) && $autsri_mant->aut_FecRegistro->Value !== false)
                $autsri_mant->aut_FecRegistro->SetValue(time());
// -------------------------
//End Custom Code

//Close autsri_mant_aut_Usuario_BeforeShow @57-70B14B8B
    return $autsri_mant_aut_Usuario_BeforeShow;
}
//End Close autsri_mant_aut_Usuario_BeforeShow


?>
