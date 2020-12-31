In this tutorial I will explain, how the comment system of XOOPS is working and how you can implement or adapt it.

Pay attention:
You do not get notifications about event caused by you. For testing purposes you need minimum two users (one who has subscribed to the event, one who is executing the event).

In this explaination the name of the module is "myexample", we have a table "articles", and the goal is to get notifications concerning events related to this table.

## 1) xoops_version.php
you have to declare the callback functions, which should be used by your comment system

        // ------------------- Comments ------------------- //
        $modversion['hasComments'] = 1;
        $modversion['comments']['pageName'] = 'article.php';
        $modversion['comments']['itemName'] = 'art_id';
        // Comment callback functions
        $modversion['comments']['callbackFile'] = 'include/comment_functions.php';
        $modversion['comments']['callback'] = [
        	'approve' => 'myexampleCommentsApprove',
        	'update'  => 'myexampleCommentsUpdate',
        ];

### Explaination:
'hasComments' defines in general, that this module is using comment system.

With 'pageName' you define, from which pages you can send a comment.

With 'itemName' you define the linked item id for the comment.

In our example the article id from table articles will be linked with the comment and you can send a comment from articles.php

if not existing please add the definition to xoops_version.php

## 3) File include/comment_functions.php

As in xoops_version.php you have to add the file where the lookup functions are defined.

Each time a comment event is happening this function will be called.

In our example we have two functions:

### Update function 'myexampleCommentsUpdate'

This function will be called in each update event. You can add here e.g. the code to update your comments counter in your table

        function myexampleCommentsUpdate($itemId, $itemNumb)
        {
            // Get instance of module
            $helper = \XoopsModules\Myexample\Helper::getInstance();
            $articlesHandler = $helper->getHandler('Articles');
            $artId = (int)$itemId;
            $articlesObj = $articlesHandler->get($artId);
            $articlesObj->setVar('art_comments', (int)$itemNumb);
            if ($testfieldsHandler->insert($articlesObj)) {
                return true;
            }
            return false;
        }

### Approve function 'myexampleCommentsApprove'

This function will be called in each time a comment is approved. You can add here e.g. the code to notify users about this comment.
As a parameter you get the comment object with all necessary information
        
        function myexampleCommentsApprove(&$comment)
        {
            // Notification event
            // Get instance of module
            $helper = \XoopsModules\Myexample\Helper::getInstance();
            $articlesHandler = $helper->getHandler('Testfields');
            $artId = $comment->getVar('com_itemid');
            $articlesObj = $articlesHandler->get($artId);
            $artTitle = $articlesObj->getVar('art_title');
        
            $tags = [];
            $tags['ITEM_NAME'] = $artTitle;
            $tags['ITEM_URL']  = XOOPS_URL . '/modules/myexample/articles.php?op=show&art_id=' . $artId;
            $notificationHandler = xoops_getHandler('notification');
            // Event modify notification
            $notificationHandler->triggerEvent('global', 0, 'global_comment', $tags);
            $notificationHandler->triggerEvent('articles', $artId, 'articles_comment', $tags);
            return true;
        
        }

## 4) Add comment includes
Your module needs following files, which define what happens in the different comment events:

  * comment_edit.php
  * comment_delete.php
  * comment_post.php
  * comment_reply.php
  
These files have to contain the include to the corresponding comment file of xoops core system, 
e.g. comment_edit.php contains

        include_once dirname(dirname(__DIR__)) . '/mainfile.php';
        include_once XOOPS_ROOT_PATH.'/include/comment_edit.php';

## 5) Add caller for comment system

Finally we have to add the caller from xoops core in each file where we want to have the comments.

E.g. in our example we add this code in the articles.php: 

        require_once XOOPS_ROOT_PATH . '/include/comment_view.php';





Thats all :)
