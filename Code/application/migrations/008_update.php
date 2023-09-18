<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v5.4
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$fields = array(
			'receipt' => array(
				'type' => 'TEXT',
			),
		);
		$this->dbforge->add_column('invoices', $fields);

		$fields = array(
			'receipt' => array(
				'type' => 'TEXT',
			),
		);
		$this->dbforge->add_column('offline_requests', $fields);

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'saas_id' => array(
					'type' => 'INT',
				),
				'user_id' => array(
					'type' => 'INT',
				),
				'note' => array(
					'type' => 'TEXT',
				),
				'check_in timestamp default current_timestamp',
				'check_out' => array(
					'type' => 'timestamp',
					'null' => TRUE,
					'default' => NULL,
				),
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('attendance');

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'user_id' => array(
					'type' => 'INT',
				),
				'subject' => array(
					'type' => 'TEXT',
				),
				'status' => array(
					'type' => 'INT',
					'default' => 1,
				),
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('support');

		$this->dbforge->add_field(array(
			'id' => array(
					'type' => 'INT',
					'auto_increment' => TRUE
			),
			'from_id' => array(
				'type' => 'INT',
			),
			'to_id' => array(
				'type' => 'INT',
			),
			'message' => array(
				'type' => 'TEXT',
			),
			'is_read' => array(
				'type' => 'INT',
				'default' => 0,
			),
			'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('support_messages');

		$this->db->set('value', '5.4');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');

	}

	public function down() {
	}
}
