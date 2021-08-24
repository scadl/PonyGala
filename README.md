# PonyGala
This one is my beloved web app. It implements my view to how online photogallaries should be built. So ofcourse I myself use this webengine for my biggest project [Big DigitalArt Gallery](https://artgala.scadsdnd.net/index.php).

### Functionality
The current version you see here, is completely rewritten system I supporting and evolving now. 
* Now it uses fast MySQL database, with improved tables structure. All the previews and full versions of arts are shipped by dA engine, so you are not dependent from performance of my server. My engine now only stores unique id of arts, and it's binding to my categories. 
* The category previews are now generated with modern CSS and not stored to my server slow HDD. 
* The statistics got better with more intelligent IP filters, and has visualized graphs for easy analytic. 
* The deviantArt integrator was also improved, so it stores found art ids from given user's subscriptions directly into db while reading it, but all this new data marked with special category, invisible for regular users. 
* The new Twitter integrator powered with [twitteroauth](https://github.com/abraham/twitteroauth) is fast and clean, able to parse tweets from designited list, find and implement  arts inside them, and store it to my own universal structure, verified by time. 
* If you’re logged in with master password, you will see this storage category, and special 'sticky' toolbar, allowing you to easily manage individual arts or whole groups: delete or move them to normal (visible) categories.
* And finally I rewrote gallery's self-repair system with full dA API integration, so it always able to detect and fix any differences between real art’s properties (title, author, source links) on devianArt and your gallery instance.

### Config
To make this app work, create `config.php` in the root folder, and fill it like this:
```php
<?php
/* MainConfig */
$master = 'master0pass';
$db_server = 'db_doamin';
$db_database = 'db_name';
$db_user = 'db_user';
$db_password= 'db_user_pass';
$redirect_url = 'https://your.domain/Ponygala/mods/dA_add-art.php';
$redirect_url_t = 'https://your.domain/Ponygala/mods/tW_add-art.php';
$da_client = '0000';                                                        //a.k.a your deviantArt's app client_id
$da_secret = '000000000000000000000000000000';                              //a.k.a your deviantArt's app client_secret
$tw_api_key = '0000000000000000000000000';                                  //a.k.a your Twitter's app oauth_consumer_key
$tw_api_secret = '000000000000000000000000000000000000000000';              //a.k.a your Twitter's app oauth_consumer_secret
?>
```
Don't forget, that your *redirect_url* should be publicaly avalable and same here, in your deviantArt's Twitter's apps settings!

### History
I wrote first version back in 2012. At that moment it was just web interface for searching through several of my folders, holding downloaded full versions of the best fanarts from the web. When scanned, it used GD-PHP lib to generate previews and store them on special sub-folders. This was pretty slow on work (because of 7200RPM mechanical HDD), but easy to write and understand. Unfortunately I didn't backed up this version of app, thou Some parts of it you can be found in commit's [df2d5bd](https://github.com/scadl/PonyGala/tree/df2d5bdf52fb47c240fdbee010875d044673dec4) '_tmp-old' folder

The second version, didn't have to scan my HDD for art’s files, because it finally got first DB: small sqlite file, holding direct links to full arts on dA, and virtual categories, which replaced my folders names in this role. But as on previous version it still used GD to generate thumbnails of arts and categories, and store them on my server hard drive. It also get first version of devianArt API integration script, able to parse my account's subscriptions and dynamically insert them into forms in gallary’s control panel. One of this old versions you can find in this commit [df2d5bd](https://github.com/scadl/PonyGala/tree/df2d5bdf52fb47c240fdbee010875d044673dec4).

### Screenshots

![The main page](https://github.com/scadl/PonyGala/blob/master/screens/DigitalArt%20Gallery%20v3%20%20by%20scadl%20%20%20Full%20Archive%20.png)
![Search mode](https://github.com/scadl/PonyGala/blob/master/screens/DigitalArt%20Gallery%20v3%20%20by%20scadl%20%20%20Full%20Archive2%20.png)
![Date filter](https://github.com/scadl/PonyGala/blob/master/screens/DigitalArt%20Gallery%20v3%20%20by%20scadl%20%20%20Full%20Archive%203.png)
![Main category view and management](https://github.com/scadl/PonyGala/blob/master/screens/DigitalArt%20Gallery%20v.2%20%20%20Category%20View%20%20by%20scadl%20.png)
![gallary self-repair](https://github.com/scadl/PonyGala/blob/master/screens/PonyArt%20Gallary%20%20by%20scadl%20%20%20DB%20Cleaner%20.png)
![The statistics](https://github.com/scadl/PonyGala/blob/master/screens/Statistics_Index.png)
