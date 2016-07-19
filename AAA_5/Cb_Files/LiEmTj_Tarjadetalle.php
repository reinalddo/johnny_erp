<?php
/**  ARCHIVO RENOMBRADO: antes: detacompro
*   Grid Editable con detalle de comprobantes
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
**/
if (!isset ($_SESSION)) session_start();

header('Pragma: no-cache ');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");// always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Content-Type: text/html;  charset=ISO-8859-1");
require "GenUti.inc.php";
$FileName=basename($_SERVER["PHP_SELF"], ".php"); //   Definicion obligatoria para que funciones Seglib
error_reporting(E_ALL);
$gsSesVar="coDetalle";
if (fGetParam("init", false) >= 1) {
	$_SESSION[$gsSesVar]=
			"select d.*,txp_CajDescrip,txp_Marca,txp_producto,txp_productor
                            /*, txp_producto*/, c1.cte_Descripcion txp_carton, c2.cte_Descripcion txp_plastico
                            , c3.cte_Descripcion txp_material, c4.cte_Descripcion txp_etiq
                        from liqtarjadetal d 
                                inner join v_opetarjexpand v1 on v1.txp_NumTarja=tad_NumTarja
                                left join liqcomponent c1 on c1.cte_codigo=tad_codcompon1
				left join liqcomponent c2 on c2.cte_codigo=tad_codcompon2
				left join liqcomponent c3 on c3.cte_codigo=tad_codcompon3
				left join liqcomponent c4 on c4.cte_codigo=tad_codcompon4
                        where tad_NumTarja={tad_NumTarja}
                        order by tad_Secuencia";
	$_SESSION["LiEmTj_empaque"] =
		"select caj_CodCaja cod, concat(caj_CodCaja,' ',caj_Descripcion) txt,
		caj_Componente1 txt1,caj_Componente2 txt2,caj_Componente3 txt3,caj_Componente4 txt4
                    from liqcajas 
                    where caj_CodMarca={pMarca} AND concat(caj_CodCaja,' ',caj_Descripcion) like '%{query}%'
                    order by caj_Descripcion" ;

	$_SESSION["LiEmTj_marca"] =
		"select par_Secuencia cod, concat(par_Secuencia,' ', par_Descripcion) txt
                    from genparametros where par_Clave = 'IMARCA'
                    and concat(par_Secuencia,' ', par_Descripcion) like '%{query}%'
                    order by par_Descripcion" ;
	$_SESSION["LiEmTj_producto"] =
		"select act_CodAuxiliar cod, concat(act_CodAuxiliar,' ',act_descripcion) txt 
                    from conactivos
                    where concat(act_CodAuxiliar,' ',act_descripcion) like '%{query}%'
                    and act_SubCategoria in (300 ,310)
                    order by act_descripcion" ;
	$_SESSION["LiEmTj_componente"] =
		"select cte_codigo cod, concat(cte_codigo, ' ', cte_Descripcion) txt 
                    from liqcomponent
                    where concat(cte_codigo, ' ', cte_Descripcion) like '%{query}%'
                    order by cte_Descripcion" ;
	$_SESSION[$gsSesVar . '_defs']['det_Usuario'] = $_SESSION['g_user'];
	$_SESSION[$gsSesVar . '_defs']['det_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "LiEmTj_Tarjadetalle"; // Script a cargar en loadgrid.php
    require "../Ge_Files/GeGeGe_loadgrid_basico.php"; // Process a basic grid object 'goGrid', not render yet
	$goGrid->addOptions("cellAndRowSM,inputMask"); // Add this options for grid
	$goGrid->render();
    }
else 
    {
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    require('../LibPhp/NoCache.php');	
    $db->debug = fGetParam('pAdoDbg', false);
    $db->Execute("SET lc_time_names = 'es_EC'");
    /*
     *	Derivated class to implement a variation on metadata (adding field options) before output
     *
     */
    class clsQueryGrid extends clsQueryToJson {
        function init(){
            //$this->metaDataFlag =$blMetaFlag;
        }
        function beforeGetData(){
            global $goGrid;
            global $gsSqlId;
            if (!$this->metaDataFlag) return;
            $slRendFunc="function(v, params, data){
                    return ((v === 0 || v > 1) ? '(' + v +' Tasks)' : '(1 Task)');
                }";
            $alNumbField = array("type"=>"Ext.form.NumberField",
                                "options"=>array("allowBlank" => "false",
                                                 "allowNegative"=> "true",
                                                 "style"=> "text-align:right"));
            $alTextField = array("type"=>"Ext.form.TextField",
                                "options"=>array("allowBlank" => "false",
                                                 "style"=> "text-align:left"));
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            switch ($gsSqlId){
                case "sel":
                    $goGrid->setFieldOpt('tad_numTarja', array("header"=>"ID", "hidden"=>0));
                    $goGrid->setFieldOpt('tad_Secuencia', array("header"=>"SEC", "hidden"=>0, "width"=>10));
                    $goGrid->setFieldOpt('tad_CodCaja', array("header"=>"EMPAQUE", "hidden"=>0, "width"=>40));
                    $goGrid->setFieldOpt('tad_CodMarca', array("header"=>"MARCA", "hidden"=>0, "width"=>10));		    
                    $goGrid->setFieldOpt('tad_CodProducto', array("header"=>"PRODUCTO", "hidden"=>0, "width"=>50));
                    $goGrid->setFieldOpt('tad_CantDespachada', array("header"=>"DESPACHADO", "hidden"=>0, "width"=>10));
                    $goGrid->setFieldOpt('tad_CantRecibida', array("header"=>"RECIB.PTO.", "hidden"=>0, "width"=>10));
                    $goGrid->setFieldOpt('tad_CantCaidas', array("header"=>"CAIDAS", "hidden"=>0, "width"=>40));
                    $goGrid->setFieldOpt('tad_CantRechazada', array("header"=>"RECHAZADO", "hidden"=>0, "width"=>50));
                    $goGrid->setFieldOpt('tad_CodEmpacador', array("header"=>"EMBARCADO", "hidden"=>0, "width"=>50));
                    $goGrid->setFieldOpt('tad_ValUnitario',array("header"=>"$ CAJA", "hidden"=>1, "width"=>10));
		            $goGrid->setFieldOpt('tad_DifUnitario',array("header"=>"DIF.PRECIO", "hidden"=>1, "width"=>0));
                    $goGrid->setFieldOpt('tad_CodCompon1', array("header"=>"CARTON", "hidden"=>1, "width"=>50));
                    $goGrid->setFieldOpt('tad_CodCompon2',array("header"=>"PLATICO", "hidden"=>1, "width"=>40));
                    $goGrid->setFieldOpt('tad_CodCompon3',array("header"=>"MATERIAL", "hidden"=>1, "width"=>300));
                    $goGrid->setFieldOpt('tad_CodCompon4',array("header"=>"ETIQUETA", "hidden"=>1, "width"=>10));
                    break;
                default:
                    break;                
            }
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
            return true;
        }
    }

    $goGrid = new clsExtGrid("detGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $blMetaFlag = fGetParam('meta', false);
    if (strlen($blMetaFlag) > 0 && ($blMetaFlag == 1 || $blMetaFlag =="true")) $goData->setMetadataFlag(true);
    ;
    $goData->getJson();
    ob_end_flush();
    
}
?>