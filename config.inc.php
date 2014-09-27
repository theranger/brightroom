<?php

// General configuration file

/**
 * Directory where your image files are located
 *
 * Can be absolute path and begin with / or relative path to this directory
 */
define("DATA_DIR", "files");

/**
 * URL prefix where actual image files can be retrieved
 *
 * Usually no need to change unless you have a gallery with the same name
 * Must start with / and can be any name
 * If there is a gallery folder with the same name, it cannot be accessed!
 */
define("IMG_PREFIX", "/img");

/**
 * URL prefix if content will not be displayed from web root directory
 * 
 * Must begin with /. Comment out if content is in web root.
 */

//define("URL_PREFIX", "/doc/samples");

/**
 * Directory to store thumbnails and other resized images
 *
 * Created in every gallery folder
 * Therefore the gallery folder must be writable to the webserver
 * To disable caching comment out this variable:
 *
 *      //define("CACHE_FOLDER", ".cache");
 */
define("CACHE_FOLDER", ".cache");

/**
 * Force HTTPS.
 *
 * If set to true, non-HTTPS connections will be automatically redirected to HTTPS.
 *
 * Default value: false
 */
//define("FORCE_HTTPS", true);

/**
 * Thumbnail size
 *
 * Image size for folder listings
 */
define("THUMBNAIL_SIZE", 70);

/**
 * Full image size
 *
 * Image size for full image preview
 */
define("IMAGE_SIZE", 600);

/**
 * Show EXIF info under image
 */
define("SHOW_EXIF", true);

/**
 * Create overlay with a title for each big image
 */
define("OVERLAY_TITLE", true);

/**
 * Readme file name
 *
 * The contents of this file will be shown in gallery listings
 * Can be used to describe the contents of the gallery folder
 */
define("README_FILE", "readme.html");


/**
 * Enable pagination
 *
 * Enables the pagination for large galleries. The URL-s will contain the page number between
 * the gallery path and file name. All physical subgalleries that might have the same name will
 * be ignored. The number determines how many images per page will be shown.
 *
 * Default value: 200
 */
define("PAGINATION", 5);


/**
 * Anchor offset.
 *
 * When cycling through images, use anchors for scrolling sidebar in case AJAX loading is not available.
 * Specifiy how many previous images will be shown when scrolling.
 *
 * Default value: 3
 */
//define("ANCHOR_OFFSET", 3);

/**
 * Veto folders
 *
 * Define array of folders that will be hidden and inaccessible from web.
 * Note that items must be surrounded by forward slashes. For more examples, search "SAMBA veto files" from web.
 *
 * Default value: /@eaDir/
 */
define("VETO_FOLDERS", "/@eaDir/");


/**
 * Gallery password file
 *
 * Specifies file name, located in DATA_DIR root folder, where user names and passwords are stored.
 * Access to this file will be prohibited from web.
 *
 *  Default value: galpasswd.txt
 */
//define("PASSWD_FILE", "galpasswd.txt");

/**
 * Gallery access file
 *
 * Specifies file name, located in each gallery folder, that will include access control lists for that
 * particular gallery.
 * Access to this file will be prohibited from web.
 *
 * Default value: galaccess.txt
 */
//define("ACCESS_FILE", "galaccess.txt");


/**
 * Salt
 *
 * Used for securing gallery sessions. Should be unique and long enough!
 */
define("SALT", "asdlfkjcv+04sz=)sadlkfdsxcmsdf0+=)(efdksdc+02,LKO");

/**
 * Theme
 * 
 * Specify custom theme. Name indicates a subfolder in a theme/ directory.
 * 
 * Default value: default
 */
define("THEME", "embed");


?>