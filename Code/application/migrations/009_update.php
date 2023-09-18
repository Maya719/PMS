<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v5.5
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$this->db->query("ALTER TABLE `languages` ADD `status` INT NOT NULL DEFAULT '1'");
		$this->db->query("ALTER TABLE `invoices` ADD `products_id` TEXT NOT NULL AFTER `items_id`");
		$this->db->query("UPDATE invoices SET products_id = items_id WHERE type = 'estimate'");

		$this->db->query("INSERT INTO `email_templates` (`name`, `subject`, `message`, `variables`) VALUES ('front_enquiry_form', 'Contact Form submitted', '<p>Name:&nbsp;<span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">{NAME} </span></p>\r\n<p><span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">Email: {EMAIL}</span></p>\r\n<p><span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">{MESSAGE}</span></p>', '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {NAME}, {EMAIL}, {MESSAGE}')");
		
		$this->db->set('value', '5.5');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');

	}

	public function down() {
	}
}
