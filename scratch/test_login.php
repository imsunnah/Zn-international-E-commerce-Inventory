<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$email = 'admin@ZnInternational.com';
$password = 'password'; // Assuming this is the password

echo "Testing login for $email...\n";

$user = User::where('email', $email)->first();
if (!$user) {
    echo "User not found!\n";
    exit;
}

echo "User found: " . $user->name . " (Role: " . $user->role . ", Active: " . $user->is_active . ")\n";

if (Auth::attempt(['email' => $email, 'password' => $password, 'is_active' => true])) {
    echo "Login successful!\n";
} else {
    echo "Login failed! Credentials or active status might be wrong.\n";
}
