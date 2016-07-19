<?php
//BindEvents Method @1-0E8FA9D8
function BindEvents()
{
 global $liqprocesos;
 $liqprocesos->Button_Update->CCSEvents["BeforeShow"] = "liqprocesos_Button_Update_BeforeShow";
 $liqprocesos->CCSEvents["BeforeShow"] = "liqprocesos_BeforeShow";
 $liqprocesos->CCSEvents["AfterUpdate"] = "liqprocesos_AfterUpdate";
}
//End BindEvents Method

//DEL  // -------------------------
//DEL   global $liqprocesos;
//DEL  // -------------------------

//liqprocesos_Button_Update_BeforeShow @260-22E0849E
function liqprocesos_Button_Update_BeforeShow()
{
 $liqprocesos_Button_Update_BeforeShow = true;
//End liqprocesos_Button_Update_BeforeShow

//Custom Code @348-A35EB8FD
// -------------------------
 global $liqprocesos;
  	$opCode=CCGetParam('pOpCode',false);
	if ($opCode  == 'R') { // 			Instruccion de retornar a la pagina de procesamiento
		$liqprocesos->Button_Insert->Visible = False;
		$liqprocesos->Button_Update->Visible = False;
		$liqprocesos->Button_Delete->Visible = False;
		}
 // Write your own code here.
// -------------------------
//End Custom Code

//Close liqprocesos_Button_Update_BeforeShow @260-11EFDAE1
 return $liqprocesos_Button_Update_BeforeShow;
}
//End Close liqprocesos_Button_Update_BeforeShow

//liqprocesos_BeforeShow @258-DBF82709
function liqprocesos_BeforeShow()
{
 $liqprocesos_BeforeShow = true;
//End liqprocesos_BeforeShow

//Custom Code @346-A35EB8FD
// -------------------------

// -------------------------
//End Custom Code

//Close liqprocesos_BeforeShow @258-25E5E39C
 return $liqprocesos_BeforeShow;
}
//End Close liqprocesos_BeforeShow

//liqprocesos_AfterUpdate @258-71FED20E
function liqprocesos_AfterUpdate()
{
 $liqprocesos_AfterUpdate = true;
//End liqprocesos_AfterUpdate

//Custom Code @349-A35EB8FD
// -------------------------
 global $liqprocesos;
  	$opCode=CCGetParam('pOpCode',false);

//End Custom Code

//Close liqprocesos_AfterUpdate @258-4F18C1CC
 return $liqprocesos_AfterUpdate;
}
//End Close liqprocesos_AfterUpdate


?>
