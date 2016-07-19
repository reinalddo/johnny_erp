<?php
/** 
*   Grid para presentar movimientos de conciliacion bancaria
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
$gsSesVar="conDetConcil";
if (fGetParam("init", false) != false) {
    /* Query principal */
    /* fGetParam("pAuxil", '0') */
    $_SESSION[$gsSesVar]=NULL;
    
    // Para la paginacion
    // esl	19/Julio/2010	Agregar pagi
    
    $_SESSION[$gsSesVar . "_count"]="select count(*) as totalRecs            
		from condetalle JOIN concomprobantes ON com_regnumero = det_regnumero
		/*SELECT QUE TRAE LA LISTA DE CHEQUES QUE TIENEN ESTADO N/A O ANULADO*/
		LEFT JOIN ( SELECT det.com_regNum AS che_RegNum, cab.operacion AS che_operacion FROM concheques_cab cab
			    JOIN concheques_det det ON cab.idbatch=det.idbatch 
			    WHERE cab.tipo = 1 AND cab.operacion IN (4,0)
			    GROUP BY det.com_regNum
			  ) AS cheques ON che_RegNum = det_regnumero
                left join v_conauxiliar on aux_codigo = com_codreceptor
		where det_codcuenta = '".fGetParam("pCuenta", '0')."' and  
                det_idauxiliar = ".fGetParam("pAuxil", '0')." and  
                (   	com_feccontab <= '".fGetParam("fecCorte", '2020-12-31')."' OR 
			com_fectrans between ifnull(DATE_ADD((select con_FecCorte from conconciliacion
			where con_CodCuenta='".fGetParam("pCuenta", '0')."' and con_CodAuxiliar=".fGetParam("pAuxil", '0')."
			and con_FecCorte < '".fGetParam("fecCorte", '2020-12-31')."'
			order by con_FecCorte desc limit 0,1), INTERVAL 1 DAY) ,'2009-01-01')
                        and '".fGetParam("fecCorte", '2020-12-31')."' 
                ) AND ((det_feclibros = '".fGetParam("fecCorte", '2020-12-31')."'   and  det_EstLibros=1)
                        OR (det_feclibros > '".fGetParam("fecCorte", '2020-12-31')."'  and  det_EstLibros=0)
                        OR det_feclibros  = '0000-00-00'
                        OR det_feclibros <= '2001-01-01')
		/*LA columna che_operacion solo tiene valor para aquellos cheques que estan en el select del join operacion 4 Anulados y operacion 0 N/A 
		si es nula entonces no esta en el listado de cheques q no deben aparecer*/
		AND che_operacion IS NULL
        ";
    
    // esl	21/07/2010	Agregar parametro pIdRegistro al query
    
    $_SESSION[$gsSesVar]=
        "select com_TipoComp, com_NumComp,
            com_FecContab, det_ValDebito, det_ValCredito
            ,aux_Nombre, com_Concepto, det_NumCheque,  det_EstLibros, det_FecLibros,
            com_RegNumero, '".fGetParam("fecCorte", '2020-12-31')."' fecCorte
	    ,'".fGetParam("pIdRegistro", '0')."' IdRegistro
	    ,det_codcuenta,det_idauxiliar
        from condetalle JOIN concomprobantes ON com_regnumero = det_regnumero 
        left join v_conauxiliar on aux_codigo = com_codreceptor
	/*SELECT QUE TRAE LA LISTA DE CHEQUES QUE TIENEN ESTADO N/A O ANULADO*/
	LEFT JOIN ( SELECT det.com_regNum AS che_RegNum, cab.operacion AS che_operacion FROM concheques_cab cab
                    JOIN concheques_det det ON cab.idbatch=det.idbatch 
                    WHERE cab.tipo = 1 AND cab.operacion IN (4,0)
                    GROUP BY det.com_regNum
                  ) AS cheques ON che_RegNum = det_regnumero
        where det_codcuenta = '".fGetParam("pCuenta", '0')."' and  
        det_idauxiliar = ".fGetParam("pAuxil", '0')." and  
                (	com_feccontab <= '".fGetParam("fecCorte", '2020-12-31')."' OR 
			com_fectrans between ifnull(DATE_ADD((select con_FecCorte from conconciliacion
			where con_CodCuenta='".fGetParam("pCuenta", '0')."' and con_CodAuxiliar=".fGetParam("pAuxil", '0')."
			and con_FecCorte < '".fGetParam("fecCorte", '2020-12-31')."'
			order by con_FecCorte desc limit 0,1), INTERVAL 1 DAY) ,'2009-01-01')
                        and '".fGetParam("fecCorte", '2020-12-31')."' 
                ) AND ((det_feclibros = '".fGetParam("fecCorte", '2020-12-31')."'   and  det_EstLibros=1)
                        OR (det_feclibros > '".fGetParam("fecCorte", '2020-12-31')."'  and  det_EstLibros=0)
                        OR det_feclibros  = '0000-00-00'
                        OR det_feclibros <= '2001-01-01')
		/*LA columna che_operacion solo tiene valor para aquellos cheques que estan en el select del join operacion 4 Anulados y operacion 0 N/A 
		si es nula entonces no esta en el listado de cheques q no deben aparecer*/
		AND che_operacion IS NULL
        AND {filter}
        ORDER BY  {sort} {dir}
        LIMIT {start}, {limit}        ";
	
	
	
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrCl_conciliaciongridDet";
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
	    $this->countFlag = 2;
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
            $this->totalProperty="recordCount";
            $goGrid->colWidthFlag= true;
	    
	    
	    $goGrid->setFieldOpt('IdRegistro', array("header"=>"IdConciliacion", "hidden"=>1, "width"=>5));
	    $goGrid->setFieldOpt('det_codcuenta', array("header"=>"Cuenta", "hidden"=>1, "width"=>5));
	    $goGrid->setFieldOpt('det_idauxiliar', array("header"=>"IdAuxiliar", "hidden"=>1, "width"=>5));
	    
	    $goGrid->setFieldOpt('fecCorte', array("header"=>"fecCorte", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('fecCorte', array("header"=>"fecCorte", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('com_RegNumero', array("header"=>"RegNumero", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('com_TipoComp', array("header"=>"TIPO", "hidden"=>0, "width"=>5));
            $goGrid->setFieldOpt('com_NumComp', array("header"=>"NUM.COMP.", "hidden"=>0, "width"=>10));//, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('com_FecContab', array("header"=>"FECHA", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('det_ValDebito', array("header"=>"DEBITO", "hidden"=>0, "width"=>10, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('det_ValCredito', array("header"=>"CREDITO", "hidden"=>0, "width"=>10, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('aux_Nombre', array("header"=>"BENEFICIARIO", "hidden"=>0, "width"=>20));
            $goGrid->setFieldOpt('com_Concepto', array("header"=>"CONCEPTO", "hidden"=>0, "width"=>20));
            $goGrid->setFieldOpt('det_NumCheque', array("header"=>"CHEQUE", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('det_EstLibros', array("header"=>"EST.LIBROS", "hidden"=>0, "width"=>10, "renderer"=>"check", "css"=>"background-color:#F2F5A9;"));
            $goGrid->setFieldOpt('det_FecLibros', array("header"=>"FEC.LIBROS", "hidden"=>0, "width"=>15, "css"=>"background-color:#F3F781;"/*,'editor'=>array("xtype"=>"datefield","renderer" => "formatDate")*/));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("concilGrid"); //cntGrid
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();  
}