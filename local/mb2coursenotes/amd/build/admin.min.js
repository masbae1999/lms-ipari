/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2022 Mariusz Boloz (https://mb2themes.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery"],function(e){return{toggleFilter:function(){e(document).on("click",".mb2coursenotes_toggle_filter",function(){var o=e(this).closest(".mb2coursenotes-filter-notes");o.hasClass("open")?o.removeClass("open"):o.addClass("open")})}}});