<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   local_mb2builder
 * @copyright 2017 Mariusz Boloz (http://marbol2.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */

defined('MOODLE_INTERNAL') || die();


if (! class_exists( 'mb2builderBuilderContent' )) {

    class mb2builderBuilderContent
    {


        /**
         *
         * Method to get courses
         *
         */
        public static function get_content( $items, $options) {

            $output = '';
            $i = 0;
            $x = 0;

            if (! count( $items )) {
                return '<div class="theme-box">' . get_string( 'nothingtodisplay' ) . '</div>';
            }

            foreach ($items as $item) {

                $i++;
                $x++;
                $item_cls = $i%2 ? ' item-odd' : ' item-even';

                // Color class
                $c_color = self::get_custom_color( $item->id, $options );
                $item_cls .= $c_color ? ' color' : '';

                // Show item b
                $showtext = ( $options['desclimit'] > 0 || $options['linkbtn'] || $item->price );

                // Item id for admin users
                $item_ID  = '';
                $item_edit_link  = '';

                if (is_siteadmin()) {
                    $item_ID = ' <span class="helper-itemid" style="background-color:green;color:#fff;padding:0 3px;">ID: ' . $item->id . '</span>';
                }

                $output .= '<div class="mb2-pb-content-item theme-box item-' . $item->id . $item_cls .'">';
                $output .= $item_ID . $item_edit_link;
                $output .= '<div class="mb2-pb-content-item-inner">';
                $output .= '<div class="mb2-pb-content-item-a">';

                $output .= self::get_image_from_desc( $item->description );

                if ($item->imgurl) {
                    $output .= '<div class="mb2-pb-content-img">';
                    $output .= '<a href="' . $item->link . '">';
                    $output .= '<img src="' . $item->imgurl . '" alt="' . $item->imgname . '" />';
                    $output .= '</a>';
                    $output .= '</div>';
                }

                $output .= '<div class="mb2-pb-content1">';
                $output .= '<div class="mb2-pb-content2">';
                $output .= '<div class="mb2-pb-content3">';
                $output .= '<div class="mb2-pb-content4">';

                $output .= '<h4 class="mb2-pb-content-title">';
                $output .= '<a href="' . $item->link . '">';
                $output .= self::set_wordlimit( $item->title, $options['titlelimit'] );
                $output .= '</a>';
                $output .= '</h4>';
                $output .= ( $item->details && $options['details'] ) ? '<div class="mb2-pb-content-details">' . $item->details . '</div>' : '';
                $output .= $c_color ? '<span class="color-el" style="background-color:' . $c_color . ';"></span>' : '';
                $output .= '</div>';

                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';

                if ($showtext) {
                    $output .= '<div class="mb2-pb-content-item-b">';

                    if ($options['desclimit'] > 0) {
                        $output .= '<div class="mb2-pb-content-desc">';
                        $output .= self::set_wordlimit( $item->description, $options['desclimit'] );
                        $output .= '</div>';
                    }

                    if ($options['linkbtn']) {
                        $btntext = $options['btntext'] ? $options['btntext'] : get_string('btntext','local_mb2builder');

                        $output .= '<div class="mb2-pb-content-readmore">';
                        $output .= '<a class="btn btn-primary" href="' . $item->link . '">' . $btntext . '</a>';
                        $output .= '</div>';
                    }

                    if ($item->price) {
                        $output .= '<div class="mb2-pb-content-price">';
                        $output .=  $item->price;
                        $output .= '</div>';
                    }

                    $output .= '</div>';
                }

                $output .= '</div>';
                $output .= '</div>';

            }

            return $output;

        }





        /**
         *
         * Method to get courses
         *
         */
        public static function get_courses_content( $courses, $options) {
            $output = '';

            if (! count( $courses )) {
                return '<div class="theme-course-item nothingtodisplay">' . get_string( 'nothingtodisplay' ) . '</div>';
            }

            foreach ($courses as $course) {
                $courselink = new moodle_url( '/course/view.php', array( 'id' => $course->id ) );
                $price = self::course_list_price( $course->id, $options );
                //$cls = $price ?  ' isprice' : ' noprice';

                $cls = ' cat-' . $course->category;

                $coursecontext = context_course::instance( $course->id );
                $bestseller = self::is_bestseller( $coursecontext->id, $course->category );
                $cls .= $bestseller ?  ' bestseller' : '';

                $output .= '<div class="theme-course-item theme-box course-' . $course->id . $cls . '">';
                $output .= '<div class="theme-course-item-inner">';

                $output .= '<div class="image-wrap">';

                $output .= '<div class="image">';
                $output .= $bestseller ? '<span class="bestseller">' . get_string( 'bestseller', 'local_mb2builder' ) . '</span>': '';
                $output .= '<img src="' . self::course_image_url( $course->id, true ) . '" alt="' . $course->fullname . '">';
                $output .= '</div>'; // image

                $output .= '<div class="image-content">';
                $output .= '<a href="' . $courselink . '" class="linkbtn">' . get_string('view') . '</a>';
                $output .= '</div>'; // image-content

                $output .= '</div>'; // image-wrap

                $output .= '<h4 class="title">';
                $output .= '<a href="' . $courselink . '">' . $course->fullname . '</a>';
                $output .= '</h4>';

                $output .= '<div class="course-content">';
                $output .= self::course_list_teachers( $course->id, $options );
                $output .= '</div>'; // course-content

                $output .= '<div class="course-footer">';
                $output .= $price;
                $output .= self::course_list_date( $course, $options );
                $output .= self::course_list_students( $course->id, $options );
                $output .= '</div>'; // course-content

                $output .= '</div>'; // theme-course-item
                $output .= '</div>'; // theme-course-item-inner
            }

            return $output;

        }




        /**
         *
         * Method to get course price on course list
         *
         */
        public static function course_list_students( $courseid, $options) {
            global $PAGE;

            $output = '';
            $coursestudentscount = $options['coursestudentscount']; //theme_mb2nl_theme_setting( $PAGE, 'coursestudentscount' );
            $students = self::get_sudents_count( $courseid );

            if (! $coursestudentscount) {
                return;
            }

            $output .= '<div class="students">';
            $output .= '<i class="fa fa-graduation-cap"></i>' . $students;
            $output .= '</div>';

            return $output;

        }




        /**
         *
         * Method to get users enrolled to paid courses
         *
         */
        public static function get_payenrolled_users( $categoryid) {
            global $DB;

            $params = [];
            $sqlwhere = ' WHERE 1=1';

            $sqlquery = 'SELECT DISTINCT ra.id, ra.contextid FROM {role_assignments} ra';

            $sqlquery .= ' JOIN {context} cx ON cx.id=ra.contextid';
            $sqlquery .= ' JOIN {enrol} er ON er.courseid=cx.instanceid';
            $sqlquery .= ' JOIN {course} c ON er.courseid=c.id';

            list( $payenrolinsql, $payenrolparams ) = $DB->get_in_or_equal( self::pay_enrolements() );
            $params = array_merge( $params, $payenrolparams );
            $sqlwhere .= ' AND er.enrol ' . $payenrolinsql;
            $sqlwhere .= ' AND er.status = ' . ENROL_INSTANCE_ENABLED;
            $sqlwhere .= ' AND c.visible = 1';

            if ($categoryid) {
                $sqlwhere .= ' AND c.category = ' . $categoryid;
            }

            $sqlwhere .= ' AND ra.roleid = ' . self::get_user_role_id();

            return $DB->get_records_sql( $sqlquery . $sqlwhere, $params );

        }



        /**
         *
         * Method to get bestseller courses array
         *
         */
        public static function bestsellers( $itemsnum, $categoryid) {

            $payenrolled_roles = self::get_payenrolled_users( $categoryid );
            $bestsellers = [];

            if (! count( $payenrolled_roles )) {
                return [];
            }

            foreach ($payenrolled_roles as $role) {
                $bestsellers[] = $role->contextid;
            }

            $bestsellers = array_count_values( $bestsellers );

            arsort( $bestsellers );

            $bestsellers = array_slice( $bestsellers, 0, $itemsnum, true );

            return $bestsellers;

        }







        /**
         *
         * Method to get bestseller courses array
         *
         */
        public static function is_bestseller( $contextid, $categoryid = 0) {
            $bestsellers = self::bestsellers( 3, $categoryid );

            if (array_key_exists( $contextid, $bestsellers )) {
                return true;
            }

            return false;

        }





        /**
         *
         * Method to get course students count
         *
         */
        public static function get_sudents_count( $courseid = 0) {
            global $PAGE, $COURSE;

            $iscourseid = $courseid ? $courseid : $COURSE->id;
            $coursecontext = context_course::instance( $iscourseid );
            $students = get_role_users( self::get_user_role_id(), $coursecontext );

            return count( $students );
        }







        /**
         *
         * Method to get course teachers on course list
         *
         */
        public static function course_list_teachers( $courseid, $options) {
            global $PAGE;

            $output = '';
            $teachers = self::get_course_teachers( $courseid );
            $coursinstructor = $options['coursinstructor'];//theme_mb2nl_theme_setting( $PAGE, 'coursinstructor' );

            if (! count( $teachers ) || ! $coursinstructor) {
                return;
            }

            $otherteachers = count( $teachers ) - 1;
            $mainteacher = array_shift( $teachers );

            $output .= '<div class="teacher">';
            $output .= $mainteacher['firstname'];
            $output .= ' ' . $mainteacher['lastname'];

            if ($otherteachers) {
                $output .= ' <span class="info">(';
                $output .= get_string( 'xmoreteachers', 'local_mb2builder', array( 'teachers' => $otherteachers ) );
                $output .= ')</span>';
            }

            $output .= '</div>';

            return $output;


        }





        /**
         *
         * Method to get course teachers
         *
         */
        public static function get_course_teachers( $courseid = 0) {
            global $COURSE, $USER, $OUTPUT, $CFG;

            $results = [];
            $teacherroleid = self::get_user_role_id( true );
            $iscourseid = $courseid ? $courseid : $COURSE->id;
            $context = context_course::instance( $iscourseid );
            $teachers = get_role_users( $teacherroleid, $context, false, 'u.id,u.firstname,u.firstnamephonetic,u.lastnamephonetic,u.middlename,u.alternatename,u.email,u.lastname,u.picture,u.imagealt,u.description' );
            $hiddenuserfields = explode( ',', $CFG->hiddenuserfields );
            $isdesc = ! in_array( 'description', $hiddenuserfields );

            foreach ($teachers as $teacher) {
                $results[] = array(
                    'id' => $teacher->id,
                    'firstname' => $teacher->firstname,
                    'lastname' => $teacher->lastname,
                    //'description' => $isdesc ?  $teacher->description : '',
                    //'picture' => $OUTPUT->user_picture( $teacher, array( 'size' => 100, 'link' => 0 ) ),
                    //'coursescount' => theme_mb2nl_get_instructor_courses_count( $teacher->id ),
                    //'studentscount' => theme_mb2nl_get_instructor_students_count( $teacher->id )
                );
            }

            return $results;

        }




        /**
         *
         * Method to get course date on course list
         *
         */
        public static function course_list_date( $course, $options) {
            global $PAGE;

            $output = '';

            if (! $options['coursestartdate']) {
                return;
            }

            $output .= '<div class="date">';
            $output .= '<i class="fa fa-calendar"></i>' . userdate( $course->startdate, get_string('strftimedatemoncourselist', 'local_mb2builder') );
            $output .= '</div>';

            return $output;


        }



        /**
         *
         * Method to get course price on course list
         *
         */
        public static function course_list_price( $courseid, $options = []) {
            global $PAGE;

            $output = '';

            if (! $options['courseprice']) {
                return;
            }

            $iscourseprice = self::is_course_price( $courseid );
            $priceobj = self::get_course_price( $courseid );
            $currency = '';

            if (! $iscourseprice || ! $priceobj || $priceobj->cost == 0) {
                $price = get_string( 'noprice', 'local_mb2builder' );
            } else {
                $price = $priceobj->cost;
                $currency = self::get_currency_symbol( $priceobj->currency );
            }

            $output .= '<div class="price-container">';
            $output .= '<span class="currency">' . $currency . '</span>';
            $output .= '<span class="price">' . $price . '</span>';
            $output .= '</div>';

            return $output;

        }




        /**
         *
         * Method to get currency array
         *
         */
        public static function get_currency_arr() {

            return array('ALL:4c,65,6b'=>'ALL','AFN:60b'=>'AFN','ARS:24'=>'ARS','AWG:192'=>'AWG','AUD:24'=>'AUD','AZN:43c,430,43d'=>'AZN','BSD:24'=>'BSD','BBD:24'=>'BBD','BYR:70,2e'=>'BYR','BZD:42,5a,24'=>'BZD','BMD:24'=>'BMD','BOB:24,62'=>'BOB','BAM:4b,4d'=>'BAM','BWP:50'=>'BWP','BGN:43b,432'=>'BGN','BRL:52,24'=>'BRL','BND:24'=>'BND','KHR:17db'=>'KHR','CAD:24'=>'CAD','KYD:24'=>'KYD','CLP:24'=>'CLP','CNY:a5'=>'CNY','COP:24'=>'COP','CRC:20a1'=>'CRC','HRK:6b,6e'=>'HRK','CUP:20b1'=>'CUP','CZK:4b,10d'=>'CZK','DKK:6b,72'=>'DKK','DOP:52,44,24'=>'DOP','XCD:24'=>'XCD','EGP:a3'=>'EGP','SVC:24'=>'SVC','EEK:6b,72'=>'EEK','EUR:20ac'=>'EUR','FKP:a3'=>'FKP','FJD:24'=>'FJD','GHC:a2'=>'GHC','GIP:a3'=>'GIP','GTQ:51'=>'GTQ','GGP:a3'=>'GGP','GYD:24'=>'GYD','HNL:4c'=>'HNL','HKD:24'=>'HKD','HUF:46,74'=>'HUF','ISK:6b,72'=>'ISK','INR:20a8'=>'INR','IDR:52,70'=>'IDR','IRR:fdfc'=>'IRR','IMP:a3'=>'IMP','ILS:20aa'=>'ILS','JMD:4a,24'=>'JMD','JPY:a5'=>'JPY','JEP:a3'=>'JEP','KZT:43b,432'=>'KZT','KES:4b,73,68,73'=>'KES','KGS:43b,432'=>'KGS','LAK:20ad'=>'LAK','LVL:4c,73'=>'LVL','LBP:a3'=>'LBP','LRD:24'=>'LRD','LTL:4c,74'=>'LTL','MKD:434,435,43d'=>'MKD','MYR:52,4d'=>'MYR','MUR:20a8'=>'MUR','MXN:24'=>'MXN','MNT:20ae'=>'MNT','MZN:4d,54'=>'MZN','NAD:24'=>'NAD','NPR:20a8'=>'NPR','ANG:192'=>'ANG','NZD:24'=>'NZD','NIO:43,24'=>'NIO','NGN:20a6'=>'NGN','KPW:20a9'=>'KPW','NOK:6b,72'=>'NOK','OMR:fdfc'=>'OMR','PKR:20a8'=>'PKR','PAB:42,2f,2e'=>'PAB','PYG:47,73'=>'PYG','PEN:53,2f,2e'=>'PEN','PHP:50,68,70'=>'PHP','PLN:7a,142'=>'PLN','QAR:fdfc'=>'QAR','RON:6c,65,69'=>'RON','RUB:440,443,431'=>'RUB','SHP:a3'=>'SHP','SAR:fdfc'=>'SAR','RSD:414,438,43d,2e'=>'RSD','SCR:20a8'=>'SCR','SGD:24'=>'SGD','SBD:24'=>'SBD','SOS:53'=>'SOS','ZAR:52'=>'ZAR','KRW:20a9'=>'KRW','LKR:20a8'=>'LKR','SEK:6b,72'=>'SEK','CHF:43,48,46'=>'CHF','SRD:24'=>'SRD','SYP:a3'=>'SYP','TWD:4e,54,24'=>'TWD','THB:e3f'=>'THB','TTD:54,54,24'=>'TTD','TRY:54,4c'=>'TRY','TRL:20a4'=>'TRL','TVD:24'=>'TVD','UAH:20b4'=>'UAH','GBP:a3'=>'GBP','USD:24'=>'USD','UYU:24,55'=>'UYU','UZS:43b,432'=>'UZS','VEF:42,73'=>'VEF','VND:20ab'=>'VND','YER:fdfc'=>'YER','ZWD:5a,24'=>'ZWD');

        }




        /**
         *
         * Method to get currency symbol
         *
         */
        public static function get_currency_symbol( $currency) {

            $currencyarr = self::get_currency_arr();
            $output = '';

            foreach ($currencyarr as $k => $c) {
                $curr = explode( ':', $k );

                if ($c === $currency) {
                    $curr2 = explode( ',', $curr[1] );

                    foreach ($curr2 as $c) {
                        $output .= '&#x' . $c;
                    }

                }
            }

            return $output;

        }






        /**
         *
         * Method to get course enrolements methods
         *
         */
        public static function get_course_enrolements( $courseid = 0) {
            global $DB, $COURSE;
            $iscourseid = $courseid ? $courseid : $COURSE->id;
            return $DB->get_records( 'enrol', array( 'courseid' => $iscourseid, 'status' => 0 ), '', 'id, enrol, name, sortorder' );
        }



        /**
         *
         * Method to check if course require pay
         *
         */
        public static function is_course_price( $courseid = 0) {

            $enrolements = self::get_course_enrolements( $courseid );
            $paymethods = self::pay_enrolements();

            foreach ($enrolements as $enrol) {
                if (in_array( $enrol->enrol, $paymethods)) {
                    return $enrol->enrol;
                }
            }

            return false;

        }




        /**
         *
         * Method to get course price
         *
         */
        public static function get_course_price( $courseid = 0) {
            global $DB, $COURSE;

            $iscourseid = $courseid ? $courseid : $COURSE->id;
            $payenrol = self::is_course_price( $iscourseid );

            if (! $payenrol) {
                return 0;
            }

            $recordsql = 'SELECT cost, currency FROM {enrol} WHERE courseid = ? AND enrol = ?';
            $price = $DB->get_record_sql( $recordsql, array( $iscourseid, $payenrol ) );

            return $price;
        }




        /**
         *
         * Method to set custom color for item
         *
         */
        public static function get_custom_color( $id, $atts) {

            $colors = self::get_color_arr( $atts );

            foreach ($colors as $color) {
                if ($color['id'] == $id) {
                    return $color['color'];
                }
            }

            return false;

        }




        /**
         *
         * Method to get colors array
         *
         */
        public static function get_color_arr( $atts) {

            $colors = [];
            $defColors = $atts['colors'];
            $colorArr1 = explode( ',', str_replace( ' ', '', $defColors ) );
            $i=-1;

            foreach ($colorArr1 as $color) {
                if ($color) {
                    $i++;
                    $colorEl = explode( ':', $color );
                    $colors[$i]['id']= $colorEl[0];
                    $colors[$i]['color'] = $colorEl[1];
                }
            }

            return $colors;

        }




        /**
         *
         * Method to get colors array
         *
         */
        public static function get_image_from_desc( $desc) {
            $urlfromdesc = self::get_image_from_text( s( $desc ), true );
            $namefromdesc = basename( $urlfromdesc );

            if (preg_match( '@%20@', $namefromdesc )) {
                return '<span style="color:red;"><strong>' . get_string( 'imgnoticespace', 'local_mb2pages', array( 'img'=> urldecode( $namefromdesc ) ) ) . '</strong></span>';
            }

            return;
        }




        /**
         *
         * Method to set string word limit
         *
         */
        public static function set_wordlimit( $string, $limit = 999, $end = '...') {

            // $output = $string;

            if ($limit >= 999) {
                return $string;
            }

            if ($limit == 0) {
                return;
            }

            $content_limit = strip_tags( $string );
            $words = explode( ' ', $content_limit );
            $words_count = count( $words );
            $new_string = implode( ' ', array_splice( $words, 0, $limit ) );
            $end_char = ( $end !== '' && $words_count > $limit ) ? $end : '';
            $output = $new_string . $end_char;

            return $output;

        }





        /**
         *
         * Method to get courses
         *
         */
        public static function get_courses( $opt = [], $count = false) {

            global $DB, $CFG, $PAGE;

            //$page = isset( $opt['page'] ) ? $opt['page'] : 1;
            $perpage = $opt['limit'];
            $limitfrom = 0;//( $page -1 ) * $perpage;
            $teacherroleid = self::get_user_role_id( true );

            $params = [];
            $sqlwhere = ' WHERE 1=1';
            $sqlorder = '';
            $sql = '';

            if ($count) {
                // Select courses count
                $sql .= 'SELECT COUNT(DISTINCT c.id) FROM {course} c';
            } else {
                // Select courses
                $sql .= 'SELECT DISTINCT c.* FROM {course} c';
            }

            // Check for frontpage course
            // and for hidden courses
            $sqlwhere .= ' AND c.id > 1';

            $sqlwhere .= ' AND c.visible = 1';

            // Filter exclude courses (OLD)
            if ($opt['excourses'] &&  $opt['courseids']) {
                $isnot = '';
                $opt['courseids'] = explode( ',', $opt['courseids'] );

                if ($opt['excourses'] === 'exclude') {
                    $isnot = count( $opt['courseids'] ) > 1 ? 'NOT ' : '!';
                }

                list( $coursesnsql, $coursesparams ) = $DB->get_in_or_equal( $opt['courseids'] );
                $params = array_merge( $params, $coursesparams );
                $sqlwhere .= ' AND c.id ' . $isnot . $coursesnsql;
            }

            // Filter exclude categories (OLD)
            if ($opt['excats'] &&  $opt['catids']) {
                $isnot = '';
                $opt['catids'] = explode( ',', $opt['catids'] );

                if ($opt['excats'] === 'exclude') {
                    $isnot = count( $opt['catids'] ) > 1 ? 'NOT ' : '!';
                }

                list( $excatinsql, $excatparams ) = $DB->get_in_or_equal( $opt['catids'] );
                $params = array_merge( $params, $excatparams );
                $sqlwhere .= ' AND c.category ' . $isnot . $excatinsql;
            }

            // Filter categories
            if (! empty( $opt['categories'] )) {
                list( $catinsql, $catparams ) = $DB->get_in_or_equal( $opt['categories'] );
                $params = array_merge( $params, $catparams );
                $sqlwhere .= ' AND c.category ' . $catinsql;
            }

            // Filter instructors
            if (! empty( $opt['instructors'] )) {
                list( $instructorsinsql, $instructorsparams ) = $DB->get_in_or_equal( $opt['instructors'] );
                $params = array_merge( $params, $instructorsparams );
                $sqlwhere .= ' AND EXISTS( SELECT ra.id FROM {role_assignments} ra JOIN {context} cx ON ra.contextid = cx.id AND cx.contextlevel = ' . CONTEXT_COURSE . ' WHERE cx.instanceid = c.id AND ra.roleid = ' . $teacherroleid . ' AND ra.userid ' . $instructorsinsql . ')';
            }

            // Filter price
            if ( isset( $opt['price'] ) && ( $opt['price'] == 0 ||  $opt['price'] == 1 )) {
                $params[] = ENROL_INSTANCE_ENABLED;
                list( $priceinsql, $priceparams ) = $DB->get_in_or_equal( self::pay_enrolements() );
                $params = array_merge( $params, $priceparams );
                $isnot = '';

                if ($opt['price'] == 0) {
                    $isnot = 'NOT ';
                }

                $sqlwhere .= ' AND ' . $isnot . 'EXISTS( SELECT er.id FROM {enrol} er WHERE er.courseid = c.id AND er.status = ? AND er.enrol ' . $priceinsql . ')';
            }

            // Filter search
            if (isset( $opt['searchstr'] )  && $opt['searchstr'] !== '') {
                $searchstr = strip_tags( $opt['searchstr'] );
                $searchstr = trim( $searchstr );
                $params[] = '%' . $searchstr . '%';
                $concat = $DB->sql_concat("COALESCE(c.summary, '')", "' '", 'c.fullname', "' '", 'c.idnumber', "' '", 'c.shortname');
                $sqlwhere .= ' AND ' . $DB->sql_like($concat, '?');
            }

            $sqlorder .= ' ORDER BY c.sortorder';

            if ($count) {
                return $DB->count_records_sql( $sql . $sqlwhere . $sqlorder, $params, $limitfrom , $perpage );
            }

            return $DB->get_records_sql( $sql . $sqlwhere . $sqlorder, $params, $limitfrom, $perpage );

        }







        /**
         *
         * Method to get user role id
         *
         */
        public static function get_user_role_id( $teacher = false) {

            global $DB, $PAGE;

            $usershortname = $teacher ? get_config('local_mb2builder')->teacherroleshortname : get_config('local_mb2builder')->studentroleshortname;
            $query = 'SELECT id FROM {role} WHERE shortname = ?';

            if ( ! $DB->record_exists_sql( $query, array( $usershortname ) )) {
                return 0;
            }

            $roleid = $DB->get_record( 'role', array( 'shortname' => $usershortname ), 'id', MUST_EXIST );

            return $roleid->id;

        }





        /**
         *
         * Method to get payments erollement methods
         *
         */
        public static function pay_enrolements() {
            $enrolements = array(
                'paypal',
                'fee',
                'stripepayment'
            );

            return $enrolements;
        }





        /**
         *
         * Method to get categories
         *
         */
        public static function get_categories( $options) {

            global $CFG, $USER, $DB, $OUTPUT;

            require_once( $CFG->dirroot . '/course/lib.php' );

            if (! self::moodle_from( 2018120300 )) {
                require_once( $CFG->libdir . '/coursecatlib.php' );
            }

            $categories = [];

            $catids = str_replace( ' ', '', $options['catids'] );
            $exCats = $options['excats'] === 'exclude' ? ' NOT' : '';

            $query = 'SELECT * FROM ' . $CFG->prefix . 'course_categories';
            $query .= ( $options['excats'] && $catids > 0 ) ? ' WHERE id' . $exCats . ' IN (' . $catids . ')' : '';
            $query .= ' ORDER BY sortorder';

            $categories = $DB->get_records_sql( $query );
            $itemCount = count( $categories );

            if (! $itemCount) {
                return [];
            }

            foreach ($categories as $category) {

                $context = context_coursecat::instance( $category->id );
                $coursecat_canmanage = has_capability( 'moodle/category:manage', $context );

                if (( ! isset($category->visible ) || ! $category->visible ) && ! $coursecat_canmanage) {
                    unset( $categories[$category->id] );
                }

                // Get category image
                $image_options = array( 'context' => $context->id, 'mod' => 'coursecat', 'area' => 'description', 'itemid' => 0 );
                $imgUrlAtt = self::get_image( $image_options, false, $category->description );
                $imgNameAtt = self::get_image( $image_options, true, $category->description );

                $moodle33 = 2017051500;
                $placeholder_image = $CFG->version >= $moodle33 ?
                $OUTPUT->image_url( 'course-default', 'theme' ) : $OUTPUT->pix_url( 'course-default', 'theme' );
                $category->imgurl = $imgUrlAtt ? $imgUrlAtt : $placeholder_image;
                $category->imgname = $imgNameAtt;

                // Define item elements
                $category->link = new moodle_url( $CFG->wwwroot . '/course/index.php', array( 'categoryid' => $category->id ) );
                $category->link_edit = new moodle_url( $CFG->wwwroot . '/course/editcategory.php', array( 'id' => $category->id ) );
                $category->edit_text = get_string( 'editcategorythis', 'core' );

                $category->title = $category->name;
                $category->description = file_rewrite_pluginfile_urls( $category->description, 'pluginfile.php', $context->id, 'coursecat', 'description', null );
                $category->description = format_text( $category->description, FORMAT_HTML );

                if (isset( $category->visible ) && ! $category->visible) {
                    $category->title .= ' (' . get_string('hidden','local_mb2builder') . ')';
                }

                // Get course count in a category
                $coursesList = [];

                if ($category->id && $category->visible) {
                    if (self::moodle_from( 2018120300 )) {
                        $coursesList = core_course_category::get( $category->id )->get_courses( array( 'recursive' => false ) );
                    }
                    else
                    {
                        $coursesList = coursecat::get( $category->id )->get_courses( array( 'recursive' => false ) );
                    }
                }

                $courseCount = count( $coursesList );
                $courseString = $courseCount > 1 ? get_string( 'courses' ) : get_string( 'course' );
                $category->details = $courseCount > 0 ? $courseCount . ' ' . $courseString : get_string( 'nocourseincategory', 'local_mb2builder' );
                $category->redmoretext = '';
                $category->price = '';

            }

            // Slice categories array by categories limit
            $categories = array_slice( $categories, 0, $options['limit'] );

            return $categories;

        }







        /**
         *
         * Method to check Moodle version
         *
         */
        public static function moodle_from( $version) {
            global $CFG;

            if ($CFG->version >= $version) {
                return true;
            }

            return false;

        }


        /**
         *
         * Method to get image
         *
         */
        public static function get_image( $attribs = [], $name = false, $desc = '', $cid = 0) {

            global $CFG;
            require_once( $CFG->libdir . '/filelib.php' );

            $output = '';
            $desc_img = true;

            if (! empty( $attribs )) {
                $files = get_file_storage()->get_area_files( $attribs['context'], $attribs['mod'], $attribs['area'], $attribs['itemid'] );
            }

            // Get image from course summary files
            if ($cid) {
                if (self::moodle_from( 2018120300 )) {
                    $courseObj = new core_course_list_element( get_course( $cid ) );
                }
                else
                {
                    $courseObj = new course_in_list( get_course( $cid ) );
                }

                $files = $courseObj->get_course_overviewfiles();
            }

            if ($desc) {
                $urlfromdesc = self::get_image_from_text( s( $desc ),true );
                $namefromdesc = basename( $urlfromdesc );
            }

            foreach ($files as $file) {

                if ($desc) {
                    $desc_img = ( $namefromdesc === $file->get_filename() );
                }

                $isimage = ( $file->is_valid_image() && $desc_img );

                if ($isimage) {
                    if ($name) {
                        return $file->get_filename();
                    }

                    $item_id = null;

                    if (isset( $attribs['itemid'] ) && $attribs['itemid']) {
                        $item_id = $file->get_itemid();
                    }

                    return moodle_url::make_pluginfile_url( $file->get_contextid(), $file->get_component(), $file->get_filearea(),
                    $item_id, $file->get_filepath(), $file->get_filename() );
                }
            }

        }




        /**
         *
         * Method to get image from text
         *
         */
        public static function get_image_from_text( $text) {

            $output = '';

            $matches = [];
            $str = '@@PLUGINFILE@@/';

            $isplug = preg_match( '|' . $str . '|', $text );

            if ($isplug) {
                preg_match_all( '!@@PLUGINFILE@@/[^?#]+\.(?:jpe?g|png|gif)!Ui', $text, $matches );
            } else {
                preg_match_all( '!http://[^?#]+\.(?:jpe?g|png|gif)!Ui', $text, $matches );
            }

            foreach ($matches as $el) {
                $output = isset( $el[0] ) ? $isplug ? str_replace( $str, '', $el[0] ) : $el[0] : '';
            }

            return $output;

        }








        /**
         *
         * Method to get image from text
         *
         */
        public static function get_currency( $currency) {

            $output = '';
            $is_c = '';

            // Get currency symbol
            $currencyarr = explode( ':', $currency );

            $output .= '<span class="currency">';

            if (preg_match( '#\\,#', $currencyarr[1] )) {

                $curr = explode( ',', $currencyarr[1] );

                foreach ($curr as $c) {
                    $output .= '&#x' . $c;
                }
            } else {
                $output .= '&#x' . $currencyarr[1];
            }

            $output .= '</span>';

            return $output;

        }





        /**
         *
         * Method to get content options
         *
         */
        public static function get_options( $options, $urloptions) {
            $opts = [];

            foreach ($options as $k => $v) {
                $v = $v;

                if (isset( $urloptions[$k] )) {
                    $v = $urloptions[$k];
                }

                $opts[$k] = $v;
            }

            return $opts;

        }





        /**
         *
         * Method to get video embed url
         *
         */
        public static function get_videoweb_url( $url = '', $default = '') {

            if (! $url) {
                $url = $default;
            }

            $videoid = self::get_video_id( $url );
            $urlaparat = '//aparat.com/video/video/embed/videohash/' . $videoid . '/vt/frame';
            $urlvimeo = '//player.vimeo.com/video/' . $videoid;
            $urlyoutube = '//youtube.com/embed/' . $videoid;

            // Support old shortcode feature
            // this means that user insert video ID instead video url
            if (! filter_var( $url, FILTER_VALIDATE_URL )) {
                if( preg_match( '/^[0-9]+$/', $url )) {
                    return $urlvimeo;
                }
                else
                {
                    return $urlyoutube;
                }
            }

            // User use video url
            if (preg_match( '@aparat.com@', $url )) {
                return '//aparat.com/video/video/embed/videohash/' . $videoid . '/vt/frame';
            } else if (preg_match( '@vimeo.com@', $url )) {
                return '//player.vimeo.com/video/' . $videoid;
            } else if (preg_match( '@youtube.com@', $url ) || preg_match( '@youtu.be@', $url )) {
                return '//youtube.com/embed/' . $videoid;
            }

            return null;

        }







        /**
         *
         * Method to get video id from video url
         *
         */
        public static function get_video_id( $url, $list = false) {

            $parts = parse_url($url);

            if (isset( $parts['query'] )) {

                parse_str($parts['query'], $qs);

                if ($list && isset( $qs['list'] )) {
                    return $qs['list'];
                }

                if (isset( $qs['v'] )) {
                    return $qs['v'];
                }
                else if (isset( $qs['vi'] )) {
                    return $qs['vi'];
                }

            }

            if (! $list && isset( $parts['path'] )) {
                $path = explode('/', trim( $parts['path'], '/') );
                return $path[count($path)-1];
            }

            return false;

        }



        /**
         *
         * Method to get image url from course 'overviewfiles' file area
         *
         */
        public static function course_image_url( $courseid, $placeholder = false) {
            global $CFG, $COURSE, $OUTPUT;

            if (! $courseid) {
                return;
            }

            require_once( $CFG->libdir . '/filelib.php' );

            $url = $placeholder ? $OUTPUT->image_url('course-default','theme') : '';
            $context = context_course::instance( $courseid );
            $fs = get_file_storage();
            $files = $fs->get_area_files( $context->id, 'course', 'overviewfiles', 0 );

            foreach ($files as $f) {
                if ($f->is_valid_image()) {
                    $url = moodle_url::make_pluginfile_url(
                        $f->get_contextid(), $f->get_component(), $f->get_filearea(), null, $f->get_filepath(), $f->get_filename(), false );
                }
            }

            return $url;

        }

    }


}
