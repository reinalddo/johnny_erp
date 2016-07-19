<?php
/** 
*   Grid para presentar datos de cuentas por cobrar
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @author    Gina Franco
*   @date      27/Nov/09    
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
include_once("General.inc.php");
define("RelativePath", "..");
error_reporting(E_ALL);
include_once("adodb.inc.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);


$gsSesVar="conCxc";
if (fGetParam("init", false) != false) {
    
    /*$sql = '
    CREATE TEMPORARY TABLE saldos_temp
    (
      det_codcuenta varchar(15) ,
      det_idauxiliar int(10),
      det_numcheque int(10), 
      saldo decimal(12,2)     
      ) ;';*/
    $sql = "delete from saldos_temp where usuario='".$_SESSION['g_user']."'";
    $db->Execute($sql);

    $sql2 = "
    insert saldos_temp
    select det_codcuenta, det_idauxiliar, det_numcheque, sum(det_valdebito - det_ValCredito) as saldo
            , '".$_SESSION['g_user']."'
    FROM concomprobantes join condetalle on det_regnumero = com_regnumero
            where com_fecContab < '" . fGetParam("fecFin", '2020-12-31') . "' -- det_numcheque=13819 and det_codcuenta=10039902
    GROUP BY 1,2,3;";
    $db->Execute($sql2);
    
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    
    $_SESSION[$gsSesVar]= "
            SELECT  com_TipoComp, com_NumComp,
            com_FecContab, com_fecVencim
            ,datediff(com_fecContab, com_fecVencim) plazo
            ,case when st.saldo = 0 then 'Cancelado' else 'Pendiente' end status
	-- det_codcuenta AS det_codcuenta, 
        -- concat(det_Codcuenta, ' ', cue_Descripcion) as nombrcue,
        -- det_idauxiliar as det_idauxiliar,
        -- aux_nombre as aux_nombre,
        -- ,sum(det_valdebito - det_ValCredito) as saldo
        , det_ValCredito valor
        , st.saldo as saldo
        , d.det_NumCheque
        ,ifnull(iab_valorTex, aux_nombre) as txt_Beneficiario
	,det_Glosa
	,case when st.saldo <> 0 then datediff(CURRENT_TIMESTAMP(), com_fecContab) else 0 end morosidad
        
        
        FROM concomprobantes join condetalle d on det_regnumero = com_regnumero 
            JOIN v_conauxiliar on aux_codigo = det_idauxiliar
            JOIN concuentas  on cue_codcuenta = det_codcuenta
            JOIN genparametros on par_clave = 'CUCXC'
            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11
            left join saldos_temp st on st.det_codcuenta=d.det_codcuenta and 
			st.det_idauxiliar=d.det_idauxiliar and st.det_numcheque = d.det_numcheque
                        and usuario='".$_SESSION['g_user']."'
        WHERE d.det_codcuenta like concat(par_valor1 , '%')
                and com_FecContab between '".fGetParam("fecIni", '2020-12-31')."' and '".fGetParam("fecFin", '2020-12-31')."'
                AND {filter}   
		-- and com_tipocomp = 'FA'
        -- GROUP BY 1,2,3,4 
        ORDER BY txt_Beneficiario -- d.det_codcuenta";
    
 /*   "
        select com_TipoComp, com_NumComp, com_FecContab, det_ValDebito, det_ValCredito,
            com_Concepto, det_NumCheque,   concat(com_usuario,', ',com_fecDigita) txt_usuario
        from concomprobantes c inner join condetalle d on c.com_RegNumero=d.det_RegNumero
        where det_NumCheque <> 0 and det_IDAuxiliar=".fGetParam("pAuxil", '0')." 
	and com_FecContab between '".fGetParam("fecIni", '2020-12-31')."' and '".fGetParam("fecFin", '2020-12-31')."'
         AND {filter}
	order by {sort} {dir}
        LIMIT {start}, {limit}";*/
    
    
    //echo $_SESSION[$gsSesVar];
    // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrTr_detCuentasCobrar";
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
            $goGrid->setFieldOpt('com_TipoComp', array("header"=>"TIPO", "hidden"=>0, "width"=>5));
            $goGrid->setFieldOpt('com_NumComp', array("header"=>"NUM.COMP.", "hidden"=>0, "width"=>10));//, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('com_FecContab', array("header"=>"FECHA", "hidden"=>0,"width"=>15));
            $goGrid->setFieldOpt('com_fecVencim', array("header"=>"FECHA VCTO.", "hidden"=>0,"width"=>15));
            $goGrid->setFieldOpt('plazo', array("header"=>"PLAZO", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('status', array("header"=>"STATUS", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('valor', array("header"=>"VALOR", "hidden"=>0, "width"=>15, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('saldo', array("header"=>"SALDO", "hidden"=>0, "width"=>15, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('det_NumCheque', array("header"=>"DOC.", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('txt_Beneficiario', array("header"=>"BENEFICIARIO", "hidden"=>0, "width"=>100));
            $goGrid->setFieldOpt('det_Glosa', array("header"=>"CONCEPTO", "hidden"=>0,"width"=>100));
            $goGrid->setFieldOpt('morosidad', array("header"=>"MOROSIDAD", "hidden"=>0,"width"=>10));
            //$goGrid->setFieldOpt('txt_usuario', array("header"=>"USUARIO", "hidden"=>0,"width"=>50));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}