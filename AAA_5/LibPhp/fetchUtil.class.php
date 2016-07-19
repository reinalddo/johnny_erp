<?
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
    function FetchAllHtml($query, $pOpc, $pHdr=Array()){
        $this->Execute($query);
        return  rs2html($this->row,$pOpc, $pHdr);
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
?>
