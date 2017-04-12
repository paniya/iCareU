
<html>
<body>
<?php

$con = mysqli_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysqli_select_db($con,"icareu");


?>
</body>
</html>



