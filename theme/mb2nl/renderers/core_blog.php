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
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */


/**
 * Blog renderer
 */
class theme_mb2nl_core_blog_renderer extends plugin_renderer_base {

    /**
     * Renders a blog entry
     *
     * @param blog_entry $entry
     * @return string The table HTML
     */
    public function render_blog_entry(blog_entry $entry) {

        global $CFG, $gl0bmb2blogpost;
        $gl0bmb2blogpost++;

        $o = '';
        $blogpage  = optional_param('blogpage', 0, PARAM_INT);
        $itemsperpage = theme_mb2nl_blog_itemsperpage();
        $syscontext = context_system::instance();
        $single = theme_mb2nl_is_blogsingle();
        $loadmore = theme_mb2nl_theme_setting($this->page, 'blogmore');
        $bloglayout = theme_mb2nl_theme_setting($this->page, 'bloglayout');
        $grid = $bloglayout === 'col2' || $bloglayout === 'col3';
        $introtext = theme_mb2nl_hrintro($entry->summary, true);
        $iscontent = ! $single && $introtext && theme_mb2nl_theme_setting($this->page, 'blogpageintro');
        $entryurl = new moodle_url('/blog/index.php', ['entryid' => $entry->id]);
        $singletext = theme_mb2nl_hrfulltext($entry->summary, false);
        $gridcls = ! $single ? ' blog-' . $bloglayout : '';

        $stredit = get_string('edit');
        $strdelete = get_string('delete');

        // Header.
        $mainclass = 'post blog-post post-' . $entry->id;
        $mainclass .= theme_mb2nl_is_videopost($entry) ? ' post-video' : '';
        $mainclass .= ! theme_mb2nl_is_videopost($entry) && theme_mb2nl_is_image(theme_mb2nl_post_attachements($entry->id)[0]) ?
        ' post-image' : '';

        if ($entry->renderable->unassociatedentry) {
            $mainclass .= ' draft';
        } else {
            $mainclass .= ' ' . $entry->publishstate;
        }

        $o .= ! $single && $gl0bmb2blogpost == 1 ? '<!-- START BLOG CONTAINER --><div class="theme-blog-container'. $gridcls .'">' :
        ''; // Start blog posts container.

        $o .= $this->output->container_start($mainclass, 'b' . $entry->id);

        if ($entry->renderable->usercanedit) {
            $o .= $this->output->container_start('commands');

            // External blog entries should not be edited.
            if (empty($entry->uniquehash)) {
                $o .= html_writer::link(new moodle_url('/blog/edit.php', ['action' => 'edit', 'entryid' => $entry->id]),
                $stredit) . ' | ';
            }
            $o .= html_writer::link(new moodle_url('/blog/edit.php', ['action' => 'delete', 'entryid' => $entry->id]), $strdelete);

            $o .= $this->output->container_end(); // ...commands
        }

        // Post title.
        $o .= $single ? $this->render_blog_entry_header($entry) : '';

        $o .= $single ? '<div class="post-intro">' . theme_mb2nl_format_txt($introtext, $entry->summaryformat, []) . '</div>' :
        '';

        $mediaonsingle = $single && theme_mb2nl_theme_setting($this->page, 'blogfeaturedmedia');

        if (! $single || $mediaonsingle || $entry->renderable->attachments) {

            $o .= $this->output->container_start('post-media');

            if (!$single || $mediaonsingle) {

                $blogplaceholder = theme_mb2nl_theme_setting($this->page, 'blogplaceholder', '', true);
                $postimgurl = $blogplaceholder ? $blogplaceholder : $this->output->image_url('blog-default', 'theme');
                $featuredmedia = theme_mb2nl_blog_featuredmedia($entry, false, true);

                $o .= $this->output->container_start('post-featured-media');

                if ($featuredmedia) {
                    $o .= $featuredmedia;
                } else {
                    $o .= ! $single ? '<a href="' . $entryurl . '" class="postlink">' : '';
                    $o .= '<img class="lazy" src="' . theme_mb2nl_lazy_plc() . '" data-src="' . $postimgurl . '" alt="' .
                    $entry->subject . '">';
                    $o .= ! $single ? '</a>' : '';
                }

                $o .= $this->output->container_end(); // ...post-featured-media
            } else {
                $attachmentsoutputs = [];
                if ($entry->renderable->attachments) {
                    foreach ($entry->renderable->attachments as $attachment) {
                        $o .= $this->render($attachment, false);
                    }
                }
            }

            $o .= $this->output->container_end(); // ...post-media
        }

        $o .= $single ? $this->render_blog_entry_meta($entry) : '';

        $o .= ! $single ? $this->render_blog_entry_header($entry) : '';

        // Post content.
        $o .= $this->output->container_start('post-content');

        // Entry text.
        if ($iscontent) {
            $o .= $this->output->container_start('post-text');
            $o .= theme_mb2nl_format_txt($introtext, $entry->summaryformat, []);
            $o .= $this->output->container_end(); // ...post-text
        } else if ($single) {
            $o .= $this->output->container_start('post-text');
            $o .= theme_mb2nl_format_txt($singletext, $entry->summaryformat, []);

            // Add associations.
            if (!empty($CFG->useblogassociations) && !empty($entry->renderable->blogassociations)) {

                // First find and show the associated course.
                $assocstr = '';
                $coursesarray = [];
                foreach ($entry->renderable->blogassociations as $assocrec) {
                    if ($assocrec->contextlevel == CONTEXT_COURSE) {
                        $coursesarray[] = $this->output->action_icon($assocrec->url, $assocrec->icon, null, [], true);
                    }
                }
                if (!empty($coursesarray)) {
                    $assocstr .= get_string('associated', 'blog', get_string('course')) . ': ' . implode(',', $coursesarray);
                }

                // Now show mod association.
                $modulesarray = [];
                foreach ($entry->renderable->blogassociations as $assocrec) {
                    if ($assocrec->contextlevel == CONTEXT_MODULE) {
                        $str = get_string('associated', 'blog', $assocrec->type) . ': ';
                        $str .= $this->output->action_icon($assocrec->url, $assocrec->icon, null, [], true);
                        $modulesarray[] = $str;
                    }
                }
                if (!empty($modulesarray)) {
                    if (!empty($coursesarray)) {
                        $assocstr .= '<br/>';
                    }
                    $assocstr .= implode('<br/>', $modulesarray);
                }

                // Adding the asociations to the output.
                $o .= $this->output->container($assocstr, 'tags');
            }

            if ($entry->renderable->unassociatedentry) {
                $o .= $this->output->container(get_string('associationunviewable', 'blog'), 'noticebox');
            }

            if ($single && ! empty($entry->uniquehash)) {
                // Uniquehash is used as a link to an external blog.
                $url = clean_param($entry->uniquehash, PARAM_URL);
                if (!empty($url)) {
                    $o .= $this->output->container_start('externalblog');
                    $o .= html_writer::link($url, get_string('linktooriginalentry', 'blog'));
                    $o .= $this->output->container_end(); // ...externalblog
                }
            }

            $o .= $this->output->container_end(); // ...post-text
        }

        $taglist = $this->output->tag_list(core_tag_tag::get_item_tags('core', 'post', $entry->id));
        $taglistcls = $taglist ? ' istags' : ' notags';

        $o .= $single ? $this->output->container_start('post-content-bottom' . $taglistcls) : '';

        // List of tags.
        $o .= $single ? $taglist : '';

        // Last modified date.
        if ($single && $entry->created != $entry->lastmodified && theme_mb2nl_theme_setting($this->page, 'blogmodify')) {
            $o .= $this->output->container(get_string('modified') . ': ' .
            date(theme_mb2nl_theme_setting($this->page, 'blogsingledateformat'), $entry->lastmodified), 'modify');
        }

        $o .= $single ? $this->output->container_end() : ''; // ...post-content-bottom

        $o .= $this->output->container_start('post-footer');

        // Read more link.
        if ($iscontent) {
            $o .= $this->output->container_start('readmore');
            $o .= html_writer::link($entryurl, get_string('continuereading', 'theme_mb2nl'), ['class' =>
            'mb2-pb-btn typelink fwbold']);
            $o .= $this->output->container_end(); // ...readmore
        }

        // Share icons.
        $o .= $single && theme_mb2nl_theme_setting($this->page, 'blogshareicons') ?
        theme_mb2nl_course_share_list($entry->id, theme_mb2nl_format_str($entry->subject), true) : '';

        // Comments.
        if ($single && ! empty($entry->renderable->comment) && (theme_mb2nl_post_comment_count($entry->id) ||
        (isloggedin() && ! isguestuser()))) {
            $o .= $entry->renderable->comment->output(true);
        }

        $o .= $this->output->container_end(); // ...post-footer
        $o .= $this->output->container_end(); // ...post-content

        $o .= $this->output->container_end();

        if (!$single && $gl0bmb2blogpost == $itemsperpage) {
            $o .= '</div><!-- END BLOG CONTAINER -->';

            if ($loadmore && $blogpage == 0 && theme_mb2nl_blog_pagesnum() > 1) {
                $o .= '<div class="theme-blog-load-posts">';
                $o .= '<button data-url="' . $this->page->url . '" type="button" data-page="1" data-pages="' .
                theme_mb2nl_blog_pagesnum() . '" class="blog-more-post mb2-pb-btn typeinverse sizelg" data-strload="' .
                get_string('loadmore', 'theme_mb2nl') . '" data-strloading="' . get_string('loading', 'theme_mb2nl') . '"
                aria-hidden="true">';
                $o .= get_string('loadmore', 'theme_mb2nl');
                $o .= '</button>';
                $o .= '</div>';
            }

        }

        return $o;
    }


    /**
     * Renders an entry attachment
     *
     * Print link for non-images and returns images as HTML
     *
     * @param blog_entry_attachment $attachment
     * @return string List of attachments depending on the $return input
     */
    public function render_blog_entry_attachment(blog_entry_attachment $attachment) {

        $syscontext = context_system::instance();

        // Image attachments don't get printed as links.
        if (file_mimetype_in_typegroup($attachment->file->get_mimetype(), 'web_image')) {
            $attrs = ['src' => $attachment->url, 'alt' => ''];
            $o = html_writer::empty_tag('img', $attrs);
            $class = 'attachedimages';
        } else {
            $image = $this->output->pix_icon(file_file_icon($attachment->file), $attachment->filename, 'moodle', ['class' =>
            'icon']);
            $o = html_writer::link($attachment->url, $image);
            $o .= theme_mb2nl_format_txt(html_writer::link($attachment->url, $attachment->filename), FORMAT_HTML, ['context' =>
            $syscontext]);
            $class = 'attachments';
        }

        return $this->output->container($o, $class);
    }



    /**
     *
     * Method to render blog post header.
     *
     *
     */
    public function render_blog_entry_header($entry) {

        $o = '';
        $single = theme_mb2nl_is_blogsingle();
        $syscontext = context_system::instance();

        $o .= $this->output->container_start('post-header');

        if ($entry->renderable->usercanedit) {
            // Determine text for publish state.
            switch ($entry->publishstate) {
                case 'draft':
                    $blogtype = get_string('publishtonoone', 'blog');
                    break;
                case 'site':
                    $blogtype = get_string('publishtosite', 'blog');
                    break;
                case 'public':
                    $blogtype = get_string('publishtoworld', 'blog');
                    break;
                default:
                    $blogtype = '';
                    break;
            }

            $o .= $this->output->container($blogtype, 'audience sr-only');
        }

        $o .= ! $single ? $this->render_blog_entry_meta($entry) : '';

        if ($single) {
            $o .= html_writer::tag('div', theme_mb2nl_format_str($entry->subject), ['class' => 'subject h2']);
        } else {
            $titlelink = html_writer::link(new moodle_url('/blog/index.php', ['entryid' => $entry->id]),
            theme_mb2nl_format_str($entry->subject));
            $o .= html_writer::tag('h3', $titlelink, ['class' => 'subject']);
        }

        $o .= $this->output->container_end(); // ...post-header

        return $o;

    }


    /**
     *
     * Method to render blog post meta.
     *
     *
     */
    public function render_blog_entry_meta($entry) {

        $o = '';
        $syscontext = context_system::instance();
        $single = theme_mb2nl_is_blogsingle();

        $o .= $this->output->container_start('post-meta');

        if ($single) {
            $o .= $this->output->container_start('post-author');
            $o .= $this->output->container_start('author-image');
            $o .= $this->output->user_picture($entry->renderable->user, ['size' => 100, 'link' => 0]);
            $o .= $this->output->container_end(); // ...author-image
            $o .= $this->output->container_start('author-name');
            $o .= $entry->renderable->user->firstname . ' ' . $entry->renderable->user->lastname;
            $o .= $this->output->container_end(); // ...author-name
            $o .= $this->output->container_end(); // ...post-author
            $o .= $this->output->container_start('post-date');
            $o .= date(theme_mb2nl_theme_setting($this->page, 'blogsingledateformat'), $entry->created);
            $o .= $this->output->container_end(); // ...post-author
        } else {
            $o .= $this->output->container_start('post-date');
            $o .= date(theme_mb2nl_theme_setting($this->page, 'blogdateformat'), $entry->created);
            $o .= $this->output->container_end(); // ...post-date
        }

        // Adding external blog link.
        if ($single && ! empty($entry->renderable->externalblogtext)) {
            $o .= $this->output->container($entry->renderable->externalblogtext, 'externalblog');
        }

        $o .= $this->output->container_end(); // ...post-meta

        return $o;

    }
}
