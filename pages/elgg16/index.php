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

global $IZAP_ECOMMERCE;
$list_param = izap_get_params(1);
$title = elgg_echo('izap-ecommerce:welcome_to_store');
$body = elgg_view_title($title);
$options = get_default_listing_options_izap_ecommerce();
$context = get_context();
set_context('search');
$body .= list_entities_from_metadata(
        $options['metadata_name'],
        $options['metadata_value'],
        $options['type'],
        $options['subtype'],
        $options['container_guid'],
        $options['limit'],
        $options['full_view'],
        $options['toggleview'],
        $options['pagination']);
set_context($context);
IzapEcommerce::draw_page($title, $body);
