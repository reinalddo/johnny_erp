<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

Class clsRecordliqcomponentSearch { //liqcomponentSearch Class @3-BFB35CB0

//Variables @3-CB19EB75

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

    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @3-FA1666DA
    function clsRecordliqcomponentSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqcomponentSearch/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "liqcomponentSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_cte_Clase = new clsControl(ccsListBox, "s_cte_Clase", "s_cte_Clase", ccsInteger, "", CCGetRequestParam("s_cte_Clase", $Method));
            $this->s_cte_Clase->DSType = dsTable;
            list($this->s_cte_Clase->BoundColumn, $this->s_cte_Clase->TextColumn, $this->s_cte_Clase->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_cte_Clase->ds = new clsDBdatos();
            $this->s_cte_Clase->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_cte_Clase->ds->Parameters["expr31"] = 'LGCOM';
            $this->s_cte_Clase->ds->wp = new clsSQLParameters();
            $this->s_cte_Clase->ds->wp->AddParameter("1", "expr31", ccsText, "", "", $this->s_cte_Clase->ds->Parameters["expr31"], "", false);
            $this->s_cte_Clase->ds->wp->Criterion[1] = $this->s_cte_Clase->ds->wp->Operation(opEqual, "par_Clave", $this->s_cte_Clase->ds->wp->GetDBValue("1"), $this->s_cte_Clase->ds->ToSQL($this->s_cte_Clase->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_cte_Clase->ds->Where = $this->s_cte_Clase->ds->wp->Criterion[1];
            $this->s_cte_Referencia = new clsControl(ccsTextBox, "s_cte_Referencia", "s_cte_Referencia", ccsText, "", CCGetRequestParam("s_cte_Referencia", $Method));
            $this->s_cte_Descripcion = new clsControl(ccsTextBox, "s_cte_Descripcion", "s_cte_Descripcion", ccsText, "", CCGetRequestParam("s_cte_Descripcion", $Method));
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_cte_Clase", "s_cte_Referencia", "s_cte_Descripcion", "ccsForm"));
            $this->ClearParameters->Page = "LiAdCo.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @3-EF75B039
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_cte_Clase->Validate() && $Validation);
        $Validation = ($this->s_cte_Referencia->Validate() && $Validation);
        $Validation = ($this->s_cte_Descripcion->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-DB424274
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_cte_Clase->Errors->Count());
        $errors = ($errors || $this->s_cte_Referencia->Errors->Count());
        $errors = ($errors || $this->s_cte_Descripcion->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-C0A724CC
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "LiAdCo.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdCo.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-DAC02A2B
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_cte_Clase->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_cte_Clase->Errors->ToString();
            $Error .= $this->s_cte_Referencia->Errors->ToString();
            $Error .= $this->s_cte_Descripcion->Errors->ToString();
            $Error .= $this->ClearParameters->Errors->ToString();
            $Error .= $this->Errors->ToString();
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

        $this->s_cte_Clase->Show();
        $this->s_cte_Referencia->Show();
        $this->s_cte_Descripcion->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liqcomponentSearch Class @3-FCB6E20C

Class clsEditableGridliqcomponent { //liqcomponent Class @2-E5D7AD2B

//Variables @2-3D5009E3

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
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_cte_Codigo;
    var $Sorter_cte_Clase;
    var $Sorter_cte_IndRechazo;
    var $Sorter_cte_Referencia;
    var $Sorter_cte_Descripcion;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-A00570E5
    function clsEditableGridliqcomponent()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid liqcomponent/Error";
        $this->ComponentName = "liqcomponent";
        $this->CachedColumns["cte_Codigo"][0] = "cte_Codigo";
        $this->ds = new clsliqcomponentDataSource();

        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 5;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
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

        $this->SorterName = CCGetParam("liqcomponentOrder", "");
        $this->SorterDirection = CCGetParam("liqcomponentDir", "");

        $this->liqcomponent_TotalRecords = new clsControl(ccsLabel, "liqcomponent_TotalRecords", "liqcomponent_TotalRecords", ccsText, "");
        $this->Sorter_cte_Codigo = new clsSorter($this->ComponentName, "Sorter_cte_Codigo", $FileName);
        $this->Sorter_cte_Clase = new clsSorter($this->ComponentName, "Sorter_cte_Clase", $FileName);
        $this->Sorter_cte_IndRechazo = new clsSorter($this->ComponentName, "Sorter_cte_IndRechazo", $FileName);
        $this->Sorter_cte_Referencia = new clsSorter($this->ComponentName, "Sorter_cte_Referencia", $FileName);
        $this->Sorter_cte_Descripcion = new clsSorter($this->ComponentName, "Sorter_cte_Descripcion", $FileName);
        $this->cte_Codigo = new clsControl(ccsLink, "cte_Codigo", "Cte Codigo", ccsInteger, "");
        $this->cte_Clase = new clsControl(ccsListBox, "cte_Clase", "Cte Clase", ccsInteger, "");
        $this->cte_Clase->DSType = dsTable;
        list($this->cte_Clase->BoundColumn, $this->cte_Clase->TextColumn, $this->cte_Clase->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
        $this->cte_Clase->ds = new clsDBdatos();
        $this->cte_Clase->ds->SQL = "SELECT *  " .
"FROM genparametros";
        $this->cte_Clase->ds->Parameters["expr30"] = 'LGCOM';
        $this->cte_Clase->ds->wp = new clsSQLParameters();
        $this->cte_Clase->ds->wp->AddParameter("1", "expr30", ccsText, "", "", $this->cte_Clase->ds->Parameters["expr30"], "", false);
        $this->cte_Clase->ds->wp->Criterion[1] = $this->cte_Clase->ds->wp->Operation(opEqual, "par_Clave", $this->cte_Clase->ds->wp->GetDBValue("1"), $this->cte_Clase->ds->ToSQL($this->cte_Clase->ds->wp->GetDBValue("1"), ccsText),false);
        $this->cte_Clase->ds->Where = $this->cte_Clase->ds->wp->Criterion[1];
        $this->cte_Clase->Required = true;
        $this->cte_IndRechazo = new clsControl(ccsListBox, "cte_IndRechazo", "Cte Ind Rechazo", ccsInteger, "");
        $this->cte_IndRechazo->DSType = dsListOfValues;
        $this->cte_IndRechazo->Values = array(array("0", "No Reutilizable"), array("1", "Reutilizable"));
        $this->cte_Referencia = new clsControl(ccsTextBox, "cte_Referencia", "Cte Referencia", ccsText, "");
        $this->cte_Descripcion = new clsControl(ccsTextBox, "cte_Descripcion", "Cte Descripcion", ccsText, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @2-0005510D
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urls_cte_Clase"] = CCGetFromGet("s_cte_Clase", "");
        $this->ds->Parameters["urls_cte_Referencia"] = CCGetFromGet("s_cte_Referencia", "");
        $this->ds->Parameters["urls_cte_Descripcion"] = CCGetFromGet("s_cte_Descripcion", "");
    }
//End Initialize Method

//GetFormParameters Method @2-17E2F8B8
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["cte_Clase"][$RowNumber] = CCGetFromPost("cte_Clase_" . $RowNumber);
            $this->FormParameters["cte_IndRechazo"][$RowNumber] = CCGetFromPost("cte_IndRechazo_" . $RowNumber);
            $this->FormParameters["cte_Referencia"][$RowNumber] = CCGetFromPost("cte_Referencia_" . $RowNumber);
            $this->FormParameters["cte_Descripcion"][$RowNumber] = CCGetFromPost("cte_Descripcion_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-478C4EF3
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cte_Codigo"] = $this->CachedColumns["cte_Codigo"][$RowNumber];
            $this->cte_Clase->SetText($this->FormParameters["cte_Clase"][$RowNumber], $RowNumber);
            $this->cte_IndRechazo->SetText($this->FormParameters["cte_IndRechazo"][$RowNumber], $RowNumber);
            $this->cte_Referencia->SetText($this->FormParameters["cte_Referencia"][$RowNumber], $RowNumber);
            $this->cte_Descripcion->SetText($this->FormParameters["cte_Descripcion"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-9192006E
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->cte_Clase->Validate() && $Validation);
        $Validation = ($this->cte_IndRechazo->Validate() && $Validation);
        $Validation = ($this->cte_Referencia->Validate() && $Validation);
        $Validation = ($this->cte_Descripcion->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->cte_Clase->Errors->ToString();
            $errors .= $this->cte_IndRechazo->Errors->ToString();
            $errors .= $this->cte_Referencia->Errors->ToString();
            $errors .= $this->cte_Descripcion->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->cte_Clase->Errors->Clear();
            $this->cte_IndRechazo->Errors->Clear();
            $this->cte_Referencia->Errors->Clear();
            $this->cte_Descripcion->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @2-8A4EC473
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["cte_Clase"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cte_IndRechazo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cte_Referencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cte_Descripcion"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @2-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-7B861278
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

//UpdateGrid Method @2-A433DD0F
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cte_Codigo"] = $this->CachedColumns["cte_Codigo"][$RowNumber];
            $this->cte_Clase->SetText($this->FormParameters["cte_Clase"][$RowNumber], $RowNumber);
            $this->cte_IndRechazo->SetText($this->FormParameters["cte_IndRechazo"][$RowNumber], $RowNumber);
            $this->cte_Referencia->SetText($this->FormParameters["cte_Referencia"][$RowNumber], $RowNumber);
            $this->cte_Descripcion->SetText($this->FormParameters["cte_Descripcion"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) $this->DeleteRow($RowNumber);
                } else if($this->UpdateAllowed) {
                    $this->UpdateRow($RowNumber);
                }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $this->InsertRow($RowNumber);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0);
    }
//End UpdateGrid Method

//InsertRow Method @2-D27557A1
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->cte_Codigo->SetValue($this->cte_Codigo->GetValue());
        $this->ds->cte_Clase->SetValue($this->cte_Clase->GetValue());
        $this->ds->cte_IndRechazo->SetValue($this->cte_IndRechazo->GetValue());
        $this->ds->cte_Referencia->SetValue($this->cte_Referencia->GetValue());
        $this->ds->cte_Descripcion->SetValue($this->cte_Descripcion->GetValue());
        $this->ds->Insert();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @2-E5401448
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->cte_Codigo->SetValue($this->cte_Codigo->GetValue());
        $this->ds->cte_Clase->SetValue($this->cte_Clase->GetValue());
        $this->ds->cte_IndRechazo->SetValue($this->cte_IndRechazo->GetValue());
        $this->ds->cte_Referencia->SetValue($this->cte_Referencia->GetValue());
        $this->ds->cte_Descripcion->SetValue($this->cte_Descripcion->GetValue());
        $this->ds->Update();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . $this->ComponentName . " / Update Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//DeleteRow Method @2-0C9DDC34
    function DeleteRow($RowNumber)
    {
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . ComponentName . " / Delete Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @2-212D0AAE
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var liqcomponentElements;\n";
        $script .= "var liqcomponentEmptyRows = 5;\n";
        $script .= "var " . $this->ComponentName . "cte_ClaseID = 0;\n";
        $script .= "var " . $this->ComponentName . "cte_IndRechazoID = 1;\n";
        $script .= "var " . $this->ComponentName . "cte_ReferenciaID = 2;\n";
        $script .= "var " . $this->ComponentName . "cte_DescripcionID = 3;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 4;\n";
        $script .= "\nfunction initliqcomponentElements() {\n";
        $script .= "\tvar ED = document.forms[\"liqcomponent\"];\n";
        $script .= "\tliqcomponentElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.cte_Clase_" . $i . ", " . "ED.cte_IndRechazo_" . $i . ", " . "ED.cte_Referencia_" . $i . ", " . "ED.cte_Descripcion_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-17D3CF2A
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 1)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cte_Codigo"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["cte_Codigo"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-19F8CDD3
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cte_Codigo"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-0A5FCE12
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cte_Clase->Prepare();
        $this->cte_IndRechazo->Prepare();

        $this->ds->open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) { return; }

        $this->Button_Submit->Visible = ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $is_next_record = false;
        if($this->Errors->Count() == 0)
        {
            $is_next_record = ($this->ds->next_record() && $RowNumber < $this->PageSize);
            if($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed))
            {
                do
                {
                    $RowNumber++;
                    if($is_next_record) {
                        $NonEmptyRows++;
                        $this->ds->SetValues();
                        $this->cte_Codigo->SetValue($this->ds->cte_Codigo->GetValue());
                        $this->cte_Codigo->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                        $this->cte_Codigo->Parameters = CCAddParam($this->cte_Codigo->Parameters, "cte_Codigo", $this->ds->f("cte_Codigo"));
                        $this->cte_Codigo->Page = "LiAdRe.php";
                    } else {
                        $this->cte_Codigo->SetText("");
                    }
                    if(!$is_next_record || !$this->DeleteAllowed)
                        $this->CheckBox_Delete->Visible = false;
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["cte_Codigo"][$RowNumber] = $this->ds->CachedColumns["cte_Codigo"];
                        $this->cte_Clase->SetValue($this->ds->cte_Clase->GetValue());
                        $this->cte_IndRechazo->SetValue($this->ds->cte_IndRechazo->GetValue());
                        $this->cte_Referencia->SetValue($this->ds->cte_Referencia->GetValue());
                        $this->cte_Descripcion->SetValue($this->ds->cte_Descripcion->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["cte_Codigo"][$RowNumber] = "";
                        $this->cte_Clase->SetText("");
                        $this->cte_IndRechazo->SetText("");
                        $this->cte_Referencia->SetText("");
                        $this->cte_Descripcion->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->cte_Clase->SetText($this->FormParameters["cte_Clase"][$RowNumber], $RowNumber);
                        $this->cte_IndRechazo->SetText($this->FormParameters["cte_IndRechazo"][$RowNumber], $RowNumber);
                        $this->cte_Referencia->SetText($this->FormParameters["cte_Referencia"][$RowNumber], $RowNumber);
                        $this->cte_Descripcion->SetText($this->FormParameters["cte_Descripcion"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->cte_Codigo->Show($RowNumber);
                    $this->cte_Clase->Show($RowNumber);
                    $this->cte_IndRechazo->Show($RowNumber);
                    $this->cte_Referencia->Show($RowNumber);
                    $this->cte_Descripcion->Show($RowNumber);
                    $this->CheckBox_Delete->Show($RowNumber);
                    if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                        $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                        $Tpl->parse("RowError", false);
                    } else {
                        $Tpl->setblockvar("RowError", "");
                    }
                    $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                    $Tpl->parse();
                    if($is_next_record) $is_next_record = ($this->ds->next_record() && $RowNumber < $this->PageSize);
                    else $EmptyRowsLeft--;
                } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
            } else {
                $Tpl->block_path = $EditableGridPath;
                $Tpl->parse("NoRecords", false);
            }
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->liqcomponent_TotalRecords->Show();
        $this->Sorter_cte_Codigo->Show();
        $this->Sorter_cte_Clase->Show();
        $this->Sorter_cte_IndRechazo->Show();
        $this->Sorter_cte_Referencia->Show();
        $this->Sorter_cte_Descripcion->Show();
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

} //End liqcomponent Class @2-FCB6E20C

class clsliqcomponentDataSource extends clsDBdatos {  //liqcomponentDataSource Class @2-80BE2225

//DataSource Variables @2-1B89EE25
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $cte_Codigo;
    var $cte_Clase;
    var $cte_IndRechazo;
    var $cte_Referencia;
    var $cte_Descripcion;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @2-C378B8A2
    function clsliqcomponentDataSource()
    {
        $this->ErrorBlock = "EditableGrid liqcomponent/Error";
        $this->Initialize();
        $this->cte_Codigo = new clsField("cte_Codigo", ccsInteger, "");
        $this->cte_Clase = new clsField("cte_Clase", ccsInteger, "");
        $this->cte_IndRechazo = new clsField("cte_IndRechazo", ccsInteger, "");
        $this->cte_Referencia = new clsField("cte_Referencia", ccsText, "");
        $this->cte_Descripcion = new clsField("cte_Descripcion", ccsText, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @2-D27830D6
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cte_Codigo" => array("cte_Codigo", ""), 
            "Sorter_cte_Clase" => array("cte_Clase", ""), 
            "Sorter_cte_IndRechazo" => array("cte_IndRechazo", ""), 
            "Sorter_cte_Referencia" => array("cte_Referencia", ""), 
            "Sorter_cte_Descripcion" => array("cte_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @2-61B725E4
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_cte_Clase", ccsInteger, "", "", $this->Parameters["urls_cte_Clase"], "", false);
        $this->wp->AddParameter("2", "urls_cte_Referencia", ccsText, "", "", $this->Parameters["urls_cte_Referencia"], "", false);
        $this->wp->AddParameter("3", "urls_cte_Descripcion", ccsText, "", "", $this->Parameters["urls_cte_Descripcion"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cte_Clase", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "cte_Referencia", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "cte_Descripcion", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @2-504F4395
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM liqcomponent";
        $this->SQL = "SELECT *  " .
        "FROM liqcomponent";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-3C200FFA
    function SetValues()
    {
        $this->CachedColumns["cte_Codigo"] = $this->f("cte_Codigo");
        $this->cte_Codigo->SetDBValue(trim($this->f("cte_Codigo")));
        $this->cte_Clase->SetDBValue(trim($this->f("cte_Clase")));
        $this->cte_IndRechazo->SetDBValue(trim($this->f("cte_IndRechazo")));
        $this->cte_Referencia->SetDBValue($this->f("cte_Referencia"));
        $this->cte_Descripcion->SetDBValue($this->f("cte_Descripcion"));
    }
//End SetValues Method

//Insert Method @2-1878B3A5
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqcomponent ("
             . "cte_Clase, "
             . "cte_IndRechazo, "
             . "cte_Referencia, "
             . "cte_Descripcion"
             . ") VALUES ("
             . $this->ToSQL($this->cte_Clase->GetDBValue(), $this->cte_Clase->DataType) . ", "
             . $this->ToSQL($this->cte_IndRechazo->GetDBValue(), $this->cte_IndRechazo->DataType) . ", "
             . $this->ToSQL($this->cte_Referencia->GetDBValue(), $this->cte_Referencia->DataType) . ", "
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

//Update Method @2-1C5BA156
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->Where = "cte_Codigo=" . $this->ToSQL($this->CachedColumns["cte_Codigo"], ccsInteger);
        $this->SQL = "UPDATE liqcomponent SET "
             . "cte_Clase=" . $this->ToSQL($this->cte_Clase->GetDBValue(), $this->cte_Clase->DataType) . ", "
             . "cte_IndRechazo=" . $this->ToSQL($this->cte_IndRechazo->GetDBValue(), $this->cte_IndRechazo->DataType) . ", "
             . "cte_Referencia=" . $this->ToSQL($this->cte_Referencia->GetDBValue(), $this->cte_Referencia->DataType) . ", "
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

//Delete Method @2-589ABF6B
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->Where = "cte_Codigo=" . $this->ToSQL($this->CachedColumns["cte_Codigo"], ccsInteger);
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

//Initialize Page @1-62EFA1E9
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

$FileName = "LiAdCo.php";
$Redirect = "";
$TemplateFileName = "LiAdCo.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-5338BE91
$DBdatos = new clsDBdatos();

// Controls
$liqcomponentSearch = new clsRecordliqcomponentSearch();
$liqcomponent = new clsEditableGridliqcomponent();
$liqcomponent->Initialize();

// Events
include("./LiAdCo_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-F786626A
$liqcomponentSearch->Operation();
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

//Show Page @1-FC19892D
$liqcomponentSearch->Show();
$liqcomponent->Show();
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
