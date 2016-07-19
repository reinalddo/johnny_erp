<?php
/** 
*   Grid para presentar datos de Precios porproductor
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @rev    fah 20/Abr/08   Manejar como parametro GET/POST la bandera de Metadata (meta=true/1)
**/
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
error_reporting(E_ALL);
$gsSesVar="opPrecios";
$gsSqlId=fGetParam("op", "pro");
$slCondBase ="txp_semana = {pSem} "; // txp_refOperativa = {pEmb}
switch ($gsSqlId){
    case ("pro"): //           Precios  a  nivel Productor
        $_SESSION[$gsSesVar]=
        "SELECT txp_Semana,
            txp_RefOperativa, 
            txp_Nombbuque, 
            txp_CatProducto,
            txp_Embarcador, 
            txp_Productor,
            txp_PrecUnit as tmp_PrecUnit,
            txp_CodProducto, txp_Producto, ifnull((fle_cantidad >0),0) as tmp_Flete,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand LEFT JOIN opefleteproduct on fle_Refoperat = txp_refoperativa and
                                        fle_codproductor = txp_embarcador AND
                                        fle_codproducto  = txp_codproducto AND 
                                        fle_codmarca     = 0
        WHERE $slCondBase AND {filter}
        GROUP BY 1,2,3,4,5,6,7,8,9
        ORDER BY txp_CatProducto, txp_Producto, txp_Productor " ;  // txp_CatProducto, txp_Producto, txp_Productor 
        break;
    case ("mar"): //           Precios  a  nivel de marca
        $_SESSION[$gsSesVar]=
        "SELECT txp_Semana,
            txp_RefOperativa, 
            txp_Nombbuque, 
            txp_CatProducto,
            txp_Embarcador, 
            txp_Productor,
            txp_CodProducto, txp_Producto,
            txp_CodMarca, txp_Marca,
            txp_PrecUnit as tmp_PrecUnit,
            ifnull((fle_cantidad >0),0) as tmp_Flete,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand LEFT JOIN opefleteproduct on fle_Refoperat = txp_refoperativa and
                                        fle_codproductor = txp_embarcador AND
                                        fle_codproducto  = txp_codproducto AND 
                                        fle_codmarca     = txp_CodMarca
        WHERE $slCondBase AND txp_Embarcador = {pProd} AND txp_CodProducto = {pPrdc} AND {filter}
        GROUP BY 1,2,3,4,5,6,7,8,9, 10, 11,12
        ORDER BY txp_CatProducto, txp_Producto, txp_Marca, txp_PrecUnit " ;  // txp_CatProducto, txp_Producto, txp_Productor 
        break;
    case ("dia"): //           Precios  a  nivel de Diario
        $_SESSION[$gsSesVar]=
        "SELECT txp_Semana,
            txp_RefOperativa, 
            txp_Nombbuque, 
            txp_CatProducto,
            txp_Embarcador, 
            txp_Productor,
            txp_CodProducto, txp_Producto,
            txp_CodMarca, txp_Marca,
            txp_Fecha,
            txp_PrecUnit as tmp_PrecUnit,
            ifnull((fle_cantidad >0),0) as tmp_Flete,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand LEFT JOIN opefleteproduct on fle_Refoperat = txp_refoperativa and
                                        fle_codproductor = txp_embarcador AND
                                        fle_codproducto  = txp_codproducto AND 
                                        fle_codmarca     = txp_CodMarca
        WHERE $slCondBase  AND txp_Embarcador = {pProd} AND txp_CodProducto = {pPrdc} AND  {filter}
        GROUP BY 1,2,3,4,5,6,7,8,9, 10, 11,12
        ORDER BY txp_CatProducto, txp_Producto, txp_Marca, txp_Fecha, txp_PrecUnit " ;  // txp_CatProducto, txp_Producto, txp_Productor 
        break;
    case ("tar"): //           Precios  a niver tarja
        $_SESSION[$gsSesVar]=
         "SELECT txp_Semana,
            txp_CatProducto,
            txp_Embarcador, 
            txp_Productor,
            txp_CodProducto, txp_Producto,
            txp_CodMarca, txp_Marca,
            txp_Fecha,
            txp_CodCaja,  txp_CajDescrip,
            txp_PrecUnit as tmp_PrecUnit,
            ifnull((fle_cantidad >0),0) as tmp_Flete,
            sum(txp_CantNeta) tmp_Cantidad,
            sum(txp_CantNeta * txp_PrecUnit ) as tmp_Valor
        FROM v_opetarjexpand LEFT JOIN opefleteproduct on fle_Refoperat = txp_refoperativa and
            fle_codproductor = txp_embarcador AND
            fle_codproducto  = txp_codproducto AND 
            fle_codmarca     = txp_CodMarca
        WHERE $slCondBase  AND txp_Embarcador = {pProd} AND txp_CodProducto = {pPrdc} AND  {filter}
        GROUP BY 1,2,3,4,5,6,7,8,9, 10, 11,12,13,14,15
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
                case "pro":
                    $goGrid->setFieldOpt('txp_Semana', array("header"=>"SEMANA", "hidden"=>0));
                    $goGrid->setFieldOpt('txp_RefOperativa', array("header"=>"ID EMB.", "hidden"=>0));
                    $goGrid->setFieldOpt('txp_NombBuque', array("header"=>"VAPOR", "hidden"=>0, "width"=>20));
                    $goGrid->setFieldOpt('txp_Embarcador', array("header"=>"COD.PRODUCTR", "hidden"=>1));        
                    $goGrid->setFieldOpt('txp_Productor', array("header"=>"PRODUCTOR", "hidden"=>0, "width"=>35));
                    break;
                case "mar": //           Precios  a  nivel Marca
                    $goGrid->setFieldOpt('txp_CodMarca', array("header"=>"COD. MARCA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Marca', array("header"=>"MARCA", "hidden"=>0, "width"=>20));
                    $goGrid->setFieldOpt('txp_Productor', array("header"=>"PRODUCTOR", "hidden"=>1, "width"=>35));
                    break;
                case "tar": //           Precios  a  nivel Detalle    
                    $goGrid->setFieldOpt('txp_CodMarca', array("header"=>"COD. MARCA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Marca', array("header"=>"MARCA", "hidden"=>0, "width"=>20));
                    $goGrid->setFieldOpt('txp_Fecha',   array("header"=>"FECHA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CodCaja', array("header"=>"COD. CJA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_CajDescrip', array("header"=>"CAJA", "hidden"=>0, "width"=>20));
                    break;
                case "dia": //           Precios  a  nivel Diario
                    $goGrid->setFieldOpt('txp_CodMarca', array("header"=>"COD. MARCA", "hidden"=>1));
                    $goGrid->setFieldOpt('txp_Marca', array("header"=>"MARCA", "hidden"=>0, "width"=>20));
                    $goGrid->setFieldOpt('txp_Fecha',   array("header"=>"FECHA", "hidden"=>1));
                    break;
                default:
                
            }
            $goGrid->setFieldOpt('txp_CatProducto', array("header"=>"FRUTA", "hidden"=>0));
            $goGrid->setFieldOpt('txp_CodProducto', array("header"=>"PROD", "hidden"=>1));
            $goGrid->setFieldOpt('txp_Producto', array("header"=>"PRODUCTO", "hidden"=>20));

            $goGrid->setFieldOpt('tmp_Cantidad', array("header"=>"CANTIDAD", "hidden"=>0, 'width'=>25, "summaryType"=>"sum", "hideable"=>"false"));
            $goGrid->setFieldOpt('tmp_Valor',    array("header"=>"VALOR", 'width'=>15, "hidden"=>0, "summaryType"=>"sum", "hideable"=>"false"));
            $goGrid->setFieldOpt('tmp_PrecUnit', array("header"=>"PRECIO", 'width'=>15, "hidden"=>0, "editor"=>$alNumbField));
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