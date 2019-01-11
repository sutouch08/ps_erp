<?php
function getIdCustomer()
{
	$id_customer = 0;
	$c =& get_instance();
	if( !$c->input->cookie("id_customer") )
	{
		$id_customer = uniqid();
		$c->input->set_cookie("id_customer", $id_customer, (3600*24*14));
	}
	else
	{
		$id_customer = $c->input->cookie("id_customer");
	}
	return $id_customer;
}
		

?>