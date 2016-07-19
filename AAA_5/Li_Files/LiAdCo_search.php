<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsRecordcomponent_qry { //component_qry Class @20-47024E25

//Variables @20-CB19EB75

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

//Class_Initialize Event @20-F60CC539
    function clsRecordcomponent_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record component_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "component_qry";
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
            $this->s_TipoComponente = new clsControl(ccsRadioButton, "s_TipoComponente", "s_TipoComponente", ccsText, "", CCGetRequestParam("s_TipoComponente", $Method));
            $this->s_TipoComponente->DSType = dsListOfValues;
            $this->s_TipoComponente->Values = array(array("1", "Cartón"), array("2", "Plástico"), array("3", "Materiales"), array("4", "Etiquetas"));
            $this->s_TipoComponente->HTML = true;
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_keyword", "s_TipoComponente", "ccsForm"));
            $this->ClearParameters->Page = "LiAdCo_search.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @20-DA35F18A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $Validation = ($this->s_TipoComponente->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @20-ECD425F1
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pMensj->Errors->Count());
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->s_TipoComponente->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @20-434D381F
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
        $Redirect = "LiAdCo_search.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdCo_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "s_TipoComponente", "ccsForm")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @20-5714FF65
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_TipoComponente->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->pMensj->Errors->ToString();
            $Error .= $this->s_keyword->Errors->ToString();
            $Error .= $this->s_TipoComponente->Errors->ToString();
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
        $this->s_TipoComponente->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End component_qry Class @20-FCB6E20C

class clsGridcomponent_list { //component_list class @3-C7389760

//Variables @3-4ADF8E0A

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
    var $Sorter_cte_Codigo;
    var $Sorter_cte_Referencia;
    var $Sorter_cte_Descripcion;
    var $Navigator;
//End Variables

//Class_Initialize Event @3-9B56DB37
    function clsGridcomponent_list()
    {
        global $FileName;
        $this->ComponentName = "component_list";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid component_list";
        $this->ds = new clscomponent_listDataSource();
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("component_listOrder", "");
        $this->SorterDirection = CCGetParam("component_listDir", "");

        $this->cte_Clase = new clsControl(ccsHidden, "cte_Clase", "cte_Clase", ccsInteger, "", CCGetRequestParam("cte_Clase", ccsGet));
        $this->cte_Codigo = new clsControl(ccsHidden, "cte_Codigo", "cte_Codigo", ccsInteger, "", CCGetRequestParam("cte_Codigo", ccsGet));
        $this->Button1 = new clsButton("Button1");
        $this->cte_Referencia = new clsControl(ccsTextBox, "cte_Referencia", "cte_Referencia", ccsText, "", CCGetRequestParam("cte_Referencia", ccsGet));
        $this->cte_Descripcion = new clsControl(ccsTextBox, "cte_Descripcion", "cte_Descripcion", ccsText, "", CCGetRequestParam("cte_Descripcion", ccsGet));
        $this->Button2 = new clsButton("Button2");
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_cte_Codigo = new clsSorter($this->ComponentName, "Sorter_cte_Codigo", $FileName);
        $this->Sorter_cte_Referencia = new clsSorter($this->ComponentName, "Sorter_cte_Referencia", $FileName);
        $this->Sorter_cte_Descripcion = new clsSorter($this->ComponentName, "Sorter_cte_Descripcion", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpMoving);
    }
//End Class_Initialize Event

//Initialize Method @3-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @3-411787E9
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
        $this->ds->Parameters["urls_TipoComponente"] = CCGetFromGet("s_TipoComponente", "");

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
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->cte_Clase->SetValue($this->ds->cte_Clase->GetValue());
                $this->cte_Codigo->SetValue($this->ds->cte_Codigo->GetValue());
                $this->cte_Referencia->SetValue($this->ds->cte_Referencia->GetValue());
                $this->cte_Descripcion->SetValue($this->ds->cte_Descripcion->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->cte_Clase->Show();
                $this->cte_Codigo->Show();
                $this->Button1->Show();
                $this->cte_Referencia->Show();
                $this->cte_Descripcion->Show();
                $this->Button2->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
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
        $this->btNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_cte_Codigo->Show();
        $this->Sorter_cte_Referencia->Show();
        $this->Sorter_cte_Descripcion->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @3-45893499
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->cte_Clase->Errors->ToString();
        $errors .= $this->cte_Codigo->Errors->ToString();
        $errors .= $this->cte_Referencia->Errors->ToString();
        $errors .= $this->cte_Descripcion->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End component_list Class @3-FCB6E20C

class clscomponent_listDataSource extends clsDBdatos {  //component_listDataSource Class @3-C703C670

//DataSource Variables @3-B505D79D
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $cte_Clase;
    var $cte_Codigo;
    var $cte_Referencia;
    var $cte_Descripcion;
//End DataSource Variables

//Class_Initialize Event @3-C6C82EF0
    function clscomponent_listDataSource()
    {
        $this->ErrorBlock = "Grid component_list";
        $this->Initialize();
        $this->cte_Clase = new clsField("cte_Clase", ccsInteger, "");
        $this->cte_Codigo = new clsField("cte_Codigo", ccsInteger, "");
        $this->cte_Referencia = new clsField("cte_Referencia", ccsText, "");
        $this->cte_Descripcion = new clsField("cte_Descripcion", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @3-DB82EF6D
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cte_Codigo" => array("cte_Codigo", ""), 
            "Sorter_cte_Referencia" => array("cte_Referencia", ""), 
            "Sorter_cte_Descripcion" => array("cte_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @3-49146704
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("2", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("3", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("4", "urls_TipoComponente", ccsInteger, "", "", $this->Parameters["urls_TipoComponente"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cte_Codigo", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opBeginsWith, "cte_Referencia", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opBeginsWith, "cte_Descripcion", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "cte_Clase", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsInteger),false);
        $this->Where = $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]);
    }
//End Prepare Method

//Open Method @3-504F4395
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

//SetValues Method @3-3DA668A3
    function SetValues()
    {
        $this->cte_Clase->SetDBValue(trim($this->f("cte_Clase")));
        $this->cte_Codigo->SetDBValue(trim($this->f("cte_Codigo")));
        $this->cte_Referencia->SetDBValue($this->f("cte_Referencia"));
        $this->cte_Descripcion->SetDBValue($this->f("cte_Descripcion"));
    }
//End SetValues Method

} //End component_listDataSource Class @3-FCB6E20C

//Initialize Page @1-5D9B8690
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

$FileName = "LiAdCo_search.php";
$Redirect = "";
$TemplateFileName = "LiAdCo_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-B4F6E775
$DBdatos = new clsDBdatos();

// Controls
$component_qry = new clsRecordcomponent_qry();
$component_list = new clsGridcomponent_list();
$component_list->Initialize();

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

//Execute Components @1-E79107FE
$component_qry->Operation();
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

//Show Page @1-1966D58A
$component_qry->Show();
$component_list->Show();
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
