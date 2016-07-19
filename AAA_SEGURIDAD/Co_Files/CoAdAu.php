<?php
/**
*    Busqueda de Auxiliares de tipo Persona
*    @abstract   Lista en una rejilla los Auxiliares que correspondan a un criterio especificado por
*		 el usuario, que puede estar basado en codigo, nombre, categoria, etc.
*    @abstract   En cada linea se dispone un enlace para presentar el registro completo del auxiliar, y
*		 dependiendo de los atributos del usuario, modificarlo
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdAu
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*    @see	 CoAdAu_mant.php
*/
//
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include (RelativePath . "/LibPhp/ConLib.php") ;
//Include Page implementation @2-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordCoAdAu_qry { //CoAdAu_qry Class @164-D81E2EAF

//Variables @164-CB19EB75

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

//Class_Initialize Event @164-13E814C9
    function clsRecordCoAdAu_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdAu_qry/Error";
        $this->ds = new clsCoAdAu_qryDataSource();
        if($this->Visible)
        {
            $this->ComponentName = "CoAdAu_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_per_Apellidos = new clsControl(ccsTextBox, "s_per_Apellidos", "s_per_Apellidos", ccsText, "", CCGetRequestParam("s_per_Apellidos", $Method));
            $this->s_per_PersonaContacto = new clsControl(ccsTextBox, "s_per_PersonaContacto", "s_per_PersonaContacto", ccsText, "", CCGetRequestParam("s_per_PersonaContacto", $Method));
            $this->s_per_Catext = new clsControl(ccsTextBox, "s_per_Catext", "s_per_Catext", ccsText, "", CCGetRequestParam("s_per_Catext", $Method));
            $this->s_per_Subcategoria = new clsControl(ccsListBox, "s_per_Subcategoria", "s_per_Subcategoria", ccsText, "", CCGetRequestParam("s_per_Subcategoria", $Method));
            $this->s_per_Subcategoria->DSType = dsTable;
            list($this->s_per_Subcategoria->BoundColumn, $this->s_per_Subcategoria->TextColumn, $this->s_per_Subcategoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_per_Subcategoria->ds = new clsDBdatos();
            $this->s_per_Subcategoria->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_per_Subcategoria->ds->Parameters["expr333"] = 'PERSUB';
            $this->s_per_Subcategoria->ds->wp = new clsSQLParameters();
            $this->s_per_Subcategoria->ds->wp->AddParameter("1", "expr333", ccsText, "", "", $this->s_per_Subcategoria->ds->Parameters["expr333"], "", false);
            $this->s_per_Subcategoria->ds->wp->Criterion[1] = $this->s_per_Subcategoria->ds->wp->Operation(opEqual, "par_Clave", $this->s_per_Subcategoria->ds->wp->GetDBValue("1"), $this->s_per_Subcategoria->ds->ToSQL($this->s_per_Subcategoria->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_per_Subcategoria->ds->Where = $this->s_per_Subcategoria->ds->wp->Criterion[1];
            $this->s_per_Grupo = new clsControl(ccsTextBox, "s_per_Grupo", "s_per_Grupo", ccsText, "", CCGetRequestParam("s_per_Grupo", $Method));
            $this->s_per_Zona = new clsControl(ccsListBox, "s_per_Zona", "s_per_Zona", ccsInteger, "", CCGetRequestParam("s_per_Zona", $Method));
            $this->s_per_Zona->DSType = dsTable;
            list($this->s_per_Zona->BoundColumn, $this->s_per_Zona->TextColumn, $this->s_per_Zona->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_per_Zona->ds = new clsDBdatos();
            $this->s_per_Zona->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_per_Zona->ds->Parameters["expr170"] = 'LZONA';
            $this->s_per_Zona->ds->wp = new clsSQLParameters();
            $this->s_per_Zona->ds->wp->AddParameter("1", "expr170", ccsText, "", "", $this->s_per_Zona->ds->Parameters["expr170"], "", false);
            $this->s_per_Zona->ds->wp->Criterion[1] = $this->s_per_Zona->ds->wp->Operation(opEqual, "par_Clave", $this->s_per_Zona->ds->wp->GetDBValue("1"), $this->s_per_Zona->ds->ToSQL($this->s_per_Zona->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_per_Zona->ds->Where = $this->s_per_Zona->ds->wp->Criterion[1];
            $this->s_per_SubZona = new clsControl(ccsListBox, "s_per_SubZona", "s_per_SubZona", ccsInteger, "", CCGetRequestParam("s_per_SubZona", $Method));
            $this->s_per_SubZona->DSType = dsTable;
            list($this->s_per_SubZona->BoundColumn, $this->s_per_SubZona->TextColumn, $this->s_per_SubZona->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_per_SubZona->ds = new clsDBdatos();
            $this->s_per_SubZona->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_per_SubZona->ds->Parameters["url'LSZON'"] = CCGetFromGet("'LSZON'", "");
            $this->s_per_SubZona->ds->wp = new clsSQLParameters();
            $this->s_per_SubZona->ds->wp->AddParameter("1", "url'LSZON'", ccsText, "", "", $this->s_per_SubZona->ds->Parameters["url'LSZON'"], "", false);
            $this->s_per_SubZona->ds->wp->Criterion[1] = $this->s_per_SubZona->ds->wp->Operation(opEqual, "par_Clave", $this->s_per_SubZona->ds->wp->GetDBValue("1"), $this->s_per_SubZona->ds->ToSQL($this->s_per_SubZona->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_per_SubZona->ds->Where = $this->s_per_SubZona->ds->wp->Criterion[1];
            $this->s_per_Pais = new clsControl(ccsListBox, "s_per_Pais", "s_per_Pais", ccsInteger, "", CCGetRequestParam("s_per_Pais", $Method));
            $this->s_per_Pais->DSType = dsTable;
            list($this->s_per_Pais->BoundColumn, $this->s_per_Pais->TextColumn, $this->s_per_Pais->DBFormat) = array("pai_CodPais", "pai_Descripcion", "");
            $this->s_per_Pais->ds = new clsDBdatos();
            $this->s_per_Pais->ds->SQL = "SELECT *  " .
"FROM genpaises";
            $this->s_per_Provincia = new clsControl(ccsListBox, "s_per_Provincia", "s_per_Provincia", ccsInteger, "", CCGetRequestParam("s_per_Provincia", $Method));
            $this->s_per_Provincia->DSType = dsTable;
            list($this->s_per_Provincia->BoundColumn, $this->s_per_Provincia->TextColumn, $this->s_per_Provincia->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_per_Provincia->ds = new clsDBdatos();
            $this->s_per_Provincia->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_per_Provincia->ds->Parameters["expr175"] = 'LPROV';
            $this->s_per_Provincia->ds->wp = new clsSQLParameters();
            $this->s_per_Provincia->ds->wp->AddParameter("1", "expr175", ccsText, "", "", $this->s_per_Provincia->ds->Parameters["expr175"], "", false);
            $this->s_per_Provincia->ds->wp->Criterion[1] = $this->s_per_Provincia->ds->wp->Operation(opEqual, "par_Clave", $this->s_per_Provincia->ds->wp->GetDBValue("1"), $this->s_per_Provincia->ds->ToSQL($this->s_per_Provincia->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_per_Provincia->ds->Where = $this->s_per_Provincia->ds->wp->Criterion[1];
            $this->s_per_Canton = new clsControl(ccsListBox, "s_per_Canton", "s_per_Canton", ccsInteger, "", CCGetRequestParam("s_per_Canton", $Method));
            $this->s_per_Canton->DSType = dsTable;
            list($this->s_per_Canton->BoundColumn, $this->s_per_Canton->TextColumn, $this->s_per_Canton->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_per_Canton->ds = new clsDBdatos();
            $this->s_per_Canton->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_per_Canton->ds->Parameters["expr177"] = 'LCANT';
            $this->s_per_Canton->ds->wp = new clsSQLParameters();
            $this->s_per_Canton->ds->wp->AddParameter("1", "expr177", ccsText, "", "", $this->s_per_Canton->ds->Parameters["expr177"], "", false);
            $this->s_per_Canton->ds->wp->Criterion[1] = $this->s_per_Canton->ds->wp->Operation(opEqual, "par_Clave", $this->s_per_Canton->ds->wp->GetDBValue("1"), $this->s_per_Canton->ds->ToSQL($this->s_per_Canton->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_per_Canton->ds->Where = $this->s_per_Canton->ds->wp->Criterion[1];
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            $this->CANCELAR = new clsButton("CANCELAR");
        }
    }
//End Class_Initialize Event

//Initialize Method @164-E39D6796
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["expr179"] = 'LPAIS';
    }
//End Initialize Method

//Validate Method @164-A5618B44
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_per_Apellidos->Validate() && $Validation);
        $Validation = ($this->s_per_PersonaContacto->Validate() && $Validation);
        $Validation = ($this->s_per_Catext->Validate() && $Validation);
        $Validation = ($this->s_per_Subcategoria->Validate() && $Validation);
        $Validation = ($this->s_per_Grupo->Validate() && $Validation);
        $Validation = ($this->s_per_Zona->Validate() && $Validation);
        $Validation = ($this->s_per_SubZona->Validate() && $Validation);
        $Validation = ($this->s_per_Pais->Validate() && $Validation);
        $Validation = ($this->s_per_Provincia->Validate() && $Validation);
        $Validation = ($this->s_per_Canton->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @164-ECCAD570
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_per_Apellidos->Errors->Count());
        $errors = ($errors || $this->s_per_PersonaContacto->Errors->Count());
        $errors = ($errors || $this->s_per_Catext->Errors->Count());
        $errors = ($errors || $this->s_per_Subcategoria->Errors->Count());
        $errors = ($errors || $this->s_per_Grupo->Errors->Count());
        $errors = ($errors || $this->s_per_Zona->Errors->Count());
        $errors = ($errors || $this->s_per_SubZona->Errors->Count());
        $errors = ($errors || $this->s_per_Pais->Errors->Count());
        $errors = ($errors || $this->s_per_Provincia->Errors->Count());
        $errors = ($errors || $this->s_per_Canton->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @164-B9D401DC
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
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            } else if(strlen(CCGetParam("CANCELAR", ""))) {
                $this->PressedButton = "CANCELAR";
            }
        }
        $Redirect = "CoAdAu.php";
        if($this->PressedButton == "CANCELAR") {
            if(!CCGetEvent($this->CANCELAR->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdAu.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch", "CANCELAR")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @164-4852AB49
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_per_Subcategoria->Prepare();
        $this->s_per_Zona->Prepare();
        $this->s_per_SubZona->Prepare();
        $this->s_per_Pais->Prepare();
        $this->s_per_Provincia->Prepare();
        $this->s_per_Canton->Prepare();

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
                    echo "Error in Record CoAdAu_qry";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
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
            $Error .= $this->s_per_Apellidos->Errors->ToString();
            $Error .= $this->s_per_PersonaContacto->Errors->ToString();
            $Error .= $this->s_per_Catext->Errors->ToString();
            $Error .= $this->s_per_Subcategoria->Errors->ToString();
            $Error .= $this->s_per_Grupo->Errors->ToString();
            $Error .= $this->s_per_Zona->Errors->ToString();
            $Error .= $this->s_per_SubZona->Errors->ToString();
            $Error .= $this->s_per_Pais->Errors->ToString();
            $Error .= $this->s_per_Provincia->Errors->ToString();
            $Error .= $this->s_per_Canton->Errors->ToString();
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
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->s_per_Apellidos->Show();
        $this->s_per_PersonaContacto->Show();
        $this->s_per_Catext->Show();
        $this->s_per_Subcategoria->Show();
        $this->s_per_Grupo->Show();
        $this->s_per_Zona->Show();
        $this->s_per_SubZona->Show();
        $this->s_per_Pais->Show();
        $this->s_per_Provincia->Show();
        $this->s_per_Canton->Show();
        $this->Button_DoSearch->Show();
        $this->CANCELAR->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdAu_qry Class @164-FCB6E20C

class clsCoAdAu_qryDataSource extends clsDBdatos {  //CoAdAu_qryDataSource Class @164-B49B9EF6

//DataSource Variables @164-610CE934
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $s_per_Apellidos;
    var $s_per_PersonaContacto;
    var $s_per_Catext;
    var $s_per_Subcategoria;
    var $s_per_Grupo;
    var $s_per_Zona;
    var $s_per_SubZona;
    var $s_per_Pais;
    var $s_per_Provincia;
    var $s_per_Canton;
//End DataSource Variables

//Class_Initialize Event @164-2E1FF131
    function clsCoAdAu_qryDataSource()
    {
        $this->ErrorBlock = "Record CoAdAu_qry/Error";
        $this->Initialize();
        $this->s_per_Apellidos = new clsField("s_per_Apellidos", ccsText, "");
        $this->s_per_PersonaContacto = new clsField("s_per_PersonaContacto", ccsText, "");
        $this->s_per_Catext = new clsField("s_per_Catext", ccsText, "");
        $this->s_per_Subcategoria = new clsField("s_per_Subcategoria", ccsText, "");
        $this->s_per_Grupo = new clsField("s_per_Grupo", ccsText, "");
        $this->s_per_Zona = new clsField("s_per_Zona", ccsInteger, "");
        $this->s_per_SubZona = new clsField("s_per_SubZona", ccsInteger, "");
        $this->s_per_Pais = new clsField("s_per_Pais", ccsInteger, "");
        $this->s_per_Provincia = new clsField("s_per_Provincia", ccsInteger, "");
        $this->s_per_Canton = new clsField("s_per_Canton", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @164-6E578698
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr179", ccsText, "", "", $this->Parameters["expr179"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @164-8A62BABD
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM genparametros";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @164-BAF0975B
    function SetValues()
    {
    }
//End SetValues Method

} //End CoAdAu_qryDataSource Class @164-FCB6E20C

class clsGridCoAdAu_list { //CoAdAu_list class @351-4542EA7E

//Variables @351-FEC88323

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
    var $Navigator;
    var $Sorter_per_CodAuxiliar;
    var $Sorter_par_Descripcion;
    var $Sorter_per_Apellidos;
    var $Sorter_per_Subcategoria;
    var $Sorter_per_Ruc;
    var $Sorter_per_Direccion;
    var $Sorter_per_Ciudad;
    var $Sorter_per_Grupo;
//End Variables

//Class_Initialize Event @351-3CAF2976
    function clsGridCoAdAu_list()
    {
        global $FileName;
        $this->ComponentName = "CoAdAu_list";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid CoAdAu_list";
        $this->ds = new clsCoAdAu_listDataSource();
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("CoAdAu_listOrder", "");
        $this->SorterDirection = CCGetParam("CoAdAu_listDir", "");

        $this->per_CodAuxiliar = new clsControl(ccsLink, "per_CodAuxiliar", "per_CodAuxiliar", ccsInteger, "", CCGetRequestParam("per_CodAuxiliar", ccsGet));
        $this->par_Descripcion = new clsControl(ccsLabel, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->per_Apellidos = new clsControl(ccsLabel, "per_Apellidos", "per_Apellidos", ccsText, "", CCGetRequestParam("per_Apellidos", ccsGet));
        $this->per_Subcategoria = new clsControl(ccsLabel, "per_Subcategoria", "per_Subcategoria", ccsInteger, "", CCGetRequestParam("per_Subcategoria", ccsGet));
        $this->per_Ruc = new clsControl(ccsLabel, "per_Ruc", "per_Ruc", ccsText, "", CCGetRequestParam("per_Ruc", ccsGet));
        $this->per_Direccion = new clsControl(ccsLabel, "per_Direccion", "per_Direccion", ccsText, "", CCGetRequestParam("per_Direccion", ccsGet));
        $this->per_Ciudad = new clsControl(ccsLabel, "per_Ciudad", "per_Ciudad", ccsText, "", CCGetRequestParam("per_Ciudad", ccsGet));
        $this->per_Grupo = new clsControl(ccsLabel, "per_Grupo", "per_Grupo", ccsInteger, "", CCGetRequestParam("per_Grupo", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->lkNuevo = new clsControl(ccsLink, "lkNuevo", "lkNuevo", ccsText, "", CCGetRequestParam("lkNuevo", ccsGet));
        $this->lkNuevo->Parameters = CCGetQueryString("QueryString", Array("per_CodAuxiliar", "ccsForm"));
        $this->lkNuevo->Parameters = CCAddParam($this->lkNuevo->Parameters, "pOpCode", 'ADD');
        $this->lkNuevo->Page = "CoAdAu_mant.php";
        $this->Sorter_per_CodAuxiliar = new clsSorter($this->ComponentName, "Sorter_per_CodAuxiliar", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Sorter_per_Apellidos = new clsSorter($this->ComponentName, "Sorter_per_Apellidos", $FileName);
        $this->Sorter_per_Subcategoria = new clsSorter($this->ComponentName, "Sorter_per_Subcategoria", $FileName);
        $this->Sorter_per_Ruc = new clsSorter($this->ComponentName, "Sorter_per_Ruc", $FileName);
        $this->Sorter_per_Direccion = new clsSorter($this->ComponentName, "Sorter_per_Direccion", $FileName);
        $this->Sorter_per_Ciudad = new clsSorter($this->ComponentName, "Sorter_per_Ciudad", $FileName);
        $this->Sorter_per_Grupo = new clsSorter($this->ComponentName, "Sorter_per_Grupo", $FileName);
    }
//End Class_Initialize Event

//Initialize Method @351-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @351-DFF86FA6
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["expr417"] = 'CAUTI';
        $this->ds->Parameters["expr476"] = 'cat_Categoria';
        $this->ds->Parameters["urls_per_Catext"] = CCGetFromGet("s_per_Catext", "");
        $this->ds->Parameters["urls_per_Apellidos"] = CCGetFromGet("s_per_Apellidos", "");
        $this->ds->Parameters["urls_per_PersonaContacto"] = CCGetFromGet("s_per_PersonaContacto", "");
        $this->ds->Parameters["urls_per_Subcategoria"] = CCGetFromGet("s_per_Subcategoria", "");
        $this->ds->Parameters["urls_per_Grupo"] = CCGetFromGet("s_per_Grupo", "");
        $this->ds->Parameters["urls_per_Zona"] = CCGetFromGet("s_per_Zona", "");
        $this->ds->Parameters["expr522"] = 0;

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
                $this->per_CodAuxiliar->SetValue($this->ds->per_CodAuxiliar->GetValue());
                $this->per_CodAuxiliar->Parameters = CCGetQueryString("QueryString", Array("pOpCode", "ccsForm"));
                $this->per_CodAuxiliar->Parameters = CCAddParam($this->per_CodAuxiliar->Parameters, "per_CodAuxiliar", $this->ds->f("per_CodAuxiliar"));
                $this->per_CodAuxiliar->Page = "CoAdAu_mant.php";
                $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                $this->per_Subcategoria->SetValue($this->ds->per_Subcategoria->GetValue());
                $this->per_Ruc->SetValue($this->ds->per_Ruc->GetValue());
                $this->per_Direccion->SetValue($this->ds->per_Direccion->GetValue());
                $this->per_Ciudad->SetValue($this->ds->per_Ciudad->GetValue());
                $this->per_Grupo->SetValue($this->ds->per_Grupo->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->per_CodAuxiliar->Show();
                $this->par_Descripcion->Show();
                $this->per_Apellidos->Show();
                $this->per_Subcategoria->Show();
                $this->per_Ruc->Show();
                $this->per_Direccion->Show();
                $this->per_Ciudad->Show();
                $this->per_Grupo->Show();
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
        $this->lkNuevo->SetText("NUEVO AUXILIAR");
        $this->Navigator->Show();
        $this->lkNuevo->Show();
        $this->Sorter_per_CodAuxiliar->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Sorter_per_Apellidos->Show();
        $this->Sorter_per_Subcategoria->Show();
        $this->Sorter_per_Ruc->Show();
        $this->Sorter_per_Direccion->Show();
        $this->Sorter_per_Ciudad->Show();
        $this->Sorter_per_Grupo->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @351-32FE1E0D
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->per_CodAuxiliar->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->per_Apellidos->Errors->ToString();
        $errors .= $this->per_Subcategoria->Errors->ToString();
        $errors .= $this->per_Ruc->Errors->ToString();
        $errors .= $this->per_Direccion->Errors->ToString();
        $errors .= $this->per_Ciudad->Errors->ToString();
        $errors .= $this->per_Grupo->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End CoAdAu_list Class @351-FCB6E20C

class clsCoAdAu_listDataSource extends clsDBdatos {  //CoAdAu_listDataSource Class @351-37544D6F

//DataSource Variables @351-095E6E36
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lkNuevo;
    var $per_CodAuxiliar;
    var $par_Descripcion;
    var $per_Apellidos;
    var $per_Subcategoria;
    var $per_Ruc;
    var $per_Direccion;
    var $per_Ciudad;
    var $per_Grupo;
//End DataSource Variables

//Class_Initialize Event @351-B686ABBE
    function clsCoAdAu_listDataSource()
    {
        $this->ErrorBlock = "Grid CoAdAu_list";
        $this->Initialize();
        $this->lkNuevo = new clsField("lkNuevo", ccsText, "");
        $this->per_CodAuxiliar = new clsField("per_CodAuxiliar", ccsInteger, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->per_Subcategoria = new clsField("per_Subcategoria", ccsInteger, "");
        $this->per_Ruc = new clsField("per_Ruc", ccsText, "");
        $this->per_Direccion = new clsField("per_Direccion", ccsText, "");
        $this->per_Ciudad = new clsField("per_Ciudad", ccsText, "");
        $this->per_Grupo = new clsField("per_Grupo", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @351-645BC00B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "per_Apellidos, per_Nombres";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_per_CodAuxiliar" => array("per_CodAuxiliar", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", ""), 
            "Sorter_per_Apellidos" => array("per_Apellidos", ""), 
            "Sorter_per_Subcategoria" => array("per_Subcategoria", ""), 
            "Sorter_per_Ruc" => array("per_Ruc", ""), 
            "Sorter_per_Direccion" => array("per_Direccion", ""),
            "Sorter_per_Ciudad" => array("per_Ciudad", ""), 
            "Sorter_per_Grupo" => array("per_Grupo", "")));
    }
//End SetOrder Method

//Prepare Method @351-11589FA0
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr417", ccsText, "", "", $this->Parameters["expr417"], "", false);
        $this->wp->AddParameter("2", "expr476", ccsText, "", "", $this->Parameters["expr476"], "", false);
        $this->wp->AddParameter("3", "urls_per_Catext", ccsText, "", "", $this->Parameters["urls_per_Catext"], "", false);
        $this->wp->AddParameter("4", "urls_per_Apellidos", ccsText, "", "", $this->Parameters["urls_per_Apellidos"], "", false);
        $this->wp->AddParameter("5", "urls_per_Apellidos", ccsText, "", "", $this->Parameters["urls_per_Apellidos"], "", false);
        $this->wp->AddParameter("6", "urls_per_PersonaContacto", ccsText, "", "", $this->Parameters["urls_per_PersonaContacto"], "", false);
        $this->wp->AddParameter("7", "urls_per_Subcategoria", ccsInteger, "", "", $this->Parameters["urls_per_Subcategoria"], "", false);
        $this->wp->AddParameter("8", "urls_per_Grupo", ccsInteger, "", "", $this->Parameters["urls_per_Grupo"], "", false);
        $this->wp->AddParameter("9", "urls_per_Zona", ccsInteger, "", "", $this->Parameters["urls_per_Zona"], "", false);
        $this->wp->AddParameter("10", "expr522", ccsInteger, "", "", $this->Parameters["expr522"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opIsNull, "par_Clave", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "par_Descripcion", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "per_Apellidos", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "per_Nombres", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "per_PersonaContacto", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "per_Subcategoria", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsInteger),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opContains, "per_Grupo", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsInteger),false);
        $this->wp->Criterion[9] = $this->wp->Operation(opContains, "per_Zona", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsInteger),false);
        $this->wp->Criterion[10] = $this->wp->Operation(opNotEqual, "per_CodAuxiliar", $this->wp->GetDBValue("10"), $this->ToSQL($this->wp->GetDBValue("10"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opOR(true, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->opOR(true, $this->wp->Criterion[4], $this->wp->Criterion[5])), $this->wp->Criterion[6]), $this->wp->Criterion[7]), $this->wp->Criterion[8]), $this->wp->Criterion[9]), $this->wp->Criterion[10]);
    }
//End Prepare Method

//Open Method @351-8DBD7A39
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (concategorias RIGHT JOIN conpersonas ON concategorias.cat_CodAuxiliar = conpersonas.per_CodAuxiliar) LEFT JOIN genparametros ON concategorias.cat_Categoria = genparametros.par_Secuencia";
        $this->SQL = "SELECT concategorias.*, conpersonas.*, par_Clave, par_Descripcion, concat(per_Apellidos, \" \", per_Nombres) AS per_Apellidos  " .
        "FROM (concategorias RIGHT JOIN conpersonas ON concategorias.cat_CodAuxiliar = conpersonas.per_CodAuxiliar) LEFT JOIN genparametros ON concategorias.cat_Categoria = genparametros.par_Secuencia";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @351-A735EE63
    function SetValues()
    {
        $this->per_CodAuxiliar->SetDBValue(trim($this->f("per_CodAuxiliar")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->per_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->per_Subcategoria->SetDBValue(trim($this->f("per_Subcategoria")));
        $this->per_Ruc->SetDBValue($this->f("per_Ruc"));
        $this->per_Direccion->SetDBValue($this->f("per_Direccion"));
        $this->per_Ciudad->SetDBValue($this->f("per_Ciudad"));
        $this->per_Grupo->SetDBValue(trim($this->f("per_Grupo")));
    }
//End SetValues Method

} //End CoAdAu_listDataSource Class @351-FCB6E20C

//Initialize Page @1-34CC219D
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

$FileName = "CoAdAu.php";
$Redirect = "";
$TemplateFileName = "CoAdAu.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-6A329750
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$CoAdAu_qry = new clsRecordCoAdAu_qry();
$CoAdAu_list = new clsGridCoAdAu_list();
$CoAdAu_qry->Initialize();
$CoAdAu_list->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-7848F630
$Cabecera->Operations();
$CoAdAu_qry->Operation();
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

//Show Page @1-A6E05162
$Cabecera->Show("Cabecera");
$CoAdAu_qry->Show();
$CoAdAu_list->Show();
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
