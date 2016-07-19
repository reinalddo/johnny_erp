<?php /* Smarty version 2.6.18, created on 2009-04-21 12:35:43
         compiled from ../templates/Cabecera.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '../templates/Cabecera.tpl', 16, false),)), $this); ?>

<table background="../Images/p_header.gif" border="0" style="WIDTH: 988px; BORDER-COLLAPSE: collapse; HEIGHT: 50px" cellspacing="0" cellpadding="0">
  <tr style= "BORDER-COLLAPSE: collapse;" >
    <td class="" style= "BORDER-COLLAPSE: collapse;" align="left" nowrap width="33%">
		<font color="black" style="FONT-SIZE: 12px">
		&nbsp;&nbsp;<?php echo $_SESSION['g_user']; ?>

		</font>
	</td> 
    <td align="middle" nowrap valign="center" width="34%">
		<font color="black" style="FONT-WEIGHT: bold; FONT-SIZE: 12px">
			<?php echo $_SESSION['g_empr']; ?>

		</font> 
	</td> 
    <td align="right" nowrap width="33%">
	 	<font color="black" style="BORDER-COLLAPSE: collapse; FONT-SIZE: 12px">
			<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%b-%Y   %H:%M:%S    ") : smarty_modifier_date_format($_tmp, "%d-%b-%Y   %H:%M:%S    ")); ?>
&nbsp;
		</font> 
 	</td> 
  </tr>
</table>