<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Report</title>
<link rel="stylesheet" type="text/css" href="../../../ext3/resources/css/ext-all.css">
<link rel="stylesheet" type="text/css" href="printer.css" media="print">
<script type="text/javascript">
	function goprint(obj){
		opener.Ext.ux.printmygridGO(obj);
	}
	<?php if ( isset($_GET['pdfgen']) ) { ?>
	function genPDF(){
		var test  = document.getElementById('myresult');
		var test  = document.getElementById('pageprinter');
		var test2 = document.getElementById('mybodyvalue');
		var tmpstring =     test.innerHTML;
		var numstart  =     tmpstring.indexOf("<form");
		var numend    =     tmpstring.indexOf("</form>");
		var tmpstring2=     tmpstring.substring(numstart,numend+7);
		var newstring =     tmpstring.replace(tmpstring2,'');
		var numstart  = 	newstring.indexOf('<div class="printer_controls">');
		var numend    = 	newstring.indexOf('</div>',numstart);
		var tmpstring2=     newstring.substring(numstart,numend+6);
		var newstring2=     newstring.replace(tmpstring2,'')
		test2.value = newstring2;
		document.form1.submit();
	}
	<?php } ?>
</script>
</head>
<body id="pageprinter" onload="goprint(this)" style="font-size:6px">
<link rel="stylesheet" type="text/css" href="printer.css" media="print">
<?php if ( isset($_GET['pdfgen']) ) { ?>
    <form id="form1" name="form1" method="post" action="base_pdf.php">
        <input id="mybodyvalue" name="mybodyvalue" type="hidden" value="" />
        <input id="mypaperor" name="mypaperor" type="hidden" value="<?php print $_GET['paper'];?>" />
	</form>
<?php }  ?>
</body>
</html>
