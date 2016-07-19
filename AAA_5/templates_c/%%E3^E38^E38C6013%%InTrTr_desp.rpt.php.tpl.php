<?php /* Smarty version 2.6.18, created on 2009-04-24 10:54:10
         compiled from InTrTr_desp.rpt.php.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_desp.rpt.php.tpl', 12, false),array('block', 'report_header', 'InTrTr_desp.rpt.php.tpl', 15, false),array('block', 'report_detail', 'InTrTr_desp.rpt.php.tpl', 54, false),array('block', 'report_footer', 'InTrTr_desp.rpt.php.tpl', 66, false),array('modifier', 'number_format', 'InTrTr_desp.rpt.php.tpl', 68, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DESPACHO SEMANAL <?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />

<body align:"center" id="top" style="font-family:'Arial'; ">

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "bodega, productor",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center">REPORTE DE DESPACHO SEMANAL</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center">MODULO INVENTARIO</td>
			</tr>
			<tr>
			  <td class="colhead"  colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="left">SEMANA: <?php echo $this->_tpl_vars['rec']['semana']; ?>
 </td>
			</tr>
	      		    
			<tr>
			<td colspan=2 align="left">Item</td>
			<td>Comprobante</td>
			<td># Unidades</td>
			<td>Precio </td>
			<td>Total</td>
			</tr>
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'bodega')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			<td height="40" class="colhead" style="vertical-align: bottom"  align="left" colspan=6 > Bodega: <?php echo $this->_tpl_vars['rec']['bodega']; ?>
</td>
       		</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'productor')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			<td height="40"  align="left" colspan=5 style="vertical-align: bottom"> Productor: <?php echo $this->_tpl_vars['rec']['productor']; ?>
</td>
       		</tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr>
			<td><?php echo $this->_tpl_vars['rec']['coditem']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['item']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['tipo_comp']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['cantidad']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['precio']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['total']; ?>
</td>
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array('group' => 'productor')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td colspan="5">Total_Productor:  <?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'bodega')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td colspan="5">Total_Bodega:  <?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">T O T A L</td>
			<td>&nbsp;</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			
		  </tr>
	  
		  
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

</tfoot>

</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>