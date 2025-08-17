<?php

namespace Modules\PageVault\App\Console;

use Illuminate\Console\Command;
use Modules\PageVault\App\Models\PageVault;

class LoadDefaultPages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'load:pages';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pages = [

            ["type" => PageVault::TYPE_ABOUT_US,
                "title" => "About Us",
                "slug" => "about",
                "meta" => [
                    "metaTitle" => "",
                    "keywords" => ["", ""],
                    "metaDescription" => ""],
                "files" => [
                    "main_image" => ""
                ]
            ],

            [
                "type" => PageVault::TYPE_TERMS_AND_CONDITIONS,
                "title" => "Terms And Conditions",
                "slug" => "terms-and-conditions",
                "meta" => [
                    "metaTitle" => "",
                    "keywords" => ["", ""],
                    "metaDescription" => ""],
                "files" => [
                    "main_image" => ""
                ]
            ],

            ["type" => PageVault::TYPE_PRIVACY_POLICY,
                "title" => "Privacy Policy",
                "slug" => "privacy",
                "meta" => [
                    "metaTitle" => "",
                    "keywords" => ["", ""],
                    "metaDescription" => ""],
                "files" => [
                    "main_image" => ""
                ]
            ],

            ["type" => PageVault::TYPE_INQUERY_AND_CANCELLATION,
                "title" => "Inquiry And Cancellation",
                "slug" => "inquiry-and-cancellation",
                "meta" => [
                    "metaTitle" => "",
                    "keywords" => ["", ""],
                    "metaDescription" => ""],
                "files" => [
                    "main_image" => ""
                ]
            ],

            ["type" => PageVault::TYPE_IMPACT,
                "title" => "Impact",
                "slug" => "impact",
                "meta" => [
                    "metaTitle" => "",
                    "keywords" => ["", ""],
                    "metaDescription" => ""],
                "files" => [
                    "main_image" => ""
                ]
            ],

            ["type" => PageVault::TYPE_PARTNER,
                "title" => "Partner With Us",
                "slug" => "partner-with-us",
                "meta" => [
                    "metaTitle" => "",
                    "keywords" => ["", ""],
                    "metaDescription" => ""],
                "files" => [
                    "main_image" => ""
                ]
            ],

            ["type" => PageVault::TYPE_SAFETY,
                "title" => "Safety",
                "slug" => "safety",
                "meta" => [
                    "metaTitle" => "",
                    "keywords" => ["", ""],
                    "metaDescription" => ""],
                "files" => [
                    "main_image" => ""
                ]
            ],
        ];

        foreach ($pages as $page) {
            PageVault::create($page);
        }

        $this->info('Default pages have been inserted successfully.');

    }
}
