<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version {version} $Revision: {revision}
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
$plugin = $vars['entity'];
?>
<p>
  <label>
    <?php echo elgg_echo('izap-ecommerce:allow_to_download_upgraded_version');?>
    <?php echo elgg_view('input/pulldown', array(
    'name' => 'params[allow_to_download_upgraded_version]',
    'options_values' => array(
            'no' => elgg_echo('izap-ecommerce:no'),
            'yes' => elgg_echo('izap-ecommerce:yes'),
    ),
    'value' => izap_plugin_settings(array(
              'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
              'setting_name' => 'allow_to_download_upgraded_version',
              'value' => 'no'
              )),
    ));?>
  </label>
</p>

<br />
<p>
  <label>
    <?php echo elgg_echo('izap-ecommerce:default_list_view');?>
    <?php echo elgg_view('input/pulldown', array(
    'name' => 'params[default_list_view]',
    'options_values' => array(
            'list' => elgg_echo('izap-ecommerce:list'),
            'gallery' => elgg_echo('izap-ecommerce:gallery'),
    ),
    'value' => izap_plugin_settings(array(
              'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
              'setting_name' => 'default_list_view',
              'value' => 'list'
              )),
    ));?>
  </label>
</p>

<br />
<p>
  <label>
    <?php echo elgg_echo('izap-ecommerce:izap_product_limit');?>
    <?php echo elgg_view('input/text', array(
    'name' => 'params[izap_product_limit]',
    'value' => izap_plugin_settings(array(
              'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
              'setting_name' => 'izap_product_limit',
              'value' => 10
              )),
    ));?>
  </label>
</p>

<br />
<p>
  <label>
    <?php echo elgg_echo('izap-ecommerce:izap_product_discount');?>
    <?php echo elgg_view('input/text', array(
    'name' => 'params[izap_product_discount]',
    'value' => izap_plugin_settings(array(
              'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
              'setting_name' => 'izap_product_discount',
              'value' => 0
              )),
    ));?>
  </label>
</p>