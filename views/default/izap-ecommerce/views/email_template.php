<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

global $IZAP_ECOMMERCE, $IZAP_PAYMENT_GATEWAYS;
$order = $vars['entity'];
$order_owner = $order->getOwnerEntity();
if (is_plugin_enabled('messages')) {
  $notify_link = $vars['url'] . 'mod/messages/send.php?send_to=' . $order_owner->guid;
} else {
  $notify_link = 'mailto:' . $order_owner->email;
}
?>
<html>
  <head>
    <style type ="text/css">
      .order_detail_owner .icon {
        float:left;
        margin-right: 10px;
      }
      .izap-order-detait-odd {
        padding: 4px;
        background-color: #ECE9E9
      }
      .izap-order-detait-even {
        padding: 4px
      }
      .izap-order-detait-total {
        border-top: 1px solid #000000;
        padding-right: 90px
      }
      .izap-product-float-left {
        float:left;
      }
    </style>
  </head>
  <body>
    <div class="izapcontentWrapper">
      <div class="order_detail_owner">

        <div class="icon">
          <a href="<?php echo $order_owner->getURL(); ?>">
            <?php
            echo elgg_view('profile/icon', array('entity' => $order_owner, 'override' => true));
            ?>
          </a>
        </div>

        <div class="info">
          <?php echo elgg_echo('izap-ecommerce:name'); ?>:
            <b>
              <a href="<?php echo $order_owner->getURL(); ?>">
              <?php echo $order_owner->name; ?>
            </a>
          </b>
          <br />

          <?php echo elgg_echo('izap-ecommerce:email'); ?>:
              <b>
                <a href="<?php echo $notify_link; ?>">
              <?php echo $order_owner->email; ?>
            </a>
          </b>
          <br />

          <?php echo elgg_echo('izap-ecommerce:order_date'); ?>:
              <b>
            <?php echo date('d-n-Y', $order->time_updated); ?>
            </b>
            <br />

          <?php echo elgg_echo('izap-ecommerce:order_comfirmed'); ?>:
              <b>
            <?php echo $order->confirmed; ?>
            </b>
            <br />

          <?php echo elgg_echo('izap-ecommerce:payment_method'); ?>:
              <b>
            <?php
              if (elgg_is_admin_logged_in ()) {
                $method = ($order->payment_method) ? $order->payment_method : 'paypal';
              } else {
                $method = ($order->payment_method) ? $order->payment_method : 'paypal';
                $info = $IZAP_PAYMENT_GATEWAYS->custom['gateways_info'];

                $method = $info[$method]['title'];
              }
              echo $method;
            ?>
            </b>
            <br />

          <?php
              if (elgg_is_admin_logged_in ()) {
                echo elgg_echo('izap-ecommerce:order_transction_id'); ?>:
                <b>
            <?php echo $order->payment_transaction_id; ?>
              </b>
              <br />
          <?php } ?>
            </div>

          </div>
          <div class="clearfloat"></div>
        </div>

    <?php
              echo elgg_view_title(elgg_echo('izap-ecommerce:order_details'));
    ?>
              <div class="izapcontentWrapper">
      <?php
              $odd_even = 1;
              for ($i = 0; $i < $order->total_items; $i++):
                $item_name = 'item_name_' . $i;
                $item_price = 'item_price_' . $i;
                $item_guid = 'item_guid_' . $i;
                $class = ($odd_even % 2 == 0) ? 'even' : 'odd';

                $product = get_entity($order->$item_guid);
                if ($product) {
                  $product_url = $product->getURL();
//                  $download_link = create_product_download_link_izap_ecommerce($order, $order->$item_guid);
                  $download_link = create_product_download_link_izap_ecommerce(array(
                        'order' => $order,
                        'product_guid' => $order->{$item_guid},
                      ));
                } else {
                  $product_url = '#';
                  $download_link = '#';
                }
      ?>
                <div class="izap-order-detait-<?php echo $class; ?>">
                  <div class="izap-product-float-left" style="width: 55%">
                    <a href="<?php echo $product_url ?>">
            <?php echo $order->$item_name ?>
              </a>
          <?php
                $item_attributes = $item_name . '_attribs';
          ?>
                <p>
                <ul type="disc">
            <?php
                foreach ($order->$item_attributes as $key) {
                  echo '<li>';
                  echo $key;
                  echo'</li>';
                }
            ?>
              </ul>

          <?php
                if ($product_url == '#') {
                  echo '<em>' . elgg_echo('izap-ecommerce:deleted') . '</em>';
                }
          ?>
              </div>

              <div class="izap-product-float-left" style="width: 20%">
          <?php echo $download_link ?>
              </div>

              <div class="izap-product-float-right" style="width: 20%">
                <b><?php echo $IZAP_ECOMMERCE->currency_sign . $order->$item_price ?></b>
              </div>

              <div class="clearfloat"></div>
            </div>
      <?php
                $odd_even++;
              endfor;
      ?>
              <div class="izap-order-detait-total">
                <p align="right">
                  <b>
            <?php
              echo elgg_echo('izap-ecommerce:total');
            ?>
              : <?php echo $IZAP_ECOMMERCE->currency_sign . $order->total_amount ?></b>
        </p>
      </div>
    </div></body>
</html>