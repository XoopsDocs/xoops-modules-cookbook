In this tutorial I will explain, how the notification system of XOOPS is working and how you can implement or adapt it.

Pay attention:
You do not get notifications about event caused by you. For testing purposes you need minimum two users (one who has subscribed to the event, one who is executing the event).

In this explaination the name of the module is "myexample", we have a table "articles", and the goal is to get notifications concerning events related to this table.

## 1) xoops_version.php

### First part: 
you have to declare the callback functions, which should be used by your notification system

        // ------------------- Notifications ------------------- //
        $modversion['hasNotification'] = 1;
        $modversion['notification'] = [
        	'lookup_file' => 'include/notification.inc.php',
        	'lookup_func' => 'myexample_notify_iteminfo',
        ];

if not existing please add the definition to xoops_version.php

### Second part: 
you have to declare the categories, which should be used by your notification system

        // Global Notify
        $modversion['notification']['category'][] = [
        	'name'           => 'global',
        	'title'          => _MI_MYEXAMPLE_GLOBAL_NOTIFY,
        	'description'    => _MI_MYEXAMPLE_GLOBAL_NOTIFY_DESC,
        	'subscribe_from' => ['index.php', 'articles.php'],
        ];
        // Article Notify
        $modversion['notification']['category'][] = [
        	'name'           => 'articles',
        	'title'          => _MI_MYEXAMPLE_ARTICLE_NOTIFY,
        	'description'    => _MI_MYEXAMPLE_ARTICLE_NOTIFY_DESC,
        	'subscribe_from' => 'articles.php',
        	'item_name'      => 'art_id',
        	'allow_bookmark' => 1,
        ];

Each event must be linked to a category.

if not please add the definition to xoops_version.php

#### Explaination:
The name of category must be unique.
The title and description should be unique and will be used on user side for the notification select block.
"subscribe_from" defines the files, from where it can be selected (e.g. the global events will be selectable in 'index.php' and 'articles.php').
"item_name" defines, whether a specific item must be available. 
I our example events of category 'articles' will be selectable if 'articles.php' is the current page and additionally 'art_id' is in the request (e.g. articles.php?op=show&art_id=1).
If you are not using "item_name", then the vents of category 'articles' will be selectable for 'articles.php', also without op params.


## Third part:
If we have the categories we can define the events, which should initiate a notification.

In our example we want to be informed, if a new article is submitted or an article is deleted.

        // ARTICLE Notify
        $modversion['notification']['event'][] = [
            'name'          => 'global_new',
            'category'      => 'articles',
            'admin_only'    => 0,
            'title'         => _MI_MYEXAMPLE_NOTIFY_GLOBAL_NEW,
            'caption'       => _MI_MYEXAMPLE_NOTIFY_GLOBAL_NEW_CAPTION,
            'description'   => '',
            'mail_template' => 'global_new_notify',
            'mail_subject'  => _MI_MYEXAMPLE_NOTIFY_GLOBAL_NEW_SUBJECT,
        ];
        // ARTICLE Notify
        $modversion['notification']['event'][] = [
            'name'          => 'article_delete',
            'category'      => 'articles',
            'admin_only'    => 0,
            'title'         => _MI_MYEXAMPLE_ARTICLE_NOTIFY,
            'caption'       => _MI_MYEXAMPLE_ARTICLE_NOTIFY_CAPTION,
            'description'   => '',
            'mail_template' => 'article_delete_notify',
            'mail_subject'  => _MI_MYEXAMPLE_ARTICLE_NOTIFY_SUBJECT,
        ];

## 2) Language files    
  
Add the necessary defines to your language file modinfo.php, e.g.

        define('_MI_MYEXAMPLE_NOTIFY_GLOBAL', 'Global notification');
        define('_MI_MYEXAMPLE_NOTIFY_GLOBAL_NEW', 'Any new item');
        define('_MI_MYEXAMPLE_NOTIFY_GLOBAL_NEW_CAPTION', 'Notify me about any new item');
        define('_MI_MYEXAMPLE_NOTIFY_GLOBAL_NEW_SUBJECT', 'Notification about new item');
        ...
        // Article notifications
        define('_MI_MYEXAMPLE_NOTIFY_ARTICLE', 'Article notification');
        define('_MI_MYEXAMPLE_NOTIFY_ARTICLE_DELETE', 'Article deleted');
        define('_MI_MYEXAMPLE_NOTIFY_ARTICLE_DELETE_CAPTION', 'Notify me about deleted articles');
        define('_MI_MYEXAMPLE_NOTIFY_ARTICLE_DELETE_SUBJECT', 'Notification delete article');
        ....


## 3) File include/notification.inc.php

As in xoops_version.php you have to add the file where the lookup function is defined.
Each time a notification event is happening this function will be called and it returns the data which will be used to fill in the template.
As a parameter you get the notification category and the item id,
With the item id you get provide the necessary date for the notification template.
In our example we provide the article title and a link to the related article.
        function myexample_notify_iteminfo($category, $item_id)
        {
            global $xoopsDB;
        
            if (!defined('MYEXAMPLE_URL')) {
                define('MYEXAMPLE_URL', XOOPS_URL . '/modules/myexample');
            }
        
            switch($category) {
                case 'global':
                    $item['name'] = '';
                    $item['url']  = '';
                    return $item;
                break;
                case 'articles':
                    $sql          = 'SELECT art_title FROM ' . $xoopsDB->prefix('myexample_articles') . ' WHERE art_id = '. $item_id;
                    $result       = $xoopsDB->query($sql);
                    $result_array = $xoopsDB->fetchArray($result);
                    $item['name'] = $result_array['art_title'];
                    $item['url']  = MYEXAMPLE_URL . '/articles.php?art_id=' . $item_id;
                    return $item;
                break;
            }
            return null;
        }

## 4) Add notification template to language file

Add a notification template to language/english/mail_template (or in your language file directory). 
Use the name you defined in xoops_version.php (in our example: 'mail_template' => 'global_new_notify',)

        Hello {X_UNAME},
        
        A new item "{ITEM_NAME}" has been added at {X_SITENAME}.
        
        You can view this item here:
        {ITEM_URL}
        
        ------------------------------------------------------------------
        
        You are receiving this message because you selected to be notified when a new item is added to our site.
        
        If this is an error or you wish not to receive further such notifications, please update your subscriptions by visiting the link below:
        {X_UNSUBSCRIBE_URL}
        
        Please do not reply to this message.
        
        ------------------------------------------------------------------
        
        {X_SITENAME} ({X_SITEURL})
        webmaster
        {X_ADMINMAIL}
        
        ------------------------------------------------------------------

The xoops notification system will replace all tags, e.g. ITEM_NAME will be replaced by the value you get from '$item['name']' in myexample_notify_iteminfo
The xoops tags like X_UNSUBSCRIBE_URL,X_SITENAME and so one will be replaced automatically.

## 5) Implementation and event calling

In any case where we want to check for a notification event we have to add the event trigger.
As a parameter you get the comment object with all necessary information
Therefore we need only to add the call of notification handler.

The event trigger use following parameters:

     * @param string $category     notification category
     * @param int    $item_id      ID of the item
     * @param array  $events       trigger events
     * @param array  $extra_tags   array of substitutions for template to be
     *                              merged with the one from function..
     * @param array  $user_list    only notify the selected users
     * @param int    $module_id    ID of the module
     * @param int    $omit_user_id ID of the user to omit from notifications. (default to current user).  set to 0 for all users to receive notification.

If we want to call the global event for new article we can add following code to the save-funtion:
        
        $tags = [];
        $tags['ITEM_NAME'] = $artTitle;
        $tags['ITEM_URL']  = XOOPS_URL . '/modules/myexample/articles.php?op=show&art_id=' . $artId;
        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('global', 0, 'global_new', $tags);


Thats all :)
