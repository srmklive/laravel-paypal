<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

use GuzzleHttp\Utils;

trait Identity
{
    private function mockShowProfileInfoResponse(): array
    {
        return Utils::jsonDecode('{
  "user_id": "https://www.paypal.com/webapps/auth/identity/user/mWq6_1sU85v5EG9yHdPxJRrhGHrnMJ-1PQKtX6pcsmA",
  "name": "identity test",
  "given_name": "identity",
  "family_name": "test",
  "payer_id": "WDJJHEBZ4X2LY",
  "address": {
    "street_address": "1 Main St",
    "locality": "San Jose",
    "region": "CA",
    "postal_code": "95131",
    "country": "US"
  },
  "verified_account": "true",
  "emails": [
    {
      "value": "user1@example.com",
      "primary": true
    }
  ]
}', true);
    }

    private function mockCreateMerchantApplicationResponse(): array
    {
        return Utils::jsonDecode('{
  "client_id": "AeTeCqaPp7JZBfUUb2d21cQ2KqyQGVhonfiUOJu99kgLhFFSrE59ruvhLOT4K3NzQoErgsUH6MY9uRqD",
  "client_secret": "cf136dc3c1fc93f31185e5885805d",
  "client_id_issued_at": 2893256800,
  "client_secret_expires_at": 2893276800,
  "redirect_uris": [
    "https://example.com/callback",
    "https://example.com/callback2"
  ],
  "grant_types": [
    "authorization_code",
    "refresh_token"
  ],
  "client_name": "AGGREGATOR",
  "logo_uri": "https://example.com/logo.png",
  "contacts": [
    "facilitator@example.com",
    "merchant@example.com"
  ],
  "policy_uri": "https://example.com/policyuri",
  "tos_uri": "https://example.com/tosuri",
  "scope": "profile email address",
  "token_endpoint_auth_method": "client_secret_basic",
  "jwks_uri": "https://example.com/my_public_keys.jwks"
}', true);
    }
}
