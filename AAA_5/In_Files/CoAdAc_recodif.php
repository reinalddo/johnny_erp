<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsRecordconactivosSearch { //conactivosSearch Class @12-224218AF

//Variables @12-B2F7A83E

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

//Class_Initialize Event @12-426638B6
    function clsRecordconactivosSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record conactivosSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "conactivosSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_act_Descripcion = new clsControl(ccsTextBox, "s_act_Descripcion", "s_act_Descripcion", ccsText, "", CCGetRequestParam("s_act_Descripcion", $Method));
            $this->s_act_Descripcion1 = new clsControl(ccsTextBox, "s_act_Descripcion1", "s_act_Descripcion1", ccsText, "", CCGetRequestParam("s_act_Descripcion1", $Method));
            $this->s_act_Abreviatura = new clsControl(ccsTextBox, "s_act_Abreviatura", "s_act_Abreviatura", ccsText, "", CCGetRequestParam("s_act_Abreviatura", $Method));
            $this->s_act_CodAuxiliar = new clsControl(ccsTextBox, "s_act_CodAuxiliar", "s_act_CodAuxiliar", ccsInteger, "", CCGetRequestParam("s_act_CodAuxiliar", $Method));
            $this->s_act_UniMedida = new clsControl(ccsTextBox, "s_act_UniMedida", "s_act_UniMedida", ccsInteger, "", CCGetRequestParam("s_act_UniMedida", $Method));
            $this->s_act_NumSerie = new clsControl(ccsTextBox, "s_act_NumSerie", "s_act_NumSerie", ccsText, "", CCGetRequestParam("s_act_NumSerie", $Method));
            $this->s_act_CodAnterior = new clsControl(ccsTextBox, "s_act_CodAnterior", "s_act_CodAnterior", ccsText, "", CCGetRequestParam("s_act_CodAnterior", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @12-8300E6B5
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_act_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_act_Descripcion1->Validate() && $Validation);
        $Validation = ($this->s_act_Abreviatura->Validate() && $Validation);
        $Validation = ($this->s_act_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->s_act_UniMedida->Validate() && $Validation);
        $Validation = ($this->s_act_NumSerie->Validate() && $Validation);
        $Validation = ($this->s_act_CodAnterior->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->s_act_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Descripcion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Abreviatura->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_CodAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_UniMedida->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_NumSerie->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_CodAnterior->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @12-6E05BDF3
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_act_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_act_Descripcion1->Errors->Count());
        $errors = ($errors || $this->s_act_Abreviatura->Errors->Count());
        $errors = ($errors || $this->s_act_CodAuxiliar->Errors->Count());
        $errors = ($errors || $this->s_act_UniMedida->Errors->Count());
        $errors = ($errors || $this->s_act_NumSerie->Errors->Count());
        $errors = ($errors || $this->s_act_CodAnterior->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @12-7B04FCAB
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "CoAdAc_recodif.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdAc_recodif.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @12-3CDB257F
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_act_Descripcion->Errors->ToString();
            $Error .= $this->s_act_Descripcion1->Errors->ToString();
            $Error .= $this->s_act_Abreviatura->Errors->ToString();
            $Error .= $this->s_act_CodAuxiliar->Errors->ToString();
            $Error .= $this->s_act_UniMedida->Errors->ToString();
            $Error .= $this->s_act_NumSerie->Errors->ToString();
            $Error .= $this->s_act_CodAnterior->Errors->ToString();
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

        $this->s_act_Descripcion->Show();
        $this->s_act_Descripcion1->Show();
        $this->s_act_Abreviatura->Show();
        $this->s_act_CodAuxiliar->Show();
        $this->s_act_UniMedida->Show();
        $this->s_act_NumSerie->Show();
        $this->s_act_CodAnterior->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End conactivosSearch Class @12-FCB6E20C

class clsEditableGridconactivos { //conactivos Class @2-607F232F

//Variables @2-3080B36F

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
    var $Sorter_act_CodAuxiliar;
    var $Sorter_act_Abreviatura;
    var $Sorter_act_Descripcion;
    var $Sorter_act_Descripcion1;
    var $Sorter_act_UniMedida;
    var $Sorter_act_NumSerie;
    var $Sorter_act_CodAnterior;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-28C5FD6B
    function clsEditableGridconactivos()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid conactivos/Error";
        $this->ComponentName = "conactivos";
        $this->CachedColumns["act_CodAuxiliar"][0] = "act_CodAuxiliar";
        $this->ds = new clsconactivosDataSource();
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

        $this->SorterName = CCGetParam("conactivosOrder", "");
        $this->SorterDirection = CCGetParam("conactivosDir", "");

        $this->Sorter_act_CodAuxiliar = new clsSorter($this->ComponentName, "Sorter_act_CodAuxiliar", $FileName);
        $this->Sorter_act_Abreviatura = new clsSorter($this->ComponentName, "Sorter_act_Abreviatura", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_act_Descripcion1 = new clsSorter($this->ComponentName, "Sorter_act_Descripcion1", $FileName);
        $this->Sorter_act_UniMedida = new clsSorter($this->ComponentName, "Sorter_act_UniMedida", $FileName);
        $this->Sorter_act_NumSerie = new clsSorter($this->ComponentName, "Sorter_act_NumSerie", $FileName);
        $this->Sorter_act_CodAnterior = new clsSorter($this->ComponentName, "Sorter_act_CodAnterior", $FileName);
        $this->act_CodAuxiliar = new clsControl(ccsLabel, "act_CodAuxiliar", "Act Cod Auxiliar", ccsInteger, "");
        $this->act_Abreviatura = new clsControl(ccsTextBox, "act_Abreviatura", "Act Abreviatura", ccsText, "");
        $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Act Descripcion", ccsText, "");
        $this->act_Descripcion1 = new clsControl(ccsTextBox, "act_Descripcion1", "Act Descripcion1", ccsText, "");
        $this->act_UniMedida = new clsControl(ccsTextBox, "act_UniMedida", "Act Uni Medida", ccsInteger, "");
        $this->act_NumSerie = new clsControl(ccsTextBox, "act_NumSerie", "Act Num Serie", ccsText, "");
        $this->act_CodAnterior = new clsControl(ccsTextBox, "act_CodAnterior", "Act Cod Anterior", ccsText, "");
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
    }
//End Class_Initialize Event

//Initialize Method @2-9CC3C275
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urls_act_CodAuxiliar"] = CCGetFromGet("s_act_CodAuxiliar", "");
        $this->ds->Parameters["urls_act_Abreviatura"] = CCGetFromGet("s_act_Abreviatura", "");
        $this->ds->Parameters["urls_act_Descripcion"] = CCGetFromGet("s_act_Descripcion", "");
        $this->ds->Parameters["urls_act_Descripcion1"] = CCGetFromGet("s_act_Descripcion1", "");
        $this->ds->Parameters["urls_act_NumSerie"] = CCGetFromGet("s_act_NumSerie", "");
        $this->ds->Parameters["urls_act_UniMedida"] = CCGetFromGet("s_act_UniMedida", "");
        $this->ds->Parameters["urls_act_CodAnterior"] = CCGetFromGet("s_act_CodAnterior", "");
    }
//End Initialize Method

//GetFormParameters Method @2-8F55E43F
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["act_Abreviatura"][$RowNumber] = CCGetFromPost("act_Abreviatura_" . $RowNumber);
            $this->FormParameters["act_Descripcion"][$RowNumber] = CCGetFromPost("act_Descripcion_" . $RowNumber);
            $this->FormParameters["act_Descripcion1"][$RowNumber] = CCGetFromPost("act_Descripcion1_" . $RowNumber);
            $this->FormParameters["act_UniMedida"][$RowNumber] = CCGetFromPost("act_UniMedida_" . $RowNumber);
            $this->FormParameters["act_NumSerie"][$RowNumber] = CCGetFromPost("act_NumSerie_" . $RowNumber);
            $this->FormParameters["act_CodAnterior"][$RowNumber] = CCGetFromPost("act_CodAnterior_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-336C172F
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["act_CodAuxiliar"] = $this->CachedColumns["act_CodAuxiliar"][$RowNumber];
            $this->act_Abreviatura->SetText($this->FormParameters["act_Abreviatura"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->act_Descripcion1->SetText($this->FormParameters["act_Descripcion1"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->act_NumSerie->SetText($this->FormParameters["act_NumSerie"][$RowNumber], $RowNumber);
            $this->act_CodAnterior->SetText($this->FormParameters["act_CodAnterior"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
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

//ValidateRow Method @2-4567C29E
    function ValidateRow($RowNumber)
    {
        $this->act_Abreviatura->Validate();
        $this->act_Descripcion->Validate();
        $this->act_Descripcion1->Validate();
        $this->act_UniMedida->Validate();
        $this->act_NumSerie->Validate();
        $this->act_CodAnterior->Validate();
        $this->RowErrors = new clsErrors();
        $errors = $this->act_Abreviatura->Errors->ToString();
        $errors .= $this->act_Descripcion->Errors->ToString();
        $errors .= $this->act_Descripcion1->Errors->ToString();
        $errors .= $this->act_UniMedida->Errors->ToString();
        $errors .= $this->act_NumSerie->Errors->ToString();
        $errors .= $this->act_CodAnterior->Errors->ToString();
        $this->act_Abreviatura->Errors->Clear();
        $this->act_Descripcion->Errors->Clear();
        $this->act_Descripcion1->Errors->Clear();
        $this->act_UniMedida->Errors->Clear();
        $this->act_NumSerie->Errors->Clear();
        $this->act_CodAnterior->Errors->Clear();
        $errors .=$this->RowErrors->ToString();
        $this->RowsErrors[$RowNumber] = $errors;
        return $errors ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @2-723E97E7
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["act_Abreviatura"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_Descripcion1"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_UniMedida"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_NumSerie"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_CodAnterior"][$RowNumber]));
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

//Operation Method @2-6A172129
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
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @2-2447CC40
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["act_CodAuxiliar"] = $this->CachedColumns["act_CodAuxiliar"][$RowNumber];
            $this->act_Abreviatura->SetText($this->FormParameters["act_Abreviatura"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->act_Descripcion1->SetText($this->FormParameters["act_Descripcion1"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->act_NumSerie->SetText($this->FormParameters["act_NumSerie"][$RowNumber], $RowNumber);
            $this->act_CodAnterior->SetText($this->FormParameters["act_CodAnterior"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->UpdateAllowed) { $Validation = ($this->UpdateRow($RowNumber) && $Validation); }
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

//UpdateRow Method @2-88900C04
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->act_NumSerie->SetValue($this->act_NumSerie->GetValue());
        $this->ds->act_CodAnterior->SetValue($this->act_CodAnterior->GetValue());
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

//FormScript Method @2-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-8B421F8A
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
                $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-2B657901
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["act_CodAuxiliar"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-F0CD01ED
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
                    $this->act_CodAuxiliar->SetValue($this->ds->act_CodAuxiliar->GetValue());
                } else {
                    $this->act_CodAuxiliar->SetText("");
                }
                if(!$this->FormSubmitted && $is_next_record) {
                    $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = $this->ds->CachedColumns["act_CodAuxiliar"];
                    $this->act_Abreviatura->SetValue($this->ds->act_Abreviatura->GetValue());
                    $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                    $this->act_Descripcion1->SetValue($this->ds->act_Descripcion1->GetValue());
                    $this->act_UniMedida->SetValue($this->ds->act_UniMedida->GetValue());
                    $this->act_NumSerie->SetValue($this->ds->act_NumSerie->GetValue());
                    $this->act_CodAnterior->SetValue($this->ds->act_CodAnterior->GetValue());
                    $this->ValidateRow($RowNumber);
                } else if (!$this->FormSubmitted){
                    $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = "";
                    $this->act_Abreviatura->SetText("");
                    $this->act_Descripcion->SetText("");
                    $this->act_Descripcion1->SetText("");
                    $this->act_UniMedida->SetText("");
                    $this->act_NumSerie->SetText("");
                    $this->act_CodAnterior->SetText("");
                } else {
                    $this->act_Abreviatura->SetText($this->FormParameters["act_Abreviatura"][$RowNumber], $RowNumber);
                    $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
                    $this->act_Descripcion1->SetText($this->FormParameters["act_Descripcion1"][$RowNumber], $RowNumber);
                    $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
                    $this->act_NumSerie->SetText($this->FormParameters["act_NumSerie"][$RowNumber], $RowNumber);
                    $this->act_CodAnterior->SetText($this->FormParameters["act_CodAnterior"][$RowNumber], $RowNumber);
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->act_CodAuxiliar->Show($RowNumber);
                $this->act_Abreviatura->Show($RowNumber);
                $this->act_Descripcion->Show($RowNumber);
                $this->act_Descripcion1->Show($RowNumber);
                $this->act_UniMedida->Show($RowNumber);
                $this->act_NumSerie->Show($RowNumber);
                $this->act_CodAnterior->Show($RowNumber);
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
        $this->Sorter_act_CodAuxiliar->Show();
        $this->Sorter_act_Abreviatura->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_act_Descripcion1->Show();
        $this->Sorter_act_UniMedida->Show();
        $this->Sorter_act_NumSerie->Show();
        $this->Sorter_act_CodAnterior->Show();
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

} //End conactivos Class @2-FCB6E20C

class clsconactivosDataSource extends clsDBdatos {  //conactivosDataSource Class @2-E94B6F92

//DataSource Variables @2-21164F66
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
    var $act_CodAuxiliar;
    var $act_Abreviatura;
    var $act_Descripcion;
    var $act_Descripcion1;
    var $act_UniMedida;
    var $act_NumSerie;
    var $act_CodAnterior;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-D1986413
    function clsconactivosDataSource()
    {
        $this->ErrorBlock = "EditableGrid conactivos/Error";
        $this->Initialize();
        $this->act_CodAuxiliar = new clsField("act_CodAuxiliar", ccsInteger, "");
        $this->act_Abreviatura = new clsField("act_Abreviatura", ccsText, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->act_Descripcion1 = new clsField("act_Descripcion1", ccsText, "");
        $this->act_UniMedida = new clsField("act_UniMedida", ccsInteger, "");
        $this->act_NumSerie = new clsField("act_NumSerie", ccsText, "");
        $this->act_CodAnterior = new clsField("act_CodAnterior", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-27435A5F
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_act_CodAuxiliar" => array("act_CodAuxiliar", ""), 
            "Sorter_act_Abreviatura" => array("act_Abreviatura", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", ""), 
            "Sorter_act_Descripcion1" => array("act_Descripcion1", ""), 
            "Sorter_act_UniMedida" => array("act_UniMedida", ""), 
            "Sorter_act_NumSerie" => array("act_NumSerie", ""), 
            "Sorter_act_CodAnterior" => array("act_CodAnterior", "")));
    }
//End SetOrder Method

//Prepare Method @2-E97A00E0
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_act_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urls_act_CodAuxiliar"], "", false);
        $this->wp->AddParameter("2", "urls_act_Abreviatura", ccsText, "", "", $this->Parameters["urls_act_Abreviatura"], "", false);
        $this->wp->AddParameter("3", "urls_act_Descripcion", ccsText, "", "", $this->Parameters["urls_act_Descripcion"], "", false);
        $this->wp->AddParameter("4", "urls_act_Descripcion1", ccsText, "", "", $this->Parameters["urls_act_Descripcion1"], "", false);
        $this->wp->AddParameter("5", "urls_act_NumSerie", ccsText, "", "", $this->Parameters["urls_act_NumSerie"], "", false);
        $this->wp->AddParameter("6", "urls_act_UniMedida", ccsInteger, "", "", $this->Parameters["urls_act_UniMedida"], "", false);
        $this->wp->AddParameter("7", "urls_act_CodAnterior", ccsText, "", "", $this->Parameters["urls_act_CodAnterior"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "act_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "act_Abreviatura", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "act_Descripcion", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "act_Descripcion1", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "act_NumSerie", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "act_UniMedida", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsInteger),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "act_CodAnterior", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]), 
             $this->wp->Criterion[5]), 
             $this->wp->Criterion[6]), 
             $this->wp->Criterion[7]);
    }
//End Prepare Method

//Open Method @2-A7C1409A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM conactivos";
        $this->SQL = "SELECT act_CodAuxiliar, act_Abreviatura, act_Descripcion, act_Descripcion1, act_NumSerie, act_UniMedida, act_CodAnterior  " .
        "FROM conactivos";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-1F0EA7A0
    function SetValues()
    {
        $this->CachedColumns["act_CodAuxiliar"] = $this->f("act_CodAuxiliar");
        $this->act_CodAuxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->act_Abreviatura->SetDBValue($this->f("act_Abreviatura"));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->act_Descripcion1->SetDBValue($this->f("act_Descripcion1"));
        $this->act_UniMedida->SetDBValue(trim($this->f("act_UniMedida")));
        $this->act_NumSerie->SetDBValue($this->f("act_NumSerie"));
        $this->act_CodAnterior->SetDBValue($this->f("act_CodAnterior"));
    }
//End SetValues Method

//Update Method @2-78B5F88F
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["act_NumSerie"] = new clsSQLParameter("ctrlact_NumSerie", ccsText, "", "", $this->act_NumSerie->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_CodAnterior"] = new clsSQLParameter("ctrlact_CodAnterior", ccsText, "", "", $this->act_CodAnterior->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dsact_CodAuxiliar", ccsInteger, "", "", $this->CachedColumns["act_CodAuxiliar"], "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "act_CodAuxiliar", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = " act_CodAuxiliar = " . $this->CachedColumns["act_CodAuxiliar"];
//             $wp->Criterion[1];
        $this->SQL = "UPDATE conactivos SET "
             . "act_NumSerie=" . $this->ToSQL($this->cp["act_NumSerie"]->GetDBValue(), $this->cp["act_NumSerie"]->DataType) . ", "
             . "act_CodAnterior=" . $this->ToSQL($this->cp["act_CodAnterior"]->GetDBValue(), $this->cp["act_CodAnterior"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End conactivosDataSource Class @2-FCB6E20C

//Initialize Page @1-F5FA39B7
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

$FileName = "CoAdAc_recodif.php";
$Redirect = "";
$TemplateFileName = "CoAdAc_recodif.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-93FDB413
$DBdatos = new clsDBdatos();

// Controls
$conactivosSearch = new clsRecordconactivosSearch();
$conactivos = new clsEditableGridconactivos();
$conactivos->Initialize();

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

//Execute Components @1-FF622BB7
$conactivosSearch->Operation();
$conactivos->Operation();
//End Execute Components

//Go to destination page @1-DEACFEFB
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    unset($conactivosSearch);
    unset($conactivos);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-FBCA75D5
$conactivosSearch->Show();
$conactivos->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$HBOD5R1D2T2P = "<center><font face=\"Arial\"><small>G&#101;&#110;e&#114;ated <!-- CCS -->&#119;it&#104; <!-- CCS -->Co&#100;e&#67;&#104;&#97;rge <!-- CCS -->&#83;t&#117;di&#111;.</small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $HBOD5R1D2T2P . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $HBOD5R1D2T2P . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $HBOD5R1D2T2P;
}
echo $main_block;
//End Show Page

//Unload Page @1-F7BA9F0A
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($conactivosSearch);
unset($conactivos);
unset($Tpl);
//End Unload Page


?>
