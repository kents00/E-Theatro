<?php
// reset_db.php

require_once __DIR__ . '/config/db.php';

function resetDatabase() {
    global $conn;

    // List of tables to truncate
    $tables = [
        'notifications',
        'announcements',
        'auditionees'
    ];

    try {
        // Start a transaction
        pg_query($conn, 'BEGIN');

        // Disable triggers if necessary (optional, but safer)
        pg_query($conn, 'SET session_replication_role = replica;');

        foreach ($tables as $table) {
            echo "Truncating table: $table...\n";
            // Use CASCADE to remove dependent objects
            $result = pg_query($conn, "TRUNCATE TABLE $table RESTART IDENTITY CASCADE;");
            if (!$result) {
                throw new Exception("Failed to truncate table $table: " . pg_last_error($conn));
            }
        }

        // Re-enable triggers
        pg_query($conn, 'SET session_replication_role = DEFAULT;');

        // Re-seed the database from the SQL file
        echo "Re-seeding database...\n";
        $sql = file_get_contents(__DIR__ . '/database/audition_system_supabase.sql');
        if ($sql === false) {
            throw new Exception("Failed to read SQL seed file.");
        }

        $result = pg_query($conn, $sql);
        if (!$result) {
            throw new Exception("Failed to re-seed database: " . pg_last_error($conn));
        }

        // Commit the transaction
        pg_query($conn, 'COMMIT');

        echo "Database has been successfully reset and re-seeded.\n";

    } catch (Exception $e) {
        // Rollback on error
        pg_query($conn, 'ROLLBACK');
        die("Error resetting database: " . $e->getMessage() . "\n");
    } finally {
        // Close the connection
        pg_close($conn);
    }
}

// Execute the reset function
resetDatabase();
?>