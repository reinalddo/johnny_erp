<?php /* Smarty version 2.6.18, created on 2010-10-25 17:48:08
         compiled from InTrTr_facturaImpresa.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_facturaImpresa.tpl', 10, false),array('block', 'report_header', 'InTrTr_facturaImpresa.tpl', 12, false),array('block', 'report_detail', 'InTrTr_facturaImpresa.tpl', 26, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Erika Suarez" />
  <title> Factura </title>
</head>

<body id="top" style=" font:'helvetica'; !important;  font-size:10px;" onload="window.print();">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!-- Logo -->
    <?php if ($this->_tpl_vars['rec']['conf_logo'] == 1): ?>
        <table>
            <tr> <td> <img src= "<?php echo $this->_tpl_vars['rec']['conf_rutalogo']; ?>
"> </td> </tr>
        </table>
    <?php endif; ?>
    <!-- CABECERA DEL DOCUMENTO -->
<table width="<?php echo $this->_tpl_vars['rec']['conf_longdef']; ?>
" cellspacing=0 style=" border:0; border-color: #504A4B ;solid 1px; text-align:left;font-size:11px;">
  <tr>
    <td>
      <hr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
      
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table>
        <tr>
            <?php if ($this->_tpl_vars['rec']['conf_c1'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc1']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc1']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc1']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c2'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc2']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc2']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc2']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c3'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc3']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc3']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc3']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c4'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc4']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc4']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc4']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c5'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc5']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc5']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc5']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c6'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc6']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc6']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc6']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c7'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc7']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc7']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc7']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c8'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc8']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc8']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc8']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c9'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc9']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc9']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc9']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec']['conf_c10'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec']['conf_longc10']; ?>
" > <strong><?php echo $this->_tpl_vars['rec']['conf_textoc10']; ?>
</strong> <?php echo $this->_tpl_vars['rec']['datoc10']; ?>
 </td> <?php endif; ?>
</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    <hr>
      <br>
    </td>
    </tr>
  </table>
  
<!-- DETALLE DEL DOCUMENTO -->
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agDet'],'record' => 'rec2','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="<?php echo $this->_tpl_vars['rec2']['conf_longdef']; ?>
" cellspacing=0 style=" border:0; border-color: #504A4B ; font-size:10px; text-align:left;">
        <tr>
            <?php if ($this->_tpl_vars['rec2']['conf_c1'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc1']; ?>
" > <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc1']; ?>
</strong> </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c2'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc2']; ?>
"> <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc2']; ?>
</strong>  </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c3'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc3']; ?>
" > <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc3']; ?>
</strong> </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c4'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc4']; ?>
"> <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc4']; ?>
</strong>  </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c5'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc5']; ?>
" > <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc5']; ?>
</strong> </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c6'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc6']; ?>
" > <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc6']; ?>
</strong> </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c7'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc7']; ?>
"> <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc7']; ?>
</strong>  </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c8'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc8']; ?>
" > <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc8']; ?>
</strong> </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c9'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc9']; ?>
"> <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc9']; ?>
</strong>  </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c10'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc10']; ?>
" > <strong><?php echo $this->_tpl_vars['rec2']['conf_textoc10']; ?>
</strong> </td> <?php endif; ?>
        </tr>
</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="<?php echo $this->_tpl_vars['rec2']['conf_longdef']; ?>
" cellspacing=0 style="  font-size:10px; text-align:left;">
        <tr>
            <?php if ($this->_tpl_vars['rec2']['conf_c1'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc1']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc1']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c2'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc2']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc2']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c3'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc3']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc3']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c4'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc4']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc4']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c5'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc5']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc5']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c6'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc6']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc6']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c7'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc7']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc7']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c8'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc8']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc8']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c9'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc9']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc9']; ?>
 </td> <?php endif; ?>
            <?php if ($this->_tpl_vars['rec2']['conf_c10'] == 1): ?> <td width="<?php echo $this->_tpl_vars['rec2']['conf_longc10']; ?>
"> <?php echo $this->_tpl_vars['rec2']['datoc10']; ?>
 </td> <?php endif; ?>
        </tr>
</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>