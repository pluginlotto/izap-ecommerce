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

$billing_info = get_billing_info_izap_ecommerce();
?>
<div class="contentWrapper">
  <?php
    echo elgg_view_title(__('billing_info'));
  ?>

  <div class="izap-product-float-left" style="width: 45%">
    <p>
      <label>
        <?php
          _e('firstname');
          echo '<br />' . elgg_view('input/text', array('internalname' => 'billing_info[FirstName]', 'value' => $billing_info->LastName, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
          _e('lastname');
          echo '<br />' . elgg_view('input/text', array('internalname' => 'billing_info[LastName]', 'value' => $billing_info->LastName, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
          _e('email');
          echo '<br />' . elgg_view('input/text', array('internalname' => 'billing_info[email]', 'value' => $billing_info->email, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>
    
  </div>

  <div class="izap-product-float-left" style="width: 45%">
    <p>
      <label>
        <?php
          _e('firstname');
          echo '<br />' . elgg_view('input/text', array('internalname' => 'billing_info[last_name]', 'value' => $billing_info->first_name, 'class' => 'general-textarea'));
        ?>
      </label>
    </p>
  </div>

  <div class="clearfloat"></div>
</div>