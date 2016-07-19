<?
if (!isset ($_SESSION)) session_start();
/*
*   Generar un string Xml con informacion de Ordenes  por facturar. la sentencia Sql, se asigna via Sesion, o get
*
*/
define("RelativePath", "../");
require_once("../Common.php");
//require_once("../LibPhp/SegLib.php");
require_once('adodb/adodb.inc.php'); 
require_once('adodb/tohtml.inc.php'); 
//require_once('../LibPhp/fetchUtil.class.php');
class FetchUtil {
    var $row;
    var $DB_type;
    var $DB; 
    function FetchUtil($dsn){
        $this->DB = NewADOConnection($dsn);
        if ( !$this->DB ) die("Conexion fallida - $dsn");
        $this->DB->SetFetchMode(ADODB_FETCH_ASSOC);
    }
    function Execute($query='select now()'){
        $this->row  = $this->DB->Execute($query) or die ($this->DB->ErrorMsg());
    }
    function FetchAll($query){
        $this->Execute($query);
        while(!$this->row->EOF){
            $temp[] = $this->row->fields;
            $this->row->MoveNext();
        } 
        return $temp; 
    }
    function FetchAllHtml($query, $pHdr=Array()){
        $this->Execute($query);
        return  rs2html($this->row,$pHdr);
    }
    function FetchAllArray($query,$key,$value) {
        $this->Execute($query);
        while(!$this->row->EOF){
            $temp[$this->row->fields[$key]] = $this->row->fields[$value];
            $this->row->MoveNext();
        } 
        return $temp; 
    }
    function qstr($string) {
        return $this->DB->qstr($string,get_magic_quotes_gpc());
    }
}

$data_base = 'mysql';
$user = 'exportuser';
$passwd = 'Exp2008Dusal';
$host = 'localhost';
$db_name = '08_jorcorp';
$dsn = "$data_base://$user:$passwd@$host/$db_name?clientflags=65536";
$db = new FetchUtil($dsn);
//if (fGetParam("pSql", false)) $sqltext= fGetParam("pSql", false);
include_once ("GenUti.inc.php");
$pSem = fGetParam("pSem", -1);
$pEmb = fGetParam("pEmb", -1);
$pDbg = fGetParam("pAdoDbg", -1);
//$gsSqlText = "call spLiqGetMagDat($pSem, $pEmb )";
//fProcesar();
//require "../Ge_Files/GeGeGe_queryToXml.php";

$gsSqlText = "call spLiqGetMagDat($pSem, $pEmb)";

$rows = $db->fetchAll($gsSqlText);
if ($pDbg > 2 ){
    echo "LISTA<pre>$gsSqlText<br>";
    print_r($rows);
    echo "----------</pre>";
}
$doc  = NULL;
$doc  = new DomDocument('1.0', 'UTF-8');
$root = $doc->createElement('XmlData');
$root = $doc->appendChild($root);
//
//
$rset = $doc->createElement('recordset');

if (count($rows) >= 1){
	$ilCount= 0;
	//while ($alRec = $rsDb->FetchRow()) {
    foreach ($rows as $slK0 => $alRec) {
		$xmlRec = $doc->createElement('record');
		foreach ($alRec as $slK => $slV) {
        	$xmlField = $doc->createElement($slK);
			$xmlVal   = $doc->createTextNode(utf8_encode($slV));
			$v        = $xmlField->appendChild($xmlVal);
            $x        = $xmlRec->appendChild($xmlField);
	    }
		$det   = $rset->appendChild($xmlRec);
		$ilCount++;
    }
*/
    /*              No se aplica si es SP
     $slQryCount = "SELECT FOUND_ROWS() as totalRecs";
	if($rs=$db->execute($slQryCount)){
	    $r=$rs->fetchRow();
	    $giTotalRecs=$r['totalRecs'];
	}*/

    $giTotalRecs=$ilCount;
    $giTotalRecs=$giPageRecs;
    $giTotalRecs=$r['totalRecs'];
    $root->setAttribute('success', !$db->DB->ErrorNo());
	$root->setAttribute('pageRecords', $ilCount);       // $db->DB->recordCount()
	$root->setAttribute('totalRecords',$giTotalRecs);
    $root->appendChild($rset);
} else {
	$slMensaje = 'ERROR EN LA CONSULTA';
    $root->setAttribute('success', 'false');
    $root->setAttribute('message', $slMensaje);
	$root->setAttribute('totalRecords', $giTotalRecs);
	$root->setAttribute('pageRecords', $giPageRecs);
	/*$xmlE = $doc->createElement('error');                         // @TODO: Manejo de errores
	$xmlV = $doc->createTextNode(utf8_encode($appStatus->getError('t')));
	$v    = $xmlE->appendChild($xmlV);
	$root->appendChild($xmlE);*/
}

if ($_SESSION['pAppDbg'] == 2) {
	$msge =$doc->createElement('XmlSql');
	$t    = $doc->createTextNode("-- " . utf8_encode($gsSql) ." --" );
	$v    = $msge->appendChild($t);
	$root->appendChild($msge);
}

$xml_string = $doc->saveXML();
if ($db->debug > 0 ) echo "<code>";
echo $xml_string;
if ($db->debug > 0 ) echo "</code>";

?>
