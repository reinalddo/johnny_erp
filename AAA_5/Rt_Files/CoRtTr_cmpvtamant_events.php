<?php
/*
 *
 *	@REV	fah	05/02/09		Omitir Validacion de fecha de Inicio de contabilizacion, realizarla dentro de la funcion
 **/
include_once("CoRtTr_cmpvtamant_contab.php");
//BindEvents Method @1-A0840FAE
function BindEvents()
{
    global $fiscompras;
    $fiscompras->txt_ProvDescripcionFact->CCSEvents["OnValidate"] = "fiscompras_txt_ProvDescripcionFact_OnValidate";
    $fiscompras->tmp_Descripcion->CCSEvents["BeforeShow"] = "fiscompras_tmp_Descripcion_BeforeShow";
    $fiscompras->ds->CCSEvents["BeforeBuildInsert"] = "fiscompras_ds_BeforeBuildInsert";
    $fiscompras->ds->CCSEvents["BeforeBuildUpdate"] = "fiscompras_ds_BeforeBuildUpdate";
    $fiscompras->CCSEvents["AfterInsert"] = "fiscompras_AfterInsert";
    $fiscompras->ds->CCSEvents["AfterExecuteUpdate"] = "fiscompras_ds_AfterExecuteUpdate";
}
//End BindEvents Method

//fiscompras_txt_ProvDescripcionFact_OnValidate @355-D950EC72
function fiscompras_txt_ProvDescripcionFact_OnValidate()
{
    $fiscompras_txt_ProvDescripcionFact_OnValidate = true;
//End fiscompras_txt_ProvDescripcionFact_OnValidate

//Custom Code @375-D7BDDFDC
// -------------------------
	$fiscompras_txt_ProvDescripcionFact_OnValidate = fValidacionRuc();
// -------------------------
//End Custom Code

//Close fiscompras_txt_ProvDescripcionFact_OnValidate @355-14115015
    return $fiscompras_txt_ProvDescripcionFact_OnValidate;
}
//End Close fiscompras_txt_ProvDescripcionFact_OnValidate

//fiscompras_tmp_Descripcion_BeforeShow @467-45261A58
function fiscompras_tmp_Descripcion_BeforeShow()
{
    $fiscompras_tmp_Descripcion_BeforeShow = true;
//End fiscompras_tmp_Descripcion_BeforeShow

//Custom Code @468-D7BDDFDC
// -------------------------
    global $fiscompras, $DBdatos;
    $ilID= CCGetParam("ID",false);
    if($ilID){
    	$ilID=CCDLookUp("com_Concepto", "concomprobantes", "com_numretenc = " . $ilID, $DBdatos);
    	$fiscompras->tmp_Descripcion->SetValue();
    }
// -------------------------
//End Custom Code

//Close fiscompras_tmp_Descripcion_BeforeShow @467-E12ED886
    return $fiscompras_tmp_Descripcion_BeforeShow;
}
//End Close fiscompras_tmp_Descripcion_BeforeShow
function fValidacionRuc()
{
    global $fiscompras;
	//echo "<br> 1<br>";
	$blRcode = fValidaRuc($fiscompras->txt_rucProv->Value, $fiscompras->tpIdProv->Value);
	//echo "<br> 2<br>";
	if($blRcode < 0 ) {
	//echo "<br> pr: $blRcode <br>";
            $fiscompras->txt_rucProv->Errors->addError("RUC DE PROVEDOR CONTABLE INVALIDO (" . $blRcode . ")");
        }
	$blRcode2 = fValidaRuc($fiscompras->txt_rucProvFact->Value, $fiscompras->tpIdProvFact->Value);
    //echo "<br> 3 / $blRcode2 <br>";
	if($blRcode2 < 0) {
	//echo "<br> fa: $blRcode <br>";
            $fiscompras->txt_rucProvFact->Errors->addError("RUC DE PROVEEDOR FISCAL INVALIDO (" . $blRcode2 . ")");
        }
	return ($blRcode && $blRcode2);
}
//fiscompras_ds_BeforeBuildInsert @2-C0E52627
function fiscompras_ds_BeforeBuildInsert()
{
    $fiscompras_ds_BeforeBuildInsert = true;
//End fiscompras_ds_BeforeBuildInsert

//Custom Code @376-D7BDDFDC
// -------------------------
    global $fiscompras;
//	$fiscompras_ds_BeforeBuildInsert = fValidacionRuc();
// -------------------------
//End Custom Code

//Close fiscompras_ds_BeforeBuildInsert @2-A39834E5
    return $fiscompras_ds_BeforeBuildInsert;
}
//End Close fiscompras_ds_BeforeBuildInsert

//fiscompras_ds_BeforeBuildUpdate @2-BCF88CE3
function fiscompras_ds_BeforeBuildUpdate()
{
    $fiscompras_ds_BeforeBuildUpdate = true;
//End fiscompras_ds_BeforeBuildUpdate

//Custom Code @377-D7BDDFDC
// -------------------------
    global $fiscompras;
/**
	echo "11<br>";
	$fiscompras_ds_BeforeBuildUpdate = fValidacionRuc();
	echo "20<br>";
**/
// -------------------------
//End Custom Code

//Close fiscompras_ds_BeforeBuildUpdate @2-6CB1F56A
    return $fiscompras_ds_BeforeBuildUpdate;
}
//End Close fiscompras_ds_BeforeBuildUpdate

//fiscompras_AfterInsert @2-FD9240B4
function fiscompras_AfterInsert()
{
    $fiscompras_AfterInsert = true;
//End fiscompras_AfterInsert

//Custom Code @453-D7BDDFDC
// -------------------------
    global $fiscompras;
    global $InTrTr_comp;
    global $Redirect;
	global $DBdatos;
    $InTrTr_comp_AfterInsert = true;
	$dbD = new clsDBdatos();
    $SQL = "SELECT LAST_INSERT_ID() as id";
    $dbD->query($SQL);
    $Result = $dbD->next_record();

    if ($Result) {
	$ilRecID=	$dbD->f("id");
     	$Redirect .= "&ID=" . $dbD->f("id");
    }
    unset($dbD);
	//$slFec = $fiscompras->fechaRegistro->GetValue();
	//$slCond = "par_clave='DIMINI' AND par_valor1 <= '" . date('Y-m-d', $slFec[0]) . "'";
	//if (CCDLookUp("par_valor1", "genparametros", $slCond, $DBdatos) >=1)   //			Se valida <aDENTRO
		fContabDim($ilRecID, $fiscompras->tmp_Descripcion->GetValue());
// -------------------------
//End Custom Code

//Close fiscompras_AfterInsert @2-6F82E5BA
    return $fiscompras_AfterInsert;
}
//End Close fiscompras_AfterInsert

//fiscompras_ds_AfterExecuteUpdate @2-7D1A2636
function fiscompras_ds_AfterExecuteUpdate()
{
    $fiscompras_ds_AfterExecuteUpdate = true;
//End fiscompras_ds_AfterExecuteUpdate

//Custom Code @463-D7BDDFDC
// -------------------------
    global $fiscompras, $DBdatos;
	//$slFec = $fiscompras->fechaRegistro->GetValue();
	//$slCond = "par_clave='DIMINI' AND par_valor1 <= '" . date('Y-m-d', $slFec[0]) . "'";
	//if (CCDLookUp("count(*)", "genparametros", $slCond, $DBdatos) >=1)
		fContabDim(CCGetParam("ID",false),$fiscompras->tmp_Descripcion->GetValue());
	setcookie("coTabActive", "4", time()+1800,".");
// -------------------------
//End Custom Code

//Close fiscompras_ds_AfterExecuteUpdate @2-C5472B0D
    return $fiscompras_ds_AfterExecuteUpdate;
}
//End Close fiscompras_ds_AfterExecuteUpdate



?>
