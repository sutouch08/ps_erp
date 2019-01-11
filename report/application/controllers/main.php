<?php 
class Main extends CI_Controller
{

	public $home;
	public $layout = "include/template";
	public $title = "ยินดีต้อนรับ";
	public $id_customer;
	
	public function __construct()
	{
		parent::__construct();		
		//$this->load->model("main_model");
		$this->home = base_url()."main";
		$this->id_customer = getIdCustomer();
	}
	
	public function index()
	{
		$data['view'] 			= "main";
		$data['id_customer'] 	= $this->id_customer;
		$this->load->view($this->layout, $data);
	}
}

?>