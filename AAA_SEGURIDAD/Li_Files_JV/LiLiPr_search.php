<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");

//End Include Common Files

//Include Page implementation @258-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqprecaja_qry { //liqprecaja_qry Class @51-B06F2D81

//Variables @51-CB19EB75

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

//Class_Initialize Event @51-DC09A09C
    function clsRecordliqprecaja_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqprecaja_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "liqprecaja_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pro_Ano = new clsControl(ccsTextBox, "pro_Ano", "pro_Ano", ccsInteger, "", CCGetRequestParam("pro_Ano", $Method));
            $this->pro_Semana = new clsControl(ccsTextBox, "pro_Semana", "pro_Semana", ccsInteger, "", CCGetRequestParam("pro_Semana", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            if(!$this->FormSubmitted) {
                if(!is_array($this->pro_Ano->Value) && !strlen($this->pro_Ano->Value) && $this->pro_Ano->Value !== false)
                $this->pro_Ano->SetText((isset($_SESSION['anio']))?$_SESSION['anio']:05);
                if(!is_array($this->pro_Semana->Value) && !strlen($this->pro_Semana->Value) && $this->pro_Semana->Value !== false)
                $this->pro_Semana->SetText((isset($_SESSION['semana']))?$_SESSION['semana']:'');
            }
        }
    }
//End Class_Initialize Event

//Validate Method @51-B76DFCE3
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->pro_Ano->Validate() && $Validation);
        $Validation = ($this->pro_Semana->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @51-355879D4
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pro_Ano->Errors->Count());
        $errors = ($errors || $this->pro_Semana->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @51-E89BE357
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
        $Redirect = "LiLiPr_search.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiLiPr_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @51-219F35E2
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
            $Error .= $this->pro_Ano->Errors->ToString();
            $Error .= $this->pro_Semana->Errors->ToString();
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

        $this->pro_Ano->Show();
        $this->pro_Semana->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liqprecaja_qry Class @51-FCB6E20C

class clsEditableGridliqprecaja_list { //liqprecaja_list Class @2-3BC66E1F

//Variables @2-D65BAEF2

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
    var $Navigator;
    var $Sorter_pre_ID;
    var $Sorter_pre_RefOperativa;
    var $Sorter_pre_CodProducto;
    var $Sorter_pre_CodMarca;
    var $Sorter_pre_CodEmpaque;
    var $Sorter_pre_GruLiquidacion;
    var $Sorter_pre_Zona;
    var $Sorter_pre_Productor;
    var $Sorter_pre_FecInicio;
    var $Sorter_pre_Usuario;
    var $Sorter_pre_Estado;
//End Variables

//Class_Initialize Event @2-969A8EFE
    function clsEditableGridliqprecaja_list()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid liqprecaja_list/Error";
        $this->ComponentName = "liqprecaja_list";
        $this->CachedColumns["pro_ID"][0] = "pro_ID";
        $this->ds = new clsliqprecaja_listDataSource();

        $this->PageSize = 15;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 5;
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

        $this->SorterName = CCGetParam("liqprecaja_listOrder", "");
        $this->SorterDirection = CCGetParam("liqprecaja_listDir", "");

        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
        $this->Sorter_pre_ID = new clsSorter($this->ComponentName, "Sorter_pre_ID", $FileName);
        $this->Sorter_pre_RefOperativa = new clsSorter($this->ComponentName, "Sorter_pre_RefOperativa", $FileName);
        $this->Sorter_pre_CodProducto = new clsSorter($this->ComponentName, "Sorter_pre_CodProducto", $FileName);
        $this->Sorter_pre_CodMarca = new clsSorter($this->ComponentName, "Sorter_pre_CodMarca", $FileName);
        $this->Sorter_pre_CodEmpaque = new clsSorter($this->ComponentName, "Sorter_pre_CodEmpaque", $FileName);
        $this->Sorter_pre_GruLiquidacion = new clsSorter($this->ComponentName, "Sorter_pre_GruLiquidacion", $FileName);
        $this->Sorter_pre_Zona = new clsSorter($this->ComponentName, "Sorter_pre_Zona", $FileName);
        $this->Sorter_pre_Productor = new clsSorter($this->ComponentName, "Sorter_pre_Productor", $FileName);
        $this->Sorter_pre_FecInicio = new clsSorter($this->ComponentName, "Sorter_pre_FecInicio", $FileName);
        $this->Sorter_pre_Usuario = new clsSorter($this->ComponentName, "Sorter_pre_Usuario", $FileName);
        $this->Sorter_pre_Estado = new clsSorter($this->ComponentName, "Sorter_pre_Estado", $FileName);
        $this->pre_ID = new clsControl(ccsTextBox, "pre_ID", "Pre ID", ccsInteger, "");
        $this->pre_Ano = new clsControl(ccsHidden, "pre_Ano", "Pre Ano", ccsInteger, "");
        $this->pre_Semana = new clsControl(ccsHidden, "pre_Semana", "Pre Semana", ccsInteger, "");
        $this->caj_Abreviatura = new clsControl(ccsHidden, "caj_Abreviatura", "Caj Abreviatura", ccsText, "");
        $this->caj_Descripcion = new clsControl(ccsHidden, "caj_Descripcion", "Caj Descripcion", ccsText, "");
        $this->pre_RefOperativa = new clsControl(ccsListBox, "pre_RefOperativa", "Embarque", ccsInteger, "");
        $this->pre_RefOperativa->DSType = dsSQL;
        list($this->pre_RefOperativa->BoundColumn, $this->pre_RefOperativa->TextColumn, $this->pre_RefOperativa->DBFormat) = array("emb_RefOperativa", "txt_Descrip", "");
        $this->pre_RefOperativa->ds = new clsDBdatos();
        $this->pre_RefOperativa->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
        $this->pre_RefOperativa->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
        $this->pre_RefOperativa->ds->wp = new clsSQLParameters();
        $this->pre_RefOperativa->ds->wp->AddParameter("1", "urlpro_Ano", ccsInteger, "", "", $this->pre_RefOperativa->ds->Parameters["urlpro_Ano"], 0, false);
        $this->pre_RefOperativa->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_RefOperativa->ds->Parameters["urlpro_Semana"], 0, false);
        $this->pre_RefOperativa->ds->SQL = "SELECT emb_RefOperativa, concat_ws('- ', buq_Descripcion, emb_NumViaje,  emb_SemInicio, emb_SemTermino, par_Descripcion ) as txt_Descrip " .
        "FROM (liqembarques LEFT JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque) LEFT JOIN genparametros ON par_Clave = 'IMARCA' AND " .
	"liqembarques.emb_CodMarca = genparametros.par_Secuencia " .
        "WHERE emb_AnoOperacion = " . $this->pre_RefOperativa->ds->SQLValue($this->pre_RefOperativa->ds->wp->GetDBValue("1"), ccsInteger) . " and  " .
        "" . $this->pre_RefOperativa->ds->SQLValue($this->pre_RefOperativa->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino ";
        $this->pre_CodProducto = new clsControl(ccsListBox, "pre_CodProducto", "Codigo de Producto", ccsInteger, "");
        $this->pre_CodProducto->DSType = dsSQL;
        list($this->pre_CodProducto->BoundColumn, $this->pre_CodProducto->TextColumn, $this->pre_CodProducto->DBFormat) = array("tad_CodProducto", "txt_Descrip", "");
        $this->pre_CodProducto->ds = new clsDBdatos();
        $this->pre_CodProducto->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
        $this->pre_CodProducto->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
        $this->pre_CodProducto->ds->wp = new clsSQLParameters();
        $this->pre_CodProducto->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_CodProducto->ds->Parameters["urlpro_Ano"], -1, false);
        $this->pre_CodProducto->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_CodProducto->ds->Parameters["urlpro_Semana"], -1, false);
        $this->pre_CodProducto->ds->SQL = "SELECT  distinct tad_CodProducto as tad_CodProducto, concat(act_Descripcion, act_Descripcion1) as txt_Descrip " .
        "FROM liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa  " .
        "      JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja " .
        "     LEFT JOIN conactivos ON act_CodAuxiliar = tad_CodProducto " .
        "WHERE   " . $this->pre_CodProducto->ds->SQLValue($this->pre_CodProducto->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino ";
        /*
        "WHERE emb_AnoOperacion = " . $this->pre_CodProducto->ds->SQLValue($this->pre_CodProducto->ds->wp->GetDBValue("1"), ccsText) . " and  " .
        "" . $this->pre_CodProducto->ds->SQLValue($this->pre_CodProducto->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino";
        */
        $this->pre_CodMarca = new clsControl(ccsListBox, "pre_CodMarca", "Pre Cod Marca", ccsInteger, "");
        $this->pre_CodMarca->DSType = dsSQL;
        list($this->pre_CodMarca->BoundColumn, $this->pre_CodMarca->TextColumn, $this->pre_CodMarca->DBFormat) = array("tad_CodMarca", "par_Descripcion", "");
        $this->pre_CodMarca->ds = new clsDBdatos();
        $this->pre_CodMarca->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
        $this->pre_CodMarca->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
        $this->pre_CodMarca->ds->wp = new clsSQLParameters();
        $this->pre_CodMarca->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_CodMarca->ds->Parameters["urlpro_Ano"], -1, false);
        $this->pre_CodMarca->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_CodMarca->ds->Parameters["urlpro_Semana"], -1, false);
        $this->pre_CodMarca->ds->SQL = "SELECT  distinct tad_CodMarca, par_Descripcion " .
        "FROM liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa  " .
        "     JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja " .
        "     LEFT JOIN genparametros ON par_Clave = 'IMARCA' AND par_Secuencia = tad_CodMarca " .
        "WHERE emb_AnoOperacion = " . $this->pre_CodMarca->ds->SQLValue($this->pre_CodMarca->ds->wp->GetDBValue("1"), ccsText) . " and  " .
        "" . $this->pre_CodMarca->ds->SQLValue($this->pre_CodMarca->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino ";
        $this->pre_CodMarca->ds->Order = "2";
        $this->pre_CodEmpaque = new clsControl(ccsListBox, "pre_CodEmpaque", "Pre Cod Empaque", ccsInteger, "");
        $this->pre_CodEmpaque->DSType = dsSQL;
        list($this->pre_CodEmpaque->BoundColumn, $this->pre_CodEmpaque->TextColumn, $this->pre_CodEmpaque->DBFormat) = array("tad_CodCaja", "caj_Descripcion", "");
        $this->pre_CodEmpaque->ds = new clsDBdatos();
        $this->pre_CodEmpaque->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
        $this->pre_CodEmpaque->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
        $this->pre_CodEmpaque->ds->wp = new clsSQLParameters();
        $this->pre_CodEmpaque->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_CodEmpaque->ds->Parameters["urlpro_Ano"], -1, false);
        $this->pre_CodEmpaque->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_CodEmpaque->ds->Parameters["urlpro_Semana"], -1, false);
        $this->pre_CodEmpaque->ds->SQL = "SELECT  distinct tad_CodCaja, caj_Descripcion " .
        "FROM liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa  " .
        "      JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja " .
        "      JOIN liqcajas ON caj_CodCaja = tad_CodCaja " .
        "WHERE emb_AnoOperacion = " . $this->pre_CodEmpaque->ds->SQLValue($this->pre_CodEmpaque->ds->wp->GetDBValue("1"), ccsText) . " and  " .
        "" . $this->pre_CodEmpaque->ds->SQLValue($this->pre_CodEmpaque->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino  " .
        "";
        $this->pre_CodEmpaque->ds->Order = "2";
        $this->pre_GruLiquidacion = new clsControl(ccsListBox, "pre_GruLiquidacion", "Pre Gru Liquidacion", ccsInteger, "");
        $this->pre_GruLiquidacion->DSType = dsSQL;
        list($this->pre_GruLiquidacion->BoundColumn, $this->pre_GruLiquidacion->TextColumn, $this->pre_GruLiquidacion->DBFormat) = array("per_codAuxiliar", "txt_Descrip", "");
        $this->pre_GruLiquidacion->ds = new clsDBdatos();
        $this->pre_GruLiquidacion->ds->SQL = "SELECT per_codAuxiliar, concat(left(per_Apellidos,12),' ', concat(per_Nombres)) as txt_Descrip  " .
        "FROM concategorias INNER JOIN conpersonas ON cat_Categoria = 51  AND concategorias.cat_CodAuxiliar = conpersonas.per_CodAuxiliar ";
        $this->pre_GruLiquidacion->ds->Order = "txt_Descrip";
        $this->pre_Zona = new clsControl(ccsListBox, "pre_Zona", "Pre Zona", ccsInteger, "");
        $this->pre_Zona->DSType = dsSQL;
        list($this->pre_Zona->BoundColumn, $this->pre_Zona->TextColumn, $this->pre_Zona->DBFormat) = array("tac_Zona", "par_Descripcion", "");
        $this->pre_Zona->ds = new clsDBdatos();
        $this->pre_Zona->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
        $this->pre_Zona->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
        $this->pre_Zona->ds->wp = new clsSQLParameters();
        $this->pre_Zona->ds->wp->AddParameter("1", "urlpro_Ano", ccsText, "", "", $this->pre_Zona->ds->Parameters["urlpro_Ano"], -1, false);
        $this->pre_Zona->ds->wp->AddParameter("2", "urlpro_Semana", ccsText, "", "", $this->pre_Zona->ds->Parameters["urlpro_Semana"], -1, false);
        $this->pre_Zona->ds->SQL = "SELECT  distinct tac_Zona, par_Descripcion " .
        "FROM liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa " .
        "      JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja " .
        "      JOIN genparametros ON par_CLAVE='LSZON' AND par_secuencia = tac_Zona " .
        "WHERE emb_AnoOperacion = " . $this->pre_Zona->ds->SQLValue($this->pre_Zona->ds->wp->GetDBValue("1"), ccsText) . " and  " .
        "" . $this->pre_Zona->ds->SQLValue($this->pre_Zona->ds->wp->GetDBValue("2"), ccsText) . " between emb_SemInicio AND emb_SemTermino ";
        $this->pre_Zona->ds->Order = "2";
        $this->pre_Productor = new clsControl(ccsTextBox, "pre_Productor", "Pre Productor", ccsInteger, "");
        $this->per_Apellidos = new clsControl(ccsTextBox, "per_Apellidos", "Nombre del Productor", ccsText, "");
        $this->pro_FechaCierre = new clsControl(ccsTextBox, "pro_FechaCierre", "Fecha de Cierre", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->DatePicker_pre_FecInicio = new clsDatePicker("DatePicker_pre_FecInicio", "liqprecaja_list", "pro_FechaCierre");
        $this->pro_FechaLiquid = new clsControl(ccsTextBox, "pro_FechaLiquid", "Fecha de Liquidación", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->DatePicker1 = new clsDatePicker("DatePicker1", "liqprecaja_list", "pro_FechaLiquid");
        $this->pre_Usuario = new clsControl(ccsTextBox, "pre_Usuario", "Pre Usuario", ccsText, "");
        $this->TextBox1 = new clsControl(ccsTextBox, "TextBox1", "TextBox1", ccsDate, Array("dd", "/", "mmm", "/", "yy", " ", "HH", ":", "nn"));
        $this->pre_Estado = new clsControl(ccsTextBox, "pre_Estado", "Pre Estado", ccsInteger, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
    }
//End Class_Initialize Event

//Initialize Method @2-5E9B64B4
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlpro_Ano"] = CCGetFromGet("pro_Ano", "");
        $this->ds->Parameters["urlpro_Semana"] = CCGetFromGet("pro_Semana", "");
    }
//End Initialize Method

//GetFormParameters Method @2-5338E08B
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["pre_ID"][$RowNumber] = CCGetFromPost("pre_ID_" . $RowNumber);
            $this->FormParameters["pre_Ano"][$RowNumber] = CCGetFromPost("pre_Ano_" . $RowNumber);
            $this->FormParameters["pre_Semana"][$RowNumber] = CCGetFromPost("pre_Semana_" . $RowNumber);
            $this->FormParameters["caj_Abreviatura"][$RowNumber] = CCGetFromPost("caj_Abreviatura_" . $RowNumber);
            $this->FormParameters["caj_Descripcion"][$RowNumber] = CCGetFromPost("caj_Descripcion_" . $RowNumber);
            $this->FormParameters["pre_RefOperativa"][$RowNumber] = CCGetFromPost("pre_RefOperativa_" . $RowNumber);
            $this->FormParameters["pre_CodProducto"][$RowNumber] = CCGetFromPost("pre_CodProducto_" . $RowNumber);
            $this->FormParameters["pre_CodMarca"][$RowNumber] = CCGetFromPost("pre_CodMarca_" . $RowNumber);
            $this->FormParameters["pre_CodEmpaque"][$RowNumber] = CCGetFromPost("pre_CodEmpaque_" . $RowNumber);
            $this->FormParameters["pre_GruLiquidacion"][$RowNumber] = CCGetFromPost("pre_GruLiquidacion_" . $RowNumber);
            $this->FormParameters["pre_Zona"][$RowNumber] = CCGetFromPost("pre_Zona_" . $RowNumber);
            $this->FormParameters["pre_Productor"][$RowNumber] = CCGetFromPost("pre_Productor_" . $RowNumber);
            $this->FormParameters["per_Apellidos"][$RowNumber] = CCGetFromPost("per_Apellidos_" . $RowNumber);
            $this->FormParameters["pro_FechaCierre"][$RowNumber] = CCGetFromPost("pro_FechaCierre_" . $RowNumber);
            $this->FormParameters["pro_FechaLiquid"][$RowNumber] = CCGetFromPost("pro_FechaLiquid_" . $RowNumber);
            $this->FormParameters["pre_Usuario"][$RowNumber] = CCGetFromPost("pre_Usuario_" . $RowNumber);
            $this->FormParameters["TextBox1"][$RowNumber] = CCGetFromPost("TextBox1_" . $RowNumber);
            $this->FormParameters["pre_Estado"][$RowNumber] = CCGetFromPost("pre_Estado_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-F2D8201C
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["pro_ID"] = $this->CachedColumns["pro_ID"][$RowNumber];
            $this->pre_ID->SetText($this->FormParameters["pre_ID"][$RowNumber], $RowNumber);
            $this->pre_Ano->SetText($this->FormParameters["pre_Ano"][$RowNumber], $RowNumber);
            $this->pre_Semana->SetText($this->FormParameters["pre_Semana"][$RowNumber], $RowNumber);
            $this->caj_Abreviatura->SetText($this->FormParameters["caj_Abreviatura"][$RowNumber], $RowNumber);
            $this->caj_Descripcion->SetText($this->FormParameters["caj_Descripcion"][$RowNumber], $RowNumber);
            $this->pre_RefOperativa->SetText($this->FormParameters["pre_RefOperativa"][$RowNumber], $RowNumber);
            $this->pre_CodProducto->SetText($this->FormParameters["pre_CodProducto"][$RowNumber], $RowNumber);
            $this->pre_CodMarca->SetText($this->FormParameters["pre_CodMarca"][$RowNumber], $RowNumber);
            $this->pre_CodEmpaque->SetText($this->FormParameters["pre_CodEmpaque"][$RowNumber], $RowNumber);
            $this->pre_GruLiquidacion->SetText($this->FormParameters["pre_GruLiquidacion"][$RowNumber], $RowNumber);
            $this->pre_Zona->SetText($this->FormParameters["pre_Zona"][$RowNumber], $RowNumber);
            $this->pre_Productor->SetText($this->FormParameters["pre_Productor"][$RowNumber], $RowNumber);
            $this->per_Apellidos->SetText($this->FormParameters["per_Apellidos"][$RowNumber], $RowNumber);
            $this->pro_FechaCierre->SetText($this->FormParameters["pro_FechaCierre"][$RowNumber], $RowNumber);
            $this->pro_FechaLiquid->SetText($this->FormParameters["pro_FechaLiquid"][$RowNumber], $RowNumber);
            $this->pre_Usuario->SetText($this->FormParameters["pre_Usuario"][$RowNumber], $RowNumber);
            $this->TextBox1->SetText($this->FormParameters["TextBox1"][$RowNumber], $RowNumber);
            $this->pre_Estado->SetText($this->FormParameters["pre_Estado"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-1B61A5D3
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->pre_ID->Validate() && $Validation);
        $Validation = ($this->pre_Ano->Validate() && $Validation);
        $Validation = ($this->pre_Semana->Validate() && $Validation);
        $Validation = ($this->caj_Abreviatura->Validate() && $Validation);
        $Validation = ($this->caj_Descripcion->Validate() && $Validation);
        $Validation = ($this->pre_RefOperativa->Validate() && $Validation);
        $Validation = ($this->pre_CodProducto->Validate() && $Validation);
        $Validation = ($this->pre_CodMarca->Validate() && $Validation);
        $Validation = ($this->pre_CodEmpaque->Validate() && $Validation);
        $Validation = ($this->pre_GruLiquidacion->Validate() && $Validation);
        $Validation = ($this->pre_Zona->Validate() && $Validation);
        $Validation = ($this->pre_Productor->Validate() && $Validation);
        $Validation = ($this->per_Apellidos->Validate() && $Validation);
        $Validation = ($this->pro_FechaCierre->Validate() && $Validation);
        $Validation = ($this->pro_FechaLiquid->Validate() && $Validation);
        $Validation = ($this->pre_Usuario->Validate() && $Validation);
        $Validation = ($this->TextBox1->Validate() && $Validation);
        $Validation = ($this->pre_Estado->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->pre_ID->Errors->ToString();
            $errors .= $this->pre_Ano->Errors->ToString();
            $errors .= $this->pre_Semana->Errors->ToString();
            $errors .= $this->caj_Abreviatura->Errors->ToString();
            $errors .= $this->caj_Descripcion->Errors->ToString();
            $errors .= $this->pre_RefOperativa->Errors->ToString();
            $errors .= $this->pre_CodProducto->Errors->ToString();
            $errors .= $this->pre_CodMarca->Errors->ToString();
            $errors .= $this->pre_CodEmpaque->Errors->ToString();
            $errors .= $this->pre_GruLiquidacion->Errors->ToString();
            $errors .= $this->pre_Zona->Errors->ToString();
            $errors .= $this->pre_Productor->Errors->ToString();
            $errors .= $this->per_Apellidos->Errors->ToString();
            $errors .= $this->pro_FechaCierre->Errors->ToString();
            $errors .= $this->pro_FechaLiquid->Errors->ToString();
            $errors .= $this->pre_Usuario->Errors->ToString();
            $errors .= $this->TextBox1->Errors->ToString();
            $errors .= $this->pre_Estado->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->pre_ID->Errors->Clear();
            $this->pre_Ano->Errors->Clear();
            $this->pre_Semana->Errors->Clear();
            $this->caj_Abreviatura->Errors->Clear();
            $this->caj_Descripcion->Errors->Clear();
            $this->pre_RefOperativa->Errors->Clear();
            $this->pre_CodProducto->Errors->Clear();
            $this->pre_CodMarca->Errors->Clear();
            $this->pre_CodEmpaque->Errors->Clear();
            $this->pre_GruLiquidacion->Errors->Clear();
            $this->pre_Zona->Errors->Clear();
            $this->pre_Productor->Errors->Clear();
            $this->per_Apellidos->Errors->Clear();
            $this->pro_FechaCierre->Errors->Clear();
            $this->pro_FechaLiquid->Errors->Clear();
            $this->pre_Usuario->Errors->Clear();
            $this->TextBox1->Errors->Clear();
            $this->pre_Estado->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @2-7F45ED12
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["pre_ID"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_Ano"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_Semana"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Abreviatura"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["caj_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_RefOperativa"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_CodProducto"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_CodMarca"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_CodEmpaque"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_GruLiquidacion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_Zona"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_Productor"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["per_Apellidos"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_FechaCierre"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pro_FechaLiquid"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_Usuario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["TextBox1"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_Estado"][$RowNumber]));
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

//UpdateGrid Method @2-EE665209
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["pro_ID"] = $this->CachedColumns["pro_ID"][$RowNumber];
            $this->pre_ID->SetText($this->FormParameters["pre_ID"][$RowNumber], $RowNumber);
            $this->pre_Ano->SetText($this->FormParameters["pre_Ano"][$RowNumber], $RowNumber);
            $this->pre_Semana->SetText($this->FormParameters["pre_Semana"][$RowNumber], $RowNumber);
            $this->caj_Abreviatura->SetText($this->FormParameters["caj_Abreviatura"][$RowNumber], $RowNumber);
            $this->caj_Descripcion->SetText($this->FormParameters["caj_Descripcion"][$RowNumber], $RowNumber);
            $this->pre_RefOperativa->SetText($this->FormParameters["pre_RefOperativa"][$RowNumber], $RowNumber);
            $this->pre_CodProducto->SetText($this->FormParameters["pre_CodProducto"][$RowNumber], $RowNumber);
            $this->pre_CodMarca->SetText($this->FormParameters["pre_CodMarca"][$RowNumber], $RowNumber);
            $this->pre_CodEmpaque->SetText($this->FormParameters["pre_CodEmpaque"][$RowNumber], $RowNumber);
            $this->pre_GruLiquidacion->SetText($this->FormParameters["pre_GruLiquidacion"][$RowNumber], $RowNumber);
            $this->pre_Zona->SetText($this->FormParameters["pre_Zona"][$RowNumber], $RowNumber);
            $this->pre_Productor->SetText($this->FormParameters["pre_Productor"][$RowNumber], $RowNumber);
            $this->per_Apellidos->SetText($this->FormParameters["per_Apellidos"][$RowNumber], $RowNumber);
            $this->pro_FechaCierre->SetText($this->FormParameters["pro_FechaCierre"][$RowNumber], $RowNumber);
            $this->pro_FechaLiquid->SetText($this->FormParameters["pro_FechaLiquid"][$RowNumber], $RowNumber);
            $this->pre_Usuario->SetText($this->FormParameters["pre_Usuario"][$RowNumber], $RowNumber);
            $this->TextBox1->SetText($this->FormParameters["TextBox1"][$RowNumber], $RowNumber);
            $this->pre_Estado->SetText($this->FormParameters["pre_Estado"][$RowNumber], $RowNumber);
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

//InsertRow Method @2-D459E19D
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->pre_RefOperativa->SetValue($this->pre_RefOperativa->GetValue());
        $this->ds->pre_CodProducto->SetValue($this->pre_CodProducto->GetValue());
        $this->ds->pre_CodMarca->SetValue($this->pre_CodMarca->GetValue());
        $this->ds->pre_CodEmpaque->SetValue($this->pre_CodEmpaque->GetValue());
        $this->ds->pre_GruLiquidacion->SetValue($this->pre_GruLiquidacion->GetValue());
        $this->ds->pre_Zona->SetValue($this->pre_Zona->GetValue());
        $this->ds->pre_Productor->SetValue($this->pre_Productor->GetValue());
        $this->ds->pro_FechaCierre->SetValue($this->pro_FechaCierre->GetValue());
        $this->ds->pre_Usuario->SetValue($this->pre_Usuario->GetValue());
        $this->ds->pre_Estado->SetValue($this->pre_Estado->GetValue());
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

//UpdateRow Method @2-19CD7672
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->pre_RefOperativa->SetValue($this->pre_RefOperativa->GetValue());
        $this->ds->pre_CodProducto->SetValue($this->pre_CodProducto->GetValue());
        $this->ds->pre_CodMarca->SetValue($this->pre_CodMarca->GetValue());
        $this->ds->pre_CodEmpaque->SetValue($this->pre_CodEmpaque->GetValue());
        $this->ds->pre_GruLiquidacion->SetValue($this->pre_GruLiquidacion->GetValue());
        $this->ds->pre_Zona->SetValue($this->pre_Zona->GetValue());
        $this->ds->pre_Productor->SetValue($this->pre_Productor->GetValue());
        $this->ds->pre_Usuario->SetValue($this->pre_Usuario->GetValue());
        $this->ds->pre_Estado->SetValue($this->pre_Estado->GetValue());
        $this->ds->TextBox1->SetValue($this->TextBox1->GetValue());
        $this->ds->pro_FechaCierre->SetValue($this->pro_FechaCierre->GetValue());
        $this->ds->pro_FechaLiquid->SetValue($this->pro_FechaLiquid->GetValue());
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

//FormScript Method @2-F630576A
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var liqprecaja_listElements;\n";
        $script .= "var liqprecaja_listEmptyRows = 5;\n";
        $script .= "var " . $this->ComponentName . "pre_IDID = 0;\n";
        $script .= "var " . $this->ComponentName . "pre_AnoID = 1;\n";
        $script .= "var " . $this->ComponentName . "pre_SemanaID = 2;\n";
        $script .= "var " . $this->ComponentName . "caj_AbreviaturaID = 3;\n";
        $script .= "var " . $this->ComponentName . "caj_DescripcionID = 4;\n";
        $script .= "var " . $this->ComponentName . "pre_RefOperativaID = 5;\n";
        $script .= "var " . $this->ComponentName . "pre_CodProductoID = 6;\n";
        $script .= "var " . $this->ComponentName . "pre_CodMarcaID = 7;\n";
        $script .= "var " . $this->ComponentName . "pre_CodEmpaqueID = 8;\n";
        $script .= "var " . $this->ComponentName . "pre_GruLiquidacionID = 9;\n";
        $script .= "var " . $this->ComponentName . "pre_ZonaID = 10;\n";
        $script .= "var " . $this->ComponentName . "pre_ProductorID = 11;\n";
        $script .= "var " . $this->ComponentName . "per_ApellidosID = 12;\n";
        $script .= "var " . $this->ComponentName . "pro_FechaCierreID = 13;\n";
        $script .= "var " . $this->ComponentName . "pro_FechaLiquidID = 14;\n";
        $script .= "var " . $this->ComponentName . "pre_UsuarioID = 15;\n";
        $script .= "var " . $this->ComponentName . "TextBox1ID = 16;\n";
        $script .= "var " . $this->ComponentName . "pre_EstadoID = 17;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 18;\n";
        $script .= "\nfunction initliqprecaja_listElements() {\n";
        $script .= "\tvar ED = document.forms[\"liqprecaja_list\"];\n";
        $script .= "\tliqprecaja_listElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.pre_ID_" . $i . ", " . "ED.pre_Ano_" . $i . ", " . "ED.pre_Semana_" . $i . ", " . "ED.caj_Abreviatura_" . $i . ", " . "ED.caj_Descripcion_" . $i . ", " . "ED.pre_RefOperativa_" . $i . ", " . "ED.pre_CodProducto_" . $i . ", " . "ED.pre_CodMarca_" . $i . ", " . "ED.pre_CodEmpaque_" . $i . ", " . "ED.pre_GruLiquidacion_" . $i . ", " . "ED.pre_Zona_" . $i . ", " . "ED.pre_Productor_" . $i . ", " . "ED.per_Apellidos_" . $i . ", " . "ED.pro_FechaCierre_" . $i . ", " . "ED.pro_FechaLiquid_" . $i . ", " . "ED.pre_Usuario_" . $i . ", " . "ED.TextBox1_" . $i . ", " . "ED.pre_Estado_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-970F9019
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
                $this->CachedColumns["pro_ID"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["pro_ID"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-BE0A6F50
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["pro_ID"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-24CF6BDB
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->pre_RefOperativa->Prepare();
        $this->pre_CodProducto->Prepare();
        $this->pre_CodMarca->Prepare();
        $this->pre_CodEmpaque->Prepare();
        $this->pre_GruLiquidacion->Prepare();
        $this->pre_Zona->Prepare();

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
                        $this->CachedColumns["pro_ID"][$RowNumber] = $this->ds->CachedColumns["pro_ID"];
                        $this->pre_ID->SetValue($this->ds->pre_ID->GetValue());
                        $this->pre_Ano->SetValue($this->ds->pre_Ano->GetValue());
                        $this->pre_Semana->SetValue($this->ds->pre_Semana->GetValue());
                        $this->caj_Abreviatura->SetValue($this->ds->caj_Abreviatura->GetValue());
                        $this->caj_Descripcion->SetValue($this->ds->caj_Descripcion->GetValue());
                        $this->pre_RefOperativa->SetValue($this->ds->pre_RefOperativa->GetValue());
                        $this->pre_CodProducto->SetValue($this->ds->pre_CodProducto->GetValue());
                        $this->pre_CodMarca->SetValue($this->ds->pre_CodMarca->GetValue());
                        $this->pre_CodEmpaque->SetValue($this->ds->pre_CodEmpaque->GetValue());
                        $this->pre_GruLiquidacion->SetValue($this->ds->pre_GruLiquidacion->GetValue());
                        $this->pre_Zona->SetValue($this->ds->pre_Zona->GetValue());
                        $this->pre_Productor->SetValue($this->ds->pre_Productor->GetValue());
                        $this->pro_FechaCierre->SetValue($this->ds->pro_FechaCierre->GetValue());
                        $this->pro_FechaLiquid->SetValue($this->ds->pro_FechaLiquid->GetValue());
                        $this->pre_Usuario->SetValue($this->ds->pre_Usuario->GetValue());
                        $this->TextBox1->SetValue($this->ds->TextBox1->GetValue());
                        $this->pre_Estado->SetValue($this->ds->pre_Estado->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["pro_ID"][$RowNumber] = "";
                        $this->pre_ID->SetText("");
                        $this->pre_Ano->SetText("");
                        $this->pre_Semana->SetText("");
                        $this->caj_Abreviatura->SetText("");
                        $this->caj_Descripcion->SetText("");
                        $this->pre_RefOperativa->SetText("");
                        $this->pre_CodProducto->SetText("");
                        $this->pre_CodMarca->SetText("");
                        $this->pre_CodEmpaque->SetText("");
                        $this->pre_GruLiquidacion->SetText("");
                        $this->pre_Zona->SetText("");
                        $this->pre_Productor->SetText("");
                        $this->per_Apellidos->SetText("");
                        $this->pro_FechaCierre->SetText("");
                        $this->pro_FechaLiquid->SetText("");
                        $this->pre_Usuario->SetText("");
                        $this->TextBox1->SetText("");
                        $this->pre_Estado->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->pre_ID->SetText($this->FormParameters["pre_ID"][$RowNumber], $RowNumber);
                        $this->pre_Ano->SetText($this->FormParameters["pre_Ano"][$RowNumber], $RowNumber);
                        $this->pre_Semana->SetText($this->FormParameters["pre_Semana"][$RowNumber], $RowNumber);
                        $this->caj_Abreviatura->SetText($this->FormParameters["caj_Abreviatura"][$RowNumber], $RowNumber);
                        $this->caj_Descripcion->SetText($this->FormParameters["caj_Descripcion"][$RowNumber], $RowNumber);
                        $this->pre_RefOperativa->SetText($this->FormParameters["pre_RefOperativa"][$RowNumber], $RowNumber);
                        $this->pre_CodProducto->SetText($this->FormParameters["pre_CodProducto"][$RowNumber], $RowNumber);
                        $this->pre_CodMarca->SetText($this->FormParameters["pre_CodMarca"][$RowNumber], $RowNumber);
                        $this->pre_CodEmpaque->SetText($this->FormParameters["pre_CodEmpaque"][$RowNumber], $RowNumber);
                        $this->pre_GruLiquidacion->SetText($this->FormParameters["pre_GruLiquidacion"][$RowNumber], $RowNumber);
                        $this->pre_Zona->SetText($this->FormParameters["pre_Zona"][$RowNumber], $RowNumber);
                        $this->pre_Productor->SetText($this->FormParameters["pre_Productor"][$RowNumber], $RowNumber);
                        $this->per_Apellidos->SetText($this->FormParameters["per_Apellidos"][$RowNumber], $RowNumber);
                        $this->pro_FechaCierre->SetText($this->FormParameters["pro_FechaCierre"][$RowNumber], $RowNumber);
                        $this->pro_FechaLiquid->SetText($this->FormParameters["pro_FechaLiquid"][$RowNumber], $RowNumber);
                        $this->pre_Usuario->SetText($this->FormParameters["pre_Usuario"][$RowNumber], $RowNumber);
                        $this->TextBox1->SetText($this->FormParameters["TextBox1"][$RowNumber], $RowNumber);
                        $this->pre_Estado->SetText($this->FormParameters["pre_Estado"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->pre_ID->Show($RowNumber);
                    $this->pre_Ano->Show($RowNumber);
                    $this->pre_Semana->Show($RowNumber);
                    $this->caj_Abreviatura->Show($RowNumber);
                    $this->caj_Descripcion->Show($RowNumber);
                    $this->pre_RefOperativa->Show($RowNumber);
                    $this->pre_CodProducto->Show($RowNumber);
                    $this->pre_CodMarca->Show($RowNumber);
                    $this->pre_CodEmpaque->Show($RowNumber);
                    $this->pre_GruLiquidacion->Show($RowNumber);
                    $this->pre_Zona->Show($RowNumber);
                    $this->pre_Productor->Show($RowNumber);
                    $this->per_Apellidos->Show($RowNumber);
                    $this->pro_FechaCierre->Show($RowNumber);
                    $this->DatePicker_pre_FecInicio->Show($RowNumber);
                    $this->pro_FechaLiquid->Show($RowNumber);
                    $this->DatePicker1->Show($RowNumber);
                    $this->pre_Usuario->Show($RowNumber);
                    $this->TextBox1->Show($RowNumber);
                    $this->pre_Estado->Show($RowNumber);
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
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();
        $this->Sorter_pre_ID->Show();
        $this->Sorter_pre_RefOperativa->Show();
        $this->Sorter_pre_CodProducto->Show();
        $this->Sorter_pre_CodMarca->Show();
        $this->Sorter_pre_CodEmpaque->Show();
        $this->Sorter_pre_GruLiquidacion->Show();
        $this->Sorter_pre_Zona->Show();
        $this->Sorter_pre_Productor->Show();
        $this->Sorter_pre_FecInicio->Show();
        $this->Sorter_pre_Usuario->Show();
        $this->Sorter_pre_Estado->Show();

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

} //End liqprecaja_list Class @2-FCB6E20C

class clsliqprecaja_listDataSource extends clsDBdatos {  //liqprecaja_listDataSource Class @2-728A1590

//DataSource Variables @2-B5426690
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
    var $pre_ID;
    var $pre_Ano;
    var $pre_Semana;
    var $caj_Abreviatura;
    var $caj_Descripcion;
    var $pre_RefOperativa;
    var $pre_CodProducto;
    var $pre_CodMarca;
    var $pre_CodEmpaque;
    var $pre_GruLiquidacion;
    var $pre_Zona;
    var $pre_Productor;
    var $per_Apellidos;
    var $pro_FechaCierre;
    var $pro_FechaLiquid;
    var $pre_Usuario;
    var $TextBox1;
    var $pre_Estado;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @2-4DCC0D7C
    function clsliqprecaja_listDataSource()
    {
        $this->ErrorBlock = "EditableGrid liqprecaja_list/Error";
        $this->Initialize();
        $this->pre_ID = new clsField("pre_ID", ccsInteger, "");
        $this->pre_Ano = new clsField("pre_Ano", ccsInteger, "");
        $this->pre_Semana = new clsField("pre_Semana", ccsInteger, "");
        $this->caj_Abreviatura = new clsField("caj_Abreviatura", ccsText, "");
        $this->caj_Descripcion = new clsField("caj_Descripcion", ccsText, "");
        $this->pre_RefOperativa = new clsField("pre_RefOperativa", ccsInteger, "");
        $this->pre_CodProducto = new clsField("pre_CodProducto", ccsInteger, "");
        $this->pre_CodMarca = new clsField("pre_CodMarca", ccsInteger, "");
        $this->pre_CodEmpaque = new clsField("pre_CodEmpaque", ccsInteger, "");
        $this->pre_GruLiquidacion = new clsField("pre_GruLiquidacion", ccsInteger, "");
        $this->pre_Zona = new clsField("pre_Zona", ccsInteger, "");
        $this->pre_Productor = new clsField("pre_Productor", ccsInteger, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->pro_FechaCierre = new clsField("pro_FechaCierre", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->pro_FechaLiquid = new clsField("pro_FechaLiquid", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->pre_Usuario = new clsField("pre_Usuario", ccsText, "");
        $this->TextBox1 = new clsField("TextBox1", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->pre_Estado = new clsField("pre_Estado", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @2-A18BF8C2
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "pro_ID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_pre_ID" => array("pro_ID", ""), 
            "Sorter_pre_RefOperativa" => array("pro_RefOperativa", ""), 
            "Sorter_pre_CodProducto" => array("pro_CodProducto", ""), 
            "Sorter_pre_CodMarca" => array("pro_CodMarca", ""), 
            "Sorter_pre_CodEmpaque" => array("pro_CodEmpaque", ""), 
            "Sorter_pre_GruLiquidacion" => array("pro_GruLiquidacion", ""), 
            "Sorter_pre_Zona" => array("pro_Zona", ""), 
            "Sorter_pre_Productor" => array("pro_Productor", ""), 
            "Sorter_pre_FecInicio" => array("pro_FecInicio", ""), 
            "Sorter_pre_Usuario" => array("pro_Usuario", ""), 
            "Sorter_pre_Estado" => array("pro_Estado", "")));
    }
//End SetOrder Method

//Prepare Method @2-FE91254B
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlpro_Ano", ccsInteger, "", "", $this->Parameters["urlpro_Ano"], "", true);
        $this->wp->AddParameter("2", "urlpro_Semana", ccsInteger, "", "", $this->Parameters["urlpro_Semana"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pro_AnoProceso", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "pro_Semana", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-50884B46
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM liqprocesos";
        $this->SQL = "SELECT *  " .
        "FROM liqprocesos";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-34855321
    function SetValues()
    {
        $this->CachedColumns["pro_ID"] = $this->f("pro_ID");
        $this->pre_ID->SetDBValue(trim($this->f("pro_ID")));
        $this->pre_Ano->SetDBValue(trim($this->f("pre_Ano")));
        $this->pre_Semana->SetDBValue(trim($this->f("pre_Semana")));
        $this->caj_Abreviatura->SetDBValue($this->f("caj_Abreviatura"));
        $this->caj_Descripcion->SetDBValue($this->f("caj_Descripcion"));
        $this->pre_RefOperativa->SetDBValue(trim($this->f("pro_Embarque")));
        $this->pre_CodProducto->SetDBValue(trim($this->f("pro_CodProducto")));
        $this->pre_CodMarca->SetDBValue(trim($this->f("pro_CodMarca")));
        $this->pre_CodEmpaque->SetDBValue(trim($this->f("pro_CodEmpaque")));
        $this->pre_GruLiquidacion->SetDBValue(trim($this->f("pro_GrupoLiquidacion")));
        $this->pre_Zona->SetDBValue(trim($this->f("pro_Zona")));
        $this->pre_Productor->SetDBValue(trim($this->f("pro_Productor")));
        $this->pro_FechaCierre->SetDBValue(trim($this->f("pro_FechaCierre")));
        $this->pro_FechaLiquid->SetDBValue(trim($this->f("pro_FechaLiquid")));
        $this->pre_Usuario->SetDBValue($this->f("pro_Usuario"));
        $this->TextBox1->SetDBValue(trim($this->f("pro_FecRegistro")));
        $this->pre_Estado->SetDBValue(trim($this->f("pro_Estado")));
    }
//End SetValues Method

//Insert Method @2-CC652FA8
    function Insert()
    {
        $this->cp["pro_AnoProceso"] = new clsSQLParameter("urlpro_Ano", ccsInteger, "", "", CCGetFromGet("pro_Ano", ""), 0, false, $this->ErrorBlock);
        $this->cp["pro_Semana"] = new clsSQLParameter("urlpro_Semana", ccsInteger, "", "", CCGetFromGet("pro_Semana", ""), 0, false, $this->ErrorBlock);
        $this->cp["pro_Embarque"] = new clsSQLParameter("ctrlpre_RefOperativa", ccsInteger, "", "", $this->pre_RefOperativa->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_CodProducto"] = new clsSQLParameter("ctrlpre_CodProducto", ccsInteger, "", "", $this->pre_CodProducto->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_CodMarca"] = new clsSQLParameter("ctrlpre_CodMarca", ccsInteger, "", "", $this->pre_CodMarca->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_CodEmpaque"] = new clsSQLParameter("ctrlpre_CodEmpaque", ccsInteger, "", "", $this->pre_CodEmpaque->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_GrupoLiquidacion"] = new clsSQLParameter("ctrlpre_GruLiquidacion", ccsInteger, "", "", $this->pre_GruLiquidacion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_Zona"] = new clsSQLParameter("ctrlpre_Zona", ccsInteger, "", "", $this->pre_Zona->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_Productor"] = new clsSQLParameter("ctrlpre_Productor", ccsInteger, "", "", $this->pre_Productor->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["pro_FechaCierre"] = new clsSQLParameter("ctrlpro_FechaCierre", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("dd", "/", "mmm", "/", "yyyy"), $this->pro_FechaCierre->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Usuario"] = new clsSQLParameter("ctrlpre_Usuario", ccsText, "", "", $this->pre_Usuario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Estado"] = new clsSQLParameter("ctrlpre_Estado", ccsInteger, "", "", $this->pre_Estado->GetValue(), 1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqprocesos ("
             . "pro_AnoProceso, "
             . "pro_Semana, "
             . "pro_Embarque, "
             . "pro_CodProducto, "
             . "pro_CodMarca, "
             . "pro_CodEmpaque, "
             . "pro_GrupoLiquidacion, "
             . "pro_Zona, "
             . "pro_Productor, "
             . "pro_FechaCierre, "
             . "pro_Usuario, "
             . "pro_Estado"
             . ") VALUES ("
             . $this->ToSQL($this->cp["pro_AnoProceso"]->GetDBValue(), $this->cp["pro_AnoProceso"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Semana"]->GetDBValue(), $this->cp["pro_Semana"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Embarque"]->GetDBValue(), $this->cp["pro_Embarque"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_CodProducto"]->GetDBValue(), $this->cp["pro_CodProducto"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_CodMarca"]->GetDBValue(), $this->cp["pro_CodMarca"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_CodEmpaque"]->GetDBValue(), $this->cp["pro_CodEmpaque"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_GrupoLiquidacion"]->GetDBValue(), $this->cp["pro_GrupoLiquidacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Zona"]->GetDBValue(), $this->cp["pro_Zona"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Productor"]->GetDBValue(), $this->cp["pro_Productor"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_FechaCierre"]->GetDBValue(), $this->cp["pro_FechaCierre"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Usuario"]->GetDBValue(), $this->cp["pro_Usuario"]->DataType) . ", "
             . $this->ToSQL($this->cp["pro_Estado"]->GetDBValue(), $this->cp["pro_Estado"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-0E0A0F94
    function Update()
    {
        $this->cp["pro_Embarque"] = new clsSQLParameter("ctrlpre_RefOperativa", ccsInteger, "", "", $this->pre_RefOperativa->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_CodProducto"] = new clsSQLParameter("ctrlpre_CodProducto", ccsInteger, "", "", $this->pre_CodProducto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_CodMarca"] = new clsSQLParameter("ctrlpre_CodMarca", ccsInteger, "", "", $this->pre_CodMarca->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_CodEmpaque"] = new clsSQLParameter("ctrlpre_CodEmpaque", ccsInteger, "", "", $this->pre_CodEmpaque->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_GrupoLiquidacion"] = new clsSQLParameter("ctrlpre_GruLiquidacion", ccsInteger, "", "", $this->pre_GruLiquidacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Zona"] = new clsSQLParameter("ctrlpre_Zona", ccsInteger, "", "", $this->pre_Zona->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Productor"] = new clsSQLParameter("ctrlpre_Productor", ccsInteger, "", "", $this->pre_Productor->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Usuario"] = new clsSQLParameter("ctrlpre_Usuario", ccsText, "", "", $this->pre_Usuario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_Estado"] = new clsSQLParameter("ctrlpre_Estado", ccsInteger, "", "", $this->pre_Estado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_FecRegistro"] = new clsSQLParameter("ctrlTextBox1", ccsDate, Array("dd", "/", "mmm", "/", "yy", " ", "HH", ":", "nn"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->TextBox1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_FechaCierre"] = new clsSQLParameter("ctrlpro_FechaCierre", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->pro_FechaCierre->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["pro_FechaLiquid"] = new clsSQLParameter("ctrlpro_FechaLiquid", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->pro_FechaLiquid->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dspro_ID", ccsInteger, "", "", $this->CachedColumns["pro_ID"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "pro_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE liqprocesos SET "
             . "pro_Embarque=" . $this->ToSQL($this->cp["pro_Embarque"]->GetDBValue(), $this->cp["pro_Embarque"]->DataType) . ", "
             . "pro_CodProducto=" . $this->ToSQL($this->cp["pro_CodProducto"]->GetDBValue(), $this->cp["pro_CodProducto"]->DataType) . ", "
             . "pro_CodMarca=" . $this->ToSQL($this->cp["pro_CodMarca"]->GetDBValue(), $this->cp["pro_CodMarca"]->DataType) . ", "
             . "pro_CodEmpaque=" . $this->ToSQL($this->cp["pro_CodEmpaque"]->GetDBValue(), $this->cp["pro_CodEmpaque"]->DataType) . ", "
             . "pro_GrupoLiquidacion=" . $this->ToSQL($this->cp["pro_GrupoLiquidacion"]->GetDBValue(), $this->cp["pro_GrupoLiquidacion"]->DataType) . ", "
             . "pro_Zona=" . $this->ToSQL($this->cp["pro_Zona"]->GetDBValue(), $this->cp["pro_Zona"]->DataType) . ", "
             . "pro_Productor=" . $this->ToSQL($this->cp["pro_Productor"]->GetDBValue(), $this->cp["pro_Productor"]->DataType) . ", "
             . "pro_Usuario=" . $this->ToSQL($this->cp["pro_Usuario"]->GetDBValue(), $this->cp["pro_Usuario"]->DataType) . ", "
             . "pro_Estado=" . $this->ToSQL($this->cp["pro_Estado"]->GetDBValue(), $this->cp["pro_Estado"]->DataType) . ", "
             . "pro_FecRegistro=" . $this->ToSQL($this->cp["pro_FecRegistro"]->GetDBValue(), $this->cp["pro_FecRegistro"]->DataType) . ", "
             . "pro_FechaCierre=" . $this->ToSQL($this->cp["pro_FechaCierre"]->GetDBValue(), $this->cp["pro_FechaCierre"]->DataType) . ", "
             . "pro_FechaLiquid=" . $this->ToSQL($this->cp["pro_FechaLiquid"]->GetDBValue(), $this->cp["pro_FechaLiquid"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-8C3DF593
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dspro_ID", ccsInteger, "", "", $this->CachedColumns["pro_ID"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "pro_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM liqprocesos";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqprecaja_listDataSource Class @2-FCB6E20C

//Initialize Page @1-4BBBBAC4
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

$FileName = "LiLiPr_search.php";
$Redirect = "";
$TemplateFileName = "LiLiPr_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-14595DE5
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqprecaja_qry = new clsRecordliqprecaja_qry();
$liqprecaja_list = new clsEditableGridliqprecaja_list();
$liqprecaja_list->Initialize();

// Events
include("./LiLiPr_search_events.php");
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

//Execute Components @1-5A70B1D7
$Cabecera->Operations();
$liqprecaja_qry->Operation();
$liqprecaja_list->Operation();
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

//Show Page @1-88A42190
$Cabecera->Show("Cabecera");
$liqprecaja_qry->Show();
$liqprecaja_list->Show();
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
