<?php
/*
*	Aplicar flete a un embarque, segun los datos de ingreso
*	@author  Fausto Astudillo
*	@package Operaciones
*/
ob_start();
if (!isset ($_SESSION)) session_start();
include('../LibPhp/queryToJson.class.php');
class aplicafle  extends  clsQueryToJson {
    function init(){
        $this->type="U";
    }
    function beforePrepareSql(){
        global $db;
        $db->debug=fgetParam("pAdoDbg",0);
       $dlPrecio = fGetParam('pNvoPr', 0); //Nuevo Valor
       $ilSeman  = fGetParam('pSeman', 0);
       $ilEmbarq = fGetParam('pEmb',  0);
       $ilTarja  = fGetParam('pTarj',  0);
       $ilProduc = fGetParam('pProd',  0); 
       $ilZona   = fGetParam('pZona',  0);
       $ilMarca  = fGetParam('pMarc',  0);
       $ilEmpaq  = fGetParam('pEmpq',  0);
       $ilCanti  = fGetParam('pCant',  0);
       $ilItem   = fGetParam('pPrdc',  40002); // Default: Banano de Segunda (MS)
       $slConCond= "";
       if ($ilSeman)  $slConCond .= " tac_Semana = " . $ilSeman;
       if ($ilEmbarq >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "fle_RefOperativa = " . $ilEmbarq;
       if ($ilProduc >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "fle_Productor = " . $ilProduc;
       if ($ilMarca  >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "fle_CodMarca = " . $ilMarca;
       if ($ilEmpaq  >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "fle_CodCaja = " . $ilEmpaq;
       if ($ilItem   >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "fle_CodProducto = " . $ilItem;
       $slQry = "REPLACE INTO opefleteproduct
                 VALUES ($ilEmbarq, $ilProduc, $ilItem,  $ilMarca,  $ilCanti, 0) "; // Instruccion Sql Directa
//       $slQry = str_replace("{pCond}", $slConCond, $slQry);
//       $slQry = str_replace("{pPOfi}", $dlPrecOfic, $slQry);
 //      $slQry = str_replace("{pDife}", $dlDiferenc, $slQry);
       $this->arrayQry[0] = $slQry; // 
    }
}
;
$data = new aplicafle();
$data->getJson();
ob_end_flush();
?>
