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
      'internalname' => 'params[allow_to_download_upgraded_version]',
      'options_values' => array(
				'no' => elgg_echo('izap-ecommerce:no'),
				'yes' => elgg_echo('izap-ecommerce:yes'),
			),
			'value' => $plugin->allow_to_download_upgraded_version,
    ));?>
  </label>
</p>