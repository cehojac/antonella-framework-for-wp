<?php

namespace CH;

use CH\Config;

/**
 * Install Class - Handles plugin installation, database setup and updates
 * 
 * This class manages all database operations during plugin activation including:
 * - Table creation using WordPress dbDelta() function
 * - Column additions with proper validation
 * - Plugin options setup
 * - Error handling and logging
 * 
 * @package Antonella Framework
 * @version 1.9.0
 * @since 1.0.0
 */
class Install
{
    /**
     * Database charset for tables
     * @var string
     */
    private static $charset_collate;
    
    /**
     * WordPress database object
     * @var \wpdb
     */
    private static $wpdb;
    
    /**
     * Installation errors log
     * @var array
     */
    private static $errors = array();

    /**
     * Constructor - Initialize database properties
     */
    public function __construct()
    {
        global $wpdb;
        self::$wpdb = $wpdb;
        self::$charset_collate = $wpdb->get_charset_collate();
    }

    /**
     * Main installation function - Entry point for plugin activation
     * 
     * @since 1.0.0
     * @return void
     */
    public static function index()
    {
        try {
            $config = new Config();
            $install = new Install();
            
            // Log installation start
    
            
            // Create/update database tables
            if (isset($config->database_tables) && !empty($config->database_tables)) {
                $install->create_tables($config->database_tables);
            }
            
            // Add new columns to existing tables
            if (isset($config->table_updates) && !empty($config->table_updates)) {
                $install->update_table_columns($config->table_updates);
            }
            
            // Setup plugin options
            if (isset($config->plugin_options) && !empty($config->plugin_options)) {
                $install->setup_plugin_options($config->plugin_options);
            }
            
            // Update plugin version
            $install->update_plugin_version();
            
        } catch (Exception $e) {
            self::$errors[] = 'Installation failed: ' . $e->getMessage();
    
            
            // Show admin notice on error
            add_action('admin_notices', array(__CLASS__, 'show_installation_errors'));
        }
    }

    /**
     * Create database tables using WordPress dbDelta function
     * 
     * @since 1.0.0
     * @param array $tables Array of table definitions
     * @return bool Success status
     */
    public function create_tables($tables)
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $success = true;
        
        foreach ($tables as $table_config) {
            try {
                $table_name = self::$wpdb->prefix . $table_config['name'];
                
                // Build complete SQL with proper formatting for dbDelta
                $sql = "CREATE TABLE {$table_name} (\n";
                $sql .= $table_config['columns'] . "\n";
                $sql .= ") " . self::$charset_collate . ";";
                
                // Use dbDelta for intelligent table creation/updates
                $result = dbDelta($sql);
                
                // Check if table creation was successful
                if (empty($result)) {
                    throw new \Exception(esc_html("Failed to create table: {$table_name}"));
                }
                
            } catch (Exception $e) {
                self::$errors[] = "Table creation error: " . $e->getMessage();
                $success = false;
            }
        }
        
        return $success;
    }

    /**
     * Add columns to existing tables with proper validation
     * 
     * @since 1.0.0
     * @param array $updates Array of table update configurations
     * @return bool Success status
     */
    public function update_table_columns($updates)
    {
        $success = true;
        
        foreach ($updates as $table_name => $columns) {
            $full_table_name = self::$wpdb->prefix . $table_name;
            
            // Verify table exists first
            if (!$this->table_exists($full_table_name)) {
                self::$errors[] = "Cannot update columns: Table '{$full_table_name}' does not exist";
                $success = false;
                continue;
            }
            
            foreach ($columns as $column) {
                try {
                    $this->add_column_if_not_exists($full_table_name, $column);
                } catch (Exception $e) {
                    self::$errors[] = "Column addition error: " . $e->getMessage();
                    $success = false;
                }
            }
        }
        
        return $success;
    }

    /**
     * Add column to table if it doesn't exist
     * 
     * @since 1.0.0
     * @param string $table_name Full table name with prefix
     * @param array $column Column configuration
     * @return bool True if column was added, false if already exists
     */
    private function add_column_if_not_exists($table_name, $column)
    {
        // Check if column already exists
        $safe_table_name = esc_sql($table_name);
        $safe_column_name = esc_sql($column['name']);
        
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared -- Direct query needed to check column existence, identifiers escaped manually for DDL operation
        $column_exists = self::$wpdb->get_results(
            "SHOW COLUMNS FROM `" . $safe_table_name . "` LIKE '" . $safe_column_name . "'"
        );
        
        if (!empty($column_exists)) {
            return false; // Column already exists
        }
        
        // Add the column with manual escaping for identifiers
        $safe_column_name = esc_sql($column['name']);
        $safe_definition = esc_sql($column['definition']);
        
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared -- Direct query needed for table structure modification, identifiers escaped manually for DDL operation
        $result = self::$wpdb->query("ALTER TABLE `" . $safe_table_name . "` ADD COLUMN `" . $safe_column_name . "` " . $safe_definition);
        
        if ($result === false) {
            throw new \Exception(esc_html("Failed to add column '{$column['name']}' to table '{$table_name}'"));
        }
        
        return true;
    }

    /**
     * Check if a table exists in the database
     * 
     * @since 1.0.0
     * @param string $table_name Full table name with prefix
     * @return bool True if table exists
     */
    private function table_exists($table_name)
    {
        $safe_table_name = esc_sql($table_name);
        
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared -- Direct query needed to check table existence, table name escaped manually
        $table_exists = self::$wpdb->get_var(
            "SHOW TABLES LIKE '" . $safe_table_name . "'"
        );
        
        return $table_exists === $table_name;
    }

    /**
     * Setup plugin options with proper handling
     * 
     * @since 1.0.0
     * @param array $options Plugin options to set
     * @return void
     */
    public function setup_plugin_options($options)
    {
        foreach ($options as $key => $option) {
            // Use add_option which won't overwrite existing values
            $result = add_option($key, $option);
            

        }
    }

    /**
     * Update plugin version in database
     * 
     * @since 1.0.0
     * @return void
     */
    private function update_plugin_version()
    {
        $current_version = get_option('antonella_framework_version', '0.0.0');
        $new_version = '1.9.0'; // Should match plugin header
        
        if (version_compare($current_version, $new_version, '<')) {
            update_option('antonella_framework_version', $new_version);

        }
    }

    /**
     * Show installation errors in admin notices
     * 
     * @since 1.0.0
     * @return void
     */
    public static function show_installation_errors()
    {
        if (!empty(self::$errors)) {
            echo '<div class="notice notice-error"><p>';
            echo esc_html(__('Antonella Framework installation encountered errors. Check error logs for details.', 'antonella-framework'));
            echo '</p></div>';
        }
    }

    /**
     * Get installation errors (for debugging)
     * 
     * @since 1.0.0
     * @return array Array of error messages
     */
    public static function get_errors()
    {
        return self::$errors;
    }
}
