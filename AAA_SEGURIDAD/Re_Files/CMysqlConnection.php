<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CMysqlConnection
 *
 * @author antonio
 */
include_once 'IDatabaseConnection.php';

class CMysqlConnection implements IDatabaseConnection {

    //put your code here
    private $_connection;

    /*
     * Crea el objeto conexion
     * */

    public function connection($config) {
        if (is_array($config)) {
            $this->_connection = mysqli_connect($config['DBSRVR'], $config['DBUSER'], $config['DBPASS'], $config['DBNAME']);
        } else {
            $this->_connection = mysqli_connect(DBSRVR, DBUSER, DBPASS, DBNAME);
        }
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    /*
     * Retorna un arreglo con el contenido 
     * */

    public function executeQuery($query) {
        $data = array();
        $result = mysqli_query($this->_connection, $query);
        if (mysqli_num_rows($result)) {
            while ($datos = mysqli_fetch_array($result, true)) {
                $data[] = $datos;
            }
        }
        return $data;
    }

    public function insertId() {
        return mysqli_insert_id($this->_connection);
    }

    public function executeMultiQuery($query) {
        if (mysqli_multi_query($this->_connection, $query)) {
            do {
                /* almacenar primer juego de resultados */
                if ($result = mysqli_store_result($this->_connection)) {
                    /*while ($row = mysqli_fetch_row($result)) {
                        printf("%s\n", $row[0]);
                    }*/
                    mysqli_free_result($result);
                }
                /* mostrar divisor */
                /*if (mysqli_more_results($link)) {
                    //printf("-----------------\n");
                }*/
            } while (mysqli_next_result($this->_connection));
        }
    }

}
