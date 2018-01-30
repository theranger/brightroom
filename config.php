<?php
/**
 * Copyright 2016 The Ranger <ranger@risk.ee>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types = 1);

// General configuration file
// Check if some configuration is already loaded.
if (isset($settings)) return;

/**
 * Directory where your image files are located
 *
 * Can be absolute path and begin with / or relative path to this directory
 */
$settings["dataDirectory"] = "files";

/**
 * URL prefix where actual image files can be retrieved
 *
 * Usually no need to change unless you have a gallery with the same name
 * Must start with / and can be any name
 * If there is a gallery folder with the same name, it cannot be accessed!
 */
$settings["imagePrefix"] = "/img";

/**
 * Gallery URL where to load themes and images from. Must be specified if
 * external gallery instance will be used for loading themes and images.
 * This is most common when embedding gallery into existing CMS.
 *
 * Comment out if only one instance is used.
 */
//$settings["galleryURL"] = "";

/**
 * URL prefix if content will not be displayed from web root directory
 *
 * Must begin with /. Comment out if content is in web root.
 * Note that enabling this option requires modification in .htaccess file as well!
 */

//$settings["documentRoot"] = "/photos/sfg";

/**
 * Directory to store thumbnails and other resized images
 *
 * Name of a folder which will be created in every gallery folder
 * (which means the gallery folder must be writable to the webserver)
 * or an absolute path to another folder which will be used.
 * Comment out to disable caching.
 */
$settings["cacheFolder"] = ".cache";

/**
 * Force HTTPS.
 *
 * If set to true, non-HTTPS connections will be automatically redirected to HTTPS.
 *
 * Default value: false
 */
//$settings["forceHTTPS"] = false;

/**
 * Thumbnail size
 *
 * Image size for folder listings
 */
$settings["thumbnailSize"] = 100;

/**
 * Full image size
 *
 * Image size for full image preview
 * Comment out to always return the original image.
 *
 * Default value: -1 (disable resize)
 */
//$settings["imageSize"] = -1;

/**
 * Badge width
 *
 * Width of the directory badge. Badge height is the same as THUMBNAIL_SIZE
 *
 * Default value: 200
 */
//$settings["badgeWidth"] = 200;

/**
 * Badge image count
 *
 * Number of small images to be displayed on badge
 *
 * Default value: 3
 */
//$settings["badgeElementCount"] = 3;

/**
 * Show EXIF info under image
 */
$settings["showExif"] = true;

/**
 * Create overlay with a title for each big image
 */
$settings["overlayTitle"] = true;

/**
 * Readme file name
 *
 * The contents of this file will be shown in gallery listings
 * Can be used to describe the contents of the gallery folder
 */
$settings["readmeFile"] = "readme.html";


/**
 * Enable pagination
 *
 * Enables the pagination for large galleries. The URL-s will contain the page number between
 * the gallery path and file name. All physical subgalleries that might have the same name will
 * be ignored. The number determines how many images per page will be shown.
 *
 * Default value: 200
 */
$settings["pagination"] = 5;


/**
 * Anchor offset.
 *
 * When cycling through images, use anchors for scrolling sidebar in case AJAX loading is not available.
 * Specifiy how many previous images will be shown when scrolling.
 *
 * Default value: 3
 */
//$settings["anchorOffset"] = 3;

/**
 * Veto folders
 *
 * Define array of folders that will be hidden and inaccessible from web.
 * Note that items must be surrounded by forward slashes. For more examples, search "SAMBA veto files" from web.
 *
 * Default value: /@eaDir/
 */
//$settings["vetoFolders"] = "/@eaDir/";


/**
 * Gallery password file
 *
 * Specifies file name, located in DATA_DIR root folder, where user names and passwords are stored.
 * Access to this file will be prohibited from web.
 *
 *  Default value: galpasswd.txt
 */
//$settings["passwordFile"] = "galpasswd.txt";

/**
 * Gallery access file
 *
 * Specifies file name, located in each gallery folder, that will include access control lists for that
 * particular gallery.
 * Access to this file will be prohibited from web.
 *
 * Default value: galaccess.txt
 */
//$settings["accessFile"] = "galaccess.txt";


/**
 * Salt
 *
 * Used for securing gallery sessions. Should be unique and long enough!
 */
$settings["salt"] = "asdlfkjcv+04sz=)sadlkfdsxcmsdf0+=)(efdksdc+02,LKO";

/**
 * Theme
 *
 * Specify custom theme. Name indicates a subfolder in a theme/ directory.
 *
 * Default value: default
 */
//$settings["theme"] = "default";
