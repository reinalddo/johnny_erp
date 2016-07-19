<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @88-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordCoAdTi_qry { //CoAdTi_qry Class @89-99EC5F26

//Variables @89-CB19EB75

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

//Class_Initialize Event @89-9E0CA959
    function clsRecordCoAdTi_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdTi_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdTi_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_cla_aplicacion = new clsControl(ccsListBox, "s_cla_aplicacion", "Cla Aplicacion", ccsText, "", CCGetRequestParam("s_cla_aplicacion", $Method));
            $this->s_cla_aplicacion->DSType = dsTable;
            list($this->s_cla_aplicacion->BoundColumn, $this->s_cla_aplicacion->TextColumn, $this->s_cla_aplicacion->DBFormat) = array("mod_subsistema", "mod_descripcion", "");
            $this->s_cla_aplicacion->ds = new clsDBSeguridad();
            $this->s_cla_aplicacion->ds->SQL = "SELECT *  " .
"FROM segmodulos";
            $this->s_cla_aplicacion->ds->Parameters["expr91"] = 'ooo';
            $this->s_cla_aplicacion->ds->wp = new clsSQLParameters();
            $this->s_cla_aplicacion->ds->wp->AddParameter("1", "expr91", ccsText, "", "", $this->s_cla_aplicacion->ds->Parameters["expr91"], "", false);
            $this->s_cla_aplicacion->ds->wp->Criterion[1] = $this->s_cla_aplicacion->ds->wp->Operation(opEqual, "mod_modulo", $this->s_cla_aplicacion->ds->wp->GetDBValue("1"), $this->s_cla_aplicacion->ds->ToSQL($this->s_cla_aplicacion->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_cla_aplicacion->ds->Where = $this->s_cla_aplicacion->ds->wp->Criterion[1];
            $this->s_cla_aplicacion->Required = true;
            $this->s_cla_tipoComp = new clsControl(ccsTextBox, "s_cla_tipoComp", "s_cla_tipoComp", ccsText, "", CCGetRequestParam("s_cla_tipoComp", $Method));
            $this->s_cla_Descripcion = new clsControl(ccsTextBox, "s_cla_Descripcion", "s_cla_Descripcion", ccsText, "", CCGetRequestParam("s_cla_Descripcion", $Method));
            $this->s_cla_Contabilizacion = new clsControl(ccsTextBox, "s_cla_Contabilizacion", "s_cla_Contabilizacion", ccsInteger, "", CCGetRequestParam("s_cla_Contabilizacion", $Method));
            $this->s_cla_Formulario = new clsControl(ccsTextBox, "s_cla_Formulario", "s_cla_Formulario", ccsText, "", CCGetRequestParam("s_cla_Formulario", $Method));
            $this->s_cla_Informe = new clsControl(ccsTextBox, "s_cla_Informe", "s_cla_Informe", ccsText, "", CCGetRequestParam("s_cla_Informe", $Method));
            $this->s_cla_Indicador = new clsControl(ccsListBox, "s_cla_Indicador", "Cla Indicador", ccsInteger, "", CCGetRequestParam("s_cla_Indicador", $Method));
            $this->s_cla_Indicador->DSType = dsTable;
            list($this->s_cla_Indicador->BoundColumn, $this->s_cla_Indicador->TextColumn, $this->s_cla_Indicador->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_cla_Indicador->ds = new clsDBdatos();
            $this->s_cla_Indicador->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_cla_Indicador->ds->Parameters["expr98"] = 'LGESTA';
            $this->s_cla_Indicador->ds->wp = new clsSQLParameters();
            $this->s_cla_Indicador->ds->wp->AddParameter("1", "expr98", ccsText, "", "", $this->s_cla_Indicador->ds->Parameters["expr98"], "", false);
            $this->s_cla_Indicador->ds->wp->Criterion[1] = $this->s_cla_Indicador->ds->wp->Operation(opEqual, "par_Clave", $this->s_cla_Indicador->ds->wp->GetDBValue("1"), $this->s_cla_Indicador->ds->ToSQL($this->s_cla_Indicador->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_cla_Indicador->ds->Where = $this->s_cla_Indicador->ds->wp->Criterion[1];
            $this->s_cla_Indicador->Required = true;
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_cla_Indicador->Value) && !strlen($this->s_cla_Indicador->Value) && $this->s_cla_Indicador->Value !== false)
                $this->s_cla_Indicador->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @89-1389CE3B
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_cla_aplicacion->Validate() && $Validation);
        $Validation = ($this->s_cla_tipoComp->Validate() && $Validation);
        $Validation = ($this->s_cla_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_cla_Contabilizacion->Validate() && $Validation);
        $Validation = ($this->s_cla_Formulario->Validate() && $Validation);
        $Validation = ($this->s_cla_Informe->Validate() && $Validation);
        $Validation = ($this->s_cla_Indicador->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @89-D036E224
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_cla_aplicacion->Errors->Count());
        $errors = ($errors || $this->s_cla_tipoComp->Errors->Count());
        $errors = ($errors || $this->s_cla_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_cla_Contabilizacion->Errors->Count());
        $errors = ($errors || $this->s_cla_Formulario->Errors->Count());
        $errors = ($errors || $this->s_cla_Informe->Errors->Count());
        $errors = ($errors || $this->s_cla_Indicador->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @89-374B90DF
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
        $Redirect = "CoAdTi.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdTi.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @89-EF77E77C
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_cla_aplicacion->Prepare();
        $this->s_cla_Indicador->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_cla_aplicacion->Errors->ToString();
            $Error .= $this->s_cla_tipoComp->Errors->ToString();
            $Error .= $this->s_cla_Descripcion->Errors->ToString();
            $Error .= $this->s_cla_Contabilizacion->Errors->ToString();
            $Error .= $this->s_cla_Formulario->Errors->ToString();
            $Error .= $this->s_cla_Informe->Errors->ToString();
            $Error .= $this->s_cla_Indicador->Errors->ToString();
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

        $this->s_cla_aplicacion->Show();
        $this->s_cla_tipoComp->Show();
        $this->s_cla_Descripcion->Show();
        $this->s_cla_Contabilizacion->Show();
        $this->s_cla_Formulario->Show();
        $this->s_cla_Informe->Show();
        $this->s_cla_Indicador->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End CoAdTi_qry Class @89-FCB6E20C

class clsGridCoAdTi_list { //CoAdTi_list class @100-D167238B

//Variables @100-D1B46FE2

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
    var $Sorter_cla_tipoComp;
    var $Sorter_cla_aplicacion;
    var $Sorter_cla_Descripcion;
    var $Sorter_cla_Formulario;
    var $Sorter_cla_Informe;
    var $Navigator;
//End Variables

//Class_Initialize Event @100-2B74D871
    function clsGridCoAdTi_list()
    {
        global $FileName;
        $this->ComponentName = "CoAdTi_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid CoAdTi_list";
        $this->ds = new clsCoAdTi_listDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("CoAdTi_listOrder", "");
        $this->SorterDirection = CCGetParam("CoAdTi_listDir", "");

        $this->cla_tipoComp = new clsControl(ccsLink, "cla_tipoComp", "cla_tipoComp", ccsText, "", CCGetRequestParam("cla_tipoComp", ccsGet));
        $this->cla_aplicacion = new clsControl(ccsLabel, "cla_aplicacion", "cla_aplicacion", ccsText, "", CCGetRequestParam("cla_aplicacion", ccsGet));
        $this->cla_Descripcion = new clsControl(ccsLabel, "cla_Descripcion", "cla_Descripcion", ccsText, "", CCGetRequestParam("cla_Descripcion", ccsGet));
        $this->cla_Formulario = new clsControl(ccsLabel, "cla_Formulario", "cla_Formulario", ccsText, "", CCGetRequestParam("cla_Formulario", ccsGet));
        $this->cla_Informe = new clsControl(ccsLabel, "cla_Informe", "cla_Informe", ccsText, "", CCGetRequestParam("cla_Informe", ccsGet));
        $this->Alt_cla_tipoComp = new clsControl(ccsLink, "Alt_cla_tipoComp", "Alt_cla_tipoComp", ccsText, "", CCGetRequestParam("Alt_cla_tipoComp", ccsGet));
        $this->Alt_cla_aplicacion = new clsControl(ccsLabel, "Alt_cla_aplicacion", "Alt_cla_aplicacion", ccsText, "", CCGetRequestParam("Alt_cla_aplicacion", ccsGet));
        $this->Alt_cla_Descripcion = new clsControl(ccsLabel, "Alt_cla_Descripcion", "Alt_cla_Descripcion", ccsText, "", CCGetRequestParam("Alt_cla_Descripcion", ccsGet));
        $this->Alt_cla_Formulario = new clsControl(ccsLabel, "Alt_cla_Formulario", "Alt_cla_Formulario", ccsText, "", CCGetRequestParam("Alt_cla_Formulario", ccsGet));
        $this->Alt_cla_Informe = new clsControl(ccsLabel, "Alt_cla_Informe", "Alt_cla_Informe", ccsText, "", CCGetRequestParam("Alt_cla_Informe", ccsGet));
        $this->genclasetran_TotalRecords = new clsControl(ccsLabel, "genclasetran_TotalRecords", "genclasetran_TotalRecords", ccsText, "", CCGetRequestParam("genclasetran_TotalRecords", ccsGet));
        $this->Sorter_cla_tipoComp = new clsSorter($this->ComponentName, "Sorter_cla_tipoComp", $FileName);
        $this->Sorter_cla_aplicacion = new clsSorter($this->ComponentName, "Sorter_cla_aplicacion", $FileName);
        $this->Sorter_cla_Descripcion = new clsSorter($this->ComponentName, "Sorter_cla_Descripcion", $FileName);
        $this->Sorter_cla_Formulario = new clsSorter($this->ComponentName, "Sorter_cla_Formulario", $FileName);
        $this->Sorter_cla_Informe = new clsSorter($this->ComponentName, "Sorter_cla_Informe", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button1 = new clsButton("Button1");
    }
//End Class_Initialize Event

//Initialize Method @100-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @100-EBAE3543
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_cla_aplicacion"] = CCGetFromGet("s_cla_aplicacion", "");
        $this->ds->Parameters["urls_cla_tipoComp"] = CCGetFromGet("s_cla_tipoComp", "");
        $this->ds->Parameters["urls_cla_Descripcion"] = CCGetFromGet("s_cla_Descripcion", "");
        $this->ds->Parameters["urls_cla_Formulario"] = CCGetFromGet("s_cla_Formulario", "");
        $this->ds->Parameters["urls_cla_Informe"] = CCGetFromGet("s_cla_Informe", "");
        $this->ds->Parameters["urls_cla_Contabilizacion"] = CCGetFromGet("s_cla_Contabilizacion", "");
        $this->ds->Parameters["urls_cla_Indicador"] = CCGetFromGet("s_cla_Indicador", "");

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
                    $this->cla_tipoComp->SetValue($this->ds->cla_tipoComp->GetValue());
                    $this->cla_tipoComp->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->cla_tipoComp->Parameters = CCAddParam($this->cla_tipoComp->Parameters, "cla_tipoComp", $this->ds->f("cla_tipoComp"));
                    $this->cla_tipoComp->Page = "CoAdTi_mant.php";
                    $this->cla_aplicacion->SetValue($this->ds->cla_aplicacion->GetValue());
                    $this->cla_Descripcion->SetValue($this->ds->cla_Descripcion->GetValue());
                    $this->cla_Formulario->SetValue($this->ds->cla_Formulario->GetValue());
                    $this->cla_Informe->SetValue($this->ds->cla_Informe->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->cla_tipoComp->Show();
                    $this->cla_aplicacion->Show();
                    $this->cla_Descripcion->Show();
                    $this->cla_Formulario->Show();
                    $this->cla_Informe->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_cla_tipoComp->SetValue($this->ds->Alt_cla_tipoComp->GetValue());
                    $this->Alt_cla_tipoComp->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Alt_cla_tipoComp->Parameters = CCAddParam($this->Alt_cla_tipoComp->Parameters, "cla_tipoComp", $this->ds->f("cla_tipoComp"));
                    $this->Alt_cla_tipoComp->Page = "CoAdTi_mant.php";
                    $this->Alt_cla_aplicacion->SetValue($this->ds->Alt_cla_aplicacion->GetValue());
                    $this->Alt_cla_Descripcion->SetValue($this->ds->Alt_cla_Descripcion->GetValue());
                    $this->Alt_cla_Formulario->SetValue($this->ds->Alt_cla_Formulario->GetValue());
                    $this->Alt_cla_Informe->SetValue($this->ds->Alt_cla_Informe->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_cla_tipoComp->Show();
                    $this->Alt_cla_aplicacion->Show();
                    $this->Alt_cla_Descripcion->Show();
                    $this->Alt_cla_Formulario->Show();
                    $this->Alt_cla_Informe->Show();
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
        $this->genclasetran_TotalRecords->Show();
        $this->Sorter_cla_tipoComp->Show();
        $this->Sorter_cla_aplicacion->Show();
        $this->Sorter_cla_Descripcion->Show();
        $this->Sorter_cla_Formulario->Show();
        $this->Sorter_cla_Informe->Show();
        $this->Navigator->Show();
        $this->Button1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @100-410B0562
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->cla_tipoComp->Errors->ToString();
        $errors .= $this->cla_aplicacion->Errors->ToString();
        $errors .= $this->cla_Descripcion->Errors->ToString();
        $errors .= $this->cla_Formulario->Errors->ToString();
        $errors .= $this->cla_Informe->Errors->ToString();
        $errors .= $this->Alt_cla_tipoComp->Errors->ToString();
        $errors .= $this->Alt_cla_aplicacion->Errors->ToString();
        $errors .= $this->Alt_cla_Descripcion->Errors->ToString();
        $errors .= $this->Alt_cla_Formulario->Errors->ToString();
        $errors .= $this->Alt_cla_Informe->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End CoAdTi_list Class @100-FCB6E20C

class clsCoAdTi_listDataSource extends clsDBdatos {  //CoAdTi_listDataSource Class @100-77769FC4

//DataSource Variables @100-5B505F00
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $cla_tipoComp;
    var $cla_aplicacion;
    var $cla_Descripcion;
    var $cla_Formulario;
    var $cla_Informe;
    var $Alt_cla_tipoComp;
    var $Alt_cla_aplicacion;
    var $Alt_cla_Descripcion;
    var $Alt_cla_Formulario;
    var $Alt_cla_Informe;
//End DataSource Variables

//Class_Initialize Event @100-1CE41E39
    function clsCoAdTi_listDataSource()
    {
        $this->ErrorBlock = "Grid CoAdTi_list";
        $this->Initialize();
        $this->cla_tipoComp = new clsField("cla_tipoComp", ccsText, "");
        $this->cla_aplicacion = new clsField("cla_aplicacion", ccsText, "");
        $this->cla_Descripcion = new clsField("cla_Descripcion", ccsText, "");
        $this->cla_Formulario = new clsField("cla_Formulario", ccsText, "");
        $this->cla_Informe = new clsField("cla_Informe", ccsText, "");
        $this->Alt_cla_tipoComp = new clsField("Alt_cla_tipoComp", ccsText, "");
        $this->Alt_cla_aplicacion = new clsField("Alt_cla_aplicacion", ccsText, "");
        $this->Alt_cla_Descripcion = new clsField("Alt_cla_Descripcion", ccsText, "");
        $this->Alt_cla_Formulario = new clsField("Alt_cla_Formulario", ccsText, "");
        $this->Alt_cla_Informe = new clsField("Alt_cla_Informe", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @100-DB31E312
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "cla_Descripcion";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cla_tipoComp" => array("cla_tipoComp", ""), 
            "Sorter_cla_aplicacion" => array("cla_aplicacion", ""), 
            "Sorter_cla_Descripcion" => array("cla_Descripcion", ""), 
            "Sorter_cla_Formulario" => array("cla_Formulario", ""), 
            "Sorter_cla_Informe" => array("cla_Informe", "")));
    }
//End SetOrder Method

//Prepare Method @100-6B67FC04
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_cla_aplicacion", ccsText, "", "", $this->Parameters["urls_cla_aplicacion"], "", false);
        $this->wp->AddParameter("2", "urls_cla_tipoComp", ccsText, "", "", $this->Parameters["urls_cla_tipoComp"], "", false);
        $this->wp->AddParameter("3", "urls_cla_Descripcion", ccsText, "", "", $this->Parameters["urls_cla_Descripcion"], "", false);
        $this->wp->AddParameter("4", "urls_cla_Formulario", ccsText, "", "", $this->Parameters["urls_cla_Formulario"], "", false);
        $this->wp->AddParameter("5", "urls_cla_Informe", ccsText, "", "", $this->Parameters["urls_cla_Informe"], "", false);
        $this->wp->AddParameter("6", "urls_cla_Contabilizacion", ccsInteger, "", "", $this->Parameters["urls_cla_Contabilizacion"], "", false);
        $this->wp->AddParameter("7", "urls_cla_Indicador", ccsInteger, "", "", $this->Parameters["urls_cla_Indicador"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "cla_aplicacion", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "cla_tipoComp", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "cla_Descripcion", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "cla_Formulario", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "cla_Informe", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "cla_Contabilizacion", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsInteger),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opEqual, "cla_Indicador", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[6]), $this->wp->Criterion[7]);
    }
//End Prepare Method

//Open Method @100-8E7EB4D1
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM genclasetran";
        $this->SQL = "SELECT *  " .
        "FROM genclasetran";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @100-1B11024B
    function SetValues()
    {
        $this->cla_tipoComp->SetDBValue($this->f("cla_tipoComp"));
        $this->cla_aplicacion->SetDBValue($this->f("cla_aplicacion"));
        $this->cla_Descripcion->SetDBValue($this->f("cla_Descripcion"));
        $this->cla_Formulario->SetDBValue($this->f("cla_Formulario"));
        $this->cla_Informe->SetDBValue($this->f("cla_Informe"));
        $this->Alt_cla_tipoComp->SetDBValue($this->f("cla_tipoComp"));
        $this->Alt_cla_aplicacion->SetDBValue($this->f("cla_aplicacion"));
        $this->Alt_cla_Descripcion->SetDBValue($this->f("cla_Descripcion"));
        $this->Alt_cla_Formulario->SetDBValue($this->f("cla_Formulario"));
        $this->Alt_cla_Informe->SetDBValue($this->f("cla_Informe"));
    }
//End SetValues Method

} //End CoAdTi_listDataSource Class @100-FCB6E20C



//Initialize Page @1-0A0990A9
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

$FileName = "CoAdTi.php";
$Redirect = "";
$TemplateFileName = "CoAdTi.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-3B693720
$DBSeguridad = new clsDBSeguridad();
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$CoAdTi_qry = new clsRecordCoAdTi_qry();
$CoAdTi_list = new clsGridCoAdTi_list();
$CoAdTi_list->Initialize();

// Events
include("./CoAdTi_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-408FA667
$Cabecera->Operations();
$CoAdTi_qry->Operation();
//End Execute Components

//Go to destination page @1-468824C4
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBSeguridad->close();
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-4FC49BCF
$Cabecera->Show("Cabecera");
$CoAdTi_qry->Show();
$CoAdTi_list->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-945CF9B5
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBSeguridad->close();
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
