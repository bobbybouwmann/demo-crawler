<?php

namespace App\Console\Commands;

use App\Jobs\Crawler;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CrawlWebsites extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:websites {--website= : The website you want to crawl}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl websites. If the website option is set we only crawl one website';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websites = $this->getWebsitesForCrawler();

        foreach ($websites as $website) {
            $this->info('Crawling :' . $website);
            
            $this->dispatch(new Crawler($website));
        }
    }

    /**
     * Get all the sites we need to crawl.
     *
     * @return array
     */
    protected function getWebsitesForCrawler()
    {
        if ($website = $this->option('website')) {
            $this->websiteExists($website);

            return [$website];
        }

        return $this->getAllWebsites();
    }

    /**
     * Check if the website exists in the config.
     *
     * @param $website
     * @return bool
     */
    protected function websiteExists($website)
    {
        return in_array($website, $this->getAllWebsites());
    }

    /**
     * Get all the websites from the config.
     *
     * @return array
     */
    protected function getAllWebsites()
    {
        return config('websites');
    }
}
