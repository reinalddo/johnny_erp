<?php
$session =session_id();
echo $session;
IF($session)session_id($id);
session_start(); //                                 INICIO DE SESION
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsGridsegempresas { //segempresas class @2-C378992F

//Variables @2-2470AE69

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
    var $Sorter_emp_IDempresa;
    var $Sorter_emp_Descripcion;
    var $Sorter_emp_BaseDatos;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-925DB991
    function clsGridsegempresas()
    {
        global $FileName;
        $this->ComponentName = "segempresas";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid segempresas";
        $this->ds = new clssegempresasDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("segempresasOrder", "");
        $this->SorterDirection = CCGetParam("segempresasDir", "");

        $this->p2 = new clsControl(ccsTextBox, "p2", "p2", ccsText, "", CCGetRequestParam("p2", ccsGet));
        $this->pDe = new clsControl(ccsTextBox, "pDe", "pDe", ccsText, "", CCGetRequestParam("pDe", ccsGet));
        $this->pBd = new clsControl(ccsTextBox, "pBd", "pBd", ccsText, "", CCGetRequestParam("pBd", ccsGet));
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link2 = new clsControl(ccsLink, "Link2", "Link2", ccsText, "", CCGetRequestParam("Link2", ccsGet));
        $this->emp_IDempresa = new clsControl(ccsLabel, "emp_IDempresa", "emp_IDempresa", ccsText, "", CCGetRequestParam("emp_IDempresa", ccsGet));
        $this->emp_Descripcion = new clsControl(ccsLabel, "emp_Descripcion", "emp_Descripcion", ccsText, "", CCGetRequestParam("emp_Descripcion", ccsGet));
        $this->emp_BaseDatos = new clsControl(ccsLabel, "emp_BaseDatos", "emp_BaseDatos", ccsText, "", CCGetRequestParam("emp_BaseDatos", ccsGet));
        $this->lbUsuario = new clsControl(ccsLabel, "lbUsuario", "lbUsuario", ccsText, "", CCGetRequestParam("lbUsuario", ccsGet));
        $this->Sorter_emp_IDempresa = new clsSorter($this->ComponentName, "Sorter_emp_IDempresa", $FileName);
        $this->Sorter_emp_Descripcion = new clsSorter($this->ComponentName, "Sorter_emp_Descripcion", $FileName);
        $this->Sorter_emp_BaseDatos = new clsSorter($this->ComponentName, "Sorter_emp_BaseDatos", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
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

//Show Method @2-259FD8DF
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlp1"] = CCGetFromGet("p1", "");

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
                $this->Link1->SetText("....");
                $this->Link1->Parameters = CCGetQueryString("All", Array("ccsForm"));
                $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "pDe", $this->ds->f("emp_Descripcion"));
                $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "pBd", $this->ds->f("emp_BaseDatos"));
                $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "pProc", 1);
                $this->Link1->Page = "SeGeEm_selec.php";
                $this->Link2->SetText(sss);
                $this->Link2->Parameters = CCGetQueryString("All", Array("p1", "ccsForm"));
                $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "pBd", $this->ds->f("emp_BaseDatos"));
                $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "pDe", $this->ds->f("emp_Descripcion"));
                $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "p1", "fah");
                $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "pProc", 1);
                $this->Link2->Page = "SeGeEm_selec.php";
                $this->emp_IDempresa->SetValue($this->ds->emp_IDempresa->GetValue());
                $this->emp_Descripcion->SetValue($this->ds->emp_Descripcion->GetValue());
                $this->emp_BaseDatos->SetValue($this->ds->emp_BaseDatos->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->p2->Show();
                $this->pDe->Show();
                $this->pBd->Show();
                $this->Link1->Show();
                $this->Link2->Show();
                $this->emp_IDempresa->Show();
                $this->emp_Descripcion->Show();
                $this->emp_BaseDatos->Show();
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
        $this->lbUsuario->SetText($_SERVER['g_user']);
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->lbUsuario->Show();
        $this->Sorter_emp_IDempresa->Show();
        $this->Sorter_emp_Descripcion->Show();
        $this->Sorter_emp_BaseDatos->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-ADAD1F57
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->p2->Errors->ToString();
        $errors .= $this->pDe->Errors->ToString();
        $errors .= $this->pBd->Errors->ToString();
        $errors .= $this->Link1->Errors->ToString();
        $errors .= $this->Link2->Errors->ToString();
        $errors .= $this->emp_IDempresa->Errors->ToString();
        $errors .= $this->emp_Descripcion->Errors->ToString();
        $errors .= $this->emp_BaseDatos->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End segempresas Class @2-FCB6E20C

class clssegempresasDataSource extends clsDBSeguridad {  //segempresasDataSource Class @2-95775AAD

//DataSource Variables @2-C3452F49
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lbUsuario;
    var $Link1;
    var $Link2;
    var $emp_IDempresa;
    var $emp_Descripcion;
    var $emp_BaseDatos;
//End DataSource Variables

//Class_Initialize Event @2-D2889290
    function clssegempresasDataSource()
    {
        $this->ErrorBlock = "Grid segempresas";
        $this->Initialize();
        $this->lbUsuario = new clsField("lbUsuario", ccsText, "");
        $this->Link1 = new clsField("Link1", ccsText, "");
        $this->Link2 = new clsField("Link2", ccsText, "");
        $this->emp_IDempresa = new clsField("emp_IDempresa", ccsText, "");
        $this->emp_Descripcion = new clsField("emp_Descripcion", ccsText, "");
        $this->emp_BaseDatos = new clsField("emp_BaseDatos", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-B6A08182
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_emp_IDempresa" => array("emp_IDempresa", ""), 
            "Sorter_emp_Descripcion" => array("emp_Descripcion", ""), 
            "Sorter_emp_BaseDatos" => array("emp_BaseDatos", "")));
    }
//End SetOrder Method

//Prepare Method @2-D033A5CD
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlp1", ccsText, "", "", $this->Parameters["urlp1"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "usu_login", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-9FFCBA67
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (segusperfiles INNER JOIN segempresas ON segempresas.emp_IDempresa = segusperfiles.usp_IDempresa) INNER JOIN segusuario ON segusperfiles.usp_IDusuario = segusuario.usu_IDusuario";
        $this->SQL = "SELECT emp_IDempresa, emp_Descripcion, emp_BaseDatos, usu_login  " .
        "FROM (segusperfiles INNER JOIN segempresas ON segempresas.emp_IDempresa = segusperfiles.usp_IDempresa) INNER JOIN segusuario ON segusperfiles.usp_IDusuario = segusuario.usu_IDusuario";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
echo $this->SQL, $this->Where, $this->Order;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-192EC3F5
    function SetValues()
    {
        $this->emp_IDempresa->SetDBValue($this->f("emp_IDempresa"));
        $this->emp_Descripcion->SetDBValue($this->f("emp_Descripcion"));
        $this->emp_BaseDatos->SetDBValue($this->f("emp_BaseDatos"));
    }
//End SetValues Method

} //End segempresasDataSource Class @2-FCB6E20C

//Initialize Page @1-126F32F3
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

$FileName = "SeGeEm_selec.php";
$Redirect = "";
$TemplateFileName = "SeGeEm_selec.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-F723B501
$DBSeguridad = new clsDBSeguridad();

// Controls
$segempresas = new clsGridsegempresas();
$segempresas->Initialize();

// Events
include("./SeGeEm_selec_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Go to destination page @1-ADB1C9C4
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBSeguridad->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-E4B0D81F
$segempresas->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-23B48A28
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBSeguridad->close();
unset($Tpl);
//End Unload Page


?>
