<?php
class ControllerExtensionModuleCustomTemplate extends Controller {

	public 	$_route 	= 'extension/module/custom_template';

	public function filter(&$route, &$data) {
		if(!$this->registry->has('custom_template')){
			$this->load->library('custom_template/custom_template');
		}
		$short_route = preg_replace('/^(.?)*\/template\//', '', $route);

		$template = $this->custom_template->filterTemplate($short_route);
		if ($template !== false) {
			$route = str_replace($short_route, $template, $route);
		}
	}
}