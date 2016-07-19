<?php

//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @36-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation
class clsRecordliquidac_qry { //liquidac_qry Class @2-66285507

//Variables @2-CB19EB75

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

//Class_Initialize Event @2-8623A631
    function clsRecordliquidac_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liquidac_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "liquidac_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pro_Ano = new clsControl(ccsTextBox, "pro_Ano", "pro_Ano", ccsInteger, "", CCGetRequestParam("pro_Ano", $Method));
            $this->pro_Semana = new clsControl(ccsTextBox, "pro_Semana", "pro_Semana", ccsInteger, "", CCGetRequestParam("pro_Semana", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            if(!$this->FormSubmitted) {
                if(!is_array($this->pro_Ano->Value) && !strlen($this->pro_Ano->Value) && $this->pro_Ano->Value !== false)
                $this->pro_Ano->SetText($_SESSION['anio']);
                if(!is_array($this->pro_Semana->Value) && !strlen($this->pro_Semana->Value) && $this->pro_Semana->Value !== false)
                $this->pro_Semana->SetText($_SESSION['semana']);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @2-B76DFCE3
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->pro_Ano->Validate() && $Validation);
        $Validation = ($this->pro_Semana->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-355879D4
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pro_Ano->Errors->Count());
        $errors = ($errors || $this->pro_Semana->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-5BB66EE6
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
        $Redirect = "LiLiLi_search.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiLiLi_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-219F35E2
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
            $Error .= $this->pro_Ano->Errors->ToString();
            $Error .= $this->pro_Semana->Errors->ToString();
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

        $this->pro_Ano->Show();
        $this->pro_Semana->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liquidac_qry Class @2-FCB6E20C

class clsGridliquidac_list { //liquidac_list class @7-61B73CB0

//Variables @7-4814E7C0

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
    var $Sorter_liq_numliquida;
    var $Sorter_pro_id;
    var $Sorter_tmp_nombre;
    var $Sorter_tmp_cantembarcada;
    var $Sorter_sum_liq_valtotal_rub_inddbcr_1;
    var $Navigator;
//End Variables

//Class_Initialize Event @7-18D8F0B6
    function clsGridliquidac_list()
    {
        global $FileName;
        $this->ComponentName = "liquidac_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid liquidac_list";
        $this->ds = new clsliquidac_listDataSource();
        $this->PageSize = 30;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("liquidac_listOrder", "");
        $this->SorterDirection = CCGetParam("liquidac_listDir", "");

        $this->liq_numliquida = new clsControl(ccsHidden, "liq_numliquida", "liq_numliquida", ccsInteger, "", CCGetRequestParam("liq_numliquida", ccsGet));
        $this->btMantto1 = new clsButton("btMantto1");
        $this->pro_id = new clsControl(ccsLabel, "pro_id", "pro_id", ccsInteger, "", CCGetRequestParam("pro_id", ccsGet));
        $this->tmp_nombre = new clsControl(ccsLabel, "tmp_nombre", "tmp_nombre", ccsText, "", CCGetRequestParam("tmp_nombre", ccsGet));
        $this->tmp_cantembarcada = new clsControl(ccsLabel, "tmp_cantembarcada", "tmp_cantembarcada", ccsFloat, "", CCGetRequestParam("tmp_cantembarcada", ccsGet));
        $this->sum_liq_valtotal_rub_inddbcr_1 = new clsControl(ccsLabel, "sum_liq_valtotal_rub_inddbcr_1", "sum_liq_valtotal_rub_inddbcr_1", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("sum_liq_valtotal_rub_inddbcr_1", ccsGet));
        $this->Alt_liq_numliquida = new clsControl(ccsHidden, "Alt_liq_numliquida", "Alt_liq_numliquida", ccsInteger, "", CCGetRequestParam("Alt_liq_numliquida", ccsGet));
        $this->btMantto2 = new clsButton("btMantto2");
        $this->Alt_pro_id = new clsControl(ccsLabel, "Alt_pro_id", "Alt_pro_id", ccsInteger, "", CCGetRequestParam("Alt_pro_id", ccsGet));
        $this->Alt_tmp_nombre = new clsControl(ccsLabel, "Alt_tmp_nombre", "Alt_tmp_nombre", ccsText, "", CCGetRequestParam("Alt_tmp_nombre", ccsGet));
        $this->Alt_tmp_cantembarcada = new clsControl(ccsLabel, "Alt_tmp_cantembarcada", "Alt_tmp_cantembarcada", ccsFloat, "", CCGetRequestParam("Alt_tmp_cantembarcada", ccsGet));
        $this->Alt_sum_liq_valtotal_rub_inddbcr_1 = new clsControl(ccsLabel, "Alt_sum_liq_valtotal_rub_inddbcr_1", "Alt_sum_liq_valtotal_rub_inddbcr_1", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("Alt_sum_liq_valtotal_rub_inddbcr_1", ccsGet));
        $this->tmp_res002_TotalRecords = new clsControl(ccsLabel, "tmp_res002_TotalRecords", "tmp_res002_TotalRecords", ccsText, "", CCGetRequestParam("tmp_res002_TotalRecords", ccsGet));
        $this->Sorter_liq_numliquida = new clsSorter($this->ComponentName, "Sorter_liq_numliquida", $FileName);
        $this->Sorter_pro_id = new clsSorter($this->ComponentName, "Sorter_pro_id", $FileName);
        $this->Sorter_tmp_nombre = new clsSorter($this->ComponentName, "Sorter_tmp_nombre", $FileName);
        $this->Sorter_tmp_cantembarcada = new clsSorter($this->ComponentName, "Sorter_tmp_cantembarcada", $FileName);
        $this->Sorter_sum_liq_valtotal_rub_inddbcr_1 = new clsSorter($this->ComponentName, "Sorter_sum_liq_valtotal_rub_inddbcr_1", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
    }
//End Class_Initialize Event

//Initialize Method @7-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @7-0290C7FB
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
        $this->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");

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
                    $this->liq_numliquida->SetValue($this->ds->liq_numliquida->GetValue());
                    $this->pro_id->SetValue($this->ds->pro_id->GetValue());
                    $this->tmp_nombre->SetValue($this->ds->tmp_nombre->GetValue());
                    $this->tmp_cantembarcada->SetValue($this->ds->tmp_cantembarcada->GetValue());
                    $this->sum_liq_valtotal_rub_inddbcr_1->SetValue($this->ds->sum_liq_valtotal_rub_inddbcr_1->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->liq_numliquida->Show();
                    $this->btMantto1->Show();
                    $this->pro_id->Show();
                    $this->tmp_nombre->Show();
                    $this->tmp_cantembarcada->Show();
                    $this->sum_liq_valtotal_rub_inddbcr_1->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_liq_numliquida->SetValue($this->ds->Alt_liq_numliquida->GetValue());
                    $this->Alt_pro_id->SetValue($this->ds->Alt_pro_id->GetValue());
                    $this->Alt_tmp_nombre->SetValue($this->ds->Alt_tmp_nombre->GetValue());
                    $this->Alt_tmp_cantembarcada->SetValue($this->ds->Alt_tmp_cantembarcada->GetValue());
                    $this->Alt_sum_liq_valtotal_rub_inddbcr_1->SetValue($this->ds->Alt_sum_liq_valtotal_rub_inddbcr_1->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_liq_numliquida->Show();
                    $this->btMantto2->Show();
                    $this->Alt_pro_id->Show();
                    $this->Alt_tmp_nombre->Show();
                    $this->Alt_tmp_cantembarcada->Show();
                    $this->Alt_sum_liq_valtotal_rub_inddbcr_1->Show();
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
        $this->tmp_res002_TotalRecords->Show();
        $this->Sorter_liq_numliquida->Show();
        $this->Sorter_pro_id->Show();
        $this->Sorter_tmp_nombre->Show();
        $this->Sorter_tmp_cantembarcada->Show();
        $this->Sorter_sum_liq_valtotal_rub_inddbcr_1->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @7-43F880F8
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->liq_numliquida->Errors->ToString();
        $errors .= $this->pro_id->Errors->ToString();
        $errors .= $this->tmp_nombre->Errors->ToString();
        $errors .= $this->tmp_cantembarcada->Errors->ToString();
        $errors .= $this->sum_liq_valtotal_rub_inddbcr_1->Errors->ToString();
        $errors .= $this->Alt_liq_numliquida->Errors->ToString();
        $errors .= $this->Alt_pro_id->Errors->ToString();
        $errors .= $this->Alt_tmp_nombre->Errors->ToString();
        $errors .= $this->Alt_tmp_cantembarcada->Errors->ToString();
        $errors .= $this->Alt_sum_liq_valtotal_rub_inddbcr_1->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End liquidac_list Class @7-FCB6E20C

class clsliquidac_listDataSource extends clsDBdatos {  //liquidac_listDataSource Class @7-0682649D

//DataSource Variables @7-15A608A8
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $liq_numliquida;
    var $pro_id;
    var $tmp_nombre;
    var $tmp_cantembarcada;
    var $sum_liq_valtotal_rub_inddbcr_1;
    var $Alt_liq_numliquida;
    var $Alt_pro_id;
    var $Alt_tmp_nombre;
    var $Alt_tmp_cantembarcada;
    var $Alt_sum_liq_valtotal_rub_inddbcr_1;
//End DataSource Variables

//Class_Initialize Event @7-37F1523E
    function clsliquidac_listDataSource()
    {
        $this->ErrorBlock = "Grid liquidac_list";
        $this->Initialize();
        $this->liq_numliquida = new clsField("liq_numliquida", ccsInteger, "");
        $this->pro_id = new clsField("pro_id", ccsInteger, "");
        $this->tmp_nombre = new clsField("tmp_nombre", ccsText, "");
        $this->tmp_cantembarcada = new clsField("tmp_cantembarcada", ccsFloat, "");
        $this->sum_liq_valtotal_rub_inddbcr_1 = new clsField("sum_liq_valtotal_rub_inddbcr_1", ccsFloat, "");
        $this->Alt_liq_numliquida = new clsField("Alt_liq_numliquida", ccsInteger, "");
        $this->Alt_pro_id = new clsField("Alt_pro_id", ccsInteger, "");
        $this->Alt_tmp_nombre = new clsField("Alt_tmp_nombre", ccsText, "");
        $this->Alt_tmp_cantembarcada = new clsField("Alt_tmp_cantembarcada", ccsFloat, "");
        $this->Alt_sum_liq_valtotal_rub_inddbcr_1 = new clsField("Alt_sum_liq_valtotal_rub_inddbcr_1", ccsFloat, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-110729E3
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_liq_numliquida" => array("liq_numliquida", ""), 
            "Sorter_pro_id" => array("pro_id", ""), 
            "Sorter_tmp_nombre" => array("tmp_nombre", ""), 
            "Sorter_tmp_cantembarcada" => array("tmp_cantembarcada", ""), 
            "Sorter_sum_liq_valtotal_rub_inddbcr_1" => array("5", "")));
    }
//End SetOrder Method

//Prepare Method @7-D4FA3803
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlpro_Ano", ccsInteger, "", "", $this->Parameters["urlpro_Ano"], 0, false);
        $this->wp->AddParameter("2", "urlpro_Semana", ccsInteger, "", "", $this->Parameters["urlpro_Semana"], 0, false);
    }
//End Prepare Method

//Open Method @7-6FD54C0F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM (((liqliquidaciones inner join liqprocesos on liq_numproceso = liqprocesos.pro_ID ) " .
        "             left join concomprobantes on liq_numliquida = com_numcomp ) " .
        "             left join conpersonas on per_codauxiliar = com_codreceptor ) " .
        "             left join liqrubros on rub_codrubro = liq_codrubro " .
        "WHERE com_tipocomp = \"LQ\" AND  " .
        "      liqprocesos.pro_AnoProceso = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " AND " .
        "      liqprocesos.pro_semana = " . $this->SQLValue($this->wp->GetDBValue("2"), ccsInteger) . "   " .
        "GROUP BY liqprocesos.pro_id, tmp_nombre, liqliquidaciones.liq_numliquida";
        $this->SQL = "SELECT liqprocesos.pro_id,  " .
        "concat(per_Apellidos, ' ', per_nombres) as tmp_nombre, " .
        "                 liq_numliquida,  sum(liq_cantidad), " .
        "                 sum(liq_valtotal * rub_inddbcr) " .
        "FROM (((liqliquidaciones inner join liqprocesos on liq_numproceso = liqprocesos.pro_ID ) " .
        "             left join concomprobantes on liq_numliquida = com_numcomp ) " .
        "             left join conpersonas on per_codauxiliar = com_codreceptor ) " .
        "             left join liqrubros on rub_codrubro = liq_codrubro " .
        "WHERE com_tipocomp = \"LQ\" AND  " .
        "      liqprocesos.pro_AnoProceso = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " AND " .
        "      liqprocesos.pro_semana = " . $this->SQLValue($this->wp->GetDBValue("2"), ccsInteger) . "   " .
        "GROUP BY liqprocesos.pro_id, tmp_nombre, liqliquidaciones.liq_numliquida";
		$this->CountSQL = "SELECT COUNT(*) FROM ( " . $this->SQL . ") AS tmp"; // CORRECCION PARA EJECUTAR
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-407697F8
    function SetValues()
    {
        $this->liq_numliquida->SetDBValue(trim($this->f("liq_numliquida")));
        $this->pro_id->SetDBValue(trim($this->f("pro_id")));
        $this->tmp_nombre->SetDBValue($this->f("tmp_nombre"));
        $this->tmp_cantembarcada->SetDBValue(trim($this->f("tmp_cantembarcada")));
        $this->sum_liq_valtotal_rub_inddbcr_1->SetDBValue(trim($this->f("sum(liq_valtotal * rub_inddbcr)")));
        $this->Alt_liq_numliquida->SetDBValue(trim($this->f("liq_numliquida")));
        $this->Alt_pro_id->SetDBValue(trim($this->f("pro_id")));
        $this->Alt_tmp_nombre->SetDBValue($this->f("tmp_nombre"));
        $this->Alt_tmp_cantembarcada->SetDBValue(trim($this->f("tmp_cantembarcada")));
        $this->Alt_sum_liq_valtotal_rub_inddbcr_1->SetDBValue(trim($this->f("sum(liq_valtotal * rub_inddbcr)")));
    }
//End SetValues Method

} //End liquidac_listDataSource Class @7-FCB6E20C

//Initialize Page @1-61348286
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

$FileName = "LiLiLi_search.php";
$Redirect = "";
$TemplateFileName = "LiLiLi_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-9763DE4E
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liquidac_qry = new clsRecordliquidac_qry();
$liquidac_list = new clsGridliquidac_list();
$liquidac_list->Initialize();

// Events
include("./LiLiLi_search_events.php");
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

//Execute Components @1-D03CCE75
$Cabecera->Operations();
$liquidac_qry->Operation();
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

//Show Page @1-A19F4010
$Cabecera->Show("Cabecera");
$liquidac_qry->Show();
$liquidac_list->Show();
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
