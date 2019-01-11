<?php
class product_cost
{

  public function __construct()
  {
    $this->dropZero();
  }

  public function add(array $ds = array())
  {
    $sc = FALSE;
    if( ! empty($ds))
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

      $sc = dbQuery("INSERT INTO tbl_product_cost (".$fields.") VALUES (".$values.")");
    }

    return $sc;
  }



  public function addCostList($id_pd, $cost, $qty, $date = '')
  {
    $sc = FALSE;
    if( $qty > 0 )
    {

      $date = $date == '' ? date('Y-m-d') : $date;
      $id = $this->isExists($id_pd, $cost);
      if( $id !== FALSE )
      {
        $sc = dbQuery("UPDATE tbl_product_cost SET qty = qty + ".$qty." WHERE id = '".$id."'");
      }
      else
      {
        $qr  = "INSERT INTO tbl_product_cost (id_product, cost, qty, date_add) ";
        $qr .= "VALUES ('".$id_pd."', '".$cost."', '".$qty."', '".$date."')";
        $sc  = dbQuery($qr);
      }
    }

    return $sc;
  }


  public function removeCostList($id_pd, $qty)
  {
    $sc = TRUE;

    if( $qty != 0 )
    {
      $qs = dbQuery("SELECT * FROM tbl_product_cost WHERE id_product = '".$id_pd."' ORDER BY date_add ASC");
      //--- ถ้ามีผลลัพธ์แค่ แถวเดียว
      if(dbNumRows($qs) == 1 )
      {
        $rs = dbFetchObject($qs);
        //--- ถ้ายอดที่จะตัดน้อยกว่าหรือเท่ากับยอดที่มี ให้ใช้ยอดที่จะตัด ถ้ามากกว่าให้ใช้ยอดคงเหลือ
        $c_qty = ($rs->qty - $qty) >= 0 ? $qty : $rs->qty;

        if(dbQuery("UPDATE tbl_product_cost SET qty = qty - ".$c_qty." WHERE id = '".$rs->id."'") === FALSE)
        {
          $sc = FALSE;
        }
      }
      else if(dbNumRows($qs) > 1)
      {
        while($rs = dbFetchObject($qs))
        {
          if($qty <= 0)
          {
            break;
          }
          
          //--- ถ้ายอดที่จะตัดน้อยกว่าหรือเท่ากับยอดที่มี ให้ใช้ยอดที่จะตัด ถ้ามากกว่าให้ใช้ยอดคงเหลือ
          $c_qty = ($rs->qty - $qty) >= 0 ? $qty : $rs->qty;

          if(dbQuery("UPDATE tbl_product_cost SET qty = qty - ".$c_qty." WHERE id = '".$rs->id."'") === FALSE)
          {
            $sc = FALSE;
          }

          $qty -= $c_qty;
        }
      }

        $this->dropZero();
      }
      return $sc;
    }





  public function deleteCostList($id_pd, $cost, $qty)
  {
    $sc = TRUE;

    if( $qty != 0 )
    {
      $qs = dbQuery("SELECT * FROM tbl_product_cost WHERE id_product = '".$id_pd."' AND cost = '".$cost."' ORDER BY date_add ASC");
      //--- ถ้ามีผลลัพธ์มากกว่า 0
      if(dbNumRows($qs) > 0 )
      {
        while($rs = dbFetchObject($qs))
        {
          if($qty <= 0)
          {
            break;
          }

          //--- ถ้ายอดที่จะตัดน้อยกว่าหรือเท่ากับยอดที่มี ให้ใช้ยอดที่จะตัด ถ้ามากกว่าให้ใช้ยอดคงเหลือ
          $c_qty = ($rs->qty - $qty) >= 0 ? $qty : $rs->qty;

          if(dbQuery("UPDATE tbl_product_cost SET qty = qty - ".$c_qty." WHERE id = '".$rs->id."'") === FALSE)
          {
            $sc = FALSE;
          }

          $qty -= $c_qty;
        }

        $this->dropZero();
      }
      else
      {
        $sc = $this->removeCostList($id_pd, $qty);
      }
    }

    return $sc;
  }

  public function isExists($id_product, $cost)
  {
    $sc = FALSE;
    $cost = round($cost, 2);
    $qs = dbQuery("SELECT id FROM tbl_product_cost WHERE id_product = '".$id_product."' AND cost = '".$cost."'");
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }


  public function getCost($id_pd)
  {
    $sc = 0.00;
    $cost = 0;
    $qty = 0;
    $qs = dbQuery("SELECT cost, qty FROM tbl_product_cost WHERE id_product = '".$id_pd."'");
    if( dbNumRows($qs) == 1)
    {
      $rs = dbFetchObject($qs);
      $sc = $rs->cost;
    }
    else if( dbNumRows($qs) > 1)
    {
      while($rs = dbFetchObject($qs))
      {
        $cost += $rs->cost * $rs->qty;
        $qty  += $rs->qty;
      }

      $sc = $cost / $qty;
    }
    else
    {
      $product = new product();
      $sc = $product->getCost($id_pd);
    }

    return $sc;
  }




  private function dropZero()
  {
    return dbQuery("DELETE FROM tbl_product_cost WHERE qty <= 0 ");
  }

}

 ?>
