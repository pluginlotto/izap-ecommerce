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

global $CONFIG;

if (!$CONFIG->post_byizap->form_validated) {
  register_error(elgg_echo("izap_elgg_bridge:error_empty_input_fields"));
  forward($_SERVER['HTTP_REFERER']);
}

if(!filter_var($CONFIG->post_byizap->attributes['send_email'], FILTER_VALIDATE_EMAIL) || !filter_var($CONFIG->post_byizap->attributes['email'], FILTER_VALIDATE_EMAIL)) {
  register_error(__('not_valid_email'));
  forward($_SERVER['HTTP_REFERER']);
}

$entity = get_entity($CONFIG->post_byizap->attributes['guid']);
if (!$entity) {
  register_error(__('not_valid_entity'));
  forward($_SERVER['HTTP_REFERER']);
}

$params=array();
$params['to']=$CONFIG->post_byizap->attributes['send_email'];
$params['from']=$CONFIG->post_byizap->attributes['email'];
$params['from_username']=$CONFIG->post_byizap->attributes['name'];
$params['subject']="Offer: {$entity->title}";
$params['msg']="
  Hello, {$CONFIG->post_byizap->attributes['send_name']} \n
  I like this post, {$entity->getURL()} & please go through that once.\n
  <p>{$CONFIG->post_byizap->attributes['msg']}</p>
  From:\n
    {$CONFIG->post_byizap->attributes['name']},
    {$CONFIG->post_byizap->attributes['email']}.
  ";
//func_printarray_byizap($params);
$success=func_send_mail_byizap($params);
// send email

// Success message
if($success) {
  system_message(__('success_send_to_friend'));
  unset ($_SESSION['postArray']);
} else {
  register_error(__('error_send_to_friend'));
}forward($entity->getURL());
