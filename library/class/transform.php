<?php
class transform
{
  public $id;
  public $id_order;
  public $id_zone;
  public $role; //--- 1 = Sell,  2 = Sponsor OR Support, 3 = Keep Stock
  public $is_closed = 0;

  public function __construct($id='')
  {
    if( $id !='')
    {
      $this->getData($id);
    }
  }



  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_order_transform WHERE id_order ='".$id."'");
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }



  //--- Add order_transform
  public function add( array $ds = array())
  {
    $sc = FALSE;
    if( !empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value )
      {
        $fields .= $i == 1 ? $field : ', ' . $field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_order_transform (".$fields.") VALUES (".$values.")");
    }

    return $sc;
  }





  //---   update order transform
  public function update($id_order, array $ds = array())
  {
    $sc = FALSE;
    if( ! empty($ds))
    {
      $set = "";
      $i   = 1;
      foreach ($ds as $field => $value)
      {
        $set .= $i == 1 ? $field ." = '".$value."'" : ", ". $field ." = '".$value."'";
        $i++;
      }

      $sc = dbQuery("UPDATE tbl_order_transform SET ".$set." WHERE id_order = '".$id_order."'");
    }

    return $sc;
  }




  //--- update หรือ เพื่ม การเชื่อมโยงสินค้าทีละรายการ
  public function addDetail($id_order_detail, $id_product, array $ds = array())
  {
    $sc = FALSE;
    if( ! empty($ds))
    {
      if( $this->isExists($id_order_detail, $id_product) === TRUE )
      {
        $sc = $this->updateDetail($id_order_detail, $id_product, $ds['qty']);
      }
      else
      {
        $sc = $this->insertDetail($ds);
      }
    }

    return $sc;
  }




  //--- update การเชื่อมโยงสินค้าทีละรายการ
  public function updateDetail($id_order_detail, $id_product, $qty)
  {
    return dbQuery("UPDATE tbl_order_transform_detail SET qty = qty + " . $qty." WHERE id_order_detail = ".$id_order_detail." AND id_product = '".$id_product."'");
  }


  //--- ลบรายการทั้งหมด(ยกเลิกออเดอร์)
  public function deleteDetails($id_order)
  {
    return dbQuery("DELETE FROM tbl_order_transform_detail WHERE id_order = '".$id_order."'");
  }


  //--- เมื่อมีการเปิดบิล
  public function updateSoldQty($id, $qty)
  {
    return dbQuery("UPDATE tbl_order_transform_detail SET sold_qty = sold_qty + ".$qty.", valid = 1 WHERE id = ".$id);
  }


  //--- เปลี่ยนยอดที่เปิดบิลให้เป็น 0 เพราะมีการย้อนสถานะ หรือ ยกเลิก
  public function resetSoldQty($id)
  {
    return dbQuery("UPDATE tbl_order_transform_detail SET sold_qty = 0 WHERE id_order = '".$id."'");
  }



  //---
  public function setValid($id, $valid)
  {
    return dbQuery("UPDATE tbl_order_transform_detail SET valid = ".$valid." WHERE id = ".$id);
  }


  //--- set valid for all
  public function setValidAll($id, $valid)
  {
    return dbQuery("UPDATE tbl_order_transform_detail SET valid = ".$valid." WHERE id_order = '".$id."'");
  }


  ///------ รับเข้าครบแล้ว หรือ ยกเลิก
  public function closed($id_order)
  {
    return dbQuery("UPDATE tbl_order_transform SET is_closed = 1 WHERE id_order = '".$id_order."'");
  }




  //--- Unclosed
  public function unClosed($id_order)
  {
    return dbQuery("UPDATE tbl_order_transform SET is_closed = 0 WHERE id_order = '".$id_order."'");
  }



  
  //--- เพื่มการเชื่อมโยงสินค้าทีละรายการ
  public function insertDetail(array $ds = array())
  {
    $sc = FALSE;
    if( !empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value )
      {
        $fields .= $i == 1 ? $field : ', ' . $field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_order_transform_detail (".$fields.") VALUES (".$values.")");
    }

    return $sc;
  }




  public function getDetails($id_order)
  {
    return dbQuery("SELECT * FROM tbl_order_transform_detail WHERE id_order = ".$id_order);
  }



  //---
  public function getDetail($id_order, $id_product)
  {
    return dbQuery("SELECT * FROM tbl_order_transform_detail WHERE id_order = ".$id_order." AND id_product = '".$id_product."'");
  }


  //--- สำหรับรับเข้า
  public function getReceiveTransfromProductDetails($id_order)
  {
    $qr  = "SELECT id, id_order, id_order_detail, from_product, id_product, SUM(sold_qty) AS qty, SUM(received) AS received, valid, is_closed ";
    $qr .= "FROM tbl_order_transform_detail ";
    $qr .= "WHERE id_order = ".$id_order." AND valid = 1 AND is_closed = 0 ";
    $qr .= "GROUP BY id_product";
    return dbQuery($qr);
  }




  //--- ตรวจสอบว่ามีการเชื่อมโยงสินค้าแล้วหรือยัง
  public function isExists($id_order_detail, $id_product)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail." AND id_product = '".$id_product."'");
    if( dbNumRows($qs) > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }




  public function hasTransformProduct($id_order_detail)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT * FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail);
    if( dbNumRows($qs) > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }




  //--- ลบการเชื่อมโยงเฉพาะ 1 รายการสินค้าแปรสภาพ
  public function removeTransformProduct($id_order_detail, $id_product)
  {
    return dbQuery("DELETE FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail." AND id_product = '".$id_product."'");
  }



  //--- ลบการเชื่อมโยง 1 รายการสั่งแปรสภาพ (อาจมีหลายสินค้าแปรสภาพ)
  public function removeTransformDetail($id_order_detail)
  {
    return dbQuery("DELETE FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail);
  }




  //--- อยากรู้ว่ามีการเชื่อมโยงสินค้าอะไรแล้วบ้างในรายการนี้
  public function getTransformProducts($id_order_detail)
  {
    return dbQuery("SELECT * FROM tbl_order_transform_detail WHERE id_order_detail = '.$id_order_detail.'");
  }




  //--- เอายอดรวมสินค้าที่เชื่อมโยงแล้วเฉพาะสินค้า เพื่อใช้โอนเข้าคลังฝากขาย
  public function getTransformProductQty($id_order_detail, $id_product)
  {
    $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail." AND id_product = '".$id_product."'");
    list( $qty ) = dbFetchArray($qs);

    return is_null($qty) ? 0 : $qty;
  }



  //--- เชื่อมโยงสินค้าไปแล้วเท่าไร
  public function getSumTransformProductQty($id_order_detail)
  {
    $qs = dbQuery("SELECT SUM(qty) FROM tbl_order_transform_detail WHERE id_order_detail = '".$id_order_detail."'");
    list( $qty ) = dbFetchArray($qs);

    return is_null($qty) ? 0 : $qty;
  }




  //--- รับสินค้าแล้วนะ
  public function received($id, $qty)
  {
    return dbQuery("UPDATE tbl_order_transform_detail SET received = received + ".$qty." WHERE id = ".$id);
  }




  //--- มีการรับเข้าแล้วหรือยัง
  public function isReceived($id)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_order_transform_detail WHERE id_order = '".$id."' AND received > 0");
    if( dbNumRows($qs) > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }


  //--- รับเข้าครบแล้วหรือยัง
  public function isCompleted($id)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_order_transform_detail WHERE id_order = '".$id."' AND received < sold_qty");
    if(dbNumRows($qs) == 0)
    {
      $sc = TRUE;
    }

    return  $sc;
  }


  public function getOriginalProductId($id_order_detail)
  {
    $qs = dbQuery("SELECT id_product FROM tbl_order_detail WHERE id = ".$id_order_detail);
    if(dbNumRows($qs) == 1)
    {
      list($id) = dbFetchArray($qs);
      return $id;
    }

    return FALSE;
  }




  //---	auto complete
	public function searchReference($reference, $is_closed = '', $limit = 50)
	{
		$qr  = "SELECT o.id, o.reference FROM tbl_order AS o ";
		$qr .= "JOIN tbl_order_transform AS t ON o.id = t.id_order ";
		$qr .= "WHERE o.role = 5 ";
		$qr .= "AND o.state IN(8,9,10) ";
    $qr .= $is_closed == '' ? '' : "AND t.is_closed = ".$is_closed." ";
		$qr .= "AND o.reference LIKE '%".$reference."%' ";
    $qr .= "LIMIT ".$limit;

		return dbQuery($qr);
	}


} //--- end class

 ?>
