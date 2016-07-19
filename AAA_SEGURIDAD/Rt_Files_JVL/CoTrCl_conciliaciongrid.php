<?php
/** 
*   Grid para presentar detalles de una CXC especifica
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @param  pCuent  String      Codigo de cuenta que se desea aplicar
*   @param  pAuxil  String      Codigo de auxiliar que se desea aplicar   
*   @param  init    boolean     bandera que define si es la ejecucion incial (true) que define variables de sesion
*                               o una llamada posterior que retorna los datos del grid con su metadata
*   @param  page    boolean     Bandera para generar (true) el codgio para una pagina html ó (false) solamente un componente Ext
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="conCabConcil";
if (fGetParam("init", false) != false) {
    /* Query principal */
    /* fGetParam("pAuxil", '0') */
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]= "SELECT con_CodCuenta,con_CodAuxiliar,concat('../Co_Files/CoTrCl_mant.php?con_CodCuenta=', con_CodCuenta, 
            '&con_CodAuxiliar=', con_CodAuxiliar, '&con_IdRegistro=', con_IdRegistro)as txt_Url, 
            con_FecCorte, con_DebIncluidos, con_CreIncluidos, 
            concat(ifnull(con_Ususario, ''), '-', con_FecRegistro) as txt_Digitado 
            from conconciliacion 
            where con_CodCuenta = '".fGetParam("pCuenta", '0')."' and con_CodAuxiliar = ".fGetParam("pAuxil", '0')."
            group by con_FecCorte
            ORDER BY {sort} {order}
             LIMIT {start}, {limit}" ;
    //echo( $_SESSION[$gsSesVar]);
    
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrCl_conciliaciongrid.js";
    require "../Ge_Files/GeGeGe_loadgrid.php";

    
    } 
else {
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    require('../LibPhp/NoCache.php');
    //$db->debug = $_SESSION['pAdoDbg'] >= 2;
    //echo( $_SESSION[$gsSesVar]);
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
                    return ((v === 0 || v > 1) ? '(' + v +' Contenedores)' : '(1 Contenedor)');
                }";
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('con_FecCorte',   array("header"=>"Fecha Corte", "hidden"=>0, "width"=>20));
            $goGrid->setFieldOpt('con_DebIncluidos',  array("header"=>"DB Marcados", "hidden"=>0, "width"=>20, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('con_CreIncluidos',      array("header"=>"CR Marcados", "hidden"=>0,"width"=>20, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('txt_Digitado', array("header"=>"Digitado", "hidden"=>0,"width"=>30));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("detGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}