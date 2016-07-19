<?php /* Smarty version 2.6.18, created on 2009-04-01 14:41:09
         compiled from OpTrTr_restarjconten.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'OpTrTr_restarjconten.tpl', 12, false),array('block', 'report_header', 'OpTrTr_restarjconten.tpl', 14, false),array('block', 'report_detail', 'OpTrTr_restarjconten.tpl', 43, false),array('block', 'report_footer', 'OpTrTr_restarjconten.tpl', 62, false),array('function', 'concat', 'OpTrTr_restarjconten.tpl', 39, false),array('modifier', 'number_format', 'OpTrTr_restarjconten.tpl', 46, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <meta name="author" content="Fausto Astudillo" />
    <link rel="stylesheet" type="text/css" href="../css/report.css" title="Default CSS Sheet" />
    <title>CONTENEDORES</title>
  </head>

  <body id="top">

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "txt_naviera, txt_embarque, txt_contenedor, txt_producto, txt_productor, txt_marcemp, txt_empacador",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:16; font-weight:bold"> <?php echo $this->_tpl_vars['rec']['txt_embarque']; ?>
</span>
    <table border=1 cellspacing=0 >

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'txt_contenedor')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr ><td colspan=5>CONTENEDOR <?php echo $this->_tpl_vars['rec']['txt_tipo']; ?>
: <?php echo $this->_tpl_vars['rec']['txt_contenedor']; ?>
</td><td colspan=1><?php echo $this->_tpl_vars['rec']['txt_naviera']; ?>
</td></tr>
    <tr ><td colspan=6>SELLO: <?php echo $this->_tpl_vars['rec']['region']; ?>
</td></tr>
    <tr><td class="colhead headerrow">PRODUCTOR</td>
	    <td class="colhead headerrow">CODIGOS<td class="colhead headerrow">
        <?php unset($this->_sections['col']);
$this->_sections['col']['name'] = 'col';
$this->_sections['col']['loop'] = is_array($_loop=$this->_tpl_vars['agCabeceras']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['col']['show'] = true;
$this->_sections['col']['max'] = $this->_sections['col']['loop'];
$this->_sections['col']['step'] = 1;
$this->_sections['col']['start'] = $this->_sections['col']['step'] > 0 ? 0 : $this->_sections['col']['loop']-1;
if ($this->_sections['col']['show']) {
    $this->_sections['col']['total'] = $this->_sections['col']['loop'];
    if ($this->_sections['col']['total'] == 0)
        $this->_sections['col']['show'] = false;
} else
    $this->_sections['col']['total'] = 0;
if ($this->_sections['col']['show']):

            for ($this->_sections['col']['index'] = $this->_sections['col']['start'], $this->_sections['col']['iteration'] = 1;
                 $this->_sections['col']['iteration'] <= $this->_sections['col']['total'];
                 $this->_sections['col']['index'] += $this->_sections['col']['step'], $this->_sections['col']['iteration']++):
$this->_sections['col']['rownum'] = $this->_sections['col']['iteration'];
$this->_sections['col']['index_prev'] = $this->_sections['col']['index'] - $this->_sections['col']['step'];
$this->_sections['col']['index_next'] = $this->_sections['col']['index'] + $this->_sections['col']['step'];
$this->_sections['col']['first']      = ($this->_sections['col']['iteration'] == 1);
$this->_sections['col']['last']       = ($this->_sections['col']['iteration'] == $this->_sections['col']['total']);
?>
            <?php echo $this->_tpl_vars['agCabeCeras'][$this->_tpl_vars['col']]; ?>
</td><td class="colhead headerrow">
        <?php endfor; endif; ?>
        TOTAL</td></tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'txt_productor')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?> 
	<?php $this->assign('tplCodigos', ""); ?>
	<?php unset($this->_sections['col']);
$this->_sections['col']['name'] = 'col';
$this->_sections['col']['loop'] = is_array($_loop=$this->_tpl_vars['agMarcas']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['col']['show'] = true;
$this->_sections['col']['max'] = $this->_sections['col']['loop'];
$this->_sections['col']['step'] = 1;
$this->_sections['col']['start'] = $this->_sections['col']['step'] > 0 ? 0 : $this->_sections['col']['loop']-1;
if ($this->_sections['col']['show']) {
    $this->_sections['col']['total'] = $this->_sections['col']['loop'];
    if ($this->_sections['col']['total'] == 0)
        $this->_sections['col']['show'] = false;
} else
    $this->_sections['col']['total'] = 0;
if ($this->_sections['col']['show']):

            for ($this->_sections['col']['index'] = $this->_sections['col']['start'], $this->_sections['col']['iteration'] = 1;
                 $this->_sections['col']['iteration'] <= $this->_sections['col']['total'];
                 $this->_sections['col']['index'] += $this->_sections['col']['step'], $this->_sections['col']['iteration']++):
$this->_sections['col']['rownum'] = $this->_sections['col']['iteration'];
$this->_sections['col']['index_prev'] = $this->_sections['col']['index'] - $this->_sections['col']['step'];
$this->_sections['col']['index_next'] = $this->_sections['col']['index'] + $this->_sections['col']['step'];
$this->_sections['col']['first']      = ($this->_sections['col']['iteration'] == 1);
$this->_sections['col']['last']       = ($this->_sections['col']['iteration'] == $this->_sections['col']['total']);
?>
		<?php $this->_tpl_vars['agSumas'][$this->_tpl_vars['col']] = ''; ?>
  <?php endfor; endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'txt_empacador')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php echo smarty_function_concat(array('var' => 'tplCodigos','value' => ", "), $this);?>

	<?php echo smarty_function_concat(array('var' => 'tplCodigos','value' => $this->_tpl_vars['rec']['txt_empacador']), $this);?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td nowrap><?php echo $this->_tpl_vars['rec']['txt_productor']; ?>
</td><td class="colnum "><?php echo $this->_tpl_vars['tplCodigos']; ?>
</td>
        <?php $_from = $this->_tpl_vars['agCols']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
            <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
        <?php endforeach; endif; unset($_from); ?>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['c_total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txt_contenedor')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr >
        <td>TOTALES </td><td> - -</td>
            <?php $_from = $this->_tpl_vars['agCols']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
                <td class="colnum"><?php echo $this->_tpl_vars['sum'][$this->_tpl_vars['col']]; ?>
</td>
            <?php endforeach; endif; unset($_from); ?>
        <td class="colnum headerrow"><?php echo $this->_tpl_vars['sum']['c_total']; ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
    </tr>
    </table>
    <table>
    	<tr>
    		<td>RESUMEN DE MARCAS</td><td>TOTAL</td><td>PORCENTAJE</td>
    	</tr>
    	<?php $_from = $this->_tpl_vars['agResumen']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['linea']):
        $this->_foreach['outer']['iteration']++;
?>
    	<tr>
  			<?php $_from = $this->_tpl_vars['linea']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['col']):
?>
    			<td><?php echo $this->_tpl_vars['col']; ?>
</td>
  			<?php endforeach; endif; unset($_from); ?>
    	</tr>
		<?php endforeach; endif; unset($_from); ?>
    </table>
    <hr/>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>


