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
    if (1 == fGetParam("env", 0)){
        $persona = "origen";
        $persona2 = "destino";
        $filtro = "";
    }else{
        $persona = "destino";
        $persona2 = "origen";
        $filtro = "and confirmado=1";
    }
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]= "select com_regnumero regNum, d.det_secuencia secuencia,  
                    cab.fecha fecha, ".$persona2." persona, cab.observacion, confirmado,
                    per_Apellidos banco, det_NumCheque cheque,
                    det_ValCredito valor, com_Receptor beneficiario, /*com_Concepto concepto, */
                    com_TipoComp tipoComp, com_NumComp numComp
        from concheques_cab cab
        inner join concheques_det det on cab.idbatch=det.idbatch
        join concomprobantes c on c.com_regnumero=det.com_regnum
                join condetalle d on c.com_regnumero=d.det_regnumero
                join conpersonas p on det_idauxiliar=per_CodAuxiliar
                join concategorias cat on cat_CodAuxiliar=per_CodAuxiliar and cat_Categoria=10
        where ".$persona." = '".$_SESSION['g_user']."' ".$filtro."
        and origen <> destino and det_ValCredito<>0 
        and {filter}
        order by 3 desc, {sort} {dir}
        LIMIT {start}, {limit}        ";
        /*
         and cab.idbatch in 
        (
        select  max(c.idbatch)
        from concheques_cab c 
        inner join concheques_det d on c.idbatch=d.idbatch
        where ".$persona." = '".$_SESSION['g_user']."' and confirmado=1
        group by c.idbatch
        order by fecconfir desc
        )
        */
    /*$_SESSION[$gsSesVar]=
        "select com_regnumero regNum, det_secuencia secuencia, per_Apellidos banco, det_NumCheque cheque, 
            com_FecContab fecha, det_ValCredito valor, com_Receptor beneficiario, com_Concepto concepto, 
            com_TipoComp tipoComp, com_NumComp numComp
        from concomprobantes c
        join condetalle d on c.com_regnumero=d.det_regnumero
        join conpersonas p on det_idauxiliar=per_CodAuxiliar
        join concategorias cat on cat_CodAuxiliar=per_CodAuxiliar and cat_Categoria=10
        where com_tipocomp <> 'AN' and det_NumCheque <> ''  and  
            com_FecContab between '".fGetParam("pFecIni", '2020-12-12')."' and '".fGetParam("pFecFin", '2020-12-12')."'
            and com_regnumero not in (select com_regnum from concheques_det)
            and {filter}
        order by per_Apellidos, {sort} {dir}
        LIMIT {start}, {limit}        ";*/
        
    //echo( $_SESSION[$gsSesVar]);
    
    $_SESSION["CoTrTr_usuarios"] = "select usu_login cod,usu_Nombre txt from seguridad.segusuario
                                    where usu_Activo=1 and usu_ValidoHasta >= curdate()
                                        and usu_Nombre LIKE '%{query}%'
                                    order by usu_Nombre";
    
    
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
        }
        function beforeGetData(){
            if (1 == fGetParam("env", 0)){
                $persona2 = "DESTINO";
            }else{
                $persona2 = "ORIGEN";
            }
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
            $goGrid->setFieldOpt('regNum', array("header"=>"Reg.Num.", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('secuencia', array("header"=>"secuencia", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('fecha', array("header"=>"FECHA", "hidden"=>0,"width"=>15, "css"=>"background-color:#F2F5A9;"));
            $goGrid->setFieldOpt('persona', array("header"=>"USUARIO", "hidden"=>0,"width"=>10, "css"=>"background-color:#F2F5A9;"));
            $goGrid->setFieldOpt('observacion', array("header"=>"OBSERV.", "hidden"=>0, "width"=>20, "css"=>"background-color:#F2F5A9;"));
            $goGrid->setFieldOpt('confirmado', array("header"=>"CONFIRMADO", "hidden"=>0, "width"=>10, "renderer"=>"check", "css"=>"background-color:#F2F5A9;"));
            
            $goGrid->setFieldOpt('banco', array("header"=>"BANCO", "hidden"=>0, "width"=>17));
            $goGrid->setFieldOpt('cheque', array("header"=>"CHEQUE", "hidden"=>0, "width"=>10));//, "renderer"=>"intSimple"));
            
            $goGrid->setFieldOpt('valor', array("header"=>"VALOR", "hidden"=>0, "width"=>12, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('beneficiario', array("header"=>"BENEFICIARIO", "hidden"=>0, "width"=>20));
            
            $goGrid->setFieldOpt('tipoComp', array("header"=>"TIPO COMP.", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('numComp', array("header"=>"NUM.COMP.", "hidden"=>0,"width"=>10));
            
            //$goGrid->setFieldOpt('det_EstLibros', array("header"=>"EST.LIBROS", "hidden"=>0, "width"=>10, "renderer"=>"check", "css"=>"background-color:#F2F5A9;"));
            //$goGrid->setFieldOpt('det_FecLibros', array("header"=>"FEC.LIBROS", "hidden"=>0, "width"=>15, "css"=>"background-color:#F3F781;"/*,'editor'=>array("xtype"=>"datefield","renderer" => "formatDate")*/));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}