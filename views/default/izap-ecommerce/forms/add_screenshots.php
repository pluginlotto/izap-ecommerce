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
global $IZAP_ECOMMERCE;
$product = $vars['entity'];
$number_of_screenshots = 4; ?>
<form action="<?php echo $vars['url']?>action/izap_ecommerce/add_screenshots" method="POST" enctype="multipart/form-data">
  <?php
  for($i=0; $i<$number_of_screenshots; $i++) :
    echo elgg_view('input/file', array('name' => 'screenshots[]'));
  endfor;
  echo elgg_view('input/securitytoken');
  echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $product->guid));
  echo elgg_view('input/hidden', array('name' => 'total_images', 'value' => $number_of_screenshots));
  echo '<br />' . elgg_view('input/submit', array('name' => 'submit' , 'value' => elgg_echo('izap-ecommerce:upload_images'))); ?>
</form>