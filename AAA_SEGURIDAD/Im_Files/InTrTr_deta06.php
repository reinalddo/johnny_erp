<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");

//End Include Common Files

Class clsEditableGridInTrTr_detalle { //InTrTr_detalle Class @2-BE250E6B

//Variables @2-0AD37A13

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
    var $Sorter_det_Secuencia;
    var $Sorter_det_CodItem;
    var $Sorter_act_Descripcion;
    var $Navigator;
    var $Recibir;
    var $idRecibir;
//End Variables

//Class_Initialize Event @2-54C08CC9
    function clsEditableGridInTrTr_detalle()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid InTrTr_detalle/Error";
        $this->ComponentName = "InTrTr_detalle";
        $this->CachedColumns["det_Secuencia"][0] = "det_Secuencia";
        $this->ds = new clsInTrTr_detalleDataSource();

        $this->PageSize = 15;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 15;
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
        
        $this->Recibir = CCGetFromPost("hdRecibir", "");
        
        $this->SorterName = CCGetParam("InTrTr_detalleOrder", "");
        $this->SorterDirection = CCGetParam("InTrTr_detalleDir", "");

        $this->invdetalle_conactivos_gen_TotalRecords = new clsControl(ccsLabel, "invdetalle_conactivos_gen_TotalRecords", "invdetalle_conactivos_gen_TotalRecords", ccsText, "");
        $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "");
        $this->Sorter_det_Secuencia = new clsSorter($this->ComponentName, "Sorter_det_Secuencia", $FileName);
        $this->Sorter_det_CodItem = new clsSorter($this->ComponentName, "Sorter_det_CodItem", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->det_RegNumero = new clsControl(ccsHidden, "det_RegNumero", "ID de Comprobante.", ccsInteger, "");
        $this->hdRegNumero = new clsControl(ccsHidden, "hdRegNumero", "hdRegNumero", ccsText, "");
        $this->det_Secuencia = new clsControl(ccsTextBox, "det_Secuencia", "Secuencia de detalle", ccsInteger, "");
        $this->det_CodItem = new clsControl(ccsTextBox, "det_CodItem", "Codigo de Item", ccsInteger, "");
        $this->act_codanterior = new clsControl(ccsTextBox, "act_codanterior", "Codigo Alterno", ccsText, "");
        $this->det_NumSerie = new clsControl(ccsTextBox, "det_NumSerie", "Num Serie", ccsText, "");
        $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Descripcion de Item", ccsText, "");
        $this->det_CanDespachada = new clsControl(ccsTextBox, "det_CanDespachada", "Cantidad Despachada", ccsFloat, Array(True, 4, ".", ",", False,Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#", "#"), 1, True, ""));
        $this->det_UniMedida = new clsControl(ccsListBox, "det_UniMedida", "Det Uni Medida", ccsText, "");
        $this->det_UniMedida->DSType = dsTable;
        list($this->det_UniMedida->BoundColumn, $this->det_UniMedida->TextColumn, $this->det_UniMedida->DBFormat) = array("uni_CodUnidad", "uni_Abreviatura", "");
        $this->det_UniMedida->ds = new clsDBdatos();
        $this->det_UniMedida->ds->SQL = "SELECT *  " .
"FROM genunmedida";
        $this->det_UniMedida->ds->Order = "uni_Magnitud, uni_Abreviatura";
        $this->det_CantEquivale = new clsControl(ccsTextBox, "det_CantEquivale", "Cantidad Equivalente", ccsFloat, Array(True, 4, ".", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#", "#"), 1, True, ""));
        $this->det_CosUnitario = new clsControl(ccsTextBox, "det_CosUnitario", "Costo Unitario", ccsFloat, Array(True, 6, ".", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#", "#"), 1, True, ""));
        $this->det_CosTotal = new clsControl(ccsTextBox, "det_CosTotal", "Costo Total",          ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#", "#", "#", "0"), Array("0", "0"), 1, True, ""));
        $this->det_ValUnitario = new clsControl(ccsTextBox, "det_ValUnitario", "Valor Unitario", ccsFloat, Array(True, 6, ".", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#", "#"), 1, True, ""));
        $this->det_ValTotal = new clsControl(ccsTextBox, "det_ValTotal", "valor Total",          ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#", "#", "#", "0"), Array("0", "0"), 1, True, ""));
        $this->det_RefOperativa = new clsControl(ccsTextBox, "det_RefOperativa", "Ref Operativa", ccsInteger, "");
        $this->det_Estado = new clsControl(ccsTextBox, "det_Estado", "Estado", ccsInteger, "");
        $this->det_Destino = new clsControl(ccsTextBox, "det_Destino", "Destino", ccsInteger, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->hdMulti = new clsControl(ccsHidden, "hdMulti", "hdMulti", ccsText, "");
        $this->act_UniMedida = new clsControl(ccsHidden, "act_UniMedida", "Ums Abreviatura", ccsText, "");
        $this->hdDivisor = new clsControl(ccsHidden, "hdDivisor", "hdDivisor", ccsText, "");
        $this->lbCosTotal = new clsControl(ccsTextBox, "lbCosTotal", "lbCosTotal", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""));
        $this->lbValTotal = new clsControl(ccsTextBox, "lbValTotal", "lbValTotal", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->btCancelar = new clsButton("btCancelar");
        $this->btRecibir = new clsButton("btRecibir");
        $this->hdRecibir = new clsControl(ccsHidden, "hdRecibir", "hdRecibir", ccsText, "");
    }
//End Class_Initialize Event

//Initialize Method @2-7B32C6DE
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlcom_RegNumero"] = CCGetFromGet("com_RegNumero", "");
    }
//End Initialize Method

//GetFormParameters Method @2-47DD5555
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["det_RegNumero"][$RowNumber] = CCGetFromPost("det_RegNumero_" . $RowNumber);
            $this->FormParameters["hdRegNumero"][$RowNumber] = CCGetFromPost("hdRegNumero_" . $RowNumber);
            $this->FormParameters["det_Secuencia"][$RowNumber] = CCGetFromPost("det_Secuencia_" . $RowNumber);
            $this->FormParameters["det_CodItem"][$RowNumber] = CCGetFromPost("det_CodItem_" . $RowNumber);
            $this->FormParameters["act_codanterior"][$RowNumber] = CCGetFromPost("act_codanterior_" . $RowNumber);
            $this->FormParameters["det_NumSerie"][$RowNumber] = CCGetFromPost("det_NumSerie_" . $RowNumber);
            $this->FormParameters["act_Descripcion"][$RowNumber] = CCGetFromPost("act_Descripcion_" . $RowNumber);
            $this->FormParameters["det_CanDespachada"][$RowNumber] = CCGetFromPost("det_CanDespachada_" . $RowNumber);
            $this->FormParameters["det_UniMedida"][$RowNumber] = CCGetFromPost("det_UniMedida_" . $RowNumber);
            $this->FormParameters["det_CantEquivale"][$RowNumber] = CCGetFromPost("det_CantEquivale_" . $RowNumber);
            $this->FormParameters["det_CosUnitario"][$RowNumber] = CCGetFromPost("det_CosUnitario_" . $RowNumber);
            $this->FormParameters["det_CosTotal"][$RowNumber] = CCGetFromPost("det_CosTotal_" . $RowNumber);
            $this->FormParameters["det_ValUnitario"][$RowNumber] = CCGetFromPost("det_ValUnitario_" . $RowNumber);
            $this->FormParameters["det_ValTotal"][$RowNumber] = CCGetFromPost("det_ValTotal_" . $RowNumber);
            $this->FormParameters["det_RefOperativa"][$RowNumber] = CCGetFromPost("det_RefOperativa_" . $RowNumber);
            $this->FormParameters["det_Estado"][$RowNumber] = CCGetFromPost("det_Estado_" . $RowNumber);
            $this->FormParameters["det_Destino"][$RowNumber] = CCGetFromPost("det_Destino_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
            $this->FormParameters["hdMulti"][$RowNumber] = CCGetFromPost("hdMulti_" . $RowNumber);
            $this->FormParameters["act_UniMedida"][$RowNumber] = CCGetFromPost("act_UniMedida_" . $RowNumber);
            $this->FormParameters["hdDivisor"][$RowNumber] = CCGetFromPost("hdDivisor_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-846815C6
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["det_Secuencia"] = $this->CachedColumns["det_Secuencia"][$RowNumber];
            $this->det_RegNumero->SetText($this->FormParameters["det_RegNumero"][$RowNumber], $RowNumber);
            $this->hdRegNumero->SetText($this->FormParameters["hdRegNumero"][$RowNumber], $RowNumber);
            $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
            $this->det_CodItem->SetText($this->FormParameters["det_CodItem"][$RowNumber], $RowNumber);
            $this->act_codanterior->SetText($this->FormParameters["act_codanterior"][$RowNumber], $RowNumber);
            $this->det_NumSerie->SetText($this->FormParameters["det_NumSerie"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->det_CanDespachada->SetText($this->FormParameters["det_CanDespachada"][$RowNumber], $RowNumber);
            $this->det_UniMedida->SetText($this->FormParameters["det_UniMedida"][$RowNumber], $RowNumber);
            $this->det_CantEquivale->SetText($this->FormParameters["det_CantEquivale"][$RowNumber], $RowNumber);
            $this->det_CosUnitario->SetText($this->FormParameters["det_CosUnitario"][$RowNumber], $RowNumber);
            $this->det_CosTotal->SetText($this->FormParameters["det_CosTotal"][$RowNumber], $RowNumber);
            $this->det_ValUnitario->SetText($this->FormParameters["det_ValUnitario"][$RowNumber], $RowNumber);
            $this->det_ValTotal->SetText($this->FormParameters["det_ValTotal"][$RowNumber], $RowNumber);
            $this->det_RefOperativa->SetText($this->FormParameters["det_RefOperativa"][$RowNumber], $RowNumber);
            $this->det_Estado->SetText($this->FormParameters["det_Estado"][$RowNumber], $RowNumber);
            $this->det_Destino->SetText($this->FormParameters["det_Destino"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            $this->hdMulti->SetText($this->FormParameters["hdMulti"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->hdDivisor->SetText($this->FormParameters["hdDivisor"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-8BCF26FA
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->det_RegNumero->Validate() && $Validation);
        $Validation = ($this->hdRegNumero->Validate() && $Validation);
        $Validation = ($this->det_Secuencia->Validate() && $Validation);
        $Validation = ($this->det_CodItem->Validate() && $Validation);
        $Validation = ($this->act_codanterior->Validate() && $Validation);
        $Validation = ($this->det_NumSerie->Validate() && $Validation);
        $Validation = ($this->act_Descripcion->Validate() && $Validation);
        $Validation = ($this->det_CanDespachada->Validate() && $Validation);
        $Validation = ($this->det_UniMedida->Validate() && $Validation);
        $Validation = ($this->det_CantEquivale->Validate() && $Validation);
        $Validation = ($this->det_CosUnitario->Validate() && $Validation);
        $Validation = ($this->det_CosTotal->Validate() && $Validation);
        $Validation = ($this->det_ValUnitario->Validate() && $Validation);
        $Validation = ($this->det_ValTotal->Validate() && $Validation);
        $Validation = ($this->det_RefOperativa->Validate() && $Validation);
        $Validation = ($this->det_Estado->Validate() && $Validation);
        $Validation = ($this->det_Destino->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $Validation = ($this->hdMulti->Validate() && $Validation);
        $Validation = ($this->act_UniMedida->Validate() && $Validation);
        $Validation = ($this->hdDivisor->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->det_RegNumero->Errors->ToString();
            $errors .= $this->hdRegNumero->Errors->ToString();
            $errors .= $this->det_Secuencia->Errors->ToString();
            $errors .= $this->det_CodItem->Errors->ToString();
            $errors .= $this->act_codanterior->Errors->ToString();
            $errors .= $this->det_NumSerie->Errors->ToString();
            $errors .= $this->act_Descripcion->Errors->ToString();
            $errors .= $this->det_CanDespachada->Errors->ToString();
            $errors .= $this->det_UniMedida->Errors->ToString();
            $errors .= $this->det_CantEquivale->Errors->ToString();
            $errors .= $this->det_CosUnitario->Errors->ToString();
            $errors .= $this->det_CosTotal->Errors->ToString();
            $errors .= $this->det_ValUnitario->Errors->ToString();
            $errors .= $this->det_ValTotal->Errors->ToString();
            $errors .= $this->det_RefOperativa->Errors->ToString();
            $errors .= $this->det_Estado->Errors->ToString();
            $errors .= $this->det_Destino->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $errors .= $this->hdMulti->Errors->ToString();
            $errors .= $this->act_UniMedida->Errors->ToString();
            $errors .= $this->hdDivisor->Errors->ToString();
            $this->det_RegNumero->Errors->Clear();
            $this->hdRegNumero->Errors->Clear();
            $this->det_Secuencia->Errors->Clear();
            $this->det_CodItem->Errors->Clear();
            $this->act_codanterior->Errors->Clear();
            $this->det_NumSerie->Errors->Clear();
            $this->act_Descripcion->Errors->Clear();
            $this->det_CanDespachada->Errors->Clear();
            $this->det_UniMedida->Errors->Clear();
            $this->det_CantEquivale->Errors->Clear();
            $this->det_CosUnitario->Errors->Clear();
            $this->det_CosTotal->Errors->Clear();
            $this->det_ValUnitario->Errors->Clear();
            $this->det_ValTotal->Errors->Clear();
            $this->det_RefOperativa->Errors->Clear();
            $this->det_Estado->Errors->Clear();
            $this->det_Destino->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
            $this->hdMulti->Errors->Clear();
            $this->act_UniMedida->Errors->Clear();
            $this->hdDivisor->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @2-8C37EBF2
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["det_RegNumero"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["hdRegNumero"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_CodItem"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_codanterior"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_NumSerie"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_CanDespachada"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_UniMedida"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_CantEquivale"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_CosUnitario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_CosTotal"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_ValUnitario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_ValTotal"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_RefOperativa"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_Estado"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_Destino"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["hdMulti"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_UniMedida"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["hdDivisor"][$RowNumber]));
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

//Operation Method @2-A0BDB421
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
        } else if(strlen(CCGetParam("btCancelar", ""))) {
            $this->PressedButton = "btCancelar";
        } else if(strlen(CCGetParam("btRecibir", ""))) {
            $this->PressedButton = "btRecibir";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));        
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btCancelar") {
            if(!CCGetEvent($this->btCancelar->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btRecibir") {
            if(!CCGetEvent($this->btRecibir->CCSEvents, "OnClick")|| !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @2-D6A4F89E
    function UpdateGrid()
    {   
//      fah --->
        global $DBdatos;
        global $gfValTotal;
        global $gfCosTotal;
        global $gfCanTotal;
        $gfValTotal = 0;
        $gfCosTotal = 0;
        $gfCanTotal = 0;
//      <--- fah
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {            
            $this->ds->CachedColumns["det_Secuencia"] = $this->CachedColumns["det_Secuencia"][$RowNumber];
            $this->det_RegNumero->SetText($this->FormParameters["det_RegNumero"][$RowNumber], $RowNumber);
            $this->hdRegNumero->SetText($this->FormParameters["hdRegNumero"][$RowNumber], $RowNumber);
            $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
            $this->det_CodItem->SetText($this->FormParameters["det_CodItem"][$RowNumber], $RowNumber);
            $this->act_codanterior->SetText($this->FormParameters["act_codanterior"][$RowNumber], $RowNumber);
            $this->det_NumSerie->SetText($this->FormParameters["det_NumSerie"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->det_CanDespachada->SetText($this->FormParameters["det_CanDespachada"][$RowNumber], $RowNumber);
            $this->det_UniMedida->SetText($this->FormParameters["det_UniMedida"][$RowNumber], $RowNumber);
            $this->det_CantEquivale->SetText($this->FormParameters["det_CantEquivale"][$RowNumber], $RowNumber);
            $this->det_CosUnitario->SetText($this->FormParameters["det_CosUnitario"][$RowNumber], $RowNumber);
            $this->det_CosTotal->SetText($this->FormParameters["det_CosTotal"][$RowNumber], $RowNumber);
            $this->det_ValUnitario->SetText($this->FormParameters["det_ValUnitario"][$RowNumber], $RowNumber);
            $this->det_ValTotal->SetText($this->FormParameters["det_ValTotal"][$RowNumber], $RowNumber);
            $this->det_RefOperativa->SetText($this->FormParameters["det_RefOperativa"][$RowNumber], $RowNumber);
            $this->det_Estado->SetText($this->FormParameters["det_Estado"][$RowNumber], $RowNumber);
            $this->det_Destino->SetText($this->FormParameters["det_Destino"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            $this->hdMulti->SetText($this->FormParameters["hdMulti"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->hdDivisor->SetText($this->FormParameters["hdDivisor"][$RowNumber], $RowNumber);
//      fah --->
            $gfValTotal += $this->FormParameters["det_ValTotal"][$RowNumber];
            $gfCosTotal += $this->FormParameters["det_CosTotal"][$RowNumber];
            $gfCanTotal += $this->FormParameters["det_CantEquivale"][$RowNumber];
//      <--- fah            
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
        
    
        
        
        
//die ();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0);

    }
//End UpdateGrid Method

//InsertRow Method @2-23B3D346
    function InsertRow($RowNumber)
    {  
        if(!$this->InsertAllowed) return false;       
        $this->ds->det_Secuencia->SetValue($this->det_Secuencia->GetValue());        
        $this->ds->det_CodItem->SetValue($this->det_CodItem->GetValue());        
        $this->ds->act_codanterior->SetValue($this->act_codanterior->GetValue());        
        $this->ds->det_NumSerie->SetValue($this->det_NumSerie->GetValue());        
        $this->ds->det_CanDespachada->SetValue($this->det_CanDespachada->GetValue());
        $this->ds->det_UniMedida->SetValue($this->det_UniMedida->GetValue());
        $this->ds->det_CantEquivale->SetValue($this->det_CantEquivale->GetValue());
        $this->ds->det_CosTotal->SetValue($this->det_CosTotal->GetValue());
        $this->ds->det_ValTotal->SetValue($this->det_ValTotal->GetValue());
        $this->ds->det_RefOperativa->SetValue($this->det_RefOperativa->GetValue());
        $this->ds->det_Estado->SetValue($this->det_Estado->GetValue());
        $this->ds->det_CosUnitario->SetValue($this->det_CosUnitario->GetValue());
        $this->ds->det_ValUnitario->SetValue($this->det_ValUnitario->GetValue());
        $this->ds->det_Destino->SetValue($this->det_Destino->GetValue());
        
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

//UpdateRow Method @2-CD1613C3
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->det_CodItem->SetValue($this->det_CodItem->GetValue());
        $this->ds->act_codanterior->SetValue($this->act_codanterior->GetValue());
        $this->ds->det_NumSerie->SetValue($this->det_NumSerie->GetValue());
        $this->ds->det_CanDespachada->SetValue($this->det_CanDespachada->GetValue());
        $this->ds->det_UniMedida->SetValue($this->det_UniMedida->GetValue());
        $this->ds->det_CantEquivale->SetValue($this->det_CantEquivale->GetValue());
        $this->ds->det_CosTotal->SetValue($this->det_CosTotal->GetValue());
        $this->ds->det_ValTotal->SetValue($this->det_ValTotal->GetValue());
        $this->ds->det_RefOperativa->SetValue($this->det_RefOperativa->GetValue());
        $this->ds->det_Estado->SetValue($this->det_Estado->GetValue());
        $this->ds->det_CosUnitario->SetValue($this->det_CosUnitario->GetValue());
        $this->ds->det_ValUnitario->SetValue($this->det_ValUnitario->GetValue());
        $this->ds->det_Destino->SetValue($this->det_Destino->GetValue());
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

//FormScript Method @2-D6BD4AB0
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var InTrTr_detalleElements;\n";
        $script .= "var InTrTr_detalleEmptyRows = 10;\n";
        $script .= "var " . $this->ComponentName . "det_RegNumeroID = 0;\n";
        $script .= "var " . $this->ComponentName . "hdRegNumeroID = 1;\n";
        $script .= "var " . $this->ComponentName . "det_SecuenciaID = 2;\n";
        $script .= "var " . $this->ComponentName . "det_CodItemID = 3;\n";
        $script .= "var " . $this->ComponentName . "act_codanteriorID = 4;\n";
        $script .= "var " . $this->ComponentName . "act_DescripcionID = 5;\n";
        $script .= "var " . $this->ComponentName . "det_NumSerieID = 6;\n";
        $script .= "var " . $this->ComponentName . "det_CanDespachadaID = 7;\n";
        $script .= "var " . $this->ComponentName . "det_UniMedidaID = 8;\n";
        $script .= "var " . $this->ComponentName . "det_CantEquivaleID = 9;\n";
        $script .= "var " . $this->ComponentName . "det_CosUnitarioID = 10;\n";
        $script .= "var " . $this->ComponentName . "det_CosTotalID = 11;\n";
        $script .= "var " . $this->ComponentName . "det_ValUnitarioID = 12;\n";
        $script .= "var " . $this->ComponentName . "det_ValTotalID = 13;\n";
        $script .= "var " . $this->ComponentName . "det_RefOperativaID = 14;\n";
        $script .= "var " . $this->ComponentName . "det_EstadoID = 15;\n";
        $script .= "var " . $this->ComponentName . "det_DestinoID = 16;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 17;\n";
        $script .= "var " . $this->ComponentName . "hdMultiID = 18;\n";
        $script .= "var " . $this->ComponentName . "act_UniMedidaID = 19;\n";
        $script .= "var " . $this->ComponentName . "hdDivisorID = 20;\n";
        $script .= "\nfunction initInTrTr_detalleElements() {\n";
        $script .= "\tvar ED = document.forms[\"InTrTr_detalle\"];\n";
        $script .= "\tInTrTr_detalleElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.det_RegNumero_" . $i . ", " . "ED.hdRegNumero_" . $i . ", " . "ED.det_Secuencia_" . $i . ", " . "ED.det_CodItem_" . $i . ", " . "ED.act_Descripcion_" . $i . ", " . "ED.det_CanDespachada_" . $i . ", " . "ED.det_UniMedida_" . $i . ", " . "ED.det_CantEquivale_" . $i . ", " . "ED.det_CosUnitario_" . $i . ", " . "ED.det_CosTotal_" . $i . ", " . "ED.det_ValUnitario_" . $i . ", " . "ED.det_ValTotal_" . $i . ", " . "ED.det_RefOperativa_" . $i . ", " . "ED.det_Estado_" . $i . ", " . "ED.det_Destino_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ", " . "ED.hdMulti_" . $i . ", " . "ED.act_UniMedida_" . $i . ", " . "ED.hdDivisor_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-01736EBB
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 1)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["det_Secuencia"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["det_Secuencia"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-13514F69
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["det_Secuencia"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-B83D55BD
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->det_UniMedida->Prepare();

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
        // fah_mod: para hacer que se agregue records solo al final
            if ($this->ds->AbsolutePage < $this->ds->PageCount()) $EmptyRowsLeft = 0;
            else {
                if ($this->ds->RecordsCount > $this->PageSize) $resto = fmod($this->ds->RecordsCount, $this->PageSize);
                else $resto = $this->ds->RecordsCount;
                $EmptyRowsLeft = $this->PageSize - $resto;
            }
            if ($EmptyRowsLeft <= 0 && $this->ds->RecordsCount == ($this->PageSize * $this->ds->AbsolutePage)) $EmptyRowsLeft =1;
        // end fah mod
        //----------  Modificacion para Habilitar / deshabilitar Botones Segun perfil del usuario
        $aOpc = array();
        global $DBdatos;
        if ($this->InsertAllowed) $aOpc[]="ADD";
        if ($this->UpdateAllowed) $aOpc[]="UPD";
        if ($this->DeleteAllowed) $aOpc[]="DEL";
        $ilEstado = CCGetDBValue("SELECT per_Estado FROM concomprobantes JOIN conperiodos ON per_Aplicacion = 'CO' AND per_numperiodo = com_numperiodo WHERE com_RegNumero =" . CCGetParam('com_RegNumero', -1), $DBdatos);
        fHabilitaBotonesCCS(false, $aOpc, $ilEstado );
        if ($ilEstado < 1 ) $Tpl->SetVar('lbEstado', ' **Solo para Consulta**');
        //----------
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
                        $this->CachedColumns["det_Secuencia"][$RowNumber] = $this->ds->CachedColumns["det_Secuencia"];
                        $this->det_RegNumero->SetValue($this->ds->det_RegNumero->GetValue());
                        $this->hdRegNumero->SetValue($this->ds->hdRegNumero->GetValue());
                        $this->det_Secuencia->SetValue($this->ds->det_Secuencia->GetValue());
                        $this->det_CodItem->SetValue($this->ds->det_CodItem->GetValue());
                        $this->act_codanterior->SetValue($this->ds->act_codanterior->GetValue());
                        $this->det_NumSerie->SetValue($this->ds->det_NumSerie->GetValue());
                        $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                        $this->det_CanDespachada->SetValue($this->ds->det_CanDespachada->GetValue());
                        $this->det_UniMedida->SetValue($this->ds->det_UniMedida->GetValue());
                        $this->det_CantEquivale->SetValue($this->ds->det_CantEquivale->GetValue());
                        $this->det_CosUnitario->SetValue($this->ds->det_CosUnitario->GetValue());
                        $this->det_CosTotal->SetValue($this->ds->det_CosTotal->GetValue());
                        $this->det_ValUnitario->SetValue($this->ds->det_ValUnitario->GetValue());
                        $this->det_ValTotal->SetValue($this->ds->det_ValTotal->GetValue());
                        $this->det_RefOperativa->SetValue($this->ds->det_RefOperativa->GetValue());
                        $this->det_Estado->SetValue($this->ds->det_Estado->GetValue());
                        $this->det_Destino->SetValue($this->ds->det_Destino->GetValue());
                        $this->hdMulti->SetValue($this->ds->hdMulti->GetValue());
                        $this->act_UniMedida->SetValue($this->ds->act_UniMedida->GetValue());
                        $this->hdDivisor->SetValue($this->ds->hdDivisor->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["det_Secuencia"][$RowNumber] = "";
                        $this->det_RegNumero->SetText("");
                        $this->hdRegNumero->SetText("");
                        $this->det_Secuencia->SetText("");
                        $this->det_CodItem->SetText("");
                        $this->act_codanterior->SetText("");
                        $this->det_NumSerie->SetText("");
                        $this->act_Descripcion->SetText("");
                        $this->det_CanDespachada->SetText("");
                        $this->det_UniMedida->SetText("");
                        $this->det_CantEquivale->SetText("");
                        $this->det_CosUnitario->SetText("");
                        $this->det_CosTotal->SetText("");
                        $this->det_ValUnitario->SetText("");
                        $this->det_ValTotal->SetText("");
                        $this->det_RefOperativa->SetText("");
                        $this->det_Estado->SetText("");
                        $this->det_Destino->SetText("");
                        $this->CheckBox_Delete->SetText("");
                        $this->hdMulti->SetText("");
                        $this->act_UniMedida->SetText("");
                        $this->hdDivisor->SetText("");
                    } else {
                        $this->det_RegNumero->SetText($this->FormParameters["det_RegNumero"][$RowNumber], $RowNumber);
                        $this->hdRegNumero->SetText($this->FormParameters["hdRegNumero"][$RowNumber], $RowNumber);
                        $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
                        $this->det_CodItem->SetText($this->FormParameters["det_CodItem"][$RowNumber], $RowNumber);
                        $this->act_codanterior->SetText($this->FormParameters["act_codanterior"][$RowNumber], $RowNumber);
                        $this->det_NumSerie->SetText($this->FormParameters["det_NumSerie"][$RowNumber], $RowNumber);
                        $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
                        $this->det_CanDespachada->SetText($this->FormParameters["det_CanDespachada"][$RowNumber], $RowNumber);
                        $this->det_UniMedida->SetText($this->FormParameters["det_UniMedida"][$RowNumber], $RowNumber);
                        $this->det_CantEquivale->SetText($this->FormParameters["det_CantEquivale"][$RowNumber], $RowNumber);
                        $this->det_CosUnitario->SetText($this->FormParameters["det_CosUnitario"][$RowNumber], $RowNumber);
                        $this->det_CosTotal->SetText($this->FormParameters["det_CosTotal"][$RowNumber], $RowNumber);
                        $this->det_ValUnitario->SetText($this->FormParameters["det_ValUnitario"][$RowNumber], $RowNumber);
                        $this->det_ValTotal->SetText($this->FormParameters["det_ValTotal"][$RowNumber], $RowNumber);
                        $this->det_RefOperativa->SetText($this->FormParameters["det_RefOperativa"][$RowNumber], $RowNumber);
                        $this->det_Estado->SetText($this->FormParameters["det_Estado"][$RowNumber], $RowNumber);
                        $this->det_Destino->SetText($this->FormParameters["det_Destino"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                        $this->hdMulti->SetText($this->FormParameters["hdMulti"][$RowNumber], $RowNumber);
                        $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
                        $this->hdDivisor->SetText($this->FormParameters["hdDivisor"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->det_RegNumero->Show($RowNumber);
                    $this->hdRegNumero->Show($RowNumber);
                    $this->det_Secuencia->Show($RowNumber);
                    $this->det_CodItem->Show($RowNumber);
                    $this->act_codanterior->Show($RowNumber);
                    $this->det_NumSerie->Show($RowNumber);
                    $this->act_Descripcion->Show($RowNumber);
                    $this->det_CanDespachada->Show($RowNumber);
                    $this->det_UniMedida->Show($RowNumber);
                    $this->det_CantEquivale->Show($RowNumber);
                    $this->det_CosUnitario->Show($RowNumber);
                    $this->det_CosTotal->Show($RowNumber);
                    $this->det_ValUnitario->Show($RowNumber);
                    $this->det_ValTotal->Show($RowNumber);
                    $this->det_RefOperativa->Show($RowNumber);
                    $this->det_Estado->Show($RowNumber);
                    $this->det_Destino->Show($RowNumber);
                    $this->CheckBox_Delete->Show($RowNumber);
                    $this->hdMulti->Show($RowNumber);
                    $this->act_UniMedida->Show($RowNumber);
                    $this->hdDivisor->Show($RowNumber);
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
        if(!is_array($this->lbCosTotal->Value) && !strlen($this->lbCosTotal->Value) && $this->lbCosTotal->Value !== false)
        $this->lbCosTotal->SetText(0);
        if(!is_array($this->lbValTotal->Value) && !strlen($this->lbValTotal->Value) && $this->lbValTotal->Value !== false)
        $this->lbValTotal->SetText(0);
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->invdetalle_conactivos_gen_TotalRecords->Show();
        $this->lbTitulo->Show();
        $this->Sorter_det_Secuencia->Show();
        $this->Sorter_det_CodItem->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->lbCosTotal->Show();
        $this->lbValTotal->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->btCancelar->Show();
        $this->btRecibir->Show();

        if($this->CheckErrors()) {
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->ComponentName;
        if($this->FormSubmitted || CCGetFromGet("ccsForm")) {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        } else {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("All", ""), "ccsForm", $CCSForm);
        }
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

} //End InTrTr_detalle Class @2-FCB6E20C

class clsInTrTr_detalleDataSource extends clsDBdatos {  //InTrTr_detalleDataSource Class @2-DF9A07E6

//DataSource Variables @2-2889897C
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
    var $det_RegNumero;
    var $hdRegNumero;
    var $det_Secuencia;
    var $det_CodItem;
    var $act_codanterior;
    var $det_NumSerie;
    var $act_Descripcion;
    var $det_CanDespachada;
    var $det_UniMedida;
    var $det_CantEquivale;
    var $det_CosUnitario;
    var $det_CosTotal;
    var $det_ValUnitario;
    var $det_ValTotal;
    var $det_RefOperativa;
    var $det_Estado;
    var $det_Destino;
    var $CheckBox_Delete;
    var $hdMulti;
    var $act_UniMedida;
    var $hdDivisor;
    
    
//End DataSource Variables

//Class_Initialize Event @2-0F8D9C7B
    function clsInTrTr_detalleDataSource()
    {
        $this->ErrorBlock = "EditableGrid InTrTr_detalle/Error";
        $this->Initialize();
        $this->det_RegNumero = new clsField("det_RegNumero", ccsInteger, "");
        $this->hdRegNumero = new clsField("hdRegNumero", ccsText, "");
        $this->det_Secuencia = new clsField("det_Secuencia", ccsInteger, "");
        $this->det_CodItem = new clsField("det_CodItem", ccsInteger, "");
        $this->act_codanterior = new clsField("act_codanterior", ccsText, "");
        $this->det_NumSerie = new clsField("det_NumSerie", ccsText, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->det_CanDespachada = new clsField("det_CanDespachada", ccsFloat, "");
        $this->det_UniMedida = new clsField("det_UniMedida", ccsText, "");
        $this->det_CantEquivale = new clsField("det_CantEquivale", ccsFloat, "");
        $this->det_CosUnitario = new clsField("det_CosUnitario", ccsFloat, "");
        $this->det_CosTotal = new clsField("det_CosTotal", ccsFloat, "");
        $this->det_ValUnitario = new clsField("det_ValUnitario", ccsFloat, "");
        $this->det_ValTotal = new clsField("det_ValTotal", ccsFloat, "");
        $this->det_RefOperativa = new clsField("det_RefOperativa", ccsInteger, "");
        $this->det_Estado = new clsField("det_Estado", ccsInteger, "");
        $this->det_Destino = new clsField("det_Destino", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));
        $this->hdMulti = new clsField("hdMulti", ccsText, "");
        $this->act_UniMedida = new clsField("act_UniMedida", ccsText, "");
        $this->hdDivisor = new clsField("hdDivisor", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-F2BE1C38
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "det_Secuencia";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_det_Secuencia" => array("det_Secuencia", ""), 
            "Sorter_det_CodItem" => array("det_CodItem", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @2-6AFBE1DD
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcom_RegNumero", ccsText, "", "", $this->Parameters["urlcom_RegNumero"], -1, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @2-B4A291E1
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM ( ((((concomprobantes   join  invdetalle on invdetalle.det_RegNumero = concomprobantes.com_RegNumero)  " .
        "             left join  conactivos  on conactivos.act_codauxiliar =  invdetalle.det_coditem) " .
        "             left join genunmedida umdet on umdet.uni_codunidad =  invdetalle.det_Unimedida) " .
        "             left join genunmedida umite on umite.uni_codunidad =  invdetalle.det_Unimedida) ) " .
        "             left join gentabconvers on (gentabconvers.tab_desde = invdetalle.det_Unimedida and  " .
        "                                         gentabconvers.tab_hasta = conactivos.act_Unimedida) " .
        "where concomprobantes.com_RegNumero = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . " " .
        "";
        $this->SQL = "SELECT com_RegNumero, com_NumPeriodo, invdetalle.*,  det_cosunitario AS det_CosUnitario,
                det_valUnitario as det_ValUnitario, concat(act_Descripcion,' ', act_descripcion1) as act_descripcion, " .
        "       act_UniMedida, umite.uni_descripcion, umdet.uni_descripcion, " .
        "       tab_multiplicador, tab_divisor, det_CodAnterior as act_codanterior, det_NumSerie " .
        "FROM ( ((((concomprobantes   join  invdetalle on invdetalle.det_RegNumero = concomprobantes.com_RegNumero)  " .
        "             left join  conactivos  on conactivos.act_codauxiliar =  invdetalle.det_coditem) " .
        "             left join genunmedida umdet on umdet.uni_codunidad =  invdetalle.det_Unimedida) " .
        "             left join genunmedida umite on umite.uni_codunidad =  invdetalle.det_Unimedida) ) " .
        "             left join gentabconvers on (gentabconvers.tab_desde = invdetalle.det_Unimedida and  " .
        "                                         gentabconvers.tab_hasta = conactivos.act_Unimedida) " .
        "where concomprobantes.com_RegNumero = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . " " .
        "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-4F293BAD
    function SetValues()
    {
        $this->CachedColumns["det_Secuencia"] = $this->f("det_Secuencia");
        $this->det_RegNumero->SetDBValue(trim($this->f("det_RegNUmero")));
        $this->hdRegNumero->SetDBValue($this->f("com_RegNumero"));
        $this->det_Secuencia->SetDBValue(trim($this->f("det_Secuencia")));
        $this->det_CodItem->SetDBValue(trim($this->f("det_CodItem")));
        $this->act_codanterior->SetDBValue(trim($this->f("act_codanterior")));
        $this->det_NumSerie->SetDBValue(trim($this->f("det_NumSerie")));
        $this->act_Descripcion->SetDBValue($this->f("act_descripcion"));
        $this->det_CanDespachada->SetDBValue(trim($this->f("det_CanDespachada")));
        $this->det_UniMedida->SetDBValue($this->f("det_UniMedida"));
        $this->det_CantEquivale->SetDBValue(trim($this->f("det_CantEquivale")));
        $this->det_CosUnitario->SetDBValue(trim($this->f("det_CosUnitario")));
        $this->det_CosTotal->SetDBValue(trim($this->f("det_CosTotal")));
        $this->det_ValUnitario->SetDBValue(trim($this->f("det_ValUnitario")));
        $this->det_ValTotal->SetDBValue(trim($this->f("det_ValTotal")));
        $this->det_RefOperativa->SetDBValue(trim($this->f("det_RefOperativa")));
        $this->det_Estado->SetDBValue(trim($this->f("det_Estado")));
        $this->det_Destino->SetDBValue(trim($this->f("det_Destino")));
        $this->hdMulti->SetDBValue($this->f("tab_multiplicador"));
        $this->act_UniMedida->SetDBValue($this->f("act_UniMedida"));
        $this->hdDivisor->SetDBValue($this->f("tab_divisor"));
    }
//End SetValues Method

//Insert Method @2-756EEC02
    function Insert()
    {
        $this->cp["det_RegNumero"] = new clsSQLParameter("urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", ""), -1, false, $this->ErrorBlock);
        $this->cp["det_Secuencia"] = new clsSQLParameter("ctrldet_Secuencia", ccsInteger, "", "", $this->det_Secuencia->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CodItem"] = new clsSQLParameter("ctrldet_CodItem", ccsInteger, "", "", $this->det_CodItem->GetValue(), 0, false, $this->ErrorBlock);        
        $this->cp["det_NumSerie"] = new clsSQLParameter("ctrldet_NumSerie", ccsText, "", "", $this->det_NumSerie->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CanDespachada"] = new clsSQLParameter("ctrldet_CanDespachada", ccsFloat, "", "", $this->det_CanDespachada->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_UniMedida"] = new clsSQLParameter("ctrldet_UniMedida", ccsText, "", "", $this->det_UniMedida->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CantEquivale"] = new clsSQLParameter("ctrldet_CantEquivale", ccsFloat, "", "", $this->det_CantEquivale->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CosTotal"] = new clsSQLParameter("ctrldet_CosTotal", ccsFloat, "", "", $this->det_CosTotal->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_ValTotal"] = new clsSQLParameter("ctrldet_ValTotal", ccsFloat, "", "", $this->det_ValTotal->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_RefOperativa"] = new clsSQLParameter("ctrldet_RefOperativa", ccsInteger, "", "", $this->det_RefOperativa->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_Estado"] = new clsSQLParameter("ctrldet_Estado", ccsInteger, "", "", $this->det_Estado->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CosUnitario"] = new clsSQLParameter("ctrldet_CosUnitario", ccsFloat, "", "", $this->det_CosUnitario->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_ValUnitario"] = new clsSQLParameter("ctrldet_ValUnitario", ccsFloat, "", "", $this->det_ValUnitario->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_Destino"] = new clsSQLParameter("ctrldet_Destino", ccsInteger, "", "", $this->det_Destino->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CodAnterior"] = new clsSQLParameter("ctrldet_CodAnterior", ccsText, "", "", $this->act_codanterior->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO invdetalle ("
             . "det_RegNumero, "
             . "det_Secuencia, "
             . "det_CodItem, "
             . "det_CanDespachada, "
             . "det_UniMedida, "
             . "det_CantEquivale, "
             . "det_CosTotal, "
             . "det_ValTotal, "
             . "det_RefOperativa, "
             . "det_Estado, "
             . "det_CosUnitario, "
             . "det_ValUnitario, "
             . "det_Destino, "
             . "det_CodAnterior, "
             . "det_NumSerie"
             . ") VALUES ("
             . $this->ToSQL($this->cp["det_RegNumero"]->GetDBValue(), $this->cp["det_RegNumero"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_Secuencia"]->GetDBValue(), $this->cp["det_Secuencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_CodItem"]->GetDBValue(), $this->cp["det_CodItem"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_CanDespachada"]->GetDBValue(), $this->cp["det_CanDespachada"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_UniMedida"]->GetDBValue(), $this->cp["det_UniMedida"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_CantEquivale"]->GetDBValue(), $this->cp["det_CantEquivale"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_CosTotal"]->GetDBValue(), $this->cp["det_CosTotal"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_ValTotal"]->GetDBValue(), $this->cp["det_ValTotal"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_RefOperativa"]->GetDBValue(), $this->cp["det_RefOperativa"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_Estado"]->GetDBValue(), $this->cp["det_Estado"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_CosUnitario"]->GetDBValue(), $this->cp["det_CosUnitario"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_ValUnitario"]->GetDBValue(), $this->cp["det_ValUnitario"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_Destino"]->GetDBValue(), $this->cp["det_Destino"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_CodAnterior"]->GetDBValue(), $this->cp["det_CodAnterior"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_NumSerie"]->GetDBValue(), $this->cp["det_NumSerie"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-6DC39C2E
    function Update()
    {
        $this->cp["det_CodItem"] = new clsSQLParameter("ctrldet_CodItem", ccsInteger, "", "", $this->det_CodItem->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_CanDespachada"] = new clsSQLParameter("ctrldet_CanDespachada", ccsFloat, "", "", $this->det_CanDespachada->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_UniMedida"] = new clsSQLParameter("ctrldet_UniMedida", ccsText, "", "", $this->det_UniMedida->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_CantEquivale"] = new clsSQLParameter("ctrldet_CantEquivale", ccsFloat, "", "", $this->det_CantEquivale->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_CosTotal"] = new clsSQLParameter("ctrldet_CosTotal", ccsFloat, "", "", $this->det_CosTotal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_ValTotal"] = new clsSQLParameter("ctrldet_ValTotal", ccsFloat, "", "", $this->det_ValTotal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_RefOperativa"] = new clsSQLParameter("ctrldet_RefOperativa", ccsInteger, "", "", $this->det_RefOperativa->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_Estado"] = new clsSQLParameter("ctrldet_Estado", ccsInteger, "", "", $this->det_Estado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_CosUnitario"] = new clsSQLParameter("ctrldet_CosUnitario", ccsFloat, "", "", $this->det_CosUnitario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_ValUnitario"] = new clsSQLParameter("ctrldet_ValUnitario", ccsFloat, "", "", $this->det_ValUnitario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_Destino"] = new clsSQLParameter("ctrldet_Destino", ccsInteger, "", "", $this->det_Destino->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_NumSerie"] = new clsSQLParameter("det_NumSerie", ccsText, "", "", $this->det_NumSerie->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_CodAnterior"] = new clsSQLParameter("det_CodAnterior", ccsText, "", "", $this->act_codanterior->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", ""), "", true);
        $wp->AddParameter("2", "dsdet_Secuencia", ccsInteger, "", "", $this->CachedColumns["det_Secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "det_RegNUmero", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "det_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE invdetalle SET "
             . "det_CodItem=" . $this->ToSQL($this->cp["det_CodItem"]->GetDBValue(), $this->cp["det_CodItem"]->DataType) . ", "
             . "det_CanDespachada=" . $this->ToSQL($this->cp["det_CanDespachada"]->GetDBValue(), $this->cp["det_CanDespachada"]->DataType) . ", "
             . "det_UniMedida=" . $this->ToSQL($this->cp["det_UniMedida"]->GetDBValue(), $this->cp["det_UniMedida"]->DataType) . ", "
             . "det_CantEquivale=" . $this->ToSQL($this->cp["det_CantEquivale"]->GetDBValue(), $this->cp["det_CantEquivale"]->DataType) . ", "
             . "det_CosTotal=" . $this->ToSQL($this->cp["det_CosTotal"]->GetDBValue(), $this->cp["det_CosTotal"]->DataType) . ", "
             . "det_ValTotal=" . $this->ToSQL($this->cp["det_ValTotal"]->GetDBValue(), $this->cp["det_ValTotal"]->DataType) . ", "
             . "det_RefOperativa=" . $this->ToSQL($this->cp["det_RefOperativa"]->GetDBValue(), $this->cp["det_RefOperativa"]->DataType) . ", "
             . "det_Estado=" . $this->ToSQL($this->cp["det_Estado"]->GetDBValue(), $this->cp["det_Estado"]->DataType) . ", "
             . "det_CosUnitario=" . $this->ToSQL($this->cp["det_CosUnitario"]->GetDBValue(), $this->cp["det_CosUnitario"]->DataType) . ", "
             . "det_ValUnitario=" . $this->ToSQL($this->cp["det_ValUnitario"]->GetDBValue(), $this->cp["det_ValUnitario"]->DataType) . ", "
             . "det_NumSerie=" . $this->ToSQL($this->cp["det_NumSerie"]->GetDBValue(), $this->cp["det_NumSerie"]->DataType) . ", "
                . "det_CodAnterior=" . $this->ToSQL($this->cp["det_CodAnterior"]->GetDBValue(), $this->cp["det_CodAnterior"]->DataType) . ", "
             . "det_Destino=" . $this->ToSQL($this->cp["det_Destino"]->GetDBValue(), $this->cp["det_Destino"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-35EA2524
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", ""), "", true);
        $wp->AddParameter("2", "dsdet_Secuencia", ccsInteger, "", "", $this->CachedColumns["det_Secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "det_RegNUmero", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "det_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM invdetalle";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End InTrTr_detalleDataSource Class @2-FCB6E20C

//Initialize Page @1-D2DC2A16
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$gfValTotal = 0;
$gfCosTotal = 0;
$gfCanTotal = 0;
;
// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "InTrTr_deta06.php";
$Redirect = "";
$TemplateFileName = "InTrTr_deta06.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-60801D53
$DBdatos = new clsDBdatos();

// Controls
$InTrTr_detalle = new clsEditableGridInTrTr_detalle();
$InTrTr_detalle->Initialize();

// Events
include("./InTrTr_deta_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-77057344
$InTrTr_detalle->Operation();
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

//Show Page @1-7F273884
$InTrTr_detalle->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
//$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
//$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
