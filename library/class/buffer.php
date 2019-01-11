<?php
class buffer
{
  public $id;
  public $id_order;
  public $id_style;
  public $id_product;
  public $qty;
  public $id_zone;
  public $id_warehouse;

  public function __construct($id = "")
  {
    if( $id !="")
    {
      //--- initialize
      $this->getData($id);
    }
  }





  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_buffer WHERE id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value )
      {
        $this->$key = $value;
      }
    }
  }





  public function getDetails($id_order, $id_product)
  {
    return dbQuery("SELECT * FROM tbl_buffer WHERE id_order = ".$id_order." AND id_product = '".$id_product."'");
  }




  public function add($id_order, $id_style, $id_pd, $id_zone, $id_warehouse, $qty)
  {
    $qr  = "INSERT INTO tbl_buffer (id_order, id_style, id_product, qty, id_zone, id_warehouse) ";
    $qr .= "VALUES ";
    $qr .= "(".$id_order.", '".$id_style."', '".$id_pd."', ".$qty.", ".$id_zone.", '".$id_warehouse."')";

    return dbQuery($qr);
  }






  public function update($id_order, $id_pd, $id_zone, $qty)
  {
    $qr = "UPDATE tbl_buffer SET qty = qty + ".$qty." ";
    $qr .= "WHERE id_order = ".$id_order." AND id_product = '".$id_pd."' ";
    $qr .= "AND id_zone = ".$id_zone;

    return dbQuery($qr);
  }




  public function delete($id)
  {
    return dbQuery("DELETE FROM tbl_buffer WHERE id = ".$id);
  }




  public function isExists($id_order, $id_product, $id_zone)
  {
      $qr  = "SELECT id FROM tbl_buffer ";
      $qr .= "WHERE id_order = ".$id_order." ";
      $qr .= "AND id_product = '".$id_product."' ";
      $qr .= "AND id_zone = ".$id_zone." ";

      $qs = dbQuery($qr);
      if( dbNumRows($qs) == 1 )
      {
          return TRUE;
      }

      return FALSE;
  }






  public function updateBuffer($id_order, $id_style, $id_pd, $id_zone, $id_warehouse, $qty)
  {
    if(!$this->isExists($id_order, $id_pd, $id_zone))
    {
      return $this->add($id_order, $id_style, $id_pd, $id_zone, $id_warehouse, $qty);
    }

    return $this->update($id_order, $id_pd, $id_zone, $qty);
  }





  //--- ยอดรวมสินค้าที่จัดไปแล้ว
  public function getSumQty($id_order, $id_pd)
  {
    $qs = dbQuery("SELECT SUM(qty) FROM tbl_buffer WHERE id_order = '".$id_order."' AND id_product = '".$id_pd."'");
    list( $qty ) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }




  //--- drop รายการที่เป็น 0 ทิ้ง
  public function dropZero($id_order)
  {
    return dbQuery("DELETE FROM tbl_buffer WHERE id_order = ".$id_order." AND qty = 0");
  }



  //--- เอารายการที่ค้างอยู่ใน buffer
  public function getBuffer($id_order)
  {
    return dbQuery("SELECT * FROM tbl_buffer WHERE id_order = '".$id_order."'");
  }



  //----  จำนวนที่ค้างใน buffer แต่ไม่มีรายการอยู่ในออเดอร์ไหนๆเลย (ถูกลบรายการเมื่อจัดสินค้าแล้ว)
  public function getBufferQty($id_pd)
  {
    $qr  = "SELECT SUM(bf.qty) AS qty FROM tbl_buffer AS bf ";
    $qr .= "LEFT JOIN tbl_order_detail AS od ON bf.id_order = od.id_order AND bf.id_product = od.id_product ";
    $qr .= "WHERE bf.id_product = '".$id_pd."' ";
    $qr .= "AND od.id_product IS NULL ";

    $qs = dbQuery($qr);

    list($sc) = dbFetchArray($qs);

    return is_null($sc) ? 0 : $sc;
  }


  public function isInOrder($id_order, $id_pd)
  {
    $qr  = "SELECT od.id_product FROM tbl_buffer AS bf ";
    $qr .= "LEFT JOIN tbl_order_detail AS od ON bf.id_order = od.id_order AND bf.id_product = od.id_product ";
    $qr .= "WHERE bf.id_order = '".$id_order."' AND bf.id_product = '".$id_pd."' ";

    //echo $qr;
    $qs = dbQuery($qr);

    list($sc) = dbFetchArray($qs);

    return is_null($sc) ? FALSE : TRUE;
  }


  public function getStockInBuffer($id_pd)
  {
    $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_buffer WHERE id_product = '".$id_pd."'");
    list($qty) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }






}//---- End class


 ?>
