
# WordPress Commands.


## Cache

```wp
wp cache flush --allow-root
wp redis flush
```
// Wp core
wp core update [--version=6.5.2]
wp core version
wp core download

//For checking wordpress crons
wp cron event list
wp cron test

// checking home and site url
wp option get home && wp option get siteurl

 wp plugin list --skip-plugin --skip-themes --allow-root
 wp theme list --skip-plugin --skip-themes --allow-root

// Activating/deactivating/switching  plugins/themses.
wp plugin activate [plugin-name]
wp plugin deactivate [plugin-name]

wp theme activate [theme-name] // it will switch the theme

// For Search and Replace in Database
wp search-replace olddomain newdomain --all-tables --dry-run

// For Search and Replace from http to https in Database
wp search-replace http://olddomain https://newdomain --all-tables --dry-run

// For Search and Replace in all files
grep -rl "olddomain"| xargs sed -i 's#olddomain#newdomain#g'





## WP perimeters


// For increasing memory limit
define('WP_MEMORY_LIMIT', '64M');

// Incase if the option of 'Add New Plugins' Dissappear
define('DISALLOW_FILE_MODS',false);

// For setting Home and Site URL
define( 'WP_HOME', 'http://example.com' );
define( 'WP_SITEURL', 'http://example.com' );

// When  ask for SFTP Credentials while installing any plugin
define( 'FS_METHOD', 'direct' );

// Changing permission of directory and file
define( 'FS_CHMOD_DIR', 0775);
define( 'FS_CHMOD_FILE', 0664 );




# Use X-Forwarded-For HTTP Header to Get Visitor's Real IP Address

if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
    $http_x_headers = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );

    $_SERVER['REMOTE_ADDR'] = $http_x_headers[0];
}

$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP']; 


#Wordpress asking for Enabling Cookies in the browser
define('COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] );
#OR
define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');


#For setting memcache/redis cache key salt
define ('WP_CACHE_KEY_SALT', 'domain.com');

#If error shows "Sorry, this file type is not supported for security reasons?" while uploading fonts
define('ALLOW_UNFILTERED_UPLOADS', true);

#For disabling cron of Wordpress
define('DISABLE_WP_CRON', true);


# WP user management
#For viewing list of wp users
wp user list
#For creating wp-admin testing user credentials
wp user create Cloudways platformops@cloudways.com --role=administrator
wp user create bob bob@example.com --role=author
#For deleting test user
wp user delete test --reassign=567

# Permalinks 
# Checking permalinks
wp option get permalink_structure
# Resetting the permalinks to teh curent state
wp option update permalink_structure '/'
# Updating the permalinks structure
wp option update permalink_structure '/%postname%'
# Flush rewrite rules
wp rewrite flush

# Resetting the permalnks to the current state
wp option update permalink_structure '/'

#Enable WP_DEBUG mode
define( 'WP_DEBUG', true );
#Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );
#Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

#Check Site URL and Home URL from CLI:
wp option get siteurl --allow-root
wp option get home --allow-root

#WP DB Optimization (Make sure to take DB backup first):
wp plugin install wp-sweep
wp plugin activate wp-sweep
wp sweep --all
wp sweep --skip-plugins=<plugin name> --all

# Sometimes due to CF, Wp-cron doesn't work, So in such cases you can simply replace it with following one:
wget --no-check-certificate -q -O - https://domainhere.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1

#Wp-Doctor:
wp package install wp-cli/doctor-command
wp doctor check --all

#Post-processing of the image failed
Add this to the functions.php file of the running theme:
add_filter( 'big_image_size_threshold', '__return_false' );



wp plugin activate [plugin-name]
wp plugin deactivate [plugin-name]
wp theme activate [theme-name]
# You can switch the theme but there are no commands to deactivate the theme until you rename the theme directory.

#If wordpress have some non-active plugins and we wish to save the list of active plugin only
wp plugin list --status=active --format=csv  --allow-root | awk -F, '{print $1}'   >  plugin.txt
#Reactivate all of those specific plugin by using this
wp plugin activate $(<plugin.txt) --allow-root

#For changing Files and Foldr Permission
find -type d -exec chmod 775 {} ';'
find -type f -exec chmod 664 {} ';'

#Blocking spamming bots request
RewriteEngine On
RewriteCond %{HTTP_USER_AGENT} ^.*(SCspider|Textbot|s2bot|MJ12bot|YandexBot|SemrushBot|AspiegelBot|BLEXBot|webmeup-crawler|oBot|Semrush|SiteExplorer|BaiDuSpider).*$ [NC]
RewriteRule .* - [F,L]


##  Manual Migration.



## Validating the  WP checksums
wp core verify-checksums --allow-root
# Searching and removing .htaccess
find wp-admin wp-includes wp-content -name .htaccess -delete -print | tee >(wc -l)

# Remocing PHP.ini from the Nginx base host. 
find wp-admin wp-includes wp-content -name php.ini -delete -print | tee >(wc -l)

# Checking Broken symlinks in WP.
find /home/master/applications/*/public_html/ -type l

#  replacing core files of WP 
wp core download --version=6.5.2 --force --skip-content --allow-root

# Checking last modified time.
find ./ -type f -mtime -15



#Searching for single plugin in all applications on server
for i in $(find /home/master/applications/*/public_html/wp-content/plugins/ -maxdepth 1 -type d | grep "<plugin-name>"| cut -d "/" -f5); do echo $i && cat /home/master/applications/$i/conf/server.nginx | grep server_name | grep -v Domain_alias | awk -F";" '{print $1}' ; done 2>/dev/null

#Searching for more than one plugin in applications on server
for i in $(find /home/master/applications/*/public_html/wp-content/plugins/ -maxdepth 1 -type d | grep "<plugin-name>\|<plugin-name>\|<plugin-name>"| cut -d "/" -f5); do echo $i && cat /home/master/applications/$i/conf/server.nginx | grep server_name | grep -v Domain_alias | awk -F";" '{print $1}' ; done 2>/dev/null

# Deactivate all the plugins on all apps. 
find /home/master/applications/*/public_html/ -type f -name wp-config.php -execdir wp plugin deactivate --all  --allow-root \;




