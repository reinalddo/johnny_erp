<?php
/** 
*   Grid para presentar cheques que estan en custodia del usuario actual
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
    
    /* esl 14/mayo/2010
    * $_SESSION['CoTrTr_nPerfil'] define si el usuario tiene perfil de supervisor o no
    * El valor de la variable de sesión se asigna en CoTrTr_panelcheques.php
    * CSUPE = Es el perfil de Supervisor de Contabilidad
    * TESR  = Es el perfil de TES Senior
    * Si $_SESSION['CoTrTr_nPerfil'] > 0 significa que el usuario tiene uno de estos perfiles en la empresa en que inicio sesion,
    * no se va a filtrar por usuario la consulta de los cheques.
    */
        
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    
    
    if ($_SESSION['CoTrTr_nPerfil'] > 0){ // No se filtra por usuario.
	
	$_SESSION[$gsSesVar . "_count"]= "select count(*) as totalRecs
        from concheques_cab cab
        inner join concheques_det det on det.idbatch = cab.idbatch
	LEFT join ( /*query q reemplaza la vista*/
		select `cab`.`IdBatch` AS `IdBatch`
			,`c`.`com_RegNumero` AS `regNum`
			,`d`.`det_Secuencia` AS `secuencia`
			,`cab`.`Fecha` AS `fecha`
			,`cab`.`Origen` AS `origen`
			,`cab`.`Destino` AS `destino`
			,`cab`.`Observacion` AS `observacion`
			,(case when (`det`.`confirmado` = 1) then 's' else 'n' end) AS `confirmado`
		       /* ,`p`.`per_Apellidos` AS `banco` */
			,`d`.`det_IDAuxiliar` 
			,`d`.`det_NumCheque` AS `cheque`
			,`d`.`det_ValCredito` AS `valor`
		       /*  ,`c`.`com_Receptor` AS `beneficiario`*/
			,`c`.`com_TipoComp` AS `tipoComp`
			,`c`.`com_NumComp` AS `numComp`
		from v_conCheqEst join 	`concheques_cab` `cab` on cab.idbatch = ves_idbatch and ves_tipo = 2
			    join `concheques_det` `det` on `cab`.`IdBatch` = `det`.`IdBatch` and `cab`.`tipo` = 2
			    join `concomprobantes` `c` on `c`.`com_RegNumero` = `det`.`com_regNum`
			    join `condetalle` `d` on `d`.`det_RegNumero` =`c`.`com_RegNumero`
			    /* join `conpersonas` `p` on `p`.`per_CodAuxiliar` = `d`.`det_IDAuxiliar` */
			    join `concategorias` `cat` on `cat`.`cat_CodAuxiliar` = `d`.`det_IdAuxiliar` and `cat`.`cat_Categoria` = 10  /*  and `cab`.`Origen` <> `cab`.`Destino` */
		group by regNum	    
		) ce on ce.regNum=det.com_regNum
        join concomprobantes c on c.com_regnumero=det.com_regnum 
	join condetalle d on d.det_regnumero = c.com_regnumero
	join conpersonas p on p.per_CodAuxiliar = d.det_idauxiliar
	join concategorias cat on cat_CodAuxiliar= d.det_IdAuxiliar and cat_Categoria=10
	join v_conCheqEst on ves_idbatch =cab.idbatch and ves_confirmado = 1 and ves_tipo=2 /* and ves_regnum = det.com_regnum */
	join v_conCheqRegNum reg on reg.com_regnum = det.com_regnum
        where det.confirmado=1 and tipo=2 and det_ValCredito<>0
	and date_format(com_FecContab, '%Y-%m-%d') between '".fGetParam("pFecIni",'')."' and '".fGetParam("pFecFin",'')."'
        ";
	
	
	
	$_SESSION[$gsSesVar]= "select com_regnumero regNum
				    , d.det_secuencia secuencia
				    , per_Apellidos banco
				    , det_NumCheque cheque
				    , com_FecContab fecha
				    , det_ValCredito valor
				    , com_Receptor beneficiario
				    , com_Concepto concepto
				    , com_TipoComp tipoComp
				    , com_NumComp numComp
				    , case when (det.confirmado=1 and tipo=2 and det_ValCredito<>0) then cab.destino else cab.origen end origen
				    , cab.observacion
				    ,case when not regnum  is null and ce.confirmado='n' then 'n' else '' end confirmado
        from concheques_cab cab
        inner join concheques_det det on det.idbatch = cab.idbatch
	LEFT join ( /*query q reemplaza la vista*/
		select `cab`.`IdBatch` AS `IdBatch`
			,`c`.`com_RegNumero` AS `regNum`
			,`d`.`det_Secuencia` AS `secuencia`
			,`cab`.`Fecha` AS `fecha`
			,`cab`.`Origen` AS `origen`
			,`cab`.`Destino` AS `destino`
			,`cab`.`Observacion` AS `observacion`
			,(case when (`det`.`confirmado` = 1) then 's' else 'n' end) AS `confirmado`
		        /*,`p`.`per_Apellidos` AS `banco`*/
			,`d`.`det_IDAuxiliar` 
			,`d`.`det_NumCheque` AS `cheque`
			,`d`.`det_ValCredito` AS `valor`
		       /*  ,`c`.`com_Receptor` AS `beneficiario`*/
			,`c`.`com_TipoComp` AS `tipoComp`
			,`c`.`com_NumComp` AS `numComp`
		from v_conCheqEst join 	`concheques_cab` `cab` on cab.idbatch = ves_idbatch and ves_tipo = 2
			    join `concheques_det` `det` on `cab`.`IdBatch` = `det`.`IdBatch` and `cab`.`tipo` = 2
			    join `concomprobantes` `c` on `c`.`com_RegNumero` = `det`.`com_regNum`
			    join `condetalle` `d` on `d`.`det_RegNumero` =`c`.`com_RegNumero`
			    /* join `conpersonas` `p` on `p`.`per_CodAuxiliar` = `d`.`det_IDAuxiliar` */
			    join `concategorias` `cat` on `cat`.`cat_CodAuxiliar` = `d`.`det_IdAuxiliar` and `cat`.`cat_Categoria` = 10  /*  and `cab`.`Origen` <> `cab`.`Destino` */
			    group by regNum
		) ce on ce.regNum=det.com_regNum
        join concomprobantes c on c.com_regnumero=det.com_regnum 
	join condetalle d on d.det_regnumero = c.com_regnumero
	join conpersonas p on p.per_CodAuxiliar = d.det_idauxiliar
	join concategorias cat on cat_CodAuxiliar= d.det_IdAuxiliar and cat_Categoria=10
	join v_conCheqEst on ves_idbatch =cab.idbatch and ves_confirmado = 1 and ves_tipo=2 /* and ves_regnum = det.com_regnum*/
	join v_conCheqRegNum reg on reg.com_regnum = det.com_regnum
        where det.confirmado=1 and tipo=2 and det_ValCredito<>0
	and date_format(com_FecContab, '%Y-%m-%d')  between '".fGetParam("pFecIni",'')."' and '".fGetParam("pFecFin",'')."' 
        and {filter}
        LIMIT {start}, {limit} ";
	
	
	
	
	//echo($_SESSION[$gsSesVar]);
    }
    else{ // Se filtra por usuario	
	$_SESSION[$gsSesVar . "_count"]= "select count(*) as totalRecs
        from concheques_cab cab
        inner join concheques_det det on det.idbatch = cab.idbatch
	LEFT join ( /*query q reemplaza la vista*/
		select `cab`.`IdBatch` AS `IdBatch`
			,`c`.`com_RegNumero` AS `regNum`
			,`d`.`det_Secuencia` AS `secuencia`
			,`cab`.`Fecha` AS `fecha`
			,`cab`.`Origen` AS `origen`
			,`cab`.`Destino` AS `destino`
			,`cab`.`Observacion` AS `observacion`
			,(case when (`det`.`confirmado` = 1) then 's' else 'n' end) AS `confirmado`
		       /* ,`p`.`per_Apellidos` AS `banco` */
			,`d`.`det_IDAuxiliar` 
			,`d`.`det_NumCheque` AS `cheque`
			,`d`.`det_ValCredito` AS `valor`
		       /*  ,`c`.`com_Receptor` AS `beneficiario` */
			,`c`.`com_TipoComp` AS `tipoComp`
			,`c`.`com_NumComp` AS `numComp`
		from v_conCheqEst join 	`concheques_cab` `cab` on cab.idbatch = ves_idbatch and ves_tipo = 2
			    join `concheques_det` `det` on `cab`.`IdBatch` = `det`.`IdBatch` and `cab`.`tipo` = 2
			    join `concomprobantes` `c` on `c`.`com_RegNumero` = `det`.`com_regNum`
			    join `condetalle` `d` on `d`.`det_RegNumero` =`c`.`com_RegNumero`
			    /* join `conpersonas` `p` on `p`.`per_CodAuxiliar` = `d`.`det_IDAuxiliar` */
			    join `concategorias` `cat` on `cat`.`cat_CodAuxiliar` = `d`.`det_IdAuxiliar` and `cat`.`cat_Categoria` = 10  /*  and `cab`.`Origen` <> `cab`.`Destino`*/
		where origen ='".$_SESSION['g_user']."' and ifnull(det.confirmado,0) = 0 group by regNum) ce on ce.regNum=det.com_regNum
	join concomprobantes c on c.com_regnumero=det.com_regnum 
	join condetalle d on d.det_regnumero = c.com_regnumero
	join conpersonas p on p.per_CodAuxiliar = d.det_idauxiliar
	join concategorias cat on cat_CodAuxiliar= d.det_IdAuxiliar and cat_Categoria=10
	join v_conCheqEst on ves_idbatch =cab.idbatch and ves_confirmado = 1 and ves_tipo=2
	join v_conCheqRegNum reg on reg.com_regnum = det.com_regnum
	where cab.destino = '".$_SESSION['g_user']."' and det.confirmado=1 and tipo=2 and det_ValCredito<>0
	and date_format(com_FecContab, '%Y-%m-%d') between '".fGetParam("pFecIni",'')."' and '".fGetParam("pFecFin",'')."' 
        ";
	
	
	$_SESSION[$gsSesVar]= "select com_regnumero regNum
				    , d.det_secuencia secuencia
				    , per_Apellidos banco
				    , det_NumCheque cheque
				    , com_FecContab fecha
				    , det_ValCredito valor
				    , com_Receptor beneficiario
				    , com_Concepto concepto
				    , com_TipoComp tipoComp
				    , com_NumComp numComp
				    , case when (det.confirmado=1 and tipo=2 and det_ValCredito<>0) then cab.destino else cab.origen end origen
				    , cab.observacion
				    ,case when not regnum  is null and ce.confirmado='n' then 'n' else '' end confirmado
        from concheques_cab cab
        inner join concheques_det det on det.idbatch = cab.idbatch
	LEFT join ( /*query q reemplaza la vista*/
		select `cab`.`IdBatch` AS `IdBatch`
			,`c`.`com_RegNumero` AS `regNum`
			,`d`.`det_Secuencia` AS `secuencia`
			,`cab`.`Fecha` AS `fecha`
			,`cab`.`Origen` AS `origen`
			,`cab`.`Destino` AS `destino`
			,`cab`.`Observacion` AS `observacion`
			,(case when (`det`.`confirmado` = 1) then 's' else 'n' end) AS `confirmado`
		       /* ,`p`.`per_Apellidos` AS `banco` */
			,`d`.`det_IDAuxiliar` 
			,`d`.`det_NumCheque` AS `cheque`
			,`d`.`det_ValCredito` AS `valor`
		       /*  ,`c`.`com_Receptor` AS `beneficiario` */
			,`c`.`com_TipoComp` AS `tipoComp`
			,`c`.`com_NumComp` AS `numComp`
		from v_conCheqEst join 	`concheques_cab` `cab` on cab.idbatch = ves_idbatch and ves_tipo = 2
			    join `concheques_det` `det` on `cab`.`IdBatch` = `det`.`IdBatch` and `cab`.`tipo` = 2
			    join `concomprobantes` `c` on `c`.`com_RegNumero` = `det`.`com_regNum`
			    join `condetalle` `d` on `d`.`det_RegNumero` =`c`.`com_RegNumero`
			    /* join `conpersonas` `p` on `p`.`per_CodAuxiliar` = `d`.`det_IDAuxiliar` */
			    join `concategorias` `cat` on `cat`.`cat_CodAuxiliar` = `d`.`det_IdAuxiliar` and `cat`.`cat_Categoria` = 10  /*  and `cab`.`Origen` <> `cab`.`Destino`*/
		where origen ='".$_SESSION['g_user']."' and ifnull(det.confirmado,0) = 0 group by regNum) ce on ce.regNum=det.com_regNum
	join concomprobantes c on c.com_regnumero=det.com_regnum 
	join condetalle d on d.det_regnumero = c.com_regnumero
	join conpersonas p on p.per_CodAuxiliar = d.det_idauxiliar
	join concategorias cat on cat_CodAuxiliar= d.det_IdAuxiliar and cat_Categoria=10
	join v_conCheqEst on ves_idbatch =cab.idbatch and ves_confirmado = 1 and ves_tipo=2
	join v_conCheqRegNum reg on reg.com_regnum = det.com_regnum
	where cab.destino = '".$_SESSION['g_user']."' and det.confirmado=1 and tipo=2 and det_ValCredito<>0
	and date_format(com_FecContab, '%Y-%m-%d') between '".fGetParam("pFecIni",'')."' and '".fGetParam("pFecFin",'')."' 
        and {filter}
        LIMIT {start}, {limit}        ";
	
    }
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
            $goGrid->setFieldOpt('regNum', array("header"=>"Reg.Num.", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('secuencia', array("header"=>"secuencia", "hidden"=>1, "width"=>5));
            $goGrid->setFieldOpt('banco', array("header"=>"BANCO", "hidden"=>0, "width"=>17));
            $goGrid->setFieldOpt('cheque', array("header"=>"CHEQUE", "hidden"=>0, "width"=>10));//, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('fecha', array("header"=>"FECHA", "hidden"=>0,"width"=>15));
            $goGrid->setFieldOpt('valor', array("header"=>"VALOR", "hidden"=>0, "width"=>12, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('beneficiario', array("header"=>"BENEFICIARIO", "hidden"=>0, "width"=>20));
            $goGrid->setFieldOpt('concepto', array("header"=>"CONCEPTO", "hidden"=>0, "width"=>20));
            $goGrid->setFieldOpt('tipoComp', array("header"=>"TIPO COMP.", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('numComp', array("header"=>"NUM.COMP.", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('origen', array("header"=>"ORIGEN", "hidden"=>0,"width"=>10, "css"=>"background-color:#F2F5A9;"));
            $goGrid->setFieldOpt('observacion', array("header"=>"OBSERVACION", "hidden"=>0,"width"=>30, "css"=>"background-color:#F2F5A9;"));
            $goGrid->setFieldOpt('confirmado', array("header"=>"CONFIRM", "hidden"=>1,"width"=>10));
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