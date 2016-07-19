<?php
/**  ARCHIVO RENOMBRADO: antes: CoTrTr_deta.php
*   Grid Editable con detalle de comprobantes
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
**/

if (!isset ($_SESSION)) session_start();
ob_start();
header('Pragma: no-cache ');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");// always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
require "GenUti.inc.php";
$FileName=basename($_SERVER["PHP_SELF"], ".php"); //   Definicion obligatoria para que funciones Seglib
error_reporting(E_ALL);
$gsSesVar="svGastos";
$gsSqlId=fGetParam("co", "sel");

switch ($gsSqlId){
    case ("sel"): //           Seleccion de Datos
        $_SESSION[$gsSesVar]=
        "SELECT condetalle.*, 
		cue_TipAuxiliar, cue_ReqRefOperat,
		concat(aux_codigo, ' - ', aux_Nombre) AS aux_Nombre,
		concat(cue_codcuenta, ' - - ', cue_Descripcion) as txt_Cuenta
	FROM condetalle 
		LEFT JOIN v_conauxiliar on aux_Codigo = det_idauxiliar 
		INNER JOIN concuentas ON condetalle.det_CodCuenta = concuentas.Cue_CodCuenta 
	WHERE det_RegNumero = {com_RegNumero} AND det_clasRegistro = 5 
	ORDER BY det_secuencia ";
        break;
    case ("upd"): 
        break;
    case ("dia"): 
    default:
    break;
}
/* clase de registro 5 - cuentas de gastos - usar parámetro */

$_SESSION["CoRtTr_CtaGasto"] = "select c1.cue_codcuenta cod,
                                concat(c1.cue_codcuenta,' / ',c2.cue_Descripcion,' / ',c1.cue_Descripcion) txt   
                                from concuentas c1 
                                inner join concuentas c2 on c1.cue_padre=c2.cue_id
				where concat(c1.cue_codcuenta,' / ',c2.cue_Descripcion,' / ',c1.cue_Descripcion) LIKE '{query}%'
				order by c1.cue_codcuenta";

$_SESSION["CoRtTr_CtaGastoAux"] = "select aux_Codigo cod, concat(aux_Codigo,' / ',aux_Nombre) txt
                                from v_conauxiliar
                                where concat(aux_Codigo,' / ',aux_Nombre) like '{query}%'";
                                

$_SESSION[$gsSesVar . '_defs']['det_Usuario'] = $_SESSION['g_user'];
$_SESSION[$gsSesVar . '_defs']['det_FecRegistro'] = '@date("Y-m-d H:i:s")';
$slInit = fGetParam("init", 1);

if ($slInit == 1) {
    $_SESSION[$gsSesVar]=NULL;
    $tplFile ="../Ge_Files/GeGeGe_extpanels.tpl";  // Template to load in loadgrid.
    include "../LibPhp/extTpl.class.php"; // deprecated include "../LibPhp/extTpl_2.class.php";
    $goGrid = new clsExtTpl($tplFile, true, false);
    $goGrid->addCssFile("../css/ext-aaa");
    header("Content-Type: text/html;  charset=ISO-8859-1");
    $goGrid->set("options", "cellAndRowSM,inputMask,summary,filter");
    $loadJSFile = "CoRtTr_GridAnexo"; // Script a cargar en loadgrid.php// Script JS
    $goGrid->addBodyScript ("../LibJs/extExtensions"); 
    $goGrid->addBodyScript ("../LibJs/extAutogrid"); // AutoGrid
    $goGrid->addBodyScript ("../LibJs/ext/ux/Ext.ux.gen.cmbBox"); // Cargamos archivo genérico de combos.
    $goGrid->addCssFile("../LibJs/ext/resources/css/xtheme-slate");
    $goGrid->addCssFile("../LibJs/ext/resources/css/Ext.ux.grid.GridSummary");
    $goGrid->addCssRule(".x-grid3-hd-inner {height:30px;text-align:center;white-space:normal;}");
    $goGrid->render();
    }
else { 
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    ob_start("ob_gzhandler");
    $db->debug = fGetParam('pAdoDbg', false);
    header("Content-Type: text/html;  charset=ISO-8859-1");
    $db->Execute("SET lc_time_names = 'es_EC'");
    class clsQueryGrid extends clsQueryToJson {
        function init(){
        }
        function beforeGetData(){
            global $goGrid;
            global $gsSqlId;
            if (!$this->metaDataFlag) return;
            $slRendFunc="function(v, params, data){
                    return ((v === 0 || v > 1) ? '(' + v +' Tasks)' : '(1 Task)');
                }";
            $alNumbField = array("type"=>"numberField",
                                "config"=>array("allowBlank" => "false",
	                        "allowNegative"=> "false",
				"allowDecimals"=> "true",
				"decimalPrecision"=> "2",
                                "style"=> "text-align:right",
				"change"=>"function(a,b,c,d){m=a;}",
				"onChange"=>"function(a,b,c,d){ m=a;}",
				"selectOnFocus"=>"true"
				));
	    
            $alTextField = array("type"=>"textField",
                                 "config"=>array("allowBlank" => "false",
						 "selectOnFocus"=>"true",
                                                 "style"=> "text-align:left"));
	    
            $alFieldsOpt = array();        
            $this->getRecordset();
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            switch ($gsSqlId){
                case "sel":
		    $goGrid->setFieldOpt('det_ValDebito', array("header"=>"DEBITO", "hidden"=>0, "width"=>20,  "summaryType"=>"sum",  "editor"=>$alNumbField));
		    $goGrid->setFieldOpt('det_ValCredito',array("header"=>"CREDITO", "hidden"=>0, "width"=>20, "summaryType"=>"sum",  "editor"=>$alNumbField));
		    $goGrid->setFieldOpt('det_Glosa',array("header"=>"GLOSA", "hidden"=>0, "width"=>300, "editor"=>$alTextField));
		    $goGrid->setFieldOpt('det_IDAuxiliar',array("header"=>"COD. AUXIL", "hidden"=>0, "width"=>40,
			    "editor"=>array("type"=>"genCmbBox",
					"config"=>array("sqlId"=>"CoRtTr_CtaGastoAux",
					    "hiddenName"=>"det_IDAuxiliar",
					    "minChars"=>"3",
                                            "style"=>"text-align:left")),
			    "renderer"=>"Ext.ux.renderer.Combo(genCmbBox)"));
		    $goGrid->setFieldOpt('det_CodCuenta', array("header"=>"COD. CUENTA", "hidden"=>0, "width"=>40,
			    "editor"=>array("type"=>"genCmbBox",
			    "config"=>array("sqlId"=>"CoRtTr_CtaGasto",
					    "hiddenName"=>"det_CodCuenta",
					    "minChars"=>"3",
                                            "style"=> "text-align:left"))));
                    break;
                default:
                    break;                
            }
            $this->metaData = $goGrid->processMetaData($this->metaData); 
            return true;
        }
    }
    $goGrid = new clsExtGrid("precGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $blMetaFlag = fGetParam('meta', false);
    if (strlen($blMetaFlag) > 0 && ($blMetaFlag == 1 || $blMetaFlag =="true")) $goData->setMetadataFlag(true);
    ;
    $goData->getJson();
    ob_end_flush();
}
?>