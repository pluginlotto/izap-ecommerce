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

class IzapEcommerce extends IzapObject {

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

  function __construct($guid = NULL) {
    parent::__construct($guid);
  }

  /**
   * initilise attributes
   * 
   * @global ElggObject $IZAP_ECOMMERCE sharing globals
   */
  function initialise_attributes() {
    global $IZAP_ECOMMERCE;
    parent::initializeAttributes();
    $this->attributes['subtype'] = $IZAP_ECOMMERCE->object_name;
  }

  /**
   * verify posted data
   *
   * @param array $data collection of data
   *
   * @return boolean
   */
  public function verify_posted_data($data) {
    // make sure it is object else convert it
    $error = false;
    $data = array_to_object_izap_ecommerce($data);
    foreach ($data as $key => $value) {
      if (in_array($key, $this->required_fields) && empty($value)) {
        $error = true;
      }
    }

    return $error;
  }

  /**
   *
   * @param string $edit_mode bolean
   * @return mixed
   */
  public function saveFiles($edit_mode = false) {

    // set some default values so that we can.. make things work proper
    if ($edit_mode) {
      $file_written = true;
    }
    $image_written = true;
    $dir = dirname($this->getFilenameOnFilestore($this->file_prefix)) . "/";

    // start uploading product file
    $content = get_uploaded_file('file');
    if ($content != '' && $_FILES['file']['error'] == 0) {
      $this->setMimeType($_FILES['file']['type']);
      $file_extension = get_extension_izap_ecommerce($_FILES['file']['name']);
      if (!in_array($file_extension, $this->allowed_file_types)) {
        return $edit_mode;
      }

      $items = glob($dir . '*.' . (($this->file_extension) ? $this->file_extension : $file_extension));
      if (is_array($items) && sizeof($items)) {
        foreach ($items as $file) {
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
    if ($image_content != '' && $_FILES['image']['error'] == 0) {
      $file_extension = get_extension_izap_ecommerce($_FILES['image']['name']);
      $items = glob($dir . '*.' . (($this->image_extension) ? $this->image_extension : $file_extension));
      if (is_array($items) && sizeof($items)) {
        foreach ($items as $file) {
          unlink($file);
        }
      }

      $this->image_extension = $file_extension;
      $this->image_mime_type = $_FILES['image']['type'];
      if (in_array($this->image_extension, $this->allowed_image_types)) {
        $this->image_path = $this->file_prefix . 'icon.' . $this->image_extension;
        $image = get_uploaded_file('image');
        $this->setFilename($this->image_path);
        $this->open("write");
        $image_written = $this->write($image);
      }
    }

    if ($file_written && $image_written) {
      return true; // return if there is no content to upload and it is edit mode
    } elseif ($edit_mode) {
      return $file_written || $image_written;
    } else {
      return $edit_mode;
    }
  }

  /**
   * check the file size
   *
   * @return mixed
   */
  public function size() {
    return strlen($this->getFile());
  }

  /**
   * save fuction
   *
   * @global ElggObject $IZAP_ECOMMERCE sharing globals
   *
   * @return integer 
   */
  public function save() {
    global $IZAP_ECOMMERCE;
    $this->slug = friendly_title($this->title);
    $return = parent::save();
    if ($return)
      $this->file_prefix = $IZAP_ECOMMERCE->plugin_name . '/' . $this->guid . '/';
    return $return;
  }

  /**
   * archive old products
   *
   * @param integer $old_guid entity old guid
   *
   * @return <type>
   */
  public function archiveOldProduct($old_guid) {
    $old_product = get_entity($old_guid);
    if ($old_product) {
      $old_childs = $old_product->children;
      if (!empty($old_childs)) {
        if (!is_array($old_childs)) {
          $old_childs = array($old_childs);
        }
      }
      $new_childs = array_merge((array) $old_childs, (array) $old_product->guid);
      $old_product->archived = 'yes';
      $old_product->parent_guid = $this->guid;
      $old_product->save();

      // save some imp data
      $this->children = $new_childs;
      if (empty($old_product->code)) {
        $old_product->code = func_generate_unique_id();
      }
      $this->code = $old_product->code;
      $this->avg_rating = $old_product->avg_rating;
      $this->total_views = $old_product->total_views;
      $this->total_downloads = $old_product->total_downloads;

      if ((string) $this->image_path == '') {
        $this->copyOldFiles($old_product);
      }
    }

    return false;
  }

  /**
   * check weather the product is archive or not
   * 
   * @return bolean
   */
  public function isArchived() {
    if ($this->archived == 'yes') {
      return true;
    }

    return false;
  }

  /**
   * copy old files
   *
   * @param ElggObject $old_product product entity
   */
  public function copyOldFiles($old_product) {
    if ($old_product) {
      $old_file_handler = new ElggFile();
      $old_file_handler->owner_guid = $old_product->owner_guid;
      $old_file_handler->setFilename($old_product->image_path);

      $this->image_extension = $old_product->image_extension;
      $new_file_handler = new ElggFile();
      $new_file_handler->owner_guid = $this->owner_guid;
      $new_file_handler->setFilename($this->file_prefix . 'icon.' . $this->image_extension);

      if (copy($old_file_handler->getFilenameOnFilestore(), $new_file_handler->getFilenameOnFilestore())) {
        $this->image_path = $this->file_prefix . 'icon.' . $this->image_extension;
      }

      $old_file_handler->close();
      $new_file_handler->close();
      unset($old_file_handler, $new_file_handler);

      $old_file_handler = new ElggFile();
      $old_file_handler->owner_guid = $old_product->owner_guid;

      $new_file_handler = new ElggFile();
      $new_file_handler->owner_guid = $this->owner_guid;

      $screenshots = unserialize($old_product->screenshots);
      if (sizeof($screenshots)) {
        foreach ($screenshots as $thumb) {
          // main image
          $new_file_handler->setFilename($this->file_prefix . $thumb);
          $old_file_handler->setFilename($old_product->file_prefix . $thumb);
          @copy($old_file_handler->getFilenameOnFilestore(), $new_file_handler->getFilenameOnFilestore());

          // thumb
          $new_file_handler->setFilename($this->file_prefix . 'thumb_' . $thumb);
          $old_file_handler->setFilename($old_product->file_prefix . 'thumb_' . $thumb);
          @copy($old_file_handler->getFilenameOnFilestore(), $new_file_handler->getFilenameOnFilestore());
        }
        $this->screenshots = $old_product->screenshots;
        $this->screenshot_thumbs = $old_product->screenshot_thumbs;
      }

      $old_file_handler->close();
      $new_file_handler->close();
      unset($old_file_handler, $new_file_handler);
    }
  }

  /**
   * delete fuction
   *
   * @return bolean
   */
  public function delete() {
    // check for the child products
    $childers = get_archived_products_izap_ecommerce($this, true);
    if ($childers) {
      $last_archived = get_entity(max($childers));
      $last_archived->archived = $this->archived;
    }

    $dir = dirname($this->getFilenameOnFilestore($this->file_prefix)) . "/";
    $items = glob($dir . '*.*');
    if (is_array($items) && sizeof($items)) {
      foreach ($items as $file) {
        @unlink($file);
      }
    }
    @rmdir($dir);
    return parent::delete();
  }

  /**
   * check product can be downloaded or not
   *
   * @param integer $user user id
   *
   * @return bolean
   */
  public function canDownload($user = false) {
    if (!$user) {
      $user = elgg_get_logged_in_user_entity();
    }

    // if it is free
    if (!$this->getPrice(false)) {
      return TRUE;
    }

    // check for user then
    if (!$user) {
      return false;
    }

    // if admin loggeding
    if (elgg_is_admin_logged_in ()) {
      return true;
    }

    // if the user have already bought it
    if ($this->hasUserPurched($user)) {
      return true;
    }

    // if admin has set to download the product
    if (get_plugin_setting('allow_to_download_upgraded_version', GLOBAL_IZAP_ECOMMERCE_PLUGIN) == 'yes' && $this->hasUserPurchasedOldVersion($user)) {
      return true;
    }

    return false;
  }

  /**
   * check user has purchased it or not
   *
   * @param integer $user user id
   *
   * @return bolean
   */
  public function hasUserPurched($user = NULL) {
    if (!($user instanceof ElggUser)) {
      $user = get_loggedin_user();
    }

    $purchased = 'purchased_' . $this->guid;
    if ($user->$purchased == 'yes') {
      return true;
    }

    return false;
  }

  /**
   * check user has purchased old version or not
   *
   * @param integer $user user id
   *
   * @return bolean
   */
  public function hasUserPurchasedOldVersion($user = NULL) {
    if (!($user instanceof ElggUser)) {
      $user = elgg_get_logged_in_user_entity();
    }


    $purchased = 'purchased_' . $this->code;
    if ($user->$purchased == 'yes') {
      return true;
    }

    return false;
  }

  /**
   * make image size
   *
   * @param integer $size size in number
   *
   * @return integer
   */
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

  /**
   *
   * @global ElggObject $IZAP_ECOMMERCE sharing globals
   *
   * @param integer $size size of icon
   *
   * @return string
   */
  public function getIcon($size) {
    global $IZAP_ECOMMERCE;
    $url = $IZAP_ECOMMERCE->link . 'icon/' . $this->guid . '/' . $this->makeImageSize($size) . '/' . $this->time_updated . elgg_get_friendly_title($this->title) . '.jpg';
    return $url;
  }

  /**
   * get file
   *
   * @global ElggObject $IZAP_ECOMMERCE sharing globals
   * 
   * @return mixed
   */
  public function getFile() {
    global $IZAP_ECOMMERCE;

    $file_handler = new ElggFile();
    $file_handler->owner_guid = $this->owner_guid;
    $file_handler->setFilename($this->file_path);
    $file_handler->open("read");
    if (file_exists($file_handler->getFilenameOnFilestore())) {
      $content = $file_handler->grabFile();
    } else {
      $content = false;
    }

    return $content;
  }

  /**
   * get the price
   *
   * @global ElggObject $IZAP_ECOMMERCE sharing globals
   *
   * @param mixed $format currency format
   *
   * @return integer
   */
  public function getPrice($format = TRUE) {
    global $IZAP_ECOMMERCE;

    if (elgg_is_logged_in ()) {
      $price = $this->getUserPrice();
    } else {
      $price = $this->makePrice('max');
    }

    // calculate the discount if any
    $price = $this->calculateDiscountedPrice($price);

    if ($format) {
      return $IZAP_ECOMMERCE->currency_sign . $price;
    } else {
      return (int) $price;
    }
  }

  /**
   * calculate discount prince if any
   *
   * @param integer $price integer
   *
   * @return integer
   */
  public function calculateDiscountedPrice($price) {
    if ($this->hasUserPurchasedOldVersion()) {
      $disount = $this->discount;
      if (strpos($disount, '%')) {
        $disount = ($disount / 100 * $price);
      }
    }

    return $price - $disount;
  }

  /**
   * get group attributes
   *
   * @return mixed
   */
  public function getAttributeGroups() {
    $group = unserialize($this->attrib_groups);
    if (is_array($group) && count($group)) {
      return $group;
    }

    return false;
  }

  /**
   * get attribute
   *
   * @param string|integer $group_key key of the array
   *
   * @return mixed
   */
  public function getAttribute($group_key) {
    $attribs = unserialize($this->attribs);
    $attribs = $attribs[$group_key];
    if (is_array($attribs) && count($attribs)) {
      return $attribs;
    }

    return false;
  }

  /**
   * get download
   *
   * @return integer
   */
  public function getDownloads() {
    return (int) $this->total_downloads;
  }

  /**
   * get user price
   *
   * @param integer $user price from user
   *
   * @return integer
   */
  public function getUserPrice($user) {
    // send max price as new version will not have variable price for different users
    return $this->makePrice('max');

    // we'll delete this section once tested TODO: delete this code
    if (elgg_instanceof($user, ElggUser)) {
      $user_guid = $user->guid;
    } else {
      $user_guid = elgg_get_logged_in_user_guid();
    }
    $price_array = (array) unserialize($this->user_pirce_array);

    if (!isset($price_array[$user_guid])) {
      $price_array[$user_guid] = $this->makePrice();
      self::get_access();
      $this->user_pirce_array = serialize((array) $price_array);
      self::remove_access();
    }

    $current_price = $price_array[$user_guid];
    return $current_price;
  }

  /**
   * make price
   *
   * @param string $type price
   * @return integer
   */
  function makePrice($type = 'rand') {
    $price = $this->price;
    if (strstr($price, '-')) {
      $price_range = explode('-', $price);
      // casting whole array as int
      foreach ($price_range as $val) {
        $price_range_array[] =  (float) $val;
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
    } else {
      return $price;
    }
  }

  /**
   * get wish list
   *
   * @param ElggObject $user object of the user
   *
   * @return mixed
   */
  public static function getWishList($user = false) {
    if (!$user) {
      $user = elgg_get_logged_in_user_entity();
    }
    if (!$user) {
      return false;
    }

    $wishlist = $user->izap_wishlist;
    if (!is_array($wishlist) && (int) $wishlist) {
      $wishlist = array($wishlist);
    }
    foreach ($wishlist as $pro) {
      if (get_entity($pro)) {
        $return[] = $pro;
      }
    }

    return $return;
  }

  /**
   * count of wish list items
   *
   * @return integer
   */
  public static function countWishtlistItems() {
    $wishlist = self::getWishList();
    return (int) count($wishlist);
  }

  /**
   * is in wish list
   *
   * @param integer $product_guid
   *
   * @return mixed
   */
  public static function isInWishlist($product_guid) {
    $wishlist = self::getWishList();

    if ($wishlist) {
      return in_array($product_guid, $wishlist);
    }

    return false;
  }

  /**
   * notify admin on new order
   *
   * @global ElggObject $CONFIG array of global variable
   *
   * @param ElggObject $order order details
   */
  public static function notifyAdminForNewOrder($order) {
    global $CONFIG;
    if (($order instanceof ElggObject) && $order->getSubtype() == 'izap_order') {
      $site_admin = func_get_admin_entities_byizap(array('limit' => 1));

      IzapBase::sendMail(array(
            'to' => get_user($site_admin[0]->guid)->email,
            'from' => $CONFIG->site->email,
            'from_username' => $CONFIG->site->name,
            'subject' => elgg_echo('izap-ecommerce:new_order'),
            'msg' => elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/email_template', array('entity' => $order))
          ));
    }
  }

  /**
   * send order notification
   *
   * @global ElggObject $CONFIG array of global vars
   *
   * @param ElggObject $order product entity
   */
  public static function sendOrderNotification($order) {
    global $CONFIG;
    if (($order instanceof ElggObject) && $order->getSubtype() == 'izap_order') {

      IzapBase::sendMail(array(
            'to' => get_user($order->owner_guid)->email,
            'from' => $CONFIG->site->email,
            'from_username' => $CONFIG->site->name,
            'subject' => elgg_echo('izap-ecommerce:order_processed'),
            'msg' => elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/email_template', array('entity' => $order))
          ));

      self::notifyAdminForNewOrder($order);
    }
  }

  /**
   * get rating
   *
   * @return integer
   */
  public function getRating() {
    return (int) $this->getAnnotationsAvg('generic_rate');
  }

  /**
   * check availablity
   *
   * @return bolean
   */
  public function isAvailable() {
    if ($this->comming_soon == 'yes') {
      return false;
    }

    return true;
  }

  /**
   * draw the page
   *
   * @global ElggObject $CONFIG         array of global vars
   * @global ElggObject $IZAP_ECOMMERCE object of  izap ecommerece
   * @global Object $IZAPTEMPLATE       object of  izap template
   *
   * @param  string $title title of the page
   * @param  string $body  body of page
   * @param  bolean $remove_cart true or false
   */
  public static function draw_page($title, $body, $remove_cart = false) {
    global $CONFIG, $IZAP_ECOMMERCE, $IZAPTEMPLATE;

    $categories = '<div class="izapcontentWrapper">' .
        elgg_view('categories/list', array(
          'baseurl' => $CONFIG->wwwroot . 'search/?subtype=' . $IZAP_ECOMMERCE->object_name . '&tagtype=universal_categories&tag=')) .
        '</div>';

    $IZAPTEMPLATE->drawPage(array(
      'title' => $title,
      'area1' => (($remove_cart) ? '' : izap_view_cart()),
      'area2' => $body,
      'area3' => $categories,
    ));
  }

  /**
   * create attributes
   *
   * @global ElggObject $IZAP_ECOMMERCE object of izap ecommerece
   *
   * @param  string $default default value null
   *
   * @return html
   */
  public static function createAttributes($array = array()) {
    global $IZAP_ECOMMERCE;
    return elgg_view($IZAP_ECOMMERCE->product . 'attributes', $array);
  }

  /**
   * izap get plugin entity
   *
   * @global ElggObject $IZAP_ECOMMERCE object of IZAP_ECOMMERCE
   *
   * @return string
   */
  public function izap_get_plugin_entity() {
    global $IZAP_ECOMMERCE;
    return find_plugin_settings($IZAP_ECOMMERCE->plugin_name);
  }

  /**
   * get access
   */
  public function get_access() {
    IzapBase::getAllAccess();
  }

  /**
   * remove access
   */
  public function remove_access() {
    IzapBase::removeAccess();
  }

  /**
   * Can a user comment on this store?
   *
   * @see ElggObject::canComment()
   *
   * @param int $user_guid User guid (default is logged in user)
   *
   * @return bool
   * 
   * @since 1.8.0
   */
  public function canComment($user_guid = 0) {
    $result = parent::canComment($user_guid);
    if ($result == false) {
      return $result;
    }
    if (!$this->comments_on) {
      return false;
    }
    return true;
  }

}

/**
 * view cart
 *
 * @global ElggObject $IZAP_ECOMMERCE object of IZAP_ECOMMERCE
 *
 * @param bolean $full true or false
 *
 * @return mixed
 */
function izap_view_cart($full = false) {
  global $IZAP_ECOMMERCE;
  $cart = get_from_session_izap_ecommerce('izap_cart');
  if (is_array($cart) && sizeof($cart)) {
    return elgg_view($IZAP_ECOMMERCE->views . 'cart', array('cart' => $cart, 'full_view' => $full));
  } else {
    return null;
  }
}

/**
 * view header cart
 *
 * @global ElggObject $IZAP_ECOMMERCE object of IZAP_ECOMMERCE
 *
 * @return html
 */
function izap_view_header_cart() {
  global $IZAP_ECOMMERCE;
  $cart = get_from_session_izap_ecommerce('izap_cart');
  return elgg_view($IZAP_ECOMMERCE->views . '/cart/header_cart', array('cart' => $cart));
}

/**
 * get link expire time
 *
 * @return integer
 */
function get_link_expire_time_izap_ecommerce() {
  $plugin = IzapEcommerce::izap_get_plugin_entity();
  return (int) $plugin->link_expire_time;
}

/**
 * get loaded data
 *
 * @param string $post_name
 *
 * @param IzapEcommerce $entity name
 *
 * @return object | class object
 */
function get_loaded_data_izap_ecommerce($post_name = '', $entity = '') {
  $posted_data = get_from_session_izap_ecommerce($post_name . '_posted_data', true);
  if ($posted_data) {
    return $posted_data;
  }

  if ($entity instanceof IzapEcommerce) {
    return $entity;
  }
  return new stdClass();
}

/**
 * array to object
 * 
 * @param array $array collection of data
 *
 * @return array | object
 */
function array_to_object_izap_ecommerce(array $array) {
  if (is_object($array)) {
    return $array;
  }

  $object = new stdClass();
  if (is_array($array) && sizeof($array)) {
    foreach ($array as $key => $value) {
      if (!empty($key)) {
        $object->$key = $value;
      }
    }
  }

  return $object;
}

/**
 * get posted data
 *
 * @param string $name name
 *
 * @return object
 */
function get_posted_data_izap_ecommerce($name) {
  $posted_data = get_input($name);
  $posted_object = array_to_object_izap_ecommerce($posted_data);
  add_to_session_izap_ecommerce($name . '_posted_data', $posted_object);
  return $posted_object;
}

/**
 * unset posted data
 *
 * @param string $name name
 */
function unset_posted_data_izap_ecommerce($name) {
  remove_from_session_izap_ecommerce($name . '_posted_data');
}

/**
 * add to session
 *
 * @param string $key key of the array
 *
 * @param string $data value again the key
 */
function add_to_session_izap_ecommerce($key, $data) {
  $_SESSION['izap'][$key] = $data;
}

/**
 * get value from session
 *
 * @param string $key array key
 *
 * @param bolean $remove true false
 * 
 * @return mixed
 */
function get_from_session_izap_ecommerce($key, $remove = false) {

  if (isset($_SESSION['izap'][$key])) {
    $data = $_SESSION['izap'][$key];
    if ($remove) {
      remove_from_session_izap_ecommerce($key);
    }
    return $data;
  }

  return false;
}

/**
 * remove from session
 *
 * @param string $key array key
 */
function remove_from_session_izap_ecommerce($key) {
  unset($_SESSION['izap'][$key]);
}

/**
 * get product
 *
 * @param integer $guid
 * 
 * @global ElggObject $IZAP_ECOMMERCE object of IZAP_ECOMMERCE
 */
function get_product_izap_ecommerce($guid) {
  $entity = get_entity($guid);
  if ($entity) {
    if ($entity instanceof IzapEcommerce) {
      return $entity;
    }
  }

  return false;
}

/**
 * get extension
 *
 * @param string $file_name name of file
 *
 * @return string
 */
function get_extension_izap_ecommerce($file_name) {
  return strtolower(end(explode('.', $file_name)));
}

/**
 * array to plugin settings
 *
 * @param mixed $value data
 *
 * @return mixed
 */
function array_to_plugin_settings_izap_iecommerce($value) {
  if (!is_array($value)) {
    return $value;
  }

  if (count($value) == 1) {
    $new_value = current($value);
  } else {
    $new_value = implode('|', $value);
  }

  return $new_value;
}

/**
 * plugin value to array
 *
 * @param array $value data array
 *
 * @return array
 */
function plugin_value_to_array_izap_iecommerce($value) {
  return explode('|', $value);
}

/**
 * get billing info
 *
 * @param integer $user_guid user guid
 * 
 * @return bolean
 */
function get_billing_info_izap_ecommerce($user_guid = 0) {
  if (!$user_guid) {
    $user = get_loggedin_user();
  } else {
    $user = get_user($guid);
  }

  if (!$user) {
    return false;
  }

  $billing_info = $user->billing_info;
}

/**
 * get full access
 *
 * @param string $hook name of hook
 *
 * @param string $entity_type type of entity
 *
 * @param mixed $returnvalue value return by the function
 *
 * @param array $params elgg default parameters
 *
 * @return bolean
 */
function get_full_access_IZAP($hook, $entity_type, $returnvalue, $params) {
  return true;
}

/**
 * save order
 *
 * @param array $items order array
 *
 * @param <type> $cart_id
 *
 * @return ElggObject object of IZAP_ECOMMERCE
 */
function save_order_izap_ecommerce($items, $cart_id) {

  $cart = get_from_session_izap_ecommerce('izap_cart');
  $order = new ElggObject();
  $order->subtype = 'izap_order';
  $order->access_id = ACCESS_PUBLIC;
  $i = 0;
  $total_price = 0;
  foreach ($items as $product) {

    $item_name = 'item_name_' . $i;
    $item_price = 'item_price_' . $i;
    $item_guid = 'item_guid_' . $i;
    $item_code = 'item_code_' . $i;

    $order->$item_name = $product['name'];
    $order->$item_price = $product['amount'];
    $order->$item_guid = $product['guid'];
    $order->$item_code = $product['code'];
    if (isset($product['attributes'])) {

      $item_attribs = array();
      foreach ($product['attributes'] as $key => $value) {
        if ((int) $value > 0)
          $item_attribs[] = $key;
      }
      $item_attributes = $item_name . '_attribs';
      $order->$item_attributes = $item_attribs;
    }
    $i++;
    $total_price += (int) $product['amount'];

    $description .= $product['name'] . '<br />';
    if ($title == '') {
      $title = $product['name'];
    } else {
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

  if ($order->save()) {
    return $order;
  }
}

/**
 * create product link
 *
 * @global  $CONFIG array of global variable
 *
 * @param Object $order object of order
 *
 * @param integer $product_guid product guid
 *
 * @param integer $time timestamp
 *
 * @return string
 */
function create_product_download_link_izap_ecommerce(array $options) {
  $default = array(
    'time' => md5(microtime())
  );

  $options = array_merge($default, $options);
  global $CONFIG;

  $owner_guid = elgg_get_logged_in_user_guid();
  if ($time == '') {
    $time = md5(microtime());
  }

  $hash = create_hash_izap_ecommerce((int)$options['order']->guid, $options['product_guid'], $options['time'], $owner_guid);

  $download_link = $CONFIG->wwwroot . 'action/izap_ecommerce/download?o=' . $options['order']->guid;
  $download_link .= '&p=' . $options['product_guid'] . '&t=' . $options['time'] . '&h=' . $hash;

  $download_link = elgg_view('output/url',array(
                    'text' => 'Download',
                    'href' => $download_link,
                    'is_action' => true,
                    'class' => (string) $options['class']

            ));

  return $download_link;
}

/**
 * create hash code
 *
 * @param integer $order_guid   order id
 * @param integer $product_guid product id
 * @param integer $time         timestamp
 * @param integer $owner_guid   owner id
 *
 * @return mixed
 */
function create_hash_izap_ecommerce($order_guid, $product_guid, $time, $owner_guid) {
  return md5($order->guid . $owner_guid . $product_guid . $time);
}

/**
 * verify order
 *
 * @global ElggObject $IZAP_ECOMMERCE object of IZAP_ECOMMERCE
 *
 * @param array $order elgg default parameters
 *
 * @return bolean
 */
function verify_order_izap_ecommerce($order) {
  global $IZAP_ECOMMERCE;

  if (elgg_is_admin_logged_in ()) {
    return true;
  }

  if ($order->confirmed == 'no') {
    register_error(elgg_echo('izap-ecommerce:not_processed_properly'));
    forward($IZAP_ECOMMERCE->link . 'order');
  }

  if (!elgg_is_admin_logged_in() && $order->owner_guid != get_loggedin_userid()) {
    register_error(elgg_echo('izap-ecommerce:no_access'));
    forward();
  }

  $cart_id = get_from_session_izap_ecommerce('cart_id');
  if ($cart_id == $order->cart_id) {
    remove_from_session_izap_ecommerce('izap_cart');
  }
}

/**
 * save cart
 *
 * @param string $event       elgg event
 * @param string $object_type type of object
 * @param string $object      elgg object
 *
 * @return <type>
 */
function func_save_cart_izap_ecommerce($event, $object_type, $object) {
  return func_save_wishlist_izap_ecommerce();
}

/**
 * save wishlist
 *
 * @param array $products array of product
 * @param integer $user   user id
 *
 * @return bolean
 */
function func_save_wishlist_izap_ecommerce($products = array(), $user = false) {
  if (!($user instanceof ElggUser)) {
    $user = get_loggedin_user();
    if (!$user) {
      return false;
    }
  }

  if (!is_array($products) && (int) $products > 0) {
    $products = array($products);
  }
  if (!sizeof($products)) {
    $products = get_from_session_izap_ecommerce('izap_cart');
  }

  if (!sizeof($products)) {
    return false;
  }

  $old_wishlist = IzapEcommerce::getWishList($user);
  $new_wishlist = array_unique(array_merge((array) $old_wishlist, (array) $products));

  $user->izap_wishlist = $new_wishlist;
  return true;
}

/**
 * remove from wishlist
 *
 * @param array $products array of product
 * @param integer $user   user id
 *
 * @return bolean
 */
function func_remove_from_wishlist_izap_ecommerce($products, $user = false) {
  if (!($user instanceof ElggUser)) {
    $user = get_loggedin_user();
    if (!$user) {
      return false;
    }
  }

  if (!is_array($products)) {
    $products = array($products);
  }

  $old_wishlist = $user->izap_wishlist;
  $new_wishlist = array_unique(array_diff((array) $old_wishlist, $products));
  $user->izap_wishlist = $new_wishlist;

  return true;
}

/**
 * get archive product
 *
 * @global ElggObject $IZAP_ECOMMERCE object of IZAP_ECOMMERCE
 * @param array $products array of product
 * @param array $return_array array of child product
 *
 * @return array
 */
function get_archived_products_izap_ecommerce($product, $return_array = false) {
  global $IZAP_ECOMMERCE;

  $children = (array) $product->children;

  if (sizeof($children)) {
    if ($return_array) {
      return $children;
    }
    foreach ($children as $child) {
      $child_product = get_entity($child);
      if ($child_product) {
        $return_array[$child] = $child_product;
      }
    }
  }

  krsort($return_array);
  return $return_array;
}

/**
 * get default listing options
 *
 * @param array $array array of listing options
 *
 * @return array
 */
function get_default_listing_options_izap_ecommerce($array = array()) {
  $options['type'] = 'object';
  $options['subtype'] = GLOBAL_IZAP_ECOMMERCE_SUBTYPE;
  $options['limit'] = izap_plugin_settings(array(
        'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
        'setting_name' => 'izap_product_limit',
        'value' => 10
      ));
  $options['full_view'] = false;
  $options['offset'] = get_input('offset', 0);
  $options['view_type_toggle'] = true;
  $options['pagination'] = true;
  $options['metadata_name'] = 'archived';
  $options['metadata_value'] = 'no';

  return array_merge($options, $array);
}

function get_user_listing_options_izap_ecommerce($array = array()) {
  $options['type'] = 'object';
  $options['subtype'] = GLOBAL_IZAP_ECOMMERCE_SUBTYPE;
  $options['limit'] = izap_plugin_settings(array(
        'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
        'setting_name' => 'izap_product_limit',
        'value' => 10
      ));
  $options['full_view'] = false;
  $options['offset'] = get_input('offset', 0);
  $options['view_type_toggle'] = true;
  $options['pagination'] = true;
  $options['metadata_name'] = 'archived';
  $options['metadata_value'] = 'no';
  $options['owner_guid'] = elgg_get_logged_in_user_guid();

  return array_merge($options, $array);
}

/**
 * save order
 *
 * @param object $order 
 */
function save_order_with_user_izap_ecommerce($order) {
  for ($i = 0; $i < $order->total_items; $i++) {
    $item_guid = 'item_guid_' . $i;
    $item_code = 'item_code_' . $i;
    $provided['guid'] = $order->owner_guid;
    $provided['metadata'] = array(
      'purchased_' . $order->$item_guid => 'yes',
      'purchased_' . $order->$item_code => 'yes',
    );
    IzapBase::updateMetadata($provided);
  }
}