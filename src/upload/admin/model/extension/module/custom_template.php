<?php 
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */

class ModelExtensionModuleCustomTemplate extends Model {

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."custom_template` ( 
			`custom_template_id` int(11) NOT NULL AUTO_INCREMENT, 
			`route` varchar(64) NOT NULL, 
			`data` text NOT NULL, 
			PRIMARY KEY (`custom_template_id`), 
			KEY `route` (`route`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."custom_template`");
		$this->removeModuleEvents();
	}

	public function removeModuleEvents() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` REGEXP '^ctm_.{28}$'");
	}

	public function getCustomTemplates() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_template`");
		return $query->rows;
	}

	public function saveCustomTemplates($data) {
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "custom_template`");
		foreach ($data as $item) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_template` SET `route` = '" . $this->db->escape($item['route']) . "', `data` = '" . $this->db->escape($item['value']) . "'");
		}
	}

	public function getCustomTemplateData($custom_template_id) {
		$query = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "custom_template` WHERE `custom_template_id` = '" . (int)$custom_template_id . "'");
		if ($query->num_rows) {
			return $query->row['data'];
		}else{
			return '';
		}
	}
}