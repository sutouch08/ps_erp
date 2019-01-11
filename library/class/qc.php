<?php
class qc
{
  public function __construct()
  {

  }



  //--- เพิ่มรายการตรวจสินค้า
  public function add($id_order, $id_box, $id_product, $qty)
  {
    $id_emp = getCookie('user_id') ? getCookie('user_id') : 0;
    return dbQuery("INSERT INTO tbl_qc (id_order, id_product, id_box, qty, id_employee) VALUES (".$id_order.", '".$id_product."', ".$id_box.", ".$qty.", ".$id_emp.")");

  }





  //--- update รายการตรวจ
  public function update($id_order, $id_box, $id_product, $qty)
  {
    return dbQuery("UPDATE tbl_qc SET qty = qty + ".$qty." WHERE id_order = ".$id_order." AND id_product = '".$id_product."' AND id_box = ".$id_box);
  }



  //--- ออเดอร์รอตรวจทั้งหมด
  public function getDatas()
  {
    return dbQuery("SELECT * FROM tbl_order WHERE state = 5");
  }




  //---   ออเดอร์กำลังตรวจทั้งหมด
  public function getProcessDatas()
  {
    return dbQuery("SELECT * FROM tbl_order WHERE state = 6");
  }





  //--- รายการกล่องทั้งหมดที่ตรวจในออเดอร์ที่กำหนด
  public function getBoxList($id_order)
  {
    $qr = "SELECT b.id_box,b.box_no, SUM(qty) AS qty FROM tbl_box AS b ";
    $qr .= "LEFT JOIN tbl_qc AS q ON b.id_box = q.id_box AND b.id_order = q.id_order ";
    $qr  .= "WHERE b.id_order = ".$id_order." GROUP BY b.id_box";
    return dbQuery($qr);
  }




  //--- รายการที่ยังไม่ได้ตรวจหรือยังตรวจไม่ครบ
  public function getIncompleteList($id_order)
  {
    $qr = "SELECT o.id_product, o.product_code, o.product_name, o.qty AS order_qty, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_prepare WHERE id_order = ".$id_order." AND id_product = o.id_product) AS prepared, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_qc WHERE id_order = ".$id_order." AND id_product = o.id_product) AS qc ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "JOIN tbl_product AS p ON o.id_product = p.id ";
    $qr .= "WHERE o.id_order = ".$id_order." AND p.count_stock = 1 GROUP BY o.id_product HAVING ( prepared > qc OR ISNULL(qc) )";

    return dbQuery($qr);
  }



  //--- รายการที่ตรวจครบแล้ว
  public function getCompleteList($id_order)
  {
    $qr = "SELECT o.id_product, o.product_code, o.product_name, o.qty AS order_qty, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_buffer WHERE id_order = ".$id_order." AND id_product = o.id_product) AS prepared, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_qc WHERE id_order = ".$id_order." AND id_product = o.id_product) AS qc ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "WHERE o.id_order = ".$id_order." GROUP BY o.id_product HAVING prepared <= qc ";

    return dbQuery($qr);
  }


  public function getVisualProductList($id_order)
  {
    $qr  = "SELECT o.id_product, o.product_code, o.product_name, o.qty AS order_qty ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "JOIN tbl_product AS p ON o.id_product = p.id ";
    $qr .= "WHERE o.id_order = ".$id_order." AND p.count_stock = 0 ";

    return dbQuery($qr);
  }


  /*
  //--- รายการที่ตรวจครบแล้ว
  public function getCompleteList($id_order)
  {
    $qr = "SELECT o.id_product, o.product_code, o.product_name, o.qty AS order_qty, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_prepare WHERE id_order = ".$id_order." AND id_product = o.id_product) AS prepared, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_qc WHERE id_order = ".$id_order." AND id_product = o.id_product) AS qc ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "WHERE o.id_order = ".$id_order." GROUP BY o.id_product HAVING prepared <= qc ";

    return dbQuery($qr);
  }
*/




  //--- จำนวนรวมของสินค้าที่ตรวจแล้วทั้งออเดอร์(ไม่รวมที่ยังไม่ตรวจ)
  public function totalQc($id_order)
  {
    $qr  = "SELECT SUM(qc.qty) AS qty FROM tbl_qc AS qc ";
    $qr .= "JOIN tbl_order_detail AS od ON qc.id_order = od.id_order AND qc.id_product = od.id_product ";
    $qr .= "WHERE qc.id_order = '".$id_order."'";
    //$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_qc WHERE id_order = ".$id_order);
    $qs = dbQuery($qr);
    list( $qty ) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }


  //---- ยอดรวมสินค้าที่ตรวจไปแล้ว
  public function getSumQty($id_order, $id_pd)
  {
    $qr  = "SELECT SUM(qty) AS qty FROM tbl_qc ";
    $qr .= "WHERE id_order = '".$id_order."' ";
    $qr .= "AND id_product = '".$id_pd."' ";

    $qs = dbQuery($qr);

    list($qty) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }







  //----  ถ้ามีรายการที่ตรวจอยู่แล้ว
  public function isExists($id_order, $id_box, $id_product)
  {
    $qs = dbQuery("SELECT id FROM tbl_qc WHERE id_order = ".$id_order." AND id_product = '".$id_product."' AND id_box = ".$id_box);
    return dbNumRows($qs) > 0 ? TRUE : FALSE;
  }





  //---
  public function updateChecked($id_order, $id_box, $id_product, $qty)
  {
    if( $this->isExists($id_order, $id_box, $id_product) === TRUE )
    {
      return $this->update($id_order, $id_box, $id_product, $qty);
    }
    else
    {
      return $this->add($id_order, $id_box, $id_product, $qty);
    }
  }




  //--- พิมพ์ packing list
  public function getPackingList($id_order, $id_box)
  {
    $qr = "SELECT o.product_code, o.product_name, SUM(q.qty) AS qty ";
    $qr .= "FROM tbl_qc AS q JOIN tbl_order_detail AS o ON q.id_order = o.id_order AND q.id_product = o.id_product ";
    $qr .= "WHERE q.id_order = ".$id_order." AND q.id_box = ".$id_box." GROUP BY q.id_product";

    return dbQuery($qr);
  }


  public function dropQcData($id_order)
  {
    return dbQuery("DELETE FROM tbl_qc WHERE id_order = '".$id_order."'");
  }


} //--- end class

 ?>
