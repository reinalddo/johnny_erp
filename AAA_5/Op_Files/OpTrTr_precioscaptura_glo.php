<?php
/** 
*   Grid para presentar datos de Precios por productor, Globalmente sin agrupar por Embarque
*    
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @rev    fah 20/Abr/08   Manejar como parametro GET/POST la bandera de Metadata (meta=true/1)
*   @rev    fah 13/May/08   Se define el flete como un atributo del detalle de tarja
*   @rev    fah 14/03/09    
**/
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
error_reporting(E_ALL);
$gsSesVar="opPrecios";
$gsSqlId=fGetParam("op", "pro");
/*
*       INstruccion para Modificar un flete en tarjas
*/
$_SESSION[$gsSesVar. "_flete"]=
"Update liqtarjacabec JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	set tad_indFlete = {pFlete}
WHERE {pCond} ";
/*
 *
 */
//$slCondBase ="txp_refOperativa = {pEmb} ";
$slCondBase ="txp_semana = {pSem} ";
switch ($gsSqlId){
    case ("pro"): //           Precios  a  nivel Productor
        $_SESSION[$gsSesVar]=
        "SELECT txp_Semana,
            txp_CatProducto,
            txp_Embarcador, 
            left(txp_Productor, 30) as txp_Productor,
            txp_CodProducto,
            txp_Producto,
            txp_indFlete as tmp_Flete,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) / sum(if(txp_CantNeta =0,1,txp_CantNeta)) as tmp_PrecUnit,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand 
        WHERE $slCondBase AND {filter}
        GROUP BY 1,2,3,4,5,6,7
        ORDER BY txp_CatProducto, txp_Producto, txp_Productor " ;  // txp_CatProducto, txp_Producto, txp_Productor 
        break;
    case ("mar"): //           Precios  a  nivel de marca
        $_SESSION[$gsSesVar]=
        "SELECT txp_Semana,
            txp_Embarcador, 
            txp_CodProducto,
            txp_CodMarca, txp_Marca, txp_NumTarja,
            txp_CodCaja, txp_CajDescrip,
            txp_PrecUnit as tmp_PrecUnit,
            txp_indFlete as tmp_Flete,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand 
        WHERE $slCondBase  AND txp_Embarcador = {pProd} AND txp_CodProducto = {pPrdc} AND  {filter}
        GROUP BY 1,2,3,4,5,6,7,8,9,10
        ORDER BY txp_CatProducto, txp_Producto, txp_Marca, txp_PrecUnit " ;  // txp_CatProducto, txp_Producto, txp_Productor 
        break;
    case ("dia"): //           Precios  a  nivel de Diario
        $_SESSION[$gsSesVar]=
        "SELECT txp_Semana,
            txp_Embarcador, 
            txp_CodProducto,
            txp_CodMarca, txp_Marca,
            txp_CodCaja, txp_CajDescrip,
            date_format(txp_Fecha, '%Y-%m-%d') as txp_Fecha,
            txp_PrecUnit as tmp_PrecUnit,
            txp_indFlete as tmp_Flete, txp_NumTarja,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand 
        WHERE $slCondBase  AND  txp_Embarcador = {pProd} AND txp_CodProducto = {pPrdc} AND  {filter}
        GROUP BY 1,2,3,4,5,6,7,8,9, 10, 11
        ORDER BY txp_CatProducto, txp_Producto, txp_Marca, txp_Fecha, txp_PrecUnit " ;  // txp_CatProducto, txp_Producto, txp_Productor 
        break;
    case ("tar"): //           Precios  a niver tarja
        $_SESSION[$gsSesVar]=
         "SELECT txp_Semana,
            txp_Embarcador, 
            txp_CodProducto,
            txp_CodMarca, txp_Marca,
            date_format(txp_Fecha, '%Y-%m-%d') as txp_Fecha,
            txp_CodCaja,  txp_CajDescrip,
            txp_PrecUnit as tmp_PrecUnit,
            txp_NumTarja,
	    txp_Secuencia,
            txp_indFlete as tmp_Flete,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand 
        WHERE $slCondBase AND txp_Embarcador = {pProd} AND txp_CodProducto = {pPrdc} AND  {filter}
        GROUP BY 1,2,3,4,5,6,7,8,9, 10,11,12
        ORDER BY txp_CatProducto, txp_Producto, txp_Marca, txp_Fecha, txp_CajDescrip, txp_PrecUnit " ;  // txp_CatProducto, txp_Producto, txp_Productor 
        break;  //       se elimino       sum(txp_CantNeta * txp_PrecUnit ) / sum(if(txp_CantNeta =0,1,txp_CantNeta)) as tmp_PrecUnit
    default:
    break;
}
//print_r($_SESSION);
$_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
$_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';

if (fGetParam("init", false) != false) {
    $_SESSION[$gsSesVar]=NULL;
  // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $loadJSFile = "../Op_Files/OpTrTr_precioscaptura_glo"; // Script a cargar en loadgrid.php
    $tplFile ="../Ge_Files/GeGeGe_extpanels.tpl";  // Template to load in loadgrid.
    require "../Ge_Files/GeGeGe_loadgrid.php";
    }
else { 
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    ob_start("ob_gzhandler");
    //echo "2";
    //$db->debug = isset($_SESSION['pAdoDbg'])? $_SESSION['pAdoDbg'] : (isset($_GET['pAdoDbg']) ? $_GET['pAdoDbg']: -1);
    $db->debug = fGetParam('pAdoDbg', false);
    header("Content-Type: text/html;  charset=ISO-8859-1");
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
	     $alNumbField = array("xtype"=>"numberField",
                                "options"=>array("allowBlank" => "false",
                                                 "allowNegative"=> "true",
                                                 "style"=> "text-align:right"));
            $alTextField = array("xtype"=>"textField",
                                "options"=>array("allowBlank" => "false",
                                                 "style"=> "text-align:left"));
	    /*
            $alNumbField = array("type"=>"Ext.form.NumberField",
                                "options"=>array("allowBlank" => "false",
                                                 "allowNegative"=> "true",
                                                 "style"=> "text-align:right"));
            $alTextField = array("type"=>"Ext.form.TextField",
                                "options"=>array("allowBlank" => "false",
                                                 "style"=> "text-align:left"));
	    */
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            switch ($gsSqlId){           
                case "pro":
                    $goGrid->setFieldOpt('txp_Semana', array("header"=>"SEMANA", "hidden"=>0));
                    $goGrid->setFieldOpt('txp_RefOperativa', array("header"=>"ID EMB.", "hidden"=>0));
                    $goGrid->setFieldOpt('txp_NombBuque', array("header"=>"VAPOR", "hidden"=>0, "width"=>15));
                    $goGrid->setFieldOpt('txp_Embarcador', array("header"=>"COD.PRODUCTR", "hidden"=>1));        
                    $goGrid->setFieldOpt('txp_Productor', array("header"=>"PRODUCTOR", "hidden"=>0, "width"=>15));
                    $goGrid->setFieldOpt('txp_CatProducto', array("header"=>"FRUTA", "hidden"=>0));
                    $goGrid->setFieldOpt('txp_CodProducto', array("header"=>"PROD", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Producto', array("header"=>"PRODUCTO", "hidden"=>0, "width"=>15));
                    $goGrid->setFieldOpt('txp_Fecha',   array("header"=>"FECHA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_NumTarja', array("header"=>"TARJA NUM", "hidden"=>0, "width"=>10));
		    $goGrid->setFieldOpt('txp_Secuencia', array("header"=>"TARJA SEC", "hidden"=>1, "width"=>8));
                    break;
                case "mar": //           Precios  a  nivel Marca
                    $goGrid->setFieldOpt('txp_Semana', array("header"=>"SEMANA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_RefOperativa', array("header"=>"ID EMB.", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Embarcador', array("header"=>"COD.PRODUCTR", "hidden"=>1));        
                    $goGrid->setFieldOpt('txp_CodProducto', array("header"=>"PROD", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CodMarca', array("header"=>"COD. MARCA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Marca', array("header"=>"MARCA", "hidden"=>0, "width"=>20));
                    $goGrid->setFieldOpt('txp_CodCaja', array("header"=>"COD. CJA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CajDescrip', array("header"=>"CAJA", "hidden"=>0, "width"=>15));
                    $goGrid->setFieldOpt('txp_Fecha',   array("header"=>"FECHA", "hidden"=>1, "width"=>10));
                    $goGrid->setFieldOpt('txp_NumTarja', array("header"=>"TARJA NUM", "hidden"=>1, "width"=>10));
                    break;
                case "tar": //           Precios  a  nivel Detalle
                    $goGrid->setFieldOpt('txp_NumTarja', array("header"=>"TARJA NUM", "hidden"=>0, "width"=>15));
                    $goGrid->setFieldOpt('txp_Semana', array("header"=>"SEMANA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_RefOperativa', array("header"=>"ID EMB.", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Embarcador', array("header"=>"COD.PRODUCTR", "hidden"=>1));        
                    $goGrid->setFieldOpt('txp_CatProducto', array("header"=>"FRUTA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CodProducto', array("header"=>"PROD", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Producto', array("header"=>"PRODUCTO", "hidden"=>40));
                    $goGrid->setFieldOpt('txp_CodMarca', array("header"=>"COD. MARCA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Marca', array("header"=>"MARCA", "hidden"=>0, "width"=>40));
                    $goGrid->setFieldOpt('txp_CodCaja', array("header"=>"COD. CJA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CajDescrip', array("header"=>"CAJA", "hidden"=>0, "width"=>40));
                    //$goGrid->setFieldOpt('txp_Fecha',   array("header"=>"FECHA", "hidden"=>0, "width"=>50));
					$goGrid->setFieldOpt('txp_Secuencia', array("header"=>"SEC", "hidden"=>1, "width"=>10));
                    break;
                case "dia": //           Precios  a  nivel Diario
                    $goGrid->setFieldOpt('txp_Semana', array("header"=>"SEMANA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_RefOperativa', array("header"=>"ID EMB.", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_NombBuque', array("header"=>"VAPOR", "hidden"=>1, "width"=>15));
                    $goGrid->setFieldOpt('txp_Embarcador', array("header"=>"COD.PRODUCTR", "hidden"=>1));        
                    $goGrid->setFieldOpt('txp_Productor', array("header"=>"PRODUCTOR", "hidden"=>1, "width"=>20));
                    $goGrid->setFieldOpt('txp_CatProducto', array("header"=>"FRUTA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CodProducto', array("header"=>"PROD", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Producto', array("header"=>"PRODUCTO", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CodMarca', array("header"=>"COD. MARCA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Marca', array("header"=>"MARCA", "hidden"=>0, "width"=>12));
                    $goGrid->setFieldOpt('txp_CodCaja', array("header"=>"COD. CJA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CajDescrip', array("header"=>"CAJA", "hidden"=>0, "width"=>15));
                    $goGrid->setFieldOpt('txp_Fecha',   array("header"=>"FECHA", "hidden"=>0, "width"=>10));
                    //$goGrid->setFieldOpt('txp_NumTarja', array("header"=>"TARJA NUM", "hidden"=>1, "width"=>50));
                    break;
                default:
                
            }
            $goGrid->setFieldOpt('tmp_Cantidad', array("header"=>"CANTIDAD", "hidden"=>0, 'width'=>8, "summaryType"=>"sum", "hideable"=>"false"));
            $goGrid->setFieldOpt('tmp_Valor',    array("header"=>"VALOR", 'width'=>10, "hidden"=>0, "summaryType"=>"sum", "hideable"=>"false"));
            $goGrid->setFieldOpt('tmp_PrecUnit', array("header"=>"PRECIO", 'width'=>8, "hidden"=>0, "editor"=>$alNumbField));
            $goGrid->setFieldOpt('tmp_Flete',    array("header"=>"FLETE",  'width'=>5, "hidden"=>0, "editor"=>$alNumbField));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
//     echo "3";
    $goGrid = new clsExtGrid("precGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $blMetaFlag = fGetParam('meta', false);
    if (strlen($blMetaFlag) > 0 && ($blMetaFlag == 1 || $blMetaFlag =="true")) $goData->setMetadataFlag(true);
    ;
    $goData->getJson();
    ob_end_flush();
}
?>