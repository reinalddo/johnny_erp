<?php
/*
*   Generic CRUD  for condetalle table.
*	@author: fah.
*	@package: DB Access
*	@version: 1.0
*/
ob_start();
include_once('../LibPhp/genericCrud.class.php');
include_once('../LibPhp/ConTranLib.php');
class condetalleCls extends clsGenericCrud {
    function init    (){
        $this->tabla = "condetalle";
        $this->action = fGetParam("pAction", "REP");
        $this->auxFlds = array("rga_RegNumero"=>"I", "rga_Secuencia"=>"I",
             "rga_ProveedorId"=>"I", "rga_Documento"=>"C",
             "rga_BaseIvaCero"=>"F", "rga_BaseIvaServ"=>"F",
             "rga_BaseIvaBien"=>"F", "rga_ValorIvaServ"=>"F", 
             "rga_ValorIvaBien"=>"F");
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
        $slReg = $this->parseValue('I', $this->actualRec['DET_REGNUMERO']) ;
        $slSec = $this->parseValue('I', $this->actualRec['DET_SECUENCIA']) ;
        

        foreach ($this->auxFlds AS $k => $v){
            if (isset($this->params[$k]) && isset($this->params[$k][$pIdx])){
                $slVal = $this->parseValue($v, $this->params[$k][$pIdx]) ;
                $slFlds .=  ", " . $k;
                $slVals .=  ", " . $slVal;
            }
        }
        if (strlen($slFlds)>0){
            $this->sqlText="REPLACE INTO sergastosdetalle (rga_regnumero, rga_secuencia" . $slFlds . ") ".
                            " VALUES ( " .  $this->parseValue('I', $this->actualRec['DET_REGNUMERO']) . ", ".
                                            $this->parseValue('I', $this->actualRec['DET_SECUENCIA'])  .
                                            $slVals . " ) ";
        }
  /*      
        if ((isset($this->params['rga_Documento'])    && isset($this->params['rga_Documento'][$pIdx]) >=12 )or
            (isset($this->params['rga_BaseIvaCero'])    && isset($this->params['rga_BaseIvaCero'][$pIdx]) >=12 )or
            (isset($this->params['rga_BaseIvaServ'])    && isset($this->params['rga_BaseIvaServ'][$pIdx]) >=12 )or 
            (isset($this->params['rga_ProveedorId'])  && isset($this->params['rga_ProveedorId'][$pIdx]) >=10)) {
            
            $slPro = $this->parseValue('I', isset($this->params['rga_ProveedorID'][$pIdx]) ? $this->params['rga_ProveedorID'][$pIdx]: 0 );
            $slDoc = $this->parseValue('C', (isset($this->params['rga_Documento'][$pIdx])?$this->params['rga_Documento'][$pIdx]:0));
            $slBi0 = $this->parseValue('F', (isset($this->params['rga_BaseIvaCero'][$pIdx])?$this->params['rga_BaseIvaCero'][$pIdx]:0) );
            $slBis = $this->parseValue('F', (isset($this->params['rga_BaseIvaServ'][$pIdx])?$this->params['rga_BaseIvaServ'][$pIdx]:0) );
            $slVis = $this->parseValue('F', (isset($this->params['rga_ValorIvaServ'][$pIdx])?$this->params['rga_ValorIvaServ'][$pIdx]:0));
    
            $this->sqlText="REPLACE INTO sergastosdetalle VALUES ( " . $slReg . ", " . $slSec . ", " . $slPro . ", " .
                            $slDoc . ", " . $slBi0 . ", " . $slBis . ",0, " . $slVis . ",0)";
*/
        $this->executeSql($slSql);
    }

    function beforeExit(){
        $ilRegNum=fGetParam("det_RegNumero", -9999);
        if(is_array($ilRegNum)) $ilRegNum= $ilRegNum[0];
        $ilNumComp=fGetParam("det_NumComp", -9999);
        $trSql = "SELECT * 
            FROM condetalle
            WHERE det_regNumero=" . $ilRegNum;
        $rs = $this->db->execute($trSql);
        if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
        $rs->MoveFirst();
  //print_r($rs);
        //while (!$rs->EOF) {
        $sSql ="UPDATE  concomprobantes SET com_estproceso = 5  WHERE com_regnumero = " . $ilRegNum;
        $this->db->Execute($sSql);
        return true;
    }
}
$goGast = new condetalleCls("condetalle");
$goGast->execute();
$goGast->output();
?>
