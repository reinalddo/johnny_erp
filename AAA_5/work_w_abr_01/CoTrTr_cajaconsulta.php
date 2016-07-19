<?php
/*
*   Generar un string JSON con informacion de Base de datos.
*	La sentencia Sql, se asigna via Sesion, o get
*/
ob_start();
if (!isset ($_SESSION)) session_start();
include('../LibPhp/queryToJson.class.php');
class treeEmbarques extends clsQueryToJson
{
	function init(){
		global $db;
		$pNiv = fGetParam('pNivel', 0)+1;
		//$pCls = fGetParam('pArbol', "A");
                $pTipo = fGetParam('pTipo', "");
                if ("" != $pTipo && "undefined" != $pTipo)
                    $slID = $this->sqlID ."_" . $pNiv."_".$pTipo;
                else
                    $slID = $this->sqlID ."_" . $pNiv;
		if ($pNiv <=0 ) {											// PAra el Nodo raiz
			if (isset($_SESSION[$slID])) {
				$this->sqlID =  $slID; // si existe una variable de sesion definida para este nivel, usarla
			}
			else {
				$this->sqlID= $this->sqlID . "_1";				 // sino, asignar la de totales como default
			}
		}
		else 	$this->sqlID= $slID;
		$this->rootElem=false;
		$this->statusFlag=false;
		$db->debug=isset($_GET['pAdoDbg'])? $_GET['pAdoDbg']: (isset($_SESSION['pAdoDbg'])? $_SESSION['pAdoDbg']: false);
	}
	function 		afterInit($param=null){
	}
	function beforeOutput($param=null){
	}
}
//obsafe_print_r($_SESSION, false, true, 4);
$emb= new treeEmbarques(false, false); // json data without root or status data
$emb->getJson();
ob_end_flush();














?>
