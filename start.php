<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * @version 1.0
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

/**
 * define globals
 */
define('GLOBAL_IZAP_ECOMMERCE_PLUGIN', 'izap-ecommerce');
define('GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER', 'store');
define('GLOBAL_IZAP_ECOMMERCE_SUBTYPE', 'izap_ecommerce');

if (elgg_is_active_plugin('izap-elgg-bridge'))
  register_elgg_event_handler('init', 'system', 'izap_ecommerce_init');

/**
 * init function
 * 
 * @global  $CONFIG array of global variable
 */
function izap_ecommerce_init() {
  global $CONFIG;
  izap_plugin_init(GLOBAL_IZAP_ECOMMERCE_PLUGIN);
  IzapBase::loadLib(array('lib' => 'load', 'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN));
  IzapBase::loadLib(array('lib' => 'izap_ecommerce', 'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN));
  elgg_register_menu_item('site', array(
      'name' => elgg_echo('izap-ecommerce:store'),
      'text' => elgg_echo('izap-ecommerce:store'),
      'href' => IzapBase::setHref(array(
          'context' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,
          'action' => 'list',
          'page_owner' => false,
          'vars' => array('all')
      ))));

  if (elgg_get_context() == 'store') {
    $submenu = array(
        '' . GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/list/all/' => array('title' => "izap-ecommerce:all_products", 'public' => false, 'groupby' => 'all'),
        '' . GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/add' => array('title' => 'izap-ecommerce:add_product', 'admin_only' => TRUE, 'groupby' => 'my'),
        '' . GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/list/' . get_loggedin_user()->username . '/' => array('title' => "izap-ecommerce:my_products", 'admin_only' => TRUE, 'groupby' => 'my'),
        '' . GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/orders/' => array('title' => "izap-ecommerce:my_orders", 'public' => TRUE, 'groupby' => 'my'),
        '' . GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/wishlist' => array('title' => 'izap-ecommerce:wishlist', 'extra_title' => ' (' . IzapEcommerce::countWishtlistItems() . ')', 'public' => TRUE, 'groupby' => 'my'),
        '' . GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/all_orders/' => array('title' => "izap-ecommerce:all_orders", 'admin_only' => TRUE, 'groupby' => 'all'),
    );
    foreach ($submenu as $url => $options) {
      if (isset($options['public']) && $options['public'] == TRUE && !elgg_is_logged_in()) {
        continue;
      } else if (isset($options['admin_only']) && $options['admin_only'] == true && !elgg_is_admin_logged_in()) {
        continue;
      } else {
        elgg_register_menu_item('page', array(
            'name' => elgg_echo($options['title']),
            'text' => elgg_echo($options['title']),
            'href' => $url,
            'section' => $options['groupby']
        ));
      }
    }
  }

  elgg_register_widget_type('latest_product', elgg_echo('izap-ecommerce:widgets:latest_products:name'), elgg_echo('izap-ecommerce:widgets:latest_products:description'));
  // Extend hover-over menu
  elgg_extend_view('profile/menu/links', GLOBAL_IZAP_ECOMMERCE_PLUGIN . "/menu");
  elgg_register_page_handler(GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER, GLOBAL_IZAP_PAGEHANDLER);
  elgg_register_entity_type('object', 'izap_ecommerce');
  elgg_register_entity_url_handler('object', 'izap_ecommerce', 'izap_ecommerce_getUrl');
  elgg_register_entity_url_handler('object', 'izap_order', 'izap_order_getUrl');
  add_subtype('object', 'izap_ecommerce', 'IzapEcommerce');

  $actions_arr = array(
      'admin' => array(
          'izap_ecommerce/save_settings' => "save_settings.php",
          'izap_ecommerce/add_attrib' => "add_attrib.php",
          'izap_ecommerce/add_attrib_group' => "add_attrib_group.php",
          'izap_ecommerce/remove_attrib' => "remove_attrib.php",
          'izap_ecommerce/delete' => "delete.php",
          'izap_ecommerce/add' => "add_edit.php",
          'izap_ecommerce/add_screenshots' => "add_screenshots.php"
      ),
      'public' => array(
          'izap_ecommerce/add_to_cart' => "add_to_cart.php",
          'izap_ecommerce/remove_from_cart' => "remove_from_cart.php",
          'izap_ecommerce/buy' => "buy.php",
          'izap_ecommerce/sendtofriend' => "send_to_friends.php",
          'izap_ecommerce/delete_screenshot' => "delete_screenshot.php",
          'izap_ecommerce/paypal_notify' => "paypal_notify.php",
      ),
      'logged_in' => array(
          'izap_ecommerce/add_to_wishlist' => "add_to_wishlist.php",
          'izap_ecommerce/remove_from_wishlist' => "remove_from_wishlist.php",
          'izap_ecommerce/download' => "download.php",
          ));
  foreach ($actions_arr as $access_id => $actions) {
    foreach ($actions as $action => $filename) {
      elgg_register_action($action, $CONFIG->pluginspath . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/actions/' . $filename, $access_id);
      elgg_register_plugin_hook_handler('action', $action, GLOBAL_IZAP_ACTIONHOOK);
    }
  }
  elgg_register_event_handler('logout', 'user', 'func_save_cart_izap_ecommerce');
  elgg_register_plugin_hook_handler('izap_payment_gateway', 'IPN_NOTIFY_ALERTPAY:SUCCESS', 'izap_alertpay_process_order');
  elgg_register_plugin_hook_handler('izap_payment_gateway', 'IPN_NOTIFY_ALERTPAY:FAIL', 'izap_alertpay_fail');
}


/**
 * url for entity
 *
 * @param ElggObject $entity product entity
 *
 * @return URL main url for product
 */
function izap_ecommerce_getUrl($entity) {
  return GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/product/' . get_user($entity->container_guid)->username . '/' . $entity->guid . '/' . $entity->slug;
}

/**
 * url for product
 *
 * @param ElggObject $entity order entity
 *
 * @return URL main url for order
 */
function izap_order_getUrl($entity) {
  return GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/order/' . $entity->guid;
}

/**
 * HELPER FUNCTIONS
 * set params
 *
 * @param array $array set params value
 */
function izap_set_params($array) {
  foreach ($array as $key => $value) {
    set_input('izap_param_' . $key, $value);
  }
}

/**
 * get params
 * 
 * @param  array  $key     index of an array
 * @param  string $default default value null
 *
 * @return array
 */
function izap_get_params($key, $default = '') {
  return get_input('izap_param_' . $key, $default);
}

/**
 * function for register plugin hook
 *
 * @global ElggObject $IZAP_ECOMMERCE object of IZAP_ECOMMERCE
 *
 * @param string $hook register plugin hook
 * @param string $entity_type   type of the entity
 * @param mixed $returnvalue    value return by the function
 * @param array  $params        elgg default parameters
 */
function izap_alertpay_process_order($hook, $entity_type, $returnvalue, $params) {
  global $IZAP_ECOMMERCE;

  $reference_num = $params['transactionReferenceNumber'];
  $order_id = $params['myCustomField_3'];
  $order = get_entity($order_id);

  $main_array['confirmed'] = 'yes';
  $main_array['payment_transaction_id'] = $reference_num;

  $provided['entity'] = $order;
  $provided['metadata'] = $main_array;
  IzapBase::updateMetadata($provided);

  // save purchased product info with user
  save_order_with_user_izap_ecommerce($order);

  IzapEcommerce::sendOrderNotification($order);
}

/**
 * function for register plugin hook
 *
 * @global ElggObject $IZAP_ECOMMERCE IZAP_ECOMMERCE
 *
 * @param string $hook        register plugin hook
 * @param string $entity_type type of the entity
 * @param mixed  $returnvalue value return by the function
 * @param array  $params elgg default parameters
 */
function izap_alertpay_fail($hook, $entity_type, $returnvalue, $params) {
  global $IZAP_ECOMMERCE;

  $order_id = $params['myCustomField_3'];
  $order = get_entity($order_id);

  $main_array['confirmed'] = 'no';
  $main_array['error_status'] = 'Error while Payment';
  $main_array['error_time'] = time();
  $main_array['return_response'] = serialize($params);

  $provided['entity'] = $order;
  $provided['metadata'] = $main_array;
  IzapBase::updateMetadata($provided);

  notify_user(
          $order->owner_guid,
          $CONFIG->site->guid,
          elgg_echo('izap-ecommerce:order_processe_error'),
          elgg_echo('izap-ecommerce:order_processe_error_message') . $IZAP_ECOMMERCE->link . 'order_detail/' . $order->guid
  );
}

/**
 * Functions from izap-elgg-bridge 1.7
 * load file
 *
 * @global IzapTemplate $IZAPTEMPLATE load template
 * @global array        $CONFIG       array of global variable
 *
 * @param string $file    load provided file
 * @param array  $options optional array
 *
 * @return string bolean
 */
function izap_load_file($file, $options = array()) {
  global $IZAPTEMPLATE, $CONFIG;

  // statring the global template render class
  $IZAPTEMPLATE = new IzapTemplate;

  if (include_once ($file)) {
    return TRUE;
  }
  return FALSE;
}

/**
 * plugin settings
 *
 * @param array $supplied_array plugin setting array
 *
 * @return array 
 */
function izap_plugin_settings($supplied_array) {
  $default = array(
      'override' => FALSE,
      'make_array' => FALSE,
  );

  extract(array_merge($default, (array) $supplied_array));

  if (isset($plugin) && !empty($plugin)) {
    $plugin_name = $plugin;
  }

  // get the old value
  $old_setting = elgg_get_plugin_setting($setting_name, $plugin_name);

  if (is_array($value)) {
    $plugin_values = implode('|', $value);
  } else {
    $plugin_values = $value;
  }
  // if it is not set yet
  if ((is_null($old_setting) && !empty($plugin_values)) || $override) {
    if (!set_plugin_setting($setting_name, $plugin_values, $plugin_name)) {
      return FALSE;
    } else {
      $return_val = $value;
    }
  }

  if ($old_setting !== FALSE) {
    $old_array = explode('|', $old_setting);
    if (count($old_array) > 1) {
      $return_val = $old_array;
    } else {
      $return_val = $old_setting;
    }
  }

  if (!is_array($return_val) && $make_array) {
    $new_return_val[] = $return_val;
    $return_val = $new_return_val;
  }

  return $return_val;
}

/**
 * generate unique id
 *
 * @param mixed $input mixed value
 *
 * @return mixed
 */
function func_generate_unique_id($input = '') {
  $microtime = md5(microtime(TRUE));
  $rand = rand(rand(43, 1985), time());
  $input = md5($input);

  return md5($microtime . $rand . $input . rand(43, 1985));
}

/**
 * check weather is request is ajax or not
 *
 * @return bolean
 */
function isAjax() {
  return izap_is_ajax_request();
}

/**
 * check weather is request is ajax or not
 *
 * @return bolean
 */
function izap_is_ajax_request() {
  return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
  ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

/**
 * to cache header
 * 
 * @param array $options optional array
 */
function izap_cache_headers($options = array()) {
  $sessions_start_time = (int) $_SESSION['start_time'];
  if (!$sessions_start_time) {
    $_SESSION['start_time'] = time();
  }
  $defaults = array(
      'expire_time' => 86400, // one day
      'content_type' => 'text/html',
      'file_name' => current_page_url(),
      'filemtime' => $sessions_start_time,
  );

  $working = array_merge($defaults, $options);
  extract($working);

  header("Pragma: public");
  header("Cache-Control: maxage=" . $expire_time . " must-revalidate");
  header('Expires: ' . gmdate('D, d M Y H:i:s', $sessions_start_time + $expire_time) . ' GMT');
  header("Last-Modified: " . gmdate('D, d M Y H:i:s', $filemtime) . ' GMT');
  header("Content-type: {$content_type}");
  header("Content-Disposition: inline; filename=\"{$file_name}\"");
}