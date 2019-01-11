<?php
class discount_rule
{
  public $id = 0;
  public $code;
  public $name;
  public $id_discount_policy = 0;
  public $error;


  public function __construct($id = '')
  {
    if(is_numeric($id))
    {
      $this->getData($id);
    }
  }



  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_discount_rule WHERE id = ".$id);
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
    if(!empty($ds))
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

      $qs = dbQuery("INSERT INTO tbl_discount_rule (".$fields.") VALUES (".$values.")");
      if($qs === TRUE)
      {
        return dbInsertId();
      }

      $this->error = dbError();
    }

    return FALSE;
  }



  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      $set = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }

      $qs = dbQuery("UPDATE tbl_discount_rule SET ".$set." WHERE id = ".$id);
      if($qs !== TRUE)
      {
        $this->error = dbError();
      }

      return $qs;
    }

    return FALSE;
  }





  public function deleteRule($id, $option)
  {
    if($option == 'HIDE')
    {
      $sc = dbQuery("UPDATE tbl_discount_rule SET isDeleted = 1 WHERE id = ".$id);
    }
    else if($option == 'DELETE')
    {
      $sc = dbQuery("DELETE FROM tbl_discount_rule WHERE id = ".$id);
    }

    return $sc;
  }




  public function countOrder($id)
  {
    $qs = dbQuery("SELECT COUNT(id_rule) FROM tbl_order_detail WHERE id_rule = '".$id."'");
    list($count) = dbFetchArray($qs);
    return $count;
  }




  public function countOrderSold($id)
  {
    $qs = dbQuery("SELECT COUNT(id_rule) FROM tbl_order_sold WHERE id_rule = '".$id."'");
    list($count) = dbFetchArray($qs);
    return $count;
  }





  public function getPolicyId($id)
  {
    $sc = 0;
    $qs = dbQuery("SELECT id_discount_policy FROM tbl_discount_rule WHERE id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }




  public function getPolicyCode($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT p.reference FROM tbl_discount_rule AS r JOIN tbl_discount_policy AS p ON r.id_discount_policy = p.id WHERE r.id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }





  public function getPolicyName($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT p.name FROM tbl_discount_rule AS r JOIN tbl_discount_policy AS p ON r.id_discount_policy = p.id WHERE r.id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }






  public function getCode($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT code FROM tbl_discount_rule WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }






  public function getId($code)
  {
    $sc = '';
    $qs = dbQuery("SELECT id FROM tbl_discount_rule WHERE code = '".$code."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }






  public function getName($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT name FROM tbl_discount_rule WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }





  //-----------------  New Reference --------------//
	public function getNewReference($date = '')
	{
		$date = $date == '' ? date('Y-m-d') : $date;
		$Y		= date('y', strtotime($date));
		$M		= date('m', strtotime($date));
		$prefix = getConfig('PREFIX_RULE');
		$runDigit = getConfig('RUN_DIGIT'); //--- รันเลขที่เอกสารกี่หลัก
		$preRef = $prefix . '-' . $Y . $M;
		$qs = dbQuery("SELECT MAX(code) AS code FROM tbl_discount_rule WHERE code LIKE '".$preRef."%' ORDER BY code DESC");
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




  public function getRuleList($id_policy)
  {
    return dbQuery("SELECT * FROM tbl_discount_rule WHERE id_discount_policy = '".$id_policy."'");
  }


  public function getActiveRuleList()
  {
    return dbQuery("SELECT * FROM tbl_discount_rule WHERE active = 1 AND id_discount_policy = 0 ORDER BY code DESC");
  }



  public function setAllProduct($id, $val)
  {
    return dbQuery("UPDATE tbl_discount_rule SET all_product = ".$val." WHERE id = ".$id);
  }



  //--- ผูกกฎกับนโยบาย
  public function setPolicy($id_rule, $id_policy)
  {
    return dbQuery("UPDATE tbl_discount_rule SET id_discount_policy = ".$id_policy." WHERE id = ".$id_rule);
  }


  //--- ยกเลิกการผูกกฎ
  public function unsetPolicy($id_rule)
  {
    return dbQuery("UPDATE tbl_discount_rule SET id_discount_policy = 0 WHERE id = ".$id_rule);
  }




  public function getCustomerRuleList($id)
  {
    $qr  = "SELECT cs.id, cs.code, cs.name FROM tbl_discount_rule_customers AS cr ";
    $qr .= "JOIN tbl_customer AS cs ON cr.id_customer = cs.id ";
    $qr .= "WHERE cr.id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getCustomerGroupRule($id)
  {
    $qr  = "SELECT cs.id, cs.code, cs.name FROM tbl_discount_rule_customer_group AS cr ";
    $qr .= "JOIN tbl_customer_group AS cs ON cr.id_customer_group = cs.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getCustomerTypeRule($id)
  {
    $qr  = "SELECT cs.id, cs.code, cs.name FROM tbl_discount_rule_customer_type AS cr ";
    $qr .= "JOIN tbl_customer_type AS cs ON cr.id_customer_type = cs.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getCustomerKindRule($id)
  {
    $qr  = "SELECT cs.id, cs.code, cs.name FROM tbl_discount_rule_customer_kind AS cr ";
    $qr .= "JOIN tbl_customer_kind AS cs ON cr.id_customer_kind = cs.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }

  public function getCustomerAreaRule($id)
  {
    $qr  = "SELECT cs.id, cs.code, cs.name FROM tbl_discount_rule_customer_area AS cr ";
    $qr .= "JOIN tbl_customer_area AS cs ON cr.id_customer_area = cs.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }

  public function getCustomerClassRule($id)
  {
    $qr  = "SELECT cs.id, cs.code, cs.name FROM tbl_discount_rule_customer_class AS cr ";
    $qr .= "JOIN tbl_customer_class AS cs ON cr.id_customer_class = cs.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getProductStyleRule($id)
  {
    $qr = "SELECT ps.id, ps.code FROM tbl_discount_rule_product_style AS sr ";
    $qr .= "JOIN tbl_product_style AS ps ON sr.id_product_style = ps.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getProductGroupRule($id)
  {
    $qr = "SELECT ps.id, ps.code, ps.name FROM tbl_discount_rule_product_group AS sr ";
    $qr .= "JOIN tbl_product_group AS ps ON sr.id_product_group = ps.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getProductSubGroupRule($id)
  {
    $qr = "SELECT ps.id, ps.code, ps.name FROM tbl_discount_rule_product_sub_group AS sr ";
    $qr .= "JOIN tbl_product_sub_group AS ps ON sr.id_product_sub_group = ps.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }

  public function getProductTypeRule($id)
  {
    $qr = "SELECT ps.id, ps.code, ps.name FROM tbl_discount_rule_product_type AS sr ";
    $qr .= "JOIN tbl_product_type AS ps ON sr.id_product_type = ps.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }

  public function getProductKindRule($id)
  {
    $qr = "SELECT ps.id, ps.code, ps.name FROM tbl_discount_rule_product_kind AS sr ";
    $qr .= "JOIN tbl_product_kind AS ps ON sr.id_product_kind = ps.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }

  public function getProductCategoryRule($id)
  {
    $qr = "SELECT ps.id, ps.code, ps.name FROM tbl_discount_rule_product_category AS sr ";
    $qr .= "JOIN tbl_product_category AS ps ON sr.id_product_category = ps.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getProductBrandRule($id)
  {
    $qr = "SELECT ps.id, ps.code, ps.name FROM tbl_discount_rule_product_brand AS sr ";
    $qr .= "JOIN tbl_brand AS ps ON sr.id_product_brand = ps.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getProductYearRule($id)
  {
    $qr = "SELECT year FROM tbl_discount_rule_product_year WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getChannelsRule($id)
  {
    $qr = "SELECT cn.name FROM tbl_discount_rule_channels AS cr ";
    $qr .= "JOIN tbl_channels AS cn ON cr.id_channels = cn.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }


  public function getPaymentRule($id)
  {
    $qr = "SELECT cn.name FROM tbl_discount_rule_payment AS cr ";
    $qr .= "JOIN tbl_payment_method AS cn ON cr.id_payment = cn.id ";
    $qr .= "WHERE id_rule = ".$id;

    return dbQuery($qr);
  }
}

 ?>
