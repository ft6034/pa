<html>
<head>
<title>設定系統參數</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--<link href="style.css" rel="stylesheet" type="text/css">-->
<!--shadowbox-->

<link rel="shortcut icon" href="http://www.ftstour.com.tw/FTSMVC/favicon.ico" type="image/x-icon" />

<script src="http://code.jquery.com/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="sb303/shadowbox.css" />

<script type="text/javascript" src="sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->
</head>
<body>
<table class="outtable">

 <script language="Javascript">
function playmidi()
{
   switch(midiform.mysel.selectedIndex)
   {
      case 1:
         parent.location.href="pclasspub.php?cid=1&mname=601";
         break;
      case 2:
         parent.location.href="pclasspub.php?cid=2&mname=602";
         break;
      case 3:
         parent.location.href="pclasspub.php?cid=3&mname=603";
         break;
      case 4:
         parent.location.href="pclasspub.php?cid=4&mname=604";
         break;
      case 5:
         parent.location.href="pclasspub.php?cid=5&mname=605";
         break;
   }
}
</script>

<form name=midiform>
<select name=mysel onchange="playmidi()">
<option>請選擇班級
<option>601
<option>602
<option>603
<option>604
<option>605
</select>
</form>

<a href="showpub?mid=1&sid=960142" rel="shadowbox;width=482;height=388">My Movie</a>

</table>
</body>
</html>