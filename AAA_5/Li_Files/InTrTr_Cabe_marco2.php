<script type="text/javascript" src="LiEmTj_Tarjamant2.js"></script>
<script type="text/javascript" src="../LibJs/ext/ux/Ext.ux.gen.cmbBox.js"></script>
 
<!-- form will render to this element -->
<div id="form" style="font-size:0.7em;"></div>
<?php
    ob_start("ob_gzhandler");
    require "GenUti.inc.php";
    if (!isset ($_SESSION)) session_start();
    $_SESSION["tarEmbarque"]= "SELECT emb_RefOperativa cod, buq_Descripcion txt, emb_SemInicio inicio, emb_SemTermino fin
        FROM (liqembarques LEFT JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque)  
         LEFT JOIN genparametros ON par_clave='IMARCA' AND liqembarques.emb_CodMarca = genparametros.par_Secuencia
        order by emb_SemInicio desc";
    include_once "../LibPhp/NoCache.php";
    error_reporting(E_ALL);
    
?>

