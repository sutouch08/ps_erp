<?php
class order_role
{

  public $id;
  public $name;

  public function __construct($id=""){
    if($id !=''){
      $qs = dbQuery("SELECT * FROM tbl_order_role WHERE id = '".$id."'");
      if(dbNumRows($qs) == 1 ){
        $rs = dbFetchObject($qs);
        $this->id = $rs->id;
        $this->name = $rs->name;
      }
    }
  }


  public function getName($id){
    $sc = '';
    $qs = dbQuery("SELECT name FROM tbl_order_role WHERE id = ".$id);
    if(dbNumRows($qs) == 1 ){
      list( $sc ) = dbFetchArray($qs);
    }
    return $sc;
  }



}

 ?>
