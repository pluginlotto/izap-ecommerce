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
      $this->setMimeType($_FILES['file']['type']);
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
      $this->file_path = $this->file_prefix . $_FILES['file']['name'];
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
      $this->image_mime_type = $_FILES['image']['type'];
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
    }else {
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

  public function archiveOldProduct($old_guid) {
    $old_product = get_entity($old_guid);
    if($old_product) {
      $old_childs = $old_product->children;
      if(!empty($old_childs)) {
        if(!is_array($old_childs)) {
          $old_childs = array($old_childs);
        }
      }
      $new_childs = array_merge((array) $old_childs, (array) $old_product->guid);
      $old_product->archived = 'yes';
      $old_product->parent_guid = $this->guid;
      $old_product->save();

      // save some imp data
      $this->children = $new_childs;
      if(empty ($old_product->code)) {
        $old_product->code = func_generate_unique_id();
      }
      $this->code = $old_product->code;
      if((string)$this->image_path == '') {
        $this->copyOldFiles($old_product);
      }
    }

    return FALSE;
  }

  public function isArchived() {
    if($this->archived == 'yes') {
      return TRUE;
    }

    return FALSE;
  }

  public function copyOldFiles($old_product) {
    if($old_product) {
      $old_file_handler = new ElggFile();
      $old_file_handler->owner_guid = $old_product->owner_guid;
      $old_file_handler->setFilename($old_product->image_path);

      $this->image_extension = $old_product->image_extension;
      $new_file_handler = new ElggFile();
      $new_file_handler->owner_guid = $this->owner_guid;
      $new_file_handler->setFilename($this->file_prefix . 'icon.' . $this->image_extension);

      if(copy($old_file_handler->getFilenameOnFilestore(), $new_file_handler->getFilenameOnFilestore())) {
        $this->image_path = $this->file_prefix . 'icon.' . $this->image_extension;
      }

      $old_file_handler->close();
      $new_file_handler->close();
      unset ($old_file_handler, $new_file_handler);

      $old_file_handler = new ElggFile();
      $old_file_handler->owner_guid = $old_product->owner_guid;

      $new_file_handler = new ElggFile();
      $new_file_handler->owner_guid = $this->owner_guid;

      $screenshots = unserialize($old_product->screenshots);
      if(sizeof($screenshots)) {
        foreach($screenshots as $thumb) {
          // main image
          $new_file_handler->setFilename($this->file_prefix . $thumb);
          $old_file_handler->setFilename($old_product->file_prefix . $thumb);
          @copy($old_file_handler->getFilenameOnFilestore(), $new_file_handler->getFilenameOnFilestore());

          // thumb
          $new_file_handler->setFilename($this->file_prefix . 'thumb_' . $thumb);
          $old_file_handler->setFilename($old_product->file_prefix . 'thumb_' .  $thumb);
          @copy($old_file_handler->getFilenameOnFilestore(), $new_file_handler->getFilenameOnFilestore());
        }
        $this->screenshots = $old_product->screenshots;
        $this->screenshot_thumbs = $old_product->screenshot_thumbs;
      }

      $old_file_handler->close();
      $new_file_handler->close();
      unset ($old_file_handler, $new_file_handler);
    }
  }

  public function delete() {
    // check for the child products
    $childers = get_archived_products_izap_ecommerce($this, TRUE);
    if($childers) {
      $last_archived = get_entity(max($childers));
        $last_archived->archived = $this->archived;
    }

    $dir = dirname($this->getFilenameOnFilestore($this->file_prefix))."/";
    $items = glob($dir . '*.*');
    if(is_array($items) && sizeof($items)) {
      foreach($items as $file) {
        @unlink($file);
      }
    }
    @rmdir($dir);
    return parent::delete();
  }

  public function canDownload($user = FALSE) {
    if(!$user) {
      $user = get_loggedin_user();
    }

    // if it is free
    if(!$this->getPrice(FALSE)) {
      return TRUE;
    }

    // check for user then
    if(!$user) {
      return FALSE;
    }

    // if admin loggeding
    if(isadminloggedin()) {
      return TRUE;
    }

    // if the user have already bought it
    $purchased = 'purchased_' . $this->guid;
    if($user->$purchased == 'yes') {
      return TRUE;
    }

    $purchased = 'purchased_' . $this->code;
    if(get_plugin_setting('allow_to_download_upgraded_version', GLOBAL_IZAP_ECOMMERCE_PLUGIN) == 'yes' && $user->$purchased == 'yes') {
      return TRUE;
    }

    return FALSE;
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
    $url = $IZAP_ECOMMERCE->link . 'icon/' . $this->guid . '/' . $this->makeImageSize($size) . '/'.friendly_title($this->title).'.jpg';
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
      $price = $this->makePrice('max');
    }

    if($format) {
      return $IZAP_ECOMMERCE->currency_sign . (int)$price;
    }else {
      return (int)$price;
    }
  }

  public function getDownloads() {
    return (int) $this->total_downloads;
  }

  public function getUserPrice($user) {
    $user_guid = get_loggedin_userid();
    $price_array = (array) unserialize($this->user_pirce_array);

    if(!isset ($price_array[$user_guid])) {
      $price_array[$user_guid] = $this->makePrice();
      self::get_access();
      $this->user_pirce_array = serialize((array) $price_array);
      self::remove_access();
    }

    $current_price = $price_array[$user_guid];
    return $current_price;
  }

  function makePrice($type = 'rand') {
    $price = $this->price;
    if(strstr($price, '-')) {
      $price_range = explode('-', $price);
      // casting whole array as int
      foreach($price_range as $val) {
        $price_range_array[] = (int) $val;
      }
      switch ($type) {
        case "rand":
          return rand(min($price_range_array), max($price_range_array));
          break;

        case "max":
          return max($price_range_array);
          break;

        case "min":
          return min($price_range_array);
          break;

        default:
          return max($price_range_array);
          break;
      }
    }else {
      return (int) $price;
    }
  }

  public static function getWishList($user = false) {
    if(!$user) {
      $user = get_loggedin_user();
    }
    if(!$user) {
      return FALSE;
    }

    $wishlist = $user->izap_wishlist;
    if(!is_array($wishlist) && (int) $wishlist) {
      $wishlist = array($wishlist);
    }
    foreach($wishlist as $pro) {
      if(get_entity($pro)) {
        $return[] = $pro;
      }
    }

    return $return;
  }

  public static function countWishtlistItems() {
    $wishlist = self::getWishList();
    return (int) count($wishlist);

  }

  public static function isInWishlist($product_guid) {
    $wishlist = self::getWishList();

    if($wishlist) {
      return in_array($product_guid, $wishlist);
    }

    return FALSE;
  }

  public static function notifyAdminForNewOrder($order) {
    global $CONFIG;
    if(($order instanceof ElggObject) && $order->getSubtype() == 'izap_order') {
      $site_admin = func_get_admin_entities_byizap(array('limit' => 1));
      notify_user(
              $site_admin[0]->guid,
              $CONFIG->site->guid,
              elgg_echo('izap-ecommerce:new_order'),
              sprintf(elgg_echo('izap-ecommerce:new_order_description'), $order->getURL(), $order->getURL())
      );
    }
  }

  public static function sendOrderNotification($order) {
    global $CONFIG;
    if(($order instanceof ElggObject) && $order->getSubtype() == 'izap_order') {
      notify_user(
              $order->owner_guid,
              $CONFIG->site->guid,
              elgg_echo('izap-ecommerce:order_processed'),
              elgg_echo('izap-ecommerce:order_processed_message') . $order->getURL()
      );

      self::notifyAdminForNewOrder($order);
    }
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
    global $CONFIG, $IZAP_ECOMMERCE;

    $categories = '<div class="contentWrapper">'.
            elgg_view('categories/list', array('baseurl' => $CONFIG->wwwroot . 'search/?subtype='.$IZAP_ECOMMERCE->object_name.'&tagtype=universal_categories&tag=')).
            '</div>';
    if($remove_cart) {
      $body = elgg_view_layout('two_column_left_sidebar', '', $body, $categories);
    }else {
      $body = elgg_view_layout('two_column_left_sidebar', izap_view_cart(), $body, $categories);
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
    $item_code = 'item_code_' . $i;

    $order->$item_name = $product['name'];
    $order->$item_price = $product['amount'];
    $order->$item_guid = $product['guid'];
    $order->$item_code = $product['code'];

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
  $order->payment_transaction_id = 'no';
  $order->cart_id = $cart_id;

  if($order->save()) {
    return $order;
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

  if(isadminloggedin()) {
    return TRUE;
  }

  if($order->confirmed == 'no') {
    register_error(elgg_echo('izap-ecommerce:not_processed_properly'));
    forward($IZAP_ECOMMERCE->link . 'order');
  }

  if(!isadminloggedin() && $order->owner_guid != get_loggedin_userid()) {
    register_error(elgg_echo('izap-ecommerce:no_access'));
    forward();
  }

  $cart_id = get_from_session_izap_ecommerce('cart_id');
  if($cart_id == $order->cart_id) {
    remove_from_session_izap_ecommerce('izap_cart');
  }
}

function func_save_cart_izap_ecommerce($event, $object_type, $object) {
  return func_save_wishlist_izap_ecommerce();
}

function func_save_wishlist_izap_ecommerce($products = array(), $user = FALSE) {
  if(!($user instanceof ElggUser)) {
    $user = get_loggedin_user();
    if(!$user) {
      return FALSE;
    }
  }

  if(!is_array($products) && (int)$products > 0) {
    $products = array($products);
  }
  if(!sizeof($products)) {
    $products = get_from_session_izap_ecommerce('izap_cart');
  }

  if(!sizeof($products)) {
    return FALSE;
  }

  $old_wishlist = IzapEcommerce::getWishList($user);
  $new_wishlist = array_unique(array_merge((array) $old_wishlist, (array) $products));

  $user->izap_wishlist = $new_wishlist;
  return TRUE;
}

function func_remove_from_wishlist_izap_ecommerce($products, $user = FALSE) {
  if(!($user instanceof ElggUser)) {
    $user = get_loggedin_user();
    if(!$user) {
      return FALSE;
    }
  }

  if(!is_array($products)) {
    $products = array($products);
  }

  $old_wishlist = $user->izap_wishlist;
  $new_wishlist = array_unique(array_diff((array)$old_wishlist, $products));
  $user->izap_wishlist = $new_wishlist;

  return TRUE;
}

function get_archived_products_izap_ecommerce($product, $return_array = FALSE) {
  global $IZAP_ECOMMERCE;

  $children = (array) $product->children;

  if(sizeof($children)) {
    if($return_array) {
      return $children;
    }
    foreach($children as $child) {
      $child_product = get_entity($child);
      if($child_product) {
        $return_array[$child] = $child_product;
      }
    }
  }

  krsort($return_array);
  return $return_array;
}

function get_default_listing_options_izap_ecommerce($array = array()) {
  $options['type'] = 'object';
  $options['subtype'] = GLOBAL_IZAP_ECOMMERCE_SUBTYPE;
  $options['limit'] = izap_plugin_settings(array(
    'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
    'setting_name' => 'izap_product_limit',
    'value' => 10
  ));
  $options['full_view'] = FALSE;
  $options['offset'] = get_input('offset', 0);
  $options['view_type_toggle'] = TRUE;
  $options['pagination'] = TRUE;
  $options['metadata_name'] = 'archived';
  $options['metadata_value'] = 'no';


  return array_merge($options, $array);
}

function save_order_with_user_izap_ecommerce($order) {
  for($i=0; $i<$order->total_items; $i++) {
    $item_guid = 'item_guid_' . $i;
    $item_code = 'item_code_' . $i;
    $provided['guid'] = $order->owner_guid;
    $provided['metadata'] = array(
            'purchased_' . $order->$item_guid => 'yes',
            'purchased_' . $order->$item_code => 'yes',
    );
    func_izap_update_metadata($provided);
  }
}