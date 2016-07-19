<?php
/** 
*   Grid para presentar datos de CXC
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);

$cols = array();
$cols = fColumnas();
//print_r($cols);

$gsSesVar="conLiqRubro";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]="select vau_codauxiliar des_Productor,vau_descripcion";
    
    $limite = count($cols);
    $ind = 0;
    while ($ind < $limite){
        //echo $cols[$ind]["rep_CodRubro"]."<br>";
        $codRubro = $cols[$ind]["rep_CodRubro"];
        $nomRubro = str_replace(".","0",str_replace(" ","",$cols[$ind]["rep_TitCorto"]));
        $_SESSION[$gsSesVar].=",sum(des_Valor*(1-abs(sign(des_Rubro-".$codRubro.")))) as '".$nomRubro."-".$codRubro."'";
        $ind++;
    }
    
    $_SESSION[$gsSesVar].= " from     liqdefaults d 
        right join v_auxgeneral p on des_Productor=vau_codauxiliar and des_Semana=".fGetParam("pSemana", 0)."
	where vau_codauxiliar in (select txp_Embarcador from v_opetarjexpand where txp_semana=".fGetParam("pSemana", 0).")
        group by vau_descripcion";
    
   /* $_SESSION[$gsSesVar].="        
        from liqdefaults  d inner join v_auxgeneral p on des_Productor=vau_codauxiliar
        where des_semana=".fGetParam("pSemana", 0)." /and {filter}/
        group by des_Semana,vau_descripcion,des_Productor";*/
    /*order by  {sort} {dir}
    LIMIT {start}, {limit}";*/
    
    //echo $_SESSION[$gsSesVar];
    // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "LiLiLi_panelLiquidaRubro";
    
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
                    return ((v === 0 || v > 1) ? '(' + v +' Tarjas)' : '(1 TarjaS)');
                }";
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 0);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            
            $goGrid->setFieldOpt('des_Productor', array("header"=>"cod prod", "hidden"=>1, "width"=>10, "locked"=>true));
            $goGrid->setFieldOpt('vau_descripcion', array("header"=>"Productor", "hidden"=>0, "width"=>10, "locked"=>true));
            
            $cols = fColumnas();
            $limite = count($cols);
            $ind = 0;
            while ($ind < $limite){
                //echo $cols[$ind]["rep_CodRubro"]."<br>";
                $codRubro = $cols[$ind]["rep_CodRubro"];
                $nomRubro = str_replace(".","0",str_replace(" ","",$cols[$ind]["rep_TitCorto"]));
                $nombreRubro = $cols[$ind]["rep_TitCorto"];
                $goGrid->setFieldOpt($nomRubro."-".$codRubro, array("header"=>$nombreRubro, "hidden"=>0,
                                        "width"=>10, "renderer"=>"floatPosNeg", "tooltip" => $nombreRubro,
                                        "editor"=>array("type"=>"numberField","renderer"=>"floatPosNeg","config"=>array("allowBlank"=>false,"allowNegative"=>true
                                                    ,"style"=>"text-align:right"))));
                $ind++;
            }
           /* $goGrid->setFieldOpt('codProv', array("header"=>"Cod Prov", "Cod Prov."=>0, "width"=>10, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('aux_nombre', array("header"=>"Proveedor", "hidden"=>0,"width"=>300));
            $goGrid->setFieldOpt('comprobante', array("header"=>"Comprobante", "hidden"=>0,"width"=>20));
            $goGrid->setFieldOpt('concepto', array("header"=>"Concepto", "hidden"=>0,"width"=>200));*/
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}

function fColumnas(){
    $db = Null;
    $db = &ADONewConnection('mysql');
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $db->autoRollback = true;
    $db->debug = fGetParam("pAdoDbg",0);
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
    
    /*$trSql = "select rep_CodRubro,rep_TitCorto 
                from liqreportes where rep_ReporteID=1 and rep_CodRubro<>0 and rep_Activo=1
                and rep_CodRubro in (1,2,4,3,5,15,7,6,12,13,10,9,8,11,14,18,20,19,16)
                order by rep_secuencia";*/
    $trSql = "select rep_CodRubro
	,case when rep_TitCorto='' or rep_TitCorto IS NULL then rep_TitLargo else rep_TitCorto end rep_TitCorto
                from liqreportes where rep_ReporteID = 101  and rep_CodRubro<>0 and rep_Activo=1
                /*and rep_CodRubro in (1,2,4,3,5,15,7,6,12,13,10,9,8,11,14,18,20,19,16)*/
                order by rep_secuencia";
    
    return $db->GetArray($trSql);
}

?>