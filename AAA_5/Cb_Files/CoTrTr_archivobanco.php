<?php
/**
* Codigo para generar archivo para bancos
* Recibe como parametros :
* 	- pNumLote,      //numero de lote a procesar
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         27/07/09
* 				
*/
//include_once("General.inc.php");
//define("RelativePath", "..");
//include_once("adodb.inc.php");
////include_once("../LibPhp/ConLib.php");
//include_once("GenUti.inc.php");
////include_once("../LibPhp/ConTranLib.php");
////include_once("../LibPhp/ConTasas.php");
//$gbTrans	= false;
//$db = Null;
//$cla=null;
//$olEsq=null;
//$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
//$db = &ADONewConnection('mysql');
//$db->autoRollback = true;
//$db->debug = fGetParam("pAdoDbg",0);
//$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
//@TODO    Recibir el codigo del banco como un parametro




/**
*   Genera archivo para banco bolivariano
**/
function fArchivoBolivariano($IdBatch){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}
     
    $sSql = "select cab.idbatch,com_regnumero regNum, d.det_secuencia secuencia, det_idauxiliar aux, p.per_Apellidos banco
		, det_NumCheque cheque, com_FecContab fecha, det_ValCredito valor, per.per_ruc ruc, 
		com_Receptor beneficiario, com_Concepto concepto, com_TipoComp tipoComp, com_NumComp numComp,
                    /*origen ,*/ observacion, fecRegistro, usuario, operacion, per.per_tipoId tipoIdent, iab_ValorTex numcta
                    ,per.per_direccion direccion
        from concheques_cab cab
        inner join concheques_det det on cab.idbatch=det.idbatch
        join concomprobantes c on c.com_regnumero=det.com_regnum
                join condetalle d on c.com_regnumero=d.det_regnumero
                join conpersonas p on det_idauxiliar=p.per_CodAuxiliar
		join conpersonas per on com_codreceptor=per.per_CodAuxiliar
                join concategorias cat on cat_CodAuxiliar=p.per_CodAuxiliar and cat_Categoria=10
                join genvariables i on det_idauxiliar=iab_ObjetoID and iab_tipo=10 and iab_VariableID=3
	where  tipo=3 and det_idauxiliar=102 and cab.idbatch=".$IdBatch;//$agPar["batch"];
       
    //echo "<br/>".$sSql;
       
    $detalle = "BZDET";
    $ind = 1;
    $maxSecuencia = 6; //A002 - Longitud de secuencial de fila
    $maxRuc = 18; //A003 - Longitud de codigo de proveedor
    $identificador = "X"; //A004
    $maxIdentificacion = 14; //A005 - numero de identificacion cedula o ruc
    $maxBeneficiario = 60; //A006 - Longitud de codigo de beneficiario
    $formaPago = "CHE"; //A007 - forma de pago
    $pais = "001"; //A008 - codigo de pais
    $codBanco = "34"; //A009 - codigo de banco
    $tipoCta = "03"; //A010 - tipo cuenta - 03: corriente - 04: ahorros
    $maxNumCta = 20; //A011
    $codMoneda = "1"; //A012 - codigo de moneda
    $maxValorPago = 15; //A013 - longitud
    $maxConcepto = 255;//60; //A014
    $maxNumComprob = 15; //A015 - numero unico de pago
    $maxDireccion = 50; //A021 - direccion del beneficiario
    $codServicio = "CCH"; //A023 - codigo de servicio
    $horario = "N"; //A027 - señala horario de atencio, N: no controla horario, o puede ir en blanco
    $codEmpresa = "01907"; //A028 - codigo de empresa asignado por banco
    $codSubEmpresa = "      "; //A029 - codigo de subempresa
    $subMotivo = "   "; //A030 - sub motivo de pago o   cobro
    
    $cuerpo = "";
    
    
    $rs = $db->execute($sSql);
    //if(!($rs->EOF)){
    
    if($rs->EOF){
        return "";
    }
        while ($r = $rs->fetchRow()){
            //$total += $r['VALOR'];
            $indice = $ind++;
            while (strlen($indice) < $maxSecuencia){
                $indice = '0'.$indice;
            }
            $ruc = $r['ruc'];
            while (strlen($ruc) < $maxRuc){
                $ruc = '0'.$ruc;
            }
            
            $tipoIdentificacion = $r["tipoIdent"];
            switch ($tipoIdentificacion){
                case 1: $identificador='C';
                    break;
                case 2: $identificador='R';
                    break;
                default: $identificador='P';
                    break;
            }
            $identificador = "X";
            
            $numIdent = ' ';//$r['ruc'];
            while (strlen($numIdent) < $maxIdentificacion){
                $numIdent = ' '.$numIdent; //echo "<br/>".strlen($numIdent);//;'0'.$numIdent;
            }
            
            $benef = substr($r['beneficiario'],0,$maxBeneficiario);
            while (strlen($benef) < $maxBeneficiario){
                $benef = $benef.' ';
            }
            $numCta = trim(str_replace("-","",$r["numcta"]));
            $cheque = trim($r["cheque"]);
            while (strlen($numCta) < $maxNumCta - strlen($cheque)){
                $numCta = $numCta.'0';
            }
            $numCta .= $cheque;
            //echo "<br/>--".strlen($numCta)."<br/>";
            
            $aux = (string) $r['valor'];
            $entero = substr( $aux, 0 , strpos( $aux, "." ) );
            $decimal = substr( $aux, strpos( $aux, "." )+1 );
            //echo $r['valor']." --- ".$entero." -- ".$decimal."<br/>";
            $valor = $entero.$decimal;
            while (strlen($valor) < $maxValorPago){
                $valor = '0'.$valor;
            }
            $concepto = substr($r['concepto'],0,$maxConcepto);
            while (strlen($concepto) < $maxConcepto){
                $concepto = $concepto.' ';
            }
            //echo "<br/>--".strlen($concepto)."<br/>";
            $batch = $agPar["batch"];
            while (strlen($batch) < $maxNumComprob){
                $batch = '0'.$batch;
            }
            //echo "<br/>--".strlen($batch)."<br/>";
            
            
            $textoBlanco = ''; $maxTxtBlanco = 30;
            while (strlen($textoBlanco) < $maxTxtBlanco){
                $textoBlanco = ' '.$textoBlanco;
            }
            
            $cuerpo .= $detalle . $indice . $ruc . $identificador . $numIdent . $benef . $formaPago . $pais . $codBanco .
                        $tipoCta . $numCta;
            
            $cuerpo .= $codMoneda . $valor . $concepto . $codServicio;//;. $batch;
            
            $cuerpo .= $textoBlanco . $horario . $codEmpresa . $codSubEmpresa . $subMotivo;
            
                         
            //echo $cuerpo."<br/>".strlen($cuerpo)."<br/>";
            $cuerpo .= "\r\n";//"\n";
            
            //echo $cuerpo."<br/>".strlen($cuerpo)."<br/>";
         }
    //}
    
    echo $cuerpo."<br/>".strlen($cuerpo);
    
    $nombreArchivo = "FORZAFR".date('Ymd')."SEC1";
    
    $archivo = "archivos/".$nombreArchivo.".biz";
    
    
    
    $fp=fopen($archivo,'w');        // Abrir el archivo para anexar al final
    fwrite($fp,$cuerpo);            // Escribir en el archivo
    fclose($fp);                    // Cerrrar el archivo
    
    //echo $archivo;
    //fDbgContab("Paso 1aa " . $sqltext);
    
    //$olResp= array("success"=>true,"totalRecords"=>1);
    return $archivo;//$olResp;
}

function fArchivoPichincha($IdBatch){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    //$agPar = fGetAllParams("ALL");
    /*if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}*/
     
    $sSql = "select iab_ValorTex numcta , det_NumCheque cheque, det_ValCredito valor, com_Receptor beneficiario
		/*,cab.idbatch,com_regnumero regNum, d.det_secuencia secuencia, det_idauxiliar aux, 
		p.per_Apellidos banco ,  com_FecContab fecha, 
		per.per_ruc ruc, com_Concepto concepto, com_TipoComp tipoComp, 
		com_NumComp numComp, origen , observacion, fecRegistro, usuario, operacion, 
		per.per_tipoId tipoIdent, per.per_direccion direccion */
        from concheques_cab cab
        inner join concheques_det det on cab.idbatch=det.idbatch
        join concomprobantes c on c.com_regnumero=det.com_regnum
                join condetalle d on c.com_regnumero=d.det_regnumero
                join conpersonas p on det_idauxiliar=p.per_CodAuxiliar
		join conpersonas per on com_codreceptor=per.per_CodAuxiliar
                join concategorias cat on cat_CodAuxiliar=p.per_CodAuxiliar and cat_Categoria=10
                join genvariables i on det_idauxiliar=iab_ObjetoID and iab_tipo=10 and iab_VariableID=3
	where  tipo=3 and det_idauxiliar=101 and cab.idbatch=".$IdBatch;//$agPar["batch"];
       
    //echo "<br/>".$sSql;
       
    
    $cuerpo = "";
    
    
    $rs = $db->execute($sSql);
    //if(!($rs->EOF)){
    
    if($rs->EOF){
        return 0;
    }
        
    return 1;//$archivo;//$olResp;
}



?>
