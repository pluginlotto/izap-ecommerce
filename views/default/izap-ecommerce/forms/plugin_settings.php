<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
 */

$plugin = $vars['entity'];
?>
<div class="contentWrapper">
  <form action="<?php echo $vars['url']?>action/izap_ecommerce/save_settings" method="POST">
    <!--
    <p>
      <label>
        <?php
        _e('download_link_expire_time') . '<br />';
        echo elgg_view('input/text', array('internalname' => 'params[link_expire_time]', 'value' => $plugin->link_expire_time));
        ?>
      </label>
    </p>
    -->
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