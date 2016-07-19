<?php /* Smarty version 2.6.18, created on 2011-08-17 15:37:45
         compiled from CoTrTr_comprob_html.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_comprob_html.tpl', 15, false),array('block', 'report_header', 'CoTrTr_comprob_html.tpl', 17, false),array('block', 'report_detail', 'CoTrTr_comprob_html.tpl', 62, false),array('block', 'report_footer', 'CoTrTr_comprob_html.tpl', 77, false),array('modifier', 'number_format', 'CoTrTr_comprob_html.tpl', 68, false),array('modifier', 'date_format', 'CoTrTr_comprob_html.tpl', 99, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de comprobantes (PC - Dinaser) -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <!--link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" /-->
  <!--link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" /-->
    <title>COMPROBANTE</title>
  
</head>

<body id="top" style="font-size:11px; font-family: sans-serif;">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <table cellspacing=0 style=" margin-top:20px; width:100%;">
        <tr>
            <td style="width:60%; text-align: center; font-size:14px;"><strong><?php echo $_SESSION['g_empr']; ?>
</strong></td>
            <td style="width:10%; text-align: left;"><strong>RUC:</strong></td>
            <td style="width:30%; text-align: left;"><?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>
        </tr>
        <tr>
            <td collspan = 3 style="width:100%; text-align: center; font-size:12px;"><strong><?php echo $this->_tpl_vars['rec']['TXT']; ?>
</strong></td>
        </tr>
    </table>
    
    
    <table cellspacing=0 style=" margin-top:20px; width:100%;">
            <tr>
               <td style="width:10%;"><strong>COMPROBANTE:</strong></td>
               <td style="width:60%;"><?php echo $this->_tpl_vars['rec']['TIP']; ?>
-<?php echo $this->_tpl_vars['rec']['COM']; ?>
</td>
               <td style="width:10%;"><strong>FECHA</strong></td>
               <td style="width:10%;"><?php echo $this->_tpl_vars['rec']['FCO']; ?>
</td>
            </tr>
            <tr>
               <td style="width:10%;"><strong>PROVEEDOR</strong></td>
               <td style="width:60%;"><?php echo $this->_tpl_vars['rec']['NRE']; ?>
</td>
               <td style="width:10%;"><strong>EMISION</strong></td>
               <td style="width:10%;"><?php echo $this->_tpl_vars['rec']['FTR']; ?>
</td>
            </tr>
            <tr>
               <td style="width:10%;"><strong>CONCEPTO</strong></td>
               <td style="width:60%;"><?php echo $this->_tpl_vars['rec']['CON']; ?>
</td>
               <td style="width:10%;"><strong>VENCIMIENTO</strong></td>
               <td style="width:10%;"><?php echo $this->_tpl_vars['rec']['FVE']; ?>
</td>
            </tr>
    </table>
            
   <table cellspacing=0 style=" margin-top:20px;width:100%;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td style="width:10%;">CTA/AUX</td>
            <td style="width:65%;"><strong>DESCRIPCION</strong></td>
            <td style="width:5%;"># DOC</td>
            <td style="width:10%;"><strong>DEBITO</strong></td>
            <td style="width:10%;"><strong>CREDITO</strong></td>
        </tr>
        <tr><td style="width:100%;"colspan=5><hr style="height:1px; border-bottom: 1px solid;"></td></tr>
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <table cellspacing=0 style=" margin-top:0px;width:100%;">
    <tr style="text-align:left; vertical-align:middle;">
              <td style="width:10%"><?php echo $this->_tpl_vars['rec']['CCU']; ?>
<br>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['rec']['CAU']; ?>
</td>
              <td style="width:65%"><?php echo $this->_tpl_vars['rec']['CUE2']; ?>
<br><?php echo $this->_tpl_vars['rec']['DES']; ?>
</td> <!-- PARA DINASER -->
              <td style="width:5%; text-align: center;"><?php echo $this->_tpl_vars['rec']['CHE']; ?>
</td>
              <td style="width:10%; text-align: right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VDB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
              <td style="width:10%; text-align: right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VCR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
              
              <?php $this->assign('VDB', $this->_tpl_vars['VDB']+$this->_tpl_vars['rec']['VDB']); ?>
              <?php $this->assign('VCR', $this->_tpl_vars['VCR']+$this->_tpl_vars['rec']['VCR']); ?>
              
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;text-align:left; vertical-align:middle;">
       <td style="width:10%;"></td>
       <td style="width:65%;">SUMAN&nbsp;&nbsp;&nbsp;U.S.&nbsp;&nbsp;Dolares: </td>
       <td style="width:5%;"></td>
       <td style="width:10%; text-align: right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['VDB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td style="width:10%; text-align: right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['VCR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
   </table>
  
  <table cellspacing=0 style=" margin-top:5px;width:100%;">
        <tr><td style="width:100%;"colspan=4><hr style="height:1px; border-bottom: 1px solid;"></td></tr>
        <tr>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Emitido Por</td>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Contabilidad</td>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Gerencia</td>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Recibi Conforme</td>
        </tr>
        <tr>
            <td colspan="4"><br><b>Usuario:</b> <?php echo $_SESSION['g_user']; ?>
 </td>
        </tr>
        <tr>
            <td colspan="4"><b>Fecha de Impresion:</b> <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
        </tr>
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>