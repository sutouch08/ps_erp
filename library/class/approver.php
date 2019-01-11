<?php
class approver
{

  public $id;
  public $doc_type;
  public $id_employee;
  public $name;
  public $approve_key;
  public $active;

  public function __construct($id=''){
    if($id != '')
    {
      $this->getData($id);
    }
  }


  //---
  public function getApprover($doc_type, $id_emp)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT * FROM tbl_approver WHERE doc_type = '".$doc_type."' AND id_employee = '".$id_emp."' AND active = 1");
    if( dbNumRows($qs) == 1 )
    {
      $sc = dbFetchObject($qs);
    }

    return $sc;
  }



  public function isExistsApprover($doc_type, $id_emp)
  {
    $qs = dbQuery("SELECT * FROM tbl_approver WHERE doc_type = '".$doc_type."' AND id_employee = '".$id_emp."'");
    if( dbNumRows($qs) == 1 )
    {
      return TRUE;
    }

    return FALSE;
  }



  public function getData($id)
  {
    $qr  = "SELECT ap.id, ap.doc_type, em.id_employee, em.first_name, em.last_name, ap.approve_key, ap.active ";
  	$qr .= "FROM tbl_approver AS ap ";
  	$qr .= "JOIN tbl_employee AS em ON ap.id_employee = em.id_employee ";
  	$qr .= "WHERE ap.id = '".$id."' ";

  	$qs = dbQuery($qr);

  	if(dbNumRows($qs) == 1)
  	{
  		$rs = dbFetchObject($qs);
      $this->id = $rs->id;
      $this->doc_type = $rs->doc_type;
      $this->id_employee = $rs->id_employee;
      $this->name = $rs->first_name.' '.$rs->last_name;
      $this->approv_key = $rs->approve_key;
      $this->active = $rs->active;
  	}
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      $i = 1;
      $fields = "";
      $values = "";
      foreach($ds as $field => $value)
      {
        $fields .= $i== 1 ? $field : ", ".$field;
        $values .= $i== 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $qs = dbQuery("INSERT INTO tbl_approver (".$fields.") VALUES (".$values.")");
      if($qs)
      {
        return dbInsertId();
      }
    }

    return FALSE;
  }



  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      $i = 1;
      $set = "";
      foreach($ds as $field=>$value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }
      $sc = dbQuery("UPDATE tbl_approver SET ".$set." WHERE id = ".$id);
      return $sc;
    }

    return FALSE;
  }


  public function delete($id)
  {
    return dbQuery("DELETE FROM tbl_approver WHERE id = '".$id."'");
  }






} //--- end class;
 ?>
