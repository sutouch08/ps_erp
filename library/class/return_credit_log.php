<?php
class return_credit_log
{
  public function __construct(){}

  public function isExists($id_order, $id_customer)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_return_credit_log WHERE id_order = '".$id_order."' AND id_customer = '".$id_customer."'");
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }


  public function add_log($id_order, $id_customer, $amount)
  {
    $sc = FALSE;

    $id = $this->isExists($id_order, $id_customer);

    if( $id !== FALSE)
    {
      $sc = dbQuery("UPDATE tbl_return_credit_log SET amount = amount + ".$amount." WHERE id = '".$id."'");
    }
    else
    {
      $sc = dbQuery("INSERT INTO tbl_return_credit_log (id_order, id_customer, amount) VALUES ('".$id_order."', '".$id_customer."', ".$amount.")");
    }

    return $sc;
  }


  public function getReturnCreditLogAmount($id_order, $id_customer)
  {
    $sc = 0;
    $qs = dbQuery("SELECT amount FROM tbl_return_credit_log WHERE id_order = '".$id_order."' AND id_customer = '".$id_customer."'");
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }


}

 ?>
