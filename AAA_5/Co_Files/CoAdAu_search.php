<?php
/**
*    Consulta General de Auxiliares tipo personas.
*    @abstract   Se presenta dos 
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdAu
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*    @see	 CoAdAu_mant.php
*/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include_once (RelativePath . "/LibPhp/ValLib.php");
include_once (RelativePath . "/LibPhp/SegLib.php");
Class clsRecordAux_search { //Aux_search Class @3-94784A53

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

//Class_Initialize Event @3-117FC062
    function clsRecordAux_search()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record Aux_search/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "Aux_search";
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
            $this->ClearParameters->Page = "CoAdAu_search.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @3-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-E7F39E68
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

//Operation Method @3-1D710481
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
        $Redirect = "CoAdAu_search.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdAu_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "ccsForm")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-CEE35C29
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

} //End Aux_search Class @3-FCB6E20C

Class clsEditableGridAux_list { //Aux_list Class @34-C610C344

//Variables @34-89F0195E

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
    var $Sorter_per_CodAuxiliar;
    var $Sorter_per_Apellidos;
    var $Sorter_per_Ruc;
    var $Sorter_per_Categ;
    var $Sorter_per_Estado;
//End Variables

//Class_Initialize Event @34-75607739
    function clsEditableGridAux_list()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid Aux_list/Error";
        $this->ComponentName = "Aux_list";
        $this->CachedColumns["per_CodAuxiliar"][0] = "per_CodAuxiliar";
        $this->ds = new clsAux_listDataSource();

        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 0;
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

        $this->SorterName = CCGetParam("Aux_listOrder", "");
        $this->SorterDirection = CCGetParam("Aux_listDir", "");

        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 10, tpMoving);
        $this->Sorter_per_CodAuxiliar = new clsSorter($this->ComponentName, "Sorter_per_CodAuxiliar", $FileName);
        $this->Sorter_per_Apellidos = new clsSorter($this->ComponentName, "Sorter_per_Apellidos", $FileName);
        $this->Sorter_per_Ruc = new clsSorter($this->ComponentName, "Sorter_per_Ruc", $FileName);
        $this->Sorter_per_Categ = new clsSorter($this->ComponentName, "Sorter_per_Categ", $FileName);
        $this->Sorter_per_Estado = new clsSorter($this->ComponentName, "Sorter_per_Estado", $FileName);
        $this->btSelecciona = new clsButton("btSelecciona");
        $this->codaux_lbl = new clsControl(ccsLabel, "codaux_lbl", "Per Cod Auxiliar", ccsInteger, "");
        $this->per_CodAuxiliar = new clsControl(ccsHidden, "per_CodAuxiliar", "per_CodAuxiliar", ccsText, "");
        $this->per_Apellidos_lbl = new clsControl(ccsLabel, "per_Apellidos_lbl", "Per Apellidos", ccsText, "");
        $this->per_Apellidos = new clsControl(ccsHidden, "per_Apellidos", "per_Apellidos", ccsText, "");
        $this->aux_Clase = new clsControl(ccsHidden, "aux_Clase", "aux_Clase", ccsText, "");                    //fah man
        $this->numid = new clsControl(ccsLabel, "numid", "Per Ruc", ccsText, "");
        $this->d_par_Descripcion = new clsControl(ccsLabel, "d_par_Descripcion", "Per Pais", ccsText, "");
        $this->e_par_Descripcion = new clsControl(ccsLabel, "e_par_Descripcion", "Per Provincia", ccsText, "");
        $this->num_Recs = new clsControl(ccsText, "num_Recs", "num_Recs", ccsText, "");
    }
//End Class_Initialize Event

//Initialize Method @34-E9B30F44
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
    }
//End Initialize Method

//GetFormParameters Method @34-64801E52
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["per_CodAuxiliar"][$RowNumber] = CCGetFromPost("per_CodAuxiliar_" . $RowNumber);
            $this->FormParameters["per_Apellidos"][$RowNumber] = CCGetFromPost("per_Apellidos_" . $RowNumber);
            $this->FormParameters["aux_Clase"][$RowNumber] = CCGetFromPost("aux_Clase_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @34-03082789
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["per_CodAuxiliar"] = $this->CachedColumns["per_CodAuxiliar"][$RowNumber];
            $this->per_CodAuxiliar->SetText($this->FormParameters["per_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->per_Apellidos->SetText($this->FormParameters["per_Apellidos"][$RowNumber], $RowNumber);
            $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);
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

//ValidateRow Method @34-49639A9B
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->per_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->per_Apellidos->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->per_CodAuxiliar->Errors->ToString();
            $errors .= $this->per_Apellidos->Errors->ToString();
            $this->per_CodAuxiliar->Errors->Clear();
            $this->per_Apellidos->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @34-FCB1A943
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["per_CodAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_Apellidos"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @34-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @34-08B09C71
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
        if(strlen(CCGetParam("btSelecciona", ""))) {
            $this->PressedButton = "btSelecciona";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "btSelecciona") {
            if(!CCGetEvent($this->btSelecciona->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @34-E3F0F8F6
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["per_CodAuxiliar"] = $this->CachedColumns["per_CodAuxiliar"][$RowNumber];
            $this->per_CodAuxiliar->SetText($this->FormParameters["per_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->per_Apellidos->SetText($this->FormParameters["per_Apellidos"][$RowNumber], $RowNumber);
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

//FormScript Method @34-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @34-7C475D99
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
                $this->CachedColumns["per_CodAuxiliar"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["per_CodAuxiliar"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @34-CFD93D25
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["per_CodAuxiliar"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @34-AF1419B0
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
                        $this->codaux_lbl->SetValue($this->ds->codaux_lbl->GetValue());
                        $this->per_Apellidos_lbl->SetValue($this->ds->per_Apellidos_lbl->GetValue());
                        $this->numid->SetValue($this->ds->numid->GetValue());
                        $this->d_par_Descripcion->SetValue($this->ds->d_par_Descripcion->GetValue());
                        $this->e_par_Descripcion->SetValue($this->ds->e_par_Descripcion->GetValue());
                    } else {
                        $this->codaux_lbl->SetText("");
                        $this->per_Apellidos_lbl->SetText("");
                        $this->numid->SetText("");
                        $this->d_par_Descripcion->SetText("");
                        $this->e_par_Descripcion->SetText("");
                    }
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["per_CodAuxiliar"][$RowNumber] = $this->ds->CachedColumns["per_CodAuxiliar"];
                        $this->per_CodAuxiliar->SetValue($this->ds->per_CodAuxiliar->GetValue());
                        $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                        $this->aux_Clase->SetValue($this->ds->aux_Clase->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["per_CodAuxiliar"][$RowNumber] = "";
                        $this->per_CodAuxiliar->SetText("");
                        $this->per_Apellidos->SetText("");
                        $this->aux_Clase->SetValue("");
                    } else {
                        $this->per_CodAuxiliar->SetText($this->FormParameters["per_CodAuxiliar"][$RowNumber], $RowNumber);
                        $this->per_Apellidos->SetText($this->FormParameters["per_Apellidos"][$RowNumber], $RowNumber);
                        $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->btSelecciona->Show($RowNumber);
                    $this->codaux_lbl->Show($RowNumber);
                    $this->per_CodAuxiliar->Show($RowNumber);
                    $this->per_Apellidos_lbl->Show($RowNumber);
                    $this->per_Apellidos->Show($RowNumber);
                    $this->aux_Clase->Show($RowNumber);
                    $this->numid->Show($RowNumber);
                    $this->d_par_Descripcion->Show($RowNumber);
                    $this->e_par_Descripcion->Show($RowNumber);
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
                $this->num_Recs->Show();                                                    //fah
            } else {
                $Tpl->block_path = $EditableGridPath;
                $Tpl->parse("NoRecords", false);
            }
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->Navigator1->Show();
        $this->Sorter_per_CodAuxiliar->Show();
        $this->Sorter_per_Apellidos->Show();
        $this->Sorter_per_Ruc->Show();
        $this->Sorter_per_Categ->Show();
        $this->Sorter_per_Estado->Show();

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

} //End Aux_list Class @34-FCB6E20C

class clsAux_listDataSource extends clsDBdatos {  //Aux_listDataSource Class @34-709AFB39

//DataSource Variables @34-4EA1207A
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $codaux_lbl;
    var $per_CodAuxiliar;
    var $per_Apellidos_lbl;
    var $per_Apellidos;
    var $numid;
    var $d_par_Descripcion;
    var $e_par_Descripcion;
//End DataSource Variables

//Class_Initialize Event @34-B5E5D2F3
    function clsAux_listDataSource()
    {
        $this->ErrorBlock = "EditableGrid Aux_list/Error";
        $this->Initialize();
        $this->codaux_lbl = new clsField("codaux_lbl", ccsInteger, "");
        $this->per_CodAuxiliar = new clsField("per_CodAuxiliar", ccsText, "");
        $this->per_Apellidos_lbl = new clsField("per_Apellidos_lbl", ccsText, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->aux_Clase = new clsField("aux_Clase", ccsText, "");
        $this->numid = new clsField("numid", ccsText, "");
        $this->d_par_Descripcion = new clsField("d_par_Descripcion", ccsText, "");
        $this->e_par_Descripcion = new clsField("e_par_Descripcion", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @34-1F18369D
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_per_CodAuxiliar" => array("codaux", ""), 
            "Sorter_per_Apellidos" => array("per_Apellidos", ""), 
            "Sorter_per_Ruc" => array("numid", ""), 
            "Sorter_per_Categ" => array("d_par_Descripcion", ""), 
            "Sorter_per_Estado" => array("e_par_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @34-858E9B2B
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @34-5553708A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $slArg  = CCGetFromGet("pArgs", "X");
//      $slArg  = "A";
//     echo "ARGUM: ". $slArg;
        $slPerSql =   "SELECT p.per_codauxiliar as codaux, p.per_Ruc as numid, c.cat_activo as estado, d.par_Descripcion AS d_par_Descripcion, " .
                                "if(c.cat_activo = 1,'ACTIVO', 'INACT.')  e_par_Descripcion, " .
                                "concat(per_Apellidos, \" \", per_Nombres) AS per_Apellidos, par_Valor1 as aux_Clase " .
                        "FROM ((conpersonas p LEFT JOIN concategorias c ON p.per_CodAuxiliar = c.cat_CodAuxiliar  )  " .
                                "LEFT JOIN genparametros d ON d.par_Clave = 'CAUTI' AND c.cat_Categoria = d.par_Secuencia)  " .
                        "WHERE  " .
                                "( p.per_Apellidos LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
                                "OR p.per_Nombres LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
                                "OR d.par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
                                "OR per_CodAuxiliar LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' ) " ;

        $slPerCnt =     "SELECT COUNT(*) " .
                        "FROM ((conpersonas p LEFT JOIN concategorias c ON p.per_CodAuxiliar = c.cat_CodAuxiliar  )  " .
                                "LEFT JOIN genparametros d ON d.par_Clave = 'CAUTI' AND c.cat_Categoria = d.par_Secuencia)  " .
                        "WHERE  " .
                                "( p.per_Apellidos LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR p.per_Nombres LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
                                "OR d.par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
                                "OR per_CodAuxiliar LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' ) " ;

        $slActCnt =   "SELECT COUNT(*) " .
                        "FROM ((concategorias c INNER JOIN conactivos p ON c.cat_CodAuxiliar = p.act_CodAuxiliar)  " .
                             "INNER JOIN genparametros d ON d.par_Clave = 'CAUTI' AND c.cat_Categoria = d.par_Secuencia)  " .
                        "WHERE  " .
                            " (p.act_descripcion  LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR p.act_descripcion  LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
                            "OR act_CodAuxiliar LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR d.par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%'  " .
                            ")   ";

        $slActSql =    "SELECT 	p.act_codauxiliar as codaux, p.act_NumSerie as numid, c.cat_activo as estado,  d.par_Descripcion AS d_par_Descripcion, " .
                            "if(c.cat_activo = 1,'ACTIVO', 'INACT.') AS e_par_Descripcion,  " .
                            "concat(act_descripcion, \" \", act_descripcion1) AS per_Apellidos, par_Valor1 as aux_Clase " .
                        "FROM ((concategorias c RIGHT JOIN conactivos p ON c.cat_CodAuxiliar = p.act_CodAuxiliar)  " .
                            "INNER JOIN genparametros d ON d.par_Clave = 'CAUTI' AND c.cat_Categoria = d.par_Secuencia)  " .
                        "WHERE  " .
                            "(p.act_descripcion  LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR p.act_descripcion1  LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
                            "OR act_CodAuxiliar LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR d.par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%'  " .
                            ")   ";

        switch ($slArg) {
            case "A":
                $this->CountSQL = $slActCnt;
                $this->SQL = $slActSql;
                break;
            case "P":
                $this->CountSQL =   $slPerCnt;
                $this->SQL = $slPerSql;
                break;
            default;
                $this->CountSQL =   $slPerCnt;
                $this->SQL = $slPerSql  . " UNION " . $slActSql;
            }
//echo "<br>" . $this->SQL;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @34-447B2972
    function SetValues()
    {
        $this->CachedColumns["per_CodAuxiliar"] = $this->f("per_CodAuxiliar");
        $this->codaux_lbl->SetDBValue(trim($this->f("codaux")));
        $this->per_CodAuxiliar->SetDBValue($this->f("codaux"));
        $this->per_Apellidos_lbl->SetDBValue($this->f("per_Apellidos"));
        $this->per_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->aux_Clase->SetDBValue($this->f("aux_Clase"));
        $this->numid->SetDBValue($this->f("numid"));
        $this->d_par_Descripcion->SetDBValue($this->f("d_par_Descripcion"));
        $this->e_par_Descripcion->SetDBValue($this->f("e_par_Descripcion"));
    }
//End SetValues Method

} //End Aux_listDataSource Class @34-FCB6E20C

//Initialize Page @1-7C4F4F36
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

$FileName = "CoAdAu_search.php";
$Redirect = "";
$TemplateFileName = "CoAdAu_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-7E21B06D
$DBdatos = new clsDBdatos();

// Controls
$Aux_search = new clsRecordAux_search();
$Aux_list = new clsEditableGridAux_list();
$Aux_list->Initialize();

// Events
include("./CoAdAu_search_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-4E3D046E
$Aux_search->Operation();
$Aux_list->Operation();
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

//Show Page @1-5424860E
$Aux_search->Show();
$Aux_list->Show();
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
