<?php
class event
{
  //--- ไอดี
  public $id;

  //--- รหัสงาน
  public $code;

  //--- ชื่องาน
  public $name;

  //--- วันเริ่ม
  public $start_date;

  //--- วันจบ
  public $end_date;

  //--- พนักงานผู้รับผิดชอบ
  public $id_employee;

  //--- วันที่เพิ่ม
  public $date_add;

  //--- วันที่แก้ไข
  public $date_upd;

  //--- พนักงานที่แก้ไข
  public $emp_upd;


  public function __construct($id='')
  {
    if( $id )
    {
      $this->getDate($id);
    }
  }


  public function getDate($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_event WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);
      if(!empty($rs))
      {
        foreach($rs as $key => $value)
        {
          $this->$key = $value;
        }
      }
    }
  }



  public function search($txt)
  {
    if( $txt != '*')
    {
      $qs = dbQuery("SELECT * FROM tbl_event WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
    }
    else
    {
      $qs = dbQuery("SELECT * FROM tbl_event");
    }

    return $qs;
  }



}

 ?>
