<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
 */

global $IZAP_ECOMMERCE;
$guid = (int) get_input('guid');
$defalt_values = array(
        'sandbox' => 'no',
);
$posted_values = get_input('params');
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