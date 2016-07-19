<?php
/**
*   Grid para presentar detalles de una CXC especifica
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @param  pCuent  String      Codigo de cuenta que se desea aplicar
*   @param  pAuxil  String      Codigo de auxiliar que se desea aplicar
*   @param  init    boolean     bandera que define si es la ejecucion incial (true) que define variables de sesion
*                               o una llamada posterior que retorna los datos del grid con su metadata
*   @param  page    boolean     Bandera para generar (true) el codgio para una pagina html � (false) solamente un componente Ext
*   @rev	esl	20/12/2010	Parametrizar base para inventario.
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";

// Para la consulta de la base de inventario, si viene en blanco es porque no es coorporativo.

include_once("General.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");


error_reporting(E_ALL);
$gsSesVar="conDetCxC";
$baseInv = "09_inventario.";

//@rev	esl	20/12/2010	Parametrizar base para inventario.
$sql = "SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'IDATO' AND par_Secuencia = 1";
$rs = $db->execute($sql);
$r = $rs->fetchRow(); 
$baseInv = $r['par_Valor1'];
// print($baseInv);


/* $baseInv = " ";  */
if (fGetParam("init", false) != false) {
    /* Query principal */
    /*fGetParam("pAuxil", '0')*/
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]=
        "select	'".fGetParam("pTComp", '0')."' TComp, ".fGetParam("pNComp", '0')." NComp, 
			'".fGetParam("pRegNum", '0')."' RegNum, ".fGetParam("pSecuencia", '0')." Secuencia
            ,com_tipocomp, com_numcomp,0 seleccionar , concat(com_tipocomp, '-', com_numcomp) as txt_comprobante,
			det_RegNUmero as RegNum1,det_Secuencia as Secuencia1,
              IFNULL(CONCAT(doc_Tipo, '-', doc_Establecimiento,doc_PtoEmision,doc_Secuencial),'GR-XXX') AS txt_guia,
	    det_CodItem AS Cod_Item,c.com_Concepto as Concepto,ROUND(det_CantEquivale,2) AS Cantidad,IFNULL(sal_Pendiente,0) AS Recibido,
	    IFNULL(ROUND((det_CantEquivale+sal_Pendiente),2),ROUND(det_CantEquivale,2)) AS Pendiente,".fGetParam("pCantidad", '0')." CantidadFalta,
	    act.act_Descripcion AS Item, det_Secuencia,
            com_feccontab, concat(p.per_Apellidos,' ', p.per_Nombres)  bodega,
            CONCAT(r.per_Apellidos,' ',r. per_Nombres) as Proveedor
            from ".$baseInv.".concomprobantes c
            INNER JOIN ".$baseInv.".invdetalle d ON c.com_RegNumero=d.det_RegNUmero
	    LEFT JOIN ".$baseInv.".v_invsaldoib ON det_RegNUmero=sal_RegNumero AND det_CodITem=sal_CodItem and sal_Empresa='". $_SESSION['g_dbase'] ."'
			LEFT JOIN ".$baseInv."condocasoc cd ON d.det_RegNUmero=cd.doc_RegNumero
            inner join ".$baseInv.".conpersonas p on c.com_emisor=per_CodAuxiliar
            INNER JOIN ".$baseInv.".conpersonas r ON c.com_CodReceptor=r.per_CodAuxiliar
            INNER JOIN ".$baseInv.".conactivos act ON d.det_CodItem=act.act_CodAuxiliar
            where com_tipoComp='IB' and com_CodReceptor=".fGetParam("proveedor", '0')." and det_CodItem=".fGetParam("pCodItem", '0')."
            AND IFNULL(ROUND((det_CantEquivale+sal_Pendiente),2), ROUND(det_CantEquivale,2)) <> 0 AND ROUND(det_CantEquivale,2)<=".fGetParam("pCantidad", '0')."
	    AND {filter}
		GROUP BY 5,6,9,10
        ORDER BY {sort} {dir}" ;
		//print_r($_SESSION);
 //echo($_SESSION[$gsSesVar]);
//die();
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrTr_gridvinculardet.js";
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
                    return ((v === 0 || v > 1) ? '(' + v +' Contenedores)' : '(1 Contenedor)');
                }";
            $alFieldsOpt = array();
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('TComp',   array("header"=>"TComp1", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('NComp',  array("header"=>"NComp1", "hidden"=>1, "width"=>8));
			$goGrid->setFieldOpt('RegNum',  array("header"=>"RegNum", "hidden"=>1, "width"=>8));
			$goGrid->setFieldOpt('Secuencia',  array("header"=>"Secuencia", "hidden"=>1, "width"=>8));
			$goGrid->setFieldOpt('RegNum1',  array("header"=>"RegNum1", "hidden"=>1, "width"=>8));
			$goGrid->setFieldOpt('Secuencia1',  array("header"=>"Secuencia1", "hidden"=>1, "width"=>8));
            $goGrid->setFieldOpt('com_tipocomp',   array("header"=>"TComp", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('com_numcomp',  array("header"=>"NComp", "hidden"=>1, "width"=>8));
            $goGrid->setFieldOpt('seleccionar',      array("header"=>"Sel.", "hidden"=>0,"width"=>5, "renderer"=>"check"));
            $goGrid->setFieldOpt('txt_comprobante',      array("header"=>"Comprobante", "hidden"=>0,"width"=>20));
            $goGrid->setFieldOpt('Cod_Item',   array("header"=>"Cod. Item", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('Concepto',   array("header"=>"Concepto", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('Recibido',   array("header"=>"Recibido", "hidden"=>1, "width"=>15));
            $goGrid->setFieldOpt('Pendiente',   array("header"=>"Pendiente", "hidden"=>1, "width"=>15));
            $goGrid->setFieldOpt('det_Secuencia',   array("header"=>"Secuencia", "hidden"=>1, "width"=>30));

            $goGrid->setFieldOpt('Cantidad',   array("header"=>"Cantidad", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('CantidadFalta',   array("header"=>"CantidadFalta", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('Item',   array("header"=>"Item", "hidden"=>0, "width"=>30));
            $goGrid->setFieldOpt('com_feccontab', array("header"=>"Fecha Cont.", "hidden"=>0,"width"=>14));
            $goGrid->setFieldOpt('bodega', array("header"=>"Bodega", "hidden"=>0,"width"=>23));
            $goGrid->setFieldOpt('Proveedor', array("header"=>"Proveedor", "hidden"=>0,"width"=>23));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("detGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}