<? 

// -------------------------------------------------------------
// Check languages accepted by browser
// and see if there is a match
// -------------------------------------------------------------

$jsDir="../LibJava/";
$lang=strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
$arLang=explode(",",$lang);
for ($i=0; $i<count($arLang); $i++)
{
  $lang2=strtolower(substr(trim($arLang[$i]),0,2));
  if ($lang2=='en') break;
  $fname=$jsDir."livegrid_".$lang2.".js";
  if (file_exists($fname))
  {
    print "<script src='".$fname."' type='text/javascript'></script>";
    break;
  } 
}

?>
