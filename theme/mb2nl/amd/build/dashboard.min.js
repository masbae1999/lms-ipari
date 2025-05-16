/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery","core/ajax","core/notification"],function(a,e,t){"use strict";let o=o=>{e.call([{methodname:"theme_mb2nl_"+o+"_dashboard",args:{}}])[0].then(e=>{a(".dshb-wrap").html(e.dashboard)}).catch(t.exception)};return{loadDashboard:o}});