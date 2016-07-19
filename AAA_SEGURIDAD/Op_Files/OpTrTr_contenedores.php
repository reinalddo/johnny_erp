<?php
/** 
*   Grid para presentar datos de contenedores
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
$gsSesVar="opCntList";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]=
    " SELECT * FROM (SELECT cnt_ID, cnt_Serial,
        concat(nav.per_Apellidos, ' ', ifNUll(nav.per_Nombres,'')) as txt_Naviera,
        emb_SemInicio,
        concat(buq_Descripcion, '-', emb_numviaje, ', S ', IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) as txt_Embarque,
        cnt_Usuario, 	cnt_FechaReg,	concat(con.per_Apellidos, ' ', ifNUll(con.per_Nombres,'')) as txt_Consignatario,
        cnt_Observaciones, 
        pai_Descripcion  as txt_Destino,
        cnt_CantDeclarada,
        vco_CantNeta,
        cnt_CantDeclarada - vco_CantNeta as tmp_Diferencia,
        est.par_Descripcion  as txt_Estado,
        cnt_SelloNav,
        cnt_SelloCia,
        cnt_FecInicio,    cnt_HorInicio,    cnt_FecFin,     cnt_HorFin,
        concat(chq.per_Apellidos, ' ', ifNUll(chq.per_Nombres,'')) as txt_Chequeador,
        date_format(cnt_Conexion, '%Y-%m-%d %H:%i') cnt_Conexion,
        date_format(cnt_Enchufe, '%Y-%m-%d %H:%i') cnt_Enchufe,
        date_format(cnt_CtrlTemp, '%Y-%m-%d %H:%i') cnt_CtrlTemp,
        date_format(cnt_CtrlEmbarque, '%Y-%m-%d %H:%i') cnt_CtrlEmbarque,
        cnt_Chequeador, 	          cnt_Estado, 	cnt_Temperatura,
        cnt_Embarque,
        cnt_Naviera,
        cnt_Destino,  cnt_Consignatario
    FROM	opecontenedores
        LEFT JOIN conpersonas con on con.per_codauxiliar = cnt_consignatario
        LEFT JOIN conpersonas chq on chq.per_codauxiliar = cnt_chequeador
        LEFT JOIN conpersonas nav on nav.per_codauxiliar = cnt_naviera
        LEFT JOIN liqembarques    on emb_refOperativa = cnt_embarque
        LEFT JOIN liqbuques       on buq_codbuque = emb_codvapor
        LEFT JOIN genpaises       on pai_codPais = cnt_destino
        LEFT JOIN v_opecantconte  on vco_refoperativa = cnt_embarque and vco_contenedor = cnt_serial
      LEFT JOIN genparametros est on est.par_clave = 'OGESTC' AND est.par_secuencia = cnt_Estado 
      WHERE " .  (fGetParam("pSem", false) ? fGetParam("pSem", false) : " 801" ) 
              . " Between emb_seminicio and emb_semtermino
    ) tmp_00
    WHERE {filter}   ORDER BY {sort} {dir}" 
    ;  // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
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
                    return ((v === 0 || v > 1) ? '(' + v +' Contenedores)' : '(1 Contenedor)');
                }";
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('cnt_ID', array("header"=>"ID", "hidden"=>0));
            $goGrid->setFieldOpt('cnt_Serial', array("header"=>"NUM SERIE", "hidden"=>0));
            $goGrid->setFieldOpt('txt_Embarque', array("header"=>"EMBARQUE", "hidden"=>0));
            $goGrid->setFieldOpt('txt_Naviera', array("header"=>"NAVIERA", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('emb_SemInicio', array("header"=>"SEMANA", "hidden"=>0));        
            $goGrid->setFieldOpt('txt_Destino', array("header"=>"DESTINO", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('txt_Consignatario', array("header"=>"CONSIGNATARIO", "hidden"=>0));
            $goGrid->setFieldOpt('cnt_CantDeclarada', array("header"=>"CANT. DECLARADA", "hidden"=>0,
                                "summaryType"=>"sum", "hideable"=>"false", "type"=>"int"));
            $goGrid->setFieldOpt('vco_CantNeta', array("header"=>"CANT.TARJAS", "hidden"=>0,
                                "summaryType"=>"sum", "hideable"=>"false", "type"=>"int"));
            $goGrid->setFieldOpt('tmp_Diferencia', array("header"=>"DIFERENCIA", "hidden"=>0,
                                "summaryType"=>"sum", "hideable"=>"false", "type"=>"int"));
            $goGrid->setFieldOpt('cnt_SelloNav', array("header"=>"SELLO NAV.", "hidden"=>0));
            $goGrid->setFieldOpt('cnt_SelloCia', array("header"=>"SELLO CIA", "hidden"=>0));
            $goGrid->setFieldOpt('cnt_FecInicio', array("header"=>"FEC INICIO", "hidden"=>0));
            $goGrid->setFieldOpt('txt_Estado', array("header"=>"ESTADO", "hidden"=>0, "width"=>10));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}