<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
*/

$queue_object = new izapQueue();
foreach ($queue_object->get(get_input('guid')) as $key => $prods){
  get_entity($prods['guid'])->delete();
}
system_message(elgg_echo('izap-videos:queue_retriggred'));
forward(REFERER);
exit;


