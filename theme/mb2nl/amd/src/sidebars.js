/**
 *
 * @module     theme_mb2nl
 * @copyright  2017 - 2021 Mariusz Boloz (https://lmsstyle.com)
 * @license    Commercial https://themeforest.net/licenses
 */ define(["jquery","theme_mb2nl/userpreference"],function(e,a){return{sidebarToggle:function(){var r=e("body");e(".theme-hide-sidebar").click(function(){var t=e(this).attr("data-text_show"),s=e(this).attr("data-text_hide");r.hasClass("hide-sidebars")?(r.removeClass("hide-sidebars"),a.sePreference("mb2_usersidebar","true"),e(this).attr("aria-expanded","true"),e(this).attr("aria-label",s)):(r.addClass("hide-sidebars"),a.sePreference("mb2_usersidebar","false"),e(this).attr("aria-expanded","false"),e(this).attr("aria-label",t))})}}});