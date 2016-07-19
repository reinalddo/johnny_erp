<?php /* Smarty version 2.6.18, created on 2015-06-17 13:30:44
         compiled from CoTrTr_CXCSimple.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_CXCSimple.tpl', 18, false),array('block', 'report_header', 'CoTrTr_CXCSimple.tpl', 20, false),array('block', 'report_detail', 'CoTrTr_CXCSimple.tpl', 95, false),array('block', 'report_footer', 'CoTrTr_CXCSimple.tpl', 124, false),array('modifier', 'date_format', 'CoTrTr_CXCSimple.tpl', 26, false),array('modifier', 'default', 'CoTrTr_CXCSimple.tpl', 60, false),array('modifier', 'number_format', 'CoTrTr_CXCSimple.tpl', 99, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>FACTURAS PENDIENTES</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "cue_Descripcion,txt_nombre")); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong>
	<br> <?php echo $this->_tpl_vars['subtitulo']; ?>

	<?php if (! $this->_tpl_vars['pcom_FecContab']): ?>
	  '<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
'
	<?php else: ?>
	  <?php echo $this->_tpl_vars['pcom_FecContab']; ?>

	<?php endif; ?>
	<br> 
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="9"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
    </tfoot>
    
    <tbody>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'cue_Descripcion')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr ><td colspan=9>
      <br>
      <p style="text-align: center; font:10; display:block; width=70%; font-size:1.20em;"><b> CUENTA: <?php echo $this->_tpl_vars['rec']['cue_Descripcion']; ?>
</b> </p> </td>
  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_header', array('group' => 'txt_nombre')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr >
    <td colspan=9>
      <p style="text-align: center; font:10; display:block; width=70%; font-size:1.20em;">
	  <b><?php echo $this->_tpl_vars['rec']['txt_nombre']; ?>
</b>
	  <br>
	    
	  <?php $this->assign('idAux', $this->_tpl_vars['rec']['det_idauxiliar']); ?>
  	  <?php $this->assign('stSaldo', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['idAux']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php $this->assign('stValor', ((is_array($_tmp=@$this->_tpl_vars['agValor'][$this->_tpl_vars['idAux']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  
	  <?php $this->assign('ndias', 15); ?>
	  <?php $this->assign('stSal15', ((is_array($_tmp=@$this->_tpl_vars['agSal'][$this->_tpl_vars['idAux']][$this->_tpl_vars['ndias']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  
	  <?php $this->assign('ndias', 30); ?>
	  <?php $this->assign('stSal30', ((is_array($_tmp=@$this->_tpl_vars['agSal'][$this->_tpl_vars['idAux']][$this->_tpl_vars['ndias']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  
	  <?php $this->assign('ndias', 60); ?>
	  <?php $this->assign('stSal60', ((is_array($_tmp=@$this->_tpl_vars['agSal'][$this->_tpl_vars['idAux']][$this->_tpl_vars['ndias']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  
	  <?php $this->assign('ndias', 90); ?>
	  <?php $this->assign('stSal90', ((is_array($_tmp=@$this->_tpl_vars['agSal'][$this->_tpl_vars['idAux']][$this->_tpl_vars['ndias']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  
	  <?php $this->assign('ndias', 91); ?>
	  <?php $this->assign('stSal91', ((is_array($_tmp=@$this->_tpl_vars['agSal'][$this->_tpl_vars['idAux']][$this->_tpl_vars['ndias']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>

      </p>
    </td>
  </tr>
<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">FECHA FACT.</td>
	<td class="headerrow"><strong>No DOC.</strong></td>
            	<td class="headerrow"><strong>VALOR<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>CORRIENTE</strong></td>
	<td class="headerrow"><strong>30 DIAS</strong></td>
	<td class="headerrow"><strong>31 - 60 DIAS</strong></td>
	<td class="headerrow"><strong>61 - 90 DIAS</strong></td>
	<td class="headerrow"><strong>+90 DIAS</strong></td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="text-align:center; vertical-align:middle;">
	<td class="coldata "> <?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['det_numdocum']; ?>
</td>
	<td class="colnum " ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValDebito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<?php if (( $this->_tpl_vars['rec']['Ndias'] == 15 )): ?>
	  <td> <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td><td></td><td></td><td></td><td></td>
	  <?php $this->assign('tSaldoC', $this->_tpl_vars['tSaldoC']+$this->_tpl_vars['rec']['txt_valor']); ?>
	<?php elseif (( $this->_tpl_vars['rec']['Ndias'] == 30 )): ?>
	  <td></td><td> <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td><td></td><td></td><td></td>
	  <?php $this->assign('tSaldo30', $this->_tpl_vars['tSaldo30']+$this->_tpl_vars['rec']['txt_valor']); ?>
	<?php elseif (( $this->_tpl_vars['rec']['Ndias'] == 60 )): ?>
	  <td></td><td></td><td> <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td><td></td><td></td>
	  <?php $this->assign('tSaldo60', $this->_tpl_vars['tSaldo60']+$this->_tpl_vars['rec']['txt_valor']); ?>
	<?php elseif (( $this->_tpl_vars['rec']['Ndias'] == 90 )): ?>
	  <td></td><td></td><td></td><td> <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td><td></td>
	  <?php $this->assign('tSaldo90', $this->_tpl_vars['tSaldo90']+$this->_tpl_vars['rec']['txt_valor']); ?>
	<?php elseif (( $this->_tpl_vars['rec']['Ndias'] == 91 )): ?>
	  <td></td><td></td><td></td><td></td><td> <?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php $this->assign('tSaldo_90', $this->_tpl_vars['tSaldo_90']+$this->_tpl_vars['rec']['txt_valor']); ?>
	<?php endif; ?>
	
  	<?php $this->assign('tValor', $this->_tpl_vars['tValor']+$this->_tpl_vars['rec']['det_ValDebito']); ?>
	<?php $this->assign('tSaldo', $this->_tpl_vars['tSaldo']+$this->_tpl_vars['rec']['txt_valor']); ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


 <?php $this->_tag_stack[] = array('report_footer', array('group' => 'txt_nombre')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr >
      <td></td>
       <td>Total por Cliente</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['stValor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['stSaldo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['stSal15'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['stSal30'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['stSal60'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['stSal90'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['stSal91'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       
       
    </tr>
    <tr></tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow"></td>
	<td class="headerrow">TOTAL</td>
            	<td class="headerrow"><strong>FACTURADO<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>CORRIENTE</strong></td>
	<td class="headerrow"><strong>30 DIAS</strong></td>
	<td class="headerrow"><strong>31 - 60 DIAS</strong></td>
	<td class="headerrow"><strong>61 - 90 DIAS</strong></td>
	<td class="headerrow"><strong>+90 DIAS</strong></td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle;">
      <td></td>
      <td></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tValor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tSaldo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tSaldoC'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tSaldo30'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tSaldo60'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tSaldo90'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tSaldo_90'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>