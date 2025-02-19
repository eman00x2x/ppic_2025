<?php

class DBMigration extends Database
{
    private $url  = "https://images.philproperties.ph/global_assets";

    function __construct($host, $db, $user, $pass, $dbPrefix)
    {
        $this->connector($host, $db, $user, $pass, $dbPrefix);
    }

    public function migrateTableUser()
    {
        $permissions['administrator'] = '{"accounts":["my_account","manage_accounts","add_accounts","edit_accounts","view_accounts","delete_accounts","download_accounts"],"properties":["add_properties","edit_properties","view_properties","set_category","set_status","delete","delete_property_image","download_properties"],"leads":["add_leads","edit_leads","view_leads","set_source","delete","download_leads"],"articles":["add_articles","edit_articles","view_articles","set_category","set_status","delete","download_articles"],"settings":["access_settings","update_system_settings","update_web_settings","update_data_privacy","update_terms","update_refund_policy","update_community_guidelines"],"traffics":["access_traffics","delete_traffics"],"database":["access_administration"]}';
        $permissions['regular'] = '{"accounts":["my_account","edit_accounts","view_accounts"],"properties":["add_properties","edit_properties","view_properties","set_category","set_status","delete","delete_property_image","download_properties"],"leads":["add_leads","edit_leads","view_leads","set_source","delete","download_leads"],"traffics":["access_traffics"]}';

        $result = $this->query("SELECT * FROM `#__users`");

        if($this->numRows($result) > 0) {
            while($line = $this->fetchAssoc($result)) {
                $mobile_number = "";
                if($line['contact_numbers'] != "") {
                    $contact_numbers = json_decode($line['contact_numbers'], true);
                    $mobile_number = $contact_numbers[0]["value"];
                }

				$export[] = [
                    "account_id" => $line['user_id'],
                    "photo" => $this->url . "/images/accounts/" . $line['photo'],
                    "names" => [
                        "first_name" => $line['firstname'],
                        "last_name" => $line['lastname'],
                        "middle_name" => $line['middlename']
                    ],
                    "username" => $line['username'],
                    "email" => $line['email'],
                    "password" => $line['password'],
                    "mobile_number" => $mobile_number,
                    "status" => $line['status'],
                    "account_type" => ($line['userlevel'] == 1) ? "Administrator" : "Registered User",
                    "permissions" => ($line['userlevel'] == 1) ? $permissions['administrator'] : $permissions['regular'],
                    "registered_at" => strtotime($line['date_created'])
                ];
			}

            $statement = "INSERT INTO `#__accounts` (`account_id`, `photo`, `names`, `username`, `email`, `password`, `mobile_number`, `status`, `account_type`, `permissions`, `registered_at`) VALUES \n";
            $statement .= $this->mapData($export);

            $this->download("accounts.sql", $statement);

        }

    }

    function migrateTableProperties()
    {
        $result = $this->query("SELECT * FROM `#__listings`");

        if($this->numRows($result) > 0) {
            while($line = $this->fetchAssoc($result)) {
                $export[] = [
                    "property_id" => $line['listing_id'],
                    "account_id" => $line['user_id'],
                    "featured" => ($line['featured'] == 2 ? 0 : 1),
                    "listing_type" => $line['type'],
                    "property_type" => "Residential",
                    "foreclosure" => 0,
                    "service_type" => "general brokerage",
                    "name" => $line['name'],
                    "title" => $line['title'],
                    "tags" => [$line['quality']],
                    "long_desc" => str_replace(["'", "\n", "\r"], ["", "", ""], $line['long_desc']),
                    "category" => $line['category'],
                    "address" => [
                        "barangay" => "",
                        "municiplaity" => $line['city'],
                        "province" => "",
                        "region" => "",
                        "street" => $line['street'],
                        "village" => ""
                    ],
                    "price" => $line['price'],
                    "reservation" => $line['reservation'],
                    "payment_details" => [
                        "option_money_duration" => "",
                        "payment_mode" => "",
                        "tax_allocation" => "",
                        "bank_loan" => false,
                        "pagibig_loan" => false,
                        "assume_balance" => false
                    ],
                    "floor_area" => $line['floor_area'],
                    "lot_area" => $line['lot_area'],
                    "bedroom" => $line['bedroom'],
                    "bathroom" => $line['bathroom'],
                    "parking" => $line['parking'],
                    "thumb_img" => $this->url . "/images/properties/" . $line['thumb_img'],
                    "videos" => [],
                    "amenities" => [],
                    "other_details" => [
                        "authority_type" => "Non-Exclusive Authority To Sell",
                        "authority_to_sell_expiration" => 0,
                        "owned_by" => $line['ownedby'],
                        "contact_details" => $line['contactdetails'],
                        "exact_address" => $line['exactaddress'],
                    ],
                    "created_at" => strtotime($line['date_added']),
                    "modified_at" => strtotime($line['last_modified']),
                    "status" => $line['status'],
                    "duration" => 0,
                    "post_score" => 0,
                    "documents" => []
                ];
            }

            $statement = "INSERT INTO `#__properties` (`property_id`, `account_id`, `featured`, `listing_type`, `property_type`, `foreclosure`, `service_type`, `name`, `title`, `tags`, `long_desc`, `category`, `address`, `price`, `reservation`, `payment_details`, `floor_area`, `lot_area`, `bedroom`, `bathroom`, `parking`, `thumb_img`, `videos`, `amenities`, `other_details`, `created_at`, `modified_at`, `status`, `duration`, `post_score`, `documents`) VALUES\n";
            $statement .= $this->mapData($export);

            $this->download("properties.sql", $statement);
        }

    }

    function migrateTablePropertyImages()
    {
        $result = $this->query("SELECT * FROM `#__listing_images`");

        if($this->numRows($result) > 0) {
            while($line = $this->fetchAssoc($result)) {
                $export[] = [
                    "image_id" => $line['image_id'],
                    "property_id" => $line['property_id'],
                    "filename" => $line['filename'],
                    "width" => 0,
                    "height" => 0,
                    "url" => $line['url']
                ];
            }

            $statement = "INSERT INTO `#__property_images` (`image_id`, `property_id`, `filename`, `width`, `height`, `url`) VALUES\n";
            $statement .= $this->mapData($export);

            $this->download("property_images.sql", $statement);
        }
    }

    function migrateTableArticles()
    {
        $result = $this->query("SELECT * FROM `#__articles`");

        if($this->numRows($result) > 0) {
            while($line = $this->fetchAssoc($result)) {
                $export[] = [
                    "article_id" => $line['article_id'],
                    "category" => "News",
                    "title" => $line['title'],
                    "name" => $line['name'],
                    "content" => str_replace(["'", "\n", "\r"], ["\"", "", ""], $line['content']),
                    "is_published" => 1,
                    "created_by" => $line['posted_by'],
                    "modified_by" => $line['posted_by'],
                    "created_at" => strtotime($line['date_created']),
                    "modified_at" => strtotime($line['date_created'])
                ];
            }

            $statement = "INSERT INTO `#__articles` (`article_id`, `category`, `title`, `name`, `content`, `is_published`, `created_by`, `modified_by`, `created_at`, `modified_at`) VALUES\n";
            $statement .= $this->mapData($export);

            $this->download("articles.sql", $statement);
        }
    }

    function migrateTableLeads()
    {
        $result = $this->query("SELECT * FROM `#__leads`");
        if($this->numRows($result) > 0) {
            while($line = $this->fetchAssoc($result)) {
                $export[] = [
                    "lead_id" => $line['lead_id'],
                    "account_id" => $line['user_id'],
                    "name" => $line['firstname'] . " " . $line['lastname'],
                    "email" => $line['email'],
                    "contact_number" => $line['phone'],
                    "message" => str_replace(["'", "\n", "\r"], ["\"", "", ""], $line['message']),
                    "source" => $line['source'],
                    "reference" => $line['reference'],
                    "requirements" => $line['requirements'],
                    "description" => $line['description'],
                    "created_at" => strtotime($line['dateadded'])
                ];
            }

            $statement = "INSERT INTO `#__leads` (`lead_id`, `account_id`, `name`, `email`, `contact_number`, `message`, `source`, `reference`, `requirements`, `description`, `created_at`) VALUES\n";
            $statement .= $this->mapData($export);

            $this->download("leads.sql", $statement);
        }
    }

    function mapData($data_array)
    {
        foreach($data_array as $item) {
            $statement[] = "(" . implode(",", array_map(function($item) {
                if(is_array($item)) {
                    $item = json_encode($item);
                }
                return "'" . $item . "'";
            }, $item)) . "),";
        }

        return implode("\n", $statement);
    }

    function download($file, $data)
    {
        // Force download the CSV file
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $file . '"');
		header('Pragma: no-cache');
		header('Expires: 50');
		echo $data;
    }
}