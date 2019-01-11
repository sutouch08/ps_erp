<?php
class return_lend
{
  public $id;
  public $bookcode;
  public $reference;
  public $id_order;
  public $order_code;
  public $id_customer;
  public $id_employee;
  public $isCancle = 0;
  public $remark;
  public $date_add;
  public $date_upd;
  public $emp_upd;
  public $error;

  public function __construct($id = '')
  {
    if($id != '' && $id != FALSE)
    {
      $this->getData($id);
    }
  }



  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_return_lend WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);

      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }



  public function add(array $ds = array())
  {
    $sc = FALSE;
    if(!empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ', '.$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $qs = dbQuery("INSERT INTO tbl_return_lend (".$fields.") VALUES (".$values.")");
      if($qs === TRUE)
      {
        $sc = dbInsertId();
      }
      else
      {
        $this->error = dbError();
      }
    }

    return $sc;
  }




  public function addDetail(array $ds = array())
  {
    $sc = FALSE;
    if(!empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ', '.$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_return_lend_detail (".$fields.") VALUES (".$values.")");

      if($sc !== TRUE)
      {
        $this->error = dbError();
      }
    }
    else
    {
      $this->error = 'Can not insert empty data';
    }

    return $sc;
  }





  public function getDetails($id)
  {
    return dbQuery("SELECT * FROM tbl_return_lend_detail WHERE id_return_lend = '".$id."'");
  }





  public function getSumQty($id)
  {
    $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_return_lend_detail WHERE id_return_lend = '".$id."'");
    list($qty) = dbFetchArray($qs);

    return is_null($qty) ? 0 : $qty;
  }



  //--- cancle Detail
  public function cancleDetails($id)
  {
    return dbQuery("UPDATE tbl_return_lend_detail SET isCancle = 1 WHERE id_return_lend = '".$id."'");
  }



  //--- cancle เอกสาร
  public function setCancle($id)
  {
    return dbQuery("UPDATE tbl_return_lend SET isCancle = 1 WHERE id = '".$id."'");
  }


  //-----------------  New Reference --------------//
	public function getNewReference($date='')
	{
		$date = $date == '' ? date('Y-m-d') : $date;
		$Y		= date('y', strtotime($date));
		$M		= date('m', strtotime($date));
		$prefix = getConfig('PREFIX_RETURN_LEND');
		$runDigit = getConfig('RUN_DIGIT_LEND'); //--- รันเลขที่เอกสารกี่หลัก
		$preRef = $prefix . '-' . $Y . $M;
		$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_return_lend WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");
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


} //--- end class
 ?>
