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

class IzapTemplate {
  public $view;
  public $vars;
  public $content;

  public function render($view, $vars = array()) {
    // get the real view path
    //$this->view = izap_get_real_view($view, (($vars['plugin']) ? $vars['plugin'] : $this->plugin));
    $this->view = $view;
    $this->vars = $vars;
    $this->content = elgg_view($this->view, $this->vars);
    return $this->content;
  }

  public function drawPage($vars = array()) {
    global $CONFIG;

    // make body via layout
    $body = elgg_view_layout(
            (($vars['layout']) ? $vars['layout'] : 'content'),
            array(
                'content'=>($vars['area1'] . $vars['area2'] . $vars['area3'] . $vars['area4'])
                )
    );

    // finally draw page
    echo elgg_view_page($vars['title'], $body);
  }
}

//function izap_view($view, $vars) {
//  $view_template = new IzapTemplate();
//  $content = $view_template->render($view, $vars);
//  unset ($view_template);
//  return $content;
//}