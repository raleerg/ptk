<?php
namespace App;

use App\Lib\Client;
use App\Helper\Helper;
use App\Lib\Constants;
use Symfony\Component\DomCrawler\Crawler;
use FPDF;

class Ptk
{
    /** @var  Client */
    public $client;

    /** @var string */
    public $responseHTML;

    /** @var integer */
    public $issueNumber;

    /** @var string */
    public $issueLink;

    /** @var  Crawler */
    public $allPages;

    /** @var integer */
    public $numOfPages;

    /** @var FPDF */
    public $fpdf;

    /**
     * Generate kindle file.
     */
    public function generateKindleFile()
    {
        ini_set('memory_limit', '-1');
        $this->client = new Client();
        $this->responseHTML = $this->client->login();
        $this->getLastIssueUrl();
        $this->grabPages();
        $this->downloadAllPages();
        //$this->createPdf();
    }

    /**
     * Get latest issues url
     */
    public function getLastIssueUrl()
    {
        $crawler = new Crawler($this->responseHTML);
        $this->issueLink = $crawler->filter('div.thumbnail a')->first()->attr('href');
        $this->issueNumber = Helper::getBetween($this->issueLink, '-', '.');
    }

    /**
     * Get the number of pages.
     */
    public function grabPages()
    {
        $issuesHomePageHTML = $this->client->getIssuesHomePage($this->issueLink);
        $file = 'debug.txt';
        file_put_contents($file, $issuesHomePageHTML);

        $crawler = new Crawler($issuesHomePageHTML);
        $this->allPages = $crawler->filter('div.page-thumbnail img');
        if (empty($this->allPages)) {

        }

    }

    /**
     * Download all pages.
     *
     * @return boolean
     */
    public function downloadAllPages()
    {
        if (!$this->prepareDownload()) {
            echo "Folder exists \n";
        }

        $this->allPages->each(function (Crawler $node, $i) {
            /** @var string $imageURL */
            $imageURL = $node->attr('src');
            $imageURL = str_replace('pageThumb', 'page', $imageURL);
            $filePath = Constants::DOWNLOAD_FOLDER . $this->issueNumber . '/' . ($i + 1) . '.jpeg';
            if (!file_exists($filePath)) {
                $result = $this->client->getPage($imageURL);
                $fh = fopen($filePath, (file_exists($filePath)) ? 'a' : 'w');
                fwrite($fh, $result->getBody()->getContents());
                fclose($fh);
                echo $node->attr('src') . " downloaded \n";
            } else {
                echo $filePath . " exists, skipped \n";
            }

            $this->fpdf->AddPage();
            $this->fpdf->Image($filePath);

            unlink($filePath);
        });

        $this->fpdf->Output('F', Constants::DOWNLOAD_FOLDER . $this->issueNumber . '/' . $this->issueNumber . ".pdf");
    }

    /**
     * Prepare folders for download.
     *
     * @return bool
     */
    public function prepareDownload()
    {
        if (!file_exists(Constants::DOWNLOAD_FOLDER . $this->issueNumber)) {
            mkdir(Constants::DOWNLOAD_FOLDER . $this->issueNumber, 0777, true);

            return true;
        }

        $this->fpdf = new FPDF('P', 'cm', [52, 72]);
        $this->fpdf->SetTitle('Politika');
        $this->fpdf->SetMargins(0, 0);

        return false;
    }

}