<?php /* Smarty version 2.6.18, created on 2009-10-23 12:52:28
         compiled from CoAdEf_balcom_.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoAdEf_balcom_.tpl', 11, false),array('block', 'report_header', 'CoAdEf_balcom_.tpl', 13, false),array('block', 'report_detail', 'CoAdEf_balcom_.tpl', 41, false),array('modifier', 'substr', 'CoAdEf_balcom_.tpl', 44, false),array('modifier', 'number_format', 'CoAdEf_balcom_.tpl', 56, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=11 align="center" style="text-align: center; font-weight:bold; "><?php echo $this->_tpl_vars['gsEmpresa']; ?>
</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=11 align="center" style="text-align: center; font-weight:bold; ">BALANCE GENERAL</td>
			</tr>
			<tr>
			  <td class="colhead"  colspan=11 align="center" style="text-align: center; font-weight:bold; ">Acumulado a: <?php echo $this->_tpl_vars['rec']['PERI']; ?>
 </td>
			</tr>
	      		    
			<tr>
			<td  align="center">Cuenta</td>
			<td  align="center">Auxiliar</td>
			<td  align="center">Descripcion</td>
			<td  align="center">Saldo Final</td>
			</tr>
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr style="white-space:nowrap;">
			
			<?php if (( ( ((is_array($_tmp=$this->_tpl_vars['rec']['CUE'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 1) : substr($_tmp, 0, 1)) ) == ' ' )): ?>
			  <td align="LEFT">&nbsp</td>
			<?php else: ?>
			  <td align="LEFT"><?php echo $this->_tpl_vars['rec']['CUE']; ?>
</td>
			<?php endif; ?>
			<?php if (( ( ((is_array($_tmp=$this->_tpl_vars['rec']['CUE'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 1) : substr($_tmp, 0, 1)) ) == ' ' )): ?>
			  <td align="LEFT"><?php echo $this->_tpl_vars['rec']['CUE']; ?>
</td>
			<?php else: ?>
			  <td align="LEFT">&nbsp</td>
			<?php endif; ?>
			
			<td align="Left"><?php echo $this->_tpl_vars['rec']['DES']; ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['SAB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>




</table>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>