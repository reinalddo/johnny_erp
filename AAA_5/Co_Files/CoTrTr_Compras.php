<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include(RelativePath . "/LibPhp/SegLib.php");
class clsEditableGridinvcompras { //invcompras Class @2-335A8108

//Variables @2-C78AF3FF

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
    var $ds;
    var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_cpr_TipoDocum;
    var $Sorter_cpr_IdAuxiliar;
    var $Sorter_cpr_NumAutoriz;
    var $Sorter_cpr_ImpIva;
    var $Sorter_cpr_CeroIva;
    var $Sorter_cpr_ImpIce;
    var $Sorter_cpr_CeroIce;
    var $Sorter_cpr_FecEmision;
    var $Sorter_cpr_FecRecepcion;
    var $Sorter_cpr_Solicita;
    var $Sorter_cpr_Destino;
    var $Sorter_cpr_Autoriza;
    var $Sorter_cpr_Observac;
    var $Sorter_cpr_Usuario;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-B2075677
    function clsEditableGridinvcompras()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid invcompras/Error";
        $this->ComponentName = "invcompras";
        $this->CachedColumns["cpr_TipoDocum"][0] = "cpr_TipoDocum";
        $this->CachedColumns["cpr_NumDocum"][0] = "cpr_NumDocum";
        $this->CachedColumns["cpr_RegNumero"][0] = "cpr_RegNumero";
        $this->ds = new clsinvcomprasDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 10;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
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

        $this->SorterName = CCGetParam("invcomprasOrder", "");
        $this->SorterDirection = CCGetParam("invcomprasDir", "");

        $this->invcompras_TotalRecords = new clsControl(ccsLabel, "invcompras_TotalRecords", "invcompras_TotalRecords", ccsText, "");
        $this->Sorter_cpr_TipoDocum = new clsSorter($this->ComponentName, "Sorter_cpr_TipoDocum", $FileName);
        $this->Sorter_cpr_IdAuxiliar = new clsSorter($this->ComponentName, "Sorter_cpr_IdAuxiliar", $FileName);
        $this->Sorter_cpr_NumAutoriz = new clsSorter($this->ComponentName, "Sorter_cpr_NumAutoriz", $FileName);
        $this->Sorter_cpr_ImpIva = new clsSorter($this->ComponentName, "Sorter_cpr_ImpIva", $FileName);
        $this->Sorter_cpr_CeroIva = new clsSorter($this->ComponentName, "Sorter_cpr_CeroIva", $FileName);
        $this->Sorter_cpr_ImpIce = new clsSorter($this->ComponentName, "Sorter_cpr_ImpIce", $FileName);
        $this->Sorter_cpr_CeroIce = new clsSorter($this->ComponentName, "Sorter_cpr_CeroIce", $FileName);
        $this->Sorter_cpr_FecEmision = new clsSorter($this->ComponentName, "Sorter_cpr_FecEmision", $FileName);
        $this->Sorter_cpr_FecRecepcion = new clsSorter($this->ComponentName, "Sorter_cpr_FecRecepcion", $FileName);
        $this->Sorter_cpr_Solicita = new clsSorter($this->ComponentName, "Sorter_cpr_Solicita", $FileName);
        $this->Sorter_cpr_Destino = new clsSorter($this->ComponentName, "Sorter_cpr_Destino", $FileName);
        $this->Sorter_cpr_Autoriza = new clsSorter($this->ComponentName, "Sorter_cpr_Autoriza", $FileName);
        $this->Sorter_cpr_Observac = new clsSorter($this->ComponentName, "Sorter_cpr_Observac", $FileName);
        $this->Sorter_cpr_Usuario = new clsSorter($this->ComponentName, "Sorter_cpr_Usuario", $FileName);
        $this->cpr_RegNumero = new clsControl(ccsTextBox, "cpr_RegNumero", "Cpr Reg Numero", ccsInteger, "");
        $this->cpr_TipoDocum = new clsControl(ccsTextBox, "cpr_TipoDocum", "Cpr Tipo Docum", ccsText, "");
        $this->cpr_NumDocum = new clsControl(ccsTextBox, "cpr_NumDocum", "Cpr Num Docum", ccsText, "");
        $this->cpr_IdAuxiliar = new clsControl(ccsTextBox, "cpr_IdAuxiliar", "Cpr Id Auxiliar", ccsInteger, "");
        $this->cpr_NumAutoriz = new clsControl(ccsTextBox, "cpr_NumAutoriz", "Cpr Num Autoriz", ccsText, "");
        $this->cpr_ImpIva = new clsControl(ccsTextBox, "cpr_ImpIva", "Cpr Imp Iva", ccsFloat, "");
        $this->cpr_CeroIva = new clsControl(ccsTextBox, "cpr_CeroIva", "Cpr Cero Iva", ccsFloat, "");
        $this->cpr_ImpIce = new clsControl(ccsTextBox, "cpr_ImpIce", "Cpr Imp Ice", ccsFloat, "");
        $this->cpr_CeroIce = new clsControl(ccsTextBox, "cpr_CeroIce", "Cpr Cero Ice", ccsFloat, "");
        $this->cpr_FecEmision = new clsControl(ccsTextBox, "cpr_FecEmision", "Cpr Fec Emision", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->DatePicker_cpr_FecEmision = new clsDatePicker("DatePicker_cpr_FecEmision", "invcompras", "cpr_FecEmision");
        $this->cpr_FecRecepcion = new clsControl(ccsTextBox, "cpr_FecRecepcion", "Cpr Fec Recepcion", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->DatePicker_cpr_FecRecepcion = new clsDatePicker("DatePicker_cpr_FecRecepcion", "invcompras", "cpr_FecRecepcion");
        $this->cpr_Solicita = new clsControl(ccsTextBox, "cpr_Solicita", "Cpr Solicita", ccsInteger, "");
        $this->cpr_Destino = new clsControl(ccsTextBox, "cpr_Destino", "Cpr Destino", ccsInteger, "");
        $this->cpr_Autoriza = new clsControl(ccsTextBox, "cpr_Autoriza", "Cpr Autoriza", ccsInteger, "");
        $this->cpr_Observac = new clsControl(ccsTextBox, "cpr_Observac", "Cpr Observac", ccsText, "");
        $this->cpr_Usuario = new clsControl(ccsTextBox, "cpr_Usuario", "Cpr Usuario", ccsText, "");
        $this->cpr_FecRegistro = new clsControl(ccsTextBox, "cpr_FecRegistro", "Cpr Fec Registro", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->cpr_FecRegistro->Required = true;
        $this->DatePicker_cpr_FecRegistro = new clsDatePicker("DatePicker_cpr_FecRegistro", "invcompras", "cpr_FecRegistro");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
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

//GetFormParameters Method @2-E9039784
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["cpr_RegNumero"][$RowNumber] = CCGetFromPost("cpr_RegNumero_" . $RowNumber);
            $this->FormParameters["cpr_TipoDocum"][$RowNumber] = CCGetFromPost("cpr_TipoDocum_" . $RowNumber);
            $this->FormParameters["cpr_NumDocum"][$RowNumber] = CCGetFromPost("cpr_NumDocum_" . $RowNumber);
            $this->FormParameters["cpr_IdAuxiliar"][$RowNumber] = CCGetFromPost("cpr_IdAuxiliar_" . $RowNumber);
            $this->FormParameters["cpr_NumAutoriz"][$RowNumber] = CCGetFromPost("cpr_NumAutoriz_" . $RowNumber);
            $this->FormParameters["cpr_ImpIva"][$RowNumber] = CCGetFromPost("cpr_ImpIva_" . $RowNumber);
            $this->FormParameters["cpr_CeroIva"][$RowNumber] = CCGetFromPost("cpr_CeroIva_" . $RowNumber);
            $this->FormParameters["cpr_ImpIce"][$RowNumber] = CCGetFromPost("cpr_ImpIce_" . $RowNumber);
            $this->FormParameters["cpr_CeroIce"][$RowNumber] = CCGetFromPost("cpr_CeroIce_" . $RowNumber);
            $this->FormParameters["cpr_FecEmision"][$RowNumber] = CCGetFromPost("cpr_FecEmision_" . $RowNumber);
            $this->FormParameters["cpr_FecRecepcion"][$RowNumber] = CCGetFromPost("cpr_FecRecepcion_" . $RowNumber);
            $this->FormParameters["cpr_Solicita"][$RowNumber] = CCGetFromPost("cpr_Solicita_" . $RowNumber);
            $this->FormParameters["cpr_Destino"][$RowNumber] = CCGetFromPost("cpr_Destino_" . $RowNumber);
            $this->FormParameters["cpr_Autoriza"][$RowNumber] = CCGetFromPost("cpr_Autoriza_" . $RowNumber);
            $this->FormParameters["cpr_Observac"][$RowNumber] = CCGetFromPost("cpr_Observac_" . $RowNumber);
            $this->FormParameters["cpr_Usuario"][$RowNumber] = CCGetFromPost("cpr_Usuario_" . $RowNumber);
            $this->FormParameters["cpr_FecRegistro"][$RowNumber] = CCGetFromPost("cpr_FecRegistro_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-46F36F84
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cpr_TipoDocum"] = $this->CachedColumns["cpr_TipoDocum"][$RowNumber];
            $this->ds->CachedColumns["cpr_NumDocum"] = $this->CachedColumns["cpr_NumDocum"][$RowNumber];
            $this->ds->CachedColumns["cpr_RegNumero"] = $this->CachedColumns["cpr_RegNumero"][$RowNumber];
            $this->cpr_RegNumero->SetText($this->FormParameters["cpr_RegNumero"][$RowNumber], $RowNumber);
            $this->cpr_TipoDocum->SetText($this->FormParameters["cpr_TipoDocum"][$RowNumber], $RowNumber);
            $this->cpr_NumDocum->SetText($this->FormParameters["cpr_NumDocum"][$RowNumber], $RowNumber);
            $this->cpr_IdAuxiliar->SetText($this->FormParameters["cpr_IdAuxiliar"][$RowNumber], $RowNumber);
            $this->cpr_NumAutoriz->SetText($this->FormParameters["cpr_NumAutoriz"][$RowNumber], $RowNumber);
            $this->cpr_ImpIva->SetText($this->FormParameters["cpr_ImpIva"][$RowNumber], $RowNumber);
            $this->cpr_CeroIva->SetText($this->FormParameters["cpr_CeroIva"][$RowNumber], $RowNumber);
            $this->cpr_ImpIce->SetText($this->FormParameters["cpr_ImpIce"][$RowNumber], $RowNumber);
            $this->cpr_CeroIce->SetText($this->FormParameters["cpr_CeroIce"][$RowNumber], $RowNumber);
            $this->cpr_FecEmision->SetText($this->FormParameters["cpr_FecEmision"][$RowNumber], $RowNumber);
            $this->cpr_FecRecepcion->SetText($this->FormParameters["cpr_FecRecepcion"][$RowNumber], $RowNumber);
            $this->cpr_Solicita->SetText($this->FormParameters["cpr_Solicita"][$RowNumber], $RowNumber);
            $this->cpr_Destino->SetText($this->FormParameters["cpr_Destino"][$RowNumber], $RowNumber);
            $this->cpr_Autoriza->SetText($this->FormParameters["cpr_Autoriza"][$RowNumber], $RowNumber);
            $this->cpr_Observac->SetText($this->FormParameters["cpr_Observac"][$RowNumber], $RowNumber);
            $this->cpr_Usuario->SetText($this->FormParameters["cpr_Usuario"][$RowNumber], $RowNumber);
            $this->cpr_FecRegistro->SetText($this->FormParameters["cpr_FecRegistro"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-FBE67127
    function ValidateRow($RowNumber)
    {
        $this->cpr_RegNumero->Validate();
        $this->cpr_TipoDocum->Validate();
        $this->cpr_NumDocum->Validate();
        $this->cpr_IdAuxiliar->Validate();
        $this->cpr_NumAutoriz->Validate();
        $this->cpr_ImpIva->Validate();
        $this->cpr_CeroIva->Validate();
        $this->cpr_ImpIce->Validate();
        $this->cpr_CeroIce->Validate();
        $this->cpr_FecEmision->Validate();
        $this->cpr_FecRecepcion->Validate();
        $this->cpr_Solicita->Validate();
        $this->cpr_Destino->Validate();
        $this->cpr_Autoriza->Validate();
        $this->cpr_Observac->Validate();
        $this->cpr_Usuario->Validate();
        $this->cpr_FecRegistro->Validate();
        $this->CheckBox_Delete->Validate();
        $this->RowErrors = new clsErrors();
        $errors = $this->cpr_RegNumero->Errors->ToString();
        $errors .= $this->cpr_TipoDocum->Errors->ToString();
        $errors .= $this->cpr_NumDocum->Errors->ToString();
        $errors .= $this->cpr_IdAuxiliar->Errors->ToString();
        $errors .= $this->cpr_NumAutoriz->Errors->ToString();
        $errors .= $this->cpr_ImpIva->Errors->ToString();
        $errors .= $this->cpr_CeroIva->Errors->ToString();
        $errors .= $this->cpr_ImpIce->Errors->ToString();
        $errors .= $this->cpr_CeroIce->Errors->ToString();
        $errors .= $this->cpr_FecEmision->Errors->ToString();
        $errors .= $this->cpr_FecRecepcion->Errors->ToString();
        $errors .= $this->cpr_Solicita->Errors->ToString();
        $errors .= $this->cpr_Destino->Errors->ToString();
        $errors .= $this->cpr_Autoriza->Errors->ToString();
        $errors .= $this->cpr_Observac->Errors->ToString();
        $errors .= $this->cpr_Usuario->Errors->ToString();
        $errors .= $this->cpr_FecRegistro->Errors->ToString();
        $errors .= $this->CheckBox_Delete->Errors->ToString();
        $this->cpr_RegNumero->Errors->Clear();
        $this->cpr_TipoDocum->Errors->Clear();
        $this->cpr_NumDocum->Errors->Clear();
        $this->cpr_IdAuxiliar->Errors->Clear();
        $this->cpr_NumAutoriz->Errors->Clear();
        $this->cpr_ImpIva->Errors->Clear();
        $this->cpr_CeroIva->Errors->Clear();
        $this->cpr_ImpIce->Errors->Clear();
        $this->cpr_CeroIce->Errors->Clear();
        $this->cpr_FecEmision->Errors->Clear();
        $this->cpr_FecRecepcion->Errors->Clear();
        $this->cpr_Solicita->Errors->Clear();
        $this->cpr_Destino->Errors->Clear();
        $this->cpr_Autoriza->Errors->Clear();
        $this->cpr_Observac->Errors->Clear();
        $this->cpr_Usuario->Errors->Clear();
        $this->cpr_FecRegistro->Errors->Clear();
        $this->CheckBox_Delete->Errors->Clear();
        $errors .=$this->RowErrors->ToString();
        $this->RowsErrors[$RowNumber] = $errors;
        return $errors ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @2-D9B7DEA3
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["cpr_RegNumero"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_TipoDocum"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_NumDocum"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_IdAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_NumAutoriz"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_ImpIva"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_CeroIva"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_ImpIce"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_CeroIce"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_FecEmision"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_FecRecepcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_Solicita"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_Destino"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_Autoriza"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_Observac"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_Usuario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cpr_FecRegistro"][$RowNumber]));
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

//UpdateGrid Method @2-7FDC3E19
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cpr_TipoDocum"] = $this->CachedColumns["cpr_TipoDocum"][$RowNumber];
            $this->ds->CachedColumns["cpr_NumDocum"] = $this->CachedColumns["cpr_NumDocum"][$RowNumber];
            $this->ds->CachedColumns["cpr_RegNumero"] = $this->CachedColumns["cpr_RegNumero"][$RowNumber];
            $this->cpr_RegNumero->SetText($this->FormParameters["cpr_RegNumero"][$RowNumber], $RowNumber);
            $this->cpr_TipoDocum->SetText($this->FormParameters["cpr_TipoDocum"][$RowNumber], $RowNumber);
            $this->cpr_NumDocum->SetText($this->FormParameters["cpr_NumDocum"][$RowNumber], $RowNumber);
            $this->cpr_IdAuxiliar->SetText($this->FormParameters["cpr_IdAuxiliar"][$RowNumber], $RowNumber);
            $this->cpr_NumAutoriz->SetText($this->FormParameters["cpr_NumAutoriz"][$RowNumber], $RowNumber);
            $this->cpr_ImpIva->SetText($this->FormParameters["cpr_ImpIva"][$RowNumber], $RowNumber);
            $this->cpr_CeroIva->SetText($this->FormParameters["cpr_CeroIva"][$RowNumber], $RowNumber);
            $this->cpr_ImpIce->SetText($this->FormParameters["cpr_ImpIce"][$RowNumber], $RowNumber);
            $this->cpr_CeroIce->SetText($this->FormParameters["cpr_CeroIce"][$RowNumber], $RowNumber);
            $this->cpr_FecEmision->SetText($this->FormParameters["cpr_FecEmision"][$RowNumber], $RowNumber);
            $this->cpr_FecRecepcion->SetText($this->FormParameters["cpr_FecRecepcion"][$RowNumber], $RowNumber);
            $this->cpr_Solicita->SetText($this->FormParameters["cpr_Solicita"][$RowNumber], $RowNumber);
            $this->cpr_Destino->SetText($this->FormParameters["cpr_Destino"][$RowNumber], $RowNumber);
            $this->cpr_Autoriza->SetText($this->FormParameters["cpr_Autoriza"][$RowNumber], $RowNumber);
            $this->cpr_Observac->SetText($this->FormParameters["cpr_Observac"][$RowNumber], $RowNumber);
            $this->cpr_Usuario->SetText($this->FormParameters["cpr_Usuario"][$RowNumber], $RowNumber);
            $this->cpr_FecRegistro->SetText($this->FormParameters["cpr_FecRegistro"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) { $Validation = ($this->DeleteRow($RowNumber) && $Validation); }
                } else if($this->UpdateAllowed) {
                    $Validation = ($this->UpdateRow($RowNumber) && $Validation);
                }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $Validation = ($this->InsertRow($RowNumber) && $Validation);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0 && $Validation);
    }
//End UpdateGrid Method

//InsertRow Method @2-6F2CDB4C
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->cpr_RegNumero->SetValue($this->cpr_RegNumero->GetValue());
        $this->ds->cpr_TipoDocum->SetValue($this->cpr_TipoDocum->GetValue());
        $this->ds->cpr_NumDocum->SetValue($this->cpr_NumDocum->GetValue());
        $this->ds->cpr_IdAuxiliar->SetValue($this->cpr_IdAuxiliar->GetValue());
        $this->ds->cpr_NumAutoriz->SetValue($this->cpr_NumAutoriz->GetValue());
        $this->ds->cpr_ImpIva->SetValue($this->cpr_ImpIva->GetValue());
        $this->ds->cpr_CeroIva->SetValue($this->cpr_CeroIva->GetValue());
        $this->ds->cpr_ImpIce->SetValue($this->cpr_ImpIce->GetValue());
        $this->ds->cpr_CeroIce->SetValue($this->cpr_CeroIce->GetValue());
        $this->ds->cpr_FecEmision->SetValue($this->cpr_FecEmision->GetValue());
        $this->ds->cpr_FecRecepcion->SetValue($this->cpr_FecRecepcion->GetValue());
        $this->ds->cpr_Solicita->SetValue($this->cpr_Solicita->GetValue());
        $this->ds->cpr_Destino->SetValue($this->cpr_Destino->GetValue());
        $this->ds->cpr_Autoriza->SetValue($this->cpr_Autoriza->GetValue());
        $this->ds->cpr_Observac->SetValue($this->cpr_Observac->GetValue());
        $this->ds->cpr_Usuario->SetValue($this->cpr_Usuario->GetValue());
        $this->ds->cpr_FecRegistro->SetValue($this->cpr_FecRegistro->GetValue());
        $this->ds->Insert();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @2-D3B3D243
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->cpr_RegNumero->SetValue($this->cpr_RegNumero->GetValue());
        $this->ds->cpr_TipoDocum->SetValue($this->cpr_TipoDocum->GetValue());
        $this->ds->cpr_NumDocum->SetValue($this->cpr_NumDocum->GetValue());
        $this->ds->cpr_IdAuxiliar->SetValue($this->cpr_IdAuxiliar->GetValue());
        $this->ds->cpr_NumAutoriz->SetValue($this->cpr_NumAutoriz->GetValue());
        $this->ds->cpr_ImpIva->SetValue($this->cpr_ImpIva->GetValue());
        $this->ds->cpr_CeroIva->SetValue($this->cpr_CeroIva->GetValue());
        $this->ds->cpr_ImpIce->SetValue($this->cpr_ImpIce->GetValue());
        $this->ds->cpr_CeroIce->SetValue($this->cpr_CeroIce->GetValue());
        $this->ds->cpr_FecEmision->SetValue($this->cpr_FecEmision->GetValue());
        $this->ds->cpr_FecRecepcion->SetValue($this->cpr_FecRecepcion->GetValue());
        $this->ds->cpr_Solicita->SetValue($this->cpr_Solicita->GetValue());
        $this->ds->cpr_Destino->SetValue($this->cpr_Destino->GetValue());
        $this->ds->cpr_Autoriza->SetValue($this->cpr_Autoriza->GetValue());
        $this->ds->cpr_Observac->SetValue($this->cpr_Observac->GetValue());
        $this->ds->cpr_Usuario->SetValue($this->cpr_Usuario->GetValue());
        $this->ds->cpr_FecRegistro->SetValue($this->cpr_FecRegistro->GetValue());
        $this->ds->Update();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//DeleteRow Method @2-E90CB5E3
    function DeleteRow($RowNumber)
    {
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @2-35B3349F
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var invcomprasElements;\n";
        $script .= "var invcomprasEmptyRows = 10;\n";
        $script .= "var " . $this->ComponentName . "cpr_RegNumeroID = 0;\n";
        $script .= "var " . $this->ComponentName . "cpr_TipoDocumID = 1;\n";
        $script .= "var " . $this->ComponentName . "cpr_NumDocumID = 2;\n";
        $script .= "var " . $this->ComponentName . "cpr_IdAuxiliarID = 3;\n";
        $script .= "var " . $this->ComponentName . "cpr_NumAutorizID = 4;\n";
        $script .= "var " . $this->ComponentName . "cpr_ImpIvaID = 5;\n";
        $script .= "var " . $this->ComponentName . "cpr_CeroIvaID = 6;\n";
        $script .= "var " . $this->ComponentName . "cpr_ImpIceID = 7;\n";
        $script .= "var " . $this->ComponentName . "cpr_CeroIceID = 8;\n";
        $script .= "var " . $this->ComponentName . "cpr_FecEmisionID = 9;\n";
        $script .= "var " . $this->ComponentName . "cpr_FecRecepcionID = 10;\n";
        $script .= "var " . $this->ComponentName . "cpr_SolicitaID = 11;\n";
        $script .= "var " . $this->ComponentName . "cpr_DestinoID = 12;\n";
        $script .= "var " . $this->ComponentName . "cpr_AutorizaID = 13;\n";
        $script .= "var " . $this->ComponentName . "cpr_ObservacID = 14;\n";
        $script .= "var " . $this->ComponentName . "cpr_UsuarioID = 15;\n";
        $script .= "var " . $this->ComponentName . "cpr_FecRegistroID = 16;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 17;\n";
        $script .= "\nfunction initinvcomprasElements() {\n";
        $script .= "\tvar ED = document.forms[\"invcompras\"];\n";
        $script .= "\tinvcomprasElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.cpr_RegNumero_" . $i . ", " . "ED.cpr_TipoDocum_" . $i . ", " . "ED.cpr_NumDocum_" . $i . ", " . "ED.cpr_IdAuxiliar_" . $i . ", " . "ED.cpr_NumAutoriz_" . $i . ", " . "ED.cpr_ImpIva_" . $i . ", " . "ED.cpr_CeroIva_" . $i . ", " . "ED.cpr_ImpIce_" . $i . ", " . "ED.cpr_CeroIce_" . $i . ", " . "ED.cpr_FecEmision_" . $i . ", " . "ED.cpr_FecRecepcion_" . $i . ", " . "ED.cpr_Solicita_" . $i . ", " . "ED.cpr_Destino_" . $i . ", " . "ED.cpr_Autoriza_" . $i . ", " . "ED.cpr_Observac_" . $i . ", " . "ED.cpr_Usuario_" . $i . ", " . "ED.cpr_FecRegistro_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-ABB97C95
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 3)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cpr_TipoDocum"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cpr_NumDocum"][$RowNumber] = $piece;
                $piece = $pieces[$i + 2];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cpr_RegNumero"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["cpr_TipoDocum"][$RowNumber] = "";
                $this->CachedColumns["cpr_NumDocum"][$RowNumber] = "";
                $this->CachedColumns["cpr_RegNumero"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-1B8D00EA
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cpr_TipoDocum"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cpr_NumDocum"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cpr_RegNumero"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-3A351CEA
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) { return; }

        $this->Button_Submit->Visible = $this->Button_Submit->Visible && ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
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
                    $this->CachedColumns["cpr_TipoDocum"][$RowNumber] = $this->ds->CachedColumns["cpr_TipoDocum"];
                    $this->CachedColumns["cpr_NumDocum"][$RowNumber] = $this->ds->CachedColumns["cpr_NumDocum"];
                    $this->CachedColumns["cpr_RegNumero"][$RowNumber] = $this->ds->CachedColumns["cpr_RegNumero"];
                    $this->cpr_RegNumero->SetValue($this->ds->cpr_RegNumero->GetValue());
                    $this->cpr_TipoDocum->SetValue($this->ds->cpr_TipoDocum->GetValue());
                    $this->cpr_NumDocum->SetValue($this->ds->cpr_NumDocum->GetValue());
                    $this->cpr_IdAuxiliar->SetValue($this->ds->cpr_IdAuxiliar->GetValue());
                    $this->cpr_NumAutoriz->SetValue($this->ds->cpr_NumAutoriz->GetValue());
                    $this->cpr_ImpIva->SetValue($this->ds->cpr_ImpIva->GetValue());
                    $this->cpr_CeroIva->SetValue($this->ds->cpr_CeroIva->GetValue());
                    $this->cpr_ImpIce->SetValue($this->ds->cpr_ImpIce->GetValue());
                    $this->cpr_CeroIce->SetValue($this->ds->cpr_CeroIce->GetValue());
                    $this->cpr_FecEmision->SetValue($this->ds->cpr_FecEmision->GetValue());
                    $this->cpr_FecRecepcion->SetValue($this->ds->cpr_FecRecepcion->GetValue());
                    $this->cpr_Solicita->SetValue($this->ds->cpr_Solicita->GetValue());
                    $this->cpr_Destino->SetValue($this->ds->cpr_Destino->GetValue());
                    $this->cpr_Autoriza->SetValue($this->ds->cpr_Autoriza->GetValue());
                    $this->cpr_Observac->SetValue($this->ds->cpr_Observac->GetValue());
                    $this->cpr_Usuario->SetValue($this->ds->cpr_Usuario->GetValue());
                    $this->cpr_FecRegistro->SetValue($this->ds->cpr_FecRegistro->GetValue());
                    $this->ValidateRow($RowNumber);
                } else if (!$this->FormSubmitted){
                    $this->CachedColumns["cpr_TipoDocum"][$RowNumber] = "";
                    $this->CachedColumns["cpr_NumDocum"][$RowNumber] = "";
                    $this->CachedColumns["cpr_RegNumero"][$RowNumber] = "";
                    $this->cpr_RegNumero->SetText("");
                    $this->cpr_TipoDocum->SetText("");
                    $this->cpr_NumDocum->SetText("");
                    $this->cpr_IdAuxiliar->SetText("");
                    $this->cpr_NumAutoriz->SetText("");
                    $this->cpr_ImpIva->SetText("");
                    $this->cpr_CeroIva->SetText("");
                    $this->cpr_ImpIce->SetText("");
                    $this->cpr_CeroIce->SetText("");
                    $this->cpr_FecEmision->SetText("");
                    $this->cpr_FecRecepcion->SetText("");
                    $this->cpr_Solicita->SetText("");
                    $this->cpr_Destino->SetText("");
                    $this->cpr_Autoriza->SetText("");
                    $this->cpr_Observac->SetText("");
                    $this->cpr_Usuario->SetText("");
                    $this->cpr_FecRegistro->SetText("");
                    $this->CheckBox_Delete->SetText("");
                } else {
                    $this->cpr_RegNumero->SetText($this->FormParameters["cpr_RegNumero"][$RowNumber], $RowNumber);
                    $this->cpr_TipoDocum->SetText($this->FormParameters["cpr_TipoDocum"][$RowNumber], $RowNumber);
                    $this->cpr_NumDocum->SetText($this->FormParameters["cpr_NumDocum"][$RowNumber], $RowNumber);
                    $this->cpr_IdAuxiliar->SetText($this->FormParameters["cpr_IdAuxiliar"][$RowNumber], $RowNumber);
                    $this->cpr_NumAutoriz->SetText($this->FormParameters["cpr_NumAutoriz"][$RowNumber], $RowNumber);
                    $this->cpr_ImpIva->SetText($this->FormParameters["cpr_ImpIva"][$RowNumber], $RowNumber);
                    $this->cpr_CeroIva->SetText($this->FormParameters["cpr_CeroIva"][$RowNumber], $RowNumber);
                    $this->cpr_ImpIce->SetText($this->FormParameters["cpr_ImpIce"][$RowNumber], $RowNumber);
                    $this->cpr_CeroIce->SetText($this->FormParameters["cpr_CeroIce"][$RowNumber], $RowNumber);
                    $this->cpr_FecEmision->SetText($this->FormParameters["cpr_FecEmision"][$RowNumber], $RowNumber);
                    $this->cpr_FecRecepcion->SetText($this->FormParameters["cpr_FecRecepcion"][$RowNumber], $RowNumber);
                    $this->cpr_Solicita->SetText($this->FormParameters["cpr_Solicita"][$RowNumber], $RowNumber);
                    $this->cpr_Destino->SetText($this->FormParameters["cpr_Destino"][$RowNumber], $RowNumber);
                    $this->cpr_Autoriza->SetText($this->FormParameters["cpr_Autoriza"][$RowNumber], $RowNumber);
                    $this->cpr_Observac->SetText($this->FormParameters["cpr_Observac"][$RowNumber], $RowNumber);
                    $this->cpr_Usuario->SetText($this->FormParameters["cpr_Usuario"][$RowNumber], $RowNumber);
                    $this->cpr_FecRegistro->SetText($this->FormParameters["cpr_FecRegistro"][$RowNumber], $RowNumber);
                    $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->cpr_RegNumero->Show($RowNumber);
                $this->cpr_TipoDocum->Show($RowNumber);
                $this->cpr_NumDocum->Show($RowNumber);
                $this->cpr_IdAuxiliar->Show($RowNumber);
                $this->cpr_NumAutoriz->Show($RowNumber);
                $this->cpr_ImpIva->Show($RowNumber);
                $this->cpr_CeroIva->Show($RowNumber);
                $this->cpr_ImpIce->Show($RowNumber);
                $this->cpr_CeroIce->Show($RowNumber);
                $this->cpr_FecEmision->Show($RowNumber);
                $this->DatePicker_cpr_FecEmision->Show($RowNumber);
                $this->cpr_FecRecepcion->Show($RowNumber);
                $this->DatePicker_cpr_FecRecepcion->Show($RowNumber);
                $this->cpr_Solicita->Show($RowNumber);
                $this->cpr_Destino->Show($RowNumber);
                $this->cpr_Autoriza->Show($RowNumber);
                $this->cpr_Observac->Show($RowNumber);
                $this->cpr_Usuario->Show($RowNumber);
                $this->cpr_FecRegistro->Show($RowNumber);
                $this->DatePicker_cpr_FecRegistro->Show($RowNumber);
                $this->CheckBox_Delete->Show($RowNumber);
                if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                    $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                    $Tpl->parse("RowError", false);
                } else {
                    $Tpl->setblockvar("RowError", "");
                }
                $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                $Tpl->parse();
                if($is_next_record) $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
                else $EmptyRowsLeft--;
            } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
        } else {
            $Tpl->block_path = $EditableGridPath;
            $Tpl->parse("NoRecords", false);
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->invcompras_TotalRecords->Show();
        $this->Sorter_cpr_TipoDocum->Show();
        $this->Sorter_cpr_IdAuxiliar->Show();
        $this->Sorter_cpr_NumAutoriz->Show();
        $this->Sorter_cpr_ImpIva->Show();
        $this->Sorter_cpr_CeroIva->Show();
        $this->Sorter_cpr_ImpIce->Show();
        $this->Sorter_cpr_CeroIce->Show();
        $this->Sorter_cpr_FecEmision->Show();
        $this->Sorter_cpr_FecRecepcion->Show();
        $this->Sorter_cpr_Solicita->Show();
        $this->Sorter_cpr_Destino->Show();
        $this->Sorter_cpr_Autoriza->Show();
        $this->Sorter_cpr_Observac->Show();
        $this->Sorter_cpr_Usuario->Show();
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

} //End invcompras Class @2-FCB6E20C

class clsinvcomprasDataSource extends clsDBdatos {  //invcomprasDataSource Class @2-A09CF4EE

//DataSource Variables @2-9A5DF086
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $cpr_RegNumero;
    var $cpr_TipoDocum;
    var $cpr_NumDocum;
    var $cpr_IdAuxiliar;
    var $cpr_NumAutoriz;
    var $cpr_ImpIva;
    var $cpr_CeroIva;
    var $cpr_ImpIce;
    var $cpr_CeroIce;
    var $cpr_FecEmision;
    var $cpr_FecRecepcion;
    var $cpr_Solicita;
    var $cpr_Destino;
    var $cpr_Autoriza;
    var $cpr_Observac;
    var $cpr_Usuario;
    var $cpr_FecRegistro;
    var $CheckBox_Delete;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-2E808560
    function clsinvcomprasDataSource()
    {
        $this->ErrorBlock = "EditableGrid invcompras/Error";
        $this->Initialize();
        $this->cpr_RegNumero = new clsField("cpr_RegNumero", ccsInteger, "");
        $this->cpr_TipoDocum = new clsField("cpr_TipoDocum", ccsText, "");
        $this->cpr_NumDocum = new clsField("cpr_NumDocum", ccsText, "");
        $this->cpr_IdAuxiliar = new clsField("cpr_IdAuxiliar", ccsInteger, "");
        $this->cpr_NumAutoriz = new clsField("cpr_NumAutoriz", ccsText, "");
        $this->cpr_ImpIva = new clsField("cpr_ImpIva", ccsFloat, "");
        $this->cpr_CeroIva = new clsField("cpr_CeroIva", ccsFloat, "");
        $this->cpr_ImpIce = new clsField("cpr_ImpIce", ccsFloat, "");
        $this->cpr_CeroIce = new clsField("cpr_CeroIce", ccsFloat, "");
        $this->cpr_FecEmision = new clsField("cpr_FecEmision", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->cpr_FecRecepcion = new clsField("cpr_FecRecepcion", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->cpr_Solicita = new clsField("cpr_Solicita", ccsInteger, "");
        $this->cpr_Destino = new clsField("cpr_Destino", ccsInteger, "");
        $this->cpr_Autoriza = new clsField("cpr_Autoriza", ccsInteger, "");
        $this->cpr_Observac = new clsField("cpr_Observac", ccsText, "");
        $this->cpr_Usuario = new clsField("cpr_Usuario", ccsText, "");
        $this->cpr_FecRegistro = new clsField("cpr_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-BF94EF6D
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cpr_TipoDocum" => array("cpr_TipoDocum", ""), 
            "Sorter_cpr_IdAuxiliar" => array("cpr_IdAuxiliar", ""), 
            "Sorter_cpr_NumAutoriz" => array("cpr_NumAutoriz", ""), 
            "Sorter_cpr_ImpIva" => array("cpr_ImpIva", ""), 
            "Sorter_cpr_CeroIva" => array("cpr_CeroIva", ""), 
            "Sorter_cpr_ImpIce" => array("cpr_ImpIce", ""), 
            "Sorter_cpr_CeroIce" => array("cpr_CeroIce", ""), 
            "Sorter_cpr_FecEmision" => array("cpr_FecEmision", ""), 
            "Sorter_cpr_FecRecepcion" => array("cpr_FecRecepcion", ""), 
            "Sorter_cpr_Solicita" => array("cpr_Solicita", ""), 
            "Sorter_cpr_Destino" => array("cpr_Destino", ""), 
            "Sorter_cpr_Autoriza" => array("cpr_Autoriza", ""), 
            "Sorter_cpr_Observac" => array("cpr_Observac", ""), 
            "Sorter_cpr_Usuario" => array("cpr_Usuario", "")));
    }
//End SetOrder Method

//Prepare Method @2-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @2-F6F8DE68
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM invcompras";
        $this->SQL = "SELECT *  " .
        "FROM invcompras";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-B200AA9C
    function SetValues()
    {
        $this->CachedColumns["cpr_TipoDocum"] = $this->f("cpr_TipoDocum");
        $this->CachedColumns["cpr_NumDocum"] = $this->f("cpr_NumDocum");
        $this->CachedColumns["cpr_RegNumero"] = $this->f("cpr_RegNumero");
        $this->cpr_RegNumero->SetDBValue(trim($this->f("cpr_RegNumero")));
        $this->cpr_TipoDocum->SetDBValue($this->f("cpr_TipoDocum"));
        $this->cpr_NumDocum->SetDBValue($this->f("cpr_NumDocum"));
        $this->cpr_IdAuxiliar->SetDBValue(trim($this->f("cpr_IdAuxiliar")));
        $this->cpr_NumAutoriz->SetDBValue($this->f("cpr_NumAutoriz"));
        $this->cpr_ImpIva->SetDBValue(trim($this->f("cpr_ImpIva")));
        $this->cpr_CeroIva->SetDBValue(trim($this->f("cpr_CeroIva")));
        $this->cpr_ImpIce->SetDBValue(trim($this->f("cpr_ImpIce")));
        $this->cpr_CeroIce->SetDBValue(trim($this->f("cpr_CeroIce")));
        $this->cpr_FecEmision->SetDBValue(trim($this->f("cpr_FecEmision")));
        $this->cpr_FecRecepcion->SetDBValue(trim($this->f("cpr_FecRecepcion")));
        $this->cpr_Solicita->SetDBValue(trim($this->f("cpr_Solicita")));
        $this->cpr_Destino->SetDBValue(trim($this->f("cpr_Destino")));
        $this->cpr_Autoriza->SetDBValue(trim($this->f("cpr_Autoriza")));
        $this->cpr_Observac->SetDBValue($this->f("cpr_Observac"));
        $this->cpr_Usuario->SetDBValue($this->f("cpr_Usuario"));
        $this->cpr_FecRegistro->SetDBValue(trim($this->f("cpr_FecRegistro")));
    }
//End SetValues Method

//Insert Method @2-C9C9C13C
    function Insert()
    {
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO invcompras ("
             . "cpr_RegNumero, "
             . "cpr_TipoDocum, "
             . "cpr_NumDocum, "
             . "cpr_IdAuxiliar, "
             . "cpr_NumAutoriz, "
             . "cpr_ImpIva, "
             . "cpr_CeroIva, "
             . "cpr_ImpIce, "
             . "cpr_CeroIce, "
             . "cpr_FecEmision, "
             . "cpr_FecRecepcion, "
             . "cpr_Solicita, "
             . "cpr_Destino, "
             . "cpr_Autoriza, "
             . "cpr_Observac, "
             . "cpr_Usuario, "
             . "cpr_FecRegistro"
             . ") VALUES ("
             . $this->ToSQL($this->cpr_RegNumero->GetDBValue(), $this->cpr_RegNumero->DataType) . ", "
             . $this->ToSQL($this->cpr_TipoDocum->GetDBValue(), $this->cpr_TipoDocum->DataType) . ", "
             . $this->ToSQL($this->cpr_NumDocum->GetDBValue(), $this->cpr_NumDocum->DataType) . ", "
             . $this->ToSQL($this->cpr_IdAuxiliar->GetDBValue(), $this->cpr_IdAuxiliar->DataType) . ", "
             . $this->ToSQL($this->cpr_NumAutoriz->GetDBValue(), $this->cpr_NumAutoriz->DataType) . ", "
             . $this->ToSQL($this->cpr_ImpIva->GetDBValue(), $this->cpr_ImpIva->DataType) . ", "
             . $this->ToSQL($this->cpr_CeroIva->GetDBValue(), $this->cpr_CeroIva->DataType) . ", "
             . $this->ToSQL($this->cpr_ImpIce->GetDBValue(), $this->cpr_ImpIce->DataType) . ", "
             . $this->ToSQL($this->cpr_CeroIce->GetDBValue(), $this->cpr_CeroIce->DataType) . ", "
             . $this->ToSQL($this->cpr_FecEmision->GetDBValue(), $this->cpr_FecEmision->DataType) . ", "
             . $this->ToSQL($this->cpr_FecRecepcion->GetDBValue(), $this->cpr_FecRecepcion->DataType) . ", "
             . $this->ToSQL($this->cpr_Solicita->GetDBValue(), $this->cpr_Solicita->DataType) . ", "
             . $this->ToSQL($this->cpr_Destino->GetDBValue(), $this->cpr_Destino->DataType) . ", "
             . $this->ToSQL($this->cpr_Autoriza->GetDBValue(), $this->cpr_Autoriza->DataType) . ", "
             . $this->ToSQL($this->cpr_Observac->GetDBValue(), $this->cpr_Observac->DataType) . ", "
             . $this->ToSQL($this->cpr_Usuario->GetDBValue(), $this->cpr_Usuario->DataType) . ", "
             . $this->ToSQL($this->cpr_FecRegistro->GetDBValue(), $this->cpr_FecRegistro->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @2-82D79E7D
    function Update()
    {
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->Where = "cpr_TipoDocum=" . $this->ToSQL($this->CachedColumns["cpr_TipoDocum"], ccsText) . " AND cpr_NumDocum=" . $this->ToSQL($this->CachedColumns["cpr_NumDocum"], ccsText) . " AND cpr_RegNumero=" . $this->ToSQL($this->CachedColumns["cpr_RegNumero"], ccsInteger);
        $this->SQL = "UPDATE invcompras SET "
             . "cpr_RegNumero=" . $this->ToSQL($this->cpr_RegNumero->GetDBValue(), $this->cpr_RegNumero->DataType) . ", "
             . "cpr_TipoDocum=" . $this->ToSQL($this->cpr_TipoDocum->GetDBValue(), $this->cpr_TipoDocum->DataType) . ", "
             . "cpr_NumDocum=" . $this->ToSQL($this->cpr_NumDocum->GetDBValue(), $this->cpr_NumDocum->DataType) . ", "
             . "cpr_IdAuxiliar=" . $this->ToSQL($this->cpr_IdAuxiliar->GetDBValue(), $this->cpr_IdAuxiliar->DataType) . ", "
             . "cpr_NumAutoriz=" . $this->ToSQL($this->cpr_NumAutoriz->GetDBValue(), $this->cpr_NumAutoriz->DataType) . ", "
             . "cpr_ImpIva=" . $this->ToSQL($this->cpr_ImpIva->GetDBValue(), $this->cpr_ImpIva->DataType) . ", "
             . "cpr_CeroIva=" . $this->ToSQL($this->cpr_CeroIva->GetDBValue(), $this->cpr_CeroIva->DataType) . ", "
             . "cpr_ImpIce=" . $this->ToSQL($this->cpr_ImpIce->GetDBValue(), $this->cpr_ImpIce->DataType) . ", "
             . "cpr_CeroIce=" . $this->ToSQL($this->cpr_CeroIce->GetDBValue(), $this->cpr_CeroIce->DataType) . ", "
             . "cpr_FecEmision=" . $this->ToSQL($this->cpr_FecEmision->GetDBValue(), $this->cpr_FecEmision->DataType) . ", "
             . "cpr_FecRecepcion=" . $this->ToSQL($this->cpr_FecRecepcion->GetDBValue(), $this->cpr_FecRecepcion->DataType) . ", "
             . "cpr_Solicita=" . $this->ToSQL($this->cpr_Solicita->GetDBValue(), $this->cpr_Solicita->DataType) . ", "
             . "cpr_Destino=" . $this->ToSQL($this->cpr_Destino->GetDBValue(), $this->cpr_Destino->DataType) . ", "
             . "cpr_Autoriza=" . $this->ToSQL($this->cpr_Autoriza->GetDBValue(), $this->cpr_Autoriza->DataType) . ", "
             . "cpr_Observac=" . $this->ToSQL($this->cpr_Observac->GetDBValue(), $this->cpr_Observac->DataType) . ", "
             . "cpr_Usuario=" . $this->ToSQL($this->cpr_Usuario->GetDBValue(), $this->cpr_Usuario->DataType) . ", "
             . "cpr_FecRegistro=" . $this->ToSQL($this->cpr_FecRegistro->GetDBValue(), $this->cpr_FecRegistro->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @2-657B5F08
    function Delete()
    {
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->Where = "cpr_TipoDocum=" . $this->ToSQL($this->CachedColumns["cpr_TipoDocum"], ccsText) . " AND cpr_NumDocum=" . $this->ToSQL($this->CachedColumns["cpr_NumDocum"], ccsText) . " AND cpr_RegNumero=" . $this->ToSQL($this->CachedColumns["cpr_RegNumero"], ccsInteger);
        $this->SQL = "DELETE FROM invcompras";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End invcomprasDataSource Class @2-FCB6E20C

//Initialize Page @1-BD155344
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

$FileName = "CoTrTr_Compras.php";
$Redirect = "";
$TemplateFileName = "CoTrTr_Compras.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-FB38EC34
$DBdatos = new clsDBdatos();

// Controls
$invcompras = new clsEditableGridinvcompras();
$invcompras->Initialize();

// Events
include("./CoTrTr_Compras_events.php");
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

//Execute Components @1-4E4C68B8
$invcompras->Operation();
//End Execute Components

//Go to destination page @1-AE097A21
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    unset($invcompras);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-E0182763
$invcompras->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$CKDAOFT7I0D8O4R8O = array("<center><font face=\"Arial\">","<small>&#71;enerat&#101;d"," <!-- SCC -->&#119;ith <!--"," CCS -->&#67;o&#100;eC&#1","04;a&#114;&#103;e <!-- SCC ","-->&#83;tud&#105;o.</small","></font></center>","");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($CKDAOFT7I0D8O4R8O,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($CKDAOFT7I0D8O4R8O,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($CKDAOFT7I0D8O4R8O,"");
}
echo $main_block;
//End Show Page

//Unload Page @1-1E8EB5D9
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($invcompras);
unset($Tpl);
//End Unload Page


?>
