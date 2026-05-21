<?php
/**
 * OOP Database Setup - Now uses Database::createSchema()
 * Run: php create_db.php (sekali saja)
 */

require_once 'config/database.php';

echo "🚀 Setting up OOP Database Schema...\n\n";

$result = Database::createSchema();

if ($result['success']) {
    echo "✅ " . $result['message'] . "\n";
    echo "✅ Default admin: username='admin', password='password'\n\n";
} else {
    echo "❌ Error: " . $result['error'] . "\n";
}

echo "🎉 Ready! Open: http://localhost/LATIHAN/index.php\n";
echo "💡 Run seed_data.php for demo products.\n";
echo "📱 Login as admin/password to test.\n";
?>

