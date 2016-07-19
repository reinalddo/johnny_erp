<?php
ob_start();
include_once('../LibPhp/genericCrud.class.php');
class invdetalleCls extends clsGenericCrud {
    function init   (){
        $this->tabla = "liqtarjadetal";
        $this->action = fGetParam("pAction", "REP");
    }
    function beforeDefineSql(){
        $this->bulkInsert = false;
        return true;
    }
    /*
    *   After Saving a condetalle record, scan params and set a Sql instruction to insert records in sergastosdetalle
    */
    function afterRecord($pIdx){
        $slFlds = "";
        $slVals = "";

        foreach ($this->auxFlds AS $k => $v){
            if (isset($this->params[$k]) && isset($this->params[$k][$pIdx])){
                $slVal = $this->parseValue($v, $this->params[$k][$pIdx]) ;
                $slFlds .=  ", " . $k;
                $slVals .=  ", " . $slVal;
            }
        }
    }
}
$goGast = new invdetalleCls("tarja");
$goGast->execute();
$goGast->output();
?>