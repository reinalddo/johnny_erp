<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
   
class clsRecordauxquery { //auxquery Class @30-A45FB13F

//Variables @30-B2F7A83E

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

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @30-0D89FD0E
    function clsRecordauxquery()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record auxquery/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "auxquery";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pMensj = new clsControl(ccsLabel, "pMensj", "pMensj", ccsText, "", CCGetRequestParam("pMensj", $Method));
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->s_actividad = new clsControl(ccsCheckBox, "s_actividad", "s_actividad", ccsInteger, "", CCGetRequestParam("s_actividad", $Method));
            $this->s_actividad->HTML = true;
            $this->s_actividad->CheckedValue = $this->s_actividad->GetParsedValue(1);
            $this->s_actividad->UncheckedValue = $this->s_actividad->GetParsedValue(0);
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_keyword", "ccsForm"));
            $this->ClearParameters->Page = "CoAdAu_search2.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_actividad->Value) && !strlen($this->s_actividad->Value) && $this->s_actividad->Value !== false)
                $this->s_actividad->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @30-3D911BC0
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $Validation = ($this->s_actividad->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->s_keyword->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_actividad->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @30-1A8EF391
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pMensj->Errors->Count());
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->s_actividad->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @30-B65E47CF
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "CoAdAu_search2.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdAu_search2.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "s_actividad", "ccsForm")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @30-7468915B
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
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->pMensj->Errors->ToString();
            $Error .= $this->s_keyword->Errors->ToString();
            $Error .= $this->s_actividad->Errors->ToString();
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
        $this->s_actividad->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End auxquery Class @30-FCB6E20C

class clsGridauxlist { //auxlist class @2-74061BA8

//Variables @2-78F420DA

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
    var $Sorter_per_CodAuxiliar;
    var $Sorter_per_Apellidos;
    var $Sorter_par_Descripcion;
    var $Sorter_cat_Activo;
//End Variables

//Class_Initialize Event @2-7B827D65
    function clsGridauxlist()
    {
        global $FileName;
        $this->ComponentName = "auxlist";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid auxlist";
        $this->ds = new clsauxlistDataSource();
        $this->PageSize = 30;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("auxlistOrder", "");
        $this->SorterDirection = CCGetParam("auxlistDir", "");

        $this->CodAuxiliar = new clsControl(ccsHidden, "CodAuxiliar", "CodAuxiliar", ccsInteger, "", CCGetRequestParam("CodAuxiliar", ccsGet));
        $this->btMantto = new clsButton("btMantto");
        $this->Descripcion = new clsControl(ccsTextBox, "Descripcion", "Descripcion", ccsText, "", CCGetRequestParam("Descripcion", ccsGet));
        $this->Categoria = new clsControl(ccsTextBox, "Categoria", "Categoria", ccsText, "", CCGetRequestParam("Categoria", ccsGet));
        $this->Actividad = new clsControl(ccsTextBox, "Actividad", "Actividad", ccsText, "", CCGetRequestParam("Actividad", ccsGet));
        $this->Alt_CodAuxiliar = new clsControl(ccsHidden, "Alt_CodAuxiliar", "Alt_CodAuxiliar", ccsInteger, "", CCGetRequestParam("Alt_CodAuxiliar", ccsGet));
        $this->btMantto2 = new clsButton("btMantto2");
        $this->Alt_Descripcion = new clsControl(ccsTextBox, "Alt_Descripcion", "Alt_Descripcion", ccsText, "", CCGetRequestParam("Alt_Descripcion", ccsGet));
        $this->Alt_Categoria = new clsControl(ccsTextBox, "Alt_Categoria", "Alt_Categoria", ccsText, "", CCGetRequestParam("Alt_Categoria", ccsGet));
        $this->Alt_Actividad = new clsControl(ccsTextBox, "Alt_Actividad", "Alt_Actividad", ccsText, "", CCGetRequestParam("Alt_Actividad", ccsGet));
        $this->conpersonas_per_concatego_TotalRecords = new clsControl(ccsLabel, "conpersonas_per_concatego_TotalRecords", "conpersonas_per_concatego_TotalRecords", ccsText, "", CCGetRequestParam("conpersonas_per_concatego_TotalRecords", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpMoving);
        $this->BtNuevo = new clsButton("BtNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_per_CodAuxiliar = new clsSorter($this->ComponentName, "Sorter_per_CodAuxiliar", $FileName);
        $this->Sorter_per_Apellidos = new clsSorter($this->ComponentName, "Sorter_per_Apellidos", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Sorter_cat_Activo = new clsSorter($this->ComponentName, "Sorter_cat_Activo", $FileName);
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

//Show Method @2-82473DF9
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
        $this->ds->Parameters["urlpCondEx"] = CCGetFromGet("pCondEx", "");
        $this->ds->Parameters["urls_estado"] = CCGetFromGet("s_estado", "");

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
                    $this->CodAuxiliar->SetValue($this->ds->CodAuxiliar->GetValue());
                    $this->Descripcion->SetValue($this->ds->Descripcion->GetValue());
                    $this->Categoria->SetValue($this->ds->Categoria->GetValue());
                    $this->Actividad->SetValue($this->ds->Actividad->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->CodAuxiliar->Show();
                    $this->btMantto->Show();
                    $this->Descripcion->Show();
                    $this->Categoria->Show();
                    $this->Actividad->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_CodAuxiliar->SetValue($this->ds->Alt_CodAuxiliar->GetValue());
                    $this->Alt_Descripcion->SetValue($this->ds->Alt_Descripcion->GetValue());
                    $this->Alt_Categoria->SetValue($this->ds->Alt_Categoria->GetValue());
                    $this->Alt_Actividad->SetValue($this->ds->Alt_Actividad->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_CodAuxiliar->Show();
                    $this->btMantto2->Show();
                    $this->Alt_Descripcion->Show();
                    $this->Alt_Categoria->Show();
                    $this->Alt_Actividad->Show();
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
        $this->conpersonas_per_concatego_TotalRecords->Show();
        $this->Navigator->Show();
        $this->BtNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_per_CodAuxiliar->Show();
        $this->Sorter_per_Apellidos->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Sorter_cat_Activo->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-E1CF331C
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->CodAuxiliar->Errors->ToString();
        $errors .= $this->Descripcion->Errors->ToString();
        $errors .= $this->Categoria->Errors->ToString();
        $errors .= $this->Actividad->Errors->ToString();
        $errors .= $this->Alt_CodAuxiliar->Errors->ToString();
        $errors .= $this->Alt_Descripcion->Errors->ToString();
        $errors .= $this->Alt_Categoria->Errors->ToString();
        $errors .= $this->Alt_Actividad->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End auxlist Class @2-FCB6E20C

class clsauxlistDataSource extends clsDBdatos {  //auxlistDataSource Class @2-8676D2CD

//DataSource Variables @2-741991AD
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $CodAuxiliar;
    var $Descripcion;
    var $Categoria;
    var $Actividad;
    var $Alt_CodAuxiliar;
    var $Alt_Descripcion;
    var $Alt_Categoria;
    var $Alt_Actividad;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-8D5F4A36
    function clsauxlistDataSource()
    {
        $this->ErrorBlock = "Grid auxlist";
        $this->Initialize();
        $this->CodAuxiliar = new clsField("CodAuxiliar", ccsInteger, "");
        $this->Descripcion = new clsField("Descripcion", ccsText, "");
        $this->Categoria = new clsField("Categoria", ccsText, "");
        $this->Actividad = new clsField("Actividad", ccsText, "");
        $this->Alt_CodAuxiliar = new clsField("Alt_CodAuxiliar", ccsInteger, "");
        $this->Alt_Descripcion = new clsField("Alt_Descripcion", ccsText, "");
        $this->Alt_Categoria = new clsField("Alt_Categoria", ccsText, "");
        $this->Alt_Actividad = new clsField("Alt_Actividad", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-45BE80AE
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "per_Apellidos";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_per_CodAuxiliar" => array("per_CodAuxiliar", ""), 
            "Sorter_per_Apellidos" => array("per_Apellidos", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", ""), 
            "Sorter_cat_Activo" => array("cat_Activo", "")));
    }
//End SetOrder Method

//Prepare Method @2-276013F1
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("2", "urlpCondEx", ccsText, "", "", $this->Parameters["urlpCondEx"], 'true', false);
        $this->wp->AddParameter("3", "urls_estado", ccsInteger, "", "", $this->Parameters["urls_estado"], 0, false);
    }
//End Prepare Method

//Open Method @2-DA176E74
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM (conpersonas per LEFT JOIN concategorias cat  ON per.per_CodAuxiliar = cat.cat_CodAuxiliar)  " .
        "      LEFT JOIN genparametros par ON par_clave = 'CAUTI' and par.par_Secuencia " .
        "WHERE  " .
        "      (per_CodAuxiliar LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "       per_Apellidos   LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "       per_Nombres     LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "       par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%') AND " .
        "      (cat_Activo >= " . $this->SQLValue($this->wp->GetDBValue("3"), ccsInteger) . ") AND " .
        "      " . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . " " .
        "";
        $this->SQL = "SELECT per_CodAuxiliar as CodAuxiliar,  " .
        "       concat(per_Apellidos, ' ', per_Nombres) AS Descripcion, " .
        "       if(cat_activo = 1,'ACTIVO', 'INACT.') as Estado, par_Descripcion as Categoria " .
        "FROM (conpersonas per LEFT JOIN concategorias cat  ON per.per_CodAuxiliar = cat.cat_CodAuxiliar)  " .
        "      LEFT JOIN genparametros par ON par_clave = 'CAUTI' and cat.cat_Categoria = par.par_Secuencia " .
        "WHERE  " .
        "      (per_CodAuxiliar LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "       per_Apellidos   LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "       per_Nombres     LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "       par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%') AND " .
        "      (cat_Activo >= " . $this->SQLValue($this->wp->GetDBValue("3"), ccsInteger) . ") AND " .
        "      " . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . " " .
        "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-6C9E6C08
    function SetValues()
    {
        $this->CodAuxiliar->SetDBValue(trim($this->f("CodAuxiliar")));
        $this->Descripcion->SetDBValue($this->f("Descripcion"));
        $this->Categoria->SetDBValue($this->f("Categoria"));
        $this->Actividad->SetDBValue($this->f("Estado"));
        $this->Alt_CodAuxiliar->SetDBValue(trim($this->f("CodAuxiliar")));
        $this->Alt_Descripcion->SetDBValue($this->f("Descripcion"));
        $this->Alt_Categoria->SetDBValue($this->f("Categoria"));
        $this->Alt_Actividad->SetDBValue($this->f("Estado"));
    }
//End SetValues Method

} //End auxlistDataSource Class @2-FCB6E20C

//Initialize Page @1-C36266DA
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

$FileName = "CoAdAu_search2.php";
$Redirect = "";
$TemplateFileName = "CoAdAu_search2.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-CA1316D7
$DBdatos = new clsDBdatos();

// Controls
$auxquery = new clsRecordauxquery();
$auxlist = new clsGridauxlist();
$auxlist->Initialize();

// Events
include("./CoAdAu_search2_events.php");
BindEvents();

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

//Execute Components @1-0550A4A6
$auxquery->Operation();
//End Execute Components

//Go to destination page @1-CE0033DE
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    unset($auxquery);
    unset($auxlist);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-6CBB70F2
$auxquery->Show();
$auxlist->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$LIFHESN8J9F4L = array("<center><font face=\"Arial\"><smal","l>&#71;&#101;&#110;&#101;&#114;&","#97;t&#101;d <!-- CCS -->wi&#","116;&#104; <!-- SCC -->&#67;&#111",";&#100;&#101;C&#104;&#97;&#11","4;ge <!-- CCS -->&#83;t&#117;d&","#105;o.</small></font></center>");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($LIFHESN8J9F4L,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($LIFHESN8J9F4L,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($LIFHESN8J9F4L,"");
}
echo $main_block;
//End Show Page

//Unload Page @1-FA83D82E
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($auxquery);
unset($auxlist);
unset($Tpl);
//End Unload Page


?>
