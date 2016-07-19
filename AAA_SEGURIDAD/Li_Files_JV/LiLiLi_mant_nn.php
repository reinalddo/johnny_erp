<?php
/*
 *      Mantenimiento del detalle de liquidaciones
 *      @package    Liquidaciones
 *      @subpackage Liquidaciones
 *      @rev    fah     10/04/09    Habilitar mas detalles en cada pantalla, omitiendo valores lineas adicionales
 **/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include ("General.inc.php");
//Include Page implementation @134-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
include_once("LiLiPr_func.inc.php");
class clsGridliqcabece { //liqcabece class @63-6F7101D8

//Variables @63-0B3A0FB0

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
//End Variables

//Class_Initialize Event @63-23815682
    function clsGridliqcabece()
    {
        global $FileName;
        $this->ComponentName = "liqcabece";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid liqcabece";
        $this->ds = new clsliqcabeceDataSource();
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

        $this->inv_RegNumero = new clsControl(ccsHidden, "inv_RegNumero", "inv_RegNumero", ccsInteger, "", CCGetRequestParam("inv_RegNumero", ccsGet));
        $this->Label1 = new clsControl(ccsLabel, "Label1", "Label1", ccsText, "", CCGetRequestParam("Label1", ccsGet));
        $this->com_NumComp = new clsControl(ccsTextBox, "com_NumComp", "com_NumComp", ccsInteger, "", CCGetRequestParam("com_NumComp", ccsGet));
        $this->com_RegNumero = new clsControl(ccsTextBox, "com_RegNumero", "com_RegNumero", ccsInteger, "", CCGetRequestParam("com_RegNumero", ccsGet));
        $this->com_FecContab = new clsControl(ccsLabel, "com_FecContab", "com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", ccsGet));
        $this->com_CodReceptor = new clsControl(ccsTextBox, "com_CodReceptor", "com_CodReceptor", ccsInteger, "", CCGetRequestParam("com_CodReceptor", ccsGet));
        $this->per_Apellidos = new clsControl(ccsLabel, "per_Apellidos", "per_Apellidos", ccsText, "", CCGetRequestParam("per_Apellidos", ccsGet));
        $this->per_Nombres = new clsControl(ccsLabel, "per_Nombres", "per_Nombres", ccsText, "", CCGetRequestParam("per_Nombres", ccsGet));
        $this->com_Concepto = new clsControl(ccsLabel, "com_Concepto", "com_Concepto", ccsMemo, "", CCGetRequestParam("com_Concepto", ccsGet));
        $this->lbl_NumLiquida = new clsControl(ccsLabel, "lbl_NumLiquida", "lbl_NumLiquida", ccsText, "", CCGetRequestParam("lbl_NumLiquida", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @63-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @63-EB4F33F5
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlliq_NumLiquida"] = CCGetFromGet("liq_NumLiquida", "");

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
                $this->inv_RegNumero->SetValue($this->ds->inv_RegNumero->GetValue());
                $this->Label1->SetValue($this->ds->Label1->GetValue());
                $this->com_NumComp->SetValue($this->ds->com_NumComp->GetValue());
                $this->com_RegNumero->SetValue($this->ds->com_RegNumero->GetValue());
                $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                $this->com_CodReceptor->SetValue($this->ds->com_CodReceptor->GetValue());
                $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                $this->per_Nombres->SetValue($this->ds->per_Nombres->GetValue());
                $this->com_Concepto->SetValue($this->ds->com_Concepto->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->inv_RegNumero->Show();
                $this->Label1->Show();
                $this->com_NumComp->Show();
                $this->com_RegNumero->Show();
                $this->com_FecContab->Show();
                $this->com_CodReceptor->Show();
                $this->per_Apellidos->Show();
                $this->per_Nombres->Show();
                $this->com_Concepto->Show();
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
        $this->lbl_NumLiquida->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @63-CB7C0121
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->inv_RegNumero->Errors->ToString();
        $errors .= $this->Label1->Errors->ToString();
        $errors .= $this->com_NumComp->Errors->ToString();
        $errors .= $this->com_RegNumero->Errors->ToString();
        $errors .= $this->com_FecContab->Errors->ToString();
        $errors .= $this->com_CodReceptor->Errors->ToString();
        $errors .= $this->per_Apellidos->Errors->ToString();
        $errors .= $this->per_Nombres->Errors->ToString();
        $errors .= $this->com_Concepto->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End liqcabece Class @63-FCB6E20C

class clsliqcabeceDataSource extends clsDBdatos {  //liqcabeceDataSource Class @63-92D6254C

//DataSource Variables @63-162D2B3C
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $inv_RegNumero;
    var $Label1;
    var $com_NumComp;
    var $com_RegNumero;
    var $com_FecContab;
    var $com_CodReceptor;
    var $per_Apellidos;
    var $per_Nombres;
    var $com_Concepto;
//End DataSource Variables

//Class_Initialize Event @63-958051F7
    function clsliqcabeceDataSource()
    {
        $this->ErrorBlock = "Grid liqcabece";
        $this->Initialize();
        $this->inv_RegNumero = new clsField("inv_RegNumero", ccsInteger, "");
        $this->Label1 = new clsField("Label1", ccsText, "");
        $this->com_NumComp = new clsField("com_NumComp", ccsInteger, "");
        $this->com_RegNumero = new clsField("com_RegNumero", ccsInteger, "");
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_CodReceptor = new clsField("com_CodReceptor", ccsInteger, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->per_Nombres = new clsField("per_Nombres", ccsText, "");
        $this->com_Concepto = new clsField("com_Concepto", ccsMemo, "");

    }
//End Class_Initialize Event

//SetOrder Method @63-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @63-117CDEA5
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlliq_NumLiquida", ccsInteger, "", "", $this->Parameters["urlliq_NumLiquida"], 0, false);
    }
//End Prepare Method

//Open Method @63-CCDD48ED
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM concomprobantes a, " .
        "     conpersonas p,     concomprobantes b  " .
        "WHERE a.com_TipoComp = 'LQ' AND a.com_NumComp = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " AND " .
        "      b.com_TipoComp = 'LI' AND b.com_NumComp = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " AND " .
        "      p.per_CodAuxiliar = a.com_CodReceptor";
        $this->SQL = "SELECT a.com_TipoComp AS com_TipoComp,  " .
        "       a.com_NumComp AS com_NumComp,  " .
        "       a.com_RegNumero AS com_RegNumero,  " .
        "       a.com_FecContab AS com_FecContab,  " .
        "       a.com_CodReceptor AS com_CodReceptor,  " .
        "       a.com_Concepto AS com_Concepto,  " .
        "       per_Apellidos, per_Nombres, b.com_RegNumero as inv_RegNumero, " .
        "       per_Estado, per_PerContable " .
        "FROM concomprobantes a, " .
        "     conpersonas p,     concomprobantes b, conperiodos  " .
        "WHERE a.com_TipoComp = 'LQ' AND a.com_NumComp = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " AND " .
        "      b.com_TipoComp = 'LI' AND b.com_NumComp = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " AND " .
        "      p.per_CodAuxiliar = a.com_CodReceptor AND
               per_aplicacion = 'LI' AND  per_numperiodo = a.com_refoperat";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @63-BE515C09
    function SetValues()
    {
        global $giEstado;
        global $giPerContable;
        $this->inv_RegNumero->SetDBValue(trim($this->f("inv_RegNumero")));
        $this->Label1->SetDBValue($this->f("com_TipoComp"));
        $this->com_NumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->com_RegNumero->SetDBValue(trim($this->f("com_RegNumero")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->com_CodReceptor->SetDBValue(trim($this->f("com_CodReceptor")));
        $this->per_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->per_Nombres->SetDBValue($this->f("per_Nombres"));
        $this->com_Concepto->SetDBValue($this->f("com_Concepto"));
        $giPerContable =$this->f("per_PerContable");
        $giEstado = $this->f("per_Estado");
    }
//End SetValues Method

} //End liqcabeceDataSource Class @63-FCB6E20C

class clsEditableGridliqdetalle { //liqdetalle Class @2-B7DAE6E4

//Variables @2-F7DBDE2B

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
    var $Sorter_liq_Secuencia;
    var $Sorter_liq_CodRubro;
    var $Sorter_liq_Descripcion;
    var $Sorter_liq_Cantidad;
    var $Sorter_liq_ValUnitario;
    var $Sorter_liq_ValTotal;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-ADF9DC5E
    function clsEditableGridliqdetalle()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid liqdetalle/Error";
        $this->ComponentName = "liqdetalle";
        $this->CachedColumns["liq_Secuencia"][0] = "liq_Secuencia";
        $this->ds = new clsliqdetalleDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 22)
            $this->PageSize = 22;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 1;
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

        $this->SorterName = CCGetParam("liqdetalleOrder", "");
        $this->SorterDirection = CCGetParam("liqdetalleDir", "");

        $this->Sorter_liq_Secuencia = new clsSorter($this->ComponentName, "Sorter_liq_Secuencia", $FileName);
        $this->Sorter_liq_CodRubro = new clsSorter($this->ComponentName, "Sorter_liq_CodRubro", $FileName);
        $this->Sorter_liq_Descripcion = new clsSorter($this->ComponentName, "Sorter_liq_Descripcion", $FileName);
        $this->Sorter_liq_Cantidad = new clsSorter($this->ComponentName, "Sorter_liq_Cantidad", $FileName);
        $this->Sorter_liq_ValUnitario = new clsSorter($this->ComponentName, "Sorter_liq_ValUnitario", $FileName);
        $this->Sorter_liq_ValTotal = new clsSorter($this->ComponentName, "Sorter_liq_ValTotal", $FileName);
        $this->liq_NumProceso = new clsControl(ccsHidden, "liq_NumProceso", "Liq Num Proceso", ccsInteger, "");
        $this->liq_Numliquida = new clsControl(ccsHidden, "liq_Numliquida", "liq_Numliquida", ccsText, "");
        $this->liq_Secuencia = new clsControl(ccsHidden, "liq_Secuencia", "Liq Secuencia", ccsInteger, "");
        $this->liq_RefOperativa = new clsControl(ccsHidden, "liq_RefOperativa", "Liq Ref Operativa", ccsInteger, "");
        $this->liq_Clase = new clsControl(ccsHidden, "liq_Clase", "Liq Clase", ccsInteger, "");
        $this->rub_CtaOrigen = new clsControl(ccsHidden, "rub_CtaOrigen", "rub_CtaOrigen", ccsText, "");
        $this->rub_PagAuxiliar = new clsControl(ccsHidden, "rub_PagAuxiliar", "rub_PagAuxiliar", ccsText, "");
        $this->btMantto1 = new clsButton("btMantto1");
        $this->liq_CodRubro = new clsControl(ccsListBox, "liq_CodRubro", "Liq Cod Rubro", ccsInteger, "");
        $this->liq_CodRubro->DSType = dsTable;
        list($this->liq_CodRubro->BoundColumn, $this->liq_CodRubro->TextColumn, $this->liq_CodRubro->DBFormat) = array("rub_CodRubro", "rub_DescCorta", "");
        $this->liq_CodRubro->ds = new clsDBdatos();
        $this->liq_CodRubro->ds->SQL = "SELECT *  " .
"FROM liqrubros";
        $this->liq_Descripcion = new clsControl(ccsTextBox, "liq_Descripcion", "Liq Descripcion", ccsText, "");
        $this->liq_Cantidad = new clsControl(ccsTextBox, "liq_Cantidad", "Liq Cantidad", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""));
        $this->liq_ValUnitario = new clsControl(ccsTextBox, "liq_ValUnitario", "Liq Val Unitario", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""));
        $this->liq_ValTotal = new clsControl(ccsTextBox, "liq_ValTotal", "Liq Val Total", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""));
        $this->liq_EsGenerado = new clsControl(ccsHidden, "liq_EsGenerado", "Liq Es Generado", ccsInteger, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->tmp_Total = new clsControl(ccsTextBox, "tmp_Total", "Liq Val Total", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Button_Delete = new clsButton("Button_Delete");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @2-3122386C
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlliq_NumLiquida"] = CCGetFromGet("liq_NumLiquida", "");
    }
//End Initialize Method

//GetFormParameters Method @2-DFEA1D63
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["liq_NumProceso"][$RowNumber] = CCGetFromPost("liq_NumProceso_" . $RowNumber);
            $this->FormParameters["liq_Numliquida"][$RowNumber] = CCGetFromPost("liq_Numliquida_" . $RowNumber);
            $this->FormParameters["liq_Secuencia"][$RowNumber] = CCGetFromPost("liq_Secuencia_" . $RowNumber);
            $this->FormParameters["liq_RefOperativa"][$RowNumber] = CCGetFromPost("liq_RefOperativa_" . $RowNumber);
            $this->FormParameters["liq_Clase"][$RowNumber] = CCGetFromPost("liq_Clase_" . $RowNumber);
            $this->FormParameters["rub_CtaOrigen"][$RowNumber] = CCGetFromPost("rub_CtaOrigen_" . $RowNumber);
            $this->FormParameters["rub_PagAuxiliar"][$RowNumber] = CCGetFromPost("rub_PagAuxiliar_" . $RowNumber);
            $this->FormParameters["liq_CodRubro"][$RowNumber] = CCGetFromPost("liq_CodRubro_" . $RowNumber);
            $this->FormParameters["liq_Descripcion"][$RowNumber] = CCGetFromPost("liq_Descripcion_" . $RowNumber);
            $this->FormParameters["liq_Cantidad"][$RowNumber] = CCGetFromPost("liq_Cantidad_" . $RowNumber);
            $this->FormParameters["liq_ValUnitario"][$RowNumber] = CCGetFromPost("liq_ValUnitario_" . $RowNumber);
            $this->FormParameters["liq_ValTotal"][$RowNumber] = CCGetFromPost("liq_ValTotal_" . $RowNumber);
            $this->FormParameters["liq_EsGenerado"][$RowNumber] = CCGetFromPost("liq_EsGenerado_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-D5FA9266
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["liq_Secuencia"] = $this->CachedColumns["liq_Secuencia"][$RowNumber];
            $this->liq_NumProceso->SetText($this->FormParameters["liq_NumProceso"][$RowNumber], $RowNumber);
            $this->liq_Numliquida->SetText($this->FormParameters["liq_Numliquida"][$RowNumber], $RowNumber);
            $this->liq_Secuencia->SetText($this->FormParameters["liq_Secuencia"][$RowNumber], $RowNumber);
            $this->liq_RefOperativa->SetText($this->FormParameters["liq_RefOperativa"][$RowNumber], $RowNumber);
            $this->liq_Clase->SetText($this->FormParameters["liq_Clase"][$RowNumber], $RowNumber);
            $this->rub_CtaOrigen->SetText($this->FormParameters["rub_CtaOrigen"][$RowNumber], $RowNumber);
            $this->rub_PagAuxiliar->SetText($this->FormParameters["rub_PagAuxiliar"][$RowNumber], $RowNumber);
            $this->liq_CodRubro->SetText($this->FormParameters["liq_CodRubro"][$RowNumber], $RowNumber);
            $this->liq_Descripcion->SetText($this->FormParameters["liq_Descripcion"][$RowNumber], $RowNumber);
            $this->liq_Cantidad->SetText($this->FormParameters["liq_Cantidad"][$RowNumber], $RowNumber);
            $this->liq_ValUnitario->SetText($this->FormParameters["liq_ValUnitario"][$RowNumber], $RowNumber);
            $this->liq_ValTotal->SetText($this->FormParameters["liq_ValTotal"][$RowNumber], $RowNumber);
            $this->liq_EsGenerado->SetText($this->FormParameters["liq_EsGenerado"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-D7E20D3A
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->liq_NumProceso->Validate() && $Validation);
        $Validation = ($this->liq_Numliquida->Validate() && $Validation);
        $Validation = ($this->liq_Secuencia->Validate() && $Validation);
        $Validation = ($this->liq_RefOperativa->Validate() && $Validation);
        $Validation = ($this->liq_Clase->Validate() && $Validation);
        $Validation = ($this->rub_CtaOrigen->Validate() && $Validation);
        $Validation = ($this->rub_PagAuxiliar->Validate() && $Validation);
        $Validation = ($this->liq_CodRubro->Validate() && $Validation);
        $Validation = ($this->liq_Descripcion->Validate() && $Validation);
        $Validation = ($this->liq_Cantidad->Validate() && $Validation);
        $Validation = ($this->liq_ValUnitario->Validate() && $Validation);
        $Validation = ($this->liq_ValTotal->Validate() && $Validation);
        $Validation = ($this->liq_EsGenerado->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->liq_NumProceso->Errors->ToString();
            $errors .= $this->liq_Numliquida->Errors->ToString();
            $errors .= $this->liq_Secuencia->Errors->ToString();
            $errors .= $this->liq_RefOperativa->Errors->ToString();
            $errors .= $this->liq_Clase->Errors->ToString();
            $errors .= $this->rub_CtaOrigen->Errors->ToString();
            $errors .= $this->rub_PagAuxiliar->Errors->ToString();
            $errors .= $this->liq_CodRubro->Errors->ToString();
            $errors .= $this->liq_Descripcion->Errors->ToString();
            $errors .= $this->liq_Cantidad->Errors->ToString();
            $errors .= $this->liq_ValUnitario->Errors->ToString();
            $errors .= $this->liq_ValTotal->Errors->ToString();
            $errors .= $this->liq_EsGenerado->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->liq_NumProceso->Errors->Clear();
            $this->liq_Numliquida->Errors->Clear();
            $this->liq_Secuencia->Errors->Clear();
            $this->liq_RefOperativa->Errors->Clear();
            $this->liq_Clase->Errors->Clear();
            $this->rub_CtaOrigen->Errors->Clear();
            $this->rub_PagAuxiliar->Errors->Clear();
            $this->liq_CodRubro->Errors->Clear();
            $this->liq_Descripcion->Errors->Clear();
            $this->liq_Cantidad->Errors->Clear();
            $this->liq_ValUnitario->Errors->Clear();
            $this->liq_ValTotal->Errors->Clear();
            $this->liq_EsGenerado->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @2-BC2ACB8D
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["liq_NumProceso"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_Numliquida"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_RefOperativa"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_Clase"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rub_CtaOrigen"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["rub_PagAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_CodRubro"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_Cantidad"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_ValUnitario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_ValTotal"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["liq_EsGenerado"][$RowNumber]));
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

//Operation Method @2-4CE20173
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
        if(strlen(CCGetParam("btMantto1", ""))) {
            $this->PressedButton = "btMantto1";
        } else if(strlen(CCGetParam("Button_Submit", ""))) {
            $this->PressedButton = "Button_Submit";
        } else if(strlen(CCGetParam("Button_Delete", ""))) {
            $this->PressedButton = "Button_Delete";
        } else if(strlen(CCGetParam("Cancel", ""))) {
            $this->PressedButton = "Cancel";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "btMantto1") {
            if(!CCGetEvent($this->btMantto1->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
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

//UpdateGrid Method @2-A3D51D67
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["liq_Secuencia"] = $this->CachedColumns["liq_Secuencia"][$RowNumber];
            $this->liq_NumProceso->SetText($this->FormParameters["liq_NumProceso"][$RowNumber], $RowNumber);
            $this->liq_Numliquida->SetText($this->FormParameters["liq_Numliquida"][$RowNumber], $RowNumber);
            $this->liq_Secuencia->SetText($this->FormParameters["liq_Secuencia"][$RowNumber], $RowNumber);
            $this->liq_RefOperativa->SetText($this->FormParameters["liq_RefOperativa"][$RowNumber], $RowNumber);
            $this->liq_Clase->SetText($this->FormParameters["liq_Clase"][$RowNumber], $RowNumber);
            $this->rub_CtaOrigen->SetText($this->FormParameters["rub_CtaOrigen"][$RowNumber], $RowNumber);
            $this->rub_PagAuxiliar->SetText($this->FormParameters["rub_PagAuxiliar"][$RowNumber], $RowNumber);
            $this->liq_CodRubro->SetText($this->FormParameters["liq_CodRubro"][$RowNumber], $RowNumber);
            $this->liq_Descripcion->SetText($this->FormParameters["liq_Descripcion"][$RowNumber], $RowNumber);
            $this->liq_Cantidad->SetText($this->FormParameters["liq_Cantidad"][$RowNumber], $RowNumber);
            $this->liq_ValUnitario->SetText($this->FormParameters["liq_ValUnitario"][$RowNumber], $RowNumber);
            $this->liq_ValTotal->SetText($this->FormParameters["liq_ValTotal"][$RowNumber], $RowNumber);
            $this->liq_EsGenerado->SetText($this->FormParameters["liq_EsGenerado"][$RowNumber], $RowNumber);
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

//InsertRow Method @2-511853C7
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->liq_Secuencia->SetValue($this->liq_Secuencia->GetValue());
        $this->ds->liq_Clase->SetValue($this->liq_Clase->GetValue());
        $this->ds->liq_CodRubro->SetValue($this->liq_CodRubro->GetValue());
        $this->ds->liq_Descripcion->SetValue($this->liq_Descripcion->GetValue());
        $this->ds->liq_Cantidad->SetValue($this->liq_Cantidad->GetValue());
        $this->ds->liq_ValUnitario->SetValue($this->liq_ValUnitario->GetValue());
        $this->ds->liq_ValTotal->SetValue($this->liq_ValTotal->GetValue());
        $this->ds->liq_EsGenerado->SetValue($this->liq_EsGenerado->GetValue());
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

//UpdateRow Method @2-3B110E46
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->liq_Clase->SetValue($this->liq_Clase->GetValue());
        $this->ds->liq_CodRubro->SetValue($this->liq_CodRubro->GetValue());
        $this->ds->liq_Descripcion->SetValue($this->liq_Descripcion->GetValue());
        $this->ds->liq_Cantidad->SetValue($this->liq_Cantidad->GetValue());
        $this->ds->liq_ValUnitario->SetValue($this->liq_ValUnitario->GetValue());
        $this->ds->liq_ValTotal->SetValue($this->liq_ValTotal->GetValue());
        $this->ds->liq_EsGenerado->SetValue($this->liq_EsGenerado->GetValue());
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

//FormScript Method @2-BBDA2B36
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var liqdetalleElements;\n";
        $script .= "var liqdetalleEmptyRows = 3;\n";
        $script .= "var " . $this->ComponentName . "liq_NumProcesoID = 0;\n";
        $script .= "var " . $this->ComponentName . "liq_NumliquidaID = 1;\n";
        $script .= "var " . $this->ComponentName . "liq_SecuenciaID = 2;\n";
        $script .= "var " . $this->ComponentName . "liq_RefOperativaID = 3;\n";
        $script .= "var " . $this->ComponentName . "liq_ClaseID = 4;\n";
        $script .= "var " . $this->ComponentName . "rub_CtaOrigenID = 5;\n";
        $script .= "var " . $this->ComponentName . "rub_PagAuxiliarID = 6;\n";
        $script .= "var " . $this->ComponentName . "btMantto1ID = 7;\n";
        $script .= "var " . $this->ComponentName . "liq_CodRubroID = 8;\n";
        $script .= "var " . $this->ComponentName . "liq_DescripcionID = 9;\n";
        $script .= "var " . $this->ComponentName . "liq_CantidadID = 10;\n";
        $script .= "var " . $this->ComponentName . "liq_ValUnitarioID = 11;\n";
        $script .= "var " . $this->ComponentName . "liq_ValTotalID = 12;\n";
        $script .= "var " . $this->ComponentName . "liq_EsGeneradoID = 13;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 14;\n";
        $script .= "\nfunction initliqdetalleElements() {\n";
        $script .= "\tvar ED = document.forms[\"liqdetalle\"];\n";
        $script .= "\tliqdetalleElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.liq_NumProceso_" . $i . ", " . "ED.liq_Numliquida_" . $i . ", " . "ED.liq_Secuencia_" . $i . ", " . "ED.liq_RefOperativa_" . $i . ", " . "ED.liq_Clase_" . $i . ", " . "ED.rub_CtaOrigen_" . $i . ", " . "ED.rub_PagAuxiliar_" . $i . ", " . "ED.btMantto1_" . $i . ", " . "ED.liq_CodRubro_" . $i . ", " . "ED.liq_Descripcion_" . $i . ", " . "ED.liq_Cantidad_" . $i . ", " . "ED.liq_ValUnitario_" . $i . ", " . "ED.liq_ValTotal_" . $i . ", " . "ED.liq_EsGenerado_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-B85A7C67
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
                $this->CachedColumns["liq_Secuencia"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["liq_Secuencia"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-6563D116
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["liq_Secuencia"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-78CB1B80
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->liq_CodRubro->Prepare();

        $this->ds->open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) { return; }

        $this->Button_Submit->Visible = ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $this->Button_Delete->Visible = ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
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
                        $this->CachedColumns["liq_Secuencia"][$RowNumber] = $this->ds->CachedColumns["liq_Secuencia"];
                        $this->liq_NumProceso->SetValue($this->ds->liq_NumProceso->GetValue());
                        $this->liq_Numliquida->SetValue($this->ds->liq_Numliquida->GetValue());
                        $this->liq_Secuencia->SetValue($this->ds->liq_Secuencia->GetValue());
                        $this->liq_RefOperativa->SetValue($this->ds->liq_RefOperativa->GetValue());
                        $this->liq_Clase->SetValue($this->ds->liq_Clase->GetValue());
                        $this->rub_CtaOrigen->SetValue($this->ds->rub_CtaOrigen->GetValue());
                        $this->rub_PagAuxiliar->SetValue($this->ds->rub_PagAuxiliar->GetValue());
                        $this->liq_CodRubro->SetValue($this->ds->liq_CodRubro->GetValue());
                        $this->liq_Descripcion->SetValue($this->ds->liq_Descripcion->GetValue());
                        $this->liq_Cantidad->SetValue($this->ds->liq_Cantidad->GetValue());
                        $this->liq_ValUnitario->SetValue($this->ds->liq_ValUnitario->GetValue());
                        $this->liq_ValTotal->SetValue($this->ds->liq_ValTotal->GetValue());
                        $this->liq_EsGenerado->SetValue($this->ds->liq_EsGenerado->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["liq_Secuencia"][$RowNumber] = "";
                        $this->liq_NumProceso->SetText("");
                        $this->liq_Numliquida->SetText("");
                        $this->liq_Secuencia->SetText("");
                        $this->liq_RefOperativa->SetText("");
                        $this->liq_Clase->SetText("");
                        $this->rub_CtaOrigen->SetText("");
                        $this->rub_PagAuxiliar->SetText("");
                        $this->liq_CodRubro->SetText("");
                        $this->liq_Descripcion->SetText("");
                        $this->liq_Cantidad->SetText("");
                        $this->liq_ValUnitario->SetText("");
                        $this->liq_ValTotal->SetText("");
                        $this->liq_EsGenerado->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->liq_NumProceso->SetText($this->FormParameters["liq_NumProceso"][$RowNumber], $RowNumber);
                        $this->liq_Numliquida->SetText($this->FormParameters["liq_Numliquida"][$RowNumber], $RowNumber);
                        $this->liq_Secuencia->SetText($this->FormParameters["liq_Secuencia"][$RowNumber], $RowNumber);
                        $this->liq_RefOperativa->SetText($this->FormParameters["liq_RefOperativa"][$RowNumber], $RowNumber);
                        $this->liq_Clase->SetText($this->FormParameters["liq_Clase"][$RowNumber], $RowNumber);
                        $this->rub_CtaOrigen->SetText($this->FormParameters["rub_CtaOrigen"][$RowNumber], $RowNumber);
                        $this->rub_PagAuxiliar->SetText($this->FormParameters["rub_PagAuxiliar"][$RowNumber], $RowNumber);
                        $this->liq_CodRubro->SetText($this->FormParameters["liq_CodRubro"][$RowNumber], $RowNumber);
                        $this->liq_Descripcion->SetText($this->FormParameters["liq_Descripcion"][$RowNumber], $RowNumber);
                        $this->liq_Cantidad->SetText($this->FormParameters["liq_Cantidad"][$RowNumber], $RowNumber);
                        $this->liq_ValUnitario->SetText($this->FormParameters["liq_ValUnitario"][$RowNumber], $RowNumber);
                        $this->liq_ValTotal->SetText($this->FormParameters["liq_ValTotal"][$RowNumber], $RowNumber);
                        $this->liq_EsGenerado->SetText($this->FormParameters["liq_EsGenerado"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->liq_NumProceso->Show($RowNumber);
                    $this->liq_Numliquida->Show($RowNumber);
                    $this->liq_Secuencia->Show($RowNumber);
                    $this->liq_RefOperativa->Show($RowNumber);
                    $this->liq_Clase->Show($RowNumber);
                    $this->rub_CtaOrigen->Show($RowNumber);
                    $this->rub_PagAuxiliar->Show($RowNumber);
                    $this->btMantto1->Show($RowNumber);
                    $this->liq_CodRubro->Show($RowNumber);
                    $this->liq_Descripcion->Show($RowNumber);
                    $this->liq_Cantidad->Show($RowNumber);
                    $this->liq_ValUnitario->Show($RowNumber);
                    $this->liq_ValTotal->Show($RowNumber);
                    $this->liq_EsGenerado->Show($RowNumber);
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
        $this->Sorter_liq_Secuencia->Show();
        $this->Sorter_liq_CodRubro->Show();
        $this->Sorter_liq_Descripcion->Show();
        $this->Sorter_liq_Cantidad->Show();
        $this->Sorter_liq_ValUnitario->Show();
        $this->Sorter_liq_ValTotal->Show();
        $this->tmp_Total->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Button_Delete->Show();
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

} //End liqdetalle Class @2-FCB6E20C

class clsliqdetalleDataSource extends clsDBdatos {  //liqdetalleDataSource Class @2-997A12C7

//DataSource Variables @2-8C18B061
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
    var $liq_NumProceso;
    var $liq_Numliquida;
    var $liq_Secuencia;
    var $liq_RefOperativa;
    var $liq_Clase;
    var $rub_CtaOrigen;
    var $rub_PagAuxiliar;
    var $liq_CodRubro;
    var $liq_Descripcion;
    var $liq_Cantidad;
    var $liq_ValUnitario;
    var $liq_ValTotal;
    var $liq_EsGenerado;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @2-B9C29132
    function clsliqdetalleDataSource()
    {
        $this->ErrorBlock = "EditableGrid liqdetalle/Error";
        $this->Initialize();
        $this->liq_NumProceso = new clsField("liq_NumProceso", ccsInteger, "");
        $this->liq_Numliquida = new clsField("liq_Numliquida", ccsText, "");
        $this->liq_Secuencia = new clsField("liq_Secuencia", ccsInteger, "");
        $this->liq_RefOperativa = new clsField("liq_RefOperativa", ccsInteger, "");
        $this->liq_Clase = new clsField("liq_Clase", ccsInteger, "");
        $this->rub_CtaOrigen = new clsField("rub_CtaOrigen", ccsText, "");
        $this->rub_PagAuxiliar = new clsField("rub_PagAuxiliar", ccsText, "");
        $this->liq_CodRubro = new clsField("liq_CodRubro", ccsInteger, "");
        $this->liq_Descripcion = new clsField("liq_Descripcion", ccsText, "");
        $this->liq_Cantidad = new clsField("liq_Cantidad", ccsFloat, "");
        $this->liq_ValUnitario = new clsField("liq_ValUnitario", ccsFloat, "");
        $this->liq_ValTotal = new clsField("liq_ValTotal", ccsFloat, "");
        $this->liq_EsGenerado = new clsField("liq_EsGenerado", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @2-49351C87
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "rub_PosOrdinal";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_liq_Secuencia" => array("liq_Secuencia", ""), 
            "Sorter_liq_CodRubro" => array("liq_CodRubro", ""), 
            "Sorter_liq_Descripcion" => array("liq_Descripcion", ""), 
            "Sorter_liq_Cantidad" => array("liq_Cantidad", ""), 
            "Sorter_liq_ValUnitario" => array("liq_ValUnitario", ""), 
            "Sorter_liq_ValTotal" => array("liq_ValTotal", "")));
    }
//End SetOrder Method

//Prepare Method @2-F1CAD3BA
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlliq_NumLiquida", ccsInteger, "", "", $this->Parameters["urlliq_NumLiquida"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "liq_Numliquida", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-D7CB077F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM liqliquidaciones LEFT JOIN liqrubros ON " .
        "liqliquidaciones.liq_CodRubro = liqrubros.rub_CodRubro";
        $this->SQL = "SELECT liqliquidaciones.*, rub_DescLarga, rub_CtaOrigen, rub_PagAuxiliar  " .
        "FROM liqliquidaciones LEFT JOIN liqrubros ON " .
        "liqliquidaciones.liq_CodRubro = liqrubros.rub_CodRubro";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-72793F28
    function SetValues()
    {
        $this->CachedColumns["liq_Secuencia"] = $this->f("liq_Secuencia");
        $this->liq_NumProceso->SetDBValue(trim($this->f("liq_NumProceso")));
        $this->liq_Numliquida->SetDBValue($this->f("liq_Numliquida"));
        $this->liq_Secuencia->SetDBValue(trim($this->f("liq_Secuencia")));
        $this->liq_RefOperativa->SetDBValue(trim($this->f("liq_RefOperativa")));
        $this->liq_Clase->SetDBValue(trim($this->f("liq_Clase")));
        $this->rub_CtaOrigen->SetDBValue($this->f("rub_CtaOrigen"));
        $this->rub_PagAuxiliar->SetDBValue($this->f("rub_PagAuxiliar"));
        $this->liq_CodRubro->SetDBValue(trim($this->f("liq_CodRubro")));
        $this->liq_Descripcion->SetDBValue($this->f("liq_Descripcion"));
        $this->liq_Cantidad->SetDBValue(trim($this->f("liq_Cantidad")));
        $this->liq_ValUnitario->SetDBValue(trim($this->f("liq_ValUnitario")));
        $this->liq_ValTotal->SetDBValue(trim($this->f("liq_ValTotal")));
        $this->liq_EsGenerado->SetDBValue(trim($this->f("liq_EsGenerado")));
    }
//End SetValues Method

//Insert Method @2-9FCBD573
    function Insert()
    {
        $this->cp["liq_Numliquida"] = new clsSQLParameter("urlliq_NumLiquida", ccsInteger, "", "", CCGetFromGet("liq_NumLiquida", ""), -1, false, $this->ErrorBlock);
        $this->cp["liq_NumProceso"] = new clsSQLParameter("urlliq_NumProceso", ccsInteger, "", "", CCGetFromGet("liq_NumProceso", ""), -1, false, $this->ErrorBlock);
        $this->cp["liq_Secuencia"] = new clsSQLParameter("ctrlliq_Secuencia", ccsInteger, "", "", $this->liq_Secuencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_Clase"] = new clsSQLParameter("ctrlliq_Clase", ccsInteger, "", "", $this->liq_Clase->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_CodRubro"] = new clsSQLParameter("ctrlliq_CodRubro", ccsInteger, "", "", $this->liq_CodRubro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_Descripcion"] = new clsSQLParameter("ctrlliq_Descripcion", ccsText, "", "", $this->liq_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_Cantidad"] = new clsSQLParameter("ctrlliq_Cantidad", ccsFloat, Array(True, 5, ",", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#"), 1, True, ""), "", $this->liq_Cantidad->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_ValUnitario"] = new clsSQLParameter("ctrlliq_ValUnitario", ccsFloat, Array(True, 5, ",", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#"), 1, True, ""), "", $this->liq_ValUnitario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_ValTotal"] = new clsSQLParameter("ctrlliq_ValTotal", ccsFloat, Array(True, 5, ",", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#"), 1, True, ""), "", $this->liq_ValTotal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_EsGenerado"] = new clsSQLParameter("ctrlliq_EsGenerado", ccsInteger, "", "", $this->liq_EsGenerado->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqliquidaciones ("
             . "liq_Numliquida, "
             . "liq_NumProceso, "
             . "liq_Secuencia, "
             . "liq_Clase, "
             . "liq_CodRubro, "
             . "liq_Descripcion, "
             . "liq_Cantidad, "
             . "liq_ValUnitario, "
             . "liq_ValTotal, "
             . "liq_EsGenerado"
             . ") VALUES ("
             . $this->ToSQL($this->cp["liq_Numliquida"]->GetDBValue(), $this->cp["liq_Numliquida"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_NumProceso"]->GetDBValue(), $this->cp["liq_NumProceso"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_Secuencia"]->GetDBValue(), $this->cp["liq_Secuencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_Clase"]->GetDBValue(), $this->cp["liq_Clase"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_CodRubro"]->GetDBValue(), $this->cp["liq_CodRubro"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_Descripcion"]->GetDBValue(), $this->cp["liq_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_Cantidad"]->GetDBValue(), $this->cp["liq_Cantidad"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_ValUnitario"]->GetDBValue(), $this->cp["liq_ValUnitario"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_ValTotal"]->GetDBValue(), $this->cp["liq_ValTotal"]->DataType) . ", "
             . $this->ToSQL($this->cp["liq_EsGenerado"]->GetDBValue(), $this->cp["liq_EsGenerado"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-958DED8E
    function Update()
    {
        $this->cp["liq_Clase"] = new clsSQLParameter("ctrlliq_Clase", ccsInteger, "", "", $this->liq_Clase->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_CodRubro"] = new clsSQLParameter("ctrlliq_CodRubro", ccsInteger, "", "", $this->liq_CodRubro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_Descripcion"] = new clsSQLParameter("ctrlliq_Descripcion", ccsText, "", "", $this->liq_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_Cantidad"] = new clsSQLParameter("ctrlliq_Cantidad", ccsFloat, Array(True, 5, ",", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#"), 1, True, ""), "", $this->liq_Cantidad->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_ValUnitario"] = new clsSQLParameter("ctrlliq_ValUnitario", ccsFloat, Array(True, 5, ",", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#"), 1, True, ""), "", $this->liq_ValUnitario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_ValTotal"] = new clsSQLParameter("ctrlliq_ValTotal", ccsFloat, Array(True, 5, ",", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#", "#"), 1, True, ""), "", $this->liq_ValTotal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["liq_EsGenerado"] = new clsSQLParameter("ctrlliq_EsGenerado", ccsInteger, "", "", $this->liq_EsGenerado->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlliq_NumLiquida", ccsInteger, "", "", CCGetFromGet("liq_NumLiquida", ""), "", true);
        $wp->AddParameter("2", "dsliq_Secuencia", ccsInteger, "", "", $this->CachedColumns["liq_Secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "liq_Numliquida", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "liq_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE liqliquidaciones SET "
             . "liq_Clase=" . $this->ToSQL($this->cp["liq_Clase"]->GetDBValue(), $this->cp["liq_Clase"]->DataType) . ", "
             . "liq_CodRubro=" . $this->ToSQL($this->cp["liq_CodRubro"]->GetDBValue(), $this->cp["liq_CodRubro"]->DataType) . ", "
             . "liq_Descripcion=" . $this->ToSQL($this->cp["liq_Descripcion"]->GetDBValue(), $this->cp["liq_Descripcion"]->DataType) . ", "
             . "liq_Cantidad=" . NZ($this->ToSQL($this->cp["liq_Cantidad"]->GetDBValue(), $this->cp["liq_Cantidad"]->DataType),0) . ", "
             . "liq_ValUnitario=" . NZ($this->ToSQL($this->cp["liq_ValUnitario"]->GetDBValue(), $this->cp["liq_ValUnitario"]->DataType),0) . ", "
             . "liq_ValTotal=" . NZ($this->ToSQL($this->cp["liq_ValTotal"]->GetDBValue(), $this->cp["liq_ValTotal"]->DataType),0) . ", "
             . "liq_EsGenerado=" . NZ($this->ToSQL($this->cp["liq_EsGenerado"]->GetDBValue(), $this->cp["liq_EsGenerado"]->DataType),0);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-54F3186F
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlliq_NumLiquida", ccsInteger, "", "", CCGetFromGet("liq_NumLiquida", ""), "", true);
        $wp->AddParameter("2", "dsliq_Secuencia", ccsInteger, "", "", $this->CachedColumns["liq_Secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "liq_Numliquida", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "liq_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM liqliquidaciones";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqdetalleDataSource Class @2-FCB6E20C

//Initialize Page @1-F1C83856
// Variables
$giEstado = 0;              // ESTADO DE LA SEMANA
$giPerContable =0;          // numero de periodo contable
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "LiLiLi_mant.php";
$Redirect = "";
$TemplateFileName = "LiLiLi_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-9EAC5E99
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqcabece = new clsGridliqcabece();
$liqdetalle = new clsEditableGridliqdetalle();
$liqcabece->Initialize();
$liqdetalle->Initialize();

// Events
include("./LiLiLi_mant_events.php");
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

//Execute Components @1-82CFA021
$Cabecera->Operations();
$liqdetalle->Operation();
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

//Show Page @1-E2178EA7
// $Cabecera->Show("Cabecera");   NO MOSTRAR CABECERA
$liqcabece->Show();
//  Habilitar/Deshabilitar botones segun los atributos del usuario
if($giEstado == 1 && $_SESSION['atr']['LiLiLi']['040'] == 1) {
    $Tpl->SetVar('UPD_flag','');
    $Tpl->SetVar('DEL_flag','');
}
else {
    $Tpl->SetVar('UPD_flag','disabled');
    $Tpl->SetVar('DEL_flag','disabled');
}
$liqdetalle->Show();
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
