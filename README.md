## WP-CLI Cheat-sheet

```
##Cache
wp cache flush --allow-root
wp redis flush

##Core
wp core update [--version=6.5.2]
wp core version
wp core download

## Validate WP Checksums
wp core verify-checksums --allow-root

## Crons
wp cron event list
wp cron test

## Options
wp option get <option-name>
wp option get home && wp option get siteurl
wp option update <option-name> <value>
wp option add <option-name> <value>
wp option delete <option-name>

# List all options (can be quite long)
wp option list

# Check if an option exists
wp option exists <option-name>
# Get all options starting with a specific prefix
wp option list --search=<prefix>

```

## Plugins/Themes $ Search/Replace
```
wp plugin list --skip-plugins --skip-themes --allow-root // Skiping the errors caused by plugins
wp theme list --skip-plugins --skip-themes --allow-root // Skiping the errors caused by themes.
wp plugin activate [plugin-name]
wp plugin deactivate [plugin-name]
wp theme activate [theme-name]

### WP Database Optimization
wp plugin install wp-sweep
wp plugin activate wp-sweep
wp sweep --all // Create a backup before using it.

### WP Doctor
wp package install wp-cli/doctor-command
wp doctor check --all

## Search and Replace in Database
wp search-replace old-domain new-domain <flag-name>
wp search-replace http://olddomain https://newdomain --all-tables --dry-run

```

## WP-user Management.

```
# List useres
wp user list

### Creating a New Admin User
wp user create user-name user@gmail.com --role=administrator
wp user create new-user new-user@example.com --role=editor --user_pass=Password123

# Updating password for an existing user.
wp user update <user-id-or-user-login> --user_pass=<new-password>

### Deleting a User
wp user delete test --reassign=567
```


## Permalinks

```
### Checking Permalinks
wp option get permalink_structure

### Resetting Permalinks
wp option update permalink_structure '/'

### Updating Permalink Structure
wp option update permalink_structure '/%postname%'

### Flushing Rewrite Rules
wp rewrite flush

### Check Site and Home URL from CLI
wp option get siteurl --allow-root
wp option get home --allow-root

### Replace WP Core Files
wp core download --version=6.5.2 --force --skip-content --allow-root

```


## WP Parameters

```
### Increasing Memory Limit
define('WP_MEMORY_LIMIT', '64M');

### Restoring 'Add New Plugins' Option
define('DISALLOW_FILE_MODS', false);

### Setting Home and Site URL
define('WP_HOME', 'http://example.com');
define('WP_SITEURL', 'http://example.com');

### Direct FS Method (For SFTP credentials prompt)
define('FS_METHOD', 'direct');

### File and Directory Permissions
define('FS_CHMOD_DIR', 0775);
define('FS_CHMOD_FILE', 0664);

## X-Forwarded-For HTTP Header (Visitor's Real IP Address)
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $http_x_headers = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $_SERVER['REMOTE_ADDR'] = $http_x_headers[0];
}
$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];

## Cookie Management
define('COOKIE_DOMAIN', $_SERVER['HTTP_HOST']);
define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');

## Cache Key Salt for Memcache/Redis
define('WP_CACHE_KEY_SALT', 'domain.com');

## Enabling Font Uploads
define('ALLOW_UNFILTERED_UPLOADS', true);

## Disabling WordPress Cron
define('DISABLE_WP_CRON', true);

```


### Enable WP_DEBUG Mode
```
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

### WP Cron Replacement (for Cloudflare issues)
wget --no-check-certificate -q -O - https://domainhere.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1

### Image Post-Processing Error Fix (functions.php)
add_filter('big_image_size_threshold', '__return_false');

## File and Directory Permissions
find -type d -exec chmod 775 {} ';'
find -type f -exec chmod 664 {} ';'

## Blocking Spamming Bots from htaccess.
RewriteEngine On
RewriteCond %{HTTP_USER_AGENT} ^.*(SCspider|Textbot|s2bot|MJ12bot|YandexBot|SemrushBot|AspiegelBot|BLEXBot|webmeup-crawler|oBot|Semrush|SiteExplorer|BaiDuSpider).*$ [NC]
RewriteRule .* - [F,L]

```

## Manual Migration
```
# Creating database from shell.

```

## General shell commands
```
## Search and Replace in Files
grep -rl "olddomain" | xargs sed -i 's#olddomain#newdomain#g'

### Remove .htaccess Files
find wp-admin wp-includes wp-content -name .htaccess -delete -print | tee >(wc -l)

### Remove PHP.ini from Nginx Base Host
find wp-admin wp-includes wp-content -name php.ini -delete -print | tee >(wc -l)

### Check for Broken Symlinks
find /home/master/applications/*/public_html/ -type l
```
##..Contined..
```
### Check Last Modified Time
find ./ -type f -mtime -15

### Search for a Plugin in All Applications
for i in $(find /home/master/applications/*/public_html/wp-content/plugins/ -maxdepth 1 -type d | grep "<plugin-name>" | cut -d "/" -f5); do echo $i && cat /home/master/applications/$i/conf/server.nginx | grep server_name | grep -v Domain_alias | awk -F";" '{print $1}' ; done 2>/dev/null

### Deactivate All Plugins in All Applications
find /home/master/applications/*/public_html/ -type f -name wp-config.php -execdir wp plugin deactivate --all --allow-root \;

```

# Basic Git Commands

```
git status

```
