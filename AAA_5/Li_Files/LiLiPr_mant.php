<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
include(RelativePath . "/LibPhp/SegLib.php");
  
//End Include Common Files

Class clsRecordliqprocesos { //liqprocesos Class @258-0E51ABC4

//Variables @258-4A82E0A3

 // Public variables
 var $ComponentName;
 var $HTMLFormAction;
 var $PressedButton;
 var $Errors;
 var $ErrorBlock;
 var $FormSubmitted;
 var $FormEnctype;
 var $Visible;
 var $Recordset;

 var $CCSEvents = "";
 var $CCSEventResult;

 var $InsertAllowed;
 var $UpdateAllowed;
 var $DeleteAllowed;
 var $ds;
 var $EditMode;
 var $ValidatingControls;
 var $Controls;

 // Class variables
//End Variables

//Class_Initialize Event @258-3031648D
 function clsRecordliqprocesos()
 {

  global $FileName;
  $this->Visible = true;
  $this->Errors = new clsErrors();
  $this->ErrorBlock = "Record liqprocesos/Error";
  $this->ds = new clsliqprocesosDataSource();
  $this->InsertAllowed = true;
  $this->UpdateAllowed = true;
  $this->DeleteAllowed = true;
  if($this->Visible)
  {
   $this->ComponentName = "liqprocesos";
   $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
   if(sizeof($CCSForm) == 1)
    $CCSForm[1] = "";
   list($FormName, $FormMethod) = $CCSForm;
   $this->EditMode = ($FormMethod == "Edit");
   $this->FormEnctype = "application/x-www-form-urlencoded";
   $this->FormSubmitted = ($FormName == $this->ComponentName);
   $Method = $this->FormSubmitted ? ccsPost : ccsGet;
   $this->pro_AnoProceso = new clsControl(ccsTextBox, "pro_AnoProceso", "Pro Ano Proceso", ccsInteger, "", CCGetRequestParam("pro_AnoProceso", $Method));
   $this->pro_AnoProceso->Required = true;
   $this->pro_Semana = new clsControl(ccsTextBox, "pro_Semana", "Pro Semana", ccsInteger, "", CCGetRequestParam("pro_Semana", $Method));
   $this->pro_Semana->Required = true;
   $this->pre_RefOperativa = new clsControl(ccsListBox, "pre_RefOperativa", "Embarque", ccsInteger, "", CCGetRequestParam("pre_RefOperativa", $Method));
   $this->pre_RefOperativa->DSType = dsSQL;
   list($this->pre_RefOperativa->BoundColumn, $this->pre_RefOperativa->TextColumn, $this->pre_RefOperativa->DBFormat) = array("emb_RefOperativa", "txt_Descrip", "");
   $this->pre_RefOperativa->ds = new clsDBdatos();
   $this->pre_RefOperativa->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
   $this->pre_RefOperativa->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
   $this->pre_RefOperativa->ds->wp = new clsSQLParameters();
   $this->pre_RefOperativa->ds->wp->AddParameter("1", "urlpro_Ano", ccsInteger, "", "", $this->pre_RefOperativa->ds->Parameters["urlpro_Ano"], 0, false);
   $this->pre_RefOperativa->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_RefOperativa->ds->Parameters["urlpro_Semana"], 0, false);
   $this->pre_RefOperativa->ds->SQL = "SELECT emb_RefOperativa, concat_ws('- ', buq_Descripcion, emb_NumViaje,  emb_SemInicio, emb_SemTermino, par_Descripcion ) as txt_Descrip " .
   "FROM (liqembarques LEFT JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque) LEFT JOIN genparametros ON liqembarques.emb_CodMarca = genparametros.par_Secuencia " .
   "WHERE emb_AnoOperacion = " . $this->pre_RefOperativa->ds->SQLValue($this->pre_RefOperativa->ds->wp->GetDBValue("1"), ccsInteger) . " and  " .
   "" . $this->pre_RefOperativa->ds->SQLValue($this->pre_RefOperativa->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino AND " .
   "par_Clave = 'IMARCA'";
   $this->pre_CodProducto = new clsControl(ccsListBox, "pre_CodProducto", "Codigo de Producto", ccsInteger, "", CCGetRequestParam("pre_CodProducto", $Method));
   $this->pre_CodProducto->DSType = dsSQL;
   list($this->pre_CodProducto->BoundColumn, $this->pre_CodProducto->TextColumn, $this->pre_CodProducto->DBFormat) = array("tad_CodProducto", "txt_Descrip", "");
   $this->pre_CodProducto->ds = new clsDBdatos();
   $this->pre_CodProducto->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
   $this->pre_CodProducto->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
   $this->pre_CodProducto->ds->wp = new clsSQLParameters();
   $this->pre_CodProducto->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_CodProducto->ds->Parameters["urlpro_Ano"], -1, false);
   $this->pre_CodProducto->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_CodProducto->ds->Parameters["urlpro_Semana"], -1, false);
   $this->pre_CodProducto->ds->SQL = "SELECT  distinct tad_CodProducto, concat(act_Descripcion, act_Descripcion1) as txt_Descrip " .
   "FROM ((liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa)  " .
   "      JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja) " .
   "     LEFT JOIN conactivos ON act_CodAuxiliar = tad_CodProducto " .
   "WHERE emb_AnoOperacion = " . $this->pre_CodProducto->ds->SQLValue($this->pre_CodProducto->ds->wp->GetDBValue("1"), ccsText) . " and  " .
   "" . $this->pre_CodProducto->ds->SQLValue($this->pre_CodProducto->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino";
   $this->pre_CodMarca = new clsControl(ccsListBox, "pre_CodMarca", "Pre Cod Marca", ccsInteger, "", CCGetRequestParam("pre_CodMarca", $Method));
   $this->pre_CodMarca->DSType = dsSQL;
   list($this->pre_CodMarca->BoundColumn, $this->pre_CodMarca->TextColumn, $this->pre_CodMarca->DBFormat) = array("tad_CodMarca", "par_Descripcion", "");
   $this->pre_CodMarca->ds = new clsDBdatos();
   $this->pre_CodMarca->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
   $this->pre_CodMarca->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
   $this->pre_CodMarca->ds->wp = new clsSQLParameters();
   $this->pre_CodMarca->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_CodMarca->ds->Parameters["urlpro_Ano"], -1, false);
   $this->pre_CodMarca->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_CodMarca->ds->Parameters["urlpro_Semana"], -1, false);
   $this->pre_CodMarca->ds->SQL = "SELECT  distinct tad_CodMarca, par_Descripcion " .
   "FROM ((liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa)  " .
   "     JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja) " .
   "     LEFT JOIN genparametros ON par_Secuencia = tad_CodMarca " .
   "WHERE emb_AnoOperacion = " . $this->pre_CodMarca->ds->SQLValue($this->pre_CodMarca->ds->wp->GetDBValue("1"), ccsText) . " and  " .
   "" . $this->pre_CodMarca->ds->SQLValue($this->pre_CodMarca->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino AND " .
   "par_Clave = 'IMARCA' " .
   "";
   $this->pre_CodMarca->ds->Order = "2";
   $this->pre_CodEmpaque = new clsControl(ccsListBox, "pre_CodEmpaque", "Pre Cod Empaque", ccsInteger, "", CCGetRequestParam("pre_CodEmpaque", $Method));
   $this->pre_CodEmpaque->DSType = dsSQL;
   list($this->pre_CodEmpaque->BoundColumn, $this->pre_CodEmpaque->TextColumn, $this->pre_CodEmpaque->DBFormat) = array("tad_CodCaja", "caj_Descripcion", "");
   $this->pre_CodEmpaque->ds = new clsDBdatos();
   $this->pre_CodEmpaque->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
   $this->pre_CodEmpaque->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
   $this->pre_CodEmpaque->ds->wp = new clsSQLParameters();
   $this->pre_CodEmpaque->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_CodEmpaque->ds->Parameters["urlpro_Ano"], -1, false);
   $this->pre_CodEmpaque->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_CodEmpaque->ds->Parameters["urlpro_Semana"], -1, false);
   $this->pre_CodEmpaque->ds->SQL = "SELECT  distinct tad_CodCaja, caj_Descripcion " .
   "FROM ((liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa)  " .
   "      JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja) " .
   "      JOIN liqcajas ON caj_CodCaja = tad_CodCaja " .
   "WHERE emb_AnoOperacion = " . $this->pre_CodEmpaque->ds->SQLValue($this->pre_CodEmpaque->ds->wp->GetDBValue("1"), ccsText) . " and  " .
   "" . $this->pre_CodEmpaque->ds->SQLValue($this->pre_CodEmpaque->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino  " .
   "";
   $this->pre_CodEmpaque->ds->Order = "2";
   $this->pre_GruLiquidacion = new clsControl(ccsListBox, "pre_GruLiquidacion", "Pre Gru Liquidacion", ccsInteger, "", CCGetRequestParam("pre_GruLiquidacion", $Method));
   $this->pre_GruLiquidacion->DSType = dsSQL;
   list($this->pre_GruLiquidacion->BoundColumn, $this->pre_GruLiquidacion->TextColumn, $this->pre_GruLiquidacion->DBFormat) = array("per_codAuxiliar", "txt_Descrip", "");
   $this->pre_GruLiquidacion->ds = new clsDBdatos();
   $this->pre_GruLiquidacion->ds->SQL = "SELECT per_codAuxiliar, concat(left(per_Apellidos,12),' ', concat(per_Nombres)) as txt_Descrip  " .
   "FROM concategorias INNER JOIN conpersonas ON cat_Categoria = 51 AND concategorias.cat_CodAuxiliar = conpersonas.per_CodAuxiliar " .
   "  ";
   $this->pre_GruLiquidacion->ds->Order = "txt_Descrip";
   $this->pre_Zona = new clsControl(ccsListBox, "pre_Zona", "Pre Zona", ccsInteger, "", CCGetRequestParam("pre_Zona", $Method));
   $this->pre_Zona->DSType = dsSQL;
   list($this->pre_Zona->BoundColumn, $this->pre_Zona->TextColumn, $this->pre_Zona->DBFormat) = array("tac_Zona", "par_Descripcion", "");
   $this->pre_Zona->ds = new clsDBdatos();
   $this->pre_Zona->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
   $this->pre_Zona->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
   $this->pre_Zona->ds->wp = new clsSQLParameters();
   $this->pre_Zona->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_Zona->ds->Parameters["urlpro_Ano"], -1, false);
   $this->pre_Zona->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_Zona->ds->Parameters["urlpro_Semana"], -1, false);
   $this->pre_Zona->ds->SQL = "SELECT  distinct tac_Zona, par_Descripcion " .
   "FROM ((liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa)  " .
   "      JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja) " .
   "      JOIN genparametros ON par_secuencia = tac_Zona " .
   "WHERE emb_AnoOperacion = " . $this->pre_Zona->ds->SQLValue($this->pre_Zona->ds->wp->GetDBValue("1"), ccsText) . " and  " .
   "" . $this->pre_Zona->ds->SQLValue($this->pre_Zona->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino AND " .
   "par_CLAVE=\"LSZON\" " .
   "";
   $this->pre_Zona->ds->Order = "2";
   $this->pro_FechaCierre = new clsControl(ccsTextBox, "pro_FechaCierre", "Pro Fecha Cierre", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("pro_FechaCierre", $Method));
   $this->DatePicker_pre_FecInicio = new clsDatePicker("DatePicker_pre_FecInicio", "liqprocesos", "pro_FechaCierre");
   $this->pro_FechaLiquid = new clsControl(ccsTextBox, "pro_FechaLiquid", "Pro Fecha Liquid", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("pro_FechaLiquid", $Method));
   $this->DatePicker_pro_FechaLiquid = new clsDatePicker("DatePicker_pro_FechaLiquid", "liqprocesos", "pro_FechaLiquid");
   $this->pro_TipoComprob = new clsControl(ccsTextBox, "pro_TipoComprob", "Pro Tipo Comprob", ccsText, "", CCGetRequestParam("pro_TipoComprob", $Method));
   $this->pro_CuentaContable = new clsControl(ccsListBox, "pro_CuentaContable", "Pro Cuenta Contable", ccsText, "", CCGetRequestParam("pro_CuentaContable", $Method));
   $this->pro_CuentaContable->DSType = dsSQL;
   list($this->pro_CuentaContable->BoundColumn, $this->pro_CuentaContable->TextColumn, $this->pro_CuentaContable->DBFormat) = array("cue_CodCuenta", "Descr", "");
   $this->pro_CuentaContable->ds = new clsDBdatos();
   $this->pro_CuentaContable->ds->SQL = "SELECT cue_CodCuenta, concat(cue_codcuenta,' ---- ', cue_descripcion) as Descr " .
   "FROM concuentas " .
   "";
   $this->pro_CuentaContable->ds->Order = "1";
   $this->ListBox1 = new clsControl(ccsListBox, "ListBox1", "ListBox1", ccsInteger, "", CCGetRequestParam("ListBox1", $Method));
   $this->ListBox1->DSType = dsSQL;
   list($this->ListBox1->BoundColumn, $this->ListBox1->TextColumn, $this->ListBox1->DBFormat) = array("per_CodAuxiliar", "Descr", "");
   $this->ListBox1->ds = new clsDBdatos();
   $this->ListBox1->ds->SQL = "SELECT per_CodAuxiliar, concat(per_Apellidos, ' ', per_Nombres) as Descr " .
   "FROM conpersonas, concategorias " .
   "WHERE cat_codauxiliar = per_codauxiliar AND  " .
   "cat_categoria in (10, 11) " .
   "UNION " .
   "SELECT -1 as per_CodAuxiliar, '    DE CADA PRODUCTOR. ' as per_Nombres " .
   "UNION  " .
   "SELECT  0 as per_CodAuxiliar, '    N I N G U N O. ' as per_Nombres";
   $this->pro_CodCuenta2 = new clsControl(ccsListBox, "pro_CodCuenta2", "Pro Cuenta Contable", ccsText, "", CCGetRequestParam("pro_CodCuenta2", $Method));
   $this->pro_CodCuenta2->DSType = dsSQL;
   list($this->pro_CodCuenta2->BoundColumn, $this->pro_CodCuenta2->TextColumn, $this->pro_CodCuenta2->DBFormat) = array("cue_CodCuenta", "Descr", "");
   $this->pro_CodCuenta2->ds = new clsDBdatos();
   $this->pro_CodCuenta2->ds->SQL = "SELECT cue_CodCuenta, concat(cue_codcuenta,' ---- ', cue_descripcion) as Descr " .
   "FROM concuentas " .
   "";
   $this->pro_CodCuenta2->ds->Order = "1";
   $this->pro_Auxiliar2 = new clsControl(ccsListBox, "pro_Auxiliar2", "pro_Auxiliar2", ccsInteger, "", CCGetRequestParam("pro_Auxiliar2", $Method));
   $this->pro_Auxiliar2->DSType = dsSQL;
   list($this->pro_Auxiliar2->BoundColumn, $this->pro_Auxiliar2->TextColumn, $this->pro_Auxiliar2->DBFormat) = array("per_CodAuxiliar", "Descr", "");
   $this->pro_Auxiliar2->ds = new clsDBdatos();
   $this->pro_Auxiliar2->ds->SQL = "SELECT per_CodAuxiliar, concat(per_Apellidos, ' ', per_Nombres) as Descr " .
   "FROM conpersonas, concategorias " .
   "WHERE cat_codauxiliar = per_codauxiliar AND  " .
   "cat_categoria in (10, 11) " .
   "UNION " .
   "SELECT -1 as per_CodAuxiliar, '    DE CADA PRODUCTOR. ' as per_Nombres " .
   "UNION  " .
   "SELECT  0 as per_CodAuxiliar, '    N I N G U N O. ' as per_Nombres";
   $this->pro_CodCuenta3 = new clsControl(ccsListBox, "pro_CodCuenta3", "Pro Cuenta Contable", ccsText, "", CCGetRequestParam("pro_CodCuenta3", $Method));
   $this->pro_CodCuenta3->DSType = dsSQL;
   list($this->pro_CodCuenta3->BoundColumn, $this->pro_CodCuenta3->TextColumn, $this->pro_CodCuenta3->DBFormat) = array("cue_CodCuenta", "Descr", "");
   $this->pro_CodCuenta3->ds = new clsDBdatos();
   $this->pro_CodCuenta3->ds->SQL = "SELECT cue_CodCuenta, concat(cue_codcuenta,' ---- ', cue_descripcion) as Descr " .
   "FROM concuentas " .
   "";
   $this->pro_CodCuenta3->ds->Order = "1";
   $this->pro_Auxiliar3 = new clsControl(ccsListBox, "pro_Auxiliar3", "pro_Auxiliar3", ccsInteger, "", CCGetRequestParam("pro_Auxiliar3", $Method));
   $this->pro_Auxiliar3->DSType = dsSQL;
   list($this->pro_Auxiliar3->BoundColumn, $this->pro_Auxiliar3->TextColumn, $this->pro_Auxiliar3->DBFormat) = array("per_CodAuxiliar", "Descr", "");
   $this->pro_Auxiliar3->ds = new clsDBdatos();
   $this->pro_Auxiliar3->ds->SQL = "SELECT per_CodAuxiliar, concat(per_Apellidos, ' ', per_Nombres) as Descr " .
   "FROM conpersonas, concategorias " .
   "WHERE cat_codauxiliar = per_codauxiliar AND  " .
   "cat_categoria in (10, 11) " .
   "UNION " .
   "SELECT -1 as per_CodAuxiliar, '    DE CADA PRODUCTOR. ' as per_Nombres " .
   "UNION  " .
   "SELECT  0 as per_CodAuxiliar, '    N I N G U N O. ' as per_Nombres";
   $this->TextBox1 = new clsControl(ccsTextBox, "TextBox1", "TextBox1", ccsInteger, "", CCGetRequestParam("TextBox1", $Method));
   $this->pre_Usuario = new clsControl(ccsTextBox, "pre_Usuario", "Pre Usuario", ccsText, "", CCGetRequestParam("pre_Usuario", $Method));
   $this->pro_FecRegistro = new clsControl(ccsTextBox, "pro_FecRegistro", "Fecha de Registro", ccsDate, Array("dd", "/", "mmm", "/", "yy", " ", "HH", ":", "nn"), CCGetRequestParam("pro_FecRegistro", $Method));
   $this->Button_Insert = new clsButton("Button_Insert");
   $this->Button_Update = new clsButton("Button_Update");
   $this->Button_Delete = new clsButton("Button_Delete");
   $this->Button_Cancel = new clsButton("Button_Cancel");
   if(!$this->FormSubmitted) {
    if(!is_array($this->ListBox1->Value) && !strlen($this->ListBox1->Value) && $this->ListBox1->Value !== false)
    $this->ListBox1->SetText(0);
    if(!is_array($this->pro_Auxiliar2->Value) && !strlen($this->pro_Auxiliar2->Value) && $this->pro_Auxiliar2->Value !== false)
    $this->pro_Auxiliar2->SetText(0);
   }
  }
 }
//End Class_Initialize Event

//Initialize Method @258-01C86044
 function Initialize()
 {

  if(!$this->Visible)
   return;

  $this->ds->Parameters["urlpro_ID"] = CCGetFromGet("pro_ID", "");
 }
//End Initialize Method

//Validate Method @258-857743FD
 function Validate()
 {
  $Validation = true;
  $Where = "";
  $Validation = ($this->pro_AnoProceso->Validate() && $Validation);
  $Validation = ($this->pro_Semana->Validate() && $Validation);
  $Validation = ($this->pre_RefOperativa->Validate() && $Validation);
  $Validation = ($this->pre_CodProducto->Validate() && $Validation);
  $Validation = ($this->pre_CodMarca->Validate() && $Validation);
  $Validation = ($this->pre_CodEmpaque->Validate() && $Validation);
  $Validation = ($this->pre_GruLiquidacion->Validate() && $Validation);
  $Validation = ($this->pre_Zona->Validate() && $Validation);
  $Validation = ($this->pro_FechaCierre->Validate() && $Validation);
  $Validation = ($this->pro_FechaLiquid->Validate() && $Validation);
  $Validation = ($this->pro_TipoComprob->Validate() && $Validation);
  $Validation = ($this->pro_CuentaContable->Validate() && $Validation);
  $Validation = ($this->ListBox1->Validate() && $Validation);
  $Validation = ($this->pro_CodCuenta2->Validate() && $Validation);
  $Validation = ($this->pro_Auxiliar2->Validate() && $Validation);
  $Validation = ($this->pro_CodCuenta3->Validate() && $Validation);
  $Validation = ($this->pro_Auxiliar3->Validate() && $Validation);
  $Validation = ($this->TextBox1->Validate() && $Validation);
  $Validation = ($this->pre_Usuario->Validate() && $Validation);
  $Validation = ($this->pro_FecRegistro->Validate() && $Validation);
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
  return (($this->Errors->Count() == 0) && $Validation);
 }
//End Validate Method

//CheckErrors Method @258-F627A05E
 function CheckErrors()
 {
  $errors = false;
  $errors = ($errors || $this->pro_AnoProceso->Errors->Count());
  $errors = ($errors || $this->pro_Semana->Errors->Count());
  $errors = ($errors || $this->pre_RefOperativa->Errors->Count());
  $errors = ($errors || $this->pre_CodProducto->Errors->Count());
  $errors = ($errors || $this->pre_CodMarca->Errors->Count());
  $errors = ($errors || $this->pre_CodEmpaque->Errors->Count());
  $errors = ($errors || $this->pre_GruLiquidacion->Errors->Count());
  $errors = ($errors || $this->pre_Zona->Errors->Count());
  $errors = ($errors || $this->pro_FechaCierre->Errors->Count());
  $errors = ($errors || $this->DatePicker_pre_FecInicio->Errors->Count());
  $errors = ($errors || $this->pro_FechaLiquid->Errors->Count());
  $errors = ($errors || $this->DatePicker_pro_FechaLiquid->Errors->Count());
  $errors = ($errors || $this->pro_TipoComprob->Errors->Count());
  $errors = ($errors || $this->pro_CuentaContable->Errors->Count());
  $errors = ($errors || $this->ListBox1->Errors->Count());
  $errors = ($errors || $this->pro_CodCuenta2->Errors->Count());
  $errors = ($errors || $this->pro_Auxiliar2->Errors->Count());
  $errors = ($errors || $this->pro_CodCuenta3->Errors->Count());
  $errors = ($errors || $this->pro_Auxiliar3->Errors->Count());
  $errors = ($errors || $this->TextBox1->Errors->Count());
  $errors = ($errors || $this->pre_Usuario->Errors->Count());
  $errors = ($errors || $this->pro_FecRegistro->Errors->Count());
  $errors = ($errors || $this->Errors->Count());
  $errors = ($errors || $this->ds->Errors->Count());
  return $errors;
 }
//End CheckErrors Method

//Operation Method @258-1128A707
 function Operation()
 {
  if(!$this->Visible)
   return;

  global $Redirect;
  global $FileName;

  $this->ds->Prepare();
  $this->EditMode = $this->ds->AllParametersSet;
  if(!$this->FormSubmitted)
   return;

  if($this->FormSubmitted) {
   $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
   if(strlen(CCGetParam("Button_Insert", ""))) {
    $this->PressedButton = "Button_Insert";
   } else if(strlen(CCGetParam("Button_Update", ""))) {
    $this->PressedButton = "Button_Update";
   } else if(strlen(CCGetParam("Button_Delete", ""))) {
    $this->PressedButton = "Button_Delete";
   } else if(strlen(CCGetParam("Button_Cancel", ""))) {
    $this->PressedButton = "Button_Cancel";
   }
  }
  $Redirect = "LiLiPr_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
  if($this->PressedButton == "Button_Delete") {
   if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
    $Redirect = "";
   }
  } else if($this->PressedButton == "Button_Cancel") {
   if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
    $Redirect = "";
   }
  } else if($this->Validate()) {
   if($this->PressedButton == "Button_Insert") {
    if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
     $Redirect = "";
    }
   } else if($this->PressedButton == "Button_Update") {
    if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
     $Redirect = "";
    }
   }
  } else {
   $Redirect = "";
  }
 }
//End Operation Method

//InsertRow Method @258-70028E9B
 function InsertRow()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
  if(!$this->InsertAllowed) return false;
  $this->ds->pro_AnoProceso->SetValue($this->pro_AnoProceso->GetValue());
  $this->ds->pro_Semana->SetValue($this->pro_Semana->GetValue());
  $this->ds->pre_RefOperativa->SetValue($this->pre_RefOperativa->GetValue());
  $this->ds->pre_CodProducto->SetValue($this->pre_CodProducto->GetValue());
  $this->ds->pre_CodMarca->SetValue($this->pre_CodMarca->GetValue());
  $this->ds->pre_CodEmpaque->SetValue($this->pre_CodEmpaque->GetValue());
  $this->ds->pre_GruLiquidacion->SetValue($this->pre_GruLiquidacion->GetValue());
  $this->ds->pre_Zona->SetValue($this->pre_Zona->GetValue());
  $this->ds->pro_FechaCierre->SetValue($this->pro_FechaCierre->GetValue());
  $this->ds->pro_FechaLiquid->SetValue($this->pro_FechaLiquid->GetValue());
  $this->ds->pro_TipoComprob->SetValue($this->pro_TipoComprob->GetValue());
  $this->ds->pro_CuentaContable->SetValue($this->pro_CuentaContable->GetValue());
  $this->ds->ListBox1->SetValue($this->ListBox1->GetValue());
  $this->ds->pro_CodCuenta2->SetValue($this->pro_CodCuenta2->GetValue());
  $this->ds->pro_Auxiliar2->SetValue($this->pro_Auxiliar2->GetValue());
  $this->ds->pro_CodCuenta3->SetValue($this->pro_CodCuenta3->GetValue());
  $this->ds->pro_Auxiliar3->SetValue($this->pro_Auxiliar3->GetValue());
  $this->ds->TextBox1->SetValue($this->TextBox1->GetValue());
  $this->ds->pre_Usuario->SetValue($this->pre_Usuario->GetValue());
  $this->ds->pro_FecRegistro->SetValue($this->pro_FecRegistro->GetValue());
  $this->ds->Insert();
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
  if($this->ds->Errors->Count() > 0) {
   echo "Error in Record " . $this->ComponentName . " / Insert Operation";
   $this->ds->Errors->Clear();
   $this->Errors->AddError("Database command error.");
  }
  return (!$this->CheckErrors());
 }
//End InsertRow Method

//UpdateRow Method @258-DD3F908C
 function UpdateRow()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
  if(!$this->UpdateAllowed) return false;
  $this->ds->pro_AnoProceso->SetValue($this->pro_AnoProceso->GetValue());
  $this->ds->pro_Semana->SetValue($this->pro_Semana->GetValue());
  $this->ds->pre_RefOperativa->SetValue($this->pre_RefOperativa->GetValue());
  $this->ds->pre_CodProducto->SetValue($this->pre_CodProducto->GetValue());
  $this->ds->pre_CodMarca->SetValue($this->pre_CodMarca->GetValue());
  $this->ds->pre_CodEmpaque->SetValue($this->pre_CodEmpaque->GetValue());
  $this->ds->pre_GruLiquidacion->SetValue($this->pre_GruLiquidacion->GetValue());
  $this->ds->pre_Zona->SetValue($this->pre_Zona->GetValue());
  $this->ds->pro_FechaCierre->SetValue($this->pro_FechaCierre->GetValue());
  $this->ds->pro_FechaLiquid->SetValue($this->pro_FechaLiquid->GetValue());
  $this->ds->pro_TipoComprob->SetValue($this->pro_TipoComprob->GetValue());
  $this->ds->pro_CuentaContable->SetValue($this->pro_CuentaContable->GetValue());
  $this->ds->ListBox1->SetValue($this->ListBox1->GetValue());
  $this->ds->pro_CodCuenta2->SetValue($this->pro_CodCuenta2->GetValue());
  $this->ds->pro_Auxiliar2->SetValue($this->pro_Auxiliar2->GetValue());
  $this->ds->pro_CodCuenta3->SetValue($this->pro_CodCuenta3->GetValue());
  $this->ds->pro_Auxiliar3->SetValue($this->pro_Auxiliar3->GetValue());
  $this->ds->TextBox1->SetValue($this->TextBox1->GetValue());
  $this->ds->pre_Usuario->SetValue($this->pre_Usuario->GetValue());
  $this->ds->pro_FecRegistro->SetValue($this->pro_FecRegistro->GetValue());
  $this->ds->Update();
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
  if($this->ds->Errors->Count() > 0) {
   echo "Error in Record " . $this->ComponentName . " / Update Operation";
   $this->ds->Errors->Clear();
   $this->Errors->AddError("Database command error.");
  }
  return (!$this->CheckErrors());
 }
//End UpdateRow Method

//DeleteRow Method @258-EA88835F
 function DeleteRow()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
  if(!$this->DeleteAllowed) return false;
  $this->ds->Delete();
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
  if($this->ds->Errors->Count() > 0) {
   echo "Error in Record " . ComponentName . " / Delete Operation";
   $this->ds->Errors->Clear();
   $this->Errors->AddError("Database command error.");
  }
  return (!$this->CheckErrors());
 }
//End DeleteRow Method

//Show Method @258-CF8E0876
 function Show()
 {
  global $Tpl;
  global $FileName;
  $Error = "";

  if(!$this->Visible)
   return;

  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

  $this->pre_RefOperativa->Prepare();
  $this->pre_CodProducto->Prepare();
  $this->pre_CodMarca->Prepare();
  $this->pre_CodEmpaque->Prepare();
  $this->pre_GruLiquidacion->Prepare();
  $this->pre_Zona->Prepare();
  $this->pro_CuentaContable->Prepare();
  $this->ListBox1->Prepare();
  $this->pro_CodCuenta2->Prepare();
  $this->pro_Auxiliar2->Prepare();
  $this->pro_CodCuenta3->Prepare();
  $this->pro_Auxiliar3->Prepare();

  $this->ds->open();

  $RecordBlock = "Record " . $this->ComponentName;
  $ParentPath = $Tpl->block_path;
  $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
  if($this->EditMode)
  {
   if($this->Errors->Count() == 0)
   {
    if($this->ds->Errors->Count() > 0)
    {
     echo "Error in Record liqprocesos";
    }
    else if($this->ds->next_record())
    {
     $this->ds->SetValues();
     if(!$this->FormSubmitted)
     {
      $this->pro_AnoProceso->SetValue($this->ds->pro_AnoProceso->GetValue());
      $this->pro_Semana->SetValue($this->ds->pro_Semana->GetValue());
      $this->pre_RefOperativa->SetValue($this->ds->pre_RefOperativa->GetValue());
      $this->pre_CodProducto->SetValue($this->ds->pre_CodProducto->GetValue());
      $this->pre_CodMarca->SetValue($this->ds->pre_CodMarca->GetValue());
      $this->pre_CodEmpaque->SetValue($this->ds->pre_CodEmpaque->GetValue());
      $this->pre_GruLiquidacion->SetValue($this->ds->pre_GruLiquidacion->GetValue());
      $this->pre_Zona->SetValue($this->ds->pre_Zona->GetValue());
      $this->pro_FechaCierre->SetValue($this->ds->pro_FechaCierre->GetValue());
      $this->pro_FechaLiquid->SetValue($this->ds->pro_FechaLiquid->GetValue());
      $this->pro_TipoComprob->SetValue($this->ds->pro_TipoComprob->GetValue());
      $this->pro_CuentaContable->SetValue($this->ds->pro_CuentaContable->GetValue());
      $this->ListBox1->SetValue($this->ds->ListBox1->GetValue());
      $this->pro_CodCuenta2->SetValue($this->ds->pro_CodCuenta2->GetValue());
      $this->pro_Auxiliar2->SetValue($this->ds->pro_Auxiliar2->GetValue());
      $this->pro_CodCuenta3->SetValue($this->ds->pro_CodCuenta3->GetValue());
      $this->pro_Auxiliar3->SetValue($this->ds->pro_Auxiliar3->GetValue());
      $this->TextBox1->SetValue($this->ds->TextBox1->GetValue());
      $this->pre_Usuario->SetValue($this->ds->pre_Usuario->GetValue());
      $this->pro_FecRegistro->SetValue($this->ds->pro_FecRegistro->GetValue());
     }
    }
    else
    {
     $this->EditMode = false;
    }
   }
  }
  if(!$this->FormSubmitted)
  {
  }
    //----------  Modificacion para Habilitar / deshabilitar Botones Segun perfil del usuario
    $aOpc = array();
    global $DBdatos;
    if ($this->InsertAllowed || $this->UpdateAllowed) $aOpc[]="ADD";
    if ($this->DeleteAllowed ) $aOpc[]="DEL";
    $aOpc[]="EXE";
    $ilEstado = $this->ds->f('per_Estado');
//    $ilEstado = CCGetDBValue("SELECT per_Estado FROM concomprobantes JOIN conperiodos ON per_Aplicacion = 'CO' AND per_numperiodo = com_numperiodo WHERE com_RegNumero =" . CCGetParam('com_RegNumero', -1), $DBdatos);
    fHabilitaBotonesCCS(false, $aOpc, $ilEstado );
    if ($ilEstado < 1 ) $Tpl->SetVar('lbEstado', ' **Solo para Consulta**');
    //----------
  if($this->FormSubmitted || $this->CheckErrors()) {
   $Error .= $this->pro_AnoProceso->Errors->ToString();
   $Error .= $this->pro_Semana->Errors->ToString();
   $Error .= $this->pre_RefOperativa->Errors->ToString();
   $Error .= $this->pre_CodProducto->Errors->ToString();
   $Error .= $this->pre_CodMarca->Errors->ToString();
   $Error .= $this->pre_CodEmpaque->Errors->ToString();
   $Error .= $this->pre_GruLiquidacion->Errors->ToString();
   $Error .= $this->pre_Zona->Errors->ToString();
   $Error .= $this->pro_FechaCierre->Errors->ToString();
   $Error .= $this->DatePicker_pre_FecInicio->Errors->ToString();
   $Error .= $this->pro_FechaLiquid->Errors->ToString();
   $Error .= $this->DatePicker_pro_FechaLiquid->Errors->ToString();
   $Error .= $this->pro_TipoComprob->Errors->ToString();
   $Error .= $this->pro_CuentaContable->Errors->ToString();
   $Error .= $this->ListBox1->Errors->ToString();
   $Error .= $this->pro_CodCuenta2->Errors->ToString();
   $Error .= $this->pro_Auxiliar2->Errors->ToString();
   $Error .= $this->pro_CodCuenta3->Errors->ToString();
   $Error .= $this->pro_Auxiliar3->Errors->ToString();
   $Error .= $this->TextBox1->Errors->ToString();
   $Error .= $this->pre_Usuario->Errors->ToString();
   $Error .= $this->pro_FecRegistro->Errors->ToString();
   $Error .= $this->Errors->ToString();
   $Error .= $this->ds->Errors->ToString();
   $Tpl->SetVar("Error", $Error);
   $Tpl->Parse("Error", false);
  }
  $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
  $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
  $Tpl->SetVar("Action", $this->HTMLFormAction);
  $Tpl->SetVar("HTMLFormName", $this->ComponentName);
  $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
  if(!$this->Visible) {
   $Tpl->block_path = $ParentPath;
   return;
  }

  $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
  $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
  $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;
  $this->pro_AnoProceso->Show();
  $this->pro_Semana->Show();
  $this->pre_RefOperativa->Show();
  $this->pre_CodProducto->Show();
  $this->pre_CodMarca->Show();
  $this->pre_CodEmpaque->Show();
  $this->pre_GruLiquidacion->Show();
  $this->pre_Zona->Show();
  $this->pro_FechaCierre->Show();
  $this->DatePicker_pre_FecInicio->Show();
  $this->pro_FechaLiquid->Show();
  $this->DatePicker_pro_FechaLiquid->Show();
  $this->pro_TipoComprob->Show();
  $this->pro_CuentaContable->Show();
  $this->ListBox1->Show();
  $this->pro_CodCuenta2->Show();
  $this->pro_Auxiliar2->Show();
  $this->pro_CodCuenta3->Show();
  $this->pro_Auxiliar3->Show();
  $this->TextBox1->Show();
  $this->pre_Usuario->Show();
  $this->pro_FecRegistro->Show();
  $this->Button_Insert->Show();
  $this->Button_Update->Show();
  $this->Button_Delete->Show();
  $this->Button_Cancel->Show();
  $Tpl->parse();
  $Tpl->block_path = $ParentPath;
  $this->ds->close();
 }
//End Show Method

} //End liqprocesos Class @258-FCB6E20C

class clsliqprocesosDataSource extends clsDBdatos {  //liqprocesosDataSource Class @258-8C992536

//DataSource Variables @258-0672D996
 var $CCSEvents = "";
 var $CCSEventResult;
 var $ErrorBlock;

 var $InsertParameters;
 var $UpdateParameters;
 var $DeleteParameters;
 var $wp;
 var $AllParametersSet;


 // Datasource fields
 var $pro_AnoProceso;
 var $pro_Semana;
 var $pre_RefOperativa;
 var $pre_CodProducto;
 var $pre_CodMarca;
 var $pre_CodEmpaque;
 var $pre_GruLiquidacion;
 var $pre_Zona;
 var $pro_FechaCierre;
 var $pro_FechaLiquid;
 var $pro_TipoComprob;
 var $pro_CuentaContable;
 var $ListBox1;
 var $pro_CodCuenta2;
 var $pro_Auxiliar2;
 var $pro_CodCuenta3;
 var $pro_Auxiliar3;
 var $TextBox1;
 var $pre_Usuario;
 var $pro_FecRegistro;
//End DataSource Variables

//Class_Initialize Event @258-18964BAF
 function clsliqprocesosDataSource()
 {
  $this->ErrorBlock = "Record liqprocesos/Error";
  $this->Initialize();
  $this->pro_AnoProceso = new clsField("pro_AnoProceso", ccsInteger, "");
  $this->pro_Semana = new clsField("pro_Semana", ccsInteger, "");
  $this->pre_RefOperativa = new clsField("pre_RefOperativa", ccsInteger, "");
  $this->pre_CodProducto = new clsField("pre_CodProducto", ccsInteger, "");
  $this->pre_CodMarca = new clsField("pre_CodMarca", ccsInteger, "");
  $this->pre_CodEmpaque = new clsField("pre_CodEmpaque", ccsInteger, "");
  $this->pre_GruLiquidacion = new clsField("pre_GruLiquidacion", ccsInteger, "");
  $this->pre_Zona = new clsField("pre_Zona", ccsInteger, "");
  $this->pro_FechaCierre = new clsField("pro_FechaCierre", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
  $this->pro_FechaLiquid = new clsField("pro_FechaLiquid", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
  $this->pro_TipoComprob = new clsField("pro_TipoComprob", ccsText, "");
  $this->pro_CuentaContable = new clsField("pro_CuentaContable", ccsText, "");
  $this->ListBox1 = new clsField("ListBox1", ccsInteger, "");
  $this->pro_CodCuenta2 = new clsField("pro_CodCuenta2", ccsText, "");
  $this->pro_Auxiliar2 = new clsField("pro_Auxiliar2", ccsInteger, "");
  $this->pro_CodCuenta3 = new clsField("pro_CodCuenta3", ccsText, "");
  $this->pro_Auxiliar3 = new clsField("pro_Auxiliar3", ccsInteger, "");
  $this->TextBox1 = new clsField("TextBox1", ccsInteger, "");
  $this->pre_Usuario = new clsField("pre_Usuario", ccsText, "");
  $this->pro_FecRegistro = new clsField("pro_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

 }
//End Class_Initialize Event

//Prepare Method @258-380DE588
 function Prepare()
 {
  $this->wp = new clsSQLParameters($this->ErrorBlock);
  $this->wp->AddParameter("1", "urlpro_ID", ccsInteger, "", "", $this->Parameters["urlpro_ID"], "", false);
  $this->AllParametersSet = $this->wp->AllParamsSet();
  $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pro_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
  $this->Where = $this->wp->Criterion[1];
 }
//End Prepare Method

//Open Method @258-390F690C
 function Open()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
  $this->SQL = "SELECT liqprocesos.*, per_Estado, per_PerContable  " .
  "FROM liqprocesos JOIN conperiodos ON per_aplicacion = 'LI' and per_Numperiodo = pro_semana";
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
  $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
  $this->MoveToPage($this->AbsolutePage);
 }
//End Open Method

//SetValues Method @258-A3994298
 function SetValues()
 {
  $this->pro_AnoProceso->SetDBValue(trim($this->f("pro_AnoProceso")));
  $this->pro_Semana->SetDBValue(trim($this->f("pro_Semana")));
  $this->pre_RefOperativa->SetDBValue(trim($this->f("pro_Embarque")));
  $this->pre_CodProducto->SetDBValue(trim($this->f("pro_CodProducto")));
  $this->pre_CodMarca->SetDBValue(trim($this->f("pro_CodMarca")));
  $this->pre_CodEmpaque->SetDBValue(trim($this->f("pro_CodEmpaque")));
  $this->pre_GruLiquidacion->SetDBValue(trim($this->f("pro_GrupoLiquidacion")));
  $this->pre_Zona->SetDBValue(trim($this->f("pro_Zona")));
  $this->pro_FechaCierre->SetDBValue(trim($this->f("pro_FechaCierre")));
  $this->pro_FechaLiquid->SetDBValue(trim($this->f("pro_FechaLiquid")));
  $this->pro_TipoComprob->SetDBValue($this->f("pro_TipoComprob"));
  $this->pro_CuentaContable->SetDBValue($this->f("pro_CodCuenta1"));
  $this->ListBox1->SetDBValue(trim($this->f("pro_Auxiliar1")));
  $this->pro_CodCuenta2->SetDBValue($this->f("pro_CodCuenta2"));
  $this->pro_Auxiliar2->SetDBValue(trim($this->f("pro_Auxiliar2")));
  $this->pro_CodCuenta3->SetDBValue($this->f("pro_CodCuenta3"));
  $this->pro_Auxiliar3->SetDBValue(trim($this->f("pro_Auxiliar3")));
  $this->TextBox1->SetDBValue(trim($this->f("pro_Estado")));
  $this->pre_Usuario->SetDBValue($this->f("pro_Usuario"));
  $this->pro_FecRegistro->SetDBValue(trim($this->f("pro_FecRegistro")));
 }
//End SetValues Method

//Insert Method @258-EEEA7485
 function Insert()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
  $this->SQL = "INSERT INTO liqprocesos ("
    . "pro_AnoProceso, "
    . "pro_Semana, "
    . "pro_Embarque, "
    . "pro_CodProducto, "
    . "pro_CodMarca, "
    . "pro_CodEmpaque, "
    . "pro_GrupoLiquidacion, "
    . "pro_Zona, "
    . "pro_FechaCierre, "
    . "pro_FechaLiquid, "
    . "pro_TipoComprob, "
    . "pro_CodCuenta1, "
    . "pro_Auxiliar1, "
    . "pro_CodCuenta2, "
    . "pro_Auxiliar2, "
    . "pro_CodCuenta3, "
    . "pro_Auxiliar3, "
    . "pro_Estado, "
    . "pro_Usuario, "
    . "pro_FecRegistro"
    . ") VALUES ("
    . $this->ToSQL($this->pro_AnoProceso->GetDBValue(), $this->pro_AnoProceso->DataType) . ", "
    . $this->ToSQL($this->pro_Semana->GetDBValue(), $this->pro_Semana->DataType) . ", "
    . $this->ToSQL($this->pre_RefOperativa->GetDBValue(), $this->pre_RefOperativa->DataType) . ", "
    . $this->ToSQL($this->pre_CodProducto->GetDBValue(), $this->pre_CodProducto->DataType) . ", "
    . $this->ToSQL($this->pre_CodMarca->GetDBValue(), $this->pre_CodMarca->DataType) . ", "
    . $this->ToSQL($this->pre_CodEmpaque->GetDBValue(), $this->pre_CodEmpaque->DataType) . ", "
    . $this->ToSQL($this->pre_GruLiquidacion->GetDBValue(), $this->pre_GruLiquidacion->DataType) . ", "
    . $this->ToSQL($this->pre_Zona->GetDBValue(), $this->pre_Zona->DataType) . ", "
    . $this->ToSQL($this->pro_FechaCierre->GetDBValue(), $this->pro_FechaCierre->DataType) . ", "
    . $this->ToSQL($this->pro_FechaLiquid->GetDBValue(), $this->pro_FechaLiquid->DataType) . ", "
    . $this->ToSQL($this->pro_TipoComprob->GetDBValue(), $this->pro_TipoComprob->DataType) . ", "
    . $this->ToSQL($this->pro_CuentaContable->GetDBValue(), $this->pro_CuentaContable->DataType) . ", "
    . $this->ToSQL($this->ListBox1->GetDBValue(), $this->ListBox1->DataType) . ", "
    . $this->ToSQL($this->pro_CodCuenta2->GetDBValue(), $this->pro_CodCuenta2->DataType) . ", "
    . $this->ToSQL($this->pro_Auxiliar2->GetDBValue(), $this->pro_Auxiliar2->DataType) . ", "
    . $this->ToSQL($this->pro_CodCuenta3->GetDBValue(), $this->pro_CodCuenta3->DataType) . ", "
    . $this->ToSQL($_POST['pro_Auxiliar3'], $this->pro_Auxiliar3->DataType) . ", "
    . $this->ToSQL($this->TextBox1->GetDBValue(), $this->TextBox1->DataType) . ", "
    . $this->ToSQL($this->pre_Usuario->GetDBValue(), $this->pre_Usuario->DataType) . ", "
    . $this->ToSQL($this->pro_FecRegistro->GetDBValue(), $this->pro_FecRegistro->DataType)
    . ")";
//    . $this->ToSQL($this->pro_Auxiliar3->GetDBValue(), $this->pro_Auxiliar3->DataType) . ", "
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
  $this->query($this->SQL);
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
  if($this->Errors->Count() > 0)
   $this->Errors->AddError($this->Errors->ToString());
  $this->close();
 }
//End Insert Method

//Update Method @258-BE127123
 function Update()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
  $this->SQL = "UPDATE liqprocesos SET "
    . "pro_AnoProceso=" . $this->ToSQL($this->pro_AnoProceso->GetDBValue(), $this->pro_AnoProceso->DataType) . ", "
    . "pro_Semana=" . $this->ToSQL($this->pro_Semana->GetDBValue(), $this->pro_Semana->DataType) . ", "
    . "pro_Embarque=" . $this->ToSQL($this->pre_RefOperativa->GetDBValue(), $this->pre_RefOperativa->DataType) . ", "
    . "pro_CodProducto=" . $this->ToSQL($this->pre_CodProducto->GetDBValue(), $this->pre_CodProducto->DataType) . ", "
    . "pro_CodMarca=" . $this->ToSQL($this->pre_CodMarca->GetDBValue(), $this->pre_CodMarca->DataType) . ", "
    . "pro_CodEmpaque=" . $this->ToSQL($this->pre_CodEmpaque->GetDBValue(), $this->pre_CodEmpaque->DataType) . ", "
    . "pro_GrupoLiquidacion=" . $this->ToSQL($this->pre_GruLiquidacion->GetDBValue(), $this->pre_GruLiquidacion->DataType) . ", "
    . "pro_Zona=" . $this->ToSQL($this->pre_Zona->GetDBValue(), $this->pre_Zona->DataType) . ", "
    . "pro_FechaCierre=" . $this->ToSQL($this->pro_FechaCierre->GetDBValue(), $this->pro_FechaCierre->DataType) . ", "
    . "pro_FechaLiquid=" . $this->ToSQL($this->pro_FechaLiquid->GetDBValue(), $this->pro_FechaLiquid->DataType) . ", "
    . "pro_TipoComprob=" . $this->ToSQL($this->pro_TipoComprob->GetDBValue(), $this->pro_TipoComprob->DataType) . ", "
    . "pro_CodCuenta1=" . $this->ToSQL($this->pro_CuentaContable->GetDBValue(), $this->pro_CuentaContable->DataType) . ", "
    . "pro_Auxiliar1=" . $this->ToSQL($this->ListBox1->GetDBValue(), $this->ListBox1->DataType) . ", "
    . "pro_CodCuenta2=" . $this->ToSQL($this->pro_CodCuenta2->GetDBValue(), $this->pro_CodCuenta2->DataType) . ", "
    . "pro_Auxiliar2=" . $this->ToSQL($this->pro_Auxiliar2->GetDBValue(), $this->pro_Auxiliar2->DataType) . ", "
    . "pro_CodCuenta3=" . $this->ToSQL($this->pro_CodCuenta3->GetDBValue(), $this->pro_CodCuenta3->DataType) . ", "
    . "pro_Auxiliar3=" . $this->ToSQL($_POST['pro_Auxiliar3'], $this->pro_Auxiliar3->DataType) . ", "
    . "pro_Estado=" . $this->ToSQL($this->TextBox1->GetDBValue(), $this->TextBox1->DataType) . ", "
    . "pro_Usuario=" . $this->ToSQL($_SESSION['g_user'], $this->pre_Usuario->DataType) . ", "
    . "pro_FecRegistro=" . $this->ToSQL($this->pro_FecRegistro->GetDBValue(), $this->pro_FecRegistro->DataType);
  $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
//  . "pro_Auxiliar3=" . $this->ToSQL($this->pro_Auxiliar3->GetDBValue(), $this->pro_Auxiliar3->DataType) . ", "
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
  $this->query($this->SQL);
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
  if($this->Errors->Count() > 0)
   $this->Errors->AddError($this->Errors->ToString());
  $this->close();
 }
//End Update Method

//Delete Method @258-287AF88A
 function Delete()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
  $this->SQL = "DELETE FROM liqprocesos";
  $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
  $this->query($this->SQL);
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
  if($this->Errors->Count() > 0)
   $this->Errors->AddError($this->Errors->ToString());
  $this->close();
 }
//End Delete Method

} //End liqprocesosDataSource Class @258-FCB6E20C

//Initialize Page @1-A9B38061
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "LiLiPr_mant.php";
$Redirect = "";
$TemplateFileName = "LiLiPr_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-825B6A2A
$DBdatos = new clsDBdatos();

// Controls
$liqprocesos = new clsRecordliqprocesos();
$liqprocesos->Initialize();

// Events
include("./LiLiPr_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-12B57693
$liqprocesos->Operation();
//End Execute Components

//Go to destination page @1-CDA9AAFD
if($Redirect)
{
 $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
 $DBdatos->close();
 header("Location: " . $Redirect);
 exit;
}
//End Go to destination page

//Show Page @1-D5F843F9
$liqprocesos->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
