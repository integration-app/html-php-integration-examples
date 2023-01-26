<?php

use Firebase\JWT\JWT;

function getIntegrationAppToken(): string
{
  // Generating a token to access Integration.app API on behalf of your User
  
  // Your workspace key and secret.
  // You can find them on the Settings page.
  $secret = '{WORKSPACE_SECRET}';
  $key = '{WORKSPACE_KEY}';
  
  $payload = [
      'id' => "{USER_ID}",  // Identifier of user or organization.
      'name' => "{USER_NAME}", // Human-readable name (it will simplify troubleshooting)
      'fields' => (object)[
        'apiKey' => "my_api_key",
      ], //.... (optional) Any user fields you want to attach to your user.
      'iss' => $key,
      'exp' => date(strtotime('+4 hours')), // To prevent token from being used for too long
  ];
    return JWT::encode($payload, $secret, 'HS256');
};
