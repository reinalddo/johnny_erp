<span style="font-family:Arial, Helvetica, sans-serif; font-size:8pt"><?php  echo $_SESSION['g_user']?>,
<script languaje="JavaScript">

var mydate=new Date()
var year=mydate.getYear()
if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
var dayarray=new Array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado")
var montharray=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
document.write (dayarray[day]+" "+daym+" de "+montharray[month]+" de "+year)

</script></span>
