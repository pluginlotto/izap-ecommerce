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

class IzapEcommerce extends ElggFile {
  public $required_fields = array(
          'title', 'description', 'file'
  );

  public $allowed_image_types = array(
          'jpeg', 'jpg', 'gif', 'png'
  );

  public $allowed_file_types = array(
          'zip', 'gz', 'tar', 'tgz'
  );

  public $file_prefix;

  function  __construct($guid = NULL) {
    parent::__construct($guid);
  }

  function initialise_attributes() {
    global $IZAP_ECOMMERCE;
    parent::initialise_attributes();
    $this->attributes['subtype'] = $IZAP_ECOMMERCE->object_name;
  }

  public function verify_posted_data($data) {
    // make sure it is object else convert it
    $error = FALSE;
    $data = array_to_object_izap_ecommerce($data);
    foreach($data as $key => $value) {
      if(in_array($key, $this->required_fields) && empty($value)) {
        $error = TRUE;
      }
    }

    return $error;
  }

  public function saveFiles($edit_mode = FALSE) {

    // set some default values so that we can.. make things work proper
    if($edit_mode) {
    $file_written = TRUE;
    }
    $image_written = TRUE;
    $dir = dirname($this->getFilenameOnFilestore($this->file_prefix))."/";

    // start uploading product file
    $content = get_uploaded_file('file');
    if($content != '' && $_FILES['file']['error'] == 0) {
      $file_extension = get_extension_izap_ecommerce($_FILES['file']['name']);
      if(!in_array($file_extension, $this->allowed_file_types)) {
        return $edit_mode;
      }

      $items = glob($dir . '*.' . (($this->file_extension) ? $this->file_extension : $file_extension));
      if(is_array($items) && sizeof($items)) {
        foreach($items as $file) {
          unlink($file);
        }
      }

      $this->file_extension = $file_extension;
      $this->file_path = $this->file_prefix . $this->slug . '.' . $this->file_extension;
      $this->setFilename($this->file_path);
      $this->open("write");
      $file_written = $this->write($content);
    }

    // start uploading image file
    $image_content = get_uploaded_file('image');
    if($image_content != '' && $_FILES['image']['error'] == 0) {
      $file_extension = get_extension_izap_ecommerce($_FILES['image']['name']);
      $items = glob($dir . '*.' . (($this->image_extension) ? $this->image_extension : $file_extension));
      if(is_array($items) && sizeof($items)) {
        foreach($items as $file) {
          unlink($file);
        }
      }

      $this->image_extension = $file_extension;
      if(in_array($this->image_extension, $this->allowed_image_types)) {
        $this->image_path = $this->file_prefix . 'icon.' . $this->image_extension;
        $image = get_uploaded_file('image');
        $this->setFilename($this->image_path);
        $this->open("write");
        $image_written = $this->write($image);
      }
    }

    if($file_written && $image_written) {
      return TRUE; // return if there is no content to upload and it is edit mode
    }elseif($edit_mode) {
      return $file_written || $image_written;
    }else{
      return $edit_mode;
    }
  }

  public function save() {
    global $IZAP_ECOMMERCE;
    $this->slug = friendly_title($this->title);
    $return = parent::save();
    if($return)
      $this->file_prefix = $IZAP_ECOMMERCE->plugin_name . '/' . $this->guid . '/';
    return $return;
  }

  public function delete() {
    $dir = dirname($this->getFilenameOnFilestore($this->file_prefix))."/";
    $items = glob($dir . '*.*');
    if(is_array($items) && sizeof($items)) {
      foreach($items as $file) {
        unlink($file);
      }
    }
    rmdir($dir);
    return parent::delete();
  }
  
  public function makeImageSize($size) {
    switch ($size) {
      case "tiny":
        return '25x25';
        break;

      case "small":
        return '40x40';
        break;

      case "medium":
        return '100x100';
        break;

      case "large":
        return '200x200';
        break;

      case "master":
        return '300x300';
        break;

      case "orignal":
        return 'naxna';
        break;

      default:
        return '100x100';
        break;
    }
  }
  
  public function getIcon($size) {
    global $IZAP_ECOMMERCE;
    $url = $IZAP_ECOMMERCE->link . 'icon/' . $this->guid . '/' . $this->makeImageSize($size) . '/'.time().'.jpg';
    return $url;
  }

  public function getFile() {
    global $IZAP_ECOMMERCE;
    
    $file_handler = new ElggFile();
    $file_handler->owner_guid = $this->owner_guid;
    $file_handler->setFilename($this->file_path);
    $file_handler->open("read");
    if(file_exists($file_handler->getFilenameOnFilestore())) {
      $content = $file_handler->grabFile();
    }else {
      $content = false;
    }

    return $content;
  }

  public function getPrice($format = TRUE) {
    global $IZAP_ECOMMERCE;

    if(isloggedin()) {
      $price = $this->getUserPrice();
    }else {
      $price_range_array = explode('-', $this->price);
      $price = ($price_range_array[1]) ? $price_range_array[1] : $price_range_array[0];
    }

    if($format) {
      return $IZAP_ECOMMERCE->currency_sign . (int)$price;
    }else {
      return (int)$price;
    }
  }

  public function getUserPrice($user) {
    $user_guid = get_loggedin_userid();

    $price_array = (array) unserialize($this->user_pirce_array);
    if(!isset ($price_array[$user_guid])) {
      $price_range_array = explode('-', $this->price);
      $current_price = rand((int) $price_range_array[0], (int) $price_range_array[1]);
      $price_array[$user_guid] = $current_price;
      self::get_access();
      $this->user_pirce_array = serialize((array) $price_array);
      self::remove_access();
    }else {
      $current_price = $price_array[$user_guid];
    }

    return $current_price;
  }

  public function getRating() {
    return (int)$this->getAnnotationsAvg('generic_rate');
  }

  public function isAvailable() {
    if($this->comming_soon == 'yes') {
      return FALSE;
    }

    return TRUE;
  }
  
  public function draw_page($title, $body, $remove_cart = FALSE) {
    if($remove_cart) {
      $body = elgg_view_layout('two_column_left_sidebar', '', $body);
    }else {
      $body = elgg_view_layout('two_column_left_sidebar', izap_view_cart(), $body);
    }
    
    page_draw($title, $body);
  }

  public function izap_get_plugin_entity() {
    global $IZAP_ECOMMERCE;
    return find_plugin_settings($IZAP_ECOMMERCE->plugin_name);
  }

  public function get_access($functionName = 'get_full_access_IZAP') {
    register_plugin_hook('permissions_check', 'all', $functionName);
    register_plugin_hook('container_permissions_check', 'all', $functionName);
    register_plugin_hook('permissions_check:metadata', 'all', $functionName);
  }

  public function remove_access($functionName = 'get_full_access_IZAP') {
    global $CONFIG;
    if (isset($CONFIG->hooks['permissions_check']['object'])) {
      foreach ($CONFIG->hooks['permissions_check']['object'] as $key => $hookFunction) {
        if ($hookFunction == $functionName) {
          unset($CONFIG->hooks['permissions_check']['object'][$key]);
        }
      }
    }
  }

}

function izap_view_cart($full = FALSE) {
  global $IZAP_ECOMMERCE;
  $cart = get_from_session_izap_ecommerce('izap_cart');
  if(is_array($cart) && sizeof($cart)) {
    return elgg_view($IZAP_ECOMMERCE->views . 'cart', array('cart' => $cart, 'full' => $full));
  }else {
    return '';
  }
}

function izap_view_header_cart() {
  global $IZAP_ECOMMERCE;
  $cart = get_from_session_izap_ecommerce('izap_cart');
  return elgg_view($IZAP_ECOMMERCE->views . '/cart/header_cart', array('cart' => $cart));
}

function get_link_expire_time_izap_ecommerce() {
  $plugin = IzapEcommerce::izap_get_plugin_entity();
  return (int) $plugin->link_expire_time;
}

function get_loaded_data_izap_ecommerce($post_name = '', $entity = '') {
  $posted_data = get_from_session_izap_ecommerce($post_name . '_posted_data', TRUE);
  if($posted_data) {
    return $posted_data;
  }

  if($entity instanceof IzapEcommerce) {
    return $entity;
  }
  return new stdClass();
}

function array_to_object_izap_ecommerce($array) {
  if(is_object($array)) {
    return $array;
  }

  $object = new stdClass();
  if(is_array($array) && sizeof($array)) {
    foreach($array as $key => $value) {
      if(!empty ($key)) {
        $object->$key = $value;
      }
    }
  }

  return $object;
}

function get_posted_data_izap_ecommerce($name) {
  $posted_data = get_input($name);
  $posted_object = array_to_object_izap_ecommerce($posted_data);
  add_to_session_izap_ecommerce($name . '_posted_data', $posted_object);
  return $posted_object;
}

function unset_posted_data_izap_ecommerce($name) {
  remove_from_session_izap_ecommerce($name . '_posted_data');
}

function add_to_session_izap_ecommerce($key, $data) {
  $_SESSION['izap'][$key] = $data;
}

function get_from_session_izap_ecommerce($key, $remove = FALSE) {
  if(isset ($_SESSION['izap'][$key])) {
    $data = $_SESSION['izap'][$key];
    if($remove) {
      remove_from_session_izap_ecommerce($key);
    }
    return $data;
  }

  return FALSE;
}

function remove_from_session_izap_ecommerce($key) {
  unset ($_SESSION['izap'][$key]);
}

function get_product_izap_ecommerce($guid) {
  $entity = get_entity($guid);
  if($entity) {
    if($entity instanceof IzapEcommerce) {
      return $entity;
    }
  }

  return FALSE;
}

function get_extension_izap_ecommerce($file_name) {
  return strtolower(end(explode('.', $file_name)));
}

function array_to_plugin_settings_izap_iecommerce($value) {
  if(!is_array($value)) {
    return $value;
  }

  if(count($value) == 1) {
    $new_value = current($value);
  }else {
    $new_value = implode('|', $value);
  }

  return $new_value;
}

function plugin_value_to_array_izap_iecommerce($value) {
  return explode('|', $value);
}

function get_billing_info_izap_ecommerce($user_guid = 0) {
  if(!$user_guid) {
    $user = get_loggedin_user();
  }else {
    $user = get_user($guid);
  }

  if(!$user) {
    return FALSE;
  }

  $billing_info = $user->billing_info;
}

function get_full_access_IZAP($hook, $entity_type, $returnvalue, $params) {
  return TRUE;
}

function save_order_izap_ecommerce($items, $cart_id) {
  $cart = get_from_session_izap_ecommerce('izap_cart');
  $order = new ElggObject();
  $order->subtype = 'izap_order';
  $order->access_id = ACCESS_PUBLIC;

  $i=0;
  $total_price=0;
  foreach($items as $product) {
    $item_name = 'item_name_' . $i;
    $item_price = 'item_price_' . $i;
    $item_guid = 'item_guid_' . $i;

    $order->$item_name = $product['name'];
    $order->$item_price = $product['amount'];
    $order->$item_guid = $product['guid'];

    $i++;
    $total_price += (int)$product['amount'];

    $description .= $product['name'] . '<br />';
    if($title == '') {
      $title = $product['name'];
    }else {
      $title .= '...';
    }
  }

  $order->total_items = $i;
  $order->total_amount = $total_price;
  $order->title = $title;
  $order->description = $description;
  $order->confirmed = 'no';
  $order->paypal_invoice_id = 'no';
  $order->cart_id = $cart_id;

  if($order->save()) {
    return $order->guid;
  }


}

function create_product_download_link_izap_ecommerce($order, $product_guid, $time = '') {
  global $CONFIG;

  $owner_guid = get_loggedin_userid();
  if($time == '') {
    $time = md5(microtime());
  }

  $hash = create_hash_izap_ecommerce($order->guid, $product_guid, $time, $owner_guid);

  $download_link = $CONFIG->wwwroot . 'action/izap_ecommerce/download?o=' . $order->guid;
  $download_link = elgg_add_action_tokens_to_url($download_link);
  $download_link = $download_link . '&p=' . $product_guid . '&t=' . $time . '&h=' . $hash;

  return $download_link;
}

function create_hash_izap_ecommerce($order_guid, $product_guid, $time, $owner_guid) {
  return md5($order->guid . $owner_guid . $product_guid . $time);
}

function verify_order_izap_ecommerce($order) {
  global $IZAP_ECOMMERCE;

  if($order->confirmed == 'no') {
    register_error(__('not_processed_properly'));
    forward($IZAP_ECOMMERCE->link . 'order');
  }

  if(!isadminloggedin() && $order->owner_guid != get_loggedin_userid()) {
    register_error(__('no_access'));
    forward();
  }

  $cart_id = get_from_session_izap_ecommerce('cart_id');
  if($cart_id == $order->cart_id) {
    remove_from_session_izap_ecommerce('izap_cart');
  }
}