<?php /* Smarty version 2.6.18, created on 2010-09-29 14:57:47
         compiled from CoTrTr_cierrecajaCta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_cierrecajaCta.tpl', 21, false),array('block', 'report_header', 'CoTrTr_cierrecajaCta.tpl', 23, false),array('block', 'report_detail', 'CoTrTr_cierrecajaCta.tpl', 50, false),array('block', 'report_footer', 'CoTrTr_cierrecajaCta.tpl', 73, false),array('modifier', 'number_format', 'CoTrTr_cierrecajaCta.tpl', 53, false),array('modifier', 'default', 'CoTrTr_cierrecajaCta.tpl', 53, false),array('modifier', 'date_format', 'CoTrTr_cierrecajaCta.tpl', 88, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Cierre de Caja por cuenta contable -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  <?php echo '
  <style type="text/css">
        .tot{background-color:#D9D9D9;}
    </style>
  '; ?>

  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CIERRE DE CAJA</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->assign('acum', 0); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        CIERRE DE CAJA<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    <table border=1 cellspacing=0 >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">CAJA/BANCOS</td>
            <td class="headerrow"><strong>SDO/INICIAL</strong></td>
            <td class="headerrow"><strong>(+) DEP.</strong></td>
            <td class="headerrow"><strong>(+) N/C</strong></td>
	    <td class="headerrow" style="background-color:#E6E6E6;"><strong>CH. N/A.</strong></td>
            <td class="headerrow" style="background-color:#E6E6E6;"><strong>CH. EMITIDOS <br> CUSTODIA.</strong></td>
            <td class="headerrow"><strong>(-) CHEQUES <br> GIRADOS</strong></td>
            <td class="headerrow"><strong>N/D</strong></td>
            <td class="headerrow"><strong>SDO/FINAL</strong></td>
        </tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
	  <td class="coldata "> <?php echo $this->_tpl_vars['rec']['per_CodAuxiliar']; ?>
 - <?php echo $this->_tpl_vars['rec']['aux']; ?>
</td>
	  <td class="colnum "> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['INI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  <td class="colnum "> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['DEP'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  <td class="colnum "> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['NC'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  <td class="colnum "style="background-color:#E6E6E6;color:#424242;"> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['NA'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  <td class="colnum "style="background-color:#E6E6E6;color:#424242;"> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['EMI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  <td class="colnum "> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['CHEQUES'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  <td class="colnum "> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['ND'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  <td class="colnum "><strong><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['saldo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  
	  <?php $this->assign('Tini', $this->_tpl_vars['Tini']+$this->_tpl_vars['rec']['INI']); ?>
	  <?php $this->assign('TDEP', $this->_tpl_vars['TDEP']+$this->_tpl_vars['rec']['DEP']); ?>
	  <?php $this->assign('TNC', $this->_tpl_vars['TNC']+$this->_tpl_vars['rec']['NC']); ?>
	  <?php $this->assign('TNA', $this->_tpl_vars['TNA']+$this->_tpl_vars['rec']['NA']); ?>
	  <?php $this->assign('TEMI', $this->_tpl_vars['TEMI']+$this->_tpl_vars['rec']['EMI']); ?>
	  <?php $this->assign('TCHEQUES', $this->_tpl_vars['TCHEQUES']+$this->_tpl_vars['rec']['CHEQUES']); ?>
	  <?php $this->assign('TND', $this->_tpl_vars['TND']+$this->_tpl_vars['rec']['ND']); ?>
	  <?php $this->assign('Tsaldo', $this->_tpl_vars['Tsaldo']+$this->_tpl_vars['rec']['saldo']); ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr>
	  <td class="coldata"style="background-color:#BDBDBD;"><strong> TOTAL:</td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Tini'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['TDEP'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['TNC'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['TNA'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['TEMI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['TCHEQUES'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['TND'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Tsaldo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</strong></td>
</tr>
 </table>
    <div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong><?php echo $_SESSION['g_user']; ?>
</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</p>
        <p style="line-height:0.5em;"><?php echo $this->_tpl_vars['agArchivo']; ?>
</p>
    </div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>