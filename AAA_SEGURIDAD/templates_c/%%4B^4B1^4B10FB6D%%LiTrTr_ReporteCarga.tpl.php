<?php /* Smarty version 2.6.18, created on 2011-11-08 12:05:06
         compiled from ../Li_Files/LiTrTr_ReporteCarga.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiTrTr_ReporteCarga.tpl', 12, false),array('block', 'report_header', '../Li_Files/LiTrTr_ReporteCarga.tpl', 13, false),array('block', 'report_detail', '../Li_Files/LiTrTr_ReporteCarga.tpl', 117, false),array('block', 'report_footer', '../Li_Files/LiTrTr_ReporteCarga.tpl', 132, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Reporte de Cajas Embarcadas por Semana y Cliente -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8;" />
  <meta name="author" content="Gina Franco" />
  <!-- link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" /--> 
   <title>REPORT </title>
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!-- <table cellspacing=0 style="font-size:0.8em; width:19cm; border:solid;border-width:1px;">
	<tr style="vertical-align:middle; text-align: center;">
            <td style="border:solid; border-width:1px; width:7cm;"><img src="../Images/InformeCliente/Logo_140x120.jpg"></td>
	    <td style="border:thin;  border-width:1px; width:7cm; background-color:#A7E0F9"><strong><?php echo $this->_tpl_vars['rec']['EDir']; ?>
</strong><br>
											    <strong><?php echo $this->_tpl_vars['rec']['ECiu']; ?>
,<?php echo $this->_tpl_vars['rec']['EPai']; ?>
</strong><br>
											    <strong>Phone <?php echo $this->_tpl_vars['rec']['ETel']; ?>
</strong></td> 
    </table>-->
      <table cellspacing=0 style="font-size:1.0em; width:19cm;height:auto;">
	<tr style="vertical-align:middle; text-align: center;">
            <td style=" width:7cm; vertical-align:middle;"><img src="../Images/InformeCliente/Logo_84x72.jpg"></td>
	    <td valign="middle" style="width:7cm; vertical-align:middle; background-color:#A7E0F9;"><?php echo $this->_tpl_vars['rec']['EDir']; ?>
<br><?php echo $this->_tpl_vars['rec']['ECiu']; ?>
, <?php echo $this->_tpl_vars['rec']['EPai']; ?>
<br>Phone <?php echo $this->_tpl_vars['rec']['ETel']; ?>
</td>
	
	</tr>
      </table>
      <br><br>      
      <table  style="background-color:#A7E0F9;font-size:1.0em;">
	<tr>
	  <td style="width:6cm; vertical-align:middle;"> REF NO. FRT EC-<?php echo $this->_tpl_vars['rec']['Semana']; ?>
-001</td>
	  <td style="width:6cm; vertical-align:middle;"> LOADING SURVEY REPORT </td>
	  <td style="width:4cm; vertical-align:middle;"> <?php echo $this->_tpl_vars['rec']['FechaImp']; ?>
</td>
	</tr>
      </table>
      <br><br>      
      <table style="font-size:0.8em;">
	<tr>
	  <td style="width:6cm; vertical-align:middle;" colspan=2>MAIN PARTICULARS OF THE CARGO</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> TO: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Cliente']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> SHIPPER: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Shipper']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> DESCRIBED AS: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Producto']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> QUANTITY: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['sum']['CajasTotales']; ?>
</td>
	</tr>
      </table>
      <br><br>      
      <table style="font-size:0.8em;">
	<tr>
	  <td style="width:6cm; vertical-align:middle;" colspan=2>CARGO DESCRIPTION</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Weight according to the Exporter: </td>
	  <td style="width:6cm; vertical-align:middle;"> </td>
	</tr>
	<!-- <tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Type of cartons: </td>
	  <td style="width:6cm; vertical-align:middle;"> </td>
	</tr> -->
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Brands: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['TodasMarcas']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Vessel: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Vapor']; ?>
</td>
	</tr>
      <tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Shipping Line: </td>
	  <td style="width:6cm; vertical-align:middle;"> </td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Port of loading: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['PtoEmbarque']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Port of destination: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['PtoDestino']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Date of sailing: </td>
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['FechaSalida']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Our reference: </td>
	  <td style="width:6cm; vertical-align:middle;">FRT EC-<?php echo $this->_tpl_vars['rec']['Semana']; ?>
-001</td>
	</tr>
      </table>
      <br><br>      
      <table style="font-size:0.8em;">
	<tr>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Container Number</td>
	  <td style="width:3cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Producer </td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Code</td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Brand</td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Quantity of Boxes </td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Kind of Package</td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Net Weight (kg)</td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Bag Weight(kg) </td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Quality</td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Pod Length (cm)</td>
	</tr>
     <!-- <td style="width:7cm;">&nbsp;</td> -->
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr>
	  <td style="vertical-align:middle; text-align:center;"></td>
	  <td style="vertical-align:middle; text-align:left;"><?php echo $this->_tpl_vars['rec']['Productor']; ?>
</td>
	  <td style="vertical-align:middle; text-align:center;"> </td>
	  <td style="vertical-align:middle; text-align:left;"><?php echo $this->_tpl_vars['rec']['Marca']; ?>
</td>
	  <td style="vertical-align:middle; text-align:right;"><?php echo $this->_tpl_vars['rec']['Embarcadas']; ?>
</td>
	  <td style="vertical-align:middle; text-align:center;"></td>
	  <td style="vertical-align:middle; text-align:center;"></td>
	  <td style="vertical-align:middle; text-align:center;"></td>
	  <td style="vertical-align:middle; text-align:right;"><?php echo $this->_tpl_vars['rec']['Calidad']; ?>
</td>
	  <td style="vertical-align:middle; text-align:center;"></td>
	</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
 </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>