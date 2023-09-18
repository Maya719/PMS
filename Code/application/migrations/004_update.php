<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v4
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  
		
		$this->db->where('id=2');
		$this->db->delete('time_formats');

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'saas_id' => array(
					'type' => 'INT',
				),
				'leave_reason' => array(
					'type' => 'TEXT',
				),
				'user_id' => array(
					'type' => 'INT',
				),
				'leave_days' => array(
					'type' => 'INT',
				),
				'starting_date' => array(
                    'type' => 'DATE',
				),
				'ending_date' => array(
                    'type' => 'DATE',
				),
				'status' => array(
					'type' => 'INT',
				),
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('leaves');

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'saas_id' => array(
					'type' => 'INT',
				),
				'project_id' => array(
					'type' => 'INT',
				),
				'task_id' => array(
					'type' => 'INT',
				),
				'user_id' => array(
					'type' => 'INT',
				),
				'note' => array(
					'type' => 'TEXT',
				),
				'starting_time' => array(
					'type' => 'datetime',
                    'null' => TRUE,
				),
				'ending_time' => array(
					'type' => 'datetime',
                    'null' => TRUE,
				),
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('timesheet');

		$this->db->where('id=3');
		$this->db->delete('time_formats');
		
		$this->dbforge->add_field(array(
			'id' => array(
					'type' => 'INT',
					'auto_increment' => TRUE
			),
			'saas_id' => array(
				'type' => 'INT',
			),
			'description' => array(
				'type' => 'TEXT',
			),
			'date' => array(
				'type' => 'DATE',
			),
			'amount' => array(
				'type' => 'TEXT',
			),
			'team_member_id' => array(
				'type' => 'INT',
				'null' => TRUE,
			),
			'client_id' => array(
				'type' => 'INT',
				'null' => TRUE,
			),
			'project_id' => array(
				'type' => 'INT',
				'null' => TRUE,
			),
			'receipt' => array(
				'type' => 'TEXT',
			),
			'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('expenses');

        $this->db->set('js_format', 'hh:mm A');
        $this->db->where('id', 1);
        $this->db->update('time_formats');

		$fields = array(
			'icon' => array(
				'type' => 'TEXT',
				'null' => FALSE,
			),
		);
		$this->dbforge->add_column('features', $fields);

		$fields = array(
			'modules' => array(
				'type' => 'TEXT',
				'null' => FALSE,
			),
			'storage' => array(
				'type' => 'INT',
				'null' => FALSE,
			),
		);
		$this->dbforge->add_column('plans', $fields);

        $this->db->set('js_format', 'H:mm');
        $this->db->where('id', 4);
        $this->db->update('time_formats');

		$this->db->set('system_version', '4');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');

	}

	public function down() {
	}
}
