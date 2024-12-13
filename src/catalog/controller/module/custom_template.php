<?php
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */

namespace Opencart\Catalog\Controller\Extension\CustomTemplatePro\Module;
class CustomTemplate extends \Opencart\System\Engine\Controller {

	public 	$_route 	= 'extension/module/custom_template';

	public function filter(string &$route, array &$data):void {

		if(!$this->registry->has('custom_template')){
			$custom_template = new \Opencart\Extension\custom_template_pro\System\Library\custom_template\Custom_template($this->registry);
			$this->registry->set('custom_template', $custom_template);
		}
		$short_route = preg_replace('/^(.?)*\/template\//', '', $route);

		$template = $this->custom_template->filterTemplate($short_route);
		if ($template !== false) {
			$route = str_replace($short_route, $template, $route);
		}
	}
}