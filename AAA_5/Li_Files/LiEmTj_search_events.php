<?php
//BindEvents Method @1-57094231
function BindEvents()
{
    global $tarjas_list;
    $tarjas_list->txt_CantRecibida->CCSEvents["BeforeShow"] = "tarjas_list_txt_CantRecibida_BeforeShow";
    $tarjas_list->txt_ValUnitario->CCSEvents["BeforeShow"] = "tarjas_list_txt_ValUnitario_BeforeShow";
    $tarjas_list->txt_DifUnitario->CCSEvents["BeforeShow"] = "tarjas_list_txt_DifUnitario_BeforeShow";
    $tarjas_list->ds->CCSEvents["BeforeExecuteSelect"] = "tarjas_list_ds_BeforeExecuteSelect";
    $tarjas_list->CCSEvents["BeforeShow"] = "tarjas_list_BeforeShow";
}
//End BindEvents Method

//tarjas_list_txt_CantRecibida_BeforeShow @534-55E6EA09
function tarjas_list_txt_CantRecibida_BeforeShow()
{
    $tarjas_list_txt_CantRecibida_BeforeShow = true;
//End tarjas_list_txt_CantRecibida_BeforeShow

//Custom Code @535-60C882B3
// -------------------------
    global $tarjas_list;
	global $DBdatos;
	$aText=explode("FROM ",$tarjas_list->ds->SQL);
	$aText[0]="SELECT SUM(tad_CantDespachada) AS txt_SumDes, 
					  SUM(tad_CantRecibida) AS txt_SumReci, 
					  SUM(tad_CantRechazada + tad_CantCaidas) AS txt_SumRech, 
					  SUM(tad_CantRecibida - tad_CantRechazada  ) AS txt_SumEmba, 
					  SUM((tad_CantRecibida - tad_CantRechazada  ) * tad_ValUnitario) AS txt_Valor, 
					  SUM((tad_CantRecibida - tad_CantRechazada  ) * tad_DifUnitario) AS txt_Adela";
	$slSql = $aText[0] . " FROM " . $aText[1];
	$DBdatos->query($slSql);
	if ($DBdatos->next_record()) {
		$tarjas_list->txt_CantRecibida->SetValue($DBdatos->f("txt_SumReci"));
		$tarjas_list->txt_CantRechazada->SetValue($DBdatos->f("txt_SumRech"));
		$tarjas_list->txt_CantEmbarcada->SetValue($DBdatos->f("txt_SumEmba"));
		$tarjas_list->txt_ValUnitario->SetValue($DBdatos->f("txt_Valor"));
		$tarjas_list->txt_DifUnitario->SetValue($DBdatos->f("txt_Adela"));
		$tarjas_list->txt_ValTotal->SetValue($DBdatos->f("txt_Valor") - $DBdatos->f("txt_Adela"));
		}
// -------------------------
//End Custom Code

//Close tarjas_list_txt_CantRecibida_BeforeShow @534-F8B88FBF
    return $tarjas_list_txt_CantRecibida_BeforeShow;
}
//End Close tarjas_list_txt_CantRecibida_BeforeShow

//tarjas_list_txt_ValUnitario_BeforeShow @536-20013C6A
function tarjas_list_txt_ValUnitario_BeforeShow()
{
    $tarjas_list_txt_ValUnitario_BeforeShow = true;
//End tarjas_list_txt_ValUnitario_BeforeShow

//Custom Code @537-60C882B3
// -------------------------
    global $tarjas_list;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close tarjas_list_txt_ValUnitario_BeforeShow @536-A799D8F7
    return $tarjas_list_txt_ValUnitario_BeforeShow;
}
//End Close tarjas_list_txt_ValUnitario_BeforeShow

//tarjas_list_txt_DifUnitario_BeforeShow @538-D5C7430B
function tarjas_list_txt_DifUnitario_BeforeShow()
{
    $tarjas_list_txt_DifUnitario_BeforeShow = true;
//End tarjas_list_txt_DifUnitario_BeforeShow

//Custom Code @539-60C882B3
// -------------------------
    global $tarjas_list;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close tarjas_list_txt_DifUnitario_BeforeShow @538-9663C763
    return $tarjas_list_txt_DifUnitario_BeforeShow;
}
//End Close tarjas_list_txt_DifUnitario_BeforeShow

//tarjas_list_ds_BeforeExecuteSelect @2-4FC07CF1
function tarjas_list_ds_BeforeExecuteSelect()
{
    $tarjas_list_ds_BeforeExecuteSelect = true;
//End tarjas_list_ds_BeforeExecuteSelect

//Custom Code @427-88C27638
// -------------------------
    global $tarjas_list, $tarjas_qry ;
	global $gsSql;
//	$slSql = array ( 'sql'=>'', 'txt'=>'');
	$slSql='';
	$slTxt='';
	$slTxt .= fConstruct($tarjas_qry->s_tar_NumTarja->GetValue(), 		'i', '', 'tar_NumTarja', 		$slSql, 'AND', ' TARJA #');
	$slTxt .= fConstruct($tarjas_qry->s_emb_AnoOperacion->GetValue(), 	'i', '', 'emb_AnoOperacion', 	$slSql, 'AND', ' AÑO ');
	$slTxt .= fConstruct($tarjas_qry->s_tac_Semana->GetValue(), 		'i', '', 'tac_Semana', 			$slSql, 'AND', ' SEMANA');
//echo "ZONA: " . $tarjas_qry->s_tac_Zona->GetValue();
	$slTxt .= fConstruct($tarjas_qry->s_tac_Fecha->GetValue(), 			'd', '', 'tac_Fecha', 			$slSql, 'AND', ' FECHA ');
	$slTxt .= fConstruct($tarjas_qry->s_tac_Zona->GetValue(), 			'i', '', 'tac_Zona', 			$slSql, 'AND', ' ZONA ');
	$slTxt .= fConstruct($tarjas_qry->s_tac_CodOrigen->GetValue(), 		'i', '', 'tac_CodOrigen', 		$slSql, 'AND', ' CODIGO ');
	$slTxt .= fConstruct($tarjas_qry->s_tac_GrupLiquidacion->GetValue(), 	'd', '', 'tac_GrupLiquidacion', $slSql, 'AND', ' GRUPO ');
	$slTxt .= fConstruct($tarjas_qry->s_tac_Estado->GetValue(), 			'i', '', 'tac_Estado', 			$slSql, 'AND', ' ESTADO TARJA ');
	$slTxt .= fConstruct($tarjas_qry->s_emb_NumViaje->GetValue(), 		'i', '', 'emb_NumViaje', 		$slSql, 'AND', ' NUM.VIAJE ');
	$slTxt .= fConstruct($tarjas_qry->s_emb_Estado->GetValue(), 			'i', '', 'emb_Estado', 			$slSql, 'AND', ' ESTADO EMBARQ ');
	$slTxt .= fConstruct($tarjas_qry->s_buq_Abreviatura->GetValue(),		't', '%', 'buq_Abreviatura', 	$slSql, 'AND', ' ABR. BUQUE ');
	//$slTxt .= fConstruct($tarjas_qry->s_buq_Descripcion->GetValue(),		't', '%', 'buq_Descripcion', 	$slSql, 'AND', ' BUQUE ');
	$slTxt .= fConstruct($tarjas_qry->s_act_Descripcion1->GetValue(),		't', '%', 'act_Descripcion1', 	$slSql, 'AND', ' PRODUCTO ');
	if (is_numeric($tarjas_qry->s_per_Apellidos->GetValue()))
		$slTxt .= fConstruct($tarjas_qry->s_per_Apellidos->GetValue(),		'i', '', 'tac_Embarcador', 		$slSql, 'AND', ' APELLIDOS ');
	else {
		$slTxt .= "(". fConstruct($tarjas_qry->s_per_Apellidos->GetValue(),		't', '%', '(per_Apellidos', 		$slSql, 'AND ', ' APELLIDOS ');
		$slTxt .= fConstruct($tarjas_qry->s_per_Nombres->GetValue(),			't', '%', 'per_Nombres', 		$slSql, 'OR', ' NOMBRES ') . ")";
		if ($tarjas_qry->s_per_Apellidos->GetValue()) $slSql .= ") ";
		}
	$slTxt .= fConstruct($tarjas_qry->s_caj_Descripcion->GetValue(),		't', '%', 'caj_Descripcion', 	$slSql, 'AND', ' EMPAQUE ');
	$slTxt .= fConstruct($tarjas_qry->s_buq_Descripcion->GetValue(),		't', '%', 'tac_Contenedor', 	$slSql, 'AND', ' CONTENEDOR ');
	if (strlen($slSql)> 0)	$tarjas_list->ds->SQL .= " WHERE  " . $slSql ;
	if (strlen($slSql)> 0)	$tarjas_list->ds->CountSQL .= " WHERE  " . $slSql ;
//echo "<br>" .$tarjas_list->ds->SQL . "<br>";


// -------------------------
//End Custom Code

//Close tarjas_list_ds_BeforeExecuteSelect @2-4C0DCA66
    return $tarjas_list_ds_BeforeExecuteSelect;
}
//End Close tarjas_list_ds_BeforeExecuteSelect

//tarjas_list_BeforeShow @2-70BD1558
function tarjas_list_BeforeShow()
{
    $tarjas_list_BeforeShow = true;
//End tarjas_list_BeforeShow

//Custom Code @541-60C882B3
// -------------------------
    global $tarjas_list;
/*	print_r($_POST);
	print_r($_GET);
	print_r($_POST["Button_DoSearch"]);
	if(!strlen(CCGetParam("Button_DoSearch", ""))) {
                $tarjas_list->Visible = false;

    }*/
// -------------------------
//End Custom Code

//Close tarjas_list_BeforeShow @2-FF10983A
    return $tarjas_list_BeforeShow;
}
//End Close tarjas_list_BeforeShow

?>
