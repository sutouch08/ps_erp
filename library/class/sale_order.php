<?php
/**
 *
 */
class sale_order extends order
{

  function __construct($id='')
  {
    # code...
  }

  public function getSaleOrderNotSave($id_sale, $id_emp)
  {
    return dbQuery("SELECT * FROM tbl_order WHERE id_employee = ".$id_emp." AND id_sale = ".$id_sale." AND status = 0");
  }

  public function getSaleOrderDetail($id_order)
  {
    return dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id_order);
  }


  public function getSaleTotalAmount($id_order)
  {
    $qs = dbQuery("SELECT SUM(total_amount) AS amount FROM tbl_order_detail WHERE id_order = ".$id_order);
    list( $amount ) = dbFetchArray($qs);

    return is_null($amount) ? 0 : $amount;
  }


}



 ?>
