<?php
#
#     Form->Database Functions
#     Written By Gil Hildebrand Jr (root@moflava.net)
#     Use is granted under GNU Public License
#
#####################################################

####################
# do_insert function
####################
# Purpose: Produces 2 strings which can be used to make a database insert.
#
# How it works: In your form, name fields with "do_" as a prefix. For example,
#       if the field name in your db is "foobar", then name your form field
#       "do_foobar". Note that you can also make required fields, which will
#       halt the program before any database call if the required field has
#       no value. To use this, name your required field "do_required_foobar".
#
#       In your program, call the function as follows:
#       list($fields,$values) = do_insert($HTTP_POST_VARS);
#
#       The function will return an array which is broken into the $fields
#       and $values variables. To insert into your db, just do this:
#       mysql_query("Insert into table_name ($fields) VALUES ($values)");
#
# Usage: list($fields,$values) = do_insert($HTTP_POST_VARS);
#        if(!empty($values)) mysql_query("Insert into table_name ($fields) VALUES ($values)");
##################
function do_insert($vars) {
  while(list($key,$value) = each($vars)) {
    if(preg_match("/do\_/i",$key)) {
      if(is_array($value)) {
        $x=0;
        while(list($key2,$value2)=each($value)) {
          $valinput .= $value2;
          if($x<count($value)-1) { $valinput .= ",";$x++; }
        }
        $columns[] = $key;
        $values[] = $valinput;
        $x=0;$valinput = "";
      }
      else if($value!="") {
        $columns[] = $key;
        $values[] = $value;
      }
    }
    if(preg_match("/requ\_/i",$key) && empty($value)) die("The $key field cannot be left empty. Please go back and fill in this field.");
  }

  $numcols = count($columns);
  $numvals = count($values);

  $columns = preg_replace("/do\_/i", "", $columns);
  $columns = preg_replace("/requ\_/i", "", $columns);

  for($i=0;$i<$numcols;$i++) {
    $columnstring .= $columns[$i];
    if($i<$numcols-1) $columnstring .= ",";
  }
  for($i=0;$i<$numvals;$i++) {
    $valuestring .= "'$values[$i]'";
    if($i<$numvals-1) $valuestring .= ",";
  }
  $return[0] = $columnstring;
  $return[1] = $valuestring;
  return $return;
}

###################
# do_update function
###################
# Purpose: Produces a string which can be used to make a database update.
#
# How it works: In your form, name fields with "do_" as a prefix. For example,
#       if the field name in your db is "foobar", then name your form field
#       "do_foobar". Note that you can also make required fields, which will
#       halt the program before any database call if the required field has
#       no value. To use this, name your required field "do_required_foobar".
#
#       In your program, call the function as follows:
#       list($fields,$values) = do_insert($HTTP_POST_VARS);
#
#       The function will return a variable which can be used as the
#       string for your update query. Example:
#       mysql_query("Update table_name SET $updatestring WHERE foo='bar'");
#
# Usage: $updatestring = do_update($HTTP_POST_VARS);
#        if(!empty($updatestring)) mysql_query("Update table_name SET $updatestring WHERE foo='bar'");
################
function do_update($vars) {
  while(list($key,$value) = each($vars)) {
    if(preg_match("/do\_/i",$key)) {
      if(is_array($value)) {
        $x=0;
        while(list($key2,$value2)=each($value)) {
          $valinput .= $value2;
          if($x<count($value)-1) { $valinput .= ",";$x++; }
        }
        $columns[] = $key;
        $values[] = $valinput;
        $x=0;$valinput = "";
      }
      else if($value!="") {
        $columns[] = $key;
        $values[] = $value;
      }
    }
    if(preg_match("/requ\_/i",$key) && empty($value)) die("The $key field cannot be left empty. Please go back and fill in this field.");
  }

  $numcols = count($columns);
  $numvals = count($values);

  $columns = preg_replace("/do\_/i", "", $columns);
  $columns = preg_replace("/requ\_/i", "", $columns);

  for($i=0;$i<$numcols;$i++) {

    $updatestring .= $columns[$i] . "='" . $values[$i] . "'";

    if($i<$numcols-1) $updatestring .= ", ";

  }
  return $updatestring;
}

?>
/*
For database input:
$updatestring = do_update($HTTP_POST_VARS);
if(!empty($updatestring)) sql_query("Update table_name SET $updatestring WHERE foo='bar'");
*/
?>
