<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @196-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqrubros_qry { //liqrubros_qry Class @53-FBB9DDB9

//Variables @53-CB19EB75

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

//Class_Initialize Event @53-07AE10DB
    function clsRecordliqrubros_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqrubros_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "liqrubros_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @53-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @53-D6729123
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @53-93F0D09E
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
        $Redirect = "LiAdRu_search.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdRu_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @53-7C48BB57
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
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liqrubros_qry Class @53-FCB6E20C

class clsGridliqrubros_list { //liqrubros_list class @2-3E3DF39C

//Variables @2-45094464

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
    var $Sorter_rub_CodRubro;
    var $Sorter_rub_Abreviatura;
    var $Sorter_rub_TipoProceso;
    var $Sorter_rub_Grupo;
    var $Sorter_rub_DescLarga;
    var $Sorter_run_DescCorta;
    var $Sorter_rub_PosOrdinal;
    var $Sorter_rub_variablea;
    var $Sorter_rub_Operacion;
    var $Sorter_rub_Variableb;
    var $Sorter_rub_IndContab;
    var $Sorter_rub_IndDbCr;
    var $Sorter_rub_Fecha;
    var $Sorter_rub_Activo;
//End Variables

//Class_Initialize Event @2-CEF174C0
    function clsGridliqrubros_list()
    {
        global $FileName;
        $this->ComponentName = "liqrubros_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid liqrubros_list";
        $this->ds = new clsliqrubros_listDataSource();
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("liqrubros_listOrder", "");
        $this->SorterDirection = CCGetParam("liqrubros_listDir", "");

        $this->rub_CodRubro = new clsControl(ccsHidden, "rub_CodRubro", "rub_CodRubro", ccsInteger, "", CCGetRequestParam("rub_CodRubro", ccsGet));
        $this->btMantt = new clsButton("btMantt");
        $this->rub_Abreviatura = new clsControl(ccsLabel, "rub_Abreviatura", "rub_Abreviatura", ccsText, "", CCGetRequestParam("rub_Abreviatura", ccsGet));
        $this->rub_TipoProceso = new clsControl(ccsLabel, "rub_TipoProceso", "rub_TipoProceso", ccsInteger, "", CCGetRequestParam("rub_TipoProceso", ccsGet));
        $this->rub_Grupo = new clsControl(ccsLabel, "rub_Grupo", "rub_Grupo", ccsInteger, "", CCGetRequestParam("rub_Grupo", ccsGet));
        $this->rub_DescLarga = new clsControl(ccsLabel, "rub_DescLarga", "rub_DescLarga", ccsText, "", CCGetRequestParam("rub_DescLarga", ccsGet));
        $this->run_DescCorta = new clsControl(ccsLabel, "run_DescCorta", "run_DescCorta", ccsText, "", CCGetRequestParam("run_DescCorta", ccsGet));
        $this->rub_PosOrdinal = new clsControl(ccsLabel, "rub_PosOrdinal", "rub_PosOrdinal", ccsInteger, "", CCGetRequestParam("rub_PosOrdinal", ccsGet));
        $this->rub_Constantea = new clsControl(ccsLabel, "rub_Constantea", "rub_Constantea", ccsFloat, "", CCGetRequestParam("rub_Constantea", ccsGet));
        $this->var_DescripA = new clsControl(ccsLabel, "var_DescripA", "var_DescripA", ccsText, "", CCGetRequestParam("var_DescripA", ccsGet));
        $this->rub_Operacion = new clsControl(ccsLabel, "rub_Operacion", "rub_Operacion", ccsText, "", CCGetRequestParam("rub_Operacion", ccsGet));
        $this->rub_Constanteb = new clsControl(ccsLabel, "rub_Constanteb", "rub_Constanteb", ccsFloat, "", CCGetRequestParam("rub_Constanteb", ccsGet));
        $this->Alt_var_DescripB = new clsControl(ccsLabel, "Alt_var_DescripB", "Alt_var_DescripB", ccsText, "", CCGetRequestParam("Alt_var_DescripB", ccsGet));
        $this->rub_IndContab = new clsControl(ccsLabel, "rub_IndContab", "rub_IndContab", ccsInteger, "", CCGetRequestParam("rub_IndContab", ccsGet));
        $this->rub_IndDbCr = new clsControl(ccsLabel, "rub_IndDbCr", "rub_IndDbCr", ccsInteger, "", CCGetRequestParam("rub_IndDbCr", ccsGet));
        $this->rub_Fecha = new clsControl(ccsLabel, "rub_Fecha", "rub_Fecha", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("rub_Fecha", ccsGet));
        $this->rub_usuario = new clsControl(ccsLabel, "rub_usuario", "rub_usuario", ccsText, "", CCGetRequestParam("rub_usuario", ccsGet));
        $this->rub_Activo = new clsControl(ccsLabel, "rub_Activo", "rub_Activo", ccsText, "", CCGetRequestParam("rub_Activo", ccsGet));
        $this->Alt_rub_CodRubro = new clsControl(ccsHidden, "Alt_rub_CodRubro", "Alt_rub_CodRubro", ccsInteger, "", CCGetRequestParam("Alt_rub_CodRubro", ccsGet));
        $this->btMantt2 = new clsButton("btMantt2");
        $this->Alt_rub_Abreviatura = new clsControl(ccsLabel, "Alt_rub_Abreviatura", "Alt_rub_Abreviatura", ccsText, "", CCGetRequestParam("Alt_rub_Abreviatura", ccsGet));
        $this->Alt_rub_TipoProceso = new clsControl(ccsLabel, "Alt_rub_TipoProceso", "Alt_rub_TipoProceso", ccsInteger, "", CCGetRequestParam("Alt_rub_TipoProceso", ccsGet));
        $this->Alt_rub_Grupo = new clsControl(ccsLabel, "Alt_rub_Grupo", "Alt_rub_Grupo", ccsInteger, "", CCGetRequestParam("Alt_rub_Grupo", ccsGet));
        $this->Alt_rub_DescLarga = new clsControl(ccsLabel, "Alt_rub_DescLarga", "Alt_rub_DescLarga", ccsText, "", CCGetRequestParam("Alt_rub_DescLarga", ccsGet));
        $this->Alt_run_DescCorta = new clsControl(ccsLabel, "Alt_run_DescCorta", "Alt_run_DescCorta", ccsText, "", CCGetRequestParam("Alt_run_DescCorta", ccsGet));
        $this->Alt_rub_PosOrdinal = new clsControl(ccsLabel, "Alt_rub_PosOrdinal", "Alt_rub_PosOrdinal", ccsInteger, "", CCGetRequestParam("Alt_rub_PosOrdinal", ccsGet));
        $this->Alt_rub_Constantea = new clsControl(ccsLabel, "Alt_rub_Constantea", "Alt_rub_Constantea", ccsFloat, "", CCGetRequestParam("Alt_rub_Constantea", ccsGet));
        $this->Alt_var_DescripA = new clsControl(ccsLabel, "Alt_var_DescripA", "Alt_var_DescripA", ccsText, "", CCGetRequestParam("Alt_var_DescripA", ccsGet));
        $this->Alt_rub_Operacion = new clsControl(ccsLabel, "Alt_rub_Operacion", "Alt_rub_Operacion", ccsText, "", CCGetRequestParam("Alt_rub_Operacion", ccsGet));
        $this->Alt_rub_Constanteb = new clsControl(ccsLabel, "Alt_rub_Constanteb", "Alt_rub_Constanteb", ccsFloat, "", CCGetRequestParam("Alt_rub_Constanteb", ccsGet));
        $this->var_DescripB = new clsControl(ccsLabel, "var_DescripB", "var_DescripB", ccsText, "", CCGetRequestParam("var_DescripB", ccsGet));
        $this->Alt_rub_IndContab = new clsControl(ccsLabel, "Alt_rub_IndContab", "Alt_rub_IndContab", ccsInteger, "", CCGetRequestParam("Alt_rub_IndContab", ccsGet));
        $this->Alt_rub_IndDbCr = new clsControl(ccsLabel, "Alt_rub_IndDbCr", "Alt_rub_IndDbCr", ccsInteger, "", CCGetRequestParam("Alt_rub_IndDbCr", ccsGet));
        $this->Alt_rub_Fecha = new clsControl(ccsLabel, "Alt_rub_Fecha", "Alt_rub_Fecha", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("Alt_rub_Fecha", ccsGet));
        $this->Alt_rub_usuario = new clsControl(ccsLabel, "Alt_rub_usuario", "Alt_rub_usuario", ccsText, "", CCGetRequestParam("Alt_rub_usuario", ccsGet));
        $this->Alt_rub_Activo = new clsControl(ccsLabel, "Alt_rub_Activo", "Alt_rub_Activo", ccsText, "", CCGetRequestParam("Alt_rub_Activo", ccsGet));
        $this->liqrubros_r_genvarproceso_TotalRecords = new clsControl(ccsLabel, "liqrubros_r_genvarproceso_TotalRecords", "liqrubros_r_genvarproceso_TotalRecords", ccsText, "", CCGetRequestParam("liqrubros_r_genvarproceso_TotalRecords", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_rub_CodRubro = new clsSorter($this->ComponentName, "Sorter_rub_CodRubro", $FileName);
        $this->Sorter_rub_Abreviatura = new clsSorter($this->ComponentName, "Sorter_rub_Abreviatura", $FileName);
        $this->Sorter_rub_TipoProceso = new clsSorter($this->ComponentName, "Sorter_rub_TipoProceso", $FileName);
        $this->Sorter_rub_Grupo = new clsSorter($this->ComponentName, "Sorter_rub_Grupo", $FileName);
        $this->Sorter_rub_DescLarga = new clsSorter($this->ComponentName, "Sorter_rub_DescLarga", $FileName);
        $this->Sorter_run_DescCorta = new clsSorter($this->ComponentName, "Sorter_run_DescCorta", $FileName);
        $this->Sorter_rub_PosOrdinal = new clsSorter($this->ComponentName, "Sorter_rub_PosOrdinal", $FileName);
        $this->Sorter_rub_variablea = new clsSorter($this->ComponentName, "Sorter_rub_variablea", $FileName);
        $this->Sorter_rub_Operacion = new clsSorter($this->ComponentName, "Sorter_rub_Operacion", $FileName);
        $this->Sorter_rub_Variableb = new clsSorter($this->ComponentName, "Sorter_rub_Variableb", $FileName);
        $this->Sorter_rub_IndContab = new clsSorter($this->ComponentName, "Sorter_rub_IndContab", $FileName);
        $this->Sorter_rub_IndDbCr = new clsSorter($this->ComponentName, "Sorter_rub_IndDbCr", $FileName);
        $this->Sorter_rub_Fecha = new clsSorter($this->ComponentName, "Sorter_rub_Fecha", $FileName);
        $this->Sorter_rub_Activo = new clsSorter($this->ComponentName, "Sorter_rub_Activo", $FileName);
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

//Show Method @2-76259A67
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
                    $this->rub_CodRubro->SetValue($this->ds->rub_CodRubro->GetValue());
                    $this->rub_Abreviatura->SetValue($this->ds->rub_Abreviatura->GetValue());
                    $this->rub_TipoProceso->SetValue($this->ds->rub_TipoProceso->GetValue());
                    $this->rub_Grupo->SetValue($this->ds->rub_Grupo->GetValue());
                    $this->rub_DescLarga->SetValue($this->ds->rub_DescLarga->GetValue());
                    $this->run_DescCorta->SetValue($this->ds->run_DescCorta->GetValue());
                    $this->rub_PosOrdinal->SetValue($this->ds->rub_PosOrdinal->GetValue());
                    $this->rub_Constantea->SetValue($this->ds->rub_Constantea->GetValue());
                    $this->var_DescripA->SetValue($this->ds->var_DescripA->GetValue());
                    $this->rub_Operacion->SetValue($this->ds->rub_Operacion->GetValue());
                    $this->rub_Constanteb->SetValue($this->ds->rub_Constanteb->GetValue());
                    $this->Alt_var_DescripB->SetValue($this->ds->Alt_var_DescripB->GetValue());
                    $this->rub_IndContab->SetValue($this->ds->rub_IndContab->GetValue());
                    $this->rub_IndDbCr->SetValue($this->ds->rub_IndDbCr->GetValue());
                    $this->rub_Fecha->SetValue($this->ds->rub_Fecha->GetValue());
                    $this->rub_usuario->SetValue($this->ds->rub_usuario->GetValue());
                    $this->rub_Activo->SetValue($this->ds->rub_Activo->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->rub_CodRubro->Show();
                    $this->btMantt->Show();
                    $this->rub_Abreviatura->Show();
                    $this->rub_TipoProceso->Show();
                    $this->rub_Grupo->Show();
                    $this->rub_DescLarga->Show();
                    $this->run_DescCorta->Show();
                    $this->rub_PosOrdinal->Show();
                    $this->rub_Constantea->Show();
                    $this->var_DescripA->Show();
                    $this->rub_Operacion->Show();
                    $this->rub_Constanteb->Show();
                    $this->Alt_var_DescripB->Show();
                    $this->rub_IndContab->Show();
                    $this->rub_IndDbCr->Show();
                    $this->rub_Fecha->Show();
                    $this->rub_usuario->Show();
                    $this->rub_Activo->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_rub_CodRubro->SetValue($this->ds->Alt_rub_CodRubro->GetValue());
                    $this->Alt_rub_Abreviatura->SetValue($this->ds->Alt_rub_Abreviatura->GetValue());
                    $this->Alt_rub_TipoProceso->SetValue($this->ds->Alt_rub_TipoProceso->GetValue());
                    $this->Alt_rub_Grupo->SetValue($this->ds->Alt_rub_Grupo->GetValue());
                    $this->Alt_rub_DescLarga->SetValue($this->ds->Alt_rub_DescLarga->GetValue());
                    $this->Alt_run_DescCorta->SetValue($this->ds->Alt_run_DescCorta->GetValue());
                    $this->Alt_rub_PosOrdinal->SetValue($this->ds->Alt_rub_PosOrdinal->GetValue());
                    $this->Alt_rub_Constantea->SetValue($this->ds->Alt_rub_Constantea->GetValue());
                    $this->Alt_var_DescripA->SetValue($this->ds->Alt_var_DescripA->GetValue());
                    $this->Alt_rub_Operacion->SetValue($this->ds->Alt_rub_Operacion->GetValue());
                    $this->Alt_rub_Constanteb->SetValue($this->ds->Alt_rub_Constanteb->GetValue());
                    $this->var_DescripB->SetValue($this->ds->var_DescripB->GetValue());
                    $this->Alt_rub_IndContab->SetValue($this->ds->Alt_rub_IndContab->GetValue());
                    $this->Alt_rub_IndDbCr->SetValue($this->ds->Alt_rub_IndDbCr->GetValue());
                    $this->Alt_rub_Fecha->SetValue($this->ds->Alt_rub_Fecha->GetValue());
                    $this->Alt_rub_usuario->SetValue($this->ds->Alt_rub_usuario->GetValue());
                    $this->Alt_rub_Activo->SetValue($this->ds->Alt_rub_Activo->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_rub_CodRubro->Show();
                    $this->btMantt2->Show();
                    $this->Alt_rub_Abreviatura->Show();
                    $this->Alt_rub_TipoProceso->Show();
                    $this->Alt_rub_Grupo->Show();
                    $this->Alt_rub_DescLarga->Show();
                    $this->Alt_run_DescCorta->Show();
                    $this->Alt_rub_PosOrdinal->Show();
                    $this->Alt_rub_Constantea->Show();
                    $this->Alt_var_DescripA->Show();
                    $this->Alt_rub_Operacion->Show();
                    $this->Alt_rub_Constanteb->Show();
                    $this->var_DescripB->Show();
                    $this->Alt_rub_IndContab->Show();
                    $this->Alt_rub_IndDbCr->Show();
                    $this->Alt_rub_Fecha->Show();
                    $this->Alt_rub_usuario->Show();
                    $this->Alt_rub_Activo->Show();
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
        $this->liqrubros_r_genvarproceso_TotalRecords->Show();
        $this->Navigator->Show();
        $this->btNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_rub_CodRubro->Show();
        $this->Sorter_rub_Abreviatura->Show();
        $this->Sorter_rub_TipoProceso->Show();
        $this->Sorter_rub_Grupo->Show();
        $this->Sorter_rub_DescLarga->Show();
        $this->Sorter_run_DescCorta->Show();
        $this->Sorter_rub_PosOrdinal->Show();
        $this->Sorter_rub_variablea->Show();
        $this->Sorter_rub_Operacion->Show();
        $this->Sorter_rub_Variableb->Show();
        $this->Sorter_rub_IndContab->Show();
        $this->Sorter_rub_IndDbCr->Show();
        $this->Sorter_rub_Fecha->Show();
        $this->Sorter_rub_Activo->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-5B9952CF
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->rub_CodRubro->Errors->ToString();
        $errors .= $this->rub_Abreviatura->Errors->ToString();
        $errors .= $this->rub_TipoProceso->Errors->ToString();
        $errors .= $this->rub_Grupo->Errors->ToString();
        $errors .= $this->rub_DescLarga->Errors->ToString();
        $errors .= $this->run_DescCorta->Errors->ToString();
        $errors .= $this->rub_PosOrdinal->Errors->ToString();
        $errors .= $this->rub_Constantea->Errors->ToString();
        $errors .= $this->var_DescripA->Errors->ToString();
        $errors .= $this->rub_Operacion->Errors->ToString();
        $errors .= $this->rub_Constanteb->Errors->ToString();
        $errors .= $this->Alt_var_DescripB->Errors->ToString();
        $errors .= $this->rub_IndContab->Errors->ToString();
        $errors .= $this->rub_IndDbCr->Errors->ToString();
        $errors .= $this->rub_Fecha->Errors->ToString();
        $errors .= $this->rub_usuario->Errors->ToString();
        $errors .= $this->rub_Activo->Errors->ToString();
        $errors .= $this->Alt_rub_CodRubro->Errors->ToString();
        $errors .= $this->Alt_rub_Abreviatura->Errors->ToString();
        $errors .= $this->Alt_rub_TipoProceso->Errors->ToString();
        $errors .= $this->Alt_rub_Grupo->Errors->ToString();
        $errors .= $this->Alt_rub_DescLarga->Errors->ToString();
        $errors .= $this->Alt_run_DescCorta->Errors->ToString();
        $errors .= $this->Alt_rub_PosOrdinal->Errors->ToString();
        $errors .= $this->Alt_rub_Constantea->Errors->ToString();
        $errors .= $this->Alt_var_DescripA->Errors->ToString();
        $errors .= $this->Alt_rub_Operacion->Errors->ToString();
        $errors .= $this->Alt_rub_Constanteb->Errors->ToString();
        $errors .= $this->var_DescripB->Errors->ToString();
        $errors .= $this->Alt_rub_IndContab->Errors->ToString();
        $errors .= $this->Alt_rub_IndDbCr->Errors->ToString();
        $errors .= $this->Alt_rub_Fecha->Errors->ToString();
        $errors .= $this->Alt_rub_usuario->Errors->ToString();
        $errors .= $this->Alt_rub_Activo->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End liqrubros_list Class @2-FCB6E20C

class clsliqrubros_listDataSource extends clsDBdatos {  //liqrubros_listDataSource Class @2-3C953186

//DataSource Variables @2-D34C6A53
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $rub_CodRubro;
    var $rub_Abreviatura;
    var $rub_TipoProceso;
    var $rub_Grupo;
    var $rub_DescLarga;
    var $run_DescCorta;
    var $rub_PosOrdinal;
    var $rub_Constantea;
    var $var_DescripA;
    var $rub_Operacion;
    var $rub_Constanteb;
    var $Alt_var_DescripB;
    var $rub_IndContab;
    var $rub_IndDbCr;
    var $rub_Fecha;
    var $rub_usuario;
    var $rub_Activo;
    var $Alt_rub_CodRubro;
    var $Alt_rub_Abreviatura;
    var $Alt_rub_TipoProceso;
    var $Alt_rub_Grupo;
    var $Alt_rub_DescLarga;
    var $Alt_run_DescCorta;
    var $Alt_rub_PosOrdinal;
    var $Alt_rub_Constantea;
    var $Alt_var_DescripA;
    var $Alt_rub_Operacion;
    var $Alt_rub_Constanteb;
    var $var_DescripB;
    var $Alt_rub_IndContab;
    var $Alt_rub_IndDbCr;
    var $Alt_rub_Fecha;
    var $Alt_rub_usuario;
    var $Alt_rub_Activo;
//End DataSource Variables

//Class_Initialize Event @2-8B50EAD3
    function clsliqrubros_listDataSource()
    {
        $this->ErrorBlock = "Grid liqrubros_list";
        $this->Initialize();
        $this->rub_CodRubro = new clsField("rub_CodRubro", ccsInteger, "");
        $this->rub_Abreviatura = new clsField("rub_Abreviatura", ccsText, "");
        $this->rub_TipoProceso = new clsField("rub_TipoProceso", ccsInteger, "");
        $this->rub_Grupo = new clsField("rub_Grupo", ccsInteger, "");
        $this->rub_DescLarga = new clsField("rub_DescLarga", ccsText, "");
        $this->run_DescCorta = new clsField("run_DescCorta", ccsText, "");
        $this->rub_PosOrdinal = new clsField("rub_PosOrdinal", ccsInteger, "");
        $this->rub_Constantea = new clsField("rub_Constantea", ccsFloat, "");
        $this->var_DescripA = new clsField("var_DescripA", ccsText, "");
        $this->rub_Operacion = new clsField("rub_Operacion", ccsText, "");
        $this->rub_Constanteb = new clsField("rub_Constanteb", ccsFloat, "");
        $this->Alt_var_DescripB = new clsField("Alt_var_DescripB", ccsText, "");
        $this->rub_IndContab = new clsField("rub_IndContab", ccsInteger, "");
        $this->rub_IndDbCr = new clsField("rub_IndDbCr", ccsInteger, "");
        $this->rub_Fecha = new clsField("rub_Fecha", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->rub_usuario = new clsField("rub_usuario", ccsText, "");
        $this->rub_Activo = new clsField("rub_Activo", ccsText, "");
        $this->Alt_rub_CodRubro = new clsField("Alt_rub_CodRubro", ccsInteger, "");
        $this->Alt_rub_Abreviatura = new clsField("Alt_rub_Abreviatura", ccsText, "");
        $this->Alt_rub_TipoProceso = new clsField("Alt_rub_TipoProceso", ccsInteger, "");
        $this->Alt_rub_Grupo = new clsField("Alt_rub_Grupo", ccsInteger, "");
        $this->Alt_rub_DescLarga = new clsField("Alt_rub_DescLarga", ccsText, "");
        $this->Alt_run_DescCorta = new clsField("Alt_run_DescCorta", ccsText, "");
        $this->Alt_rub_PosOrdinal = new clsField("Alt_rub_PosOrdinal", ccsInteger, "");
        $this->Alt_rub_Constantea = new clsField("Alt_rub_Constantea", ccsFloat, "");
        $this->Alt_var_DescripA = new clsField("Alt_var_DescripA", ccsText, "");
        $this->Alt_rub_Operacion = new clsField("Alt_rub_Operacion", ccsText, "");
        $this->Alt_rub_Constanteb = new clsField("Alt_rub_Constanteb", ccsFloat, "");
        $this->var_DescripB = new clsField("var_DescripB", ccsText, "");
        $this->Alt_rub_IndContab = new clsField("Alt_rub_IndContab", ccsInteger, "");
        $this->Alt_rub_IndDbCr = new clsField("Alt_rub_IndDbCr", ccsInteger, "");
        $this->Alt_rub_Fecha = new clsField("Alt_rub_Fecha", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_rub_usuario = new clsField("Alt_rub_usuario", ccsText, "");
        $this->Alt_rub_Activo = new clsField("Alt_rub_Activo", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-E3BE9BC8
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "rub_Grupo, rub_PosOrdinal";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_rub_CodRubro" => array("rub_CodRubro", ""), 
            "Sorter_rub_Abreviatura" => array("rub_Abreviatura", ""), 
            "Sorter_rub_TipoProceso" => array("rub_TipoProceso", ""), 
            "Sorter_rub_Grupo" => array("rub_Grupo", ""), 
            "Sorter_rub_DescLarga" => array("rub_DescLarga", ""), 
            "Sorter_run_DescCorta" => array("run_DescCorta", ""), 
            "Sorter_rub_PosOrdinal" => array("rub_PosOrdinal", ""), 
            "Sorter_rub_variablea" => array("rub_variablea", ""), 
            "Sorter_rub_Operacion" => array("rub_Operacion", ""), 
            "Sorter_rub_Variableb" => array("rub_Variableb", ""), 
            "Sorter_rub_IndContab" => array("rub_IndContab", ""), 
            "Sorter_rub_IndDbCr" => array("rub_IndDbCr", ""), 
            "Sorter_rub_Fecha" => array("rub_Fecha", ""), 
            "Sorter_rub_Activo" => array("rub_Activo", "")));
    }
//End SetOrder Method

//Prepare Method @2-977E6AA2
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("2", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("3", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("4", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("5", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("6", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("7", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "rub_CodRubro", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opBeginsWith, "rub_TipoProceso", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opBeginsWith, "rub_Grupo", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "rub_Abreviatura", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "rub_DescLarga", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "rub_CtaOrigen", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "rub_CtaDestino", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsText),false);
        $this->Where = $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[6]), $this->wp->Criterion[7]);
    }
//End Prepare Method

//Open Method @2-85EA18E0
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (liqrubros r LEFT JOIN genvarproceso a ON " .
        "r.rub_variablea = a.var_Nombre) LEFT JOIN genvarproceso b ON " .
        "r.rub_Variableb = b.var_Nombre";
        $this->SQL = "SELECT r.*, b.var_Nombre AS var_VariableB, b.var_Descripcion AS var_DescripB, a.var_Nombre AS var_VariableA, a.var_Descripcion AS var_DescripA  " .
        "FROM (liqrubros r LEFT JOIN genvarproceso a ON " .
        "r.rub_variablea = a.var_Nombre) LEFT JOIN genvarproceso b ON " .
        "r.rub_Variableb = b.var_Nombre";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-18AADC33
    function SetValues()
    {
        $this->rub_CodRubro->SetDBValue(trim($this->f("rub_CodRubro")));
        $this->rub_Abreviatura->SetDBValue($this->f("rub_Abreviatura"));
        $this->rub_TipoProceso->SetDBValue(trim($this->f("rub_TipoProceso")));
        $this->rub_Grupo->SetDBValue(trim($this->f("rub_Grupo")));
        $this->rub_DescLarga->SetDBValue($this->f("rub_DescLarga"));
        $this->run_DescCorta->SetDBValue($this->f("run_DescCorta"));
        $this->rub_PosOrdinal->SetDBValue(trim($this->f("rub_PosOrdinal")));
        $this->rub_Constantea->SetDBValue(trim($this->f("rub_Constantea")));
        $this->var_DescripA->SetDBValue($this->f("var_DescripA"));
        $this->rub_Operacion->SetDBValue($this->f("rub_Operacion"));
        $this->rub_Constanteb->SetDBValue(trim($this->f("rub_Constanteb")));
        $this->Alt_var_DescripB->SetDBValue($this->f("var_DescripB"));
        $this->rub_IndContab->SetDBValue(trim($this->f("rub_IndContab")));
        $this->rub_IndDbCr->SetDBValue(trim($this->f("rub_IndDbCr")));
        $this->rub_Fecha->SetDBValue(trim($this->f("rub_Fecha")));
        $this->rub_usuario->SetDBValue($this->f("rub_usuario"));
        $this->rub_Activo->SetDBValue($this->f("rub_Activo"));
        $this->Alt_rub_CodRubro->SetDBValue(trim($this->f("rub_CodRubro")));
        $this->Alt_rub_Abreviatura->SetDBValue($this->f("rub_Abreviatura"));
        $this->Alt_rub_TipoProceso->SetDBValue(trim($this->f("rub_TipoProceso")));
        $this->Alt_rub_Grupo->SetDBValue(trim($this->f("rub_Grupo")));
        $this->Alt_rub_DescLarga->SetDBValue($this->f("rub_DescLarga"));
        $this->Alt_run_DescCorta->SetDBValue($this->f("run_DescCorta"));
        $this->Alt_rub_PosOrdinal->SetDBValue(trim($this->f("rub_PosOrdinal")));
        $this->Alt_rub_Constantea->SetDBValue(trim($this->f("rub_Constantea")));
        $this->Alt_var_DescripA->SetDBValue($this->f("var_DescripA"));
        $this->Alt_rub_Operacion->SetDBValue($this->f("rub_Operacion"));
        $this->Alt_rub_Constanteb->SetDBValue(trim($this->f("rub_Constanteb")));
        $this->var_DescripB->SetDBValue($this->f("var_DescripB"));
        $this->Alt_rub_IndContab->SetDBValue(trim($this->f("rub_IndContab")));
        $this->Alt_rub_IndDbCr->SetDBValue(trim($this->f("rub_IndDbCr")));
        $this->Alt_rub_Fecha->SetDBValue(trim($this->f("rub_Fecha")));
        $this->Alt_rub_usuario->SetDBValue($this->f("rub_usuario"));
        $this->Alt_rub_Activo->SetDBValue($this->f("rub_Activo"));
    }
//End SetValues Method

} //End liqrubros_listDataSource Class @2-FCB6E20C

//Initialize Page @1-93338C58
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

$FileName = "LiAdRu_search.php";
$Redirect = "";
$TemplateFileName = "LiAdRu_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-1A0F96C6
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqrubros_qry = new clsRecordliqrubros_qry();
$liqrubros_list = new clsGridliqrubros_list();
$liqrubros_list->Initialize();

// Events
include("./LiAdRu_search_events.php");
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

//Execute Components @1-E736AA1A
$Cabecera->Operations();
$liqrubros_qry->Operation();
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

//Show Page @1-914CD9B1
$Cabecera->Show("Cabecera");
$liqrubros_qry->Show();
$liqrubros_list->Show();
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
