<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

Class clsRecordperfil_mant { //perfil_mant Class @2-DED6C195

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

//Class_Initialize Event @2-4E166325
    function clsRecordperfil_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record perfil_mant/Error";
        $this->ds = new clsperfil_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "perfil_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->txt_Titulo = new clsControl(ccsLabel, "txt_Titulo", "txt_Titulo", ccsText, "", CCGetRequestParam("txt_Titulo", $Method));
            $this->pfl_IDperfil = new clsControl(ccsTextBox, "pfl_IDperfil", "Pfl IDperfil", ccsText, "", CCGetRequestParam("pfl_IDperfil", $Method));
            $this->pfl_Descripcion = new clsControl(ccsTextBox, "pfl_Descripcion", "Pfl Descripcion", ccsText, "", CCGetRequestParam("pfl_Descripcion", $Method));
            $this->pfl_estado = new clsControl(ccsListBox, "pfl_estado", "Pfl Estado", ccsInteger, "", CCGetRequestParam("pfl_estado", $Method));
            $this->pfl_estado->DSType = dsListOfValues;
            $this->pfl_estado->Values = array(array("0", "Inactivo"), array("1", "Activo"));
            $this->pfl_estado->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->bt_GenAtrib = new clsButton("bt_GenAtrib");
            if(!$this->FormSubmitted) {
                if(!is_array($this->pfl_estado->Value) && !strlen($this->pfl_estado->Value) && $this->pfl_estado->Value !== false)
                $this->pfl_estado->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-26DF9B13
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlpfl_IDperfil"] = CCGetFromGet("pfl_IDperfil", "");
    }
//End Initialize Method

//Validate Method @2-44B906CB
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->pfl_IDperfil->Validate() && $Validation);
        $Validation = ($this->pfl_Descripcion->Validate() && $Validation);
        $Validation = ($this->pfl_estado->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-EA797904
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->txt_Titulo->Errors->Count());
        $errors = ($errors || $this->pfl_IDperfil->Errors->Count());
        $errors = ($errors || $this->pfl_Descripcion->Errors->Count());
        $errors = ($errors || $this->pfl_estado->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-CCCD770C
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
            } else if(strlen(CCGetParam("bt_GenAtrib", ""))) {
                $this->PressedButton = "bt_GenAtrib";
            }
        }
        $Redirect = "SeGePe_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            } else {
                $Redirect = "SeGePe_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "pfl_IDperfil"));
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
            } else if($this->PressedButton == "bt_GenAtrib") {
                if(!CCGetEvent($this->bt_GenAtrib->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @2-F56F9A58
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->pfl_IDperfil->SetValue($this->pfl_IDperfil->GetValue());
        $this->ds->pfl_Descripcion->SetValue($this->pfl_Descripcion->GetValue());
        $this->ds->pfl_estado->SetValue($this->pfl_estado->GetValue());
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

//UpdateRow Method @2-C306AF45
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->pfl_IDperfil->SetValue($this->pfl_IDperfil->GetValue());
        $this->ds->pfl_Descripcion->SetValue($this->pfl_Descripcion->GetValue());
        $this->ds->pfl_estado->SetValue($this->pfl_estado->GetValue());
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

//Show Method @2-F4080870
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->pfl_estado->Prepare();

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
                    echo "Error in Record perfil_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->pfl_IDperfil->SetValue($this->ds->pfl_IDperfil->GetValue());
                        $this->pfl_Descripcion->SetValue($this->ds->pfl_Descripcion->GetValue());
                        $this->pfl_estado->SetValue($this->ds->pfl_estado->GetValue());
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
            $this->txt_Titulo->SetText("AGREGAR NUEVO PERFIL");
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->txt_Titulo->Errors->ToString();
            $Error .= $this->pfl_IDperfil->Errors->ToString();
            $Error .= $this->pfl_Descripcion->Errors->ToString();
            $Error .= $this->pfl_estado->Errors->ToString();
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
        $this->txt_Titulo->Show();
        $this->pfl_IDperfil->Show();
        $this->pfl_Descripcion->Show();
        $this->pfl_estado->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->bt_GenAtrib->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End perfil_mant Class @2-FCB6E20C

class clsperfil_mantDataSource extends clsDBSeguridad {  //perfil_mantDataSource Class @2-62DA328F

//DataSource Variables @2-15F43A29
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $txt_Titulo;
    var $pfl_IDperfil;
    var $pfl_Descripcion;
    var $pfl_estado;
//End DataSource Variables

//Class_Initialize Event @2-D2133117
    function clsperfil_mantDataSource()
    {
        $this->ErrorBlock = "Record perfil_mant/Error";
        $this->Initialize();
        $this->txt_Titulo = new clsField("txt_Titulo", ccsText, "");
        $this->pfl_IDperfil = new clsField("pfl_IDperfil", ccsText, "");
        $this->pfl_Descripcion = new clsField("pfl_Descripcion", ccsText, "");
        $this->pfl_estado = new clsField("pfl_estado", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @2-AAD707B6
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlpfl_IDperfil", ccsText, "", "", $this->Parameters["urlpfl_IDperfil"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pfl_IDperfil", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-3801CFCB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM segperfiles";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-EEF43DC4
    function SetValues()
    {
        $this->pfl_IDperfil->SetDBValue($this->f("pfl_IDperfil"));
        $this->pfl_Descripcion->SetDBValue($this->f("pfl_Descripcion"));
        $this->pfl_estado->SetDBValue(trim($this->f("pfl_estado")));
    }
//End SetValues Method

//Insert Method @2-E67C518D
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO segperfiles ("
             . "pfl_IDperfil, "
             . "pfl_Descripcion, "
             . "pfl_estado"
             . ") VALUES ("
             . $this->ToSQL($this->pfl_IDperfil->GetDBValue(), $this->pfl_IDperfil->DataType) . ", "
             . $this->ToSQL($this->pfl_Descripcion->GetDBValue(), $this->pfl_Descripcion->DataType) . ", "
             . $this->ToSQL($this->pfl_estado->GetDBValue(), $this->pfl_estado->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-FD0712CB
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE segperfiles SET "
             . "pfl_IDperfil=" . $this->ToSQL($this->pfl_IDperfil->GetDBValue(), $this->pfl_IDperfil->DataType) . ", "
             . "pfl_Descripcion=" . $this->ToSQL($this->pfl_Descripcion->GetDBValue(), $this->pfl_Descripcion->DataType) . ", "
             . "pfl_estado=" . $this->ToSQL($this->pfl_estado->GetDBValue(), $this->pfl_estado->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-3FD9B1B1
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM segperfiles";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End perfil_mantDataSource Class @2-FCB6E20C

//Initialize Page @1-7E3DF0A6
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

$FileName = "SeGePe_mant.php";
$Redirect = "";
$TemplateFileName = "SeGePe_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-A41D4106
$DBdatos = new clsDBseguridad();

// Controls
$perfil_mant = new clsRecordperfil_mant();
$perfil_mant->Initialize();

// Events
include("./SeGePe_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-0507B35B
$perfil_mant->Operation();
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

//Show Page @1-99E17437
$perfil_mant->Show();
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
