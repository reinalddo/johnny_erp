<?php

error_reporting(E_ALL);

class ComComprobante {

    private $link;

    public function ComComprobante() {

        $this->link = mysqli_connect(DBSRVR, DBUSER, DBPASS, DBNAME);
    }

    public function saveIb($txtNumero) {
        $where = "";
        $queryParametros = "select * from genparametros where par_Clave = 'OCAIB'";
        $res = mysqli_query($this->link, $queryParametros);
        while ($data = mysqli_fetch_array($res)) {
            if ($data['par_Valor1'] == "ACTCLA") {
                if ($where == "") {
                    $where .= " act_Tipo = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_Tipo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTSGR") {
                if ($where == "") {
                    $where .= " act_SubGrupo = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_SubGrupo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTGRU") {
                if ($where == "") {
                    $where .= " act_Grupo = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_Grupo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTSUB") {
                if ($where == "") {
                    $where .= " act_Subcategoria = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_Subcategoria = '{$data['par_valor2']}'";
                }
            }
        }
        if ($where != "") {
            $where = " AND ( $where )";
        }

        /*
         * Validar si ya existe el enlace
         */
        //$queryEnlace = "SELECT * FROM invenlace WHERE enl_RegNumero2 = '$txtNumero'";
        
        //$resEnlace = mysqli_query($this->link, $queryEnlace);
        //if (mysqli_num_rows($resEnlace) == 0) {

            /*
             * Sacar el siguiente numComprobante
             */
            $maxValue = "1";
            $max = "SELECT max(com_NumComp) + 1  AS valor FROM concomprobantes WHERE com_TipoComp = 'IB'";
            $res = mysqli_query($this->link, $max);
            if (mysqli_num_rows($res) > 0) {
                $data = mysqli_fetch_array($res);
                $maxValue = $data['valor'];
            }
            
            $insert = "INSERT INTO concomprobantes (com_CodEmpresa,com_Establecimie,com_PtoEmision,com_TipoComp,com_NumComp,
  com_FecTrans,com_FecContab,com_FecVencim,com_emisor,com_CodReceptor,com_Receptor,com_Concepto,
  com_Valor,com_TipoCambio,com_Libro,com_NumRetenc,com_RefOperat,com_EstProceso,com_EstOperacion,com_NumProceso,
  com_CodMoneda,com_Usuario,com_NumPeriodo,com_PerOperativo,com_Vendedor,com_TsaImpuestos,com_FecDigita,
  com_embarque,com_payment,com_business,com_beneficiary,com_kgsneto,com_kgsbruto,com_fpago,com_Supervisor,
  com_Contenedor,com_NumUnit,com_autsri) SELECT com_CodEmpresa,com_Establecimie,com_PtoEmision,'IB','$maxValue',com_FecTrans,com_FecContab,com_FecVencim,com_emisor,com_CodReceptor,com_Receptor,concat(com_Concepto, '. Orden de compra ', com_NumComp),com_Valor,com_TipoCambio,com_Libro,com_NumRetenc,com_RefOperat,com_EstProceso,com_EstOperacion,com_NumProceso,com_CodMoneda,com_Usuario,com_NumPeriodo,com_PerOperativo,com_Vendedor,com_TsaImpuestos,com_FecDigita,com_embarque,com_payment,com_business,com_beneficiary,com_kgsneto,com_kgsbruto,com_fpago,com_Supervisor,com_Contenedor,com_NumUnit,com_autsri
  FROM concomprobantes WHERE com_RegNumero = '$txtNumero'";
            //echo $insert;
            mysqli_query($this->link, $insert);
            $id = mysqli_insert_id($this->link);
            $insertEnlace = "INSERT INTO invenlace (enl_RegNumero, enl_RegNumero2) VALUES ('$id','$txtNumero')";
            mysqli_query($this->link, $insertEnlace);
        /*}else{
            $data = mysqli_fetch_array($resEnlace);
            $id = $data['enl_RegNumero'];            
        }*/
        
        /*
         * Obtenemos la secuencia
         */
        $secuencia = 0;
        $querySecuencia = "SELECT MAX(det_Secuencia) AS valor FROM invdetalle WHERE det_RegNumero = '$id'";
        
        $resSecuencia = mysqli_query($this->link, $querySecuencia);
        if(mysqli_num_rows($resSecuencia)>0){
           $dataSecuencia = mysqli_fetch_array($resSecuencia);
           $secuencia = $dataSecuencia['valor'];
        }
        
        
    //echo $insertEnlace;
            $query = "SELECT * FROM (SELECT 
    invdetalle.*,
    (IFNULL(invdetalle.`det_CanDespachada`,0) -
    IFNULL(SUM(ibd.`det_CanDespachada`),0)) AS faltante
  FROM
    concomprobantes 
    LEFT JOIN conpersonas emisor 
      ON concomprobantes.`com_Emisor` = emisor.`per_CodAuxiliar` 
    INNER JOIN invdetalle 
      ON concomprobantes.com_RegNumero = invdetalle.det_regNUmero 
    INNER JOIN conactivos 
      ON conactivos.act_codauxiliar = invdetalle.det_coditem 
    LEFT JOIN invenlace 
      ON concomprobantes.com_RegNumero = enl_RegNumero2 
    LEFT JOIN invdetalle ibd 
      ON enl_RegNumero = ibd.det_regNUmero 
      AND ibd.det_CodItem = invdetalle.det_CodItem 
  WHERE com_TipoComp = 'OC' AND com_RegNumero = '$txtNumero' 
    $where
GROUP BY
  invdetalle.`det_CodItem` ) AS A where faltante <> 0";

            $res = mysqli_query($this->link, $query);
            while($data = mysqli_fetch_array($res)){
                $secuencia = $secuencia + 1;
                $costoTotal = $data['faltante'] * $data['det_cosunitario']; 
                $detail = "INSERT INTO invdetalle (det_CodEmpresa,det_RegNUmero,det_Secuencia,det_NumSerie,det_CodItem,det_CanDespachada,det_UniMedida,det_CantEquivale,det_CosTotal,det_ValTotal,det_DescTotal,det_RefOperativa,det_Estado,det_cosunitario,det_valunitario,det_Destino,det_Lote,det_IndiceIva,det_IndiceIce,det_SerieNew,det_SerieOld,det_WorkOrder,det_Alarm,det_Tag,det_CodAnterior,det_Patio)"
                        . "VALUES (
                        '{$data['det_CodEmpresa']}',
                        '$id',
                        '$secuencia',
                        '{$data['det_NumSerie']}',
                        '{$data['det_CodItem']}',
                        '{$data['faltante']}',
                        '{$data['det_UniMedida']}',
                        '{$data['faltante']}',
                        '$costoTotal',
                        '{$data['det_ValTotal']}',
                        '{$data['det_DescTotal']}',
                        '{$data['det_RefOperativa']}',
                        '{$data['det_Estado']}',
                        '{$data['det_cosunitario']}',
                        '{$data['det_valunitario']}',
                        '{$data['det_Destino']}',
                        '{$data['det_Lote']}',
                        '{$data['det_IndiceIva']}',
                        '{$data['det_IndiceIce']}',
                        '{$data['det_SerieNew']}',
                        '{$data['det_SerieOld']}',
                        '{$data['det_WorkOrder']}',
                        '{$data['det_Alarm']}',
                        '{$data['det_Tag']}',
                        '{$data['det_CodAnterior']}',
                        '{$data['det_Patio']}')";                        
                        mysqli_query($this->link, $detail);                        
            }
            return $maxValue;
        
    }

    public function getComprobantesEmitidos($txtNumero, $txtFechaDesde, $txtCodItem, $txtProveedor, $txtFechaHasta, $txtCodAlterno, $txtBodega, $txtItem) {
        $where = "";
        $query = "SELECT * FROM (SELECT 
  com_RegNumero,
  com_TipoComp,
  com_NumComp,
  com_FecTrans,
  CONCAT(
    emisor.per_Nombres,
    ' ',
    emisor.per_Apellidos
  ) AS Bodega,
  com_Receptor,
  invdetalle.det_CanDespachada AS pedida,
  SUM(ibd.det_CanDespachada) AS entregada
FROM
  concomprobantes 
  LEFT JOIN conpersonas emisor 
    ON concomprobantes.`com_Emisor` = emisor.`per_CodAuxiliar` 
  INNER JOIN invdetalle 
    ON concomprobantes.com_RegNumero = invdetalle.det_regNUmero 
  INNER JOIN conactivos 
    ON conactivos.act_codauxiliar = invdetalle.det_coditem 
    LEFT JOIN invenlace 
    ON concomprobantes.com_RegNumero = enl_RegNumero2 
  LEFT JOIN invdetalle ibd 
    ON enl_RegNumero = ibd.det_regNUmero 
    AND ibd.det_CodItem = invdetalle.det_CodItem 
WHERE com_TipoComp = 'OC' 
";
        if ($txtNumero != "") {
            $where .= " AND com_NumComp = '$txtNumero'";
        }
        if ($txtFechaDesde != "") {
            $where .= " AND com_FecTrans >= '$txtFechaDesde'";
        }
        if ($txtFechaHasta != "") {
            $where .= " AND com_FecTrans <= '$txtFechaHasta'";
        }
        if ($txtProveedor != "") {
            $where .= " AND com_Receptor like '%$txtProveedor%'";
        }
        if ($txtBodega != "") {
            $where .= " AND ( emisor.per_Nombres LIKE '%$txtBodega%' OR emisor.per_Apellidos LIKE '%$txtBodega%')";
        }
        if ($txtCodAlterno != "") {
            $where .= " AND invdetalle.det_CodAnterior = '$txtCodAlterno'";
        }
        if ($txtCodItem != "") {
            $where .= " AND invdetalle.det_CodItem = '$txtCodItem'";
        }
        if ($txtItem != "") {
            $where .= " AND act_Descripcion LIKE '%$txtItem%'";
        }

        $innerWhere = "";
        $queryParametros = "select * from genparametros where par_Clave = 'OCAIB'";
        $res = mysqli_query($this->link, $queryParametros);
        while ($data = mysqli_fetch_array($res)) {
            if ($data['par_Valor1'] == "ACTCLA") {
                if ($innerWhere == "") {
                    $innerWhere .= " act_Tipo = '{$data['par_valor2']}'";
                } else {
                    $innerWhere .= " OR act_Tipo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTSGR") {
                if ($innerWhere == "") {
                    $innerWhere .= " act_SubGrupo = '{$data['par_valor2']}'";
                } else {
                    $innerWhere .= " OR act_SubGrupo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTGRU") {
                if ($innerWhere == "") {
                    $innerWhere .= " act_Grupo = '{$data['par_valor2']}'";
                } else {
                    $innerWhere .= " OR act_Grupo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTSUB") {
                if ($innerWhere == "") {
                    $innerWhere .= " act_Subcategoria = '{$data['par_valor2']}'";
                } else {
                    $innerWhere .= " OR act_Subcategoria = '{$data['par_valor2']}'";
                }
            }
        }
        if ($innerWhere != "") {
            $innerWhere = " AND ( " . $innerWhere . " )";
        }
        $query = $query . $where . $innerWhere . "  
GROUP BY com_NumComp, invdetalle.`det_CodItem` ) AS A WHERE (IFNULL(pedida,0)-IFNULL(entregada,0))>0
GROUP BY com_NumComp";
        //echo $query;
        return mysqli_query($this->link, $query);
    }

    public function getDetalleComprobantesEmitidos($txtNumero) {
        $where = "";
        $queryParametros = "select * from genparametros where par_Clave = 'OCAIB'";
        $res = mysqli_query($this->link, $queryParametros);
        while ($data = mysqli_fetch_array($res)) {
            if ($data['par_Valor1'] == "ACTCLA") {
                if ($where == "") {
                    $where .= " act_Tipo = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_Tipo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTSGR") {
                if ($where == "") {
                    $where .= " act_SubGrupo = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_SubGrupo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTGRU") {
                if ($where == "") {
                    $where .= " act_Grupo = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_Grupo = '{$data['par_valor2']}'";
                }
            } elseif ($data['par_Valor1'] == "ACTSUB") {
                if ($where == "") {
                    $where .= " act_Subcategoria = '{$data['par_valor2']}'";
                } else {
                    $where .= " OR act_Subcategoria = '{$data['par_valor2']}'";
                }
            }
        }
        if ($where != "") {
            $where = " AND ( $where )";
        }

$query = "SELECT 
  invdetalle.*,
  conactivos.*,
  ifnull(sum(ibd.det_CanDespachada), 0) AS recibido,
  ifnull(invdetalle.det_CanDespachada, 0) - ifnull(sum(ibd.det_CanDespachada), 0) AS pendiente 
            FROM concomprobantes                     
                    INNER JOIN invdetalle ON concomprobantes.com_RegNumero = invdetalle.det_regNUmero AND det_RegNUmero = '$txtNumero'                 
                    INNER JOIN conactivos  on conactivos.act_codauxiliar =  invdetalle.det_coditem $where
                    LEFT JOIN invenlace ON concomprobantes.com_RegNumero = enl_RegNumero2 
                    LEFT JOIN invdetalle ibd ON enl_RegNumero =  ibd.det_regNUmero AND ibd.det_CodItem = invdetalle.det_CodItem GROUP BY det_CodItem
                    ";
        
        //echo $query ;
        return mysqli_query($this->link, $query);
    }

    public function getIbsComprobantesEmitidos($txtNumero) {

        $query = "SELECT *
            FROM concomprobantes                     
                    INNER JOIN invdetalle ON concomprobantes.com_RegNumero = invdetalle.det_regNUmero                    
                    INNER JOIN invenlace ON det_RegNUmero =  enl_RegNumero AND enl_RegNumero2 = '$txtNumero'                        
                    INNER JOIN conactivos  on conactivos.act_codauxiliar =  invdetalle.det_coditem";

        //echo $query ;
        return mysqli_query($this->link, $query);
    }

}

define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
extract($_REQUEST);
$table = "";
$objComComprobante = new ComComprobante();

if ($accion == "1") {
    $rs = $objComComprobante->getComprobantesEmitidos($txtNumero, $txtFechaDesde, $txtCodItem, $txtProveedor, $txtFechaHasta, $txtCodAlterno, $txtBodega, $txtItem);
    if (mysqli_num_rows($rs) > 0) {
        $table .= "<tr><th>Comprobante</th><th>N&uacute;mero</th><th>Fecha</th><th>Bodega</th><th>Proveedor</th><th>Opciones</th></tr>";
        while ($data = mysqli_fetch_array($rs)) {
            $table .= "<tr onClick='obtenerDetalle({$data['com_RegNumero']}, this)'><td>{$data['com_TipoComp']}</td><td>{$data['com_NumComp']}</td><td>{$data['com_FecTrans']}</td><td>{$data['Bodega']}</td><td>{$data['com_Receptor']}</td><td><input type='button' value='Generar' onClick='generarIb({$data['com_RegNumero']},event)'> <input type='button' value='Ver orden' onClick='generarImpresion({$data['com_NumComp']},event)'   </td></tr>";
        }   
    }
    if ($table != "") {
        $table = "<hr/><table id='comprobantes' class='table'>" . $table . "</table>";
    }
    echo $table;
} else if ($accion == "2") {
    $rs = $objComComprobante->getDetalleComprobantesEmitidos($txtNumero);
    if (mysqli_num_rows($rs) > 0) {
        $table .= "<tr><th>Cod Items</th><th>Cod Alterno</th><th>Descripci&oacute;n</th><th>Pedido</th><th>Recibido</th><th>Pendiente</th></tr>";
        while ($data = mysqli_fetch_array($rs)) {
            $table .= "<tr><td>{$data['det_CodItem']}</td><td>{$data['det_CodAnterior']}</td><td>{$data['act_Descripcion']}</td><td>{$data['det_CanDespachada']}</td><td>{$data['recibido']}</td><td>{$data['pendiente']}</td></tr>";
        }
    }
    if ($table != "") {
        $table = "<h3 class='center-block'>Detalle de items</h3><hr/><table class='table'>" . $table . "</table>";
    }
    $table_ibs = "";
    $rs = $objComComprobante->getIbsComprobantesEmitidos($txtNumero);
    if (mysqli_num_rows($rs) > 0) {
        $table_ibs .= "<tr><th>Comprobante</th><th>Fecha</th><th>Cod Alterno</th><th>Descripci&oacute;n</th><th>Recibido</th><th>Concepto</th></tr>";
        while ($data = mysqli_fetch_array($rs)) {
            //var_dump($data);
            $table_ibs .= "<tr><td>{$data['com_TipoComp']} {$data['com_NumComp']}</td><td>{$data['com_FecTrans']}</td><td>{$data['det_CodAnterior']}</td><td>{$data['act_Descripcion']}</td><td>{$data['det_CanDespachada']}</td><td>{$data['com_Concepto']}</td></tr>";
        }
    }
    if ($table_ibs != "") {
        $table_ibs = "<h3 class='center-block'>Ingreso a bodegas asociados</h3><hr/><table class='table'>" . $table_ibs . "</table>";
    }
    echo $table . $table_ibs;
} else if ($accion == "3") {
    
    echo "<h3>Se generó el IB " . $objComComprobante->saveIb($txtNumero)."</h3>";
    /*$rs = $objComComprobante->getDetalleComprobantesEmitidos($txtNumero);
    if (mysqli_num_rows($rs) > 0) {
        $table .= "<tr><th>Cod Items</th><th>Cod Alterno</th><th>Descripci&oacute;n</th><th>Pedido</th><th>Recibido</th><th>Pendiente</th></tr>";
        while ($data = mysqli_fetch_array($rs)) {
            $table .= "<tr><td>{$data['det_CodItem']}</td><td>{$data['det_CodAnterior']}</td><td>{$data['act_Descripcion']}</td><td>{$data['det_CanDespachada']}</td><td>{$data['com_Receptor']}</td><td>{$data['com_Receptor']}</td></tr>";
        }
    }
    if ($table != "") {
        $table = "<h3 class='center-block'>Detalle de items</h3><hr/><table class='table'>" . $table . "</table>";
    }
    $table_ibs = "";
    $rs = $objComComprobante->getIbsComprobantesEmitidos($txtNumero);
    if (mysqli_num_rows($rs) > 0) {
        $table_ibs .= "<tr><th>Comprobante</th><th>Fecha</th><th>Cod Alterno</th><th>Descripci&oacute;n</th><th>Recibido</th><th>Concepto</th></tr>";
        while ($data = mysqli_fetch_array($rs)) {
            
            $table_ibs .= "<tr><td>{$data['enl_RegNumero']}</td><td>{$data['det_CodAnterior']}</td><td>{$data['det_CodAnterior']}</td><td>{$data['act_Descripcion']}</td><td>{$data['com_CanDespachada']}</td><td>{$data['enl_RegNumero2']}</td></tr>";
        }
    }
    if ($table_ibs != "") {
        $table_ibs = "<h3 class='center-block'>Detalle de ibs asociados</h3><hr/><table class='table'>" . $table_ibs . "</table>";
    }
    echo $table . $table_ibs;*/
}
