<?php
class Login_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_customer($user, $pass)
	{
		$rs = $this->db->where("email", $user)->where("password", $pass)->get("tbl_customer");
		if($rs->num_rows() == 1 )
		{
			return $rs->row();
		}
		else
		{
			return false;
		}
	}
	
	public function loged($id_customer)
	{
		return $this->db->where("id_customer", $id_customer)->update("tbl_customer", array("last_login" => NOW()));	
	}
	
	public function update_cart($id_c, $id_customer)
	{
		return $this->db->where("id_customer", $id_c)->update("tbl_cart", array("id_customer" => $id_customer));	
	}
	
	
	
}/// end class

?>