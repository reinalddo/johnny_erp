<?php
/*
*   Generar un string Xml con informacion de Ordenes  por facturar. la sentencia Sql, se asigna via Sesion, o get
*@package
*@author fah
*@package   DB access
*/
class clsQueryToXml {
    /*
      * @var object rs  current recordset
     */
    var $rs;
    /*
      * @var Array rec  current record
     */
    var $rec;
    /*
      * @var XmlNode xmlRec  current XML record
     */
    var $xmlRec;
    /*
      * @var array working Row 
     */
    var $row;
    /*
     * @var string Type of DB
     */
    var $DB_type;
    /*
     * @var object Ref to database Object
     */
    var $DB;
    /*
     * @var string Name of the root element
     */
    var $rootName ="XmlData";
    /*
     * @var string Name of the data element
     */
    var $recName  = "record";
    /*
     *  Constructor: Defines DB connection, parameter conexion. Takes info from parameters or Session/constants defined at login phase.
     *  @param  string  $dsn    Conexion String
     *  @param  string  $pTipo  Fetch type: ADODB_FETCH_ASSOC, ADODB_FETCH_NUM, ADODB_FETCH_BOTH, defaults to ADODB_FETCH_ASSOC
     **/
    function clsQueryToXml($dsn=false, $pTipo=ADODB_FETCH_ASSOC){
        if (!$dsn){
            $dbtype = DBTYPE;
            $user = DBUSER;
            $pass = DBPASS;
            $host = DBSRVR;
            $dbname = DBNAME;
            $dsn = "$dbtype://$user:$pass@$host/$dbname?clientflags=65536";
        }
        $this->DB = NewADOConnection($dsn);
        if ( !$this->DB ) die("Conexion fallida - $dsn");
        $this->DB->SetFetchMode($pTipo);
        $this->DB->debug= fGetParam("pAdoDbg", 0);
    }
    /*
     *  Run a Query
     *  @param  $query  string  SQL sentence to run
     **/
    function Execute($query='select now()'){
        $this->rs  = $this->DB->Execute($query) or die ("No se ejecuto la consulta: " . $this->DB->ErrorMsg());
    }
    /*
     *  Fetch a Recordset resulting from a SQL sentence into an bi-dimensional array
     *  @param  $query  string  SQL sentence to run
     *  @return array   All rows from recordset or false if no recordset
     */
    function FetchAll($query){
        $temp=Null;
        $this->Execute($query);
        while(!$this->rs->EOF){
            $temp[] = $this->rs->fields;
            $this->rs->MoveNext();
        } 
        return $temp; 
    }
    /*
     *  Get an array of a field from a resulting recordset 
     *  @param  $query  string  SQL sentence to run
     *  @param  string $key Name of the element in the returning array
     *  @param  string $value Name of the field in the resulting recordset
     *  @return array  Containing all values of 'value' field from recordset into 'key' element
     */
    function FetchAllArray($query,$key,$value) {
        $this->Execute($query);
        while(!$this->rs->EOF){
            $temp[$this->rs->fields[$key]] = $this->rs->fields[$value];
            $this->rs->MoveNext();
        } 
        return $temp; 
    }
    /*
     *  Transform a input text into a string with magic-quotes
     *  @param  string  $tring text to transform
     *  @return string  Resulting text
     */
    function qstr($string) {
        return $this->DB->qstr($string,get_magic_quotes_gpc());
    }
    /*
     *  Before Row Event
     *  @return  boolean  true for continuing process, false for cancel processing of current record
     **/
    function beforeRow(){
        return true;
    }
    /*
     *  After Row Event
     *  @return void
     **/
    function afterRow(){
    }
    /*
     *  Before Field Event
     *  @param string $pFld Name of the current field
     *  @return  boolean  true for continuing process, false for cancel processing of current record
     **/
    function beforeField($pFld){
        return true;
    }
    /*
     *  After Field Event
     *  @param string $pFld Name of the current field
     *  @return void
     **/
    function afterField($pFld){
    }

    /*
     *  Get an XML Doc with recordset data
     *  @param  string $pQry    Sql sentence to execute
     *  @return array  Containing all values of 'value' field from recordset into 'key' element
     */
    function fetchAllXml($pQry){
        $doc  = NULL;
        $doc  = new DomDocument('1.0', 'UTF-8');
        $root = $doc->createElement($this->rootName);
        $root = $doc->appendChild($root);
        $rset = $doc->createElement($this->recName);
        $this->Execute($pQry);
        $ilCount= 0;
        if ($this->rs){
            while($this->rec = $this->rs->fetchRow()){
                if($this->beforeRow()){                
                    $this->xmlRec = $doc->createElement('record');
                    foreach ($this->rec as $slK => $slV) {
                        if($this->beforeField($slK)){
                            $xmlField = $doc->createElement($slK);
                            $xmlVal   = $doc->createTextNode(utf8_encode($slV));
                            $v        = $xmlField->appendChild($xmlVal);
                            $x        = $this->xmlRec->appendChild($xmlField);
                        }
                        $this->afterField($slK);
                    }
                    $this->afterRow();
                    $det   = $rset->appendChild($this->xmlRec);
                    $ilCount++;
                }
                /*
                $slQryCount = "SELECT FOUND_ROWS() as totalRecs";
                    if($rs=$this->DB->execute($slQryCount)){
                        $r=$rs->fetchRow();
                        $giTotalRecs=$r['totalRecs'];
                    }*/
                $root->setAttribute('success', !$this->DB->ErrorNo());
                    $root->setAttribute('pageRecords', $ilCount);
                    $root->setAttribute('totalRecords',$giTotalRecs);
                $root->appendChild($rset);
        //echo "\nReg: " ; print_r($this->rec);
            }
        } else {
                $slMensaje = 'ERROR EN LA CONSULTA';
                $root->setAttribute('success', 'false');
                $root->setAttribute('message', $slMensaje);
                $root->setAttribute('totalRecords', $giTotalRecs);
                $root->setAttribute('pageRecords', $giPageRecs);
                $xmlE = $doc->createElement('error');
                $xmlV = $doc->createTextNode(utf8_encode($appStatus->getError('t')));
                $v    = $xmlE->appendChild($xmlV);
                $root->appendChild($xmlE);
        }
        if ($_SESSION['pAppDbg'] == 2) {
                $msge =$doc->createElement('XmlSql');
                $t    = $doc->createTextNode("-- " . utf8_encode($pQry) ." --" );
                $v    = $msge->appendChild($t);
                $root->appendChild($msge);
        }
        $xml_string = $doc->saveXML();
        if ($this->DB->debug > 0 )  $xml_string =  "<pre>" . $xml_string . "</pre>";
            return  $xml_string;
        }
}
?> 
