<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author antonio
 */
interface IDatabaseConnection {
    //put your code here
    public function connection($config); //Arreglo
    public function executeQuery($query); //String con consulta
    
}
