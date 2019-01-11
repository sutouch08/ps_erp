<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';
require "../function/transport_helper.php";

if(isset($_GET['clearFilter']))
{
  deleteCookie('cus_search');
  deleteCookie('sender');
  echo 'done';
}

 ?>
