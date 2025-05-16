/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery","theme_mb2nl/tgsdb"],function(t,a){return{init:function(){t(document).on("click",".header-tools-jslink",function(){t(this).attr("data-id")===t(".sliding-panel").attr("data-open")?(t(".sliding-panel").attr("data-open","false"),t("body").hasClass("tgsdb")&&a.tgsdbTopPos()):(t(".sliding-panel").attr("data-open",t(this).attr("data-id")),t("body").hasClass("tgsdb")&&a.tgsdbTopPos())})}}});