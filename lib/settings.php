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

global $CONFIG;

return array(
        'path'=>array(
                'www'=>array(
                        'page' => $CONFIG->wwwroot . 'pg/store/',
                        'images' => $CONFIG->wwwroot . 'mod/izap-ecommerce/_graphics/',
                        'action' => $CONFIG->wwwroot . 'action/izap_ecommerce/',
                ),
                'dir'=>array(
                        'plugin'=>dirname(dirname(__FILE__))."/",
                        'actions'=>$CONFIG->pluginspath."izap-ecommerce/actions/",
                        'class'=>dirname(__FILE__)."/classes/",
                        'functions'=>dirname(__FILE__)."/functions/",
                        'gateways' => dirname(__FILE__) . '/gateways/',
                        'lib' => dirname(__FILE__) . '/',
                        'views'=>array(
                                'home'=>"izap-ecommerce/",
                                'forms'=>"izap-ecommerce/forms/",
                                'views'=>"izap-ecommerce/views/",
                                'river'=>"river/izap-ecommerce/",
                                'product' => 'izap-ecommerce/views/product/',
                        ),
                        'pages'=>dirname(dirname(__FILE__)).'/pages/',
                ),
        ),

        'plugin'=>array(
                'name'=>"izap-ecommerce",

                'title'=>"E-commerce",

                'url_title'=>"store",

                'objects'=>array(
                        'izap_ecommerce'=>array(
                                'getUrl'=>"izap_ecommerce_getUrl",
                                'class'=>"IzapEcommerce",
                                'type' => 'object',
                        ),
                ),

                'actions'=>array(
                        'izap_ecommerce/save_settings'=>array('file' => "save_settings.php",'public'=>false,'admin_only'=>true),
                        'izap_ecommerce/add'=>array('file' => "add_edit.php",'public'=>false,'admin_only'=>true),
                        'izap_ecommerce/add_to_cart'=>array('file' => "add_to_cart.php",'public'=>true,'admin_only'=>false),
                        'izap_ecommerce/add_to_wishlist'=>array('file' => "add_to_wishlist.php",'public'=>FALSE),

                        'izap_ecommerce/remove_from_cart'=>array('file' => "remove_from_cart.php",'public'=>true,'admin_only'=>false),
                        'izap_ecommerce/buy'=>array('file' => "buy.php",'public'=>true,'admin_only'=>false),
                        'izap_ecommerce/download'=>array('file' => "download.php",'public'=>false,'admin_only'=>false),
                        'izap_ecommerce/delete'=>array('file' => "delete.php",'public'=>false,'admin_only'=>TRUE),
                        'izap_ecommerce/sendtofriend' => array(
                                'file' => 'send_to_friends.php',
                                'public' => TRUE,
                        ),
                ),

                'page_handler'=>array('store'=>'izap_ecommerce_page_handler'),

                'menu'=>array(
                        'pg/store/'=>array('title'=>"izap-ecommerce:store",'public'=>true),
                ),

                'widget' => array(

                        'latest_product' => array(
                                'name' => __('widgets:latest_products:name'),
                                'description' => __('widgets:latest_products:description'),
                        ),

                ),

                'submenu'=>array(
                        'store' => array(
                                'pg/store/'=>array('title'=>"izap-ecommerce:products",'public'=>true),
                                'pg/store/order/'=>array('title'=>"izap-ecommerce:my_orders",'public'=>false),
                                'pg/store/settings' => array ('title' => 'izap-ecommerce:edit_settings', 'admin_only' => TRUE),
                                'pg/store/add' => array ('title' => 'izap-ecommerce:add_product', 'admin_only' => TRUE),
                                'pg/store/wishlist' => array ('title' => 'izap-ecommerce:wishlist'),
                        ),
                ),

                'custom' => array(
                        'default_image' => $CONFIG->pluginspath . 'izap-ecommerce/_graphics/no_image.jpg',
                        'currency' => 'USD',
                        'currency_sign' => '$',
                ),

                'search' => array(
                        'enabled' => TRUE,
                        'type' => 'object',
                        'subtype' => 'izap_ecommerce',
                ),

                'events' => array(
                        'logout' => array(
                                'user' => array(
                                        'func_save_cart_izap_ecommerce'
                                ),
                        ),
                ),
        ),

        'includes'=>array(
                dirname(__FILE__) => array('load.php'),
        ),
);