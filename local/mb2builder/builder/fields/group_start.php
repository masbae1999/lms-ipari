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
 * @package    local_mb2builder
 * @copyright  2018 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();


class LocalMb2builderGroup_start
{

	static function local_mb2builder_get_input($key, $attr)
	{
		$output = '';

		if (! isset( $attr['showon'] ) )
		{
			$attr['showon'] = '';
		}

		$showon = local_mb2builder_showon_field( $attr['showon'] );

		$output .= '<div class="mb2-pb-collapse-group form-group">';
		$output .= '<div class="mb2-pb-collapse-group-header"' . $showon . '>';
		$output .= '<button type="button">' . $attr['title'] . '</button>';
		$output .= '</div>'; //mb2-pb-collapse-group-header
		$output .= '<div class="mb2-pb-collapse-group-content">';
		$output .= '<div class="mb2-pb-collapse-group-content-inner">';

		return $output;

	}

}
