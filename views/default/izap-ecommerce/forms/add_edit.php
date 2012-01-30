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

$product = $vars['entity'];
$loaded_data = get_loaded_data_izap_ecommerce('izap_product', $product);
?>
<div class="izapcontentWrapper">
  <form action="<?php echo $vars['url']?>action/izap_ecommerce/add" method="POST" enctype="multipart/form-data">
    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:title') . '<br />';
        echo elgg_view('input/text', array(
        'name' => 'izap_product[title]',
        'value' => $loaded_data->title,
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:file') . '<br />';
        echo '<br />' . elgg_view('input/file', array(
                'name' => 'file',
                'value' => $loaded_data->file,
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:image') . '<br />';
        echo elgg_view('input/pro_image', array(
        'name' => 'image',
        'value' => $loaded_data->image,
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:description') . '<br />';
        echo elgg_view('input/longtext', array(
        'name' => 'izap_product[description]',
        'value' => $loaded_data->description,
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:price_range') . '<br />';
        echo elgg_view('input/text', array(
        'name' => 'izap_product[price]',
        'value' => $loaded_data->price,
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:izap_product_discount') . '<br />';
        echo elgg_view('input/text', array(
        'name' => 'izap_product[discount]',
        'value' => (($loaded_data->discount) ? $loaded_data->discount : izap_plugin_settings(array(
        'plugin_name' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
        'setting_name' => 'izap_product_discount',
        ))),
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_view('input/checkboxes', array(
        'name' => 'izap_product[comming_soon]',
        'value' => $loaded_data->comming_soon,
        'options' => array(
                elgg_echo('izap-ecommerce:comming soon') => 'yes',
        ),
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:tags') . '<br />';
        echo elgg_view('input/tags', array(
        'name' => 'izap_product[tags]',
        'value' => $loaded_data->tags,
        ));
        ?>
      </label>
    </p>

    <p>
    <label>
      <?php
        echo elgg_echo('izap-ecommerce:comments');
        echo elgg_view('input/dropdown',array(
          'name' => 'izap_product[comments_on]',
          'value' => $loaded_data->comments_on,
          'options_values' => array('1' => elgg_echo('izap-ecommerce:on'), '0' => elgg_echo('izap-ecommerce:off'))
      ));
      ?>
    </label>
    </p>
    <p><label>
        <?php //echo elgg_echo('izap-elgg-bridge:select_categories')?>
<!--        <br/>-->
      </label>
      <?php// echo elgg_view('input/izap_categories', array('name' => 'izap_product[categories]','plugin_id' => GLOBAL_IZAP_ECOMMERCE_PLUGIN, 'value'=>$loaded_data->categories));?>
    </p>
    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:access_id') . '<br />';
        echo elgg_view('input/access', array(
        'name' => 'izap_product[access_id]',
        'value' => isset($loaded_data->access_id) ? $loaded_data->access_id : ACCESS_DEFAULT,
        ));
        ?>
      </label>
    </p>

    <p>
      <label>
        <?php
        echo elgg_echo('izap-ecommerce:terms') . '<br />';
        echo elgg_view('input/longtext', array(
        'name' => 'izap_product[terms]',
        'value' => $loaded_data->terms,
        ));
        ?>
      </label>
    </p>

    <?php
    echo elgg_view('categories', array('entity' => $product));
    if($vars['archive']) {
      echo elgg_view('input/hidden', array('name' => 'izap_product[parent_of]', 'value' => (int)$vars['parent_guid']));
    }else {
      echo elgg_view('input/hidden', array('name' => 'izap_product[guid]', 'value' => (int)$loaded_data->guid));
    }
    echo elgg_view('input/securitytoken');
    echo elgg_view('input/submit', array('value' => elgg_echo('izap-ecommerce:save')));
    ?>
  </form>
</div>