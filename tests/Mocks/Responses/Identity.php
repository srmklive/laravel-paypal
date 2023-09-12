<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

use GuzzleHttp\Utils;

trait Identity
{
    private function mockShowProfileInfoResponse(): array
    {
        return Utils::jsonDecode('{
  "address": {
    "street_address": "7917394 Annursnac Hill Road Unit 0C",
    "locality": "Ventura",
    "region": "CA",
    "postal_code": "93003",
    "country": "US"
  }
}', true);
    }

    private function mocklistUsersResponse(): array
    {
        return Utils::jsonDecode('{
  "schemas": [
    "http://example.com"
  ],
  "startIndex": 1,
  "itemsPerPage": 1,
  "totalResults": 5000,
  "Resources": [
    {
      "schemas": [
        "http://example.com"
      ],
      "externalId": "string",
      "userName": "string",
      "name": {
        "familyName": "string",
        "givenName": "string",
        "middleName": "string",
        "honorificPrefix": "string",
        "honorificSuffix": "string"
      },
      "active": true,
      "emails": [
        {
          "type": "work",
          "primary": true,
          "value": "string"
        }
      ],
      "phoneNumbers": [
        {
          "value": "string",
          "type": "work",
          "primary": true
        }
      ],
      "addresses": [
        {
          "streetAddress": "string",
          "locality": "string",
          "region": "string",
          "postalCode": "string",
          "type": "work",
          "country": "string"
        }
      ],
      "entitlements": [
        {
          "value": "string"
        }
      ],
      "id": "string",
      "meta": {
        "resourceType": "User",
        "location": "http://example.com",
        "created": "string",
        "lastModified": "string"
      },
      "preferredLanguage": "string",
      "timezone": "string"
    }
  ]
}', true);
    }

    private function mocklistUserResponse(): array
    {
        return Utils::jsonDecode('{
  "schemas": [
    "http://example.com"
  ],
  "externalId": "string",
  "userName": "string",
  "name": {
    "familyName": "string",
    "givenName": "string",
    "middleName": "string",
    "honorificPrefix": "string",
    "honorificSuffix": "string"
  },
  "active": true,
  "emails": [
    {
      "type": "work",
      "primary": true,
      "value": "string"
    }
  ],
  "phoneNumbers": [
    {
      "value": "string",
      "type": "work",
      "primary": true
    }
  ],
  "addresses": [
    {
      "streetAddress": "string",
      "locality": "string",
      "region": "string",
      "postalCode": "string",
      "type": "work",
      "country": "string"
    }
  ],
  "entitlements": [
    {
      "value": "string"
    }
  ],
  "id": "string",
  "meta": {
    "resourceType": "User",
    "location": "http://example.com",
    "created": "string",
    "lastModified": "string"
  },
  "preferredLanguage": "string",
  "timezone": "string"
}', true);
    }
}
