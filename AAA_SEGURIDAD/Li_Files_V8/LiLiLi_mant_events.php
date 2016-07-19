<?php
//BindEvents Method @1-5C34B8BC
function BindEvents()
{
    global $liqcabece;
    global $liqdetalle;
    global $CCSEvents;
    $liqcabece->lbl_NumLiquida->CCSEvents["BeforeShow"] = "liqcabece_lbl_NumLiquida_BeforeShow";
    $liqdetalle->tmp_Total->CCSEvents["BeforeShow"] = "liqdetalle_tmp_Total_BeforeShow";
    $liqdetalle->Button_Delete->CCSEvents["OnClick"] = "liqdetalle_Button_Delete_OnClick";
    $liqdetalle->ds->CCSEvents["BeforeExecuteUpdate"] = "liqdetalle_ds_BeforeExecuteUpdate";
    $liqdetalle->CCSEvents["BeforeShow"] = "liqdetalle_BeforeShow";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method

//liqcabece_lbl_NumLiquida_BeforeShow @129-3B54BAEC
function liqcabece_lbl_NumLiquida_BeforeShow()
{
    $liqcabece_lbl_NumLiquida_BeforeShow = true;
//End liqcabece_lbl_NumLiquida_BeforeShow

//Custom Code @130-4F8C235D
// -------------------------
    global $liqcabece;
     $liqcabece->lbl_NumLiquida->SetText(CCGetParam("liq_NumLiquida"));
// -------------------------
//End Custom Code

//Close liqcabece_lbl_NumLiquida_BeforeShow @129-7994DC41
    return $liqcabece_lbl_NumLiquida_BeforeShow;
}
//End Close liqcabece_lbl_NumLiquida_BeforeShow

//liqdetalle_tmp_Total_BeforeShow @132-515D6303
function liqdetalle_tmp_Total_BeforeShow()
{
    $liqdetalle_tmp_Total_BeforeShow = true;
//End liqdetalle_tmp_Total_BeforeShow

//Custom Code @133-684101F7
// -------------------------
    global $liqdetalle;
	global $DBdatos;
	$slQry = "SELECT sum(liq_ValTotal * rub_IndDbCr) as tmp_Valor
        		FROM liqliquidaciones
					LEFT JOIN liqrubros ON liqliquidaciones.liq_CodRubro = liqrubros.rub_CodRubro
				WHERE liq_numliquida = " . CCGetParam("liq_NumLiquida");
	$DBdatos->query($slQry);
	if ($DBdatos->next_record()) $liqdetalle->tmp_Total->SetValue($DBdatos->f("tmp_Valor"));
// -------------------------
//End Custom Code

//Close liqdetalle_tmp_Total_BeforeShow @132-78E95833
    return $liqdetalle_tmp_Total_BeforeShow;
}
//End Close liqdetalle_tmp_Total_BeforeShow

//liqdetalle_Button_Delete_OnClick @127-2BBD8437
function liqdetalle_Button_Delete_OnClick()
{
    $liqdetalle_Button_Delete_OnClick = true;
//End liqdetalle_Button_Delete_OnClick

//Custom Code @128-684101F7
// -------------------------
    global $liqdetalle;
	global $liqcabece;
	$db = NewADOConnection(DBTYPE);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
	$db->debug=fGetParam('pAdoDbg', 0);
	$ilNumComp= CCGetParam("liq_NumLiquida");
	fEliminaLiq($db, "com_NumComp=" . $ilNumComp, "liq_NumLiquida=" . $ilNumComp, "tad_LiqNumero=" . $ilNumComp );

// -------------------------
//End Custom Code

//Close liqdetalle_Button_Delete_OnClick @127-EC37B96A
    return $liqdetalle_Button_Delete_OnClick;
}
//End Close liqdetalle_Button_Delete_OnClick

//liqdetalle_ds_BeforeExecuteUpdate @2-FF31E26D
function liqdetalle_ds_BeforeExecuteUpdate()
{
    $liqdetalle_ds_BeforeExecuteUpdate = true;
//End liqdetalle_ds_BeforeExecuteUpdate

//Custom Code @126-684101F7
// -------------------------
    global $liqdetalle;
	/*
	dassd;
            $liqdetalle->Errors->AddError($liqdetalle->SQL);
	*/	
// -------------------------
//End Custom Code

//Close liqdetalle_ds_BeforeExecuteUpdate @2-A89740AE
    return $liqdetalle_ds_BeforeExecuteUpdate;
}
//End Close liqdetalle_ds_BeforeExecuteUpdate

//liqdetalle_BeforeShow @2-355491B7
function liqdetalle_BeforeShow()
{
    $liqdetalle_BeforeShow = true;
//End liqdetalle_BeforeShow

//Custom Code @131-684101F7
// -------------------------
    global $liqdetalle;
	if($liqdetalle->ds->RecordsCount >=1)
		$liqdetalle->Visible = true;
	else
		$liqdetalle->Visible = false;
	$liqdetalle->Button_Delete->visible = false;

// -------------------------
//End Custom Code

//Close liqdetalle_BeforeShow @2-3426C7A2
    return $liqdetalle_BeforeShow;
}
//End Close liqdetalle_BeforeShow

//Page_AfterInitialize @1-7910B30B
function Page_AfterInitialize()
{
    $Page_AfterInitialize = true;
//End Page_AfterInitialize

//Custom Code @99-43073766
// -------------------------
    global $LiLiLi_mant;
   	include_once (RelativePath . "/LibPhp/SegLib.php") ;
	if (!fValidAcceso("","","")) {
    	fMensaje ("UD. NO TIENE ACCESO A ESTE MODULO", 1);
        die();
		}// -------------------------
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize


?>
