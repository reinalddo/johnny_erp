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
    "SELECT  det_codcuenta AS det_codcuenta, 
        concat(det_Codcuenta, ' ', cue_Descripcion) as nombrcue,
        det_idauxiliar as det_idauxiliar,
        aux_nombre as aux_nombre, 
        sum(det_valdebito - det_ValCredito) as saldo
        FROM concomprobantes join condetalle on det_regnumero = com_regnumero 
            JOIN v_conauxiliar on aux_codigo = det_idauxiliar
            JOIN concuentas  on cue_codcuenta = det_codcuenta
            JOIN genparametros on par_clave = 'CUCX" . fGetParam("pTipo", 'C') . "'
        WHERE det_codcuenta like concat(par_valor1 , '%') AND {filter}   
        GROUP BY 1, 2 ,3 
        ORDER BY {sort} {dir}
        LIMIT {start}, {limit}" 
    ;
    // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "OpTrTr_contenedores";
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
                    return ((v === 0 || v > 1) ? '(' + v +' Personas)' : '(1 PersonaS)');
                }";
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('det_cuenta', array("header"=>"CUENTA", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('det_idauxiliar', array("header"=>"CODIGO", "hidden"=>0, "width"=>10, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('aux_nombre', array("header"=>"NOMBRE", "hidden"=>0,"width"=>200));
            $goGrid->setFieldOpt('saldo', array("header"=>"SALDO", "hidden"=>0, "width"=>15, "renderer"=>"floatPosNeg"));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}