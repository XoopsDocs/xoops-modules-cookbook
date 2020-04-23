<?php

namespace XoopsModules\Myexample;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module:  xSitemap
 *
 * @package      \module\xsitemap\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <owners@zyspec.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        File available since version 1.54
 */

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
     * www.cakephp.org
     *
     * @param string $text         String to truncate.
     * @param int    $length       Length of returned string, including ellipsis.
     * @param string $ending       Ending to be appended to the trimmed string.
     * @param bool   $exact        If false, $text will not be cut mid-word
     * @param bool   $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
    {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (mb_strlen(preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = mb_strlen($ending);
            $open_tags    = [];
            $truncate     = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags, true);
                        if (false !== $pos) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, mb_strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left            = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($left >= $entity[1] + 1 - $entities_length) {
                                $left--;
                                $entities_length += mb_strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= mb_substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                }
                $truncate     .= $line_matchings[2];
                $total_length += $content_length;

                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            }
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    //--------------- Custom module methods -----------------------------

    /**
     * add selected cats
     * @param $cats
     * @return string
     */
    public static function addBlockCatSelect($cats)
    {
        if (is_array($cats)) {
            $cat_sql = '(' . current($cats);
            array_shift($cats);
            foreach ($cats as $cat) {
                $cat_sql .= ',' . $cat;
            }
            $cat_sql .= ')';
        }

        return $cat_sql;
    }

    /**
     * Get the permissions ids
     * @param $permtype
     * @param $dirname
     * @return mixed $images
     */
    public static function getMyItemIds($permtype, $dirname)
    {
        global $xoopsUser;
        static $permissions = [];
        if (is_array($permissions) && array_key_exists($permtype, $permissions)) {
            return $permissions[$permtype];
        }
        $moduleHandler   = xoops_getHandler('module');
        $wggalleryModule = $moduleHandler->getByDirname($dirname);
        $groups          = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler    = xoops_getHandler('groupperm');
        $images          = $gpermHandler->getItemIds($permtype, $groups, $wggalleryModule->getVar('mid'));

        return $images;
    }

    /**
     * Get the number of images from the sub categories of a category or sub topics of or topic
     * @param $mytree
     * @param $images
     * @param $entries
     * @param $cid
     * @return int
     */
    public static function getNumbersOfEntries($mytree, $images, $entries, $cid)
    {
        $count = 0;
        if (in_array($cid, $images)) {
            $child = $mytree->getAllChild($cid);
            foreach (array_keys($entries) as $i) {
                if ($entries[$i]->getVar('img_id') == $cid) {
                    $count++;
                }
                foreach (array_keys($child) as $j) {
                    if ($entries[$i]->getVar('img_id') == $j) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Add content as meta tag to template
     * @param $content
     */
    public static function getMetaKeywords($content)
    {
        global $xoopsTpl, $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (null !== $xoTheme && is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'keywords', strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_meta_keywords', strip_tags($content));
        }
    }

    /**
     * Add content as meta description to template
     * @param $content
     */
    public static function getMetaDescription($content)
    {
        global $xoopsTpl, $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (null !== $xoTheme && is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'description', strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_meta_description', strip_tags($content));
        }
    }

    /**
     * Rewrite all url
     *
     * @param string $module module name
     * @param array  $array  array
     * @param string $type   type
     * @return null|string $type    string replacement for any blank case
     */
    public static function rewriteUrl($module, $array, $type = 'content')
    {
        $comment = '';
        /** @var \XoopsModules\Wgtimelines\Helper $helper */
        $helper = \XoopsModules\Wgtimelines\Helper::getInstance();
        //$images = $helper->getHandler('Images');
        $lenght_id   = $helper->getConfig('lenght_id');
        $rewrite_url = $helper->getConfig('rewrite_url');

        if (0 !== $lenght_id) {
            $id = $array['content_id'];
            while (mb_strlen($id) < $lenght_id) {
                $id = '0' . $id;
            }
        } else {
            $id = $array['content_id'];
        }

        if (isset($array['topic_alias']) && $array['topic_alias']) {
            $topic_name = $array['topic_alias'];
        } else {
            $topic_name = static::getFilter(xoops_getModuleOption('static_name', $module));
        }

        switch ($rewrite_url) {
            case 'none':
                if ($topic_name) {
                    $topic_name = 'topic=' . $topic_name . '&amp;';
                }
                $rewrite_base = '/modules/';
                $page         = 'page=' . $array['content_alias'];

                return XOOPS_URL . $rewrite_base . $module . '/' . $type . '.php?' . $topic_name . 'id=' . $id . '&amp;' . $page . $comment;
                break;
            case 'rewrite':
                if ($topic_name) {
                    $topic_name .= '/';
                }
                $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
                $rewrite_ext  = xoops_getModuleOption('rewrite_ext', $module);
                $module_name  = '';
                if (xoops_getModuleOption('rewrite_name', $module)) {
                    $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
                }
                $page = $array['content_alias'];
                $type .= '/';
                $id   .= '/';
                if ('content/' === $type) {
                    $type = '';
                }
                if ('comment-edit/' === $type || 'comment-reply/' === $type || 'comment-delete/' === $type) {
                    return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
                }

                return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $id . $page . $rewrite_ext;
                break;
            case 'short':
                if ($topic_name) {
                    $topic_name .= '/';
                }
                $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
                $rewrite_ext  = xoops_getModuleOption('rewrite_ext', $module);
                $module_name  = '';
                if (xoops_getModuleOption('rewrite_name', $module)) {
                    $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
                }
                $page = $array['content_alias'];
                $type .= '/';
                if ('content/' === $type) {
                    $type = '';
                }
                if ('comment-edit/' === $type || 'comment-reply/' === $type || 'comment-delete/' === $type) {
                    return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
                }

                return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $page . $rewrite_ext;
                break;
        }

        return null;
    }

    /**
     * Replace all escape, character, ... for display a correct url
     *
     * @param string $url  string to transform
     * @param string $type string replacement for any blank case
     * @param string $module
     * @return string $url
     */
    public static function getFilter($url, $type = '', $module = 'wggallery')
    {
        // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
        $helper = \XoopsModules\Wgtimelines\Helper::getInstance();
        //$images = $helper->getHandler('Images');
        $regular_expression = $helper->getConfig('regular_expression');

        $url = strip_tags($url);
        $url = preg_replace("`\[.*\]`U", '', $url);
        $url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $url);
        $url = htmlentities($url, ENT_COMPAT, 'utf-8');
        $url = preg_replace('`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', "\1", $url);
        $url = preg_replace([$regular_expression, '`[-]+`'], '-', $url);
        $url = ('' == $url) ? $type : mb_strtolower(trim($url, '-'));

        return $url;
    }
}
