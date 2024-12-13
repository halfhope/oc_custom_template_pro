<?php 
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */
namespace Opencart\Admin\Model\Extension\CustomTemplate\Module;
class CustomTemplate extends \Opencart\System\Engine\Model {

	public function install():void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."custom_template` ( 
			`custom_template_id` int(11) NOT NULL AUTO_INCREMENT, 
			`route` varchar(64) NOT NULL, 
			`data` text NOT NULL, 
			`sort_order` int(11) DEFAULT 0,
			PRIMARY KEY (`custom_template_id`), 
			KEY `route` (`route`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	}

	public function uninstall():void {
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."custom_template`");
		$this->removeModuleEvents();
	}

	public function removeModuleEvents():void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` REGEXP '^ctm_.{28}$'");
	}

	public function getCustomTemplates():array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_template` ORDER BY sort_order");
		if (isset($query->rows)) {
			return $query->rows;
		} else {
			return [];
		}
	}

	public function saveCustomTemplates(array $data = []):void {
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "custom_template`");
		foreach ($data as $item) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_template` SET `route` = '" . $this->db->escape($item['route']) . "', `data` = '" . $this->db->escape($item['value']) . "', `sort_order` = " . (int)$item['sort_order']);
		}
	}

	public function getCustomTemplateData(int $custom_template_id):string {
		$query = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "custom_template` WHERE `custom_template_id` = '" . (int)$custom_template_id . "'");
		if (isset($query->row['data'])) {
			return $query->row['data'];
		}else{
			return '';
		}
	}
}