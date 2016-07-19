<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include_once ("../LibPhp/ValLib.php");
include_once ("GenUti.inc.php");
Class clsRecordItem_qry { //Item_qry Class @32-752E5501

//Variables @32-CB19EB75

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

//Class_Initialize Event @32-8A67474C
    function clsRecordItem_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record Item_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "Item_qry";
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
        }
    }
//End Class_Initialize Event

//Validate Method @32-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @32-D6729123
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @32-8D4710E3
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
        $Redirect = "InTrTr_items.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "InTrTr_items.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "ccsForm")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @32-5216D6D3
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

        $this->s_keyword->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End Item_qry Class @32-FCB6E20C

Class clsEditableGridItem_lista { //Item_lista Class @59-D66B9A30

//Variables @59-0317BEA6

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
    var $Sorter_act_CodAuxiliar;
    var $Sorter_act_Descripcion;
//End Variables

//Class_Initialize Event @59-C3DC2ED4
    function clsEditableGridItem_lista()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid Item_lista/Error";
        $this->ComponentName = "Item_lista";
        $this->ds = new clsItem_listaDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 20)
            $this->PageSize = 20;
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

        $this->SorterName = CCGetParam("Item_listaOrder", "");
        $this->SorterDirection = CCGetParam("Item_listaDir", "");

        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 20, tpMoving);
        $this->Sorter_act_CodAuxiliar = new clsSorter($this->ComponentName, "Sorter_act_CodAuxiliar", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Button1 = new clsButton("Button1");
        $this->act_codauxiliar = new clsControl(ccsTextBox, "act_codauxiliar", "Act Cod Auxiliar", ccsInteger, "");
        $this->act_codauxiliar->Required = true;
        $this->act_descrip = new clsControl(ccsTextBox, "act_descrip", "act_descrip", ccsText, "");
        $this->uni_Abreviatura = new clsControl(ccsTextBox, "uni_Abreviatura", "uni_Abreviatura", ccsText, "");
        $this->act_UniMedida = new clsControl(ccsHidden, "act_UniMedida", "act_UniMedida", ccsText, "");
        $this->tab_Multiplicador = new clsControl(ccsHidden, "tab_Multiplicador", "tab_Multiplicador", ccsFloat, "");
        $this->tab_Divisor = new clsControl(ccsHidden, "tab_Divisor", "tab_Divisor", ccsFloat, "");
        $this->tmp_unitario = new clsControl(ccsHidden, "tmp_unitario", "tmp_unitario", ccsFloat, "");
        $this->num_Recs = new clsControl(ccsHidden, "num_Recs", "num_Recs", ccsInteger, "");
    }
//End Class_Initialize Event

//Initialize Method @59-E9B30F44
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
    }
//End Initialize Method

//GetFormParameters Method @59-F2C33504
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["act_codauxiliar"][$RowNumber] = CCGetFromPost("act_codauxiliar_" . $RowNumber);
            $this->FormParameters["act_descrip"][$RowNumber] = CCGetFromPost("act_descrip_" . $RowNumber);
            $this->FormParameters["uni_Abreviatura"][$RowNumber] = CCGetFromPost("uni_Abreviatura_" . $RowNumber);
            $this->FormParameters["act_UniMedida"][$RowNumber] = CCGetFromPost("act_UniMedida_" . $RowNumber);
            $this->FormParameters["tab_Multiplicador"][$RowNumber] = CCGetFromPost("tab_Multiplicador_" . $RowNumber);
            $this->FormParameters["tab_Divisor"][$RowNumber] = CCGetFromPost("tab_Divisor_" . $RowNumber);
            $this->FormParameters["tmp_unitario"][$RowNumber] = CCGetFromPost("tmp_unitario_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @59-0F8F514C
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->act_codauxiliar->SetText($this->FormParameters["act_codauxiliar"][$RowNumber], $RowNumber);
            $this->act_descrip->SetText($this->FormParameters["act_descrip"][$RowNumber], $RowNumber);
            $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->tab_Multiplicador->SetText($this->FormParameters["tab_Multiplicador"][$RowNumber], $RowNumber);
            $this->tab_Divisor->SetText($this->FormParameters["tab_Divisor"][$RowNumber], $RowNumber);
            $this->tmp_unitario->SetText($this->FormParameters["tmp_unitario"][$RowNumber], $RowNumber);
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

//ValidateRow Method @59-6309A282
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->act_codauxiliar->Validate() && $Validation);
        $Validation = ($this->act_descrip->Validate() && $Validation);
        $Validation = ($this->uni_Abreviatura->Validate() && $Validation);
        $Validation = ($this->act_UniMedida->Validate() && $Validation);
        $Validation = ($this->tab_Multiplicador->Validate() && $Validation);
        $Validation = ($this->tab_Divisor->Validate() && $Validation);
        $Validation = ($this->tmp_unitario->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->act_codauxiliar->Errors->ToString();
            $errors .= $this->act_descrip->Errors->ToString();
            $errors .= $this->uni_Abreviatura->Errors->ToString();
            $errors .= $this->act_UniMedida->Errors->ToString();
            $errors .= $this->tab_Multiplicador->Errors->ToString();
            $errors .= $this->tab_Divisor->Errors->ToString();
            $errors .= $this->tmp_unitario->Errors->ToString();
            $this->act_codauxiliar->Errors->Clear();
            $this->act_descrip->Errors->Clear();
            $this->uni_Abreviatura->Errors->Clear();
            $this->act_UniMedida->Errors->Clear();
            $this->tab_Multiplicador->Errors->Clear();
            $this->tab_Divisor->Errors->Clear();
            $this->tmp_unitario->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @59-22A84F80
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["act_codauxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_descrip"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["uni_Abreviatura"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_UniMedida"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tab_Multiplicador"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tab_Divisor"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tmp_unitario"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @59-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @59-FA085C18
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

//UpdateGrid Method @59-BE774EBF
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->act_codauxiliar->SetText($this->FormParameters["act_codauxiliar"][$RowNumber], $RowNumber);
            $this->act_descrip->SetText($this->FormParameters["act_descrip"][$RowNumber], $RowNumber);
            $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->tab_Multiplicador->SetText($this->FormParameters["tab_Multiplicador"][$RowNumber], $RowNumber);
            $this->tab_Divisor->SetText($this->FormParameters["tab_Divisor"][$RowNumber], $RowNumber);
            $this->tmp_unitario->SetText($this->FormParameters["tmp_unitario"][$RowNumber], $RowNumber);
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

//FormScript Method @59-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @59-69E01441
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

//GetFormState Method @59-BF9CEBD0
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

//Show Method @59-CD51A368
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
                        $this->act_codauxiliar->SetValue($this->ds->act_codauxiliar->GetValue());
                        $this->act_descrip->SetValue($this->ds->act_descrip->GetValue());
                        $this->uni_Abreviatura->SetValue($this->ds->uni_Abreviatura->GetValue());
                        $this->act_UniMedida->SetValue($this->ds->act_UniMedida->GetValue());
                        $this->tab_Multiplicador->SetValue($this->ds->tab_Multiplicador->GetValue());
                        $this->tab_Divisor->SetValue($this->ds->tab_Divisor->GetValue());
                        $this->tmp_unitario->SetValue($this->ds->tmp_unitario->GetValue());
                        $Tpl->setVar("destino",trim($this->ds->f("act_pagina")));
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->act_codauxiliar->SetText("");
                        $this->act_descrip->SetText("");
                        $this->uni_Abreviatura->SetText("");
                        $this->act_UniMedida->SetText("");
                        $this->tab_Multiplicador->SetText("");
                        $this->tab_Divisor->SetText("");
                        $this->tmp_unitario->SetText("");
                        $Tpl->setVar("destino","");
                    } else {
                        $this->act_codauxiliar->SetText($this->FormParameters["act_codauxiliar"][$RowNumber], $RowNumber);
                        $this->act_descrip->SetText($this->FormParameters["act_descrip"][$RowNumber], $RowNumber);
                        $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
                        $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
                        $this->tab_Multiplicador->SetText($this->FormParameters["tab_Multiplicador"][$RowNumber], $RowNumber);
                        $this->tab_Divisor->SetText($this->FormParameters["tab_Divisor"][$RowNumber], $RowNumber);
                        $this->tmp_unitario->SetText($this->FormParameters["tmp_unitario"][$RowNumber], $RowNumber);
                        $Tpl->setVar("destino","");
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Button1->Show($RowNumber);
                    $this->act_codauxiliar->Show($RowNumber);
                    $this->act_descrip->Show($RowNumber);
                    $this->uni_Abreviatura->Show($RowNumber);
                    $this->act_UniMedida->Show($RowNumber);
                    $this->tab_Multiplicador->Show($RowNumber);
                    $this->tab_Divisor->Show($RowNumber);
                    $this->tmp_unitario->Show($RowNumber);
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
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->Navigator1->Show();
        $this->Sorter_act_CodAuxiliar->Show();
        $this->Sorter_act_Descripcion->Show();

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

} //End Item_lista Class @59-FCB6E20C

class clsItem_listaDataSource extends clsDBdatos {  //Item_listaDataSource Class @59-53206EAA

//DataSource Variables @59-F9C77A88
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $act_codauxiliar;
    var $act_descrip;
    var $uni_Abreviatura;
    var $act_UniMedida;
    var $tab_Multiplicador;
    var $tab_Divisor;
    var $tmp_unitario;
//End DataSource Variables

//Class_Initialize Event @59-27AD9AF6
    function clsItem_listaDataSource()
    {
        $this->ErrorBlock = "EditableGrid Item_lista/Error";
        $this->Initialize();
        $this->act_codauxiliar = new clsField("act_codauxiliar", ccsInteger, "");
        $this->act_descrip = new clsField("act_descrip", ccsText, "");
        $this->uni_Abreviatura = new clsField("uni_Abreviatura", ccsText, "");
        $this->act_UniMedida = new clsField("act_UniMedida", ccsText, "");
        $this->tab_Multiplicador = new clsField("tab_Multiplicador", ccsFloat, "");
        $this->tab_Divisor = new clsField("tab_Divisor", ccsFloat, "");
        $this->tmp_unitario = new clsField("tmp_unitario", ccsFloat, "");

    }
//End Class_Initialize Event

//SetOrder Method @59-42C94B54
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_act_CodAuxiliar" => array("act_CodAuxiliar", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @59-858E9B2B
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @59-B2B51B65
    function Open()
    {
        $pTip=fGetParam("pTip", "FA");
        $slCondAct = " WHERE act_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
                           "act_Descripcion1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
                           "act_CodAuxiliar LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR " .
                           "act_CodAnterior LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%'  " ;
        $slCondPer = "WHERE per_Nombres  LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
                           "per_Apellidos LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
                           "per_CodAuxiliar LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR " .
                           "per_CodAnterior LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%'";

        $slSql     = "SELECT act_CodAuxiliar, cat_Activo, uni_Descripcion,
                           concat(act_Descripcion,' ' , act_descripcion1)as act_descrip,
                           act_UniMedida, 1 as act_Multiplicador, 1 as act_Divisor,
                           concat('../Co_Files/CoAdAc_mant2.php?act_CodAuxiliar=', act_codauxiliar) as act_pagina,
                           ifnull(pre_PreUnitario,0) as tmp_unitario
                        FROM conactivos
                	INNER JOIN concategorias ON concategorias.cat_CodAuxiliar = conactivos.act_CodAuxiliar
                	INNER JOIN genunmedida ON conactivos.act_UniMedida = genunmedida.uni_CodUnidad 
                        LEFT JOIN genclasetran ON cla_tipocomp = '" . $pTip . "' 
		        LEFT JOIN invprecios ON pre_lisprecios = cla_lisprecios AND pre_coditem = act_codauxiliar " 
                        . $slCondAct 
                        ;
        if (CCGetParam("pGral", 0) == 1)            	
        $slSql .= " UNION ".
                "SELECT per_CodAuxiliar, cat_Activo, '',
                       concat(per_Apellidos,' ' , per_Nombres)as act_descrip,
                       '', 1 as act_Multiplicador, 1 as act_Divisor,
                       concat('../Co_Files/CoAdAu_mant.php?per_CodAuxiliar=', per_codauxiliar) as act_pagina,
                       ' '
                FROM conpersonas
               	INNER JOIN concategorias ON cat_CodAuxiliar = per_CodAuxiliar " . $slCondPer ;
        $slSql .= " Order by 1 ";               	

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");

        $this->CountSQL = "SELECT COUNT(*) FROM (" . $slSql . ") t ";
        $this->SQL = $slSql;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
        
//echo $this->SQL;
    }
//End Open Method

//SetValues Method @59-7D603572
    function SetValues()
    {
        $this->act_codauxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->act_descrip->SetDBValue($this->f("act_descrip"));
        $this->uni_Abreviatura->SetDBValue($this->f("uni_Descripcion"));
        $this->act_UniMedida->SetDBValue($this->f("act_UniMedida"));
        $this->tab_Multiplicador->SetDBValue(trim($this->f("act_Multiplicador")));
        $this->tab_Divisor->SetDBValue(trim($this->f("act_Divisor")));
        $this->tmp_unitario->SetDBValue(trim($this->f("tmp_unitario")));
    }
//End SetValues Method

} //End Item_listaDataSource Class @59-FCB6E20C

//Initialize Page @1-1DE85599
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

$FileName = "InTrTr_items.php";
$Redirect = "";
$TemplateFileName = "InTrTr_items.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-F309FF2C
$DBdatos = new clsDBdatos();

// Controls
$Item_qry = new clsRecordItem_qry();
$Item_lista = new clsEditableGridItem_lista();
$Item_lista->Initialize();

// Events
include("./InTrTr_items_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-163029F6
$Item_qry->Operation();
$Item_lista->Operation();
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

//Show Page @1-C8F45287
$Item_qry->Show();
$Item_lista->Show();
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
