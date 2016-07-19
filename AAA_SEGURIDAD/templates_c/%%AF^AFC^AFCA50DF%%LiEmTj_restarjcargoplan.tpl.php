<?php /* Smarty version 2.6.18, created on 2009-04-20 18:04:12
         compiled from LiEmTj_restarjcargoplan.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'LiEmTj_restarjcargoplan.tpl', 11, false),array('block', 'report_header', 'LiEmTj_restarjcargoplan.tpl', 13, false),array('block', 'report_detail', 'LiEmTj_restarjcargoplan.tpl', 51, false),array('block', 'report_footer', 'LiEmTj_restarjcargoplan.tpl', 63, false),array('modifier', 'upper', 'LiEmTj_restarjcargoplan.tpl', 26, false),array('modifier', 'number_format', 'LiEmTj_restarjcargoplan.tpl', 57, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Fausto Astudillo" />
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "txt_vapor, txt_producto",'resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 650px !important ">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <caption> CARGO PLAN</caption>
	<?php $this->_tpl_vars['rowcls'] = 'rowpar'; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'txt_vapor')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	  <thead>
		<tr>
		  <td colspan=<?php echo $this->_tpl_vars['agNumCols']; ?>
  class="colhead headerrow grouphead"  style="font-weight:bold !important; text-align:'center'; height:40px">
			  SEMANA: <?php echo $this->_tpl_vars['pSem']; ?>
 &nbsp;  &nbsp; &nbsp; &nbsp; <br></br>VAPOR: <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_vapor'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:80px;">BODEGA</td>
		  <td class="headerrow" style="width:100px;" >PISO</td>
			<?php $_from = $this->_tpl_vars['agNombres']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
			  <td class="colhead headerrow coldat" ><?php echo ((is_array($_tmp=$this->_tpl_vars['col'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
			<?php endforeach; endif; unset($_from); ?>
		  <td class="colhead headerrow" >TOTAL</td>
		</tr>
	  </thead>
	  <tfoot>
		  <tr> 
			<td colspan=<?php echo $this->_tpl_vars['agNumCols']; ?>
></td>
		  </tr>
	  </tfoot>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'txt_producto')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<tr ><td colspan=<?php echo $this->_tpl_vars['agNumCols']; ?>
  class="colhead headerrow grouphead" style="font-weight:bold !important; text-align:'center'; height:40px">
			  
			  <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_producto'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

		</td></tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	  <tbody>
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<tr class="<?php echo $this->_tpl_vars['rowcls']; ?>
">
		<?php if ($this->_tpl_vars['rowcls'] == 'rowpar'): ?><?php $this->_tpl_vars['rowcls'] = 'rowimpar'; ?><?php endif; ?> 
		  <td class="colnom " style="width:80px;" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['tac_Bodega'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
		  <td class="colnom" style="width:120px; white-space: nowrap; " ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['tac_Piso'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
			<?php $_from = $this->_tpl_vars['agNombres']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
				<td class="coldat colnum" ><?php if ($this->_tpl_vars['rec'][$this->_tpl_vars['col']] == 0): ?>&nbsp;<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['rec'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
<?php endif; ?></td>
			<?php endforeach; endif; unset($_from); ?>
			<td class="coldat colnum col120"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['sumCant'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txt_producto')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<tr >
			<td style="font-weight:bold !important" colspan=2>SUMA <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_producto'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
			  <?php $_from = $this->_tpl_vars['agNombres']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
				  <td class="coldat colnum " style="font-weight:bold !important" ><?php if ($this->_tpl_vars['sum'][$this->_tpl_vars['col']] == 0): ?><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sum'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
<?php endif; ?></td>
			  <?php endforeach; endif; unset($_from); ?>
			<td class="coldat colnum col120"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['sumCant'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txt_vapor')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<td style="font-weight:bold !important" colspan=2>SUMA <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_vapor'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
			  <?php $_from = $this->_tpl_vars['agNombres']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
				  <td class="coldat colnum " style="font-weight:bold !important" ><?php if ($this->_tpl_vars['sum'][$this->_tpl_vars['col']] == 0): ?><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sum'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
<?php endif; ?></td>
			  <?php endforeach; endif; unset($_from); ?>
			<td class="colnum col120"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['sumCant'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<tr >
			<td style="font-weight:bold !important">TOTAL <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_vapor'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
			  <?php $_from = $this->_tpl_vars['agNombres']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
				  <td class="coldat colnum " style="font-weight:bold !important"><?php if ($this->_tpl_vars['sum'][$this->_tpl_vars['col']] == 0): ?><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sum'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
<?php endif; ?></td>
			  <?php endforeach; endif; unset($_from); ?>
			<td class="colnum col120"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['sumCant'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		</tr>
      <tr><td colspan=<?php echo $this->_tpl_vars['agNumCols']; ?>
  ><?php echo $this->_tpl_vars['usuario']; ?>
, <?php echo $this->_tpl_vars['fecha']; ?>
<td></tr>
	  </tbody>
    <tfoot>
      <tr><td colspan=<?php echo $this->_tpl_vars['agNumCols']; ?>
  ><?php echo $this->_tpl_vars['usuario']; ?>
, <?php echo $this->_tpl_vars['fecha']; ?>
<td></tr>
      <tfoot>
	</table>  
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  </div>
</div> <!-- end container -->
</body>


