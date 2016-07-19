<?php

// Define Collumns
$_reader_root = "rows";
$_reader_id = 0;
$_reader_fields = Array(
    Array(
        'name' => "id", // not that withoud a header value the field will not be shown
        'type' => "int",
    ),
    Array(
        'name' => "company",
        'header' => "Company",
        'width' => 250,
        'editor' => Array(
            'type'=>'TextField',
            'config' => Array('allowBlank' => false))   
    ),
    Array(
        'name' => "price",
        'header' => "Price",
        'width' => 100
    ),
    Array(
        'name' => "date",
        'header' => "Last Updated",
        'width' => 100
    ),
    Array(
        'name' => "info",
        'header' => "Info",
        'hidden' => true
    )    
);

// Remove last column for alternate view
if($_POST['style'] == "s2") array_pop($_reader_fields);


// Re-Arrange the fields using data.txt (see saveconfig)
$cols = $_reader_fields;
$newcols = array();
/*
$data = unserialize(file_get_contents("data.txt"));
foreach($data['fields'] as $field) {
    for($i = 0; $i < count($cols); $i++) {
        if($cols[$i]['name'] == $field['name']) {
            list($c) = array_splice($cols, $i, 1);
            $c['width'] = (int) $field['width'];
            $c['hidden'] = isset($field['hidden']) ? true : false;
            array_push($newcols, $c);
        }
    }
}
*/
// Add remaining fields
$_reader_fields = array_merge($newcols, $cols); 



// Dummy Data

$data = Array(
    Array("id" => 0, "company" => "3m Co", "price" => "71.72", "date" => "2007-06-10", "info" => "Hello"),
    Array("id" => 1, "company" => "Alcoa Inc", "price" => "29.01", "date" => "2007-06-11", "info" => "World"),
    Array("id" => 2, "company" => "Altria Group Inc", "price" => "83.81", "date" => "2007-07-01", "info" => "!!!"),
    Array("id" => 3, "company" => "Microsoft", "price" => "100.10", "date" => "2007-08-01", "info" => "testing"),
    Array("id" => 4, "company" => "IBM", "price" => "120.81", "date" => "2007-08-10", "info" => "autogrid"),
    Array("id" => 5, "company" => "NOVELL", "price" => "211.15", "date" => "2007-09-03", "info" => "paging"),
    Array("id" => 6, "company" => "PHP Group", "price" => "453.11", "date" => "2007-09-05", "info" => "and"),
    Array("id" => 7, "company" => "MySQL Group", "price" => "233.41", "date" => "2007-10-01", "info" => "more"),
    Array("id" => 8, "company" => "BOTECH", "price" => "843.81", "date" => "2007-11-04", "info" => "testing..."),
    Array("id" => 9, "company" => "3bm Co", "price" => "71.72", "date" => "2007-06-10", "info" => "Hello"),
    Array("id" => 10, "company" => "Ablcoa Inc", "price" => "29.01", "date" => "2007-06-11", "info" => "World"),
    Array("id" => 12, "company" => "Abltria Group Inc", "price" => "83.81", "date" => "2007-07-01", "info" => "!!!"),
    Array("id" => 13, "company" => "Mbicrosoft", "price" => "100.10", "date" => "2007-08-01", "info" => "testing"),
    Array("id" => 14, "company" => "IbBM", "price" => "120.81", "date" => "2007-08-10", "info" => "autogrid"),
    Array("id" => 15, "company" => "NbOVELL", "price" => "211.15", "date" => "2007-09-03", "info" => "paging"),
    Array("id" => 16, "company" => "PbHP Group", "price" => "453.11", "date" => "2007-09-05", "info" => "and"),
    Array("id" => 17, "company" => "MbySQL Group", "price" => "233.41", "date" => "2007-10-01", "info" => "more"),
    Array("id" => 18, "company" => "BbOTECH", "price" => "843.81", "date" => "2007-11-04", "info" => "testing..."),
    Array("id" => 20, "company" => "3cm Co", "price" => "71.72", "date" => "2007-06-10", "info" => "Hello"),
    Array("id" => 21, "company" => "Aclcoa Inc", "price" => "29.01", "date" => "2007-06-11", "info" => "World"),
    Array("id" => 22, "company" => "Acltria Group Inc", "price" => "83.81", "date" => "2007-07-01", "info" => "!!!"),
    Array("id" => 23, "company" => "Mcicrosoft", "price" => "100.10", "date" => "2007-08-01", "info" => "testing"),
    Array("id" => 24, "company" => "IcBM", "price" => "120.81", "date" => "2007-08-10", "info" => "autogrid"),
    Array("id" => 25, "company" => "NcOVELL", "price" => "211.15", "date" => "2007-09-03", "info" => "paging"),
    Array("id" => 26, "company" => "PcHP Group", "price" => "453.11", "date" => "2007-09-05", "info" => "and"),
    Array("id" => 27, "company" => "McySQL Group", "price" => "233.41", "date" => "2007-10-01", "info" => "more"),
    Array("id" => 28, "company" => "BcOTECH", "price" => "843.81", "date" => "2007-11-04", "info" => "testing..."),
);

// Define Rows (using start & limit)
$rows = Array();

$start = isset($_POST['start']) ? $_POST['start'] : 0;
$limit = isset($_POST['limit']) ? $_POST['limit'] : count($data);

$rows = array_slice($data, $start, $limit);


$json = Array();

// if requested send meta data
if(isset($_REQUEST['meta'])) {  
    $json['metaData'] = Array(
        'root' => $_reader_root,
        'id' => $_reader_id,
        'totalProperty' => "totalCount",        
        'fields' => $_reader_fields
    );
}

$json[$_reader_root] = Array();        
foreach ($rows as $i => $row) {
    $jrow = Array();
        
    // For each field, add the according value to the data row
    foreach($_reader_fields as $field) {
        $jrow[$field['name']] = $row[$field['name']];
    }

    // push this row to the rows array
    array_push($json[$_reader_root], $jrow);            
}
$json['totalCount'] = count($data);

echo json_encode($json);