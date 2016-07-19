<?php /* Smarty version 2.6.18, created on 2009-05-13 10:37:40
         compiled from LiEmTr_dettarjdiario_total_prec_empresa.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'LiEmTr_dettarjdiario_total_prec_empresa.tpl', 11, false),array('block', 'report_header', 'LiEmTr_dettarjdiario_total_prec_empresa.tpl', 13, false),array('block', 'report_detail', 'LiEmTr_dettarjdiario_total_prec_empresa.tpl', 48, false),array('block', 'report_footer', 'LiEmTr_dettarjdiario_total_prec_empresa.tpl', 60, false),array('modifier', 'number_format', 'LiEmTr_dettarjdiario_total_prec_empresa.tpl', 52, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
<link rel="stylesheet" type="text/css" href="/AAA/AAA_5/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="/AAA/AAA_5/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="/AAA/AAA_5/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'groups' => "txp_Producto, txp_Shipper, txp_NombZona ",'record' => 'rec')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0">
			<tr>
			  <td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center"><?php echo $this->_tpl_vars['gsSubTitul']; ?>
</td>
			</tr>
			<tr style="height: 20px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:left">
			<td style="height: 20px; vertical-align:bottom; font-size:10; text-align:center; font-weight:bold;">PRODUCTOR</td>
			<td>TARJA #</td>
			<td>CJS EMBARC.</td>
			<td>BONIFIC</td>
			<td>PRECIO PACTADO</td>
			<td>VALOR</td>	
			</tr>
         	 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'txp_Producto')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr>
  <td colspan=6 style="height: 35px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:center">PRODUCTO: <?php echo $this->_tpl_vars['rec']['txp_Producto']; ?>
</td>
</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'txp_Shipper')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr>
  <td colspan=6 style="height: 25px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:center"><?php echo $this->_tpl_vars['rec']['txp_Shipper']; ?>
                         </td>
</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'txp_NombZona')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr>
  <td colspan=6 style="height: 20px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:left">ZONA: <?php echo $this->_tpl_vars['rec']['txp_NombZona']; ?>
</td>
</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr style="white-space:nowrap;">
			<td style="text-align:left;"><?php echo $this->_tpl_vars['rec']['txp_Productor']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_NumTarja']; ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_Bono'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_PrecUnit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txp_NombZona')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <?php $this->assign('flProm', $this->_tpl_vars['sum']['txp_ValTotal']/$this->_tpl_vars['sum']['txp_CantPagar']); ?>
  <tr style="white-space:nowrap">
      <td colspan="2">SUMA <?php echo $this->_tpl_vars['rec']['txp_NombZona']; ?>
 </td>
      <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
      <td class="colnum"> </td>
      <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_PrecUnit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>
      <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
   </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txp_Shipper')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <?php $this->assign('flProm', $this->_tpl_vars['sum']['txp_ValTotal']/$this->_tpl_vars['sum']['txp_CantPagar']); ?>
  <tr style="white-space:nowrap">
      <td>SUMA <?php echo $this->_tpl_vars['rec']['txp_Shipper']; ?>
 </td>
      <td></td>
      <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
   </tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txp_Producto')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <?php $this->assign('flProm', $this->_tpl_vars['sum']['txp_ValTotal']/$this->_tpl_vars['sum']['txp_CantPagar']); ?>
  <tr style="white-space:nowrap">
      <td>SUMA <?php echo $this->_tpl_vars['rec']['txp_Producto']; ?>
 </td>
      <td></td>
      <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
   </tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>