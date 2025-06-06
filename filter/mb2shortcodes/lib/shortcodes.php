<?php

defined('MOODLE_INTERNAL') || die();

/**
 * WordPress API for creating bbcode like tags or what WordPress calls
 * "shortcodes." The tag and attribute parsing or regular expression code is
 * based on the Textpattern tag parser.
 *
 * A few examples are below:
 *
 * [shortcode /]
 * [shortcode foo="bar" baz="bing" /]
 * [shortcode foo="bar"]content[/shortcode]
 *
 * Shortcode tags support attributes and enclosed content, but does not entirely
 * support inline shortcodes in other shortcodes. You will have to call the
 * shortcode parser in your function to account for that.
 *
 * {@internal
 * Please be aware that the above note was made during the beta of WordPress 2.6
 * and in the future may not be accurate. Please update the note when it is no
 * longer the case.}}
 *
 * To apply shortcode tags to content:
 *
 * <code>
 * $out = mb2_do_shortcode($content);
 * </code>
 *
 * @link http://codex.wordpress.org/Shortcode_API
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @since 2.5
 */



/**
 * Container for storing shortcode tags and their hook to call for the shortcode
 *
 * @since 2.5
 * @name $shortcode_tags
 * @var array
 * @global array $shortcode_tags
 */
$shortcode_tags = array();

/**
 * Add hook for shortcode tag.
 *
 * There can only be one hook for each shortcode. Which means that if another
 * plugin has a similar shortcode, it will override yours or yours will override
 * theirs depending on which order the plugins are included and/or ran.
 *
 * Simplest example of a shortcode tag using the API:
 *
 * <code>
 * // [footag foo="bar"]
 * function footag_func($atts) {
 * 	return "foo = {$atts[foo]}";
 * }
 * mb2_add_shortcode('footag', 'footag_func');
 * </code>
 *
 * Example with nice attribute defaults:
 *
 * <code>
 * // [bartag foo="bar"]
 * function bartag_func($atts) {
 * 	extract(mb2_shortcode_atts(array(
 * 		'foo' => 'no foo',
 * 		'baz' => 'default baz',
 * 	), $atts));
 *
 * 	return "foo = {$foo}";
 * }
 * mb2_add_shortcode('bartag', 'bartag_func');
 * </code>
 *
 * Example with enclosed content:
 *
 * <code>
 * // [baztag]content[/baztag]
 * function baztag_func($atts, $content='') {
 * 	return "content = $content";
 * }
 * mb2_add_shortcode('baztag', 'baztag_func');
 * </code>
 *
 * @since 2.5
 * @uses $shortcode_tags
 *
 * @param string $tag Shortcode tag to be searched in post content.
 * @param callable $func Hook to run when shortcode is found.
 */
function mb2_add_shortcode($tag, $func) {
    global $shortcode_tags;

    if ( is_callable($func) )
        $shortcode_tags[$tag] = $func;
}

/**
 * Removes hook for shortcode.
 *
 * @since 2.5
 * @uses $shortcode_tags
 *
 * @param string $tag shortcode tag to remove hook for.
 */
function mb2_remove_shortcode($tag) {
    global $shortcode_tags;

    unset($shortcode_tags[$tag]);
}

/**
 * Clear all shortcodes.
 *
 * This function is simple, it clears all of the shortcode tags by replacing the
 * shortcodes global by a empty array. This is actually a very efficient method
 * for removing all shortcodes.
 *
 * @since 2.5
 * @uses $shortcode_tags
 */
function mb2_remove_all_shortcodes() {
    global $shortcode_tags;

    $shortcode_tags = array();
}

/**
 * Search content for shortcodes and filter shortcodes through their hooks.
 *
 * If there are no shortcode tags defined, then the content will be returned
 * without any filtering. This might cause issues when plugins are disabled but
 * the shortcode will still show up in the post or content.
 *
 * @since 2.5
 * @uses $shortcode_tags
 * @uses mb2_get_shortcode_regex() Gets the search pattern for searching shortcodes.
 *
 * @param string $content Content to search for shortcodes
 * @return string Content with shortcodes filtered out.
 */
function mb2_do_shortcode($content) {
    global $shortcode_tags;

    if (empty($shortcode_tags) || !is_array($shortcode_tags))
        return $content;

    $pattern = mb2_get_shortcode_regex();
    return preg_replace_callback( "/$pattern/s", 'mb2_do_shortcode_tag', $content );
}

/**
 * Retrieve the shortcode regular expression for searching.
 *
 * The regular expression combines the shortcode tags in the regular expression
 * in a regex class.
 *
 * The regular expression contains 6 different sub matches to help with parsing.
 *
 * 1 - An extra [ to allow for escaping shortcodes with double [[]]
 * 2 - The shortcode name
 * 3 - The shortcode argument list
 * 4 - The self closing /
 * 5 - The content of a shortcode when it wraps some content.
 * 6 - An extra ] to allow for escaping shortcodes with double [[]]
 *
 * @since 2.5
 * @uses $shortcode_tags
 *
 * @return string The shortcode search regular expression
 */
function mb2_get_shortcode_regex() {
    global $shortcode_tags;
    $tagnames = array_keys($shortcode_tags);
    $tagregexp = join( '|', array_map('preg_quote', $tagnames) );

    // WARNING! Do not change this regex without changing mb2_do_shortcode_tag() and mb2_strip_shortcode_tag()
    // Also, see shortcode_unautop() and shortcode.js.
    return
          '\\['                              // Opening bracket
        . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
        . "($tagregexp)"                     // 2: Shortcode name
        . '(?![\\w-])'                       // Not followed by word character or hyphen
        . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
        .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
        .     '(?:'
        .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
        .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
        .     ')*?'
        . ')'
        . '(?:'
        .     '(\\/)'                        // 4: Self closing tag ...
        .     '\\]'                          // ... and closing bracket
        . '|'
        .     '\\]'                          // Closing bracket
        .     '(?:'
        .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
        .             '[^\\[]*+'             // Not an opening bracket
        .             '(?:'
        .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
        .                 '[^\\[]*+'         // Not an opening bracket
        .             ')*+'
        .         ')'
        .         '\\[\\/\\2\\]'             // Closing shortcode tag
        .     ')?'
        . ')'
        . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
}

/**
 * Regular Expression callable for mb2_do_shortcode() for calling shortcode hook.
 * @see mb2_get_shortcode_regex for details of the match array contents.
 *
 * @since 2.5
 * @access private
 * @uses $shortcode_tags
 *
 * @param array $m Regular expression match array
 * @return mixed False on failure.
 */
function mb2_do_shortcode_tag( $m ) {
    global $shortcode_tags;

    // allow [[foo]] syntax for escaping a tag
    if ( $m[1] == '[' && $m[6] == ']' ) {
        return substr($m[0], 1, -1);
    }

    $tag = $m[2];
    $attr = shortcode_parse_atts( $m[3] );

    if ( isset( $m[5] ) ) {
        // enclosing tag - extra parameter
        return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, $m[5], $tag ) . $m[6];
    } else {
        // self-closing tag
        return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, null,  $tag ) . $m[6];
    }
}




/**
 * Retrieve the shortcode attributes regex.
 *
 * @since 4.4.0
 *
 * @return string The shortcode attribute regular expression
 */
function get_shortcode_atts_regex() {
    return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
}





/**
 * Retrieve all attributes from the shortcodes tag.
 *
 * The attributes list has the attribute name as the key and the value of the
 * attribute as the value in the key/value pair. This allows for easier
 * retrieval of the attributes, since all attributes have to be known.
 *
 * @since 2.5
 *
 * @param string $text
 * @return array List of attributes and their value.
 */
function shortcode_parse_atts($text) {
    $atts    = array();
    $pattern = get_shortcode_atts_regex();
    $text    = preg_replace( "/[\x{00a0}\x{200b}]+/u", ' ', $text );
    if ( preg_match_all( $pattern, $text, $match, PREG_SET_ORDER ) ) {
        foreach ( $match as $m ) {
            if ( ! empty( $m[1] ) ) {
                $atts[ strtolower( $m[1] ) ] = stripcslashes( $m[2] );
            } elseif ( ! empty( $m[3] ) ) {
                $atts[ strtolower( $m[3] ) ] = stripcslashes( $m[4] );
            } elseif ( ! empty( $m[5] ) ) {
                $atts[ strtolower( $m[5] ) ] = stripcslashes( $m[6] );
            } elseif ( isset( $m[7] ) && strlen( $m[7] ) ) {
                $atts[] = stripcslashes( $m[7] );
            } elseif ( isset( $m[8] ) && strlen( $m[8] ) ) {
                $atts[] = stripcslashes( $m[8] );
            } elseif ( isset( $m[9] ) ) {
                $atts[] = stripcslashes( $m[9] );
            }
        }

        // Reject any unclosed HTML elements.
        foreach ( $atts as &$value ) {
            if ( false !== strpos( $value, '<' ) ) {
                if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                    $value = '';
                }
            }
        }
    } else {
        $atts = ltrim( $text );
    }

    return $atts;
}

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 * @since 2.5
 *
 * @param array $pairs Entire list of supported attributes and their defaults.
 * @param array $atts User defined attributes in shortcode tag.
 * @return array Combined and filtered attribute list.
 */
function mb2_shortcode_atts($pairs, $atts) {
    $atts = (array)$atts;
    $out = array();
    foreach($pairs as $name => $default) {
        if ( array_key_exists($name, $atts) )
            $out[$name] = $atts[$name];
        else
            $out[$name] = $default;
    }
    return $out;
}

/**
 * Remove all shortcode tags from the given content.
 *
 * @since 2.5
 * @uses $shortcode_tags
 *
 * @param string $content Content to remove shortcode tags.
 * @return string Content without shortcode tags.
 */
function mb2_strip_shortcodes( $content ) {
    global $shortcode_tags;

    if (empty($shortcode_tags) || !is_array($shortcode_tags))
        return $content;

    $pattern = mb2_get_shortcode_regex();

    return preg_replace_callback( "/$pattern/s", 'mb2_strip_shortcode_tag', $content );
}

function mb2_strip_shortcode_tag( $m ) {
    // allow [[foo]] syntax for escaping a tag
    if ( $m[1] == '[' && $m[6] == ']' ) {
        return substr($m[0], 1, -1);
    }

    return $m[1] . $m[6];
}

//add_filter('the_content', 'mb2_do_shortcode', 11); // AFTER wpautop()
