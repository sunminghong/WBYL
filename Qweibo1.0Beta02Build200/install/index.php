<?php
require_once("install.var.php");
require_once("install.func.php");
if (checkInstalled())
   {redirect("step4.html?action=refuse");}
else
   {redirect("step0.html");}
?>