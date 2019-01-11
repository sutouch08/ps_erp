<?php
class prepare
{
    public $id;
    public $id_order;
    public $id_product;
    public $qty;
    public $id_zone;
    public $id_employee;
    public $date_upd;
    public $status; //---   0 = รอจัด, 1 = กำลังจัด, 2 = จัดแล้ว, 3 = ยกเลิก

    public function __construct($id="")
    {
        if( $id != "")
        {
            $qs = dbQuery("SELECT * FROM tbl_prepare WHERE id = ".$id);
            if(dbNumRows($qs) == 1 )
            {
                $rs = dbFetchArray($qs);
                foreach($rs as $key => $value)
                {
                    $this->$key = $value;
                }
            }
        }

    }




    public function add($id_order, $id_pd, $id_zone, $qty)
    {
      $qr = "INSERT INTO tbl_prepare (id_order, id_product, qty, id_zone, id_employee) ";
      $qr .= "VALUES (".$id_order.", '".$id_pd."', ".$qty.", ".$id_zone.", '".getCookie('user_id')."')";
      return dbQuery($qr);
    }






    public function update($id_order, $id_pd, $id_zone, $qty)
    {
      $qr = "UPDATE tbl_prepare SET qty = qty + ".$qty." WHERE id_order = ".$id_order." AND id_product = '".$id_pd."' AND id_zone = '".$id_zone."'";
      return dbQuery($qr);
    }





    public function updatePrepare($id_order, $id_pd, $id_zone, $qty)
    {
      if( $this->isExists($id_order, $id_pd, $id_zone))
      {
        return $this->update($id_order, $id_pd, $id_zone, $qty);
      }
      else
      {
        return $this->add($id_order, $id_pd, $id_zone, $qty);
      }
    }






    public function isExists($id_order, $id_product, $id_zone)
    {
        $qs = dbQuery("SELECT id FROM tbl_prepare WHERE id_order = '".$id_order."' AND id_product ='".$id_product."' AND id_zone = '".$id_zone."'");

        return dbNumRows($qs) == 1 ? TRUE : FALSE;
    }




    //--- จำนวนที่จัดไปแล้ว
    public function getPrepared($id_order, $id_product)
    {
      $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_prepare WHERE id_order = ".$id_order." AND id_product = '".$id_product."'");
      list( $qty ) = dbFetchArray($qs);

      return is_null($qty) ? 0 : $qty;
    }




    public function getOrderPrepared($id_order)
    {
      $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_prepare WHERE id_order = ".$id_order);
      list( $qty ) = dbFetchArray($qs);

      return is_null($qty) ? 0 : $qty;
    }



    //--- จัดสินค้ามาจากที่ไหนบ้าง
    public function prepareFromZone($id_order, $id_pd)
    {
      $qr = "SELECT zone_name AS name, qty FROM tbl_prepare JOIN tbl_zone ON tbl_prepare.id_zone = tbl_zone.id_zone ";
      $qr .= "WHERE id_order = ".$id_order." AND id_product = '".$id_pd."'";
      return dbQuery($qr);
    }




    public function getPreparedData($id_order, $id_product)
    {
      return dbQuery("SELECT * FROM tbl_prepare WHERE id_order = ".$id_order." AND id_product = '".$id_product."'");
    }



    public function dropPreparedData($id_order)
    {
      return dbQuery("DELETE FROM tbl_prepare WHERE id_order = '".$id_order."'");
    }


    public function deletePrepared($id_order, $id_pd, $id_zone)
    {
      return dbQuery("DELETE FROM tbl_prepare WHERE id_order = '".$id_order."' AND id_product = '".$id_pd."' AND id_zone = '".$id_zone."'");
    }




}   //---   End class


?>
