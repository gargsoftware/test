<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class userMaster extends CI_Model {  
    public function __construct()
    {
        parent::__construct();
    }
    
    public function show($s_name, $s_email, $s_phoneno, $column_name, $sorting, $recordsPerPage,$offset) {
        
        $var=$this->fx->whereString($this->input->post('search') ,array('*'=>'t1.'),array('name'=>'like','email_id'=>'like','mobile_no'=>'like'),array('search_fname'=>'name','search_email'=>'email_id','search_mobile_no'=>'mobile_no'));

        $this->db->select('*');
        $this->db->from('user_master t1');
        
        // if (!empty($s_name)) {
        //     $this->db->like('name', $s_name);
        // }
        // if (!empty($s_email)) {
        //     $this->db->like('email_id', $s_email);
        // }
        // if (!empty($s_phoneno)) {
        //     $this->db->like('mobile_no', $s_phoneno);
        // }
        // // ORDER BY clause
        
        $this->db->where($var);
        // $query = $this->db->get();
        // print_r($this->db->last_query());die;

        $this->db->order_by($column_name, $sorting);

        $temp=clone $this->db;
        $count=$temp->count_all_results();
        // print_r($count);die;

        $this->db->limit($recordsPerPage,$offset);
        $query = $this->db->get();
        $rows = $query->result();
        $num = $query->num_rows();
        // print_r($num);die;


        $this->db->select('*');
        $this->db->from('user_master');
        $query2 = $this->db->get();
        $rows2 = $query2->result();
        $num2 = $query2->num_rows();

        
        return array('code' => '105','rows' => $rows,'rows2' => $num2, 'num' => $num, 'count'=> $count );

        // print_r($query);die;
    }
    public function delete($d_id) {
        $this->db->get_where('user_master', array('id' => $d_id));

        $storedId= $_SESSION['id'];

        if ($d_id == $storedId) {
            return array('code' => '200','rows' => 'login user');
            exit();
        }
        else{
            $this->db->where('id', $d_id);	
            $rows=$this->db->delete('user_master');
        
            if($rows){
            return array('code' => '400','rows' => $rows);
        }
        }
    } 
    public function insert($h_input,$name,$email,$no,$pwd,$hid_pwd,$s_pwd) {
        $duplicate = $this->db->get_where('user_master', array('email_id' => $email));
        if ($h_input === "") {
            if ($duplicate->num_rows() > 0) {
                echo json_encode(array('success' => true, 'statuscode' => 150, 'message' => 'email already exists'));
            } elseif ($email == "") {
                echo json_encode(array('success' => true, 'statuscode' => 180, 'message' => 'email required'));
            } elseif ($pwd == "") {
                echo json_encode(array('success' => true, 'statuscode' => 180, 'message' => 'password required'));
            } else {
                $data = array(
                    'name' => $name,
                    'email_id' => $email,
                    'mobile_no' => $no,
                    'PASSWORD' => $s_pwd
                );
        
                $this->db->insert('user_master', $data);
        
                echo json_encode(array('success' => true, 'statuscode' => 200, 'message' => 'added successfully!!'));
            }
        } else {
            if ($email == "") {
                echo json_encode(array('success' => true, 'statuscode' => 180, 'message' => 'email required'));
            } elseif (!empty($pwd)) {
                $data = array(
                    'name' => $name,
                    'email_id' => $email,
                    'mobile_no' => $no,
                    'PASSWORD' => $s_pwd
                );
        
                $this->db->where('id', $h_input);
                $this->db->update('user_master', $data);
        
                echo json_encode(array('success' => true, 'statuscode' => 250, 'message' => 'update successfully!!'));
            } else {
                $data = array(
                    'name' => $name,
                    'email_id' => $email,
                    'mobile_no' => $no,
                    'PASSWORD' => $hid_pwd
                );
        
                $this->db->where('id', $h_input);
                $this->db->update('user_master', $data);
        
                echo json_encode(array('success' => true, 'statuscode' => 250, 'message' => 'update successfully!!'));
            }
        }
        
    } 
    public function edit($e_id) {
        $query=$this->db->get_where('user_master', array('id' => $e_id));
        $result = $query->result();
        $data=$result[0];
        // print_r($this->db->last_query());die;
        echo json_encode($data);
    } 

}