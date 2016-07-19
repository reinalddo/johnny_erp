<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @73-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsEditableGridliqreportes { //liqreportes Class @7-213E19BD

//Variables @7-CEA512CA

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
    var $Sorter_rep_PosOrdinal;
    var $Sorter_rub_CodRubro;
    var $Sorter_rep_TitLargo;
    var $Sorter_rep_TitCorto;
    var $Sorter_rep_IndDbCr;
    var $Sorter_rep_Activo;
    var $Navigator;
//End Variables

//Class_Initialize Event @7-F4EDD06A
    function clsEditableGridliqreportes()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid liqreportes/Error";
        $this->ComponentName = "liqreportes";
        $this->CachedColumns["rub_CodRubro"][0] = "rub_CodRubro";
        $this->CachedColumns["rep_ReporteID"][0] = "rep_ReporteID";
        $this->CachedColumns["rep_secuencia"][0] = "rep_secuencia";
        $this->ds = new clsliqreportesDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 15)
            $this->PageSize = 15;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 3;
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

        $this->SorterName = CCGetParam("liqreportesOrder", "");
        $this->SorterDirection = CCGetParam("liqreportesDir", "");

        $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "");
        $this->liqreportes_TotalRecords = new clsControl(ccsLabel, "liqreportes_TotalRecords", "liqreportes_TotalRecords", ccsText, "");
        $this->Sorter_rep_PosOrdinal = new clsSorter($this->ComponentName, "Sorter_rep_PosOrdinal", $FileName);
        $this->Sorter_rub_CodRubro = new clsSorter($this->ComponentName, "Sorter_rub_CodRubro", $FileName);
        $this->Sorter_rep_TitLargo = new clsSorter($this->ComponentName, "Sorter_rep_TitLargo", $FileName);
        $this->Sorter_rep_TitCorto = new clsSorter($this->ComponentName, "Sorter_rep_TitCorto", $FileName);
        $this->Sorter_rep_IndDbCr = new clsSorter($this->ComponentName, "Sorter_rep_IndDbCr", $FileName);
        $this->Sorter_rep_Activo = new clsSorter($this->ComponentName, "Sorter_rep_Activo", $FileName);
        $this->rep_Secuencia = new clsControl(ccsHidden, "rep_Secuencia", "rep_Secuencia", ccsText, "");
        $this->rep_PosOrdinal = new clsControl(ccsTextBox, "rep_PosOrdinal", "Rep Pos Ordinal", ccsInteger, "");
        $this->rub_CodRubro = new clsControl(ccsListBox, "rub_CodRubro", "Rub Cod Rubro", ccsInteger, "");
        $this->rub_CodRubro->DSType = dsTable;
        list($this->rub_CodRubro->BoundColumn, $this->rub_CodRubro->TextColumn, $this->rub_CodRubro->DBFormat) = array("rub_CodRubro", "rub_DescLarga", "");
        $this->rub_CodRubro->ds = new clsDBdatos();
        $this->rub_CodRubro->ds->SQL = "SELECT *  " .
"FROM liqrubros";
        $this->rep_TitLargo = new clsControl(ccsTextBox, "rep_TitLargo", "Rep Tit Largo", ccsText, "");
        $this->rep_TitCorto = new clsControl(ccsTextBox, "rep_TitCorto", "Rep Tit Corto", ccsText, "");
        $this->rep_IndDbCr = new clsControl(ccsListBox, "rep_IndDbCr", "Rep Ind Db Cr", ccsInteger, "");
        $this->rep_IndDbCr->DSType = dsListOfValues;
        $this->rep_IndDbCr->Values = array(array("1", "POSITIVO (DB)"), array("2", "NEGATIVO (CR)"));
        $this->rep_Activo = new clsControl(ccsListBox, "rep_Activo", "Rep Activo", ccsInteger, "");
        $this->rep_Activo->DSType = dsListOfValues;
        $this->rep_Activo->Values = array(array("1", "ACTIVO"), array("2", "INACTIVO"));
        $this->del_Check = new clsControl(ccsCheckBox, "del_Check", "del_Check", ccsBoolean, Array("True", "False", ""));
        $this->del_Check->CheckedValue = true;
        $this->del_Check->UncheckedValue = false;
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
    }
//End Class_Initialize Event

//Initialize Method @7-702E9779
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlrep_ReporteID"] = CCGetFromGet("rep_ReporteID", "");
        $this->ds->Parameters["urlrep_PadreID"] = CCGetFromGet("rep_PadreID", "");
    }
//End Initialize Method

//GetFormParameters Method @7-54759AD7
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["rep_Secuencia"][$RowNumber] = CCGetFromPost("rep_Secuencia_" . $RowNumber);
            $this->FormParameters["rep_PosOrdinal"][$RowNumber] = CCGetFromPost("rep_PosOrdinal_" . $RowNumber);
            $this->FormParameters["rub_CodRubro"][$RowNumber] = CCGetFromPost("rub_CodRubro_" . $RowNumber);
            $this->FormParameters["rep_TitLargo"][$RowNumber] = CCGetFromPost("rep_TitLargo_" . $RowNumber);
            $this->FormParameters["rep_TitCorto"][$RowNumber] = CCGetFromPost("rep_TitCorto_" . $RowNumber);
            $this->FormParameters["rep_IndDbCr"][$RowNumber] = CCGetFromPost("rep_IndDbCr_" . $RowNumber);
            $this->FormParameters["rep_Activo"][$RowNumber] = CCGetFromPost("rep_Activo_" . $RowNumber);
            $this->FormParameters["del_Check"][$RowNumber] = CCGetFromPost("del_Check_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @7-34370EDB
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["rub_CodRubro"] = $this->CachedColumns["rub_CodRubro"][$RowNumber];
            $this->ds->CachedColumns["rep_ReporteID"] = $this->CachedColumns["rep_ReporteID"][$RowNumber];
            $this->ds->CachedColumns["rep_secuencia"] = $this->CachedColumns["rep_secuencia"][$RowNumber];
            $this->rep_Secuencia->SetText($this->FormParameters["rep_Secuencia"][$RowNumber], $RowNumber);
            $this->rep_PosOrdinal->SetText($this->FormParameters["rep_PosOrdinal"][$RowNumber], $RowNumber);
            $this->rub_CodRubro->SetText($this->FormParameters["rub_CodRubro"][$RowNumber], $RowNumber);
            $this->rep_TitLargo->SetText($this->FormParameters["rep_TitLargo"][$RowNumber], $RowNumber);
            $this->rep_TitCorto->SetText($this->FormParameters["rep_TitCorto"][$RowNumber], $RowNumber);
            $this->rep_IndDbCr->SetText($this->FormParameters["rep_IndDbCr"][$RowNumber], $RowNumber);
            $this->rep_Activo->SetText($this->FormParameters["rep_Activo"][$RowNumber], $RowNumber);
            $this->del_Check->SetText($this->FormParameters["del_Check"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if(!$this->del_Check->Value)
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

//ValidateRow Method @7-1D0832D8
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->rep_Secuencia->Validate() && $Validation);
        $Validation = ($this->rep_PosOrdinal->Validate() && $Validation);
        $Validation = ($this->rub_CodRubro->Validate() && $Validation);
        $Validation = ($this->rep_TitLargo->Validate() && $Validation);
        $Validation = ($this->rep_TitCorto->Validate() && $Validation);
        $Validation = ($this->rep_IndDbCr->Validate() && $Validation);
        $Validation = ($this->rep_Activo->Validate() && $Validation);
        $Validation = ($this->del_Check->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->rep_Secuencia->Errors->ToString();
            $errors .= $this->rep_PosOrdinal->Errors->ToString();
            $errors .= $this->rub_CodRubro->Errors->ToString();
            $errors .= $this->rep_TitLargo->Errors->ToString();
            $errors .= $this->rep_TitCorto->Errors->ToString();
            $errors .= $this->rep_IndDbCr->Errors->ToString();
            $errors .= $this->rep_Activo->Errors->ToString();
            $errors .= $this->del_Check->Errors->ToString();
            $this->rep_Secuencia->Errors->Clear();
            $this->rep_PosOrdinal->Errors->Clear();
            $this->rub_CodRubro->Errors->Clear();
            $this->rep_TitLargo->Errors->Clear();
            $this->rep_TitCorto->Errors->Clear();
            $this->rep_IndDbCr->Errors->Clear();
            $this->rep_Activo->Errors->Clear();
            $this->del_Check->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @7-ADB80DC8
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["rep_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rep_PosOrdinal"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rub_CodRubro"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rep_TitLargo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rep_TitCorto"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rep_IndDbCr"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rep_Activo"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @7-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @7-6A172129
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

//UpdateGrid Method @7-839D27D9
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["rub_CodRubro"] = $this->CachedColumns["rub_CodRubro"][$RowNumber];
            $this->ds->CachedColumns["rep_ReporteID"] = $this->CachedColumns["rep_ReporteID"][$RowNumber];
            $this->ds->CachedColumns["rep_secuencia"] = $this->CachedColumns["rep_secuencia"][$RowNumber];
            $this->rep_Secuencia->SetText($this->FormParameters["rep_Secuencia"][$RowNumber], $RowNumber);
            $this->rep_PosOrdinal->SetText($this->FormParameters["rep_PosOrdinal"][$RowNumber], $RowNumber);
            $this->rub_CodRubro->SetText($this->FormParameters["rub_CodRubro"][$RowNumber], $RowNumber);
            $this->rep_TitLargo->SetText($this->FormParameters["rep_TitLargo"][$RowNumber], $RowNumber);
            $this->rep_TitCorto->SetText($this->FormParameters["rep_TitCorto"][$RowNumber], $RowNumber);
            $this->rep_IndDbCr->SetText($this->FormParameters["rep_IndDbCr"][$RowNumber], $RowNumber);
            $this->rep_Activo->SetText($this->FormParameters["rep_Activo"][$RowNumber], $RowNumber);
            $this->del_Check->SetText($this->FormParameters["del_Check"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->del_Check->Value) {
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

//InsertRow Method @7-378DDCEA
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->rub_CodRubro->SetValue($this->rub_CodRubro->GetValue());
        $this->ds->rep_TitCorto->SetValue($this->rep_TitCorto->GetValue());
        $this->ds->rep_TitLargo->SetValue($this->rep_TitLargo->GetValue());
        $this->ds->rep_PosOrdinal->SetValue($this->rep_PosOrdinal->GetValue());
        $this->ds->rep_IndDbCr->SetValue($this->rep_IndDbCr->GetValue());
        $this->ds->rep_Activo->SetValue($this->rep_Activo->GetValue());
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

//UpdateRow Method @7-1AA17C92
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->rub_CodRubro->SetValue($this->rub_CodRubro->GetValue());
        $this->ds->rep_TitCorto->SetValue($this->rep_TitCorto->GetValue());
        $this->ds->rep_TitLargo->SetValue($this->rep_TitLargo->GetValue());
        $this->ds->rep_PosOrdinal->SetValue($this->rep_PosOrdinal->GetValue());
        $this->ds->rep_IndDbCr->SetValue($this->rep_IndDbCr->GetValue());
        $this->ds->rep_Activo->SetValue($this->rep_Activo->GetValue());
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

//DeleteRow Method @7-0C9DDC34
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

//FormScript Method @7-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @7-9857FD32
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 3)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["rub_CodRubro"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["rep_ReporteID"][$RowNumber] = $piece;
                $piece = $pieces[$i + 2];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["rep_secuencia"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["rub_CodRubro"][$RowNumber] = "";
                $this->CachedColumns["rep_ReporteID"][$RowNumber] = "";
                $this->CachedColumns["rep_secuencia"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @7-6C9578F8
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["rub_CodRubro"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["rep_ReporteID"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["rep_secuencia"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @7-425960FF
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->rub_CodRubro->Prepare();
        $this->rep_IndDbCr->Prepare();
        $this->rep_Activo->Prepare();

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
                    } else {
                    }
                    if(!$is_next_record || !$this->DeleteAllowed)
                        $this->del_Check->Visible = false;
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["rub_CodRubro"][$RowNumber] = $this->ds->CachedColumns["rub_CodRubro"];
                        $this->CachedColumns["rep_ReporteID"][$RowNumber] = $this->ds->CachedColumns["rep_ReporteID"];
                        $this->CachedColumns["rep_secuencia"][$RowNumber] = $this->ds->CachedColumns["rep_secuencia"];
                        $this->rep_Secuencia->SetValue($this->ds->rep_Secuencia->GetValue());
                        $this->rep_PosOrdinal->SetValue($this->ds->rep_PosOrdinal->GetValue());
                        $this->rub_CodRubro->SetValue($this->ds->rub_CodRubro->GetValue());
                        $this->rep_TitLargo->SetValue($this->ds->rep_TitLargo->GetValue());
                        $this->rep_TitCorto->SetValue($this->ds->rep_TitCorto->GetValue());
                        $this->rep_IndDbCr->SetValue($this->ds->rep_IndDbCr->GetValue());
                        $this->rep_Activo->SetValue($this->ds->rep_Activo->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["rub_CodRubro"][$RowNumber] = "";
                        $this->CachedColumns["rep_ReporteID"][$RowNumber] = "";
                        $this->CachedColumns["rep_secuencia"][$RowNumber] = "";
                        $this->rep_Secuencia->SetText("");
                        $this->rep_PosOrdinal->SetText("");
                        $this->rub_CodRubro->SetText("");
                        $this->rep_TitLargo->SetText("");
                        $this->rep_TitCorto->SetText("");
                        $this->rep_IndDbCr->SetText("");
                        $this->rep_Activo->SetText("");
                        $this->del_Check->SetText("");
                    } else {
                        $this->rep_Secuencia->SetText($this->FormParameters["rep_Secuencia"][$RowNumber], $RowNumber);
                        $this->rep_PosOrdinal->SetText($this->FormParameters["rep_PosOrdinal"][$RowNumber], $RowNumber);
                        $this->rub_CodRubro->SetText($this->FormParameters["rub_CodRubro"][$RowNumber], $RowNumber);
                        $this->rep_TitLargo->SetText($this->FormParameters["rep_TitLargo"][$RowNumber], $RowNumber);
                        $this->rep_TitCorto->SetText($this->FormParameters["rep_TitCorto"][$RowNumber], $RowNumber);
                        $this->rep_IndDbCr->SetText($this->FormParameters["rep_IndDbCr"][$RowNumber], $RowNumber);
                        $this->rep_Activo->SetText($this->FormParameters["rep_Activo"][$RowNumber], $RowNumber);
                        $this->del_Check->SetText($this->FormParameters["del_Check"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->rep_Secuencia->Show($RowNumber);
                    $this->rep_PosOrdinal->Show($RowNumber);
                    $this->rub_CodRubro->Show($RowNumber);
                    $this->rep_TitLargo->Show($RowNumber);
                    $this->rep_TitCorto->Show($RowNumber);
                    $this->rep_IndDbCr->Show($RowNumber);
                    $this->rep_Activo->Show($RowNumber);
                    $this->del_Check->Show($RowNumber);
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
        if(!is_array($this->lbTitulo->Value) && !strlen($this->lbTitulo->Value) && $this->lbTitulo->Value !== false)
        $this->lbTitulo->SetText('DETALLE');
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->lbTitulo->Show();
        $this->liqreportes_TotalRecords->Show();
        $this->Sorter_rep_PosOrdinal->Show();
        $this->Sorter_rub_CodRubro->Show();
        $this->Sorter_rep_TitLargo->Show();
        $this->Sorter_rep_TitCorto->Show();
        $this->Sorter_rep_IndDbCr->Show();
        $this->Sorter_rep_Activo->Show();
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

} //End liqreportes Class @7-FCB6E20C

class clsliqreportesDataSource extends clsDBdatos {  //liqreportesDataSource Class @7-6E037E32

//DataSource Variables @7-22AE2CE7
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
    var $rep_Secuencia;
    var $rep_PosOrdinal;
    var $rub_CodRubro;
    var $rep_TitLargo;
    var $rep_TitCorto;
    var $rep_IndDbCr;
    var $rep_Activo;
    var $del_Check;
//End DataSource Variables

//Class_Initialize Event @7-AB55265E
    function clsliqreportesDataSource()
    {
        $this->ErrorBlock = "EditableGrid liqreportes/Error";
        $this->Initialize();
        $this->rep_Secuencia = new clsField("rep_Secuencia", ccsText, "");
        $this->rep_PosOrdinal = new clsField("rep_PosOrdinal", ccsInteger, "");
        $this->rub_CodRubro = new clsField("rub_CodRubro", ccsInteger, "");
        $this->rep_TitLargo = new clsField("rep_TitLargo", ccsText, "");
        $this->rep_TitCorto = new clsField("rep_TitCorto", ccsText, "");
        $this->rep_IndDbCr = new clsField("rep_IndDbCr", ccsInteger, "");
        $this->rep_Activo = new clsField("rep_Activo", ccsInteger, "");
        $this->del_Check = new clsField("del_Check", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @7-91177B1B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "rep_PadreID, rep_PosOrdinal";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_rep_PosOrdinal" => array("rep_PosOrdinal", ""), 
            "Sorter_rub_CodRubro" => array("rub_CodRubro", ""), 
            "Sorter_rep_TitLargo" => array("rep_TitLargo", ""), 
            "Sorter_rep_TitCorto" => array("rep_TitCorto", ""), 
            "Sorter_rep_IndDbCr" => array("rep_IndDbCr", ""), 
            "Sorter_rep_Activo" => array("rep_Activo", "")));
    }
//End SetOrder Method

//Prepare Method @7-1472A001
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlrep_ReporteID", ccsInteger, "", "", $this->Parameters["urlrep_ReporteID"], "", false);
        $this->wp->AddParameter("2", "urlrep_PadreID", ccsInteger, "", "", $this->Parameters["urlrep_PadreID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "rep_ReporteID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "rep_PadreID", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @7-80611EB8
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM liqreportes";
        $this->SQL = "SELECT *  " .
        "FROM liqreportes";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-7861594C
    function SetValues()
    {
        $this->CachedColumns["rub_CodRubro"] = $this->f("rub_CodRubro");
        $this->CachedColumns["rep_ReporteID"] = $this->f("rep_ReporteID");
        $this->CachedColumns["rep_secuencia"] = $this->f("rep_secuencia");
        $this->rep_Secuencia->SetDBValue($this->f("rep_secuencia"));
        $this->rep_PosOrdinal->SetDBValue(trim($this->f("rep_PosOrdinal")));
        $this->rub_CodRubro->SetDBValue(trim($this->f("rep_CodRubro")));
        $this->rep_TitLargo->SetDBValue($this->f("rep_TitLargo"));
        $this->rep_TitCorto->SetDBValue($this->f("rep_TitCorto"));
        $this->rep_IndDbCr->SetDBValue(trim($this->f("rep_IndDbCr")));
        $this->rep_Activo->SetDBValue(trim($this->f("rep_Activo")));
    }
//End SetValues Method

//Insert Method @7-0F2DCA25
    function Insert()
    {
        $this->cp["rep_ReporteID"] = new clsSQLParameter("urlrep_ReporteID", ccsInteger, "", "", CCGetFromGet("rep_ReporteID", ""), "", false, $this->ErrorBlock);
        $this->cp["rep_Nivel"] = new clsSQLParameter("urlrep_Nivel", ccsInteger, "", "", CCGetFromGet("rep_Nivel", ""), "", false, $this->ErrorBlock);
        $this->cp["rep_PadreID"] = new clsSQLParameter("urlrep_PadreID", ccsText, "", "", CCGetFromGet("rep_PadreID", ""), "", false, $this->ErrorBlock);
        $this->cp["rep_CodRubro"] = new clsSQLParameter("ctrlrub_CodRubro", ccsInteger, "", "", $this->rub_CodRubro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rep_TitCorto"] = new clsSQLParameter("ctrlrep_TitCorto", ccsText, "", "", $this->rep_TitCorto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rep_TitLargo"] = new clsSQLParameter("ctrlrep_TitLargo", ccsText, "", "", $this->rep_TitLargo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rep_PosOrdinal"] = new clsSQLParameter("ctrlrep_PosOrdinal", ccsInteger, "", "", $this->rep_PosOrdinal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rep_IndDbCr"] = new clsSQLParameter("ctrlrep_IndDbCr", ccsInteger, "", "", $this->rep_IndDbCr->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rep_Activo"] = new clsSQLParameter("ctrlrep_Activo", ccsInteger, "", "", $this->rep_Activo->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqreportes ("
             . "rep_ReporteID, "
             . "rep_Nivel, "
             . "rep_PadreID, "
             . "rep_CodRubro, "
             . "rep_TitCorto, "
             . "rep_TitLargo, "
             . "rep_PosOrdinal, "
             . "rep_IndDbCr, "
             . "rep_Activo"
             . ") VALUES ("
             . $this->ToSQL($this->cp["rep_ReporteID"]->GetDBValue(), $this->cp["rep_ReporteID"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_Nivel"]->GetDBValue(), $this->cp["rep_Nivel"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_PadreID"]->GetDBValue(), $this->cp["rep_PadreID"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_CodRubro"]->GetDBValue(), $this->cp["rep_CodRubro"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_TitCorto"]->GetDBValue(), $this->cp["rep_TitCorto"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_TitLargo"]->GetDBValue(), $this->cp["rep_TitLargo"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_PosOrdinal"]->GetDBValue(), $this->cp["rep_PosOrdinal"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_IndDbCr"]->GetDBValue(), $this->cp["rep_IndDbCr"]->DataType) . ", "
             . $this->ToSQL($this->cp["rep_Activo"]->GetDBValue(), $this->cp["rep_Activo"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @7-5C65ED40
    function Update()
    {
        $this->cp["rep_CodRubro"] = new clsSQLParameter("ctrlrub_CodRubro", ccsInteger, "", "", $this->rub_CodRubro->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["rep_TitCorto"] = new clsSQLParameter("ctrlrep_TitCorto", ccsText, "", "", $this->rep_TitCorto->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["rep_TitLargo"] = new clsSQLParameter("ctrlrep_TitLargo", ccsText, "", "", $this->rep_TitLargo->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["rep_PosOrdinal"] = new clsSQLParameter("ctrlrep_PosOrdinal", ccsInteger, "", "", $this->rep_PosOrdinal->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["rep_IndDbCr"] = new clsSQLParameter("ctrlrep_IndDbCr", ccsInteger, "", "", $this->rep_IndDbCr->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["rep_Activo"] = new clsSQLParameter("ctrlrep_Activo", ccsInteger, "", "", $this->rep_Activo->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["rep_Nivel"] = new clsSQLParameter("urlrep_Nivel", ccsInteger, "", "", CCGetFromGet("rep_Nivel", ""), 1, false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dsrep_ReporteID", ccsInteger, "", "", $this->CachedColumns["rep_ReporteID"], "", false);
        $wp->AddParameter("2", "dsrep_secuencia", ccsInteger, "", "", $this->CachedColumns["rep_secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "rep_ReporteID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "rep_secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE liqreportes SET "
             . "rep_CodRubro=" . $this->ToSQL($this->cp["rep_CodRubro"]->GetDBValue(), $this->cp["rep_CodRubro"]->DataType) . ", "
             . "rep_TitCorto=" . $this->ToSQL($this->cp["rep_TitCorto"]->GetDBValue(), $this->cp["rep_TitCorto"]->DataType) . ", "
             . "rep_TitLargo=" . $this->ToSQL($this->cp["rep_TitLargo"]->GetDBValue(), $this->cp["rep_TitLargo"]->DataType) . ", "
             . "rep_PosOrdinal=" . $this->ToSQL($this->cp["rep_PosOrdinal"]->GetDBValue(), $this->cp["rep_PosOrdinal"]->DataType) . ", "
             . "rep_IndDbCr=" . $this->ToSQL($this->cp["rep_IndDbCr"]->GetDBValue(), $this->cp["rep_IndDbCr"]->DataType) . ", "
             . "rep_Activo=" . $this->ToSQL($this->cp["rep_Activo"]->GetDBValue(), $this->cp["rep_Activo"]->DataType) . ", "
             . "rep_Nivel=" . $this->ToSQL($this->cp["rep_Nivel"]->GetDBValue(), $this->cp["rep_Nivel"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @7-82E2F90E
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dsrep_ReporteID", ccsInteger, "", "", $this->CachedColumns["rep_ReporteID"], "", false);
        $wp->AddParameter("2", "dsrep_secuencia", ccsInteger, "", "", $this->CachedColumns["rep_secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "rep_ReporteID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "rep_secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->Criterion[2];
        $this->SQL = "DELETE FROM liqreportes";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqreportesDataSource Class @7-FCB6E20C

//Initialize Page @1-6A19EB7E
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

$FileName = "LiLiRp_confdet.php";
$Redirect = "";
$TemplateFileName = "LiLiRp_confdet.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-CD03D175
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqreportes = new clsEditableGridliqreportes();
$liqreportes->Initialize();

// Events
include("./LiLiRp_confdet_events.php");
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

//Execute Components @1-01E1DCEA
$Cabecera->Operations();
$liqreportes->Operation();
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

//Show Page @1-206905E0
$Cabecera->Show("Cabecera");
$liqreportes->Show();
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
