/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery"],function(e){return{shareUrl:function(r){e(".shareurl_link").each(function(){var r=e(this),a=r.attr("data-url");r.click(function(e){e.preventDefault(),r.hasClass("copied")||(navigator.clipboard.writeText(a),r.addClass("copied"),setTimeout(function(){r.removeClass("copied"),r.blur()},1200))})})}}});