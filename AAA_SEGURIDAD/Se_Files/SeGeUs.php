<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @87-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsGridSeGeUs_qry { //SeGeUs_qry class @2-09203B6E

//Variables @2-25F51C69

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
    var $Sorter_usu_IDusuario;
    var $Sorter_usu_login;
    var $Sorter_usu_Nombre;
    var $Sorter_usu_Activo;
//End Variables

//Class_Initialize Event @2-F9022B60
    function clsGridSeGeUs_qry()
    {
        global $FileName;
        $this->ComponentName = "SeGeUs_qry";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid SeGeUs_qry";
        $this->ds = new clsSeGeUs_qryDataSource();
        $this->PageSize = 45;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("SeGeUs_qryOrder", "");
        $this->SorterDirection = CCGetParam("SeGeUs_qryDir", "");

        $this->usu_IDusuario = new clsControl(ccsLink, "usu_IDusuario", "usu_IDusuario", ccsInteger, "", CCGetRequestParam("usu_IDusuario", ccsGet));
        $this->usu_login = new clsControl(ccsLabel, "usu_login", "usu_login", ccsText, "", CCGetRequestParam("usu_login", ccsGet));
        $this->usu_Nombre = new clsControl(ccsLabel, "usu_Nombre", "usu_Nombre", ccsText, "", CCGetRequestParam("usu_Nombre", ccsGet));
        $this->usu_Activo = new clsControl(ccsLabel, "usu_Activo", "usu_Activo", ccsInteger, "", CCGetRequestParam("usu_Activo", ccsGet));
        $this->Sorter_usu_IDusuario = new clsSorter($this->ComponentName, "Sorter_usu_IDusuario", $FileName);
        $this->Sorter_usu_login = new clsSorter($this->ComponentName, "Sorter_usu_login", $FileName);
        $this->Sorter_usu_Nombre = new clsSorter($this->ComponentName, "Sorter_usu_Nombre", $FileName);
        $this->Sorter_usu_Activo = new clsSorter($this->ComponentName, "Sorter_usu_Activo", $FileName);
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
        $this->Link1->Page = "SeGeUs_mant.php";
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

//Show Method @2-FC83F72B
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;


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
                $this->usu_IDusuario->SetValue($this->ds->usu_IDusuario->GetValue());
                $this->usu_IDusuario->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->usu_IDusuario->Parameters = CCAddParam($this->usu_IDusuario->Parameters, "usu_IDusuario", $this->ds->f("usu_IDusuario"));
                $this->usu_IDusuario->Parameters = CCAddParam($this->usu_IDusuario->Parameters, "usp_IDusuario", $this->ds->f("usu_IDusuario"));
                $this->usu_IDusuario->Page = "SeGeUs_mant.php";
                $this->usu_login->SetValue($this->ds->usu_login->GetValue());
                $this->usu_Nombre->SetValue($this->ds->usu_Nombre->GetValue());
                $this->usu_Activo->SetValue($this->ds->usu_Activo->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->usu_IDusuario->Show();
                $this->usu_login->Show();
                $this->usu_Nombre->Show();
                $this->usu_Activo->Show();
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
        $this->Sorter_usu_IDusuario->Show();
        $this->Sorter_usu_login->Show();
        $this->Sorter_usu_Nombre->Show();
        $this->Sorter_usu_Activo->Show();
        $this->Link1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-1D3FD07C
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->usu_IDusuario->Errors->ToString();
        $errors .= $this->usu_login->Errors->ToString();
        $errors .= $this->usu_Nombre->Errors->ToString();
        $errors .= $this->usu_Activo->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End SeGeUs_qry Class @2-FCB6E20C

class clsSeGeUs_qryDataSource extends clsDBseguridad {  //SeGeUs_qryDataSource Class @2-3D5F1507

//DataSource Variables @2-A524C040
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $usu_IDusuario;
    var $usu_login;
    var $usu_Nombre;
    var $usu_Activo;
//End DataSource Variables

//Class_Initialize Event @2-B0FE7913
    function clsSeGeUs_qryDataSource()
    {
        $this->ErrorBlock = "Grid SeGeUs_qry";
        $this->Initialize();
        $this->usu_IDusuario = new clsField("usu_IDusuario", ccsInteger, "");
        $this->usu_login = new clsField("usu_login", ccsText, "");
        $this->usu_Nombre = new clsField("usu_Nombre", ccsText, "");
        $this->usu_Activo = new clsField("usu_Activo", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-998049EC
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "usu_Nombre";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_usu_IDusuario" => array("usu_IDusuario", ""), 
            "Sorter_usu_login" => array("usu_login", ""), 
            "Sorter_usu_Nombre" => array("usu_Nombre", ""), 
            "Sorter_usu_Activo" => array("usu_Activo", "")));
    }
//End SetOrder Method

//Prepare Method @2-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @2-A0EFD732
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM segusuario";
        $this->SQL = "SELECT *  " .
        "FROM segusuario";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-39FE39D6
    function SetValues()
    {
        $this->usu_IDusuario->SetDBValue(trim($this->f("usu_IDusuario")));
        $this->usu_login->SetDBValue($this->f("usu_login"));
        $this->usu_Nombre->SetDBValue($this->f("usu_Nombre"));
        $this->usu_Activo->SetDBValue(trim($this->f("usu_Activo")));
    }
//End SetValues Method

} //End SeGeUs_qryDataSource Class @2-FCB6E20C



//Initialize Page @1-D051C4B6
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

$FileName = "SeGeUs.php";
$Redirect = "";
$TemplateFileName = "SeGeUs.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-E1BE3F78
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$SeGeUs_qry = new clsGridSeGeUs_qry();
$SeGeUs_qry->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-1CF50FF4
$Cabecera->Operations();
//End Execute Components

//Go to destination page @1-034F3ABF
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-2BC655BF
$Cabecera->Show("Cabecera");
$SeGeUs_qry->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-3E9F2D84
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
