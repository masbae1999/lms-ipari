/**
 *
 * @package    local_mb2reviews
 * @copyright  2019 - 2024 Mariusz Boloz (mb2themes.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */ define(["jquery","core/ajax","core/notification"],function(e,a,t){"use strict";let i=(t,i)=>(e("form.theme-course-filter"),a.call([{methodname:"local_mb2reviews_review_list",args:{courseid:t,page:i}}])[0]),r=(a,t=1,r=0)=>{i(a,t).then(a=>{if(e(".mb2reviews-review-list").append(a.reviews),r){let t=e(".mb2reviews-more");t.removeClass("loading");let i=Number(t.attr("data-page"))+1;t.attr("data-page",i),i==Number(t.attr("data-maxpages"))&&t.addClass("nodata")}}).catch(Notification.exception)},s=a=>{e(document).on("click",".mb2reviews-more",function(){let t=Number(e(this).attr("data-page")),i=Number(e(this).attr("data-maxpages"));if(e(this).hasClass("loading")||t>=i)return null;e(this).addClass("loading"),r(a,t+1,1)})};return{loadReviewList:r,loadMore:s}});