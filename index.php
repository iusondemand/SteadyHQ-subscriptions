<?php
# just as easy as it seems. Do not use in production, it is not still safe as it should
session_start();

// Replace xxx with your actual client ID, secret, and redirect URI

$clientId = 'xxx';
$clientSecret = 'xxx';
$redirectUri = 'https://xxx/callback.php'; // Update this to your redirect URI


$scope = 'read'; // Define the scope as needed

// Step 1: Redirect to the authorization URL
if (!isset($_GET['code'])) {
    $authUrl = "https://steadyhq.com/oauth/authorize?response_type=code&client_id=$clientId&redirect_uri=" . urlencode($redirectUri) . "&scope=" . urlencode($scope);
    header('Location: ' . $authUrl);
    exit;
}
?>
<a href="index.php">Login with Steady</a>
