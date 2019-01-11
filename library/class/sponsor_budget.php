<?php
class sponsor_budget
{
  public $id;
  public $reference;
  public $id_sponsor;
  public $id_customer;
  public $start;
  public $end;
  public $year;
  public $budget = 0.00;
  public $used = 0.00;
  public $balance = 0.00;
  public $active = 0;
  public $remark;
  public $approver;
  public $approve_key;
  public $date_upd;

  public function __construct($id = '')
  {
    if( $id != '' && $id != 0)
    {
      $this->getData($id);
    }
  }


  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_sponsor_budget WHERE id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }


  public function add(array $ds )
	{
		$sc = FALSE;
		if( ! empty($ds) )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_sponsor_budget (".$fields.") VALUES (".$values.")");
		}

		return $sc === TRUE ? dbInsertId() : FALSE;
	}



  public function update($id, array $ds = array())
	{
		$sc = FALSE;
		if( ! empty( $ds ) )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;
			}

			$sc = dbQuery("UPDATE tbl_sponsor_budget SET " . $set . " WHERE id = '".$id."'");
		}

		return $sc;
	}





  public function deleteSponsorBudget($id_sponsor)
  {
    $id_emp = getCookie('user_id');
    return dbQuery("UPDATE tbl_sponsor_budget SET is_deleted = 1, emp_deleted = ".$id_emp." WHERE id_sponsor = ".$id_sponsor);
  }




  //--- ตรวจสอบว่าปีซ้ำหรือไม่
  public function isExistsYear($id_sponsor, $year, $id_budget = FALSE)
  {
    $sc = FALSE;
    if( $id_budget !== FALSE )
    {
      $qs = dbQuery("SELECT id FROM tbl_sponsor_budget WHERE id_sponsor = ".$id_sponsor." AND year = '".$year."' AND id != ".$id_budget);
    }
    else
    {
      $qs = dbQuery("SELECT id FROM tbl_sponsor_budget WHERE id_sponsor = ".$id_sponsor." AND year = '".$year."'");
    }


    if( dbNumRows($qs) > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }





  //--- คำนวนงบประมาณคงเหลือใหม่
  public function calculate($id)
  {
    return dbQuery("UPDATE tbl_sponsor_budget SET balance = budget - used WHERE id = ".$id);
  }



  public function getBudgetList($id_sponsor)
  {
    return dbQuery("SELECT * FROM tbl_sponsor_budget WHERE id_sponsor = ".$id_sponsor);
  }



  public function getSponsorBudgetYear($id_sponsor)
  {
    return dbQuery("SELECT id, year FROM tbl_sponsor_budget WHERE id_sponsor = ".$id_sponsor);
  }





  public function increaseUsed( $id, $amount = 0 )
	{
		$sc = dbQuery("UPDATE tbl_sponsor_budget SET used = used + ".$amount." WHERE id = ".$id);
		if( $sc )
		{
			$sc = $this->calculate($id);
		}
		return $sc;
	}





	public function decreaseUsed( $id, $amount = 0 )
	{
		$sc = dbQuery("UPDATE tbl_sponsor_budget SET used = used - ".$amount." WHERE id = ".$id);
		if( $sc )
		{
			$sc = $this->calculate($id);
		}
		return $sc;
	}


  public function getBalance($id)
  {
    $sc = 0;
    $qs = dbQuery("SELECT balance FROM tbl_sponsor_budget WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }



  public function isEnought($id, $amount)
  {
    $balance = $this->getBalance($id);
    return  $amount <= $balance ? TRUE : FALSE;
  }

}
 ?>
