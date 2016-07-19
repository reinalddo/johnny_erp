<?php

error_reporting(E_ALL);
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("CMysqlConnection.php");
extract($_REQUEST);
//var_dump($_REQUEST);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reporte
 *
 * @author antonio
 */
class Folio {

    private $_objConnection = "";

    //put your code here
    public function __construct() {
        $this->_objConnection = new CMysqlConnection();
        $this->_objConnection->connection();
    }

    function getCompanyRecord() {
        $query = "SELECT par_clave, par_descripcion, par_Valor1, par_valor2 FROM `genparametros` WHERE par_clave IN ('EGNOM','EGRUC','EGDIR')";
        //echo $query;
        $data = array();
        $arrData = $this->_objConnection->executeQuery($query);
        foreach ($arrData as $d) {
            if ($d['par_clave'] == 'EGDIR') {
                $data[$d['par_clave']] = $d['par_descripcion'] . " " . $d['par_Valor1'] . " " . $d['par_valor2'];
            } else {
                $data[$d['par_clave']] = $d['par_descripcion'];
            }
        }
        return $data;
    }

    function getRecords($txtDescripcion = "") {
        if ($txtDescripcion != "") {
            $txtDescripcion = " WHERE fol_Descripcion like '%$txtDescripcion%'";
        }
        $query = "SELECT * FROM reqfolio $txtDescripcion ORDER BY fol_Id DESC";
        return $this->_objConnection->executeQuery($query);
    }

    function getRecordByDescription($txtDescripcion = "") {

        $query = "SELECT * FROM reqfolio WHERE fol_Descripcion = '$txtDescripcion'";
        //echo $query;
        $data = $this->_objConnection->executeQuery($query);
        if (count($data) > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    function getRecordDataById($id = "") {

        $query = "SELECT * FROM reqfolio WHERE fol_Id = '$id'";
        $data = $this->_objConnection->executeQuery($query);
        return $data;
    }

    public function insertFolio($descripcion, $fechaInicio, $fechaFin, $intervalo) {
        $query = "INSERT INTO reqfolio (fol_Descripcion, fol_fechaInicio, fol_fechaFin, fol_intervalo ,fol_Estatus, Fol_fechaCreacion) "
                . "VALUES ('$descripcion','$fechaInicio','$fechaFin', '$intervalo','A', CURDATE())";
        //echo $query;
        return $this->_objConnection->executeQuery($query);
    }

    public function updateFolio($id) {
        $query = "UPDATE reqfolio set fol_Estatus = 'I'"
                . "  WHERE fol_Id = '$id'";
        return $this->_objConnection->executeQuery($query);
    }

    public function getFolios() {
        $query = "SELECT * FROM reqfolio";
        return $this->_objConnection->executeQuery($query);
    }

    public function insertDetalleFolio($dfo_FolioId, $dfo_CodItem, $dfo_Marca, $dfo_Stock, $dfo_Transito, $dfo_Subtotal, $dfo_Avarage, $dfo_CompleteUsed, $dfo_Suggested, $dfo_UnitCost, $dfo_TotalCost) {
        $query = "INSERT INTO reqdetallefolio (dfo_FolioId, dfo_CodItem, dfo_Marca, dfo_Stock, dfo_Transito, dfo_Subtotal, dfo_Avarage, dfo_CompleteUsed, dfo_Suggested, dfo_UnitCost, dfo_TotalCost) "
                . "VALUES ('$dfo_FolioId', '$dfo_CodItem',NULL, '$dfo_Stock', '$dfo_Transito', '$dfo_Subtotal', '$dfo_Avarage', '$dfo_CompleteUsed', '$dfo_Suggested', '$dfo_UnitCost', '$dfo_TotalCost')";
        return $this->_objConnection->executeQuery($query);
    }
    
    public function insertDescripcionBodega($dbo_FolioId, $dbo_Bodega, $dbo_Descripcion) {
        $query = "INSERT INTO reqdescripcionbodega (dbo_FolioId, dbo_Bodega, dbo_Descripcion) "
                . "VALUES ('$dbo_FolioId', '$dbo_Bodega', '$dbo_Descripcion')";
        return $this->_objConnection->executeQuery($query);
    }
    
    

    /* public function updateSimpleDetalleFolio($dbo_Dd, $dfo_Stock, $dfo_Transito, $dfo_Subtotal, $dfo_Avarage, $dfo_CompleteUsed, $dfo_Suggested, $dfo_UnitCost, $dfo_TotalCost) {
      $query = "UPDATE reqdetallefolio dfo_UnitCost, dfo_TotalCost) "
      . " SET dfo_Stock = '$dfo_Stock', dfo_Transito = '$dfo_Transito', dfo_Subtotal = '$dfo_Subtotal', dfo_Avarage = '$dfo_Avarage', dfo_CompleteUsed = '$dfo_CompleteUsed', dfo_Suggested = '$dfo_Suggested', dfo_UnitCost = '$dfo_UnitCost', dfo_TotalCost = '$dfo_TotalCost'"
      . " WHERE dbo_Dd = '$dbo_Dd'";
      return $this->_objConnection->executeQuery($query);
      } */

    public function updateSimpleDetalleFolio($dbo_Dd, $dfo_Suggested, $dfo_TotalCost) {
        $query = "UPDATE reqdetallefolio "
                . " SET dfo_Suggested = '$dfo_Suggested', dfo_TotalCost = '$dfo_TotalCost'"
                . " WHERE dfo_Id = '$dbo_Dd'";
        //echo $query;
        return $this->_objConnection->executeQuery($query);
    }

    public function updateDetalleFolio($dbo_Dd, $dfo_Marca, $dfo_Stock, $dfo_Transito, $dfo_Subtotal, $dfo_Avarage, $dfo_CompleteUsed, $dfo_Suggested, $dfo_UnitCost, $dfo_TotalCost) {
        $query = "UPDATE reqdetallefolio dfo_UnitCost, dfo_TotalCost) "
                . " SET dfo_Marca = '$dfo_Marca', dfo_Stock = '$dfo_Stock', dfo_Transito = '$dfo_Transito', dfo_Subtotal = '$dfo_Subtotal', dfo_Avarage = '$dfo_Avarage', dfo_CompleteUsed = '$dfo_CompleteUsed', dfo_Suggested = '$dfo_Suggested', dfo_UnitCost = '$dfo_UnitCost', dfo_TotalCost = '$dfo_TotalCost'"
                . " WHERE dfo_Id = '$dbo_Dd'";
        return $this->_objConnection->executeQuery($query);
    }

    function getRecordsDetalle($id) {
        $query = "SELECT * FROM reqdetallefolio r"
                . " JOIN conactivos a ON act_codauxiliar = dfo_CodItem "
                . " LEFT JOIN `genparametros` g ON a.act_Marca = g.par_Secuencia AND g.par_Clave = 'IMARCA'"
                . " WHERE dfo_FolioId = '$id'";
        //echo $query;
        return $this->_objConnection->executeQuery($query);
    }
    
     function getRecordsBodegaByItem($idFolio, $idItem) {
        $query = "SELECT * FROM reqdetallebodega r"
                . " WHERE dbf_FolioId = '$idFolio' AND dbf_CodItem = '$idItem'";
        //echo $query;
        return $this->_objConnection->executeQuery($query);
    }
    

    function getRecordsBodega($id) {
        $query = "SELECT * FROM reqdetallebodega r"
                . " WHERE dbf_Detalle = '$id'";
        //echo $query;
        return $this->_objConnection->executeQuery($query);
    }

    
     function getRecordsDescripcionBodega($id) {
        $query = "SELECT * FROM reqdescripcionbodega"                
                . " WHERE dbo_FolioId = '$id'";
        //echo $query;
        return $this->_objConnection->executeQuery($query);
    }
    public function deleteDetalleFolio($id) {
        $query = "DELETE FROM reqdetallefolio WHERE dfo_Id = '$id'";
        return $this->_objConnection->executeQuery($query);
    }

    public function insertDetalleBodegaFolio($detalle, $bodega, $valor) {
        $query = "INSERT INTO reqdetallebodega (dbf_Detalle, dbf_Bodega, dbf_Valor) "
                . "VALUES ('$detalle','$bodega','$valor')";
        return $this->_objConnection->executeQuery($query);
    }

    public function deleteDetalleBodegaFolio($id) {
        $query = "DELETE FROM reqdetallebodega WHERE dfo_Detalle = '$id'";
        return $this->_objConnection->executeQuery($query);
    }

    public function insertId() {
        return $this->_objConnection->insertId();
    }

    /*public function deleteDetalleFolio($id) {
        $query = "DELETE FROM reqdetallefolio WHERE dfo_Id = '$id'";
        return $this->_objConnection->executeQuery($query);
    }*/
    
    public function rawQuery($query) {        
        return $this->_objConnection->executeQuery($query);
    }
    
    public function rawMultiQuery($query) {        
        return $this->_objConnection->executeMultiQuery($query);
    }    
    
}
