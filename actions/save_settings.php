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

global $IZAP_ECOMMERCE;
$guid = (int) get_input('guid');
$defalt_values = array(
        'sandbox' => 'no',
);
$posted_values = get_input('params');

$sandbox = get_input('sandbox', FALSE);
if($sandbox) {
  $posted_values['sandbox'] = $sandbox[0];
}

$posted_values = array_merge($defalt_values, $posted_values);
$plugin = new ElggPlugin($guid);
$plugin->access_id = ACCESS_PUBLIC;
$plugin->title = sanitise_string($IZAP_ECOMMERCE->plugin_name);
if($plugin->save()) {
  foreach($posted_values as $key => $value) {
    if(!empty($key) && !empty ($value)) {
      if(is_array($value)) {
        $plugin->$key = array_to_plugin_settings_izap_iecommerce($value);
      }else {
        $plugin->$key = $value;
      }
    }
  }
  system_message(__('settings_saved'));
}else {
  register_error(__('error_saving_settings'));
}forward
($_SERVER['HTTP_REFERER']);