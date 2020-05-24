In some cases it can be interesting to get notified, if for a specific item a new comment was published on your website.
The following example shows how to get this working.

In this example you can see the implementation in module wgGallery


## 1) Adapt xoops_version.php
### First step: 
you have to check whether there is a callback function defined or not

        // Comment callback functions
        $modversion['comments']['callbackFile']        = 'include/comment_functions.php';
        $modversion['comments']['callback']['approve'] = 'wggalleryCommentsApprove';
        $modversion['comments']['callback']['update']  = 'wggalleryCommentsUpdate';

if not please as the definition to xoops_version.php

## Second step:
You have to add the notification options, which you want to have. In wgGallery we have the option to get a notification of a comment 
### a) in any case

        // Global Events Image commented
        $modversion['notification']['event'][] = [
            'name'          => 'img_comment_all',
            'category'      => 'global',
            'admin_only'    => 0,
            'title'         => _MI_WGGALLERY_GLOBAL_IMG_COMMENT_NOTIFY,
            'caption'       => _MI_WGGALLERY_GLOBAL_IMG_COMMENT_NOTIFY_CAPTION,
            'description'   => '',
            'mail_template' => 'global_img_comment_notify',
            'mail_subject'  => _MI_WGGALLERY_GLOBAL_IMG_COMMENT_NOTIFY_SUBJECT,
        ];
        
### b) or if an image of a specific album have been commented

        // Album Events Image commented
        $modversion['notification']['event'][] = [
            'name'          => 'image_comment',
            'category'      => 'albums',
            'admin_only'    => 0,
            'title'         => _MI_WGGALLERY_ALBUMS_IMG_COMMENT_NOTIFY,
            'caption'       => _MI_WGGALLERY_ALBUMS_IMG_COMMENT_NOTIFY_CAPTION,
            'description'   => '',
            'mail_template' => 'global_img_comment_notify',
            'mail_subject'  => _MI_WGGALLERY_ALBUMS_IMG_COMMENT_NOTIFY_SUBJECT,
        ];


## 2) Language files      
add the defines to your language file modinfo.php

        define('_MI_WGGALLERY_GLOBAL_IMG_COMMENT_NOTIFY', 'Notify me about new comments for images');
        define('_MI_WGGALLERY_GLOBAL_IMG_COMMENT_NOTIFY_CAPTION', 'Notify me about comments for images');
        define('_MI_WGGALLERY_GLOBAL_IMG_COMMENT_NOTIFY_SUBJECT', 'Notification about comments for an image');
        define('_MI_WGGALLERY_ALBUMS_IMG_COMMENT_NOTIFY', 'Notify me about new comments for images in this album');
        define('_MI_WGGALLERY_ALBUMS_IMG_COMMENT_NOTIFY_CAPTION', 'Notify me about comments for images in this album');
        define('_MI_WGGALLERY_ALBUMS_IMG_COMMENT_NOTIFY_SUBJECT', 'Notification about new comment for an image');


## 3) Adapt include/comment_functions.php
In any case a comment is sent and approved xoops with call the callback function wggalleryCommentsApprove($comment)
As a parameter you get the comment object with all necessary information
Therefore we need only to add the call of notification handler

        $helper = \XoopsModules\Wggallery\Helper::getInstance();
        // send notifications
        $imgId               = $comment->getVar('com_itemid');
        $imageObj            = $helper->getHandler('Images')->get($imgId);
        $albId               = $imageObj->getVar('img_albid');
        $tags                = [];
        $tags['IMAGE_NAME']  = $imageObj->getVar('img_name');
        $tags['IMAGE_URL']   = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/images.php?op=show&img_id=' . $imgId . '&amp;alb_id=' . $albId;
        $tags['ALBUM_URL']   = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/albums.php?op=show&alb_id=' . $albId;
        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('global', 0, 'img_comment_all', $tags, [], $comment->getVar('com_modid'));
        $notificationHandler->triggerEvent('albums', $albId, 'img_comment', $tags);

## 4) Add notification template to language file

Add a notification template to language/english/mail_template (or in your language file directory). 
Use the name you defined in xoops_version.php (in our example: 'mail_template' => 'global_img_comment_notify',)

Thats all :)
