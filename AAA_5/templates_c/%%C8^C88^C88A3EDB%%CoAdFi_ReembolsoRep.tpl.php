<?php /* Smarty version 2.6.18, created on 2011-08-30 11:33:20
         compiled from CoAdFi_ReembolsoRep.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoAdFi_ReembolsoRep.tpl', 18, false),array('block', 'report_header', 'CoAdFi_ReembolsoRep.tpl', 19, false),array('block', 'report_detail', 'CoAdFi_ReembolsoRep.tpl', 66, false),array('block', 'report_footer', 'CoAdFi_ReembolsoRep.tpl', 75, false),array('modifier', 'date_format', 'CoAdFi_ReembolsoRep.tpl', 30, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de las transacciones de COMEX
      MUESTRA LA INFORMACION DE LA TRANSACCION Y DE LA CONTABILIZACION
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <!-- meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" /-->
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>REEMBOLSO DE GASTOS</title>
  
</head>

<body id="top" style="font-family:'Arial'">

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <p style="text-align: left; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>
<br>
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="4"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
      </tr>
    </tfoot>
    
    <tbody>
      
    <tr>
      <td><strong>No. TRANSACCION:</strong></td><td><?php echo $this->_tpl_vars['rec']['ree_Id']; ?>
</td>
      <td><strong>FECHA DE EMISION:</strong></td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['fecTra'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
</td>
    </tr>
    <tr>
      <td ><strong>EMISOR:<strong></td><td colspan= 3><?php echo $this->_tpl_vars['rec']['tx_Emisor']; ?>
</td>
    </tr>
    <tr>
      <td ><strong>VALOR:<strong></td><td colspan= 3><?php echo $this->_tpl_vars['rec']['valTotal']; ?>
</td>
    </tr>
    <tr>
      <td><strong>CONCEPTO:<strong></td><td><?php echo $this->_tpl_vars['rec']['ree_Concepto']; ?>
</td>
      <td><strong>SEMANA:<strong></td><td><?php echo $this->_tpl_vars['rec']['semanaTra']; ?>
</td>
    </tr>
    <tr>
      <td><strong>ESTADO:<strong></td><td><?php echo $this->_tpl_vars['rec']['tx_Estado']; ?>
</td>
      <td><strong>USUARIO EMISION:<strong></td><td><?php echo $this->_tpl_vars['rec']['ree_Usuario']; ?>
</td>
    </tr>
    
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td colspan=4><br>DETALLE DE REEMBOLSO</td>
	</tr>  
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	    <td class="headerrow">SECUENCIA</td>
	    <td class="headerrow">MOTIVO</td>
	    <td class="headerrow">TIPO</td>
	    <td class="headerrow"><strong>VALOR</strong></td>
	</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
  <?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="text-align:center; vertical-align:middle;">
	  <td class="coldata" align="center"> <?php echo $this->_tpl_vars['rec']['red_Sec']; ?>
</td>
	  <td class="colnum"><?php echo $this->_tpl_vars['rec']['cue_Descripcion']; ?>
</td>
	  <td class="coldata"><?php echo $this->_tpl_vars['rec']['tx_Aux']; ?>
</td>
	  <td class="coldata"><?php echo $this->_tpl_vars['rec']['red_Valor']; ?>
</td>
      </tr>
  <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  
  <?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    </tbody>
    </table>
  <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<!-- REPORTE PARA LA PARTE CONTABLE (SI LA TRANSACCION HA SIDO CONTABILIZADA)-->
<?php if ($this->_tpl_vars['rec']['ree_NumComp'] > 0): ?>
<hr>
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agContab'],'record' => 'rec2','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <p style="text-align: left; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong> <br> CONTABILIZACION <br>
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="4"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
      </tr>
    </tfoot>
    
    <tbody>
    <tr>
      <td><strong>COMPROBANTE:</strong></td><td><?php echo $this->_tpl_vars['rec2']['com_TipoComp']; ?>
-<?php echo $this->_tpl_vars['rec2']['com_NumComp']; ?>
</td>
      <td><strong>VALOR:<strong></td><td><?php echo $this->_tpl_vars['rec2']['com_Valor']; ?>
</td>
    </tr>
    <tr><td colspan=4></td></tr>
    
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow"><strong>CUENTA</strong></td>
	<td class="headerrow">AUXILIAR</td>
	<td class="headerrow"><strong>DEBITO</strong></td>
	<td class="headerrow"><strong>CREDITO</strong></td>
    </tr>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
    <?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
      <tr style="vertical-align:middle;">
	    <td class="coldata" WIDTH=200><?php echo $this->_tpl_vars['rec2']['tx_Cuenta']; ?>
</td>
	    <td class="coldata" WIDTH=100> <?php echo $this->_tpl_vars['rec2']['tx_Auxiliar']; ?>
</td>
	    <td class="colnum"  WIDTH=100><?php echo $this->_tpl_vars['rec2']['det_ValDebito']; ?>
</td>
	    <td class="colnum"  WIDTH=100><?php echo $this->_tpl_vars['rec2']['det_ValCredito']; ?>
</td>
	</tr>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
    <?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	    <tr>
	      <td class="coldata" align = "center" colspan="2">
		  <br><br><br>________________________ </td>
	      <td class="coldata" align = "center" colspan="2"><br><br><br>________________________ </td>
	    </tr>
	    <tr>
	      <td class="coldata" align = "center" colspan="2"><I>Emitido por:<?php echo $this->_tpl_vars['rec2']['ree_Usuario']; ?>
</I></td>
	      <td class="coldata" align = "center" colspan="2"><I>Aprobado por:<?php echo $this->_tpl_vars['rec2']['ree_UsuAprueba']; ?>
</I></td>
	    </tr>
      </tbody>
      </table>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php endif; ?>
</body>