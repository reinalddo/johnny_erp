<?php
//error_reporting(E_ALL);
extract($_REQUEST);
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
?>
<html>
    <body>        
                    <table id="example" border="2">
                        <thead><th colspan="7"><h3>Requisici&oacute;n</h3></th></thead>
                         <thead><th colspan="7"><h3>De <?php echo $desde; ?> a <?php echo $hasta; ?> </h3></th></thead>
                        <thead>
                        <th>Cod SMART</th>
                        <th>Cod Alterno</th>
                        <th>Descripci&oacute;n</th>                       
                        <th>Completed Used</th>                       
                        <th>Suggested</th>
                        <th>Costo U</th>
                        <th>Costo Total</th>
                        </thead>
                        <tbody>
                           <?php
                                $total = 0;
                                $rows = count($cod);
                                for($x=0; $x< $rows; $x++){
                                    echo "<tr><td>{$cod[$x]}</td><td>{$cod2[$x]}</td><td>{$desc[$x]}</td>"; 
                                    echo "<td style='text-align:right'>{$val5[$x]}</td>";
                                    echo "<td style='text-align:right'>{$val6[$x]}</td>";
                                    echo "<td style='text-align:right'>{$val7[$x]}</td>";
                                    //$oper = $val6[$x]*$val7[$x];
                                    echo "<td style='text-align:right'>{$val8[$x]}</td>";
                                    echo "</tr>";
                                $total = $total + $val8[$x];
                                }
                                echo "<tr><td><b>Costo Total</b></td><td colspan='6' style='text-align:right'>$total</td></tr>";
                           ?>
                           
                        </tbody>
                    </table>
                        
    </body>
        <script type="text/javascript">
            window.print();
        </script>
</html> 