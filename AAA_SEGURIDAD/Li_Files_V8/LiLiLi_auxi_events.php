<?php
//BindEvents Method @1-86D3F87D
function BindEvents()
{
    global $dettarjas_list;
    $dettarjas_list->CCSEvents["BeforeShowRow"] = "dettarjas_list_BeforeShowRow";
}
//End BindEvents Method

//dettarjas_list_BeforeShowRow @24-53625A47
function dettarjas_list_BeforeShowRow()
{
    $dettarjas_list_BeforeShowRow = true;
//End dettarjas_list_BeforeShowRow

//Custom Code @144-BDA26E0A
// -------------------------
    global $dettarjas_list;
	$clDB = New clsDBdatos;
    $tlSql ="SELECT SUM(tad_CantDespachada) AS tmp_SumDesp,
					SUM(tad_CantRecibida) AS tmp_SumReci,
					SUM(tad_CantRechazada) AS tmp_SumRech,
					SUM(tad_CantCaidas) AS tmp_SumCaid,
					SUM(tad_CantRecibida - tad_CantRechazada  ) AS tmp_SumEmba,
					SUM(ROUND(tad_ValUnitario * (tad_CantRecibida - tad_CantRechazada  ),2)) AS tmp_SumValor,
					SUM(ROUND(if(tad_DifUnitario>0, tad_DifUnitario * (tad_CantRecibida - tad_CantRechazada  ),0),2))  as tmp_SumAdel,
					SUM(ROUND(if(tad_DifUnitario<=0, tad_DifUnitario * (tad_CantRecibida - tad_CantRechazada  ) * (-1),0),2)) as tmp_SumBono
			 FROM liqtarjadetal
			 WHERE tad_liqnumero = " . CCTOSQL(CCGetParam("liq_NumLiquida", 0), "Integer");
	$clDB->Query($tlSql);
	$rs = $clDB->Next_Record();
    if ($rs) { 
		$dettarjas_list->txt_SumDesp->SetValue($clDB->f("tmp_SumDesp"));
		$dettarjas_list->txt_SumReci->SetValue($clDB->f("tmp_SumReci"));
		$dettarjas_list->txt_SumRech->SetValue($clDB->f("tmp_SumRech"));
		$dettarjas_list->txt_SumCaid->SetValue($clDB->f("tmp_SumCaid"));
		$dettarjas_list->txt_SumEmba->SetValue($clDB->f("tmp_SumEmba"));
		$dettarjas_list->txt_SumFruta->SetValue($clDB->f("tmp_SumValor"));
		$dettarjas_list->txt_SumAdel->SetValue($clDB->f("tmp_SumAdel"));
		$dettarjas_list->txt_SumBono->SetValue($clDB->f("tmp_SumBono"));
	}
// -------------------------
//End Custom Code

//Close dettarjas_list_BeforeShowRow @24-251F54EF
    return $dettarjas_list_BeforeShowRow;
}
//End Close dettarjas_list_BeforeShowRow


?>
