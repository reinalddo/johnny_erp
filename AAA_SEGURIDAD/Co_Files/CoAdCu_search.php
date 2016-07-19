<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
/*
 *  @rev    fah 26/01/01    Acortar los niveles de cuaentas presentados en el resultado de la busqueda
  ***/
  
//End Include Common Files
include_once (RelativePath . "/LibPhp/ValLib.php");
include_once (RelativePath . "/LibPhp/SegLib.php");
Class clsRecordCoAdCu_search { //CoAdCu_search Class @68-5C2EAB44

//Variables @68-CB19EB75

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

//Class_Initialize Event @68-5AD6CA07
    function clsRecordCoAdCu_search()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdCu_search/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdCu_search";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("All", Array("s_keyword", "ccsForm"));
            $this->ClearParameters->Page = "CoAdCu_search.php";
        }
    }
//End Class_Initialize Event

//Validate Method @68-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @68-B0E04A52
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @68-01780365
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
        $Redirect = "CoAdCu_search.php". CCGetQueryString("QueryString", Array("ccsForm"));
       if($this->PressedButton == "Button_DoSearch") {
            if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
            $Redirect = "CoAdCu_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "ccsForm")));
            }
        } else {
            $Redirect = "";
        } 
    }
//End Operation Method

//Show Method @68-945D55FB
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
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_keyword->Errors->ToString();
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

        $this->s_keyword->Show();
        $this->Button_DoSearch->Show();
        $this->ClearParameters->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End CoAdCu_search Class @68-FCB6E20C



Class clsEditableGridCoAdCu_list { //CoAdCu_list Class @255-6DA75F19

//Variables @255-8C338244

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
    var $Navigator;
    var $Sorter_Cue_CodCuenta;
    var $Sorter_cue_Descripcion;
    var $Sorter_cue_TipAuxiliar;
    var $Sorter_cue_ReqRefOperat;
//End Variables

//Class_Initialize Event @255-60F9D7D5
    function clsEditableGridCoAdCu_list()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid CoAdCu_list/Error";
        $this->ComponentName = "CoAdCu_list";
        $this->CachedColumns["Cue_ID"][0] = "Cue_ID";
        $this->ds = new clsCoAdCu_listDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 15)
            $this->PageSize = 15;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 3;
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

        $this->SorterName = CCGetParam("CoAdCu_listOrder", "");
        $this->SorterDirection = CCGetParam("CoAdCu_listDir", "");

        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Sorter_Cue_CodCuenta = new clsSorter($this->ComponentName, "Sorter_Cue_CodCuenta", $FileName);
        $this->Sorter_cue_Descripcion = new clsSorter($this->ComponentName, "Sorter_cue_Descripcion", $FileName);
        $this->Sorter_cue_TipAuxiliar = new clsSorter($this->ComponentName, "Sorter_cue_TipAuxiliar", $FileName);
        $this->Sorter_cue_ReqRefOperat = new clsSorter($this->ComponentName, "Sorter_cue_ReqRefOperat", $FileName);
        $this->Button1 = new clsButton("Button1");
        $this->cue_CodCuenta = new clsControl(ccsTextBox, "cue_CodCuenta", "Cue Cod Cuenta", ccsText, "");
        $this->cue_CodCuenta->Required = true;
        $this->cue_Texto = new clsControl(ccsTextBox, "cue_Texto", "Cue Descripcion", ccsText, "");
        $this->cue_Descripcion = new clsControl(ccsHidden, "cue_Descripcion", "cue_Descripcion", ccsText, "");
        $this->par_Descripcion = new clsControl(ccsTextBox, "par_Descripcion", "Cue Tip Auxiliar", ccsText, "");
        $this->cue_TipAuxiliar = new clsControl(ccsHidden, "cue_TipAuxiliar", "cue_TipAuxiliar", ccsText, "");
        $this->cue_Ro = new clsControl(ccsTextBox, "cue_Ro", "Cue Req Ref Operat", ccsText, "");
        $this->cue_ReqAuxiliar = new clsControl(ccsHidden, "cue_ReqAuxiliar", "cue_ReqAuxiliar", ccsText, "");
        $this->aux_Clase = new clsControl(ccsHidden, "aux_Clase", "aux_Clase", ccsText, "");                                //fah mod
        $this->cue_ReqRefOperat = new clsControl(ccsHidden, "cue_ReqRefOperat", "cue_ReqRefOperat", ccsText, "");
        $this->num_Recs = new clsControl(ccsTextBox, "num_Recs", "num_Recs", ccsText, "");
    }
//End Class_Initialize Event

//Initialize Method @255-E9B30F44
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
    }
//End Initialize Method

//GetFormParameters Method @255-8BE7443E
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["cue_CodCuenta"][$RowNumber] = CCGetFromPost("cue_CodCuenta_" . $RowNumber);
            $this->FormParameters["cue_Texto"][$RowNumber] = CCGetFromPost("cue_Texto_" . $RowNumber);
            $this->FormParameters["cue_Descripcion"][$RowNumber] = CCGetFromPost("cue_Descripcion_" . $RowNumber);
            $this->FormParameters["par_Descripcion"][$RowNumber] = CCGetFromPost("par_Descripcion_" . $RowNumber);
            $this->FormParameters["cue_TipAuxiliar"][$RowNumber] = CCGetFromPost("cue_TipAuxiliar_" . $RowNumber);
            $this->FormParameters["cue_Ro"][$RowNumber] = CCGetFromPost("cue_Ro_" . $RowNumber);
            $this->FormParameters["cue_ReqAuxiliar"][$RowNumber] = CCGetFromPost("cue_ReqAuxiliar_" . $RowNumber);
            $this->FormParameters["cue_ReqRefOperat"][$RowNumber] = CCGetFromPost("cue_ReqRefOperat_" . $RowNumber);
            $this->FormParameters["aux_Clase"][$RowNumber] = CCGetFromPost("aux_Clase_" . $RowNumber);                      // fah mod
        }
    }
//End GetFormParameters Method

//Validate Method @255-CCBE0B68
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["Cue_ID"] = $this->CachedColumns["Cue_ID"][$RowNumber];
            $this->cue_CodCuenta->SetText($this->FormParameters["cue_CodCuenta"][$RowNumber], $RowNumber);
            $this->cue_Texto->SetText($this->FormParameters["cue_Texto"][$RowNumber], $RowNumber);
            $this->cue_Descripcion->SetText($this->FormParameters["cue_Descripcion"][$RowNumber], $RowNumber);
            $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
            $this->cue_TipAuxiliar->SetText($this->FormParameters["cue_TipAuxiliar"][$RowNumber], $RowNumber);
            $this->cue_Ro->SetText($this->FormParameters["cue_Ro"][$RowNumber], $RowNumber);
            $this->cue_ReqAuxiliar->SetText($this->FormParameters["cue_ReqAuxiliar"][$RowNumber], $RowNumber);
            $this->cue_ReqRefOperat->SetText($this->FormParameters["cue_ReqRefOperat"][$RowNumber], $RowNumber);
            $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);                  // fah mod
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

//ValidateRow Method @255-7309F69E
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->cue_CodCuenta->Validate() && $Validation);
        $Validation = ($this->cue_Texto->Validate() && $Validation);
        $Validation = ($this->cue_Descripcion->Validate() && $Validation);
        $Validation = ($this->par_Descripcion->Validate() && $Validation);
        $Validation = ($this->cue_TipAuxiliar->Validate() && $Validation);
        $Validation = ($this->cue_Ro->Validate() && $Validation);
        $Validation = ($this->cue_ReqAuxiliar->Validate() && $Validation);
        $Validation = ($this->cue_ReqRefOperat->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->cue_CodCuenta->Errors->ToString();
            $errors .= $this->cue_Texto->Errors->ToString();
            $errors .= $this->cue_Descripcion->Errors->ToString();
            $errors .= $this->par_Descripcion->Errors->ToString();
            $errors .= $this->cue_TipAuxiliar->Errors->ToString();
            $errors .= $this->cue_Ro->Errors->ToString();
            $errors .= $this->cue_ReqAuxiliar->Errors->ToString();
            $errors .= $this->cue_ReqRefOperat->Errors->ToString();
            $this->cue_CodCuenta->Errors->Clear();
            $this->cue_Texto->Errors->Clear();
            $this->cue_Descripcion->Errors->Clear();
            $this->par_Descripcion->Errors->Clear();
            $this->cue_TipAuxiliar->Errors->Clear();
            $this->cue_Ro->Errors->Clear();
            $this->cue_ReqAuxiliar->Errors->Clear();
            $this->cue_ReqRefOperat->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @255-4D543D22
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["cue_CodCuenta"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_Texto"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_TipAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_Ro"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_ReqAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_ReqRefOperat"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @255-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @255-FA085C18
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
        $this->PressedButton = "";
        if(strlen(CCGetParam("Button1", ""))) {
            $this->PressedButton = "Button1";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button1") {
            if(!CCGetEvent($this->Button1->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @255-18D2868A
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["Cue_ID"] = $this->CachedColumns["Cue_ID"][$RowNumber];
            $this->cue_CodCuenta->SetText($this->FormParameters["cue_CodCuenta"][$RowNumber], $RowNumber);
            $this->cue_Texto->SetText($this->FormParameters["cue_Texto"][$RowNumber], $RowNumber);
            $this->cue_Descripcion->SetText($this->FormParameters["cue_Descripcion"][$RowNumber], $RowNumber);
            $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
            $this->cue_TipAuxiliar->SetText($this->FormParameters["cue_TipAuxiliar"][$RowNumber], $RowNumber);
            $this->cue_Ro->SetText($this->FormParameters["cue_Ro"][$RowNumber], $RowNumber);
            $this->cue_ReqAuxiliar->SetText($this->FormParameters["cue_ReqAuxiliar"][$RowNumber], $RowNumber);
            $this->cue_ReqRefOperat->SetText($this->FormParameters["cue_ReqRefOperat"][$RowNumber], $RowNumber);
            $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->UpdateAllowed) $this->UpdateRow($RowNumber);
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

//FormScript Method @255-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @255-CBFD2342
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
                $this->CachedColumns["Cue_ID"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["Cue_ID"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @255-B9150505
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["Cue_ID"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @255-77C1F366
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
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["Cue_ID"][$RowNumber] = $this->ds->CachedColumns["Cue_ID"];
                        $this->cue_CodCuenta->SetValue($this->ds->cue_CodCuenta->GetValue());
                        $this->cue_Texto->SetValue($this->ds->cue_Texto->GetValue());
                        $this->cue_Descripcion->SetValue($this->ds->cue_Descripcion->GetValue());
                        $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                        $this->cue_TipAuxiliar->SetValue($this->ds->cue_TipAuxiliar->GetValue());
                        $this->cue_Ro->SetValue($this->ds->cue_Ro->GetValue());
                        $this->cue_ReqAuxiliar->SetValue($this->ds->cue_ReqAuxiliar->GetValue());
                        $this->cue_ReqRefOperat->SetValue($this->ds->cue_ReqRefOperat->GetValue());
                        $this->aux_Clase->SetValue($this->ds->aux_Clase->GetValue());                       // fah mod
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["Cue_ID"][$RowNumber] = "";
                        $this->cue_CodCuenta->SetText("");
                        $this->cue_Texto->SetText("");
                        $this->cue_Descripcion->SetText("");
                        $this->par_Descripcion->SetText("");
                        $this->cue_TipAuxiliar->SetText("");
                        $this->cue_Ro->SetText("");
                        $this->cue_ReqAuxiliar->SetText("");
                        $this->cue_ReqRefOperat->SetText("");
                        $this->aux_Clase->SetText("");
                    } else {
                        $this->cue_CodCuenta->SetText($this->FormParameters["cue_CodCuenta"][$RowNumber], $RowNumber);
                        $this->cue_Texto->SetText($this->FormParameters["cue_Texto"][$RowNumber], $RowNumber);
                        $this->cue_Descripcion->SetText($this->FormParameters["cue_Descripcion"][$RowNumber], $RowNumber);
                        $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
                        $this->cue_TipAuxiliar->SetText($this->FormParameters["cue_TipAuxiliar"][$RowNumber], $RowNumber);
                        $this->cue_Ro->SetText($this->FormParameters["cue_Ro"][$RowNumber], $RowNumber);
                        $this->cue_ReqAuxiliar->SetText($this->FormParameters["cue_ReqAuxiliar"][$RowNumber], $RowNumber);
                        $this->cue_ReqRefOperat->SetText($this->FormParameters["cue_ReqRefOperat"][$RowNumber], $RowNumber);
                        $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);      // fah mod
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Button1->Show($RowNumber);
                    $this->cue_CodCuenta->Show($RowNumber);
                    $this->cue_Texto->Show($RowNumber);
                    $this->cue_Descripcion->Show($RowNumber);
                    $this->par_Descripcion->Show($RowNumber);
                    $this->cue_TipAuxiliar->Show($RowNumber);
                    $this->cue_Ro->Show($RowNumber);
                    $this->cue_ReqAuxiliar->Show($RowNumber);
                    $this->cue_ReqRefOperat->Show($RowNumber);
                    $this->aux_Clase->Show($RowNumber);
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
                $this->num_Recs->Show();
            } else {
                $Tpl->block_path = $EditableGridPath;
                $Tpl->parse("NoRecords", false);
            }
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Navigator->Show();
        $this->Sorter_Cue_CodCuenta->Show();
        $this->Sorter_cue_Descripcion->Show();
        $this->Sorter_cue_TipAuxiliar->Show();
        $this->Sorter_cue_ReqRefOperat->Show();

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

} //End CoAdCu_list Class @255-FCB6E20C

class clsCoAdCu_listDataSource extends clsDBdatos {  //CoAdCu_listDataSource Class @255-731964BE

//DataSource Variables @255-2E8FDA9D
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $cue_CodCuenta;
    var $cue_Texto;
    var $cue_Descripcion;
    var $par_Descripcion;
    var $cue_TipAuxiliar;
    var $cue_Ro;
    var $cue_ReqAuxiliar;
    var $cue_ReqRefOperat;
//End DataSource Variables

//Class_Initialize Event @255-A27D539C
    function clsCoAdCu_listDataSource()
    {
        $this->ErrorBlock = "EditableGrid CoAdCu_list/Error";
        $this->Initialize();
        $this->cue_CodCuenta = new clsField("cue_CodCuenta", ccsText, "");
        $this->cue_Texto = new clsField("cue_Texto", ccsText, "");
        $this->cue_Descripcion = new clsField("cue_Descripcion", ccsText, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->cue_TipAuxiliar = new clsField("cue_TipAuxiliar", ccsText, "");
        $this->cue_Ro = new clsField("cue_Ro", ccsText, "");
        $this->cue_ReqAuxiliar = new clsField("cue_ReqAuxiliar", ccsText, "");
        $this->cue_ReqRefOperat = new clsField("cue_ReqRefOperat", ccsText, "");
        $this->aux_Clase = new clsField("aux_Clase", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @255-A407129B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "concuentas.Cue_CodCuenta";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Cue_CodCuenta" => array("Cue_CodCuenta", ""), 
            "Sorter_cue_Descripcion" => array("cue_Texto", ""), 
            "Sorter_cue_TipAuxiliar" => array("cue_TipAuxiliar", ""), 
            "Sorter_cue_ReqRefOperat" => array("cue_ReqRefOperat", "")));
    }
//End SetOrder Method

//Prepare Method @255-858E9B2B
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @255-D505B9AF
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM (((concuentas padre RIGHT JOIN concuentas ON padre.Cue_ID = concuentas.Cue_Padre ) LEFT JOIN concuentas abuelo ON padre.Cue_Padre = abuelo.Cue_ID)) LEFT JOIN genparametros ON genparametros.par_Secuencia = concuentas.cue_TipAuxiliar  " .
        "WHERE ( concuentas.Cue_ID > 1 AND par_Clave = 'CAUTI' ) AND ( concuentas.Cue_CodCuenta LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR concuentas.cue_Descripcion LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' ) " .
        "";
        $this->SQL = "SELECT concuentas.Cue_ID AS Cue_ID, concuentas.Cue_CodCuenta AS Cue_CodCuenta, concuentas.cue_Descripcion AS cue_Descripcion, " .
                            "concuentas.cue_ReqAuxiliar AS cue_ReqAuxiliar, concuentas.cue_TipAuxiliar AS cue_TipAuxiliar, " .
                            "concuentas.cue_ReqRefOperat AS cue_ReqRefOperat, padre.cue_Descripcion AS pad_Descripcion, " .
                            "abuelo.Cue_ID AS abu_Cue_ID, abuelo.cue_Descripcion AS abu_Descripcion, par_Clave, par_Descripcion,
                             concat_ws(\" // \",  " .
                            "if(padre.cue_descripcion = '   Raiz'  ,'', padre.cue_descripcion) ,  " .
                            "concuentas.cue_Descripcion) AS cue_Texto, " .
                            "if(concuentas.cue_ReqAuxiliar=1,\"Sí\",\"No\")as cue_siauxi, " .
                            "if(concuentas.cue_ReqRefOperat=1, 'Si', 'No') as cue_Ro, par_Valor1 as aux_Clase " .
                    "FROM (((concuentas padre RIGHT JOIN concuentas ON padre.Cue_ID = concuentas.Cue_Padre )
                        LEFT JOIN concuentas abuelo ON padre.Cue_Padre = abuelo.Cue_ID))
                        LEFT JOIN genparametros ON genparametros.par_Secuencia = concuentas.cue_TipAuxiliar  " .
                    "WHERE ( concuentas.Cue_ID > 1 AND par_Clave = 'CAUTI' ) AND
                        ( concuentas.Cue_CodCuenta LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR
                          concuentas.cue_Descripcion LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' ) " .
                    "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @255-AE650120
    function SetValues()
    {
        $this->CachedColumns["Cue_ID"] = $this->f("Cue_ID");
        $this->cue_CodCuenta->SetDBValue($this->f("Cue_CodCuenta"));
        $this->cue_Texto->SetDBValue($this->f("cue_Texto"));
        $this->cue_Descripcion->SetDBValue($this->f("cue_Descripcion"));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->cue_TipAuxiliar->SetDBValue($this->f("cue_TipAuxiliar"));
        $this->cue_Ro->SetDBValue($this->f("cue_Ro"));
        $this->cue_ReqAuxiliar->SetDBValue($this->f("cue_ReqAuxiliar"));
        $this->cue_ReqRefOperat->SetDBValue($this->f("cue_ReqRefOperat"));
        $this->aux_Clase->SetDBValue($this->f("aux_Clase"));
    }
//End SetValues Method

} //End CoAdCu_listDataSource Class @255-FCB6E20C

//Initialize Page @1-4DDEAE43
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

$FileName = "CoAdCu_search.php";
$Redirect = "";
$TemplateFileName = "CoAdCu_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-8A972ACA
$DBdatos = new clsDBdatos();

// Controls
$CoAdCu_search = new clsRecordCoAdCu_search();
$CoAdCu_list = new clsEditableGridCoAdCu_list();
$CoAdCu_list->Initialize();

// Events
include("./CoAdCu_search_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-43976B5D
$CoAdCu_search->Operation();
$CoAdCu_list->Operation();
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

//Show Page @1-0DD77C60
$CoAdCu_search->Show();
$CoAdCu_list->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
