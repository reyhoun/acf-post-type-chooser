# ACF Post Type Chooser Field

Display all Post Types

-----------------------

### Structure

* `/css`:  folder for .css files.
* `/images`: folder for image files
* `/js`: folder for .js files
* `/lang`: folder for .pot, .po and .mo files
* `acf-post-type-chooser.php`: Main plugin file that includes the correct field file based on the ACF version
* `post-type-chooser-v5.php`: Field class compatible with ACF version 5 
* `readme.txt`: WordPress readme file to be used by the wordpress repository

### Compatibility

This ACF field type is compatible with:
* ACF 5

### Installation

1. Copy the `acf-post-type-chooser` folder into your `wp-content/plugins` folder
2. Activate the Post Type Chooser plugin via the plugins admin page
3. Create a new field via ACF and select the Post Type Chooser type
4. Please refer to the description for more info regarding the field type settings