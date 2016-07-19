<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include (RelativePath . "/LibPhp/ConLib.php") ;
//Include Page implementation @35-6BA6AD4B
include_once(RelativePath . "/Co_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordCoAdAc_mant { //CoAdAc_mant Class @115-A1F2D5E3

//Variables @115-B2F7A83E

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

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @115-23632F73
    function clsRecordCoAdAc_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdAc_mant/Error";
        $this->ds = new clsCoAdAc_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdAc_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->act_CodAuxiliar = new clsControl(ccsTextBox, "act_CodAuxiliar", "act_CodAuxiliar", ccsInteger, "", CCGetRequestParam("act_CodAuxiliar", $Method));
            $this->par_Valor4 = new clsControl(ccsHidden, "par_Valor4", "par_Valor4", ccsText, "", CCGetRequestParam("par_Valor4", $Method));
            $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Act Descripcion", ccsText, "", CCGetRequestParam("act_Descripcion", $Method));
            $this->act_Descripcion->Required = true;
            $this->act_Descripcion1 = new clsControl(ccsTextBox, "act_Descripcion1", "Act Descripcion1", ccsText, "", CCGetRequestParam("act_Descripcion1", $Method));
            $this->lbCategAux = new clsControl(ccsListBox, "lbCategAux", "lbCategAux", ccsInteger, "", CCGetRequestParam("lbCategAux", $Method));
            $this->lbCategAux->DSType = dsTable;
            list($this->lbCategAux->BoundColumn, $this->lbCategAux->TextColumn, $this->lbCategAux->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->lbCategAux->ds = new clsDBdatos();
            $this->lbCategAux->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->lbCategAux->ds->Order = "par_Descripcion";
            $this->lbCategAux->ds->wp = new clsSQLParameters();
            $this->lbCategAux->ds->wp->Criterion[1] = "par_clave='CAUTI' and par_valor1='Activo'";
            $this->lbCategAux->ds->Where = 
                 $this->lbCategAux->ds->wp->Criterion[1];
            $this->act_SubCategoria = new clsControl(ccsListBox, "act_SubCategoria", "Act Sub Categoria", ccsInteger, "", CCGetRequestParam("act_SubCategoria", $Method));
            $this->act_SubCategoria->DSType = dsTable;
            list($this->act_SubCategoria->BoundColumn, $this->act_SubCategoria->TextColumn, $this->act_SubCategoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_SubCategoria->ds = new clsDBdatos();
            $this->act_SubCategoria->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_SubCategoria->ds->Order = "par_Descripcion";
            $this->act_SubCategoria->ds->wp = new clsSQLParameters();
            $this->act_SubCategoria->ds->wp->Criterion[1] = "par_clave='ACTSUB'";
            $this->act_SubCategoria->ds->Where = 
                 $this->act_SubCategoria->ds->wp->Criterion[1];
            $this->act_SubCategoria->Required = true;
            $this->act_Abreviatura = new clsControl(ccsTextBox, "act_Abreviatura", "Act Abreviatura", ccsText, "", CCGetRequestParam("act_Abreviatura", $Method));
            $this->act_Tipo = new clsControl(ccsListBox, "act_Tipo", "Act Tipo", ccsInteger, "", CCGetRequestParam("act_Tipo", $Method));
            $this->act_Tipo->DSType = dsTable;
            list($this->act_Tipo->BoundColumn, $this->act_Tipo->TextColumn, $this->act_Tipo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_Tipo->ds = new clsDBdatos();
            $this->act_Tipo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_Tipo->ds->Order = "par_Descripcion";
            $this->act_Tipo->ds->wp = new clsSQLParameters();
            $this->act_Tipo->ds->wp->Criterion[1] = "par_clave='ACTCLA'";
            $this->act_Tipo->ds->Where = 
                 $this->act_Tipo->ds->wp->Criterion[1];
            $this->act_Grupo = new clsControl(ccsListBox, "act_Grupo", "Act Grupo", ccsInteger, "", CCGetRequestParam("act_Grupo", $Method));
            $this->act_Grupo->DSType = dsTable;
            list($this->act_Grupo->BoundColumn, $this->act_Grupo->TextColumn, $this->act_Grupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_Grupo->ds = new clsDBdatos();
            $this->act_Grupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_Grupo->ds->Order = "par_Descripcion";
            $this->act_Grupo->ds->wp = new clsSQLParameters();
            $this->act_Grupo->ds->wp->Criterion[1] = "par_clave='ACTGRU'";
            $this->act_Grupo->ds->Where = 
                 $this->act_Grupo->ds->wp->Criterion[1];
            $this->act_SubGrupo = new clsControl(ccsListBox, "act_SubGrupo", "Act Sub Grupo", ccsInteger, "", CCGetRequestParam("act_SubGrupo", $Method));
            $this->act_SubGrupo->DSType = dsTable;
            list($this->act_SubGrupo->BoundColumn, $this->act_SubGrupo->TextColumn, $this->act_SubGrupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_SubGrupo->ds = new clsDBdatos();
            $this->act_SubGrupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_SubGrupo->ds->Order = "par_Descripcion";
            $this->act_SubGrupo->ds->wp = new clsSQLParameters();
            $this->act_SubGrupo->ds->wp->Criterion[1] = "par_clave='ACTSGR'";
            $this->act_SubGrupo->ds->Where = 
                 $this->act_SubGrupo->ds->wp->Criterion[1];
            $this->act_Marca = new clsControl(ccsListBox, "act_Marca", "Act Marca", ccsInteger, "", CCGetRequestParam("act_Marca", $Method));
            $this->act_Marca->DSType = dsTable;
            list($this->act_Marca->BoundColumn, $this->act_Marca->TextColumn, $this->act_Marca->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_Marca->ds = new clsDBdatos();
            $this->act_Marca->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_Marca->ds->Order = "par_Descripcion";
            $this->act_Marca->ds->wp = new clsSQLParameters();
            $this->act_Marca->ds->wp->Criterion[1] = "par_clave='IMARCA'";
            $this->act_Marca->ds->Where = 
                 $this->act_Marca->ds->wp->Criterion[1];
            $this->act_Marca->Required = true;
            $this->act_Modelo = new clsControl(ccsTextBox, "act_Modelo", "Act Modelo", ccsText, "", CCGetRequestParam("act_Modelo", $Method));
            $this->act_NumSerie = new clsControl(ccsTextBox, "act_NumSerie", "Act Num Serie", ccsText, "", CCGetRequestParam("act_NumSerie", $Method));
            $this->act_UniMedida = new clsControl(ccsListBox, "act_UniMedida", "Act Uni Medida", ccsInteger, "", CCGetRequestParam("act_UniMedida", $Method));
            $this->act_UniMedida->DSType = dsTable;
            list($this->act_UniMedida->BoundColumn, $this->act_UniMedida->TextColumn, $this->act_UniMedida->DBFormat) = array("uni_CodUnidad", "uni_Descripcion", "");
            $this->act_UniMedida->ds = new clsDBdatos();
            $this->act_UniMedida->ds->SQL = "SELECT *  " .
"FROM genunmedida";
            $this->act_UniMedida->ds->Order = "uni_Magnitud, uni_CodUnidad";
            $this->act_CodAnterior = new clsControl(ccsTextBox, "act_CodAnterior", "Act Cod Anterior", ccsText, "", CCGetRequestParam("act_CodAnterior", $Method));
            $this->act_IvaFlag = new clsControl(ccsListBox, "act_IvaFlag", "Act Iva Flag", ccsInteger, "", CCGetRequestParam("act_IvaFlag", $Method));
            $this->act_IvaFlag->DSType = dsTable;
            list($this->act_IvaFlag->BoundColumn, $this->act_IvaFlag->TextColumn, $this->act_IvaFlag->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_IvaFlag->ds = new clsDBdatos();
            $this->act_IvaFlag->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_IvaFlag->ds->Parameters["expr260"] = CTIVA;
            $this->act_IvaFlag->ds->wp = new clsSQLParameters();
            $this->act_IvaFlag->ds->wp->AddParameter("1", "expr260", ccsText, "", "", $this->act_IvaFlag->ds->Parameters["expr260"], "", false);
            $this->act_IvaFlag->ds->wp->Criterion[1] = $this->act_IvaFlag->ds->wp->Operation(opEqual, "par_Clave", $this->act_IvaFlag->ds->wp->GetDBValue("1"), $this->act_IvaFlag->ds->ToSQL($this->act_IvaFlag->ds->wp->GetDBValue("1"), ccsText),false);
            $this->act_IvaFlag->ds->Where = 
                 $this->act_IvaFlag->ds->wp->Criterion[1];
            $this->act_IvaFlag->HTML = true;
            $this->act_IvaFlag->Required = true;
            $this->act_IceFlag = new clsControl(ccsCheckBox, "act_IceFlag", "Act Ice Flag", ccsInteger, "", CCGetRequestParam("act_IceFlag", $Method));
            $this->act_IceFlag->HTML = true;
            $this->act_IceFlag->CheckedValue = $this->act_IceFlag->GetParsedValue(1);
            $this->act_IceFlag->UncheckedValue = $this->act_IceFlag->GetParsedValue(0);
            $this->act_Im3Flag = new clsControl(ccsCheckBox, "act_Im3Flag", "Act Im3 Flag", ccsInteger, "", CCGetRequestParam("act_Im3Flag", $Method));
            $this->act_Im3Flag->HTML = true;
            $this->act_Im3Flag->CheckedValue = $this->act_Im3Flag->GetParsedValue(1);
            $this->act_Im3Flag->UncheckedValue = $this->act_Im3Flag->GetParsedValue(0);
            $this->act_Im4Flag = new clsControl(ccsCheckBox, "act_Im4Flag", "Act Im4 Flag", ccsInteger, "", CCGetRequestParam("act_Im4Flag", $Method));
            $this->act_Im4Flag->HTML = true;
            $this->act_Im4Flag->CheckedValue = $this->act_Im4Flag->GetParsedValue(1);
            $this->act_Im4Flag->UncheckedValue = $this->act_Im4Flag->GetParsedValue(0);
            $this->act_Im5Flag = new clsControl(ccsCheckBox, "act_Im5Flag", "Act Im5 Flag", ccsInteger, "", CCGetRequestParam("act_Im5Flag", $Method));
            $this->act_Im5Flag->HTML = true;
            $this->act_Im5Flag->CheckedValue = $this->act_Im5Flag->GetParsedValue(1);
            $this->act_Im5Flag->UncheckedValue = $this->act_Im5Flag->GetParsedValue(0);
            $this->act_SufijoCuenta = new clsControl(ccsTextBox, "act_SufijoCuenta", "Act Sufijo Cuenta", ccsText, "", CCGetRequestParam("act_SufijoCuenta", $Method));
            $this->act_Inventariable = new clsControl(ccsCheckBox, "act_Inventariable", "Act Inventariable", ccsInteger, "", CCGetRequestParam("act_Inventariable", $Method));
            $this->act_Inventariable->HTML = true;
            $this->act_Inventariable->CheckedValue = $this->act_Inventariable->GetParsedValue(1);
            $this->act_Inventariable->UncheckedValue = $this->act_Inventariable->GetParsedValue(0);
            $this->act_usuario = new clsControl(ccsTextBox, "act_usuario", "Act Usuario", ccsText, "", CCGetRequestParam("act_usuario", $Method));
            $this->act_FecRegistro = new clsControl(ccsTextBox, "act_FecRegistro", "Act Fec Registro", ccsDate, Array("dd", "/", "mmm", "/", "yy", " ", "h", ":", "nn"), CCGetRequestParam("act_FecRegistro", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->btNuevo = new clsButton("btNuevo");
            $this->Button1 = new clsButton("Button1");
            if(!$this->FormSubmitted) {
                if(!is_array($this->act_Descripcion1->Value) && !strlen($this->act_Descripcion1->Value) && $this->act_Descripcion1->Value !== false)
                $this->act_Descripcion1->SetText('');
                if(!is_array($this->act_Abreviatura->Value) && !strlen($this->act_Abreviatura->Value) && $this->act_Abreviatura->Value !== false)
                $this->act_Abreviatura->SetText('');
                if(!is_array($this->act_Tipo->Value) && !strlen($this->act_Tipo->Value) && $this->act_Tipo->Value !== false)
                $this->act_Tipo->SetText(9999);
                if(!is_array($this->act_Grupo->Value) && !strlen($this->act_Grupo->Value) && $this->act_Grupo->Value !== false)
                $this->act_Grupo->SetText(9999);
                if(!is_array($this->act_SubGrupo->Value) && !strlen($this->act_SubGrupo->Value) && $this->act_SubGrupo->Value !== false)
                $this->act_SubGrupo->SetText(9999);
                if(!is_array($this->act_Marca->Value) && !strlen($this->act_Marca->Value) && $this->act_Marca->Value !== false)
                $this->act_Marca->SetText(9999);
                if(!is_array($this->act_Modelo->Value) && !strlen($this->act_Modelo->Value) && $this->act_Modelo->Value !== false)
                $this->act_Modelo->SetText(' ');
                if(!is_array($this->act_NumSerie->Value) && !strlen($this->act_NumSerie->Value) && $this->act_NumSerie->Value !== false)
                $this->act_NumSerie->SetText('');
                if(!is_array($this->act_UniMedida->Value) && !strlen($this->act_UniMedida->Value) && $this->act_UniMedida->Value !== false)
                $this->act_UniMedida->SetText(0);
                if(!is_array($this->act_CodAnterior->Value) && !strlen($this->act_CodAnterior->Value) && $this->act_CodAnterior->Value !== false)
                $this->act_CodAnterior->SetText(' ');
                if(!is_array($this->act_IvaFlag->Value) && !strlen($this->act_IvaFlag->Value) && $this->act_IvaFlag->Value !== false)
                $this->act_IvaFlag->SetText(1);
                if(!is_array($this->act_IceFlag->Value) && !strlen($this->act_IceFlag->Value) && $this->act_IceFlag->Value !== false)
                $this->act_IceFlag->SetText(0);
                if(!is_array($this->act_Im3Flag->Value) && !strlen($this->act_Im3Flag->Value) && $this->act_Im3Flag->Value !== false)
                $this->act_Im3Flag->SetText(0);
                if(!is_array($this->act_Im4Flag->Value) && !strlen($this->act_Im4Flag->Value) && $this->act_Im4Flag->Value !== false)
                $this->act_Im4Flag->SetText(0);
                if(!is_array($this->act_Im5Flag->Value) && !strlen($this->act_Im5Flag->Value) && $this->act_Im5Flag->Value !== false)
                $this->act_Im5Flag->SetText(0);
                if(!is_array($this->act_SufijoCuenta->Value) && !strlen($this->act_SufijoCuenta->Value) && $this->act_SufijoCuenta->Value !== false)
                $this->act_SufijoCuenta->SetText('');
                if(!is_array($this->act_Inventariable->Value) && !strlen($this->act_Inventariable->Value) && $this->act_Inventariable->Value !== false)
                $this->act_Inventariable->SetText(1);
                if(!is_array($this->act_usuario->Value) && !strlen($this->act_usuario->Value) && $this->act_usuario->Value !== false)
                $this->act_usuario->SetText(' ');
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @115-DD45F12A
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlact_CodAuxiliar"] = CCGetFromGet("act_CodAuxiliar", "");
    }
//End Initialize Method

//Validate Method @115-3D56E9DC
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->act_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->par_Valor4->Validate() && $Validation);
        $Validation = ($this->act_Descripcion->Validate() && $Validation);
        $Validation = ($this->act_Descripcion1->Validate() && $Validation);
        $Validation = ($this->lbCategAux->Validate() && $Validation);
        $Validation = ($this->act_SubCategoria->Validate() && $Validation);
        $Validation = ($this->act_Abreviatura->Validate() && $Validation);
        $Validation = ($this->act_Tipo->Validate() && $Validation);
        $Validation = ($this->act_Grupo->Validate() && $Validation);
        $Validation = ($this->act_SubGrupo->Validate() && $Validation);
        $Validation = ($this->act_Marca->Validate() && $Validation);
        $Validation = ($this->act_Modelo->Validate() && $Validation);
        $Validation = ($this->act_NumSerie->Validate() && $Validation);
        $Validation = ($this->act_UniMedida->Validate() && $Validation);
        $Validation = ($this->act_CodAnterior->Validate() && $Validation);
        $Validation = ($this->act_IvaFlag->Validate() && $Validation);
        $Validation = ($this->act_IceFlag->Validate() && $Validation);
        $Validation = ($this->act_Im3Flag->Validate() && $Validation);
        $Validation = ($this->act_Im4Flag->Validate() && $Validation);
        $Validation = ($this->act_Im5Flag->Validate() && $Validation);
        $Validation = ($this->act_SufijoCuenta->Validate() && $Validation);
        $Validation = ($this->act_Inventariable->Validate() && $Validation);
        $Validation = ($this->act_usuario->Validate() && $Validation);
        $Validation = ($this->act_FecRegistro->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->act_CodAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->par_Valor4->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Descripcion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->lbCategAux->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_SubCategoria->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Abreviatura->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Tipo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Grupo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_SubGrupo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Marca->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Modelo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_NumSerie->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_UniMedida->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_CodAnterior->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_IvaFlag->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_IceFlag->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Im3Flag->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Im4Flag->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Im5Flag->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_SufijoCuenta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_Inventariable->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_usuario->Errors->Count() == 0);
        $Validation =  $Validation && ($this->act_FecRegistro->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @115-83591CCF
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->act_CodAuxiliar->Errors->Count());
        $errors = ($errors || $this->par_Valor4->Errors->Count());
        $errors = ($errors || $this->act_Descripcion->Errors->Count());
        $errors = ($errors || $this->act_Descripcion1->Errors->Count());
        $errors = ($errors || $this->lbCategAux->Errors->Count());
        $errors = ($errors || $this->act_SubCategoria->Errors->Count());
        $errors = ($errors || $this->act_Abreviatura->Errors->Count());
        $errors = ($errors || $this->act_Tipo->Errors->Count());
        $errors = ($errors || $this->act_Grupo->Errors->Count());
        $errors = ($errors || $this->act_SubGrupo->Errors->Count());
        $errors = ($errors || $this->act_Marca->Errors->Count());
        $errors = ($errors || $this->act_Modelo->Errors->Count());
        $errors = ($errors || $this->act_NumSerie->Errors->Count());
        $errors = ($errors || $this->act_UniMedida->Errors->Count());
        $errors = ($errors || $this->act_CodAnterior->Errors->Count());
        $errors = ($errors || $this->act_IvaFlag->Errors->Count());
        $errors = ($errors || $this->act_IceFlag->Errors->Count());
        $errors = ($errors || $this->act_Im3Flag->Errors->Count());
        $errors = ($errors || $this->act_Im4Flag->Errors->Count());
        $errors = ($errors || $this->act_Im5Flag->Errors->Count());
        $errors = ($errors || $this->act_SufijoCuenta->Errors->Count());
        $errors = ($errors || $this->act_Inventariable->Errors->Count());
        $errors = ($errors || $this->act_usuario->Errors->Count());
        $errors = ($errors || $this->act_FecRegistro->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @115-14846506
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->ds->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            } else if(strlen(CCGetParam("btNuevo", ""))) {
                $this->PressedButton = "btNuevo";
            } else if(strlen(CCGetParam("Button1", ""))) {
                $this->PressedButton = "Button1";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button1") {
            if(!CCGetEvent($this->Button1->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdAc_search2.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "act_CodAuxiliar"));
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
            } else if($this->PressedButton == "btNuevo") {
                if(!CCGetEvent($this->btNuevo->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = $FileName. "?" . CCGetQueryString("QueryString", Array("ccsForm", "act_CodAuxiliar"));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @115-AB4E49ED
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->act_CodAuxiliar->SetValue($this->act_CodAuxiliar->GetValue());
        $this->ds->act_SubCategoria->SetValue($this->act_SubCategoria->GetValue());
        $this->ds->act_Abreviatura->SetValue($this->act_Abreviatura->GetValue());
        $this->ds->act_Descripcion->SetValue($this->act_Descripcion->GetValue());
        $this->ds->act_Descripcion1->SetValue($this->act_Descripcion1->GetValue());
        $this->ds->act_Tipo->SetValue($this->act_Tipo->GetValue());
        $this->ds->act_Grupo->SetValue($this->act_Grupo->GetValue());
        $this->ds->act_SubGrupo->SetValue($this->act_SubGrupo->GetValue());
        $this->ds->act_Marca->SetValue($this->act_Marca->GetValue());
        $this->ds->act_Modelo->SetValue($this->act_Modelo->GetValue());
        $this->ds->act_NumSerie->SetValue($this->act_NumSerie->GetValue());
        $this->ds->act_UniMedida->SetValue($this->act_UniMedida->GetValue());
        $this->ds->act_CodAnterior->SetValue($this->act_CodAnterior->GetValue());
        $this->ds->act_IvaFlag->SetValue($this->act_IvaFlag->GetValue());
        $this->ds->act_IceFlag->SetValue($this->act_IceFlag->GetValue());
        $this->ds->act_Im3Flag->SetValue($this->act_Im3Flag->GetValue());
        $this->ds->act_Im4Flag->SetValue($this->act_Im4Flag->GetValue());
        $this->ds->act_Im5Flag->SetValue($this->act_Im5Flag->GetValue());
        $this->ds->act_SufijoCuenta->SetValue($this->act_SufijoCuenta->GetValue());
        $this->ds->act_Inventariable->SetValue($this->act_Inventariable->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @115-73B17852
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->act_SubCategoria->SetValue($this->act_SubCategoria->GetValue());
        $this->ds->act_Abreviatura->SetValue($this->act_Abreviatura->GetValue());
        $this->ds->act_Descripcion->SetValue($this->act_Descripcion->GetValue());
        $this->ds->act_Descripcion1->SetValue($this->act_Descripcion1->GetValue());
        $this->ds->act_Tipo->SetValue($this->act_Tipo->GetValue());
        $this->ds->act_Grupo->SetValue($this->act_Grupo->GetValue());
        $this->ds->act_SubGrupo->SetValue($this->act_SubGrupo->GetValue());
        $this->ds->act_Marca->SetValue($this->act_Marca->GetValue());
        $this->ds->act_Modelo->SetValue($this->act_Modelo->GetValue());
        $this->ds->act_NumSerie->SetValue($this->act_NumSerie->GetValue());
        $this->ds->act_UniMedida->SetValue($this->act_UniMedida->GetValue());
        $this->ds->act_CodAnterior->SetValue($this->act_CodAnterior->GetValue());
        $this->ds->act_IvaFlag->SetValue($this->act_IvaFlag->GetValue());
        $this->ds->act_IceFlag->SetValue($this->act_IceFlag->GetValue());
        $this->ds->act_Im3Flag->SetValue($this->act_Im3Flag->GetValue());
        $this->ds->act_Im4Flag->SetValue($this->act_Im4Flag->GetValue());
        $this->ds->act_Im5Flag->SetValue($this->act_Im5Flag->GetValue());
        $this->ds->act_SufijoCuenta->SetValue($this->act_SufijoCuenta->GetValue());
        $this->ds->act_Inventariable->SetValue($this->act_Inventariable->GetValue());
        $this->ds->act_usuario->SetValue($this->act_usuario->GetValue());
        $this->ds->act_FecRegistro->SetValue($this->act_FecRegistro->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @115-91867A4A
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @115-9085081D
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->lbCategAux->Prepare();
        $this->act_SubCategoria->Prepare();
        $this->act_Tipo->Prepare();
        $this->act_Grupo->Prepare();
        $this->act_SubGrupo->Prepare();
        $this->act_Marca->Prepare();
        $this->act_UniMedida->Prepare();
        $this->act_IvaFlag->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode)
        {
            $this->ds->open();
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record CoAdAc_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->act_CodAuxiliar->SetValue($this->ds->act_CodAuxiliar->GetValue());
                        $this->par_Valor4->SetValue($this->ds->par_Valor4->GetValue());
                        $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                        $this->act_Descripcion1->SetValue($this->ds->act_Descripcion1->GetValue());
                        $this->act_SubCategoria->SetValue($this->ds->act_SubCategoria->GetValue());
                        $this->act_Abreviatura->SetValue($this->ds->act_Abreviatura->GetValue());
                        $this->act_Tipo->SetValue($this->ds->act_Tipo->GetValue());
                        $this->act_Grupo->SetValue($this->ds->act_Grupo->GetValue());
                        $this->act_SubGrupo->SetValue($this->ds->act_SubGrupo->GetValue());
                        $this->act_Marca->SetValue($this->ds->act_Marca->GetValue());
                        $this->act_Modelo->SetValue($this->ds->act_Modelo->GetValue());
                        $this->act_NumSerie->SetValue($this->ds->act_NumSerie->GetValue());
                        $this->act_UniMedida->SetValue($this->ds->act_UniMedida->GetValue());
                        $this->act_CodAnterior->SetValue($this->ds->act_CodAnterior->GetValue());
                        $this->act_IvaFlag->SetValue($this->ds->act_IvaFlag->GetValue());
                        $this->act_IceFlag->SetValue($this->ds->act_IceFlag->GetValue());
                        $this->act_Im3Flag->SetValue($this->ds->act_Im3Flag->GetValue());
                        $this->act_Im4Flag->SetValue($this->ds->act_Im4Flag->GetValue());
                        $this->act_Im5Flag->SetValue($this->ds->act_Im5Flag->GetValue());
                        $this->act_SufijoCuenta->SetValue($this->ds->act_SufijoCuenta->GetValue());
                        $this->act_Inventariable->SetValue($this->ds->act_Inventariable->GetValue());
                        $this->act_usuario->SetValue($this->ds->act_usuario->GetValue());
                        $this->act_FecRegistro->SetValue($this->ds->act_FecRegistro->GetValue());
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

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbTitulo->Errors->ToString();
            $Error .= $this->act_CodAuxiliar->Errors->ToString();
            $Error .= $this->par_Valor4->Errors->ToString();
            $Error .= $this->act_Descripcion->Errors->ToString();
            $Error .= $this->act_Descripcion1->Errors->ToString();
            $Error .= $this->lbCategAux->Errors->ToString();
            $Error .= $this->act_SubCategoria->Errors->ToString();
            $Error .= $this->act_Abreviatura->Errors->ToString();
            $Error .= $this->act_Tipo->Errors->ToString();
            $Error .= $this->act_Grupo->Errors->ToString();
            $Error .= $this->act_SubGrupo->Errors->ToString();
            $Error .= $this->act_Marca->Errors->ToString();
            $Error .= $this->act_Modelo->Errors->ToString();
            $Error .= $this->act_NumSerie->Errors->ToString();
            $Error .= $this->act_UniMedida->Errors->ToString();
            $Error .= $this->act_CodAnterior->Errors->ToString();
            $Error .= $this->act_IvaFlag->Errors->ToString();
            $Error .= $this->act_IceFlag->Errors->ToString();
            $Error .= $this->act_Im3Flag->Errors->ToString();
            $Error .= $this->act_Im4Flag->Errors->ToString();
            $Error .= $this->act_Im5Flag->Errors->ToString();
            $Error .= $this->act_SufijoCuenta->Errors->ToString();
            $Error .= $this->act_Inventariable->Errors->ToString();
            $Error .= $this->act_usuario->Errors->ToString();
            $Error .= $this->act_FecRegistro->Errors->ToString();
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
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->lbTitulo->Show();
        $this->act_CodAuxiliar->Show();
        $this->par_Valor4->Show();
        $this->act_Descripcion->Show();
        $this->act_Descripcion1->Show();
        $this->lbCategAux->Show();
        $this->act_SubCategoria->Show();
        $this->act_Abreviatura->Show();
        $this->act_Tipo->Show();
        $this->act_Grupo->Show();
        $this->act_SubGrupo->Show();
        $this->act_Marca->Show();
        $this->act_Modelo->Show();
        $this->act_NumSerie->Show();
        $this->act_UniMedida->Show();
        $this->act_CodAnterior->Show();
        $this->act_IvaFlag->Show();
        $this->act_IceFlag->Show();
        $this->act_Im3Flag->Show();
        $this->act_Im4Flag->Show();
        $this->act_Im5Flag->Show();
        $this->act_SufijoCuenta->Show();
        $this->act_Inventariable->Show();
        $this->act_usuario->Show();
        $this->act_FecRegistro->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->btNuevo->Show();
        $this->Button1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdAc_mant Class @115-FCB6E20C

class clsCoAdAc_mantDataSource extends clsDBdatos {  //CoAdAc_mantDataSource Class @115-CA73BC71

//DataSource Variables @115-042A5128
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $lbTitulo;
    var $act_CodAuxiliar;
    var $par_Valor4;
    var $act_Descripcion;
    var $act_Descripcion1;
    var $lbCategAux;
    var $act_SubCategoria;
    var $act_Abreviatura;
    var $act_Tipo;
    var $act_Grupo;
    var $act_SubGrupo;
    var $act_Marca;
    var $act_Modelo;
    var $act_NumSerie;
    var $act_UniMedida;
    var $act_CodAnterior;
    var $act_IvaFlag;
    var $act_IceFlag;
    var $act_Im3Flag;
    var $act_Im4Flag;
    var $act_Im5Flag;
    var $act_SufijoCuenta;
    var $act_Inventariable;
    var $act_usuario;
    var $act_FecRegistro;
//End DataSource Variables

//DataSourceClass_Initialize Event @115-AACA7CAF
    function clsCoAdAc_mantDataSource()
    {
        $this->ErrorBlock = "Record CoAdAc_mant/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->act_CodAuxiliar = new clsField("act_CodAuxiliar", ccsInteger, "");
        $this->par_Valor4 = new clsField("par_Valor4", ccsText, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->act_Descripcion1 = new clsField("act_Descripcion1", ccsText, "");
        $this->lbCategAux = new clsField("lbCategAux", ccsInteger, "");
        $this->act_SubCategoria = new clsField("act_SubCategoria", ccsInteger, "");
        $this->act_Abreviatura = new clsField("act_Abreviatura", ccsText, "");
        $this->act_Tipo = new clsField("act_Tipo", ccsInteger, "");
        $this->act_Grupo = new clsField("act_Grupo", ccsInteger, "");
        $this->act_SubGrupo = new clsField("act_SubGrupo", ccsInteger, "");
        $this->act_Marca = new clsField("act_Marca", ccsInteger, "");
        $this->act_Modelo = new clsField("act_Modelo", ccsText, "");
        $this->act_NumSerie = new clsField("act_NumSerie", ccsText, "");
        $this->act_UniMedida = new clsField("act_UniMedida", ccsInteger, "");
        $this->act_CodAnterior = new clsField("act_CodAnterior", ccsText, "");
        $this->act_IvaFlag = new clsField("act_IvaFlag", ccsInteger, "");
        $this->act_IceFlag = new clsField("act_IceFlag", ccsInteger, "");
        $this->act_Im3Flag = new clsField("act_Im3Flag", ccsInteger, "");
        $this->act_Im4Flag = new clsField("act_Im4Flag", ccsInteger, "");
        $this->act_Im5Flag = new clsField("act_Im5Flag", ccsInteger, "");
        $this->act_SufijoCuenta = new clsField("act_SufijoCuenta", ccsText, "");
        $this->act_Inventariable = new clsField("act_Inventariable", ccsInteger, "");
        $this->act_usuario = new clsField("act_usuario", ccsText, "");
        $this->act_FecRegistro = new clsField("act_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End DataSourceClass_Initialize Event

//Prepare Method @115-7B3596B9
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlact_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urlact_CodAuxiliar"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "act_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = "par_clave='ACTSUB'";
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @115-51EFCB3B
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT conactivos.*, par_Valor4  " .
        "FROM conactivos LEFT JOIN genparametros ON " .
        "conactivos.act_SubCategoria = genparametros.par_Secuencia";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @115-437C5462
    function SetValues()
    {
        $this->act_CodAuxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->par_Valor4->SetDBValue($this->f("par_Valor4"));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->act_Descripcion1->SetDBValue($this->f("act_Descripcion1"));
        $this->act_SubCategoria->SetDBValue(trim($this->f("act_SubCategoria")));
        $this->act_Abreviatura->SetDBValue($this->f("act_Abreviatura"));
        $this->act_Tipo->SetDBValue(trim($this->f("act_Tipo")));
        $this->act_Grupo->SetDBValue(trim($this->f("act_Grupo")));
        $this->act_SubGrupo->SetDBValue(trim($this->f("act_SubGrupo")));
        $this->act_Marca->SetDBValue(trim($this->f("act_Marca")));
        $this->act_Modelo->SetDBValue($this->f("act_Modelo"));
        $this->act_NumSerie->SetDBValue($this->f("act_NumSerie"));
        $this->act_UniMedida->SetDBValue(trim($this->f("act_UniMedida")));
        $this->act_CodAnterior->SetDBValue($this->f("act_CodAnterior"));
        $this->act_IvaFlag->SetDBValue(trim($this->f("act_IvaFlag")));
        $this->act_IceFlag->SetDBValue(trim($this->f("act_IceFlag")));
        $this->act_Im3Flag->SetDBValue(trim($this->f("act_Im3Flag")));
        $this->act_Im4Flag->SetDBValue(trim($this->f("act_Im4Flag")));
        $this->act_Im5Flag->SetDBValue(trim($this->f("act_Im5Flag")));
        $this->act_SufijoCuenta->SetDBValue($this->f("act_SufijoCuenta"));
        $this->act_Inventariable->SetDBValue(trim($this->f("act_Inventariable")));
        $this->act_usuario->SetDBValue($this->f("act_usuario"));
        $this->act_FecRegistro->SetDBValue(trim($this->f("act_FecRegistro")));
    }
//End SetValues Method

//Insert Method @115-70711778
    function Insert()
    {
        $this->CmdExecution = true;
        $this->cp["act_CodAuxiliar"] = new clsSQLParameter("ctrlact_CodAuxiliar", ccsInteger, "", "", $this->act_CodAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_SubCategoria"] = new clsSQLParameter("ctrlact_SubCategoria", ccsInteger, "", "", $this->act_SubCategoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Abreviatura"] = new clsSQLParameter("ctrlact_Abreviatura", ccsText, "", "", $this->act_Abreviatura->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_Descripcion"] = new clsSQLParameter("ctrlact_Descripcion", ccsText, "", "", $this->act_Descripcion->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_Descripcion1"] = new clsSQLParameter("ctrlact_Descripcion1", ccsText, "", "", $this->act_Descripcion1->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_Tipo"] = new clsSQLParameter("ctrlact_Tipo", ccsInteger, "", "", $this->act_Tipo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Grupo"] = new clsSQLParameter("ctrlact_Grupo", ccsInteger, "", "", $this->act_Grupo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_SubGrupo"] = new clsSQLParameter("ctrlact_SubGrupo", ccsInteger, "", "", $this->act_SubGrupo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Marca"] = new clsSQLParameter("ctrlact_Marca", ccsInteger, "", "", $this->act_Marca->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Modelo"] = new clsSQLParameter("ctrlact_Modelo", ccsText, "", "", $this->act_Modelo->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_NumSerie"] = new clsSQLParameter("ctrlact_NumSerie", ccsText, "", "", $this->act_NumSerie->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_UniMedida"] = new clsSQLParameter("ctrlact_UniMedida", ccsInteger, "", "", $this->act_UniMedida->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["act_CodAnterior"] = new clsSQLParameter("ctrlact_CodAnterior", ccsText, "", "", $this->act_CodAnterior->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_IvaFlag"] = new clsSQLParameter("ctrlact_IvaFlag", ccsInteger, "", "", $this->act_IvaFlag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_IceFlag"] = new clsSQLParameter("ctrlact_IceFlag", ccsInteger, "", "", $this->act_IceFlag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Im3Flag"] = new clsSQLParameter("ctrlact_Im3Flag", ccsInteger, "", "", $this->act_Im3Flag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Im4Flag"] = new clsSQLParameter("ctrlact_Im4Flag", ccsInteger, "", "", $this->act_Im4Flag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Im5Flag"] = new clsSQLParameter("ctrlact_Im5Flag", ccsInteger, "", "", $this->act_Im5Flag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_SufijoCuenta"] = new clsSQLParameter("ctrlact_SufijoCuenta", ccsText, "", "", $this->act_SufijoCuenta->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_Inventariable"] = new clsSQLParameter("ctrlact_Inventariable", ccsInteger, "", "", $this->act_Inventariable->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_usuario"] = new clsSQLParameter("expr176", ccsText, "", "", $_SESSION['g_user'], "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO conactivos ("
             . "act_CodAuxiliar, "
             . "act_SubCategoria, "
             . "act_Abreviatura, "
             . "act_Descripcion, "
             . "act_Descripcion1, "
             . "act_Tipo, "
             . "act_Grupo, "
             . "act_SubGrupo, "
             . "act_Marca, "
             . "act_Modelo, "
             . "act_NumSerie, "
             . "act_UniMedida, "
             . "act_CodAnterior, "
             . "act_IvaFlag, "
             . "act_IceFlag, "
             . "act_Im3Flag, "
             . "act_Im4Flag, "
             . "act_Im5Flag, "
             . "act_SufijoCuenta, "
             . "act_Inventariable, "
             . "act_usuario"
             . ") VALUES ("
             . $this->ToSQL($this->cp["act_CodAuxiliar"]->GetDBValue(), $this->cp["act_CodAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_SubCategoria"]->GetDBValue(), $this->cp["act_SubCategoria"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Abreviatura"]->GetDBValue(), $this->cp["act_Abreviatura"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Descripcion"]->GetDBValue(), $this->cp["act_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Descripcion1"]->GetDBValue(), $this->cp["act_Descripcion1"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Tipo"]->GetDBValue(), $this->cp["act_Tipo"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Grupo"]->GetDBValue(), $this->cp["act_Grupo"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_SubGrupo"]->GetDBValue(), $this->cp["act_SubGrupo"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Marca"]->GetDBValue(), $this->cp["act_Marca"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Modelo"]->GetDBValue(), $this->cp["act_Modelo"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_NumSerie"]->GetDBValue(), $this->cp["act_NumSerie"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_UniMedida"]->GetDBValue(), $this->cp["act_UniMedida"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_CodAnterior"]->GetDBValue(), $this->cp["act_CodAnterior"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_IvaFlag"]->GetDBValue(), $this->cp["act_IvaFlag"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_IceFlag"]->GetDBValue(), $this->cp["act_IceFlag"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Im3Flag"]->GetDBValue(), $this->cp["act_Im3Flag"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Im4Flag"]->GetDBValue(), $this->cp["act_Im4Flag"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Im5Flag"]->GetDBValue(), $this->cp["act_Im5Flag"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_SufijoCuenta"]->GetDBValue(), $this->cp["act_SufijoCuenta"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Inventariable"]->GetDBValue(), $this->cp["act_Inventariable"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_usuario"]->GetDBValue(), $this->cp["act_usuario"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @115-0D1CCF91
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["act_SubCategoria"] = new clsSQLParameter("ctrlact_SubCategoria", ccsInteger, "", "", $this->act_SubCategoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Abreviatura"] = new clsSQLParameter("ctrlact_Abreviatura", ccsText, "", "", $this->act_Abreviatura->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_Descripcion"] = new clsSQLParameter("ctrlact_Descripcion", ccsText, "", "", $this->act_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Descripcion1"] = new clsSQLParameter("ctrlact_Descripcion1", ccsText, "", "", $this->act_Descripcion1->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_Tipo"] = new clsSQLParameter("ctrlact_Tipo", ccsInteger, "", "", $this->act_Tipo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Grupo"] = new clsSQLParameter("ctrlact_Grupo", ccsInteger, "", "", $this->act_Grupo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_SubGrupo"] = new clsSQLParameter("ctrlact_SubGrupo", ccsInteger, "", "", $this->act_SubGrupo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Marca"] = new clsSQLParameter("ctrlact_Marca", ccsInteger, "", "", $this->act_Marca->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Modelo"] = new clsSQLParameter("ctrlact_Modelo", ccsText, "", "", $this->act_Modelo->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_NumSerie"] = new clsSQLParameter("ctrlact_NumSerie", ccsText, "", "", $this->act_NumSerie->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_UniMedida"] = new clsSQLParameter("ctrlact_UniMedida", ccsInteger, "", "", $this->act_UniMedida->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_CodAnterior"] = new clsSQLParameter("ctrlact_CodAnterior", ccsText, "", "", $this->act_CodAnterior->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_IvaFlag"] = new clsSQLParameter("ctrlact_IvaFlag", ccsInteger, "", "", $this->act_IvaFlag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_IceFlag"] = new clsSQLParameter("ctrlact_IceFlag", ccsInteger, "", "", $this->act_IceFlag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Im3Flag"] = new clsSQLParameter("ctrlact_Im3Flag", ccsInteger, "", "", $this->act_Im3Flag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Im4Flag"] = new clsSQLParameter("ctrlact_Im4Flag", ccsInteger, "", "", $this->act_Im4Flag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Im5Flag"] = new clsSQLParameter("ctrlact_Im5Flag", ccsInteger, "", "", $this->act_Im5Flag->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_SufijoCuenta"] = new clsSQLParameter("ctrlact_SufijoCuenta", ccsText, "", "", $this->act_SufijoCuenta->GetValue(), ' ' , false, $this->ErrorBlock);
        $this->cp["act_Inventariable"] = new clsSQLParameter("ctrlact_Inventariable", ccsInteger, "", "", $this->act_Inventariable->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_usuario"] = new clsSQLParameter("ctrlact_usuario", ccsText, "", "", $this->act_usuario->GetValue(), ' ', false, $this->ErrorBlock);
        $this->cp["act_FecRegistro"] = new clsSQLParameter("ctrlact_FecRegistro", ccsDate, Array("mm", "/", "dd", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->act_FecRegistro->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlact_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("act_CodAuxiliar", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "act_CodAuxiliar", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "UPDATE conactivos SET "
             . "act_SubCategoria=" . $this->ToSQL($this->cp["act_SubCategoria"]->GetDBValue(), $this->cp["act_SubCategoria"]->DataType) . ", "
             . "act_Abreviatura=" . $this->ToSQL($this->cp["act_Abreviatura"]->GetDBValue(), $this->cp["act_Abreviatura"]->DataType) . ", "
             . "act_Descripcion=" . $this->ToSQL($this->cp["act_Descripcion"]->GetDBValue(), $this->cp["act_Descripcion"]->DataType) . ", "
             . "act_Descripcion1=" . $this->ToSQL($this->cp["act_Descripcion1"]->GetDBValue(), $this->cp["act_Descripcion1"]->DataType) . ", "
             . "act_Tipo=" . $this->ToSQL($this->cp["act_Tipo"]->GetDBValue(), $this->cp["act_Tipo"]->DataType) . ", "
             . "act_Grupo=" . $this->ToSQL($this->cp["act_Grupo"]->GetDBValue(), $this->cp["act_Grupo"]->DataType) . ", "
             . "act_SubGrupo=" . $this->ToSQL($this->cp["act_SubGrupo"]->GetDBValue(), $this->cp["act_SubGrupo"]->DataType) . ", "
             . "act_Marca=" . $this->ToSQL($this->cp["act_Marca"]->GetDBValue(), $this->cp["act_Marca"]->DataType) . ", "
             . "act_Modelo=" . $this->ToSQL($this->cp["act_Modelo"]->GetDBValue(), $this->cp["act_Modelo"]->DataType) . ", "
             . "act_NumSerie=" . $this->ToSQL($this->cp["act_NumSerie"]->GetDBValue(), $this->cp["act_NumSerie"]->DataType) . ", "
             . "act_UniMedida=" . $this->ToSQL($this->cp["act_UniMedida"]->GetDBValue(), $this->cp["act_UniMedida"]->DataType) . ", "
             . "act_CodAnterior=" . $this->ToSQL($this->cp["act_CodAnterior"]->GetDBValue(), $this->cp["act_CodAnterior"]->DataType) . ", "
             . "act_IvaFlag=" . $this->ToSQL($this->cp["act_IvaFlag"]->GetDBValue(), $this->cp["act_IvaFlag"]->DataType) . ", "
             . "act_IceFlag=" . $this->ToSQL($this->cp["act_IceFlag"]->GetDBValue(), $this->cp["act_IceFlag"]->DataType) . ", "
             . "act_Im3Flag=" . $this->ToSQL($this->cp["act_Im3Flag"]->GetDBValue(), $this->cp["act_Im3Flag"]->DataType) . ", "
             . "act_Im4Flag=" . $this->ToSQL($this->cp["act_Im4Flag"]->GetDBValue(), $this->cp["act_Im4Flag"]->DataType) . ", "
             . "act_Im5Flag=" . $this->ToSQL($this->cp["act_Im5Flag"]->GetDBValue(), $this->cp["act_Im5Flag"]->DataType) . ", "
             . "act_SufijoCuenta=" . $this->ToSQL($this->cp["act_SufijoCuenta"]->GetDBValue(), $this->cp["act_SufijoCuenta"]->DataType) . ", "
             . "act_Inventariable=" . $this->ToSQL($this->cp["act_Inventariable"]->GetDBValue(), $this->cp["act_Inventariable"]->DataType) . ", "
             . "act_usuario=" . $this->ToSQL($this->cp["act_usuario"]->GetDBValue(), $this->cp["act_usuario"]->DataType) . ", "
             . "act_FecRegistro=" . $this->ToSQL($this->cp["act_FecRegistro"]->GetDBValue(), $this->cp["act_FecRegistro"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @115-7A7C2C27
    function Delete()
    {
        $this->CmdExecution = true;
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlact_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("act_CodAuxiliar", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "act_CodAuxiliar", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "DELETE FROM conactivos";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End CoAdAc_mantDataSource Class @115-FCB6E20C

class clsEditableGridCoAdAc_cate { //CoAdAc_cate Class @200-004CA6E9

//Variables @200-29A02ECE

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormParameters;
    var $FormState;
    var $FormEnctype;
    var $CachedColumns;
    var $TotalRows;
    var $UpdatedRows;
    var $EmptyRows;
    var $Visible;
    var $EditableGridset;
    var $RowsErrors;
    var $ds;
    var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_cat_Categoria;
    var $Sorter_cat_FecIncorp;
    var $Sorter_cat_Activo;
    var $Navigator;
//End Variables

//Class_Initialize Event @200-974B18EF
    function clsEditableGridCoAdAc_cate()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid CoAdAc_cate/Error";
        $this->ComponentName = "CoAdAc_cate";
        $this->CachedColumns["cat_CodAuxiliar"][0] = "cat_CodAuxiliar";
        $this->CachedColumns["cat_Categoria"][0] = "cat_Categoria";
        $this->ds = new clsCoAdAc_cateDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 5;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 1;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if(!$this->Visible) return;

        $CCSForm = CCGetFromGet("ccsForm", "");
        $this->FormEnctype = "application/x-www-form-urlencoded";
        $this->FormSubmitted = ($CCSForm == $this->ComponentName);
        if($this->FormSubmitted) {
            $this->FormState = CCGetFromPost("FormState", "");
            $this->SetFormState($this->FormState);
        } else {
            $this->FormState = "";
        }
        $Method = $this->FormSubmitted ? ccsPost : ccsGet;

        $this->SorterName = CCGetParam("CoAdAc_cateOrder", "");
        $this->SorterDirection = CCGetParam("CoAdAc_cateDir", "");

        $this->Sorter_cat_Categoria = new clsSorter($this->ComponentName, "Sorter_cat_Categoria", $FileName);
        $this->Sorter_cat_FecIncorp = new clsSorter($this->ComponentName, "Sorter_cat_FecIncorp", $FileName);
        $this->Sorter_cat_Activo = new clsSorter($this->ComponentName, "Sorter_cat_Activo", $FileName);
        $this->cat_Categoria = new clsControl(ccsListBox, "cat_Categoria", "Cat Categoria", ccsInteger, "");
        $this->cat_Categoria->DSType = dsTable;
        list($this->cat_Categoria->BoundColumn, $this->cat_Categoria->TextColumn, $this->cat_Categoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
        $this->cat_Categoria->ds = new clsDBdatos();
        $this->cat_Categoria->ds->SQL = "SELECT *  " .
"FROM genparametros";
        $this->cat_Categoria->ds->Order = "par_Descripcion";
        $this->cat_Categoria->ds->wp = new clsSQLParameters();
        $this->cat_Categoria->ds->wp->Criterion[1] = "par_clave='CAUTI'";
        $this->cat_Categoria->ds->Where = 
             $this->cat_Categoria->ds->wp->Criterion[1];
        $this->cat_Categoria->Required = true;
        $this->cat_CodAuxiliar = new clsControl(ccsHidden, "cat_CodAuxiliar", "Cat Cod Auxiliar", ccsInteger, "");
        $this->cat_FecIncorp = new clsControl(ccsTextBox, "cat_FecIncorp", "Cat Fec Incorp", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->cat_FecIncorp->Required = true;
        $this->DatePicker_cat_FecIncorp = new clsDatePicker("DatePicker_cat_FecIncorp", "CoAdAc_cate", "cat_FecIncorp");
        $this->cat_Activo = new clsControl(ccsListBox, "cat_Activo", "Cat Activo", ccsInteger, "");
        $this->cat_Activo->DSType = dsListOfValues;
        $this->cat_Activo->Values = array(array("1", "Activo"), array("0", "Inactivo"));
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @200-D56D31B8
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlact_CodAuxiliar"] = CCGetFromGet("act_CodAuxiliar", "");
    }
//End Initialize Method

//GetFormParameters Method @200-15CC79D5
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["cat_Categoria"][$RowNumber] = CCGetFromPost("cat_Categoria_" . $RowNumber);
            $this->FormParameters["cat_CodAuxiliar"][$RowNumber] = CCGetFromPost("cat_CodAuxiliar_" . $RowNumber);
            $this->FormParameters["cat_FecIncorp"][$RowNumber] = CCGetFromPost("cat_FecIncorp_" . $RowNumber);
            $this->FormParameters["cat_Activo"][$RowNumber] = CCGetFromPost("cat_Activo_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @200-61FFD6EB
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cat_CodAuxiliar"] = $this->CachedColumns["cat_CodAuxiliar"][$RowNumber];
            $this->ds->CachedColumns["cat_Categoria"] = $this->CachedColumns["cat_Categoria"][$RowNumber];
            $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
            $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
            $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if(!$this->CheckBox_Delete->Value)
                    $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
            else if($this->CheckInsert($RowNumber))
            {
                $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
        }
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//ValidateRow Method @200-533B0733
    function ValidateRow($RowNumber)
    {
        $this->cat_Categoria->Validate();
        $this->cat_CodAuxiliar->Validate();
        $this->cat_FecIncorp->Validate();
        $this->cat_Activo->Validate();
        $this->CheckBox_Delete->Validate();
        $this->RowErrors = new clsErrors();
        $errors = $this->cat_Categoria->Errors->ToString();
        $errors .= $this->cat_CodAuxiliar->Errors->ToString();
        $errors .= $this->cat_FecIncorp->Errors->ToString();
        $errors .= $this->cat_Activo->Errors->ToString();
        $errors .= $this->CheckBox_Delete->Errors->ToString();
        $this->cat_Categoria->Errors->Clear();
        $this->cat_CodAuxiliar->Errors->Clear();
        $this->cat_FecIncorp->Errors->Clear();
        $this->cat_Activo->Errors->Clear();
        $this->CheckBox_Delete->Errors->Clear();
        $errors .=$this->RowErrors->ToString();
        $this->RowsErrors[$RowNumber] = $errors;
        return $errors ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @200-565E7166
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["cat_Categoria"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_CodAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_FecIncorp"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_Activo"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @200-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @200-7B861278
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        if(!$this->FormSubmitted)
            return;

        $this->GetFormParameters();
        $this->PressedButton = "Button_Submit";
        if(strlen(CCGetParam("Button_Submit", ""))) {
            $this->PressedButton = "Button_Submit";
        } else if(strlen(CCGetParam("Cancel", ""))) {
            $this->PressedButton = "Cancel";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @200-4D51AA69
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cat_CodAuxiliar"] = $this->CachedColumns["cat_CodAuxiliar"][$RowNumber];
            $this->ds->CachedColumns["cat_Categoria"] = $this->CachedColumns["cat_Categoria"][$RowNumber];
            $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
            $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
            $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) { $Validation = ($this->DeleteRow($RowNumber) && $Validation); }
                } else if($this->UpdateAllowed) {
                    $Validation = ($this->UpdateRow($RowNumber) && $Validation);
                }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $Validation = ($this->InsertRow($RowNumber) && $Validation);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0 && $Validation);
    }
//End UpdateGrid Method

//InsertRow Method @200-0EAEECB5
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->cat_Categoria->SetValue($this->cat_Categoria->GetValue());
        $this->ds->cat_FecIncorp->SetValue($this->cat_FecIncorp->GetValue());
        $this->ds->cat_Activo->SetValue($this->cat_Activo->GetValue());
        $this->ds->Insert();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @200-6558923D
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->cat_FecIncorp->SetValue($this->cat_FecIncorp->GetValue());
        $this->ds->cat_Activo->SetValue($this->cat_Activo->GetValue());
        $this->ds->Update();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//DeleteRow Method @200-E90CB5E3
    function DeleteRow($RowNumber)
    {
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @200-05CB4DF2
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var CoAdAc_cateElements;\n";
        $script .= "var CoAdAc_cateEmptyRows = 1;\n";
        $script .= "var " . $this->ComponentName . "cat_CategoriaID = 0;\n";
        $script .= "var " . $this->ComponentName . "cat_CodAuxiliarID = 1;\n";
        $script .= "var " . $this->ComponentName . "cat_FecIncorpID = 2;\n";
        $script .= "var " . $this->ComponentName . "cat_ActivoID = 3;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 4;\n";
        $script .= "\nfunction initCoAdAc_cateElements() {\n";
        $script .= "\tvar ED = document.forms[\"CoAdAc_cate\"];\n";
        $script .= "\tCoAdAc_cateElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.cat_Categoria_" . $i . ", " . "ED.cat_CodAuxiliar_" . $i . ", " . "ED.cat_FecIncorp_" . $i . ", " . "ED.cat_Activo_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @200-B74427C8
    function SetFormState($FormState)
    {
        if(strlen($FormState)) {
            $FormState = str_replace("\\\\", "\\" . ord("\\"), $FormState);
            $FormState = str_replace("\\;", "\\" . ord(";"), $FormState);
            $pieces = explode(";", $FormState);
            $this->UpdatedRows = $pieces[0];
            $this->EmptyRows   = $pieces[1];
            $this->TotalRows = $this->UpdatedRows + $this->EmptyRows;
            $RowNumber = 0;
            for($i = 2; $i < sizeof($pieces); $i = $i + 2)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cat_Categoria"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = "";
                $this->CachedColumns["cat_Categoria"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @200-FBDD9216
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cat_CodAuxiliar"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cat_Categoria"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @200-EA3366DE
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cat_Categoria->Prepare();
        $this->cat_Activo->Prepare();

        $this->ds->open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) { return; }

        $this->Button_Submit->Visible = $this->Button_Submit->Visible && ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
        if($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed))
        {
            do
            {
                $RowNumber++;
                if($is_next_record) {
                    $NonEmptyRows++;
                    $this->ds->SetValues();
                } else {
                }
                if(!$is_next_record || !$this->DeleteAllowed)
                    $this->CheckBox_Delete->Visible = false;
                if(!$this->FormSubmitted && $is_next_record) {
                    $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = $this->ds->CachedColumns["cat_CodAuxiliar"];
                    $this->CachedColumns["cat_Categoria"][$RowNumber] = $this->ds->CachedColumns["cat_Categoria"];
                    $this->cat_Categoria->SetValue($this->ds->cat_Categoria->GetValue());
                    $this->cat_CodAuxiliar->SetValue($this->ds->cat_CodAuxiliar->GetValue());
                    $this->cat_FecIncorp->SetValue($this->ds->cat_FecIncorp->GetValue());
                    $this->cat_Activo->SetValue($this->ds->cat_Activo->GetValue());
                    $this->ValidateRow($RowNumber);
                } else if (!$this->FormSubmitted){
                    $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = "";
                    $this->CachedColumns["cat_Categoria"][$RowNumber] = "";
                    $this->cat_Categoria->SetText("");
                    $this->cat_CodAuxiliar->SetText("");
                    $this->cat_FecIncorp->SetText("");
                    $this->cat_Activo->SetText("");
                    $this->CheckBox_Delete->SetText("");
                } else {
                    $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
                    $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
                    $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
                    $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
                    $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->cat_Categoria->Show($RowNumber);
                $this->cat_CodAuxiliar->Show($RowNumber);
                $this->cat_FecIncorp->Show($RowNumber);
                $this->DatePicker_cat_FecIncorp->Show($RowNumber);
                $this->cat_Activo->Show($RowNumber);
                $this->CheckBox_Delete->Show($RowNumber);
                if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                    $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                    $Tpl->parse("RowError", false);
                } else {
                    $Tpl->setblockvar("RowError", "");
                }
                $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                $Tpl->parse();
                if($is_next_record) $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
                else $EmptyRowsLeft--;
            } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
        } else {
            $Tpl->block_path = $EditableGridPath;
            $Tpl->parse("NoRecords", false);
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_cat_Categoria->Show();
        $this->Sorter_cat_FecIncorp->Show();
        $this->Sorter_cat_Activo->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();

        if($this->CheckErrors()) {
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $Tpl->SetVar("HTMLFormProperties", "method=\"POST\" action=\"" . $this->HTMLFormAction . "\" name=\"" . $this->ComponentName . "\"");
        $Tpl->SetVar("FormState", htmlspecialchars($this->GetFormState($NonEmptyRows)));
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdAc_cate Class @200-FCB6E20C

class clsCoAdAc_cateDataSource extends clsDBdatos {  //CoAdAc_cateDataSource Class @200-B5BC2F28

//DataSource Variables @200-E7248BAE
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $cat_Categoria;
    var $cat_CodAuxiliar;
    var $cat_FecIncorp;
    var $cat_Activo;
    var $CheckBox_Delete;
//End DataSource Variables

//DataSourceClass_Initialize Event @200-85E5C266
    function clsCoAdAc_cateDataSource()
    {
        $this->ErrorBlock = "EditableGrid CoAdAc_cate/Error";
        $this->Initialize();
        $this->cat_Categoria = new clsField("cat_Categoria", ccsInteger, "");
        $this->cat_CodAuxiliar = new clsField("cat_CodAuxiliar", ccsInteger, "");
        $this->cat_FecIncorp = new clsField("cat_FecIncorp", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->cat_Activo = new clsField("cat_Activo", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @200-BDE14459
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cat_Categoria" => array("cat_Categoria", ""), 
            "Sorter_cat_FecIncorp" => array("cat_FecIncorp", ""), 
            "Sorter_cat_Activo" => array("cat_Activo", "")));
    }
//End SetOrder Method

//Prepare Method @200-3420C8F7
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlact_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urlact_CodAuxiliar"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cat_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @200-3538A4AB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM concategorias";
        $this->SQL = "SELECT *  " .
        "FROM concategorias";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @200-17E6B445
    function SetValues()
    {
        $this->CachedColumns["cat_CodAuxiliar"] = $this->f("cat_CodAuxiliar");
        $this->CachedColumns["cat_Categoria"] = $this->f("cat_Categoria");
        $this->cat_Categoria->SetDBValue(trim($this->f("cat_Categoria")));
        $this->cat_CodAuxiliar->SetDBValue(trim($this->f("cat_CodAuxiliar")));
        $this->cat_FecIncorp->SetDBValue(trim($this->f("cat_FecIncorp")));
        $this->cat_Activo->SetDBValue(trim($this->f("cat_Activo")));
    }
//End SetValues Method

//Insert Method @200-DE3A284A
    function Insert()
    {
        $this->CmdExecution = true;
        $this->cp["cat_Categoria"] = new clsSQLParameter("ctrlcat_Categoria", ccsInteger, "", "", $this->cat_Categoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_CodAuxiliar"] = new clsSQLParameter("urlact_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("act_CodAuxiliar", ""), -1, false, $this->ErrorBlock);
        $this->cp["cat_FecIncorp"] = new clsSQLParameter("ctrlcat_FecIncorp", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->cat_FecIncorp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Activo"] = new clsSQLParameter("ctrlcat_Activo", ccsInteger, "", "", $this->cat_Activo->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO concategorias ("
             . "cat_Categoria, "
             . "cat_CodAuxiliar, "
             . "cat_FecIncorp, "
             . "cat_Activo"
             . ") VALUES ("
             . $this->ToSQL($this->cp["cat_Categoria"]->GetDBValue(), $this->cp["cat_Categoria"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_CodAuxiliar"]->GetDBValue(), $this->cp["cat_CodAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_FecIncorp"]->GetDBValue(), $this->cp["cat_FecIncorp"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Activo"]->GetDBValue(), $this->cp["cat_Activo"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @200-9DDB029A
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["cat_FecIncorp"] = new clsSQLParameter("ctrlcat_FecIncorp", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->cat_FecIncorp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Activo"] = new clsSQLParameter("ctrlcat_Activo", ccsInteger, "", "", $this->cat_Activo->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dscat_Categoria", ccsInteger, "", "", $this->CachedColumns["cat_Categoria"], "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $wp->AddParameter("2", "urlact_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("act_CodAuxiliar", ""), "", true);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "cat_CodAuxiliar", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),true);
        $Where = 
             $wp->Criterion[2];
        $this->SQL = "UPDATE concategorias SET "
             . "cat_FecIncorp=" . $this->ToSQL($this->cp["cat_FecIncorp"]->GetDBValue(), $this->cp["cat_FecIncorp"]->DataType) . ", "
             . "cat_Activo=" . $this->ToSQL($this->cp["cat_Activo"]->GetDBValue(), $this->cp["cat_Activo"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @200-29791C2A
    function Delete()
    {
        $this->CmdExecution = true;
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dscat_Categoria", ccsInteger, "", "", $this->CachedColumns["cat_Categoria"], "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $wp->AddParameter("2", "urlact_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("act_CodAuxiliar", ""), -1, false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "cat_CodAuxiliar", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(
             false, 
             $wp->Criterion[1], 
             $wp->Criterion[2]);
        $this->SQL = "DELETE FROM concategorias";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End CoAdAc_cateDataSource Class @200-FCB6E20C

//Include Page implementation @261-4A3CD99F
include_once(RelativePath . "/Co_Files/CoAdAu_varimant.php");
//End Include Page implementation

//Initialize Page @1-D8313270
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

$FileName = "CoAdAc_mant2.php";
$Redirect = "";
$TemplateFileName = "CoAdAc_mant2.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-34A373AA
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$CoAdAc_mant = new clsRecordCoAdAc_mant();
$CoAdAc_cate = new clsEditableGridCoAdAc_cate();
$CoAdAu_varimant = new clsCoAdAu_varimant("");
$CoAdAu_varimant->BindEvents();
$CoAdAu_varimant->Initialize();
$CoAdAc_mant->Initialize();
$CoAdAc_cate->Initialize();

// Events
include("./CoAdAc_mant2_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

$Charset = $Charset ? $Charset : $TemplateEncoding;
if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-51DB8464
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main", $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-7E17DA2C
$Cabecera->Operations();
$CoAdAc_mant->Operation();
$CoAdAc_cate->Operation();
$CoAdAu_varimant->Operations();
//End Execute Components

//Go to destination page @1-7BB04920
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($CoAdAc_mant);
    unset($CoAdAc_cate);
    $CoAdAu_varimant->Class_Terminate();
    unset($CoAdAu_varimant);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-C38FDE2C
$Cabecera->Show("Cabecera");
$CoAdAc_mant->Show();
$CoAdAc_cate->Show();
$CoAdAu_varimant->Show("CoAdAu_varimant");
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", "<center><font face=\"Arial\"><small>G&#101;n&#101;&#114;&#97;&#116;e&#100; <!-- SCC -->w&#105;t&#104; <!-- CCS -->Cod&#101;&#67;h&#97;r&#103;e <!-- CCS -->&#83;tu&#100;i&#111;.</small></font></center>" . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", "<center><font face=\"Arial\"><small>G&#101;n&#101;&#114;&#97;&#116;e&#100; <!-- SCC -->w&#105;t&#104; <!-- CCS -->Cod&#101;&#67;h&#97;r&#103;e <!-- CCS -->&#83;tu&#100;i&#111;.</small></font></center>" . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= "<center><font face=\"Arial\"><small>G&#101;n&#101;&#114;&#97;&#116;e&#100; <!-- SCC -->w&#105;t&#104; <!-- CCS -->Cod&#101;&#67;h&#97;r&#103;e <!-- CCS -->&#83;tu&#100;i&#111;.</small></font></center>";
}
echo $main_block;
//End Show Page

//Unload Page @1-37C35C49
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($CoAdAc_mant);
unset($CoAdAc_cate);
$CoAdAu_varimant->Class_Terminate();
unset($CoAdAu_varimant);
unset($Tpl);
//End Unload Page


?>
