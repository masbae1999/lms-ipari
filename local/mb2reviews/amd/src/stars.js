/**
 *
 * @package    local_mb2reviews
 * @copyright  2019 - 2020 Mariusz Boloz (mb2themes.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery"],function(i){return{ratingStars:function(){i(document).on("mouseover","a.mb2reviews-star-link-item",function(t){var s=i(this),n=s.closest(".mb2reviews-star-links"),a=1;n.each(function(){i(this).find(".mb2reviews-star-link-item").each(function(){a<=s.attr("data-rating")?i(this).addClass("fill"):i(this).removeClass("fill"),a++})})}),i(document).on("mouseout",".mb2reviews-star-links",function(t){var s=i(this),n=s.attr("data-rating"),a=1;s.each(function(){i(this).find(".mb2reviews-star-link-item").each(function(){a<=n?i(this).addClass("fill"):i(this).removeClass("fill"),a++})})})}}});
