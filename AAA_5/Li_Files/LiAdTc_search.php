<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqcajas_qry { //liqcajas_qry Class @84-E11C1619

//Variables @84-CB19EB75

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

//Class_Initialize Event @84-188E5E5D
    function clsRecordliqcajas_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqcajas_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "liqcajas_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pMensj = new clsControl(ccsLabel, "pMensj", "pMensj", ccsText, "", CCGetRequestParam("pMensj", $Method));
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_keyword", "ccsForm"));
            $this->ClearParameters->Page = "LiAdTc_search.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @84-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @84-E7F39E68
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pMensj->Errors->Count());
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @84-C55ABCD8
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
        $Redirect = "LiAdTc_search.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdTc_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "ccsForm")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @84-CEE35C29
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
            $Error .= $this->pMensj->Errors->ToString();
            $Error .= $this->s_keyword->Errors->ToString();
            $Error .= $this->ClearParameters->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        if($this->FormSubmitted || CCGetFromGet("ccsForm")) {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        } else {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("All", ""), "ccsForm", $CCSForm);
        }
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->pMensj->Show();
        $this->s_keyword->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liqcajas_qry Class @84-FCB6E20C

class clsEditableGridliqcajas_list { //liqcajas_list Class @3-4511446D

//Variables @3-5A020C6C

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
    var $Sorter_caj_CodCaja;
    var $Sorter_caj_Abreviatura;
    var $Sorter_caj_Descripcion;
    var $Sorter_par_Descripcion;
    var $Sorter_caj_TipoCaja;
//End Variables

//Class_Initialize Event @3-C1BF9988
    function clsEditableGridliqcajas_list()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid liqcajas_list/Error";
        $this->ComponentName = "liqcajas_list";
        $this->ds = new clsliqcajas_listDataSource();

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

        $this->SorterName = CCGetParam("liqcajas_listOrder", "");
        $this->SorterDirection = CCGetParam("liqcajas_listDir", "");

        $this->liqcajas_genparametros_TotalRecords = new clsControl(ccsLabel, "liqcajas_genparametros_TotalRecords", "liqcajas_genparametros_TotalRecords", ccsText, "");
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_caj_CodCaja = new clsSorter($this->ComponentName, "Sorter_caj_CodCaja", $FileName);
        $this->Sorter_caj_Abreviatura = new clsSorter($this->ComponentName, "Sorter_caj_Abreviatura", $FileName);
        $this->Sorter_caj_Descripcion = new clsSorter($this->ComponentName, "Sorter_caj_Descripcion", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Sorter_caj_TipoCaja = new clsSorter($this->ComponentName, "Sorter_caj_TipoCaja", $FileName);
        $this->caj_CodCaja = new clsControl(ccsHidden, "caj_CodCaja", "Código de Caja", ccsInteger, "");
        $this->Button2 = new clsButton("Button2");
        $this->caj_Abreviatura = new clsControl(ccsTextBox, "caj_Abreviatura", "Caj Abreviatura", ccsText, "");
        $this->caj_Abreviatura->Required = true;
        $this->caj_Descripcion = new clsControl(ccsTextBox, "caj_Descripcion", "Caj Descripcion", ccsText, "");
        $this->caj_Descripcion->Required = true;
        $this->par_Descripcion = new clsControl(ccsTextBox, "par_Descripcion", "Par Descripcion", ccsText, "");
        $this->par_Descripcion->Required = true;
        $this->caj_TipoCaja = new clsControl(ccsTextBox, "caj_TipoCaja", "Caj Tipo Caja", ccsText, "");
        $this->caj_Componente4 = new clsControl(ccsHidden, "caj_Componente4", "Caj Componente4", ccsInteger, "");
        $this->caj_Componente4->Required = true;
        $this->caj_Componente1 = new clsControl(ccsHidden, "caj_Componente1", "Caj Componente1", ccsInteger, "");
        $this->caj_Componente1->Required = true;
        $this->caj_Componente2 = new clsControl(ccsHidden, "caj_Componente2", "Caj Componente2", ccsInteger, "");
        $this->caj_Componente2->Required = true;
        $this->caj_Componente3 = new clsControl(ccsHidden, "caj_Componente3", "Caj Componente3", ccsInteger, "");
        $this->caj_Componente3->Required = true;
        $this->caj_CodMarca = new clsControl(ccsHidden, "caj_CodMarca", "caj_CodMarca", ccsInteger, "");
    }
//End Class_Initialize Event

//Initialize Method @3-676CA7C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["expr113"] = 'IMARCA';
        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
    }
//End Initialize Method

//GetFormParameters Method @3-FC9EA3DF
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["caj_CodCaja"][$RowNumber] = CCGetFromPost("caj_CodCaja_" . $RowNumber);
            $this->FormParameters["caj_Abreviatura"][$RowNumber] = CCGetFromPost("caj_Abreviatura_" . $RowNumber);
            $this->FormParameters["caj_Descripcion"][$RowNumber] = CCGetFromPost("caj_Descripcion_" . $RowNumber);
            $this->FormParameters["par_Descripcion"][$RowNumber] = CCGetFromPost("par_Descripcion_" . $RowNumber);
            $this->FormParameters["caj_TipoCaja"][$RowNumber] = CCGetFromPost("caj_TipoCaja_" . $RowNumber);
            $this->FormParameters["caj_Componente4"][$RowNumber] = CCGetFromPost("caj_Componente4_" . $RowNumber);
            $this->FormParameters["caj_Componente1"][$RowNumber] = CCGetFromPost("caj_Componente1_" . $RowNumber);
            $this->FormParameters["caj_Componente2"][$RowNumber] = CCGetFromPost("caj_Componente2_" . $RowNumber);
            $this->FormParameters["caj_Componente3"][$RowNumber] = CCGetFromPost("caj_Componente3_" . $RowNumber);
            $this->FormParameters["caj_CodMarca"][$RowNumber] = CCGetFromPost("caj_CodMarca_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @3-BE038FEB
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->caj_CodCaja->SetText($this->FormParameters["caj_CodCaja"][$RowNumber], $RowNumber);
            $this->caj_Abreviatura->SetText($this->FormParameters["caj_Abreviatura"][$RowNumber], $RowNumber);
            $this->caj_Descripcion->SetText($this->FormParameters["caj_Descripcion"][$RowNumber], $RowNumber);
            $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
            $this->caj_TipoCaja->SetText($this->FormParameters["caj_TipoCaja"][$RowNumber], $RowNumber);
            $this->caj_Componente4->SetText($this->FormParameters["caj_Componente4"][$RowNumber], $RowNumber);
            $this->caj_Componente1->SetText($this->FormParameters["caj_Componente1"][$RowNumber], $RowNumber);
            $this->caj_Componente2->SetText($this->FormParameters["caj_Componente2"][$RowNumber], $RowNumber);
            $this->caj_Componente3->SetText($this->FormParameters["caj_Componente3"][$RowNumber], $RowNumber);
            $this->caj_CodMarca->SetText($this->FormParameters["caj_CodMarca"][$RowNumber], $RowNumber);
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

//ValidateRow Method @3-0E841627
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->caj_CodCaja->Validate() && $Validation);
        $Validation = ($this->caj_Abreviatura->Validate() && $Validation);
        $Validation = ($this->caj_Descripcion->Validate() && $Validation);
        $Validation = ($this->par_Descripcion->Validate() && $Validation);
        $Validation = ($this->caj_TipoCaja->Validate() && $Validation);
        $Validation = ($this->caj_Componente4->Validate() && $Validation);
        $Validation = ($this->caj_Componente1->Validate() && $Validation);
        $Validation = ($this->caj_Componente2->Validate() && $Validation);
        $Validation = ($this->caj_Componente3->Validate() && $Validation);
        $Validation = ($this->caj_CodMarca->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->caj_CodCaja->Errors->ToString();
            $errors .= $this->caj_Abreviatura->Errors->ToString();
            $errors .= $this->caj_Descripcion->Errors->ToString();
            $errors .= $this->par_Descripcion->Errors->ToString();
            $errors .= $this->caj_TipoCaja->Errors->ToString();
            $errors .= $this->caj_Componente4->Errors->ToString();
            $errors .= $this->caj_Componente1->Errors->ToString();
            $errors .= $this->caj_Componente2->Errors->ToString();
            $errors .= $this->caj_Componente3->Errors->ToString();
            $errors .= $this->caj_CodMarca->Errors->ToString();
            $this->caj_CodCaja->Errors->Clear();
            $this->caj_Abreviatura->Errors->Clear();
            $this->caj_Descripcion->Errors->Clear();
            $this->par_Descripcion->Errors->Clear();
            $this->caj_TipoCaja->Errors->Clear();
            $this->caj_Componente4->Errors->Clear();
            $this->caj_Componente1->Errors->Clear();
            $this->caj_Componente2->Errors->Clear();
            $this->caj_Componente3->Errors->Clear();
            $this->caj_CodMarca->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @3-3FD08F62
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["caj_CodCaja"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Abreviatura"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_TipoCaja"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Componente4"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Componente1"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Componente2"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Componente3"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_CodMarca"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @3-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-376BF26A
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
        if(strlen(CCGetParam("btNuevo", ""))) {
            $this->PressedButton = "btNuevo";
        } else if(strlen(CCGetParam("btCerrar", ""))) {
            $this->PressedButton = "btCerrar";
        } else if(strlen(CCGetParam("Button2", ""))) {
            $this->PressedButton = "Button2";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "btNuevo") {
            if(!CCGetEvent($this->btNuevo->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btCerrar") {
            if(!CCGetEvent($this->btCerrar->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button2") {
            if(!CCGetEvent($this->Button2->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @3-00BC5349
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->caj_CodCaja->SetText($this->FormParameters["caj_CodCaja"][$RowNumber], $RowNumber);
            $this->caj_Abreviatura->SetText($this->FormParameters["caj_Abreviatura"][$RowNumber], $RowNumber);
            $this->caj_Descripcion->SetText($this->FormParameters["caj_Descripcion"][$RowNumber], $RowNumber);
            $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
            $this->caj_TipoCaja->SetText($this->FormParameters["caj_TipoCaja"][$RowNumber], $RowNumber);
            $this->caj_Componente4->SetText($this->FormParameters["caj_Componente4"][$RowNumber], $RowNumber);
            $this->caj_Componente1->SetText($this->FormParameters["caj_Componente1"][$RowNumber], $RowNumber);
            $this->caj_Componente2->SetText($this->FormParameters["caj_Componente2"][$RowNumber], $RowNumber);
            $this->caj_Componente3->SetText($this->FormParameters["caj_Componente3"][$RowNumber], $RowNumber);
            $this->caj_CodMarca->SetText($this->FormParameters["caj_CodMarca"][$RowNumber], $RowNumber);
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

//FormScript Method @3-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @3-69E01441
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

//GetFormState Method @3-BF9CEBD0
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

//Show Method @3-FEB447EA
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
                        $this->caj_CodCaja->SetValue($this->ds->caj_CodCaja->GetValue());
                        $this->caj_Abreviatura->SetValue($this->ds->caj_Abreviatura->GetValue());
                        $this->caj_Descripcion->SetValue($this->ds->caj_Descripcion->GetValue());
                        $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                        $this->caj_TipoCaja->SetValue($this->ds->caj_TipoCaja->GetValue());
                        $this->caj_Componente4->SetValue($this->ds->caj_Componente4->GetValue());
                        $this->caj_Componente1->SetValue($this->ds->caj_Componente1->GetValue());
                        $this->caj_Componente2->SetValue($this->ds->caj_Componente2->GetValue());
                        $this->caj_Componente3->SetValue($this->ds->caj_Componente3->GetValue());
                        $this->caj_CodMarca->SetValue($this->ds->caj_CodMarca->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->caj_CodCaja->SetText("");
                        $this->caj_Abreviatura->SetText("");
                        $this->caj_Descripcion->SetText("");
                        $this->par_Descripcion->SetText("");
                        $this->caj_TipoCaja->SetText("");
                        $this->caj_Componente4->SetText("");
                        $this->caj_Componente1->SetText("");
                        $this->caj_Componente2->SetText("");
                        $this->caj_Componente3->SetText("");
                        $this->caj_CodMarca->SetText("");
                    } else {
                        $this->caj_CodCaja->SetText($this->FormParameters["caj_CodCaja"][$RowNumber], $RowNumber);
                        $this->caj_Abreviatura->SetText($this->FormParameters["caj_Abreviatura"][$RowNumber], $RowNumber);
                        $this->caj_Descripcion->SetText($this->FormParameters["caj_Descripcion"][$RowNumber], $RowNumber);
                        $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
                        $this->caj_TipoCaja->SetText($this->FormParameters["caj_TipoCaja"][$RowNumber], $RowNumber);
                        $this->caj_Componente4->SetText($this->FormParameters["caj_Componente4"][$RowNumber], $RowNumber);
                        $this->caj_Componente1->SetText($this->FormParameters["caj_Componente1"][$RowNumber], $RowNumber);
                        $this->caj_Componente2->SetText($this->FormParameters["caj_Componente2"][$RowNumber], $RowNumber);
                        $this->caj_Componente3->SetText($this->FormParameters["caj_Componente3"][$RowNumber], $RowNumber);
                        $this->caj_CodMarca->SetText($this->FormParameters["caj_CodMarca"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->caj_CodCaja->Show($RowNumber);
                    $this->Button2->Show($RowNumber);
                    $this->caj_Abreviatura->Show($RowNumber);
                    $this->caj_Descripcion->Show($RowNumber);
                    $this->par_Descripcion->Show($RowNumber);
                    $this->caj_TipoCaja->Show($RowNumber);
                    $this->caj_Componente4->Show($RowNumber);
                    $this->caj_Componente1->Show($RowNumber);
                    $this->caj_Componente2->Show($RowNumber);
                    $this->caj_Componente3->Show($RowNumber);
                    $this->caj_CodMarca->Show($RowNumber);
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
        $this->liqcajas_genparametros_TotalRecords->Show();
        $this->Navigator->Show();
        $this->btNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_caj_CodCaja->Show();
        $this->Sorter_caj_Abreviatura->Show();
        $this->Sorter_caj_Descripcion->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Sorter_caj_TipoCaja->Show();

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

} //End liqcajas_list Class @3-FCB6E20C

class clsliqcajas_listDataSource extends clsDBdatos {  //liqcajas_listDataSource Class @3-68906531

//DataSource Variables @3-DD75F227
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $caj_CodCaja;
    var $caj_Abreviatura;
    var $caj_Descripcion;
    var $par_Descripcion;
    var $caj_TipoCaja;
    var $caj_Componente4;
    var $caj_Componente1;
    var $caj_Componente2;
    var $caj_Componente3;
    var $caj_CodMarca;
//End DataSource Variables

//Class_Initialize Event @3-3E484B5A
    function clsliqcajas_listDataSource()
    {
        $this->ErrorBlock = "EditableGrid liqcajas_list/Error";
        $this->Initialize();
        $this->caj_CodCaja = new clsField("caj_CodCaja", ccsInteger, "");
        $this->caj_Abreviatura = new clsField("caj_Abreviatura", ccsText, "");
        $this->caj_Descripcion = new clsField("caj_Descripcion", ccsText, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->caj_TipoCaja = new clsField("caj_TipoCaja", ccsText, "");
        $this->caj_Componente4 = new clsField("caj_Componente4", ccsInteger, "");
        $this->caj_Componente1 = new clsField("caj_Componente1", ccsInteger, "");
        $this->caj_Componente2 = new clsField("caj_Componente2", ccsInteger, "");
        $this->caj_Componente3 = new clsField("caj_Componente3", ccsInteger, "");
        $this->caj_CodMarca = new clsField("caj_CodMarca", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @3-66EE9F4B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "caj_Descripcion";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_caj_CodCaja" => array("caj_CodCaja", ""), 
            "Sorter_caj_Abreviatura" => array("caj_Abreviatura", ""), 
            "Sorter_caj_Descripcion" => array("caj_Descripcion", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", ""), 
            "Sorter_caj_TipoCaja" => array("caj_TipoCaja", "")));
    }
//End SetOrder Method

//Prepare Method @3-9DCFEBDE
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr113", ccsText, "", "", $this->Parameters["expr113"], "", false);
        $this->wp->AddParameter("2", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("3", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("4", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "caj_CodCaja", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opBeginsWith, "caj_Abreviatura", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opBeginsWith, "caj_Descripcion", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->opOR(true, $this->wp->opOR(false, $this->wp->Criterion[2], $this->wp->Criterion[3]), $this->wp->Criterion[4]));
    }
//End Prepare Method

//Open Method @3-B8F0FFEC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM liqcajas LEFT JOIN genparametros ON " .
        "liqcajas.caj_CodMarca = genparametros.par_Secuencia";
        $this->SQL = "SELECT caj_CodCaja, caj_CodMarca, caj_Abreviatura, caj_Descripcion, caj_TipoCaja, caj_Componente1, caj_Componente2, caj_Componente3, " .
        "caj_Componente4, par_Descripcion  " .
        "FROM liqcajas LEFT JOIN genparametros ON " .
        "liqcajas.caj_CodMarca = genparametros.par_Secuencia";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @3-B4DD3E85
    function SetValues()
    {
        $this->caj_CodCaja->SetDBValue(trim($this->f("caj_CodCaja")));
        $this->caj_Abreviatura->SetDBValue($this->f("caj_Abreviatura"));
        $this->caj_Descripcion->SetDBValue($this->f("caj_Descripcion"));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->caj_TipoCaja->SetDBValue($this->f("caj_TipoCaja"));
        $this->caj_Componente4->SetDBValue(trim($this->f("caj_Componente4")));
        $this->caj_Componente1->SetDBValue(trim($this->f("caj_Componente1")));
        $this->caj_Componente2->SetDBValue(trim($this->f("caj_Componente2")));
        $this->caj_Componente3->SetDBValue(trim($this->f("caj_Componente3")));
        $this->caj_CodMarca->SetDBValue(trim($this->f("caj_CodMarca")));
    }
//End SetValues Method

} //End liqcajas_listDataSource Class @3-FCB6E20C

//Initialize Page @1-944C3348
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

$FileName = "LiAdTc_search.php";
$Redirect = "";
$TemplateFileName = "LiAdTc_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-B1F19ED6
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqcajas_qry = new clsRecordliqcajas_qry();
$liqcajas_list = new clsEditableGridliqcajas_list();
$liqcajas_list->Initialize();

// Events
include("./LiAdTc_search_events.php");
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

//Execute Components @1-1961CB4D
$Cabecera->Operations();
$liqcajas_qry->Operation();
$liqcajas_list->Operation();
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

//Show Page @1-3033E93E
$Cabecera->Show("Cabecera");
$liqcajas_qry->Show();
$liqcajas_list->Show();
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
