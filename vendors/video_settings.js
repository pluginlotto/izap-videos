/*
 *    This file is part of izap-videos plugin for Elgg.
 *
 *    izap-videos for Elgg is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    izap-videos for Elgg is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with izap-videos for Elgg.  If not, see <http://www.gnu.org/licenses/>.
 */
$(document).ready(function() {
    if ($(this).on) {
        $("input:radio[name='params[Onserver_enabled_izap_videos]']").on("click", function() {
            youtube_on_event();
        });
        $("#offserver_disable").on("click", function() {
            offserver_disable();
        });
        $("#offserver_enable").on("click", function() {
            offserver_enable();
        });
    } else {
        $("input:radio[name='params[Onserver_enabled_izap_videos]']").bind("click", function() {
            youtube_on_event();
        });
        $("#offserver_disable").bind("click", function() {
            offserver_disable();
        });
        $("#offserver_enable").bind("click", function() {
            offserver_enable();
        });
    }

    function youtube_on_event() {
        var radio_input = $("input:radio[name='params[Onserver_enabled_izap_videos]']:checked").val();
        if (radio_input === 'youtube') {
            $("#youtube_key").show();
            $("#youtube_key_youtube").show();
        } else if (radio_input === 'yes') {
            $("#youtube_key").hide();
            $("#youtube_key_youtube").hide();
        } else if (radio_input === 'no') {
            $("#youtube_key").hide();
            $("#youtube_key_youtube").hide();
        }
    }
    
    function offserver_disable() {
        $("#offserver_key_yes").hide();
        $("#offserver_key_no").hide();
        $("#offserver_key").hide();
    }
    
    function offserver_enable() {
        $("#offserver_key_yes").show();
        $("#offserver_key_no").show();
    }
});