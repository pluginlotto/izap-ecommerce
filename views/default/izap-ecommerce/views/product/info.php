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

global $IZAP_ECOMMERCE, $IZAPTEMPLATE;
$product = $vars['entity'];
?>
<div class="izap-product-info">
  <div class="left">
    <img src="<?php echo $product->getIcon('master');?>" alt="<?php $product->title?>" class="izap-product-image" />
  </div>

  <div class="right">
    <?php
    // link to add the new version
    if($product->canEdit() && !$product->isArchived()) {?>
    <div align="right" class="add_new_version">
      <a href="<?php echo func_set_href_byizap(array(
        'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
        'page' => 'new_version',
        'vars' => array($product->guid)
           ));?>"><?php echo elgg_echo('izap_ecommerce:add_new_version'); ?></a>
    </div>

    <form action="#" id="get_user_price_form">
      Enter username
      <input type="text" name="izap_username" size="10"/>
      <input type="hidden" name="product_guid" value="<?php echo $product->guid?>" />
      <input type="submit" value="Get Price" />
    </form>
    <div id="user_price"></div>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#get_user_price_form').submit(function(){
          var action = '<?php echo func_get_www_path_byizap(array(
          'type' => 'page',
          'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN
          )) . 'user_price';?>';
              $.ajax({
                type: 'POST',
                url: action,
                data: $('#get_user_price_form').serialize(),
                beforeSend: function(){
                  $('#user_price').html('Loading...');
                },
                success: function(data){
                  $('#user_price').html(data);
                }
              });

              return false;
            });
          });
    </script>

    <div class="clearfloat"></div>
      <?php }
    // show download count to owner
    if($product->canEdit()) {
      ?>
    <h3 align="right"><?php
        echo elgg_echo('izap-ecommerce:total_download') . ': ' . $product->getDownloads();
        ?></h3>
      <?php
    }
    echo $IZAPTEMPLATE->render('product/buy', array('entity' => $product));
    //echo $IZAPTEMPLATE->render('forms/add_attribute', array('entity'=> $product));
    ?>
  </div>
  <div class="clearfloat"></div>

  <div class="description">
      <?php
      echo elgg_view('output/longtext', array('value' => $product->description));
      ?>
      <p align="right">
        <?php
        echo elgg_echo('izap-ecommerce:tags');
        echo ': ' . elgg_view('output/tags', array('tags' => $product->tags));
        ?>
        <br />
        <?php
        echo $IZAPTEMPLATE->render('product/edit_delete', array('entity' => $product));
        ?>
      </p>
    </div>
</div>
