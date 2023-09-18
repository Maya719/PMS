<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v4.4
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$fields = array(
			'type' => array(
				'type' => 'TEXT',
				'default' => 'invoice',
			),
		);
		$this->dbforge->add_column('invoices', $fields);

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'saas_id' => array(
					'type' => 'INT',
				),
				'name' => array(
					'type' => 'TEXT',
				),
				'price' => array(
					'type' => 'INT',
				),
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('products');

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
				'duration' => array(
					'type' => 'INT',
				),
				'title' => array(
					'type' => 'TEXT',
				),
				'users' => array(
					'type' => 'TEXT',
				),
				'status' => array(
					'type' => 'INT',
					'default' => 0,
				),
				'starting_date_and_time timestamp default current_timestamp',
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('meetings');

		$this->db->set('value', '4.4');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');
	}

	public function down() {
	}
}
