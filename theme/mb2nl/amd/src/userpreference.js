/**
 *
 * @module     theme_mb2nl
 * @copyright  2017 - 2021 Mariusz Boloz (https://lmsstyle.com)
 * @license    Commercial https://themeforest.net/licenses
 */ define(["jquery"],function(e){"use strict";var r=function(e,r){var s=new Date;s.setTime(s.getTime()+2592e5);var t="expires="+s.toUTCString();document.cookie=e+"="+r+"; "+t+"; path="+mb2nljs.scp};return{sePreference:function(s,t){return e("body").hasClass("css_31a2")&&e("body").hasClass("nouser")?r(s,t):e("body").hasClass("css_31a2")?void require(["core_user/repository"],function(e){return e.setUserPreference(s,t)}):M.util.set_user_preference(s,t)},setCookie:r,getCookie:function(e){e+="=";for(var r=document.cookie.split(";"),s=0;s<r.length;s++){for(var t=r[s];" "==t.charAt(0);)t=t.substring(1,t.length);if(0==t.indexOf(e))return t.substring(e.length,t.length)}return null}}});