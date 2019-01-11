<?php
class box
{
  public function __construct(){ }



  public function getBox($barcode, $id_order)
  {
    $sc = $this->isExists($barcode, $id_order);

    if( $sc === FALSE )
    {
      $sc = $this->add($barcode, $id_order);
    }

    return $sc;
  }



  public function getBoxNo($id_box)
  {
    $sc = 1;
    $qs = dbQuery("SELECT box_no FROM tbl_box WHERE id_box = ".$id_box);
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }
    return $sc;
  }



  public function isExists($barcode, $id_order)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id_box FROM tbl_box WHERE barcode = '".$barcode."' AND id_order = '".$id_order."'");
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return  $sc;
  }





  public function add($barcode, $id_order)
  {
    $sc = FALSE;
    $no = $this->getNextNo($id_order);
    $qs = dbQuery("INSERT INTO tbl_box (barcode, id_order, box_no) VALUES ('".$barcode."', ".$id_order.", ".$no.")");
    if( $qs === TRUE )
    {
      $sc = dbInsertId();
    }
    return $sc;
  }




  public function getNextNo($id_order)
  {
    $sc = 1;
    $qs = dbQuery("SELECT MAX(box_no) FROM tbl_box WHERE id_order = ".$id_order);
    if( dbNumRows($qs) == 1 )
    {
      list($sc) = dbFetchArray($qs);
      $sc++;
    }

    return $sc;
  }


  public function getTotalBox($id_order)
  {
    $qs = dbQuery("SELECT MAX(box_no) AS no FROM tbl_box WHERE id_order = '".$id_order."'");
    list($no) = dbFetchArray($qs);

    return is_null($no) ? 1 : $no;
  }



} //--- end class


 ?>
