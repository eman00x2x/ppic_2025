<?php

define("DEFINITION", [
    /** USER PERMISSIONS */
    "accounts" => [
        "access" => "Allow this user to update the account details."
    ],
    "users" => [
        "access" => "Allow this user to create new user and update other users details in your account except administrator account.",
        "delete" => "Allow this user to permanently delete other user in your account except administrator account."
    ],
    "leads" => [
        "access" => "Allow this user to access the list of leads in your account.",
        "delete" => "Allow this user to permanently delete a lead in your account."
    ],
    "properties" => [
        "access" => "Allow this user to access the list of real estate properties posted in your account.",
        "delete" => "Allow this user to permanently delete a real estate property posted in your account."
    ],
    "premiums" => [
        "process_subscription" => "Allow this user to purchase a subscription for your account.",
    ],
    "transactions" => [
        "access" => "Allow this user to access the list of subscription purchase."
    ],

    /** PRIVILIGES */
    "max_post" => "The total number of postings this account can add.",
    "max_users" => "Total number of users this account can add.",
    "mls_access" => "Gives the account an access to MLS (Multiple Listing Service)",
    "chat_access" => "Gives the account an access to Chat Platform",
    "display_ads" => "Total number of display ad this account can create.",
    "featured_ads" => "Total number of featured ad this account can create.",
    "handshake_limit" => "Maximum number of handshake can initiate.",
    "comparative_analysis_access" => "Allow this user to access comparative analysis table.",

    /** USER PERMISSIONS */
    "ADMIN" => [
        "accounts" => [
            "access" => "Allow this user to access the accounts of others.",
            "add" => "Allow this user to create new account.",
            "edit" => "Allow this user to update the accounts of others.",
            "delete" => "Allow this user to permanently delete the accounts of others."
        ],
        "users" => [
            "access" => "Allow this user to access the users of others.",
            "edit" => "Allow this user to update the users of others.",
            "delete" => "Allow this user to permanently delete the users of others."
        ],
        "properties" => [
            "access" => "Allow this user to access the Properties Posted of others.",
            "edit" => "Allow this user to update the Properties Posted of others.",
            "delete" => "Allow this user to permanently delete the Properties Posted of others."
        ],
        "premiums" => [
            "access" => "Allow this user to access the Premiums.",
            "edit" => "Allow this user to update the Premium settings",
            "delete" => "Allow this user to permanently delete a Premium.",
            "process_subscription" => "Allow this user to process a Premium Subscription for other accounts."
        ],
        "settings" => [
            "access" => "Allow this user to access and update the Settings of the system."
        ],
        "web_settings" => [
            "access" => "Allow this user to access and update the WEB Settings of the website."
        ],
        "articles" => [
            "access" => "Allow this user to access and create Articles and post to website.",
            "edit" => "Allow this user to update the Articles posted in website.",
            "delete" => "Allow this user to permanently delete an Articles posted in website."
        ],
        "kyc" => [
            "access" => "Allow this user to access the KYC and process the verification of users."
        ],
        "transactions" => [
            "access" => "Allow this user to access the detailed report of all Transactions of users."
        ],
        "page_ads" => [
            "access" => "Allow this user to access and manage the Page Advertisements."
        ],
        "reports" => [
            "access" => "Show the link of report to this user, allowing to view the links of below reports if access is granted",
            "subscriber" => "Allow this user to access the total transactions per subscriber report.",
            "monthly_transaction" => "Allow this user to access the monthly transactions report."
        ]
    ]

]);