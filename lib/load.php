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

global $IZAP_ECOMMERCE, $CONFIG;
$IZAP_ECOMMERCE = new stdClass();

// paths and names
$IZAP_ECOMMERCE->plugin_name = "izap-ecommerce";
$IZAP_ECOMMERCE->plugin_path = dirname(dirname(__FILE__))."/";
$IZAP_ECOMMERCE->libs = dirname(__FILE__) . '/';
$IZAP_ECOMMERCE->gateways = dirname(__FILE__) . '/gateways/';

$IZAP_ECOMMERCE->object_name = GLOBAL_IZAP_ECOMMERCE_SUBTYPE;
$IZAP_ECOMMERCE->class_name = 'IzapEcommerce';
$IZAP_ECOMMERCE->graphics_path = $CONFIG->pluginspath . $IZAP_ECOMMERCE->plugin_name . '/_graphics/';
$IZAP_ECOMMERCE->default_image = $CONFIG->pluginspath . 'izap-ecommerce/_graphics/no_image.jpg';

$IZAP_ECOMMERCE->page_handler = GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER;
$IZAP_ECOMMERCE->link = $CONFIG->wwwroot . 'pg/store/';
$IZAP_ECOMMERCE->full_url = $CONFIG->wwwroot . 'mod/' . $IZAP_ECOMMERCE->plugin_name . '/';
$IZAP_ECOMMERCE->_graphics = $CONFIG->wwwroot . 'mod/'.GLOBAL_IZAP_ECOMMERCE_PLUGIN.'/_graphics/';

$IZAP_ECOMMERCE->pages = dirname(dirname(__FILE__)).'/pages/';
$IZAP_ECOMMERCE->actions = $CONFIG->pluginspath. GLOBAL_IZAP_ECOMMERCE_PLUGIN.'/actions/';
$IZAP_ECOMMERCE->views = GLOBAL_IZAP_ECOMMERCE_PLUGIN . "/views/";
$IZAP_ECOMMERCE->product = GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/';
$IZAP_ECOMMERCE->forms = GLOBAL_IZAP_ECOMMERCE_PLUGIN . "/forms/";
$IZAP_ECOMMERCE->river = "river/" . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/';

$IZAP_ECOMMERCE->currency = 'USD';
$IZAP_ECOMMERCE->currency_sign = '$';
$IZAP_ECOMMERCE->show_not_confirmed_orders = 'no';