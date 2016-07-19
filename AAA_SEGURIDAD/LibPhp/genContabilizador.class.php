<?php
/**		Contabilizacion de una transaccion GENERICA aplicando plantilla   @@@@TODO : Todo, ajustar y simplificar este proceso
 *		Genralizacion de InAdPr_contabapli.pro.php.
 *		Procesa:
 *		* Un comprobante especifico (pReg Recibiendo com_regnumero como parametro)
 *		* Un rango de comprobantes
 *	@param int 	$pReg	com_Regnumero del comprobante
 **/
//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
//include_once("../LibPhp/showArray.php");
/**
*   @class
*
**/
class clsContabilizador {
	/**
	 *@var array contabMovs		Detalle de movimientos listos para grabar
	 */
	var $contabMovs	= array();
	/**
	 *@var object $db		Conexion BD
	 */
	var $db 		= null;
	/**
	 *@var array $movBase	record basico para agregar al arreglo de movimientos
	 */
	var $movBase 	= null;
	/**
	 *@var object $rec	record actual, datos en proceso
	 */
	var $record		= null;
	/**
	 *@var integer secActual Secuencia actual de movimiento
	 */
	var $secActual;
	/**
	 *@var string $lastId	 Ultimo comprobanet procesado
	 */
	var $lastId = null;
	/**
	 *@var string	$idField	Nombre de campo que identifica cada comprobante (id)
	 */
	var $idField = null;
	/**
	 *@var array	$fields			Arreglo con la informacion de campos de tabla condetalle
	 */
	var $fields = null;
	/**
	 *@var string	$w			Espacio para variables de trbajo
	 */
	var $w = null;
	/**
	 *@var string $dateFmt		Formato de fechas en BD
	 */
	var $dateFmt="Y-m-d";
	/*+*******************************************************/
	/**
	 * Constructor
	 * @param string $pSql	Instruccion SQL que genera datos
	 * @param string $pIdField Nombre de campo que identifica cada comprobante
	 */
	function __construct($pSql, $pIdField="id"){
		$this->sql = $pSql;
		$this->idField = $pIdField;
		if (!$this->db){
			$this->db = NewADOConnection(DATOS_CON);
			if (!$this->db) {
				throw New Exception("CONEXION A LA BASE DE DATOS INCORRECTA <BR>Por favor notifique al Dpto de Soporte. <br>", 100);
				return array("success"=> false, "msg" =>"CONEXION A LA BASE DE DATOS INCORRECTA");
			};
		}
		$this->db->SetFetchMode(ADODB_FETCH_ASSOC);
		$this->fields = array_change_key_case($this->db->MetaColumns("condetalle"));
		$this->init();

	}
	/**
	 *	Metodo para configuraciones iniciales
	 */
	function init(){}
	/**
	 * Acciones de preparacion
	 * @param string $pSql	Instruccion SQL que genera datos
	 */
	function prepareSql(){

	}
	/**
	 * Ejecucion del proceso
	 * @param string $pSql	Instruccion SQL que genera datos
	 */
	function ejecutar(){
		$this->dbgdata("<br> Paso a" );
		$rs = $this->db->execute($this->sql);
		$this->dbgdata("<br> Paso b" );
		if (!$rs) echo '',"*** NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum;
		$rs->MoveFirst();
		if(!$this->beforeEjecutar()){
			return false;
		}
		$this->secActual=1;
		$this->dbgdata("<br> Paso c" );
		$slId = $this->idField;
		//echo "<br>idfld:" . $slId;
		//$this->lastId = "ccc";
		while (!$rs->EOF) {
			$this->dbgdata("<br> Paso d ----------------- " );
			$this->record = $rs->FetchNextObject(false);
		//echo "<br> <br> ID : /" . $this->lastId  . "//  " . $this->record->$slId . " / " . $this->record->det_secuencia .  " / ". $this->record->ITE . "<br>" ;// print_r( $this->record);
		//echo "empty id: " . empty($this->lastId);
			if ($this->record->$slId != $this->lastId )  {
				$this->procesarNuevoComp();
			}
			$this->dbgdata("<br> Paso j" );
			$this->beforeRecord();
			$this->acumular();
			$this->aplicarPlantilla();
		}
		$this->grabarComprob(); 			// Grabar el ultimo comprobante
	}

	function beforeEjecutar(){
		$this->tasas = array();
		$this->acum['tapa'] = 0;
		$this->acum['fond'] = 0;
		return true;
	}

	function procesarNuevoComp(){
		$this->dbgdata("<br> Paso e " );
		if (!empty($this->lastId )) {
			$this->dbgdata("<br> PASOo f ++++++++++++++++++++++++" . strlen($this->lastId));
			$this->grabarComprob();
			$this->contabMovs = array();
		}
		$this->primerDetalle() ;//                                En el primer registro de detalle
		$this->dbgdata("<br> Paso g" );
		$this->definirPlantilla($this->record->com_tipocomp, $this->record->com_libro);

		$alTasas=$this->traerTasa($db, $this->record->com_tsaimpuestos);
		$this->encerarAcumuladores();
		$this->onNuevoComprob();
		$slId = $this->idField;
		$this->lastId = $this->record->$slId;
	}

	function limpiarComprob(){
		$this->db->Execute("delete from condetalle WHERE det_regnumero = " . $this->lastId );
	}
	/**
	 * Inicializacion de Datos correspondientes a cada comprobante. Reinicia secuencia = 1
	 * @param
	 */
	function primerDetalle(){
		$this->movBase = array(
			'det_regnumero' => 	$this->record->com_regnumero,
			'det_tipocomp' => 	$this->record->com_tipocomp,
			'det_numcomp'  => 	$this->record->com_numcomp,
			'det_secuencia'=> 	0,
			'det_clasregistro' 	=> 0,
			'det_idauxiliar'=> 	0,
			'det_valdebito' =>	 0,
			'det_valcredito'=>	0,
			'det_glosa' => '',
			'det_estejecucion' 	=> 0,
			'det_fecejecucion' 	=> '2020-12-31',
			'det_estlibros'  	=> 0,
			'det_feclibros' 	=> '2020-12-31',
			'det_refoperativa' 	=> $this->record->com_refoperat,
			'det_numcheque' 	=> $this->record->com_numcomp,
			'det_feccheque' 	=> 0,
			'det_codcuenta' 	=> '' ) ;
			//if ($this->record->cla_ImpFlag) //                        Se aplica impuestos a la transaccion
			$this->secActual = 1;

	}
	/**
	 * Devuelve un arreglo con la estructura de la plantilla correspondiente
	 * @param string 	$pTipo		Tipo de transaccion
	 * @param integer	$pLibr		Libro contable a aplicar
	 */
	function definirPlantilla($pTipo, $pLibr){
		$this->dbgdata("<br> def plantilla" );
		$slSql="SELECT pla_Secuencia,
			pla_ClaseReg,
			pla_IndDetalle,
			pla_IndDbCr,
			pla_Variable,
			pla_Cuenta,
			pla_SufijoCta,
			pla_ClaseAuxil,
			pla_Observac,
			pla_Glosa,
			pla_AuxilDef
		FROM
			conplantillas
		WHERE
			pla_TipoTrans = '" . $pTipo . "'
			and pla_Variante = " . $pLibr . "
			and pla_estado= 1
		ORDER BY pla_secuencia
		";
		$rsp = $this->db->Execute($slSql);
		$cnt = 0;
		$this->plantilla =Array();
		$this->dbgdata("<br> Paso d2" );
		$this->plantilla = $this->db->GetArray($slSql);
//html_show_array($this->plantilla); // @@dbg
	}

	function aplicarPlantilla(){
			foreach($this->plantilla AS $alRec){ 				// --------------------  Procesos aplicados al detalle
				$this->dbgdata("<br> Paso f2 ". $alRec["pla_Variable"]);
				$this->secActual++;

				$alVarDat = (array) $this->record;
				$this->beforeAplicarPlantilla();

				$this->movBase['det_secuencia']  = ($alRec['pla_IndDetalle'] == 5 ? 0 : $this->secActual);
				$alVarDat = array_merge($alVarDat, $this->w); 		// Mezcla datos de Registro + variables de trabajo antes de aplicar patrones
				$this->movBase['det_valdebito']  = 0;
				$this->movBase['det_valcredito'] = 0;
				$flValor = $alVarDat[$alRec["pla_Variable"]];
				if ($alRec["pla_IndDbCr"] > 0){
					$this->movBase['det_valdebito']  = ($flValor <0?0:$flValor);
					$this->movBase['det_valcredito'] = ($flValor <0?abs($flValor):0);
					$this->dbgdata("  DB". $alRec["pla_Variable"]);
				}
				else {
					$this->movBase['det_valdebito']  = ($flValor <0?abs($flValor):0);
					$this->movBase['det_valcredito'] = ($flValor <0?0:$flValor);
					$this->dbgdata("  CR". $alRec["pla_Variable"]);
				}
				$this->movBase['det_codcuenta'] = $this->aplicarPatron('/\{[A-Z_a-z0-9]+\}/ix', $alRec["pla_Cuenta"], $alVarDat);;
				$this->movBase['det_idauxiliar'] = (strlen($alRec["pla_ClaseAuxil"]) > 0)? $alVarDat[$alRec["pla_ClaseAuxil"]]: $alRec["pla_AuxilDef"];
				$this->movBase['det_clasregistro'] = 1;
				if (strlen($alRec["pla_Glosa"]) < 1) { 			// Si NO existe un patron para glosa definir un degenerico
					$alRec["pla_Glosa"] = "{det_cantequivale} {uni_abreviatura} a $ {cosUni}";

				}
				$this->movBase['det_glosa'] = $this->aplicarPatron('/\{[A-Z_a-z0-9]+\}/ix', $alRec["pla_Glosa"], $alVarDat);
				$this->dbgData("<br> apliPla : cuenta " . $this->movBase['det_codcuenta'] . " /" . $alRec["pla_Cuenta"] . " / var auxi:" . $alRec["pla_ClaseAuxil"] . " / aux apli: " . $alVarDat[$alRec["pla_ClaseAuxil"]]);
				$this->agregarMov();
			}
//die("suspendido!!!!!!");
//html_show_array($this->contabMovs);
	}
	/*
	 *	Devuelve una cadena de texto ala cual se le ha plicado  una sustitucion de patrones
	 *	@param	string	$pPatr	Patron a buscar
	 *	@param	string	$pText	Texto original
	 *	@param	array	$pVars	Arreglo de Variables a reemplazar
	 *	@returns  string	pText modificado por los valores de pVars segun pPatr
	 *
	 **/
	function aplicarPatron($pPatr, $pText, $pVars){
	//echo "<br><br><br>PATRON : " . $pPatr . " / " .  $pText ;
	//echo "<br> ";
	//obsafe_print_r($pVars);
		$slCodCue="";
		if(preg_match_all($pPatr, $pText, $alVars)){
			foreach($alVars AS $slVar){ // resolver variables en el patron
	//echo "<br>------------<br>patr: $pPatr <br>"  ; print_r($slVar);
	//echo "<br>------------<br>variables: " ; print_r($pVars);
				if (is_array($slVar)){
					foreach($slVar AS $kk=> $slVar2) {
	//echo "<br>------------<br>var2: " ; print_r($slVar2);
						$alk=array("{","}");
						$slVar2=str_replace($alk, "",$slVar2);
	//echo "<br>------------<br>var2-2: str_resplace($slVar2 " . $pVars[$slVar2] .  " -- " . $pText .")" ; fpr($slVar2);
						$pText = str_replace("{".$slVar2."}", $pVars[$slVar2], $pText);
	//echo " reempl:   " . $slVar2. " / " . $slCodCue;
					}
					$slCodCue = $pText;
				} else {
					$alk=array("{","}");
					$slVar=str_replace($alk, "",$slVar);
					$slCodCue = str_replace($slVar, $pVars[$slVar], $pText);
				}
			}
		}
		if (!is_array($alVars) || (is_array($alVars) && is_array($alVars[0]) && count($alVars[0]) <1)) return $pText;
	//echo  " //// " . $slCodCue;
		return $slCodCue;
	}
	/*
	*	Define the raw value of a field
	*	@param string	$pType		Data type to process
	*	@param variant	$pValue		data value
	*/
	function parseValue($pType, $pValue){
		global $db, $appStatus;
		$slTxt =  " : " . $pType . " / " . $pValue . "\n\r";
		if($this->db->debug)  echo "tipo- " . $pType . " : valor " . $pValue . " ----  " ;
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
				if($this->db->debug) echo "  // pre fecha: " . $pValue . " * $this->dateFmt * $slFmt" ;
				//$slFmtTokens = explode("-", $pValue);
				//if(strlen($slFmtTokens[0]) == 4) $slFmtIn = "Y-m-d";
				//else $slFmtIn = "d-m-y";
				//$pValue = str_replace("-","/",$pValue);

				if (strlen($pValue) > 4) $pValue = "'" . date('Y-m-d', str2date($pValue, $this->dateFmt)) . "'";
				else $pValue = "'0000-00-00'";
				if($this->db->debug){
					 echo "CALCULADO: " . str2date($pValue, $this->dateFmt) . "  ";
					 echo "fecha Final-----: " . $pValue;
				}
				break;
			case 'datestamp':
			case 'datetime':
			case 'timestamp':
			   if($this->db->debug) echo " // pre fecha: " . $pValue . " $this->dateFmt  $slFmt" ;
				list($slFech, $slHora) = split(' ',$pValue);
				if (strlen($slFech) < 4) $slFech = "0000-00-00";
				if (strlen($slHora) < 4) $slHora = "00:00:00";
				if($this->db->debug) echo "\n<br>separado: " . $slFech . "  /  $slHora " ;
				if (strlen($pValue) > 4) {
				  if($this->db->debug){
					 echo "Convirtiendo-----: " . $slFech . " expr : " . date($this->db->fmtDate, str2date($slFech, $this->datefmt));
				  }
				  //$pValue = "'" . fText2Fecha($slFech,'Y-m-d', $this->dateFmt) . ' '. $slHora . "'";
				  $pValue =  str_replace("'", "", date($this->db->fmtDate, str2date($slFech, $this->dateFmt))) ;
				  $pValue = "'" . $pValue . ' '. $slHora . "'";
				}
				else $pValue = "'0000-00-00 00:00:00'";
				if($this->db->debug){
					 echo "fecha Final-----: " . $pValue;
				}
				break;
			case 'timestamp':
			case 'time':
				if (strlen($pValue) > 4) $pValue = "'" . $pValue ."'";
				else $pValue = "'00:00'";
				break;
			default:
				$pValue = "'" . $pValue . "'";
		}
		if($this->db->debug)  echo " convertido a : " . $pValue . "  ";
		return $pValue;
	}

	function grabarComprob(){
		if(!$this->beforeGrabarComprob()) return;
		$this->limpiarComprob();
		$ilSecue=1;
//print_r($this->fields);
		if ($this->debug) {echo "<br> Antes de grabar "; html_show_array($this->contabMovs);}
		foreach($this->contabMovs as $cue => $ca){ // MOvimientos ca cada cuenta
			foreach($ca as $aux => $aa){			// MOvs de cada cuanta y auxiliar
				foreach($aa as $sec => $sa){		// mov de cuenta auxiliar y secuencia
					$ilSecue++;
					$slInsSql = "INSERT INTO condetalle(det_secuencia, det_codcuenta, det_idauxiliar" ;
					$slInsDat =
						//$sa['det_regnumero'] . "," .
						$ilSecue  . "," .
						"'". $cue . "'," .
						 $aux
						;
					//unset($sa['det_regnumero']);
					//unset($sa['det_codcuenta']);
					//unset($sa['det_secuencia']);
					//unset($sa['det_idauxiliar']);
					foreach($sa as $k => $v){
						if ($this->db->debug) echo "<br>" . $k. "  " ;
						$olField = $this->fields[$k];
						$slValue = $this->parseValue($olField->type, $v);
						$slInsDat .= ((strlen ($slInsDat)>0) ? "," : "") . $slValue;
						$slInsSql .= ((strlen ($slInsSql)>0) ? "," : "") . " " . $olField->name; // first time, prepare fields def for sql
					}
					$this->db->execute($slInsSql . ") VALUES (" . $slInsDat . ")" );
				}
			}
		}
		$this->afterGrabarComprob();
		$this->encerarAcumuladores();
//die("CANCELADO!!!!!!!!!!!");
	}

	function agregarMov(){
	//echo "<br>agrMo 1<br>"; //// @@dbg
		if($this->beforeAgregarMov()){

			$ilCue = $this->movBase['det_codcuenta'];
			$ilAux = $this->movBase['det_idauxiliar'];
			$ilSec = $this->movBase['det_secuencia'];
			//unset($this->movBase['det_codcuenta']);
			//unset($this->movBase['det_idauxiliar']);
			//unset($this->movBase['det_secuencia']);
//echo "<br> "; print_r($this->movBase) ; //// @@dbg
//echo "<br> agrMo 5 -" . $ilCue . "*" . $ilAux . "/" . $ilSec; //// @@dbg
			if (!isset($this->contabMovs[$ilCue]) ){
				$this->contabMovs[$ilCue] = array();
			}
			if (!isset($this->contabMovs[$ilCue][$ilAux] )){
				$this->contabMovs[$ilCue][$ilAux] = array();
			}
			if (!isset($this->contabMovs[$ilCue][$ilAux][$ilSec] )){
				$this->contabMovs[$ilCue][$ilAux][$ilSec] = $this->movBase;
			}
			else{
				$this->contabMovs[$ilCue][$ilAux][$ilSec]['det_valdebito'] += $this->movBase['det_valdebito'];
				$this->contabMovs[$ilCue][$ilAux][$ilSec]['det_valcredito'] += $this->movBase['det_valcredito'];
			}
			unset($this->contabMovs[$ilCue][$ilAux][$ilSec]['det_codcuenta']);
			unset($this->contabMovs[$ilCue][$ilAux][$ilSec]['det_idauxiliar']);
			unset($this->contabMovs[$ilCue][$ilAux][$ilSec]['det_secuencia']);
		}
		$this->afterAgregarMov();
	}

	/**
	*   Obtener la tasa de impuestos
	*   @param  pTasa       Int     Id de la tasa de impuestos a  aplicar
	*   @descr  retorna un arreglo con la informacion de la tasa registrada en la transaccion, donde el rubro :
	*           1= Iva
	*           2= Ice
	*           Si hay que añadir otros, se indicara en este arreglo.
	**/
	function traerTasa($pTasa=1) {
		if ($pTasa == 0) $pTasa=1;
		$slSql = "SELECT tsd_Rubro as 'RUBRO', tsd_Secuencia, tsd_porcentajeBI AS 'TASA'
					FROM gentasacabecera  LEFT JOIN  gentasadetalle  ON tsd_id = tsc_id
					WHERE tsc_id = " . $pTasa . " AND tsd_Rubro > 0  ORDER BY tsd_Rubro, tsd_Secuencia";
		$rst = $this->db->Execute($slSql);
		$aTasas = array();
		while (!$rst->EOF) {
				$rec = $rst->FetchNextObject(); //          datos del tipo de transaccion
				switch ($rec->RUBRO) {
					Case 1:
						$aTasas['iva'] = $rec->TASA;
						break;
					Case 2:
						$aTasas['ice'] = $rec->TASA;
						break;
					Case 3:
						$aTasas['otr'] = $rec->TASA;
						break;
				}
		}
		if (is_array($aTasas)) {
			return $aTasas;
		}
		else return false;
	}

	function dbgData($pInfo){
		if ($this->debug){
			print_r($pInfo);
		}
	}

	/**
	 * @method void onNuevoComprob() Evento ejecutado en cada nuevo comprobante.
	 * @param
	 */
	function onNuevoComprob(){}
	/**
	 * @method void beforeAgregarMov() Evento ejecutado antes de pasar el elemento Base al arreglo de movimientos contables
	 * @param
	 */
	function beforeAgregarMov(){return true;}
	/**
	 * @method void beforeAplicarPlantilla() Evento virtual ejecutado antes de aplicar la plantilla al registro actual
	 * @param
	 */
	function beforeAplicarPlantilla(){ return true;}
	/**
	 * @method void encerarAcumuladores() Para encerar acumuladores
	 * @param
	 */
	function encerarAcumuladores(){}
 	/**
	 * @method void acumular() Evento ejecutado durante el procesamiento de un registro, para acumular valores
	 * @param
	 */
	function acumular(){}
	/**
	 * @method void beforeRecord() Evento virtual ejecutado antes de procesar un registro
	 * @param
	 */
	function beforeRecord(){}
	/**
	 * @method void beforeGrabarComprob() Evento virtual ejecutado antes de Grabar comprobante actual
	 * @param
	 */
	function beforeGrabarComprob(){ return true;}
	/**
	 * @method void afterGrabarComprob() Evento virtual ejecutado luego de Grabar comprobante actual
	 * @param
	 */
	function afterGrabarComprob(){ return true;}
}
//--------------------------------------------------------------------

//eof