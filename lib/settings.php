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

include_once dirname(__FILE__) . '/izap_ecommerce.php';

global $CONFIG;

return array(
        'includes'=>array(
                dirname(__FILE__) => array('load.php'),
        ),

        'path'=>array(
                'www'=>array(
                        'page' => $CONFIG->wwwroot . 'pg/store/',
                        'images' => $CONFIG->wwwroot . 'mod/'.GLOBAL_IZAP_ECOMMERCE_PLUGIN.'/_graphics/',
                        'action' => $CONFIG->wwwroot . 'action/izap_ecommerce/',
                ),
                'dir'=>array(
                        'plugin'=>dirname(dirname(__FILE__))."/",
                        'actions'=>$CONFIG->pluginspath. GLOBAL_IZAP_ECOMMERCE_PLUGIN.'/actions/',
                        'class'=>dirname(__FILE__)."/classes/",
                        'functions'=>dirname(__FILE__)."/functions/",
                        'gateways' => dirname(__FILE__) . '/gateways/',
                        'lib' => dirname(__FILE__) . '/',
                        'views'=>array(
                                'home'=> GLOBAL_IZAP_ECOMMERCE_PLUGIN . "/",
                                'forms'=> GLOBAL_IZAP_ECOMMERCE_PLUGIN . "/forms/",
                                'views'=> GLOBAL_IZAP_ECOMMERCE_PLUGIN . "/views/",
                                'river'=>"river/" . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/',
                                'product' => GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/',
                        ),
                        'pages'=>dirname(dirname(__FILE__)).'/pages/',
                ),
        ),

        'plugin'=>array(
                'name'=>"izap-ecommerce",

                'title'=>"E-commerce",

                'url_title' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,

                'objects'=>array(
                        'izap_ecommerce'=>array(
                                'getUrl'=>"izap_ecommerce_getUrl",
                                'class'=>"IzapEcommerce",
                                'type' => 'object',
                        ),

                        'izap_order'=>array(
                                'getUrl'=>"izap_order_getUrl",
                                'type' => 'object',
                        ),
                ),

                'actions'=>array(
                        'izap_ecommerce/save_settings'=>array('file' => "save_settings.php",'public'=>false,'admin_only'=>true),
                        'izap_ecommerce/add'=>array('file' => "add_edit.php",'public'=>false),
                        'izap_ecommerce/add_to_cart'=>array('file' => "add_to_cart.php",'public'=>true),
                        'izap_ecommerce/add_to_wishlist'=>array('file' => "add_to_wishlist.php",'public'=>FALSE),
                        'izap_ecommerce/remove_from_wishlist'=>array('file' => "remove_from_wishlist.php",'public'=>FALSE),

                        'izap_ecommerce/remove_from_cart'=>array('file' => "remove_from_cart.php",'public'=>true),
                        'izap_ecommerce/buy'=>array('file' => "buy.php",'public'=>true),
                        'izap_ecommerce/download'=>array('file' => "download.php",'public'=>false),
                        'izap_ecommerce/delete'=>array('file' => "delete.php",'public'=>false),
                        'izap_ecommerce/sendtofriend' => array(
                                'file' => 'send_to_friends.php',
                                'public' => TRUE,
                        ),

                        'izap_ecommerce/add_screenshots' => array(
                                'file' => 'add_screenshots.php',
                                'public' => FALSE,
                        ),

                        'izap_ecommerce/delete_screenshot' => array(
                                'file' => 'delete_screenshot.php',
                                'public' => FALSE,
                        ),
                ),

                'page_handler'=>array(GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER =>'izap_ecommerce_page_handler'),

                'menu'=>array(
                        'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/list/all/'=>array('title'=>"izap-ecommerce:store",'public'=>true),
                ),

                'widget' => array(

                        'latest_product' => array(
                                'name' => elgg_echo('izap-ecommerce:widgets:latest_products:name'),
                                'description' => elgg_echo('izap-ecommerce:widgets:latest_products:description'),
                        ),

                ),

                'submenu'=>array(
                        'store' => array(
                                'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/list/all/'=>array('title'=>"izap-ecommerce:all_products",'public'=>true, 'groupby' => 'all'),

                                'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/add' => array ('title' => 'izap-ecommerce:add_product','admin_only'=>TRUE, 'groupby' => 'my'),
                                'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/list/' . get_loggedin_user()->username . '/' =>array('title'=>"izap-ecommerce:my_products",'admin_only'=>TRUE, 'groupby' => 'my'),
                                'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/order/'=>array('title'=>"izap-ecommerce:my_orders",'public'=>false, 'groupby' => 'my'),
                                'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/wishlist' => array ('title' => 'izap-ecommerce:wishlist', 'extra_title' => ' (' . IzapEcommerce::countWishtlistItems() . ')', 'publid' => FALSE, 'groupby' => 'my'),

                                'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/all_orders/'=>array('title'=>"izap-ecommerce:all_orders",'admin_only'=>TRUE, 'groupby' => 'all'),

                        //'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/list/[PAGE_OWNER_USERNAME]/' =>array('title'=>"izap-ecommerce:page_owner_products",'public'=>true, 'groupby' => 'others'),
                        //'pg/'.GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER.'/settings' => array ('title' => 'izap-ecommerce:edit_settings', 'admin_only' => TRUE, 'groupby' => 'admin'),
                        ),
                ),

                'custom' => array(
                        'default_image' => $CONFIG->pluginspath . 'izap-ecommerce/_graphics/no_image.jpg',
                        'currency' => 'USD',
                        'currency_sign' => '$',
                        'show_not_confirmed_orders' => 'no',
                ),

                'events' => array(
                        'logout' => array(
                                'user' => array(
                                        'func_save_cart_izap_ecommerce'
                                ),
                        ),
                ),

                'hooks' => array(
                        'izap_payment_gateway' => array(
                                'IPN_NOTIFY_ALERTPAY:SUCCESS' => array(
                                        'izap_alertpay_process_order',
                                )
                        ),

                        'izap_payment_gateway' => array(
                                'IPN_NOTIFY_ALERTPAY:FAIL' => array(
                                        'izap_alertpay_fail',
                                )
                        ),
                ),
        ),
);