<?php
//BindEvents Method @1-4E686159
function BindEvents()
{
    global $conconcil;
    global $movimlist;
    $conconcil->CCSEvents["BeforeShow"] = "conconcil_BeforeShow";
    $movimlist->ds->CCSEvents["BeforeBuildSelect"] = "movimlist_ds_BeforeBuildSelect";
    $movimlist->ds->CCSEvents["BeforeExecuteUpdate"] = "movimlist_ds_BeforeExecuteUpdate";
}
//End BindEvents Method

//conconcil_BeforeShow @2-AACE38CD
function conconcil_BeforeShow()
{
    $conconcil_BeforeShow = true;
//End conconcil_BeforeShow

//Custom Code @60-B2BAECAC
// -------------------------
    global $conconcil;
	global $movimlist;
	global $DBdatos;
	global $Tpl;

	$slfec='';
	if ($conconcil->EditMode) {
		$slSql = "SELECT max(p.con_feccorte )  + interval 1 day AS tmp_desde
						FROM conconciliacion p 
						WHERE p.con_codcuenta = '" . $conconcil->con_CodCuenta->GetValue() . "' AND
							p.con_codauxiliar =  " . $conconcil->con_CodAuxiliar->GetValue() . " AND
							p.con_feccorte < '" . CCFormatDate($conconcil->con_FecCorte->GetValue(), array("yyyy", "/","mm", "/","dd"))  . " ' " ;
		$DBdatos->query($slSql);
		$rs = $DBdatos->next_record();
		if (!$rs) die ("NO SE PUDO PROCESAR REGISTRO");
		$slFec= $DBdatos->f('tmp_desde');
	}
	else $movimlist->Visible = False;
	if (!$conconcil->con_Ususario->GetValue()) $conconcil->con_Ususario->SetValue($_SESSION['g_user']);
	if (strlen($slFec)<10) $slFec="2004-12-31";
	$slFec = CCParseDate($slFec, array("yyyy", "-", "mm", "-", "dd"));
	$conconcil->tmp_FecInicio->SetValue($slFec);
    $Tpl->setvar('txt_CheqInicial', $txt_CheqInicial);
    $Tpl->setvar('txt_CheqFinal', $txt_CheqFinal);
    $Tpl->setvar('txt_Valor', $txt_Valor);
// -------------------------
//End Custom Code

//Close conconcil_BeforeShow @2-724D4EEF
    return $conconcil_BeforeShow;
}
//End Close conconcil_BeforeShow

//movimlist_ds_BeforeBuildSelect @24-03C38ADE
function movimlist_ds_BeforeBuildSelect()
{
    $movimlist_ds_BeforeBuildSelect = true;
//End movimlist_ds_BeforeBuildSelect

//Custom Code @59-A77A9844
// -------------------------
    global $movimlist;
	global $conconcil;
    global $txt_CheqInicial;
    global $txt_CheqFinal;
    global $txt_CheqValor;	
	$movimlist->ds->wp->Parameters[1]->DBValue = $conconcil->con_CodCuenta->GetValue();
	$movimlist->ds->wp->Parameters[2]->DBValue = $conconcil->con_CodAuxiliar->GetValue();	
	$movimlist->ds->wp->Parameters[3]->DBValue = CCFormatDate($conconcil->tmp_FecInicio->GetValue(), array("yyyy", "/","mm", "/","dd"));
	$movimlist->ds->wp->Parameters[4]->DBValue = CCFormatDate($conconcil->con_FecCorte->GetValue(), array("yyyy", "/","mm", "/","dd"));

//	print_r($movimlist->ds->wp);
// echo "<br> epx 55 ". $movimliat->ds->Parameters["expr55"];
// -------------------------
//End Custom Code

//Close movimlist_ds_BeforeBuildSelect @24-9B7A1228
    return $movimlist_ds_BeforeBuildSelect;
}
//End Close movimlist_ds_BeforeBuildSelect

//movimlist_ds_BeforeExecuteUpdate @24-7E289425
function movimlist_ds_BeforeExecuteUpdate()
{
    $movimlist_ds_BeforeExecuteUpdate = true;
//End movimlist_ds_BeforeExecuteUpdate

//Custom Code @94-A77A9844
// -------------------------
    global $movimlist;
error_log ("  : " . $movimlist->ds->SQL ."\r\n\r", 3, "/tmp/my-errors.log"); 
// -------------------------
//End Custom Code

//Close movimlist_ds_BeforeExecuteUpdate @24-30348761
    return $movimlist_ds_BeforeExecuteUpdate;
}
//End Close movimlist_ds_BeforeExecuteUpdate

//DEL  // -------------------------
//DEL      global $movimlist;
//DEL  	global $conconcil;
//DEL  	if (!$conconcil->EditMode) 
//DEL  		$movimlist->Visible = False;
//DEL  		$movimlist->Visible = False;
//DEL  // -------------------------

?>
