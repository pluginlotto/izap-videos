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

?>
this.screenshotPreview = function(){
xOffset = 10;
yOffset = 30;
$("a.screenshot").hover(function(e){
this.t = this.title;
this.title = "";
var c = (this.t != "") ? "<br/>" + this.t : "";
$("body").append("<p id='screenshot'><img src='"+ this.rel +"' alt='url preview' />"+ c +"</p>");
$("#screenshot").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px").fadeIn("fast");
},function(){
$("#screenshot").remove();
});
$("a.screenshot").mousemove(function(e){
$("#screenshot").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px");
});
};

$(document).ready(function(){
screenshotPreview();
});

function izap_vid(baseurl,eid, vid){
url = baseurl+'pg/view/'+eid+'/izap_load/'+vid+'?shell=no&username=admin&context=dashboard&callback=true';
wid = '#widgetcontent'+eid;
$(wid).html('<div align="center" class="ajax_loader"></div>');
$(wid).load(url);
}

var video_loading_image = '<?php echo $vars['url'] . 'mod/'.GLOBAL_IZAP_VIDEOS_PLUGIN.'/_graphics/ajax-loader_black.gif'?>';
var play_image = '<?php echo $vars['url'] . 'mod/'.GLOBAL_IZAP_VIDEOS_PLUGIN.'/_graphics/play_button.png'?>';
$(".izap_ajaxed_thumb").live('click', function() {
$("#load_video_" + this.rel + "").html('<img src="' + video_loading_image + '" />');
$("#load_video_" + this.rel + "").load('' + this.href + '');
return false;
});