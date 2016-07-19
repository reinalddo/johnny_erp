<?php
$gsTipoAux = "act_CodAuxiliar"; // Variable para determinar el tipo de auxiliar que procesara la ventana de variables
//BindEvents Method @1-A67036D0
function BindEvents()
{
    global $CoAdAc_mant;
    $CoAdAc_mant->btNuevo->CCSEvents["BeforeShow"] = "CoAdAc_mant_btNuevo_BeforeShow";
    $CoAdAc_mant->CCSEvents["BeforeShow"] = "CoAdAc_mant_BeforeShow";
    $CoAdAc_mant->CCSEvents["BeforeInsert"] = "CoAdAc_mant_BeforeInsert";
    $CoAdAc_mant->CCSEvents["AfterInsert"] = "CoAdAc_mant_AfterInsert";
}
//End BindEvents Method



//DEL  // -------------------------
//DEL      global $CoAdAc_mant;
//DEL     $CoAdAc_mant->btNuevo->Visible = $CoAdAc_mant->EditMode;
//DEL  // -------------------------


//DEL  // -------------------------
//DEL  // -------------------------
// -------------------------

//CoAdAc_mant_btNuevo_BeforeShow @149-E1CE7921
function CoAdAc_mant_btNuevo_BeforeShow()
{
    $CoAdAc_mant_btNuevo_BeforeShow = true;
//End CoAdAc_mant_btNuevo_BeforeShow

//Custom Code @150-B92BB06F
// -------------------------
    global $CoAdAc_mant;
    global $CoAdAc_mant;
    $CoAdAc_mant->btNuevo->Visible = $CoAdAc_mant->EditMode;
// -------------------------
//End Custom Code

//Close CoAdAc_mant_btNuevo_BeforeShow @149-4B72183F
    return $CoAdAc_mant_btNuevo_BeforeShow;
}
//End Close CoAdAc_mant_btNuevo_BeforeShow

//CoAdAc_mant_BeforeShow @115-AB9A6EE4
function CoAdAc_mant_BeforeShow()
{
    $CoAdAc_mant_BeforeShow = true;
//End CoAdAc_mant_BeforeShow

//Custom Code @152-B92BB06F
// -------------------------
    global $CoAdAc_mant;
    global $CoAdAc_mant;
  	global $CoAdAc_cate;
  
  	if (!$CoAdAc_mant->EditMode) {
  		$CoAdAc_mant->lbTitulo->SetValue("AGREGAR AUXILIAR NUEVO");
  		$CoAdAc_cate->Visible = False;
  		}
  	else {
  			$CoAdAc_mant->lbTitulo->SetValue('EDITAR / MODIFICAR AUXILIAR');
  			$CoAdAc_mant->lbCategAux->Visible = false;
  		}
// -------------------------
//End Custom Code

//Close CoAdAc_mant_BeforeShow @115-2C7EB46A
    return $CoAdAc_mant_BeforeShow;
}
//End Close CoAdAc_mant_BeforeShow

//CoAdAc_mant_BeforeInsert @115-5ED775CC
function CoAdAc_mant_BeforeInsert()
{
    $CoAdAc_mant_BeforeInsert = true;
//End CoAdAc_mant_BeforeInsert

//Custom Code @153-B92BB06F
// -------------------------
    global $CoAdAc_mant;
 	if (!fValidAcceso("","","ADD")) {
      	fMensaje ("UD NO TIENE ACCESO A ESTA FUNCION", 1);
          die(); }
     $ilNvoAux =fPxmoAuxiliar($CoAdAc_mant->lbCategAux->GetValue(), "conactivos", "act_CodAuxiliar");
     if ($ilNvoAux < 0 ) {
  	    $CoAdAc_mant->act_CodAuxiliar->Errors->addError("Secuencia de la Categoria Agotada");
  	    return false;
  	}
    $CoAdAc_mant->act_CodAuxiliar->SetValue($ilNvoAux);// -------------------------
// -------------------------
//End Custom Code

//Close CoAdAc_mant_BeforeInsert @115-09931A63
    return $CoAdAc_mant_BeforeInsert;
}
//End Close CoAdAc_mant_BeforeInsert

//CoAdAc_mant_AfterInsert @115-9320803C
function CoAdAc_mant_AfterInsert()
{
    $CoAdAc_mant_AfterInsert = true;
//End CoAdAc_mant_AfterInsert

//Custom Code @154-B92BB06F
// -------------------------
    global $CoAdAc_mant;
  	if (!$CoAdAc_mant->EditMode) {
          $clDB = New clsDBdatos;
  		$tlSql ="INSERT IGNORE INTO concategorias VALUES ( " . $CoAdAc_mant->lbCategAux->Value . " , " .
  				$CoAdAc_mant->act_CodAuxiliar->Value  . ", '" . date("Y-m-d"). "', 1)";
  		$clDB->Query($tlSql);
  	}
	// -------------------------
//End Custom Code

//Close CoAdAc_mant_AfterInsert @115-D4E6CC3D
    return $CoAdAc_mant_AfterInsert;
}
//End Close CoAdAc_mant_AfterInsert
?>
