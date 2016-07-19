<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @27-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordbuques_query { //buques_query Class @3-E7DEB81E

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

//Class_Initialize Event @3-87CAB82C
    function clsRecordbuques_query()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record buques_query/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "buques_query";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_keyword", "ccsForm"));
            $this->ClearParameters->Page = "LiAdBu_search.php";
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

//CheckErrors Method @3-B0E04A52
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-0E412FAB
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
        $Redirect = "LiAdBu_search.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdBu_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "ccsForm")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-559D8FC0
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
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End buques_query Class @3-FCB6E20C

class clsGridbuques_list { //buques_list class @2-472B6C3A

//Variables @2-DF6D2455

    // Public variables
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    // Grid Controls
    var $StaticControls; var $RowControls;
    var $AltRowControls;
    var $IsAltRow;
    var $Navigator;
    var $Sorter_buq_CodBuque;
    var $Sorter_buq_Abreviatura;
    var $Sorter_buq_Descripcion;
    var $Sorter_buq_Pais;
//End Variables

//Class_Initialize Event @2-2C949709
    function clsGridbuques_list()
    {
        global $FileName;
        $this->ComponentName = "buques_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid buques_list";
        $this->ds = new clsbuques_listDataSource();
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("buques_listOrder", "");
        $this->SorterDirection = CCGetParam("buques_listDir", "");

        $this->buq_CodBuque = new clsControl(ccsHidden, "buq_CodBuque", "buq_CodBuque", ccsInteger, "", CCGetRequestParam("buq_CodBuque", ccsGet));
        $this->Button1 = new clsButton("Button1");
        $this->buq_Abreviatura = new clsControl(ccsTextBox, "buq_Abreviatura", "buq_Abreviatura", ccsText, "", CCGetRequestParam("buq_Abreviatura", ccsGet));
        $this->buq_Descripcion = new clsControl(ccsTextBox, "buq_Descripcion", "buq_Descripcion", ccsText, "", CCGetRequestParam("buq_Descripcion", ccsGet));
        $this->buq_Pais = new clsControl(ccsTextBox, "buq_Pais", "buq_Pais", ccsInteger, "", CCGetRequestParam("buq_Pais", ccsGet));
        $this->Alt_buq_CodBuque = new clsControl(ccsHidden, "Alt_buq_CodBuque", "Alt_buq_CodBuque", ccsInteger, "", CCGetRequestParam("Alt_buq_CodBuque", ccsGet));
        $this->Button2 = new clsButton("Button2");
        $this->Alt_buq_Abreviatura = new clsControl(ccsTextBox, "Alt_buq_Abreviatura", "Alt_buq_Abreviatura", ccsText, "", CCGetRequestParam("Alt_buq_Abreviatura", ccsGet));
        $this->Alt_buq_Descripcion = new clsControl(ccsTextBox, "Alt_buq_Descripcion", "Alt_buq_Descripcion", ccsText, "", CCGetRequestParam("Alt_buq_Descripcion", ccsGet));
        $this->Alt_buq_Pais = new clsControl(ccsTextBox, "Alt_buq_Pais", "Alt_buq_Pais", ccsInteger, "", CCGetRequestParam("Alt_buq_Pais", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_buq_CodBuque = new clsSorter($this->ComponentName, "Sorter_buq_CodBuque", $FileName);
        $this->Sorter_buq_Abreviatura = new clsSorter($this->ComponentName, "Sorter_buq_Abreviatura", $FileName);
        $this->Sorter_buq_Descripcion = new clsSorter($this->ComponentName, "Sorter_buq_Descripcion", $FileName);
        $this->Sorter_buq_Pais = new clsSorter($this->ComponentName, "Sorter_buq_Pais", $FileName);
    }
//End Class_Initialize Event

//Initialize Method @2-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-2BA59E48
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->Prepare();
        $this->ds->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        $is_next_record = $this->ds->next_record();
        if($is_next_record && $ShownRecords < $this->PageSize)
        {
            do {
                    $this->ds->SetValues();
                if(!$this->IsAltRow)
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                    $this->buq_CodBuque->SetValue($this->ds->buq_CodBuque->GetValue());
                    $this->buq_Abreviatura->SetValue($this->ds->buq_Abreviatura->GetValue());
                    $this->buq_Descripcion->SetValue($this->ds->buq_Descripcion->GetValue());
                    $this->buq_Pais->SetValue($this->ds->buq_Pais->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->buq_CodBuque->Show();
                    $this->Button1->Show();
                    $this->buq_Abreviatura->Show();
                    $this->buq_Descripcion->Show();
                    $this->buq_Pais->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_buq_CodBuque->SetValue($this->ds->Alt_buq_CodBuque->GetValue());
                    $this->Alt_buq_Abreviatura->SetValue($this->ds->Alt_buq_Abreviatura->GetValue());
                    $this->Alt_buq_Descripcion->SetValue($this->ds->Alt_buq_Descripcion->GetValue());
                    $this->Alt_buq_Pais->SetValue($this->ds->Alt_buq_Pais->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_buq_CodBuque->Show();
                    $this->Button2->Show();
                    $this->Alt_buq_Abreviatura->Show();
                    $this->Alt_buq_Descripcion->Show();
                    $this->Alt_buq_Pais->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parseto("AltRow", true, "Row");
                }
                $this->IsAltRow = (!$this->IsAltRow);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record && $ShownRecords < $this->PageSize);
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Navigator->Show();
        $this->btNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_buq_CodBuque->Show();
        $this->Sorter_buq_Abreviatura->Show();
        $this->Sorter_buq_Descripcion->Show();
        $this->Sorter_buq_Pais->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-2C72FE95
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->buq_CodBuque->Errors->ToString();
        $errors .= $this->buq_Abreviatura->Errors->ToString();
        $errors .= $this->buq_Descripcion->Errors->ToString();
        $errors .= $this->buq_Pais->Errors->ToString();
        $errors .= $this->Alt_buq_CodBuque->Errors->ToString();
        $errors .= $this->Alt_buq_Abreviatura->Errors->ToString();
        $errors .= $this->Alt_buq_Descripcion->Errors->ToString();
        $errors .= $this->Alt_buq_Pais->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End buques_list Class @2-FCB6E20C

class clsbuques_listDataSource extends clsDBdatos {  //buques_listDataSource Class @2-C215821A

//DataSource Variables @2-0C7AF8E4
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $buq_CodBuque;
    var $buq_Abreviatura;
    var $buq_Descripcion;
    var $buq_Pais;
    var $Alt_buq_CodBuque;
    var $Alt_buq_Abreviatura;
    var $Alt_buq_Descripcion;
    var $Alt_buq_Pais;
//End DataSource Variables

//Class_Initialize Event @2-CA8CEE82
    function clsbuques_listDataSource()
    {
        $this->ErrorBlock = "Grid buques_list";
        $this->Initialize();
        $this->buq_CodBuque = new clsField("buq_CodBuque", ccsInteger, "");
        $this->buq_Abreviatura = new clsField("buq_Abreviatura", ccsText, "");
        $this->buq_Descripcion = new clsField("buq_Descripcion", ccsText, "");
        $this->buq_Pais = new clsField("buq_Pais", ccsInteger, "");
        $this->Alt_buq_CodBuque = new clsField("Alt_buq_CodBuque", ccsInteger, "");
        $this->Alt_buq_Abreviatura = new clsField("Alt_buq_Abreviatura", ccsText, "");
        $this->Alt_buq_Descripcion = new clsField("Alt_buq_Descripcion", ccsText, "");
        $this->Alt_buq_Pais = new clsField("Alt_buq_Pais", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-CD7740F5
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "buq_Descripcion";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_buq_CodBuque" => array("buq_CodBuque", ""), 
            "Sorter_buq_Abreviatura" => array("buq_Abreviatura", ""), 
            "Sorter_buq_Descripcion" => array("buq_Descripcion", ""), 
            "Sorter_buq_Pais" => array("buq_Pais", "")));
    }
//End SetOrder Method

//Prepare Method @2-528757BB
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("2", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "buq_Abreviatura", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "buq_Descripcion", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->Where = $this->wp->opOR(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-9F2011C1
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM liqbuques";
        $this->SQL = "SELECT *  " .
        "FROM liqbuques";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-43E7DED0
    function SetValues()
    {
        $this->buq_CodBuque->SetDBValue(trim($this->f("buq_CodBuque")));
        $this->buq_Abreviatura->SetDBValue($this->f("buq_Abreviatura"));
        $this->buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->buq_Pais->SetDBValue(trim($this->f("buq_Pais")));
        $this->Alt_buq_CodBuque->SetDBValue(trim($this->f("buq_CodBuque")));
        $this->Alt_buq_Abreviatura->SetDBValue($this->f("buq_Abreviatura"));
        $this->Alt_buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->Alt_buq_Pais->SetDBValue(trim($this->f("buq_Pais")));
    }
//End SetValues Method

} //End buques_listDataSource Class @2-FCB6E20C

//Initialize Page @1-B81B946F
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

$FileName = "LiAdBu_search.php";
$Redirect = "";
$TemplateFileName = "LiAdBu_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-282078A8
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$buques_query = new clsRecordbuques_query();
$buques_list = new clsGridbuques_list();
$buques_list->Initialize();

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

//Execute Components @1-5D25AF51
$Cabecera->Operations();
$buques_query->Operation();
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

//Show Page @1-5C3F7B94
$Cabecera->Show("Cabecera");
$buques_query->Show();
$buques_list->Show();
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
