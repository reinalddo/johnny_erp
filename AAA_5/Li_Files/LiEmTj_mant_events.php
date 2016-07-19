<?php
//BindEvents Method @1-7B0A6711
function BindEvents()
{
 global $liqtarjacabec;
 global $liqtarjadetal;
 $liqtarjacabec->tar_NumTarja->CCSEvents["BeforeShow"] = "liqtarjacabec_tar_NumTarja_BeforeShow";
 $liqtarjacabec->tac_Usuario->CCSEvents["BeforeShow"] = "liqtarjacabec_tac_Usuario_BeforeShow";
 $liqtarjacabec->CCSEvents["BeforeShow"] = "liqtarjacabec_BeforeShow";
 $liqtarjacabec->ds->CCSEvents["BeforeExecuteDelete"] = "liqtarjacabec_ds_BeforeExecuteDelete";
 $liqtarjacabec->ds->CCSEvents["BeforeExecuteUpdate"] = "liqtarjacabec_ds_BeforeExecuteUpdate";
 $liqtarjacabec->CCSEvents["AfterInsert"] = "liqtarjacabec_AfterInsert";
 $liqtarjadetal->CCSEvents["BeforeShow"] = "liqtarjadetal_BeforeShow";
}
//End BindEvents Method

//DEL  // -------------------------
//DEL      global $liqtarjacabec;
//DEL  	$liqtarjacabec->tac_Usuario->SetValue($_SERVER["g_user"]);
//DEL  // -------------------------

//liqtarjacabec_tar_NumTarja_BeforeShow @398-66BD3D91
function liqtarjacabec_tar_NumTarja_BeforeShow()
{
 $liqtarjacabec_tar_NumTarja_BeforeShow = true;
//End liqtarjacabec_tar_NumTarja_BeforeShow

//Custom Code @399-9A948823
// -------------------------
 global $liqtarjacabec;
 // Write your own code here.
// -------------------------
//End Custom Code

//Close liqtarjacabec_tar_NumTarja_BeforeShow @398-5D30FC24
 return $liqtarjacabec_tar_NumTarja_BeforeShow;
}
//End Close liqtarjacabec_tar_NumTarja_BeforeShow

//liqtarjacabec_tac_Usuario_BeforeShow @455-1E70B1D9
function liqtarjacabec_tac_Usuario_BeforeShow()
{
 $liqtarjacabec_tac_Usuario_BeforeShow = true;
//End liqtarjacabec_tac_Usuario_BeforeShow

//Custom Code @456-9A948823
// -------------------------
 global $liqtarjacabec;
 //echo "---------------<br><br><br>" ; print_r($liqtarjacabec->tac_Usuario); 	//#fah 31/01/08
 //if (strlen($liqtarjacabec->tac_Usuario->Value) < 1)  $liqtarjacabec->tac_Usuario->setValue(fUsuario()); //#fah 31/01/08
 // Write your own code here.
// -------------------------
//End Custom Code

//Close liqtarjacabec_tac_Usuario_BeforeShow @455-09D17400
 return $liqtarjacabec_tac_Usuario_BeforeShow;
}
//End Close liqtarjacabec_tac_Usuario_BeforeShow

//liqtarjacabec_BeforeShow @396-C2204B2D
function liqtarjacabec_BeforeShow()
{
 $liqtarjacabec_BeforeShow = true;
//End liqtarjacabec_BeforeShow

//Custom Code @464-9A948823
// -------------------------
 global $liqtarjacabec;
 // Write your own code here.
// -------------------------
    if (CCGetParam("tar_NumTarja", "0") ==0)
        $liqtarjacabec->EditMode=false;
 //End Custom Code

//Close liqtarjacabec_BeforeShow @396-FA3F960C
 return $liqtarjacabec_BeforeShow;
}
//End Close liqtarjacabec_BeforeShow

//DEL  // -------------------------
//DEL      global $liqtarjacabec;
//DEL  // -------------------------

//liqtarjacabec_ds_BeforeExecuteDelete @396-864378C6
function liqtarjacabec_ds_BeforeExecuteDelete()
{
 $liqtarjacabec_ds_BeforeExecuteDelete = true;
//End liqtarjacabec_ds_BeforeExecuteDelete

//Custom Code @547-9A948823
// -------------------------
    global $liqtarjacabec;
	if (!fValidAcceso("","DEL","")) {
    	fMensaje ("UD. NO TIENE ACCESO A ELIMINAR", 1);
        die();
		}
// -------------------------
//End Custom Code

//Close liqtarjacabec_ds_BeforeExecuteDelete @396-B7FAF1C5
 return $liqtarjacabec_ds_BeforeExecuteDelete;
}
//End Close liqtarjacabec_ds_BeforeExecuteDelete

//liqtarjacabec_ds_BeforeExecuteUpdate @396-285CDF9B
function liqtarjacabec_ds_BeforeExecuteUpdate()
{
 $liqtarjacabec_ds_BeforeExecuteUpdate = true;
//End liqtarjacabec_ds_BeforeExecuteUpdate

//Custom Code @548-9A948823
// -------------------------
    global $liqtarjacabec;
	$CCSForm="liqtarjacabec";
	if (!fValidAcceso("","UPD","")) {
    	fMensaje ("UD. NO TIENE ACCESO A ACTUALIZAR", 1);
		${$CCSForm}->ds->SQL="";					//			para evitar que se jecute el query
		${$CCSForm}->Errors->AddError("NO SE GRABO ESTE REGISTRO  ");
//		die();
		}
// -------------------------
//End Custom Code

//Close liqtarjacabec_ds_BeforeExecuteUpdate @396-2BDE57B4
 return $liqtarjacabec_ds_BeforeExecuteUpdate;
}
//End Close liqtarjacabec_ds_BeforeExecuteUpdate

//liqtarjacabec_AfterInsert @396-A83899D7
function liqtarjacabec_AfterInsert()
{
 $liqtarjacabec_AfterInsert = true;
//End liqtarjacabec_AfterInsert

//Custom Code @549-9A948823
// -------------------------
 global $liqtarjacabec;
 global $Redirect;
 $Redirect .=  "tar_NumTarja=" . CCGetParam("tar_NumTarja", "0");
// $liqtarjacabec->Errors->AddError("TARJA AÑADIDA CORRECTAMENTE");
// -------------------------
//End Custom Code

//Close liqtarjacabec_AfterInsert @396-70E14972
 return $liqtarjacabec_AfterInsert;
}
//End Close liqtarjacabec_AfterInsert

//liqtarjadetal_BeforeShow @292-C61A8D8B
function liqtarjadetal_BeforeShow()
{
 $liqtarjadetal_BeforeShow = true;
//End liqtarjadetal_BeforeShow

//Custom Code @389-4377885F
// -------------------------
    global $liqtarjadetal;
    if ($liqtarjadetal->ds->AbsolutePage < $liqtarjadetal->ds->PageCount())  $liqtarjadetal->EmptyRows = 0;
	if (!(CCGetParam("tar_NumTarja", "") <> 0)) $liqtarjadetal->Visible = false;

// -------------------------
//End Custom Code

//Close liqtarjadetal_BeforeShow @292-01473D48
 return $liqtarjadetal_BeforeShow;
}
//End Close liqtarjadetal_BeforeShow

?>
