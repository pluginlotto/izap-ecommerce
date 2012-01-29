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

$billing_info = get_billing_info_izap_ecommerce();
?>
<div class="izapcontentWrapper">
  <?php
    echo elgg_view_title(elgg_echo('izap-ecommerce:billing_info'));
  ?>

  <div class="izap-product-float-left" style="width: 45%">
    <p>
      <label>
        <?php
          echo elgg_echo('izap-ecommerce:firstname');
          echo '<br />' . elgg_view('input/text', array('name' => 'billing_info[FirstName]', 'value' => $billing_info->LastName, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
          echo elgg_echo('izap-ecommerce:lastname');
          echo '<br />' . elgg_view('input/text', array('name' => 'billing_info[LastName]', 'value' => $billing_info->LastName, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
          echo elgg_echo('izap-ecommerce:email');
          echo '<br />' . elgg_view('input/text', array('name' => 'billing_info[email]', 'value' => $billing_info->email, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>
    
  </div>

  <div class="izap-product-float-left" style="width: 45%">
    <p>
      <label>
        <?php
          echo elgg_echo('izap-ecommerce:firstname');
          echo '<br />' . elgg_view('input/text', array('name' => 'billing_info[last_name]', 'value' => $billing_info->first_name, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>
  </div>

  <div class="clearfloat"></div>
</div>