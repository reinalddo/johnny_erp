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
$gsSesVar="conSalCtaDet";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]="
    select com_regnumero regNum, d.det_secuencia secuencia, per_Apellidos banco, det_NumCheque cheque, 
                    com_FecContab fecha, det_ValCredito valor, com_Receptor beneficiario, /*com_Concepto concepto,*/ 
                    com_TipoComp tipoComp, com_NumComp numComp, /*origen ,*/ observacion, fecRegistro, usuario
        from concheques_cab cab
        inner join concheques_det det on cab.idbatch=det.idbatch
        join concomprobantes c on c.com_regnumero=det.com_regnum
                join condetalle d on c.com_regnumero=d.det_regnumero
                join conpersonas p on det_idauxiliar=per_CodAuxiliar
                join concategorias cat on cat_CodAuxiliar=per_CodAuxiliar and cat_Categoria=10
        where tipo=1
	and det_NumCheque  like '".fGetParam("pConsNumCheque", "")."%' and det_idauxiliar=".fGetParam("pConsAux", 0)."
    order by fecRegistro, cab.idbatch
    LIMIT {start}, {limit}";
    /*"SELECT com_RegNumero AS 'REG', com_TipoComp, com_NumComp, det_NumCheque, com_FecContab,
    com_Receptor AS 'beneficiario', det_Glosa AS 'com_Concepto', det_ValDebito,  det_ValCredito
    FROM concomprobantes 
    JOIN condetalle on (det_RegNumero = com_RegNumero) 
    LEFT JOIN conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar) 
    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar) 
    LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) 
    where det_CodCuenta='".fGetParam("pConsCta", 0)."' AND det_IDAuxiliar=".fGetParam("pConsAux", 0)."
        AND det_NumCheque like '".fGetParam("pConsNumCheque", "")."%'
        and {filter}
    ORDER BY det_CodCuenta, det_IDauxiliar, com_FecContab desc, com_TipoComp, com_NumComp  
            
    LIMIT {start}, {limit}";*/
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
            $goGrid->setFieldOpt('regNum', array("header"=>"Reg.Num.", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('secuencia', array("header"=>"secuencia", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('banco', array("header"=>"BANCO", "hidden"=>0, "width"=>17));
            $goGrid->setFieldOpt('cheque', array("header"=>"CHEQUE", "hidden"=>0, "width"=>10));//, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('fecha', array("header"=>"FECHA", "hidden"=>0,"width"=>15));
            $goGrid->setFieldOpt('valor', array("header"=>"VALOR", "hidden"=>0, "width"=>12, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('beneficiario', array("header"=>"BENEFICIARIO", "hidden"=>0, "width"=>20));
            //$goGrid->setFieldOpt('concepto', array("header"=>"CONCEPTO", "hidden"=>0, "width"=>20));
            $goGrid->setFieldOpt('tipoComp', array("header"=>"TIPO COMP.", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('numComp', array("header"=>"NUM.COMP.", "hidden"=>0,"width"=>10));
            //$goGrid->setFieldOpt('origen', array("header"=>"ORIGEN", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('observacion', array("header"=>"OBSERV", "hidden"=>0,"width"=>20));
            $goGrid->setFieldOpt('fecRegistro', array("header"=>"FEC.PROG.", "hidden"=>0,"width"=>20));
            $goGrid->setFieldOpt('usuario', array("header"=>"USUARIO", "hidden"=>0,"width"=>20));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}