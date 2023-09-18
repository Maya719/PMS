<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v4.9
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$fields = array(
			'starting_date' => array(
				'type' => 'DATE',
                'null' => TRUE,
				'default' => NULL,
			),
		);
		$this->dbforge->add_column('tasks', $fields);

		$fields = array(
			'end_date' => array(
					'name' => 'end_date',
					'type' => 'DATE',
					'null' => TRUE,
					'default' => NULL,
			),
		);
		$this->dbforge->modify_column('users_plans', $fields);

		$fields = array(
			'storage' => array(
					'name' => 'storage',
					'type' => 'VARCHAR',
					'constraint' => '256',
			),
		);
		$this->dbforge->modify_column('plans', $fields);

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'name' => array(
					'type' => 'TEXT',
				),
				'subject' => array(
					'type' => 'TEXT',
				),
				'message' => array(
					'type' => 'TEXT',
				),
				'variables' => array(
					'type' => 'TEXT',
				),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('email_templates');

		
		$data = array(
			array(
			   'name' => 'new_user_registration',
			   'subject' => 'Welcome...',
			   'message' => '<p>Welcome to the {COMPANY_NAME}, This is an automatically generated email to inform you. Below 				are the credentials for your work dashboard.</p>
							<p>Login credentials</p>
							<p>Email: {LOGIN_EMAIL}</p>
							<p>Password: {LOGIN_PASSWORD}</p>
							<p><a href="{DASHBOARD_URL}">Login Now</a></p>',
			   'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {LOGIN_EMAIL}, {LOGIN_PASSWORD}'
			),
			array(
				'name' => 'forgot_password',
				'subject' => 'Reset password',
				'message' => '<p>Hello,</p>
							<p>A password reset request has been created for your account.</p>
							<p>Please click on the following link to reset your password: {RESET_PASSWORD_LINK}</p>',
				'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {RESET_PASSWORD_LINK}'
			 ),
			 array(
				'name' => 'email_verification',
				'subject' => 'Confirm your email address',
				'message' => '<p>Welcome to the {COMPANY_NAME},</p>
							<p>Please confirm your email to activate your account.</p>
							<p>Please click on the following link to confirm your email address: {EMAIL_CONFIRMATION_LINK}</p>',
				'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {EMAIL_CONFIRMATION_LINK}'
			 ),
			 array(
				'name' => 'new_project',
				'subject' => 'New project assigned',
				'message' => '<p>Hello,</p>
							<p>New project {PROJET_TITLE} is assigned to you.</p>',
				'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL},  {PROJECT_TITLE}, {PROJECT_DESCRIPTION}, 					{STARTING_DATE}, {ENDING_DATE}, {BUDGET}, {PROJECT_URL}'
			 ),
			 array(
				'name' => 'new_task',
				'subject' => 'New task assigned',
				'message' => '<p>Hello,</p>
							<p>New task {TASK_TITLE} is assigned to you.</p>',
				'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {TASK_TITLE}, {TASK_DESCRIPTION}, {STARTING_DATE}			, {DUE_DATE}, {TASK_URL}'
			 ),
			 array(
				'name' => 'new_meeting',
				'subject' => 'New meeting scheduled',
				'message' => '<p>Hello,</p>
							<p>New meeting {MEETING_TITLE} is scheduled.</p>',
				'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {MEETING_TITLE}, {STARTING_DATE_AND_TIME}, 				{DURATION}, {MEETING_URL}'
			 ),
			 array(
				'name' => 'new_invoice',
				'subject' => 'New invoice received',
				'message' => '<p>Hello,</p>
							<p>New invoice {INVOICE_ID} received.</p>',
				'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {INVOICE_URL}, {INVOICE_NUMBER}, {INVOICE_AMOUNT}			, {INVOICE_DATE}, {INVOICE_DUE_DATE}'
			 ),
			 array(
				'name' => 'new_estimate',
				'subject' => 'New estimate received',
				'message' => '<p>Hello,</p>
							<p>New estimate {ESTIMATE_ID} received.</p>',
				'variables' => '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {ESTIMATE_URL}, {ESTIMATE_NUMBER}, 						{ESTIMATE_AMOUNT}, {ESTIMATE_DATE}, {ESTIMATE_DUE_DATE}'
			 )
		 );
		 $this->db->insert_batch('email_templates', $data);

		$this->db->set('value', '4.9');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');
	}

	public function down() {
	}
}
