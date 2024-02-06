<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('userMaster');
		$this->load->library('Acc_Model');
		$this->load->library('Fx');
		$this->load->library('Html');
	}
	
	public function index() {
		$this->load->view('UserMaster/userMaster.php');
	}
	
	public function show_user_list() {
		// print_r($_POST);die;
		$recordsPerPage = $this->input->post('recordsPerPage');
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$s_name = $this->input->post('s_name');
		$s_email = $this->input->post('s_email');
		$s_phoneno = $this->input->post('s_phoneno');
		$column_name = $this->input->post('column_name');
		$sorting = $this->input->post('sorting');
		
		
		$offset = ($page - 1) * $recordsPerPage;
		$data = $this->userMaster->show($s_name, $s_email, $s_phoneno, $column_name, $sorting,$recordsPerPage,$offset);
		
		$row = $data['rows'][0];
		// print_r($row);die;

		$this->html->tableList($data['rows'], $data['count'], $recordsPerPage, $page,array('name','email_id','mobile_no'),$row->id,0,0);
	
		// print_r($output);die;

		// echo json_encode(array('table' => $output));

		// $output = "";
		// if ($data['num'] > 0) {
		// 	// print_r($data['rows']);die;
		// 	$serial = $offset + 1;
		// 	foreach ($data['rows'] as $row) {
		// 		$sid_d = $row->id;
		// 		$output .= "
		// 		<tr>
		// 		<td class='show_u_data'>{$row->id}</td>
		// 		<td class='show_u_data edit_btn_style' onclick='user_editrec({$sid_d})'>{$row->name}</td>
		// 		<td class='show_u_data td-wrap' title='{$row->email_id}'>{$row->email_id}</td>
		// 		<td class='show_u_data'>{$row->mobile_no}</td>
		// 		<td align='center'>
		// 		<button type='button' id='edit'  class='show_u_data' onclick='user_editrec({$sid_d})' style='border:none;background-color:transparent;'><i class='fa-regular fa-pen-to-square'></i></button>
		// 		</td>
		// 		<td>
		// 		<button type='button'  id='delt'  class='show_u_data' onclick='user_deleterec({$sid_d})' style='border:none;background-color:transparent;'><i class='fa-regular fa-trash-can' style='color:red;'></i></button>
		// 		</td>
		// 		</tr>";
		// 		$serial++;
		// 	}
		// 	// print_r($data['rows2']);die;
		// 	// print_r($sid_d);die;
		// 	// print_r($output);die;
		// 	// $totalrecordsqueryResult = $conn -> Execute($temp);
		// 	$data=$data['count'];
        //     // $totalRecords = $data -> num_rows();
        //     $totalPages = ceil($data / $recordsPerPage);

	
		// 	$pagination = '';
		// 	for ($i = 1; $i <= $totalPages; $i++) {
		// 		$isActive = ($i == $page) ? 'active' : '';
		// 		$pagination .= "<button class='border pagination-btn $isActive' onclick='show_user_master_data($i)' style='color: #000; padding: 5px 10px; margin: 2px; border: 1px solid #337ab7;'>$i</button><br><br>";
		// 	}
		// 	echo json_encode(array('table' => $output, 'pagination' => $pagination));
		// } else {
		// 	$output = "<tr><td colspan='6'><h2>No Record Found</h2></td></tr>";
		// 	$pagination = "";
		// 	echo json_encode(array('table' => $output, 'pagination' => $pagination));
		// }
	}
	
	public function delete() {
		
		$d_id=$_POST['id'];
		$data = $this->userMaster->delete($d_id);
		
		if ($data['code']==400){
			echo json_encode(array('statuscode' => 400,'message' => 'delete data successfully'));
		}
		else{
			echo json_encode(array('statuscode' => 200,'message' => ' user login!!!! '));
		}
		
	}
	public function insert() {

		$rules = array(
            array(
                'field' => 'user_name',
                'label' => 'Name',
                'rules' => 'required'
            ),

            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|trim'
            ),
            array(
                'field' => 'mobile_no',
                'label' => 'Phone No',
                'rules' => 'required|trim|integer|exact_length[10]'
            )
        );
        $this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE) {
			// print_r('hello');die;
			
			$name=$email=$no=$pwd=$h_input="";
			
			$h_input=$this->input->post('h_input');
			$name=$this->input->post('user_name');
			$email=$this->input->post('email');
			$no=$this->input->post('mobile_no');
			$pwd=$this->input->post('pwd');
			$hid_pwd=$this->input->post('hid_pwd');
			$s_pwd = password_hash($pwd, PASSWORD_DEFAULT);
			
			$data = $this->userMaster->insert($h_input,$name,$email,$no,$pwd,$hid_pwd,$s_pwd);
		}
		else{
			echo json_encode(array('statuscode' => 150, 'message' => validation_errors()));

		}

		
	}
	public function edit() {
		$e_id=$_POST['id'];
		$data = $this->userMaster->edit($e_id);		
	}

 
}
