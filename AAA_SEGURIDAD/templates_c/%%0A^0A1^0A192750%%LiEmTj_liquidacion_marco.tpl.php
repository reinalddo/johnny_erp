<?php /* Smarty version 2.6.18, created on 2012-04-27 17:52:15
         compiled from LiEmTj_liquidacion_marco.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'LiEmTj_liquidacion_marco.tpl', 12, false),array('block', 'report_header', 'LiEmTj_liquidacion_marco.tpl', 15, false),array('block', 'report_detail', 'LiEmTj_liquidacion_marco.tpl', 60, false),array('block', 'report_footer', 'LiEmTj_liquidacion_marco.tpl', 92, false),array('modifier', 'number_format', 'LiEmTj_liquidacion_marco.tpl', 95, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS <?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />

<body align:"center" id="top" style="font-family:'Arial'; ">

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >			  
			    <td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center"><?php echo $this->_tpl_vars['gsEmpresa']; ?>
</td>
			</tr>
			<tr >			  
	<td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center"><?php echo $this->_tpl_vars['gsSubTitul']; ?>
</td>			
</tr>
	<tr class="nowrap">
			<td>CODIGO</td>
			<td class="nowrap">NOMBRES </td>
			<td>CANT CAJAS</td>
			<td>PRECIO PAGADO</td>
			<td>VALOR FRUTA</td>
			<td>BONIFICACION</td>
			<td>COMPENSACIN</td>
			<td>EMPAQ.PAGDO</td>
			<td>OTROS_INGR.</td>
			<td>EMPAQ.COBR</td>
			<td>RET.FUENT</td>
			<td>ADELANTO</td>
			<td>ANTICIPOS</td>
			<td>PRESTAMOS</td>
			<td>INTER.PREST</td>
			<td>GTOS.ADMIN</td>
			<td>FERTILIZANT</td>
			<td>TRANSPORT</td>
			<td>INSUMOS</td>
			<td>TRANSFEREN</td>
			<td>VAL P/LIQ</td>
			<td>TCI</td>
			<td>OTROS.DESC</td>
			<td>NETO</td>
			<td>CHEQUE 1</td>
			<td>CHEQUE 2</td>
			</tr>
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr class="nowraprow" style="text-align:right">
			<td><?php echo $this->_tpl_vars['rec']['C0']; ?>
</td>
			<td style="text-align:left"><?php echo $this->_tpl_vars['rec']['C1']; ?>
</td>
			<td ><?php echo $this->_tpl_vars['rec']['C2']; ?>
</td>
			<td> <?php echo $this->_tpl_vars['rec']['PrecioPagado']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D1']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D2']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D3']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D4']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D5']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D10']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D11']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D12']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D13']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D14']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D15']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D16']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D21']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D22']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D24']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D25']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D26']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D27']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['D28']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['TT']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['E1']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['E2']; ?>
</td>
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr style="text-align:right"> 
			<td style="text-align:left" colspan=2>TOTALES:</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['C2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			
			<?php $this->assign('prom_precio', $this->_tpl_vars['sum']['D1']+$this->_tpl_vars['sum']['D2']); ?>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['prom_precio']/$this->_tpl_vars['sum']['C2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		  
		  
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D4'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D5'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D10'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D11'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D12'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D13'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D14'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D15'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D16'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D21'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D22'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D24'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D25'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D26'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D27'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['D28'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TT'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>






</tfoot>

</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>

