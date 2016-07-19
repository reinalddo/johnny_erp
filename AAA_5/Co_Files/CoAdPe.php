<?php
/*
*    Seleccion y Mantenimiento de Periodos: Rejilla editable de Periodos definidos para las diferentes aplicaciones.
*    @abstract   Basado en la aplicacion que se seleccione,en una listbox, presenta una
*		 lista de los periodos aplicables. en esta rejilla se puede modificar, agregar
*		 o eliminar periodos.
*		 Antes de habilitar las funciones descritas, se valida los atributos del usuario.
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdPe
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @53-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordperiodos_qry { //periodos_qry Class @3-75F55626

//Variables @3-CB19EB75

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

//Class_Initialize Event @3-5209E7A5
    function clsRecordperiodos_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record periodos_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "periodos_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->per_Aplicacion = new clsControl(ccsListBox, "per_Aplicacion", "per_Aplicacion", ccsText, "", CCGetRequestParam("per_Aplicacion", $Method));
            $this->per_Aplicacion->DSType = dsListOfValues;
            $this->per_Aplicacion->Values = array(array("CO", "PERIODOS CONTABLES"), array("IN", "PERIODOS DE INVENTARIO"), array("LI", "PERIODOS DE LIQUIDACIONES"),array("CC", "CUENTAS POR COBRAR"));
        }
    }
//End Class_Initialize Event

//Validate Method @3-31AE7EA8
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->per_Aplicacion->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-FF75F4B3
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->per_Aplicacion->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-16DBAA7F
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        $Redirect = "CoAdPe.php";
    }
//End Operation Method

//Show Method @3-4CA51895
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->per_Aplicacion->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->per_Aplicacion->Errors->ToString();
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

        $this->per_Aplicacion->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End periodos_qry Class @3-FCB6E20C

Class clsEditableGridperiodos { //periodos Class @2-1A05F3DE

//Variables @2-B1349289

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormParameters;
    var $FormState;
    var $FormEnctype;
    var $CachedColumns;
    var $TotalRows;
    var $UpdatedRows;
    var $EmptyRows;
    var $Visible;
    var $EditableGridset;
    var $RowsErrors;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_per_Aplicacion;
    var $Sorter_per_NumPeriodo;
    var $Sorter_per_FecInicial;
    var $Sorter_per_FecFinal;
    var $Sorter_per_Ano;
    var $Sorter_per_Mes;
    var $Sorter_per_Semana;
    var $Sorter_per_Estado;
    var $Sorter_per_Bandera;
    var $Sorter_per_PerContable;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-E6CE46C9
    function clsEditableGridperiodos()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid periodos/Error";
        $this->ComponentName = "periodos";
        $this->CachedColumns["per_Aplicacion"][0] = "per_Aplicacion";
        $this->CachedColumns["per_NumPeriodo"][0] = "per_NumPeriodo";
        $this->ds = new clsperiodosDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 15)
            $this->PageSize = 15;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 3;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if(!$this->Visible) return;

        $CCSForm = CCGetFromGet("ccsForm", "");
        $this->FormEnctype = "application/x-www-form-urlencoded";
        $this->FormSubmitted = ($CCSForm == $this->ComponentName);
        if($this->FormSubmitted) {
            $this->FormState = CCGetFromPost("FormState", "");
            $this->SetFormState($this->FormState);
        } else {
            $this->FormState = "";
        }
        $Method = $this->FormSubmitted ? ccsPost : ccsGet;

        $this->SorterName = CCGetParam("periodosOrder", "");
        $this->SorterDirection = CCGetParam("periodosDir", "");

        $this->Sorter_per_Aplicacion = new clsSorter($this->ComponentName, "Sorter_per_Aplicacion", $FileName);
        $this->Sorter_per_NumPeriodo = new clsSorter($this->ComponentName, "Sorter_per_NumPeriodo", $FileName);
        $this->Sorter_per_FecInicial = new clsSorter($this->ComponentName, "Sorter_per_FecInicial", $FileName);
        $this->Sorter_per_FecFinal = new clsSorter($this->ComponentName, "Sorter_per_FecFinal", $FileName);
        $this->Sorter_per_Ano = new clsSorter($this->ComponentName, "Sorter_per_Ano", $FileName);
        $this->Sorter_per_Mes = new clsSorter($this->ComponentName, "Sorter_per_Mes", $FileName);
        $this->Sorter_per_Semana = new clsSorter($this->ComponentName, "Sorter_per_Semana", $FileName);
        $this->Sorter_per_Estado = new clsSorter($this->ComponentName, "Sorter_per_Estado", $FileName);
        $this->Sorter_per_Bandera = new clsSorter($this->ComponentName, "Sorter_per_Bandera", $FileName);
        $this->Sorter_per_PerContable = new clsSorter($this->ComponentName, "Sorter_per_PerContable", $FileName);
        $this->per_Aplicacion = new clsControl(ccsTextBox, "per_Aplicacion", "Aplicacion", ccsText, "");
        $this->per_Aplicacion->Required = true;
        $this->per_NumPeriodo = new clsControl(ccsTextBox, "per_NumPeriodo", "Num Periodo", ccsInteger, "");
        $this->per_NumPeriodo->Required = true;
        $this->per_FecInicial = new clsControl(ccsTextBox, "per_FecInicial", "Fec Inicial", ccsDate, Array("dd", "/", "mm", "/", "yy"));
        $this->per_FecInicial->Required = true;
        $this->DatePicker_per_FecInicial = new clsDatePicker("DatePicker_per_FecInicial", "periodos", "per_FecInicial");
        $this->per_FecFinal = new clsControl(ccsTextBox, "per_FecFinal", "Fec Final", ccsDate, Array("dd", "/", "mm", "/", "yy"));
        $this->per_FecFinal->Required = true;
        $this->DatePicker_per_FecFinal = new clsDatePicker("DatePicker_per_FecFinal", "periodos", "per_FecFinal");
        $this->per_Ano = new clsControl(ccsTextBox, "per_Ano", "Ano", ccsInteger, "");
        $this->per_Ano->Required = true;
        $this->per_Mes = new clsControl(ccsTextBox, "per_Mes", "Mes", ccsInteger, "");
        $this->per_Mes->Required = true;
        $this->per_Semana = new clsControl(ccsTextBox, "per_Semana", "Semana", ccsInteger, "");
        $this->per_Semana->Required = true;
        $this->per_Estado = new clsControl(ccsListBox, "per_Estado", "Estado", ccsInteger, "");
        $this->per_Estado->DSType = dsTable;
        list($this->per_Estado->BoundColumn, $this->per_Estado->TextColumn, $this->per_Estado->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
        $this->per_Estado->ds = new clsDBdatos();
        $this->per_Estado->ds->SQL = "SELECT *  " .
"FROM genparametros";
        $this->per_Estado->ds->Parameters["expr54"] = 'LGESTA';
        $this->per_Estado->ds->wp = new clsSQLParameters();
        $this->per_Estado->ds->wp->AddParameter("1", "expr54", ccsText, "", "", $this->per_Estado->ds->Parameters["expr54"], "", false);
        $this->per_Estado->ds->wp->Criterion[1] = $this->per_Estado->ds->wp->Operation(opEqual, "par_Clave", $this->per_Estado->ds->wp->GetDBValue("1"), $this->per_Estado->ds->ToSQL($this->per_Estado->ds->wp->GetDBValue("1"), ccsText),false);
        $this->per_Estado->ds->Where = $this->per_Estado->ds->wp->Criterion[1];
        $this->per_Estado->Required = true;
        $this->per_Bandera = new clsControl(ccsTextBox, "per_Bandera", "Per Bandera", ccsInteger, "");
        $this->per_Bandera->Required = true;
        $this->per_PerContable = new clsControl(ccsTextBox, "per_PerContable", "Per Per Contable", ccsInteger, "");
        $this->per_PerContable->Required = true;
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 15, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @2-6B176485
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlper_Aplicacion"] = CCGetFromGet("per_Aplicacion", "");
    }
//End Initialize Method

//GetFormParameters Method @2-FF2FD1A4
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["per_Aplicacion"][$RowNumber] = CCGetFromPost("per_Aplicacion_" . $RowNumber);
            $this->FormParameters["per_NumPeriodo"][$RowNumber] = CCGetFromPost("per_NumPeriodo_" . $RowNumber);
            $this->FormParameters["per_FecInicial"][$RowNumber] = CCGetFromPost("per_FecInicial_" . $RowNumber);
            $this->FormParameters["per_FecFinal"][$RowNumber] = CCGetFromPost("per_FecFinal_" . $RowNumber);
            $this->FormParameters["per_Ano"][$RowNumber] = CCGetFromPost("per_Ano_" . $RowNumber);
            $this->FormParameters["per_Mes"][$RowNumber] = CCGetFromPost("per_Mes_" . $RowNumber);
            $this->FormParameters["per_Semana"][$RowNumber] = CCGetFromPost("per_Semana_" . $RowNumber);
            $this->FormParameters["per_Estado"][$RowNumber] = CCGetFromPost("per_Estado_" . $RowNumber);
            $this->FormParameters["per_Bandera"][$RowNumber] = CCGetFromPost("per_Bandera_" . $RowNumber);
            $this->FormParameters["per_PerContable"][$RowNumber] = CCGetFromPost("per_PerContable_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-400D658C
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["per_Aplicacion"] = $this->CachedColumns["per_Aplicacion"][$RowNumber];
            $this->ds->CachedColumns["per_NumPeriodo"] = $this->CachedColumns["per_NumPeriodo"][$RowNumber];
            $this->per_Aplicacion->SetText($this->FormParameters["per_Aplicacion"][$RowNumber], $RowNumber);
            $this->per_NumPeriodo->SetText($this->FormParameters["per_NumPeriodo"][$RowNumber], $RowNumber);
            $this->per_FecInicial->SetText($this->FormParameters["per_FecInicial"][$RowNumber], $RowNumber);
            $this->per_FecFinal->SetText($this->FormParameters["per_FecFinal"][$RowNumber], $RowNumber);
            $this->per_Ano->SetText($this->FormParameters["per_Ano"][$RowNumber], $RowNumber);
            $this->per_Mes->SetText($this->FormParameters["per_Mes"][$RowNumber], $RowNumber);
            $this->per_Semana->SetText($this->FormParameters["per_Semana"][$RowNumber], $RowNumber);
            $this->per_Estado->SetText($this->FormParameters["per_Estado"][$RowNumber], $RowNumber);
            $this->per_Bandera->SetText($this->FormParameters["per_Bandera"][$RowNumber], $RowNumber);
            $this->per_PerContable->SetText($this->FormParameters["per_PerContable"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if(!$this->CheckBox_Delete->Value)
                    $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
            else if($this->CheckInsert($RowNumber))
            {
                $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
        }
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//ValidateRow Method @2-08582EA5
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->per_Aplicacion->Validate() && $Validation);
        $Validation = ($this->per_NumPeriodo->Validate() && $Validation);
        $Validation = ($this->per_FecInicial->Validate() && $Validation);
        $Validation = ($this->per_FecFinal->Validate() && $Validation);
        $Validation = ($this->per_Ano->Validate() && $Validation);
        $Validation = ($this->per_Mes->Validate() && $Validation);
        $Validation = ($this->per_Semana->Validate() && $Validation);
        $Validation = ($this->per_Estado->Validate() && $Validation);
        $Validation = ($this->per_Bandera->Validate() && $Validation);
        $Validation = ($this->per_PerContable->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->per_Aplicacion->Errors->ToString();
            $errors .= $this->per_NumPeriodo->Errors->ToString();
            $errors .= $this->per_FecInicial->Errors->ToString();
            $errors .= $this->per_FecFinal->Errors->ToString();
            $errors .= $this->per_Ano->Errors->ToString();
            $errors .= $this->per_Mes->Errors->ToString();
            $errors .= $this->per_Semana->Errors->ToString();
            $errors .= $this->per_Estado->Errors->ToString();
            $errors .= $this->per_Bandera->Errors->ToString();
            $errors .= $this->per_PerContable->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->per_Aplicacion->Errors->Clear();
            $this->per_NumPeriodo->Errors->Clear();
            $this->per_FecInicial->Errors->Clear();
            $this->per_FecFinal->Errors->Clear();
            $this->per_Ano->Errors->Clear();
            $this->per_Mes->Errors->Clear();
            $this->per_Semana->Errors->Clear();
            $this->per_Estado->Errors->Clear();
            $this->per_Bandera->Errors->Clear();
            $this->per_PerContable->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @2-C611AEB9
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["per_Aplicacion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_NumPeriodo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_FecInicial"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_FecFinal"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_Ano"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_Mes"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_Semana"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_Estado"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_Bandera"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_PerContable"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @2-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-7B861278
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        if(!$this->FormSubmitted)
            return;

        $this->GetFormParameters();
        $this->PressedButton = "Button_Submit";
        if(strlen(CCGetParam("Button_Submit", ""))) {
            $this->PressedButton = "Button_Submit";
        } else if(strlen(CCGetParam("Cancel", ""))) {
            $this->PressedButton = "Cancel";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @2-6330676B
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["per_Aplicacion"] = $this->CachedColumns["per_Aplicacion"][$RowNumber];
            $this->ds->CachedColumns["per_NumPeriodo"] = $this->CachedColumns["per_NumPeriodo"][$RowNumber];
            $this->per_Aplicacion->SetText($this->FormParameters["per_Aplicacion"][$RowNumber], $RowNumber);
            $this->per_NumPeriodo->SetText($this->FormParameters["per_NumPeriodo"][$RowNumber], $RowNumber);
            $this->per_FecInicial->SetText($this->FormParameters["per_FecInicial"][$RowNumber], $RowNumber);
            $this->per_FecFinal->SetText($this->FormParameters["per_FecFinal"][$RowNumber], $RowNumber);
            $this->per_Ano->SetText($this->FormParameters["per_Ano"][$RowNumber], $RowNumber);
            $this->per_Mes->SetText($this->FormParameters["per_Mes"][$RowNumber], $RowNumber);
            $this->per_Semana->SetText($this->FormParameters["per_Semana"][$RowNumber], $RowNumber);
            $this->per_Estado->SetText($this->FormParameters["per_Estado"][$RowNumber], $RowNumber);
            $this->per_Bandera->SetText($this->FormParameters["per_Bandera"][$RowNumber], $RowNumber);
            $this->per_PerContable->SetText($this->FormParameters["per_PerContable"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) $this->DeleteRow($RowNumber);
                } else if($this->UpdateAllowed) {
                    $this->UpdateRow($RowNumber);
                }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $this->InsertRow($RowNumber);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0);
    }
//End UpdateGrid Method

//InsertRow Method @2-CA5C3624
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->per_NumPeriodo->SetValue($this->per_NumPeriodo->GetValue());
        $this->ds->per_FecInicial->SetValue($this->per_FecInicial->GetValue());
        $this->ds->per_FecFinal->SetValue($this->per_FecFinal->GetValue());
        $this->ds->per_Ano->SetValue($this->per_Ano->GetValue());
        $this->ds->per_Mes->SetValue($this->per_Mes->GetValue());
        $this->ds->per_Semana->SetValue($this->per_Semana->GetValue());
        $this->ds->per_Estado->SetValue($this->per_Estado->GetValue());
        $this->ds->per_Bandera->SetValue($this->per_Bandera->GetValue());
        $this->ds->per_PerContable->SetValue($this->per_PerContable->GetValue());
        $this->ds->Insert();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @2-DDF714C0
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->per_FecInicial->SetValue($this->per_FecInicial->GetValue());
        $this->ds->per_FecFinal->SetValue($this->per_FecFinal->GetValue());
        $this->ds->per_Ano->SetValue($this->per_Ano->GetValue());
        $this->ds->per_Mes->SetValue($this->per_Mes->GetValue());
        $this->ds->per_Semana->SetValue($this->per_Semana->GetValue());
        $this->ds->per_Estado->SetValue($this->per_Estado->GetValue());
        $this->ds->per_Bandera->SetValue($this->per_Bandera->GetValue());
        $this->ds->per_PerContable->SetValue($this->per_PerContable->GetValue());
        $this->ds->Update();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . $this->ComponentName . " / Update Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//DeleteRow Method @2-0C9DDC34
    function DeleteRow($RowNumber)
    {
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . ComponentName . " / Delete Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @2-4D5C5D16
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var periodosElements;\n";
        $script .= "var periodosEmptyRows = 3;\n";
        $script .= "var " . $this->ComponentName . "per_AplicacionID = 0;\n";
        $script .= "var " . $this->ComponentName . "per_NumPeriodoID = 1;\n";
        $script .= "var " . $this->ComponentName . "per_FecInicialID = 2;\n";
        $script .= "var " . $this->ComponentName . "per_FecFinalID = 3;\n";
        $script .= "var " . $this->ComponentName . "per_AnoID = 4;\n";
        $script .= "var " . $this->ComponentName . "per_MesID = 5;\n";
        $script .= "var " . $this->ComponentName . "per_SemanaID = 6;\n";
        $script .= "var " . $this->ComponentName . "per_EstadoID = 7;\n";
        $script .= "var " . $this->ComponentName . "per_BanderaID = 8;\n";
        $script .= "var " . $this->ComponentName . "per_PerContableID = 9;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 10;\n";
        $script .= "\nfunction initperiodosElements() {\n";
        $script .= "\tvar ED = document.forms[\"periodos\"];\n";
        $script .= "\tperiodosElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.per_Aplicacion_" . $i . ", " . "ED.per_NumPeriodo_" . $i . ", " . "ED.per_FecInicial_" . $i . ", " . "ED.per_FecFinal_" . $i . ", " . "ED.per_Ano_" . $i . ", " . "ED.per_Mes_" . $i . ", " . "ED.per_Semana_" . $i . ", " . "ED.per_Estado_" . $i . ", " . "ED.per_Bandera_" . $i . ", " . "ED.per_PerContable_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-37619AAD
    function SetFormState($FormState)
    {
        if(strlen($FormState)) {
            $FormState = str_replace("\\\\", "\\" . ord("\\"), $FormState);
            $FormState = str_replace("\\;", "\\" . ord(";"), $FormState);
            $pieces = explode(";", $FormState);
            $this->UpdatedRows = $pieces[0];
            $this->EmptyRows   = $pieces[1];
            $this->TotalRows = $this->UpdatedRows + $this->EmptyRows;
            $RowNumber = 0;
            for($i = 2; $i < sizeof($pieces); $i = $i + 2)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["per_Aplicacion"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["per_NumPeriodo"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["per_Aplicacion"][$RowNumber] = "";
                $this->CachedColumns["per_NumPeriodo"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-88D034B3
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["per_Aplicacion"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["per_NumPeriodo"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-61E45DED
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->per_Estado->Prepare();

        $this->ds->open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) { return; }

        $this->Button_Submit->Visible = ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
//        $EmptyRowsLeft = $this->EmptyRows;
        // fah_mod: para hacer que se agregue records solo al final
            if ($this->ds->AbsolutePage < $this->ds->PageCount()) $EmptyRowsLeft = 0;
            else {
                if ($this->ds->RecordsCount > $this->PageSize) $resto = fmod($this->ds->RecordsCount, $this->PageSize);
                else $resto = $this->ds->RecordsCount;
                $EmptyRowsLeft = $this->PageSize - $resto;
            }
            if ($EmptyRowsLeft <= 0 && $this->ds->RecordsCount == ($this->PageSize * $this->ds->AbsolutePage)) $EmptyRowsLeft =1;
        // end fah mod
        $is_next_record = false;
        if($this->Errors->Count() == 0)
        {
            $is_next_record = ($this->ds->next_record() && $RowNumber < $this->PageSize);
            if($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed))
            {
                do
                {
                    $RowNumber++;
                    if($is_next_record) {
                        $NonEmptyRows++;
                        $this->ds->SetValues();
                    } else {
                    }
                    if(!$is_next_record || !$this->DeleteAllowed)
                        $this->CheckBox_Delete->Visible = false;
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["per_Aplicacion"][$RowNumber] = $this->ds->CachedColumns["per_Aplicacion"];
                        $this->CachedColumns["per_NumPeriodo"][$RowNumber] = $this->ds->CachedColumns["per_NumPeriodo"];
                        $this->per_Aplicacion->SetValue($this->ds->per_Aplicacion->GetValue());
                        $this->per_NumPeriodo->SetValue($this->ds->per_NumPeriodo->GetValue());
                        $this->per_FecInicial->SetValue($this->ds->per_FecInicial->GetValue());
                        $this->per_FecFinal->SetValue($this->ds->per_FecFinal->GetValue());
                        $this->per_Ano->SetValue($this->ds->per_Ano->GetValue());
                        $this->per_Mes->SetValue($this->ds->per_Mes->GetValue());
                        $this->per_Semana->SetValue($this->ds->per_Semana->GetValue());
                        $this->per_Estado->SetValue($this->ds->per_Estado->GetValue());
                        $this->per_Bandera->SetValue($this->ds->per_Bandera->GetValue());
                        $this->per_PerContable->SetValue($this->ds->per_PerContable->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["per_Aplicacion"][$RowNumber] = "";
                        $this->CachedColumns["per_NumPeriodo"][$RowNumber] = "";
                        $this->per_Aplicacion->SetText("");
                        $this->per_NumPeriodo->SetText("");
                        $this->per_FecInicial->SetText("");
                        $this->per_FecFinal->SetText("");
                        $this->per_Ano->SetText("");
                        $this->per_Mes->SetText("");
                        $this->per_Semana->SetText("");
                        $this->per_Estado->SetText("");
                        $this->per_Bandera->SetText("");
                        $this->per_PerContable->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->per_Aplicacion->SetText($this->FormParameters["per_Aplicacion"][$RowNumber], $RowNumber);
                        $this->per_NumPeriodo->SetText($this->FormParameters["per_NumPeriodo"][$RowNumber], $RowNumber);
                        $this->per_FecInicial->SetText($this->FormParameters["per_FecInicial"][$RowNumber], $RowNumber);
                        $this->per_FecFinal->SetText($this->FormParameters["per_FecFinal"][$RowNumber], $RowNumber);
                        $this->per_Ano->SetText($this->FormParameters["per_Ano"][$RowNumber], $RowNumber);
                        $this->per_Mes->SetText($this->FormParameters["per_Mes"][$RowNumber], $RowNumber);
                        $this->per_Semana->SetText($this->FormParameters["per_Semana"][$RowNumber], $RowNumber);
                        $this->per_Estado->SetText($this->FormParameters["per_Estado"][$RowNumber], $RowNumber);
                        $this->per_Bandera->SetText($this->FormParameters["per_Bandera"][$RowNumber], $RowNumber);
                        $this->per_PerContable->SetText($this->FormParameters["per_PerContable"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->per_Aplicacion->Show($RowNumber);
                    $this->per_NumPeriodo->Show($RowNumber);
                    $this->per_FecInicial->Show($RowNumber);
                    $this->DatePicker_per_FecInicial->Show($RowNumber);
                    $this->per_FecFinal->Show($RowNumber);
                    $this->DatePicker_per_FecFinal->Show($RowNumber);
                    $this->per_Ano->Show($RowNumber);
                    $this->per_Mes->Show($RowNumber);
                    $this->per_Semana->Show($RowNumber);
                    $this->per_Estado->Show($RowNumber);
                    $this->per_Bandera->Show($RowNumber);
                    $this->per_PerContable->Show($RowNumber);
                    $this->CheckBox_Delete->Show($RowNumber);
                    if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                        $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                        $Tpl->parse("RowError", false);
                    } else {
                        $Tpl->setblockvar("RowError", "");
                    }
                    $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                    $Tpl->parse();
                    if($is_next_record) $is_next_record = ($this->ds->next_record() && $RowNumber < $this->PageSize);
                    else $EmptyRowsLeft--;
                } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
            } else {
                $Tpl->block_path = $EditableGridPath;
                $Tpl->parse("NoRecords", false);
            }
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_per_Aplicacion->Show();
        $this->Sorter_per_NumPeriodo->Show();
        $this->Sorter_per_FecInicial->Show();
        $this->Sorter_per_FecFinal->Show();
        $this->Sorter_per_Ano->Show();
        $this->Sorter_per_Mes->Show();
        $this->Sorter_per_Semana->Show();
        $this->Sorter_per_Estado->Show();
        $this->Sorter_per_Bandera->Show();
        $this->Sorter_per_PerContable->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();

        if($this->CheckErrors()) {
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $Tpl->SetVar("HTMLFormProperties", "method=\"POST\" action=\"" . $this->HTMLFormAction . "\" name=\"" . $this->ComponentName . "\"");
        $Tpl->SetVar("FormState", htmlspecialchars($this->GetFormState($NonEmptyRows)));
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End periodos Class @2-FCB6E20C

class clsperiodosDataSource extends clsDBdatos {  //periodosDataSource Class @2-23E1245E

//DataSource Variables @2-FE1B8869
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $per_Aplicacion;
    var $per_NumPeriodo;
    var $per_FecInicial;
    var $per_FecFinal;
    var $per_Ano;
    var $per_Mes;
    var $per_Semana;
    var $per_Estado;
    var $per_Bandera;
    var $per_PerContable;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @2-E80CFF2C
    function clsperiodosDataSource()
    {
        $this->ErrorBlock = "EditableGrid periodos/Error";
        $this->Initialize();
        $this->per_Aplicacion = new clsField("per_Aplicacion", ccsText, "");
        $this->per_NumPeriodo = new clsField("per_NumPeriodo", ccsInteger, "");
        $this->per_FecInicial = new clsField("per_FecInicial", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->per_FecFinal = new clsField("per_FecFinal", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->per_Ano = new clsField("per_Ano", ccsInteger, "");
        $this->per_Mes = new clsField("per_Mes", ccsInteger, "");
        $this->per_Semana = new clsField("per_Semana", ccsInteger, "");
        $this->per_Estado = new clsField("per_Estado", ccsInteger, "");
        $this->per_Bandera = new clsField("per_Bandera", ccsInteger, "");
        $this->per_PerContable = new clsField("per_PerContable", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @2-CF09148D
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_per_Aplicacion" => array("per_Aplicacion", ""), 
            "Sorter_per_NumPeriodo" => array("per_NumPeriodo", ""), 
            "Sorter_per_FecInicial" => array("per_FecInicial", ""), 
            "Sorter_per_FecFinal" => array("per_FecFinal", ""), 
            "Sorter_per_Ano" => array("per_Ano", ""),
            "Sorter_per_Mes" => array("per_Mes", ""), 
            "Sorter_per_Semana" => array("per_Semana", ""), 
            "Sorter_per_Estado" => array("per_Estado", ""), 
            "Sorter_per_Bandera" => array("per_Bandera", ""), 
            "Sorter_per_PerContable" => array("per_PerContable", "")));
    }
//End SetOrder Method

//Prepare Method @2-60A77339
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlper_Aplicacion", ccsText, "", "", $this->Parameters["urlper_Aplicacion"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "per_Aplicacion", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-922F3A1C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM conperiodos";
        $this->SQL = "SELECT *  " .
        "FROM conperiodos";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-BFBAD96F
    function SetValues()
    {
        $this->CachedColumns["per_Aplicacion"] = $this->f("per_Aplicacion");
        $this->CachedColumns["per_NumPeriodo"] = $this->f("per_NumPeriodo");
        $this->per_Aplicacion->SetDBValue($this->f("per_Aplicacion"));
        $this->per_NumPeriodo->SetDBValue(trim($this->f("per_NumPeriodo")));
        $this->per_FecInicial->SetDBValue(trim($this->f("per_FecInicial")));
        $this->per_FecFinal->SetDBValue(trim($this->f("per_FecFinal")));
        $this->per_Ano->SetDBValue(trim($this->f("per_Ano")));
        $this->per_Mes->SetDBValue(trim($this->f("per_Mes")));
        $this->per_Semana->SetDBValue(trim($this->f("per_Semana")));
        $this->per_Estado->SetDBValue(trim($this->f("per_Estado")));
        $this->per_Bandera->SetDBValue(trim($this->f("per_Bandera")));
        $this->per_PerContable->SetDBValue(trim($this->f("per_PerContable")));
    }
//End SetValues Method

//Insert Method @2-0422BB14
    function Insert()
    {
        $this->cp["per_Aplicacion"] = new clsSQLParameter("urlper_Aplicacion", ccsText, "", "", CCGetFromGet("per_Aplicacion", ""), "", false, $this->ErrorBlock);
        $this->cp["per_NumPeriodo"] = new clsSQLParameter("ctrlper_NumPeriodo", ccsInteger, "", "", $this->per_NumPeriodo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_FecInicial"] = new clsSQLParameter("ctrlper_FecInicial", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->per_FecInicial->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_FecFinal"] = new clsSQLParameter("ctrlper_FecFinal", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->per_FecFinal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Ano"] = new clsSQLParameter("ctrlper_Ano", ccsInteger, "", "", $this->per_Ano->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["per_Mes"] = new clsSQLParameter("ctrlper_Mes", ccsInteger, "", "", $this->per_Mes->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["per_Semana"] = new clsSQLParameter("ctrlper_Semana", ccsInteger, "", "", $this->per_Semana->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["per_Estado"] = new clsSQLParameter("ctrlper_Estado", ccsInteger, "", "", $this->per_Estado->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["per_Bandera"] = new clsSQLParameter("ctrlper_Bandera", ccsInteger, "", "", $this->per_Bandera->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["per_PerContable"] = new clsSQLParameter("ctrlper_PerContable", ccsInteger, "", "", $this->per_PerContable->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO conperiodos ("
             . "per_Aplicacion, "
             . "per_NumPeriodo, "
             . "per_FecInicial, "
             . "per_FecFinal, "
             . "`per_Ano`, "
             . "per_Mes, "
             . "per_Semana, "
             . "per_Estado, "
             . "per_Bandera, "
             . "per_PerContable"
             . ") VALUES ("
             . $this->ToSQL($this->cp["per_Aplicacion"]->GetDBValue(), $this->cp["per_Aplicacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_NumPeriodo"]->GetDBValue(), $this->cp["per_NumPeriodo"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_FecInicial"]->GetDBValue(), $this->cp["per_FecInicial"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_FecFinal"]->GetDBValue(), $this->cp["per_FecFinal"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Ano"]->GetDBValue(), $this->cp["per_Ano"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Mes"]->GetDBValue(), $this->cp["per_Mes"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Semana"]->GetDBValue(), $this->cp["per_Semana"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Estado"]->GetDBValue(), $this->cp["per_Estado"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Bandera"]->GetDBValue(), $this->cp["per_Bandera"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_PerContable"]->GetDBValue(), $this->cp["per_PerContable"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-C924FCE0
    function Update()
    {
        $this->cp["per_FecInicial"] = new clsSQLParameter("ctrlper_FecInicial", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->per_FecInicial->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_FecFinal"] = new clsSQLParameter("ctrlper_FecFinal", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->per_FecFinal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Ano"] = new clsSQLParameter("ctrlper_Ano", ccsInteger, "", "", $this->per_Ano->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Mes"] = new clsSQLParameter("ctrlper_Mes", ccsInteger, "", "", $this->per_Mes->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Semana"] = new clsSQLParameter("ctrlper_Semana", ccsInteger, "", "", $this->per_Semana->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Estado"] = new clsSQLParameter("ctrlper_Estado", ccsInteger, "", "", $this->per_Estado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Bandera"] = new clsSQLParameter("ctrlper_Bandera", ccsInteger, "", "", $this->per_Bandera->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_PerContable"] = new clsSQLParameter("ctrlper_PerContable", ccsInteger, "", "", $this->per_PerContable->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dsper_Aplicacion", ccsText, "", "", $this->CachedColumns["per_Aplicacion"], "", false);
        $wp->AddParameter("2", "dsper_NumPeriodo", ccsInteger, "", "", $this->CachedColumns["per_NumPeriodo"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "per_Aplicacion", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "per_NumPeriodo", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->Criterion[2];
        $this->SQL = "UPDATE conperiodos SET "
             . "per_FecInicial=" . $this->ToSQL($this->cp["per_FecInicial"]->GetDBValue(), $this->cp["per_FecInicial"]->DataType) . ", "
             . "per_FecFinal=" . $this->ToSQL($this->cp["per_FecFinal"]->GetDBValue(), $this->cp["per_FecFinal"]->DataType) . ", "
             . "`per_Ano`=" . $this->ToSQL($this->cp["per_Ano"]->GetDBValue(), $this->cp["per_Ano"]->DataType) . ", "
             . "per_Mes=" . $this->ToSQL($this->cp["per_Mes"]->GetDBValue(), $this->cp["per_Mes"]->DataType) . ", "
             . "per_Semana=" . $this->ToSQL($this->cp["per_Semana"]->GetDBValue(), $this->cp["per_Semana"]->DataType) . ", "
             . "per_Estado=" . $this->ToSQL($this->cp["per_Estado"]->GetDBValue(), $this->cp["per_Estado"]->DataType) . ", "
             . "per_Bandera=" . $this->ToSQL($this->cp["per_Bandera"]->GetDBValue(), $this->cp["per_Bandera"]->DataType) . ", "
             . "per_PerContable=" . $this->ToSQL($this->cp["per_PerContable"]->GetDBValue(), $this->cp["per_PerContable"]->DataType);
        $Where = " per_Aplicacion ='" . CCGetParam('per_Aplicacion', 'xx') . "' AND " . $Where;
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
//echo $this->SQL;
//die();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-11FC3BC3
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dsper_Aplicacion", ccsText, "", "", $this->CachedColumns["per_Aplicacion"], "", false);
        $wp->AddParameter("2", "dsper_NumPeriodo", ccsInteger, "", "", $this->CachedColumns["per_NumPeriodo"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "per_Aplicacion", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "per_NumPeriodo", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->Criterion[2];
        $this->SQL = "DELETE FROM conperiodos";
        $Where = " per_Aplicacion ='" . CCGetParam('per_Aplicacion', 'xx') . "' AND " . $Where;
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End periodosDataSource Class @2-FCB6E20C

//Initialize Page @1-3CE0321D
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

$FileName = "CoAdPe.php";
$Redirect = "";
$TemplateFileName = "CoAdPe.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-0AE288D2
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$periodos_qry = new clsRecordperiodos_qry();
$periodos = new clsEditableGridperiodos();
$periodos->Initialize();

// Events
include("./CoAdPe_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-97FC3207
$Cabecera->Operations();
$periodos_qry->Operation();
$periodos->Operation();
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

//Show Page @1-53C0F679
$Cabecera->Show("Cabecera");
$periodos_qry->Show();
$periodos->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
