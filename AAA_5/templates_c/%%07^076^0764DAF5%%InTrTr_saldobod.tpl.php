<?php /* Smarty version 2.6.18, created on 2009-05-26 12:10:13
         compiled from InTrTr_saldobod.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_saldobod.tpl', 11, false),array('block', 'report_header', 'InTrTr_saldobod.tpl', 13, false),array('block', 'report_detail', 'InTrTr_saldobod.tpl', 50, false),array('block', 'report_footer', 'InTrTr_saldobod.tpl', 64, false),array('modifier', 'number_format', 'InTrTr_saldobod.tpl', 55, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS <?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
<body align:"center" id="top" style="font-family:'Arial'; ">

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'groups' => "bodeg,grupo",'record' => 'rec')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	  
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  
	  <thead>
	    
	    <tr>
	      <td style="text-align:center" colspan=9>LISTADO PARA TOMA FISICA DE INVENTARIO</td>
	    </tr>
			<tr>
			<td>COD</td>
			<td>ITEM</td>
			<td>UNIDAD</td>
			<td>SALDO PREVIO</td>
			<td>CANT INGRESOS</td>
			<td>CANT EGRESOS</td>
			<td>SALDO FINAL</td>
			<td>PRECIO UNI</td>
			<td>TOMA FISICA</td>	
			</tr>
         </thead>	 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'bodeg')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			<td style="text-align:center" colspan=9> <?php echo $this->_tpl_vars['rec']['bodeg']; ?>
</td>
       		</tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'grupo')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			<td></td>
			<td style="text-align:left" colspan=9><?php echo $this->_tpl_vars['rec']['GRU']; ?>
 <?php echo $this->_tpl_vars['rec']['grupo']; ?>
</td>
       		</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr style="white-space:nowrap">
			<td><?php echo $this->_tpl_vars['rec']['ITE']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['DES']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['UNI']; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['SAN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CIN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CEG'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>		
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['SAC'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PUN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 5) : smarty_modifier_number_format($_tmp, 5)); ?>
</td>
			
			
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_footer', array('group' => 'grupo')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td colspan="8" style="text-align:right"><?php echo $this->_tpl_vars['rec']['grupo']; ?>
 Subtotal: <?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['PUN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 5) : smarty_modifier_number_format($_tmp, 5)); ?>
</td>
			<td>&nbsp;</td>
			
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td colspan="8" style="text-align:center">TOTAL : <?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['PUN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 5) : smarty_modifier_number_format($_tmp, 5)); ?>
</td>
		  
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>