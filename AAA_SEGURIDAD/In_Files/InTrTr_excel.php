<?php
include '../LibPhp/excel/excel_reader.php';     // include the class
// creates an object instance of the class, and read the excel file data
$excel = new PhpExcelReader;

$excel->read($_FILES['archivo']['tmp_name']);

// Excel file data is stored in $sheets property, an Array of worksheets
/*
  The data is stored in 'cells' and the meta-data is stored in an array called 'cellsInfo'

  Example (firt_sheet - index 0, second_sheet - index 1, ...):

  $sheets[0]  -->  'cells'  -->  row --> column --> Interpreted value
  -->  'cellsInfo' --> row --> column --> 'type' (Can be 'date', 'number', or 'unknown')
  --> 'raw' (The raw data that Excel stores for that data cell)
 */

// this function creates and returns a HTML table with excel rows and columns data
// Parameter - array with excel worksheet data
define("RelativePath", "..");
include(RelativePath . "/Common.php");
$DBdatos = new clsDBdatos();

function sheetData($sheet) {
    $re = '<table>';     // starts html table

    $x = 1;
    while ($x <= $sheet['numRows']) {
        $re .= "<tr>\n";
        $y = 1;
        while ($y <= $sheet['numCols']) {
            $cell = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
            $re .= " <td>$cell</td>\n";
            $y++;
        }
        $re .= "</tr>\n";
        $x++;
    }

    return $re . '</table>';     // ends and returns the html table
}

function insertData($sheet) {
    $re = '<table>';     // starts html table
    global $DBdatos;
    $week = $_POST['semana'];
    $year = $_POST['axio'];
    $folio = CCGetDBValue("SELECT id FROM merca_folio WHERE week = '$week' AND year = '$year'", $DBdatos);
    if ($folio != "") {
        echo "Le indicamos que previamente se cargo informaci&oacute;n para esta semana";
        exit();
    }

    $query = "INSERT INTO merca_folio (date, week, year) values (curdate(),'$week','$year')";
    $DBdatos->query($query);
    $id = $DBdatos->query_id();
    $x = 1;
    while ($x <= $sheet['numRows']) {
        $re .= "<tr>\n";
        $y = 1;
        if ($sheet['numCols'] != 19) {
            echo "<h4>El archivo cargado al sistema tiene diferente n&uacute;mero de columnas que los esperados: {$sheet['numCols']} contra 19</h4>";
            exit();
        }

        $separador = "";
        $values = "";
        while ($y <= $sheet['numCols']) {
            $cell = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
            $re .= " <td>$cell</td>\n";
            $values .= $separador . "'" . $cell . "'";
            $separador = ", ";
            $y++;
        }
        if ($x > 1) {
            $query = " INSERT INTO merca (mer_mode,mer_date,mer_equipment,mer_week,mer_workorder,mer_vendor,mer_ordinary_man_hours,mer_overtime_1,mer_overtime_2,mer_overtime_3,mer_total_hours,mer_total_labour_cost,mer_part_supplier,mer_total_cost_parts,mer_total_cost_supplied,mer_import_tax,mer_tax_parts,mer_tax_labours,mer_total_to_paid, mer_folio, mer_year)"
                    . " values ($values , {$id},'{$year}')";
            $DBdatos->query($query);
        }

        $re .= "</tr>\n";
        $x++;
    }
     $folio = CCGetDBValue("SELECT id FROM merca_folio WHERE week = '$week' AND year = '$year'", $DBdatos);
//     echo "<h4>Filas: {$sheet['numRows']} ID: {$id}  Lineas: {$x}  Folio: {$folio}</h4>";
     $x = $x -2;
     $query = " UPDATE merca_folio SET total_records = {$x} WHERE id = {$folio}";   //  `week` = {$week'} AND `year` = {'$year'}";    // 
        $DBdatos->query($query);
    return $re . "</table></br></br><h4>Total Registros importados: {$x}</h4>";     // ends and returns the html table
}

$nr_sheets = count($excel->sheets);       // gets the number of sheets
$excel_data = '';              // to store the the html tables with data of each sheet
// traverses the number of sheets and sets html table with each sheet data in $excel_data
for ($i = 0; $i < $nr_sheets; $i++) {
    $excel_data .= '<h4>CARGA ' . ($i + 1) . ' (<em>' . $excel->boundsheets[$i]['name'] . '</em>)</h4>' . insertData($excel->sheets[$i]) . '<br/>';
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Datos CARGADOS</title>
        <style type="text/css">
            table {
                border-collapse: collapse;
            }        
            td {
                border: 1px solid black;
                padding: 0 0.5em;
            }        
        </style>
    </head>
    <body>

<?php
// displays tables with excel file data
echo $excel_data;
?>    

    </body>
</html>
