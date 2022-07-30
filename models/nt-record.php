<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Record class
 */
class Record
{
    /**
     * Adds new record
     *
     * @param string Record title
     * @param array  Record details
     *
     * @return int|false Number of rows affected or false on error
     */
    public static function add($title, $details)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'nt_records';

	    // Serialize details
	    $details = maybe_serialize($details);

        return $wpdb->query($wpdb->prepare
            ("INSERT INTO {$table_name} (title, details) VALUES(%s, %s)", array($title, $details))
        );
    }

    /**
     * Updates record
     *
     * @param int    Record ID
     * @param string Record title
     * @param array  Record details
     *
     * @return int|false The number of rows updated, or false on error
     */
    public static function update($id, $title, $details)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'nt_records';

        // Update record
        return $wpdb->update($table_name,
            array(
                'title'   => $title,
                'details' => maybe_serialize($details),
            ),
            array(
                'ID' => $id
            ),
            array(
                '%s',
                '%s',
            )
        );
    }

    /**
     * Deletes record
     *
     * @param int Record ID
     *
     * @return int|false The number of rows deleted, or false on error
     */
    public static function delete($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'nt_records';

        // Delete record
        return $wpdb->delete(
            $table_name,
            array(
                'ID' => (int)$id
            )
        );
    }

}