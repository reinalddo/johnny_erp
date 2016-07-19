<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @23-6BA6AD4B
include_once(RelativePath . "/Co_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordconconcil { //conconcil Class @2-F0049FDC

//Variables @2-B2F7A83E

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

//Class_Initialize Event @2-23636A66
    function clsRecordconconcil()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record conconcil/Error";
        $this->ds = new clsconconcilDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "conconcil";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->con_CodCuenta = new clsControl(ccsListBox, "con_CodCuenta", "Con Cod Cuenta", ccsText, "", CCGetRequestParam("con_CodCuenta", $Method));
            $this->con_CodCuenta->DSType = dsSQL;
            list($this->con_CodCuenta->BoundColumn, $this->con_CodCuenta->TextColumn, $this->con_CodCuenta->DBFormat) = array("cue_codcuenta", "txt_descripcion", "");
            $this->con_CodCuenta->ds = new clsDBdatos();
            $this->con_CodCuenta->ds->SQL = "select cue_codcuenta, concat(cue_CodCuenta, '  -  ', cue_Descripcion) as txt_descripcion " .
            "from concuentas " .
            "where cue_codcuenta  >  '' " .
            "";
            $this->con_CodCuenta->ds->Order = "txt_descripcion";
            $this->con_CodCuenta->Required = true;
            $this->con_IdRegistro = new clsControl(ccsTextBox, "con_IdRegistro", "Con Id Registro", ccsInteger, "", CCGetRequestParam("con_IdRegistro", $Method));
            $this->con_CodAuxiliar = new clsControl(ccsTextBox, "con_CodAuxiliar", "Con Cod Auxiliar", ccsInteger, "", CCGetRequestParam("con_CodAuxiliar", $Method));
            $this->con_CodAuxiliar->Required = true;
            $this->tmp_FecInicio = new clsControl(ccsTextBox, "tmp_FecInicio", "Con Fec Corte", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("tmp_FecInicio", $Method));
            $this->tmp_FecInicio->Required = true;
            $this->con_DebIncluidos = new clsControl(ccsTextBox, "con_DebIncluidos", "Con Deb Incluidos", ccsFloat, Array(True, 1, ",", "", False, Array("0"), Array("#"), 1, True, ""), CCGetRequestParam("con_DebIncluidos", $Method));
            $this->con_DebIncluidos->Required = true;
            $this->con_DebExcluidos = new clsControl(ccsTextBox, "con_DebExcluidos", "Con Deb Excluidos", ccsFloat, Array(True, 1, ",", "", False, Array("0"), Array("#"), 1, True, ""), CCGetRequestParam("con_DebExcluidos", $Method));
            $this->con_FecCorte = new clsControl(ccsTextBox, "con_FecCorte", "Con Fec Corte", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("con_FecCorte", $Method));
            $this->con_FecCorte->Required = true;
            $this->DatePicker_con_FecCorte = new clsDatePicker("DatePicker_con_FecCorte", "conconcil", "con_FecCorte");
            $this->con_CreIncluidos = new clsControl(ccsTextBox, "con_CreIncluidos", "Con Cre Incluidos", ccsFloat, Array(True, 1, ",", "", False, Array("0"), Array("#"), 1, True, ""), CCGetRequestParam("con_CreIncluidos", $Method));
            $this->con_CredExcluidos = new clsControl(ccsTextBox, "con_CredExcluidos", "Con Cred Excluidos", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("con_CredExcluidos", $Method));
            $this->con_SalLibros = new clsControl(ccsTextBox, "con_SalLibros", "Con Sal Libros", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("con_SalLibros", $Method));
            $this->con_Estado = new clsControl(ccsTextBox, "con_Estado", "Con Estado", ccsInteger, "", CCGetRequestParam("con_Estado", $Method));
            $this->con_Ususario = new clsControl(ccsTextBox, "con_Ususario", "Con Ususario", ccsText, "", CCGetRequestParam("con_Ususario", $Method));
            $this->con_FecRegistro = new clsControl(ccsTextBox, "con_FecRegistro", "Con Fec Registro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("con_FecRegistro", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->con_DebIncluidos->Value) && !strlen($this->con_DebIncluidos->Value) && $this->con_DebIncluidos->Value !== false)
                $this->con_DebIncluidos->SetText(0);
                if(!is_array($this->con_DebExcluidos->Value) && !strlen($this->con_DebExcluidos->Value) && $this->con_DebExcluidos->Value !== false)
                $this->con_DebExcluidos->SetText(0);
                if(!is_array($this->con_CreIncluidos->Value) && !strlen($this->con_CreIncluidos->Value) && $this->con_CreIncluidos->Value !== false)
                $this->con_CreIncluidos->SetText(0);
                if(!is_array($this->con_CredExcluidos->Value) && !strlen($this->con_CredExcluidos->Value) && $this->con_CredExcluidos->Value !== false)
                $this->con_CredExcluidos->SetText(0);
                if(!is_array($this->con_SalLibros->Value) && !strlen($this->con_SalLibros->Value) && $this->con_SalLibros->Value !== false)
                $this->con_SalLibros->SetText(0);
                if(!is_array($this->con_Estado->Value) && !strlen($this->con_Estado->Value) && $this->con_Estado->Value !== false)
                $this->con_Estado->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-0DCBFFB2
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlcon_IdRegistro"] = CCGetFromGet("con_IdRegistro", "");
    }
//End Initialize Method

//Validate Method @2-2E72E78E
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->con_CodCuenta->Validate() && $Validation);
        $Validation = ($this->con_IdRegistro->Validate() && $Validation);
        $Validation = ($this->con_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->tmp_FecInicio->Validate() && $Validation);
        $Validation = ($this->con_DebIncluidos->Validate() && $Validation);
        $Validation = ($this->con_DebExcluidos->Validate() && $Validation);
        $Validation = ($this->con_FecCorte->Validate() && $Validation);
        $Validation = ($this->con_CreIncluidos->Validate() && $Validation);
        $Validation = ($this->con_CredExcluidos->Validate() && $Validation);
        $Validation = ($this->con_SalLibros->Validate() && $Validation);
        $Validation = ($this->con_Estado->Validate() && $Validation);
        $Validation = ($this->con_Ususario->Validate() && $Validation);
        $Validation = ($this->con_FecRegistro->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->con_CodCuenta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_IdRegistro->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_CodAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_FecInicio->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_DebIncluidos->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_DebExcluidos->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_FecCorte->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_CreIncluidos->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_CredExcluidos->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_SalLibros->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_Estado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_Ususario->Errors->Count() == 0);
        $Validation =  $Validation && ($this->con_FecRegistro->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-2C18BD3E
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->con_CodCuenta->Errors->Count());
        $errors = ($errors || $this->con_IdRegistro->Errors->Count());
        $errors = ($errors || $this->con_CodAuxiliar->Errors->Count());
        $errors = ($errors || $this->tmp_FecInicio->Errors->Count());
        $errors = ($errors || $this->con_DebIncluidos->Errors->Count());
        $errors = ($errors || $this->con_DebExcluidos->Errors->Count());
        $errors = ($errors || $this->con_FecCorte->Errors->Count());
        $errors = ($errors || $this->DatePicker_con_FecCorte->Errors->Count());
        $errors = ($errors || $this->con_CreIncluidos->Errors->Count());
        $errors = ($errors || $this->con_CredExcluidos->Errors->Count());
        $errors = ($errors || $this->con_SalLibros->Errors->Count());
        $errors = ($errors || $this->con_Estado->Errors->Count());
        $errors = ($errors || $this->con_Ususario->Errors->Count());
        $errors = ($errors || $this->con_FecRegistro->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-4B87F887
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
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "CoTrCl_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick")) {
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

//InsertRow Method @2-E78AFE67
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->con_CodCuenta->SetValue($this->con_CodCuenta->GetValue());
        $this->ds->con_IdRegistro->SetValue($this->con_IdRegistro->GetValue());
        $this->ds->con_CodAuxiliar->SetValue($this->con_CodAuxiliar->GetValue());
        $this->ds->tmp_FecInicio->SetValue($this->tmp_FecInicio->GetValue());
        $this->ds->con_DebIncluidos->SetValue($this->con_DebIncluidos->GetValue());
        $this->ds->con_DebExcluidos->SetValue($this->con_DebExcluidos->GetValue());
        $this->ds->con_FecCorte->SetValue($this->con_FecCorte->GetValue());
        $this->ds->con_CreIncluidos->SetValue($this->con_CreIncluidos->GetValue());
        $this->ds->con_CredExcluidos->SetValue($this->con_CredExcluidos->GetValue());
        $this->ds->con_SalLibros->SetValue($this->con_SalLibros->GetValue());
        $this->ds->con_Estado->SetValue($this->con_Estado->GetValue());
        $this->ds->con_Ususario->SetValue($this->con_Ususario->GetValue());
        $this->ds->con_FecRegistro->SetValue($this->con_FecRegistro->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-BB54B8EB
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->con_CodCuenta->SetValue($this->con_CodCuenta->GetValue());
        $this->ds->con_IdRegistro->SetValue($this->con_IdRegistro->GetValue());
        $this->ds->con_CodAuxiliar->SetValue($this->con_CodAuxiliar->GetValue());
        $this->ds->tmp_FecInicio->SetValue($this->tmp_FecInicio->GetValue());
        $this->ds->con_DebIncluidos->SetValue($this->con_DebIncluidos->GetValue());
        $this->ds->con_DebExcluidos->SetValue($this->con_DebExcluidos->GetValue());
        $this->ds->con_FecCorte->SetValue($this->con_FecCorte->GetValue());
        $this->ds->con_CreIncluidos->SetValue($this->con_CreIncluidos->GetValue());
        $this->ds->con_CredExcluidos->SetValue($this->con_CredExcluidos->GetValue());
        $this->ds->con_SalLibros->SetValue($this->con_SalLibros->GetValue());
        $this->ds->con_Estado->SetValue($this->con_Estado->GetValue());
        $this->ds->con_Ususario->SetValue($this->con_Ususario->GetValue());
        $this->ds->con_FecRegistro->SetValue($this->con_FecRegistro->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @2-51DCFB34
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->con_CodCuenta->Prepare();

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
                    echo "Error in Record conconcil";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->con_CodCuenta->SetValue($this->ds->con_CodCuenta->GetValue());
                        $this->con_IdRegistro->SetValue($this->ds->con_IdRegistro->GetValue());
                        $this->con_CodAuxiliar->SetValue($this->ds->con_CodAuxiliar->GetValue());
                        $this->con_DebIncluidos->SetValue($this->ds->con_DebIncluidos->GetValue());
                        $this->con_DebExcluidos->SetValue($this->ds->con_DebExcluidos->GetValue());
                        $this->con_FecCorte->SetValue($this->ds->con_FecCorte->GetValue());
                        $this->con_CreIncluidos->SetValue($this->ds->con_CreIncluidos->GetValue());
                        $this->con_CredExcluidos->SetValue($this->ds->con_CredExcluidos->GetValue());
                        $this->con_SalLibros->SetValue($this->ds->con_SalLibros->GetValue());
                        $this->con_Estado->SetValue($this->ds->con_Estado->GetValue());
                        $this->con_Ususario->SetValue($this->ds->con_Ususario->GetValue());
                        $this->con_FecRegistro->SetValue($this->ds->con_FecRegistro->GetValue());
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
            $Error .= $this->con_CodCuenta->Errors->ToString();
            $Error .= $this->con_IdRegistro->Errors->ToString();
            $Error .= $this->con_CodAuxiliar->Errors->ToString();
            $Error .= $this->tmp_FecInicio->Errors->ToString();
            $Error .= $this->con_DebIncluidos->Errors->ToString();
            $Error .= $this->con_DebExcluidos->Errors->ToString();
            $Error .= $this->con_FecCorte->Errors->ToString();
            $Error .= $this->DatePicker_con_FecCorte->Errors->ToString();
            $Error .= $this->con_CreIncluidos->Errors->ToString();
            $Error .= $this->con_CredExcluidos->Errors->ToString();
            $Error .= $this->con_SalLibros->Errors->ToString();
            $Error .= $this->con_Estado->Errors->ToString();
            $Error .= $this->con_Ususario->Errors->ToString();
            $Error .= $this->con_FecRegistro->Errors->ToString();
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

        $this->con_CodCuenta->Show();
        $this->con_IdRegistro->Show();
        $this->con_CodAuxiliar->Show();
        $this->tmp_FecInicio->Show();
        $this->con_DebIncluidos->Show();
        $this->con_DebExcluidos->Show();
        $this->con_FecCorte->Show();
        $this->DatePicker_con_FecCorte->Show();
        $this->con_CreIncluidos->Show();
        $this->con_CredExcluidos->Show();
        $this->con_SalLibros->Show();
        $this->con_Estado->Show();
        $this->con_Ususario->Show();
        $this->con_FecRegistro->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End conconcil Class @2-FCB6E20C

class clsconconcilDataSource extends clsDBdatos {  //conconcilDataSource Class @2-D61CE518

//DataSource Variables @2-91ADDE51
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $con_CodCuenta;
    var $con_IdRegistro;
    var $con_CodAuxiliar;
    var $tmp_FecInicio;
    var $con_DebIncluidos;
    var $con_DebExcluidos;
    var $con_FecCorte;
    var $con_CreIncluidos;
    var $con_CredExcluidos;
    var $con_SalLibros;
    var $con_Estado;
    var $con_Ususario;
    var $con_FecRegistro;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-553B619D
    function clsconconcilDataSource()
    {
        $this->ErrorBlock = "Record conconcil/Error";
        $this->Initialize();
        $this->con_CodCuenta = new clsField("con_CodCuenta", ccsText, "");
        $this->con_IdRegistro = new clsField("con_IdRegistro", ccsInteger, "");
        $this->con_CodAuxiliar = new clsField("con_CodAuxiliar", ccsInteger, "");
        $this->tmp_FecInicio = new clsField("tmp_FecInicio", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->con_DebIncluidos = new clsField("con_DebIncluidos", ccsFloat, "");
        $this->con_DebExcluidos = new clsField("con_DebExcluidos", ccsFloat, "");
        $this->con_FecCorte = new clsField("con_FecCorte", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->con_CreIncluidos = new clsField("con_CreIncluidos", ccsFloat, "");
        $this->con_CredExcluidos = new clsField("con_CredExcluidos", ccsFloat, "");
        $this->con_SalLibros = new clsField("con_SalLibros", ccsFloat, "");
        $this->con_Estado = new clsField("con_Estado", ccsInteger, "");
        $this->con_Ususario = new clsField("con_Ususario", ccsText, "");
        $this->con_FecRegistro = new clsField("con_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-F789C7AD
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcon_IdRegistro", ccsInteger, "", "", $this->Parameters["urlcon_IdRegistro"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "con_IdRegistro", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-0B1753DF
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT 	con_CodCuenta as con_CodCuenta ,
            con_CodAuxiliar AS con_CodAuxiliar,
            con_FecCorte AS con_FecCorte,
            con_IdRegistro AS con_IdRegistro,
            con_SalLibros AS con_SalLibros,
            con_DebIncluidos con_DebIncluidos,
            con_CreIncluidos AS con_CreIncluidos,
            con_DebExcluidos AS con_DebExcluidos,
            con_CredExcluidos AS con_CredExcluidos,
            con_Ususario AS con_Ususario,
            con_FecRegistro AS con_FecRegistro,
            con_Estado  as con_Estado
        FROM conconciliacion";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
echo "<br><br>" . $this->SQL;        
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-38CC0FD0
    function SetValues()
    {
        $this->con_CodCuenta->SetDBValue($this->f("con_CodCuenta"));
        $this->con_IdRegistro->SetDBValue(trim($this->f("con_IdRegistro")));
        $this->con_CodAuxiliar->SetDBValue(trim($this->f("con_CodAuxiliar")));
        $this->con_DebIncluidos->SetDBValue(trim($this->f("con_DebIncluidos")));
        $this->con_DebExcluidos->SetDBValue(trim($this->f("con_DebExcluidos")));
        $this->con_FecCorte->SetDBValue(trim($this->f("con_FecCorte")));
        $this->con_CreIncluidos->SetDBValue(trim($this->f("con_CreIncluidos")));
        $this->con_CredExcluidos->SetDBValue(trim($this->f("con_CredExcluidos")));
        $this->con_SalLibros->SetDBValue(trim($this->f("con_SalLibros")));
        $this->con_Estado->SetDBValue(trim($this->f("con_Estado")));
        $this->con_Ususario->SetDBValue($this->f("con_Ususario"));
        $this->con_FecRegistro->SetDBValue(trim($this->f("con_FecRegistro")));
    }
//End SetValues Method

//Insert Method @2-9CEDAED8
    function Insert()
    {
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO conconciliacion ("
             . "con_CodCuenta, "
             . "con_IdRegistro, "
             . "con_CodAuxiliar, "
             . "con_DebIncluidos, "
             . "con_DebExcluidos, "
             . "con_FecCorte, "
             . "con_CreIncluidos, "
             . "con_CredExcluidos, "
             . "con_SalLibros, "
             . "con_Estado, "
             . "con_Ususario, "
             . "con_FecRegistro"
             . ") VALUES ("
             . $this->ToSQL($this->con_CodCuenta->GetDBValue(), $this->con_CodCuenta->DataType) . ", "
             . $this->ToSQL($this->con_IdRegistro->GetDBValue(), $this->con_IdRegistro->DataType) . ", "
             . $this->ToSQL($this->con_CodAuxiliar->GetDBValue(), $this->con_CodAuxiliar->DataType) . ", "
             . $this->ToSQL($this->con_DebIncluidos->GetDBValue(), $this->con_DebIncluidos->DataType) . ", "
             . $this->ToSQL($this->con_DebExcluidos->GetDBValue(), $this->con_DebExcluidos->DataType) . ", "
             . $this->ToSQL($this->con_FecCorte->GetDBValue(), $this->con_FecCorte->DataType) . ", "
             . $this->ToSQL($this->con_CreIncluidos->GetDBValue(), $this->con_CreIncluidos->DataType) . ", "
             . $this->ToSQL($this->con_CredExcluidos->GetDBValue(), $this->con_CredExcluidos->DataType) . ", "
             . $this->ToSQL($this->con_SalLibros->GetDBValue(), $this->con_SalLibros->DataType) . ", "
             . $this->ToSQL($this->con_Estado->GetDBValue(), $this->con_Estado->DataType) . ", "
             . $this->ToSQL($this->con_Ususario->GetDBValue(), $this->con_Ususario->DataType) . ", "
             . $this->ToSQL($this->con_FecRegistro->GetDBValue(), $this->con_FecRegistro->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @2-D679D7B8
    function Update()
    {
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE conconciliacion SET "
             . "con_CodCuenta=" . $this->ToSQL($this->con_CodCuenta->GetDBValue(), $this->con_CodCuenta->DataType) . ", "
             . "con_IdRegistro=" . $this->ToSQL($this->con_IdRegistro->GetDBValue(), $this->con_IdRegistro->DataType) . ", "
             . "con_CodAuxiliar=" . $this->ToSQL($this->con_CodAuxiliar->GetDBValue(), $this->con_CodAuxiliar->DataType) . ", "
             . "con_DebIncluidos=" . $this->ToSQL($this->con_DebIncluidos->GetDBValue(), $this->con_DebIncluidos->DataType) . ", "
             . "con_DebExcluidos=" . $this->ToSQL($this->con_DebExcluidos->GetDBValue(), $this->con_DebExcluidos->DataType) . ", "
             . "con_FecCorte=" . $this->ToSQL($this->con_FecCorte->GetDBValue(), $this->con_FecCorte->DataType) . ", "
             . "con_CreIncluidos=" . $this->ToSQL($this->con_CreIncluidos->GetDBValue(), $this->con_CreIncluidos->DataType) . ", "
             . "con_CredExcluidos=" . $this->ToSQL($this->con_CredExcluidos->GetDBValue(), $this->con_CredExcluidos->DataType) . ", "
             . "con_SalLibros=" . $this->ToSQL($this->con_SalLibros->GetDBValue(), $this->con_SalLibros->DataType) . ", "
             . "con_Estado=" . $this->ToSQL($this->con_Estado->GetDBValue(), $this->con_Estado->DataType) . ", "
             . "con_Ususario=" . $this->ToSQL($this->con_Ususario->GetDBValue(), $this->con_Ususario->DataType) . ", "
             . "con_FecRegistro=" . $this->ToSQL($this->con_FecRegistro->GetDBValue(), $this->con_FecRegistro->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End conconcilDataSource Class @2-FCB6E20C

class clsEditableGridmovimlist { //movimlist Class @24-8903E2F8

//Variables @24-9B351068

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
    var $Sorter_com_TipoComp;
    var $BENEFICIARIO;
    var $CONCEPTO;
    var $Sorter_com_FecTrans;
    var $Sorter_com_FecContab;
    var $Sorter_com_FecVencim;
    var $X;
    var $NUM_CHEQ;
    var $DEBITO;
    var $CREDITO;
    var $Navigator;
//End Variables

//Class_Initialize Event @24-0E5B5D33
    function clsEditableGridmovimlist()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid movimlist/Error";
        $this->ComponentName = "movimlist";
        $this->CachedColumns["com_TipoComp"][0] = "com_TipoComp";
        $this->CachedColumns["com_NumComp"][0] = "com_NumComp";
        $this->ds = new clsmovimlistDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 0;
        $this->UpdateAllowed = true;
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

        $this->SorterName = CCGetParam("movimlistOrder", "");
        $this->SorterDirection = CCGetParam("movimlistDir", "");
        $this->Sorter_com_TipoComp = new clsSorter($this->ComponentName, "Sorter_com_TipoComp", $FileName);
        $this->BENEFICIARIO = new clsSorter($this->ComponentName, "BENEFICIARIO", $FileName);
        $this->CONCEPTO = new clsSorter($this->ComponentName, "CONCEPTO", $FileName);
        $this->Sorter_com_FecTrans = new clsSorter($this->ComponentName, "Sorter_com_FecTrans", $FileName);
        $this->Sorter_com_FecContab = new clsSorter($this->ComponentName, "Sorter_com_FecContab", $FileName);
        $this->Sorter_com_FecVencim = new clsSorter($this->ComponentName, "Sorter_com_FecVencim", $FileName);
        $this->X = new clsSorter($this->ComponentName, "X", $FileName);
        $this->NUM_CHEQ = new clsSorter($this->ComponentName, "NUM_CHEQ", $FileName);
        $this->DEBITO = new clsSorter($this->ComponentName, "DEBITO", $FileName);
        $this->CREDITO = new clsSorter($this->ComponentName, "CREDITO", $FileName);
        $this->com_TipoComp = new clsControl(ccsTextBox, "com_TipoComp", "Com Tipo Comp", ccsText, "");
        $this->com_RegNumero = new clsControl(ccsTextBox, "com_RegNumero", "Com Reg Numero", ccsInteger, "");
        $this->det_Secuencia = new clsControl(ccsHidden, "det_Secuencia", "det_Secuencia", ccsInteger, "");
        $this->tmp_Benef = new clsControl(ccsTextBox, "tmp_Benef", "tmp_Benef", ccsText, "");
        $this->tmp_Concepto = new clsControl(ccsTextBox, "tmp_Concepto", "tmp_Concepto", ccsText, "");
        $this->com_FecTrans = new clsControl(ccsTextBox, "com_FecTrans", "Com Fec Trans", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->com_FecContab = new clsControl(ccsTextBox, "com_FecContab", "Fecha Cntable", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->det_FecLibros = new clsControl(ccsTextBox, "det_FecLibros", "Com Fec Vencim", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->det_EstLibros = new clsControl(ccsTextBox, "det_EstLibros", "det_EstLibros", ccsInteger, "");
        $this->Marcador = new clsControl(ccsCheckBox, "Marcador", "Marcador", ccsInteger, "");
        $this->Marcador->CheckedValue = $this->Marcador->GetParsedValue(1);
        $this->Marcador->UncheckedValue = $this->Marcador->GetParsedValue(0);
        $this->det_NumCheque = new clsControl(ccsTextBox, "det_NumCheque", "det_NumCheque", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0"), "", 1, True, ""));
        $this->det_ValDebito = new clsControl(ccsTextBox, "det_ValDebito", "det_ValDebito", ccsFloat, Array(True, 2, ",", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""));
        $this->det_ValCredito = new clsControl(ccsTextBox, "det_ValCredito", "det_ValCredito", ccsFloat, Array(True, 2, ",", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""));
        $this->Button1 = new clsButton("Button1");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
    }
//End Class_Initialize Event

//Initialize Method @24-4EC83ABE
    function Initialize()
    {
        if(!$this->Visible) return;
        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["expr55"] = $_POST['con_CodCuenta'];
        $this->ds->Parameters["expr56"] = $_POST['con_IDauxiliar'];
        $this->ds->Parameters["expr57"] = $_POST['tmp_Desde'];
        $this->ds->Parameters["expr58"] = $_POST['con_FecCorte'];
    }
//End Initialize Method

//GetFormParameters Method @24-2ADAEFA3
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["com_TipoComp"][$RowNumber] = CCGetFromPost("com_TipoComp_" . $RowNumber);
            $this->FormParameters["com_RegNumero"][$RowNumber] = CCGetFromPost("com_RegNumero_" . $RowNumber);
            $this->FormParameters["det_Secuencia"][$RowNumber] = CCGetFromPost("det_Secuencia_" . $RowNumber);
            $this->FormParameters["tmp_Benef"][$RowNumber] = CCGetFromPost("tmp_Benef_" . $RowNumber);
            $this->FormParameters["tmp_Concepto"][$RowNumber] = CCGetFromPost("tmp_Concepto_" . $RowNumber);
            $this->FormParameters["com_FecTrans"][$RowNumber] = CCGetFromPost("com_FecTrans_" . $RowNumber);
            $this->FormParameters["com_FecContab"][$RowNumber] = CCGetFromPost("com_FecContab_" . $RowNumber);
            $this->FormParameters["det_FecLibros"][$RowNumber] = CCGetFromPost("det_FecLibros_" . $RowNumber);
            $this->FormParameters["det_EstLibros"][$RowNumber] = CCGetFromPost("det_EstLibros_" . $RowNumber);
            $this->FormParameters["Marcador"][$RowNumber] = CCGetFromPost("Marcador_" . $RowNumber);
            $this->FormParameters["det_NumCheque"][$RowNumber] = CCGetFromPost("det_NumCheque_" . $RowNumber);
            $this->FormParameters["det_ValDebito"][$RowNumber] = CCGetFromPost("det_ValDebito_" . $RowNumber);
            $this->FormParameters["det_ValCredito"][$RowNumber] = CCGetFromPost("det_ValCredito_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @24-C69A3710
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["com_TipoComp"] = $this->CachedColumns["com_TipoComp"][$RowNumber];
            $this->ds->CachedColumns["com_NumComp"] = $this->CachedColumns["com_NumComp"][$RowNumber];
            $this->com_TipoComp->SetText($this->FormParameters["com_TipoComp"][$RowNumber], $RowNumber);
            $this->com_RegNumero->SetText($this->FormParameters["com_RegNumero"][$RowNumber], $RowNumber);
            $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
            $this->tmp_Benef->SetText($this->FormParameters["tmp_Benef"][$RowNumber], $RowNumber);
            $this->tmp_Concepto->SetText($this->FormParameters["tmp_Concepto"][$RowNumber], $RowNumber);
            $this->com_FecTrans->SetText($this->FormParameters["com_FecTrans"][$RowNumber], $RowNumber);
            $this->com_FecContab->SetText($this->FormParameters["com_FecContab"][$RowNumber], $RowNumber);
            $this->det_FecLibros->SetText($this->FormParameters["det_FecLibros"][$RowNumber], $RowNumber);
            $this->det_EstLibros->SetText($this->FormParameters["det_EstLibros"][$RowNumber], $RowNumber);
            $this->Marcador->SetText($this->FormParameters["Marcador"][$RowNumber], $RowNumber);
            $this->det_NumCheque->SetText($this->FormParameters["det_NumCheque"][$RowNumber], $RowNumber);
            $this->det_ValDebito->SetText($this->FormParameters["det_ValDebito"][$RowNumber], $RowNumber);
            $this->det_ValCredito->SetText($this->FormParameters["det_ValCredito"][$RowNumber], $RowNumber);
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

//ValidateRow Method @24-8942BA6F
    function ValidateRow($RowNumber)
    {
        $this->com_TipoComp->Validate();
        $this->com_RegNumero->Validate();
        $this->det_Secuencia->Validate();
        $this->tmp_Benef->Validate();
        $this->tmp_Concepto->Validate();
        $this->com_FecTrans->Validate();
        $this->com_FecContab->Validate();
        $this->det_FecLibros->Validate();
        $this->det_EstLibros->Validate();
        $this->Marcador->Validate();
        $this->det_NumCheque->Validate();
        $this->det_ValDebito->Validate();
        $this->det_ValCredito->Validate();
        $this->CheckBox_Delete->Validate();
        $this->RowErrors = new clsErrors();
        $errors = $this->com_TipoComp->Errors->ToString();
        $errors .= $this->com_RegNumero->Errors->ToString();
        $errors .= $this->det_Secuencia->Errors->ToString();
        $errors .= $this->tmp_Benef->Errors->ToString();
        $errors .= $this->tmp_Concepto->Errors->ToString();
        $errors .= $this->com_FecTrans->Errors->ToString();
        $errors .= $this->com_FecContab->Errors->ToString();
        $errors .= $this->det_FecLibros->Errors->ToString();
        $errors .= $this->det_EstLibros->Errors->ToString();
        $errors .= $this->Marcador->Errors->ToString();
        $errors .= $this->det_NumCheque->Errors->ToString();
        $errors .= $this->det_ValDebito->Errors->ToString();
        $errors .= $this->det_ValCredito->Errors->ToString();
        $errors .= $this->CheckBox_Delete->Errors->ToString();
        $this->com_TipoComp->Errors->Clear();
        $this->com_RegNumero->Errors->Clear();
        $this->det_Secuencia->Errors->Clear();
        $this->tmp_Benef->Errors->Clear();
        $this->tmp_Concepto->Errors->Clear();
        $this->com_FecTrans->Errors->Clear();
        $this->com_FecContab->Errors->Clear();
        $this->det_FecLibros->Errors->Clear();
        $this->det_EstLibros->Errors->Clear();
        $this->Marcador->Errors->Clear();
        $this->det_NumCheque->Errors->Clear();
        $this->det_ValDebito->Errors->Clear();
        $this->det_ValCredito->Errors->Clear();
        $this->CheckBox_Delete->Errors->Clear();
        $errors .=$this->RowErrors->ToString();
        $this->RowsErrors[$RowNumber] = $errors;
        return $errors ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @24-8B2CE990
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["com_TipoComp"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["com_RegNumero"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tmp_Benef"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tmp_Concepto"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["com_FecTrans"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["com_FecContab"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_FecLibros"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_EstLibros"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["Marcador"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_NumCheque"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_ValDebito"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_ValCredito"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @24-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @24-A82CD764
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
        if(strlen(CCGetParam("Button1", ""))) {
            $this->PressedButton = "Button1";
        } else if(strlen(CCGetParam("Button_Submit", ""))) {
            $this->PressedButton = "Button_Submit";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button1") {
            if(!CCGetEvent($this->Button1->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @24-A7395BE5
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["com_TipoComp"] = $this->CachedColumns["com_TipoComp"][$RowNumber];
            $this->ds->CachedColumns["com_NumComp"] = $this->CachedColumns["com_NumComp"][$RowNumber];
            $this->com_TipoComp->SetText($this->FormParameters["com_TipoComp"][$RowNumber], $RowNumber);
            $this->com_RegNumero->SetText($this->FormParameters["com_RegNumero"][$RowNumber], $RowNumber);
            $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
            $this->tmp_Benef->SetText($this->FormParameters["tmp_Benef"][$RowNumber], $RowNumber);
            $this->tmp_Concepto->SetText($this->FormParameters["tmp_Concepto"][$RowNumber], $RowNumber);
            $this->com_FecTrans->SetText($this->FormParameters["com_FecTrans"][$RowNumber], $RowNumber);
            $this->com_FecContab->SetText($this->FormParameters["com_FecContab"][$RowNumber], $RowNumber);
            $this->det_FecLibros->SetText($this->FormParameters["det_FecLibros"][$RowNumber], $RowNumber);
            $this->det_EstLibros->SetText($this->FormParameters["det_EstLibros"][$RowNumber], $RowNumber);
            $this->Marcador->SetText($this->FormParameters["Marcador"][$RowNumber], $RowNumber);
            $this->det_NumCheque->SetText($this->FormParameters["det_NumCheque"][$RowNumber], $RowNumber);
            $this->det_ValDebito->SetText($this->FormParameters["det_ValDebito"][$RowNumber], $RowNumber);
            $this->det_ValCredito->SetText($this->FormParameters["det_ValCredito"][$RowNumber], $RowNumber);
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

//UpdateRow Method @24-56ED4980
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->det_EstLibros->SetValue($this->det_EstLibros->GetValue());
        $this->ds->det_FecLibros->SetValue($this->det_FecLibros->GetValue());
        $this->ds->det_NumCheque->SetValue($this->det_NumCheque->GetValue());
        $this->ds->com_RegNumero->SetValue($this->com_RegNumero->GetValue());
        $this->ds->det_Secuencia->SetValue($this->det_Secuencia->GetValue());
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

//FormScript Method @24-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @24-C494730F
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
                $this->CachedColumns["com_TipoComp"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["com_NumComp"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["com_TipoComp"][$RowNumber] = "";
                $this->CachedColumns["com_NumComp"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @24-2B13BB0F
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["com_TipoComp"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["com_NumComp"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @24-5286262B
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


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
                    $this->CachedColumns["com_TipoComp"][$RowNumber] = $this->ds->CachedColumns["com_TipoComp"];
                    $this->CachedColumns["com_NumComp"][$RowNumber] = $this->ds->CachedColumns["com_NumComp"];
                    $this->com_TipoComp->SetValue($this->ds->com_TipoComp->GetValue());
                    $this->com_RegNumero->SetValue($this->ds->com_RegNumero->GetValue());
                    $this->det_Secuencia->SetValue($this->ds->det_Secuencia->GetValue());
                    $this->tmp_Benef->SetValue($this->ds->tmp_Benef->GetValue());
                    $this->tmp_Concepto->SetValue($this->ds->tmp_Concepto->GetValue());
                    $this->com_FecTrans->SetValue($this->ds->com_FecTrans->GetValue());
                    $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                    $this->det_FecLibros->SetValue($this->ds->det_FecLibros->GetValue());
                    $this->det_EstLibros->SetValue($this->ds->det_EstLibros->GetValue());
                    $this->Marcador->SetValue($this->ds->Marcador->GetValue());
                    $this->det_NumCheque->SetValue($this->ds->det_NumCheque->GetValue());
                    $this->det_ValDebito->SetValue($this->ds->det_ValDebito->GetValue());
                    $this->det_ValCredito->SetValue($this->ds->det_ValCredito->GetValue());
                    $this->ValidateRow($RowNumber);
                } else if (!$this->FormSubmitted){
                    $this->CachedColumns["com_TipoComp"][$RowNumber] = "";
                    $this->CachedColumns["com_NumComp"][$RowNumber] = "";
                    $this->com_TipoComp->SetText("");
                    $this->com_RegNumero->SetText("");
                    $this->det_Secuencia->SetText("");
                    $this->tmp_Benef->SetText("");
                    $this->tmp_Concepto->SetText("");
                    $this->com_FecTrans->SetText("");
                    $this->com_FecContab->SetText("");
                    $this->det_FecLibros->SetText("");
                    $this->det_EstLibros->SetText(0);
                    $this->Marcador->SetText("");
                    $this->det_NumCheque->SetText("");
                    $this->det_ValDebito->SetText("");
                    $this->det_ValCredito->SetText("");
                    $this->CheckBox_Delete->SetText("");
                } else {
                    $this->com_TipoComp->SetText($this->FormParameters["com_TipoComp"][$RowNumber], $RowNumber);
                    $this->com_RegNumero->SetText($this->FormParameters["com_RegNumero"][$RowNumber], $RowNumber);
                    $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
                    $this->tmp_Benef->SetText($this->FormParameters["tmp_Benef"][$RowNumber], $RowNumber);
                    $this->tmp_Concepto->SetText($this->FormParameters["tmp_Concepto"][$RowNumber], $RowNumber);
                    $this->com_FecTrans->SetText($this->FormParameters["com_FecTrans"][$RowNumber], $RowNumber);
                    $this->com_FecContab->SetText($this->FormParameters["com_FecContab"][$RowNumber], $RowNumber);
                    $this->det_FecLibros->SetText($this->FormParameters["det_FecLibros"][$RowNumber], $RowNumber);
                    $this->det_EstLibros->SetText($this->FormParameters["det_EstLibros"][$RowNumber], $RowNumber);
                    $this->Marcador->SetText($this->FormParameters["Marcador"][$RowNumber], $RowNumber);
                    $this->det_NumCheque->SetText($this->FormParameters["det_NumCheque"][$RowNumber], $RowNumber);
                    $this->det_ValDebito->SetText($this->FormParameters["det_ValDebito"][$RowNumber], $RowNumber);
                    $this->det_ValCredito->SetText($this->FormParameters["det_ValCredito"][$RowNumber], $RowNumber);
                    $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->com_TipoComp->Show($RowNumber);
                $this->com_RegNumero->Show($RowNumber);
                $this->det_Secuencia->Show($RowNumber);
                $this->tmp_Benef->Show($RowNumber);
                $this->tmp_Concepto->Show($RowNumber);
                $this->com_FecTrans->Show($RowNumber);
                $this->com_FecContab->Show($RowNumber);
                $this->det_FecLibros->Show($RowNumber);
                $this->det_EstLibros->Show($RowNumber);
                $this->Marcador->Show($RowNumber);
                $this->det_NumCheque->Show($RowNumber);
                $this->det_ValDebito->Show($RowNumber);
                $this->det_ValCredito->Show($RowNumber);
                $this->Button1->Show($RowNumber);
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
        $this->Sorter_com_TipoComp->Show();
        $this->BENEFICIARIO->Show();
        $this->CONCEPTO->Show();
        $this->Sorter_com_FecTrans->Show();
        $this->Sorter_com_FecContab->Show();
        $this->Sorter_com_FecVencim->Show();
        $this->X->Show();
        $this->NUM_CHEQ->Show();
        $this->DEBITO->Show();
        $this->CREDITO->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();

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

} //End movimlist Class @24-FCB6E20C

class clsmovimlistDataSource extends clsDBdatos {  //movimlistDataSource Class @24-AA3FE918

//DataSource Variables @24-64FAEB18
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $UpdateParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $com_TipoComp;
    var $com_RegNumero;
    var $det_Secuencia;
    var $tmp_Benef;
    var $tmp_Concepto;
    var $com_FecTrans;
    var $com_FecContab;
    var $det_FecLibros;
    var $det_EstLibros;
    var $Marcador;
    var $det_NumCheque;
    var $det_ValDebito;
    var $det_ValCredito;
    var $CheckBox_Delete;
//End DataSource Variables

//DataSourceClass_Initialize Event @24-8EE119FB
    function clsmovimlistDataSource()
    {
        $this->ErrorBlock = "EditableGrid movimlist/Error";
        $this->Initialize();
        $this->com_TipoComp = new clsField("com_TipoComp", ccsText, "");
        $this->com_RegNumero = new clsField("com_RegNumero", ccsInteger, "");
        $this->det_Secuencia = new clsField("det_Secuencia", ccsInteger, "");
        $this->tmp_Benef = new clsField("tmp_Benef", ccsText, "");
        $this->tmp_Concepto = new clsField("tmp_Concepto", ccsText, "");
        $this->com_FecTrans = new clsField("com_FecTrans", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->det_FecLibros = new clsField("det_FecLibros", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->det_EstLibros = new clsField("det_EstLibros", ccsInteger, "");
        $this->Marcador = new clsField("Marcador", ccsInteger, "");
        $this->det_NumCheque = new clsField("det_NumCheque", ccsInteger, "");
        $this->det_ValDebito = new clsField("det_ValDebito", ccsFloat, "");
        $this->det_ValCredito = new clsField("det_ValCredito", ccsFloat, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @24-771BE618
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_com_TipoComp" => array("tmp_NumComp", ""), 
            "BENEFICIARIO" => array("tmp_Benef", ""), 
            "CONCEPTO" => array("tmp_Concepto", ""), 
            "Sorter_com_FecTrans" => array("com_FecTrans", ""), 
            "Sorter_com_FecContab" => array("com_FecContab", ""), 
            "Sorter_com_FecVencim" => array("com_FecVencim", ""), 
            "X" => array("det_Estlibros", ""), 
            "NUM_CHEQ" => array("det_NumCheque", ""), 
            "DEBITO" => array("det_ValDebito", ""), 
            "CREDITO" => array("det_ValCredito", "")));
    }
//End SetOrder Method

//Prepare Method @24-7441430C
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr55", ccsText, "", "", $this->Parameters["expr55"], 0, false);
        $this->wp->AddParameter("2", "expr56", ccsText, "", "", $this->Parameters["expr56"], 0, false);
        $this->wp->AddParameter("3", "expr57", ccsText, "", "", $this->Parameters["expr57"], "", false);
        $this->wp->AddParameter("4", "expr58", ccsText, "", "", $this->Parameters["expr58"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @24-7741AF90
    function Open()
    {
        global $txt_CheqInicial;
        global $txt_CheqFinal;
        global $txt_CheqValor;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM condetalle JOIN concomprobantes ON com_regnumero = det_regnumero " .
        "	left join conpersonas on per_codauxiliar = com_codreceptor " .
        "where det_codcuenta = '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' and  " .
        "      det_idauxiliar = " . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . " and  " .
        "(      com_feccontab between '" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "' and '" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "' OR " .
        "      com_fectrans between '" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "' and '" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "' " .
        ")";
        $this->SQL = "select concat(com_TipoComp, ' - ' , com_NumComp) as tmp_NumComp, " .
        "        det_Secuencia, " .
        "	ifnull(com_receptor, concat(left(per_Apellidos,12), ' ', left(per_Nombres,10))) as tmp_Benef, " .
        "	left(com_concepto,37) as tmp_Concepto, " .
        "	com_RegNumero, com_FecContab, com_FecTrans, det_FecLibros, det_EstLibros, det_NumCheque, " .
        "	det_ValDebito, det_ValCredito " .
        "from condetalle JOIN concomprobantes ON com_regnumero = det_regnumero " .
        "	left join conpersonas on per_codauxiliar = com_codreceptor " .
        "where det_codcuenta = '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' and  " .
        "      det_idauxiliar = " . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . " and  " .
        "(      com_feccontab <= '" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "' OR " .
        "      com_fectrans between '" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "' and '" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "' " .
        ") AND (det_feclibros >= '". $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "' OR det_feclibros  = '0000-00-00' OR
                det_feclibros <= '2001-01-01') ";
        $slCondicion = '';
        if (ccGetParam('BUSCAR', false) == 'BUSCAR'){
            $txt_CheqInicial = ccGetParam('txt_CheqInicial', false);
            $txt_CheqFinal = ccGetParam('txt_CheqFinal', false);
            $txt_Valor = ccGetParam('txt_Valor', false);
            $txt_Semana = ccGetParam('txt_Sem', false);
            $txt_SoloSemana = ccGetParam('txt_SEM', false);
            if ($txt_CheqInicial) {
                if ($txt_CheqFinal ) $slCondicion = " AND  det_NumCheque BETWEEN " . $txt_CheqInicial . " AND "  . $txt_CheqFinal;
                else $slCondicion = " AND det_NumCheque  = " . $txt_CheqInicial;
            }
            if ($txt_Valor) $slCondicion .= " AND (det_ValDebito = " . $txt_Valor . " OR det_ValCredito = " . $txt_Valor . ")";
            if ($txt_Semana) $slCondicion .= " AND com_RefOperat = " . $txt_Semana;
            if ($txt_SoloSemana) $slCondicion = " AND com_RefOperat = " . $txt_SoloSemana;
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->SQL .= $slCondicion;
//echo $this->SQL;
        $this->CountSQL .= $slCondicion;
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @24-8AA4AFF4
    function SetValues()
    {
        $this->CachedColumns["com_TipoComp"] = $this->f("com_TipoComp");
        $this->CachedColumns["com_NumComp"] = $this->f("com_NumComp");
        $this->com_TipoComp->SetDBValue($this->f("tmp_NumComp"));
        $this->com_RegNumero->SetDBValue(trim($this->f("com_RegNumero")));
        $this->det_Secuencia->SetDBValue(trim($this->f("det_Secuencia")));
        $this->tmp_Benef->SetDBValue($this->f("tmp_Benef"));
        $this->tmp_Concepto->SetDBValue($this->f("tmp_Concepto"));
        $this->com_FecTrans->SetDBValue(trim($this->f("com_FecTrans")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->det_FecLibros->SetDBValue(trim($this->f("det_FecLibros")));
        $this->det_EstLibros->SetDBValue(trim($this->f("det_EstLibros")));
        $this->Marcador->SetDBValue(trim($this->f("det_EstLibros")));
        $this->det_NumCheque->SetDBValue(trim($this->f("det_NumCheque")));
        $this->det_ValDebito->SetDBValue(trim($this->f("det_ValDebito")));
        $this->det_ValCredito->SetDBValue(trim($this->f("det_ValCredito")));
    }
//End SetValues Method

//Update Method @24-61DA4B79
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["det_EstLibros"] = new clsSQLParameter("ctrldet_EstLibros", ccsInteger, "", "", $this->det_EstLibros->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_FecLibros"] = new clsSQLParameter("ctrldet_FecLibros", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->det_FecLibros->GetValue(), '00/00/00', false, $this->ErrorBlock);
        $this->cp["det_NumCheque"] = new clsSQLParameter("ctrldet_NumCheque", ccsInteger, "", "", $this->det_NumCheque->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["com_RegNumero"] = new clsSQLParameter("ctrlcom_RegNumero", ccsInteger, "", "", $this->com_RegNumero->GetValue(), -1, false, $this->ErrorBlock);
        $this->cp["det_Secuencia"] = new clsSQLParameter("ctrldet_Secuencia", ccsInteger, "", "", $this->det_Secuencia->GetValue(), -1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE condetalle " .
        "set det_estLibros = " . $this->SQLValue($this->cp["det_EstLibros"]->GetDBValue(), ccsInteger) . ", " .
        "    det_FecLibros = '" . $this->SQLValue($this->cp["det_FecLibros"]->GetDBValue(), ccsDate) . "', " .
        "    det_numcheque = " . $this->SQLValue($this->cp["det_NumCheque"]->GetDBValue(), ccsInteger) . " " .
        "where det_Regnumero = " . $this->SQLValue($this->cp["com_RegNumero"]->GetDBValue(), ccsInteger) . " AND " .
        "    det_secuencia = " . $this->SQLValue($this->cp["det_Secuencia"]->GetDBValue(), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End movimlistDataSource Class @24-FCB6E20C

//Initialize Page @1-AFAE915D
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

$FileName = "CoTrCl_mant.php";
$Redirect = "";
$TemplateFileName = "CoTrCl_mant.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-2139F4F1
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$conconcil = new clsRecordconconcil();
$movimlist = new clsEditableGridmovimlist();
$conconcil->Initialize();
$movimlist->Initialize();
$txt_CheqInicial;
$txt_CheqFinal;
$txt_Valor;
// Events
include("./CoTrCl_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-51DB8464
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main", $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-D7E43181
$Cabecera->Operations();
$conconcil->Operation();
$movimlist->Operation();
//End Execute Components

//Go to destination page @1-80938D49
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($conconcil);
    unset($movimlist);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-F20BC5B2
$Cabecera->Show("Cabecera");
$conconcil->Show();
$Tpl->setvar('txt_CheqInicial', $txt_CheqInicial);
$Tpl->setvar('txt_CheqFinal', $txt_CheqFinal);
$Tpl->setvar('txt_Valor', $txt_Valor);
$movimlist->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", "<center><font face=\"Arial\"><smal" . "l>Gener&#97;te&#100; <!-- CCS -->" . "wi&#116;&#104; <!-- CCS -->C&" . "#111;deC&#104;&#97;rg&#101; <!-" . "- SCC -->&#83;tud&#105;&#111;." . "</small></font></center>" . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", "<center><font face=\"Arial\"><smal" . "l>Gener&#97;te&#100; <!-- CCS -->" . "wi&#116;&#104; <!-- CCS -->C&" . "#111;deC&#104;&#97;rg&#101; <!-" . "- SCC -->&#83;tud&#105;&#111;." . "</small></font></center>" . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= "<center><font face=\"Arial\"><smal" . "l>Gener&#97;te&#100; <!-- CCS -->" . "wi&#116;&#104; <!-- CCS -->C&" . "#111;deC&#104;&#97;rg&#101; <!-" . "- SCC -->&#83;tud&#105;&#111;." . "</small></font></center>";
}
echo $main_block;
//End Show Page

//Unload Page @1-8B636DD1
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($conconcil);
unset($movimlist);
unset($Tpl);
//End Unload Page


?>
