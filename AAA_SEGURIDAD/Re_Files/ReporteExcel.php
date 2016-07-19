<?php
error_reporting(E_ALL);
extract($_REQUEST); //echo "Si"; exit();
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
header("Content-Type:  application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Disposition: attachment; filename="reporte.xls"');
?>
<html>
    <body>
                    <table id="example">
                        <thead>
                        <th>Cod Item</th>
                        <th>Cod Alterno</th>
                        <th>Descripci&oacute;n</th>
                        <?php
                        $arrCab = explode("|",$cab);
                        if (count($arrCab) > 0) {
                            for ($x=0; $x< $cellarsCount; $x++) {
                                echo "<th>{$arrCab[$x]}</th>";
                            }
                        }
                        ?>
                        <th>Stock</th>
                        <th>Transit</th>
                        <th>Subtotal</th>
                        <th>Avarage</th>
                        <th>Complete Used</th>
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
                                    for ($y=1; $y <= $cellarsCount; $y++) {
                                        $name = "cell".$y;
                                        //echo ${$name}[$x]."</br>";
                                    echo "<td>".${$name}[$x]."</td>";
                                    }
                                    echo "<td>{$val1[$x]}</td><td>{$val2[$x]}</td><td>{$val3[$x]}</td><td>{$val4[$x]}</td><td>{$val5[$x]}</td><td>{$val6[$x]}</td>";
                                    //$oper = $val6[$x]*$val7[$x];
                                    echo "<td style='text-align:right'>{$val8[$x]}</td>";    
                                    echo "<td style='text-align:right'>{$val9[$x]}</td>";    
                                    $total = $total + $val9[$x];
                                    echo "</tr>";                                    
                                }
                           ?>
                           
                        </tbody>
                    </table>
                        
    </body>

</html> 