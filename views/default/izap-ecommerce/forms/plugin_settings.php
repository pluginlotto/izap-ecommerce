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

$plugin = $vars['entity'];
?>
<div class="contentWrapper">
  <form action="<?php echo $vars['url']?>action/izap_ecommerce/save_settings" method="POST">
    <p>
      <label>
        <?php
        _e('paypal_account') . '<br />';
        echo elgg_view('input/text', array('internalname' => 'params[paypal_account]', 'value' => $plugin->paypal_account));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
          echo '<br />' . elgg_view('input/checkboxes', array('internalname' => 'params[sandbox]', 'options' => array(
            __('enable_testing_mode') => 'yes'),
            'value' => $plugin->sandbox,
            ));
        ?>
      </label>
    </p>
    
    <?php
      echo elgg_view('input/hidden', array ('internalname' => 'guid', 'value' => (int) $plugin->guid));
      echo elgg_view('input/securitytoken');
      echo elgg_view('input/submit', array('value' => __('save_settings')));
    ?>
  </form>
</div>