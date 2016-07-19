<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @134-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordconpoliticas_concuentas_cSearch { //conpoliticas_concuentas_cSearch Class @93-4F6E3890

//Variables @93-CB19EB75

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

//Class_Initialize Event @93-58B77F95
    function clsRecordconpoliticas_concuentas_cSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record conpoliticas_concuentas_cSearch/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "conpoliticas_concuentas_cSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_pol_auxiliar = new clsControl(ccsTextBox, "s_pol_auxiliar", "s_pol_auxiliar", ccsInteger, "", CCGetRequestParam("s_pol_auxiliar", $Method));
            $this->s_per_Apellidos = new clsControl(ccsTextBox, "s_per_Apellidos", "s_per_Apellidos", ccsText, "", CCGetRequestParam("s_per_Apellidos", $Method));
            $this->s_pol_semana = new clsControl(ccsTextBox, "s_pol_semana", "s_pol_semana", ccsInteger, "", CCGetRequestParam("s_pol_semana", $Method));
            $this->s_pol_Cuenta = new clsControl(ccsTextBox, "s_pol_Cuenta", "s_pol_Cuenta", ccsText, "", CCGetRequestParam("s_pol_Cuenta", $Method));
            $this->s_cue_Descripcion = new clsControl(ccsTextBox, "s_cue_Descripcion", "s_cue_Descripcion", ccsText, "", CCGetRequestParam("s_cue_Descripcion", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @93-7488944C
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_pol_auxiliar->Validate() && $Validation);
        $Validation = ($this->s_per_Apellidos->Validate() && $Validation);
        $Validation = ($this->s_pol_semana->Validate() && $Validation);
        $Validation = ($this->s_pol_Cuenta->Validate() && $Validation);
        $Validation = ($this->s_cue_Descripcion->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @93-901D04EF
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_pol_auxiliar->Errors->Count());
        $errors = ($errors || $this->s_per_Apellidos->Errors->Count());
        $errors = ($errors || $this->s_pol_semana->Errors->Count());
        $errors = ($errors || $this->s_pol_Cuenta->Errors->Count());
        $errors = ($errors || $this->s_cue_Descripcion->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @93-F0609BA4
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
        $Redirect = "LiLiPr_politica.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiLiPr_politica.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @93-288E0991
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
            $Error .= $this->s_pol_auxiliar->Errors->ToString();
            $Error .= $this->s_per_Apellidos->Errors->ToString();
            $Error .= $this->s_pol_semana->Errors->ToString();
            $Error .= $this->s_pol_Cuenta->Errors->ToString();
            $Error .= $this->s_cue_Descripcion->Errors->ToString();
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

        $this->s_pol_auxiliar->Show();
        $this->s_per_Apellidos->Show();
        $this->s_pol_semana->Show();
        $this->s_pol_Cuenta->Show();
        $this->s_cue_Descripcion->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End conpoliticas_concuentas_cSearch Class @93-FCB6E20C

class clsGridconpoliticas_concuentas_c { //conpoliticas_concuentas_c class @101-E463B804

//Variables @101-A346C4EA

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
    var $Sorter_pol_auxiliar;
    var $Sorter_per_Apellidos;
    var $Sorter_pol_Cuenta;
    var $Sorter_pol_semana;
    var $Sorter_pol_Valor;
    var $Sorter_pol_Vigencia;
    var $Navigator;
//End Variables

//Class_Initialize Event @101-E44A5F83
    function clsGridconpoliticas_concuentas_c()
    {
        global $FileName;
        $this->ComponentName = "conpoliticas_concuentas_c";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid conpoliticas_concuentas_c";
        $this->ds = new clsconpoliticas_concuentas_cDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 15;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("conpoliticas_concuentas_cOrder", "");
        $this->SorterDirection = CCGetParam("conpoliticas_concuentas_cDir", "");

        $this->pol_auxiliar = new clsControl(ccsLink, "pol_auxiliar", "pol_auxiliar", ccsInteger, "", CCGetRequestParam("pol_auxiliar", ccsGet));
        $this->per_Apellidos = new clsControl(ccsLabel, "per_Apellidos", "per_Apellidos", ccsText, "", CCGetRequestParam("per_Apellidos", ccsGet));
        $this->pol_Cuenta = new clsControl(ccsLabel, "pol_Cuenta", "pol_Cuenta", ccsText, "", CCGetRequestParam("pol_Cuenta", ccsGet));
        $this->cue_Descripcion = new clsControl(ccsLabel, "cue_Descripcion", "cue_Descripcion", ccsText, "", CCGetRequestParam("cue_Descripcion", ccsGet));
        $this->pol_semana = new clsControl(ccsLabel, "pol_semana", "pol_semana", ccsInteger, "", CCGetRequestParam("pol_semana", ccsGet));
        $this->pol_Valor = new clsControl(ccsLabel, "pol_Valor", "pol_Valor", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("pol_Valor", ccsGet));
        $this->pol_Vigencia = new clsControl(ccsLabel, "pol_Vigencia", "pol_Vigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("pol_Vigencia", ccsGet));
        $this->Sorter_pol_auxiliar = new clsSorter($this->ComponentName, "Sorter_pol_auxiliar", $FileName);
        $this->Sorter_per_Apellidos = new clsSorter($this->ComponentName, "Sorter_per_Apellidos", $FileName);
        $this->Sorter_pol_Cuenta = new clsSorter($this->ComponentName, "Sorter_pol_Cuenta", $FileName);
        $this->Sorter_pol_semana = new clsSorter($this->ComponentName, "Sorter_pol_semana", $FileName);
        $this->Sorter_pol_Valor = new clsSorter($this->ComponentName, "Sorter_pol_Valor", $FileName);
        $this->Sorter_pol_Vigencia = new clsSorter($this->ComponentName, "Sorter_pol_Vigencia", $FileName);
        $this->conpoliticas_concuentas_c_Insert = new clsControl(ccsLink, "conpoliticas_concuentas_c_Insert", "conpoliticas_concuentas_c_Insert", ccsText, "", CCGetRequestParam("conpoliticas_concuentas_c_Insert", ccsGet));
        $this->conpoliticas_concuentas_c_Insert->Parameters = CCGetQueryString("QueryString", Array("pol_secuencia", "ccsForm"));
        $this->conpoliticas_concuentas_c_Insert->Page = "LiLiPr_politica.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
    }
//End Class_Initialize Event

//Initialize Method @101-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @101-3462B359
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_pol_secuencia"] = CCGetFromGet("s_pol_secuencia", "");
        $this->ds->Parameters["urls_pol_auxiliar"] = CCGetFromGet("s_pol_auxiliar", "");
        $this->ds->Parameters["urls_per_Apellidos"] = CCGetFromGet("s_per_Apellidos", "");
        $this->ds->Parameters["urls_per_Nombres"] = CCGetFromGet("s_per_Nombres", "");
        $this->ds->Parameters["urls_pol_Cuenta"] = CCGetFromGet("s_pol_Cuenta", "");
        $this->ds->Parameters["urls_cue_Descripcion"] = CCGetFromGet("s_cue_Descripcion", "");
        $this->ds->Parameters["urls_pol_semana"] = CCGetFromGet("s_pol_semana", "");

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
                $this->pol_auxiliar->SetValue($this->ds->pol_auxiliar->GetValue());
                $this->pol_auxiliar->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->pol_auxiliar->Parameters = CCAddParam($this->pol_auxiliar->Parameters, "pol_secuencia", $this->ds->f("pol_secuencia"));
                $this->pol_auxiliar->Page = "LiLiPr_politica.php";
                $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                $this->pol_Cuenta->SetValue($this->ds->pol_Cuenta->GetValue());
                $this->cue_Descripcion->SetValue($this->ds->cue_Descripcion->GetValue());
                $this->pol_semana->SetValue($this->ds->pol_semana->GetValue());
                $this->pol_Valor->SetValue($this->ds->pol_Valor->GetValue());
                $this->pol_Vigencia->SetValue($this->ds->pol_Vigencia->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->pol_auxiliar->Show();
                $this->per_Apellidos->Show();
                $this->pol_Cuenta->Show();
                $this->cue_Descripcion->Show();
                $this->pol_semana->Show();
                $this->pol_Valor->Show();
                $this->pol_Vigencia->Show();
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
        $this->Sorter_pol_auxiliar->Show();
        $this->Sorter_per_Apellidos->Show();
        $this->Sorter_pol_Cuenta->Show();
        $this->Sorter_pol_semana->Show();
        $this->Sorter_pol_Valor->Show();
        $this->Sorter_pol_Vigencia->Show();
        $this->conpoliticas_concuentas_c_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @101-6A2E4D88
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->pol_auxiliar->Errors->ToString();
        $errors .= $this->per_Apellidos->Errors->ToString();
        $errors .= $this->pol_Cuenta->Errors->ToString();
        $errors .= $this->cue_Descripcion->Errors->ToString();
        $errors .= $this->pol_semana->Errors->ToString();
        $errors .= $this->pol_Valor->Errors->ToString();
        $errors .= $this->pol_Vigencia->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End conpoliticas_concuentas_c Class @101-FCB6E20C

class clsconpoliticas_concuentas_cDataSource extends clsDBdatos {  //conpoliticas_concuentas_cDataSource Class @101-CE569EEF

//DataSource Variables @101-0F8AA7F3
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $pol_auxiliar;
    var $per_Apellidos;
    var $pol_Cuenta;
    var $cue_Descripcion;
    var $pol_semana;
    var $pol_Valor;
    var $pol_Vigencia;
//End DataSource Variables

//Class_Initialize Event @101-47C10D67
    function clsconpoliticas_concuentas_cDataSource()
    {
        $this->ErrorBlock = "Grid conpoliticas_concuentas_c";
        $this->Initialize();
        $this->pol_auxiliar = new clsField("pol_auxiliar", ccsInteger, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->pol_Cuenta = new clsField("pol_Cuenta", ccsText, "");
        $this->cue_Descripcion = new clsField("cue_Descripcion", ccsText, "");
        $this->pol_semana = new clsField("pol_semana", ccsInteger, "");
        $this->pol_Valor = new clsField("pol_Valor", ccsFloat, "");
        $this->pol_Vigencia = new clsField("pol_Vigencia", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End Class_Initialize Event

//SetOrder Method @101-5BA26549
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "per_Apellidos, per_Nombres";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_pol_auxiliar" => array("pol_auxiliar", ""), 
            "Sorter_per_Apellidos" => array("per_Apellidos", ""), 
            "Sorter_pol_Cuenta" => array("pol_Cuenta", ""), 
            "Sorter_pol_semana" => array("pol_semana", ""), 
            "Sorter_pol_Valor" => array("pol_Valor", ""), 
            "Sorter_pol_Vigencia" => array("pol_Vigencia", "")));
    }
//End SetOrder Method

//Prepare Method @101-F59467AB
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_pol_secuencia", ccsInteger, "", "", $this->Parameters["urls_pol_secuencia"], "", false);
        $this->wp->AddParameter("2", "urls_pol_auxiliar", ccsInteger, "", "", $this->Parameters["urls_pol_auxiliar"], "", false);
        $this->wp->AddParameter("3", "urls_per_Apellidos", ccsText, "", "", $this->Parameters["urls_per_Apellidos"], "", false);
        $this->wp->AddParameter("4", "urls_per_Nombres", ccsText, "", "", $this->Parameters["urls_per_Nombres"], "", false);
        $this->wp->AddParameter("5", "urls_pol_Cuenta", ccsText, "", "", $this->Parameters["urls_pol_Cuenta"], "", false);
        $this->wp->AddParameter("6", "urls_cue_Descripcion", ccsText, "", "", $this->Parameters["urls_cue_Descripcion"], "", false);
        $this->wp->AddParameter("7", "urls_pol_semana", ccsInteger, "", "", $this->Parameters["urls_pol_semana"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pol_secuencia", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "pol_auxiliar", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "per_Apellidos", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "per_Nombres", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "pol_Cuenta", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "cue_Descripcion", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opEqual, "pol_semana", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[6]), $this->wp->Criterion[7]);
    }
//End Prepare Method

//Open Method @101-085F86AC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (conpoliticas INNER JOIN conpersonas ON " .
        "conpoliticas.pol_auxiliar = conpersonas.per_CodAuxiliar) INNER JOIN concuentas ON " .
        "conpoliticas.pol_Cuenta = concuentas.Cue_CodCuenta";
        $this->SQL = "SELECT conpoliticas.*, cue_Descripcion, per_Apellidos, per_Nombres, concat(per_Apellidos,' ', per_nombres) AS per_Apellidos, left(cue_Descripcion,25) AS cue_Descripcion  " .
        "FROM (conpoliticas INNER JOIN conpersonas ON " .
        "conpoliticas.pol_auxiliar = conpersonas.per_CodAuxiliar) INNER JOIN concuentas ON " .
        "conpoliticas.pol_Cuenta = concuentas.Cue_CodCuenta";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @101-D19A254C
    function SetValues()
    {
        $this->pol_auxiliar->SetDBValue(trim($this->f("pol_auxiliar")));
        $this->per_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->pol_Cuenta->SetDBValue($this->f("pol_Cuenta"));
        $this->cue_Descripcion->SetDBValue($this->f("cue_Descripcion"));
        $this->pol_semana->SetDBValue(trim($this->f("pol_semana")));
        $this->pol_Valor->SetDBValue(trim($this->f("pol_Valor")));
        $this->pol_Vigencia->SetDBValue(trim($this->f("pol_Vigencia")));
    }
//End SetValues Method

} //End conpoliticas_concuentas_cDataSource Class @101-FCB6E20C

class clsRecordconpoliticas { //conpoliticas Class @72-C87FE771

//Variables @72-4A82E0A3

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

    var $InsertAllowed;
    var $UpdateAllowed;
    var $DeleteAllowed;
    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @72-0E87F383
    function clsRecordconpoliticas()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record conpoliticas/Error";
        $this->ds = new clsconpoliticasDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "conpoliticas";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pol_auxiliar = new clsControl(ccsTextBox, "pol_auxiliar", "Pol Auxiliar", ccsInteger, "", CCGetRequestParam("pol_auxiliar", $Method));
            $this->pol_auxiliar->Required = true;
            $this->pol_Cuenta = new clsControl(ccsTextBox, "pol_Cuenta", "Pol Cuenta", ccsText, "", CCGetRequestParam("pol_Cuenta", $Method));
            $this->pol_Cuenta->Required = true;
            $this->pol_semana = new clsControl(ccsTextBox, "pol_semana", "Pol Semana", ccsInteger, "", CCGetRequestParam("pol_semana", $Method));
            $this->pol_Vigencia = new clsControl(ccsTextBox, "pol_Vigencia", "Pol Vigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("pol_Vigencia", $Method));
            $this->pol_Vigencia->Required = true;
            $this->DatePicker_pol_Vigencia = new clsDatePicker("DatePicker_pol_Vigencia", "conpoliticas", "pol_Vigencia");
            $this->pol_Politica = new clsControl(ccsTextBox, "pol_Politica", "Pol Politica", ccsText, "", CCGetRequestParam("pol_Politica", $Method));
            $this->pol_Valor = new clsControl(ccsTextBox, "pol_Valor", "Pol Valor", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("pol_Valor", $Method));
            $this->pol_Valor->Required = true;
            $this->pol_RegFecha = new clsControl(ccsTextBox, "pol_RegFecha", "Pol Reg Fecha", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("pol_RegFecha", $Method));
            $this->pol_RegFecha->Required = true;
            $this->pol_Ususraio = new clsControl(ccsTextBox, "pol_Ususraio", "Pol Ususraio", ccsText, "", CCGetRequestParam("pol_Ususraio", $Method));
            $this->pol_Ususraio->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
        }
    }
//End Class_Initialize Event

//Initialize Method @72-BB8CFBCA
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlpol_secuencia"] = CCGetFromGet("pol_secuencia", "");
    }
//End Initialize Method

//Validate Method @72-B70A2B2D
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->pol_auxiliar->Validate() && $Validation);
        $Validation = ($this->pol_Cuenta->Validate() && $Validation);
        $Validation = ($this->pol_semana->Validate() && $Validation);
        $Validation = ($this->pol_Vigencia->Validate() && $Validation);
        $Validation = ($this->pol_Politica->Validate() && $Validation);
        $Validation = ($this->pol_Valor->Validate() && $Validation);
        $Validation = ($this->pol_RegFecha->Validate() && $Validation);
        $Validation = ($this->pol_Ususraio->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @72-00C51E05
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pol_auxiliar->Errors->Count());
        $errors = ($errors || $this->pol_Cuenta->Errors->Count());
        $errors = ($errors || $this->pol_semana->Errors->Count());
        $errors = ($errors || $this->pol_Vigencia->Errors->Count());
        $errors = ($errors || $this->DatePicker_pol_Vigencia->Errors->Count());
        $errors = ($errors || $this->pol_Politica->Errors->Count());
        $errors = ($errors || $this->pol_Valor->Errors->Count());
        $errors = ($errors || $this->pol_RegFecha->Errors->Count());
        $errors = ($errors || $this->pol_Ususraio->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @72-ABD6AD9A
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        $this->EditMode = $this->ds->AllParametersSet;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "LiLiPr_politica.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @72-B90B8FF2
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->pol_auxiliar->SetValue($this->pol_auxiliar->GetValue());
        $this->ds->pol_Cuenta->SetValue($this->pol_Cuenta->GetValue());
        $this->ds->pol_semana->SetValue($this->pol_semana->GetValue());
        $this->ds->pol_Vigencia->SetValue($this->pol_Vigencia->GetValue());
        $this->ds->pol_Politica->SetValue($this->pol_Politica->GetValue());
        $this->ds->pol_Valor->SetValue($this->pol_Valor->GetValue());
        $this->ds->pol_RegFecha->SetValue($this->pol_RegFecha->GetValue());
        $this->ds->pol_Ususraio->SetValue($this->pol_Ususraio->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @72-35050C37
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->pol_auxiliar->SetValue($this->pol_auxiliar->GetValue());
        $this->ds->pol_Cuenta->SetValue($this->pol_Cuenta->GetValue());
        $this->ds->pol_semana->SetValue($this->pol_semana->GetValue());
        $this->ds->pol_Vigencia->SetValue($this->pol_Vigencia->GetValue());
        $this->ds->pol_Politica->SetValue($this->pol_Politica->GetValue());
        $this->ds->pol_Valor->SetValue($this->pol_Valor->GetValue());
        $this->ds->pol_RegFecha->SetValue($this->pol_RegFecha->GetValue());
        $this->ds->pol_Ususraio->SetValue($this->pol_Ususraio->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . $this->ComponentName . " / Update Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @72-EA88835F
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . ComponentName . " / Delete Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @72-5104496F
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->open();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if($this->EditMode)
        {
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record conpoliticas";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->pol_auxiliar->SetValue($this->ds->pol_auxiliar->GetValue());
                        $this->pol_Cuenta->SetValue($this->ds->pol_Cuenta->GetValue());
                        $this->pol_semana->SetValue($this->ds->pol_semana->GetValue());
                        $this->pol_Vigencia->SetValue($this->ds->pol_Vigencia->GetValue());
                        $this->pol_Politica->SetValue($this->ds->pol_Politica->GetValue());
                        $this->pol_Valor->SetValue($this->ds->pol_Valor->GetValue());
                        $this->pol_RegFecha->SetValue($this->ds->pol_RegFecha->GetValue());
                        $this->pol_Ususraio->SetValue($this->ds->pol_Ususraio->GetValue());
                    }
                }
                else
                {
                    $this->EditMode = false;
                }
            }
        }
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->pol_auxiliar->Errors->ToString();
            $Error .= $this->pol_Cuenta->Errors->ToString();
            $Error .= $this->pol_semana->Errors->ToString();
            $Error .= $this->pol_Vigencia->Errors->ToString();
            $Error .= $this->DatePicker_pol_Vigencia->Errors->ToString();
            $Error .= $this->pol_Politica->Errors->ToString();
            $Error .= $this->pol_Valor->Errors->ToString();
            $Error .= $this->pol_RegFecha->Errors->ToString();
            $Error .= $this->pol_Ususraio->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->pol_auxiliar->Show();
        $this->pol_Cuenta->Show();
        $this->pol_semana->Show();
        $this->pol_Vigencia->Show();
        $this->DatePicker_pol_Vigencia->Show();
        $this->pol_Politica->Show();
        $this->pol_Valor->Show();
        $this->pol_RegFecha->Show();
        $this->pol_Ususraio->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End conpoliticas Class @72-FCB6E20C

class clsconpoliticasDataSource extends clsDBdatos {  //conpoliticasDataSource Class @72-6E66C515

//DataSource Variables @72-1C12E32D
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $pol_auxiliar;
    var $pol_Cuenta;
    var $pol_semana;
    var $pol_Vigencia;
    var $pol_Politica;
    var $pol_Valor;
    var $pol_RegFecha;
    var $pol_Ususraio;
//End DataSource Variables

//Class_Initialize Event @72-2C71DF1B
    function clsconpoliticasDataSource()
    {
        $this->ErrorBlock = "Record conpoliticas/Error";
        $this->Initialize();
        $this->pol_auxiliar = new clsField("pol_auxiliar", ccsInteger, "");
        $this->pol_Cuenta = new clsField("pol_Cuenta", ccsText, "");
        $this->pol_semana = new clsField("pol_semana", ccsInteger, "");
        $this->pol_Vigencia = new clsField("pol_Vigencia", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->pol_Politica = new clsField("pol_Politica", ccsText, "");
        $this->pol_Valor = new clsField("pol_Valor", ccsFloat, "");
        $this->pol_RegFecha = new clsField("pol_RegFecha", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->pol_Ususraio = new clsField("pol_Ususraio", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @72-8157B38E
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlpol_secuencia", ccsInteger, "", "", $this->Parameters["urlpol_secuencia"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pol_secuencia", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @72-D983D78C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM conpoliticas";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @72-BB6EBE7C
    function SetValues()
    {
        $this->pol_auxiliar->SetDBValue(trim($this->f("pol_auxiliar")));
        $this->pol_Cuenta->SetDBValue($this->f("pol_Cuenta"));
        $this->pol_semana->SetDBValue(trim($this->f("pol_semana")));
        $this->pol_Vigencia->SetDBValue(trim($this->f("pol_Vigencia")));
        $this->pol_Politica->SetDBValue($this->f("pol_Politica"));
        $this->pol_Valor->SetDBValue(trim($this->f("pol_Valor")));
        $this->pol_RegFecha->SetDBValue(trim($this->f("pol_RegFecha")));
        $this->pol_Ususraio->SetDBValue($this->f("pol_Ususraio"));
    }
//End SetValues Method

//Insert Method @72-D13D403B
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO conpoliticas ("
             . "pol_auxiliar, "
             . "pol_Cuenta, "
             . "pol_semana, "
             . "pol_Vigencia, "
             . "pol_Politica, "
             . "pol_Valor, "
             . "pol_RegFecha, "
             . "pol_Ususraio"
             . ") VALUES ("
             . $this->ToSQL($this->pol_auxiliar->GetDBValue(), $this->pol_auxiliar->DataType) . ", "
             . $this->ToSQL($this->pol_Cuenta->GetDBValue(), $this->pol_Cuenta->DataType) . ", "
             . $this->ToSQL($this->pol_semana->GetDBValue(), $this->pol_semana->DataType) . ", "
             . $this->ToSQL($this->pol_Vigencia->GetDBValue(), $this->pol_Vigencia->DataType) . ", "
             . $this->ToSQL($this->pol_Politica->GetDBValue(), $this->pol_Politica->DataType) . ", "
             . $this->ToSQL($this->pol_Valor->GetDBValue(), $this->pol_Valor->DataType) . ", "
             . $this->ToSQL($this->pol_RegFecha->GetDBValue(), $this->pol_RegFecha->DataType) . ", "
             . $this->ToSQL($this->pol_Ususraio->GetDBValue(), $this->pol_Ususraio->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @72-0D12014A
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE conpoliticas SET "
             . "pol_auxiliar=" . $this->ToSQL($this->pol_auxiliar->GetDBValue(), $this->pol_auxiliar->DataType) . ", "
             . "pol_Cuenta=" . $this->ToSQL($this->pol_Cuenta->GetDBValue(), $this->pol_Cuenta->DataType) . ", "
             . "pol_semana=" . $this->ToSQL($this->pol_semana->GetDBValue(), $this->pol_semana->DataType) . ", "
             . "pol_Vigencia=" . $this->ToSQL($this->pol_Vigencia->GetDBValue(), $this->pol_Vigencia->DataType) . ", "
             . "pol_Politica=" . $this->ToSQL($this->pol_Politica->GetDBValue(), $this->pol_Politica->DataType) . ", "
             . "pol_Valor=" . $this->ToSQL($this->pol_Valor->GetDBValue(), $this->pol_Valor->DataType) . ", "
             . "pol_RegFecha=" . $this->ToSQL($this->pol_RegFecha->GetDBValue(), $this->pol_RegFecha->DataType) . ", "
             . "pol_Ususraio=" . $this->ToSQL($this->pol_Ususraio->GetDBValue(), $this->pol_Ususraio->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @72-97DC06F6
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM conpoliticas";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End conpoliticasDataSource Class @72-FCB6E20C

//Initialize Page @1-FC0C9D43
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

$FileName = "LiLiPr_politica.php";
$Redirect = "";
$TemplateFileName = "LiLiPr_politica.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-C0B96AB5
$DBdatos = new clsDBdatos();

// Controls
//$Cabecera = new clsCabecera(); //fah-rev
//$Cabecera->BindEvents();    //fah-rev
//$Cabecera->TemplatePath = "../De_Files/";
//$Cabecera->Initialize();    //fah-rev

$conpoliticas_concuentas_cSearch = new clsRecordconpoliticas_concuentas_cSearch();
$conpoliticas_concuentas_c = new clsGridconpoliticas_concuentas_c();
$conpoliticas = new clsRecordconpoliticas();
$conpoliticas_concuentas_c->Initialize();
$conpoliticas->Initialize();

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

//Execute Components @1-C6CAFE0E    //fah-rev
//$Cabecera->Operations();          //fah-rev
$conpoliticas_concuentas_cSearch->Operation();
$conpoliticas->Operation();
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

//Show Page @1-EC1C8477
//$Cabecera->Show("Cabecera");    //fah-rev

$conpoliticas_concuentas_cSearch->Show();
$conpoliticas_concuentas_c->Show();
$conpoliticas->Show();
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
