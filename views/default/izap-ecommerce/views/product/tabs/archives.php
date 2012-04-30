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

$archived_products = get_archived_products_izap_ecommerce($vars['entity']);
?>
<div class="izap_terms_conditions">
<?php
if($archived_products) {
  echo '<ol type="1">';
  foreach($archived_products as $product) {
    ?>
  <li style="list-style-type: decimal;">
  <a href="<?php echo $product->getUrl();?>">
        <?php echo $product->title?>
  </a>
    <br />
      <?php
      $description  = filter_var($product->description, FILTER_SANITIZE_STRIPPED);
      echo substr($description, 0, 120);
      ?>...
    
</li>
    <?php
  }
  echo '</ol>';
}
?>
</div>