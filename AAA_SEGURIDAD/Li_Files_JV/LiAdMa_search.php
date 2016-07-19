<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include (RelativePath . "/LibPhp/SegLib.php");
class clsRecordmarcas_qry { //marcas_qry Class @63-A68EDB9E

//Variables @63-CB19EB75

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

//Class_Initialize Event @63-41FC7D40
    function clsRecordmarcas_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record marcas_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "marcas_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->s_Uso = new clsControl(ccsRadioButton, "s_Uso", "s_Uso", ccsText, "", CCGetRequestParam("s_Uso", $Method));
            $this->s_Uso->DSType = dsTable;
            list($this->s_Uso->BoundColumn, $this->s_Uso->TextColumn, $this->s_Uso->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_Uso->ds = new clsDBdatos();
            $this->s_Uso->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_Uso->ds->Parameters["expr69"] = 'IGUSMA';
            $this->s_Uso->ds->wp = new clsSQLParameters();
            $this->s_Uso->ds->wp->AddParameter("1", "expr69", ccsText, "", "", $this->s_Uso->ds->Parameters["expr69"], "", false);
            $this->s_Uso->ds->wp->Criterion[1] = $this->s_Uso->ds->wp->Operation(opEqual, "par_Clave", $this->s_Uso->ds->wp->GetDBValue("1"), $this->s_Uso->ds->ToSQL($this->s_Uso->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_Uso->ds->Where = $this->s_Uso->ds->wp->Criterion[1];
            $this->s_Uso->HTML = true;
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("All", Array("s_keyword", "ccsForm"));
            $this->ClearParameters->Page = "LiAdMa_search.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @63-67C62A91
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $Validation = ($this->s_Uso->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @63-E3B2E3A2
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->s_Uso->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @63-7D21FAC9
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
        $Redirect = "LiAdMa_search.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdMa_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @63-0DFFC069
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_Uso->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_keyword->Errors->ToString();
            $Error .= $this->s_Uso->Errors->ToString();
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
        $this->s_Uso->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End marcas_qry Class @63-FCB6E20C

class clsGridmarcas_list { //marcas_list class @2-9B37CA00

//Variables @2-B06DF7E8

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
    var $Navigator;
    var $Sorter_par_Secuencia;
    var $Sorter_par_Descripcion;
    var $Sorter_par_Valor1;
    var $Sorter_par_Valor3;
    var $Sorter_par_Valor4;
//End Variables

//Class_Initialize Event @2-6C41ED4A
    function clsGridmarcas_list()
    {
        global $FileName;
        $this->ComponentName = "marcas_list";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid marcas_list";
        $this->ds = new clsmarcas_listDataSource();
        $this->PageSize = 15;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("marcas_listOrder", "");
        $this->SorterDirection = CCGetParam("marcas_listDir", "");

        $this->par_Secuencia = new clsControl(ccsHidden, "par_Secuencia", "par_Secuencia", ccsInteger, "", CCGetRequestParam("par_Secuencia", ccsGet));
        $this->Button1 = new clsButton("Button1");
        $this->par_Descripcion = new clsControl(ccsTextBox, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->par_Valor1 = new clsControl(ccsHidden, "par_Valor1", "par_Valor1", ccsText, "", CCGetRequestParam("par_Valor1", ccsGet));
        $this->txt_Clase = new clsControl(ccsLabel, "txt_Clase", "txt_Clase", ccsText, "", CCGetRequestParam("txt_Clase", ccsGet));
        $this->par_Valor3 = new clsControl(ccsHidden, "par_Valor3", "par_Valor3", ccsText, "", CCGetRequestParam("par_Valor3", ccsGet));
        $this->txt_Aplica = new clsControl(ccsLabel, "txt_Aplica", "txt_Aplica", ccsText, "", CCGetRequestParam("txt_Aplica", ccsGet));
        $this->par_Valor4 = new clsControl(ccsHidden, "par_Valor4", "par_Valor4", ccsText, "", CCGetRequestParam("par_Valor4", ccsGet));
        $this->txt_Estado = new clsControl(ccsLabel, "txt_Estado", "txt_Estado", ccsText, "", CCGetRequestParam("txt_Estado", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_par_Secuencia = new clsSorter($this->ComponentName, "Sorter_par_Secuencia", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Sorter_par_Valor1 = new clsSorter($this->ComponentName, "Sorter_par_Valor1", $FileName);
        $this->Sorter_par_Valor3 = new clsSorter($this->ComponentName, "Sorter_par_Valor3", $FileName);
        $this->Sorter_par_Valor4 = new clsSorter($this->ComponentName, "Sorter_par_Valor4", $FileName);
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

//Show Method @2-1DD6463D
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
        $this->ds->Parameters["expr21"] = 'IMARCA';
        $this->ds->Parameters["urls_Uso"] = CCGetFromGet("s_Uso", "");

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
                $this->par_Secuencia->SetValue($this->ds->par_Secuencia->GetValue());
                $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                $this->par_Valor1->SetValue($this->ds->par_Valor1->GetValue());
                $this->txt_Clase->SetValue($this->ds->txt_Clase->GetValue());
                $this->par_Valor3->SetValue($this->ds->par_Valor3->GetValue());
                $this->txt_Aplica->SetValue($this->ds->txt_Aplica->GetValue());
                $this->par_Valor4->SetValue($this->ds->par_Valor4->GetValue());
                $this->txt_Estado->SetValue($this->ds->txt_Estado->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->par_Secuencia->Show();
                $this->Button1->Show();
                $this->par_Descripcion->Show();
                $this->par_Valor1->Show();
                $this->txt_Clase->Show();
                $this->par_Valor3->Show();
                $this->txt_Aplica->Show();
                $this->par_Valor4->Show();
                $this->txt_Estado->Show();
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
        $this->Navigator->Show();
        $this->btNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_par_Secuencia->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Sorter_par_Valor1->Show();
        $this->Sorter_par_Valor3->Show();
        $this->Sorter_par_Valor4->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-BED3FD6E
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->par_Secuencia->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->par_Valor1->Errors->ToString();
        $errors .= $this->txt_Clase->Errors->ToString();
        $errors .= $this->par_Valor3->Errors->ToString();
        $errors .= $this->txt_Aplica->Errors->ToString();
        $errors .= $this->par_Valor4->Errors->ToString();
        $errors .= $this->txt_Estado->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End marcas_list Class @2-FCB6E20C

class clsmarcas_listDataSource extends clsDBdatos {  //marcas_listDataSource Class @2-FD7816CB

//DataSource Variables @2-5AE2A4D7
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $par_Secuencia;
    var $par_Descripcion;
    var $par_Valor1;
    var $txt_Clase;
    var $par_Valor3;
    var $txt_Aplica;
    var $par_Valor4;
    var $txt_Estado;
//End DataSource Variables

//Class_Initialize Event @2-3F7423B1
    function clsmarcas_listDataSource()
    {
        $this->ErrorBlock = "Grid marcas_list";
        $this->Initialize();
        $this->par_Secuencia = new clsField("par_Secuencia", ccsInteger, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->par_Valor1 = new clsField("par_Valor1", ccsText, "");
        $this->txt_Clase = new clsField("txt_Clase", ccsText, "");
        $this->par_Valor3 = new clsField("par_Valor3", ccsText, "");
        $this->txt_Aplica = new clsField("txt_Aplica", ccsText, "");
        $this->par_Valor4 = new clsField("par_Valor4", ccsText, "");
        $this->txt_Estado = new clsField("txt_Estado", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-19232277
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_par_Secuencia" => array("par_Secuencia", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", ""), 
            "Sorter_par_Valor1" => array("par_Valor1", ""), 
            "Sorter_par_Valor3" => array("par_Valor3", ""), 
            "Sorter_par_Valor4" => array("par_Valor4", "")));
    }
//End SetOrder Method

//Prepare Method @2-53AA61B0
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("2", "expr21", ccsText, "", "", $this->Parameters["expr21"], "", false);
        $this->wp->AddParameter("3", "urls_Uso", ccsText, "", "", $this->Parameters["urls_Uso"], 1, false);
    }
//End Prepare Method

//Open Method @2-A0E64030
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM ((genparametros p LEFT JOIN genparametros c ON p.par_Clave = 'IMARCA'  " .
        "					AND p.par_Valor1 = c.par_Secuencia AND c.par_Clave = 'IGTIMA')  " .
        "     LEFT JOIN genparametros a ON a.par_Clave = 'IGUSMA' AND p.par_Valor3 = a.par_Secuencia)  " .
        "     LEFT JOIN genparametros e ON e.par_Clave = 'LGESTA' AND p.par_Valor4 = e.par_Secuencia " .
        "WHERE  " .
        "      (p.par_Secuencia LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "       p.par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%') AND " .
        "       p.par_Valor3 = " . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "";
        $this->SQL = "SELECT p.*, c.par_descripcion as txt_Clase, a.par_descripcion as txt_Aplica,      e.par_descripcion as txt_estado  " .
        "FROM ((genparametros p LEFT JOIN genparametros c ON p.par_Clave = 'IMARCA'  " .
        "					AND p.par_Valor1 = c.par_Secuencia AND c.par_Clave = 'IGTIMA')  " .
        "     LEFT JOIN genparametros a ON a.par_Clave = 'IGUSMA' AND p.par_Valor3 = a.par_Secuencia)  " .
        "     LEFT JOIN genparametros e ON e.par_Clave = 'LGESTA' AND p.par_Valor4 = e.par_Secuencia " .
        "WHERE  " .
        "      (p.par_Secuencia LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "       p.par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%') AND " .
        "       p.par_Valor3 = " . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-B55D94D3
    function SetValues()
    {
        $this->par_Secuencia->SetDBValue(trim($this->f("par_Secuencia")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->par_Valor1->SetDBValue($this->f("par_Valor1"));
        $this->txt_Clase->SetDBValue($this->f("txt_Clase"));
        $this->par_Valor3->SetDBValue($this->f("par_Valor3"));
        $this->txt_Aplica->SetDBValue($this->f("txt_Aplica"));
        $this->par_Valor4->SetDBValue($this->f("par_Valor4"));
        $this->txt_Estado->SetDBValue($this->f("txt_estado"));
    }
//End SetValues Method

} //End marcas_listDataSource Class @2-FCB6E20C

//Initialize Page @1-70AB41DC
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

$FileName = "LiAdMa_search.php";
$Redirect = "";
$TemplateFileName = "LiAdMa_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-462C86B0
$DBdatos = new clsDBdatos();

// Controls
$marcas_qry = new clsRecordmarcas_qry();
$marcas_list = new clsGridmarcas_list();
$marcas_list->Initialize();

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

//Execute Components @1-C36F6832
$marcas_qry->Operation();
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

//Show Page @1-5641D838
$marcas_qry->Show();
$marcas_list->Show();
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
