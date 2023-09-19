<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function update_email_templates($name, $data){
        $this->db->where('name', $name);
        if($this->db->update('email_templates', $data)){
            return true;
        }else{
            return false;
        }
    }

    function get_email_templates($name = ''){
        $where = "";
        $where .= (!empty($name))?" WHERE name='$name'":"";
        $query = $this->db->query("SELECT * FROM email_templates $where ");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create_taxes($data){
        if($this->db->insert('taxes', $data)){
            return true;
        }else{
            return false;
        }
    }
    function update_taxes($id,$data){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->update('taxes', $data)){
            return true;
        }else{
            return false;
        }
    }

    function delete_taxes($id){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->delete('taxes'))
            return true;
        else
            return false;
    }

    function get_taxes($tax_id = ''){
        $where = "";
        $where .= (!empty($tax_id) && is_numeric($tax_id))?" AND id=$tax_id":"";
        $saas_id = $this->session->userdata('saas_id');
        $query = $this->db->query("SELECT * FROM taxes WHERE saas_id=$saas_id $where ");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function save_settings($setting_type,$data){
        $this->db->where('type', $setting_type);
        $query = $this->db->get('settings');
        if($query->num_rows() > 0){
            $this->db->where('type', $setting_type);
            $this->db->update('settings', $data);
            return true;
        }else{
            $data["type"] = $setting_type;
            if($this->db->insert('settings', $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function get_department_time($type)
    {
        $query = $this->db->query("SELECT value FROM settings WHERE type = 'department_$type'");
        $result = $query->result_array();

        if ($result) {
            return $result[0]['value'];
        } else {
            return false;
        }
    }
    
    public function get_grace_minutes()
    {
        $query = $this->db->query("SELECT value FROM settings WHERE type = 'grace_minutes_'");
        $result = $query->result_array();

        if ($result) {
            return $result[0]['value'];
        } else {
            return false;
        }
    }
    
    function get_roles()
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
        $offset = 0;
        $limit = 10;
        $sort = 'r.id';
        $order = 'ASC';
        $get = $this->input->get();
        $where = '';

        if (isset($get['sort'])) {
            $sort = strip_tags($get['sort']);
        }

        if (isset($get['offset'])) {
            $offset = strip_tags($get['offset']);
        }

        if (isset($get['limit'])) {
            $limit = strip_tags($get['limit']);
        }

        if (isset($get['order'])) {
            $order = strip_tags($get['order']);
        }

        if (isset($get['search']) && !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where .= " AND (s.id LIKE '%" . $search . "%' OR r.name LIKE '%" . $search . "%' OR r.description LIKE '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(r.id) AS total FROM groups r " . $where);
        $res = $query->result_array();

        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT r.*  
                                FROM groups r $where
                                GROUP BY r.id
                                ORDER BY $sort $order
                                LIMIT $offset, $limit");

        $results = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $counter = 1;

        foreach ($results as $result) {
            $tempRow = $result;

            $tempRow['id'] = $result['id'];
            $tempRow['name'] = $result['name'];
            $tempRow['description'] = $result['description'];
            $tempRow['sr_no'] = $counter;
            $tempRow['permissions'] = '';
            $tempRow['users'] = '';
            if (!empty($result['permissions'])) {
                $permissions = json_decode($result['permissions']);
                foreach ($permissions as $permission) {
                    $query = $this->db->query("SELECT * FROM permissions_list WHERE id=".$permission);
                    $permissions_result = $query->result_array(); 
                    $description = htmlspecialchars($permissions_result[0]['description']);
                    if (!empty($description)) {
                        $tempRow['permissions'] .= '<span>' . $description . '</span>'.'<br>';
                    }
                }
            } 
            if(strpos($result['name'], 'admin') !== false){
                $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen" data-toggle="tooltip"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                $tempRow['permissions'] = '<span class="text-muted">Admin have all the permissions.</span>';
            }elseif($result['id'] == '2' || $result['id'] == '4') {
                $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-roles" data-edit="' . $result['id'] . '" data-toggle="tooltip" title="' . ($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit') . '"><i class="fas fa-pen" ></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                $tempRow['users'] = '<span class="text-muted">Cannot assign users.</span>';
            }else {
                $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-roles" data-edit="' . $result['id'] . '" data-toggle="tooltip" title="' . ($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit') . '"><i class="fas fa-pen" ></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_roles" data-id="' . $result['id'] . '" data-toggle="tooltip" title="' . ($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete') . '"><i class="fas fa-trash" ></i></a></span>';
            }

            $usersKey=json_decode($result["assigned_users"]);
            foreach ($usersKey as $user) {
                $query = $this->db->query("SELECT * FROM users WHERE id=".$user);
                $users = $query->result_array(); 
                $first_name = htmlspecialchars($users[0]['first_name']);
                $last_name = htmlspecialchars($users[0]['last_name']);
                if (!empty($first_name) && !empty($last_name)) {
                    $tempRow['users'] .= '<span>' . $first_name . ' ' . $last_name . '</span>'.'<br>';
                }
            }
            
            $rows[] = $tempRow;
            $counter++;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function roles_create($data){
        if($this->db->insert('groups', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function roles_delete($id){
        $this->db->where('id', $id);
        if($this->db->delete('groups'))
            return true;
        else
            return false;
    }
    
    function get_roles_permissions(){
        
        $excludeIds = [1, 3];
        $excludeConditions = implode(',', $excludeIds);
        
        $query = $this->db->query("SELECT * FROM groups WHERE id NOT IN ($excludeConditions)");

        $results = $query->result_array();
        if(!empty($results)){
            $roles = [];

            foreach ($results as $row) {
                $permissions = '';
                if (!empty($row['permissions'])) {
                    $permissions_ids = json_decode($row['permissions']);
                    foreach ($permissions_ids as $permission) {
                        $query = $this->db->query("SELECT * FROM permissions_list WHERE id=".$permission);
                        $permissions_result = $query->result_array(); 
                        $name = htmlspecialchars($permissions_result[0]['name']);
                        if (!empty($name)) {
                            $permissions .= $name . ' ,';
                        }
                    }
                }
                $roles[] = [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'permissions' => $permissions,
                ];
            }
            $outputJson =$roles;
            return $outputJson;
        }
        else
            return false;
    }

    function get_roles_by_id($id)
    {
        $where = " WHERE id = $id ";

        $query = $this->db->query("SELECT * FROM groups " . $where);

        $results = $query->result_array();

        if (!empty($results)) {
            $roles = $results[0];

            return $roles;
        }

        return array();
    }

    function roles_edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('groups', $data))
            return true;
        else
            return false;
    }
    
    
    function get_leaves_type()
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
        $offset = 0;
        $limit = 10;
        $sort = 'l.id';
        $order = 'ASC';
        $get = $this->input->get();
        $where = '';
        $LEFT_JOIN = '';

        if (isset($get['sort'])) {
            $sort = strip_tags($get['sort']);
        }

        if (isset($get['offset'])) {
            $offset = strip_tags($get['offset']);
        }

        if (isset($get['limit'])) {
            $limit = strip_tags($get['limit']);
        }

        if (isset($get['order'])) {
            $order = strip_tags($get['order']);
        }

        if (isset($get['search']) && !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where .= " AND (l.id LIKE '%" . $search . "%' OR l.name LIKE '%" . $search . "%' )";
        }

        $query = $this->db->query("SELECT COUNT(l.id) AS total FROM leaves_type l $LEFT_JOIN " . $where);
        $res = $query->result_array();

        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT l.* 
                                FROM leaves_type l $where
                                ORDER BY $sort $order
                                LIMIT $offset, $limit");
        $results = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $counter = 1;

        foreach ($results as $result) {
            $tempRow = $result;
            if($this->ion_auth->is_admin() || permissions('leave_type_edit')){
                $edit_btn = '<a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-leaves-type" data-edit="' . $result['id'] . '" data-toggle="tooltip" title="' . ($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit') . '"><i class="fas fa-pen"></i></a>';
            }else{
                $edit_btn = '<a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>';
            }

            if($this->ion_auth->is_admin() || permissions('leave_type_delete')){
                $delete_btn = '<a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete-leaves-type" data-id="' . $result['id'] . '" data-toggle="tooltip" title="' . ($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete') . '"><i class="fas fa-trash"></i></a>';
            }else{
                $delete_btn = '<a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>';
            }

            $tempRow['action'] = '<span class="d-flex">'.$edit_btn.''.$delete_btn.'</span>';
            $tempRow['sr_no'] = $counter;
            $rows[] = $tempRow;
            $counter++;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_leaves_type_by_id($id)
    {
        $where = " WHERE id = $id ";

        $query = $this->db->query("SELECT * FROM leaves_type " . $where);

        $results = $query->result_array();

        if (!empty($results)) {
            $leaves_type = $results[0];

            return $leaves_type;
        }

        return array();
    }

    function leaves_type_edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('leaves_type', $data))
            return true;
        else
            return false;
    }

    function leaves_type_create($data){
        if($this->db->insert('leaves_type', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function leaves_type_delete($id){
        $this->db->where('id', $id);
        if($this->db->delete('leaves_type'))
            return true;
        else
            return false;
    }
}
