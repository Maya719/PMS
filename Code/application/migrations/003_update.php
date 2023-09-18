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
				'title' => array(
					'type' => 'TEXT',
				),
				'tax' => array(
					'type' => 'TEXT'
				),
				'created datetime default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('taxes');

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'title' => array(
					'type' => 'TEXT'
				),
				'content' => array(
					'type' => 'TEXT'
				),
				'created datetime default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('pages');
		$data = array(
			array(
			   'title' => 'About Us'
			),
			array(
			   'title' => 'Privacy Policy'
			),
			array(
			   'title' => 'Terms and Conditions'
			)
		 );
		 $this->db->insert_batch('pages', $data);


		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'created_by' => array(
					'type' => 'INT',
				),
				'from_id' => array(
					'type' => 'INT',
				),
				'to_id' => array(
					'type' => 'INT',
				),
				'items_id' => array(
					'type' => 'TEXT',
				),
				'amount' => array(
					'type' => 'TEXT',
				),
				'note' => array(
					'type' => 'TEXT',
				),
				'status' => array(
					'type' => 'INT',
					'default' => 0,
				),
				'tax' => array(
					'type' => 'TEXT',
				),
				'invoice_date' => array(
					'type' => 'DATE',
				),
				'due_date' => array(
					'type' => 'DATE',
				),
				'payment_type' => array(
					'type' => 'TEXT',
				),
				'payment_date' => array(
					'type' => 'DATE',
				),
				'created datetime default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('invoices');

		$fields = array(
			'budget' => array(
				'type' => 'TEXT',
                'null' => TRUE,
				'default' => NULL,
			)
		);
		$this->dbforge->add_column('projects', $fields);
	}

	public function down() {
	}
}
