<?php

require "../../library/config.php";

if( isset($_GET['getCurrentState']))
{
  $id = $_GET['id_order'];
  $order = new order();
  echo $order->getState($id);
}

 ?>
