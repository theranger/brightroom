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

?>