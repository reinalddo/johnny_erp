<?php /* Smarty version 2.6.18, created on 2010-03-26 16:26:26
         compiled from CoTrTr_CXCSimple.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_CXCSimple.tpl', 18, false),array('block', 'report_header', 'CoTrTr_CXCSimple.tpl', 20, false),array('block', 'report_detail', 'CoTrTr_CXCSimple.tpl', 63, false),array('block', 'report_footer', 'CoTrTr_CXCSimple.tpl', 82, false),array('modifier', 'date_format', 'CoTrTr_CXCSimple.tpl', 32, false),array('modifier', 'default', 'CoTrTr_CXCSimple.tpl', 56, false),array('modifier', 'str_pad', 'CoTrTr_CXCSimple.tpl', 69, false),array('modifier', 'number_format', 'CoTrTr_CXCSimple.tpl', 71, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Cuadro</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true,'groups' => "txt_nombre, dias")); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong>
	<br> <?php echo $this->_tpl_vars['subtitulo']; ?>

	<br> <?php echo $this->_tpl_vars['rec']['cue_Descripcion']; ?>

    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="16"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">FECHA FACTURA</td>
	<td class="headerrow"><strong>No DOCUMENTO</strong></td>
        <td class="headerrow"><strong>CLIENTE</strong></td>
	<td class="headerrow"><strong>CONCEPTO</strong></td>
    	<td class="headerrow"><strong>VALOR TOTAL<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>USUARIO/FECHA DE DIGITACION</strong></td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_header', array('group' => 'dias')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr >
    <td colspan=7>
      <p style="text-align: center; font:10; display:block; width=70%; font-size:0.8em;">
	  <strong> <?php echo $this->_tpl_vars['rec']['txt_nombre']; ?>
 <strong>
	  <br> Dias vencidos <?php echo $this->_tpl_vars['rec']['dias']; ?>

	  <?php $this->assign('idAux', $this->_tpl_vars['rec']['det_idauxiliar']); ?>
	  <?php $this->assign('ndias', $this->_tpl_vars['rec']['dias']); ?>
	  <?php $this->assign('stSaldo', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['idAux']][$this->_tpl_vars['ndias']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php $this->assign('stValor', ((is_array($_tmp=@$this->_tpl_vars['agValor'][$this->_tpl_vars['idAux']][$this->_tpl_vars['ndias']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	 </p>
    </td>
  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="text-align:center; vertical-align:middle;">
      <td class="coldata "> <?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['det_numdocum']; ?>
</td>
	<td class="coldata "align="left"><?php echo $this->_tpl_vars['rec']['txt_nombre']; ?>
</td>
	<td class="coldata "align="left"><?php echo $this->_tpl_vars['rec']['com_Concepto']; ?>
</td>
	<!-- <td class="colnum " ><a href= "../Co_Files/CoTrTr_CuadroDet.rpt.php?&pidProvFact=<?php echo $this->_tpl_vars['rec']['idProvFact']; ?>
&pnombreProveedor=<?php echo $this->_tpl_vars['rec']['nombreProveedor']; ?>
&psecuencial=<?php echo $this->_tpl_vars['rec']['secuencial']; ?>
" target="_blank">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['establecimiento'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmision'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secuencial'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 7, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 7, '0', 'STR_PAD_LEFT')); ?>
</td> 
        <td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['estabretencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmiRetencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secretencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 7, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 7, '0', 'STR_PAD_LEFT')); ?>
</td> -->
	<td class="colnum " ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValDebito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txt_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['usuario']; ?>
</td>
	
	
  	<?php $this->assign('tValor', $this->_tpl_vars['tValor']+$this->_tpl_vars['rec']['det_ValDebito']); ?>
	<?php $this->assign('tSaldo', $this->_tpl_vars['tSaldo']+$this->_tpl_vars['rec']['txt_valor']); ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array('group' => 'dias')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr >
      <td></td><td></td><td></td>
       <td><strong> Subtotal <?php echo $this->_tpl_vars['rec']['txt_nombre']; ?>
 Dias vencidos <?php echo $this->_tpl_vars['rec']['dias']; ?>
 <strong></td>
       <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['stValor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</strong></td>
       <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['stSaldo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</strong></td>
       <td></td>      
    </tr>
    <tr></tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
      <td></td><td></td><td></td>
       <td> Totales: </td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tValor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tSaldo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td></td>
    </tr>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>