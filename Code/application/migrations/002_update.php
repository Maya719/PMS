<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'saas_id' => array(
					'type' => 'INT',
				),
				'plan_id' => array(
					'type' => 'INT',
				),
				'status' => array(
					'type' => 'INT',
					'default' => 0,
				),
				'created datetime default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('offline_requests');

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'language' => array(
					'type' => 'TEXT',
				),
				'active' => array(
					'type' => 'INT',
					'default' => 0,
				),
				'created datetime default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('languages');

		$data = array(
			array(
			   'language' => 'english'
			),
			array(
			   'language' => 'hindi'
			),
			array(
			   'language' => 'italian'
			),
			array(
			   'language' => 'spanish'
			),
			array(
			   'language' => 'french' 
			)
		 );
		 $this->db->insert_batch('languages', $data);


		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'title' => array(
					'type' => 'TEXT',
				),
				'description' => array(
					'type' => 'TEXT',
				),
				'created datetime default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('features');

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'notification' => array(
					'type' => 'TEXT',
				),
				'type' => array(
					'type' => 'TEXT',
				),
				'type_id' => array(
					'type' => 'INT',
				),
				'from_id' => array(
					'type' => 'INT',
				),
				'to_id' => array(
					'type' => 'INT',
				),
				'is_read' => array(
					'type' => 'INT',
					'default' => 0,
				),
				'created datetime default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('notifications');

		$fields = array(
			'is_read' => array(
				'type' => 'INT',
				'default' => 0,
				'after' => 'message'
			)
		);
		$this->dbforge->add_column('messages', $fields);

		$this->db->where('id > 14');
		$this->db->delete('date_formats');
	}

	public function down() {
	}
}
