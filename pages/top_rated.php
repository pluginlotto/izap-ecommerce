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

global $IZAPTEMPLATE;
$title = elgg_echo('izap-ecommerce:top_rated');
$body = elgg_view_title($title);

$context = get_context();
set_context('search');
$options = get_default_listing_options_izap_ecommerce();
unset ($options['metadata_name'], $options['metadata_value']);
$options['metadata_name_value_pairs'] = array(
        array('name' => 'archived', 'value' => 'no'),
        array('name' => 'avg_rating', 'value' => 0, 'operand' => '>='),
);
$options['order_by_metadata'] = array(
        array('name' => 'avg_rating', 'direction' => 'DESC')
);
$list =elgg_list_entities_from_metadata($options);
if($list == '') {
  $list = $IZAPTEMPLATE->render('views/no_data', array('plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN));
}
$body .= $list;
set_context($context);
IzapEcommerce::draw_page($title, $body);