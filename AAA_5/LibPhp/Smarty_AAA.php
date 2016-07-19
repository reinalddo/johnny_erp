<?php
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = './';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs/';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }

}
?>
