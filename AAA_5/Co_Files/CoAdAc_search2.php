<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @159-6BA6AD4B
include_once(RelativePath . "/Co_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordconactivosSearch { //conactivosSearch Class @2-224218AF

//Variables @2-B2F7A83E

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

//Class_Initialize Event @2-8D2FF4F9
    function clsRecordconactivosSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record conactivosSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "conactivosSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_act_Abreviatura = new clsControl(ccsTextBox, "s_act_Abreviatura", "s_act_Abreviatura", ccsText, "", CCGetRequestParam("s_act_Abreviatura", $Method));
            $this->s_act_Descripcion = new clsControl(ccsTextBox, "s_act_Descripcion", "s_act_Descripcion", ccsText, "", CCGetRequestParam("s_act_Descripcion", $Method));
            $this->s_act_Marca = new clsControl(ccsTextBox, "s_act_Marca", "s_act_Marca", ccsText, "", CCGetRequestParam("s_act_Marca", $Method));
            $this->s_act_Modelo = new clsControl(ccsTextBox, "s_act_Modelo", "s_act_Modelo", ccsText, "", CCGetRequestParam("s_act_Modelo", $Method));
            $this->s_act_NumSerie = new clsControl(ccsTextBox, "s_act_NumSerie", "s_act_NumSerie", ccsText, "", CCGetRequestParam("s_act_NumSerie", $Method));
            $this->s_act_Tipo = new clsControl(ccsListBox, "s_act_Tipo", "s_act_Tipo", ccsInteger, "", CCGetRequestParam("s_act_Tipo", $Method));
            $this->s_act_Tipo->DSType = dsTable;
            list($this->s_act_Tipo->BoundColumn, $this->s_act_Tipo->TextColumn, $this->s_act_Tipo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_act_Tipo->ds = new clsDBdatos();
            $this->s_act_Tipo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_act_Tipo->ds->wp = new clsSQLParameters();
            $this->s_act_Tipo->ds->wp->Criterion[1] = "par_clave='ACTCLA'";
            $this->s_act_Tipo->ds->Where = 
                 $this->s_act_Tipo->ds->wp->Criterion[1];
            $this->s_act_Grupo = new clsControl(ccsListBox, "s_act_Grupo", "s_act_Grupo", ccsInteger, "", CCGetRequestParam("s_act_Grupo", $Method));
            $this->s_act_Grupo->DSType = dsTable;
            list($this->s_act_Grupo->BoundColumn, $this->s_act_Grupo->TextColumn, $this->s_act_Grupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_act_Grupo->ds = new clsDBdatos();
            $this->s_act_Grupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_act_Grupo->ds->wp = new clsSQLParameters();
            $this->s_act_Grupo->ds->wp->Criterion[1] = "par_clave='ACTGRU'";
            $this->s_act_Grupo->ds->Where = 
                 $this->s_act_Grupo->ds->wp->Criterion[1];
            $this->s_act_SubGrupo = new clsControl(ccsListBox, "s_act_SubGrupo", "s_act_SubGrupo", ccsInteger, "", CCGetRequestParam("s_act_SubGrupo", $Method));
            $this->s_act_SubGrupo->DSType = dsTable;
            list($this->s_act_SubGrupo->BoundColumn, $this->s_act_SubGrupo->TextColumn, $this->s_act_SubGrupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_act_SubGrupo->ds = new clsDBdatos();
            $this->s_act_SubGrupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_act_SubGrupo->ds->wp = new clsSQLParameters();
            $this->s_act_SubGrupo->ds->wp->Criterion[1] = "par_clave='ACTSGR'";
            $this->s_act_SubGrupo->ds->Where = 
                 $this->s_act_SubGrupo->ds->wp->Criterion[1];
            $this->s_act_usuario = new clsControl(ccsTextBox, "s_act_usuario", "s_act_usuario", ccsText, "", CCGetRequestParam("s_act_usuario", $Method));
            $this->s_act_CodAnterior = new clsControl(ccsTextBox, "s_act_CodAnterior", "s_act_CodAnterior", ccsText, "", CCGetRequestParam("s_act_CodAnterior", $Method));
            $this->s_act_Inventariable = new clsControl(ccsCheckBoxList, "s_act_Inventariable", "s_act_Inventariable", ccsInteger, "", CCGetRequestParam("s_act_Inventariable", $Method));
            $this->s_act_Inventariable->Multiple = true;
            $this->s_act_Inventariable->DSType = dsListOfValues;
            $this->s_act_Inventariable->Values = array(array("0", "No"), array("1", "Si"));
            $this->s_act_Inventariable->HTML = true;
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_act_Abreviatura", "s_act_Descripcion", "s_act_Marca", "s_act_Modelo", "s_act_NumSerie", "s_act_Tipo", "s_act_Grupo", "s_act_SubGrupo", "s_act_usuario", "s_act_CodAnterior", "s_act_Inventariable", "ccsForm"));
            $this->ClearParameters->Page = "CoAdAc_search2.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @2-06C31F70
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_act_Abreviatura->Validate() && $Validation);
        $Validation = ($this->s_act_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_act_Marca->Validate() && $Validation);
        $Validation = ($this->s_act_Modelo->Validate() && $Validation);
        $Validation = ($this->s_act_NumSerie->Validate() && $Validation);
        $Validation = ($this->s_act_Tipo->Validate() && $Validation);
        $Validation = ($this->s_act_Grupo->Validate() && $Validation);
        $Validation = ($this->s_act_SubGrupo->Validate() && $Validation);
        $Validation = ($this->s_act_usuario->Validate() && $Validation);
        $Validation = ($this->s_act_CodAnterior->Validate() && $Validation);
        $Validation = ($this->s_act_Inventariable->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->s_act_Abreviatura->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Marca->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Modelo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_NumSerie->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Tipo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Grupo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_SubGrupo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_usuario->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_CodAnterior->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_act_Inventariable->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-A8C529CD
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_act_Abreviatura->Errors->Count());
        $errors = ($errors || $this->s_act_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_act_Marca->Errors->Count());
        $errors = ($errors || $this->s_act_Modelo->Errors->Count());
        $errors = ($errors || $this->s_act_NumSerie->Errors->Count());
        $errors = ($errors || $this->s_act_Tipo->Errors->Count());
        $errors = ($errors || $this->s_act_Grupo->Errors->Count());
        $errors = ($errors || $this->s_act_SubGrupo->Errors->Count());
        $errors = ($errors || $this->s_act_usuario->Errors->Count());
        $errors = ($errors || $this->s_act_CodAnterior->Errors->Count());
        $errors = ($errors || $this->s_act_Inventariable->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-2550D195
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
        $Redirect = "CoAdAc_search2.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdAc_search2.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-66C34DDF
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_act_Tipo->Prepare();
        $this->s_act_Grupo->Prepare();
        $this->s_act_SubGrupo->Prepare();
        $this->s_act_Inventariable->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_act_Abreviatura->Errors->ToString();
            $Error .= $this->s_act_Descripcion->Errors->ToString();
            $Error .= $this->s_act_Marca->Errors->ToString();
            $Error .= $this->s_act_Modelo->Errors->ToString();
            $Error .= $this->s_act_NumSerie->Errors->ToString();
            $Error .= $this->s_act_Tipo->Errors->ToString();
            $Error .= $this->s_act_Grupo->Errors->ToString();
            $Error .= $this->s_act_SubGrupo->Errors->ToString();
            $Error .= $this->s_act_usuario->Errors->ToString();
            $Error .= $this->s_act_CodAnterior->Errors->ToString();
            $Error .= $this->s_act_Inventariable->Errors->ToString();
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

        $this->s_act_Abreviatura->Show();
        $this->s_act_Descripcion->Show();
        $this->s_act_Marca->Show();
        $this->s_act_Modelo->Show();
        $this->s_act_NumSerie->Show();
        $this->s_act_Tipo->Show();
        $this->s_act_Grupo->Show();
        $this->s_act_SubGrupo->Show();
        $this->s_act_usuario->Show();
        $this->s_act_CodAnterior->Show();
        $this->s_act_Inventariable->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End conactivosSearch Class @2-FCB6E20C

class clsGridconactivos { //conactivos class @16-1761D75F

//Variables @16-8F1193C0

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
    var $Navigator1;
    var $Sorter_act_CodAuxiliar;
    var $Sorter_act_Abreviatura;
    var $Sorter_act_Descripcion;
    var $Sorter_act_SubCategoria;
    var $Sorter_act_Marca;
    var $Sorter_act_Modelo;
    var $Sorter_act_NumSerie;
    var $Sorter_act_UniMedida;
    var $Sorter_act_Tipo;
    var $Sorter_act_Grupo;
    var $Sorter_act_SubGrupo;
    var $Sorter_act_CodAnterior;
    var $Sorter_act_usuario;
    var $Sorter_act_IvaFlag;
    var $Sorter_act_IceFlag;
    var $Sorter_act_SufijoCuenta;
    var $Sorter_act_Inventariable;
    var $Navigator;
//End Variables

//Class_Initialize Event @16-7E94D101
    function clsGridconactivos()
    {
        global $FileName;
        $this->ComponentName = "conactivos";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid conactivos";
        $this->ds = new clsconactivosDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("conactivosOrder", "");
        $this->SorterDirection = CCGetParam("conactivosDir", "");

        $this->act_CodAuxiliar = new clsControl(ccsHidden, "act_CodAuxiliar", "act_CodAuxiliar", ccsInteger, "", CCGetRequestParam("act_CodAuxiliar", ccsGet));
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->act_Abreviatura = new clsControl(ccsLabel, "act_Abreviatura", "act_Abreviatura", ccsText, "", CCGetRequestParam("act_Abreviatura", ccsGet));
        $this->act_Descripcion = new clsControl(ccsLabel, "act_Descripcion", "act_Descripcion", ccsText, "", CCGetRequestParam("act_Descripcion", ccsGet));
        $this->act_SubCategoria = new clsControl(ccsLabel, "act_SubCategoria", "act_SubCategoria", ccsText, "", CCGetRequestParam("act_SubCategoria", ccsGet));
        $this->act_Marca = new clsControl(ccsLabel, "act_Marca", "act_Marca", ccsText, "", CCGetRequestParam("act_Marca", ccsGet));
        $this->act_Modelo = new clsControl(ccsLabel, "act_Modelo", "act_Modelo", ccsText, "", CCGetRequestParam("act_Modelo", ccsGet));
        $this->act_NumSerie = new clsControl(ccsLabel, "act_NumSerie", "act_NumSerie", ccsText, "", CCGetRequestParam("act_NumSerie", ccsGet));
        $this->act_UniMedida = new clsControl(ccsLabel, "act_UniMedida", "act_UniMedida", ccsText, "", CCGetRequestParam("act_UniMedida", ccsGet));
        $this->act_Tipo = new clsControl(ccsLabel, "act_Tipo", "act_Tipo", ccsText, "", CCGetRequestParam("act_Tipo", ccsGet));
        $this->act_Grupo = new clsControl(ccsLabel, "act_Grupo", "act_Grupo", ccsText, "", CCGetRequestParam("act_Grupo", ccsGet));
        $this->act_SubGrupo = new clsControl(ccsLabel, "act_SubGrupo", "act_SubGrupo", ccsText, "", CCGetRequestParam("act_SubGrupo", ccsGet));
        $this->act_CodAnterior = new clsControl(ccsLabel, "act_CodAnterior", "act_CodAnterior", ccsText, "", CCGetRequestParam("act_CodAnterior", ccsGet));
        $this->act_usuario = new clsControl(ccsLabel, "act_usuario", "act_usuario", ccsText, "", CCGetRequestParam("act_usuario", ccsGet));
        $this->act_FecRegistro = new clsControl(ccsLabel, "act_FecRegistro", "act_FecRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("act_FecRegistro", ccsGet));
        $this->act_IvaFlag = new clsControl(ccsLabel, "act_IvaFlag", "act_IvaFlag", ccsInteger, "", CCGetRequestParam("act_IvaFlag", ccsGet));
        $this->act_IceFlag = new clsControl(ccsLabel, "act_IceFlag", "act_IceFlag", ccsInteger, "", CCGetRequestParam("act_IceFlag", ccsGet));
        $this->act_SufijoCuenta = new clsControl(ccsLabel, "act_SufijoCuenta", "act_SufijoCuenta", ccsText, "", CCGetRequestParam("act_SufijoCuenta", ccsGet));
        $this->act_Inventariable = new clsControl(ccsLabel, "act_Inventariable", "act_Inventariable", ccsInteger, "", CCGetRequestParam("act_Inventariable", ccsGet));
        $this->Alt_act_CodAuxiliar = new clsControl(ccsHidden, "Alt_act_CodAuxiliar", "Alt_act_CodAuxiliar", ccsInteger, "", CCGetRequestParam("Alt_act_CodAuxiliar", ccsGet));
        $this->Link2 = new clsControl(ccsLink, "Link2", "Link2", ccsText, "", CCGetRequestParam("Link2", ccsGet));
        $this->Alt_act_Abreviatura = new clsControl(ccsLabel, "Alt_act_Abreviatura", "Alt_act_Abreviatura", ccsText, "", CCGetRequestParam("Alt_act_Abreviatura", ccsGet));
        $this->Alt_act_Descripcion = new clsControl(ccsLabel, "Alt_act_Descripcion", "Alt_act_Descripcion", ccsText, "", CCGetRequestParam("Alt_act_Descripcion", ccsGet));
        $this->Label1 = new clsControl(ccsLabel, "Label1", "Label1", ccsText, "", CCGetRequestParam("Label1", ccsGet));
        $this->Alt_act_SubCategoria = new clsControl(ccsLabel, "Alt_act_SubCategoria", "Alt_act_SubCategoria", ccsText, "", CCGetRequestParam("Alt_act_SubCategoria", ccsGet));
        $this->Alt_act_Marca = new clsControl(ccsLabel, "Alt_act_Marca", "Alt_act_Marca", ccsText, "", CCGetRequestParam("Alt_act_Marca", ccsGet));
        $this->Alt_act_Modelo = new clsControl(ccsLabel, "Alt_act_Modelo", "Alt_act_Modelo", ccsText, "", CCGetRequestParam("Alt_act_Modelo", ccsGet));
        $this->Alt_act_NumSerie = new clsControl(ccsLabel, "Alt_act_NumSerie", "Alt_act_NumSerie", ccsText, "", CCGetRequestParam("Alt_act_NumSerie", ccsGet));
        $this->Alt_act_UniMedida = new clsControl(ccsLabel, "Alt_act_UniMedida", "Alt_act_UniMedida", ccsText, "", CCGetRequestParam("Alt_act_UniMedida", ccsGet));
        $this->Alt_act_Tipo = new clsControl(ccsLabel, "Alt_act_Tipo", "Alt_act_Tipo", ccsText, "", CCGetRequestParam("Alt_act_Tipo", ccsGet));
        $this->Alt_act_Grupo = new clsControl(ccsLabel, "Alt_act_Grupo", "Alt_act_Grupo", ccsText, "", CCGetRequestParam("Alt_act_Grupo", ccsGet));
        $this->Alt_act_SubGrupo = new clsControl(ccsLabel, "Alt_act_SubGrupo", "Alt_act_SubGrupo", ccsText, "", CCGetRequestParam("Alt_act_SubGrupo", ccsGet));
        $this->Alt_act_CodAnterior = new clsControl(ccsLabel, "Alt_act_CodAnterior", "Alt_act_CodAnterior", ccsText, "", CCGetRequestParam("Alt_act_CodAnterior", ccsGet));
        $this->Alt_act_usuario = new clsControl(ccsLabel, "Alt_act_usuario", "Alt_act_usuario", ccsText, "", CCGetRequestParam("Alt_act_usuario", ccsGet));
        $this->Alt_act_FecRegistro = new clsControl(ccsLabel, "Alt_act_FecRegistro", "Alt_act_FecRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_act_FecRegistro", ccsGet));
        $this->Alt_act_IvaFlag = new clsControl(ccsLabel, "Alt_act_IvaFlag", "Alt_act_IvaFlag", ccsInteger, "", CCGetRequestParam("Alt_act_IvaFlag", ccsGet));
        $this->Alt_act_IceFlag = new clsControl(ccsLabel, "Alt_act_IceFlag", "Alt_act_IceFlag", ccsInteger, "", CCGetRequestParam("Alt_act_IceFlag", ccsGet));
        $this->Alt_act_SufijoCuenta = new clsControl(ccsLabel, "Alt_act_SufijoCuenta", "Alt_act_SufijoCuenta", ccsText, "", CCGetRequestParam("Alt_act_SufijoCuenta", ccsGet));
        $this->Alt_act_Inventariable = new clsControl(ccsLabel, "Alt_act_Inventariable", "Alt_act_Inventariable", ccsInteger, "", CCGetRequestParam("Alt_act_Inventariable", ccsGet));
        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 5, tpCentered);
        $this->Sorter_act_CodAuxiliar = new clsSorter($this->ComponentName, "Sorter_act_CodAuxiliar", $FileName);
        $this->Sorter_act_Abreviatura = new clsSorter($this->ComponentName, "Sorter_act_Abreviatura", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_act_SubCategoria = new clsSorter($this->ComponentName, "Sorter_act_SubCategoria", $FileName);
        $this->Sorter_act_Marca = new clsSorter($this->ComponentName, "Sorter_act_Marca", $FileName);
        $this->Sorter_act_Modelo = new clsSorter($this->ComponentName, "Sorter_act_Modelo", $FileName);
        $this->Sorter_act_NumSerie = new clsSorter($this->ComponentName, "Sorter_act_NumSerie", $FileName);
        $this->Sorter_act_UniMedida = new clsSorter($this->ComponentName, "Sorter_act_UniMedida", $FileName);
        $this->Sorter_act_Tipo = new clsSorter($this->ComponentName, "Sorter_act_Tipo", $FileName);
        $this->Sorter_act_Grupo = new clsSorter($this->ComponentName, "Sorter_act_Grupo", $FileName);
        $this->Sorter_act_SubGrupo = new clsSorter($this->ComponentName, "Sorter_act_SubGrupo", $FileName);
        $this->Sorter_act_CodAnterior = new clsSorter($this->ComponentName, "Sorter_act_CodAnterior", $FileName);
        $this->Sorter_act_usuario = new clsSorter($this->ComponentName, "Sorter_act_usuario", $FileName);
        $this->Sorter_act_IvaFlag = new clsSorter($this->ComponentName, "Sorter_act_IvaFlag", $FileName);
        $this->Sorter_act_IceFlag = new clsSorter($this->ComponentName, "Sorter_act_IceFlag", $FileName);
        $this->Sorter_act_SufijoCuenta = new clsSorter($this->ComponentName, "Sorter_act_SufijoCuenta", $FileName);
        $this->Sorter_act_Inventariable = new clsSorter($this->ComponentName, "Sorter_act_Inventariable", $FileName);
        $this->ImageLink1 = new clsControl(ccsImageLink, "ImageLink1", "ImageLink1", ccsText, "", CCGetRequestParam("ImageLink1", ccsGet));
        $this->ImageLink1->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
        $this->ImageLink1->Page = "CoAdAc_mant2.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 5, tpCentered);
    }
//End Class_Initialize Event

//Initialize Method @16-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @16-FCF14BFC
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_act_Abreviatura"] = CCGetFromGet("s_act_Abreviatura", "");
        $this->ds->Parameters["urls_act_Descripcion"] = CCGetFromGet("s_act_Descripcion", "");
        $this->ds->Parameters["urls_act_Marca"] = CCGetFromGet("s_act_Marca", "");
        $this->ds->Parameters["urls_act_Modelo"] = CCGetFromGet("s_act_Modelo", "");
        $this->ds->Parameters["urls_act_NumSerie"] = CCGetFromGet("s_act_NumSerie", "");
        $this->ds->Parameters["urls_act_Tipo"] = CCGetFromGet("s_act_Tipo", "");
        $this->ds->Parameters["urls_act_Grupo"] = CCGetFromGet("s_act_Grupo", "");
        $this->ds->Parameters["urls_act_SubGrupo"] = CCGetFromGet("s_act_SubGrupo", "");
        $this->ds->Parameters["urls_act_usuario"] = CCGetFromGet("s_act_usuario", "");
        $this->ds->Parameters["urls_act_CodAnterior"] = CCGetFromGet("s_act_CodAnterior", "");
        $this->ds->Parameters["urls_act_Inventariable"] = CCGetFromGet("s_act_Inventariable", "");

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
                    $this->act_CodAuxiliar->SetValue($this->ds->act_CodAuxiliar->GetValue());
                    $this->Link1->SetValue($this->ds->Link1->GetValue());
                    $this->Link1->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "act_CodAuxiliar", $this->ds->f("act_CodAuxiliar"));
                    $this->Link1->Page = "CoAdAc_mant2.php";
                    $this->act_Abreviatura->SetValue($this->ds->act_Abreviatura->GetValue());
                    $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                    $this->act_SubCategoria->SetValue($this->ds->act_SubCategoria->GetValue());
                    $this->act_Marca->SetValue($this->ds->act_Marca->GetValue());
                    $this->act_Modelo->SetValue($this->ds->act_Modelo->GetValue());
                    $this->act_NumSerie->SetValue($this->ds->act_NumSerie->GetValue());
                    $this->act_UniMedida->SetValue($this->ds->act_UniMedida->GetValue());
                    $this->act_Tipo->SetValue($this->ds->act_Tipo->GetValue());
                    $this->act_Grupo->SetValue($this->ds->act_Grupo->GetValue());
                    $this->act_SubGrupo->SetValue($this->ds->act_SubGrupo->GetValue());
                    $this->act_CodAnterior->SetValue($this->ds->act_CodAnterior->GetValue());
                    $this->act_usuario->SetValue($this->ds->act_usuario->GetValue());
                    $this->act_FecRegistro->SetValue($this->ds->act_FecRegistro->GetValue());
                    $this->act_IvaFlag->SetValue($this->ds->act_IvaFlag->GetValue());
                    $this->act_IceFlag->SetValue($this->ds->act_IceFlag->GetValue());
                    $this->act_SufijoCuenta->SetValue($this->ds->act_SufijoCuenta->GetValue());
                    $this->act_Inventariable->SetValue($this->ds->act_Inventariable->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->act_CodAuxiliar->Show();
                    $this->Link1->Show();
                    $this->act_Abreviatura->Show();
                    $this->act_Descripcion->Show();
                    $this->act_SubCategoria->Show();
                    $this->act_Marca->Show();
                    $this->act_Modelo->Show();
                    $this->act_NumSerie->Show();
                    $this->act_UniMedida->Show();
                    $this->act_Tipo->Show();
                    $this->act_Grupo->Show();
                    $this->act_SubGrupo->Show();
                    $this->act_CodAnterior->Show();
                    $this->act_usuario->Show();
                    $this->act_FecRegistro->Show();
                    $this->act_IvaFlag->Show();
                    $this->act_IceFlag->Show();
                    $this->act_SufijoCuenta->Show();
                    $this->act_Inventariable->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_act_CodAuxiliar->SetValue($this->ds->Alt_act_CodAuxiliar->GetValue());
                    $this->Link2->SetValue($this->ds->Link2->GetValue());
                    $this->Link2->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "act_CodAuxiliar", $this->ds->f("act_CodAuxiliar"));
                    $this->Link2->Page = "CoAdAc_mant2.php";
                    $this->Alt_act_Abreviatura->SetValue($this->ds->Alt_act_Abreviatura->GetValue());
                    $this->Alt_act_Descripcion->SetValue($this->ds->Alt_act_Descripcion->GetValue());
                    $this->Label1->SetValue($this->ds->Label1->GetValue());
                    $this->Alt_act_SubCategoria->SetValue($this->ds->Alt_act_SubCategoria->GetValue());
                    $this->Alt_act_Marca->SetValue($this->ds->Alt_act_Marca->GetValue());
                    $this->Alt_act_Modelo->SetValue($this->ds->Alt_act_Modelo->GetValue());
                    $this->Alt_act_NumSerie->SetValue($this->ds->Alt_act_NumSerie->GetValue());
                    $this->Alt_act_UniMedida->SetValue($this->ds->Alt_act_UniMedida->GetValue());
                    $this->Alt_act_Tipo->SetValue($this->ds->Alt_act_Tipo->GetValue());
                    $this->Alt_act_Grupo->SetValue($this->ds->Alt_act_Grupo->GetValue());
                    $this->Alt_act_SubGrupo->SetValue($this->ds->Alt_act_SubGrupo->GetValue());
                    $this->Alt_act_CodAnterior->SetValue($this->ds->Alt_act_CodAnterior->GetValue());
                    $this->Alt_act_usuario->SetValue($this->ds->Alt_act_usuario->GetValue());
                    $this->Alt_act_FecRegistro->SetValue($this->ds->Alt_act_FecRegistro->GetValue());
                    $this->Alt_act_IvaFlag->SetValue($this->ds->Alt_act_IvaFlag->GetValue());
                    $this->Alt_act_IceFlag->SetValue($this->ds->Alt_act_IceFlag->GetValue());
                    $this->Alt_act_SufijoCuenta->SetValue($this->ds->Alt_act_SufijoCuenta->GetValue());
                    $this->Alt_act_Inventariable->SetValue($this->ds->Alt_act_Inventariable->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_act_CodAuxiliar->Show();
                    $this->Link2->Show();
                    $this->Alt_act_Abreviatura->Show();
                    $this->Alt_act_Descripcion->Show();
                    $this->Label1->Show();
                    $this->Alt_act_SubCategoria->Show();
                    $this->Alt_act_Marca->Show();
                    $this->Alt_act_Modelo->Show();
                    $this->Alt_act_NumSerie->Show();
                    $this->Alt_act_UniMedida->Show();
                    $this->Alt_act_Tipo->Show();
                    $this->Alt_act_Grupo->Show();
                    $this->Alt_act_SubGrupo->Show();
                    $this->Alt_act_CodAnterior->Show();
                    $this->Alt_act_usuario->Show();
                    $this->Alt_act_FecRegistro->Show();
                    $this->Alt_act_IvaFlag->Show();
                    $this->Alt_act_IceFlag->Show();
                    $this->Alt_act_SufijoCuenta->Show();
                    $this->Alt_act_Inventariable->Show();
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
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Navigator1->Show();
        $this->Sorter_act_CodAuxiliar->Show();
        $this->Sorter_act_Abreviatura->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_act_SubCategoria->Show();
        $this->Sorter_act_Marca->Show();
        $this->Sorter_act_Modelo->Show();
        $this->Sorter_act_NumSerie->Show();
        $this->Sorter_act_UniMedida->Show();
        $this->Sorter_act_Tipo->Show();
        $this->Sorter_act_Grupo->Show();
        $this->Sorter_act_SubGrupo->Show();
        $this->Sorter_act_CodAnterior->Show();
        $this->Sorter_act_usuario->Show();
        $this->Sorter_act_IvaFlag->Show();
        $this->Sorter_act_IceFlag->Show();
        $this->Sorter_act_SufijoCuenta->Show();
        $this->Sorter_act_Inventariable->Show();
        $this->ImageLink1->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @16-B5308654
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->act_CodAuxiliar->Errors->ToString();
        $errors .= $this->Link1->Errors->ToString();
        $errors .= $this->act_Abreviatura->Errors->ToString();
        $errors .= $this->act_Descripcion->Errors->ToString();
        $errors .= $this->act_SubCategoria->Errors->ToString();
        $errors .= $this->act_Marca->Errors->ToString();
        $errors .= $this->act_Modelo->Errors->ToString();
        $errors .= $this->act_NumSerie->Errors->ToString();
        $errors .= $this->act_UniMedida->Errors->ToString();
        $errors .= $this->act_Tipo->Errors->ToString();
        $errors .= $this->act_Grupo->Errors->ToString();
        $errors .= $this->act_SubGrupo->Errors->ToString();
        $errors .= $this->act_CodAnterior->Errors->ToString();
        $errors .= $this->act_usuario->Errors->ToString();
        $errors .= $this->act_FecRegistro->Errors->ToString();
        $errors .= $this->act_IvaFlag->Errors->ToString();
        $errors .= $this->act_IceFlag->Errors->ToString();
        $errors .= $this->act_SufijoCuenta->Errors->ToString();
        $errors .= $this->act_Inventariable->Errors->ToString();
        $errors .= $this->Alt_act_CodAuxiliar->Errors->ToString();
        $errors .= $this->Link2->Errors->ToString();
        $errors .= $this->Alt_act_Abreviatura->Errors->ToString();
        $errors .= $this->Alt_act_Descripcion->Errors->ToString();
        $errors .= $this->Label1->Errors->ToString();
        $errors .= $this->Alt_act_SubCategoria->Errors->ToString();
        $errors .= $this->Alt_act_Marca->Errors->ToString();
        $errors .= $this->Alt_act_Modelo->Errors->ToString();
        $errors .= $this->Alt_act_NumSerie->Errors->ToString();
        $errors .= $this->Alt_act_UniMedida->Errors->ToString();
        $errors .= $this->Alt_act_Tipo->Errors->ToString();
        $errors .= $this->Alt_act_Grupo->Errors->ToString();
        $errors .= $this->Alt_act_SubGrupo->Errors->ToString();
        $errors .= $this->Alt_act_CodAnterior->Errors->ToString();
        $errors .= $this->Alt_act_usuario->Errors->ToString();
        $errors .= $this->Alt_act_FecRegistro->Errors->ToString();
        $errors .= $this->Alt_act_IvaFlag->Errors->ToString();
        $errors .= $this->Alt_act_IceFlag->Errors->ToString();
        $errors .= $this->Alt_act_SufijoCuenta->Errors->ToString();
        $errors .= $this->Alt_act_Inventariable->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End conactivos Class @16-FCB6E20C

class clsconactivosDataSource extends clsDBdatos {  //conactivosDataSource Class @16-E94B6F92

//DataSource Variables @16-B37D511C
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $act_CodAuxiliar;
    var $Link1;
    var $act_Abreviatura;
    var $act_Descripcion;
    var $act_SubCategoria;
    var $act_Marca;
    var $act_Modelo;
    var $act_NumSerie;
    var $act_UniMedida;
    var $act_Tipo;
    var $act_Grupo;
    var $act_SubGrupo;
    var $act_CodAnterior;
    var $act_usuario;
    var $act_FecRegistro;
    var $act_IvaFlag;
    var $act_IceFlag;
    var $act_SufijoCuenta;
    var $act_Inventariable;
    var $Alt_act_CodAuxiliar;
    var $Link2;
    var $Alt_act_Abreviatura;
    var $Alt_act_Descripcion;
    var $Label1;
    var $Alt_act_SubCategoria;
    var $Alt_act_Marca;
    var $Alt_act_Modelo;
    var $Alt_act_NumSerie;
    var $Alt_act_UniMedida;
    var $Alt_act_Tipo;
    var $Alt_act_Grupo;
    var $Alt_act_SubGrupo;
    var $Alt_act_CodAnterior;
    var $Alt_act_usuario;
    var $Alt_act_FecRegistro;
    var $Alt_act_IvaFlag;
    var $Alt_act_IceFlag;
    var $Alt_act_SufijoCuenta;
    var $Alt_act_Inventariable;
//End DataSource Variables

//DataSourceClass_Initialize Event @16-7D651E1F
    function clsconactivosDataSource()
    {
        $this->ErrorBlock = "Grid conactivos";
        $this->Initialize();
        $this->act_CodAuxiliar = new clsField("act_CodAuxiliar", ccsInteger, "");
        $this->Link1 = new clsField("Link1", ccsText, "");
        $this->act_Abreviatura = new clsField("act_Abreviatura", ccsText, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->act_SubCategoria = new clsField("act_SubCategoria", ccsText, "");
        $this->act_Marca = new clsField("act_Marca", ccsText, "");
        $this->act_Modelo = new clsField("act_Modelo", ccsText, "");
        $this->act_NumSerie = new clsField("act_NumSerie", ccsText, "");
        $this->act_UniMedida = new clsField("act_UniMedida", ccsText, "");
        $this->act_Tipo = new clsField("act_Tipo", ccsText, "");
        $this->act_Grupo = new clsField("act_Grupo", ccsText, "");
        $this->act_SubGrupo = new clsField("act_SubGrupo", ccsText, "");
        $this->act_CodAnterior = new clsField("act_CodAnterior", ccsText, "");
        $this->act_usuario = new clsField("act_usuario", ccsText, "");
        $this->act_FecRegistro = new clsField("act_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->act_IvaFlag = new clsField("act_IvaFlag", ccsInteger, "");
        $this->act_IceFlag = new clsField("act_IceFlag", ccsInteger, "");
        $this->act_SufijoCuenta = new clsField("act_SufijoCuenta", ccsText, "");
        $this->act_Inventariable = new clsField("act_Inventariable", ccsInteger, "");
        $this->Alt_act_CodAuxiliar = new clsField("Alt_act_CodAuxiliar", ccsInteger, "");
        $this->Link2 = new clsField("Link2", ccsText, "");
        $this->Alt_act_Abreviatura = new clsField("Alt_act_Abreviatura", ccsText, "");
        $this->Alt_act_Descripcion = new clsField("Alt_act_Descripcion", ccsText, "");
        $this->Label1 = new clsField("Label1", ccsText, "");
        $this->Alt_act_SubCategoria = new clsField("Alt_act_SubCategoria", ccsText, "");
        $this->Alt_act_Marca = new clsField("Alt_act_Marca", ccsText, "");
        $this->Alt_act_Modelo = new clsField("Alt_act_Modelo", ccsText, "");
        $this->Alt_act_NumSerie = new clsField("Alt_act_NumSerie", ccsText, "");
        $this->Alt_act_UniMedida = new clsField("Alt_act_UniMedida", ccsText, "");
        $this->Alt_act_Tipo = new clsField("Alt_act_Tipo", ccsText, "");
        $this->Alt_act_Grupo = new clsField("Alt_act_Grupo", ccsText, "");
        $this->Alt_act_SubGrupo = new clsField("Alt_act_SubGrupo", ccsText, "");
        $this->Alt_act_CodAnterior = new clsField("Alt_act_CodAnterior", ccsText, "");
        $this->Alt_act_usuario = new clsField("Alt_act_usuario", ccsText, "");
        $this->Alt_act_FecRegistro = new clsField("Alt_act_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_act_IvaFlag = new clsField("Alt_act_IvaFlag", ccsInteger, "");
        $this->Alt_act_IceFlag = new clsField("Alt_act_IceFlag", ccsInteger, "");
        $this->Alt_act_SufijoCuenta = new clsField("Alt_act_SufijoCuenta", ccsText, "");
        $this->Alt_act_Inventariable = new clsField("Alt_act_Inventariable", ccsInteger, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @16-E41BF11A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_act_CodAuxiliar" => array("act_CodAuxiliar", ""), 
            "Sorter_act_Abreviatura" => array("act_Abreviatura", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", ""), 
            "Sorter_act_SubCategoria" => array("act_SubCategoria", ""), 
            "Sorter_act_Marca" => array("act_Marca", ""), 
            "Sorter_act_Modelo" => array("act_Modelo", ""), 
            "Sorter_act_NumSerie" => array("act_NumSerie", ""), 
            "Sorter_act_UniMedida" => array("act_UniMedida", ""), 
            "Sorter_act_Tipo" => array("act_Tipo", ""), 
            "Sorter_act_Grupo" => array("act_Grupo", ""), 
            "Sorter_act_SubGrupo" => array("act_SubGrupo", ""), 
            "Sorter_act_CodAnterior" => array("act_CodAnterior", ""), 
            "Sorter_act_usuario" => array("act_usuario", ""), 
            "Sorter_act_IvaFlag" => array("act_IvaFlag", ""), 
            "Sorter_act_IceFlag" => array("act_IceFlag", ""), 
            "Sorter_act_SufijoCuenta" => array("act_SufijoCuenta", ""), 
            "Sorter_act_Inventariable" => array("act_Inventariable", "")));
    }
//End SetOrder Method

//Prepare Method @16-D9FB7AC7
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_act_Abreviatura", ccsText, "", "", $this->Parameters["urls_act_Abreviatura"], "", false);
        $this->wp->AddParameter("2", "urls_act_Descripcion", ccsText, "", "", $this->Parameters["urls_act_Descripcion"], "", false);
        $this->wp->AddParameter("3", "urls_act_Marca", ccsText, "", "", $this->Parameters["urls_act_Marca"], "", false);
        $this->wp->AddParameter("4", "urls_act_Modelo", ccsText, "", "", $this->Parameters["urls_act_Modelo"], "", false);
        $this->wp->AddParameter("5", "urls_act_NumSerie", ccsText, "", "", $this->Parameters["urls_act_NumSerie"], "", false);
        $this->wp->AddParameter("6", "urls_act_Tipo", ccsText, "", "", $this->Parameters["urls_act_Tipo"], "", false);
        $this->wp->AddParameter("7", "urls_act_Grupo", ccsText, "", "", $this->Parameters["urls_act_Grupo"], "", false);
        $this->wp->AddParameter("8", "urls_act_SubGrupo", ccsText, "", "", $this->Parameters["urls_act_SubGrupo"], "", false);
        $this->wp->AddParameter("9", "urls_act_usuario", ccsText, "", "", $this->Parameters["urls_act_usuario"], "", false);
        $this->wp->AddParameter("10", "urls_act_CodAnterior", ccsText, "", "", $this->Parameters["urls_act_CodAnterior"], "", false);
        $this->wp->AddParameter("11", "urls_act_Inventariable", ccsText, "", "", $this->Parameters["urls_act_Inventariable"], "", false);
    }
//End Prepare Method

//Open Method @16-B7A8C461
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM conactivos " .
        "     LEFT JOIN concategorias ON cat_codauxiliar = act_codauxiliar " .
        "     LEFT JOIN genparametros ma ON ma.par_Clave = 'IMARCA' AND ma.par_secuencia = act_marca " .
        "     LEFT JOIN genparametros ca ON ca.par_Clave = 'CAUTI'  AND ca.par_secuencia = cat_categoria " .
        "     LEFT JOIN genparametros sc ON sc.par_Clave = 'ACTSUB' AND sc.par_secuencia = act_subcategoria " .
        "     LEFT JOIN genparametros ti ON ti.par_Clave = 'ACTCLA' AND ti.par_secuencia = act_tipo " .
        "     LEFT JOIN genparametros gr ON gr.par_Clave = 'ACTGRU' AND gr.par_secuencia = act_grupo " .
        "     LEFT JOIN genparametros sg ON sg.par_Clave = 'ACTSGR' AND sg.par_secuencia = act_subgrupo " .
        "     LEFT JOIN genunmedida      ON uni_codUnidad = act_unimedida " .
        "WHERE act_Abreviatura LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
        "AND act_Descripcion LIKE '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' " .
        "AND ma.par_descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' " .
        "AND act_Modelo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "AND act_NumSerie LIKE '%" . $this->SQLValue($this->wp->GetDBValue("5"), ccsText) . "%' " .
        "AND act_Tipo LIKE '" . $this->SQLValue($this->wp->GetDBValue("6"), ccsText) . "%' " .
        "AND act_Grupo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("7"), ccsText) . "' " .
        "AND act_SubGrupo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("8"), ccsText) . "' " .
        "AND act_usuario LIKE '%" . $this->SQLValue($this->wp->GetDBValue("9"), ccsText) . "%' " .
        "AND act_CodAnterior LIKE '%" . $this->SQLValue($this->wp->GetDBValue("10"), ccsText) . "%' " .
        "AND act_Inventariable LIKE '%" . $this->SQLValue($this->wp->GetDBValue("11"), ccsText) . "'";
        $this->SQL = "SELECT conactivos.act_CodAuxiliar, conactivos.act_SubCategoria, conactivos.act_Abreviatura, conactivos.act_Descripcion, conactivos.act_Descripcion1, " .
        "conactivos.act_Marca, conactivos.act_Modelo, conactivos.act_NumSerie, conactivos.act_UniMedida, conactivos.act_Tipo, conactivos.act_Grupo, " .
        "conactivos.act_CodAnterior, conactivos.act_usuario, conactivos.act_FecRegistro, conactivos.act_IvaFlag, conactivos.act_IceFlag, " .
        "conactivos.act_Im3Flag, conactivos.act_Im4Flag, conactivos.act_SubGrupo, conactivos.act_SufijoCuenta, conactivos.act_Inventariable, " .
        "conactivos.act_Im5Flag, conactivos.act_CodAuxiliar, conactivos.act_SubCategoria, conactivos.act_Abreviatura, conactivos.act_Descripcion, " .
        "conactivos.act_Descripcion1, conactivos.act_Marca, conactivos.act_Modelo, conactivos.act_NumSerie, conactivos.act_UniMedida, " .
        "conactivos.act_Tipo, conactivos.act_Grupo, conactivos.act_CodAnterior, conactivos.act_usuario, conactivos.act_FecRegistro, " .
        "conactivos.act_IvaFlag, conactivos.act_IceFlag, conactivos.act_Im3Flag, conactivos.act_Im4Flag, conactivos.act_SubGrupo, " .
        "conactivos.act_SufijoCuenta, conactivos.act_Inventariable, conactivos.act_Im5Flag, " .
        "ma.par_descripcion as txt_marca, " .
        "ca.par_descripcion as txt_categoria, " .
        "sc.par_descripcion as txt_subcateg, " .
        "ti.par_descripcion as txt_tipo, " .
        "gr.par_descripcion as txt_grupo, " .
        "sg.par_descripcion as txt_subgr, " .
        "uni_Descripcion " .
        "FROM conactivos " .
        "     LEFT JOIN concategorias ON cat_codauxiliar = act_codauxiliar " .
        "     LEFT JOIN genparametros ma ON ma.par_Clave = 'IMARCA' AND ma.par_secuencia = act_marca " .
        "     LEFT JOIN genparametros ca ON ca.par_Clave = 'CAUTI'  AND ca.par_secuencia = cat_categoria " .
        "     LEFT JOIN genparametros sc ON sc.par_Clave = 'ACTSUB' AND sc.par_secuencia = act_subcategoria " .
        "     LEFT JOIN genparametros ti ON ti.par_Clave = 'ACTCLA' AND ti.par_secuencia = act_tipo " .
        "     LEFT JOIN genparametros gr ON gr.par_Clave = 'ACTGRU' AND gr.par_secuencia = act_grupo " .
        "     LEFT JOIN genparametros sg ON sg.par_Clave = 'ACTSGR' AND sg.par_secuencia = act_subgrupo " .
        "     LEFT JOIN genunmedida      ON uni_codUnidad = act_unimedida " .
        "WHERE act_Abreviatura LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
        "AND act_Descripcion LIKE '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' " .
        "AND ma.par_descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' " .
        "AND act_Modelo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "AND act_NumSerie LIKE '%" . $this->SQLValue($this->wp->GetDBValue("5"), ccsText) . "%' " .
        "AND act_Tipo LIKE '" . $this->SQLValue($this->wp->GetDBValue("6"), ccsText) . "%' " .
        "AND act_Grupo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("7"), ccsText) . "' " .
        "AND act_SubGrupo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("8"), ccsText) . "' " .
        "AND act_usuario LIKE '%" . $this->SQLValue($this->wp->GetDBValue("9"), ccsText) . "%' " .
        "AND act_CodAnterior LIKE '%" . $this->SQLValue($this->wp->GetDBValue("10"), ccsText) . "%' " .
        "AND act_Inventariable LIKE '%" . $this->SQLValue($this->wp->GetDBValue("11"), ccsText) . "'";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @16-7E88A4A0
    function SetValues()
    {
        $this->act_CodAuxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->Link1->SetDBValue($this->f("act_CodAuxiliar"));
        $this->act_Abreviatura->SetDBValue($this->f("act_Abreviatura"));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->act_SubCategoria->SetDBValue($this->f("txt_subcateg"));
        $this->act_Marca->SetDBValue($this->f("txt_marca"));
        $this->act_Modelo->SetDBValue($this->f("act_Modelo"));
        $this->act_NumSerie->SetDBValue($this->f("act_NumSerie"));
        $this->act_UniMedida->SetDBValue($this->f("uni_Descripcion"));
        $this->act_Tipo->SetDBValue($this->f("txt_tipo"));
        $this->act_Grupo->SetDBValue($this->f("txt_grupo"));
        $this->act_SubGrupo->SetDBValue($this->f("txt_subgr"));
        $this->act_CodAnterior->SetDBValue($this->f("act_CodAnterior"));
        $this->act_usuario->SetDBValue($this->f("act_usuario"));
        $this->act_FecRegistro->SetDBValue(trim($this->f("act_FecRegistro")));
        $this->act_IvaFlag->SetDBValue(trim($this->f("act_IvaFlag")));
        $this->act_IceFlag->SetDBValue(trim($this->f("act_IceFlag")));
        $this->act_SufijoCuenta->SetDBValue($this->f("act_SufijoCuenta"));
        $this->act_Inventariable->SetDBValue(trim($this->f("act_Inventariable")));
        $this->Alt_act_CodAuxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->Link2->SetDBValue($this->f("act_CodAuxiliar"));
        $this->Alt_act_Abreviatura->SetDBValue($this->f("act_Abreviatura"));
        $this->Alt_act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->Label1->SetDBValue($this->f("act_Descripcion1"));
        $this->Alt_act_SubCategoria->SetDBValue($this->f("txt_subcateg"));
        $this->Alt_act_Marca->SetDBValue($this->f("txt_marca"));
        $this->Alt_act_Modelo->SetDBValue($this->f("act_Modelo"));
        $this->Alt_act_NumSerie->SetDBValue($this->f("act_NumSerie"));
        $this->Alt_act_UniMedida->SetDBValue($this->f("uni_Descripcion"));
        $this->Alt_act_Tipo->SetDBValue($this->f("txt_tipo"));
        $this->Alt_act_Grupo->SetDBValue($this->f("txt_grupo"));
        $this->Alt_act_SubGrupo->SetDBValue($this->f("txt_subgr"));
        $this->Alt_act_CodAnterior->SetDBValue($this->f("act_CodAnterior"));
        $this->Alt_act_usuario->SetDBValue($this->f("act_usuario"));
        $this->Alt_act_FecRegistro->SetDBValue(trim($this->f("act_FecRegistro")));
        $this->Alt_act_IvaFlag->SetDBValue(trim($this->f("act_IvaFlag")));
        $this->Alt_act_IceFlag->SetDBValue(trim($this->f("act_IceFlag")));
        $this->Alt_act_SufijoCuenta->SetDBValue($this->f("act_SufijoCuenta"));
        $this->Alt_act_Inventariable->SetDBValue(trim($this->f("act_Inventariable")));
    }
//End SetValues Method

} //End conactivosDataSource Class @16-FCB6E20C

//Initialize Page @1-6E6E3082
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

$FileName = "CoAdAc_search2.php";
$Redirect = "";
$TemplateFileName = "CoAdAc_search2.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-A24643C1
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$conactivosSearch = new clsRecordconactivosSearch();
$conactivos = new clsGridconactivos();
$conactivos->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

$Charset = $Charset ? $Charset : $TemplateEncoding;
if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-51DB8464
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main", $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-DAC0C899
$Cabecera->Operations();
$conactivosSearch->Operation();
//End Execute Components

//Go to destination page @1-89F0BC7F
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($conactivosSearch);
    unset($conactivos);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-E50CB4F5
$Cabecera->Show("Cabecera");
$conactivosSearch->Show();
$conactivos->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$CSGTA7H10L4B7N = array("<center><font face=","\"Arial\"><small>","Ge&#110;e&#114;","ate&#100; <!-- CC","S -->wit&#104; <!","-- SCC -->&#67;","o&#100;&#101;C&#","104;a&#114;ge <!-- ","SCC -->&#83;&#116;&#","117;&#100;i&#111",";.</small></font></c","enter>");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($CSGTA7H10L4B7N,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($CSGTA7H10L4B7N,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($CSGTA7H10L4B7N,"");
}
echo $main_block;
//End Show Page

//Unload Page @1-159627EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($conactivosSearch);
unset($conactivos);
unset($Tpl);
//End Unload Page


?>
