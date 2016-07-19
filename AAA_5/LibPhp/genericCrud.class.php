<?php
/*
*   Generic CRUD.
*	Prepares and Executes Insert, Update, Delete operations over a defined table in a DB.
*	Generates block of Insert/Replace intructions
*	Provides thefollowing sequence of events: init, beforeDefineSql, defineSql, beforeExecuteSql, executeSql,
*	afterExecuteSql, beforeOutput, output
*	@rev	 fah 31/08/08	Support for multirecord incomming data, by example data from a grid
*							all instances of data are scanned, moved to an "actuatRec" property and processed as if was one set of data
*	@author: fah.
*	@
*	@package: DB Access
*	@version: 1.0
*	@
*/
ob_start();
include_once('General.inc.php');
include_once('GenUti.inc.php');
include_once('adodb.inc.php'); # load code common to ADOdb
include_once('adoConn.inc.php'); # load Common conection deffs
include_once('../LibPhp/JSON.php');
$sqlText="";
$giTotalRecs = 0;
$giPageRecs = 0;
//header("Content-Type: text/javascript;  charset=UTF-8");
//header("Content-Type: text/javascript");

/**
*   Generic class for basic operations for a DB table
*   access  public
*   @tabla   string	Nombre de tabla
*/
class clsGenericCrud {
	/** @var object Database Object*/
	var $rsDb=true;
	/** @var string Table name*/
	var $tabla ="";
	/** @var string sql Instruction*/
	var $sqlText="" ;
	/** @var object Response data*/
	var $respuesta = NULL;
	/** @var array Get/Post Received parameters*/
	var $param = array();
	/** @var string Action to execute : ADD, UPD, DEL */
	var $action	= NULL;
	/** @var array Array of Get/Post received Data */
	var $rcvdData = array();
	/** @var integer Number f records to process */
	var $numRecs=1;
	/** @var string Date format for Incoming records */
	var $dateFmt = "Y-m-d";
	/* @var boolean Type of Insert sentences to be generated: true = bulk "Insert into (....) VALUES (....), (...), ..), \n false =  unitary "Insert into table (..) VALUES (...); insert into table (...) VALUES (...), defaults to true (best performance)"
	*/
	var $bulkInsert=true;
	/* @var current record of incomming data for processing
	*/
	var $actualRec = array();
	/* @var array of incomming data
	*/
	var $inputData = array();

	/** 
	*	Constructor
	*	Defines the tabla name from URL params if not defined by a class initialization parameter (pTabla)
	*	Defines action and numrec parameters from GET/POST
	*	Populates the fields array with data from meta info extracted from DB
	*/
	function clsGenericCrud($pTabla=false){
		global $db;
		$this->db = $db;
		if(!$pTabla) $this->tabla = fGetParam('pTabla', NULL);
		else $this->tabla = $pTabla;
		$this->action = fGetParam('pAction', NULL);
		$this->numRecs = fGetParam('pNumRecs', 1);
		$this->init();
		if (!$this->tabla || strlen($this->tabla) < 1) {
			$this->fail("TABLE NO DEFINED!!");
		}
		if (!$this->action || strlen($this->action) < 1) {
			$this->fail("ACTION NO DEFINED!!");
		}
		$this->fields = $db->MetaColumns($this->tabla, true);
		array_walk($this->fields, 'fUpperCaseNames');
		$this->loadParams();
	}
	/*
	*	Initialization
	*	@abstract 
	*/
	function init(){}
	/*
	*	Load all Get and Post Params. Identifying the record data (based in this->fields array) and assigning to fields structure.
	*	Note: if Get or POSt  come with arrays of data, all values are asigned to fields[]->value property and populates inputData property
	*	The fields array have uppercased keys, params array have lowercased.
	*/
	function loadParams(){
		foreach($_GET  as $k => $v){
			$ku=strtoupper($k);
			if(!$this->readFields($ku, $v, $idx)) {
				$this->params[$k] = $v;
			}
		}
		foreach($_POST  as $k => $v){
			$ku=strtoupper($k);
			if(!$this->readFields($ku, $v, $idx)) {
				$this->params[$k] = $v;
			}
		}
	}
/*
 *	Take a pair Key - value from the param, check against fields and populates the resulting 'inputData ' records array.
 *	It manages two options of incomiing data: some arrays or data for every field or a unique record referenced by various unitary fields:
 *	  fld1[0]=aaaa&fld1[1]=bbbbb&fld2[2]=cccc&fld1[1]=2333&fld1[5]=777
 *	  fld1=1111&fl2=ccc&fld3=888
 *	@param	variant $k		Key of the get/post param received
 *	@param  variant $v  	Value from the get/post param received
 *	@param  variant $idx  	position in the inputData array to populate
 *	@return boolean true if the processed pair is a field oyherwise false
 **/
	function readFields($k, $v, $idx)	{
		if (array_key_exists($k, $this->fields)){
			$this->fields[$k]->value = $v;
			$this->fields[$k]->name = $k;
			if (is_array($v) ) {
				$this->numRecs =  count($v);
				foreach ($v as $k1 => $v1){
					$this->inputData[$k1][$k] = $v1;
					//$idx++;
				}
			}
			else $this->inputData[$idx][$k] = $v1;
			return true;
		}
		return false;
	}
	/*
	*	Get a loaded Parameter
	*	@param	 string 	$pName	Name of the var to return
	*/
	function getParam($pName){
		if (isset($this->params[$pName]))	return $this->params[$pName];
		else return '@@noDef@';
	}
	/*
	*	Get a loaded value
	*	@param	 string 	$pName	Name of the datafield to return
	*	@param	 integer	$pIdx	If exists an array of values, it is the pointer inside it. 
	*/
	function getData($pName, $pIdx=false){
		$slName=strtoupper($pName);
		if (isset( $this->fields[$slName]) && isset($this->fields[$slName]->value))
			if (is_array($this->fields[$slName]->value))
				if (isset($this->fields[$slName]->value[$pIdx])) return $this->fields[$slName]->value[$pIdx];
				else return '@@noDef@';
			else return $this->fields[$slName]->value;
		else return '@@noDef@';
	}	
	/*
	*	Returns a fail string
	*/
	function fail($pMsg,  $pErr=false){
		if ($pErr === false){
			echo "{success:'false', msg: '$pMsg', err:$pErr}";
		}
		else echo "{success:'false', msg: '$pMsg}'";
		die();
	}
	/*
	*	Sql Execution. Support two lines of execution : Bulk insert / replace, for one sql sentence and one execution call and non-bulk for
	*	generating  a sql sentence and  a execution call for every record received by get/post param ( ex. a grid).
	*/
	function execute(){
        if(!$this->beforeDefineSql()) {
            $this->fail("CANCELADO");
            return true;
        }
        $blBulk = false;
        $blBulk = (($this->action == "ADD" || $this->action == "REP") && $this->bulkInsert) ? true : false;
        if ($blBulk)  {        // A bulk Insert / Replace
                if ($this->defineSql()){
                    if($this->beforeExecuteSql()){
                        if($this->executeSql()){
                            if($this->afterExecuteSql()){
                                $this->beforeOutput();
                                return true;						
                            }
                        }
                    }
                }
        }
        else {   // A sql for every record 
            $this->defineSql();
            $this->beforeOutput();
            return true;						
        }
		$this->beforeExit();
	}
	/*
	*	Before Sql instruction
	*	@abstract 
	*/
	function beforeDefineSql(){ return true;}
	/*
	*	After Sql Instruction and before Execution
	*	@abstract
	*	@return	 bool		true to Execute next Step
	*/
	function beforeExecuteSql(){ return true;}
	/*
	*	After Sql Execution and before Output Sequence
	*	@abstract
	*	@return	 bool		true to Execute next Step
	*/
	function afterExecuteSql(){ return true;}

	/*
	*	After Sql Execution and before Json Output
	*	@abstract 
	*	@return	 VOID   (always executes the next step)
	*/
	function beforeOutput(){ return true;}
	/*
	*	Before process of every record
	*	@abstract
	*	@param	integer		$pIdx	Index of processing record
	*	@return	 bool		true / false. If yo nedd tou can control the flow of nex actions
	*/
	function beforeRecord($pIdx){ return true;}	
	/*
	*	After process of every record
	*	@abstract 
	*	@param	integer		$pIdx	Index of processed record
	*	@return	 bool		true to process this record, false to skip it
	*/
	function afterRecord($pIdx){ return true;}	
	/*
	*	Before process of every rECORD
	*	@abstract
	*	@param	integer		$pIdx	Index of processing record
	*	@param	integer		$pField	Field name
	*	@param	integer		$pValue	Value
	*/
	function beforeField($pIdx, $pField, $pValue){ return true;}	
	/*
	*	After process of every Field
	*	@abstract
	*	@param	integer		$pIdx	Index of processed record
	*	@param	integer		$pField	Field name
	*	@param	integer		$pValue	Value
	*	@return	 bool		true to process this field, false to skip
	*/
	function afterField($pIdx, $pField, $pValue){ return true;}	
	/*
	*	Sql Execution and set the response object
	*	@return		bool	true. Always true.  TODO: response based on Sql execution 
	*/
	function executeSql(){
		global $db;
		//echo $this->sqlText;		
		$db->Execute($this->sqlText);
		return true;
	}
	/*
	*	Before Finish, after execution of all instructions
	*	@abstract 
	*/
	function beforeExit(){ return true;}
	/*
	*	Set the response object
	*	@return		bool	true. Always true.  TODO: response based on Sql execution 
	*/
	function dbExecutionStatus(){	
		global $db;
		$this->respuesta= NULL;
		$this->respuesta->data = array();
		//print_r($db);
		if ($this->rsDb){
			$this->respuesta->success = true;
			$this->respuesta->message="";
			$this->respuesta->records=$db->Affected_Rows();
			$this->respuesta->lastId =$db->Insert_ID();
		//	echo $this->respuesta->lastId;
			if (isset($_SESSION[$this->tabla . '_bitID']))
				fGrabaBitacora($db, ($db->Insert_ID()?$db->Insert_ID():fGetParam($_SESSION[$this->tabla . '_bitID'], -1)), " ");
			} else {
				$this->respuesta->success = false;
				$this->respuesta->records=0;
				$this->respuesta->lastId =0;
				$this->respuesta->error  = $appStatus->getError('t');
				$this->respuesta->message = $db->ErrorMsg();
				$this->respuesta->sql  = $this->sqlText;
		}
		//print_r($this->respuesta);		
		return true;
	}
	/*
	*	Generation of Json output
	*	@return VOID
	*/
	function output(){
		header("Content-Type: text/javascript");
		$this->dbExecutionStatus();
		if (fGetParam('pAppDbg') == 2) {
			$this->respuesta->sql  = $sqlText;
		//    $this->respuesta->status  = $appStatus;
			$this->respuesta->message  = $appStatus->getError('t');
		}
		$json = new Services_JSON();
		//		echo "PAR: " ; print_r($json->decode($_POST['par']));
		print($json->encode($this->respuesta));
	}
	/*
	*	Definition of Sql instruction based on table metadata
	*	@return	 bool		true to Execute next Step
	*/
	function defineSql(){
		global $db, $appStatus;
		$slSql = "";
		$slDefs= isset($_SESSION[$this->tabla . '_defs']) ? $_SESSION[$this->tabla . '_defs']: array();
		//print_r($_GET);
		//print_r($_POST);
		//print_r($this->inputData);
		//$slTipo = $db->MetaType($olField->type);
		$il = 0;
		$id =0;
		$ki= $this->numRecs-1;
		foreach  ($this->inputData as $this->actualRec){ //->  For every Record
			if (!$this->beforeRecord($il)) continue;
			$this->action = isset($this->params['_newFlag'][$il]) ? ($this->params['_newFlag'][$il]? "ADD":"UPD") : (isset($this->params['pAction'])?$this->params['pAction']:"UPD");
			//echo "\nACCION: " . $slAction . "---" ; print_r($this->actualRec);
			switch ($this->action){
				case "REP":
				case "ADD":
					list($slFields, $slValues) = $this->setInsertSql();
					$slSql = (($this->action=="ADD") ? " INSERT " : " REPLACE " ) . " INTO $this->tabla (" . $slFields . ") VALUES (" . $slValues . ")" ;
					$this->sqlText = $slSql;				
					if (!$this->bulkInsert){
						if($this->beforeExecuteSql()) $this->executeSql();
						$slValues = "";
						$slFields = "";
						$this->sqlText = "";
					}
					break;
				case "UPD":
					$this->sqlText = $this->setUpdateSql();
					break;
				case "DEL":
					$this->sqlText = $this->setDeleteSql();
					break;
				default:
					return false;
			} // eo switch
//echo "1<br>";
			if ($this->bulkInsert or strlen($this->sqlText) > 0){
				if($this->beforeExecuteSql()) {
					$this->executeSql();
//echo "2 $this->sqlText <br>";
				}
				$this->afterExecuteSql();
			}
			$this->afterRecord($il);
			$il++;
		} // eo foreach
		return true;
	}
	/*
	 *	Sets sqltext property based on data and type of action
	 *	@return VOID
	 */
	function setInsertSql(){
		$slMultiVal="(";
		$slFields = "";
		$il = 0;
		$id =0;
		$ki= $this->numRecs-1;
		foreach ($this->fields as $k => $olField) {	//-> every Field
			$dbgTxt =  " : " . $olField->type . " / " . $olField->value . "\n\r";
			fSetAppStat(10, 'I', $dbgTxt);
			if ($this->action == "ADD" && ($olField->auto_increment || (($olField->type == 'datestamp' || $olField->type == 'timestamp' ) ))) {  
				continue;
			} else {
				//echo "\n tipo: " . $olField->name . " / " . $olField->type . " -- " . $slTipo ."\n\r"; print_r($slTipo);
				//echo $slDefs[$olField->name];
				if (isset($slDefs[$olField->name])) {
					if (substr($slDefs[$olField->name],0,1) == "@") {
						$slValue =eval(substr($slDefs[$olField->name],1));
					} else {
						$slValue =$slDefs[$olField->name];
					}
				} else $slValue = $this->actualRec[$olField->name];   // $this->getData($olField->name);
				fSetAppStat(10, 'I', $dbgTxt);
				//$slTxt =  " CAMP: $olField->name : " . $olField->type . " / " . $slValue . "\n\r";
				//echo "<br> " . $slTxt;
				if(!$this->beforeField($i, $olField, $slValue)) continue;
				if ($slValue !='@@noDef@' and strlen($slValue) > 0 ) {
					$slValue = $this->parseValue($olField->type, $slValue);
					if ($this->afterField($i, $olField, $slValue)){
						$slValues .= ((strlen ($slValues)>0) ? "," : "") . $slValue;
						if ($i ==0) $slFields .= ((strlen ($slFields)   >0) ? "," : "") . " " . $olField->name; // first time, prepare fields def for sql
					}
				}
			}
			//echo $slSql ."\n";
			$il ++;
		} // <- eo Field
		return (array($slFields, $slValues));
	}
	/*
	 *	Sets sqltext property based on data and type of action
	 *	@return VOID
	 */
	function setUpdateSql(){
		$slValues = "";
		$il = 0;
		foreach ($this->fields as $olField ){
			if (!$olField->primary_key  && isset($this->actualRec[$olField->name])) {
				$slValue = $this->actualRec[$olField->name];
				if ($slValue !='@@noDef@' ) {
						$slValue = $this->parseValue($olField->type, $slValue);
						$slSql 	  .= ((strlen ($slSql) >0) ? "," : "") . " " . $olField->name . " = " . $slValue ;
					$il ++;
				}
			}
		}
		
		$slCond = $this->primaryKeyString($this->fields);
		return  "UPDATE $this->tabla SET " . $slSql . " WHERE  " . $slCond ;
	}
	/*
	 *	Sets sqltext property based on data and type of action
	 *	@return VOID
	 */
	function setDeleteSql(){
		$slValues = "";
		$slCOnd="";
		for ($i=0; $i< $this->numRecs; $i++){
			$slCond = "(" . $this->primaryKeyString($this->fields, $i) . ") ";
			if ($this->numRecs >1 && $i < $this->numRecs -1 ) $slCond .= " OR ";
		} 
		return "DELETE FROM $this->tabla WHERE (". $slCond . ") ";
		
	}
	/*
	*	Definiton of Primary Key fields: Creates a string with the key fields and his associated values
	*	@param	object	$pFlds	fields metadata
	*	@param 	integer $pIdx	Index for record array
	*	@return	 string Primary Key definition for a Sql Update - Delete
	*/
	function primaryKeyString($pFlds, $pIdx=0 ){
		$slKeyString = "";
        //print_r($this->actualRec);
		foreach ($pFlds as $k => $olField){
			if ($olField->primary_key ){
                //echo $olField->name . " : " .$olField->primary_key . " / " . $this->actualRec[$olField->name];            
				$slValue = (isset($this->actualRec[$olField->name])) ? $this->actualRec[$olField->name] : "-" ;//isset($this->param[$olField->name])? $this->param[$olField->name] : "@noDef@";
				if ($slValue !='@@noDef@' ) {
					$slKeyString .= ((strlen($slKeyString) >0) ? ' AND ' : ' ')  . $olField->name . ' = ' . $this->parseValue($olField->type, $slValue);
				}
			}
		}
		return (strlen($slKeyString)? $slKeyString : false);
	}
	/*
	*	Define the raw value of a field
	*	@param string	$pType		Data type to process
	*	@param variant	$pValue		data value
	*/
	function parseValue($pType, $pValue){
		global $db, $appStatus;
		$slTxt =  " : " . $pType . " / " . $pValue . "\n\r";
		fSetAppStat(10, 'I', $slTxt);
		if(fGetParam('pAppDbg', false))  echo " - " . $pType . " : " . $pValue . " ----<br> " ;
		switch($pType){
			case 'char':
			case 'varchar':
			case 'C':
			case 'X':
			case 'C2':
			case 'X2':
					$pValue = "'" . addSlashes($pValue) . "'";
					break;
			case 'B':
			case 'blob':
					$pValue = "'" . $pValue . "'";
					break;
			case 'int':
			case 'integer':
			case 'smallint':
			case 'tinyint':
			case 'bigint':
			case 'bool':
			case 'I':
			case 'I1':
			case 'I2':
			case 'I4':
			case 'I8':
			case 'L':
				if (is_null($pValue)) $pValue = 0;
					else $pValue = intval($pValue);
				break;
			case 'R':
			case 'autonumeric':
			case 'auto':
				if (!is_null($pValue))
					$pValue = intval($pValue);
					//if ($pValue != intval($pValue)) $pValue = 0 ;
				break;
			case 'float':
			case 'bigfloat':
			case 'double':
			case 'decimal':
			case 'numeric':
			case 'F':
			case 'N':
				if (!is_numeric($pValue)) $pValue  = 0;
				break;
			case 'date':
			case 'D':
			case 'T':
				$slFmt= ereg_replace("[-/]", "", $this->dateFmt);
				//echo "<br>pre fecha: " . $pValue . " $this->dateFmt  $slFmt" ;
				if (strlen($pValue) > 4) $pValue = "'" . date('Y-m-d', str2date($pValue, $slFmt)) . "'";
				else $pValue = "'0000-00-00'";
				//echo "CALCULADO: " . str2date($pValue, $slFmt) . " <br> ";
				//echo "fecha Final-----: " . $pValue;	
				break;
			case 'datestamp':
			case 'datetime':
				list($slFech, $slHora) = split(' ',$pValue);
				if (strlen($pValue) > 4)
					$pValue = "'" . fText2Fecha($slFech,'Y-m-d', 'd/M/Y') . ' '. $slHora . "'";
				else $pValue = "'0000-00-00 00:00:00'";
				break;
			case 'timestamp':
			case 'time':
				if (strlen($pValue) > 4) $pValue = "'" . $pValue ."'";
				else $pValue = "'00:00'";
				break;
			default:
				$pValue = "'" . $pValue . "'";
		}
		if(fGetParam('pAppDbg'))  echo " convertido a : " . $pValue . "\n";
		return $pValue;
	}
	/*
	*	Saves a log of transactions
	*/
	function saveLog(&$db, $pID, $pTxt=' ', $pDat){
		global $tabla;
		if (is_array($pDat)) {
			foreach($pDat as $k->$v){
				$slF .= $k;
				$slV .= "'". addslashes($v) . "'";
			}
		}
		$slF .= ", bit_IDusuario";
		$slV .= ",'" . addslashes($_SESSION['g_user'])  . "'";
		$slSql = "INSERT INTO segbitacora ( $slF ) VALUES ( $slV )";
		$db->Execute($slSql);
		return $db->Insert_ID();
	//echo $slSql;
	}

}
function fUpperCaseNames(& $pFld, $pVal){
	$pFld->name=strtoupper($pFld->name);
}

/*
*
*/
/*
*	Sets the aplication statuts
*
*/
function fSetAppStat($pStat, $pType, $pMens="", $pCode =0, $pExpl=""){
	global $appStatus;
    $appStatus->setError($pStat, $pType, $pMens, $pCode , $pExpl);
}
?>
