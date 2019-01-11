<?php
class Login extends CI_Controller
{
public function __construct()
{
	parent::__construct();	
	$this->load->model("login_model");
}

public function index()
{
	if( $this->input->post("user_name") && $this->input->post("password") )
	{
		$user 	= $this->input->post("user_name");
		$pass 	= md5($this->input->post("password"));
		$id_c		= $this->input->post("id_customer");
		$rmbm	= $this->input->post("rememberme");
		$rs = $this->login_model->get_customer($user, $pass);
		if( $rs )
		{
			if($rs->active)
			{
				$time = $rmbm == 1 ? 3600*24*365 : 3600*24;
				$this->input->set_cookie("id_customer", $rs->id_customer, $time);
				$this->login_model->loged($rs->id_customer);
				$this->login_model->update_cart($id_c, $rs->id_customer);
				echo "success";
			}
			else
			{
				echo "not_active";	
			}
		}
		else
		{
			echo "fail";	
		}
	}
}

public function logout()
{
	$this->input->set_cookie("id_customer", "", 0);	
}
	
}/// end class

?>