<?php /* Smarty version 2.6.18, created on 2009-09-23 21:57:15
         compiled from CoTrTr_archivobanco_pichincha.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_archivobanco_pichincha.tpl', 15, false),array('block', 'report_header', 'CoTrTr_archivobanco_pichincha.tpl', 16, false),array('block', 'report_detail', 'CoTrTr_archivobanco_pichincha.tpl', 29, false),array('block', 'report_footer', 'CoTrTr_archivobanco_pichincha.tpl', 37, false),array('modifier', 'date_format', 'CoTrTr_archivobanco_pichincha.tpl', 18, false),array('modifier', 'replace', 'CoTrTr_archivobanco_pichincha.tpl', 30, false),array('modifier', 'number_format', 'CoTrTr_archivobanco_pichincha.tpl', 32, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
    <!--<title>Archivo Pichincha</title>-->
  
</head>
<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        REPORTE DE MOVIMIENTOS DE CUENTAS<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>-->
    <table border=1 cellspacing=0 style="font-size:0.6em;">       
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['numcta'])) ? $this->_run_mod_handler('replace', true, $_tmp, '-', '') : smarty_modifier_replace($_tmp, '-', '')); ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['cheque']; ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, '.', '') : smarty_modifier_number_format($_tmp, 2, '.', '')); ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['beneficiario']; ?>
</td>	
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>