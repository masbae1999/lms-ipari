/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery"],function(s){var t=function(t,a,e){var l=a.length?a.offset().top+70:180;s(window).scrollTop()>l?(a.css("height",e),t.addClass("sticky-el")):(t.removeClass("sticky-el"),a.css("height",0)),s(window).scrollTop()>l+100?t.addClass("sticky-el-jump"):t.removeClass("sticky-el-jump")};return{init:function(){var a=s("body").hasClass("sticky-nav1")||s("body").hasClass("sticky-nav4")?s("#main-navigation"):s("#master-header"),e=s(".sticky-replace-el"),l=a.outerHeight(!0);if(!a.length||s("body").hasClass("sticky-nav0"))return null;s(window).on("scroll",function(){t(a,e,l)}),setTimeout(function(){t(a,e,l)},10)}}});