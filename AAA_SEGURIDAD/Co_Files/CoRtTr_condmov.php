<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @7-11FBC0D5
include_once(RelativePath . "/Rt_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsEditableGridfiscompras_qry { //fiscompras_qry Class @2-5E7410CA

//Variables @2-EA9679FB

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
//End Variables

//Class_Initialize Event @2-750853B2
    function clsEditableGridfiscompras_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid fiscompras_qry/Error";
        $this->ComponentName = "fiscompras_qry";
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 1;
        $this->InsertAllowed = true;
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

        $this->s_tmp_anio = new clsControl(ccsListBox, "s_tmp_anio", "s_tmp_anio", ccsText, "");
        $this->s_tmp_anio->DSType = dsSQL;
        list($this->s_tmp_anio->BoundColumn, $this->s_tmp_anio->TextColumn, $this->s_tmp_anio->DBFormat) = array("tmp_Anio", "tmp_Anio", "");
        $this->s_tmp_anio->ds = new clsDBdatos();
        $this->s_tmp_anio->ds->SQL = "SELECT 	DISTINCT YEAR(fechaRegistro) as tmp_Anio " .
        "FROM fiscompras";
        $this->s_tmp_mes = new clsControl(ccsListBox, "s_tmp_mes", "s_tmp_mes", ccsText, "");
        $this->s_tmp_mes->DSType = dsSQL;
        list($this->s_tmp_mes->BoundColumn, $this->s_tmp_mes->TextColumn, $this->s_tmp_mes->DBFormat) = array("tmp_Mes", "tmp_NomMes", "");
        $this->s_tmp_mes->ds = new clsDBdatos();
        $this->s_tmp_mes->ds->SQL = "SELECT 	DISTINCT monthname(fechaRegistro) as tmp_NomMes,  " .
        "month(fechaRegistro) as tmp_Mes " .
        "FROM fiscompras " .
        "";
        $this->s_tmp_mes->ds->Order = "1";
    }
//End Class_Initialize Event

//GetFormParameters Method @2-5C4E782F
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["s_tmp_anio"][$RowNumber] = CCGetFromPost("s_tmp_anio_" . $RowNumber);
            $this->FormParameters["s_tmp_mes"][$RowNumber] = CCGetFromPost("s_tmp_mes_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-7857CE81
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->s_tmp_anio->SetText($this->FormParameters["s_tmp_anio"][$RowNumber], $RowNumber);
            $this->s_tmp_mes->SetText($this->FormParameters["s_tmp_mes"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-E1B01B24
    function ValidateRow($RowNumber)
    {
        $this->s_tmp_anio->Validate();
        $this->s_tmp_mes->Validate();
        $this->RowErrors = new clsErrors();
        $errors = $this->s_tmp_anio->Errors->ToString();
        $errors .= $this->s_tmp_mes->Errors->ToString();
        $this->s_tmp_anio->Errors->Clear();
        $this->s_tmp_mes->Errors->Clear();
        $errors .=$this->RowErrors->ToString();
        $this->RowsErrors[$RowNumber] = $errors;
        return $errors ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @2-8D4E29CF
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["s_tmp_anio"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["s_tmp_mes"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @2-E8CE9E37
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-CA4CE3B3
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted)
            return;

        $this->GetFormParameters();

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
    }
//End Operation Method

//UpdateGrid Method @2-F9F260B2
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->s_tmp_anio->SetText($this->FormParameters["s_tmp_anio"][$RowNumber], $RowNumber);
            $this->s_tmp_mes->SetText($this->FormParameters["s_tmp_mes"][$RowNumber], $RowNumber);
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

//FormScript Method @2-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
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

//Show Method @2-C2F43A7B
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_tmp_anio->Prepare();
        $this->s_tmp_mes->Prepare();

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
        if($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed))
        {
            do
            {
                $RowNumber++;
                if($is_next_record) {
                    $NonEmptyRows++;
                } else {
                }
                if(!$this->FormSubmitted && $is_next_record) {
                } else if (!$this->FormSubmitted){
                    $this->s_tmp_anio->SetText("");
                    $this->s_tmp_mes->SetText("");
                } else {
                    $this->s_tmp_anio->SetText($this->FormParameters["s_tmp_anio"][$RowNumber], $RowNumber);
                    $this->s_tmp_mes->SetText($this->FormParameters["s_tmp_mes"][$RowNumber], $RowNumber);
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->s_tmp_anio->Show($RowNumber);
                $this->s_tmp_mes->Show($RowNumber);
                if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                    $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                    $Tpl->parse("RowError", false);
                } else {
                    $Tpl->setblockvar("RowError", "");
                }
                $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                $Tpl->parse();
            $EmptyRowsLeft--;
            } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
        } else {
            $Tpl->block_path = $EditableGridPath;
            $Tpl->parse("NoRecords", false);
        }

        $Tpl->block_path = $EditableGridPath;

        if($this->CheckErrors()) {
            $Error .= $this->Errors->ToString();
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
    }
//End Show Method

} //End fiscompras_qry Class @2-FCB6E20C

//Initialize Page @1-D42363E7
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

$FileName = "CoRtTr_condmov.php";
$Redirect = "";
$TemplateFileName = "CoRtTr_condmov.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-218C2F85
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$fiscompras_qry = new clsEditableGridfiscompras_qry();

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

//Execute Components @1-989C4A12
$Cabecera->Operations();
$fiscompras_qry->Operation();
//End Execute Components

//Go to destination page @1-D0C27255
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($fiscompras_qry);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-D8A306E0
$Cabecera->Show("Cabecera");
$fiscompras_qry->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$ODIPB8C9S7B9N2Q = array("<center><font face=\"Arial\"><small>Ge&#110;&#1","01;r&#97;ted <!-- CCS -->w&#105;th <!-- CCS --",">&#67;&#111;&#100;e&#67;&#104;a&#114;g&#101; <!--"," CCS -->&#83;tud&#105;&#111;.</small></font>","</center>");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($ODIPB8C9S7B9N2Q,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($ODIPB8C9S7B9N2Q,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($ODIPB8C9S7B9N2Q,"");
}
echo $main_block;
//End Show Page

//Unload Page @1-A6554069
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($fiscompras_qry);
unset($Tpl);
//End Unload Page


?>
