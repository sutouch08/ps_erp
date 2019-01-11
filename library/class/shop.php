<?php
class shop
{
  //--- id shop
  public $id;

  //--- รหัสร้าน
  public $code;

  //--- ชื่อร้าน
  public $name;

  //--- รหัสสาขา
  public $branch_no;

  //--- รหัสเครื่องออกใบกำกับภาษีอย่างย่อ
  public $tax_code;

  //--- โซนเก็บสินค้าที่ผูกไว้ที่หลังบ้าน
  //--- ไม่ใช่สต็อกของร้าน แต่เอาไว้คุมการจัดส่งและการตัดยอดขาย
  //--- ที่ร้านมีสต็อกแยกตางหาก เวลาขายหน้าร้านตัดสต็อกของร้านก่อน
  public $id_zone;

  //--- ลูกค้าที่ผูกไว้กับร้านนี้
  //--- เวลาขายจะหากลูกค้าไม่ได้ขอใบกำกับแบบเต็มจะใช้ลูกค้านี้บันทึกขาย
  public $id_customer;

  //--- ที่อยู่
  public $address;

  //--- ตำบล
  public $tambol;

  //--- อำเภอ
  public $ampur;

  //--- จังหวัด
  public $province;

  //--- รหัสไปรษณีย์
  public $post_code;

  //--- เบอร์โทรศัพท์
  public $phone;

  //--- เบอร์แฟกซ์
  public $fax;

  //--- เปิดร้านหรือไม่
  public $active = 0;

  //--- พนักงานผู้แก้ไขข้อมูลร้าน
  public $emp_upd;

  //--- แก้ไขข้อมูลล่าสุด
  public $date_upd;

  public function __construct($id='')
  {
    if( $id )
    {
      $this->getData($id);
    }
  }


  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_shop WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);
      if( ! empty($rs))
      {
        foreach($rs as $key => $value)
        {
          $this->$key = $value;
        }
      }
    }
  }


  public function getId($code)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_shop WHERE code = '".$code."'");
    if(dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }


  public function getShopData($id)
  {
    $qr  = "SELECT s.id_customer, c.name AS customer_name, s.id_zone, z.zone_name ";
    $qr .= "FROM tbl_shop AS s JOIN tbl_customer AS c ON s.id_customer = c.id ";
    $qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
    $qr .= "WHERE s.id = '".$id."'";

    return dbFetchObject(dbQuery($qr));
  }


  public function search($txt)
  {
    if( $txt != '*' )
    {
      $qs = dbQuery("SELECT * FROM tbl_shop WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
    }
    else
    {
      $qs = dbQuery("SELECT * FROM tbl_shop");
    }

    return $qs;
  }

}
 ?>
