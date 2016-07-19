
<table background="../Images/p_header.gif" border="0" style="WIDTH: 988px; BORDER-COLLAPSE: collapse; HEIGHT: 50px" cellspacing="0" cellpadding="0">
  <tr style= "BORDER-COLLAPSE: collapse;" >
    <td class="" style= "BORDER-COLLAPSE: collapse;" align="left" nowrap width="33%">
		<font color="black" style="FONT-SIZE: 12px">
		&nbsp;&nbsp;{$smarty.session.g_user}
		</font>
	</td> 
    <td align="middle" nowrap valign="center" width="34%">
		<font color="black" style="FONT-WEIGHT: bold; FONT-SIZE: 12px">
			{$smarty.session.g_empr}
		</font> 
	</td> 
    <td align="right" nowrap width="33%">
	 	<font color="black" style="BORDER-COLLAPSE: collapse; FONT-SIZE: 12px">
			{$smarty.now|date_format:"%d-%b-%Y   %H:%M:%S    "}&nbsp;
		</font> 
 	</td> 
  </tr>
</table>
