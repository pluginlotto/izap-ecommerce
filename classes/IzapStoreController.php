<?php

class IzapStoreController extends IzapController {

  protected $_page;

  public function __construct($page) {
    parent::__construct($page);
    global $IZAP_ECOMMERCE;
    $this->_page = $page;
    $this->page_elements['filter'] = false;
//    $this->page_elements['buttons'] = false;

    $cart = get_from_session_izap_ecommerce('izap_cart');
    if (is_array($cart) && sizeof($cart)) {
      $this->addWidget($IZAP_ECOMMERCE->views . 'cart', array('cart' => $cart, 'full' => false));
    }
  }

  public function actionList() {
    global $IZAP_ECOMMERCE;
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:welcome_to_store');

    if (izap_plugin_settings(array(
                'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
                'setting_name' => 'default_list_view',
                'value' => 'list'
            )) == 'gallery' && !isset($_REQUEST['search_viewtype'])) {
      set_input('search_viewtype', 'gallery');
    }
    $context_bak = get_context();
    set_context('search');
    $this->page_elements['content'] = elgg_list_entities_from_metadata(get_default_listing_options_izap_ecommerce());
    if (empty($this->page_elements['content']))
      $this->page_elements['content'] = elgg_view($IZAP_ECOMMERCE->views . '/no_data');
    set_context($context_bak);
    $this->drawPage();
  }

  public function actionAdd() {
    global $CONFIG, $IZAP_ECOMMERCE;
    admin_gatekeeper();
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:add_new_product');
    $this->render($IZAP_ECOMMERCE->forms . 'add_edit');
  }

  public function actionEdit() {
    global $CONFIG, $IZAP_ECOMMERCE;
    admin_gatekeeper();
    $product = get_entity($this->url_vars[1]);
    if (!$product) {
      forward();
    }
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:edit_product');
    $this->render($IZAP_ECOMMERCE->forms . 'add_edit', array('entity' => $product));
  }

  public function actionProduct() {

    $izap_product = get_product_izap_ecommerce($this->url_vars[2]);
    if (!$izap_product) {
      register_error(elgg_echo('izap-ecommerce:invalid_product'));
      forward();
    }

    if ($izap_product->canEdit()) {
      // NEW VERSION LINK
      if (!$izap_product->isArchived()) {
        $menu = new ElggMenuItem('add_new_version', elgg_echo('izap-ecommerce:add_version'), IzapBase::setHref(array(
                            'context' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,
                            'action' => 'newversion',
                            'vars' => array($izap_product->guid),
                        )));
        $menu->setSection('IMP');
        $menu->setLinkClass('izap_pro_menu');
        elgg_register_menu_item('page', $menu);
      }

      // EDIT LINK
      $menu_edit = new ElggMenuItem('edit_product', elgg_echo('izap-ecommerce:edit'), IzapBase::setHref(array(
                          'context' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,
                          'action' => 'edit',
                          'vars' => array($izap_product->guid),
                      )));
      $menu_edit->setSection('IMP');
      $menu_edit->setLinkClass('izap_pro_menu');
      elgg_register_menu_item('page', $menu_edit);

      // DELETE LINK
      $menu_delete = new ElggMenuItem('delete_product', elgg_echo('izap-ecommerce:delete'), IzapBase::deleteLink(array(
                          'only_url' => TRUE,
                          'rurl' => IzapBase::setHref(array(
                              'context' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,
                              'action' => 'all'
                          )),
                      )));
      $menu_delete->setSection('IMP');
      $menu_delete->setLinkClass('izap_pro_menu');
      $menu_delete->setConfirmText('ARe you Sure');
      elgg_register_menu_item('page', $menu_delete);

      if ($izap_product->getPrice(false) > 0) {
        $menu_add_attrib = new ElggMenuItem('add_attrib', elgg_echo('izap-ecommerce:add_attrib'), IzapBase::setHref(array(
                            'context' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,
                            'action' => 'attrib',
                            'vars' => array($izap_product->guid)
                        )));
        $menu_add_attrib->setSection('IMP');
        $menu_add_attrib->setLinkClass('izap_pro_menu');
        elgg_register_menu_item('page', $menu_add_attrib);
      }
    }




    $this->page_elements['title'] = $izap_product->title;
    $this->page_elements['content'] = elgg_view_entity($izap_product, TRUE);
    //func_increment_views_byizap($izap_product);
    $this->drawPage();
  }

  public function actionAttrib() {
    $product = get_entity($this->url_vars[2]);
    if (elgg_instanceof($product, 'object', GLOBAL_IZAP_ECOMMERCE_SUBTYPE)) {
      $title = '<a href="' . $product->getURL() . '">' . $product->title . '</a>';
      $this->page_elements['title'] = elgg_view_title(elgg_echo('izap-ecommerce:add_attribute:adding_for') . ' : ' . $title);
      $this->render(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/forms/add_attribute', array('entity' => $product));
    }
  }

  public function actionIcon() {
    global $IZAP_ECOMMERCE;
    $guid = $this->url_vars[1];
    $izap_product = get_product_izap_ecommerce($guid);
    $size = isset($this->url_vars[2]) ? $this->url_vars[2] : '100x100';
    $thumb_name = 'izap-ecommerce/' . $guid . '/icon_' . str_replace('x', '_', $size);

    $file_handler = new ElggFile();
    $file_handler->owner_guid = $izap_product->owner_guid;

    $size = explode('x', $size);
    if ($izap_product->image_path != '') {
      $file_handler->setFilename($izap_product->image_path);
      $file_handler->open('read');
      $file_name = $file_handler->getFilenameOnFilestore();
    }

    if (!file_exists($file_name)) {
      $file_name = $IZAP_ECOMMERCE->default_image;
    }

    if ($size[0] != 'na' && $size[1] != 'na') {
      // check for the thumb first
      $file_handler->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
      $file_handler->open('read');
      $thumb_file = $file_handler->getFilenameOnFilestore();
      if (file_exists($thumb_file)) {
        $file_handler->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
        $file_handler->open('read');
        $contents = $file_handler->grabFile();
      } else {
        $contents = get_resized_image_from_existing_file($file_name, $size[0], $size[1], TRUE);
        $file_handler->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
        $file_handler->open('write');
        $file_handler->write($contents);
        $file_handler->close();
      }
    } else {
      $contents = $file_handler->grabFile();
    }
    if (!$contents) {
      $contents = file_get_contents($IZAP_ECOMMERCE->default_image);
    }

    $file_friendly_name = elgg_get_friendly_title($izap_product->title . '.' . $izap_product->image_extension);
    IzapBase::cacheHeaders(array(
        'content_type' => $izap_product->image_mime_type,
        'file_name' => $file_friendly_name,
        'filemtime' => filemtime($file_name),
    ));
    echo $contents;
  }

  public function actionScreenshots() {
    $product = get_product_izap_ecommerce($this->url_vars[1]);
    if ($product) {
      global $IZAP_ECOMMERCE;

      $file_name = $this->url_vars[2];

      $file_handler = new ElggFile();
      $file_handler->owner_guid = $product->owner_guid;
      $file_handler->setFilename($IZAP_ECOMMERCE->plugin_name . '/' . $product->guid . '/' . $file_name);
      $file_handler->open('read');

      if (file_exists($file_handler->getFilenameOnFilestore())) {
        izap_cache_headers(array(
            'content_type' => image / jpeg,
            'file_name' => $file_name,
        ));
        echo $file_handler->grabFile();
      }
    }
  }

  public function actionNewversion() {
    admin_gatekeeper();
    global $IZAP_ECOMMERCE;
    $product = get_entity($this->url_vars[2]);
    if (!$product) {
      forward();
    }
    $this->page_elements['title'] = sprintf(elgg_echo('izap-ecommerce:new_version'), $product->title);
    $this->page_elements['content'] = elgg_view($IZAP_ECOMMERCE->forms . 'add_edit', array(
                'entity' => $product,
                'archive' => TRUE,
                'parent_guid' => $product->guid,
            ));
    $this->drawPage();
  }

  public function actionUserprice() {
    admin_gatekeeper();
    $user = get_user_by_username(get_input('izap_username'));
    $product = get_product_izap_ecommerce(get_input('product_guid'));

    if ($user && $product) {
      echo elgg_echo('izap-ecommerce:price') . ': ' . $product->getUserPrice($user);
    }
  }

  public function actionCart() {
    gatekeeper();
    global $IZAP_ECOMMERCE;
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:cart');
    $this->page_elements['content'] = izap_view_cart(TRUE);
    $this->drawPage();
  }

  public function actionOrders() {
    gatekeeper();
    global $CONFIG, $IZAPTEMPLATE;
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:my_orders');

    $options['type'] = 'object';
    $options['subtype'] = 'izap_order';
    $options['owner_guid'] = elgg_get_logged_in_user_guid();
    if ($IZAP_ECOMMERCE->show_not_confirmed_orders == 'no') {
      $options['metadata_names'] = 'confirmed';
      $options['metadata_values'] = 'yes';
    }
    $list = elgg_list_entities_from_metadata($options);
    if (empty($list)) {
      $list = elgg_view($IZAP_ECOMMERCE->views . 'no_data');
    }
    $this->page_elements['content'] = $list;
    $this->drawPage();
  }

  public function actionOrder() {
    gatekeeper();
    global $IZAP_ECOMMERCE;
    $guid = $this->url_vars[1];
    $order = get_entity($guid);
    verify_order_izap_ecommerce($order);
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:order_number') . ' - #' . $guid;
    $this->page_elements['content'] = elgg_view($IZAP_ECOMMERCE->views . 'order_detail', array('entity' => $order));
    $this->drawPage();
  }

  public function actionWishlist() {
    global $IZAP_ECOMMERCE;
    $page_owner = page_owner_entity();
    $wishlist = IzapEcommerce::getWishList($page_owner);
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:wishlist');

    if (sizeof($wishlist)) {
      foreach ($wishlist as $product_guid) {
        $product = get_entity($product_guid);
        if ($product) {
          $this->page_elements['content'] = elgg_view_entity($product, FALSE);
        }
      }
    } else {
      $this->page_elements['content'] = elgg_view($IZAP_ECOMMERCE->views . 'no_data');
    }
    $this->drawPage();
  }

  public function actionAll_orders() {
    admin_gatekeeper();
    global $IZAP_ECOMMERCE;
    $this->page_elements['title'] = elgg_echo('izap-ecommerce:all_orders');
    $list = elgg_list_entities(array('type' => 'object', 'subtype' => 'izap_order', 'limit' => 20));
    if (empty($list)) {
      $list = elgg_view($IZAP_ECOMMERCE->views . 'no_data');
    }
    $this->page_elements['content'] = $list;
    $this->drawPage();
  }

  public function actionPaypal_notify() {
    global $IZAP_ECOMMERCE;

    $payment = new IzapPayment('paypal');
    $debug = FALSE;
    if (get_plugin_usersetting('paypal_test_mode', get_input('owner_guid'), GLOBAL_IZAP_PAYMENT_PLUGIN) == 'yes') {
      $debug = TRUE;
    }

    $variables = $payment->validate($debug);

    if ($variables['status'] === TRUE) {
      global $IZAP_ECOMMERCE;

      $paypal_invoice_id = $variables['invoiceid'];
      $order_id = $variables['ipn_data']['custom'];
      $order = get_entity($order_id);

      $main_array['confirmed'] = 'yes';
      $main_array['payment_transaction_id'] = $paypal_invoice_id;

      $provided['entity'] = $order;
      $provided['metadata'] = $main_array;
      IzapBase::updateMetadata($provided);

      // save purchased product info with user
      save_order_with_user_izap_ecommerce($order);

      IzapEcommerce::sendOrderNotification($order);
    } else {
      $order_id = $variables['ipn_data']['custom'];
      $order = get_entity($order_id);

      $main_array['confirmed'] = 'no';
      $main_array['error_status'] = 'Error while paypal notification';
      $main_array['error_time'] = time();
      $main_array['paypal_return'] = serialize($variables);

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
  }

  public function actionPay_return() {
    remove_from_session_izap_ecommerce('izap_cart');
    forward('/' . GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER . '/orders');
    exit;
  }
}