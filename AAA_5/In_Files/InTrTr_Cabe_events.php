<?php
/*
 // fah Jun29/05: garantizar que se grabe el valor default del emisor, cuando asi lo requiera la transacc.
// fah Sep29/05: garantizar que se actualize las transacc asociadas al modificar fechas y/o productores
*	@rev	fah 03/03/09	Soporte de numeracion de comprobantes incluyendo: empresa, establecimiento,
*				pto de emision, tipo de comprobante
**/
//BindEvents Method @1-DF911B70
  
include_once("GenUti.inc.php");
function BindEvents()
{
    global $InTrTr_topline;
    global $InTrTr_comp;
    global $goTrans;
//	if (!CCGetParam("pTipoComp", False) == False) {
		$goTrans = New clsDBdatos;
	    $tlSql ="SELECT * FROM genclasetran WHERE " .
				    "cla_TipoComp  = " . CCTOSQL(CCGetParam("pTipoComp", ''), "Text");
		$goTrans->Query($tlSql);
		$rsTipos = $goTrans->Next_Record();
/**
	    if (!$rsTipos) { 
			fMensaje ("NO SE PUEDE DEFINIR EL TIPO DE TRANSACCION"); 
		    die(); 

		}
**/
//	}
    $InTrTr_topline->CCSEvents["BeforeShow"] = "InTrTr_topline_BeforeShow";
    $InTrTr_comp->com_Emisor->ds->CCSEvents["BeforeBuildSelect"] = "InTrTr_comp_com_Emisor_ds_BeforeBuildSelect";
    $InTrTr_comp->Button_Delete->CCSEvents["OnClick"] = "InTrTr_comp_Button_Delete_OnClick";
    $InTrTr_comp->CCSEvents["BeforeShow"] = "InTrTr_comp_BeforeShow";
    $InTrTr_comp->CCSEvents["AfterInsert"] = "InTrTr_comp_AfterInsert";
    $InTrTr_comp->CCSEvents["OnValidate"] = "InTrTr_comp_OnValidate";
    $InTrTr_comp->CCSEvents["BeforeInsert"] = "InTrTr_comp_BeforeInsert";
    $InTrTr_comp->CCSEvents["BeforeUpdate"] = "InTrTr_comp_BeforeUpdate";
    $InTrTr_comp->CCSEvents["AfterUpdate"] = "InTrTr_comp_AfterUpdate";
    $InTrTr_comp->CCSEvents["AfterExecuteUpdate"] = "InTrTr_Bitacora('U')";
    $InTrTr_comp->CCSEvents["AfterExecuteInsert"] = "InTrTr_Bitacora('I')";
    $InTrTr_comp->CCSEvents["AfterDelete"] = "InTrTr_comp_AfterDelete";
}
//End BindEvents Method
//InTrTr_topline_BeforeShow @220-FCDA7FB4
function InTrTr_topline_BeforeShow()
{
    $InTrTr_topline_BeforeShow = true;
    global $InTrTr_topline;
    global $InTrTr_comp;
	global $goTrans;

	if ((CCGetParam("com_RegNumero", False) == False)) {
	   $InTrTr_topline->lbTituloComp->SetValue("AGREGAR UN COMPROBANTE ");
	   }
	else  $InTrTr_topline->lbTituloComp->SetValue("MODIFICANDO UN COMPROBANTE ");
    return $InTrTr_topline_BeforeShow;
}
//InTrTr_comp_com_Emisor_ds_BeforeBuildSelect @82-E4067007
$goTrans=NULL;
function InTrTr_comp_com_Emisor_ds_BeforeBuildSelect()
{
    $InTrTr_comp_com_Emisor_ds_BeforeBuildSelect = true;

    global $InTrTr_comp;
	global $InTrTr_topline;
	global $goTrans;
/**
	if (!CCGetParam("pTipoComp", False) == False) {
		$goTrans = New clsgoTrans;
	    $tlSql ="SELECT * FROM genclasetran WHERE " .
				    "cla_TipoComp  = " . CCTOSQL(CCGetParam("pTipoComp", False), "Text");
		$goTrans->Query($tlSql);
		$rsTipos = $goTrans->Next_Record();
	        if (!$rsTipos) { 
							fMensaje ("NO SE PUEDE DEFINIR EL TIPO DE TRANSACCION"); 
						    die(); 
							}
		$goTrans = $goTrans;
**/
		$InTrTr_comp->lbEmisor->SetValue($goTrans->f("cla_TxtEmisor"));
		$InTrTr_comp->lbReceptor->SetValue($goTrans->f("cla_TxtReceptor"));
		$InTrTr_comp->hdFormulario->SetValue($goTrans->f("cla_Formulario"));
		$InTrTr_comp->hdInforme->SetValue($goTrans->f("cla_Informe"));

		$ilTipoEmisor = $goTrans->f("cla_TipoEmisor");
//		echo "TIPO: ".  $goTrans->f("cla_TipoEmisor");
		$ilTipoReceptor = $goTrans->f("cla_TipoReceptor");
		CCSetSession ("ilTipoEmisor", $ilTipoEmisor);
		CCSetSession ("ilTipoReceptor", $ilTipoReceptor);

		if ($goTrans->f("cla_ReqEmisor") == 0) {

			$InTrTr_comp->lbEmisor->Visible = False;
			$InTrTr_comp->com_Emisor->Visible = False;
			}

		if ($goTrans->f("cla_ReqReceptor") == 0) {
			$InTrTr_comp->lbReceptor->Visible = False;
			$InTrTr_comp->com_CodReceptor->Visible = False;
			$InTrTr_comp->com_Receptor->Visible = False;
			}
        $InTrTr_comp->hTipoEmisor->SetValue($goTrans->f("cla_TipoEmisor"));
        $InTrTr_comp->hTipoReceptor->SetValue($goTrans->f("cla_TipoReceptor"));
        if ($ilTipoEmisor) {
        		$slWhereAct = " WHERE cat_categoria = " . $ilTipoEmisor . ($goTrans->f("cla_EmiDefault") > 0 ? " AND act_codauxiliar = " . $goTrans->f("cla_EmiDefault") : " ");
        		$slWherePer =" WHERE cat_categoria = " . $ilTipoEmisor . ($goTrans->f("cla_EmiDefault") > 0 ? " AND per_codauxiliar = " . $goTrans->f("cla_EmiDefault") : " ");
		}
		else {
			$slWhereAct = "";
			$slWherePer = "";
		}
		$InTrTr_comp->com_Emisor->ds->SQL =
		    "SELECT concategorias.cat_categoria, act_codauxiliar as cod, concat(act_descripcion, ' ', act_descripcion1) as nomb " .
			    " FROM conactivos INNER JOIN concategorias on concategorias.cat_codauxiliar = conactivos.act_codauxiliar  " .
			    $slWhereAct .
			    " UNION SELECT concategorias.cat_categoria, per_codauxiliar as cod , concat(per_Nombres, ' ', per_apellidos) as nomb " .
			    " FROM conpersonas INNER JOIN concategorias on conpersonas.per_codauxiliar = concategorias.cat_codauxiliar " .
			    $slWherePer;
//	}
    return $InTrTr_comp_com_Emisor_ds_BeforeBuildSelect;
}
//InTrTr_comp_Button_Delete_OnClick @96-D2CAD7D1
function InTrTr_comp_Button_Delete_OnClick()
{
    $InTrTr_comp_Button_Delete_OnClick = true;
    global $InTrTr_comp;
		if (!fValidAcceso("","DEL","")) {
	
		$CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;

		$InTrTr_comp->Errors->addError("UD NO POSEE PRIVILEGIOS DE ELIMINACION EN ESTE MODULO");
		$InTrTr_comp_Button_Delete_OnClick = false;
		}
    return $InTrTr_comp_Button_Delete_OnClick;
}
//InTrTr_comp_BeforeShow @70-EE0FAF55
function InTrTr_comp_BeforeShow()
{
    $InTrTr_comp_BeforeShow = true;
    global $InTrTr_comp;
	global $InTrTr_topline;
	global $DBdatos;
	global $Tpl;
	global $goTrans;
	if ((CCGetParam("pTipoComp", False) == False)) {
		$InTrTr_comp->Visible = False;
	}
	else { // fah: garantizar que se grabe el valor default del emisor, cuando asi lo requiera la transacc.
		if (strlen($goTrans->f("cla_EmiDefault")) >1 )	$InTrTr_comp->com_Emisor->SetValue($goTrans->f("cla_EmiDefault"));
		if ((CCGetParam("com_RegNumero", False) == False)) {
		   	$InTrTr_topline->lbTituloComp->SetValue("AGREGAR UN COMPROBANTE ");
		   	$InTrTr_comp->Button_Delete->Visible = False;
		   	$InTrTr_comp->com_Usuario->SetValue($_SESSION["g_user"]);
			$InTrTr_comp->com_TipoComp->SetValue($InTrTr_topline->pTipoComp->GetValue());
			$InTrTr_comp->ro_NumComp->Visible = false;	
            $InTrTr_comp->EditMode=false;	
//			$InTrTr_comp->com_Emisor->SetValue($DBdatos->f("cla_EmiDefault"));
			$InTrTr_comp->com_Receptor->SetValue($goTrans->f("cla_RecDefault"));
		   }
		else  {
			$InTrTr_topline->lbTituloComp->SetValue("MODIFICANDO UN COMPROBANTE ");
			$InTrTr_comp->com_NumComp->Visible = false;  // En edicion, no se puede cambiar 
			}
		$jsCmd="JavaScript:alert('No DEBE CAMBIAR EL EMISOR'); return false";
		if ($goTrans->f('cla_EmiDefault') >0 ) $Tpl->SetVar("lock_flag",     "onClick=" );
		$Tpl->SetVar("cla_IndCheque",     $goTrans->f('cla_IndCheque'));
        $Tpl->SetVar("cla_ReqReferencia", $goTrans->f('cla_ReqReferencia'));
        $Tpl->SetVar("cla_ReqSemana",     $goTrans->f('cla_ReqSemana'));
        $Tpl->SetVar("cla_ReqCantidad",   $goTrans->f('cla_ReqCantidad'));
        $Tpl->SetVar("cla_ReqCantidad",   $goTrans->f('cla_ReqCantidad'));
        $Tpl->SetVar("cla_PreFijo",   	  $goTrans->f('cla_PreFijo'));
        $Tpl->SetVar("cla_CosFijo",   	  $goTrans->f('cla_CosFijo'));
        $Tpl->SetVar("cla_LisPrecios",    $goTrans->f('cla_LisPrecios'));
        $Tpl->SetVar("cla_LisCostos",     $goTrans->f('cla_LisCostos'));
        $Tpl->SetVar("cla_CtaOrigen",     $goTrans->f('cla_CtaOrigen'));
        $Tpl->SetVar("cla_CtaDestino",    $goTrans->f('cla_CtaDestino'));
        $Tpl->SetVar("cla_AuxOrigen",     $goTrans->f('cla_AuxOrigen'));
        $Tpl->SetVar("cla_AuxDestino",    $goTrans->f('cla_AuxDestino'));
		}
    return $InTrTr_comp_BeforeShow;
}
//InTrTr_comp_AfterInsert @70-DDC87FA9
function InTrTr_comp_AfterInsert()
{
    global $InTrTr_comp;
    global $Redirect;
    $InTrTr_comp_AfterInsert = true;
	$db = new clsDBdatos();
    $SQL = "SELECT LAST_INSERT_ID() as id";
    $db->query($SQL);
    $Result = $db->next_record();
	
    if ($Result) {
		$InTrTr_comp->hdRegNumero->SetValue($db->f("id"));
     	$Redirect .= "&com_RegNumero=" . $db->f("id");
    }
    unset($db);
/*
echo $InTrTr_comp->hdRegNumero->GetValue();
echo $Redirect;
*/
    InTrTr_Bitacora('I');
    return $InTrTr_comp_AfterInsert;
}
//InTrTr_comp_OnValidate @70-D5DE4809
function InTrTr_comp_OnValidate()
{
    global $InTrTr_comp;
	global $DBdatos;
    $InTrTr_comp_OnValidate = true;
	if (!$InTrTr_comp->EditMode) {
		if  (!($InTrTr_comp->com_NumComp->GetValue() > 0))  {
			$InTrTr_comp->Errors->addError("Debe Definir un Número de Comprobante");
		}
	}
//	$alFecCon=$InTrTr_comp->com_FecContab[0];
//	$alFecVen=$InTrTr_comp->com_FecVencim[0];
//	$alFecTra=$InTrTr_comp->com_FecTrans[0];
	$alFecCon=$InTrTr_comp->com_FecContab->GetValue();
	$alFecVen=$InTrTr_comp->com_FecVencim->GetValue();
	$alFecTra=$InTrTr_comp->com_FecTrans->GetValue();
//	print_r($alFecCon);
	$ilFecCon=$alFecCon[0];
	$ilFecVen=$alFecVen[0];
	$ilFecTra=$alFecTra[0];
 	if ($ilFecCon > $ilFecVen)
		$InTrTr_comp->Errors->addError("La Fecha de Vencimiento Debe ser mayor que la fecha Contable");

 	if ($ilFecVen < $ilFecTra) 
		$InTrTr_comp->Errors->addError("La Fecha de Vencimiento Debe ser mayor que la fecha de Emisión");
	$alFecContab = $InTrTr_comp->com_FecContab->GetValue();	

	$tlSql ="SELECT per_PerContable, per_Estado ".
					"FROM conperiodos ".
					"WHERE per_Aplicacion = 'CO' AND date_format('" . 
							$alFecContab[1] . "-" . $alFecContab[2] . "-" . $alFecContab[3] . "', '%Y-%m-%d') BETWEEN per_fecinicial AND per_fecfinal ";

//	$DBdatos = new clsDBdatos;
//echo $tlSql;
	$DBdatos->Query($tlSql);
	$rsPer = $DBdatos->Next_Record();
	if (!$rsPer) {
		$InTrTr_comp->Errors->addError("NO ESTA DEFINIDO EL PERIODO CONTABLE PARA LA FECHA DE CONTABILIZACION.");
		$InTrTr_comp_com_FecContab_OnValidate = false;
		}
	else {
		$liEstado = $DBdatos->f("per_Estado");
		if ($liEstado < 0 or $liestado > 95) {
			$InTrTr_comp->Errors->addError("La Fecha de Contabilizaciòn Está en un Período Cerrado");
		$InTrTr_comp_com_FecContab_OnValidate = false;
			return $InTrTr_comp_OnValidate;
		}
		$InTrTr_comp->com_NumPeriodo->SetValue($DBdatos->f("per_PerContable"));
	}
	$slCompSql = "com_Empresa=0 AND com_Establecim= " .  	    //fah03/03/09 
	    (isset($InTrTr_comp->com_Emisor)? $InTrTr_comp->com_Emisor->GetValue(): 0) .
	    "AND com_PtoEmision=  1" .	
	    " AND com_TipoComp = '" . $InTrTr_comp->com_TipoComp->GetValue() .
	    "'  AND com_Numcomp = " . $InTrTr_comp->com_NumComp->GetValue();
	if (CCGetParam("com_RegNumero", 0) != 0 ){								// Al modificar, evitar que se intente grabar un numero ya ocupado
        if ($InTrTr_comp->hdRegNumero->GetValue() > 0)                                          // fah
            $slCompSql .= " AND com_Regnumero <> " . $InTrTr_comp->hdRegNumero->GetValue();
    }
//obsafe_print_r($InTrTr_comp->ds);    
    if ($InTrTr_comp->com_NumComp->GetValue() == 0) { // Si viene un cero com numero de comprobte
    		$tlSql = " SELECT max(com_NumComp) + 1 as secue ".
    				" FROM concomprobantes ".
    				" WHERE " .
				"com_Empresa=0 AND com_Establecim= " .  	    //fah03/03/09 
				(isset($InTrTr_comp->com_Emisor)? $InTrTr_comp->com_Emisor->GetValue(): 0) .
				"AND com_PtoEmision=  1" .	
				" AND com_TipoComp = '" . $InTrTr_comp->com_TipoComp->GetValue() ;
    		$DBdatos->Query($tlSql);
    		$rsPer = $DBdatos->Next_Record();
    		$ilNumcomp  = $DBdatos->f("secue");
            if (!$rsPer || is_null($ilNumcomp)) $ilNumcomp = 1;
            $InTrTr_comp->com_NumComp->SetValue($ilNumcomp);
            $InTrTr_comp_OnValidate = true;
    }
    else {  //                                          Si viene digitado un numero de comprobante
		if ( fExisteComp($slCompSql)) {
                $InTrTr_comp->Errors->addError("EL NUMERO DE COMPROBANTE YA FUE UTILIZADO, MODIFIQUELO PARA CONTINUAR");
                $InTrTr_comp_OnValidate = false;
            }
        else
            $InTrTr_comp_OnValidate = true;
        }
	if (! $InTrTr_comp_OnValidate)	
		   $InTrTr_comp->Errors->addError( $ll . "NO SE PUDO ESTABLECER LA SIGUIENTE SECUENCIA DE COMPROBANTE. DEFINA EL NUMERO INICIAL");
//	unset($DBdatos);
    return $InTrTr_comp_OnValidate;
}
function fExisteComp($pCond){
    global $DBdatos;
    global $InTrTr_comp;
	$tlSql = " SELECT com_NumComp as secue ".
			 " FROM concomprobantes ";
	if ($pCond)
	   $tlSql .= " WHERE " . $pCond;
	$DBdatos->Query($tlSql);
	$rsPer = $DBdatos->Next_Record();
    if ($rsPer ) return $DBdatos->f("secue");
    else return false;
}
//InTrTr_comp_BeforeInsert @70-69D03064
function InTrTr_comp_BeforeInsert()
{
    $InTrTr_comp_BeforeInsert = true;
    global $InTrTr_comp;
    global $DBdatos;
//    $InTrTr_comp->com_Emisor->SetValue($DBdatos->f("cla_EmiDefault"));
		if (!fValidAcceso("","ADD","")) {
		  $InTrTr_comp->Errors->addError("UD NO POSEE PRIVILEGIOS DE ADICION EN ESTE MODULO");
        }
    return $InTrTr_comp_BeforeInsert;
}
//InTrTr_comp_BeforeUpdate @70-8F38D0C1
function InTrTr_comp_BeforeUpdate()
{
    $InTrTr_comp_BeforeUpdate = true;
    global $InTrTr_comp;
		if (!fValidAcceso("","UPD","")) {
		  $InTrTr_comp->Errors->addError("UD NO POSEE PRIVILEGIOS DE MODIFICACION EN ESTE MODULO");
        }
    InTrTr_Bitacora('U');
    return $InTrTr_comp_BeforeUpdate;
}
function InTrTr_comp_AfterUpdate()
{
    $InTrTr_comp_AfterUpdate = true;
    global $InTrTr_comp;
    global $DBdatos;
    if (CCGetParam("pTipoComp", '') == "EP") {
	    $tlSql = " UPDATE concomprobantes JOIN condetalle ON det_regnumero = com_regnumero  
	    				 set com_CodReceptor = "  . $InTrTr_comp->ds->cp['com_CodReceptor']->GetDBValue() . ", 
	    				     com_FecContab   = '" . $InTrTr_comp->ds->cp['com_FecContab']->GetDBValue() . "',
	    				     com_FecTrans   = '" . $InTrTr_comp->ds->cp['com_FecContab']->GetDBValue() . "',
	    				     com_FecVencim   = '" . $InTrTr_comp->ds->cp['com_FecContab']->GetDBValue() . "',
							 com_Receptor    = '" . $InTrTr_comp->ds->cp['com_Receptor']->GetDBValue() . "',
							 com_NumPeriodo  = '" . $InTrTr_comp->ds->cp['com_NumPeriodo']->GetDBValue() . "',
							 com_RefOperat  = '"  . $InTrTr_comp->ds->cp['com_RefOperat']->GetDBValue() . "', 
							 det_idauxiliar = "  . $InTrTr_comp->ds->cp['com_CodReceptor']->GetDBValue() . "
				   WHERE  com_TipoComp = 'TR' AND com_NumComp = " . $InTrTr_comp->ro_NumComp->GetValue() . " AND 
				 		  det_codcuenta = '1141025' " ;
		$DBdatos->Query($tlSql);
//		die($tlSql);
	}
}
//End Close InTrTr_comp_BeforeUpdate
function InTrTr_comp_AfterDelete(){
    global $DBdatos;
    global $InTrTr_comp;
    InTrTr_Bitacora('D');
    if (CCGetParam("pTipoComp", '') == "EP") {
	    $tlSql = " DELETE FROM  concomprobantes 
				   WHERE  com_TipoComp = 'TR' AND com_NumComp = " . $InTrTr_comp->ro_NumComp->GetValue() ;
		}			
	$DBdatos->Query($tlSql);
}

/**
*   Entrada en la bitacora de seguridad, anotando las condiciones de grabacion
**/
function InTrTr_Bitacora($pTipo='I'){
/*
    global $gfValTotal;
    global $gfCosTotal;
    global $gfCanTotal; */
    global $InTrTr_comp;

    $alFecContab = $InTrTr_comp->com_FecContab->GetValue(); // Fecha en formato de arreglo
    $slTxt = "  E: " . $InTrTr_comp->com_Emisor->GetValue() .
             "  R: " . $InTrTr_comp->com_CodReceptor->GetValue() .
             "  F: " . $alFecContab[1] . "-" . $alFecContab[2] . "-" . $alFecContab[3]. " / RO:" . $InTrTr_comp->com_RefOperat->GetValue();
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
    $ilNumComp = ($InTrTr_comp->com_NumComp->GetValue())?$InTrTr_comp->com_NumComp->GetValue():$InTrTr_comp->ro_NumComp->GetValue();
    fRegistroBitacora(CCGetParam('com_TipoComp', ''), // Tipo Comprobante
                      $ilNumComp,// Numero Comprobante
//                      CCGetParam('com_RegNumero', -1), 
                      $_SESSION['g_user'], // Usuario
                      $pAnot = $slOperac . $slTxt,
                      0,
                      0,
                      0,
                      " " ,
                      0,
                      0);
}

?>
