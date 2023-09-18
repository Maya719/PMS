<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device_config_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function create($data){
        if($this->db->insert('devices', $data))
            return $this->db->insert_id();
        else
            return $this->db->last_query();; 
    }
    
    function get_device_config(){
        $offset = 0;
        $limit = 10;
        $sort = 'id'; 
        $order = 'DESC';
        $get = $this->input->get();
        $where = '';
        if(isset($get['sort']))
            $sort = strip_tags($get['sort']);
        if(isset($get['offset']))
            $offset = strip_tags($get['offset']);
        if(isset($get['limit']))
            $limit = strip_tags($get['limit']);
        if(isset($get['order']))
            $order = strip_tags($get['order']);
        if(isset($get['search']) &&  !empty($get['search'])){
            $search = strip_tags($get['search']);
            $where = " WHERE (id like '%".$search."%' OR device_name like '%".$search."%' OR device_ip like '%".$search."%')";
        }
        $query = $this->db->query("SELECT * FROM devices".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array(); 

        $query2 = $this->db->query("SELECT COUNT('id') as total FROM devices");
        $res = $query2->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        $data=[]; 
        $s_no=$offset+1; 
        foreach ($results as $device) {
            $device_name=$device["device_name"];
            $device_ip=$device["device_ip"];
            $port=$device["port"];
            $id=$device["id"];
            
            $avatarQurey = $this->db->query("SELECT * FROM users ");
            $Avatars = $avatarQurey->result_array(); 
            $users='';
            foreach ($Avatars as $avtar) {
                if (!empty($avtar)) {
                    $device_id = json_decode($avtar['device_id'], true);
                    if (is_array($device_id) && in_array($id, $device_id)) {
                        $first_name = htmlspecialchars($avtar['first_name']);
                        $last_name = htmlspecialchars($avtar['last_name']);
                        $users .= $first_name . ' ' . $last_name.'<br>';
                    }
                }
            }
            if (empty($users)) {
                $users = '<span class="text-muted">No assigned members</span>';
            }
            
            $data[]=[
                "s_no"=>$s_no,
                "device_name"=>$device_name,
                "users"=>$users,
                "device_ip"=>$device_ip,
                "port"=>$port,
                'action'=>'<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-device" data-edit="'.$device['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_device" data-id="'.$device['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>'
            ];
            $s_no++;
        }
        $bulkData = array();
        $bulkData['total'] = $total;
        $bulkData['rows'] = $data;
        print_r(json_encode($bulkData));
    }
    function delete($id){
        $this->db->where('id', $id);
       if($this->db->delete('devices'))
           return true;
       else
           return false;
   }
   function get_device_by_id($id){
    $where = "";
    if($this->ion_auth->is_admin()){
        $where .= " WHERE id = $id ";
    }else{
        $where .= " AND id = $id ";
    }

    $query = $this->db->query("SELECT * FROM devices ".$where);

    $results = $query->result_array();  
    foreach ($results as $result) {
        $id = $result["id"];

        $usersQuery = $this->db->query("SELECT * FROM users");
        $usersresult = $usersQuery->result_array();
        $users='';
        foreach ($usersresult as $value) {
            $device_id = json_decode($value['device_id'], true);
            if (is_array($device_id) && in_array($id, $device_id)) {
                $users = $users.','.$value["id"];
            }
        }
        $array[]=[
            'id'=>$result["id"],
            'device_name'=>$result["device_name"],
            'device_ip'=>$result["device_ip"],
            'port'=>$result["port"],
            'saas_id'=>$result["saas_id"],
            'users'=>$users,
        ];
    }

    return $array;
}

function edit($id, $data){
    $this->db->where('id', $id);
    if($this->db->update('devices', $data))
        return true;
    else
        return false;
}
public function updateUserDeviceId($type, $user_ids)
{
    $usersQuery = $this->db->query("SELECT * FROM users");
        $usersresult = $usersQuery->result_array();
    // Loop through all users
    foreach ($usersresult as $user) {
        $user_id = $user['id'];
        $device_id = json_decode($user["device_id"], true);

        // Check if the user is in the $user_ids array
        if (in_array($user_id, $user_ids)) {
            // Update user's device_id with $type if needed
            if ($device_id === null) {
                $device_id = array($type);
            } else {
                if (!in_array($type, $device_id)) {
                    $device_id[] = $type;
                }
            }

            $json = json_encode($device_id);
            $this->db->set('device_id', $json);
            $this->db->where('id', $user_id);
            $this->db->update('users');
        } else {
            // Remove $type from device_id if needed
            if (is_array($device_id) && in_array($type, $device_id)) {
                $index = array_search($type, $device_id);
                unset($device_id[$index]);

                if (empty($device_id)) {
                    $device_id = null;
                }

                $json = json_encode($device_id);
                $this->db->set('device_id', $json);
                $this->db->where('id', $user_id);
                $this->db->update('users');
            }
        }
    }
}

}