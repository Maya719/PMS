<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v5
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$fields = array(
			'order_by_id' => array(
				'type' => 'INT',
				'default' => 0,
			),
		);
		$this->dbforge->add_column('features', $fields);

		$fields = array(
			'short_code' => array(
				'type' => 'VARCHAR',
				'constraint' => '256',
				'default' => 'en',
			),
		);
		$this->dbforge->add_column('languages', $fields);

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'saas_id' => array(
					'type' => 'INT',
				),
				'created_by' => array(
					'type' => 'INT',
				),
				'company' => array(
					'type' => 'TEXT',
				),
				'value' => array(
					'type' => 'TEXT',
				),
				'source' => array(
					'type' => 'TEXT',
				),
				'email' => array(
					'type' => 'TEXT',
				),
				'mobile' => array(
					'type' => 'TEXT',
				),
				'assigned' => array(
					'type' => 'TEXT',
				),
				'status' => array(
					'type' => 'TEXT',
				),
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('leads');

		$this->db->set('value', '5');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');

	}

	public function down() {
	}
}
