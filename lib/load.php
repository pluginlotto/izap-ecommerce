<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version 1.0
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
*/

include_once dirname(__FILE__) . '/izap_ecommerce.php';
include_once dirname(__FILE__) . '/gateways/clsGateway.php';
include_once dirname(__FILE__) . '/gateways/paypal/paypal.php';

global $IZAP_ECOMMERCE, $CONFIG;
$IZAP_ECOMMERCE = new stdClass();

$main_array = $CONFIG->plugin_conf_byizap['izap-ecommerce'];

// paths and names
$IZAP_ECOMMERCE->plugin_name = $main_array['plugin']['name'];
$IZAP_ECOMMERCE->plugin_path = $main_array['path']['dir']['plugin'];
$IZAP_ECOMMERCE->libs = $main_array['path']['dir']['lib'];
$IZAP_ECOMMERCE->gateways = $main_array['path']['dir']['gateways'];

$IZAP_ECOMMERCE->object_name = 'izap_ecommerce';
$IZAP_ECOMMERCE->class_name = 'IzapEcommerce';
$IZAP_ECOMMERCE->graphics_path = $CONFIG->pluginspath . $IZAP_ECOMMERCE->plugin_name . '/_graphics/';
$IZAP_ECOMMERCE->default_image = $main_array['plugin']['custom']['default_image'];

$IZAP_ECOMMERCE->page_handler = $main_array['plugin']['url_title'];
$IZAP_ECOMMERCE->link = $main_array['path']['www']['page'];
$IZAP_ECOMMERCE->full_url = $CONFIG->wwwroot . 'mod/' . $IZAP_ECOMMERCE->plugin_name . '/';
$IZAP_ECOMMERCE->_graphics = $main_array['path']['www']['images'];

$IZAP_ECOMMERCE->pages = func_get_pages_path_byizap();
$IZAP_ECOMMERCE->actions = $main_array['path']['dir']['actions'];
$IZAP_ECOMMERCE->views = func_get_template_path_byizap(array('type'=>'views', 'plugin' => 'izap-ecommerce'));
$IZAP_ECOMMERCE->product = func_get_template_path_byizap(array('type'=>'product', 'plugin' => 'izap-ecommerce'));
$IZAP_ECOMMERCE->forms = func_get_template_path_byizap(array('type'=>'forms', 'plugin' => 'izap-ecommerce'));

$IZAP_ECOMMERCE->currency = $main_array['plugin']['custom']['currency'];
$IZAP_ECOMMERCE->currency_sign = $main_array['plugin']['custom']['currency_sign'];