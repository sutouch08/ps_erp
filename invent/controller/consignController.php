<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/zone_helper.php';

if(isset($_GET['addToDetail']))
{
  include 'consign/consign_add_detail.php';
}



//--- โหลดรารการยอดต่างจากการระทบยอดเข้าเอกสาร
if(isset($_GET['loadCheckDiff']))
{
  include 'consign/consign_load_check_diff.php';
}



//--- นำรายการนำเข้าออก
if(isset($_GET['removeImportDetails']))
{
  include 'consign/consign_unload_check_diff.php';
}


//---- เรียกข้อมูลสินค้าในโซน
if( isset($_GET['getItemByBarcode']))
{
  include 'consign/consign_get_product_by_barcode.php';
}



if( isset($_GET['getProductData']))
{
  include 'consign/consign_get_product_data.php';
}


//---- เรียกข้อมูลสินค้าในโซน
if( isset( $_GET['getItemByCode']))
{
  include 'consign/consign_get_product_by_code.php';
}




if(isset($_GET['importUploadFile']))
{
  include '../../library/class/PHPExcel.php';
  include 'consign/consign_import_excel.php';
}



if(isset($_GET['getStockInZone']))
{
  $id_pd   = $_GET['id_product'];
  $id_zone = $_GET['id_zone'];
  $stock   = new stock();
  $qty     = $stock->getStockZone($id_zone, $id_pd);
  echo $qty;

}


if(isset($_GET['addNew']))
{
  include 'consign/consign_add.php';
}




if(isset($_GET['saveConsign']))
{
  include '../function/discount_helper.php';
  include '../function/vat_helper.php';
  include 'consign/consign_save.php';
}


if(isset($_GET['unSaveConsign']))
{
  include 'consign/consign_unsave.php';
}



if(isset($_GET['deleteConsign']))
{
  include 'consign/consign_delete.php';
}


if(isset($_GET['deleteDetail']))
{
  $id = $_POST['id_consign_detail'];
  $cs = new consign();
  $sc = TRUE;

  $qs = $cs->getDetail($id);

  if(dbNumRows($qs) == 1)
  {
    $rs = dbFetchObject($qs);
    //---ถ้าบันทึกไปแล้วไม่อนุญาติให้ลบ
    if($rs->status == 1)
    {
      $sc = FALSE;
      $message = 'รายการถูกบันทึกแล้วไม่สามารถลบได้';
    }
    else
    {
      if($cs->deleteDetail($id) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบรายการไม่สำเร็จ';
      }
    }
  }

  echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['updatePrice']))
{
  include 'consign/consign_update_price.php';
}


if(isset($_GET['updateDiscount']))
{
  include 'consign/consign_update_discount.php';
}


//--- ตรวจสอบสถานะก่อนทำการ update
if(isset($_GET['canUpdate']))
{
  $sc = TRUE;
  $cs = new consign($_GET['id_consign']);
  if( $cs->isCancle == 1)
  {
    $sc = FALSE;
    $message = 'ไม่สามารถแก้ไขได้เนื่องจากเอกสารถูกยกเลิกแล้ว';
  }

  if($cs->isSaved == 1)
  {
    $sc = FALSE;
    $message = 'ไม่สามารถแก้ไขได้เนื่องจากเอกสารถูกบันทึกแล้ว';
  }

  echo $sc === TRUE ? 'ok' : $message;
}




//--- Update document
if(isset($_GET['updateConsign']))
{
  include 'consign/consign_update.php';
}




if(isset($_GET['getProduct']))
{
  $sc = array();
  $pd = new product();
  $fields = 'id, code';
  $qs = $pd->search($_REQUEST['term'], $fields);
  if( dbNumRows($qs) > 0 )
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code;
    }
  }
  else
  {
    $sc[] = 'ไม่พบสินค้า';
  }

  echo json_encode($sc);
}






if(isset( $_GET['getCustomer']))
{
  $sc = array();
  $cs = new customer();
  $fields = 'code, name';
  $qs = $cs->search($_REQUEST['term'], $fields);
  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code.' | '.$rs->name;
    }
  }
  else
  {
    $sc[] = 'ไม่พบลูกค้า';
  }

  echo json_encode($sc);
}







if(isset($_GET['getCustomerZone']))
{
  $sc = array();
  if( $_GET['id_customer'] != '' )
  {
    $zone = new zone();
    $qs = $zone->searchConsignZone( trim( $_REQUEST['term'] ), $_GET['id_customer'] );
    if( dbNumRows($qs) > 0 )
    {
      while($rs = dbFetchObject($qs))
      {
        $sc[] = $rs->zone_name.' | '.$rs->id_zone;
      }
    }
    else
    {
      $sc[] = 'ไม่พบโซนของลูกค้า';
    }
  }
  else
  {
    $sc[] = 'กรุณาระบุลูกค้า';
  }

  echo json_encode($sc);
}







if(isset( $_GET['getConsignZone']))
{
  $sc = array();
  if( $_GET['id_customer'] != '')
  {
    $zone = new zone();
    $qs = $zone->searchConsignZone( trim( $_REQUEST['term'] ), $_GET['id_customer'] );
    if( dbNumRows($qs) > 0 )
    {
      while($rs = dbFetchObject($qs))
      {
        $sc[] = $rs->zone_name.' | '.$rs->id_zone.' | '.$rs->id_customer;
      }
    }
    else
    {
      $sc[] = 'ไม่พบรายการ';
    }
  }
  else
  {
    $sc[] = 'เลือกลูกค้าก่อน';
  }

  echo json_encode($sc);
}



if(isset($_GET['getCheckDiffData']))
{
  $sc = TRUE;
  $ds = array();
  $reference = $_GET['reference'];
  $id_customer = $_GET['id_customer'];
  $id_zone = $_GET['id_zone'];
  $id_consign = $_GET['id_consign'];

  $qr  = "SELECT id FROM tbl_consign_check ";
  $qr .= "WHERE reference = '".$reference."' ";
  $qr .= "AND id_customer = '".$id_customer."' ";
  $qr .= "AND id_zone = ".$id_zone." ";
  $qr .= "AND valid = 0 AND status = 1";
  $qs = dbQuery($qr);
  if(dbNumRows($qs) == 1 )
  {
    list($id) = dbFetchArray($qs);

    $qa = dbQuery("SELECT * FROM tbl_consign_check_detail WHERE id_consign_check = ".$id);

    if(dbNumRows($qa) > 0)
    {
      $cs = new consign();
      while($rs = dbFetchObject($qa))
      {
        $diff = $rs->stock_qty - $rs->qty;
        if( $diff > 0)
        {
          $pd = new product($rs->id_product);
          $st = new stock();
          $bc = new barcode();

          $barcode = $bc->getBarcode($rs->id_product);
          $gp = $cs->getProductGP($rs->id_product, $id_zone);
          $stock = $st->getStockZone($id_zone, $rs->id_product);
          $discAmount = ($pd->price * ($gp * 0.01)) * $diff;
          $amount  = ($diff * $pd->price) - $discAmount;

          $arr = array(
            'id_product' => $rs->id_product,
            'barcode'    => $barcode,
            'product'    => $pd->code,
            'price'      => $pd->price,
            'p_disc'     => $gp,
            'a_disc'     => 0,
            'qty'        => $diff,
            'amount'     => $amount,
            'stock'      => $stock
          );

          array_push($ds, $arr);
        }
      }
    }
    else
    {
      $sc = FALSE;
      $message = 'ไม่พบรายการที่จะตัดยอด';
    }
  }
  else
  {
    $sc = FALSE;
    $message = 'ไม่พบเอกสาร เอกสารอาจถูกยกเลิก, ยังไม่บันทึก, หรือถูกดึงตัดยอดแล้ว';
  }

  echo $sc === TRUE ? json_encode($ds) : $message;
}


if(isset( $_GET['clearFilter']))
{
  deleteCookie('sConsignCode');
  deleteCookie('sConsignCus');
  deleteCookie('sConsignZone');
  deleteCookie('sShop');
  deleteCookie('sChannels');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  deleteCookie('isSaved');
  deleteCookie('isExported');
  deleteCookie('isCancle');
  deleteCookie('is_so');
  echo 'done';
}
 ?>
