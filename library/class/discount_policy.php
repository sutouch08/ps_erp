<?php
class discount_policy
{
  public $id;
  public $reference;
  public $name;
  public $date_start;
  public $date_end;
  public $isApproved;
  public $id_emp;
  public $approver;
  public $approve_key;
  public $date_add;
  public $date_upd;
  public $emp_upd;
  public $active;
  public $isDeleted;
  public $error;

  public function __construct($id = '')
  {
    if( $id != '' && $id !== FALSE)
    {
      $this->getData($id);
    }
  }



  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_discount_policy WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);
      foreach ($rs as $key => $value)
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
      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_discount_policy (".$fields.") VALUES (".$values.")");

      if( $sc === FALSE )
      {
        $this->error = dbError();
      }
    }

    return $sc === TRUE ? dbInsertId() : FALSE;
  }




  public function update($id, array $ds = array())
  {
    $sc = FALSE;
    if(!empty($ds))
    {
      $set = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }

      $sc = dbQuery("UPDATE tbl_discount_policy SET ".$set." WHERE id = ".$id);
      if( $sc !== TRUE)
      {
        $this->error = dbError();
      }
    }

    return $sc;
  }



  public function deletePolicy($id, $option = 'HIDE')
  {
    if($option == 'HIDE')
    {
      return dbQuery("UPDATE tbl_discount_policy SET isDeleted = 1, emp_upd = '".getCookie('user_id')."' WHERE id = ".$id);
    }

    if($option == 'DELETE')
    {
      return dbQuery("DELETE FROM tbl_discount_policy WHERE id = ".$id);
    }

    return FALSE;
  }




  public function countOrderSold($id)
  {
    $qs = dbQuery("SELECT COUNT(id_policy) FROM tbl_order_sold WHERE id_policy = '".$id."'");
    list($count) = dbFetchArray($qs);

    return $count;
  }



  //-----------------  New Reference --------------//
	public function getNewReference($date = '')
	{
		$date = $date == '' ? date('Y-m-d') : $date;
		$Y		= date('y', strtotime($date));
		$M		= date('m', strtotime($date));
		$prefix = getConfig('PREFIX_POLICY');
		$runDigit = getConfig('RUN_DIGIT'); //--- รันเลขที่เอกสารกี่หลัก
		$preRef = $prefix . '-' . $Y . $M;
		$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_discount_policy WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");
		list( $ref ) = dbFetchArray($qs);
		if( ! is_null( $ref ) )
		{
			$runNo = mb_substr($ref, ($runDigit*-1), NULL, 'UTF-8') + 1;
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', $runNo);
		}
		else
		{
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', '001');
		}
		return $reference;
	}



  public function search($fields, $txt, $limit = 50)
  {
    if($txt == '*')
    {
      return dbQuery("SELECT ".$fields." FROM tbl_discount_policy ORDER BY reference DESC LIMIT ".$limit);
    }
    else
    {
      return dbQuery("SELECT ".$fields." FROM tbl_discount_policy WHERE code LIKE '%".$txt."' OR name LIKE '%".$txt."%' LIMIT ".$limit);
    }

  }

} //--- end class

 ?>
