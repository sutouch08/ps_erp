<?php
class adjust
{
  //--- ไอดีเอกสาร
  public $id;

  //--- รหัสเล่มเอกสาร
  public $bookcode;

  //--- เลขที่เอกสาร
  public $reference;

  //--- การอ้างถึง
  public $refer;

  //--- พนักงานที่ทำรายการ
  public $id_employee;

  //--- ผู้ขอให้ปรับยอด (อาจไม่ใช่พนักงานก็ได้)
  public $requester;

  //--- หมายเหตุเอกสาร
  public $remark;

  //--- วันที่เอกสาร
  public $date_add;

  //--- วันที่ปรับปรุงเอกสารล่าสุด
  public $date_upd;

  //--- พนักงานที่ทำการแก้ไขเอกสารล่าสุด
  public $emp_upd;

  //--- ยกเลิกเอกสารแล้วหรือไม่
  public $isCancle = 0;

  //--- ส่งออกแล้วหรือไม่
  public $isExport = 0;

  //--- บันทึกเอกสารแล้วหรือไม่
  public $isSaved = 0;

  //--- ส่งข้อมูลออกไป Formula หรือไม่
  public $is_so = 1;


  public function __construct($id = '')
  {
    if( $id != '')
    {
      $this->getData($id);
    }
  }


  //--- ดึงข้อมูํลเอกสาร มาใส่ตัวแปร
  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_adjust WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value )
      {
        $this->$key = $value;
      }
    }
  }




  public function add(array $ds = array())
  {
    $sc = FALSE;
    if( !empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;

      foreach( $ds as $field => $value )
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_adjust (".$fields.") VALUES (".$values.")");
    }

    return $sc === TRUE ? dbInsertId() : FALSE;
  }





  public function update($id, array $ds = array())
  {
    $sc = FALSE;
    if( !empty($ds))
    {
      $set = "";
      $i = 1;

      foreach($ds as $field => $value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }

      $sc = dbQuery("UPDATE tbl_adjust SET ".$set." WHERE id = '".$id."'");
    }

    return $sc;
  }



  public function addDetail(array $ds = array())
  {
    $sc = FALSE;
    if( !empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;

      foreach( $ds as $field => $value )
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_adjust_detail (".$fields.") VALUES (".$values.")");
    }

    return $sc === TRUE ? dbInsertId() : FALSE;
  }




  public function updateDetail($id,$qty)
  {
    return dbQuery("UPDATE tbl_adjust_detail SET qty = qty + ".$qty." WHERE id = ".$id." AND valid = 0");
  }


  public function getDetailId($id_adjust, $id_zone, $id_product)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_adjust_detail WHERE id_adjust = '".$id_adjust."' AND id_zone = '".$id_zone."' AND id_product = '".$id_product."'");
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }


  public function getDetail($id)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT * FROM tbl_adjust_detail WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1 )
    {
      $sc = dbFetchObject($qs);
    }

    return $sc;
  }



  public function getDetails($id)
  {
    return dbQuery("SELECT * FROM tbl_adjust_detail WHERE id_adjust = '".$id."'");
  }



  public function setValidDetail($id)
  {
    return dbQuery("UPDATE tbl_adjust_detail SET valid = 1 WHERE id = '".$id."'");
  }



  public function setSaved($id)
  {
    return dbQuery("UPDATE tbl_adjust SET isSaved = 1 WHERE id = '".$id."'");
  }



  public function setCancle($id)
  {
    return dbQuery("UPDATE tbl_adjust SET isCancle = 1 WHERE id = '".$id."'");
  }


  public function exported($id)
  {
    return dbQuery("UPDATE tbl_adjust SET isExport = 1 WHERE id = '".$id."'");
  }


  public function isValidDetail($id)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_adjust_detail WHERE id = '".$id."' AND valid = 1");
    if( dbNumRows($qs) == 1)
    {
      $sc = TRUE;
    }

    return $sc;
  }



  public function deleteDetail($id)
  {
    return dbQuery("DELETE FROM tbl_adjust_detail WHERE id = '".$id."'");
  }




  public function getId($reference)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_adjust WHERE reference ='".$reference."'");
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }




  public function getIdByDetail($id)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id_adjust FROM tbl_adjust_detail WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }





  //-----------------  New Reference --------------//
	public function getNewReference($date = '')
	{
		$date     = $date == '' ? date('Y-m-d') : $date;
		$Y		    = date('y', strtotime($date));
		$M		    = date('m', strtotime($date));
		$runDigit = getConfig('RUN_DIGIT_ADJUST');
		$prefix   = getConfig('PREFIX_ADJUST');
		$preRef   = $prefix . '-' . $Y . $M;

		$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_adjust WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");

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


} //--- end class
?>
