/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery","theme_mb2nl/userpreference"],function(e,o){return{panelLink:function(){e(document).on("click",".save-location",function(){0==e(this).attr("data-scrollpos")?o.sePreference("mb2_scrollpos",window.pageYOffset):o.sePreference("mb2_scrollpos",0)}),e(".save-location").attr("data-scrollpos")>0&&(window.scrollTo(0,e(".save-location").attr("data-scrollpos")),o.sePreference("mb2_scrollpos",0))}}});