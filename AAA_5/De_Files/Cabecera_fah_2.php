<?php
/**
*Script para incluir la cabecera en una pagina, no generada por CCS, pero de igual funcionalidad,
*con validacion de privilegios de acceso
*   @Crea   fah  Oct 10 /04
**/

    define("RelativePath", "../");
    include_once("General.inc.php");
    include_once("../LibPhp/SegLib.php");
    include_once("../Common.php");
?>
<table background="../Images/p_header.gif" border="0" style=" top-margin:15; WIDTH: 100%; BORDER-COLLAPSE: collapse; HEIGHT: 50px" cellspacing="0" cellpadding="0">
  <tr style= "BORDER-COLLAPSE: collapse;" >
    <td class="" style= "BORDER-COLLAPSE: collapse;" align="left" nowrap width="33%">
        <font color="#fdf5e6" style="FONT-SIZE: 12px">
         <?php
            echo "&nbsp;&nbsp;" .fTraeUsuario();
         ?>
        </font>
    </td>
    <td align="middle" nowrap valign="center" width="34%">
        <font color="#fdf5e6" style="FONT-WEIGHT: bold; FONT-SIZE: 12px">
         <?php
            echo $_SESSION["g_empr"] ? $_SESSION["g_empr"]: "??????"
         ?>
        </font>
 	</td>
    <td align="right" nowrap width="33%">
        <font color="#fdf5e6" style="BORDER-COLLAPSE: collapse; FONT-SIZE: 10px">
        <?php
            echo date("F j, Y, g:i a")
        ?>
        </font>
    </td>
  </tr>
</table>


