<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-2508A4C6
include_once(RelativePath . "/In_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsGridgenparametros { //genparametros class @27-0FC884FE

//Variables @27-36ED41AE

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
    var $Sorter_par_Secuencia;
    var $Sorter_par_Descripcion;
    var $Navigator;
//End Variables

//Class_Initialize Event @27-695DB8D4
    function clsGridgenparametros()
    {
        global $FileName;
        $this->ComponentName = "genparametros";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid genparametros";
        $this->ds = new clsgenparametrosDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("genparametrosOrder", "");
        $this->SorterDirection = CCGetParam("genparametrosDir", "");

        $this->par_Secuencia = new clsControl(ccsLink, "par_Secuencia", "par_Secuencia", ccsInteger, "", CCGetRequestParam("par_Secuencia", ccsGet));
        $this->par_Descripcion = new clsControl(ccsLabel, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->Alt_par_Secuencia = new clsControl(ccsLink, "Alt_par_Secuencia", "Alt_par_Secuencia", ccsInteger, "", CCGetRequestParam("Alt_par_Secuencia", ccsGet));
        $this->Alt_par_Descripcion = new clsControl(ccsLabel, "Alt_par_Descripcion", "Alt_par_Descripcion", ccsText, "", CCGetRequestParam("Alt_par_Descripcion", ccsGet));
        $this->Sorter_par_Secuencia = new clsSorter($this->ComponentName, "Sorter_par_Secuencia", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
    }
//End Class_Initialize Event

//Initialize Method @27-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @27-3AAA5896
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["expr37"] = 'INPRO';

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
                    $this->par_Secuencia->SetValue($this->ds->par_Secuencia->GetValue());
                    $this->par_Secuencia->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->par_Secuencia->Parameters = CCAddParam($this->par_Secuencia->Parameters, "pro_codProceso", $this->ds->f("par_Secuencia"));
                    $this->par_Secuencia->Parameters = CCAddParam($this->par_Secuencia->Parameters, "pro_Descripcion", $this->ds->f("par_Descripcion"));
                    $this->par_Secuencia->Page = "InAdPr_mant.php";
                    $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->par_Secuencia->Show();
                    $this->par_Descripcion->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_par_Secuencia->SetValue($this->ds->Alt_par_Secuencia->GetValue());
                    $this->Alt_par_Secuencia->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Alt_par_Secuencia->Parameters = CCAddParam($this->Alt_par_Secuencia->Parameters, "pro_codProceso", $this->ds->f("par_Secuencia"));
                    $this->Alt_par_Secuencia->Parameters = CCAddParam($this->Alt_par_Secuencia->Parameters, "pro_Descripcion", $this->ds->f("par_Descripcion"));
                    $this->Alt_par_Secuencia->Page = "InAdPr_mant.php";
                    $this->Alt_par_Descripcion->SetValue($this->ds->Alt_par_Descripcion->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_par_Secuencia->Show();
                    $this->Alt_par_Descripcion->Show();
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
        $this->Sorter_par_Secuencia->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @27-A6E1F7EE
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->par_Secuencia->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->Alt_par_Secuencia->Errors->ToString();
        $errors .= $this->Alt_par_Descripcion->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End genparametros Class @27-FCB6E20C

class clsgenparametrosDataSource extends clsDBdatos {  //genparametrosDataSource Class @27-F3E4BDC2

//DataSource Variables @27-67095B56
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $par_Secuencia;
    var $par_Descripcion;
    var $Alt_par_Secuencia;
    var $Alt_par_Descripcion;
//End DataSource Variables

//DataSourceClass_Initialize Event @27-AC2CB001
    function clsgenparametrosDataSource()
    {
        $this->ErrorBlock = "Grid genparametros";
        $this->Initialize();
        $this->par_Secuencia = new clsField("par_Secuencia", ccsInteger, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->Alt_par_Secuencia = new clsField("Alt_par_Secuencia", ccsInteger, "");
        $this->Alt_par_Descripcion = new clsField("Alt_par_Descripcion", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @27-B6468C52
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_par_Secuencia" => array("par_Secuencia", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @27-026C7F56
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr37", ccsText, "", "", $this->Parameters["expr37"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @27-C7AE4509
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM genparametros";
        $this->SQL = "SELECT *  " .
        "FROM genparametros";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @27-B75EEF0B
    function SetValues()
    {
        $this->par_Secuencia->SetDBValue(trim($this->f("par_Secuencia")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->Alt_par_Secuencia->SetDBValue(trim($this->f("par_Secuencia")));
        $this->Alt_par_Descripcion->SetDBValue($this->f("par_Descripcion"));
    }
//End SetValues Method

} //End genparametrosDataSource Class @27-FCB6E20C

class clsEditableGridinvprocesos { //invprocesos Class @3-8E26AA42

//Variables @3-9A991BA1

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
    var $Sorter_pro_codProceso;
    var $Sorter_cla_TipoTransacc;
    var $Sorter_pro_Orden;
    var $Sorter_pro_Signo;
    var $Sorter_pro_Efecto;
    var $Sorter_pro_EfeAcumula;
    var $Sorter_pro_Costeo;
    var $Sorter_pro_Grupo;
    var $Navigator;
//End Variables

//Class_Initialize Event @3-0375E490
    function clsEditableGridinvprocesos()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid invprocesos/Error";
        $this->ComponentName = "invprocesos";
        $this->CachedColumns["pro_codProceso"][0] = "pro_codProceso";
        $this->CachedColumns["cla_TipoTransacc"][0] = "cla_TipoTransacc";
        $this->CachedColumns["pro_Secuencia"][0] = "pro_Secuencia";
        $this->ds = new clsinvprocesosDataSource();
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

        $this->EmptyRows = 3;
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

        $this->SorterName = CCGetParam("invprocesosOrder", "");
        $this->SorterDirection = CCGetParam("invprocesosDir", "");

        $this->pro_Descripcion = new clsControl(ccsLabel, "pro_Descripcion", "pro_Descripcion", ccsText, "");
        $this->Sorter_pro_codProceso = new clsSorter($this->ComponentName, "Sorter_pro_codProceso", $FileName);
        $this->Sorter_cla_TipoTransacc = new clsSorter($this->ComponentName, "Sorter_cla_TipoTransacc", $FileName);
        $this->Sorter_pro_Orden = new clsSorter($this->ComponentName, "Sorter_pro_Orden", $FileName);
        $this->Sorter_pro_Signo = new clsSorter($this->ComponentName, "Sorter_pro_Signo", $FileName);
        $this->Sorter_pro_Efecto = new clsSorter($this->ComponentName, "Sorter_pro_Efecto", $FileName);
        $this->Sorter_pro_EfeAcumula = new clsSorter($this->ComponentName, "Sorter_pro_EfeAcumula", $FileName);
        $this->Sorter_pro_Costeo = new clsSorter($this->ComponentName, "Sorter_pro_Costeo", $FileName);
        $this->Sorter_pro_Grupo = new clsSorter($this->ComponentName, "Sorter_pro_Grupo", $FileName);
        $this->pro_codProceso = new clsControl(ccsHidden, "pro_codProceso", "Secuencia", ccsInteger, "");
        $this->pro_Secuencia = new clsControl(ccsTextBox, "pro_Secuencia", "pro_Secuencia", ccsText, "");
        $this->cla_TipoTransacc = new clsControl(ccsListBox, "cla_TipoTransacc", "Cla Tipo Transacc", ccsText, "");
        $this->cla_TipoTransacc->DSType = dsSQL;
        list($this->cla_TipoTransacc->BoundColumn, $this->cla_TipoTransacc->TextColumn, $this->cla_TipoTransacc->DBFormat) = array("cla_TipoComp", "txt_Descrip", "");
        $this->cla_TipoTransacc->ds = new clsDBdatos();
        $this->cla_TipoTransacc->ds->SQL = "SELECT cla_TipoComp, concat(cla_TipoComp, ' - ', Cla_Descripcion) as txt_Descrip " .
        "FROM genclasetran " .
        "WHERE cla_aplicacion = 'IN'";
        $this->pro_Orden = new clsControl(ccsTextBox, "pro_Orden", "Pro Orden", ccsInteger, "");
        $this->pro_Signo = new clsControl(ccsListBox, "pro_Signo", "Pro Signo", ccsInteger, "");
        $this->pro_Signo->DSType = dsListOfValues;
        $this->pro_Signo->Values = array(array("1", "POSITIVO"), array("-1", "NEGATIVO"));
        $this->pro_Efecto = new clsControl(ccsTextBox, "pro_Efecto", "Pro Efecto", ccsInteger, "");
        $this->pro_EfeAcumula = new clsControl(ccsTextBox, "pro_EfeAcumula", "Pro Efe Acumula", ccsInteger, "");
        $this->pro_Costeo = new clsControl(ccsTextBox, "pro_Costeo", "Pro Costeo", ccsInteger, "");
        $this->pro_Grupo = new clsControl(ccsTextBox, "pro_Grupo", "Pro Grupo", ccsInteger, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
    }
//End Class_Initialize Event

//Initialize Method @3-FF2B80DA
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlpro_codProceso"] = CCGetFromGet("pro_codProceso", "");
    }
//End Initialize Method

//GetFormParameters Method @3-628E09C6
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["pro_codProceso"][$RowNumber] = CCGetFromPost("pro_codProceso_" . $RowNumber);
            $this->FormParameters["pro_Secuencia"][$RowNumber] = CCGetFromPost("pro_Secuencia_" . $RowNumber);
            $this->FormParameters["cla_TipoTransacc"][$RowNumber] = CCGetFromPost("cla_TipoTransacc_" . $RowNumber);
            $this->FormParameters["pro_Orden"][$RowNumber] = CCGetFromPost("pro_Orden_" . $RowNumber);
            $this->FormParameters["pro_Signo"][$RowNumber] = CCGetFromPost("pro_Signo_" . $RowNumber);
            $this->FormParameters["pro_Efecto"][$RowNumber] = CCGetFromPost("pro_Efecto_" . $RowNumber);
            $this->FormParameters["pro_EfeAcumula"][$RowNumber] = CCGetFromPost("pro_EfeAcumula_" . $RowNumber);
            $this->FormParameters["pro_Costeo"][$RowNumber] = CCGetFromPost("pro_Costeo_" . $RowNumber);
            $this->FormParameters["pro_Grupo"][$RowNumber] = CCGetFromPost("pro_Grupo_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @3-D8090019
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["pro_codProceso"] = $this->CachedColumns["pro_codProceso"][$RowNumber];
            $this->ds->CachedColumns["cla_TipoTransacc"] = $this->CachedColumns["cla_TipoTransacc"][$RowNumber];
            $this->ds->CachedColumns["pro_Secuencia"] = $this->CachedColumns["pro_Secuencia"][$RowNumber];
            $this->pro_codProceso->SetText($this->FormParameters["pro_codProceso"][$RowNumber], $RowNumber);
            $this->pro_Secuencia->SetText($this->FormParameters["pro_Secuencia"][$RowNumber], $RowNumber);
            $this->cla_TipoTransacc->SetText($this->FormParameters["cla_TipoTransacc"][$RowNumber], $RowNumber);
            $this->pro_Orden->SetText($this->FormParameters["pro_Orden"][$RowNumber], $RowNumber);
            $this->pro_Signo->SetText($this->FormParameters["pro_Signo"][$RowNumber], $RowNumber);
            $this->pro_Efecto->SetText($this->FormParameters["pro_Efecto"][$RowNumber], $RowNumber);
            $this->pro_EfeAcumula->SetText($this->FormParameters["pro_EfeAcumula"][$RowNumber], $RowNumber);
            $this->pro_Costeo->SetText($this->FormParameters["pro_Costeo"][$RowNumber], $RowNumber);
            $this->pro_Grupo->SetText($this->FormParameters["pro_Grupo"][$RowNumber], $RowNumber);
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

//ValidateRow Method @3-C8D6C98E
    function ValidateRow($RowNumber)
    {
        $this->pro_codProceso->Validate();
        $this->pro_Secuencia->Validate();
        $this->cla_TipoTransacc->Validate();
        $this->pro_Orden->Validate();
        $this->pro_Signo->Validate();
        $this->pro_Efecto->Validate();
        $this->pro_EfeAcumula->Validate();
        $this->pro_Costeo->Validate();
        $this->pro_Grupo->Validate();
        $this->CheckBox_Delete->Validate();
        $this->RowErrors = new clsErrors();
        $errors = $this->pro_codProceso->Errors->ToString();
        $errors .= $this->pro_Secuencia->Errors->ToString();
        $errors .= $this->cla_TipoTransacc->Errors->ToString();
        $errors .= $this->pro_Orden->Errors->ToString();
        $errors .= $this->pro_Signo->Errors->ToString();
        $errors .= $this->pro_Efecto->Errors->ToString();
        $errors .= $this->pro_EfeAcumula->Errors->ToString();
        $errors .= $this->pro_Costeo->Errors->ToString();
        $errors .= $this->pro_Grupo->Errors->ToString();
        $errors .= $this->CheckBox_Delete->Errors->ToString();
        $this->pro_codProceso->Errors->Clear();
        $this->pro_Secuencia->Errors->Clear();
        $this->cla_TipoTransacc->Errors->Clear();
        $this->pro_Orden->Errors->Clear();
        $this->pro_Signo->Errors->Clear();
        $this->pro_Efecto->Errors->Clear();
        $this->pro_EfeAcumula->Errors->Clear();
        $this->pro_Costeo->Errors->Clear();
        $this->pro_Grupo->Errors->Clear();
        $this->CheckBox_Delete->Errors->Clear();
        $errors .=$this->RowErrors->ToString();
        $this->RowsErrors[$RowNumber] = $errors;
        return $errors ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @3-63FC50CA
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["pro_codProceso"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cla_TipoTransacc"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_Orden"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_Signo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_Efecto"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_EfeAcumula"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_Costeo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_Grupo"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @3-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-6A172129
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
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @3-60D6DE48
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["pro_codProceso"] = $this->CachedColumns["pro_codProceso"][$RowNumber];
            $this->ds->CachedColumns["cla_TipoTransacc"] = $this->CachedColumns["cla_TipoTransacc"][$RowNumber];
            $this->ds->CachedColumns["pro_Secuencia"] = $this->CachedColumns["pro_Secuencia"][$RowNumber];
            $this->pro_codProceso->SetText($this->FormParameters["pro_codProceso"][$RowNumber], $RowNumber);
            $this->pro_Secuencia->SetText($this->FormParameters["pro_Secuencia"][$RowNumber], $RowNumber);
            $this->cla_TipoTransacc->SetText($this->FormParameters["cla_TipoTransacc"][$RowNumber], $RowNumber);
            $this->pro_Orden->SetText($this->FormParameters["pro_Orden"][$RowNumber], $RowNumber);
            $this->pro_Signo->SetText($this->FormParameters["pro_Signo"][$RowNumber], $RowNumber);
            $this->pro_Efecto->SetText($this->FormParameters["pro_Efecto"][$RowNumber], $RowNumber);
            $this->pro_EfeAcumula->SetText($this->FormParameters["pro_EfeAcumula"][$RowNumber], $RowNumber);
            $this->pro_Costeo->SetText($this->FormParameters["pro_Costeo"][$RowNumber], $RowNumber);
            $this->pro_Grupo->SetText($this->FormParameters["pro_Grupo"][$RowNumber], $RowNumber);
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

//InsertRow Method @3-59D27455
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->cla_TipoTransacc->SetValue($this->cla_TipoTransacc->GetValue());
        $this->ds->pro_Orden->SetValue($this->pro_Orden->GetValue());
        $this->ds->pro_Signo->SetValue($this->pro_Signo->GetValue());
        $this->ds->pro_Efecto->SetValue($this->pro_Efecto->GetValue());
        $this->ds->pro_EfeAcumula->SetValue($this->pro_EfeAcumula->GetValue());
        $this->ds->pro_Costeo->SetValue($this->pro_Costeo->GetValue());
        $this->ds->pro_Grupo->SetValue($this->pro_Grupo->GetValue());
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

//UpdateRow Method @3-E0AAF90C
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->pro_codProceso->SetValue($this->pro_codProceso->GetValue());
        $this->ds->cla_TipoTransacc->SetValue($this->cla_TipoTransacc->GetValue());
        $this->ds->pro_Orden->SetValue($this->pro_Orden->GetValue());
        $this->ds->pro_Signo->SetValue($this->pro_Signo->GetValue());
        $this->ds->pro_Efecto->SetValue($this->pro_Efecto->GetValue());
        $this->ds->pro_EfeAcumula->SetValue($this->pro_EfeAcumula->GetValue());
        $this->ds->pro_Costeo->SetValue($this->pro_Costeo->GetValue());
        $this->ds->pro_Grupo->SetValue($this->pro_Grupo->GetValue());
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

//DeleteRow Method @3-E90CB5E3
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

//FormScript Method @3-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @3-A1807593
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
                $this->CachedColumns["pro_codProceso"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cla_TipoTransacc"][$RowNumber] = $piece;
                $piece = $pieces[$i + 2];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["pro_Secuencia"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["pro_codProceso"][$RowNumber] = "";
                $this->CachedColumns["cla_TipoTransacc"][$RowNumber] = "";
                $this->CachedColumns["pro_Secuencia"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @3-A2DEDB74
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["pro_codProceso"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cla_TipoTransacc"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["pro_Secuencia"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @3-9A3C055F
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cla_TipoTransacc->Prepare();
        $this->pro_Signo->Prepare();

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
                    $this->CachedColumns["pro_codProceso"][$RowNumber] = $this->ds->CachedColumns["pro_codProceso"];
                    $this->CachedColumns["cla_TipoTransacc"][$RowNumber] = $this->ds->CachedColumns["cla_TipoTransacc"];
                    $this->CachedColumns["pro_Secuencia"][$RowNumber] = $this->ds->CachedColumns["pro_Secuencia"];
                    $this->pro_codProceso->SetValue($this->ds->pro_codProceso->GetValue());
                    $this->pro_Secuencia->SetValue($this->ds->pro_Secuencia->GetValue());
                    $this->cla_TipoTransacc->SetValue($this->ds->cla_TipoTransacc->GetValue());
                    $this->pro_Orden->SetValue($this->ds->pro_Orden->GetValue());
                    $this->pro_Signo->SetValue($this->ds->pro_Signo->GetValue());
                    $this->pro_Efecto->SetValue($this->ds->pro_Efecto->GetValue());
                    $this->pro_EfeAcumula->SetValue($this->ds->pro_EfeAcumula->GetValue());
                    $this->pro_Costeo->SetValue($this->ds->pro_Costeo->GetValue());
                    $this->pro_Grupo->SetValue($this->ds->pro_Grupo->GetValue());
                    $this->ValidateRow($RowNumber);
                } else if (!$this->FormSubmitted){
                    $this->CachedColumns["pro_codProceso"][$RowNumber] = "";
                    $this->CachedColumns["cla_TipoTransacc"][$RowNumber] = "";
                    $this->CachedColumns["pro_Secuencia"][$RowNumber] = "";
                    $this->pro_codProceso->SetText("");
                    $this->pro_Secuencia->SetText("");
                    $this->cla_TipoTransacc->SetText("");
                    $this->pro_Orden->SetText("");
                    $this->pro_Signo->SetText("");
                    $this->pro_Efecto->SetText("");
                    $this->pro_EfeAcumula->SetText("");
                    $this->pro_Costeo->SetText("");
                    $this->pro_Grupo->SetText("");
                    $this->CheckBox_Delete->SetText("");
                } else {
                    $this->pro_codProceso->SetText($this->FormParameters["pro_codProceso"][$RowNumber], $RowNumber);
                    $this->pro_Secuencia->SetText($this->FormParameters["pro_Secuencia"][$RowNumber], $RowNumber);
                    $this->cla_TipoTransacc->SetText($this->FormParameters["cla_TipoTransacc"][$RowNumber], $RowNumber);
                    $this->pro_Orden->SetText($this->FormParameters["pro_Orden"][$RowNumber], $RowNumber);
                    $this->pro_Signo->SetText($this->FormParameters["pro_Signo"][$RowNumber], $RowNumber);
                    $this->pro_Efecto->SetText($this->FormParameters["pro_Efecto"][$RowNumber], $RowNumber);
                    $this->pro_EfeAcumula->SetText($this->FormParameters["pro_EfeAcumula"][$RowNumber], $RowNumber);
                    $this->pro_Costeo->SetText($this->FormParameters["pro_Costeo"][$RowNumber], $RowNumber);
                    $this->pro_Grupo->SetText($this->FormParameters["pro_Grupo"][$RowNumber], $RowNumber);
                    $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->pro_codProceso->Show($RowNumber);
                $this->pro_Secuencia->Show($RowNumber);
                $this->cla_TipoTransacc->Show($RowNumber);
                $this->pro_Orden->Show($RowNumber);
                $this->pro_Signo->Show($RowNumber);
                $this->pro_Efecto->Show($RowNumber);
                $this->pro_EfeAcumula->Show($RowNumber);
                $this->pro_Costeo->Show($RowNumber);
                $this->pro_Grupo->Show($RowNumber);
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
        $this->pro_Descripcion->Show();
        $this->Sorter_pro_codProceso->Show();
        $this->Sorter_cla_TipoTransacc->Show();
        $this->Sorter_pro_Orden->Show();
        $this->Sorter_pro_Signo->Show();
        $this->Sorter_pro_Efecto->Show();
        $this->Sorter_pro_EfeAcumula->Show();
        $this->Sorter_pro_Costeo->Show();
        $this->Sorter_pro_Grupo->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();

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

} //End invprocesos Class @3-FCB6E20C

class clsinvprocesosDataSource extends clsDBdatos {  //invprocesosDataSource Class @3-012F9289

//DataSource Variables @3-D98D80FF
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
    var $pro_codProceso;
    var $pro_Secuencia;
    var $cla_TipoTransacc;
    var $pro_Orden;
    var $pro_Signo;
    var $pro_Efecto;
    var $pro_EfeAcumula;
    var $pro_Costeo;
    var $pro_Grupo;
    var $CheckBox_Delete;
//End DataSource Variables

//DataSourceClass_Initialize Event @3-3FC90586
    function clsinvprocesosDataSource()
    {
        $this->ErrorBlock = "EditableGrid invprocesos/Error";
        $this->Initialize();
        $this->pro_codProceso = new clsField("pro_codProceso", ccsInteger, "");
        $this->pro_Secuencia = new clsField("pro_Secuencia", ccsText, "");
        $this->cla_TipoTransacc = new clsField("cla_TipoTransacc", ccsText, "");
        $this->pro_Orden = new clsField("pro_Orden", ccsInteger, "");
        $this->pro_Signo = new clsField("pro_Signo", ccsInteger, "");
        $this->pro_Efecto = new clsField("pro_Efecto", ccsInteger, "");
        $this->pro_EfeAcumula = new clsField("pro_EfeAcumula", ccsInteger, "");
        $this->pro_Costeo = new clsField("pro_Costeo", ccsInteger, "");
        $this->pro_Grupo = new clsField("pro_Grupo", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @3-009E39AE
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_pro_codProceso" => array("pro_codProceso", ""), 
            "Sorter_cla_TipoTransacc" => array("cla_TipoTransacc", ""), 
            "Sorter_pro_Orden" => array("pro_Orden", ""), 
            "Sorter_pro_Signo" => array("pro_Signo", ""), 
            "Sorter_pro_Efecto" => array("pro_Efecto", ""), 
            "Sorter_pro_EfeAcumula" => array("pro_EfeAcumula", ""), 
            "Sorter_pro_Costeo" => array("pro_Costeo", ""), 
            "Sorter_pro_Grupo" => array("pro_Grupo", "")));
    }
//End SetOrder Method

//Prepare Method @3-FB94F933
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlpro_codProceso", ccsInteger, "", "", $this->Parameters["urlpro_codProceso"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pro_codProceso", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @3-54031A76
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM invprocesos";
        $this->SQL = "SELECT *  " .
        "FROM invprocesos";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @3-692FE3AC
    function SetValues()
    {
        $this->CachedColumns["pro_codProceso"] = $this->f("pro_codProceso");
        $this->CachedColumns["cla_TipoTransacc"] = $this->f("cla_TipoTransacc");
        $this->CachedColumns["pro_Secuencia"] = $this->f("pro_Secuencia");
        $this->pro_codProceso->SetDBValue(trim($this->f("pro_codProceso")));
        $this->pro_Secuencia->SetDBValue($this->f("pro_Secuencia"));
        $this->cla_TipoTransacc->SetDBValue($this->f("cla_TipoTransacc"));
        $this->pro_Orden->SetDBValue(trim($this->f("pro_Orden")));
        $this->pro_Signo->SetDBValue(trim($this->f("pro_Signo")));
        $this->pro_Efecto->SetDBValue(trim($this->f("pro_Efecto")));
        $this->pro_EfeAcumula->SetDBValue(trim($this->f("pro_EfeAcumula")));
        $this->pro_Costeo->SetDBValue(trim($this->f("pro_Costeo")));
        $this->pro_Grupo->SetDBValue(trim($this->f("pro_Grupo")));
    }
//End SetValues Method

//Insert Method @3-9C4F7D8C
    function Insert()
    {
        $this->CmdExecution = true;
        $this->cp["pro_codProceso"] = new clsSQLParameter("urlpro_codProceso", ccsInteger, "", "", CCGetFromGet("pro_codProceso", ""), "", false, $this->ErrorBlock);
        $this->cp["cla_TipoTransacc"] = new clsSQLParameter("ctrlcla_TipoTransacc", ccsText, "", "", $this->cla_TipoTransacc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Orden"] = new clsSQLParameter("ctrlpro_Orden", ccsInteger, "", "", $this->pro_Orden->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Signo"] = new clsSQLParameter("ctrlpro_Signo", ccsInteger, "", "", $this->pro_Signo->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["pro_Efecto"] = new clsSQLParameter("ctrlpro_Efecto", ccsInteger, "", "", $this->pro_Efecto->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_EfeAcumula"] = new clsSQLParameter("ctrlpro_EfeAcumula", ccsInteger, "", "", $this->pro_EfeAcumula->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_Costeo"] = new clsSQLParameter("ctrlpro_Costeo", ccsInteger, "", "", $this->pro_Costeo->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_Grupo"] = new clsSQLParameter("ctrlpro_Grupo", ccsInteger, "", "", $this->pro_Grupo->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO invprocesos ("
             . "pro_codProceso, "
             . "cla_TipoTransacc, "
             . "pro_Orden, "
             . "pro_Signo, "
             . "pro_Efecto, "
             . "pro_EfeAcumula, "
             . "pro_Costeo, "
             . "pro_Grupo"
             . ") VALUES ("
             . $this->ToSQL($this->cp["pro_codProceso"]->GetDBValue(), $this->cp["pro_codProceso"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_TipoTransacc"]->GetDBValue(), $this->cp["cla_TipoTransacc"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Orden"]->GetDBValue(), $this->cp["pro_Orden"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Signo"]->GetDBValue(), $this->cp["pro_Signo"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Efecto"]->GetDBValue(), $this->cp["pro_Efecto"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_EfeAcumula"]->GetDBValue(), $this->cp["pro_EfeAcumula"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Costeo"]->GetDBValue(), $this->cp["pro_Costeo"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Grupo"]->GetDBValue(), $this->cp["pro_Grupo"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @3-98DE22DA
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["pro_codProceso"] = new clsSQLParameter("ctrlpro_codProceso", ccsInteger, "", "", $this->pro_codProceso->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cla_TipoTransacc"] = new clsSQLParameter("ctrlcla_TipoTransacc", ccsText, "", "", $this->cla_TipoTransacc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Orden"] = new clsSQLParameter("ctrlpro_Orden", ccsInteger, "", "", $this->pro_Orden->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Signo"] = new clsSQLParameter("ctrlpro_Signo", ccsInteger, "", "", $this->pro_Signo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Efecto"] = new clsSQLParameter("ctrlpro_Efecto", ccsInteger, "", "", $this->pro_Efecto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_EfeAcumula"] = new clsSQLParameter("ctrlpro_EfeAcumula", ccsInteger, "", "", $this->pro_EfeAcumula->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Costeo"] = new clsSQLParameter("ctrlpro_Costeo", ccsInteger, "", "", $this->pro_Costeo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Grupo"] = new clsSQLParameter("ctrlpro_Grupo", ccsInteger, "", "", $this->pro_Grupo->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlpro_codProceso", ccsInteger, "", "", CCGetFromGet("pro_codProceso", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $wp->AddParameter("2", "dspro_Secuencia", ccsInteger, "", "", $this->CachedColumns["pro_Secuencia"], "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "pro_codProceso", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "pro_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(
             false, 
             $wp->Criterion[1], 
             $wp->Criterion[2]);
        $this->SQL = "UPDATE invprocesos SET "
             . "pro_codProceso=" . $this->ToSQL($this->cp["pro_codProceso"]->GetDBValue(), $this->cp["pro_codProceso"]->DataType) . ", "
             . "cla_TipoTransacc=" . $this->ToSQL($this->cp["cla_TipoTransacc"]->GetDBValue(), $this->cp["cla_TipoTransacc"]->DataType) . ", "
             . "pro_Orden=" . $this->ToSQL($this->cp["pro_Orden"]->GetDBValue(), $this->cp["pro_Orden"]->DataType) . ", "
             . "pro_Signo=" . $this->ToSQL($this->cp["pro_Signo"]->GetDBValue(), $this->cp["pro_Signo"]->DataType) . ", "
             . "pro_Efecto=" . $this->ToSQL($this->cp["pro_Efecto"]->GetDBValue(), $this->cp["pro_Efecto"]->DataType) . ", "
             . "pro_EfeAcumula=" . $this->ToSQL($this->cp["pro_EfeAcumula"]->GetDBValue(), $this->cp["pro_EfeAcumula"]->DataType) . ", "
             . "pro_Costeo=" . $this->ToSQL($this->cp["pro_Costeo"]->GetDBValue(), $this->cp["pro_Costeo"]->DataType) . ", "
             . "pro_Grupo=" . $this->ToSQL($this->cp["pro_Grupo"]->GetDBValue(), $this->cp["pro_Grupo"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @3-0CD3F406
    function Delete()
    {
        $this->CmdExecution = true;
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlpro_codProceso", ccsInteger, "", "", CCGetFromGet("pro_codProceso", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $wp->AddParameter("2", "dspro_Secuencia", ccsInteger, "", "", $this->CachedColumns["pro_Secuencia"], "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "pro_codProceso", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "pro_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(
             false, 
             $wp->Criterion[1], 
             $wp->Criterion[2]);
        $this->SQL = "DELETE FROM invprocesos";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End invprocesosDataSource Class @3-FCB6E20C

//Initialize Page @1-369554F4
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

$FileName = "InAdPr_mant.php";
$Redirect = "";
$TemplateFileName = "InAdPr_mant.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-8B971E88
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$genparametros = new clsGridgenparametros();
$invprocesos = new clsEditableGridinvprocesos();
$genparametros->Initialize();
$invprocesos->Initialize();

// Events
include("./InAdPr_mant_events.php");
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

//Execute Components @1-7319AF8A
$Cabecera->Operations();
$invprocesos->Operation();
//End Execute Components

//Go to destination page @1-0122787F
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($genparametros);
    unset($invprocesos);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-93041FDA
$Cabecera->Show("Cabecera");
$genparametros->Show();
$invprocesos->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$KLPJIEBE7T4D0L3N = "<center><font face=\"Arial\"><small>Gene&#114;&#97;&#116;&#101;d <!-- CCS -->w&#105;t&#104; <!-- SCC -->&#67;&#111;&#100;e&#67;&#104;&#97;rge <!-- CCS -->S&#116;u&#100;i&#111;.</small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $KLPJIEBE7T4D0L3N . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $KLPJIEBE7T4D0L3N . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $KLPJIEBE7T4D0L3N;
}
echo $main_block;
//End Show Page

//Unload Page @1-77E65440
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($genparametros);
unset($invprocesos);
unset($Tpl);
//End Unload Page


?>
