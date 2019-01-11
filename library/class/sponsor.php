<?php
class sponsor
{

  public $id;
  public $name;
  public $id_customer;
  public $year;

  public function __construct($id = '')
  {
      if($id != '')
      {
        $this->getData($id);
      }
  }


  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_sponsor WHERE id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }


  public function getDataByCustomer($id_customer)
  {
    $qs = dbQuery("SELECT * FROM tbl_sponsor WHERE id_customer = '".$id_customer."'");
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
			$sc = dbQuery("INSERT INTO tbl_sponsor (".$fields.") VALUES (".$values.")");
		}

		return $sc === TRUE ? dbInsertId() : FALSE;
	}



  public function update($id, array $ds)
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
			$sc = dbQuery("UPDATE tbl_sponsor SET " . $set . " WHERE id = '".$id."'");
		}

		return $sc;
	}





  public function delete($id){
    return dbQuery("DELETE FROM tbl_sponsor WHERE id = ".$id);
  }


  public function isExistsCustomer($id_customer)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_sponsor WHERE id_customer = '".$id_customer."'");
    if( dbNumRows($qs) > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }





  public function getId($id_customer)
  {
    $sc = 0;
    $qs = dbQuery("SELECT id FROM tbl_sponsor WHERE id_customer = '".$id_customer."'");
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }





  public function getSponsorAndBudgetBalance($txt, $date)
  {
    $qr = "SELECT s.id_customer, c.code, c.name, s.id_budget, b.balance FROM tbl_sponsor AS s ";
    $qr .= "LEFT JOIN tbl_sponsor_budget AS b ON s.id_budget = b.id ";
    $qr .= "LEFT JOIN tbl_customer AS c ON b.id_customer = c.id ";
    $qr .= "WHERE b.active = 1 AND b.is_deleted = 0 AND s.name LIKE '%".$txt."%' ";
    $qr .= "AND b.start <= '".fromDate($date)."' AND b.end >= '".toDate($date)."'";

    return dbQuery($qr);
  }


  public function getBudgetBalanceByCustomer($id_customer)
  {
    $sc = 0;
    $qr  = "SELECT balance FROM tbl_sponsor AS s ";
    $qr .= "JOIN tbl_sponsor_budget AS b ON s.id_budget = b.id ";
    $qr .= "WHERE s.id_customer = '".$id_customer."'";

    $qs = dbQuery($qr);
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }



  public function isSponsorExists($id_customer, $id_sponsor)
  {
    $id = FALSE;
    if($id_sponsor === FALSE)
    {
      $qs = dbQuery("SELECT id FROM tbl_sponsor WHERE id_customer = '".$id_customer."'");
    }
    else
    {
      $qs = dbQuery("SELECT id FROM tbl_sponsor WHERE id_customer = '".$id_customer."' AND id != '".$id_sponsor."'");
    }

    if(dbNumRows($qs) == 1)
    {
      list($id) = dbFetchArray($qs);
    }

    return $id;
  }



  public function search($txt, $fields, $limit = 50)
  {
    $qr = "SELECT ".$fields." FROM tbl_sponsor ";
    if($txt != '*')
    {
      $qr .= "WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%' ";
    }

    $qr .= "ORDER BY code ASC LIMIT ".$limit;

    return dbQuery($qs);
  }


} //--- End class
 ?>
