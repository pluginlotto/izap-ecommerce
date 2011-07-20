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

$posted_data = get_posted_data_izap_ecommerce('izap_product');

$product = new IzapEcommerce($posted_data->guid);
if(!$posted_data->guid) {
  $product->archived = 'no';
  $product->avg_rating = (int) 0;
}
$error = $product->verify_posted_data($posted_data);
if($error) {
  register_error(elgg_echo('izap-ecommerce:missing_required_info'));
}else {
  if((int)$posted_data->guid) {
    $edit_mode = TRUE;
  }else {
    $edit_mode = FALSE;
  }
  $river_action = (($posted_data->guid) ? 'updated' : 'created');
  unset ($posted_data->guid);
  foreach($posted_data as $key => $value) {
    if($key == 'tags') {
      $product->$key = string_to_tag_array($value);
    }else {
      $product->$key = $value;
    }
  }

  if(isset($posted_data->comming_soon[0]) && $posted_data->comming_soon[0] == 'yes') {
    $product->comming_soon = 'yes';
  }else {
    $product->comming_soon = 'no';
  }

  if(isset($posted_data->categories)){
      $product->categories = $posted_data->categories;
  }
  if($product->save()) {
    if(!$product->saveFiles($edit_mode)) {
      delete_entity($product->guid);
      register_error(elgg_echo('izap-ecommerce:error_uploading_file'));
    }else {
      $product->user_pirce_array = array();
      if(!(bool)$product->code) {
        $product->code = func_generate_unique_id();
      }
      // check if it is new version
      if(isset ($posted_data->parent_of)) {
        $product->archiveOldProduct($posted_data->parent_of);
      }

      // add to river
      add_to_river(
              $IZAP_ECOMMERCE->river.$river_action ,
              $river_action,
              elgg_get_logged_in_user_entity(),
              $product->guid
      );
      unset_posted_data_izap_ecommerce('izap_product');
      system_message(elgg_echo('izap-ecommerce:saved_successfully'));
      forward($product->getUrl());
    }
  }else {
    register_error(elgg_echo('izap-ecommerce:error_saving'));
  }
}
forward($_SERVER['HTTP_REFERER']);
exit;