<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @15-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqcomponent { //liqcomponent Class @2-750EE4AF

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

//Class_Initialize Event @2-576E6C45
    function clsRecordliqcomponent()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqcomponent/Error";
        $this->ds = new clsliqcomponentDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "liqcomponent";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->cte_Codigo = new clsControl(ccsTextBox, "cte_Codigo", "cte_Codigo", ccsInteger, "", CCGetRequestParam("cte_Codigo", $Method));
            $this->cte_Clase = new clsControl(ccsRadioButton, "cte_Clase", "CLASE", ccsInteger, "", CCGetRequestParam("cte_Clase", $Method));
            $this->cte_Clase->DSType = dsTable;
            list($this->cte_Clase->BoundColumn, $this->cte_Clase->TextColumn, $this->cte_Clase->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cte_Clase->ds = new clsDBdatos();
            $this->cte_Clase->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->cte_Clase->ds->Parameters["expr13"] = LGCOM;
            $this->cte_Clase->ds->wp = new clsSQLParameters();
            $this->cte_Clase->ds->wp->AddParameter("1", "expr13", ccsText, "", "", $this->cte_Clase->ds->Parameters["expr13"], "", true);
            $this->cte_Clase->ds->wp->Criterion[1] = $this->cte_Clase->ds->wp->Operation(opEqual, "par_Clave", $this->cte_Clase->ds->wp->GetDBValue("1"), $this->cte_Clase->ds->ToSQL($this->cte_Clase->ds->wp->GetDBValue("1"), ccsText),true);
            $this->cte_Clase->ds->Where = $this->cte_Clase->ds->wp->Criterion[1];
            $this->cte_Clase->Required = true;
            $this->cte_Referencia = new clsControl(ccsTextBox, "cte_Referencia", "Abreviatura", ccsText, "", CCGetRequestParam("cte_Referencia", $Method));
            $this->cte_Referencia->Required = true;
            $this->cte_IndRechazo = new clsControl(ccsRadioButton, "cte_IndRechazo", "Cond. de Rechazo", ccsInteger, "", CCGetRequestParam("cte_IndRechazo", $Method));
            $this->cte_IndRechazo->DSType = dsListOfValues;
            $this->cte_IndRechazo->Values = array(array("0", "NO REUTILIZABLE"), array("1", "REUTILIZABLE"));
            $this->cte_IndRechazo->HTML = true;
            $this->cte_IndRechazo->Required = true;
            $this->cte_Descripcion = new clsControl(ccsTextBox, "cte_Descripcion", "Descripcion", ccsText, "", CCGetRequestParam("cte_Descripcion", $Method));
            $this->cte_Descripcion->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->cte_IndRechazo->Value) && !strlen($this->cte_IndRechazo->Value) && $this->cte_IndRechazo->Value !== false)
                $this->cte_IndRechazo->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-F6C84133
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlcte_Codigo"] = CCGetFromGet("cte_Codigo", "");
    }
//End Initialize Method

//Validate Method @2-4099CE84
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->cte_Codigo->Validate() && $Validation);
        $Validation = ($this->cte_Clase->Validate() && $Validation);
        $Validation = ($this->cte_Referencia->Validate() && $Validation);
        $Validation = ($this->cte_IndRechazo->Validate() && $Validation);
        $Validation = ($this->cte_Descripcion->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-522AA63B
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->cte_Codigo->Errors->Count());
        $errors = ($errors || $this->cte_Clase->Errors->Count());
        $errors = ($errors || $this->cte_Referencia->Errors->Count());
        $errors = ($errors || $this->cte_IndRechazo->Errors->Count());
        $errors = ($errors || $this->cte_Descripcion->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-34C4E344
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
        $Redirect = "LiAdCo_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @2-ECED0BE7
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->cte_Codigo->SetValue($this->cte_Codigo->GetValue());
        $this->ds->cte_Clase->SetValue($this->cte_Clase->GetValue());
        $this->ds->cte_Referencia->SetValue($this->cte_Referencia->GetValue());
        $this->ds->cte_IndRechazo->SetValue($this->cte_IndRechazo->GetValue());
        $this->ds->cte_Descripcion->SetValue($this->cte_Descripcion->GetValue());
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

//UpdateRow Method @2-62BE92D3
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->cte_Codigo->SetValue($this->cte_Codigo->GetValue());
        $this->ds->cte_Clase->SetValue($this->cte_Clase->GetValue());
        $this->ds->cte_Referencia->SetValue($this->cte_Referencia->GetValue());
        $this->ds->cte_IndRechazo->SetValue($this->cte_IndRechazo->GetValue());
        $this->ds->cte_Descripcion->SetValue($this->cte_Descripcion->GetValue());
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

//Show Method @2-997E4A5C
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cte_Clase->Prepare();
        $this->cte_IndRechazo->Prepare();

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
                    echo "Error in Record liqcomponent";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->cte_Codigo->SetValue($this->ds->cte_Codigo->GetValue());
                        $this->cte_Clase->SetValue($this->ds->cte_Clase->GetValue());
                        $this->cte_Referencia->SetValue($this->ds->cte_Referencia->GetValue());
                        $this->cte_IndRechazo->SetValue($this->ds->cte_IndRechazo->GetValue());
                        $this->cte_Descripcion->SetValue($this->ds->cte_Descripcion->GetValue());
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
            $Error .= $this->cte_Codigo->Errors->ToString();
            $Error .= $this->cte_Clase->Errors->ToString();
            $Error .= $this->cte_Referencia->Errors->ToString();
            $Error .= $this->cte_IndRechazo->Errors->ToString();
            $Error .= $this->cte_Descripcion->Errors->ToString();
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
        $this->cte_Codigo->Show();
        $this->cte_Clase->Show();
        $this->cte_Referencia->Show();
        $this->cte_IndRechazo->Show();
        $this->cte_Descripcion->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End liqcomponent Class @2-FCB6E20C

class clsliqcomponentDataSource extends clsDBdatos {  //liqcomponentDataSource Class @2-80BE2225

//DataSource Variables @2-EF515556
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
    var $cte_Codigo;
    var $cte_Clase;
    var $cte_Referencia;
    var $cte_IndRechazo;
    var $cte_Descripcion;
//End DataSource Variables

//Class_Initialize Event @2-024D4535
    function clsliqcomponentDataSource()
    {
        $this->ErrorBlock = "Record liqcomponent/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->cte_Codigo = new clsField("cte_Codigo", ccsInteger, "");
        $this->cte_Clase = new clsField("cte_Clase", ccsInteger, "");
        $this->cte_Referencia = new clsField("cte_Referencia", ccsText, "");
        $this->cte_IndRechazo = new clsField("cte_IndRechazo", ccsInteger, "");
        $this->cte_Descripcion = new clsField("cte_Descripcion", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @2-CAF77DA9
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcte_Codigo", ccsInteger, "", "", $this->Parameters["urlcte_Codigo"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cte_Codigo", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-40EA881E
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM liqcomponent";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-C7E392AD
    function SetValues()
    {
        $this->cte_Codigo->SetDBValue(trim($this->f("cte_Codigo")));
        $this->cte_Clase->SetDBValue(trim($this->f("cte_Clase")));
        $this->cte_Referencia->SetDBValue($this->f("cte_Referencia"));
        $this->cte_IndRechazo->SetDBValue(trim($this->f("cte_IndRechazo")));
        $this->cte_Descripcion->SetDBValue($this->f("cte_Descripcion"));
    }
//End SetValues Method

//Insert Method @2-820A1420
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqcomponent ("
             . "cte_Codigo, "
             . "cte_Clase, "
             . "cte_Referencia, "
             . "cte_IndRechazo, "
             . "cte_Descripcion"
             . ") VALUES ("
             . $this->ToSQL($this->cte_Codigo->GetDBValue(), $this->cte_Codigo->DataType) . ", "
             . $this->ToSQL($this->cte_Clase->GetDBValue(), $this->cte_Clase->DataType) . ", "
             . $this->ToSQL($this->cte_Referencia->GetDBValue(), $this->cte_Referencia->DataType) . ", "
             . $this->ToSQL($this->cte_IndRechazo->GetDBValue(), $this->cte_IndRechazo->DataType) . ", "
             . $this->ToSQL($this->cte_Descripcion->GetDBValue(), $this->cte_Descripcion->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-DD2EE6D5
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE liqcomponent SET "
             . "cte_Codigo=" . $this->ToSQL($this->cte_Codigo->GetDBValue(), $this->cte_Codigo->DataType) . ", "
             . "cte_Clase=" . $this->ToSQL($this->cte_Clase->GetDBValue(), $this->cte_Clase->DataType) . ", "
             . "cte_Referencia=" . $this->ToSQL($this->cte_Referencia->GetDBValue(), $this->cte_Referencia->DataType) . ", "
             . "cte_IndRechazo=" . $this->ToSQL($this->cte_IndRechazo->GetDBValue(), $this->cte_IndRechazo->DataType) . ", "
             . "cte_Descripcion=" . $this->ToSQL($this->cte_Descripcion->GetDBValue(), $this->cte_Descripcion->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-FF7FC89A
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM liqcomponent";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqcomponentDataSource Class @2-FCB6E20C

//Initialize Page @1-71D9563A
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

$FileName = "LiAdCo_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdCo_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-16455C05
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqcomponent = new clsRecordliqcomponent();
$liqcomponent->Initialize();

// Events
include("./LiAdCo_mant_events.php");
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

//Execute Components @1-D4D0190E
$Cabecera->Operations();
$liqcomponent->Operation();
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

//Show Page @1-A2314A79
$Cabecera->Show("Cabecera");
$liqcomponent->Show();
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
