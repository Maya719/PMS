<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use AgileBM\ZKLib\ZKLib;

require '../vendor/autoload.php';

class Attendance_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function create($data){
        if($this->db->insert('attendance', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function my_att_running($user_id){
 
        $where = " WHERE user_id = ".$user_id;

        $where .= " AND saas_id = ".$this->session->userdata('saas_id');

        $where .= " AND check_out IS NULL ";

        $query = $this->db->query("SELECT * FROM attendance ".$where);
    
        $results = $query->result_array();  

        return $results;
    }

    function get_attendance_by_id($id){
 
        $query = $this->db->query("SELECT * FROM attendance WHERE id = $id");
    
        $results = $query->result_array();  

        return $results;
    }
function get_attendance(){ 
        $offset = 0;
        $limit = 10;
        $sort = 'attendance.id';
        $order = 'ASC';
        $get = $this->input->get();
        // Check if the user is an admin
        if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
            if(isset($get['user_id']) && !empty($get['user_id'])){
                $where = " WHERE attendance.user_id = ".$get['user_id'];
                $where2 = " WHERE leaves.employee_id = ".$get['user_id'];
                
            }else{
            $currentDate = date('Y-m-d');
            $where = " WHERE attendance.id IS NOT NULL ";
            $where2 = " WHERE leaves.id IS NOT NULL ";
            }
        }else{
            $query2 = $this->db->query("SELECT * FROM users WHERE active = '1'");
            $results2 = $query2->result_array();
            foreach ($results2 as $current_user) {
                if ($current_user["id"] == $this->session->userdata('user_id')) {
                $employee_id=$current_user["employee_id"];
                $where = " WHERE attendance.user_id = ".$employee_id;
                $where2 = " WHERE leaves.employee_id = ".$employee_id;
                }
            }
        }
    
        if(isset($get['sort']))
        $sort = strip_tags($get['sort']);
        if(isset($get['offset']))
        $offset = strip_tags($get['offset']);
        if(isset($get['limit']))
        $limit = strip_tags($get['limit']);
        if(isset($get['order']))
        $order = strip_tags($get['order']);
    
        // Check if search term is provided and construct the search condition
        if (isset($get['search']) && !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where .= " AND (attendance.id LIKE '%".$search."%' OR users.first_name LIKE '%".$search."%' OR users.last_name LIKE '%".$search."%' OR attendance.check_in LIKE '%".$search."%' OR attendance.check_out LIKE '%".$search."%' OR attendance.note LIKE '%".$search."%')";
            $where2 .= " AND (leaves.id LIKE '%".$search."%' OR users.first_name LIKE '%".$search."%' OR users.last_name LIKE '%".$search."%')";
        }
        if (isset($get['department']) && !empty($get['department'])) {
            $department = $get['department'];
            $where .= " AND users.department = '$department'";
        }
        if (isset($get['shifts']) && !empty($get['shifts'])) {
            $shifts = $get['shifts'];
            $where .= " AND users.shift_id = '$shifts'";
        }
        if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
        if (isset($get['from']) && !empty($get['from'])) {
            $where .= " AND DATE(attendance.finger) BETWEEN '".format_date($get['from'], "Y-m-d")."' AND '".format_date($get['from'], "Y-m-d")."' ";
            $where2 .= " AND leaves.starting_date <= '".format_date($get['from'], "Y-m-d")."' AND leaves.ending_date >= '".format_date($get['from'], "Y-m-d")."'";
        }
        }else{
        // Set the filter options
        $filter = isset($get['filter']) ? $get['filter'] : 'all';
        switch ($filter) {
            case 'today':
                $currentDate = date('Y-m-d');
                $where .= " AND DATE(attendance.finger) BETWEEN '{$currentDate}' AND '{$currentDate}' ";
                $where2 .= " AND leaves.starting_date <= '{$currentDate}' AND leaves.ending_date >= '{$currentDate}'";
                break;
            case 'tweek':
                $currentDate = date('Y-m-d');
                $fromDate = date('Y-m-d', strtotime('last Monday'));
                $toDate = $currentDate;
                $where .= " AND DATE(attendance.finger) BETWEEN '{$fromDate}' AND '{$toDate}' ";
                $where2 .= " AND leaves.starting_date >= '{$fromDate}' AND leaves.ending_date <= '{$toDate}'";
                break;
            case 'ystdy':
                $currentDate = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
                $where .= " AND DATE(attendance.finger) BETWEEN '{$yesterday}' AND '{$yesterday}' ";
                $where2 .= " AND leaves.starting_date <= '{$yesterday}' AND leaves.ending_date >= '{$yesterday}'";
                break;
            case 'tmonth':
                $currentDate = date('Y-m-d');
                $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
                $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' ";
                $where2 .= " AND leaves.starting_date >= '{$firstDayOfMonth}' AND leaves.ending_date <= '{$currentDate}'";
                break;
            case 'lmonth':
                $lastMonthStart = date('Y-m-01', strtotime('first day of -1 month'));
                $lastMonthEnd = date('Y-m-t', strtotime('last day of -1 month'));
                $where .= " AND DATE(attendance.finger) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}' ";
                $where2 .= " AND leaves.starting_date <= '{$lastMonthStart}' AND leaves.ending_date >= '{$lastMonthEnd}'";
                break;
            case 'custom':
                if (isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
                    $where .= " AND DATE(attendance.finger) BETWEEN '" . format_date($get['from'], "Y-m-d") . "' AND '" . format_date($get['too'], "Y-m-d") . "' ";
                    $where2 .= " AND (leaves.starting_date >= '".format_date($get['from'], "Y-m-d")."' AND leaves.ending_date <= '".format_date($get['too'], "Y-m-d")."') ";
                }
                break;
        }
        }
            $leftjoin = "LEFT JOIN users ON attendance.user_id = users.employee_id";
            $leftjoin2 = "LEFT JOIN users ON leaves.employee_id = users.employee_id";
            
    
            $query = $this->db->query("SELECT attendance.*, CONCAT(users.first_name, ' ', users.last_name) AS user
            FROM attendance ".$leftjoin.$where." AND users.active=1 AND users.finger_config=1");
            $results = $query->result_array();
            if(isset($get['department']) && !empty($get['department'])){
                $department_id = $get["department"];
                $where2 .= " AND users.department = '$department_id'";
            }
            $where2 .= " AND leaves.status = '1'";
            $leaveQuery = $this->db->query("SELECT leaves.*, CONCAT(users.first_name, ' ', users.last_name) AS user  FROM leaves ".$leftjoin2.$where2."  AND users.active=1 AND users.finger_config=1 AND leaves.paid = 0");
    
            $leavesresult = $leaveQuery->result_array();
            // Sort the data by user and created
        usort($results, function($a, $b) {
            if ($a['user'] === $b['user']) {
                return strcmp($a['created'], $b['created']);
            }
            return strcmp($a['user'], $b['user']);
        });
    
        $groupedData = [];
        $baseUrl = base_url();
        foreach ($results as $item) {
            $user = $item['user_id'];
            $fingerDate = date('Y-m-d', strtotime($item['finger']));
            
            if (!isset($groupedData[$user])) {
                $groupedData[$user] = [];
            }
            
            if (!isset($groupedData[$user][$fingerDate])) {
                $groupedData[$user][$fingerDate] = [];
            }
            
            $groupedData[$user][$fingerDate][] = $item;
        }
        $row = [];
    
        // Print the grouped data
        foreach ($groupedData as $user => $fingerData) {
            $array = [];
            foreach ($fingerData as $fingerDate => $items) {
                $finger = '';
                $firstItem = true; // Flag to identify the first item
                
                foreach ($items as $item) {
                    if (!$firstItem) {
                        $finger .= '<br>'; // Add <br> tag if it's not the first item
                    }
                    
                    if ($this->ion_auth->is_admin() || permissions('attendance_view_all')) {
                        $finger .= format_date($item['finger'], "h:i A");
                    } else {
                        $finger .= format_date($item['finger'], "Y M d h:i A");
                    }
                    
                    // Check if 'note' attribute contains 'missing' for the current user
                    if (isset($item['note']) && stripos($item['note'], 'missing') !== false) {
                        $finger .= '<span class="text-info"><strong> &nbsp; (BM)</strong></span>'; // Append "(BM)" at the end of the 'finger' string
                    }
                    $firstItem = false; // Set the flag to false after the first item
                }
                $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves WHERE leaves.starting_date <= '".format_date($get['from'], "Y-m-d")."' AND leaves.ending_date >= '".format_date($get['from'], "Y-m-d")."' AND leaves.employee_id = '$user' AND leaves.paid = '0' AND leaves.status='1' ");
            if ($leaveQuery->num_rows() > 0) {
                $leaveRow = $leaveQuery->row();
                $leaveDuration = $leaveRow->leave_duration;
                if (strpos($leaveDuration, 'Half') !== false) { 
                    $finger .= '<br><div class="text-info"><strong>HD Leave</strong></div>'; 
                } elseif (strpos($leaveDuration, 'Short') !== false) {
                    $finger .= '<br><div class="text-info"><strong>Short Leave</strong></div>'; 
                }
            }
                $array[] = [
                    'user_id' => $user,
                    'user' => $items[0]['user'], // Include user_name from the first item
                    'fingers' => $finger
                ];
            }
            $row[] = $array;
        }
        
        $checkInOutData = [];
        foreach ($row as $objects) {
            foreach ($objects as $obj) {
                $userId = $obj['user_id'];
                $fingers = $obj['fingers'];
                $userName = $obj['user']; // Assign user_name to a variable
                $currentDate = new DateTime();
                $checkInOutData[] = [
                    'user_id' =>'<a href="'.$baseUrl.'attendance/user_attendance/'.$userId.'">'.$userId.'</a>',
                    'user' => '<a href="'.$baseUrl.'attendance/user_attendance/'.$userId.'">'.$userName.'</a>',
                    'check_in' => $fingers,
                ];
            }
        }
    
        // Custom comparison function for sorting based on "check_in" values
        function sortByCheckInDesc($a, $b) {
            return strtotime($b['check_in']) - strtotime($a['check_in']);
        }
    
        $leaveArray=[];
        foreach ($leavesresult as $leave) {
            $startingDate = new DateTime($leave['starting_date']);
            $endingDate = new DateTime($leave['ending_date']);
            $leave_duration = $leave['leave_duration'];
            $interval = $startingDate->diff($endingDate);
            $data = $interval->days + 1;
            if (strpos($leave_duration, 'Half') !== false) { 
                $finger .= '<br><div class="text-info"><strong>HD Leave</strong></div>'; 
            } elseif (strpos($leave_duration, 'Short') !== false) {
                $finger .= '<br><div class="text-info"><strong>Short Leave</strong></div>'; 
            }else{
                for ($i=0; $i < $data; $i++) { 
                    if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
                        $currentDate2 = $startingDate->format('d M Y');
                        if (isset($get['from']) && !empty($get['from']) && $currentDate2 == $get['from']) {
                            $leaveArray[]=[
                                'user_id'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["employee_id"].'</a>',
                                'user'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["user"].'</a>',
                                'check_in'=>'<div class="text-success"><strong>Leave</strong></div>',
                            ];
                        }
                    }else{
                        $currentDate2 = $startingDate->format('Y M d');
                        $leaveArray[]=[
                            'user_id'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["employee_id"].'</a>',
                            'user'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["user"].'</a>',
                            'check_in'=> $currentDate2.'<br>'.'<div class="text-success"><strong>Leave</strong></div>',
                        ];
                    }
                    $startingDate->modify('+1 day');
                }
            }
            
        }
    
        $mergedArray = array_merge($leaveArray, $checkInOutData);
        $checkInArray = array_column($mergedArray, 'check_in');
        array_multisort($checkInArray, SORT_ASC, $mergedArray);
        $serialNumber = 1; // Initialize the serial number variable
        // Retrieve the base URL using the base_url() function
    
        if ($this->ion_auth->is_admin() || permissions('attendance_view_all')) {
            $conditions = "users.active = '1' AND users.finger_config = '1'";
            $id = isset($get['user_id']) && !empty($get['user_id']) ? $get['user_id'] : null;
            $department_id = isset($get['department']) && !empty($get['department']) ? $get['department'] : null;
            $shifts = isset($get['shifts']) && !empty($get['shifts']) ? $get['shifts'] : null;
            $search = isset($get['search']) && !empty($get['search']) ? $get['search'] : null;
            if ($search) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                FROM users 
                WHERE $conditions AND users.first_name LIKE '%".$search."%' OR users.last_name LIKE '%".$search."%' ");
            }elseif ($id && $department_id && $shifts) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions AND users.id='$id' AND department='$department_id' AND users.shift_id='$shifts'");
            } elseif ($id && $department_id) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions AND users.id='$id' AND department='$department_id'");
            } elseif ($id && $shifts) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions AND users.id='$id' AND users.shift_id='$shifts'");
            } elseif ($department_id && $shifts) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions AND department = '$department_id' AND users.shift_id='$shifts'");
            } elseif ($id) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions AND users.employee_id='$id'");
            } elseif ($department_id) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions AND department = '$department_id'");
            } elseif ($shifts) {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions AND users.shift_id='$shifts'");
            } else {
                $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                    FROM users 
                    WHERE $conditions");
            }
            $absentresult = $absentQuery->result_array();
        }
        
        $bulkdata=[];
        foreach ($absentresult as $absuser) {
            $join = date("Y-m-d", strtotime($absuser["join_date"])); // Format join_date consistently
            $fromDate = date("Y-m-d", strtotime($get["from"])); // Format join_date consistently
            if ($join <= $fromDate && isset($fromDate) && !empty($fromDate)) {
                $bulkdata[] = [
                    'user_id' => '<a href="' . $baseUrl . 'attendance/user_attendance/' . $absuser["employee_id"] . '">' . $absuser["employee_id"] . '</a>',
                    'user' => '<a href="' . $baseUrl . 'attendance/user_attendance/' . $absuser["employee_id"] . '">' . $absuser["user"] . '</a>',
                    'check_in' => '<div class="text-primary"><strong>Absent</strong></div>',
                ];
            }
        }  
    
        
        // Extract the user IDs from array1 into a separate array
        $existingUserIds = array_column($mergedArray, 'user_id');
    
        // Iterate over array2 and merge the rows that have unique user IDs into array1
        foreach ($bulkdata as $row) {
            $userId = $row['user_id'];
            if (!in_array($userId, $existingUserIds)) {
                $mergedArray[] = $row;
                $existingUserIds[] = $userId;
            }
            
        }
    
        $preabs = isset($get['preabs']) ? $get['preabs'] : '';
    
        // Filter the data based on the $preabs value
        $filteredData = [];
        foreach ($mergedArray as $row) {
    
            if ($preabs === 'absent') {
                // Show rows with 'Absent' status
                if (strpos($row['check_in'], 'Absent') !== false) {
                    $filteredData[] = $row;
                }
            } elseif ($preabs === 'leave') {
                // Show rows with 'Leave' status
                if (strpos($row['check_in'], 'Leave') !== false) {
                    $filteredData[] = $row;
                }
            } elseif ($preabs === 'late') {
                if (preg_match('/\b\d{2}:\d{2} [APM]{2}\b/', $row['check_in'], $matches)) {
                    $firstCheckIn = trim($matches[0]);
                    $checkInTime = strtotime($firstCheckIn); // Convert the first time value to a timestamp
                    $userId = preg_replace('/[^0-9]/', '', $row['user_id']);
                    preg_match('/\d+/', $row['user_id'], $userIdMatches);
                    $userId = isset($userIdMatches[0]) ? $userIdMatches[0] : null;
    
                    $user_ids_query = $this->db->query("SELECT * FROM users WHERE employee_id = $userId");
                    
                    if ($user_ids_query) {
                        $user_ids_result = $user_ids_query->row_array();
                        $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '';
                    }
    
                    $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
                    if ($shift_query) {
                        $shift_result = $shift_query->row_array();
                        $starting_time = isset($shift_result['starting_time']) ? $shift_result['starting_time'] : '';
                    }
                    $starting_time = date('h:i A', strtotime($starting_time)); // Convert the starting_time to '09:00 AM' format
                    // Show rows with time greater than 9:00 AM
                    if ($checkInTime > strtotime($starting_time)) {
                        // If the first time in the check_in value is late, include the row in the filtered data
                        $filteredData[] = $row;
                    }
                }
            } elseif ($preabs === 'present')  {
                // Show rows with time in the format 08:44 AM
                if (preg_match('/\b\d{2}:\d{2} [APM]{2}\b/', $row['check_in'])) {
                    $filteredData[] = $row;
                }
            }
            else{
                $filteredData[] = $row;
            }
        }
    
        foreach ($filteredData as $key => &$value) {
            $value['s.n'] = $serialNumber; // Add the 's.n' key with the serial number value
            $serialNumber++; // Increment the serial number for the next iteration
            $userId = preg_replace('/[^0-9]/', '', $value['user_id']);
            preg_match('/\d+/', $value['user_id'], $userIdMatches);
            $userId = isset($userIdMatches[0]) ? $userIdMatches[0] : null;
            $user_ids_query = $this->db->query("SELECT * FROM users WHERE employee_id = $userId");
            $user_ids_result = $user_ids_query->row_array();
            $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '1';
    
            // Get the shift_name from the shift table based on shift_id
            $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
            $shift_result = $shift_query->row_array();
            $starting_time = date('h:i A', strtotime($shift_result['starting_time']));
            $ending_time = date('h:i A', strtotime($shift_result['ending_time']));
            $shift_name = isset($shift_result['name']) ? $shift_result['name'].' ('. $starting_time.' - '.$ending_time.' )': 'Regular Shift (09:00 AM - 06:00 PM)';
            // Add the shift_name attribute to the row
            $value['shift_name'] = $shift_name;
        }
        $holidayQuery = $this->db->query("SELECT * FROM holiday");
        $holidays = $holidayQuery->result_array();

        // weekend Holidays
        if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
            if (isset($get['from']) && !empty($get['from'])) {
                $date = format_date($get['from'], "w");
                if ($date == '0' || $date == '6') {
                    foreach ($filteredData as $key => $value5) {
                        if ($value5["check_in"] == '<div class="text-primary"><strong>Absent</strong></div>' || $value5["check_in"] == '<div class="text-success"><strong>Leave</strong></div>') {
                            $filteredData[$key]["check_in"] = '<div class="text-info"><strong>Holiday</strong></div>';
                        }
                    }
                }else {
                    $current_time = strtotime("now");
                    $getTime = strtotime($get['from']);
                    if ($getTime>$current_time) {
                        $filteredData=[];
                    }
                }
            }
        }
        // ALL Holidays
        foreach ($holidays as $value4) {
            $startDate = $value4["starting_date"];
            $endDate = $value4["ending_date"];
            $apply = $value4["apply"];
            $startDateTimestamp  = strtotime($startDate);
            $endDateTimestamp  = strtotime($endDate);
            $dateToCheckTimestamp  = strtotime($get['from']);
            if($apply == '0' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                foreach ($filteredData as $key => $value5) {
                    if ($value5["check_in"] == '<div class="text-primary"><strong>Absent</strong></div>') {
                       $filteredData[$key]["check_in"]='<div class="text-info"><strong>Holiday</strong></div>';
                    }
                }
            }elseif ($apply == '1' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                $departments = json_decode($value4["department"]);
                foreach ($filteredData as $key => $value6) {
                    foreach ($departments as $department) {
                        $userId = preg_replace('/[^0-9]/', '', $value6['user_id']);
                        preg_match('/\d+/', $value6['user_id'], $userIdMatches2);
                        $userId = isset($userIdMatches2[0]) ? $userIdMatches2[0] : null;
                        $user_ids_query = $this->db->query("SELECT * FROM users WHERE department = $department AND employee_id= $userId");
                        $user_ids_result = $user_ids_query->result_array();
                        if (count($user_ids_result)>0) {
                            $filteredData[$key]["check_in"] = '<div class="text-info"><strong>Holiday</strong></div>';
                        }
                    }
                    
                }
            }elseif($apply == '2' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                $holidayUsers = json_decode($value4["users"]);
                foreach ($filteredData as $key => $value6) {
                    foreach ($holidayUsers as $holidayUser) {
                        $userId = preg_replace('/[^0-9]/', '', $value6['user_id']);
                        preg_match('/\d+/', $value6['user_id'], $userIdMatches2);
                        $userId = isset($userIdMatches2[0]) ? $userIdMatches2[0] : null;
                        $user_ids_query = $this->db->query("SELECT * FROM users WHERE id = $holidayUser AND employee_id= $userId");
                        $user_ids_result = $user_ids_query->result_array();
                        if (count($user_ids_result)>0) {
                            $filteredData[$key]["check_in"] = '<div class="text-info"><strong>Holiday</strong></div>';
                        }
                    }
                }
            }
        }



        $total = count($filteredData);

        function sortByDateAsc($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        }
    
        // Sort the merged array using the custom comparison function
        usort($filteredData, 'sortByDateAsc');
        // Apply pagination (limit and offset) to the filtered data
        $offset = isset($get['offset']) ? (int)$get['offset'] : 0;
        $limit = isset($get['limit']) ? (int)$get['limit'] : 10;
        $filteredData = array_slice($filteredData, $offset, $limit);
    
        // Form the final data array with the filtered and paginated data
        $data = [
            'total' => $total,
            'rows' => $filteredData,
        ];
    
        print_r(json_encode($data));
    }
function get_user_attendance(){ 
    $offset = 0;
    $limit = 10;
    $sort = 'attendance.id';
    $order = 'ASC';
    $get = $this->input->get();
    $globalFromDate = '';
    $globalToDate = '';
    $conditionalDates = '';
    $user_id = 0;
    // Check if the user is an admin
    if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
        if(isset($get['user_id']) && !empty($get['user_id'])){
            $where = " WHERE attendance.user_id = ".$get['user_id'];
            $where2 = " WHERE leaves.employee_id = ".$get['user_id'];
            $user_id = $get['user_id'];
        }else{
        $currentDate = date('Y-m-d');
        $where = " WHERE attendance.id IS NOT NULL ";
        $where2 = " WHERE leaves.id IS NOT NULL ";
        }
    }else{
        $query2 = $this->db->query("SELECT * FROM users WHERE active = '1'");
        $results2 = $query2->result_array();
        foreach ($results2 as $current_user) {
            if ($current_user["id"] == $this->session->userdata('user_id')) {
            $employee_id=$current_user["employee_id"];
            $where = " WHERE attendance.user_id = ".$employee_id;
            $where2 = " WHERE leaves.employee_id = ".$employee_id;
            $user_id = $employee_id;
            }
        }
    }

    if(isset($get['sort']))
    $sort = strip_tags($get['sort']);
    if(isset($get['offset']))
    $offset = strip_tags($get['offset']);
    if(isset($get['limit']))
    $limit = strip_tags($get['limit']);
    if(isset($get['order']))
    $order = strip_tags($get['order']);
    // Check if search term is provided and construct the search condition
    if (isset($get['search']) && !empty($get['search'])) {
        $search = strip_tags($get['search']);
        $where .= " AND (attendance.id LIKE '%".$search."%' OR users.first_name LIKE '%".$search."%' OR users.last_name LIKE '%".$search."%' OR attendance.check_in LIKE '%".$search."%' OR attendance.check_out LIKE '%".$search."%' OR attendance.note LIKE '%".$search."%')";
        $where2 .= " AND (leaves.id LIKE '%".$search."%' OR users.first_name LIKE '%".$search."%' OR users.last_name LIKE '%".$search."%')";
    }
    
    $filter = isset($get['filter']) ? $get['filter'] : 'all';
    switch ($filter) {
        case 'all':
            $currentDate = date('Y-m-d');
            $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
            $globalFromDate = $firstDayOfMonth; 
            $globalToDate = $currentDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' ";
            $where2 .= " AND (leaves.starting_date >= '{$firstDayOfMonth}' OR leaves.ending_date <= '{$currentDate}')";
            $conditionalDates = ", '{$currentDate}' AS ending_date";
            break;
        case 'today':
            $currentDate = date('Y-m-d');
            $globalFromDate = $currentDate; 
            $globalToDate = $currentDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$currentDate}' AND '{$currentDate}' ";
            $where2 .= " AND (leaves.starting_date <= '{$currentDate}' AND leaves.ending_date >= '{$currentDate}')";
            $conditionalDates = ", '{$currentDate}' AS starting_date, '{$currentDate}' AS ending_date";
            break;
        case 'tweek':
            $currentDate = date('Y-m-d');
            $today = date('D');
            $fromDate = ($today === 'Mon') ? date('Y-m-d', strtotime('this Monday')) : date('Y-m-d', strtotime('last Monday'));
            $toDate = $currentDate;
            $globalFromDate = $fromDate; 
            $globalToDate = $toDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$fromDate}' AND '{$toDate}' ";
            $where2 .= " AND (leaves.starting_date BETWEEN '{$fromDate}' AND '{$toDate}' OR leaves.ending_date BETWEEN '{$fromDate}' AND '{$toDate}' OR (leaves.starting_date <= '{$fromDate}' AND leaves.ending_date >= '{$toDate}'))";
            $conditionalDates = ", IF(leaves.starting_date < '{$fromDate}', '{$fromDate}', leaves.starting_date) AS starting_date, 
            IF(leaves.ending_date > '{$toDate}', '{$toDate}', leaves.ending_date) AS ending_date";
            break;
        case 'ystdy':
            $currentDate = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
            $globalFromDate = $yesterday; 
            $globalToDate = $yesterday;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$yesterday}' AND '{$yesterday}' ";
            $where2 .= " AND (leaves.starting_date <= '{$yesterday}' AND leaves.ending_date >= '{$yesterday}')";
            $conditionalDates = ", '{$yesterday}' AS starting_date, '{$yesterday}' AS ending_date";
            break;
        case 'tmonth':
            $currentDate = date('Y-m-d');
            $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
            $globalFromDate = $firstDayOfMonth; 
            $globalToDate = $currentDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' ";
            $where2 .= " AND (leaves.starting_date BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' OR leaves.ending_date BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' OR (leaves.starting_date <= '{$firstDayOfMonth}' AND leaves.ending_date >= '{$currentDate}'))";
            $conditionalDates = ", IF(leaves.starting_date < '{$firstDayOfMonth}', '{$firstDayOfMonth}', leaves.starting_date) AS starting_date, 
            IF(leaves.ending_date > '{$currentDate}', '{$currentDate}', leaves.ending_date) AS ending_date";
            break;
        case 'lmonth':
            $lastMonthStart = date('Y-m-01', strtotime('first day of -1 month'));
            $lastMonthEnd = date('Y-m-t', strtotime('last day of -1 month'));
            $globalFromDate = $lastMonthStart; 
            $globalToDate = $lastMonthEnd;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}' "; 
            $where2 .= " AND (leaves.starting_date BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}' OR leaves.ending_date BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}' OR (leaves.starting_date <= '{$lastMonthStart}' AND leaves.ending_date >= '{$lastMonthEnd}'))";
            $conditionalDates = ", IF(leaves.starting_date < '{$lastMonthStart}', '{$lastMonthStart}', leaves.starting_date) AS starting_date, 
            IF(leaves.ending_date > '{$lastMonthEnd}', '{$lastMonthEnd}', leaves.ending_date) AS ending_date";
            break;
        case 'custom':
            if (isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
                $globalFromDate = $get['from']; 
                $globalToDate = $get['too'];
                $where .= " AND DATE(attendance.finger) BETWEEN '" . format_date($get['from'], "Y-m-d") . "' AND '" . format_date($get['too'], "Y-m-d") . "' ";
                $where2 .= " AND (leaves.starting_date BETWEEN '".format_date($get['from'], "Y-m-d")."' AND '".format_date($get['too'], "Y-m-d")."' OR leaves.ending_date BETWEEN '".format_date($get['from'], "Y-m-d")."' AND '".format_date($get['too'], "Y-m-d")."' OR (leaves.starting_date <= '".format_date($get['from'], "Y-m-d")."' AND leaves.ending_date >= '".format_date($get['too'], "Y-m-d")."'))";
                $conditionalDates = ", IF(leaves.starting_date < '".format_date($get['from'], "Y-m-d")."', '".format_date($get['from'], "Y-m-d")."', leaves.starting_date) AS starting_date, 
                IF(leaves.ending_date > '".format_date($get['too'], "Y-m-d")."', '".format_date($get['too'], "Y-m-d")."', leaves.ending_date) AS ending_date";
            }
            break;
    }
    $leftjoin = "LEFT JOIN users ON attendance.user_id = users.employee_id";
    $leftjoin2 = "LEFT JOIN users ON leaves.employee_id = users.employee_id";

    $join_date_query = $this->db->query("SELECT join_date FROM users WHERE employee_id = $user_id");
    $join_date_result = $join_date_query->row_array();
    $join_date = $join_date_result['join_date'];
    $join_date = date('Y-m-d', strtotime($join_date));

    if (strtotime($globalFromDate) < strtotime($join_date)) {
        $globalFromDate = $join_date;
    }

    $query = $this->db->query("SELECT attendance.*, CONCAT(users.first_name, ' ', users.last_name) AS user FROM attendance ".$leftjoin.$where);
    $results = $query->result_array();
  
    function compareFinger($a, $b) {
        $timestampA = strtotime($a["finger"]);
        $timestampB = strtotime($b["finger"]);
    
        if ($timestampA === false && $timestampB === false) {
            return strcmp($a["finger"], $b["finger"]); // Both are in the same format, perform string comparison
        } elseif ($timestampA === false) {
            return 1; // The first item is in a different format, so consider it greater
        } elseif ($timestampB === false) {
            return -1; // The second item is in a different format, so consider it greater
        }
    
        return $timestampA - $timestampB; // Compare timestamps for the same format
    }
    
    usort($results, 'compareFinger');
    $groupedData = [];
    $baseUrl = base_url();
    foreach ($results as $item) {
        $user = $item['user_id'];
        $fingerDate = date('Y-m-d', strtotime($item['finger']));
        
        if (!isset($groupedData[$user])) {
            $groupedData[$user] = [];
        }
        
        if (!isset($groupedData[$user][$fingerDate])) {
            $groupedData[$user][$fingerDate] = [];
        }
        
        $groupedData[$user][$fingerDate][] = $item;
    }
    $row = [];

    // Create an array of all dates between 'from' and 'too'
    $allDates = [];
    $currentDate = new DateTime($globalFromDate);
    $endDate = new DateTime($globalToDate);
    
    // Add all dates between 'fromDateGlobal' and 'toDateGlobal' to the $allDates array
    while ($currentDate <= $endDate) {
        $allDates[] = $currentDate->format('Y-m-d');
        $currentDate->modify('+1 day');
    } 

    // Print the grouped data
    
    $where2 .= " AND leaves.status = '1' ";
    foreach ($allDates as $date) {
        $fingerLoopExecuted = false; 
        foreach ($groupedData as $user => $fingerData) {
            $array = [];
            foreach ($fingerData as $fingerDate => $items) {
                $finger = '';
                foreach ($items as $item) {
                    $finger = $finger.'<br>'.format_date($item['finger'], "h:i A");
                    // Check if 'note' attribute contains 'missing' for the current user
                    if (isset($item['note']) && stripos($item['note'], 'missing') !== false) {
                        $finger .= '<span class="text-warning"><strong> &nbsp; (BM)</strong></span>'; // Append "(BM)" at the end of the 'finger' string
                    }
                }

                $whereDate = " AND leaves.starting_date = '$fingerDate'";
                $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves " . $where2 . $whereDate);
                if ($leaveQuery->num_rows() > 0) {
                    $leaveRow = $leaveQuery->row();
                    $leaveDuration = $leaveRow->leave_duration;
                    if (strpos($leaveDuration, 'Half') !== false) { 
                        $finger .= '<br><div class="text-info"><strong>HD Leave</strong></div>'; 
                    } elseif (strpos($leaveDuration, 'Short') !== false) {
                        $finger .= '<br><div class="text-info"><strong>Short Leave</strong></div>'; 
                    }
                }
                
                $array[] = [
                    'user_id' => $user,
                    'date' => date("d M Y", strtotime($fingerDate)), // Set the 'date' field as $fingerDate
                    'user' => $items[0]['user'], // Include user_name from the first item
                    'fingers' => $finger
                ];
            }
            // If there are multiple dates, check and add 'Absent' for each date that doesn't exist in $fingerData
            // Check if the current date exists in the $fingerData array
            $dateExists = array_key_exists($date, $fingerData);
    
            if (!$dateExists) {
                $holidayExecution = false;
                // Check if the specific date is present in any of the fingers from attendance table
                $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '{$date}'");
                $attendanceResult = $query->result_array();

                if (empty($attendanceResult)) {
                    // Date does not exist in attendance, so it's a holiday
                    $array[] = [
                        'user_id' => $user,
                        'date' => date("d M Y", strtotime($date)),
                        'user' => $items[0]['user'],
                        'fingers' => '<div class="text-info"><strong>Holiday</strong></div>'
                    ];
                    $holidayExecution = true;
                }
                
                $holidayQuery = $this->db->query("SELECT * FROM holiday");
                $holidays = $holidayQuery->result_array();

                foreach ($holidays as $value4) {
                    $startDate = $value4["starting_date"];
                    $endDate = $value4["ending_date"];
                    $apply = $value4["apply"];
                    $startDateTimestamp  = strtotime($startDate);
                    $endDateTimestamp  = strtotime($endDate);
                    $dateToCheckTimestamp  = strtotime($date);
                    if($apply == '0' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                        if (!$holidayExecution) {
                            $array[] = [
                                'user_id' => $user,
                                'date' => date("d M Y", strtotime($date)),
                                'user' => $items[0]['user'],
                                'fingers' => '<div class="text-info"><strong>Holiday</strong></div>'
                            ];
                            $holidayExecution = true;
                        }
                    }elseif ($apply == '1' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                        $departments = json_decode($value4["department"]);
                            foreach ($departments as $department) {
                                $user_ids_query = $this->db->query("SELECT * FROM users WHERE department = $department AND employee_id= $user_id");
                                $user_ids_result = $user_ids_query->result_array();
                                if (!$holidayExecution) {
                                    $array[] = [
                                        'user_id' => $user,
                                        'date' => date("d M Y", strtotime($date)),
                                        'user' => $items[0]['user'],
                                        'fingers' => '<div class="text-info"><strong>Holiday</strong></div>'
                                    ];
                                    $holidayExecution = true;
                                }
                            }
                    }elseif($apply == '2' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                        $holidayUsers = json_decode($value4["users"]);
                            foreach ($holidayUsers as $holidayUser) {
                                $user_ids_query = $this->db->query("SELECT * FROM users WHERE id = $holidayUser AND employee_id= $user_id");
                                $user_ids_result = $user_ids_query->result_array();
                                if (count($user_ids_result)>0) {
                                    if (!$holidayExecution) {
                                        $array[] = [
                                            'user_id' => $user,
                                            'date' => date("d M Y", strtotime($date)),
                                            'user' => $items[0]['user'],
                                            'fingers' => '<div class="text-info"><strong>Holiday</strong></div>'
                                        ];
                                        $holidayExecution = true;
                                    }
                                }
                            }
                    }
                }

                if(!$holidayExecution){
                    // Date does not exist for this user, so it's Absent
                    $array[] = [
                        'user_id' => $user,
                        'date' => date("d M Y", strtotime($date)),
                        'user' => $items[0]['user'],
                        'fingers' => '<div class="text-primary"><strong>Absent</strong></div>'
                    ];
                }
            }
            $row[] = $array; 
            $fingerLoopExecuted = true;
        }

        if (!$fingerLoopExecuted) {
            $userName = ''; // Initialize an empty variable to store the user's name
            $query = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS user_name FROM users WHERE employee_id = ".$user_id);
            $result = $query->row_array();
            if ($result) {
                $userName = $result['user_name']; // Get the user's name from the query result
            }

            // Check if the specific date is present in any of the fingers from attendance table
            $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$date'");
            $attendanceResult = $query->result_array();

            if (empty($attendanceResult)) {
                // Date does not exist in attendance, so it's a holiday
                $array[] = [
                    'user_id' => $user_id,
                    'date' => date("d M Y", strtotime($date)),
                    'user' => $userName,
                    'fingers' => '<div class="text-info"><strong>Holiday</strong></div>'
                ];
                } else {
                    // Date does not exist for this user, so it's Absent
                    $array[] = [
                        'user_id' => $user_id, // Use $get['user_id'] as the 'user_id'
                        'date' => date("d M Y", strtotime($date)),
                        'user' => $userName, // Use the user's name fetched from the database
                        'fingers' => '<div class="text-primary"><strong>Absent</strong></div>'
                    ];
                }
            $row[] = $array;
        }
    }

    $checkInOutData = [];
    foreach ($row as $objects) {
        foreach ($objects as $obj) {
            $userId = $obj['user_id'];
            $date = $obj['date'];
            $fingers = $obj['fingers'];
            $userName = $obj['user']; // Assign user_name to a variable
            $currentDate = new DateTime();
            $checkInOutData[] = [
                'user_id' =>'<a   href="'.$baseUrl.'attendance/user_attendance/'.$userId.'">'.$userId.'</a>',
                'user' => '<a  href="'.$baseUrl.'attendance/user_attendance/'.$userId.'">'.$userName.'</a>',
                'check_in' => $fingers,
                'date' => $date,
                // 'check_out' => $checkOut,
            ];
        }
    }

    // Custom comparison function for sorting based on "check_in" values
    function sortByCheckInDesc($a, $b) {
        return strtotime($b['check_in']) - strtotime($a['check_in']);
    }
    // leave Query
    
    $where2 .= " AND leaves.status = '1' "; 
    $leaveQuery = $this->db->query("SELECT leaves.*, CONCAT(users.first_name, ' ', users.last_name) AS user".$conditionalDates."  FROM leaves ".$leftjoin2.$where2."
    AND leaves.leave_duration NOT LIKE '%Half%'
    AND leaves.leave_duration NOT LIKE '%Short%'");
    $leavesresult = $leaveQuery->result_array(); 

    $leaveArray=[];
    foreach ($leavesresult as $leave) {
        $startingDate = new DateTime($leave['starting_date']);
        $endingDate = new DateTime($leave['ending_date']);
        $interval = $startingDate->diff($endingDate);
        $data = $interval->days + 1;
        $paid = $leave["paid"];
        if ($paid == 0) {
            for ($i=0; $i < $data; $i++) { 
                if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
                    $currentDate2 = $startingDate->format('d M Y');
                        if ($leave["employee_id"] == $get["user_id"]) {
                        $leaveArray[]=[
                            'user_id'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["employee_id"].'</a>',
                            'user'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["user"].'</a>',
                            'check_in'=>'<br><div class="text-info"><strong>Leave</strong></div>',
                            'date' => $currentDate2,
                        ];
                    }
                }else{
                    $currentDate2 = $startingDate->format('d M Y');
                    $leaveArray[]=[
                        'user_id'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["employee_id"].'</a>',
                        'user'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["user"].'</a>',
                        'check_in'=>'<br>'.'<div class="text-info"><strong>Leave</strong></div>',
                        'date' => $currentDate2,
                    ];
                }
                $startingDate->modify('+1 day');
            }
        }
    }

    $absentHolidayEntries = array_merge($checkInOutData, $leaveArray);

    // Filter Absent and Holiday entries if there's already a Leave entry for that date
    $filteredEntries = [];
    $datesAdded = [];
    foreach ($absentHolidayEntries as $entry) {
        $alreadyExists = false;
        $date = $entry['date'];
        $leaveExists = false;

        foreach ($leaveArray as $leaveEntry) {
            if ($leaveEntry['date'] === $date) {
                $leaveExists = true;
                break;
            }
        }

        if ($leaveExists && strpos($entry['check_in'], 'Holiday') !== false) {
            $leaveExists = false;
        } 

        // Check if the entry with the same date already exists in $filteredEntries
        $alreadyExists = in_array($date, $datesAdded);

        // Add the entry to $filteredEntries only if there is no entry with the same date
        if (!$leaveExists && !$alreadyExists) {
            $filteredEntries[] = $entry;
            $datesAdded[] = $date;
        } elseif ($leaveExists && !$alreadyExists) {
            $filteredEntries[] = $leaveEntry;
            $datesAdded[] = $date;
        }
    }

    // Remove duplicate data from the filtered array based on the 'date' field
    $filteredEntries = array_unique($filteredEntries, SORT_REGULAR);
    
    $checkInArray = array_column($filteredEntries, 'user_id');
    array_multisort($checkInArray, SORT_ASC, $filteredEntries);

    function sortByDateAsc($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    }

    // Sort the merged array using the custom comparison function
    usort($filteredEntries, 'sortByDateAsc');

    // Retrieve the base URL using the base_url() function
    $baseUrl = base_url();

    $serialNumber = 1; // Initialize the serial number variable

    $preabs = isset($get['preabs']) ? $get['preabs'] : '';

    // Filter the data based on the $preabs value
    $filteredData = [];
    foreach ($filteredEntries as $row) {

        if ($preabs === 'absent') {
            // Show rows with 'Absent' status
            if (strpos($row['check_in'], 'Absent') !== false) {
                $filteredData[] = $row;
            }
        } elseif ($preabs === 'leave') {
            // Show rows with 'Leave' status
            if (strpos($row['check_in'], 'Leave') !== false) {
                $filteredData[] = $row;
            }
        } elseif ($preabs === 'late') {
            if (preg_match('/\b\d{2}:\d{2} [APM]{2}\b/', $row['check_in'], $matches)) {
                $firstCheckIn = trim($matches[0]);
                $checkInTime = strtotime($firstCheckIn); // Convert the first time value to a timestamp
                $userId = preg_replace('/[^0-9]/', '', $row['user_id']);
                preg_match('/\d+/', $row['user_id'], $userIdMatches);
                $userId = isset($userIdMatches[0]) ? $userIdMatches[0] : null;

                $user_ids_query = $this->db->query("SELECT * FROM users WHERE employee_id = $userId");
                
                if ($user_ids_query) {
                    $user_ids_result = $user_ids_query->row_array();
                    $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '';
                }

                $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
                if ($shift_query) {
                    $shift_result = $shift_query->row_array();
                    $starting_time = isset($shift_result['starting_time']) ? $shift_result['starting_time'] : '';
                }
                $starting_time = date('h:i A', strtotime($starting_time)); // Convert the starting_time to '09:00 AM' format
                // Show rows with time greater than 9:00 AM
                if ($checkInTime > strtotime($starting_time)) {
                    // If the first time in the check_in value is late, include the row in the filtered data
                    $filteredData[] = $row;
                }
            }
        } elseif ($preabs === 'present')  {
            // Show rows with time in the format 08:44 AM
            if (preg_match('/\b\d{2}:\d{2} [APM]{2}\b/', $row['check_in'])) {
                $filteredData[] = $row;
            }
        }
        else{
            $filteredData[] = $row;
        }
    }

    foreach ($filteredData as $key => &$value) {
        $value['s.n'] = $serialNumber; // Add the 's.n' key with the serial number value
        $serialNumber++; // Increment the serial number for the next iteration
        $userId = preg_replace('/[^0-9]/', '', $value['user_id']);
        preg_match('/\d+/', $value['user_id'], $userIdMatches);
        $userId = isset($userIdMatches[0]) ? $userIdMatches[0] : null;
        $user_ids_query = $this->db->query("SELECT * FROM users WHERE employee_id = $userId");
        $user_ids_result = $user_ids_query->row_array();
        $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '1';

        // Get the shift_name from the shift table based on shift_id
        $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
        $shift_result = $shift_query->row_array();
        $starting_time = date('h:i A', strtotime($shift_result['starting_time']));
        $ending_time = date('h:i A', strtotime($shift_result['ending_time']));
        $shift_name = isset($shift_result['name']) ? $shift_result['name'].' ('. $starting_time.' - '.$ending_time.' )': 'Regular Shift (09:00 AM - 06:00 PM)';
        // Add the shift_name attribute to the row
        $value['shift_name'] = $shift_name;
    }

    // Slice the sorted merged array based on offset and limit
    $arra = array_slice($filteredData, $offset, $limit);

    $data = [
        'total' => count($filteredData),
        'rows' => $arra,
        'leaves' => $leaveArray
    ];

    print_r(json_encode($data));
}
    
// shoaib

function get_attendance_report($get){
    
    // Check if the user is an admin
    if ($this->ion_auth->is_admin() || permissions('attendance_view_all')) {
        if (isset($get['user_id']) && !empty($get['user_id'])) {
            $where = " WHERE attendance.user_id = " . $get['user_id'];
            $where2 = " WHERE leaves.employee_id = " . $get['user_id']." AND leaves.status='1'";
        } else {
            $where = " WHERE attendance.id IS NOT NULL ";
            $where2 = " WHERE leaves.id IS NOT NULL AND leaves.status='1'";
        }
    } else {
        $where = " WHERE attendance.user_id = " . $this->session->userdata('user_id');
        $where2 = " WHERE leaves.employee_id = " . $this->session->userdata('user_id')." AND leaves.status='1'";
    }
    if (isset($get['departments']) && !empty($get['departments'])) {
        $department = $get['departments'];
        $where .= " AND users.department = '$department'";
        $where2 .= " AND users.department = '$department'";
    }
    
     // Set the filter options
     $filter = isset($get['filter']) ? $get['filter'] : 'tmonth';
     switch ($filter) {
         case 'today':
             $currentDate = date('Y-m-d');
             $where .= " AND DATE(attendance.finger) BETWEEN '{$currentDate}' AND '{$currentDate}' ";
             $where2 .= " AND DATE(leaves.starting_date) BETWEEN '{$currentDate}' AND '{$currentDate}' ";
             $currentDate = new DateTime();
             $datesArray = array();
             $datesArray[] = $currentDate->format('Y-m-d');
             $datesArray = array_reverse($datesArray);
             $rowMonth= $currentDate->format('M Y');
             break;
         case 'tweek':
             $currentDate = date('Y-m-d');
             $fromDate = date('Y-m-d', strtotime('last Monday'));
             $toDate = $currentDate;
             $where .= " AND DATE(attendance.finger) BETWEEN '{$fromDate}' AND '{$toDate}' ";
             $where2 .= " AND DATE(leaves.starting_date) BETWEEN'{$fromDate}' AND '{$toDate}' ";
             $currentDate = new DateTime();
             $datesArray = array();
             // Find the previous Monday
             $dayOfWeek = $currentDate->format('N');
             $daysToSubtract = ($dayOfWeek + 6) % 7;
             $currentDate->modify('-' . $daysToSubtract . ' days');
             // Push each date into the array
             while ($currentDate <= new DateTime()) {
                 $datesArray[] = $currentDate->format('Y-m-d');
                 $currentDate->modify('+1 day');
                 $rowMonth= $currentDate->format('M Y');
             }
             break;
         case 'ystdy':
             $currentDate = date('Y-m-d');
             $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
             $where .= " AND DATE(attendance.finger) BETWEEN '{$yesterday}' AND '{$yesterday}' ";
             $where2 .= " AND DATE(leaves.starting_date) BETWEEN '{$yesterday}' AND '{$yesterday}' ";
             $currentDate = new DateTime();
             $datesArray = array();
             $yesterdayDate = $currentDate->modify('-1 day');
             $datesArray[] =$yesterdayDate->format('Y-m-d');
             $rowMonth= $currentDate->format('M Y');
             $datesArray = array_reverse($datesArray);
             break;
         case 'tmonth':
             $currentDate = date('Y-m-d');
             $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
             $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' ";
             $where2 .= " AND DATE(leaves.starting_date) BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' ";
             $currentDate = new DateTime();
             $endDate = new DateTime('first day of ' . $currentDate->format('F Y'));
             $rowMonth= $currentDate->format('M Y');
             $datesArray = array();
             while ($currentDate >= $endDate) {
                 $datesArray[] = $currentDate->format('Y-m-d');
                 $currentDate->modify('-1 day');
             }
             $datesArray = array_reverse($datesArray);
             break;
         case 'lmonth':
             $lastMonthStart = date('Y-m-01', strtotime('first day of -1 month'));
             $lastMonthEnd = date('Y-m-t', strtotime('last day of -1 month'));
             $where .= " AND DATE(attendance.finger) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}' ";
             $where2 .= " AND DATE(leaves.starting_date) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}' ";
             $firstDayOfMonth = strtotime('first day of last month');
             $lastDayOfMonth = strtotime('last day of last month');
             $datesArray=[];
             $currentDate = $firstDayOfMonth;
             $rowMonth = date('M Y', $currentDate);
             while ($currentDate <= $lastDayOfMonth) {
                 $datesArray[] = date('Y-m-d', $currentDate);
                 $currentDate = strtotime('+1 day', $currentDate);
             }
             break;
         case 'custom':
             if (isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
                 $where .= " AND DATE(attendance.finger) BETWEEN '" . format_date($get['from'], "Y-m-d") . "' AND '" . format_date($get['too'], "Y-m-d") . "' ";
                 $where2 .= " AND DATE(leaves.starting_date) BETWEEN '" . format_date($get['from'], "Y-m-d") . "' AND '" . format_date($get['too'], "Y-m-d") . "' ";
             }
             $currentDate = strtotime($get["from"]);
             $endDate = strtotime($get["too"]);
             $rowMonth3 = date('M Y', $currentDate);
             $rowMonth2 = date('M Y', $endDate);
             if ($rowMonth3 == $rowMonth2) {
                 $rowMonth = date('M Y', $currentDate);
             }else{
                 $rowMonth = "Custom Date";
             }
             $datesArray=[];
             while ($currentDate <= $endDate) {
                 $datesArray[] = date('Y-m-d', $currentDate);
                 $currentDate = strtotime('+1 day', $currentDate);
             }
             break;
        case 'tyear':
            $currentDate = date('Y-m-d');
            $firstDayThisYear = date('Y-01-01');
            $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayThisYear}' AND '{$currentDate}' ";
            $where2 .= " AND DATE(leaves.starting_date) BETWEEN '{$firstDayThisYear}' AND '{$currentDate}' ";
            $currentYear = date('Y');
            $currentDate = new DateTime('first day of January ' . $currentYear);
            while ($currentDate <= new DateTime()) {
                $datesArray[] = $currentDate->format('Y-m-d');
                $currentDate->modify('+1 day');
            }
            $rowMonth = $currentYear;
            break;
        case 'lyear':
            $currentYear = date('Y');
            $lastYear = $currentYear - 1;
            $firstDayLastYear = "{$lastYear}-01-01";
            $lastDayLastYear = "{$lastYear}-12-31";
            $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayLastYear}' AND '{$lastDayLastYear}' ";
            $where2 .= " AND DATE(leaves.starting_date) BETWEEN '{$firstDayLastYear}' AND '{$lastDayLastYear}' ";
            $lastYear = date('Y') - 1;
            $firstDayLastYear = new DateTime('first day of January ' . $lastYear);
            $lastDayLastYear = new DateTime('last day of December ' . $lastYear);
            while ($firstDayLastYear <= $lastDayLastYear) {
                $datesArray[] = $firstDayLastYear->format('Y-m-d');
                $firstDayLastYear->modify('+1 day');
            }
            $rowMonth = $lastYear;
            break;
     }
     if (isset($get['active_users']) && !empty($get['active_users'])) {
        $active_users = $get['active_users'];
        if (isset($get['active_users']) && !empty($get['active_users'])) {
            $active_users = $get['active_users'];
            if ($active_users == '1') {
                $where .= " AND users.active = '1' AND users.finger_config = '1'";
                $where2 .= " AND users.active = '1' AND users.finger_config = '1'";
            }elseif ($active_users == '3') {
                $where .= " AND users.finger_config = '1'";
                $where2 .= " AND users.finger_config = '1'";
            }else{
                $where .= " AND users.active = '0' AND users.finger_config = '1'";
                $where2 .= " AND users.active = '0' AND users.finger_config = '1'";
            }
        }
        
    }
    $leftjoin = "LEFT JOIN users ON attendance.user_id = users.employee_id";
    $leftjoin2 = "LEFT JOIN users ON leaves.employee_id = users.employee_id";
    
    $query = $this->db->query("SELECT attendance.*, CONCAT(users.first_name, ' ', users.last_name) AS user
        FROM attendance " . $leftjoin . $where);
    $results = $query->result_array();
    
    function compareFinger($a, $b) {
        $timestampA = strtotime($a["finger"]);
        $timestampB = strtotime($b["finger"]);
    
        if ($timestampA === false && $timestampB === false) {
            return strcmp($a["finger"], $b["finger"]); // Both are in the same format, perform string comparison
        } elseif ($timestampA === false) {
            return 1; // The first item is in a different format, so consider it greater
        } elseif ($timestampB === false) {
            return -1; // The second item is in a different format, so consider it greater
        }
    
        return $timestampA - $timestampB; // Compare timestamps for the same format
    }
    
    usort($results, 'compareFinger');
    // $where2 .= " AND leaves.status='1'";
    $leaveQuery = $this->db->query("SELECT leaves.*, CONCAT(users.first_name, ' ', users.last_name) AS user
        FROM leaves " . $leftjoin2 . $where2);
    $leaveResult = $leaveQuery->result_array();
    $leaveArray=[];
    

    $mergedData3 = array();

    foreach ($leaveArray as $row) {
        $user = $row['user'];
        unset($row['user']);

        if (!isset($mergedData3[$user])) {
            $mergedData3[$user] = $row;
        } else {
            $mergedData3[$user] = array_merge($mergedData3[$user], $row);
        }
    }

    $finalData = array();
    foreach ($mergedData3 as $user => $dates) {
        $userData = array('user' => $user);
        foreach ($dates as $date => $status) {
            $userData[$date] = $status;
        }
        $finalData[] = $userData;
    }
    
    $final = array();
    foreach ($results as $item) {
        $rowDate = date('Y-m-d', strtotime($item['finger']));
        $Fingertime = date('h:iA', strtotime($item['finger']));
        foreach ($datesArray as $date) {
            if ($date == $rowDate) {
                $final[] = [
                    "user" => $item["user"],
                    "$date" => $Fingertime,
                    "sq" => '0/0/0',
                    "id" => $item["user_id"],
                ];
            }
        }
    }
    $result = array();
    
    foreach ($final as $row) {
        $user = $row['user'];
        $id = $row['id'];
        $sq = $row['sq'];
        $date = array_keys($row)[1]; // Assuming the date object is always the second key
        $time = $row[$date];
    
        if (!isset($result[$user])) {
            $result[$user] = [];
        }
    
        if (isset($result[$user][$date])) {
            $result[$user][$date] .= '<br>' . $time;
        } else {
            $result[$user][$date] = $time;
        }
    
        // Set the 'sq' value for the user
        $result[$user]["sq"] = $sq;
        $result[$user]["id"] = $id;
    }
    
    $resultData = array('total' => count($result), 'rows' => array());
    foreach ($result as $user => $dates) {
        foreach ($dates as $date => $time) {
            $resultData['rows'][] = array('user' => $user, $date => $time);
        }
    }
    
    $result = array();
    
    foreach ($resultData['rows'] as $row) {
        $user = $row['user'];
        unset($row['user']);
        if (!isset($result[$user])) {
            $result[$user] = $row;
        } else {
            foreach ($row as $date => $time) {
                if (isset($result[$user][$date])) {
                    $result[$user][$date] .= ', ' . $time;
                } else {
                    $result[$user][$date] = $time;
                }
            }
        }
    }
    
    $output = array();
    foreach ($result as $user => $row) {
        $output[] = array_merge(['user' => $user], $row);
    }
    
     // Merge the two arrays

     $mergedMap = [];

     // Step 1: Merge the first array
     foreach ($finalData as $user) {
         $username = $user['user'];
         $mergedMap[$username] = $user;
     }
 
     // Step 2: Merge the second array
     foreach ($output as $user) {
         $username = $user['user'];
         if (isset($mergedMap[$username])) {
             // Merge the dates and other objects
             foreach ($user as $date => $value) {
                 if ($date !== 'user') {
                     if (!isset($mergedMap[$username][$date])) {
                         $mergedMap[$username][$date] = $value;
                     } else {
                         // Handle multi-value case for the same date-key
                         if (!is_array($mergedMap[$username][$date])) {
                             $mergedMap[$username][$date] = [$mergedMap[$username][$date]];
                         }
                         $mergedMap[$username][$date][] = $value;
                     }
                 }
             }
         } else {
             $mergedMap[$username] = $user;
         }
     }
 
    // Step 3: Extract merged objects from the map
    $mergedArray = array_values($mergedMap);
        
    // Loop through each row and add missing headings with "N/A" value
    foreach ($mergedArray as &$row) {
        $userHeadings = array_keys($row);
        $missingHeadings = array_diff($datesArray, $userHeadings);
        
        foreach ($missingHeadings as $heading) {
            $row[$heading] = '<span class="text-danger"><strong>A</strong></span>';
        }
    }

    // Now you have the merged and filtered array without duplicate users
        
    $holidayQuery = $this->db->query("SELECT * FROM holiday");
    $holidayResult = $holidayQuery->result_array();
    $dates_between = array();

    // $satSunArray = array();
    // foreach ($datesArray as $date) {
    // $dayOfWeek = date('N', strtotime($date)); // Get the day of the week (1 = Monday, 7 = Sunday)
    // // Check if the day is Saturday (6) or Sunday (7)
    // if ($dayOfWeek == 6 || $dayOfWeek == 7) {
    // $satSunArray[] = $date; // Push the date to the new array
    // }
    // }
    // foreach ($datesArray as $heading_date) {
    //     if (in_array($heading_date, $satSunArray)) {
    //         foreach ($mergedArray as $key => $afterholiday) {
    //             if (array_key_exists($heading_date, $afterholiday)) {
    //                 $value = $mergedArray[$key][$heading_date];
    //                 if ($value === '<span class="text-danger"><strong>A</strong></span>' || $value === '<span class="text-info"><strong>L</strong></span>') {
    //                     $mergedArray[$key][$heading_date] = '<span class="text-primary" style="font-weight: bold; color: blue;">H</span>';

    //                 }
    //             }
    //         }
    //     }
    // }
    // departmantal Holiday

    foreach ($holidayResult as $holiday) {
        $starting_date = $holiday['starting_date'];
        $ending_date = $holiday['ending_date'];
        $apply = $holiday['apply'];
        $start_date_obj = new DateTime($starting_date);
        $end_date_obj = new DateTime($ending_date);
        $end_date_obj->modify('+1 day'); // Add one day to include the end date
        $date_range = new DatePeriod($start_date_obj, new DateInterval('P1D'), $end_date_obj);
        foreach ($date_range as $date) {
            $dates_between[] = $date->format('Y-m-d');
        }
        if ($holiday['type'] != '2' && $apply == '0') {
            foreach ($datesArray as $heading_date) {
                if (in_array($heading_date, $dates_between)) {
                    foreach ($mergedArray as $key => $afterholiday) {
                        if (array_key_exists($heading_date, $afterholiday)) {
                            $value = $mergedArray[$key][$heading_date];
                            if ($value === '<span class="text-danger"><strong>A</strong></span>') {
                                $mergedArray[$key][$heading_date] = '<span class="text-primary" style="font-weight: bold; color: blue;">H</span>';
            
                            }
                        }
                    }
                } 
            }
        }
        elseif ($holiday['type'] != '2' && $apply == '1') {
            $departments = json_decode($holiday['department']);
            foreach ($departments as $department) {
                foreach ($dates_between as $heading_date2) {
                    foreach ($mergedArray as $key => $afterholiday) {
                        $id = $mergedArray[$key]["id"];
                        $HoliDayUsers = $this->db->query("SELECT department FROM users WHERE employee_id=".$id);
                        $HoliDayUsersresults = $HoliDayUsers->row_array();
                        if ($HoliDayUsersresults["department"] == $department) {
                            $value2 = $mergedArray[$key][$heading_date2];
                            if ($value2 === '<span class="text-danger"><strong>A</strong></span>' || $value2 === '<span class="text-info"><strong>L</strong></span>') {
                                $mergedArray[$key][$heading_date2] = '<span class="text-primary" style="font-weight: bold; color: blue;">H</span>';

                            }
                        }
                    }
                }
                
            }
        }
        elseif ($holiday['type'] != '2' && $apply == '2') {
            $users = json_decode($holiday['users']);
            foreach ($users as $user3) {
                foreach ($dates_between as $heading_date3) {
                    foreach ($mergedArray as $key => $afterholiday) {
                        $id = $mergedArray[$key]["id"];
                        $HoliDayUsers = $this->db->query("SELECT id FROM users WHERE employee_id=".$id);
                        $HoliDayUsersresults = $HoliDayUsers->row_array();
                        if ($HoliDayUsersresults["id"] == $user3) {
                            $value3 = $mergedArray[$key][$heading_date3];
                            if ($value3 === '<span class="text-danger"><strong>A</strong></span>' || $value3 === '<span class="text-info"><strong>L</strong></span>') {
                                $mergedArray[$key][$heading_date3] = '<span class="text-primary" style="font-weight: bold; color: blue;">H</span>';
                            }
                        }
                    }
                } 
            }
        }
    }

    foreach ($datesArray as $heading_date) {
        foreach ($mergedArray as $key => $afterholiday) {
            $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$heading_date'");
            $attendanceResult = $query->result_array();
            if (empty($attendanceResult)) {
                $mergedArray[$key][$heading_date] = '<span class="text-primary" style="font-weight: bold; color: blue;">H</span>';
            }
        }
    }

    foreach ($leaveResult as $leave) {
        $startingDate = new DateTime($leave['starting_date']);
        $endingDate = new DateTime($leave['ending_date']);
        $interval = $startingDate->diff($endingDate);
        $data = $interval->days + 1;
        for ($i=0; $i < $data; $i++) { 
            if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
                $currentDate2 = $startingDate->format('d M Y');
                $currentDate3 = $startingDate->format('Y-m-d');
                if (isset($get['from']) && !empty($get['from']) && $currentDate2 == $get['from']) {
                    $leaveArray[]=[
                        'employee_id'=>$leave["employee_id"],
                        'user'=>$leave["user"],
                        "$currentDate3"=>'<span class="text-info"><strong>L</strong></span>'
                    ];
                }else{
                    $leaveArray[]=[
                        'employee_id'=>$leave["employee_id"],
                        'user'=>$leave["user"],
                        "$currentDate3"=>'<span class="text-info"><strong>L</strong></span>'
                    ];
                }
            }else{
                $currentDate3 = $startingDate->format('Y-mm-dd');
                $leaveArray[]=[
                    'employee_id'=>$leave["employee_id"],
                    'user'=>$leave["user"],
                    "$currentDate3"=>'<span class="text-info"><strong>L</strong></span>'
                ];
            }
            $startingDate->modify('+1 day');
        }
    }
    foreach ($leaveArray as $leaveValue) {
        $employeeId = $leaveValue["employee_id"];
        
        foreach ($datesArray as $heading_date3) {
            foreach ($mergedArray as $key => &$employeeData) {
                if ($employeeData["id"] == $employeeId) {
                    $value3 = $employeeData[$heading_date3] ?? null;
                    if ($value3 === '<span class="text-danger"><strong>A</strong></span>' || $value3 === '<span class="text-primary"><strong>H</strong></span>') {
                        if (isset($leaveValue[$heading_date3])) {
                            $employeeData[$heading_date3] = $leaveValue[$heading_date3];
                        }
                    }
                }
            }
        }
    }

    if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
        $conditions = "users.finger_config = '1'";
        $id = isset($get['user_id']) && !empty($get['user_id']) ? $get['user_id'] : null;
        $department_id = isset($get['departments']) && !empty($get['departments']) ? $get['departments'] : null;
        $active_users = isset($get['active_users']) && !empty($get['active_users']) ? $get['active_users'] : null;
        $conditions1 = " ";
    
        if ($id) {
            $conditions1 .= " AND users.employee_id='$id'";
        }
        if ($department_id) {
            $conditions1 .= " AND department='$department_id'";
        }
        if ($active_users) {
            if ($active_users == '1' || $active_users == '3') {
                $conditions1 .= " AND users.active = '1'";
            }elseif ($active_users == '2') {
                $conditions1 .= " AND users.active = '4'";
            }
        } 
        $absentQuery = $this->db->query("SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS user  
                FROM users 
                WHERE $conditions $conditions1");
    
        $absentresult = $absentQuery->result_array();
    }
    
    foreach ($datesArray as $heading_date3) {
        $loopExecution = false;
        foreach ($mergedArray as $key => &$employeeData) {
            $user_id = $employeeData["id"];
            $value3 = $employeeData[$heading_date3] ?? null;
            $joindateQuery = $this->db->query("SELECT * FROM users WHERE employee_id=".$user_id);
            $joindateResult = $joindateQuery->row_array();
            $value3 = $employeeData[$heading_date3] ?? null;
            if (!empty($joindateResult["join_date"])) {
                if ($joindateResult["join_date"] > $heading_date3) {
                    $all[]= $heading_date3.'  '.$joindateResult["join_date"];
                    $employeeData[$heading_date3] = "-";
                }
            }
            $loopExecution = true;
        }
        if(!$loopExecution){
            foreach ($absentresult as $item) {
                $mergedArray[] = [
                    "user" => $item["user"],
                    "$heading_date3" => '<span class="text-danger"><strong>A</strong></span>',
                    "sq" => '1/0/0',
                    "id" => $item["employee_id"],
                ];
            }
            
        }
    }

    $monthsArray = array();

    // Loop through the datesArray to extract unique months and years
    foreach ($datesArray as $date) {
        $monthYear = date('M Y', strtotime($date));
        if (!in_array($monthYear, $monthsArray)) {
            $monthsArray[] = $monthYear;
        }
    }
    
    $lateDays = 0;
    $settings_query = $this->db->query("SELECT value FROM settings WHERE type = 'grace_minutes_'");
    $settings_result = $settings_query->row_array();
    $grace_settings = json_decode($settings_result['value'], true);

    $applyValue = $grace_settings['apply'];
    $daysCounter = $grace_settings['days_counter'];
    $graceMinutes = $grace_settings['grace_minutes'];

    foreach ($mergedArray as $key => $afterholiday) {
        $abs = 0;
        $late = 0;
        $half = 0;
        $totalGraceMinutes = 0;
        $graceDays = 0;
        $lateDays = 0;
        $lateMinutesDates = array();
        $lateMinutesArray = array();
        foreach ($datesArray as $heading_date) {
            $total_minutes = 0;
            $user_id = $afterholiday["id"];
            $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves WHERE leaves.starting_date = '".$heading_date."' AND leaves.employee_id='".$user_id."' AND leaves.status='1' AND leaves.paid='0'");
            if ($leaveQuery->num_rows() > 0) { 
                $leaveRow = $leaveQuery->row();
                $leaveDuration = $leaveRow->leave_duration;
                if (strpos($leaveDuration, 'Half') !== false) {
                    $mergedArray[$key][$heading_date] .= '<span class="text-info"><strong><br>HD L</strong></span>';
                } elseif (strpos($leaveDuration, 'Short') !== false) {
                    $mergedArray[$key][$heading_date] .= '<span class="text-info"><strong><br>S L</strong></span>';
                } 
            }else {
                $value = $mergedArray[$key][$heading_date];
                
                $time_values = explode('<br>', $mergedArray[$key][$heading_date]);

                $check_in_times = [];
                foreach ($time_values as $time_entry) {
                    $check_in_time = strtotime($time_entry);
                    $check_in_times[] = $check_in_time;
                }

                $first_time = trim($time_values[0]); 
                $yourTimestamp = strtotime($first_time);
                
                $num_time_entries = count($check_in_times);
                $user_id = $mergedArray[$key]["id"];
                // Construct the query to search for the user based on employee ID and join with the shifts table.
                $this->db->select('users.*, shift.*');
                $this->db->from('users');
                $this->db->where('users.employee_id', $user_id);
                $this->db->join('shift', 'users.shift_id = shift.id', 'left'); // Assuming shift_id is the foreign key in the users table.

                // Execute the query.
                $query = $this->db->get();
                $userWithShiftData = $query->row();

                $half_day_check_in_str = isset($userWithShiftData->half_day_check_in) ? $userWithShiftData->half_day_check_in : '11:00:00';
                $half_day_check_in = strtotime($half_day_check_in_str);

                $half_day_check_out_str = isset($userWithShiftData->half_day_check_out) ? $userWithShiftData->half_day_check_out : '18:00:00';
                $half_day_check_out = strtotime($half_day_check_out_str);

                $starting_time_str = isset($userWithShiftData->starting_time) ? $userWithShiftData->starting_time : '09:00:00';
                $starting_time = strtotime($starting_time_str); 

                $ending_time_str = isset($userWithShiftData->ending_time) ? $userWithShiftData->ending_time : '18:00:00';
                $ending_time = strtotime($ending_time_str);
                $halfDayExecution = false;

                if ($num_time_entries > 1) {
                    // Get the first and last time entries
                    $first_check_in_time = min($check_in_times);
                    $last_check_in_time = max($check_in_times);
                    
                    if($first_check_in_time > $half_day_check_in || $last_check_in_time < $half_day_check_out){
                        $mergedArray[$key][$heading_date] .= '<span class="text-danger"><strong><br>HD</strong></span>';
                        $half++;
                        $halfDayExecution = true;
                    }
                    elseif($last_check_in_time < $half_day_check_out && !$halfDayExecution) { 
                        $mergedArray[$key][$heading_date] .= '<span class="text-danger"><strong><br>HD</strong></span>';
                        $half++;
                    }
                    elseif($ending_time != $half_day_check_out && $last_check_in_time < $ending_time){
                        $late = max(0, ($ending_time - $last_check_in_time) / 60)+$late;
                        $total_minutes = max(0, ($ending_time - $last_check_in_time) / 60)+$total_minutes;
                    }
                    elseif ($first_check_in_time > $starting_time && $first_check_in_time < $half_day_check_in) { 
                        $late = max(0, ($first_check_in_time - $starting_time) / 60)+$late;
                        $total_minutes = max(0, ($first_check_in_time - $starting_time) / 60)+$total_minutes;
                    }
                }else {
                    $check_in_time = $check_in_times[0];
                    if ($check_in_time > $half_day_check_in && !$halfDayExecution) {
                        $mergedArray[$key][$heading_date] .= '<span class="text-danger"><strong><br>HD</strong></span>';
                        $half++;
                    }elseif ($check_in_time > $starting_time) {
                        $late = max(0, ($check_in_time - $starting_time) / 60)+$late;
                        $total_minutes = max(0, ($check_in_time - $starting_time) / 60)+$total_minutes;
                    }
                }

                // Calculate the difference in minutes between the two timestamps
                if ($value === '<span class="text-danger"><strong>A</strong></span>' && $value != '-') {
                $abs++;
                }

                if($total_minutes > $graceMinutes){
                    $lateDays ++;
                    $lateMinutesArray[] = $total_minutes;
                    $lateMinutesDates[] = $heading_date;
                }
            }
        }
        if($applyValue == '0'){
            unset($lateMinutesDates);
        }
    
        if($lateDays >= $daysCounter && $applyValue == '1'){
            $graceDays = 1;
            for ($i = 0; $i < $daysCounter; $i++) {
                if (isset($lateMinutesArray[$i])) {
                    $totalGraceMinutes += $lateMinutesArray[$i];
                }
            }
            $loopTimes = $lateDays - $daysCounter;
            $j = $lateDays - 1; 
            for ($i = 0; $i < $loopTimes; $i++) {
                if (isset($lateMinutesDates[$j])) {
                    unset($lateMinutesDates[$j]);
                }
                $j--;
                if ($j < 0) {
                    break;
                }
            }
            foreach ($lateMinutesDates as $lateMinutesDate) {
                if (isset($mergedArray[$key][$lateMinutesDate])) {
                    $mergedArray[$key][$lateMinutesDate] = '<span class="green-background">' . $mergedArray[$key][$lateMinutesDate] . '</span>';
                }
            }
        }
        if ($graceDays !== 0) {
            $half = $half + $graceDays;
            $late = $late - $totalGraceMinutes;
            $mergedArray[$key]["sq"] = '<span class="green-background">' . $abs.'/'.$half.'/'.$late . '</span>';
            
        }
        else{
            $mergedArray[$key]["sq"] = $abs.'/'.$half.'/'.$late;
        }
    }

    $arr = [
        'total' => count($mergedArray),
        'rows' => $mergedArray,
        'col_span' =>count($datesArray),
        'row_month' =>$rowMonth,
        'month' => $monthsArray,
        'headings' => $datesArray,
    ];
    
    return $arr;
}


function get_attendance_report3($get){ 
    // Disable displaying errors on the screen
    ini_set('display_errors', 0);

    // Ignore all warning messages
    error_reporting(E_ALL & ~E_WARNING);

    $globalFromDate = '';
    $globalToDate = '';
    $user_id = $get['user_id'];
    // Check if the user is an admin
    if ($this->ion_auth->is_admin() || permissions('attendance_view_all')) {
        if (isset($get['user_id']) && !empty($get['user_id'])) {
            $where = " WHERE attendance.user_id = " . $get['user_id'];
            $where2 = " WHERE leaves.employee_id = ".$get['user_id'];
        } else {
            $where = " WHERE attendance.id IS NOT NULL ";
            $where2 = " WHERE leaves.id IS NOT NULL ";
        }
    } else {

        // Build the query to retrieve the employee_id
        $this->db->select('employee_id');
        $this->db->from('users');
        $this->db->where('id', $this->session->userdata('user_id'));
        $query = $this->db->get();
        $result = $query->row_array();
        $employee_id = $result['employee_id'];
        $where = " WHERE attendance.user_id = " . $employee_id;
        $where2 = " WHERE leaves.employee_id = ".$employee_id;
    }
    
    // Set the filter options
    $filter = isset($get['filter']) ? $get['filter'] : 'tmonth';
    switch ($filter) {
        case 'today':
            $currentDate = date('Y-m-d');
            $globalFromDate = $currentDate; 
            $globalToDate = $currentDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$currentDate}' AND '{$currentDate}' ";
            // $where2 .= " AND (leaves.starting_date <= '{$currentDate}' AND leaves.ending_date >= '{$currentDate}')";
            // $conditionalDates = ", '{$currentDate}' AS starting_date, '{$currentDate}' AS ending_date";
            break;
        case 'tweek':
            $currentDate = date('Y-m-d');
            $today = date('D');
            $fromDate = ($today === 'Mon') ? date('Y-m-d', strtotime('this Monday')) : date('Y-m-d', strtotime('last Monday'));
            $toDate = $currentDate;
            $globalFromDate = $fromDate; 
            $globalToDate = $toDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$fromDate}' AND '{$toDate}' ";
            break;
        case 'lweek':
            $today = date('N'); // Get the day of the week (1 = Monday, 2 = Tuesday, etc.)
            if ($today == 1) {
                $lastWeekMonday = date('Y-m-d', strtotime('last Monday'));
            } else {
                $lastWeekMonday = date('Y-m-d', strtotime('last Monday -1 week'));
            }
            $lastWeekSunday = date('Y-m-d', strtotime('last Sunday'));
            $globalFromDate = $lastWeekMonday;
            $globalToDate = $lastWeekSunday;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$lastWeekMonday}' AND '{$lastWeekSunday}' ";
            break;
        case 'ystdy':
            $currentDate = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
            $globalFromDate = $yesterday; 
            $globalToDate = $yesterday;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$yesterday}' AND '{$yesterday}' ";
            // $where2 .= " AND (leaves.starting_date <= '{$yesterday}' AND leaves.ending_date >= '{$yesterday}')";
            // $conditionalDates = ", '{$yesterday}' AS starting_date, '{$yesterday}' AS ending_date";
            break;
        case 'tmonth':
            $currentDate = date('Y-m-d');
            $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
            $globalFromDate = $firstDayOfMonth; 
            $globalToDate = $currentDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}' ";
            // $where2 .= " AND (leaves.starting_date >= '{$firstDayOfMonth}' OR leaves.ending_date <= '{$currentDate}')";
            // $conditionalDates = ", '{$currentDate}' AS ending_date";
            break;
        case 'lmonth':
            $lastMonthStart = date('Y-m-01', strtotime('first day of -1 month'));
            $lastMonthEnd = date('Y-m-t', strtotime('last day of -1 month'));
            $globalFromDate = $lastMonthStart; 
            $globalToDate = $lastMonthEnd;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}' ";
            // $where2 .= " AND (leaves.starting_date >= '{$lastMonthStart}' AND leaves.ending_date <= '{$lastMonthEnd}')";
            break;
        case 'tyear':
            $currentDate = date('Y-m-d');
            $firstDayOfYear = date('Y-01-01', strtotime($currentDate));
            $globalFromDate = $firstDayOfYear; 
            $globalToDate = $currentDate;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayOfYear}' AND '{$currentDate}' ";
            break;
        case 'lyear':
            $currentYear = date('Y');
            $lastYear = $currentYear - 1;
            $firstDayOfLastYear = "{$lastYear}-01-01";
            $lastDayOfLastYear = "{$lastYear}-12-31";
            $globalFromDate = $firstDayOfLastYear; 
            $globalToDate = $lastDayOfLastYear;
            $where .= " AND DATE(attendance.finger) BETWEEN '{$firstDayOfLastYear}' AND '{$lastDayOfLastYear}' ";
            break;
        case 'custom':
            if (isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
                $globalFromDate = $get['from']; 
                $globalToDate = $get['too'];
                $where .= " AND DATE(attendance.finger) BETWEEN '" . format_date($get['from'], "Y-m-d") . "' AND '" . format_date($get['too'], "Y-m-d") . "' ";
                // $where2 .= " AND (leaves.starting_date BETWEEN '".format_date($get['from'], "Y-m-d")."' AND '".format_date($get['too'], "Y-m-d")."' OR leaves.ending_date BETWEEN '".format_date($get['from'], "Y-m-d")."' AND '".format_date($get['too'], "Y-m-d")."' OR (leaves.starting_date <= '".format_date($get['from'], "Y-m-d")."' AND leaves.ending_date >= '".format_date($get['too'], "Y-m-d")."'))";
                // $conditionalDates = ", IF(leaves.starting_date < '".format_date($get['from'], "Y-m-d")."', '".format_date($get['from'], "Y-m-d")."', leaves.starting_date) AS starting_date, 
                // IF(leaves.ending_date > '".format_date($get['too'], "Y-m-d")."', '".format_date($get['too'], "Y-m-d")."', leaves.ending_date) AS ending_date";
            }
            break;
    }
    
    $leftjoin = "LEFT JOIN users ON attendance.user_id = users.employee_id";
    
    $query = $this->db->query("SELECT attendance.*, CONCAT(users.first_name, ' ', users.last_name) AS user FROM attendance " . $leftjoin . $where);
    $results = $query->result_array();

    $join_date_query = $this->db->query("SELECT join_date FROM users WHERE employee_id = $user_id");
    $join_date_result = $join_date_query->row_array();
    $join_date = $join_date_result['join_date'];

    $join_date = date('Y-m-d', strtotime($join_date));

    if (strtotime($globalFromDate) < strtotime($join_date)) {
        $globalFromDate = $join_date;
    }
    
    // Define a custom comparison function
    function compareByFinger($a, $b) {
        return strcmp($a['finger'], $b['finger']);
    }

    // Sort the results array using the custom comparison function
    usort($results, 'compareByFinger');

    
    // Create an array to store the dates
    $datesArray = array();
    $currentDate = new DateTime($globalFromDate);
    $endDate = new DateTime($globalToDate);
    
    // Add all dates between 'fromDateGlobal' and 'toDateGlobal' to the $allDates array
    while ($currentDate <= $endDate) {
        $datesArray[] = $currentDate->format('Y-m-d');
        $currentDate->modify('+1 day');
    }
    
    $final = array();
    
    foreach ($results as $item) {
        $rowDate = date('Y-m-d', strtotime($item['finger']));
        $Fingertime = date('g:i a', strtotime($item['finger']));
        $biometricFlag = stripos($item['note'], 'Biometric') !== false;
    
        foreach ($datesArray as $date) {
            if ($date == $rowDate) {
                $final[] = [
                    "user" => $item["user"],
                    "$date" => $Fingertime,
                    "biometricFlag" => $biometricFlag, // Store the biometric flag
                ];
            }
        }
    }
    
    $result = array();
    
    foreach ($final as $row) {
        $user = $row['user'];
        $date = array_keys($row)[1]; // Assuming the date object is always the second key
        $time = $row[$date];
        $biometricFlag = $row['biometricFlag']; // Get the biometric flag
    
        if (!isset($result[$user])) {
            $result[$user] = [];
        }
    
        if ($biometricFlag) {
            $time .= '<span class="text-info"><strong>(BR)</strong></span>';
        }
    
        if (isset($result[$user][$date])) {
            $result[$user][$date] .= '<br>' . $time;
        } else {
            $result[$user][$date] = $time;
        }
    }
    
    $resultData = array('total' => count($result), 'rows' => array());
    foreach ($result as $user => $dates) {
        foreach ($dates as $date => $time) {
            $resultData['rows'][] = array('user' => $user, $date => $time);
        }
    }
    
    $result = array();
    
    foreach ($resultData['rows'] as $row) {
        $user = $row['user'];
        unset($row['user']);
    
        if (!isset($result[$user])) {
            $result[$user] = $row;
        } else {
            foreach ($row as $date => $time) {
                if (isset($result[$user][$date])) {
                    $result[$user][$date] .= ', ' . $time;
                } else {
                    $result[$user][$date] = $time;
                }
            }
        }
    }
    
    $output = array();
    foreach ($result as $user => $row) {
        $output[] = array_merge(['user' => $user], $row);
    }

    $count = 0;
    $absent = 0;
    $halfDay = 0;
    $late = 0;
    $lateDays = 0;
    $holiday = 0;
    $lateMinutesDates = array();

    $settings_query = $this->db->query("SELECT value FROM settings WHERE type = 'grace_minutes_'");
    $settings_result = $settings_query->row_array();
    $grace_settings = json_decode($settings_result['value'], true);

    $applyValue = $grace_settings['apply'];
    $daysCounter = $grace_settings['days_counter'];
    $graceMinutes = $grace_settings['grace_minutes'];
    
    $where2 .= " AND leaves.status = '1'  AND leaves.paid='0'";

    // Loop through the dates in $datesArray and check if there is a corresponding record in the attendance table
    foreach ($datesArray as $key => $date) {
        $fingerLoopExecuted = false; 

        // Run a query to check if there is a record in the attendance table with the current date
        $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$date'");
        $attendanceResult = $query->result_array();

        if (empty($attendanceResult)) {
            unset($datesArray[$key]);
        }

        $holidayQuery = $this->db->query("SELECT * FROM holiday");
        $holidays = $holidayQuery->result_array();

        foreach ($holidays as $value4) {
            $startDate = $value4["starting_date"];
            $endDate = $value4["ending_date"];
            $apply = $value4["apply"];
            $startDateTimestamp  = strtotime($startDate);
            $endDateTimestamp  = strtotime($endDate);
            $dateToCheckTimestamp  = strtotime($date);
            if($apply == '0' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                if (isset($datesArray[$key])) {
                    unset($datesArray[$key]);
                }
            }elseif ($apply == '1' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                $departments = json_decode($value4["department"]);
                    foreach ($departments as $department) {
                        $user_ids_query = $this->db->query("SELECT * FROM users WHERE department = $department AND employee_id= $user_id");
                        $user_ids_result = $user_ids_query->result_array();
                        if (count($user_ids_result)>0) {
                            if (isset($datesArray[$key])) {
                                unset($datesArray[$key]);
                            }
                        }
                    }
            }elseif($apply == '2' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                $holidayUsers = json_decode($value4["users"]);
                    foreach ($holidayUsers as $holidayUser) {
                        $user_ids_query = $this->db->query("SELECT * FROM users WHERE id = $holidayUser AND employee_id= $user_id");
                        $user_ids_result = $user_ids_query->result_array();
                        if (count($user_ids_result)>0) {
                            if (isset($datesArray[$key])) {
                                unset($datesArray[$key]);
                            }
                        }
                    }
            }
        }

        if (isset($datesArray[$key])) {
            foreach ($output as &$row) {
                $leavesresult = array();
                if (!isset($row[$date])) {
                    $whereDate = " AND ('$date' BETWEEN leaves.starting_date AND leaves.ending_date OR leaves.starting_date = '$date' OR leaves.ending_date = '$date')";
                    $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves " . $where2 . $whereDate);
                    if ($leaveQuery->num_rows() > 0) {
                        $row[$date] = '--';
                        $row[$row[$date]] = '<div class="text-info"><strong>Leave</strong></div>';
                        // Run a query to check if there is a record in the attendance table with the current date
                        $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$date'");
                        $attendanceResult = $query->result_array();
                        if (empty($attendanceResult)) {
                            $absent++;
                        }
                    } else {
                        $row[$date] = '-';
                        $row[$row[$date]] = '<div class="text-danger"><strong>Absent</strong></div>';
                        $absent++;
                    }
                }
                
                else{
                    $row[$row[$date]] = '<div class="text-success-dark"><strong>Present</strong></div>';
                    $whereDate = " AND leaves.starting_date = '$date'";
                    $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves " . $where2 . $whereDate);
                    $leaveExecution = false;
                    if ($leaveQuery->num_rows() > 0) {
                        $leaveRow = $leaveQuery->row();
                        $leaveDuration = $leaveRow->leave_duration;
                        if (strpos($leaveDuration, 'Half') !== false) {
                            $row[$row[$date]] = '<div class="text-info"><strong>HD<br>Leave</strong></div>';
                            $leaveExecution = true;
                        } elseif (strpos($leaveDuration, 'Short') !== false) {
                            $row[$row[$date]] = '<div class="text-info"><strong>Short<br>Leave</strong></div>';
                            $leaveExecution = true;
                        } else {
                            $row[$row[$date]] = '<div class="text-success-dark"><strong>Present</strong></div>';
                        }
                    } 

                    $user_ids_query = $this->db->query("SELECT * FROM users WHERE employee_id = $user_id");
                
                    if ($user_ids_query) {
                        $user_ids_result = $user_ids_query->row_array();
                        $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '1';
                    }
    
                    $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
                    if ($shift_query) {
                        $shift_result = $shift_query->row_array();
    
                        $half_day_check_in_str = isset($shift_result['half_day_check_in']) ? $shift_result['half_day_check_in'] : '11:00:00';
                        $half_day_check_in = strtotime($half_day_check_in_str);
    
                        $half_day_check_out_str = isset($shift_result['half_day_check_out']) ? $shift_result['half_day_check_out'] : '18:00:00';
                        $half_day_check_out = strtotime($half_day_check_out_str);
    
                        $starting_time_str = isset($shift_result['starting_time']) ? $shift_result['starting_time'] : '09:00:00';
                        $starting_time = strtotime($starting_time_str); 
    
                        $ending_time_str = isset($shift_result['ending_time']) ? $shift_result['ending_time'] : '18:00:00';
                        $ending_time = strtotime($ending_time_str); 
    
                        // Extract the time entries from $row[$date]
                        $time_entries = explode('<br>', $row[$date]);
    
                        // Convert AM/PM time to 24-hour format and store in an array
                        $check_in_times = [];
                        foreach ($time_entries as $time_entry) {
                            $time_entry = str_replace('<span class="text-info"><strong>(BR)</strong></span>', '', $time_entry);
                            $check_in_time = strtotime($time_entry);
                            $check_in_times[] = $check_in_time;
                        }
                        $halfDayExecution = false;
    
                        // Check the number of time entries
                        $num_time_entries = count($check_in_times);
                        $total_minutes = 0;
    
                        if ($num_time_entries > 1) {
                            // Get the first and last time entries
                            $first_check_in_time = min($check_in_times);
                            $last_check_in_time = max($check_in_times);
                            
                            if(($first_check_in_time > $half_day_check_in || $last_check_in_time < $half_day_check_out) && !$leaveExecution){
                                $row[$row[$date]] = '<div class="text-danger"><strong>HD</strong></div>';
                                $halfDay++;
                                $halfDayExecution = true;
                            }
                            elseif($ending_time != $half_day_check_out && $last_check_in_time < $ending_time ){
                                $late_seconds = $ending_time - $last_check_in_time;
                                $total_late_minutes = floor($late_seconds / 60); // Calculate the total minutes late (rounding down)
                                $late += $total_late_minutes;
                                $total_minutes += $total_late_minutes;
                                if(!$leaveExecution){
                                    $row[$row[$date]] = '<div class="text-warning"><strong>' . $total_minutes . ' min<br> Late</strong></div>';
                                }else{
                                    $row[$row[$date]] .= '<div class="text-warning"><strong>' . $total_minutes . ' min<br> Late</strong></div>';
                                }
                            }
                            elseif ($first_check_in_time > $starting_time && $first_check_in_time < $half_day_check_in ) { 
                                $late_seconds = $first_check_in_time - $starting_time;
                                $total_late_minutes = floor($late_seconds / 60); // Calculate the total minutes late (rounding down)
                                $late += $total_late_minutes;
                                $total_minutes += $total_late_minutes;
                                if(!$leaveExecution){
                                    $row[$row[$date]] = '<div class="text-warning"><strong>' . $total_minutes . ' min<br> Late</strong></div>';
                                }else{
                                    $row[$row[$date]] = '<div class="text-warning"><strong>' . $total_minutes . ' min<br> Late</strong></div>'.$row[$row[$date]];
                                }
                            }
                            elseif($last_check_in_time < $half_day_check_out && !$halfDayExecution && !$leaveExecution) { // If shift ending time is later than the last check-in time
                                $row[$row[$date]] = '<div class="text-danger"><strong>HD</strong></div>';
                                $halfDay++;
                                $halfDayExecution = true;
                            }
                            
                            if($total_minutes > $graceMinutes){
                                $lateDays ++;
                                $lateMinutesArray[] = $total_minutes;
                                $lateMinutesDates[] = $date;
                            }
                        }
                        else {
                            if($date !== date('Y-m-d', strtotime('today'))){
                                $temp = $row[$row[$date]];
                                $row[$date] .= '<br><div class="text-info"><strong>Biometric<br>Missing</strong></div>';
                                $row[$row[$date]] = $temp;
                            }
                            // If there is only one time entry, check if it is later than the shift starting time
                            $check_in_time = $check_in_times[0];
                            if ($check_in_time > $half_day_check_in && !$halfDayExecution) {
                                $row[$row[$date]] = '<div class="text-danger"><strong>HD</strong></div>';
                                $halfDay++;
                                $halfDayExecution = true;
                            }
                            elseif ($check_in_time > $starting_time) {
                                $late_seconds = $check_in_time - $starting_time;
                                $total_late_minutes = floor($late_seconds / 60); // Calculate the total minutes late (rounding down)
                                $late += $total_late_minutes;
                                if($total_late_minutes > $graceMinutes){
                                    $lateDays ++;
                                    $lateMinutesArray[] = $total_minutes;
                                    $lateMinutesDates[] = $date;
                                }
                                $row[$row[$date]] = '<div class="text-warning"><strong>' . $total_late_minutes . ' min<br> Late</strong></div>';
                            }
                        }
                    }
                }
                $fingerLoopExecuted = true;
            }
            if (!$fingerLoopExecuted) {
                $userName = ''; // Initialize an empty variable to store the user's name
                $query = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS user_name FROM users WHERE employee_id = ".$get['user_id']);
                $result = $query->row_array();
                if ($result) {
                    $userName = $result['user_name']; // Get the user's name from the query result
                }
     
                $whereDate = " AND ('$date' BETWEEN leaves.starting_date AND leaves.ending_date OR leaves.starting_date = '$date' OR leaves.ending_date = '$date')";
                $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves " . $where2 . $whereDate);
                if ($leaveQuery->num_rows() > 0) {
                    $output[] = [
                    "user" => $userName,
                    "$date" => '--',
                    "--" => '<div class="text-info"><strong>Leave</strong></div>',
                ];
                }
                else{
                    $output[] = [
                        "user" => $userName,
                        "$date" => '-',
                        "-" => '<div class="text-danger"><strong>Absent</strong></div>',
                    ];
                    $absent++;
                }
            }
        }
    }

    $totalGraceMinutes = 0;


    $graceDays = 0;
    if($lateDays >= $daysCounter && $applyValue == '1'){
        $graceDays = 1;
        for ($i = 0; $i < $daysCounter; $i++) {
            if (isset($lateMinutesArray[$i])) {
                $totalGraceMinutes += $lateMinutesArray[$i];
            }
        }
        $loopTimes = $lateDays - $daysCounter;
        $j = $lateDays - 1; // Adjust index to zero-based
        for ($i = 0; $i < $loopTimes; $i++) {
            if (isset($lateMinutesDates[$j])) {
                unset($lateMinutesDates[$j]);
            }
            $j--;
            if ($j < 0) {
                break; // Ensure we don't go beyond the array boundary
            }
        }
    }
    
    if($applyValue == '0'){
        unset($lateMinutesDates);
    }
    
    // If you want to reset the keys of the array after removing the dates, you can use array_values function
    $datesArray = array_values($datesArray);

    $monthsArray = array();

    // Loop through the datesArray to extract unique months and years
    foreach ($datesArray as $date) {
        $monthYear = date('M Y', strtotime($date));
        if (!in_array($monthYear, $monthsArray)) {
            $monthsArray[] = $monthYear;
        }
    }
    $halfDay = $halfDay + $graceDays;
    $late = $late - $totalGraceMinutes;
    
    $arr = [
        'total' => count($output),
        'rows' => $output,
        'month' => $monthsArray,
        'headings' => $datesArray,
        'total_count' => count($datesArray),
        'graceDates' => $lateMinutesDates,
        'graceDays' => $graceDays,
        'apl' => $absent . '/<br>' . $halfDay . '/<br>' . $late.' mins',
    ];
    
    $outputJson =$arr;
    return $outputJson;
}

function get_attendance_report4(){ 
    $offset = 0;
    $limit = 10;
    $sort = 'a.id';
    $order = 'ASC';
    $get = $this->input->post();
    
    $user_id = $this->uri->segment($this->uri->total_segments());

    // Check if the user is an admin
    if ($this->ion_auth->is_admin() || permissions('attendance_view_all')) {
        if (isset($user_id) && !empty($user_id)) {
            $where = " WHERE attendance.user_id = " . $user_id;
            $where2 = " WHERE leaves.employee_id = ".$user_id;
        }else {
            $where = " WHERE attendance.id IS NOT NULL ";
            $where2 = " WHERE leaves.employee_id = ".$user_id;
        }
    } else {
        $this->db->select('employee_id');
        $this->db->from('users');
        $this->db->where('id', $this->session->userdata('user_id'));
        $query = $this->db->get();
        $result = $query->row_array();
        $employee_id = $result['employee_id'];
        $where = " WHERE attendance.user_id = " . $employee_id;
        $where2 = " WHERE leaves.employee_id = ".$employee_id;
    }
    
    // Strip HTML tags and set values for sort, offset, limit, and order if provided
    if (isset($get['sort']))
        $sort = strip_tags($get['sort']);
    if (isset($get['offset']))
        $offset = strip_tags($get['offset']);
    if (isset($get['limit']))
        $limit = strip_tags($get['limit']);
    if (isset($get['order']))
        $order = strip_tags($get['order']);
    
    // Check if search term is provided and construct the search condition
    if (isset($get['search']) && !empty($get['search'])) {
        $search = strip_tags($get['search']);
        $where .= " AND (attendance.id LIKE '%" . $search . "%' OR users.first_name LIKE '%" . $search . "%' OR users.last_name LIKE '%" . $search . "%' OR attendance.check_in LIKE '%" . $search . "%' OR attendance.check_out LIKE '%" . $search . "%' OR attendance.note LIKE '%" . $search . "%')";
    }
    
    $currentDate = date('Y-m-d');
    $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
    $globalFromDate = $firstDayOfMonth; 
    $globalToDate = $currentDate;
    
    $leftjoin = "LEFT JOIN users ON attendance.user_id = users.employee_id";
    
    $query = $this->db->query("SELECT attendance.*, CONCAT(users.first_name, ' ', users.last_name) AS user FROM attendance " . $leftjoin . $where);
    $results = $query->result_array();

    $join_date_query = $this->db->query("SELECT join_date FROM users WHERE employee_id = $user_id");
    $join_date_result = $join_date_query->row_array();
    $join_date = $join_date_result['join_date'];

    $join_date = date('Y-m-d', strtotime($join_date));

    if (strtotime($globalFromDate) < strtotime($join_date)) {
        $globalFromDate = $join_date;
    }
    
    // Define a custom comparison function
    function compareByFinger($a, $b) {
        return strcmp($a['finger'], $b['finger']);
    }

    // Sort the results array using the custom comparison function
    usort($results, 'compareByFinger');
    
    // Create an array to store the dates
    $datesArray = array();
    $currentDate = new DateTime($globalFromDate);
    $endDate = new DateTime($globalToDate);
    
    // Add all dates between 'fromDateGlobal' and 'toDateGlobal' to the $allDates array
    while ($currentDate <= $endDate) {
        $datesArray[] = $currentDate->format('Y-m-d');
        $currentDate->modify('+1 day');
    }
    
    $final = array();
    
    foreach ($results as $item) {
        $rowDate = date('Y-m-d', strtotime($item['finger']));
        $Fingertime = date('g:i a', strtotime($item['finger']));
        $biometricFlag = stripos($item['note'], 'Biometric') !== false;
    
        foreach ($datesArray as $date) {
            if ($date == $rowDate) {
                $final[] = [
                    "user" => $item["user"],
                    "$date" => $Fingertime,
                    "biometricFlag" => $biometricFlag, // Store the biometric flag
                ];
            }
        }
    }
    
    $result = array();
    
    foreach ($final as $row) {
        $user = $row['user'];
        $date = array_keys($row)[1]; // Assuming the date object is always the second key
        $time = $row[$date];
        $biometricFlag = $row['biometricFlag']; // Get the biometric flag
    
        if (!isset($result[$user])) {
            $result[$user] = [];
        }
    
        if ($biometricFlag) {
            $time .= '(BR)';
        }
    
        if (isset($result[$user][$date])) {
            $result[$user][$date] .= '<br>' . $time;
        } else {
            $result[$user][$date] = $time;
        }
    }
    
    $resultData = array('total' => count($result), 'rows' => array());
    foreach ($result as $user => $dates) {
        foreach ($dates as $date => $time) {
            $resultData['rows'][] = array('user' => $user, $date => $time);
        }
    }
    
    $result = array();
    
    foreach ($resultData['rows'] as $row) {
        $user = $row['user'];
        unset($row['user']);
    
        if (!isset($result[$user])) {
            $result[$user] = $row;
        } else {
            foreach ($row as $date => $time) {
                if (isset($result[$user][$date])) {
                    $result[$user][$date] .= ', ' . $time;
                } else {
                    $result[$user][$date] = $time;
                }
            }
        }
    }
    
    $output = array();
    foreach ($result as $user => $row) {
        $output[] = array_merge(['user' => $user], $row);
    }

    $count = 0;
    $absent = 0;
    $halfDay = 0;
    $late = 0;
    $lateDays = 0;
    $holiday = 0;
    $lateMinutesDates = array();

    $settings_query = $this->db->query("SELECT value FROM settings WHERE type = 'grace_minutes_'");
    $settings_result = $settings_query->row_array();
    $grace_settings = json_decode($settings_result['value'], true);

    $applyValue = $grace_settings['apply'];
    $daysCounter = $grace_settings['days_counter'];
    $graceMinutes = $grace_settings['grace_minutes'];
    
    $where2 .= " AND leaves.status = '1' ";

    // Loop through the dates in $datesArray and check if there is a corresponding record in the attendance table
    foreach ($datesArray as $key => $date) {
        $fingerLoopExecuted = false; 

        // Run a query to check if there is a record in the attendance table with the current date
        $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$date'");
        $attendanceResult = $query->result_array();

        if (empty($attendanceResult)) {
            unset($datesArray[$key]);
        }

        $holidayQuery = $this->db->query("SELECT * FROM holiday");
        $holidays = $holidayQuery->result_array();

        foreach ($holidays as $value4) {
            $startDate = $value4["starting_date"];
            $endDate = $value4["ending_date"];
            $apply = $value4["apply"];
            $startDateTimestamp  = strtotime($startDate);
            $endDateTimestamp  = strtotime($endDate);
            $dateToCheckTimestamp  = strtotime($date);
            if($apply == '0' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                if (isset($datesArray[$key])) {
                    unset($datesArray[$key]);
                }
            }elseif ($apply == '1' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                $departments = json_decode($value4["department"]);
                    foreach ($departments as $department) {
                        $user_ids_query = $this->db->query("SELECT * FROM users WHERE department = $department AND employee_id= $user_id");
                        $user_ids_result = $user_ids_query->result_array();
                        if (count($user_ids_result)>0) {
                            if (isset($datesArray[$key])) {
                                unset($datesArray[$key]);
                            }
                        }
                    }
            }elseif($apply == '2' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                $holidayUsers = json_decode($value4["users"]);
                    foreach ($holidayUsers as $holidayUser) {
                        $user_ids_query = $this->db->query("SELECT * FROM users WHERE id = $holidayUser AND employee_id= $user_id");
                        $user_ids_result = $user_ids_query->result_array();
                        if (count($user_ids_result)>0) {
                            if (isset($datesArray[$key])) {
                                unset($datesArray[$key]);
                            }
                        }
                    }
            }
        }

        if (isset($datesArray[$key])) {
            foreach ($output as &$row) {
                $leavesresult = array();
                if (!isset($row[$date])) {
                    $whereDate = " AND ('$date' BETWEEN leaves.starting_date AND leaves.ending_date OR leaves.starting_date = '$date' OR leaves.ending_date = '$date')";
                    $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves " . $where2 . $whereDate);
                    if ($leaveQuery->num_rows() > 0) {
                        $row[$date] = '--';
                        $row[$row[$date]] = '<div class="text-info"><strong>Leave</strong></div>';
                        // Run a query to check if there is a record in the attendance table with the current date
                        $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$date'");
                        $attendanceResult = $query->result_array();
                        if (empty($attendanceResult)) {
                            $absent++;
                        }
                    } else {
                        $row[$date] = '-';
                        $row[$row[$date]] = '<div class="text-danger"><strong>Absent</strong></div>';
                        $absent++;
                    }
                }
                else{
                    $whereDate = " AND leaves.starting_date = '$date'";
                    $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves " . $where2 . $whereDate);
                    if ($leaveQuery->num_rows() > 0) {
                        $leaveRow = $leaveQuery->row();
                        $leaveDuration = $leaveRow->leave_duration;
                        if (strpos($leaveDuration, 'Half') !== false) {
                            $row[$row[$date]] = '<div class="text-info"><strong>HD<br>Leave</strong></div>';
                        } elseif (strpos($leaveDuration, 'Short') !== false) {
                            $row[$row[$date]] = '<div class="text-info"><strong>Short<br>Leave</strong></div>';
                        } else {
                            $row[$row[$date]] = '<div class="text-success-dark"><strong>Present</strong></div>';
                        }
                    } else {
                        $row[$row[$date]] = '<div class="text-success-dark"><strong>Present</strong></div>';

                        $user_ids_query = $this->db->query("SELECT * FROM users WHERE employee_id = $user_id");
                    
                        if ($user_ids_query) {
                            $user_ids_result = $user_ids_query->row_array();
                            $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '1';
                        }
        
                        $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
                        if ($shift_query) {
                            $shift_result = $shift_query->row_array();
        
                            $half_day_check_in_str = isset($shift_result['half_day_check_in']) ? $shift_result['half_day_check_in'] : '11:00:00';
                            $half_day_check_in = strtotime($half_day_check_in_str);
        
                            $half_day_check_out_str = isset($shift_result['half_day_check_out']) ? $shift_result['half_day_check_out'] : '18:00:00';
                            $half_day_check_out = strtotime($half_day_check_out_str);
        
                            $starting_time_str = isset($shift_result['starting_time']) ? $shift_result['starting_time'] : '09:00:00';
                            $starting_time = strtotime($starting_time_str); 
        
                            $ending_time_str = isset($shift_result['ending_time']) ? $shift_result['ending_time'] : '18:00:00';
                            $ending_time = strtotime($ending_time_str); 
        
                            // Extract the time entries from $row[$date]
                            $time_entries = explode('<br>', $row[$date]);
        
                            // Convert AM/PM time to 24-hour format and store in an array
                            $check_in_times = [];
                            foreach ($time_entries as $time_entry) {
                                $time_entry = str_replace('(BR)', '', $time_entry);
                                $check_in_time = strtotime($time_entry);
                                $check_in_times[] = $check_in_time;
                            }
                            $halfDayExecution = false;
        
                            // Check the number of time entries
                            $num_time_entries = count($check_in_times);
                            $total_minutes = 0;
        
                            if ($num_time_entries > 1) {
                                // Get the first and last time entries
                                $first_check_in_time = min($check_in_times);
                                $last_check_in_time = max($check_in_times);
                                
                                if($first_check_in_time > $half_day_check_in || $last_check_in_time < $half_day_check_out){
                                    $row[$row[$date]] = '<div class="text-danger"><strong>HD</strong></div>';
                                    $halfDay++;
                                    $halfDayExecution = true;
                                }
                                elseif($ending_time != $half_day_check_out && $last_check_in_time < $ending_time){
                                    $late_seconds = $ending_time - $last_check_in_time;
                                    $total_late_minutes = floor($late_seconds / 60); // Calculate the total minutes late (rounding down)
                                    $late += $total_late_minutes;
                                    $total_minutes += $total_late_minutes;
                                    $row[$row[$date]] = '<div class="text-warning"><strong>' . $total_minutes . ' min<br> Late</strong></div>';
                                }
                                elseif ($first_check_in_time > $starting_time && $first_check_in_time < $half_day_check_in) { 
                                    $late_seconds = $first_check_in_time - $starting_time;
                                    $total_late_minutes = floor($late_seconds / 60); // Calculate the total minutes late (rounding down)
                                    $late += $total_late_minutes;
                                    $total_minutes += $total_late_minutes;
                                    $row[$row[$date]] = '<div class="text-warning"><strong>' . $total_minutes . ' min<br> Late</strong></div>';
                                }
                                elseif($last_check_in_time < $half_day_check_out && !$halfDayExecution) { // If shift ending time is later than the last check-in time
                                    $row[$row[$date]] = '<div class="text-danger"><strong>HD</strong></div>';
                                    $halfDay++;
                                    $halfDayExecution = true;
                                }
                                
                                if($total_minutes > $graceMinutes){
                                    $lateDays ++;
                                    $lateMinutesArray[] = $total_minutes;
                                    $lateMinutesDates[] = $date;
                                }
                            }
                            else {
                                if($date !== date('Y-m-d', strtotime('today'))){
                                    $temp = $row[$row[$date]];
                                    $row[$date] .= '<br>Biometric Missing';
                                    $row[$row[$date]] = $temp;
                                }
                                // If there is only one time entry, check if it is later than the shift starting time
                                $check_in_time = $check_in_times[0];
                                if ($check_in_time > $half_day_check_in && !$halfDayExecution) {
                                    $row[$row[$date]] = '<div class="text-danger"><strong>HD</strong></div>';
                                    $halfDay++;
                                    $halfDayExecution = true;
                                }
                                elseif ($check_in_time > $starting_time) {
                                    $late_seconds = $check_in_time - $starting_time;
                                    $total_late_minutes = floor($late_seconds / 60); // Calculate the total minutes late (rounding down)
                                    $late += $total_late_minutes;
                                    if($total_late_minutes > $graceMinutes){
                                        $lateDays ++;
                                        $lateMinutesArray[] = $total_minutes;
                                        $lateMinutesDates[] = $date;
                                    }
                                    $row[$row[$date]] = '<div class="text-warning"><strong>' . $total_late_minutes . ' min<br> Late</strong></div>';
                                }
                            }
                        }
                    }
                }
                $fingerLoopExecuted = true;
            }
            if (!$fingerLoopExecuted) {
                $userName = ''; // Initialize an empty variable to store the user's name
                $query = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS user_name FROM users WHERE employee_id = ".$get['user_id']);
                $result = $query->row_array();
                if ($result) {
                    $userName = $result['user_name']; // Get the user's name from the query result
                }
     
                $whereDate = " AND ('$date' BETWEEN leaves.starting_date AND leaves.ending_date OR leaves.starting_date = '$date' OR leaves.ending_date = '$date')";
                $leaveQuery = $this->db->query("SELECT leaves.* FROM leaves " . $where2 . $whereDate);
                if ($leaveQuery->num_rows() > 0) {
                    $output[] = [
                    "user" => $userName,
                    "$date" => '--',
                    "--" => '<div class="text-info"><strong>Leave</strong></div>',
                ];
                }
                else{
                    $output[] = [
                        "user" => $userName,
                        "$date" => '-',
                        "-" => '<div class="text-danger"><strong>Absent</strong></div>',
                    ];
                    $absent++;
                }
            }
        }
    }

    $totalGraceMinutes = 0;

    $graceDays = 0;
    if($lateDays >= $daysCounter && $applyValue == '1'){
        $graceDays = 1;
        for ($i = 0; $i < $daysCounter; $i++) {
            if (isset($lateMinutesArray[$i])) {
                $totalGraceMinutes += $lateMinutesArray[$i];
            }
        }
        $loopTimes = $lateDays - $daysCounter;
        $j = $lateDays - 1; // Adjust index to zero-based
        for ($i = 0; $i < $loopTimes; $i++) {
            if (isset($lateMinutesDates[$j])) {
                unset($lateMinutesDates[$j]);
            }
            $j--;
            if ($j < 0) {
                break; // Ensure we don't go beyond the array boundary
            }
        }
    }
    
    if($applyValue == '0'){
        unset($lateMinutesDates);
    }

    // If you want to reset the keys of the array after removing the dates, you can use array_values function
    $datesArray = array_values($datesArray);

    $monthsArray = array();

    // Loop through the datesArray to extract unique months and years
    foreach ($datesArray as $date) {
        $monthYear = date('F Y', strtotime($date));
        if (!in_array($monthYear, $monthsArray)) {
            $monthsArray[] = $monthYear;
        }
    }
    $halfDay = $halfDay + $graceDays;
    $late = $late - $totalGraceMinutes;
    
    $arr = [
        'total' => count($output),
        'rows' => $output,
        'month' => $monthsArray,
        'headings' => $datesArray,
        'graceDates' => $lateMinutesDates,
        'graceDays' => $graceDays,
        'apl' => $absent . '/<br>' . $halfDay . '/<br>' . $late.' mins',
    ];
    
    $outputJson =$arr;
    return $outputJson;
    
}


function get_users_by_department($department,$active){
    if ($department == '' && $active == 1) {
        $query = $this->db->query("SELECT * FROM users WHERE active = '1' AND finger_config = '1'");
    }if ($department == '' && $active == 2) {
        $query = $this->db->query("SELECT * FROM users WHERE active = '0' AND finger_config = '1'");
    }
    if ($department != '' && $active == 1) {
       $query = $this->db->query("SELECT * FROM users WHERE active = '1' AND department= '$department' AND finger_config = '1'");
    }if ($department != '' && $active == 2) {
        $query = $this->db->query("SELECT * FROM users WHERE active = '0' AND department= '$department' AND finger_config = '1'");
    }
    if ($department != '' && $active == 3) {
        $query = $this->db->query("SELECT * FROM users WHERE finger_config = '1' AND department= '$department'");
    }
    if ($department == '' && $active == 3) {
        $query = $this->db->query("SELECT * FROM users WHERE finger_config = '1' AND department= '$department'");
    }
    $results = $query->result_array();
    return $results;
}

function get_users_by_shifts($shifts_ids,$active){
    if ($shifts_ids == '' && $active == 1) {
        $query = $this->db->query("SELECT * FROM users WHERE active = '1' AND finger_config = '1'");
    }if ($shifts_ids == '' && $active == 2) {
        $query = $this->db->query("SELECT * FROM users WHERE active = '0' AND finger_config = '1'");
    }
    if ($shifts_ids != '' && $active == 1) {
       $query = $this->db->query("SELECT * FROM users WHERE active = '1' AND shift_id= '$shifts_ids' AND finger_config = '1'");
    }if ($shifts_ids != '' && $active == 2) {
        $query = $this->db->query("SELECT * FROM users WHERE active = '0' AND shift_id= '$shifts_ids' AND finger_config = '1'");
    }
    if ($shifts_ids != '' && $active == 3) {
        $query = $this->db->query("SELECT * FROM users WHERE finger_config = '1' AND shift_id= '$shifts_ids'");
    }
    if ($shifts_ids == '' && $active == 3) {
        $query = $this->db->query("SELECT * FROM users WHERE finger_config = '1' AND shift_id= '$shifts_ids'");
    }
    $results = $query->result_array();
    return $results;
}
function get_filter_page($get){
    
    $user_id = 0;
    if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
        if(isset($get['user_id']) && !empty($get['user_id'])){
            $where = " WHERE attendance.user_id = ".$get['user_id'];
            $where2 = " WHERE leaves.employee_id = ".$get['user_id'];
            $user_id = $get['user_id'];
        }else{
            $where = " WHERE attendance.user_id IS NOT NULL ";
            $where2 = " WHERE leaves.id IS NOT NULL ";
        }
    }else{
        $query2 = $this->db->query("SELECT * FROM users");
        $results2 = $query2->result_array();
        foreach ($results2 as $current_user) {
            if ($current_user["id"] == $this->session->userdata('user_id')) {
            $employee_id=$current_user["employee_id"];
            $where = " WHERE attendance.user_id = ".$employee_id;
            $where2 = " WHERE leaves.employee_id = ".$employee_id;
            $user_id = $employee_id;
            }
        }
    }

    // Check if 'from' and 'too' dates are provided and add date condition
    if (isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
        $where .= " AND DATE(attendance.finger) BETWEEN '".format_date($get['from'], "Y-m-d")."' AND '".format_date($get['too'], "Y-m-d")."' ";
        $where2 .= " AND leaves.starting_date >= '".format_date($get['from'], "Y-m-d")."' AND leaves.ending_date <= '".format_date($get['too'], "Y-m-d")."'";
    }else{
        $where .= " AND DATE(attendance.finger) BETWEEN '".format_date($get['from'], "Y-m-d")."' AND '".format_date($get['from'], "Y-m-d")."' ";
        $where2 .= " AND leaves.starting_date <= '".format_date($get['from'], "Y-m-d")."' AND leaves.ending_date >= '".format_date($get['from'], "Y-m-d")."'";
    }
    $leftjoin = "LEFT JOIN users ON attendance.user_id = users.employee_id";
    $leftjoin2 = "LEFT JOIN users ON leaves.employee_id = users.employee_id";

    $query = $this->db->query("SELECT attendance.*, CONCAT(users.first_name, ' ', users.last_name) AS user
    FROM attendance ".$leftjoin.$where." AND users.active = 1 and finger_config = 1");
    $results = $query->result_array();
    
    $where2 .= " AND users.active= '1' AND users.finger_config='1'";
    $leaveQuery = $this->db->query("SELECT leaves.*, CONCAT(users.first_name, ' ', users.last_name) AS user  FROM leaves ".$leftjoin2.$where2." AND leaves.leave_duration NOT LIKE '%Half%' AND leaves.leave_duration NOT LIKE '%Short%'");
    $leavesresult = $leaveQuery->result_array();

    // Sort the data by user and created
    usort($results, function($a, $b) {
        if ($a['user'] === $b['user']) {
            return strcmp($a['created'], $b['created']);
        }
        return strcmp($a['user'], $b['user']);
    });$groupedData = [];
    foreach ($results as $item) {
        $user = $item['user_id'];
        $fingerDate = date('Y-m-d', strtotime($item['finger']));
        
        if (!isset($groupedData[$user])) {
            $groupedData[$user] = [];
        }
        
        if (!isset($groupedData[$user][$fingerDate])) {
            $groupedData[$user][$fingerDate] = [];
        }
        
        $groupedData[$user][$fingerDate][] = $item;
    }
    $row = [];
    // Print the grouped data
    foreach ($groupedData as $user => $fingerData) {
        $array = [];
        foreach ($fingerData as $fingerDate => $items) {
            $finger = [];
            foreach ($items as $item) {
                $finger[] = $item['finger'];
            }
            $array[] = [
                'user_id' => $user,
                'user' => $items[0]['user'], // Include user_name from the first item
                'fingers' => $finger
            ];
        }
        $row[] = $array;
    }

    $checkInOutData = [];
    foreach ($row as $objects) {
        foreach ($objects as $obj) {
            $userId = $obj['user_id'];
            $fingers = $obj['fingers'];
            $userName = $obj['user']; // Assign user_name to a variable
            $checkIn = format_date($fingers[0], system_date_format()." ".system_time_format());
            $checkOut = format_date(end($fingers), system_date_format()." ".system_time_format());
            $checkInOutData[] = [
                'user_id' => $userId,
                'user' => $userName, 
                'check_in' => $checkIn,
                'check_out' => $checkOut,
            ];
        }
    }
        
    // Custom comparison function for sorting based on "check_in" values
    function sortByCheckInDesc($a, $b) {
        return strtotime($b['check_in']) - strtotime($a['check_in']);
    }

    $query2 = $this->db->query("SELECT * FROM users WHERE active = '1' AND finger_config='1'");
    $results2 = $query2->result_array();
    $total_staff = $query2->num_rows();
    $holiday = 0;
    $present=0;
    $halfDayCounter=0;
    $absent=0;
    $late=0;
    $late_min=0;
    $leaves=0;
    $leaves_pending=0;
    $leaves_rejected=0;
    $short=0;
    $half=0;
    $allHolidayExecution = false;
    
    if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
        foreach($results2 as $row){
            $currentDate = strtotime($get['from']);
            $endDate = strtotime($get['from']);
            $datesArray=[];
            while ($currentDate <= $endDate) {
                $datesArray[] = date('Y-m-d', $currentDate);
                $currentDate = strtotime('+1 day', $currentDate);
            }
            $user_id=$row["employee_id"];
    
            $join_date_query = $this->db->query("SELECT join_date FROM users WHERE employee_id = $user_id");
            $join_date_result = $join_date_query->row_array();
            $join_date = $join_date_result['join_date'];
            $globalFromDate = $get['from'];
            $join_date = date('d M Y', strtotime($join_date));
            if (strtotime($globalFromDate) >= strtotime($join_date)) {
                // Loop through the dates in $datesArray and check if there is a corresponding record in the attendance table
                foreach ($datesArray as $key => $date) {
                    // Run a query to check if there is a record in the attendance table with the current date
                    $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$date'");
                    $attendanceResult = $query->result_array();
                    if (empty($attendanceResult)) {
                        unset($datesArray[$key]);
                        $holiday++;
                        $allHolidayExecution = true;
                    }
                    $holidayQuery = $this->db->query("SELECT * FROM holiday");
                    $holidays = $holidayQuery->result_array();

                    foreach ($holidays as $value4) {
                        $startDate = $value4["starting_date"];
                        $endDate = $value4["ending_date"];
                        $apply = $value4["apply"];
                        $startDateTimestamp  = strtotime($startDate);
                        $endDateTimestamp  = strtotime($endDate);
                        $dateToCheckTimestamp  = strtotime($date);
                        if($apply == '0' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                            if (isset($datesArray[$key])) {
                                unset($datesArray[$key]);
                                $holiday++;
                                $allHolidayExecution = true;
                            }
                        }elseif ($apply == '1' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                            $departments = json_decode($value4["department"]);
                            foreach ($departments as $department) {
                                $user_ids_query = $this->db->query("SELECT * FROM users WHERE department = $department AND employee_id= $user_id");
                                $user_ids_result = $user_ids_query->result_array();
                                if (count($user_ids_result)>0) {
                                    if (isset($datesArray[$key])) {
                                        unset($datesArray[$key]);
                                        $holiday++;
                                    }
                                }
                            }
                        }elseif($apply == '2' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                            $holidayUsers = json_decode($value4["users"]);
                            foreach ($holidayUsers as $holidayUser) {
                                $user_ids_query = $this->db->query("SELECT * FROM users WHERE id = $holidayUser AND employee_id= $user_id");
                                $user_ids_result = $user_ids_query->result_array();
                                if (count($user_ids_result)>0) {
                                    if (isset($datesArray[$key])) {
                                        unset($datesArray[$key]);
                                        $holiday++;
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                $total_staff--;
            }
        }
        $userId = 0;
        foreach ($checkInOutData as $value) {
            $checkIn_time = $value["check_in"];
            $checkout_time = $value["check_out"];
            if ($checkout_time == 'N/A') {
                $halfDayCounter++;
            }else{
                $datetime1 = new DateTime($checkIn_time);
                $datetime2 = new DateTime($checkout_time);
                $interval = $datetime1->diff($datetime2);
                $hours = $interval->h;
                if ($hours < 4) {
                    $halfDayCounter++;
                }
            }
            $time = new DateTime($checkIn_time);
            $userId = $value["user_id"];

            $user_ids_query = $this->db->query("SELECT * FROM users WHERE employee_id = $userId");
            
            if ($user_ids_query) {
                $user_ids_result = $user_ids_query->row_array();
                $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '1';
            }

            $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
            if ($shift_query) {
                $shift_result = $shift_query->row_array();
                $starting_time = isset($shift_result['starting_time']) ? $shift_result['starting_time'] : '09:00:00';
                $starting_time = new DateTime($time->format('Y-m-d') . ' ' . $starting_time); // Convert starting_time to DateTime object
            }
            $nineAM = new DateTime($time->format('Y-m-d') . ' 09:00:00');
            if ($time > $starting_time) {
                $late++;
            }

            $present++;
        }
            
        $absent = $total_staff-$present-$holiday;

    }else{
        $join_date_query = $this->db->query("SELECT join_date FROM users WHERE employee_id = $user_id");
        $join_date_result = $join_date_query->row_array();
        $join_date = $join_date_result['join_date'];
        $globalFromDate = $get['from'];
        $join_date = date('d M Y', strtotime($join_date));

        if (strtotime($globalFromDate) < strtotime($join_date)) {
            $globalFromDate = $join_date;
        }

        if (isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
            foreach ($checkInOutData as $value) {
                $checkIn_time = $value["check_in"];
                $checkout_time = $value["check_out"];
                $time = new DateTime($checkIn_time);
                $userId = $value['user_id'];

                $user_ids_query = $this->db->query("SELECT * FROM users WHERE id = $userId");
                
                if ($user_ids_query) {
                    $user_ids_result = $user_ids_query->row_array();
                    $shift_id = isset($user_ids_result['shift_id']) ? $user_ids_result['shift_id'] : '1';
                }

                $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
                if ($shift_query) {
                    $shift_result = $shift_query->row_array();
                    $starting_time = isset($shift_result['starting_time']) ? $shift_result['starting_time'] : '09:00:00';
                    $half_day_check_in = isset($shift_result['half_day_check_in']) ? $shift_result['half_day_check_in'] : '12:00:00';
                    $starting_time = new DateTime($time->format('Y-m-d') . ' ' . $starting_time); // Convert starting_time to DateTime object
                    $half_day_check_in = new DateTime($time->format('Y-m-d') . ' ' . $half_day_check_in); // Convert starting_time to DateTime object
                if ($time > $starting_time && $time > $half_day_check_in) {
                    $late++;
                }elseif ($time > $starting_time) {
                    $late++;
                    $interval = $starting_time->diff($time);
                    $late_min = $late_min+($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i; // Extract minutes from the interval
                }
            }
                $present++;
            }
            $currentDate = strtotime($globalFromDate);
            $endDate = strtotime($get["too"]);
            $datesArray=[];
            while ($currentDate <= $endDate) {
                $datesArray[] = date('Y-m-d', $currentDate);
                $currentDate = strtotime('+1 day', $currentDate);
            }

            // Loop through the dates in $datesArray and check if there is a corresponding record in the attendance table
            foreach ($datesArray as $key => $date) {

                // Run a query to check if there is a record in the attendance table with the current date
                $query = $this->db->query("SELECT * FROM attendance WHERE DATE(finger) = '$date'");
                $attendanceResult = $query->result_array();

                if (empty($attendanceResult)) {
                    unset($datesArray[$key]);
                }

                $holidayQuery = $this->db->query("SELECT * FROM holiday");
                $holidays = $holidayQuery->result_array();

                foreach ($holidays as $value4) {
                    $startDate = $value4["starting_date"];
                    $endDate = $value4["ending_date"];
                    $apply = $value4["apply"];
                    $startDateTimestamp  = strtotime($startDate);
                    $endDateTimestamp  = strtotime($endDate);
                    $dateToCheckTimestamp  = strtotime($date);
                    if($apply == '0' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                        if (isset($datesArray[$key])) {
                            unset($datesArray[$key]);
                        }
                    }elseif ($apply == '1' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                        $departments = json_decode($value4["department"]);
                            foreach ($departments as $department) {
                                $user_ids_query = $this->db->query("SELECT * FROM users WHERE department = $department AND employee_id= $user_id");
                                $user_ids_result = $user_ids_query->result_array();
                                if (count($user_ids_result)>0) {
                                    if (isset($datesArray[$key])) {
                                        unset($datesArray[$key]);
                                    }
                                }
                            }
                    }elseif($apply == '2' && $dateToCheckTimestamp >= $startDateTimestamp && $dateToCheckTimestamp <= $endDateTimestamp){
                        $holidayUsers = json_decode($value4["users"]);
                            foreach ($holidayUsers as $holidayUser) {
                                $user_ids_query = $this->db->query("SELECT * FROM users WHERE id = $holidayUser AND employee_id= $user_id");
                                $user_ids_result = $user_ids_query->result_array();
                                if (count($user_ids_result)>0) {
                                    if (isset($datesArray[$key])) {
                                        unset($datesArray[$key]);
                                    }
                                }
                            }
                    }
                }
            }
            $totalday = count($datesArray);
            $absent = $totalday-$present;
        }
    }

    foreach ($leavesresult as $leave) {
        $startingDate = new DateTime($leave['starting_date']);
        $endingDate = new DateTime($leave['ending_date']);
        $leave_duration = $leave['leave_duration'];
        $status = $leave['status'];
        $paid = $leave['paid'];
        $interval = $startingDate->diff($endingDate);
        $data = $interval->days + 1;
        for ($i=0; $i < $data; $i++) { 
            $currentDate2 = $startingDate->format('d M Y');
            if ($paid == '0' && isset($get['from']) && !empty($get['from']) && $currentDate2 == $get['from']) {
                switch ($status) {
                    case '1':
                            $leaves++;
                            $absent--;
                        break;
                    case '0':
                        $leaves_pending++;
                        break;
                    case '2':
                        $leaves_rejected++;
                        break;
                }
            }
            $startingDate->modify('+1 day');
        }
    }
    
// Biomatric
$total_bio= 0;$bio_pending = 0;$bio_approved = 0; $bio_rejected = 0;
    $bioQuery = $this->db->query("SELECT * FROM biometric_missing");
    $results4 = $bioQuery->result_array();
    if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
        foreach ($results4 as $biometric) {
            if (isset($get['from']) && !empty($get['from'])) {
                // Convert date1 to "Y-m-d" format
                $date1_formatted = date("Y-m-d", strtotime($get['from']));
                $biometric_date = $biometric["date"];
                $biometric_status = $biometric["status"];
                if ($biometric_date == $date1_formatted) {
                    if ($biometric_status == '0') {
                        $bio_pending++;
                    }elseif ($biometric_status == '1') {
                        $bio_approved++;
                    }else{
                        $bio_rejected++;
                    }
                }
            }
        }

    }else{
        foreach ($results4 as $biometric) {
            if (isset($get['from']) && isset($get['too']) && !empty($get['from']) && !empty($get['too'])) {
                $date1_formatted = date("Y-m-d", strtotime($get['from']));
                $date2_formatted = date("Y-m-d", strtotime($get['too']));
                
                $biometric_date = $biometric["date"];
                $biometric_status = $biometric["status"];
                $biometric_user_id = $biometric["user_id"];
                
                if ($biometric_date >= $date1_formatted && $biometric_date <= $date2_formatted && $biometric_user_id == $this->session->userdata("user_id")) {
                    if ($biometric_status == '0') {
                        $bio_pending++;
                    } elseif ($biometric_status == '1') {
                        $bio_approved++;
                    } else {
                        $bio_rejected++;
                    }
                }
            }
        }
        
    }
    $data = [
        'total_staff'=>$total_staff,
        'present'=>$present,
        'absent'=>$absent,
        'late'=>$late,
        'late_min'=>$late_min,
        'halfday'=> $halfDayCounter,
        'leaves'=>$leaves+$short+$half,
        'leaves_array'=>$leavesresult,
        'from'=>$get["from"],
        'too'=>$get["too"],
        'leaves_pending'=>$leaves_pending,
        'leaves_rejected'=>$leaves_rejected,
        'leaves_total'=>$leaves+$leaves_pending+$leaves_rejected+$short+$half,
        'total_bio' => $bio_pending+$bio_approved+$bio_rejected,
        'bio_pending' => $bio_pending,
        'bio_approved' => $bio_approved,
        'bio_rejected' => $bio_rejected,
    ];
    return $data;

}

function get_leaves($get){
if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
    if(isset($get['user_id']) && !empty($get['user_id'])){
        $where2 = " WHERE leaves.employee_id = ".$get['user_id'];
    }else{
        $where2 = " WHERE leaves.id IS NOT NULL ";
    }
}else{
    $query2 = $this->db->query("SELECT * FROM users");
    $results2 = $query2->result_array();
    foreach ($results2 as $current_user) {
        if ($current_user["id"] == $this->session->userdata('user_id')) {
        $employee_id=$current_user["employee_id"];
        $where2 = " WHERE leaves.employee_id = ".$employee_id;
        }
    }
}
    $leftjoin2 = "LEFT JOIN users ON leaves.employee_id = users.employee_id";

    $where2 .= " AND leaves.status = '1' AND users.finger_config='1'";
    $leaveQuery = $this->db->query("SELECT leaves.*, CONCAT(users.first_name, ' ', users.last_name) AS user  FROM leaves ".$leftjoin2.$where2);
    $leavesresult = $leaveQuery->result_array();
    $baseUrl = base_url();

$leaveArray=[];
foreach ($leavesresult as $leave) {
    $startingDate = new DateTime($leave['starting_date']);
    $endingDate = new DateTime($leave['ending_date']);
    $interval = $startingDate->diff($endingDate);
    $data = $interval->days + 1;
    for ($i=0; $i < $data; $i++) { 
        if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
            $currentDate2 = $startingDate->format('d M Y');
            if (isset($get['from']) && !empty($get['from']) && $currentDate2 == $get['from']) {
                $leaveArray[]=[
                    'user_id'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["employee_id"].'</a>',
                    'user'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["user"].'</a>',
                    'check_in'=>'<div class="text-success"><strong>L</strong></div>',
                ];
            }
        }else{
            $currentDate2 = $startingDate->format('Y M d');
            $leaveArray[]=[
                'user_id'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["employee_id"].'</a>',
                'user'=>'<a href="'.$baseUrl.'attendance/user_attendance/'.$leave["employee_id"].'">'.$leave["user"].'</a>',
                'check_in'=> $currentDate2.'<br>'.'<div class="text-success"><strong>L</strong></div>',
            ];
        }
        $startingDate->modify('+1 day');
    }
}
$serialNumber=1;
foreach ($leaveArray as $key => &$value) {
    $value['s.n'] = $serialNumber; // Add the 's.n' key with the serial number value
    $serialNumber++; // Increment the serial number for the next iteration
}
$bulk=[
    "total"=>count($leaveArray),
    "rows"=>$leaveArray
];
return $bulk;
    
 }
 
    function edit($data, $id = '', $user_id = '', $null = ''){
        if(!empty($id)){
            $this->db->where('id', $id);
        }
        if($this->db->update('attendance', $data))
            return true;
        else
            return false;
    }

    function delete($id = '', $user_id = ''){
        if($id){
            $this->db->where('id', $id);
        }

        if($user_id){
            $this->db->where('user_id', $user_id);
        }

        if($id = '' && $user_id = ''){
            return false;
        }

        // $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->delete('attendance'))
            return true;
        else
            return false;
    }
 function connect(){
        $datetime = new DateTime();
        $timezone = new DateTimeZone('Asia/Karachi');
        $datetime->setTimezone($timezone);
        $cdate = $datetime->format('Y-m-d H:i:s');
        $devices = $this->db->query("SELECT * FROM devices");
        $devicesresults = $devices->result_array();
        foreach ($devicesresults as $device) {
            $ip = $device["device_ip"];
            $zk = new ZKLib($ip, 27010);
            try {
                $zk->connect();
                $zk->disableDevice();
                $zk->setTime($cdate); // Synchronize time
                $users = $zk->getUser();
                $attendance = $zk->getAttendance();
                 $zk->clearAttendance();
                $zk->enableDevice();
                $zk->disconnect();
                    foreach ($attendance as $key => $attendances) {
                        $userid = $attendances['id'];
                        $timestamp = $attendances['timestamp'];
                        $query = $this->db->query("SELECT * FROM attendance WHERE user_id = '$userid' AND finger ='$timestamp'");
                        $results = $query->result_array();
                        $query2 = $this->db->query("SELECT * FROM users WHERE employee_id = '$userid'");
                        $result2 = $query2->row();
                        if ($result2) {
                            $id = $result2->id;
                            $email = $result2->email;
                            $name = $result2->first_name.' '.$result2->last_name;
                            $dateTime = new DateTime($timestamp);
                            $time = $dateTime->format("h:i A");
                            $numRows = $query->num_rows();
                            if ($numRows == 0) {
                                $data = [
                                    'user_id'=>$userid,
                                    'employee_id'=>$userid,
                                    'finger'=>$timestamp
                                ];
                                $this->db->insert('attendance', $data);
                                $notification_data = array(
                                    'notification' => 'Your Punch recorded at '.$timestamp,
                                    'type' => 'attendance',	
                                    'type_id' => '11',	
                                    'from_id' => $id,
                                    'to_id' => $id
                                );
                                $notification_id = $this->notifications_model->create($notification_data);
                                
                                $template_data['NAME'] = $name;
                                $template_data['TIME'] = $time;
                                $template_data['DASHBOARD_URL'] = 'https://pms.mobipixels.com';
                                $email_template = render_email_template('biometric', $template_data);
                                send_mail($email, $email_template[0]['subject'], $email_template[0]['message']);
                                
                            }
                        }
                        
                    }
                echo json_encode($attendance);
                } catch (\Exception $e) {
                         echo "Error: " . $e->getMessage() . "\n";
                }
        }
    }

    public function get_user_by_active() {
        // Use CI's Active Record or Query Builder to fetch the user row.
        $query = $this->db->get_where('users', array('active' => 1));

         // Return all the result rows as an array.
         return $query->result();
    }
    
 public function get_count_abs($get) {
        $where = " WHERE 1 "; // Initialize the WHERE clause
        $abs = 0;
        $leaves = 0;
        $present = 0;
        $leave_pending = 0;
        $leave_rejected = 0;
        $leaves_approved = 0;
        $bio_pending = 0;
        $bio_approved = 0;
        $bio_rejected = 0;
        if ($this->ion_auth->is_admin() || permissions('attendance_view_all') || permissions('attendance_view_all')) {
            $currentDate = new DateTime();
            $dateFormat = "Y-m-d";
            $todayDate = $currentDate->format($dateFormat);
            $dateRange = [$todayDate];
            $where .= " AND DATE(attendance.finger) = '" . $todayDate . "' ";
        } else {
            $currentDate = new DateTime();
            $firstDayOfMonth = new DateTime('first day of ' . $currentDate->format('Y-m'));
            $dateFormat = "Y-m-d";
            $todayDate = $currentDate->format($dateFormat);

            $dateRange = [];
            while ($firstDayOfMonth <= $currentDate) {
                $dateRange[] = $firstDayOfMonth->format($dateFormat);
                $firstDayOfMonth->modify('+1 day');
            }
            $query2 = $this->db->query("SELECT * FROM users");
            $results2 = $query2->result_array();
            foreach ($results2 as $current_user) {
                if ($current_user["id"] == $this->session->userdata('user_id')) {
                    $employee_id = $current_user["employee_id"];
                    $where .= " AND attendance.user_id = " . $employee_id;
                }
            }   
        }
    
        $leftjoin = " LEFT JOIN users ON attendance.user_id = users.employee_id";
        $leftjoin2 = "LEFT JOIN users ON leaves.employee_id = users.employee_id";
        $query = $this->db->query("SELECT attendance.*, CONCAT(users.first_name, ' ', users.last_name) AS user 
        FROM attendance " . $leftjoin . $where);
        $results = $query->result_array();
        $queryAbsents = $this->db->query("SELECT * FROM users WHERE finger_config = '1' AND active = '1' ");
        $numRows = $queryAbsents->num_rows();
        $leaveQuery = $this->db->query("SELECT leaves.*, CONCAT(users.first_name, ' ', users.last_name) AS user
        FROM leaves " . $leftjoin2." WHERE leaves.leave_duration NOT LIKE '%Half%' AND leaves.leave_duration NOT LIKE '%Short%'");
        $leaveResult = $leaveQuery->result_array();

        $final = array();
    foreach ($results as $item) {
        $rowDate = date('Y-m-d', strtotime($item['finger']));
        $Fingertime = date('h:iA', strtotime($item['finger']));
        foreach ($dateRange as $date) {
            if ($date == $rowDate) {
                $final[] = [
                    "user" => $item["user"],
                    "$date" => $Fingertime,
                    "id" => $item["user_id"],
                ];
            }
        }
    }
    $result = array();
        foreach ($final as $row) {
            $user = $row['user'];
            $id = $row['id'];
            $date = array_keys($row)[1]; 
            $time = $row[$date];
            if (!isset($result[$user])) {
                $result[$user] = [];
            }
            if (isset($result[$user][$date])) {
                $result[$user][$date] .= '<br>' . $time;
            } else {
                $present++;
                $result[$user][$date] = $time;
            }
            $result[$user]["id"] = $id;
        }
// Leaves
foreach ($leaveResult as $leave) {
    $status = $leave['status'];
    $user_id = $leave['user_id'];
    $type = $leave['type'];
    $startingDate = new DateTime($leave['starting_date']);
    $endingDate = new DateTime($leave['ending_date']);
    $currentDate = new DateTime(); // Current date and time
    $interval = $startingDate->diff($endingDate);
    $data = $interval->days + 1;
    $firstDateOfCurrentMonth = new DateTime('first day of this month');
if ($startingDate >= $firstDateOfCurrentMonth && $endingDate <= $currentDate) {
    for ($i=0; $i < $data; $i++) {
        $currentDate = new DateTime();
        $dateFormat = "Y-m-d";
        $todayDate = $currentDate->format($dateFormat);
        $currentDate3 = $startingDate->format('Y-m-d');
        if ($todayDate == $currentDate3) {
            if ($status == '1') {
                $leaves++;
            }
        }
        $startingDate->modify('+1 day');
    }
    if ($this->ion_auth->is_admin() || permissions('attendance_view_all') || permissions('attendance_view_all')) {
        if ($status == '1') {
            $leaves_approved++;
        }elseif ($status == '2') {
            $leave_rejected++;
        }else{
            $leave_pending++;
        }
    }else{
        if ($user_id == $this->session->userdata('user_id')) {
            if ($status == '1') {
                $leaves_approved++;
            }elseif ($status == '2') {
                $leave_rejected++;
            }else{
                $leave_pending++;
            }
        }
    }

}
}
// Current date
$currentDate = date("Y-m-d");

// Construct the first date of the current month
$firstDateOfCurrentMonth = date("Y-m-01");

// biometric Requests
$BioQuery = $this->db->query("SELECT * FROM biometric_missing WHERE date >= '$firstDateOfCurrentMonth' AND date <= '$currentDate'");
$BioResult = $BioQuery->result_array();
foreach ($BioResult as $BioRequest) {
    $Biodate = $BioRequest["date"];
    $Biostatus=$BioRequest["status"];
    $Biouser=$BioRequest["user_id"];
    if($this->ion_auth->is_admin() || permissions('attendance_view_all')){
        if ($Biostatus == '0') {
            $bio_pending++;
        }elseif ($Biostatus == '1') {
            $bio_approved++;
        }else{
            $bio_rejected++;
        }
    }else{
        if ($Biouser === $this->session->userdata('user_id')) {
            if ($Biostatus == '0') {
                $bio_pending++;
            }elseif ($Biostatus == '1') {
                $bio_approved++;
            }else{
                $bio_rejected++;
            }
        }
    }
}
if ($this->ion_auth->is_admin() || permissions('attendance_view_all') || permissions('attendance_view_all')) {
$abs = $numRows-$present-$leaves;
}else{
$abs = 0;
$holidayQuery = $this->db->query("SELECT * FROM holiday");
$holidayResult = $holidayQuery->result_array();
$dates_between = array();
    foreach ($holidayResult as $holiday) {
        $starting_date = $holiday['starting_date'];
        $ending_date = $holiday['ending_date'];
        $apply = $holiday['apply'];
        if ($holiday['type'] != '2' && $apply == '0') {
            $start_date_obj = new DateTime($starting_date);
            $end_date_obj = new DateTime($ending_date);
            $end_date_obj->modify('+1 day'); // Add one day to include the end date
            $date_range = new DatePeriod($start_date_obj, new DateInterval('P1D'), $end_date_obj);
            foreach ($date_range as $date) {
                $dates_between[] = $date->format('Y-m-d');
            }
        }
    }
    // Loop through each row and add missing headings with "N/A" value
    foreach ($result as &$row) {
        $userHeadings = array_keys($row);
        $missingHeadings = array_diff($dateRange, $userHeadings);
        
        foreach ($missingHeadings as $heading) {
            $row[$heading] = '<span class="text-danger"><strong>absent</strong></span>';
        }
    }
    $abs = 0;
    // Fetch holidays
$holidayQuery = $this->db->query("SELECT * FROM holiday");
$holidayResult = $holidayQuery->result_array();

$dates_between = array();
$dates_between2 = array();
$dates_between5 = array();
// Loop through each row and add missing headings with "N/A" value
foreach ($result as &$row) {
    $userHeadings = array_keys($row);
    $missingHeadings = array_diff($dateRange, $userHeadings);

    foreach ($missingHeadings as $heading) {
        $row[$heading] = '<span class="text-danger"><strong>absent</strong></span>';
    }
}
// Loop through holidays
foreach ($holidayResult as $holiday) {
    $starting_date = $holiday['starting_date'];
    $ending_date = $holiday['ending_date'];
    $apply = $holiday['apply'];
// Check holiday type and apply condition
if ($apply == '0') {
        $current_month_start = date('Y-m-01'); // 1st day of the current month
        $current_month_end = date('Y-m-t');   // Last day of the current month

        $date_range = new DatePeriod(new DateTime($starting_date), new DateInterval('P1D'), (new DateTime($ending_date))->modify('+1 day'));
        $dates_between = [];

        foreach ($date_range as $date) {
            $date_str = $date->format('Y-m-d');
            if ($date_str >= $current_month_start && $date_str <= $current_month_end) {
                $dates_between[] = $date_str;
            }
        }
    } elseif ($apply == '1') {
        $depholi=0;
        $departments = json_decode($holiday['department']);
        $current_month_start = date('Y-m-01'); // 1st day of the current month
        $current_month_end = date('Y-m-t');   // Last day of the current month

        $date_range = new DatePeriod(new DateTime($starting_date), new DateInterval('P1D'), (new DateTime($ending_date))->modify('+1 day'));
        $dates_between2 = [];

        foreach ($date_range as $date) {
            $date_str = $date->format('Y-m-d');
            if ($date_str >= $current_month_start && $date_str <= $current_month_end) {
                $dates_between2[] = $date_str;
            }
        }

        foreach ($departments as $department) {
            foreach ($dates_between2 as $heading_date2) {
                foreach ($result as $key => $afterholiday) {
                    $id = $result[$key]["id"];
                    $HoliDayUsers = $this->db->query("SELECT department FROM users WHERE employee_id=".$id);
                    $HoliDayUsersresults = $HoliDayUsers->row_array();
                    if ($HoliDayUsersresults["department"] == $department) {
                        $value2 = $result[$key][$heading_date2];
                        if ($value2 === '<span class="text-danger"><strong>absent</strong></span>' || $value2 === '<span class="text-info"><strong>L</strong></span>') {
                            $depholi++;
                        }
                    }
                }
            }
        }
    }
}



// Count Saturday and Sunday occurrences
$satSunArray = array_filter($dateRange, function ($date) {
    return date('N', strtotime($date)) >= 6;
});

$hd = count($satSunArray)+count($dates_between)+$depholi;
$abs = count($dateRange)-($hd+$present+$leaves_approved);
}
        return $array = [
            "array"=> $result,
            "dates"=> $dates_between,
            "abs"=> $abs,
            "leave"=> $leaves,
            "present"=> $present,
            "leave_pending"=> $leave_pending,
            "leave_approved"=> $leaves_approved,
            "leave_rejected"=> $leave_rejected,
            "bio_pending"=> $bio_pending,
            "bio_approved"=> $bio_approved,
            "bio_rejected"=> $bio_rejected,
        ];
    }
}


