<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @278-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordinvpreciosSearch { //invpreciosSearch Class @232-04768498

//Variables @232-CB19EB75

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

//Class_Initialize Event @232-78A07AD3
    function clsRecordinvpreciosSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record invpreciosSearch/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "invpreciosSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            var_dump($CCSForm);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_LisPrecios = new clsControl(ccsListBox, "s_LisPrecios", "LISTA DE PRECIOS", ccsInteger, "", CCGetRequestParam("s_LisPrecios", $Method));
            $this->s_LisPrecios->DSType = dsTable;
            list($this->s_LisPrecios->BoundColumn, $this->s_LisPrecios->TextColumn, $this->s_LisPrecios->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_LisPrecios->ds = new clsDBdatos();
            $this->s_LisPrecios->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_LisPrecios->ds->Parameters["expr235"] = 'ITRLIS';
            $this->s_LisPrecios->ds->wp = new clsSQLParameters();
            $this->s_LisPrecios->ds->wp->AddParameter("1", "expr235", ccsText, "", "", $this->s_LisPrecios->ds->Parameters["expr235"], "", false);
            $this->s_LisPrecios->ds->wp->Criterion[1] = $this->s_LisPrecios->ds->wp->Operation(opEqual, "par_Clave", $this->s_LisPrecios->ds->wp->GetDBValue("1"), $this->s_LisPrecios->ds->ToSQL($this->s_LisPrecios->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_LisPrecios->ds->Where = $this->s_LisPrecios->ds->wp->Criterion[1];
            $this->s_LisPrecios->Required = true;
            $this->s_TipoItem = new clsControl(ccsRadioButton, "s_TipoItem", "s_TipoItem", ccsInteger, "", CCGetRequestParam("s_TipoItem", $Method));
            $this->s_TipoItem->DSType = dsTable;
            list($this->s_TipoItem->BoundColumn, $this->s_TipoItem->TextColumn, $this->s_TipoItem->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_TipoItem->ds = new clsDBdatos();
            $this->s_TipoItem->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_TipoItem->ds->Parameters["expr239"] = 'CAUTI';
            $this->s_TipoItem->ds->Parameters["expr242"] = 'Activo';
            $this->s_TipoItem->ds->wp = new clsSQLParameters();
            $this->s_TipoItem->ds->wp->AddParameter("1", "expr239", ccsText, "", "", $this->s_TipoItem->ds->Parameters["expr239"], "", false);
            $this->s_TipoItem->ds->wp->AddParameter("2", "expr242", ccsText, "", "", $this->s_TipoItem->ds->Parameters["expr242"], "", false);
            $this->s_TipoItem->ds->wp->Criterion[1] = $this->s_TipoItem->ds->wp->Operation(opEqual, "par_Clave", $this->s_TipoItem->ds->wp->GetDBValue("1"), $this->s_TipoItem->ds->ToSQL($this->s_TipoItem->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_TipoItem->ds->wp->Criterion[2] = $this->s_TipoItem->ds->wp->Operation(opEqual, "par_Valor1", $this->s_TipoItem->ds->wp->GetDBValue("2"), $this->s_TipoItem->ds->ToSQL($this->s_TipoItem->ds->wp->GetDBValue("2"), ccsText),false);
            $this->s_TipoItem->ds->Where = $this->s_TipoItem->ds->wp->opAND(false, $this->s_TipoItem->ds->wp->Criterion[1], $this->s_TipoItem->ds->wp->Criterion[2]);
            $this->s_Grupo = new clsControl(ccsRadioButton, "s_Grupo", "s_Grupo", ccsText, "", CCGetRequestParam("s_Grupo", $Method));
            $this->s_Grupo->DSType = dsTable;
            list($this->s_Grupo->BoundColumn, $this->s_Grupo->TextColumn, $this->s_Grupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_Grupo->ds = new clsDBdatos();
            $this->s_Grupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_Grupo->ds->Parameters["expr273"] = 'ACTGRU';
            $this->s_Grupo->ds->wp = new clsSQLParameters();
            $this->s_Grupo->ds->wp->AddParameter("1", "expr273", ccsText, "", "", $this->s_Grupo->ds->Parameters["expr273"], "", false);
            $this->s_Grupo->ds->wp->Criterion[1] = $this->s_Grupo->ds->wp->Operation(opEqual, "par_Clave", $this->s_Grupo->ds->wp->GetDBValue("1"), $this->s_Grupo->ds->ToSQL($this->s_Grupo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_Grupo->ds->Where = $this->s_Grupo->ds->wp->Criterion[1];
            $this->s_Grupo->HTML = true;
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_TipoItem->Value) && !strlen($this->s_TipoItem->Value) && $this->s_TipoItem->Value !== false)
                $this->s_TipoItem->SetText(30);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @232-715FB007
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_LisPrecios->Validate() && $Validation);
        $Validation = ($this->s_TipoItem->Validate() && $Validation);
        $Validation = ($this->s_Grupo->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @232-32C670AE
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_LisPrecios->Errors->Count());
        $errors = ($errors || $this->s_TipoItem->Errors->Count());
        $errors = ($errors || $this->s_Grupo->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @232-0B8C9F5C
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
        $Redirect = "LiAdLp_mant.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdLp_mant.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @232-DFE33919
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_LisPrecios->Prepare();
        $this->s_TipoItem->Prepare();
        $this->s_Grupo->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_LisPrecios->Errors->ToString();
            $Error .= $this->s_TipoItem->Errors->ToString();
            $Error .= $this->s_Grupo->Errors->ToString();
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

        $this->s_LisPrecios->Show();
        $this->s_TipoItem->Show();
        $this->s_Grupo->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End invpreciosSearch Class @232-FCB6E20C

class clsEditableGridlispredetal { //lispredetal Class @2-3AF1774E

//Variables @2-F06B977C

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
    var $Navigator1;
    var $Sorter_pre_CodItem;
    var $Sorter_act_Descripcion;
    var $Sorter_uni_Abreviatura;
    var $Sorter_pre_PreUnitario;
//End Variables

//Class_Initialize Event @2-D8F59DC6
    function clsEditableGridlispredetal()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid lispredetal/Error";
        $this->ComponentName = "lispredetal";
        $this->ds = new clslispredetalDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 20)
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 3;
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

        $this->SorterName = CCGetParam("lispredetalOrder", "");
        $this->SorterDirection = CCGetParam("lispredetalDir", "");

        $this->conactivos_invprecios_gen_TotalRecords = new clsControl(ccsLabel, "conactivos_invprecios_gen_TotalRecords", "conactivos_invprecios_gen_TotalRecords", ccsText, "");
        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
        $this->Sorter_pre_CodItem = new clsSorter($this->ComponentName, "Sorter_pre_CodItem", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_uni_Abreviatura = new clsSorter($this->ComponentName, "Sorter_uni_Abreviatura", $FileName);
        $this->Sorter_pre_PreUnitario = new clsSorter($this->ComponentName, "Sorter_pre_PreUnitario", $FileName);
        $this->pre_CodItem = new clsControl(ccsTextBox, "pre_CodItem", "Pre Cod Item", ccsInteger, "");
        $this->pre_LisPrecios = new clsControl(ccsHidden, "pre_LisPrecios", "Pre Lis Precios", ccsInteger, "");
        $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Descripci�n", ccsText, "");
        $this->act_Descripcion->Required = true;
        $this->uni_Abreviatura = new clsControl(ccsTextBox, "uni_Abreviatura", "Uni Abreviatura", ccsText, "");
        $this->pre_PreUnitario = new clsControl(ccsTextBox, "pre_PreUnitario", "PRECIO UNITARIO", ccsFloat, Array(False, 4, ".", ",", False, "", "", 1, True, ""));
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
    }
//End Class_Initialize Event

//Initialize Method @2-396EE9CE
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urls_LisPrecios"] = CCGetFromGet("s_LisPrecios", "");
        $this->ds->Parameters["expr246"] = NULL;
        $this->ds->Parameters["urls_TipoItem"] = CCGetFromGet("s_TipoItem", "");
        $this->ds->Parameters["urls_Grupo"] = CCGetFromGet("s_Grupo", "");
    }
//End Initialize Method

//GetFormParameters Method @2-9D3B31A9
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["pre_CodItem"][$RowNumber] = CCGetFromPost("pre_CodItem_" . $RowNumber);
            $this->FormParameters["pre_LisPrecios"][$RowNumber] = CCGetFromPost("pre_LisPrecios_" . $RowNumber);
            $this->FormParameters["act_Descripcion"][$RowNumber] = CCGetFromPost("act_Descripcion_" . $RowNumber);
            $this->FormParameters["uni_Abreviatura"][$RowNumber] = CCGetFromPost("uni_Abreviatura_" . $RowNumber);
            $this->FormParameters["pre_PreUnitario"][$RowNumber] = CCGetFromPost("pre_PreUnitario_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-E5F15CD9
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->pre_CodItem->SetText($this->FormParameters["pre_CodItem"][$RowNumber], $RowNumber);
            $this->pre_LisPrecios->SetText($this->FormParameters["pre_LisPrecios"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
            $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-9E789134
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->pre_CodItem->Validate() && $Validation);
        $Validation = ($this->pre_LisPrecios->Validate() && $Validation);
        $Validation = ($this->act_Descripcion->Validate() && $Validation);
        $Validation = ($this->uni_Abreviatura->Validate() && $Validation);
        $Validation = ($this->pre_PreUnitario->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->pre_CodItem->Errors->ToString();
            $errors .= $this->pre_LisPrecios->Errors->ToString();
            $errors .= $this->act_Descripcion->Errors->ToString();
            $errors .= $this->uni_Abreviatura->Errors->ToString();
            $errors .= $this->pre_PreUnitario->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->pre_CodItem->Errors->Clear();
            $this->pre_LisPrecios->Errors->Clear();
            $this->act_Descripcion->Errors->Clear();
            $this->uni_Abreviatura->Errors->Clear();
            $this->pre_PreUnitario->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @2-80A67738
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["pre_CodItem"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_LisPrecios"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["uni_Abreviatura"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_PreUnitario"][$RowNumber]));
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

//UpdateGrid Method @2-554CA4FD
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->pre_CodItem->SetText($this->FormParameters["pre_CodItem"][$RowNumber], $RowNumber);
            $this->pre_LisPrecios->SetText($this->FormParameters["pre_LisPrecios"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
            $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
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

//UpdateRow Method @2-E8D78F2E
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->pre_CodItem->SetValue($this->pre_CodItem->GetValue());
        $this->ds->pre_PreUnitario->SetValue($this->pre_PreUnitario->GetValue());
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

//FormScript Method @2-4A67764C
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var lispredetalElements;\n";
        $script .= "var lispredetalEmptyRows = 3;\n";
        $script .= "var " . $this->ComponentName . "pre_CodItemID = 0;\n";
        $script .= "var " . $this->ComponentName . "pre_LisPreciosID = 1;\n";
        $script .= "var " . $this->ComponentName . "act_DescripcionID = 2;\n";
        $script .= "var " . $this->ComponentName . "uni_AbreviaturaID = 3;\n";
        $script .= "var " . $this->ComponentName . "pre_PreUnitarioID = 4;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 5;\n";
        $script .= "\nfunction initlispredetalElements() {\n";
        $script .= "\tvar ED = document.forms[\"lispredetal\"];\n";
        $script .= "\tlispredetalElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.pre_CodItem_" . $i . ", " . "ED.pre_LisPrecios_" . $i . ", " . "ED.act_Descripcion_" . $i . ", " . "ED.uni_Abreviatura_" . $i . ", " . "ED.pre_PreUnitario_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-69E01441
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 0)  {
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-BF9CEBD0
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-0EF29752
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
                        $this->CheckBox_Delete->Visible = false;
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->pre_CodItem->SetValue($this->ds->pre_CodItem->GetValue());
                        $this->pre_LisPrecios->SetValue($this->ds->pre_LisPrecios->GetValue());
                        $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                        $this->uni_Abreviatura->SetValue($this->ds->uni_Abreviatura->GetValue());
                        $this->pre_PreUnitario->SetValue($this->ds->pre_PreUnitario->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->pre_CodItem->SetText("");
                        $this->pre_LisPrecios->SetText("");
                        $this->act_Descripcion->SetText("");
                        $this->uni_Abreviatura->SetText("");
                        $this->pre_PreUnitario->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->pre_CodItem->SetText($this->FormParameters["pre_CodItem"][$RowNumber], $RowNumber);
                        $this->pre_LisPrecios->SetText($this->FormParameters["pre_LisPrecios"][$RowNumber], $RowNumber);
                        $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
                        $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
                        $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->pre_CodItem->Show($RowNumber);
                    $this->pre_LisPrecios->Show($RowNumber);
                    $this->act_Descripcion->Show($RowNumber);
                    $this->uni_Abreviatura->Show($RowNumber);
                    $this->pre_PreUnitario->Show($RowNumber);
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
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->conactivos_invprecios_gen_TotalRecords->Show();
        $this->Navigator1->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();
        $this->Sorter_pre_CodItem->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_uni_Abreviatura->Show();
        $this->Sorter_pre_PreUnitario->Show();

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

} //End lispredetal Class @2-FCB6E20C

class clslispredetalDataSource extends clsDBdatos {  //lispredetalDataSource Class @2-23435D11

//DataSource Variables @2-CA1F868A
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $pre_CodItem;
    var $pre_LisPrecios;
    var $act_Descripcion;
    var $uni_Abreviatura;
    var $pre_PreUnitario;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @2-AE3695D4
    function clslispredetalDataSource()
    {
        $this->ErrorBlock = "EditableGrid lispredetal/Error";
        $this->Initialize();
        $this->pre_CodItem = new clsField("pre_CodItem", ccsInteger, "");
        $this->pre_LisPrecios = new clsField("pre_LisPrecios", ccsInteger, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->uni_Abreviatura = new clsField("uni_Abreviatura", ccsText, "");
        $this->pre_PreUnitario = new clsField("pre_PreUnitario", ccsFloat, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @2-D334D8F1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "act_Descripcion, act_Descripcion1";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_pre_CodItem" => array("pre_CodItem", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", ""), 
            "Sorter_uni_Abreviatura" => array("uni_Abreviatura", ""), 
            "Sorter_pre_PreUnitario" => array("pre_PreUnitario", "")));
    }
//End SetOrder Method

//Prepare Method @2-514F7AB5
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_LisPrecios", ccsInteger, "", "", $this->Parameters["urls_LisPrecios"], "", true);
        $this->wp->AddParameter("2", "expr246", ccsInteger, "", "", $this->Parameters["expr246"], "", true);
        $this->wp->AddParameter("3", "urls_TipoItem", ccsInteger, "", "", $this->Parameters["urls_TipoItem"], "", false);
        $this->wp->AddParameter("4", "urls_Grupo", ccsInteger, "", "", $this->Parameters["urls_Grupo"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pre_LisPrecios", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->wp->Criterion[2] = $this->wp->Operation(opIsNull, "pre_LisPrecios", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "cat_Categoria", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "act_Grupo", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opOR(true, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]);
    }
//End Prepare Method

//Open Method @2-93BBCBD7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM ((conactivos LEFT JOIN invprecios ON " .
        "invprecios.pre_CodItem = conactivos.act_CodAuxiliar) LEFT JOIN genunmedida ON " .
        "conactivos.act_UniMedida = genunmedida.uni_CodUnidad) INNER JOIN concategorias ON " .
        "conactivos.act_CodAuxiliar = concategorias.cat_CodAuxiliar";
        $this->SQL = "SELECT act_CodAuxiliar, act_Descripcion, act_Descripcion1, invprecios.*, uni_Descripcion, uni_Abreviatura, cat_Categoria, cat_Activo  " .
        "FROM ((conactivos LEFT JOIN invprecios ON " .
        "invprecios.pre_CodItem = conactivos.act_CodAuxiliar) LEFT JOIN genunmedida ON " .
        "conactivos.act_UniMedida = genunmedida.uni_CodUnidad) INNER JOIN concategorias ON " .
        "conactivos.act_CodAuxiliar = concategorias.cat_CodAuxiliar";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-5A6A7A40
    function SetValues()
    {
        $this->pre_CodItem->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->pre_LisPrecios->SetDBValue(trim($this->f("pre_LisPrecios")));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->uni_Abreviatura->SetDBValue($this->f("uni_Descripcion"));
        $this->pre_PreUnitario->SetDBValue(trim($this->f("pre_PreUnitario")));
    }
//End SetValues Method

//Update Method @2-1951984D
    function Update()
    {
        $this->cp["pre_CodItem"] = new clsSQLParameter("ctrlpre_CodItem", ccsInteger, "", "", $this->pre_CodItem->GetValue(), -1, false, $this->ErrorBlock);
        $this->cp["pre_LisPrecios"] = new clsSQLParameter("urls_LisPrecios", ccsInteger, "", "", CCGetFromGet("s_LisPrecios", ""), -1, false, $this->ErrorBlock);
        $this->cp["pre_PreUnitario"] = new clsSQLParameter("ctrlpre_PreUnitario", ccsText, "", "", $this->pre_PreUnitario->GetValue(), 'NULL', false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "INSERT INTO invprecios(pre_CodItem, pre_LisPrecios, pre_PreUnitario)  " .
        "VALUES(" . $this->SQLValue($this->cp["pre_CodItem"]->GetDBValue(), ccsInteger) . ", " . $this->SQLValue($this->cp["pre_LisPrecios"]->GetDBValue(), ccsInteger) . ", " . $this->SQLValue($this->cp["pre_PreUnitario"]->GetDBValue(), ccsText) . " ) " .
        "ON DUPLICATE KEY UPDATE pre_PreUnitario = VALUES(pre_PreUnitario)";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-BA85C806
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlpre_LisPrecios", ccsInteger, "", "", CCGetFromGet("pre_LisPrecios", ""), "", true);
        $wp->AddParameter("2", "urlpre_CodItem", ccsInteger, "", "", CCGetFromGet("pre_CodItem", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "pre_LisPrecios", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "pre_CodItem", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),true);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM invprecios";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End lispredetalDataSource Class @2-FCB6E20C

//Initialize Page @1-B5617619
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

$FileName = "LiAdLp_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdLp_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-E38343F0
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$invpreciosSearch = new clsRecordinvpreciosSearch();
$lispredetal = new clsEditableGridlispredetal();
$lispredetal->Initialize();

// Events
include("./LiAdLp_mant_events.php");
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

//Execute Components @1-81A974DE
$Cabecera->Operations();
$invpreciosSearch->Operation();
$lispredetal->Operation();
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

//Show Page @1-A8456134
$Cabecera->Show("Cabecera");
$invpreciosSearch->Show();
$lispredetal->Show();
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
