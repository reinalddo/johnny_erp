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
$gsSesVar="conDetCxC";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
/*        "SELECT
            -- concat(com_tipocomp, '-', com_numcomp) as txt_comprobante,
            -- com_feccontab as com_feccontab,
            det_codcuenta AS det_codcuenta, 
            concat(det_Codcuenta, ' ', cue_Descripcion) as txt_nombrcue,
            det_idauxiliar as det_idauxiliar, 
            concat(per_Apellidos, ' ', per_Nombres) as txt_nombre,
            det_numcheque as det_numdocum,
            ifnull(iab_valorTex, aux_nombre) as txt_Beneficiario,
            -- det_regnumero as det_regnum,
            -- det_secuencia as det_secuencia,
            sum(det_valdebito - det_ValCredito) as txt_valor,
            0 as txt_pago
        FROM concomprobantes join condetalle on det_regnumero = com_regnumero
            JOIN conpersonas on per_codauxiliar = det_idauxiliar
            JOIN concuentas  on cue_codcuenta = det_codcuenta
            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11
        WHERE det_codcuenta = '" .    fGetParam("pCuent", '0') . "'
            AND det_idauxiliar = " . fGetParam("pAuxil", '0') . "
            AND {filter}
        GROUP BY 1,2,3,4,5,6,7 HAVING sum(det_valdebito - det_ValCredito) <> 0
        ORDER BY {sort} {dir}" ;
*/
    $_SESSION[$gsSesVar]=
        "SELECT
            det_numcheque as det_numdocum,
            det_codcuenta AS det_codcuenta, 
            concat(det_Codcuenta, ' ', cue_Descripcion) as txt_nombrcue,
            det_idauxiliar as det_idauxiliar, 
            concat(per_Apellidos, ' ', per_Nombres) as txt_nombre,
            ifnull(iab_valorTex, concat(per_Apellidos, ' ', per_Nombres)) as txt_Beneficiario,
            sum(det_valdebito - det_ValCredito) as txt_valor,
            0 as txt_pago
        FROM concomprobantes join condetalle on det_regnumero = com_regnumero
            JOIN conpersonas on per_codauxiliar = det_idauxiliar
            JOIN concuentas  on cue_codcuenta = det_codcuenta
            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11
        WHERE det_codcuenta = '" .    fGetParam("pCuent", '0') . "'
            AND det_idauxiliar = " . fGetParam("pAuxil", '0') . "
            AND {filter}
        GROUP BY 1,2,3,4,5,6,8 HAVING sum(det_valdebito - det_ValCredito) <> 0
        ORDER BY {sort} {dir}" ;
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrTr_salcxcgriddet.js";
    require "../Ge_Files/GeGeGe_loadgrid.php";

    $_SESSION["carTipoTr"]= "SELECT cla_tipocomp AS cod, 
                cla_descripcion AS txt, 
                cla_tipoEmisor AS cla_Emisor, 
                cla_EmiDefault  as cla_EmiDefault,
                cla_IndCheque AS cla_IndCheque, 
                cla_txtReceptor as cla_TxtReceptor,
                cla_recDefault as cla_RecDefault, 
                cla_CtaOrigen  As cla_CtaOrigen,
                cla_auxorigen as cla_AuxOrigen,
                cla_CtaDestino as cla_CtaDestino,
                cla_AuxDestino	as cla_AuxDestino
        FROM genclasetran JOIN invprocesos i ON i.pro_codproceso = 1001 AND i.cla_tipotransacc = cla_tipocomp
        and pro_Signo = " .fGetParam("pSigno", '1') ."
        WHERE cla_descripcion LIKE '%{query}%'";
        
    $_SESSION["banAuxil"]= "SELECT vau_codauxiliar as cod, vau_descripcion as txt FROM v_auxgeneralcate
        WHERE vau_categoria = 10 AND  vau_descripcion LIKE '%{query}%'";
    } 
else {
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    require('../LibPhp/NoCache.php');
    //$db->debug = $_SESSION['pAdoDbg'] >= 2;
    //header("Content-Type: text/html;  charset=ISO-8859-1");
    header("Content-Type: text/html;  charset=UTF-8");
    
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
            $goGrid->setFieldOpt('det_codcuenta',   array("header"=>"CNTA", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('det_idauxiliar',  array("header"=>"COD", "hidden"=>1, "width"=>8));
            $goGrid->setFieldOpt('txt_nombre',      array("header"=>"NOMB", "hidden"=>1,"width"=>50));
            $goGrid->setFieldOpt('txt_comprobante', array("header"=>"COMPROB.", "hidden"=>0,"width"=>14));
            $goGrid->setFieldOpt('txt_valor',       array("header"=>"VALOR", "hidden"=>0, "width"=>15, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('det_numdocum',   array("header"=>"DOCUM.", "hidden"=>0,"width"=>10, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('det_regnum',      array("header"=>"COMPR.ID", "hidden"=>1,"width"=>200));
            $goGrid->setFieldOpt('txt_pago',        array("header"=>"VALOR A PAGAR", "hidden"=>0,"width"=>20,
                                                         'editor'=>array("xtype"=>"textfield", "renderer"=>'floaPosNeg')));
            $goGrid->setFieldOpt('det_secuencia',   array("header"=>"SECUENC", "hidden"=>1,"width"=>200));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("detGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}