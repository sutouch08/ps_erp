<?php
class consign_check
{
  public $id;
  public $reference;
  public $id_customer;
  public $id_zone;
  public $d_employee;
  public $status;
  public $valid;
  public $date_add;
  public $date_upd;
  public $emp_upd;
  public $id_consign;
  public $remark;
  public $error;

  public function __construct($id='')
  {
    if($id != '')
    {
      $this->getData($id);
    }
  }


  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_consign_check WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }


  public function add(array $ds = array())
  {
    $sc = FALSE;
    if(!empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;

      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $qs = dbQuery("INSERT INTO tbl_consign_check (".$fields.") VALUES (".$values.")");

      $sc = $qs === TRUE ? dbInsertId() : FALSE;
    }

    return $sc;
  }





  public function update($id, array $ds = array())
  {
    $sc = FALSE;
    if(! empty($ds))
    {
      $set = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }

      $sc = dbQuery("UPDATE tbl_consign_check SET ".$set." WHERE id = '".$id."'");
      $this->error = $sc === TRUE ? '' : dbError();
    }

    return $sc;
  }


  public function close($id)
  {
    $sc = FALSE;
    $id_emp = getCookie('user_id');
    //--- จะปิดได้ต้องยังไม่ถูกดึงไปตัดยอด
    $qs = dbQuery("UPDATE tbl_consign_check SET status = 1, emp_upd = '".$id_emp."' WHERE id = ".$id." AND valid = 0");
    if(dbAffectedRows() > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }


  public function unClose($id)
  {
    $sc = FALSE;
    $id_emp = getCookie('user_id');
    //----   จะเปิดได้ต้องไม่ถูกส่งไปตัดยอด
    $qs = dbQuery("UPDATE tbl_consign_check SET status = 0, emp_upd = '".$id_emp."' WHERE id = ".$id." AND valid = 0");
    if(dbAffectedRows() > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }



  public function cancleConsignCheck($id)
  {
    $sc = FALSE;
    $id_emp = getCookie('user_id');
    //----- จะยกเลิกได้ต้องยังไม่ถูกดึงไปตัดยอด
    $qs = dbQuery("UPDATE tbl_consign_check SET status = 2, emp_upd = '".$id_emp."' WHERE id = ".$id." AND valid = 0");
    if(dbAffectedRows() > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }



  //----- ดึงรายละเอียดทั้งเอกสาร
  public function getDetails($id)
  {
    return dbQuery("SELECT * FROM tbl_consign_check_detail WHERE id_consign_check = '".$id."'");
  }



  //----- เพิ่มรายการกระทบยอด
  public function addDetail(array $ds = array())
  {
    $sc = FALSE;
    if(!empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_consign_check_detail (".$fields.") VALUES (".$values.")");
      $this->error = $sc === TRUE ? '' : $this->dbError();
    }

    return $sc;
  }





  public function updateCheckedQty($id_consign_check, $id_pd, $qty)
  {
    $id_emp = getCookie('user_id');
    $qr  = "UPDATE tbl_consign_check_detail ";
    $qr .= "SET qty = qty + ".$qty.", id_employee = '".$id_emp."' ";
    $qr .= "WHERE id_consign_check = ".$id_consign_check." ";
    $qr .= "AND id_product = '".$id_pd."' ";

    return dbQuery($qr);
  }




  public function addConsignBoxDetail(array $ds = array())
  {
    $sc = FALSE;
    if(!empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_consign_box_detail (".$fields.") VALUES (".$values.")");
      if($sc !== TRUE)
      {
        $this->error = dbError();
      }
    }

    return $sc;
  }







  public function updateConsignBoxDetail($id_consign_box, $id_consign_check, $id_pd, $qty)
  {
    $sc = FALSE;

    $id = $this->getConsignBoxDetailId($id_consign_box, $id_consign_check, $id_pd);

    if($id === FALSE)
    {
      $arr = array(
        'id_consign_box' => $id_consign_box,
        'id_consign_check' => $id_consign_check,
        'id_product' => $id_pd,
        'qty' => $qty
      );

      $sc = $this->addConsignBoxDetail($arr);
    }
    else
    {
      $sc = dbQuery("UPDATE tbl_consign_box_detail SET qty = qty + ".$qty." WHERE id = '".$id."'");
    }

    return $sc;
  }


  //---- เรียกรายการเอกสารที่บันทึกแล้วเพื่อจะนำไปตัดยอด
  public function getActiveCheckList($id_zone)
  {
    $qr  = "SELECT * FROM tbl_consign_check ";
    $qr .= "WHERE id_zone = '".$id_zone."' ";
    //$qr .= "AND id_customer = '".$id_customer."' ";
    $qr .= "AND status = 1 AND valid = 0 ";
    $qr .= "AND id_consign = 0 ";
    $qr .= "ORDER BY reference ASC";

    return dbQuery($qr);
  }


  //---- แสดงรายการตรวจเช็คสินค้าลงกล่อง ตามสินค้าที่เลือกมา
  public function getProductCheckedDetail($id, $id_pd)
  {
    $qr  = "SELECT bd.id_consign_box, bx.box_no, bd.id_product, SUM(bd.qty) AS qty ";
    $qr .= "FROM tbl_consign_box_detail AS bd ";
    $qr .= "LEFT JOIN tbl_consign_box AS bx ON bd.id_consign_box = bx.id ";
    $qr .= "WHERE bd.id_consign_check = ".$id." ";
    $qr .= "AND bd.id_product = '".$id_pd."' ";
    $qr .= "GROUP BY bd.id_consign_box, bd.id_product ";
    $qr .= "ORDER BY bx.box_no ASC";

    return dbQuery($qr);
  }





  public function getConsignBoxDetailId($id_consign_box, $id_consign_check, $id_pd)
  {
    $sc = FALSE;
    $qr  = "SELECT id FROM tbl_consign_box_detail ";
    $qr .= "WHERE id_consign_box = '".$id_consign_box."' ";
    $qr .= "AND id_consign_check = '".$id_consign_check."' ";
    $qr .= "AND id_product = '".$id_pd."' ";

    $qs = dbQuery($qr);

    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }





  public function getConsignBoxDetail($id_consign_check, $id_box, $id_pd)
  {
    $qr  = "SELECT * FROM tbl_consign_box_detail ";
    $qr .= "WHERE id_consign_check = ".$id_consign_check." ";
    $qr .= "AND id_consign_box = ".$id_box." ";
    $qr .= "AND id_product = '".$id_pd."' ";

    return dbQuery($qr);
  }



  //----- ลบสินค้าในกล่อง
  public function deleteCheckedProductByBox($id_consign_check, $id_box, $id_pd)
  {
    $qr  = "DELETE FROM tbl_consign_box_detail ";
    $qr .= "WHERE id_consign_check = ".$id_consign_check." ";
    $qr .= "AND id_consign_box = ".$id_box." ";
    $qr .= "AND id_product = '".$id_pd."'";

    return dbQuery($qr);
  }



  public function deleteAllBoxDetails($id_box)
  {
    return dbQuery("DELETE FROM tbl_consign_box_detail WHERE id_consign_box = ".$id_box);
  }





  public function deleteConsignBox($id_box)
  {
    return dbQuery("DELETE FROM tbl_consign_box WHERE id = ".$id_box);
  }






  public function deleteAllDetails($id_consign_check)
  {
    return dbQuery("DELETE FROM tbl_consign_check_detail WHERE id_consign_check = ".$id_consign_check);
  }





  public function getAllConsignBox($id_consign_check)
  {
    return dbQuery("SELECT * FROM tbl_consign_box WHERE id_consign_check = ".$id_consign_check);
  }





  public function getConsignBox($id_consign_check, $barcode)
  {
    $id = $this->getBoxId($id_consign_check, $barcode);

    if($id === FALSE)
    {
      $id = $this->addNewBox($id_consign_check, $barcode);
    }

    $qs = dbQuery("SELECT * FROM tbl_consign_box WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      $sc = dbFetchObject($qs);
    }
    else
    {
      $sc = FALSE;
    }

    return $sc;
  }





  public function getBoxList($id)
  {
    return dbQuery("SELECT * FROM tbl_consign_box WHERE id_consign_check = ".$id);
  }






  private function getNextBoxNo($id_consign_check)
  {
    $qs = dbQuery("SELECT MAX(box_no) FROM tbl_consign_box WHERE id_consign_check = '".$id_consign_check."'");

    list($box_no) = dbFetchArray($qs);

    return is_null($box_no) ? 1 : $box_no +1;
  }






  public function addNewBox($id_consign_check, $barcode)
  {
    $box_no = $this->getNextBoxNo($id_consign_check);
    $qr  = "INSERT INTO tbl_consign_box ";
    $qr .= "(barcode, id_consign_check, box_no) ";
    $qr .= "VALUES ('".$barcode."', '".$id_consign_check."', '".$box_no."')";
    $qs = dbQuery($qr);

    return $qs === TRUE ? dbInsertId() : FALSE;
  }






  //--- check if box in document exists will be return id_box
  private function getBoxId($id_consign_check, $barcode)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_consign_box WHERE id_consign_check = '".$id_consign_check."' AND barcode = '".$barcode."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }




  public function getBox($id_box)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT * FROM tbl_consign_box WHERE id = ".$id_box);
    if(dbNumRows($qs) == 1)
    {
      $sc = dbFetchObject($qs);
    }

    return $sc;
  }


  public function getBoxDetail($id_box)
  {
    $qr  = "SELECT * FROM tbl_consign_box_detail AS bd ";
    $qr .= "JOIN tbl_product AS pd ON bd.id_product = pd.id ";
    $qr .= "WHERE id_consign_box = ".$id_box;

    return dbQuery($qr);
  }


  public function getQtyInBox($id_box, $id_check)
  {
    $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_consign_box_detail WHERE id_consign_box = '".$id_box."' AND id_consign_check = '".$id_check."'");
    list($qty) = dbFetchArray($qs);

    return is_null($qty) ? 0 : $qty;
  }



  public function hasDetails($id)
  {
    $qs = dbQuery("SELECT COUNT(*) FROM tbl_consign_check_detail WHERE id_consign_check = '".$id."'");
    list($sc) = dbFetchArray($qs);

    return $sc == 0 ? FALSE : TRUE;
  }

  //-----------------  New Reference --------------//
  public function getNewReference($date = '')
  {
    $date     = $date == '' ? date('Y-m-d') : $date;
    $Y		    = date('y', strtotime($date));
    $M		    = date('m', strtotime($date));
    $runDigit = getConfig('RUN_DIGIT_CONSIGN_CHECK');
    $prefix   = getConfig('PREFIX_CONSIGN_CHECK');
    $preRef   = $prefix . '-' . $Y . $M;

    $qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_consign_check WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");

    list( $ref ) = dbFetchArray($qs);

    if( ! is_null( $ref ) )
    {
      $runNo = mb_substr($ref, ($runDigit*(-1)), NULL, 'UTF-8') + 1;
      $reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', $runNo);
    }
    else
    {
      $reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', '001');
    }

    return $reference;
  }


}

 ?>
