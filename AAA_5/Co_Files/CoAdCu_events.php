<?php
//BindEvents Method @1-8838D4F2
function BindEvents()
{
    global $CoAdCu_mant;
    $CoAdCu_mant->CCSEvents["BeforeInsert"] = "CoAdCu_mant_BeforeInsert";
}
//End BindEvents Method

//CoAdCu_mant_BeforeInsert @41-26F79CD4
function CoAdCu_mant_BeforeInsert()
{
    $CoAdCu_mant_BeforeInsert = true;
//End CoAdCu_mant_BeforeInsert

//Custom Code @70-2E876210
// -------------------------
    global $CoAdCu_mant;
	if (!$CoAdCu_mant->EditMode) {
		$liPxmoID = 0;
		$liPxmoID = CCDLOOKUP("MAX(cue_CodCuenta)", "concuentas", "" , clsDBdatos);
		if (is_null($liPxoID)) $liPxmoID = 0;
		$liPxmoID += 1;
	 	$CoAdCu->hdCueId->SetValue($liPxmoID );
	}
 // -------------------------
//End Custom Code

//Close CoAdCu_mant_BeforeInsert @41-9A1AC4C9
    return $CoAdCu_mant_BeforeInsert;
}
//End Close CoAdCu_mant_BeforeInsert


?>
