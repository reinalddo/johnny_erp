<?php
/*
 *
 *	@REV	fah	05/02/09		Omitir Validacion de fecha de Inicio de contabilizacion, realizarla dentro de la funcion
  *	@REV	fah	25/06/2010		Incluir bitacora de fiscompras
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
    $fiscompras->ds->CCSEvents["BeforeExecuteDelete"] = "fiscompras_ds_BeforeExecuteDelete"; // #fah 25/06/2010
    $fiscompras->ds->CCSEvents["AfterExecuteDelete"] = "fiscompras_ds_AfterExecuteDelete"; // #fah 25/06/2010
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



function fiscompras_ds_BeforeBuildUpdate()
{
    $fiscompras_ds_BeforeBuildUpdate = true;
    global $fiscompras;
/**
	echo "11<br>";
	$fiscompras_ds_BeforeBuildUpdate = fValidacionRuc();
	echo "20<br>";
**/
    return $fiscompras_ds_BeforeBuildUpdate;
}


function fiscompras_AfterInsert()
{
    $fiscompras_AfterInsert = true;
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
    $cla=fPreProcesaBitac(); //#fah 25/06/2010
    //fiscompras_Bitacora($fiscompras->ID->GetValue(), "U", $cla); //#fah 25/06/2010      return $fiscompras_AfterInsert;
    fiscompras_Bitacora($ilRecID, "U", $cla); //#fah 25/06/2010      return $fiscompras_AfterInsert;
}



function fPreProcesaBitac(){ //#fah 25/06/2010  
    global $db, $cla, $olEsq, $fiscompras;
    $ilID = CCGetParam("ID", "zzz");
    $trSql =
	"SELECT fiscompras.ID AS ID,
		com_tipocomp AS com_tipocomp,
		com_numcomp AS com_numcomp,
		cla_contabilizacion AS cla_contabilizacion,
		SUM(det_valdebito) AS tmp_db,
		SUM(det_valcredito) AS tmp_cr
	FROM fiscompras JOIN fistablassri ON tab_codtabla = 'A' AND tab_codigo = tipoTransac
	LEFT JOIN genclasetran ON cla_tipocomp = tab_txtData3
	LEFT JOIN concomprobantes ON com_tipocomp = tab_txtData3 AND com_numretenc = ID
	LEFT JOIN condetalle ON det_regnumero = com_regnumero
	WHERE ID =  " . $ilID . "
	GROUP BY 1,2,3";
    $cla = $db->GetRow($trSql);
    $cla["val1"] = $fiscompras->baseImpGrav->GetValue() + $fiscompras->baseImponible->GetValue(); //#fah 25/06/2010
    $cla["val2"] = $fiscompras->valRetAir->GetValue() + $fiscompras->valRetAir2->GetValue() + $fiscompras->valRetAir3->GetValue();
    $cla["val3"] = $fiscompras->montoIvaBienes->GetValue() + $fiscompras->montoIvaServicios->GetValue() ; //#fah 25/06/2010

    return $cla;
}


function fiscompras_ds_AfterExecuteUpdate()
{
    $fiscompras_ds_AfterExecuteUpdate = true;
    global $fiscompras, $DBdatos;
	//$slFec = $fiscompras->fechaRegistro->GetValue();
	//$slCond = "par_clave='DIMINI' AND par_valor1 <= '" . date('Y-m-d', $slFec[0]) . "'";
	//if (CCDLookUp("count(*)", "genparametros", $slCond, $DBdatos) >=1)
	fContabDim(CCGetParam("ID",false),$fiscompras->tmp_Descripcion->GetValue());
	$cla=fPreProcesaBitac(); //#fah 25/06/2010
	fiscompras_Bitacora($fiscompras->ID->GetValue(), "U", $cla); //#fah 25/06/2010  
	setcookie("coTabActive", "4", time()+1800,".");
    return $fiscompras_ds_AfterExecuteUpdate;
}



function fiscompras_ds_BeforeExecuteDelete()  //#fah 25/06/2010  
{
   global $db, $cla, $olEsq, $fiscompras;
    $ilID = CCGetParam("ID", "zzz");
    $cla=fPreProcesaBitac(); //#fah 25/06/2010
    $fiscompras_ds_BeforeExecuteDelete = true;
    //print_r($cla);
    //echo "<br>";
    return $fiscompras_ds_BeforeExecuteDelete;
}

function fiscompras_ds_AfterExecuteDelete()  //#fah 25/06/2010  
{
   global $db, $cla, $olEsq, $fiscompras;
    $ilID = CCGetParam("ID", "zzz");
    $slDel = "DELETE FROM concomprobantes WHERE com_tipocomp = '" . $cla->com_tipocomp . "' AND com_numretenc = " . $ilID;
    $db->Execute($slDel);
    //echo $slDel;
    fiscompras_Bitacora($ilID, "D", $cla);
    $fiscompras_ds_AfterExecuteDelete = true;
    return $fiscompras_ds_AfterExecuteUpdate;
}

/**
*   Entrada en la bitacora de seguridad, anotando las condiciones de grabacion
**/
function fiscompras_Bitacora($pID, $pTipo='I', $pArr = false){
    global $InTrTr_comp, $db,  $fiscompras;

    $alFecContab = $fiscompras->fechaRegistro->GetValue(); // Fecha en formato de arreglo
    $slTxt = "  CMP: " . (isset($pArr["com_tipocomp"])? $pArr["com_tipocomp"] :0 ) . " -" . (isset($pArr["com_numcomp"])? $pArr["com_numcomp"] :0 ). 
	     "  PRV: " . $fiscompras->idProvFact->GetValue() . " - " . $fiscompras->txt_rucProv->GetValue().
             "  RFT: " . $fiscompras->estabRetencion1->GetValue() . "-" .$fiscompras->puntoEmiRetencion1->GetValue() . "-" .$fiscompras->secRetencion1->GetValue() .
             "  FER: " . $alFecContab[1] . "/" . $alFecContab[2] . "/" . $alFecContab[3].
	     " / RO:" ;
    switch  ($pTipo) {
        case   "I":
            $slOperac = "ING." ;
            break;
        case   "U":
            $slOperac = "MOD.";
            break;
        case   "D":
            $slOperac = "ELIM.";
            break;
    }
    fRegistroBitacora( "RF",  
	$pID,
	$_SESSION['g_user'], // Usuario
	$pAnot = $slOperac . $slTxt,
	isset($pArr["val1"])? $pArr["val1"] :0,  
	isset($pArr["val1"])? $pArr["val3"] :0,  
	isset($pArr["val2"])? $pArr["val2"] :0,
	" " ,
	0,
	0);
    
    if (isset($pArr["com_numcomp"])  &&  $pArr["com_numcomp"] <> 0)  {
	fRegistroBitacora($pArr["cla_tipocomp"],  
	    $pArr["com_numcomp"],
	    $_SESSION['g_user'], // Usuario
	    $pAnot = "GENERADO " . $slTxt,
	    0,
	    isset($pArr["tmp_db"])? $pArr["tmp_db"] :0,  
	    isset($pArr["tmp_cr"])? $pArr["tmp_cr"] :0,
	    " " ,
	    0,
	    0);
    }
   //print_r($pArr);
   //die();
}

?>
