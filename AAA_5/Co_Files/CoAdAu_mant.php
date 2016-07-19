<?php
/**
*    Busqueda de Auxiliares de tipo Persona
*    @abstract   Lista en una rejilla los Auxiliares que correspondan a un criterio especificado por
*		 el usuario, que puede estar basado en codigo, nombre, categoria, etc.
*    		 En cada linea se dispone un enlace para presentar el registro completo del auxiliar, y
*		 dependiendo de los atributos del usuario, modificarlo
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdAu
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*    @see	 CoAdAu_mant.php
*/
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



Class clsRecordCoAdAu_mant { //CoAdAu_mant Class @212-2A29CCA6

//Variables @212-4A82E0A3

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

//Class_Initialize Event @212-DF4CAB4B
    function clsRecordCoAdAu_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdAu_mant/Error";
        $this->ds = new clsCoAdAu_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdAu_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->per_Apellidos = new clsControl(ccsTextBox, "per_Apellidos", "Per Apellidos", ccsText, "", CCGetRequestParam("per_Apellidos", $Method));
            $this->per_Apellidos->Required = true;
            $this->per_Nombres = new clsControl(ccsTextBox, "per_Nombres", "Per Nombres", ccsText, " ", CCGetRequestParam("per_Nombres", $Method));
            $this->per_Nombres->Required = false;
            $this->lbCategAux = new clsControl(ccsListBox, "lbCategAux", "lbCategAux", ccsText, "", CCGetRequestParam("lbCategAux", $Method));
            $this->lbCategAux->DSType = dsTable;
            list($this->lbCategAux->BoundColumn, $this->lbCategAux->TextColumn, $this->lbCategAux->DBFormat) = array("", "", "");
            $this->lbCategAux->ds = new clsDBdatos();
            $this->lbCategAux->ds->SQL = "SELECT par_Secuencia, par_Descripcion, gencatparam.*  " .
"FROM gencatparam INNER JOIN genparametros ON gencatparam.cat_Codigo = genparametros.par_Categoria";
            $this->lbCategAux->ds->Parameters["expr214"] = 'CAUTI';
            $this->lbCategAux->ds->wp = new clsSQLParameters();
            $this->lbCategAux->ds->wp->AddParameter("1", "expr214", ccsText, "", "", $this->lbCategAux->ds->Parameters["expr214"], "", false);
            $this->lbCategAux->ds->wp->Criterion[1] = $this->lbCategAux->ds->wp->Operation(opEqual, "par_Clave", $this->lbCategAux->ds->wp->GetDBValue("1"), $this->lbCategAux->ds->ToSQL($this->lbCategAux->ds->wp->GetDBValue("1"), ccsText),false);
            $this->lbCategAux->ds->Where = $this->lbCategAux->ds->wp->Criterion[1];
            $this->per_Subcategoria = new clsControl(ccsListBox, "per_Subcategoria", "Per Subcategoria", ccsInteger, "", CCGetRequestParam("per_Subcategoria", $Method));
            $this->per_Subcategoria->DSType = dsTable;
            list($this->per_Subcategoria->BoundColumn, $this->per_Subcategoria->TextColumn, $this->per_Subcategoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_Subcategoria->ds = new clsDBdatos();
            $this->per_Subcategoria->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->per_Subcategoria->ds->Parameters["expr222"] = 'PERSUB';
            $this->per_Subcategoria->ds->wp = new clsSQLParameters();
            $this->per_Subcategoria->ds->wp->AddParameter("1", "expr222", ccsText, "", "", $this->per_Subcategoria->ds->Parameters["expr222"], "", false);
            $this->per_Subcategoria->ds->wp->Criterion[1] = $this->per_Subcategoria->ds->wp->Operation(opEqual, "par_Clave", $this->per_Subcategoria->ds->wp->GetDBValue("1"), $this->per_Subcategoria->ds->ToSQL($this->per_Subcategoria->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_Subcategoria->ds->Where = $this->per_Subcategoria->ds->wp->Criterion[1];
            $this->per_Subcategoria->Required = true;
            $this->per_CodAuxiliar = new clsControl(ccsTextBox, "per_CodAuxiliar", "Còdigo de Auxiliar", ccsText, "", CCGetRequestParam("per_CodAuxiliar", $Method));
            $this->per_Grupo = new clsControl(ccsListBox, "per_Grupo", "Per Grupo", ccsInteger, "", CCGetRequestParam("per_Grupo", $Method));
            $this->per_Grupo->DSType = dsSQL;
            list($this->per_Grupo->BoundColumn, $this->per_Grupo->TextColumn, $this->per_Grupo->DBFormat) = array("", "", "");
            $this->per_Grupo->ds = new clsDBdatos();
            $this->per_Grupo->ds->SQL = "select per_codauxiliar,  " .
            "       concat(left(per_Apellidos,10), \" \", left(per_Nombres,10)) as per_Nombre, cat_Categoria " .
            "from conpersonas inner join  concategorias on  " .
            "     cat_Codauxiliar = per_codauxiliar " .
            "where cat_categoria = 51 and cat_activo = 1";
            $this->per_TipoID = new clsControl(ccsListBox, "per_TipoID", "Per Tipo ID", ccsInteger, "", CCGetRequestParam("per_TipoID", $Method));
            $this->per_TipoID->DSType = dsTable;
            list($this->per_TipoID->BoundColumn, $this->per_TipoID->TextColumn, $this->per_TipoID->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_TipoID->ds = new clsDBdatos();
            $this->per_TipoID->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->per_TipoID->ds->Parameters["expr228"] = 'TIPID';
            $this->per_TipoID->ds->wp = new clsSQLParameters();
            $this->per_TipoID->ds->wp->AddParameter("1", "expr228", ccsText, "", "", $this->per_TipoID->ds->Parameters["expr228"], "", false);
            $this->per_TipoID->ds->wp->Criterion[1] = $this->per_TipoID->ds->wp->Operation(opEqual, "par_Clave", $this->per_TipoID->ds->wp->GetDBValue("1"), $this->per_TipoID->ds->ToSQL($this->per_TipoID->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_TipoID->ds->Where = $this->per_TipoID->ds->wp->Criterion[1];
            $this->per_TipoID->Required = true;
            $this->per_Ruc = new clsControl(ccsTextBox, "per_Ruc", "Per Ruc", ccsText, "", CCGetRequestParam("per_Ruc", $Method));
            $this->per_PersonaContacto = new clsControl(ccsTextBox, "per_PersonaContacto", "Per Persona Contacto", ccsText, "", CCGetRequestParam("per_PersonaContacto", $Method));
            $this->per_Direccion = new clsControl(ccsTextBox, "per_Direccion", "Per Direccion", ccsText, "", CCGetRequestParam("per_Direccion", $Method));
            $this->per_Direccion->Required = true;
            $this->per_Ciudad = new clsControl(ccsTextBox, "per_Ciudad", "Per Ciudad", ccsText, "", CCGetRequestParam("per_Ciudad", $Method));
            $this->per_Ciudad->Required = true;
            $this->per_Telefono1 = new clsControl(ccsTextBox, "per_Telefono1", "Per Telefono1", ccsText, "", CCGetRequestParam("per_Telefono1", $Method));
            $this->per_Telefono2 = new clsControl(ccsTextBox, "per_Telefono2", "Per Telefono2", ccsText, "", CCGetRequestParam("per_Telefono2", $Method));
            $this->per_Telefono3 = new clsControl(ccsTextBox, "per_Telefono3", "Per Telefono3", ccsText, "", CCGetRequestParam("per_Telefono3", $Method));
            $this->per_WebPage = new clsControl(ccsTextBox, "per_WebPage", "Per Web Page", ccsText, "", CCGetRequestParam("per_WebPage", $Method));
            $this->per_Email = new clsControl(ccsTextBox, "per_Email", "Per Email", ccsText, "", CCGetRequestParam("per_Email", $Method));
            $this->per_Pais = new clsControl(ccsListBox, "per_Pais", "Per Pais", ccsInteger, "", CCGetRequestParam("per_Pais", $Method));
            $this->per_Pais->DSType = dsTable;
            list($this->per_Pais->BoundColumn, $this->per_Pais->TextColumn, $this->per_Pais->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_Pais->ds = new clsDBdatos();
            $this->per_Pais->ds->SQL = "SELECT par_Secuencia, par_Descripcion  " .
"FROM genparametros";
            $this->per_Pais->ds->Parameters["expr239"] = 'LPAIS';
            $this->per_Pais->ds->wp = new clsSQLParameters();
            $this->per_Pais->ds->wp->AddParameter("1", "expr239", ccsText, "", "", $this->per_Pais->ds->Parameters["expr239"], "", false);
            $this->per_Pais->ds->wp->Criterion[1] = $this->per_Pais->ds->wp->Operation(opEqual, "par_Clave", $this->per_Pais->ds->wp->GetDBValue("1"), $this->per_Pais->ds->ToSQL($this->per_Pais->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_Pais->ds->Where = $this->per_Pais->ds->wp->Criterion[1];
            $this->per_Provincia = new clsControl(ccsListBox, "per_Provincia", "Per Provincia", ccsInteger, "", CCGetRequestParam("per_Provincia", $Method));
            $this->per_Provincia->DSType = dsTable;
            list($this->per_Provincia->BoundColumn, $this->per_Provincia->TextColumn, $this->per_Provincia->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_Provincia->ds = new clsDBdatos();
            $this->per_Provincia->ds->SQL = "SELECT par_Secuencia, par_Descripcion  " .
"FROM genparametros";
            $this->per_Provincia->ds->Parameters["expr244"] = 'LPROV';
            $this->per_Provincia->ds->wp = new clsSQLParameters();
            $this->per_Provincia->ds->wp->AddParameter("1", "expr244", ccsText, "", "", $this->per_Provincia->ds->Parameters["expr244"], "", false);
            $this->per_Provincia->ds->wp->Criterion[1] = $this->per_Provincia->ds->wp->Operation(opEqual, "par_Clave", $this->per_Provincia->ds->wp->GetDBValue("1"), $this->per_Provincia->ds->ToSQL($this->per_Provincia->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_Provincia->ds->Where = $this->per_Provincia->ds->wp->Criterion[1];
            $this->per_Canton = new clsControl(ccsListBox, "per_Canton", "Per Canton", ccsInteger, "", CCGetRequestParam("per_Canton", $Method));
            $this->per_Canton->DSType = dsTable;
            list($this->per_Canton->BoundColumn, $this->per_Canton->TextColumn, $this->per_Canton->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_Canton->ds = new clsDBdatos();
            $this->per_Canton->ds->SQL = "SELECT par_Secuencia, par_Descripcion  " .
"FROM genparametros";
            $this->per_Canton->ds->Parameters["expr249"] = 'LCANT';
            $this->per_Canton->ds->wp = new clsSQLParameters();
            $this->per_Canton->ds->wp->AddParameter("1", "expr249", ccsText, "", "", $this->per_Canton->ds->Parameters["expr249"], "", false);
            $this->per_Canton->ds->wp->Criterion[1] = $this->per_Canton->ds->wp->Operation(opEqual, "par_Clave", $this->per_Canton->ds->wp->GetDBValue("1"), $this->per_Canton->ds->ToSQL($this->per_Canton->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_Canton->ds->Where = $this->per_Canton->ds->wp->Criterion[1];
            $this->per_Parroquia = new clsControl(ccsListBox, "per_Parroquia", "Per Parroquia", ccsInteger, "", CCGetRequestParam("per_Parroquia", $Method));
            $this->per_Parroquia->DSType = dsTable;
            list($this->per_Parroquia->BoundColumn, $this->per_Parroquia->TextColumn, $this->per_Parroquia->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_Parroquia->ds = new clsDBdatos();
            $this->per_Parroquia->ds->SQL = "SELECT par_Secuencia, par_Descripcion  " .
"FROM genparametros";
            $this->per_Parroquia->ds->Parameters["expr377"] = 'LPARR';
            $this->per_Parroquia->ds->wp = new clsSQLParameters();
            $this->per_Parroquia->ds->wp->AddParameter("1", "expr377", ccsText, "", "", $this->per_Parroquia->ds->Parameters["expr377"], "", false);
            $this->per_Parroquia->ds->wp->Criterion[1] = $this->per_Parroquia->ds->wp->Operation(opEqual, "par_Clave", $this->per_Parroquia->ds->wp->GetDBValue("1"), $this->per_Parroquia->ds->ToSQL($this->per_Parroquia->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_Parroquia->ds->Where = $this->per_Parroquia->ds->wp->Criterion[1];
            $this->per_Zona = new clsControl(ccsListBox, "per_Zona", "Per Zona", ccsInteger, "", CCGetRequestParam("per_Zona", $Method));
            $this->per_Zona->DSType = dsTable;
            list($this->per_Zona->BoundColumn, $this->per_Zona->TextColumn, $this->per_Zona->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_Zona->ds = new clsDBdatos();
            $this->per_Zona->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->per_Zona->ds->Parameters["expr381"] = 'LZONA';
            $this->per_Zona->ds->wp = new clsSQLParameters();
            $this->per_Zona->ds->wp->AddParameter("1", "expr381", ccsText, "", "", $this->per_Zona->ds->Parameters["expr381"], "", false);
            $this->per_Zona->ds->wp->Criterion[1] = $this->per_Zona->ds->wp->Operation(opEqual, "par_Clave", $this->per_Zona->ds->wp->GetDBValue("1"), $this->per_Zona->ds->ToSQL($this->per_Zona->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_Zona->ds->Where = $this->per_Zona->ds->wp->Criterion[1];
            $this->per_SubZona = new clsControl(ccsListBox, "per_SubZona", "Sub Zona", ccsInteger, "", CCGetRequestParam("per_SubZona", $Method));
            $this->per_SubZona->DSType = dsTable;
            list($this->per_SubZona->BoundColumn, $this->per_SubZona->TextColumn, $this->per_SubZona->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->per_SubZona->ds = new clsDBdatos();
            $this->per_SubZona->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->per_SubZona->ds->Parameters["expr382"] = 'LSZON';
            $this->per_SubZona->ds->wp = new clsSQLParameters();
            $this->per_SubZona->ds->wp->AddParameter("1", "expr382", ccsText, "", "", $this->per_SubZona->ds->Parameters["expr382"], "", false);
            $this->per_SubZona->ds->wp->Criterion[1] = $this->per_SubZona->ds->wp->Operation(opEqual, "par_Clave", $this->per_SubZona->ds->wp->GetDBValue("1"), $this->per_SubZona->ds->ToSQL($this->per_SubZona->ds->wp->GetDBValue("1"), ccsText),false);
            $this->per_SubZona->ds->Where = $this->per_SubZona->ds->wp->Criterion[1];
            $this->per_PerFisco = new clsControl(ccsListBox, "per_PerFisco", "Per Per Fisco", ccsInteger, "", CCGetRequestParam("per_PerFisco", $Method));
            $this->per_CodAnterior = new clsControl(ccsTextBox, "per_CodAnterior", "per_CodAnterior", ccsText, "", CCGetRequestParam("per_CodAnterior", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->Button1 = new clsButton("Button1");
            $this->lkNuevo = new clsControl(ccsLink, "lkNuevo", "lkNuevo", ccsText, "", CCGetRequestParam("lkNuevo", $Method));
            $this->lkNuevo->Parameters = CCAddParam($this->lkNuevo->Parameters, "pOpCode", 'ADD');
            $this->lkNuevo->Page = "";
            if(!$this->FormSubmitted) {
                if(!is_array($this->per_Subcategoria->Value) && !strlen($this->per_Subcategoria->Value) && $this->per_Subcategoria->Value !== false)
                $this->per_Subcategoria->SetText(9999);
                if(!is_array($this->per_Grupo->Value) && !strlen($this->per_Grupo->Value) && $this->per_Grupo->Value !== false)
                $this->per_Grupo->SetText(9999);
                if(!is_array($this->per_TipoID->Value) && !strlen($this->per_TipoID->Value) && $this->per_TipoID->Value !== false)
                $this->per_TipoID->SetText(9999);
                if(!is_array($this->per_Telefono1->Value) && !strlen($this->per_Telefono1->Value) && $this->per_Telefono1->Value !== false)
                $this->per_Telefono1->SetText(" ");
                if(!is_array($this->per_Telefono2->Value) && !strlen($this->per_Telefono2->Value) && $this->per_Telefono2->Value !== false)
                $this->per_Telefono2->SetText(" ");
                if(!is_array($this->per_Telefono3->Value) && !strlen($this->per_Telefono3->Value) && $this->per_Telefono3->Value !== false)
                $this->per_Telefono3->SetText(" ");
                if(!is_array($this->per_WebPage->Value) && !strlen($this->per_WebPage->Value) && $this->per_WebPage->Value !== false)
                $this->per_WebPage->SetText(" ");
                if(!is_array($this->per_Email->Value) && !strlen($this->per_Email->Value) && $this->per_Email->Value !== false)
                $this->per_Email->SetText(" ");
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @212-7802DCC6
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlper_CodAuxiliar"] = CCGetFromGet("per_CodAuxiliar", "");
    }
//End Initialize Method

//Validate Method @212-0533B744
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->per_CodAuxiliar->SetValue($this->per_CodAuxiliar->GetValue());
        if(CCDLookUp("COUNT(*)", "conpersonas", "per_CodAuxiliar=" . $this->ds->ToSQL($this->ds->per_CodAuxiliar->GetDBValue(), $this->ds->per_CodAuxiliar->DataType) . $Where, $DBdatos) > 0)
            $this->per_CodAuxiliar->Errors->addError("El campo Còdigo de Auxiliar ya existe.");
        $Validation = ($this->per_Apellidos->Validate() && $Validation);
        $Validation = ($this->per_Nombres->Validate() && $Validation);
        $Validation = ($this->lbCategAux->Validate() && $Validation);
        $Validation = ($this->per_Subcategoria->Validate() && $Validation);
        $Validation = ($this->per_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->per_Grupo->Validate() && $Validation);
        $Validation = ($this->per_TipoID->Validate() && $Validation);
        $Validation = ($this->per_Ruc->Validate() && $Validation);
        $Validation = ($this->per_PersonaContacto->Validate() && $Validation);
        $Validation = ($this->per_Direccion->Validate() && $Validation);
        $Validation = ($this->per_Ciudad->Validate() && $Validation);
        $Validation = ($this->per_Telefono1->Validate() && $Validation);
        $Validation = ($this->per_Telefono2->Validate() && $Validation);
        $Validation = ($this->per_Telefono3->Validate() && $Validation);
        $Validation = ($this->per_WebPage->Validate() && $Validation);
        $Validation = ($this->per_Email->Validate() && $Validation);
        $Validation = ($this->per_Pais->Validate() && $Validation);
        $Validation = ($this->per_Provincia->Validate() && $Validation);
        $Validation = ($this->per_Canton->Validate() && $Validation);
        $Validation = ($this->per_Parroquia->Validate() && $Validation);
        $Validation = ($this->per_Zona->Validate() && $Validation);
        $Validation = ($this->per_SubZona->Validate() && $Validation);
        $Validation = ($this->per_PerFisco->Validate() && $Validation);
        $Validation = ($this->per_CodAnterior->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @212-08C47C61
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->per_Apellidos->Errors->Count());
        $errors = ($errors || $this->per_Nombres->Errors->Count());
        $errors = ($errors || $this->lbCategAux->Errors->Count());
        $errors = ($errors || $this->per_Subcategoria->Errors->Count());
        $errors = ($errors || $this->per_CodAuxiliar->Errors->Count());
        $errors = ($errors || $this->per_Grupo->Errors->Count());
        $errors = ($errors || $this->per_TipoID->Errors->Count());
        $errors = ($errors || $this->per_Ruc->Errors->Count());
        $errors = ($errors || $this->per_PersonaContacto->Errors->Count());
        $errors = ($errors || $this->per_Direccion->Errors->Count());
        $errors = ($errors || $this->per_Ciudad->Errors->Count());
        $errors = ($errors || $this->per_Telefono1->Errors->Count());
        $errors = ($errors || $this->per_Telefono2->Errors->Count());
        $errors = ($errors || $this->per_Telefono3->Errors->Count());
        $errors = ($errors || $this->per_WebPage->Errors->Count());
        $errors = ($errors || $this->per_Email->Errors->Count());
        $errors = ($errors || $this->per_Pais->Errors->Count());
        $errors = ($errors || $this->per_Provincia->Errors->Count());
        $errors = ($errors || $this->per_Canton->Errors->Count());
        $errors = ($errors || $this->per_Parroquia->Errors->Count());
        $errors = ($errors || $this->per_Zona->Errors->Count());
        $errors = ($errors || $this->per_SubZona->Errors->Count());
        $errors = ($errors || $this->per_PerFisco->Errors->Count());
        $errors = ($errors || $this->per_CodAnterior->Errors->Count());
        $errors = ($errors || $this->lkNuevo->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @212-49089211
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
            } else if(strlen(CCGetParam("Button1", ""))) {
                $this->PressedButton = "Button1";
            }
        }
        $Redirect = "CoAdAu_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdAu_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "per_CodAuxiliar"));
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button1") {
            if(!CCGetEvent($this->Button1->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdAu.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "per_CodAuxiliar"));
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

//InsertRow Method @212-8A85A5DD
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->per_Subcategoria->SetValue($this->per_Subcategoria->GetValue());
        $this->ds->per_CodAuxiliar->SetValue($this->per_CodAuxiliar->GetValue());
        $this->ds->per_Grupo->SetValue($this->per_Grupo->GetValue());
        $this->ds->per_Apellidos->SetValue($this->per_Apellidos->GetValue());
        $this->ds->per_Nombres->SetValue($this->per_Nombres->GetValue());
        $this->ds->per_TipoID->SetValue($this->per_TipoID->GetValue());
        $this->ds->per_Ruc->SetValue($this->per_Ruc->GetValue());
        $this->ds->per_PersonaContacto->SetValue($this->per_PersonaContacto->GetValue());
        $this->ds->per_Direccion->SetValue($this->per_Direccion->GetValue());
        $this->ds->per_Ciudad->SetValue($this->per_Ciudad->GetValue());
        $this->ds->per_Telefono1->SetValue($this->per_Telefono1->GetValue());
        $this->ds->per_Telefono2->SetValue($this->per_Telefono2->GetValue());
        $this->ds->per_Telefono3->SetValue($this->per_Telefono3->GetValue());
        $this->ds->per_WebPage->SetValue($this->per_WebPage->GetValue());
        $this->ds->per_Email->SetValue($this->per_Email->GetValue());
        $this->ds->per_Pais->SetValue($this->per_Pais->GetValue());
        $this->ds->per_Provincia->SetValue($this->per_Provincia->GetValue());
        $this->ds->per_Canton->SetValue($this->per_Canton->GetValue());
        $this->ds->per_Parroquia->SetValue($this->per_Parroquia->GetValue());
        $this->ds->per_Zona->SetValue($this->per_Zona->GetValue());
        $this->ds->per_SubZona->SetValue($this->per_SubZona->GetValue());
        $this->ds->per_PerFisco->SetValue($this->per_PerFisco->GetValue());
        $this->ds->per_CodAnterior->SetValue($this->per_CodAnterior->GetValue());
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

//UpdateRow Method @212-683AD8D0
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->per_Apellidos->SetValue($this->per_Apellidos->GetValue());
        $nom=$this->per_Nombres->GetValue();
        if (empty($nom))
            $this->ds->per_Nombres->SetValue(' ');
        else
            $this->ds->per_Nombres->SetValue($this->per_Nombres->GetValue());
        $this->ds->lbCategAux->SetValue($this->lbCategAux->GetValue());
        $this->ds->per_Subcategoria->SetValue($this->per_Subcategoria->GetValue());
        $this->ds->per_CodAuxiliar->SetValue($this->per_CodAuxiliar->GetValue());
        $this->ds->per_Grupo->SetValue($this->per_Grupo->GetValue());
        $this->ds->per_TipoID->SetValue($this->per_TipoID->GetValue());
        $this->ds->per_Ruc->SetValue($this->per_Ruc->GetValue());
        $this->ds->per_PersonaContacto->SetValue($this->per_PersonaContacto->GetValue());
        $this->ds->per_Direccion->SetValue($this->per_Direccion->GetValue());
        $this->ds->per_Ciudad->SetValue($this->per_Ciudad->GetValue());
        $this->ds->per_Telefono1->SetValue($this->per_Telefono1->GetValue());
        $this->ds->per_Telefono2->SetValue($this->per_Telefono2->GetValue());
        $this->ds->per_Telefono3->SetValue($this->per_Telefono3->GetValue());
        $this->ds->per_WebPage->SetValue($this->per_WebPage->GetValue());
        $this->ds->per_Email->SetValue($this->per_Email->GetValue());
        $this->ds->per_Pais->SetValue($this->per_Pais->GetValue());
        $this->ds->per_Provincia->SetValue($this->per_Provincia->GetValue());
        $this->ds->per_Canton->SetValue($this->per_Canton->GetValue());
        $this->ds->per_Parroquia->SetValue($this->per_Parroquia->GetValue());
        $this->ds->per_Zona->SetValue($this->per_Zona->GetValue());
        $this->ds->per_SubZona->SetValue($this->per_SubZona->GetValue());
        $this->ds->per_PerFisco->SetValue($this->per_PerFisco->GetValue());
        $this->ds->per_CodAnterior->SetValue($this->per_CodAnterior->GetValue());
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

//DeleteRow Method @212-EA88835F
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

//Show Method @212-3B4A2C59
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->lbCategAux->Prepare();
        $this->per_Subcategoria->Prepare();
        $this->per_Grupo->Prepare();
        $this->per_TipoID->Prepare();
        $this->per_Pais->Prepare();
        $this->per_Provincia->Prepare();
        $this->per_Canton->Prepare();
        $this->per_Parroquia->Prepare();
        $this->per_Zona->Prepare();
        $this->per_SubZona->Prepare();
        $this->per_PerFisco->Prepare();

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
                    echo "Error in Record CoAdAu_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                        $this->per_Nombres->SetValue($this->ds->per_Nombres->GetValue());
                        $this->per_Subcategoria->SetValue($this->ds->per_Subcategoria->GetValue());
                        $this->per_CodAuxiliar->SetValue($this->ds->per_CodAuxiliar->GetValue());
                        $this->per_Grupo->SetValue($this->ds->per_Grupo->GetValue());
                        $this->per_TipoID->SetValue($this->ds->per_TipoID->GetValue());
                        $this->per_Ruc->SetValue($this->ds->per_Ruc->GetValue());
                        $this->per_PersonaContacto->SetValue($this->ds->per_PersonaContacto->GetValue());
                        $this->per_Direccion->SetValue($this->ds->per_Direccion->GetValue());
                        $this->per_Ciudad->SetValue($this->ds->per_Ciudad->GetValue());
                        $this->per_Telefono1->SetValue($this->ds->per_Telefono1->GetValue());
                        $this->per_Telefono2->SetValue($this->ds->per_Telefono2->GetValue());
                        $this->per_Telefono3->SetValue($this->ds->per_Telefono3->GetValue());
                        $this->per_WebPage->SetValue($this->ds->per_WebPage->GetValue());
                        $this->per_Email->SetValue($this->ds->per_Email->GetValue());
                        $this->per_Pais->SetValue($this->ds->per_Pais->GetValue());
                        $this->per_Provincia->SetValue($this->ds->per_Provincia->GetValue());
                        $this->per_Canton->SetValue($this->ds->per_Canton->GetValue());
                        $this->per_Parroquia->SetValue($this->ds->per_Parroquia->GetValue());
                        $this->per_Zona->SetValue($this->ds->per_Zona->GetValue());
                        $this->per_SubZona->SetValue($this->ds->per_SubZona->GetValue());
                        $this->per_PerFisco->SetValue($this->ds->per_PerFisco->GetValue());
                        $this->per_CodAnterior->SetValue($this->ds->per_CodAnterior->GetValue());
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
            $this->lkNuevo->SetText('NUEVO');
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbTitulo->Errors->ToString();
            $Error .= $this->per_Apellidos->Errors->ToString();
            $Error .= $this->per_Nombres->Errors->ToString();
            $Error .= $this->lbCategAux->Errors->ToString();
            $Error .= $this->per_Subcategoria->Errors->ToString();
            $Error .= $this->per_CodAuxiliar->Errors->ToString();
            $Error .= $this->per_Grupo->Errors->ToString();
            $Error .= $this->per_TipoID->Errors->ToString();
            $Error .= $this->per_Ruc->Errors->ToString();
            $Error .= $this->per_PersonaContacto->Errors->ToString();
            $Error .= $this->per_Direccion->Errors->ToString();
            $Error .= $this->per_Ciudad->Errors->ToString();
            $Error .= $this->per_Telefono1->Errors->ToString();
            $Error .= $this->per_Telefono2->Errors->ToString();
            $Error .= $this->per_Telefono3->Errors->ToString();
            $Error .= $this->per_WebPage->Errors->ToString();
            $Error .= $this->per_Email->Errors->ToString();
            $Error .= $this->per_Pais->Errors->ToString();
            $Error .= $this->per_Provincia->Errors->ToString();
            $Error .= $this->per_Canton->Errors->ToString();
            $Error .= $this->per_Parroquia->Errors->ToString();
            $Error .= $this->per_Zona->Errors->ToString();
            $Error .= $this->per_SubZona->Errors->ToString();
            $Error .= $this->per_PerFisco->Errors->ToString();
            $Error .= $this->per_CodAnterior->Errors->ToString();
            $Error .= $this->lkNuevo->Errors->ToString();
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

        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;
        $this->lbTitulo->Show();
        $this->per_Apellidos->Show();
        $this->per_Nombres->Show();
        $this->lbCategAux->Show();
        $this->per_Subcategoria->Show();
        $this->per_CodAuxiliar->Show();
        $this->per_Grupo->Show();
        $this->per_TipoID->Show();
        $this->per_Ruc->Show();
        $this->per_PersonaContacto->Show();
        $this->per_Direccion->Show();
        $this->per_Ciudad->Show();
        $this->per_Telefono1->Show();
        $this->per_Telefono2->Show();
        $this->per_Telefono3->Show();
        $this->per_WebPage->Show();
        $this->per_Email->Show();
        $this->per_Pais->Show();
        $this->per_Provincia->Show();
        $this->per_Canton->Show();
        $this->per_Parroquia->Show();
        $this->per_Zona->Show();
        $this->per_SubZona->Show();
        $this->per_PerFisco->Show();
        $this->per_CodAnterior->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->Button1->Show();
        $this->lkNuevo->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdAu_mant Class @212-FCB6E20C

class clsCoAdAu_mantDataSource extends clsDBdatos {  //CoAdAu_mantDataSource Class @212-34C84366

//DataSource Variables @212-8A0ABB2C
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $lbTitulo;
    var $per_Apellidos;
    var $per_Nombres;
    var $lbCategAux;
    var $per_Subcategoria;
    var $per_CodAuxiliar;
    var $per_Grupo;
    var $per_TipoID;
    var $per_Ruc;
    var $per_PersonaContacto;
    var $per_Direccion;
    var $per_Ciudad;
    var $per_Telefono1;
    var $per_Telefono2;
    var $per_Telefono3;
    var $per_WebPage;
    var $per_Email;
    var $per_Pais;
    var $per_Provincia;
    var $per_Canton;
    var $per_Parroquia;
    var $per_Zona;
    var $per_SubZona;
    var $per_PerFisco;
    var $per_CodAnterior;
    var $lkNuevo;
//End DataSource Variables

//Class_Initialize Event @212-23022BBE
    function clsCoAdAu_mantDataSource()
    {
        $this->ErrorBlock = "Record CoAdAu_mant/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->per_Nombres = new clsField("per_Nombres", ccsText, "");
        $this->lbCategAux = new clsField("lbCategAux", ccsText, "");
        $this->per_Subcategoria = new clsField("per_Subcategoria", ccsInteger, "");
        $this->per_CodAuxiliar = new clsField("per_CodAuxiliar", ccsText, "");
        $this->per_Grupo = new clsField("per_Grupo", ccsInteger, "");
        $this->per_TipoID = new clsField("per_TipoID", ccsInteger, "");
        $this->per_Ruc = new clsField("per_Ruc", ccsText, "");
        $this->per_PersonaContacto = new clsField("per_PersonaContacto", ccsText, "");
        $this->per_Direccion = new clsField("per_Direccion", ccsText, "");
        $this->per_Ciudad = new clsField("per_Ciudad", ccsText, "");
        $this->per_Telefono1 = new clsField("per_Telefono1", ccsText, "");
        $this->per_Telefono2 = new clsField("per_Telefono2", ccsText, "");
        $this->per_Telefono3 = new clsField("per_Telefono3", ccsText, "");
        $this->per_WebPage = new clsField("per_WebPage", ccsText, "");
        $this->per_Email = new clsField("per_Email", ccsText, "");
        $this->per_Pais = new clsField("per_Pais", ccsInteger, "");
        $this->per_Provincia = new clsField("per_Provincia", ccsInteger, "");
        $this->per_Canton = new clsField("per_Canton", ccsInteger, "");
        $this->per_Parroquia = new clsField("per_Parroquia", ccsInteger, "");
        $this->per_Zona = new clsField("per_Zona", ccsInteger, "");
        $this->per_SubZona = new clsField("per_SubZona", ccsInteger, "");
        $this->per_PerFisco = new clsField("per_PerFisco", ccsInteger, "");
        $this->per_CodAnterior = new clsField("per_CodAnterior", ccsText, "");
        $this->lkNuevo = new clsField("lkNuevo", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @212-CE8A8A30
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlper_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urlper_CodAuxiliar"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "per_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @212-85F28BEE
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM conpersonas";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @212-7461769A
    function SetValues()
    {
        $this->per_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->per_Nombres->SetDBValue($this->f("per_Nombres"));
        $this->per_Subcategoria->SetDBValue(trim($this->f("per_Subcategoria")));
        $this->per_CodAuxiliar->SetDBValue($this->f("per_CodAuxiliar"));
        $this->per_Grupo->SetDBValue(trim($this->f("per_Grupo")));
        $this->per_TipoID->SetDBValue(trim($this->f("per_TipoID")));
        $this->per_Ruc->SetDBValue($this->f("per_Ruc"));
        $this->per_PersonaContacto->SetDBValue($this->f("per_PersonaContacto"));
        $this->per_Direccion->SetDBValue($this->f("per_Direccion"));
        $this->per_Ciudad->SetDBValue($this->f("per_Ciudad"));
        $this->per_Telefono1->SetDBValue($this->f("per_Telefono1"));
        $this->per_Telefono2->SetDBValue($this->f("per_Telefono2"));
        $this->per_Telefono3->SetDBValue($this->f("per_Telefono3"));
        $this->per_WebPage->SetDBValue($this->f("per_WebPage"));
        $this->per_Email->SetDBValue($this->f("per_Email"));
        $this->per_Pais->SetDBValue(trim($this->f("per_Pais")));
        $this->per_Provincia->SetDBValue(trim($this->f("per_Provincia")));
        $this->per_Canton->SetDBValue(trim($this->f("per_Canton")));
        $this->per_Parroquia->SetDBValue(trim($this->f("per_Parroquia")));
        $this->per_Zona->SetDBValue(trim($this->f("per_Zona")));
        $this->per_SubZona->SetDBValue(trim($this->f("per_SubZona")));
        $this->per_PerFisco->SetDBValue(trim($this->f("per_PerFisco")));
        $this->per_CodAnterior->SetDBValue($this->f("per_CodAnterior"));
    }
//End SetValues Method

//Insert Method @212-C081524A
    function Insert()
    {
        $this->cp["per_Subcategoria"] = new clsSQLParameter("ctrlper_Subcategoria", ccsInteger, "", "", $this->per_Subcategoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_CodAuxiliar"] = new clsSQLParameter("ctrlper_CodAuxiliar", ccsText, "", "", $this->per_CodAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Grupo"] = new clsSQLParameter("ctrlper_Grupo", ccsInteger, "", "", $this->per_Grupo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Apellidos"] = new clsSQLParameter("ctrlper_Apellidos", ccsText, "", "", $this->per_Apellidos->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Nombres"] = new clsSQLParameter("ctrlper_Nombres", ccsText, "", "", $this->per_Nombres->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_TipoID"] = new clsSQLParameter("ctrlper_TipoID", ccsInteger, "", "", $this->per_TipoID->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Ruc"] = new clsSQLParameter("ctrlper_Ruc", ccsText, "", "", $this->per_Ruc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_PersonaContacto"] = new clsSQLParameter("ctrlper_PersonaContacto", ccsText, "", "", $this->per_PersonaContacto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Direccion"] = new clsSQLParameter("ctrlper_Direccion", ccsText, "", "", $this->per_Direccion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Ciudad"] = new clsSQLParameter("ctrlper_Ciudad", ccsText, "", "", $this->per_Ciudad->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Telefono1"] = new clsSQLParameter("ctrlper_Telefono1", ccsText, "", "", $this->per_Telefono1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Telefono2"] = new clsSQLParameter("ctrlper_Telefono2", ccsText, "", "", $this->per_Telefono2->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Telefono3"] = new clsSQLParameter("ctrlper_Telefono3", ccsText, "", "", $this->per_Telefono3->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_WebPage"] = new clsSQLParameter("ctrlper_WebPage", ccsText, "", "", $this->per_WebPage->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Email"] = new clsSQLParameter("ctrlper_Email", ccsText, "", "", $this->per_Email->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Pais"] = new clsSQLParameter("ctrlper_Pais", ccsInteger, "", "", $this->per_Pais->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Provincia"] = new clsSQLParameter("ctrlper_Provincia", ccsInteger, "", "", $this->per_Provincia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Canton"] = new clsSQLParameter("ctrlper_Canton", ccsInteger, "", "", $this->per_Canton->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Parroquia"] = new clsSQLParameter("ctrlper_Parroquia", ccsInteger, "", "", $this->per_Parroquia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_Zona"] = new clsSQLParameter("ctrlper_Zona", ccsInteger, "", "", $this->per_Zona->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_SubZona"] = new clsSQLParameter("ctrlper_SubZona", ccsInteger, "", "", $this->per_SubZona->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_PerFisco"] = new clsSQLParameter("ctrlper_PerFisco", ccsInteger, "", "", $this->per_PerFisco->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["per_CodAnterior"] = new clsSQLParameter("ctrlper_CodAnterior", ccsText, "", "", $this->per_CodAnterior->GetValue(), '', false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO conpersonas ("
             . "per_Subcategoria, "
             . "per_CodAuxiliar, "
             . "per_Grupo, "
             . "per_Apellidos, "
             . "per_Nombres, "
             . "per_TipoID, "
             . "per_Ruc, "
             . "per_PersonaContacto, "
             . "`per_Direccion`, "
             . "per_Ciudad, "
             . "`per_Telefono1`, "
             . "`per_Telefono2`, "
             . "`per_Telefono3`, "
             . "per_WebPage, "
             . "per_Email, "
             . "per_Pais, "
             . "per_Provincia, "
             . "per_Canton, "
             . "per_Parroquia, "
             . "per_Zona, "
             . "per_SubZona, "
             . "per_PerFisco, "
             . "per_CodAnterior"
             . ") VALUES ("
             . $this->ToSQL($this->cp["per_Subcategoria"]->GetDBValue(), $this->cp["per_Subcategoria"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_CodAuxiliar"]->GetDBValue(), $this->cp["per_CodAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Grupo"]->GetDBValue(), $this->cp["per_Grupo"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Apellidos"]->GetDBValue(), $this->cp["per_Apellidos"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Nombres"]->GetDBValue(), $this->cp["per_Nombres"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_TipoID"]->GetDBValue(), $this->cp["per_TipoID"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Ruc"]->GetDBValue(), $this->cp["per_Ruc"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_PersonaContacto"]->GetDBValue(), $this->cp["per_PersonaContacto"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Direccion"]->GetDBValue(), $this->cp["per_Direccion"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Ciudad"]->GetDBValue(), $this->cp["per_Ciudad"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Telefono1"]->GetDBValue(), $this->cp["per_Telefono1"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Telefono2"]->GetDBValue(), $this->cp["per_Telefono2"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Telefono3"]->GetDBValue(), $this->cp["per_Telefono3"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_WebPage"]->GetDBValue(), $this->cp["per_WebPage"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Email"]->GetDBValue(), $this->cp["per_Email"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Pais"]->GetDBValue(), $this->cp["per_Pais"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Provincia"]->GetDBValue(), $this->cp["per_Provincia"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Canton"]->GetDBValue(), $this->cp["per_Canton"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Parroquia"]->GetDBValue(), $this->cp["per_Parroquia"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_Zona"]->GetDBValue(), $this->cp["per_Zona"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_SubZona"]->GetDBValue(), $this->cp["per_SubZona"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_PerFisco"]->GetDBValue(), $this->cp["per_PerFisco"]->DataType) . ", "
             . $this->ToSQL($this->cp["per_CodAnterior"]->GetDBValue(), $this->cp["per_CodAnterior"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @212-0001C461
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE conpersonas SET "
             . "per_Apellidos=" . $this->ToSQL($this->per_Apellidos->GetDBValue(), $this->per_Apellidos->DataType) . ", "
             . "per_Nombres=" . $this->ToSQL($this->per_Nombres->GetDBValue(), $this->per_Nombres->DataType) . ", "
             . "per_Subcategoria=" . $this->ToSQL($this->per_Subcategoria->GetDBValue(), $this->per_Subcategoria->DataType) . ", "
             . "per_CodAuxiliar=" . $this->ToSQL($this->per_CodAuxiliar->GetDBValue(), $this->per_CodAuxiliar->DataType) . ", "
             . "per_Grupo=" . $this->ToSQL($this->per_Grupo->GetDBValue(), $this->per_Grupo->DataType) . ", "
             . "per_TipoID=" . $this->ToSQL($this->per_TipoID->GetDBValue(), $this->per_TipoID->DataType) . ", "
             . "per_Ruc=" . $this->ToSQL($this->per_Ruc->GetDBValue(), $this->per_Ruc->DataType) . ", "
             . "per_PersonaContacto=" . $this->ToSQL($this->per_PersonaContacto->GetDBValue(), $this->per_PersonaContacto->DataType) . ", "
             . "`per_Direccion`=" . $this->ToSQL($this->per_Direccion->GetDBValue(), $this->per_Direccion->DataType) . ", "
             . "per_Ciudad=" . $this->ToSQL($this->per_Ciudad->GetDBValue(), $this->per_Ciudad->DataType) . ", "
             . "`per_Telefono1`=" . $this->ToSQL($this->per_Telefono1->GetDBValue(), $this->per_Telefono1->DataType) . ", "
             . "`per_Telefono2`=" . $this->ToSQL($this->per_Telefono2->GetDBValue(), $this->per_Telefono2->DataType) . ", "
             . "`per_Telefono3`=" . $this->ToSQL($this->per_Telefono3->GetDBValue(), $this->per_Telefono3->DataType) . ", "
             . "per_WebPage=" . $this->ToSQL($this->per_WebPage->GetDBValue(), $this->per_WebPage->DataType) . ", "
             . "per_Email=" . $this->ToSQL($this->per_Email->GetDBValue(), $this->per_Email->DataType) . ", "
             . "per_Pais=" . $this->ToSQL($this->per_Pais->GetDBValue(), $this->per_Pais->DataType) . ", "
             . "per_Provincia=" . $this->ToSQL($this->per_Provincia->GetDBValue(), $this->per_Provincia->DataType) . ", "
             . "per_Canton=" . $this->ToSQL($this->per_Canton->GetDBValue(), $this->per_Canton->DataType) . ", "
             . "per_Parroquia=" . $this->ToSQL($this->per_Parroquia->GetDBValue(), $this->per_Parroquia->DataType) . ", "
             . "per_Zona=" . $this->ToSQL($this->per_Zona->GetDBValue(), $this->per_Zona->DataType) . ", "
             . "per_SubZona=" . $this->ToSQL($this->per_SubZona->GetDBValue(), $this->per_SubZona->DataType) . ", "
             . "per_PerFisco=" . $this->ToSQL($this->per_PerFisco->GetDBValue(), $this->per_PerFisco->DataType) . ", "
             . "per_CodAnterior=" . $this->ToSQL($this->per_CodAnterior->GetDBValue(), $this->per_CodAnterior->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @212-5EDF1012
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM conpersonas";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoAdAu_mantDataSource Class @212-FCB6E20C

Class clsEditableGridCoAdAu_cate { //CoAdAu_cate Class @334-3EF0D2CF

//Variables @334-35C6B523

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
    var $Sorter_cat_Categoria;
    var $Sorter_cat_FecIncorp;
    var $Sorter_cat_Activo;
    var $Navigator;
//End Variables

//Class_Initialize Event @334-244D15A5
    function clsEditableGridCoAdAu_cate()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid CoAdAu_cate/Error";
        $this->ComponentName = "CoAdAu_cate";
        $this->CachedColumns["cat_Categoria"][0] = "cat_Categoria";
        $this->CachedColumns["cat_CodAuxiliar"][0] = "cat_CodAuxiliar";
        $this->ds = new clsCoAdAu_cateDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 10)
            $this->PageSize = 10;
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

        $this->SorterName = CCGetParam("CoAdAu_cateOrder", "");
        $this->SorterDirection = CCGetParam("CoAdAu_cateDir", "");

        $this->Sorter_cat_Categoria = new clsSorter($this->ComponentName, "Sorter_cat_Categoria", $FileName);
        $this->Sorter_cat_FecIncorp = new clsSorter($this->ComponentName, "Sorter_cat_FecIncorp", $FileName);
        $this->Sorter_cat_Activo = new clsSorter($this->ComponentName, "Sorter_cat_Activo", $FileName);
        $this->cat_CodAuxiliar = new clsControl(ccsHidden, "cat_CodAuxiliar", "Cat Cod Auxiliar", ccsInteger, "");
        $this->cat_Categoria = new clsControl(ccsListBox, "cat_Categoria", "Cat Categoria", ccsInteger, "");
        $this->cat_Categoria->DSType = dsTable;
        list($this->cat_Categoria->BoundColumn, $this->cat_Categoria->TextColumn, $this->cat_Categoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
        $this->cat_Categoria->ds = new clsDBdatos();
        $this->cat_Categoria->ds->SQL = "SELECT par_Secuencia, par_Descripcion, par_Valor1  " .
"FROM genparametros";
        $this->cat_Categoria->ds->Order = "par_Descripcion";
        $this->cat_Categoria->ds->Parameters["expr340"] = 'CAUTI';
        $this->cat_Categoria->ds->Parameters["expr376"] = 'Persona';
        $this->cat_Categoria->ds->wp = new clsSQLParameters();
        $this->cat_Categoria->ds->wp->AddParameter("1", "expr340", ccsText, "", "", $this->cat_Categoria->ds->Parameters["expr340"], "", false);
        $this->cat_Categoria->ds->wp->AddParameter("2", "expr376", ccsText, "", "", $this->cat_Categoria->ds->Parameters["expr376"], "", false);
        $this->cat_Categoria->ds->wp->Criterion[1] = $this->cat_Categoria->ds->wp->Operation(opEqual, "par_Clave", $this->cat_Categoria->ds->wp->GetDBValue("1"), $this->cat_Categoria->ds->ToSQL($this->cat_Categoria->ds->wp->GetDBValue("1"), ccsText),false);
        $this->cat_Categoria->ds->wp->Criterion[2] = $this->cat_Categoria->ds->wp->Operation(opEqual, "par_Valor1", $this->cat_Categoria->ds->wp->GetDBValue("2"), $this->cat_Categoria->ds->ToSQL($this->cat_Categoria->ds->wp->GetDBValue("2"), ccsText),false);
        $this->cat_Categoria->ds->Where = $this->cat_Categoria->ds->wp->opAND(false, $this->cat_Categoria->ds->wp->Criterion[1], $this->cat_Categoria->ds->wp->Criterion[2]);
        $this->cat_Categoria->Required = true;
        $this->cat_FecIncorp = new clsControl(ccsTextBox, "cat_FecIncorp", "Cat Fec Incorp", ccsDate, Array("dd", "/", "mm", "/", "yy"));
        $this->cat_FecIncorp->Required = true;
        $this->DatePicker_cat_FecIncorp = new clsDatePicker("DatePicker_cat_FecIncorp", "CoAdAu_cate", "cat_FecIncorp");
        $this->cat_Activo = new clsControl(ccsListBox, "cat_Activo", "Cat Activo", ccsInteger, "");
        $this->cat_Activo->DSType = dsTable;
        list($this->cat_Activo->BoundColumn, $this->cat_Activo->TextColumn, $this->cat_Activo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
        $this->cat_Activo->ds = new clsDBdatos();
        $this->cat_Activo->ds->SQL = "SELECT *  " .
"FROM genparametros";
        $this->cat_Activo->ds->Parameters["expr347"] = 'LGESTA';
        $this->cat_Activo->ds->wp = new clsSQLParameters();
        $this->cat_Activo->ds->wp->AddParameter("1", "expr347", ccsText, "", "", $this->cat_Activo->ds->Parameters["expr347"], "", false);
        $this->cat_Activo->ds->wp->Criterion[1] = $this->cat_Activo->ds->wp->Operation(opEqual, "par_Clave", $this->cat_Activo->ds->wp->GetDBValue("1"), $this->cat_Activo->ds->ToSQL($this->cat_Activo->ds->wp->GetDBValue("1"), ccsText),false);
        $this->cat_Activo->ds->Where = $this->cat_Activo->ds->wp->Criterion[1];
        $this->cat_Activo->Required = true;
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @334-702A1C54
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlper_CodAuxiliar"] = CCGetFromGet("per_CodAuxiliar", "");
    }
//End Initialize Method

//GetFormParameters Method @334-B802970B
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["cat_CodAuxiliar"][$RowNumber] = CCGetFromPost("cat_CodAuxiliar_" . $RowNumber);
            $this->FormParameters["cat_Categoria"][$RowNumber] = CCGetFromPost("cat_Categoria_" . $RowNumber);
            $this->FormParameters["cat_FecIncorp"][$RowNumber] = CCGetFromPost("cat_FecIncorp_" . $RowNumber);
            $this->FormParameters["cat_Activo"][$RowNumber] = CCGetFromPost("cat_Activo_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @334-0B207633
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cat_Categoria"] = $this->CachedColumns["cat_Categoria"][$RowNumber];
            $this->ds->CachedColumns["cat_CodAuxiliar"] = $this->CachedColumns["cat_CodAuxiliar"][$RowNumber];
            $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
            $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
            $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
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

//ValidateRow Method @334-50BA8EA2
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->cat_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->cat_Categoria->Validate() && $Validation);
        $Validation = ($this->cat_FecIncorp->Validate() && $Validation);
        $Validation = ($this->cat_Activo->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->cat_CodAuxiliar->Errors->ToString();
            $errors .= $this->cat_Categoria->Errors->ToString();
            $errors .= $this->cat_FecIncorp->Errors->ToString();
            $errors .= $this->cat_Activo->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->cat_CodAuxiliar->Errors->Clear();
            $this->cat_Categoria->Errors->Clear();
            $this->cat_FecIncorp->Errors->Clear();
            $this->cat_Activo->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @334-F83B46DA
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["cat_CodAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_Categoria"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_FecIncorp"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_Activo"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @334-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @334-7B861278
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

//UpdateGrid Method @334-BB1F78CA
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cat_Categoria"] = $this->CachedColumns["cat_Categoria"][$RowNumber];
            $this->ds->CachedColumns["cat_CodAuxiliar"] = $this->CachedColumns["cat_CodAuxiliar"][$RowNumber];
            $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
            $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
            $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
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

//InsertRow Method @334-87AA598F
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->cat_Categoria->SetValue($this->cat_Categoria->GetValue());
        $this->ds->cat_FecIncorp->SetValue($this->cat_FecIncorp->GetValue());
        $this->ds->cat_Activo->SetValue($this->cat_Activo->GetValue());
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

//UpdateRow Method @334-991D06EB
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->cat_CodAuxiliar->SetValue($this->cat_CodAuxiliar->GetValue());
        $this->ds->cat_Categoria->SetValue($this->cat_Categoria->GetValue());
        $this->ds->cat_FecIncorp->SetValue($this->cat_FecIncorp->GetValue());
        $this->ds->cat_Activo->SetValue($this->cat_Activo->GetValue());
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

//DeleteRow Method @334-0C9DDC34
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

//FormScript Method @334-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @334-6AC55CA7
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
                $this->CachedColumns["cat_Categoria"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["cat_Categoria"][$RowNumber] = "";
                $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @334-D3A273AF
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cat_Categoria"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cat_CodAuxiliar"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @334-F894360F
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cat_Categoria->Prepare();
        $this->cat_Activo->Prepare();

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
                        $this->CachedColumns["cat_Categoria"][$RowNumber] = $this->ds->CachedColumns["cat_Categoria"];
                        $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = $this->ds->CachedColumns["cat_CodAuxiliar"];
                        $this->cat_CodAuxiliar->SetValue($this->ds->cat_CodAuxiliar->GetValue());
                        $this->cat_Categoria->SetValue($this->ds->cat_Categoria->GetValue());
                        $this->cat_FecIncorp->SetValue($this->ds->cat_FecIncorp->GetValue());
                        $this->cat_Activo->SetValue($this->ds->cat_Activo->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["cat_Categoria"][$RowNumber] = "";
                        $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = "";
                        $this->cat_CodAuxiliar->SetText("");
                        $this->cat_Categoria->SetText("");
                        $this->cat_FecIncorp->SetText("");
                        $this->cat_Activo->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
                        $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
                        $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
                        $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->cat_CodAuxiliar->Show($RowNumber);
                    $this->cat_Categoria->Show($RowNumber);
                    $this->cat_FecIncorp->Show($RowNumber);
                    $this->DatePicker_cat_FecIncorp->Show($RowNumber);
                    $this->cat_Activo->Show($RowNumber);
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
        $this->Sorter_cat_Categoria->Show();
        $this->Sorter_cat_FecIncorp->Show();
        $this->Sorter_cat_Activo->Show();
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

} //End CoAdAu_cate Class @334-FCB6E20C

class clsCoAdAu_cateDataSource extends clsDBdatos {  //CoAdAu_cateDataSource Class @334-4B07D03F

//DataSource Variables @334-E72AC76A
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
    var $cat_CodAuxiliar;
    var $cat_Categoria;
    var $cat_FecIncorp;
    var $cat_Activo;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @334-3C881A3D
    function clsCoAdAu_cateDataSource()
    {
        $this->ErrorBlock = "EditableGrid CoAdAu_cate/Error";
        $this->Initialize();
        $this->cat_CodAuxiliar = new clsField("cat_CodAuxiliar", ccsInteger, "");
        $this->cat_Categoria = new clsField("cat_Categoria", ccsInteger, "");
        $this->cat_FecIncorp = new clsField("cat_FecIncorp", ccsDate, Array("yyyy", "-", "mm", "-", "dd"));
        $this->cat_Activo = new clsField("cat_Activo", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @334-BDE14459
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cat_Categoria" => array("cat_Categoria", ""), 
            "Sorter_cat_FecIncorp" => array("cat_FecIncorp", ""), 
            "Sorter_cat_Activo" => array("cat_Activo", "")));
    }
//End SetOrder Method

//Prepare Method @334-0B4B192D
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlper_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urlper_CodAuxiliar"], -1, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cat_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @334-BB740A65
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM concategorias";
        $this->SQL = "SELECT *  " .
        "FROM concategorias";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @334-5B18EEDD
    function SetValues()
    {
        $this->CachedColumns["cat_Categoria"] = $this->f("cat_Categoria");
        $this->CachedColumns["cat_CodAuxiliar"] = $this->f("cat_CodAuxiliar");
        $this->cat_CodAuxiliar->SetDBValue(trim($this->f("cat_CodAuxiliar")));
        $this->cat_Categoria->SetDBValue(trim($this->f("cat_Categoria")));
        $this->cat_FecIncorp->SetDBValue(trim($this->f("cat_FecIncorp")));
        $this->cat_Activo->SetDBValue(trim($this->f("cat_Activo")));
    }
//End SetValues Method

//Insert Method @334-638B9F4B
    function Insert()
    {
        $this->cp["cat_CodAuxiliar"] = new clsSQLParameter("urlper_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("per_CodAuxiliar", ""), -1, false, $this->ErrorBlock);
        $this->cp["cat_Categoria"] = new clsSQLParameter("ctrlcat_Categoria", ccsInteger, "", "", $this->cat_Categoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_FecIncorp"] = new clsSQLParameter("ctrlcat_FecIncorp", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->cat_FecIncorp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Activo"] = new clsSQLParameter("ctrlcat_Activo", ccsInteger, "", "", $this->cat_Activo->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO concategorias ("
             . "cat_CodAuxiliar, "
             . "cat_Categoria, "
             . "cat_FecIncorp, "
             . "cat_Activo"
             . ") VALUES ("
             . $this->ToSQL($this->cp["cat_CodAuxiliar"]->GetDBValue(), $this->cp["cat_CodAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Categoria"]->GetDBValue(), $this->cp["cat_Categoria"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_FecIncorp"]->GetDBValue(), $this->cp["cat_FecIncorp"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Activo"]->GetDBValue(), $this->cp["cat_Activo"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @334-7C85C435
    function Update()
    {
        $this->cp["cat_CodAuxiliar"] = new clsSQLParameter("ctrlcat_CodAuxiliar", ccsInteger, "", "", $this->cat_CodAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Categoria"] = new clsSQLParameter("ctrlcat_Categoria", ccsInteger, "", "", $this->cat_Categoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_FecIncorp"] = new clsSQLParameter("ctrlcat_FecIncorp", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->cat_FecIncorp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Activo"] = new clsSQLParameter("ctrlcat_Activo", ccsInteger, "", "", $this->cat_Activo->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dscat_Categoria", ccsInteger, "", "", $this->CachedColumns["cat_Categoria"], "", false);
        $wp->AddParameter("2", "dscat_CodAuxiliar", ccsInteger, "", "", $this->CachedColumns["cat_CodAuxiliar"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "cat_CodAuxiliar", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE concategorias SET "
             . "cat_CodAuxiliar=" . $this->ToSQL($this->cp["cat_CodAuxiliar"]->GetDBValue(), $this->cp["cat_CodAuxiliar"]->DataType) . ", "
             . "cat_Categoria=" . $this->ToSQL($this->cp["cat_Categoria"]->GetDBValue(), $this->cp["cat_Categoria"]->DataType) . ", "
             . "cat_FecIncorp=" . $this->ToSQL($this->cp["cat_FecIncorp"]->GetDBValue(), $this->cp["cat_FecIncorp"]->DataType) . ", "
             . "cat_Activo=" . $this->ToSQL($this->cp["cat_Activo"]->GetDBValue(), $this->cp["cat_Activo"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @334-4BC8B79B
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dscat_Categoria", ccsInteger, "", "", $this->CachedColumns["cat_Categoria"], "", false);
        $wp->AddParameter("2", "dscat_CodAuxiliar", ccsInteger, "", "", $this->CachedColumns["cat_CodAuxiliar"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "cat_CodAuxiliar", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM concategorias";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoAdAu_cateDataSource Class @334-FCB6E20C




//Include Page implementation @261-4A3CD99F  // fah-
include_once(RelativePath . "/Co_Files/CoAdAu_varimant.php");
//End Include Page implementation

//Initialize Page @1-03DFB83F
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

$FileName = "CoAdAu_mant.php";
$Redirect = "";
$TemplateFileName = "CoAdAu_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-82AC758D
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$CoAdAu_mant = new clsRecordCoAdAu_mant();
$CoAdAu_cate = new clsEditableGridCoAdAu_cate();
$CoAdAu_varimant = new clsCoAdAu_varimant(""); //fah-
$CoAdAu_varimant->BindEvents();
$CoAdAu_varimant->Initialize();                 //fah-
$CoAdAu_mant->Initialize();
$CoAdAu_cate->Initialize();

// Events
include("./CoAdAu_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-20ED542F
$Cabecera->Operations();
$CoAdAu_mant->Operation();
$CoAdAu_cate->Operation();
$CoAdAu_varimant->Operations(); //fah-
//End Execute Components

//Go to destination page @1-CDA9AAFD
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $CoAdAu_varimant->Class_Terminate(); //fah-
    unset($CoAdAu_varimant);            //fah-
    exit;
}
//End Go to destination page

//Show Page @1-79ECADC7
$Cabecera->Show("Cabecera");
$CoAdAu_mant->Show();
$CoAdAu_cate->Show();
$CoAdAu_varimant->Show("CoAdAu_varimant");
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
//$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
//$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$CoAdAu_varimant->Class_Terminate(); //fah-
unset($CoAdAu_varimant);            //fah-
unset($Tpl);
//End Unload Page


?>
