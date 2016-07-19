<?php
/*
 *
 * Ayuda a poner el nombre de la pagina en la cual se esta trabajando en el pie
 *
 *
 */

      $filename = basename($_SERVER[ "PHP_SELF"],".rpt.php");
      $Tpl->assign("PiePagina", $filename);

?>