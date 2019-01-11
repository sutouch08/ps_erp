<?php
class address
{
	public $id_address;
	public $id_customer;
	public $alias;
	public $company;
	public $first_name;
	public $last_name;
	public $address1;
	public $address2;
	public $city;
	public $postcode;
	public $phone;
	public $remark;
	public $error = FALSE;
	
	public function __construct($id = '')
	{
		if( $id != '')
		{
			$qs = dbQuery("SELECT * FROM tbl_address WHERE id_address = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchArray($qs);
				$this->id_address 	= $id;
				$this->id_customer 	= $rs['id_customer'];
				$this->alias				= $rs['alias'];
				$this->company		= $rs['company'];
				$this->first_name		= $rs['first_name'];
				$this->last_name		= $rs['last_name'];
				$this->address1		= $rs['address1'];
				$this->address2		= $rs['address2'];
				$this->city				= $rs['city'];
				$this->postcode		= $rs['postcode'];
				$this->phone			= $rs['phone'];
				$this->remark			= $rs['remark'];
			}
			else
			{
				$this->error = 'No address found';
			}
		}
	}
	
	
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
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
			$sc = dbQuery("INSERT INTO tbl_address (".$fields.") VALUES (".$values.")");
		}
		return $sc;			
	}
	
	
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;	
			}
			$sc = dbQuery("UPDATE tbl_address SET " . $set . " WHERE id_address = '".$id."'");
		}
		return $sc;
	}
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_address WHERE id_address = '".$id."'");
	}
	
	

	public function getCustomerAddress($id_customer)
	{
		return dbQuery("SELECT * FROM tbl_address WHERE id_customer = '".$id_customer."'");	
	}
}/// end class

?>