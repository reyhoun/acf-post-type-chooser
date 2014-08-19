# ACF Field Type Template

Welcome to the Advanced Custom Fields field type template repository.
Here you will find a starter-kit for creating a new ACF field type. This start-kit will work as a normal WP plugin.

For more information about creating a new field type, please read the following article:
http://www.advancedcustomfields.com/resources/tutorials/creating-a-new-field-type/

### Structure

* `/css`:  folder for .css files.
* `/images`: folder for image files
* `/js`: folder for .js files
* `/lang`: folder for .pot, .po and .mo files
* `acf-post-type-chooser.php`: Main plugin file that includes the correct field file based on the ACF version
* `post-type-chooser-v5.php`: Field class compatible with ACF version 5 
* `readme.txt`: WordPress readme file to be used by the wordpress repository

-----------------------

# ACF Post Type Chooser Field

Display all Post Types

-----------------------

### Compatibility

This ACF field type is compatible with:
* ACF 5

### Installation

1. Copy the `acf-post-type-chooser` folder into your `wp-content/plugins` folder
2. Activate the Post Type Chooser plugin via the plugins admin page
3. Create a new field via ACF and select the Post Type Chooser type
4. Please refer to the description for more info regarding the field type settings

### Changelog

= 1.0.0 =
* Initial Release.