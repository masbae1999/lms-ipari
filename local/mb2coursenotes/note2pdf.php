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
 * @package    local_mb2coursenotes
 * @copyright  2019 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( $CFG->libdir . '/adminlib.php' );
require_once( $CFG->libdir . '/pdflib.php');

// Optional parameters.
$user = optional_param('user', 0, PARAM_INT);
$course = optional_param('course', 0, PARAM_INT);
$returnurl = optional_param('returnurl', '/local/mb2coursenotes/note2pdf.php', PARAM_LOCALURL );

require_login();

// Link generation.
$urlparameters = ['user' => $user, 'course' => $course];
$baseurl = new moodle_url( '/local/mb2coursenotes/note2pdf.php', $urlparameters );

$context = context_system::instance();
$PAGE->set_url( $baseurl );
$PAGE->set_context( $context );

// Get notes to generate PDF file content.
$notes = Mb2coursenotesApi::get_list_records(0, 0, '*', '', '', $user, $course);
$courseobj = get_course($course);
$coursename = format_text( $courseobj->fullname, FORMAT_HTML );
$notecontent = '';
$userobj = core_user::get_user($user);
$file_name = Mb2coursenotesHelper::safe_file_name('Notes_' . $coursename);

// Set PDF file.
$pdf = new pdf();
$fontfamily = $pdf->getFontFamily();
$fontstyle = $pdf->getFontStyle();
$pdf->SetFont($fontfamily);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($userobj->username);
$pdf->SetTitle($file_name);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(20, 20, 20, 100);

// PDF header.
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$notecontent .= '<h3>';
$notecontent .= get_string('course') . ': ' . $coursename;
$notecontent .= '</h3>';

// PDF content.
foreach( $notes as $note ) {
    $notecontent .= '<h4>';
    $notecontent .= $note->module ? Mb2coursenotesHelper::get_module($courseobj, $note->module)->name : get_string('all');
    $notecontent .= '</h4>';
    $notecontent .= '<p>';
    $notecontent .= preg_replace('/\r\n|\n|\r/', '<br>', $note->content);
    $notecontent .= '</p>';
}

// Create PDF file.
$pdf->AddPage('P');
$pdf->writeHTML($notecontent, 0);
$pdf->Output($file_name . '.pdf','D');

// The page title.
$titlepage = get_string('note2pdfpagetitle','local_mb2coursenotes');
$PAGE->navbar->add($titlepage);
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
