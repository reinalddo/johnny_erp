<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

include (RelativePath . "/LibPhp/SegLib.php");
class clsRecordliqbuques { //liqbuques Class @2-80364BFF

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

//Class_Initialize Event @2-54D0A003
    function clsRecordliqbuques()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqbuques/Error";
        $this->ds = new clsliqbuquesDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "liqbuques";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->buq_Codigo = new clsControl(ccsTextBox, "buq_Codigo", "Código de Buque", ccsInteger, "", CCGetRequestParam("buq_Codigo", $Method));
            $this->buq_Abreviatura = new clsControl(ccsTextBox, "buq_Abreviatura", "Buq Abreviatura", ccsText, "", CCGetRequestParam("buq_Abreviatura", $Method));
            $this->buq_Abreviatura->Required = true;
            $this->buq_Descripcion = new clsControl(ccsTextBox, "buq_Descripcion", "Buq Descripcion", ccsText, "", CCGetRequestParam("buq_Descripcion", $Method));
            $this->buq_Descripcion->Required = true;
            $this->buq_Pais = new clsControl(ccsListBox, "buq_Pais", "Buq Pais", ccsInteger, "", CCGetRequestParam("buq_Pais", $Method));
            $this->buq_Pais->DSType = dsTable;
            list($this->buq_Pais->BoundColumn, $this->buq_Pais->TextColumn, $this->buq_Pais->DBFormat) = array("pai_CodPais", "pai_Descripcion", "");
            $this->buq_Pais->ds = new clsDBdatos();
            $this->buq_Pais->ds->SQL = "SELECT *  " .
"FROM genpaises";
            $this->buq_Estado = new clsControl(ccsListBox, "buq_Estado", "Buq Estado", ccsInteger, "", CCGetRequestParam("buq_Estado", $Method));
            $this->buq_Estado->DSType = dsSQL;
            list($this->buq_Estado->BoundColumn, $this->buq_Estado->TextColumn, $this->buq_Estado->DBFormat) = array("par_secuencia", "par_descripcion", "");
            $this->buq_Estado->ds = new clsDBdatos();
            $this->buq_Estado->ds->SQL = "select par_secuencia, par_descripcion " .
            "from genparametros " .
            "where par_clave = 'LGESTA'";
            $this->buq_Estado->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->lkRegresar = new clsControl(ccsLink, "lkRegresar", "lkRegresar", ccsText, "", CCGetRequestParam("lkRegresar", $Method));
            $this->lkRegresar->Parameters = CCGetQueryString("All", Array("ccsForm"));
            $this->lkRegresar->Page = "LiAdBu_search.php";
            if(!$this->FormSubmitted) {
                if(!is_array($this->buq_Estado->Value) && !strlen($this->buq_Estado->Value) && $this->buq_Estado->Value !== false)
                $this->buq_Estado->SetText(1);
            }
            if(!is_array($this->lbTitulo->Value) && !strlen($this->lbTitulo->Value) && $this->lbTitulo->Value !== false)
            $this->lbTitulo->SetText('BUQUE / VAPOR NUEVO');
        }
    }
//End Class_Initialize Event

//Initialize Method @2-4336E9E8
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlbuq_CodBuque"] = CCGetFromGet("buq_CodBuque", "");
    }
//End Initialize Method

//Validate Method @2-18731073
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->buq_Codigo->SetValue($this->buq_Codigo->GetValue());
        if(CCDLookUp("COUNT(*)", "liqbuques", "buq_CodBuque=" . $this->ds->ToSQL($this->ds->buq_Codigo->GetDBValue(), $this->ds->buq_Codigo->DataType) . $Where, $DBdatos) > 0)
            $this->buq_Codigo->Errors->addError("El campo Código de Buque ya existe.");
        global $DBdatos;
        $this->ds->buq_Abreviatura->SetValue($this->buq_Abreviatura->GetValue());
        if(CCDLookUp("COUNT(*)", "liqbuques", "buq_Abreviatura=" . $this->ds->ToSQL($this->ds->buq_Abreviatura->GetDBValue(), $this->ds->buq_Abreviatura->DataType) . $Where, $DBdatos) > 0)
            $this->buq_Abreviatura->Errors->addError("El campo Buq Abreviatura ya existe.");
        $Validation = ($this->buq_Codigo->Validate() && $Validation);
        $Validation = ($this->buq_Abreviatura->Validate() && $Validation);
        $Validation = ($this->buq_Descripcion->Validate() && $Validation);
        $Validation = ($this->buq_Pais->Validate() && $Validation);
        $Validation = ($this->buq_Estado->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-F5FC94CB
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->buq_Codigo->Errors->Count());
        $errors = ($errors || $this->buq_Abreviatura->Errors->Count());
        $errors = ($errors || $this->buq_Descripcion->Errors->Count());
        $errors = ($errors || $this->buq_Pais->Errors->Count());
        $errors = ($errors || $this->buq_Estado->Errors->Count());
        $errors = ($errors || $this->lkRegresar->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-697DBE72
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
        $Redirect = "LiAdBu_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @2-9F80433D
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->buq_Codigo->SetValue($this->buq_Codigo->GetValue());
        $this->ds->buq_Abreviatura->SetValue($this->buq_Abreviatura->GetValue());
        $this->ds->buq_Descripcion->SetValue($this->buq_Descripcion->GetValue());
        $this->ds->buq_Pais->SetValue($this->buq_Pais->GetValue());
        $this->ds->buq_Estado->SetValue($this->buq_Estado->GetValue());
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

//UpdateRow Method @2-FE68EB47
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->buq_Abreviatura->SetValue($this->buq_Abreviatura->GetValue());
        $this->ds->buq_Descripcion->SetValue($this->buq_Descripcion->GetValue());
        $this->ds->buq_Pais->SetValue($this->buq_Pais->GetValue());
        $this->ds->buq_Estado->SetValue($this->buq_Estado->GetValue());
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

//Show Method @2-FE0CA1C9
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->buq_Pais->Prepare();
        $this->buq_Estado->Prepare();

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
                    echo "Error in Record liqbuques";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->buq_Codigo->SetValue($this->ds->buq_Codigo->GetValue());
                        $this->buq_Abreviatura->SetValue($this->ds->buq_Abreviatura->GetValue());
                        $this->buq_Descripcion->SetValue($this->ds->buq_Descripcion->GetValue());
                        $this->buq_Pais->SetValue($this->ds->buq_Pais->GetValue());
                        $this->buq_Estado->SetValue($this->ds->buq_Estado->GetValue());
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
            $this->lkRegresar->SetText('REGRESAR...');
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbTitulo->Errors->ToString();
            $Error .= $this->buq_Codigo->Errors->ToString();
            $Error .= $this->buq_Abreviatura->Errors->ToString();
            $Error .= $this->buq_Descripcion->Errors->ToString();
            $Error .= $this->buq_Pais->Errors->ToString();
            $Error .= $this->buq_Estado->Errors->ToString();
            $Error .= $this->lkRegresar->Errors->ToString();
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
        $this->buq_Codigo->Show();
        $this->buq_Abreviatura->Show();
        $this->buq_Descripcion->Show();
        $this->buq_Pais->Show();
        $this->buq_Estado->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->lkRegresar->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End liqbuques Class @2-FCB6E20C

class clsliqbuquesDataSource extends clsDBdatos {  //liqbuquesDataSource Class @2-1086C8EE

//DataSource Variables @2-9567145C
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
    var $buq_Codigo;
    var $buq_Abreviatura;
    var $buq_Descripcion;
    var $buq_Pais;
    var $buq_Estado;
    var $lkRegresar;
//End DataSource Variables

//Class_Initialize Event @2-95D36611
    function clsliqbuquesDataSource()
    {
        $this->ErrorBlock = "Record liqbuques/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->buq_Codigo = new clsField("buq_Codigo", ccsInteger, "");
        $this->buq_Abreviatura = new clsField("buq_Abreviatura", ccsText, "");
        $this->buq_Descripcion = new clsField("buq_Descripcion", ccsText, "");
        $this->buq_Pais = new clsField("buq_Pais", ccsInteger, "");
        $this->buq_Estado = new clsField("buq_Estado", ccsInteger, "");
        $this->lkRegresar = new clsField("lkRegresar", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @2-B7F04386
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlbuq_CodBuque", ccsInteger, "", "", $this->Parameters["urlbuq_CodBuque"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "buq_CodBuque", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-0DC381D1
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM liqbuques";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-F574E09D
    function SetValues()
    {
        $this->buq_Codigo->SetDBValue(trim($this->f("buq_CodBuque")));
        $this->buq_Abreviatura->SetDBValue($this->f("buq_Abreviatura"));
        $this->buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->buq_Pais->SetDBValue(trim($this->f("buq_Pais")));
        $this->buq_Estado->SetDBValue(trim($this->f("buq_Estado")));
    }
//End SetValues Method

//Insert Method @2-13A5B14D
    function Insert()
    {
        $this->cp["buq_CodBuque"] = new clsSQLParameter("ctrlbuq_Codigo", ccsInteger, "", "", $this->buq_Codigo->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["buq_Abreviatura"] = new clsSQLParameter("ctrlbuq_Abreviatura", ccsText, "", "", $this->buq_Abreviatura->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["buq_Descripcion"] = new clsSQLParameter("ctrlbuq_Descripcion", ccsText, "", "", $this->buq_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["buq_Pais"] = new clsSQLParameter("ctrlbuq_Pais", ccsInteger, "", "", $this->buq_Pais->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["buq_Estado"] = new clsSQLParameter("ctrlbuq_Estado", ccsInteger, "", "", $this->buq_Estado->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqbuques ("
             . "buq_CodBuque, "
             . "buq_Abreviatura, "
             . "buq_Descripcion, "
             . "buq_Pais, "
             . "buq_Estado"
             . ") VALUES ("
             . $this->ToSQL($this->cp["buq_CodBuque"]->GetDBValue(), $this->cp["buq_CodBuque"]->DataType) . ", "
             . $this->ToSQL($this->cp["buq_Abreviatura"]->GetDBValue(), $this->cp["buq_Abreviatura"]->DataType) . ", "
             . $this->ToSQL($this->cp["buq_Descripcion"]->GetDBValue(), $this->cp["buq_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["buq_Pais"]->GetDBValue(), $this->cp["buq_Pais"]->DataType) . ", "
             . $this->ToSQL($this->cp["buq_Estado"]->GetDBValue(), $this->cp["buq_Estado"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-7AF3F89E
    function Update()
    {
        $this->cp["buq_Abreviatura"] = new clsSQLParameter("ctrlbuq_Abreviatura", ccsText, "", "", $this->buq_Abreviatura->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["buq_Descripcion"] = new clsSQLParameter("ctrlbuq_Descripcion", ccsText, "", "", $this->buq_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["buq_Pais"] = new clsSQLParameter("ctrlbuq_Pais", ccsInteger, "", "", $this->buq_Pais->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["buq_Estado"] = new clsSQLParameter("ctrlbuq_Estado", ccsInteger, "", "", $this->buq_Estado->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlbuq_CodBuque", ccsInteger, "", "", CCGetFromGet("buq_CodBuque", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "buq_CodBuque", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE liqbuques SET "
             . "buq_Abreviatura=" . $this->ToSQL($this->cp["buq_Abreviatura"]->GetDBValue(), $this->cp["buq_Abreviatura"]->DataType) . ", "
             . "buq_Descripcion=" . $this->ToSQL($this->cp["buq_Descripcion"]->GetDBValue(), $this->cp["buq_Descripcion"]->DataType) . ", "
             . "buq_Pais=" . $this->ToSQL($this->cp["buq_Pais"]->GetDBValue(), $this->cp["buq_Pais"]->DataType) . ", "
             . "buq_Estado=" . $this->ToSQL($this->cp["buq_Estado"]->GetDBValue(), $this->cp["buq_Estado"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-28FA12E2
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlbuq_CodBuque", ccsInteger, "", "", CCGetFromGet("buq_CodBuque", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "buq_CodBuque", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM liqbuques";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqbuquesDataSource Class @2-FCB6E20C

//Initialize Page @1-529F97AF
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

$FileName = "LiAdBu_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdBu_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-DBB25B37
$DBdatos = new clsDBdatos();

// Controls
$liqbuques = new clsRecordliqbuques();
$liqbuques->Initialize();

// Events
include("./LiAdBu_mant_events.php");
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

//Execute Components @1-D3BBA135
$liqbuques->Operation();
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

//Show Page @1-0D945CC4
$liqbuques->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
//$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
//$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
