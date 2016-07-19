<?

/*
*   Panel general de Operaciones
*   @created    Oct/30/07
*   @author     fah
*   @
*/
/************************************************************************* Inclusion de librerias bassicas */
if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include_once "../De_Files/DeGeGe_cabecera.php";
$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - ANEXOS";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");                 // Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
//$goPanel->addBodyScript("CoRtTr_GridAnexo");

// de la página php del grid
//$goPanel->addBodyScript("../LibJs/extAutogrid"); // AutoGrid
//$goPanel->addCssFile("../LibJs/ext/resources/css/xtheme-slate");
//$goPanel->addCssFile("../LibJs/ext/resources/css/Ext.ux.grid.GridSummary");

$goPanel->addJsAfterScripts("window.title='" . $_SESSION['g_empre'] . "';");
//$goPanel->addBodyScript("OpTrTr_pruestr");                          // OPCIONAL:  Incluir JS con Estructura basica de paneles
$slCss= ".x-grid-group {                                                // OPCIONAL:  Declarar una Regla CSS 
            font-family:arial,tahoma,helvetica,sans-serif;
            font-size:11px;	
            font-size-adjust:none;
            font-style:normal;
            font-variant:normal;
            font-weight:bold;
            line-height:13px;
            white-space:nowrap;
        }

        .x-grid-emptyrow {
            font-family:arial,tahoma,helvetica,sans-serif;
            font-size:11px;
            font-size-adjust:none;
            font-style:italic;
            font-variant:normal;
            font-weight:bold;
            line-height:13px;
            white-space:nowrap;
        }
    
        .cell-align-right .x-grid-cell-text  {
            text-align: right;
        }
		.boton-menu{width:210px !important}
        .leftfloated {float: left} 
       
        #north_left {
            float:left;
            width:50%;
            height:70px;
            padding:0;
            margin:0;
            background-color:#ffffff;
            background-image:url(" . (isset($_SESSION['logo_emp'])? $_SESSION['logo_emp']: "logo_app.png" ) ." );
            background-repeat:no-repeat;
            text-align:left;
        }
        #north_right{
            float:right;
            width:50%;
            height:70px; 
            padding:0;
            margin:0;
            background-color:#ffffff;
            background-image:url(" . (isset($_SESSION['logo_mod'])? $_SESSION['logo_mod']: "header_app_caja.jpg" ) ." );
            background-repeat:no-repeat;
            text-align:right;
        }
        .pnl-col{
            background-color:#D0D0D0;
        }
        Body{
			align:center;
                        font-size:8px !important;
        }
        .iconImprimir{
            background-image:url(../Images/printer.png) !important;
        }
        .iconBuscar{
            background-image:url(../Images/find.png) !important;
        }
        .iconNuevo{
            background-image:url(../Images/page_white.png) !important;
        }
        .iconGrabar{
            background-image:url(../Images/disk.png) !important;
        }
        .iconBorrar{
            background-image:url(../Images/delete.png) !important;
        }
        .iconSalir{
            background-image:url(../Images/cancel.png) !important;
        }
	";
$goPanel->addCssRule($slCss);// OPCIONAL Incluir la Regla declarada previamente, que se aplica a la pagina resultante
include("work_panels.qry.php");                                         // OPCIONAL Incluir archivo que define instrucciones SQL
/*
*       DEfinicion de variables de sesion que contiene las instrucciones SQl que se ejecutaran via ajax en el servidor
*       cuando se renderize el Front End
*/
$gsSesVar = "XXXX";                                                     //Prefijo de Id se variable de sesion.

$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);

$glExpanded = fGetParam('pExpan', 1);

$_SESSION[$gsSesVar. "_aplic"]= "";

$_SESSION["CoRtTr_embarlist"]=
"SELECT  emb_refoperativa as cod, concat(
                IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino )),
                '-', buq_Descripcion, '-', emb_numviaje, ' / ', v1.vau_descripcion, ' / ', v2.vau_descripcion
                 ) AS txt, emb_SemInicio txt_sem
FROM 	liqembarques
	JOIN liqbuques on buq_codbuque = emb_codvapor
        join v_auxgeneral v1 on v1.vau_codauxiliar=emb_CodProducto
	join v_auxgeneral v2 on v2.vau_codauxiliar=emb_Consignatario
WHERE	emb_estado BETWEEN 0 and 40 and
        concat(emb_SemInicio,'-', buq_Descripcion, '-', emb_numviaje) LIKE '%{query}%'
union
select 0 cod,'0' txt,0 txt_sem
order by 2 desc
        /*AND
	{pSeman} between emb_SemInicio and emb_SemTermino
order by emb_SemInicio desc,buq_Descripcion asc*/";


$_SESSION["CoRtTr_tipoTrans"]= "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt
                                from fistablassri
                                where tab_CodTabla = 'A' and tab_Estado = 1 and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '{query}%'";

$_SESSION["CoRtTr_sustento"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt, tab_txtData codigos
                                from fistablassri
                                where tab_CodTabla = '3' and tab_Estado = 1 and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '{query}%'";
                                    
$_SESSION["CoRtTr_tipoComp"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt
                                from fistablassri
                                where tab_CodTabla = '2' and tab_Estado = 1 
                                and tab_Codigo in ({pCodigos}) and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '{query}%'";

$_SESSION["CoRtTr_proveedor"] = "select per_CodAuxiliar cod,  concat(per_Ruc,' ',per_apellidos,' ',per_nombres) txt
                                ,pa.par_valor2 tipo, per_Ruc ruc
                                from conpersonas per 
                                inner JOIN concategorias cat on
                                        cat.cat_CodAuxiliar = per.per_CodAuxiliar
                                        and cat.cat_Categoria = 53
                                inner join genparametros pa on
                                        pa.par_clave= 'TIPID' and pa.par_secuencia = per.per_tipoID
                                where concat(per_Ruc,' ',per_apellidos,' ',per_nombres) LIKE '{query}%'
                                order by per_apellidos,per_nombres";
/*cat_Categoria = 53 es para la categoría proveedor*/                                
$_SESSION["CoRtTr_porcIVA"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt,tab_Porcentaje porc   
                                from fistablassri
                                where tab_CodTabla = '4' and tab_Estado = 1 and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '%{query}%'";
                                
$_SESSION["CoRtTr_porcICE"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt,tab_Porcentaje porc 
                                from fistablassri
                                where tab_CodTabla = '6' and tab_Estado = 1 and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '%{query}%'";
                                
$_SESSION["CoRtTr_porcRetIvaBienes"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt,tab_Porcentaje porc 
                                from fistablassri
                                where tab_CodTabla = '5a' and tab_Estado = 1 and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '%{query}%'";
                                
$_SESSION["CoRtTr_porcRetIvaServ"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt,tab_Porcentaje porc 
                                from fistablassri
                                where tab_CodTabla = '5' and tab_Estado = 1 and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '%{query}%'";
                                
$_SESSION["CoRtTr_codRetencion"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt,tab_Porcentaje porc 
                                from fistablassri
                                where tab_CodTabla = '10' and tab_Estado = 1 and
                                concat(tab_Codigo,' ',tab_Descripcion) LIKE '{query}%'";
                     
$_SESSION["CoRtTr_tipoDoc"] = "select tab_Codigo cod, concat(tab_Codigo,' ',tab_Descripcion) txt
                                from fistablassri
                                where tab_CodTabla = '2' and tab_Estado = 1 
                                and concat(tab_Codigo,' ',tab_Descripcion) LIKE '{query}%'";
                                
$_SESSION["CoRtTr_CtaGasto"] = "select c1.cue_codcuenta cod,
                                concat(c1.cue_codcuenta,' / ',c2.cue_Descripcion,' / ',c1.cue_Descripcion) txt   
                                from concuentas c1 
                                inner join concuentas c2 on c1.cue_padre=c2.cue_id
                                where concat(c1.cue_codcuenta,' / ',c2.cue_Descripcion,' / ',c1.cue_Descripcion) LIKE '{query}%'";
                          
$_SESSION["CoRtTr_CtaGastoAux"] = "select aux_Codigo cod, concat(aux_Codigo,' / ',aux_Nombre) txt
                                from v_conauxiliar
                                where concat(aux_Codigo,' / ',aux_Nombre) like '{query}%'";
                                

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
        
        
$_SESSION[$gsSesVar]=NULL;

$goPanel->render();

/*
// Columnas del Grid:
require('../LibPhp/extAutoGrid.class.php');
require('../LibPhp/queryToJson.class.php');
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
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
            return true;
        }
    }
    
    $goGrid = new clsExtGrid("precGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $blMetaFlag = fGetParam('meta', false);
    if (strlen($blMetaFlag) > 0 && ($blMetaFlag == 1 || $blMetaFlag =="true")) $goData->setMetadataFlag(true);
    ;
    $goData->getJson();
                   */

ob_end_flush();
?>

