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

$izap_product = new IzapEcommerce($posted_data->guid);
$izap_product->archived = 'no';
$error = $izap_product->verify_posted_data($posted_data);
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
      $izap_product->$key = string_to_tag_array($value);
    }else {
      $izap_product->$key = $value;
    }
  }

  if(isset($posted_data->comming_soon[0]) && $posted_data->comming_soon[0] == 'yes') {
    $izap_product->comming_soon = 'yes';
  }else {
    $izap_product->comming_soon = 'no';
  }

  if($izap_product->save()) {
    if(!$izap_product->saveFiles($edit_mode)) {
      $izap_product->delete();
      register_error(elgg_echo('izap-ecommerce:error_uploading_file'));
    }else {

      if(isset ($posted_data->parent_of)) {
        $izap_product->archiveOldProduct($posted_data->parent_of);
      }
      // add to river
      add_to_river(
              func_get_template_path_byizap(
              array('plugin' => 'izap-ecommerce', 'type' => 'river')) .$river_action ,
              $river_action,
              get_loggedin_userid(),
              $izap_product->guid
      );
      unset_posted_data_izap_ecommerce('izap_product');
      system_message(elgg_echo('izap-ecommerce:saved_successfully'));
      forward($izap_product->getUrl());
    }
  }else {
    register_error(elgg_echo('izap-ecommerce:error_saving'));
  }
}
forward($_SERVER['HTTP_REFERER']);
exit;