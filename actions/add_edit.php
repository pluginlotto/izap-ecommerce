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

admin_gatekeeper();
$posted_data = get_posted_data_izap_ecommerce('izap_product');

$izap_product = new IzapEcommerce($posted_data->guid);
$error = $izap_product->verify_posted_data($posted_data);
if($error) {
  register_error(__('missing_required_info'));
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
      $izap_product->$key = string_to_tag_array($value);
    }else {
      $izap_product->$key = $value;
    }
  }

  // set the price array
  $izap_product->user_pirce_array = '';

  if(isset($posted_data->comming_soon[0]) && $posted_data->comming_soon[0] == 'yes') {
    $izap_product->comming_soon = 'yes';
  }else {
    $izap_product->comming_soon = 'no';
  }

  if($izap_product->save()) {
    if(!$izap_product->saveFiles($edit_mode)) {
      delete_entity($izap_product->guid);
      register_error(__('error_uploading_file'));
    }else {
      // add to river
      add_to_river(
              func_get_template_path_byizap(
              array('plugin' => 'izap-ecommerce', 'type' => 'river')) .$river_action ,
              $river_action,
              get_loggedin_userid(),
              $izap_product->guid
      );
      unset_posted_data_izap_ecommerce('izap_product');
      system_message(__('saved_successfully'));
      forward($izap_product->getUrl());
    }
  }else {
    register_error(__('error_saving'));
  }
}
forward($_SERVER['HTTP_REFERER']);
exit;