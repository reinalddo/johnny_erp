<?php
//BindEvents Method @1-D4C34E3E
function BindEvents()
{
 global $liqreportes;
 global $CCSEvents;
 $liqreportes->liqreportes_TotalRecords->CCSEvents["BeforeShow"] = "liqreportes_liqreportes_TotalRecords_BeforeShow";
 $liqreportes->CCSEvents["BeforeShow"] = "liqreportes_BeforeShow";
 $liqreportes->ds->CCSEvents["BeforeExecuteInsert"] = "liqreportes_ds_BeforeExecuteInsert";
 $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//liqreportes_liqreportes_TotalRecords_BeforeShow @12-D5F59096
function liqreportes_liqreportes_TotalRecords_BeforeShow()
{
 $liqreportes_liqreportes_TotalRecords_BeforeShow = true;
//End liqreportes_liqreportes_TotalRecords_BeforeShow

//Retrieve number of records @13-3B05E70A
 global $liqreportes;
 $liqreportes->liqreportes_TotalRecords->SetValue($liqreportes->ds->RecordsCount);
//End Retrieve number of records

//Close liqreportes_liqreportes_TotalRecords_BeforeShow @12-133104ED
 return $liqreportes_liqreportes_TotalRecords_BeforeShow;
}
//End Close liqreportes_liqreportes_TotalRecords_BeforeShow

//liqreportes_BeforeShow @7-41FC6506
function liqreportes_BeforeShow()
{
 $liqreportes_BeforeShow = true;
//End liqreportes_BeforeShow

//Custom Code @71-76DABA51
// ------------------------- 					Asegurarse que se agregue registros solo al final
 global $liqreportes;
 		$EmptyRowsLeft = 0;
        if ($liqreportes->ds->AbsolutePage < $liqreportes->ds->PageCount()) $EmptyRowsLeft = 0; // fah_mod: para hacer que se agregue records solo al final
        else {
            if ($liqreportes->ds->RecordsCount > $liqreportes->PageSize) $resto = fmod($liqreportes->ds->RecordsCount, $liqreportes->PageSize);
            else $resto = $liqreportes->ds->RecordsCount;
            $EmptyRowsLeft = $liqreportes->PageSize - $resto;
        }
        if ($EmptyRowsLeft <= 0 && $liqreportes->ds->RecordsCount == ($liqreportes->PageSize * $liqreportes->ds->AbsolutePage)) $EmptyRowsLeft =1;// end fah mod
		$liqreportes->EmptyRows=$EmptyRowsLeft;
// -------------------------
//End Custom Code

//Close liqreportes_BeforeShow @7-21B25BCC
 return $liqreportes_BeforeShow;
}
//End Close liqreportes_BeforeShow

//liqreportes_ds_BeforeExecuteInsert @7-CE6E7C45
function liqreportes_ds_BeforeExecuteInsert()
{
 $liqreportes_ds_BeforeExecuteInsert = true;
//End liqreportes_ds_BeforeExecuteInsert

//Custom Code @72-76DABA51
// -------------------------
 global $liqreportes;
 // Write your own code here.
// -------------------------
//End Custom Code

//Close liqreportes_ds_BeforeExecuteInsert @7-5BC77209
 return $liqreportes_ds_BeforeExecuteInsert;
}
//End Close liqreportes_ds_BeforeExecuteInsert

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
 $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @69-B81B16B9
// -------------------------
 global $LiLiRp_confdet;
 	$ilNivel = CCGetParam("rep_Nivel", 0);
	switch  ($ilNivel) {
		case 1:
			$texto = "DETALLE PARA REPORTE ";
		case 1:
					$texto = "DETALLE PARA REPORTE ";
		case 2:
					$texto = "DETALLE PARA GRUPO ";
		case 3:
					$texto = "DETALLE PARA COLUMNA ";
		default:
					$texto = " -- ";
	}
	echo $texto;
	$LiLiRp_confdet->lbTitulo->SetValue=($texto);
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
 return $Page_BeforeShow;
}
//End Close Page_BeforeShow


?>
