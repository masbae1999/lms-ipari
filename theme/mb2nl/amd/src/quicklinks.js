/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery"],function(n){"use strict";var i=function(){n(".quicklinks").addClass("open")},t=function(){n(".quicklinks").removeClass("open")};return{quickLinksInit:function(s){n(document).on("click","#quicklinks-toggle",function(s){n(this).closest(".quicklinks").hasClass("open")?(t(),n(this).attr("aria-expanded","false")):(i(),n(this).attr("aria-expanded","true"))}),n("body").on("click",function(i){n(i.target).closest(".quicklinks").length||t()})}}});