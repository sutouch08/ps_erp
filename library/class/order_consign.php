<?php
class order_consign
{
  public $id;
  public $id_order;
  public $id_zone;
  public $date_upd;

  public function __construct($id = '')
  {
    if( $id != '')
    {
      $qs = dbQuery("SELECT * FROM tbl_order_consign WHERE id_order = '".$id."'");
      if( dbNumRows($qs) == 1)
      {
        $rs = dbFetchObject($qs);
        $this->id = $rs->id;
        $this->id_order = $rs->id_order;
        $this->id_zone = $rs->id_zone;
        $this->date_upd = $rs->date_upd;
      }
    }
  }




  public function add($id_order, $id_zone)
  {
    return dbQuery("INSERT INTO tbl_order_consign (id_order, id_zone) VALUES ('".$id_order."', '".$id_zone."')");
  }



  public function update($id_order, $id_zone)
  {
    return dbQuery("UPDATE tbl_order_consign SET id_zone = '".$id_zone."' WHERE id_order = '".$id_order."'");
  }


  public function getIdZone($id_order)
  {
    $sc = '';
    $qs = dbQuery("SELECT id_zone FROM tbl_order_consign WHERE id_order = '".$id_order."'");
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }

} //--- End class
 ?>
