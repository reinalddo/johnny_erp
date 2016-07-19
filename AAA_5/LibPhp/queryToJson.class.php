<?php
/*
*   Generar un string JSON con informacion de Base de datos.
*	La sentencia Sql, se asigna via Sesion, o get
*   @rev    fah 20/Abr/08   Manejar como parametro GET/POST la bandera de Metadata (meta=true/1)
*	@ToDo:      Modificar, Hacer una funcion Generica, OO
*/
include_once('General.inc.php');
include_once('GenUti.inc.php');
include_once('adodb.inc.php'); # load code common to ADOdb
include_once('adoConn.inc.php'); # load Common conection deffs
include_once('../LibPhp/JSON.php');
$gsSql="";
$giTotalRecs = 0;
$giPageRecs = 0;
//header("Content-Type: text/javascript;  charset=UTF-8");
//header("Content-Type: text/javascript");

/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
class clsQueryToJson {
	var $Sql="";
	var $totalRecs = 0;
	var $pageRecs  = 0;
	var $arrayQry  = NULL;
	var $rs = NULL;
	var $data = array();
	var $rootElem = ""; // if the output must include a root element
	var $idElem = "id"; // name of the id element for every record
	var $statusFlag = true;
	var $sqlID = NULL;
	var $type  ="S"; //		S=SELECT, U=UPDATE, D=DELETE, I=INSERT, R=REPLACE
	var $error = NULL; // error object
	var $metaDataFlag=false;  // false, true, 2
	var $metaData=array();
	var $totalProperty="recordCount";
	var $countFlag = 1;  // 0= No count    1=auto, use qryCount      2= Specific, via session var $_SESSIOn[id .' _count'] 
	var $qryCount = "SELECT FOUND_ROWS() as totalRecs"; //Instruction to obain the total count of records
	/*
	*   Constructor
	*   @param  treeNode    String      name of the root element, no one if false
	*   @param	status      boolean     If the output must include the status data: num of recs, sttaus
	*   @param  id		    string		if of query to apply (overrides $_GET['id')])
	*/
	function clsQueryToJson($treeNode = false, $status=true, $sqlId=false) {
		global $appStatus;
		$this->sqlID=fGetParam('id', $sqlId);
		$this->appStatus = $appStatus ;
		if($treeNode !== true)	if(strlen($treeNode) > 1) $this->rootElem = $treeNode;
		$this->statusFlag = $status;
		$this->init();
	}
	function init(){
	}
	function afterInit(){
	}
	function beforePrepareSql(){
	}
	function prepareSql(){
		global $db;
		if ($this->sqlID) {
			//$alQry=  (isset($_SESSION[$this->sqlID]) && strlen($_SESSION[$this->sqlID] > 1)) ? $_SESSION[$this->sqlID] : false;
			$this->arrayQry=  $_SESSION[$this->sqlID];
			
		}
		else
			$this->arrayQry=  fGetParam('pQry', false) ;
			if (!$this->sqlID || $this->arrayQry === false ) {
				$this->appStatus->setError(false, 'h', '"DEBE DEFINIR UNA IDENTIFICACION DE INSTRUCCION SQL"') ;
		}
		$this->arrayDefs= $_SESSION[$this->sqlID . "_defs"]; //  defaul Values for params (if no defined via GET/POST)
		if (!is_array($this->arrayQry)){
		    $slTmp = $this->arrayQry;
		    unset($this->arrayQry);
			$this->arrayQry[]=$slTmp;
			unset($slTmp);
		}
		$gsSql="";
		$ilInd=0;
		if  ($this->countFlag == 2)	// Query inyected via session
			$this->qryCount = (isset($_SESSION[$this->sqlID .'_count'])?$_SESSION[$this->sqlID .'_count']: false);
		$this->beforePrepareSql();  ///
//print_r($_GET);	
		$slFilters=$this->getFiltersText();
		foreach($this->arrayQry as $slQry){
			$alVars = Array();
			preg_match_all ('/\{[a-z_A-Z0-9]+\}/',  $slQry, $alVars);
			//$ilPos = strpos(strtolower($slQry), "where {filter}");
			if (count( $alVars)>0){
				foreach ($alVars[0] as $slParName) {
				    $slParTag   = $slParName;
				    $slParName  = str_replace("{", "", $slParName);
				    $slParName  = str_replace("}", "", $slParName);
				    $slDefValue = (isset($_SESSION[$this->sqlID . "_defs"][$slParName])?
								  $_SESSION[$this->sqlID . "_defs"][$slParName]:"");
					if ($slParName == 'filter')  {
						$slParValue = $slFilters;
					}
	                else $slParValue = fGetParam($slParName, $slDefValue) ;
			        $slQry = str_replace($slParTag, $slParValue, $slQry);
//echo "<br>par: $slParName val: $slParValue ";
				}
			}
			if ($this->countFlag == 1 &&  stripos($slQry, "SQL_CALC_FOUND_ROWS") !== false ) // add a instruction to calculate the num of records
				$this->$slQry = str_replace($this->$slQry, "SELECT", "SELECT SQL_CALC_FOUND_ROWS" );
			$this->arrayQry[$ilInd]=$slQry;
			$gsSql .= $slQry ;  // the last Query
			$ilInd++;
		}
	}
/*
*
*	@TODO Hacer mas inteligente el proceso para evitar generar true cuando no hay filtros y no es necesario texto de salida
*/
	function getFiltersText(){
		if(!isset($_GET['filter'])) return  " true ";
		$alFilters = $_GET['filter'];
		$slFilter = "";
		if (count($alFilters) > 0 ) {
			foreach ($alFilters AS $k =>$v){
				$slFilter .= ((strlen($slFilter)>0 ) ? " AND " : "") . $v['field'];
				switch($v['data']['type']) {
					case "integer":
					case "int":
						$slFilter .= " = "  . $v['data']['value'];
						break;
					case "boolean":
						$slFilter .= " = "  . $v['data']['value'];
						break;
					case "date":
						$slFilter .= " = '"  . $v['data']['value'] . "'";
						break;
					case "string":
					default:
						$slFilter .= " LIKE '"  . $v['data']['value'] . "%'";
						break;
				}
			}
		}
		else $slFilter = true;
//echo "<br>FILT: $slFilter";	
		return $slFilter;

	}
	function beforeExecuteSql() {
	}
	function executeSql() {
	    global $db, $gsSql;
	    $this->rs= fSQL($db, $this->arrayQry, false);
//obsafe_print_r($this, false, true, 4) 	;					
	}
	function defineMetaData(){
		$ii=0;
		$alMetaData = array();
		foreach($this->rs->fields as $zz){
			$olF=$this->rs->fetchfield($ii++);
			//echo "<br> $ii.- " . $olF->name . "    \t  " . $olF->type . "     " . $olF->primary_key . "    " . $olF->multiple_key;
			switch($olF->type){
				case 'char':
				case 'string':
				case 'varchar':
				case 'C':
				case 'X':
				case 'C2':
				case 'X2':
						$slTipo= "string";
						break;
				case 'B':
				case 'blob':
						$slTipo= "string";
						break;
				case 'int':
				case 'integer':
				case 'smallint':
				case 'tinyint':
				case 'bigint':
				case 'I':
				case 'I1':
				case 'I2':
				case 'I4':
				case 'I8':
				case 'L':
				case 'R':
				case 'autonumeric':
				case 'auto':
						$slTipo= "int";
						break;
				case 'bool':
						$slTipo= "boolean";
						break;
				case 'float':
				case 'bigfloat':
				case 'double':
				case 'decimal':
				case 'numeric':
				case 'real':			
				case 'F':
				case 'N':
						$slTipo= "float";
						break;
				case 'date':
				case 'D':
				case 'T':
							$slTipo= "date";
							break;
				case 'datestamp':
				case 'datetime':
				case 'timestamp':
				case 'time':
							$slTipo= "datetime";
							break;
				case 'timestamp':
				case 'time':
				default:
					$slTipo= "datetime";
					break;
			}
			$alFieldConf[] =     Array(
				'name' => $olF->name,
				'id' => $olF->name,
				'header' => substr($olF->name, 0, 20),
				'width' => $olF->max_length,
				'type' => $slTipo
			);
		}
	$this->metaData = Array(
		'root' => $this->rootElem,
		'id' => $this->idElem, 
		'totalProperty' => $this->totalProperty,        
		'fields' => $alFieldConf);
	}
/*
*
*
*/
	function getMetaData(){
		return $this->metaData();
	}
/*
*   Recorre el recordset para generar cada campo en formato json
*/
	function getData(){
   	global $db;
		$nodes[] = array();
		$this->json = new Services_JSON();
		if ($this->rs){
			$ilCount= 0;
			$this->totalRecs=0;
			$this->pageRecs=0;
			if ($this->statusFlag) {
				$this->data["success"]=true;
				$this->data["totalRecords"]=$db->Affected_Rows();
			}
			if ($this->type == "S"){
				while ($alRec = $this->rs->FetchRow(false)) {
//obsafe_print_r($alRec, false, true, 4) 	;									
					if ($this->beforeRecord($alRec) ){
						foreach ($alRec as $k => $v){
							$olRec->$k = (utf8_encode($v));
						}
						if (strlen($this->rootElem) >= 1) $this->data[$this->rootElem][] = clone $olRec;
						else $this->data[] = clone $olRec;
					}
//$node[] = array('text'=>$f, 'id'=>$node.'/'.$f, 'leaf'=>true/*, 'qtip'=>$qtip, 'qtipTitle'=>$f */, 'cls'=>'file');
//$node[] = $alRec;
//obsafe_print_r($alRec, false, true, 4) 	;
					$this->afterRecord();
					$ilCount++;
				}
//obsafe_print_r($this->data, false, true, 4) 	;				
				if ($this->countFlag != 0) {
					if($rs=$db->execute($this->qryCount)){
						$r=$rs->fetchRow();
						$this->totalRecs=$r['totalRecs'];
					}
				}
			}
		} else {
			$slMensaje = 'CONSULTA MAL DEFINIDA';
		    $this->data["success"]=false;
		    $this->data["message"]=$slMensaje;
			$this->data["error"]=$this->appStatus->getError('t');
		}
//print($this->json->encode($this->data)); echo "<br>--------";
		if ($this->statusFlag) {
				$this->data['totalRecords']=$this->totalRecs;
				$this->data['pageRecords']=$this->pageRecs;
		}
//print($this->json->encode($this->data)); echo "<br>xxxxxx";
		if(isset($_SESSION['pAppDbg']) && $_SESSION['pAppDbg'] >= 99){
				$olErr = new clsError();
				$olErr->errLvl = ($db->ErrorNo() <> 0)? "I" : "E"; // Info or err
				$olErr->errMsg = $db->ErrorMsg();
				$olErr->errNum = $db->ErrorNo();
				$olErr->errSql = $gsSql;
				$this->data['error'] = $olErr;
		}
//echo"dbg  " . $_SESSION['pAppDbg'] ; print_r($this->rs); echo "data:<br>"; print_r($this->data);
	}
/*	
*   Set if the json output must include metadata
*   @param	bool	true /false
*/
	function setMetaDataFlag($param = false){
		$this->metaDataFlag=$param;
	}
	/*
*   Set the name of root element of the response object
*   @param	param	string		Name of the root element
*/
	function setRoot($param = NULL){
	    $this->rootElem = $param;
	}
/*
*   Metodo a ejecutarse antes de recorrer el recordset
*   @param	param	array	Arreglo asociativo con parametros para el metodo
*/
	function beforeGetData($param = NULL){
	}
/*
*   Metodo a ejecutarse despues de recorrer el recordset
*   @param	param	array	Arreglo asociativo con parametros para el metodo
*/
	function afterGetData($param = NULL){
	}
/*
*   Metodo a ejecutarse despues de leer el registro y antes de cargarlo al recorsdet final
*   @param	pRec 	recordset	Registro Actual, recien leido
*   @param	param	array	Arreglo asociativo con parametros para el metodo
*/
	function beforeRecord($pRec, $param = NULL){
		return true;
	}
/*
*   Metodo despues de cargar cada registro  al recorsdet final
*   @param	param	array	Arreglo asociativo con parametros para el metodo
*/
	function afterRecord($param = NULL){
	}
/*
*   Metodo a ejecutarse antes de la salida
*   @param	param	array	Arreglo asociativo con parametros para el metodo
*/
	function beforeOutput($param = NULL){
	}
/*
*	Obtiene el recorset basado en la instruccion SQL, genera tambien la metadata si es necesario
*
**/
	function getRecordset(){
		$this->afterInit();
		$this->prepareSql();
		$this->beforeExecuteSql();
		$this->executeSql();
		if ($this->metaDataFlag) $this->defineMetaData(); // generataes metadata in $this->metaData property
	}
/*
*   Metodo que genera la salida
*
*/
	function getJson(){
		$this->getRecordset();
		$this->beforeGetData();
		$this->getData();
		$this->afterGetData();
		$this->beforeOutput();
		//$this->data->dbg = $_SESSION['pAppDbg'];
		$this->outJson();
	}
	function outJson(){
		if ($this->metaDataFlag == 1) {
			$this->data["metaData"] = $this->metaData;
			$this->data[$this->totalProperty]=$this->totalRecs;
		}
	    print($this->json->encode($this->data));
	}
}
