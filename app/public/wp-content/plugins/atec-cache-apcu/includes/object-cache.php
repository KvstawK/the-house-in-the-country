<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
  * Plugin Name:  atec Cache APCu
  * Plugin URI: https://atec-systems.com/
  * Description: aAPCu object cache
  * Version: 1.1
  * Author: Chris Ahrweiler
  * Author URI: https://atec-systems.com
  * License: GPL2
  * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
  * Text Domain:  atec-cache-apcu
  * Origin of object-cache.php: https://raw.githubusercontent.com/l3rady/WordPress-APCu-Object-Cache/master/object-cache.php
  * Original Author: Scott Cariss
*/

// define('ATEC_WPCA_OC_INSTALLED',true);

function wp_cache_add($key, $data, $group = 'default', $expire = 0)
{ return WP_Object_Cache::instance()->add($key, $data, $group, $expire); }

function wp_cache_close()
{ return true; }

function wp_cache_decr($key, $offset = 1, $group = 'default')
{ return WP_Object_Cache::instance()->decr($key, $offset, $group); }

function wp_cache_delete($key, $group = 'default')
{ return WP_Object_Cache::instance()->delete($key, $group); }

function wp_cache_flush()
{ return WP_Object_Cache::instance()->flush(); }

function wp_cache_get($key, $group = 'default', $force = false, &$found = null)
{ return WP_Object_Cache::instance()->get($key, $group, $force, $found); }

function wp_cache_get_multi($groups)
{ return WP_Object_Cache::instance()->get_multi($groups); }

function wp_cache_incr($key, $offset = 1, $group = 'default')
{ return WP_Object_Cache::instance()->incr($key, $offset, $group); }

function wp_cache_init()
{ $GLOBALS['wp_object_cache'] = WP_Object_Cache::instance(); }

function wp_cache_replace($key, $data, $group = 'default', $expire = 0)
{ return WP_Object_Cache::instance()->replace($key, $data, $group, $expire); }

function wp_cache_set($key, $data, $group = 'default', $expire = 0)
{ return WP_Object_Cache::instance()->set($key, $data, $group, $expire); }

function wp_cache_switch_to_blog($blog_id)
{ WP_Object_Cache::instance()->switch_to_blog($blog_id); }

function wp_cache_add_global_groups($groups)
{ WP_Object_Cache::instance()->add_global_groups($groups); }

function wp_cache_add_non_persistent_groups($groups)
{ WP_Object_Cache::instance()->add_non_persistent_groups($groups); }

function wp_cache_reset()
{ _deprecated_function(__FUNCTION__, '3.5', 'wp_cache_switch_to_blog()'); return false; }

function wp_cache_flush_site($sites = null)
{ return WP_Object_Cache::instance()->flush_sites($sites); }

function wp_cache_flush_group($groups = 'default')
{ return WP_Object_Cache::instance()->flush_groups($groups); }

/**
 * WordPress APCu Object Cache Backend
 *
 * The WordPress Object Cache is used to save on trips to the database. The
 * APCu Object Cache stores all of the cache data to APCu and makes the cache
 * contents available by using a key, which is used to name and later retrieve
 * the cache contents.
 */
class WP_Object_Cache
{
    private $abspath;
    private $apcu_available;
    private $blog_prefix;
    public $cache_hits = 0;
    public $cache_misses = 0;
    private $global_groups = [];
    private $group_versions = [];
    private $multi_site;
    private $non_persistent_cache = [];
    private $non_persistent_groups = [];
    private $local_cache = [];
    private $site_versions = [];
    private static $instance;

    public static function instance()
    {
        if (self::$instance === null) { self::$instance = new WP_Object_Cache(); }
        return self::$instance;
    }

    private function __clone() {}

    private function __construct()
    {
        global $blog_id;

        if (!defined('WP_APCU_KEY_SALT')) { define('WP_APCU_KEY_SALT', 'wp'); }

        /**
         * define('WP_APCU_LOCAL_CACHE', false) to disable local
         * array cache and force all cache to be returned from APCu
         */
        if (!defined('WP_APCU_LOCAL_CACHE')) { define('WP_APCU_LOCAL_CACHE', true); }

        $this->abspath = md5(ABSPATH);
        $this->apcu_available = (extension_loaded('apcu') && ini_get('apc.enabled'));
        $this->multi_site = is_multisite();
        $this->blog_prefix = $this->multi_site ? $blog_id : 1;
    }
    
    public function stats()
    {
        if (!function_exists('bsize')) { function bsize($s) { foreach (array('','K','M','G') as $i => $k) { if ($s < 1024) break; $s/=1024; } return sprintf("%5.1f %sB",$s,$k); }; }

        $bytes=0; $c=0;
        foreach ( $this->local_cache as $group => $cache ) 
        { $c++; $bytes+=(int) strlen(serialize($cache)); };
            
        $total=$this->cache_hits+$this->cache_misses+0.001;
        $hits=$this->cache_hits*100/$total;
        $misses=$this->cache_misses*100/$total;

        echo '<table class="pure-table">
        <tbody>
                <tr><td class="ac_b">Type:</td><td>APCu</td></tr>
                <tr><td class="ac_b">Used:</td><td>'.esc_html(bsize($bytes)).'</td></tr>
                <tr><td class="ac_b">Items:</td><td>'.esc_html(number_format($c)).'</td></tr>
                <tr><td class="ac_b">Hits:</td><td>'.esc_html(number_format($this->cache_hits).sprintf(" (%.1f%%)",$hits)).'</td></tr>
                <tr><td class="ac_b">Misses:</td><td>'.esc_html(number_format($this->cache_misses).sprintf(" (%.1f%%)",$misses)).'</td></tr>
                <tr><td colspan="2"><center style="font-size:60%; margin:0;">Hitrate</center><div class="ac_percent_div"><span class="ac_percent" style="width:'.esc_html(round($hits)).'%; background-color:green;"></span><span class="ac_percent" style="width:'.esc_html(round($misses)).'%; background-color:red;"></span></div></td></tr>
            </tbody>
        </table>';
    }
    
    public function add($key, $var, $group = 'default', $ttl = 0)
    {
        if (wp_suspend_cache_addition()) { return false; }
        $key = $this->_key($key, $group);
        if (!$this->apcu_available || $this->_is_non_persistent_group($group)) {
            return $this->_add_np($key, $var);
        }
        return $this->_add($key, $var, $ttl);
    }

    private function _add($key, $var, $ttl)
    {
        if (apcu_add($key, $var, max((int)$ttl, 0))) {
            if (WP_APCU_LOCAL_CACHE) {
                $this->local_cache[$key] = is_object($var) ? clone $var : $var;
            }
            return true;
        }
        return false;
    }

    private function _add_np($key, $var)
    {
        if ($this->_exists_np($key)) { return false; }
        return $this->_set_np($key, $var);
    }

    public function add_global_groups($groups)
    {
        foreach ((array)$groups as $group) { $this->global_groups[$group] = true; }
    }

    public function add_non_persistent_groups($groups)
    {
        foreach ((array)$groups as $group) { $this->non_persistent_groups[$group] = true; }
    }

    public function decr($key, $offset = 1, $group = 'default')
    {
        $key = $this->_key($key, $group);
        if (!$this->apcu_available || $this->_is_non_persistent_group($group)) { return $this->_decr_np($key, $offset); }
        return $this->_decr($key, $offset);
    }

    private function _decr($key, $offset)
    {
        $this->_get($key, $success);
        if (!$success) { return false; }
        $value = apcu_dec($key, max((int)$offset, 0));
        if ($value !== false && WP_APCU_LOCAL_CACHE) { $this->local_cache[$key] = $value; }
        return $value;
    }

    private function _decr_np($key, $offset)
    {
        if (!$this->_exists_np($key)) {
            return false;
        }
        $offset = max((int)$offset, 0);
        $var = $this->_get_np($key);
        $var = is_numeric($var) ? $var : 0;
        $var -= $offset;
        return $this->_set_np($key, $var);
    }

    public function delete($key, $group = 'default', $deprecated = false)
    {
        $key = $this->_key($key, $group);

        if (!$this->apcu_available || $this->_is_non_persistent_group($group)) {
            return $this->_delete_np($key);
        }

        return $this->_delete($key);
    }

   private function _delete($key)
    {
        unset($this->local_cache[$key]);
        return apcu_delete($key);
    }

    private function _delete_np($key)
    {
        if (array_key_exists($key, $this->non_persistent_cache)) {
            unset($this->non_persistent_cache[$key]);

            return true;
        }

        return false;
    }

    private function _exists_np($key)
    {
        return array_key_exists($key, $this->non_persistent_cache);
    }

    public function flush()
    {
        $this->non_persistent_cache = [];
        if (WP_APCU_LOCAL_CACHE) {
            $this->local_cache = [];
        }
        if ($this->apcu_available) {
            apcu_clear_cache();
        }

        return true;
    }

    public function flush_groups($groups)
    {
        $groups = (array)$groups;
        if (empty($groups)) {
            return false;
        }
        foreach ($groups as $group) {
            $version = $this->_get_group_cache_version($group);
            $this->_set_group_cache_version($group, $version + 1);
        }
        return true;
    }

    public function flush_sites($sites)
    {
        $sites = (array)$sites;
        if (empty($sites)) {
            $sites = [$this->blog_prefix];
        }
        // Add global groups (site 0) to be flushed.
        if (!in_array(0, $sites, false)) {
            $sites[] = 0;
        }
        foreach ($sites as $site) {
            $version = $this->_get_site_cache_version($site);
            $this->_set_site_cache_version($site, $version + 1);
        }
        return true;
    }

    public function get($key, $group = 'default', $force = false, &$success = null)
    {
        $key = $this->_key($key, $group);

        if (!$this->apcu_available || $this->_is_non_persistent_group($group)) {
            $var = $this->_get_np($key, $success);
        } else {
            $var = $this->_get($key, $success);
        }

        if ($success) {
            $this->cache_hits++;
        } else {
            $this->cache_misses++;
        }
        return $var;
    }

    private function _get($key, &$success = null)
    {
        if (WP_APCU_LOCAL_CACHE && array_key_exists($key, $this->local_cache)
        ) {
            $success = true;
            $var = $this->local_cache[$key];
        } else {
            $var = apcu_fetch($key, $success);
            if ($success && WP_APCU_LOCAL_CACHE) {
                $this->local_cache[$key] = $var;
            }
        }

        if (is_object($var)) {
            $var = clone $var;
        }

        return $var;
    }

    private function _get_np($key, &$success = null)
    {
        if (array_key_exists($key, $this->non_persistent_cache)) {
            $success = true;
            return $this->non_persistent_cache[$key];
        }
        $success = false;
        return false;
    }

    private function _get_cache_version($key)
    {
        if ($this->apcu_available) {
            $version = (int)apcu_fetch($key);
        } elseif (array_key_exists($key, $this->non_persistent_cache)) {
            $version = (int)$this->non_persistent_cache[$key];
        } else {
            $version = 0;
        }
        return $version;
    }

    private function _get_cache_version_key($type, $value)
    { return WP_APCU_KEY_SALT . ':' . $this->abspath . ':' . $type . ':' . $value; }

    private function _get_group_cache_version($group)
    {
        if (!isset($this->group_versions[$group])) {
            $this->group_versions[$group] = $this->_get_cache_version(
                $this->_get_cache_version_key(
                    'GroupVersion',
                    $group
                )
            );
        }
        return $this->group_versions[$group];
    }

   public function get_multi($groups)
    {
        if (empty($groups) || !is_array($groups)) {
            return false;
        }
        $vars = [];
        $success = false;
        foreach ($groups as $group => $keys) {
            $vars[$group] = [];
            foreach ($keys as $key) {
                $var = $this->get($key, $group, false, $success);
                if ($success) {
                    $vars[$group][$key] = $var;
                }
            }
        }
        return $vars;
    }

    private function _get_site_cache_version($site)
    {
        if (!isset($this->site_versions[$site])) {
            $this->site_versions[$site] = $this->_get_cache_version(
                $this->_get_cache_version_key('SiteVersion', $site)
            );
        }

        return $this->site_versions[$site];
    }

    public function incr($key, $offset = 1, $group = 'default')
    {
        $key = $this->_key($key, $group);
        if (!$this->apcu_available || $this->_is_non_persistent_group($group)) {
            return $this->_incr_np($key, $offset);
        }
        return $this->_incr($key, $offset);
    }

    private function _incr($key, $offset)
    {
        $this->_get($key, $success);
        if (!$success) { return false; }
        $value = apcu_inc($key, max((int)$offset, 0));
        if ($value !== false && WP_APCU_LOCAL_CACHE) { $this->local_cache[$key] = $value; }
        return $value;
    }

    private function _incr_np($key, $offset)
    {
        if (!$this->_exists_np($key)) { return false; }
        $offset = max((int)$offset, 0);
        $var = $this->_get_np($key);
        $var = is_numeric($var) ? $var : 0;
        $var += $offset;
        return $this->_set_np($key, $var);
    }

    private function _is_non_persistent_group($group)
    { return isset($this->non_persistent_groups[$group]); }

    private function _key($key, $group)
    {
        if (empty($group)) { $group = 'default'; }
        $prefix = 0;
        if (!isset($this->global_groups[$group])) { $prefix = $this->blog_prefix; }
        $group_version = $this->_get_group_cache_version($group);
        $site_version = $this->_get_site_cache_version($prefix);
        return WP_APCU_KEY_SALT . ':' . $this->abspath . ':' . $prefix . ':' . $group . ':' . $key . ':v' . $site_version . '.' . $group_version;
    }

    public function replace($key, $var, $group = 'default', $ttl = 0)
    {
        $key = $this->_key($key, $group);
        if (!$this->apcu_available || $this->_is_non_persistent_group($group)) { return $this->_replace_np($key, $var); }
        return $this->_replace($key, $var, $ttl);
    }

    private function _replace($key, $var, $ttl)
    {
        $this->_get($key, $success);
        if ($success) { return false; }
        return $this->_set($key, $var, $ttl);
    }

    private function _replace_np($key, $var)
    {
        if (!$this->_exists_np($key)) { return false; }
        return $this->_set_np($key, $var);
    }

    public function set($key, $var, $group = 'default', $ttl = 0)
    {
        $key = $this->_key($key, $group);
        if (!$this->apcu_available || $this->_is_non_persistent_group($group)) {
            return $this->_set_np($key, $var);
        }
        return $this->_set($key, $var, $ttl);
    }

    private function _set($key, $var, $ttl)
    {
        if (is_object($var)) { $var = clone $var; }
        if (apcu_store($key, $var, max((int)$ttl, 0))) 
        {
            if (WP_APCU_LOCAL_CACHE) { $this->local_cache[$key] = $var; }
            return true;
        }
        return false;
    }

    private function _set_np($key, $var)
    {
        if (is_object($var)) { $var = clone $var; }
        return $this->non_persistent_cache[$key] = $var;
    }

    private function _set_cache_version($key, $version)
    {
        if ($this->apcu_available) { return apcu_store($key, $version); }
        return $this->non_persistent_cache[$key] = $version;
    }

    private function _set_group_cache_version($group, $version)
    { $this->_set_cache_version($this->_get_cache_version_key('GroupVersion', $group), $version); }

    private function _set_site_cache_version($site, $version)
    { $this->_set_cache_version($this->_get_cache_version_key('SiteVersion', $site), $version); }

    public function switch_to_blog($blog_id)
    { $this->blog_prefix = $this->multi_site ? $blog_id : 1; }

    public function getAbspath()
    { return $this->abspath; }

    public function getApcuAvailable()
    { return $this->apcu_available; }

    public function getBlogPrefix()
    { return $this->blog_prefix; }

    public function getCacheHits()
    { return $this->cache_hits; }

    public function getCacheMisses()
    { return $this->cache_misses; }

    public function getGlobalGroups()
    { return $this->global_groups; }

    public function getGroupVersions()
    { return $this->group_versions; }

    public function getMultiSite()
    { return $this->multi_site; }

    public function getNonPersistentCache()
    { return $this->non_persistent_cache; }

    public function getNonPersistentGroups()
    { return $this->non_persistent_groups; }

    public function getSiteVersions()
    { return $this->site_versions; }
}
