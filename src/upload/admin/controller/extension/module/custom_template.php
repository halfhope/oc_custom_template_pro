<?php
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */

class ControllerExtensionModuleCustomTemplate extends Controller {

	public 	$_route 		= 'extension/module/custom_template';
	public 	$_model 		= 'model_extension_module_custom_template';
	private $_version 		= '1.2';
	private $_name 			= 'Custom templates Pro';

	private $error = [];
	private $modified_files;

	public function install() {
		$this->load->model($this->_route);
		$this->{$this->_model}->install();
	}

	public function uninstall() {
		$this->load->model($this->_route);
		$this->{$this->_model}->uninstall();
	}

	private function loadDataModels() {
		$this->load->model($this->_route);
		$this->load->model('design/layout');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('localisation/currency');
		$this->load->model('localisation/weight_class');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');
		$this->load->model('customer/customer_group');
	}

	public function index() {
		$this->load->model($this->_route);
		$this->load->language($this->_route);

		$data = [];
		$data += $this->load->language($this->_route);

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['version'] = $this->_version;

		$this->document->addScript('view/javascript/custom_template.js?' . $this->_version);
		$this->document->addStyle('view/stylesheet/custom_template.css?' . $this->_version);

		$this->document->addScript('view/javascript/jquery/Sortable.js');
		$this->document->addScript('view/javascript/jquery/jquery-sortable.js');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$post_data = isset($this->request->post['custom_template']) ? $this->request->post['custom_template'] : [];
				
			// remove empty
			foreach ($post_data as $key => $value) {
				if (empty($value['value'])) {
					unset($post_data[$key]);
				}
			}

			$this->{$this->_model}->saveCustomTemplates($post_data);
			$this->{$this->_model}->removeModuleEvents();

			$events = [];
			foreach ($post_data as $key => $value) {
				$events[$value['route']] = [
					'code' 		=> 'ctm_' . substr(md5(http_build_query($value)), 4),
					'trigger'	=> 'catalog/view/' . $value['route'] . '/before',
					'action'	=> '/filter'
				];
			}

			$this->load->model('setting/event');
			foreach($events as $event) {
				if(!$result = $this->model_setting_event->getEvent($event['code'], $event['trigger'], $this->_route . $event['action'])) {
					$this->model_setting_event->addEvent($event['code'], $event['trigger'], $this->_route . $event['action']);
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link($this->_route, 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['breadcrumbs'] = [];
		
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		];
		
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		];
		
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->_route, 'user_token=' . $this->session->data['user_token'], 'SSL')
		];
		
		$data['action'] = $this->url->link($this->_route, 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$data['form'] = htmlspecialchars_decode($this->url->link($this->_route . '/form', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		$data['explain'] = htmlspecialchars_decode($this->url->link($this->_route . '/explain', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		$data['find_mods'] = htmlspecialchars_decode($this->url->link($this->_route . '/find_mods', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		$data['product_autocomplete'] = htmlspecialchars_decode($this->url->link($this->_route . '/product_autocomplete', 'user_token=' . $this->session->data['user_token'], 'SSL'));

		$data['ct_data'] = json_encode([
			'links' => [
				'explain' => $data['explain'],
				'form' 	=> $data['form'],
				'find_mods' => $data['find_mods'],
				'product_autocomplete' => $data['product_autocomplete']
			],
			'lang' => [
				'text_form_autocomplete' => $data['text_form_autocomplete'],
				'text_form_select' => $data['text_form_select'],
				'text_output_notset' => $data['text_output_notset'],
				'text_edit' => $data['text_edit'],
				'button_add' => $data['button_add'],
				'button_add' => $data['button_add'],
				'button_remove' => $data['button_remove']
			]
		]);

		$data['custom_template'] = [];
		$results = $this->{$this->_model}->getCustomTemplates();
		foreach ($results as $key => $value) {
			parse_str(html_entity_decode($value['data']), $custom_template);
			
			$data['custom_template'][$value['route']]['mods'] = $this->find_mods($value['route']);
			$data['custom_template'][$value['route']]['items'][] = [
				'description' => $this->explain($custom_template),
				'value' => $value['data']
			];
		}

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');
		
		header('Access-Control-Allow-Origin:*');
		$this->response->setOutput($this->load->view($this->_route . '/main', $data));
	}
	
	public function form() {
		$this->loadDataModels();

		$data = [];
		$data += $this->load->language($this->_route);
		
		// prepare data for form
		$data['module_row'] 	= (int) $this->request->post['module_row'];

		$data['languages']      = $this->model_localisation_language->getLanguages();
		$data['currencies']     = $this->model_localisation_currency->getCurrencies();
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		$data['categories']     = $this->model_catalog_category->getCategories(0);
		$data['informations']   = $this->model_catalog_information->getInformations();
		$data['manufacturers']  = $this->model_catalog_manufacturer->getManufacturers();
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['stores'] = [];
		
		$data['stores'][] = [
			'store_id' => 0,
			'name' => $this->config->get('config_name') . $this->language->get('text_default')
		];
		
		$results = $this->model_setting_store->getStores();
		
		foreach ($results as $result) {
			$data['stores'][] = [
				'store_id' => $result['store_id'],
				'name' => $result['name']
			];
		}
		
		$data['customer_groups'][] = [
			'name' => $this->language->get('text_unregistered'),
			'customer_group_id' => null
		];
				
		$data['mobile'] = [
			0 => $this->language->get('filter_mobile1'),
			1 => $this->language->get('filter_mobile2'),
			2 => $this->language->get('filter_mobile3')
		];

		// https://github.com/cbschuld/Browser.php
		$data['platforms'] = [
			'unknown', 'Windows', 'Windows CE', 'Apple', 'Linux', 'OS/2',
			'BeOS', 'iPhone', 'iPod', 'iPad', 'BlackBerry', 'Nokia',
			'FreeBSD', 'OpenBSD', 'NetBSD', 'SunOS', 'OpenSolaris', 'Android',
			'Sony PlayStation', 'Roku', 'Apple TV', 'Terminal', 'Fire OS', 'SMART-TV',
			'Chrome OS', 'Java/Android', 'Postman', 'Iframely'
		];

		// https://github.com/cbschuld/Browser.php
		$data['browsers'] = [
			'unknown', 'Opera', 'Opera Mini', 'WebTV', 'Edge', 'Internet Explorer', 'Pocket Internet Explorer', 'Konqueror',
			'iCab', 'OmniWeb', 'Firebird', 'Firefox', 'Brave', 'Palemoon', 'Iceweasel', 'Shiretoko', 'Mozilla', 'Amaya', 'Lynx', 'Safari',
			'iPhone', 'iPod', 'iPad', 'Chrome', 'Android', 'GoogleBot', 'cURL', 'Wget', 'UCBrowser', 'YandexBot', 'YandexImageResizer', 'YandexImages',
			'YandexVideo', 'YandexMedia', 'YandexBlogs', 'YandexFavicons', 'YandexWebmaster', 'YandexDirect', 'YandexMetrika', 
			'YandexNews', 'YandexCatalog', 'Yahoo! Slurp', 'W3C Validator', 'BlackBerry', 'IceCat', 'Nokia S60 OSS Browser', 'Nokia Browser', 
			'MSN Browser', 'MSN Bot', 'Bing Bot', 'Vivaldi', 'Yandex', 'Netscape Navigator', 'Galeon', 'NetPositive', 'Phoenix',
			'PlayStation', 'SamsungBrowser', 'Silk', 'Iframely', 'CocoaRestClient'
		];
		
		$route = $this->request->post['route'];

		switch ($route) {
			case 'product/product':
				$data['filters'] = [
					1 => $this->language->get('filter_type1'),
					2 => $this->language->get('filter_type2'),
					3 => $this->language->get('filter_type3')
				];
				$data['layout_type'] = 'product';
				break;
			case 'product/category':
				$data['filters'] = [
					2 => $this->language->get('filter_type2')
				];
				$data['layout_type'] = 'category';
				break;
			case 'product/manufacturer_info':
				$data['filters'] = [
					3 => $this->language->get('filter_type3')
				];
				$data['layout_type'] = 'manufacturer';
				break;
			case 'information/information':
				$data['filters'] = [
					4 => $this->language->get('filter_type4')
				];
				$data['layout_type'] = 'information';
				break;
			default:
				$route_path = array_filter(explode('/', $route));
				if (count($route_path) > 2 || $route_path[0] == 'common') {
					$data['filters'] = [
						1 => $this->language->get('filter_type1'),
						2 => $this->language->get('filter_type2'),
						3 => $this->language->get('filter_type3'),
						4 => $this->language->get('filter_type4')
					];
					$data['layout_type'] = 'default';	
				} else {
					$data['filters'] = [
						0 => 'common'
					];
					$data['layout_type'] = 'common';
				}
				break;
		}

		// process form data
		$settings = [];
		
		if (isset($this->request->post['custom_template'])) {
			parse_str(html_entity_decode($this->request->post['custom_template']), $settings);
			if (empty($settings)) {
				$settings = [];
			}
		}
		
		if (!isset($settings['template']) || empty($settings['template'])) {
			$settings['template'] = $this->request->post['route'];
		}
		
		if (isset($settings['product_id'])) {
			$products = $settings['product_id'];
		} else {
			$products = [];
		}
		
		$settings['products_parsed'] = [];
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$settings['products_parsed'][] = [
					'id' => $product_info['product_id'],
					'name' => html_entity_decode($product_info['name']),
					'selected' => true
				];
			}
		}

		$data['settings'] = $settings;
		
		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view($this->_route . '/form', $data));
	}
	
	public function explain($data = false) {
		$this->loadDataModels();
		$this->load->language($this->_route);

		$settings = $data ? $data : $this->request->post;
		
		$settings['type'] = isset($settings['type']) ? $settings['type'] : 0;
		
		$response = [];

		if (isset($settings['template'])) {
			if ($settings['template']) {
				$response[] = sprintf($this->language->get('text_output_template'), '<span class="label label-primary">' . $settings['template'] . '</span>');
			}
		}

		switch ($settings['type']) {
			case 1:
				if (isset($settings['product_id'])) {
					$products = $settings['product_id'];
				} else {
					$products = [];
				}
				
				if ($products) {
					$response[] = $this->language->get('text_output_products');
					foreach ($products as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						if ($product_info) {
							$response[] = '<span class="label label-default">' . $product_info['name'] . '</span>';
						}
					}
				}
				break;
			case 2:
				if (isset($settings['category_id'])) {
					$response[] = $this->language->get('text_output_categories');
					foreach ($settings['category_id'] as $category_id) {
						$category_info = $this->model_catalog_category->getCategory($category_id);
						if ($category_info) {
							$response[] = '<span class="label label-default">' . $category_info['name'] . '</span>';
						}
					}
				}
				break;
			case 3:
				if (isset($settings['manufacturer_id'])) {
					$response[] = $this->language->get('text_output_manufacturers');
					foreach ($settings['manufacturer_id'] as $manufacturer_id) {
						$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
						if ($manufacturer_info) {
							$response[] = '<span class="label label-default">' . $manufacturer_info['name'] . '</span>';
						}
					}
				}
				break;
			case 4:
				if (isset($settings['information_id'])) {
					if ($settings['information_id']) {
						$response[] = $this->language->get('text_output_informations');
						foreach ($settings['information_id'] as $information_id) {
							$information_info = $this->model_catalog_information->getInformationDescriptions($information_id);
							
							if ($information_info) {
								$information = end($information_info);
								$response[]  = '<span class="label label-default">' . $information['title'] . '</span>';
							}
						}
					}
				}
				break;
			default:
				break;
		}
		if (isset($settings['mobile'])) {
			if ($settings['mobile']) {
				$response[] = $this->language->get('text_output_mobile');
				foreach ($settings['mobile'] as $mobile_id) {
					$mobile     = [
						0 => $this->language->get('filter_mobile1'),
						1 => $this->language->get('filter_mobile2'),
						2 => $this->language->get('filter_mobile3')
					];
					$response[] = '<span class="label label-default">' . $mobile[$mobile_id] . '</span>';
				}
			}
		}
		if (isset($settings['language_id'])) {
			if ($settings['language_id']) {
				$response[] = $this->language->get('text_output_languages');
				foreach ($settings['language_id'] as $language_id) {
					$language_info = $this->model_localisation_language->getLanguage($language_id);
					if ($language_info) {
						$response[] = '<span class="label label-default">' . $language_info['name'] . '</span>';
					}
				}
			}
		}
		if (isset($settings['currency_id'])) {
			if ($settings['currency_id']) {
				$response[] = $this->language->get('text_output_currencies');
				foreach ($settings['currency_id'] as $currency_id) {
					$currency_info = $this->model_localisation_currency->getCurrency($currency_id);
					if ($currency_info) {
						$response[] = '<span class="label label-default">' . $currency_info['title'] . '</span>';
					}
				}
			}
		}
		if (isset($settings['customer_group_id'])) {
			if ($settings['customer_group_id']) {
				$response[] = $this->language->get('text_output_customer_groups');
				foreach ($settings['customer_group_id'] as $customer_group_id) {
					if ($customer_group_id == null) {
						$response[] = '<span class="label label-default">' . $this->language->get('text_unregistered') . '</span>';
					} else {
						$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($customer_group_id);
						if ($customer_group_info) {
							$response[] = '<span class="label label-default">' . $customer_group_info['name'] . '</span>';
						}
					}
				}
			}
		}
		if (isset($settings['store_id'])) {
			if ($settings['store_id']) {
				$response[] = $this->language->get('text_output_stores');
				foreach ($settings['store_id'] as $store_id) {
					if ($store_id == 0) {
						$store_info = [
							'store_id' => 0,
							'name' => $this->config->get('config_name') . $this->language->get('text_default')
						];
					} else {
						$store_info = $this->model_setting_store->getStore($store_id);
					}
					
					if ($store_info) {
						$response[] = '<span class="label label-default">' . $store_info['name'] . '</span>';
					}
				}
			}
		}
		if (isset($settings['sub_total'])) {
			if ($settings['sub_total']) {
				$currency = $this->model_localisation_currency->getCurrency($settings['sub_total']['currency_id']);
				$response[] = sprintf($this->language->get('text_output_sub_total'), $settings['sub_total']['min'], $settings['sub_total']['max'], $currency['title']);
			}
		}
		
		if (isset($settings['total'])) {
			if ($settings['total']) {
				$currency = $this->model_localisation_currency->getCurrency($settings['total']['currency_id']);
				$response[] = sprintf($this->language->get('text_output_total'), $settings['total']['min'], $settings['total']['max'], $currency['title']);
			}
		}

		if (isset($settings['weight'])) {
			if ($settings['weight']) {
				$currency = $this->model_localisation_weight_class->getWeightClass($settings['weight']['weight_class_id']);
				$response[] = sprintf($this->language->get('text_output_weight'), $settings['weight']['min'], $settings['weight']['max'], $currency['title']);
			}
		}

		if (isset($settings['custom'])) {
			if ($settings['custom']) {
				$response[] = sprintf($this->language->get('text_output_custom'), $settings['custom']['id'], $settings['custom']['values']);
			}
		}

		if (isset($settings['platform'])) {
			if ($settings['platform']) {
				$response[] = $this->language->get('text_output_platform');
				$response[] = '<span class="label label-default">' . implode(', ', $settings['platform']) . '</span>';
			}
		}

		if (isset($settings['browser'])) {
			if ($settings['browser']) {
				$response[] = $this->language->get('text_output_browser');
				$response[] = '<span class="label label-default">' . implode(', ', $settings['browser']) . '</span>';
			}
		}
		
		if (isset($settings['count'])) {
			if ($settings['count']) {
				$response[] = sprintf($this->language->get('text_output_count_products'), $settings['count']['min'], $settings['count']['max']);
			}
		}

		$json = [];
	
		if (isset($settings['module_row'])) {
			$json['module_row'] = $settings['module_row'];
		}

		$json['description'] = implode(' ', $response);
		if (count($response) == 1) {
			$json['description'] .= ' *';
		} elseif (count($response) == 0) {
			$json['description'] .= '<span class="label label-warning">' . $this->language->get('text_output_notset') . '</span>';
		}

		if ($data) {
			return $json['description'];
		} else {
			$this->response->addHeader('Content-type:application/json;charset=utf-8');
			$this->response->setOutput(json_encode($json));
		}
	}
	
	public function product_autocomplete() {
		
		$json = [];
		
		if (isset($this->request->get['term'])) {
			
			$this->load->model('catalog/product');
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}
			
			$filter_data = [
				'filter_name' => $this->request->get['term'],
				'filter_model' => '',
				'start' => 0,
				'limit' => $limit
			];
			
			$results = $this->model_catalog_product->getProducts($filter_data);
			
			foreach ($results as $result) {
				$json['results'][] = [
					'id' => (int) $result['product_id'],
					'text' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}
		
		$this->response->addHeader('Content-type:application/json;charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}
	
	public function find_mods($route = false) {
		$group_route = $route ? $route : $this->request->post['route'];

		$response = [];

		$modified_files = $this->getModifiedFiles();
		foreach ($modified_files as $key => $value) {
			if (substr_count($key, $group_route . '.twig') > 0) {
				$response = $value;
			}
		}

		if ($route) {
			return $response;
		} else {
			$this->response->addHeader('Content-type:application/json;charset=utf-8');
			$this->response->setOutput(json_encode($response));
		}
	}

	private function getModifiedFiles() {
		if (!isset($this->modified_files)) {
			$this->modified_files = [];
			$this->load->model('setting/modification');
			$this->load->language($this->_route);
			$xml = [];

			$files = glob(DIR_SYSTEM . '*.ocmod.xml');
			if ($files) {
				foreach ($files as $file) {
					$xml[] = file_get_contents($file);
				}
			}

			$results = $this->model_setting_modification->getModifications();
			foreach ($results as $result) {
				if ($result['status']) {
					$xml[] = $result['xml'];
				}
			}

			$modification = [];

			foreach ($xml as $xml) {
				if (empty($xml)) {
					continue;
				}

				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->preserveWhiteSpace = false;
				$dom->loadXml($xml);

				$recovery = [];
				if (isset($modification)) {
					$recovery = $modification;
				}

				$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');
				$name = $dom->getElementsByTagName('name')->item(0)->textContent;

				foreach ($files as $file) {

					$file_line_num = $file->getLineNo();
					$operations = $file->getElementsByTagName('operation');
					$files = explode('|', $file->getAttribute('path'));

					foreach ($files as $file) {
						$path = '';
						if ((substr($file, 0, 7) == 'catalog')) {
							$path = DIR_CATALOG . substr($file, 8);
						}
						if ($path) {
							$files = glob($path, GLOB_BRACE);
							if ($files) {
								foreach ($files as $file) {
									$short_filename = str_replace(DIR_CATALOG . 'view/theme/', '', $file);
									if (isset($this->modified_files[$short_filename])) {
										$this->modified_files[$short_filename]['content'][] = $name . ' [' . $file_line_num . ']';
									} else {
										$this->modified_files[$short_filename] = [
											'filename' => $short_filename,
											'content' => [$name . ' [' . $file_line_num . ']'],
											'title' => $this->language->get('text_found_mods')
										];
									}
								}
							}
						}
					}
				}
			}
		}
		return $this->modified_files;
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', $this->_route)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

}