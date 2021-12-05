<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

use GuzzleHttp\Utils;

trait PartnerReferrals
{
    private function mockCreatePartnerReferralsResponse(): array
    {
        return Utils::jsonDecode('{
  "links": [
    {
      "href": "https://uri.paypal.com/v2/customer/partner-referrals/ZjcyODU4ZWYtYTA1OC00ODIwLTk2M2EtOTZkZWQ4NmQwYzI3RU12cE5xa0xMRmk1NWxFSVJIT1JlTFdSbElCbFU1Q3lhdGhESzVQcU9iRT0=",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://www.paypal.com/merchantsignup/partner/onboardingentry?token=ZjcyODU4ZWYtYTA1OC00ODIwLTk2M2EtOTZkZWQ4NmQwYzI3RU12cE5xa0xMRmk1NWxFSVJIT1JlTFdSbElCbFU1Q3lhdGhESzVQcU9iRT0=",
      "rel": "action_url",
      "method": "GET"
    }
  ]
}', true);
    }

    private function mockShowReferralDataResponse(): array
    {
        return Utils::jsonDecode('{
  "partner_referral_id": "ZjcyODU4ZWYtYTA1OC00ODIwLTk2M2EtOTZkZWQ4NmQwYzI3RU12cE5xa0xMRmk1NWxFSVJIT1JlTFdSbElCbFU1Q3lhdGhESzVQcU9iRT0=",
  "submitter_payer_id": "RFYUH2QQDGUQU",
  "referral_data": {
    "individual_owners": [
      {
        "names": [
          {
            "prefix": "Mr.",
            "given_name": "John",
            "surname": "Doe",
            "middle_name": "Middle",
            "suffix": "Jr.",
            "full_name": "John Middle Doe Jr.",
            "type": "LEGAL"
          }
        ],
        "citizenship": "US",
        "addresses": [
          {
            "address_line_1": "One Washington Square",
            "address_line_2": "Apt 123",
            "admin_area_2": "San Jose",
            "admin_area_1": "CA",
            "postal_code": "95112",
            "country_code": "US",
            "type": "HOME"
          }
        ],
        "phones": [
          {
            "country_code": "1",
            "national_number": "6692468839",
            "extension_number": "1234",
            "type": "MOBILE"
          }
        ],
        "birth_details": {
          "date_of_birth": "1955-12-29"
        },
        "type": "PRIMARY"
      }
    ],
    "business_entity": {
      "business_type": {
        "type": "INDIVIDUAL",
        "subtype": "ASSO_TYPE_INCORPORATED"
      },
      "business_industry": {
        "category": "1004",
        "mcc_code": "2025",
        "subcategory": "8931"
      },
      "business_incorporation": {
        "incorporation_country_code": "US",
        "incorporation_date": "1986-12-29"
      },
      "names": [
        {
          "business_name": "Test Enterprise",
          "type": "LEGAL_NAME"
        }
      ],
      "emails": [
        {
          "type": "CUSTOMER_SERVICE",
          "email": "customerservice@example.com"
        }
      ],
      "website": "https://mystore.testenterprises.com",
      "addresses": [
        {
          "address_line_1": "One Washington Square",
          "address_line_2": "Apt 123",
          "admin_area_2": "San Jose",
          "admin_area_1": "CA",
          "postal_code": "95112",
          "country_code": "US",
          "type": "WORK"
        }
      ],
      "phones": [
        {
          "country_code": "1",
          "national_number": "6692478833",
          "extension_number": "1234",
          "type": "CUSTOMER_SERVICE"
        }
      ],
      "beneficial_owners": {
        "individual_beneficial_owners": [
          {
            "names": [
              {
                "prefix": "Mr.",
                "given_name": "John",
                "surname": "Doe",
                "middle_name": "Middle",
                "suffix": "Jr.",
                "full_name": "John Middle Doe Jr.",
                "type": "LEGAL"
              }
            ],
            "citizenship": "US",
            "addresses": [
              {
                "address_line_1": "One Washington Square",
                "address_line_2": "Apt 123",
                "admin_area_2": "San Jose",
                "admin_area_1": "CA",
                "postal_code": "95112",
                "country_code": "US",
                "type": "HOME"
              }
            ],
            "phones": [
              {
                "country_code": "1",
                "national_number": "6692468839",
                "extension_number": "1234",
                "type": "MOBILE"
              }
            ],
            "birth_details": {
              "date_of_birth": "1955-12-29"
            },
            "percentage_of_ownership": "50"
          }
        ],
        "business_beneficial_owners": [
          {
            "business_type": {
              "type": "INDIVIDUAL",
              "subtype": "ASSO_TYPE_INCORPORATED"
            },
            "business_industry": {
              "category": "1004",
              "mcc_code": "2025",
              "subcategory": "8931"
            },
            "business_incorporation": {
              "incorporation_country_code": "US",
              "incorporation_date": "1986-12-29"
            },
            "names": [
              {
                "business_name": "Test Enterprise",
                "type": "LEGAL_NAME"
              }
            ],
            "emails": [
              {
                "type": "CUSTOMER_SERVICE",
                "email": "customerservice@example.com"
              }
            ],
            "website": "https://mystore.testenterprises.com",
            "addresses": [
              {
                "address_line_1": "One Washington Square",
                "address_line_2": "Apt 123",
                "admin_area_2": "San Jose",
                "admin_area_1": "CA",
                "postal_code": "95112",
                "country_code": "US",
                "type": "WORK"
              }
            ],
            "phones": [
              {
                "country_code": "1",
                "national_number": "6692478833",
                "extension_number": "1234",
                "type": "CUSTOMER_SERVICE"
              }
            ],
            "percentage_of_ownership": "50"
          }
        ]
      },
      "office_bearers": [
        {
          "names": [
            {
              "prefix": "Mr.",
              "given_name": "John",
              "surname": "Doe",
              "middle_name": "Middle",
              "suffix": "Jr.",
              "full_name": "John Middle Doe Jr.",
              "type": "LEGAL"
            }
          ],
          "citizenship": "US",
          "addresses": [
            {
              "address_line_1": "One Washington Square",
              "address_line_2": "Apt 123",
              "admin_area_2": "San Jose",
              "admin_area_1": "CA",
              "postal_code": "95112",
              "country_code": "US",
              "type": "HOME"
            }
          ],
          "phones": [
            {
              "country_code": "1",
              "national_number": "6692468839",
              "extension_number": "1234",
              "type": "MOBILE"
            }
          ],
          "birth_details": {
            "date_of_birth": "1955-12-29"
          },
          "role": "DIRECTOR"
        }
      ],
      "annual_sales_volume_range": {
        "minimum_amount": {
          "currency_code": "USD",
          "value": "10000"
        },
        "maximum_amount": {
          "currency_code": "USD",
          "value": "50000"
        }
      },
      "average_monthly_volume_range": {
        "minimum_amount": {
          "currency_code": "USD",
          "value": "1000"
        },
        "maximum_amount": {
          "currency_code": "USD",
          "value": "50000"
        }
      },
      "purpose_code": "P0104"
    },
    "email": "accountemail@example.com",
    "preferred_language_code": "en-US",
    "tracking_id": "testenterprices123122",
    "partner_config_override": {
      "partner_logo_url": "https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg",
      "return_url": "https://testenterprises.com/merchantonboarded",
      "return_url_description": "the url to return the merchant after the paypal onboarding process.",
      "action_renewal_url": "https://testenterprises.com/renew-exprired-url",
      "show_add_credit_card": true
    },
    "operations": [
      {
        "operation": "API_INTEGRATION",
        "api_integration_preference": {
          "classic_api_integration": {
            "integration_type": "THIRD_PARTY",
            "third_party_details": {
              "permissions": [
                "EXPRESS_CHECKOUT",
                "REFUND",
                "DIRECT_PAYMENT",
                "AUTH_CAPTURE",
                "BUTTON_MANAGER",
                "ACCOUNT_BALANCE",
                "TRANSACTION_DETAILS"
              ]
            },
            "first_party_details": "CERTIFICATE"
          },
          "rest_api_integration": {
            "integration_method": "PAYPAL",
            "integration_type": "THIRD_PARTY",
            "third_party_details": {
              "features": [
                "PAYMENT",
                "REFUND",
                "PARTNER_FEE"
              ]
            }
          }
        },
        "billing_agreement": {
          "description": "Billing Agreement Description Field",
          "billing_experience_preference": {
            "experience_id": "string",
            "billing_context_set": true
          },
          "merchant_custom_data": "PatnerMERCHANT23124",
          "approval_url": "wttps://www.paypal.com/agreements/approve?ba_token=BA-67944913LE886121E",
          "ec_token": "EC-1S970363DB536864M"
        }
      }
    ],
    "products": [
      "EXPRESS_CHECKOUT"
    ],
    "legal_consents": [
      {
        "type": "SHARE_DATA_CONSENT",
        "granted": true
      }
    ]
  },
  "links": [
    {
      "href": "https://uri.paypal.com/v2/customer/partner-referrals/ZjcyODU4ZWYtYTA1OC00ODIwLTk2M2EtOTZkZWQ4NmQwYzI3RU12cE5xa0xMRmk1NWxFSVJIT1JlTFdSbElCbFU1Q3lhdGhESzVQcU9iRT0=",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://www.paypal.com/merchantsignup/partner/onboardingentry?token=ZjcyODU4ZWYtYTA1OC00ODIwLTk2M2EtOTZkZWQ4NmQwYzI3RU12cE5xa0xMRmk1NWxFSVJIT1JlTFdSbElCbFU1Q3lhdGhESzVQcU9iRT0=",
      "rel": "action_url",
      "method": "GET"
    }
  ]
}', true);
    }
}
