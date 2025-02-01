<?php
session_start();

// Replace these with your actual client ID, secret, and redirect URI
// do not use in production, it is not safe as it should
// please help: I can't get subscriptions from current user as it should: https://developers.steadyhq.com/#current-subscription


include("./config.php");


// Step 2: Handle the callback and exchange authorization code for access token
if (isset($_GET['code'])) {
    $code = $_GET['code'];
	$read = "read";
	
    // Prepare the POST request to exchange the code for an access token
    $tokenUrl = "https://steadyhq.com/api/v1/oauth/token";
    $postFields = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'authorization_code',
        'scope' => $read,
        'code' => $code,
        'redirect_uri' => $redirectUri,
    ];

    // Initialize cURL session
    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));

    // Execute the request and fetch response
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $tokenData = json_decode($response, true);

    if (isset($tokenData['access_token'])) {
        // Successfully received access token
        $accessToken = $tokenData['access_token'];
        echo "Access Token: " . htmlspecialchars($accessToken) . "<br>";
        
        // Optionally fetch user info using the access token
        // Here you would typically make another API call to fetch user details.
        
    } else {
        echo "Failed to obtain access token: " . htmlspecialchars($response);
    }
} else {
    echo "Authorization code not found.";
}



 





function getUserInfo($accessToken) {
    $url = "https://steadyhq.com/api/v1/users/me";
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken"
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}


function getSubscriptionStatus($accessToken) {
    $url = "https://steadyhq.com/api/v1/subscriptions/me"; // API endpoint
    $ch = curl_init($url);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken"
    ]);
    
    // Execute the request and fetch the response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP status code
    
    curl_close($ch);

    // Debugging: Log HTTP code and raw response (optional)
    echo "<li>HTTP Code: $httpCode<br>";
    echo "<li>Response: $response<br>";

    // Handle the response
    if ($httpCode === 200) {
        return json_decode($response, true); // Parse and return JSON response as an associative array
    } else {
        return null; // Return null if the request fails
    }
}





// Usage example

$userInfo 			= getUserInfo($accessToken);
$subscriptionStatus = getSubscriptionStatus($accessToken);



echo "<hr><p>Subscription Status: ";
print_r($subscriptionStatus);


echo "<hr><p>User Info: ";
print_r($userInfo);
