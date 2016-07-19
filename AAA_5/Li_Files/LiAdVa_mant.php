<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include (RelativePath . "/LibPhp/SegLib.php");
class clsRecordgenvarproceso { //genvarproceso Class @2-F4234F7F

//Variables @2-4A82E0A3

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

//Class_Initialize Event @2-3F29DA34
    function clsRecordgenvarproceso()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record genvarproceso/Error";
        $this->ds = new clsgenvarprocesoDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "genvarproceso";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->var_TipProceso = new clsControl(ccsTextBox, "var_TipProceso", "Var Tip Proceso", ccsText, "", CCGetRequestParam("var_TipProceso", $Method));
            $this->var_TipProceso->Required = true;
            $this->var_Nombre = new clsControl(ccsTextBox, "var_Nombre", "Var Nombre", ccsText, "", CCGetRequestParam("var_Nombre", $Method));
            $this->var_Nombre->Required = true;
            $this->var_Descripcion = new clsControl(ccsTextBox, "var_Descripcion", "var_Descripcion", ccsText, "", CCGetRequestParam("var_Descripcion", $Method));
            $this->var_Significado = new clsControl(ccsTextArea, "var_Significado", "Var Significado", ccsMemo, "", CCGetRequestParam("var_Significado", $Method));
            $this->var_Significado->Required = true;
            $this->var_Clase = new clsControl(ccsRadioButton, "var_Clase", "var_Clase", ccsInteger, "", CCGetRequestParam("var_Clase", $Method));
            $this->var_Clase->DSType = dsTable;
            list($this->var_Clase->BoundColumn, $this->var_Clase->TextColumn, $this->var_Clase->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->var_Clase->ds = new clsDBdatos();
            $this->var_Clase->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->var_Clase->ds->Parameters["expr21"] = 'LGVAR';
            $this->var_Clase->ds->wp = new clsSQLParameters();
            $this->var_Clase->ds->wp->AddParameter("1", "expr21", ccsText, "", "", $this->var_Clase->ds->Parameters["expr21"], "", false);
            $this->var_Clase->ds->wp->Criterion[1] = $this->var_Clase->ds->wp->Operation(opEqual, "par_Clave", $this->var_Clase->ds->wp->GetDBValue("1"), $this->var_Clase->ds->ToSQL($this->var_Clase->ds->wp->GetDBValue("1"), ccsText),false);
            $this->var_Clase->ds->Where = $this->var_Clase->ds->wp->Criterion[1];
            $this->var_Orden = new clsControl(ccsTextBox, "var_Orden", "var_Orden", ccsInteger, "", CCGetRequestParam("var_Orden", $Method));
            $this->var_Orden->Required = true;
            $this->var_Estado = new clsControl(ccsRadioButton, "var_Estado", "Var Estado", ccsInteger, "", CCGetRequestParam("var_Estado", $Method));
            $this->var_Estado->DSType = dsTable;
            list($this->var_Estado->BoundColumn, $this->var_Estado->TextColumn, $this->var_Estado->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->var_Estado->ds = new clsDBdatos();
            $this->var_Estado->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->var_Estado->ds->Parameters["expr16"] = 'LGESTA';
            $this->var_Estado->ds->wp = new clsSQLParameters();
            $this->var_Estado->ds->wp->AddParameter("1", "expr16", ccsText, "", "", $this->var_Estado->ds->Parameters["expr16"], "", false);
            $this->var_Estado->ds->wp->Criterion[1] = $this->var_Estado->ds->wp->Operation(opEqual, "par_Clave", $this->var_Estado->ds->wp->GetDBValue("1"), $this->var_Estado->ds->ToSQL($this->var_Estado->ds->wp->GetDBValue("1"), ccsText),false);
            $this->var_Estado->ds->Where = $this->var_Estado->ds->wp->Criterion[1];
            $this->var_Estado->HTML = true;
            $this->var_Estado->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->var_Clase->Value) && !strlen($this->var_Clase->Value) && $this->var_Clase->Value !== false)
                $this->var_Clase->SetText(10);
                if(!is_array($this->var_Orden->Value) && !strlen($this->var_Orden->Value) && $this->var_Orden->Value !== false)
                $this->var_Orden->SetText(0);
                if(!is_array($this->var_Estado->Value) && !strlen($this->var_Estado->Value) && $this->var_Estado->Value !== false)
                $this->var_Estado->SetText(1);
            }
            if(!is_array($this->lbTitulo->Value) && !strlen($this->lbTitulo->Value) && $this->lbTitulo->Value !== false)
            $this->lbTitulo->SetText('AÑADIR VARIABLE DE PROCESO');
        }
    }
//End Class_Initialize Event

//Initialize Method @2-DE7E074B
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlvar_ID"] = CCGetFromGet("var_ID", "");
    }
//End Initialize Method

//Validate Method @2-D6BF4E8F
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->var_Nombre->SetValue($this->var_Nombre->GetValue());
        if(CCDLookUp("COUNT(*)", "genvarproceso", "var_Nombre=" . $this->ds->ToSQL($this->ds->var_Nombre->GetDBValue(), $this->ds->var_Nombre->DataType) . $Where, $DBdatos) > 0)
            $this->var_Nombre->Errors->addError("El campo Var Nombre ya existe.");
        $Validation = ($this->var_TipProceso->Validate() && $Validation);
        $Validation = ($this->var_Nombre->Validate() && $Validation);
        $Validation = ($this->var_Descripcion->Validate() && $Validation);
        $Validation = ($this->var_Significado->Validate() && $Validation);
        $Validation = ($this->var_Clase->Validate() && $Validation);
        $Validation = ($this->var_Orden->Validate() && $Validation);
        $Validation = ($this->var_Estado->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-1898372C
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->var_TipProceso->Errors->Count());
        $errors = ($errors || $this->var_Nombre->Errors->Count());
        $errors = ($errors || $this->var_Descripcion->Errors->Count());
        $errors = ($errors || $this->var_Significado->Errors->Count());
        $errors = ($errors || $this->var_Clase->Errors->Count());
        $errors = ($errors || $this->var_Orden->Errors->Count());
        $errors = ($errors || $this->var_Estado->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-E06D91A9
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
        $Redirect = "LiAdVa_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @2-65FC8287
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->var_TipProceso->SetValue($this->var_TipProceso->GetValue());
        $this->ds->var_Nombre->SetValue($this->var_Nombre->GetValue());
        $this->ds->var_Descripcion->SetValue($this->var_Descripcion->GetValue());
        $this->ds->var_Significado->SetValue($this->var_Significado->GetValue());
        $this->ds->var_Clase->SetValue($this->var_Clase->GetValue());
        $this->ds->var_Orden->SetValue($this->var_Orden->GetValue());
        $this->ds->var_Estado->SetValue($this->var_Estado->GetValue());
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

//UpdateRow Method @2-9BCE0864
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->var_TipProceso->SetValue($this->var_TipProceso->GetValue());
        $this->ds->var_Nombre->SetValue($this->var_Nombre->GetValue());
        $this->ds->var_Descripcion->SetValue($this->var_Descripcion->GetValue());
        $this->ds->var_Significado->SetValue($this->var_Significado->GetValue());
        $this->ds->var_Clase->SetValue($this->var_Clase->GetValue());
        $this->ds->var_Orden->SetValue($this->var_Orden->GetValue());
        $this->ds->var_Estado->SetValue($this->var_Estado->GetValue());
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

//DeleteRow Method @2-EA88835F
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

//Show Method @2-2A9F7269
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->var_Clase->Prepare();
        $this->var_Estado->Prepare();

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
                    echo "Error in Record genvarproceso";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->var_TipProceso->SetValue($this->ds->var_TipProceso->GetValue());
                        $this->var_Nombre->SetValue($this->ds->var_Nombre->GetValue());
                        $this->var_Descripcion->SetValue($this->ds->var_Descripcion->GetValue());
                        $this->var_Significado->SetValue($this->ds->var_Significado->GetValue());
                        $this->var_Clase->SetValue($this->ds->var_Clase->GetValue());
                        $this->var_Orden->SetValue($this->ds->var_Orden->GetValue());
                        $this->var_Estado->SetValue($this->ds->var_Estado->GetValue());
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
            $Error .= $this->var_TipProceso->Errors->ToString();
            $Error .= $this->var_Nombre->Errors->ToString();
            $Error .= $this->var_Descripcion->Errors->ToString();
            $Error .= $this->var_Significado->Errors->ToString();
            $Error .= $this->var_Clase->Errors->ToString();
            $Error .= $this->var_Orden->Errors->ToString();
            $Error .= $this->var_Estado->Errors->ToString();
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
        $this->var_TipProceso->Show();
        $this->var_Nombre->Show();
        $this->var_Descripcion->Show();
        $this->var_Significado->Show();
        $this->var_Clase->Show();
        $this->var_Orden->Show();
        $this->var_Estado->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End genvarproceso Class @2-FCB6E20C

class clsgenvarprocesoDataSource extends clsDBdatos {  //genvarprocesoDataSource Class @2-696C806E

//DataSource Variables @2-E61BDAA6
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $lbTitulo;
    var $var_TipProceso;
    var $var_Nombre;
    var $var_Descripcion;
    var $var_Significado;
    var $var_Clase;
    var $var_Orden;
    var $var_Estado;
//End DataSource Variables

//Class_Initialize Event @2-3D9E34AF
    function clsgenvarprocesoDataSource()
    {
        $this->ErrorBlock = "Record genvarproceso/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->var_TipProceso = new clsField("var_TipProceso", ccsText, "");
        $this->var_Nombre = new clsField("var_Nombre", ccsText, "");
        $this->var_Descripcion = new clsField("var_Descripcion", ccsText, "");
        $this->var_Significado = new clsField("var_Significado", ccsMemo, "");
        $this->var_Clase = new clsField("var_Clase", ccsInteger, "");
        $this->var_Orden = new clsField("var_Orden", ccsInteger, "");
        $this->var_Estado = new clsField("var_Estado", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @2-8B25D22D
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlvar_ID", ccsInteger, "", "", $this->Parameters["urlvar_ID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "var_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-8C7C03A2
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM genvarproceso";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-26B2F533
    function SetValues()
    {
        $this->var_TipProceso->SetDBValue($this->f("var_TipProceso"));
        $this->var_Nombre->SetDBValue($this->f("var_Nombre"));
        $this->var_Descripcion->SetDBValue($this->f("var_Descripcion"));
        $this->var_Significado->SetDBValue($this->f("var_Significado"));
        $this->var_Clase->SetDBValue(trim($this->f("var_Clase")));
        $this->var_Orden->SetDBValue(trim($this->f("var_Orden")));
        $this->var_Estado->SetDBValue(trim($this->f("var_Estado")));
    }
//End SetValues Method

//Insert Method @2-F60327AA
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO genvarproceso ("
             . "var_TipProceso, "
             . "var_Nombre, "
             . "var_Descripcion, "
             . "var_Significado, "
             . "var_Clase, "
             . "var_Orden, "
             . "var_Estado"
             . ") VALUES ("
             . $this->ToSQL($this->var_TipProceso->GetDBValue(), $this->var_TipProceso->DataType) . ", "
             . $this->ToSQL($this->var_Nombre->GetDBValue(), $this->var_Nombre->DataType) . ", "
             . $this->ToSQL($this->var_Descripcion->GetDBValue(), $this->var_Descripcion->DataType) . ", "
             . $this->ToSQL($this->var_Significado->GetDBValue(), $this->var_Significado->DataType) . ", "
             . $this->ToSQL($this->var_Clase->GetDBValue(), $this->var_Clase->DataType) . ", "
             . $this->ToSQL($this->var_Orden->GetDBValue(), $this->var_Orden->DataType) . ", "
             . $this->ToSQL($this->var_Estado->GetDBValue(), $this->var_Estado->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-64A8BB9F
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE genvarproceso SET "
             . "var_TipProceso=" . $this->ToSQL($this->var_TipProceso->GetDBValue(), $this->var_TipProceso->DataType) . ", "
             . "var_Nombre=" . $this->ToSQL($this->var_Nombre->GetDBValue(), $this->var_Nombre->DataType) . ", "
             . "var_Descripcion=" . $this->ToSQL($this->var_Descripcion->GetDBValue(), $this->var_Descripcion->DataType) . ", "
             . "var_Significado=" . $this->ToSQL($this->var_Significado->GetDBValue(), $this->var_Significado->DataType) . ", "
             . "var_Clase=" . $this->ToSQL($this->var_Clase->GetDBValue(), $this->var_Clase->DataType) . ", "
             . "var_Orden=" . $this->ToSQL($this->var_Orden->GetDBValue(), $this->var_Orden->DataType) . ", "
             . "var_Estado=" . $this->ToSQL($this->var_Estado->GetDBValue(), $this->var_Estado->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-8D576D86
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM genvarproceso";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End genvarprocesoDataSource Class @2-FCB6E20C

//Initialize Page @1-70AE5674
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

$FileName = "LiAdVa_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdVa_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-588FD29D
$DBdatos = new clsDBdatos();

// Controls
$genvarproceso = new clsRecordgenvarproceso();
$genvarproceso->Initialize();

// Events
include("./LiAdVa_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if($Charset) {
    header("Content-Type: text/html; charset=" . $Charset);
}
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-6D327AEB
$genvarproceso->Operation();
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

//Show Page @1-3F8EAAD2
$genvarproceso->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $generated_with . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $generated_with;
}
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
