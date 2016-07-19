<?php

/** 
*   Grid para presentar datos de CXC
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="conSalCxc";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]=
    "select ID, tipoTransac, codSustento, devIva, codProv,
    aux_nombre
    ,establecimiento,puntoEmision,secuencial
    , concepto
    from fiscompras c inner join conpersonas p on c.codProv=p.per_CodAuxiliar
        JOIN v_conauxiliar on aux_codigo = per_CodAuxiliar
    where {filter}
    order by  id desc, {sort} {dir}
    LIMIT {start}, {limit}";
    
    //echo $_SESSION[$gsSesVar];
    
    // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoRtTr_Anexocons";
    
    require "../Ge_Files/GeGeGe_loadgrid.php";
    }
else {    
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    require('../LibPhp/NoCache.php');
    //$db->debug = $_SESSION['pAdoDbg'] >= 2;
    header("Content-Type: text/html;  charset=ISO-8859-1");
    /*
     *	Derivated class to implement a variation on metadata (adding field options) before output
     *
     */
    
    class clsQueryGrid extends clsQueryToJson {
        function init(){
            $this->metaDataFlag = true;
        }
        function beforeGetData(){
            global $goGrid;
            $slRendFunc="function(v, params, data){
                    return ((v === 0 || v > 1) ? '(' + v +' Tarjas)' : '(1 TarjaS)');
                }";
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 0);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('ID', array("header"=>"ID", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('tipoTransac', array("header"=>"Tipo Trans.", "hidden"=>0, "width"=>10, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('codSustento', array("header"=>"Sustento", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('devIva', array("header"=>"Dev IVA", "hidden"=>0,"width"=>8));
            $goGrid->setFieldOpt('codProv', array("header"=>"Cod Prov", "Cod Prov."=>0, "width"=>10, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('aux_nombre', array("header"=>"Proveedor", "hidden"=>0,"width"=>300));
            //$goGrid->setFieldOpt('comprobante', array("header"=>"Comprobante", "hidden"=>0,"width"=>20));
            $goGrid->setFieldOpt('establecimiento', array("header"=>"Estab.", "hidden"=>0,"width"=>5));
            $goGrid->setFieldOpt('puntoEmision', array("header"=>"Pto.Emi.", "hidden"=>0,"width"=>5));
            $goGrid->setFieldOpt('secuencial', array("header"=>"Secuencial", "hidden"=>0,"width"=>5));
            $goGrid->setFieldOpt('concepto', array("header"=>"Concepto", "hidden"=>0,"width"=>200));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}