<?php
/**
*   Grid para presentar ordenes de compra de un rango de fechas
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @param  pFecIni  date      fecha de inicio
*   @param  pFecFin  date      fecha de fin
*   @rev  esl	10/02/2011	Parametrizar base para inventario.  
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";

// Para la consulta de la base de inventario
include_once("General.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");


// ***********************************************************
    //@rev	esl	10/02/2011	Parametrizar base para inventario.
    $baseInv = "09_inventario.";    
    $sSql = "SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'IDATO' AND par_Secuencia = 1";	    
    $rs = $db->execute($sSql);
    $r = $rs->fetchRow();
    
    $baseInv = $r['par_Valor1'];
// ***********************************************************




error_reporting(E_ALL);
$gsSesVar="vincularDoc";
if (fGetParam("init", false) != false) {
    /* Query principal */
    /* fGetParam("pAuxil", '0') */
    
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]= "SELECT com_TipoComp, com_Numcomp,det_RegNUmero,det_Secuencia,
                CONCAT(LEFT(com_TipoComp,3), \" \", com_Numcomp) AS com_Comproba,
                det_CodItem AS Cod_Item,act_Descripcion AS Item,ROUND(det_CantEquivale) AS Pedido,
                IFNULL(sal_Pendiente,0) AS Recibido,IFNULL(ROUND((det_CantEquivale+sal_Pendiente)),ROUND(det_CantEquivale)) AS Pendiente,
                com_FecTrans, com_FecContab, com_CodReceptor, com_Receptor, com_Concepto,
                CONCAT_WS(\" - \", com_Usuario, DATE_FORMAT(com_FecDigita, '%d/%b/%Y')) AS com_Usuario,
                com_EstProceso, com_RefOperat
                FROM concomprobantes
                        JOIN invdetalle ON com_TipoComp='OC' AND com_RegNumero=det_RegNUmero AND com_FecContab between '".fGetParam("pFecIni", 0)."' and
                                                '".fGetParam("pFecFin", 0)."'
                        LEFT JOIN ".$baseInv.".v_invsaldoocib ON det_RegNUmero=sal_RegNumero AND det_CodITem=sal_CodItem and sal_Codempresa='". $_SESSION['g_dbase'] ."'
                        JOIN conactivos ON det_CodItem=act_CodAuxiliar
            WHERE IFNULL(ROUND((det_CantEquivale+sal_Pendiente)),
            ROUND(det_CantEquivale)) <> 0
            AND {filter}
            GROUP BY 1,2,3,4
            ORDER BY com_TipoComp,com_Numcomp desc
            LIMIT {start}{limit} " ;

    //echo( $_SESSION[$gsSesVar]);
    //die();

    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrTr_gridvinculardoc.js";
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
            $goGrid->setFieldOpt('com_TipoComp',   array("header"=>"Tipo Comp", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('com_Numcomp',  array("header"=>"Num Comp", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('det_RegNUmero',  array("header"=>"Reg Num", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('det_Secuencia',  array("header"=>"Secuencia", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('com_Comproba',   array("header"=>"Comprobante", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('Cod_Item',   array("header"=>"Cod. Item", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('Item',   array("header"=>"Item", "hidden"=>0, "width"=>30));
            $goGrid->setFieldOpt('Pedido',   array("header"=>"Pedido", "hidden"=>0, "width"=>15));
            $goGrid->setFieldOpt('Recibido',   array("header"=>"Recibido", "hidden"=>0, "width"=>15));
            $goGrid->setFieldOpt('Pendiente',   array("header"=>"Pendiente", "hidden"=>0, "width"=>15));
            $goGrid->setFieldOpt('com_FecTrans',   array("header"=>"Fecha Trans.", "hidden"=>0, "width"=>15));
            $goGrid->setFieldOpt('com_FecContab',   array("header"=>"Fecha Contab.", "hidden"=>0, "width"=>15));
            $goGrid->setFieldOpt('com_CodReceptor',   array("header"=>"Cod Proveedor", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('com_Receptor',   array("header"=>"Proveedor", "hidden"=>0, "width"=>50));
            $goGrid->setFieldOpt('com_Concepto', array("header"=>"Concepto", "hidden"=>0,"width"=>100));
            $goGrid->setFieldOpt('com_Usuario', array("header"=>"Digitado", "hidden"=>0,"width"=>20));
            //$goGrid->setFieldOpt('com_EstProceso',      array("header"=>"Estado", "hidden"=>0,"width"=>10, "renderer"=>"floatPosNeg"));
            //$goGrid->setFieldOpt('com_RefOperat',      array("header"=>"Ref.", "hidden"=>0,"width"=>10, "renderer"=>"floatPosNeg"));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("detGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}