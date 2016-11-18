<?php
namespace App\Lib;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;

/**
 * Class Client
 * @package App\Lib
 */
class Client
{
    /** @var \GuzzleHttp\Client Client */
    protected $client;

    /** @var CookieJar */
    protected $cookiesJar;

    /** @var  Response */
    protected $response;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        if (!$this->client instanceof \GuzzleHttp\Client) {
            $this->client = new \GuzzleHttp\Client([
                'base_uri' => Constants::URL_BASE,
                'cookies' => true
            ]);
        }
    }

    /**
     * Login
     */
    public function login()
    {
        echo "Logging in ...\n";

        if (!Constants::USER_NAME || !Constants::PASSWORD) {
            echo "Username or password are empty, please enter them in the Constants.php";
            die(1);
        }

        try {
            $this->response = $this->client->request('POST', Constants::URL_LOGIN, [
                'form_params' => [
                    Constants::USER_NAME_FIELD => Constants::USER_NAME,
                    Constants::PASSWORD_FIELD => Constants::PASSWORD,
                ],
            ]);
        } catch (ClientException $e) {
            $this->response = $e->getResponse();
        }

        return $this->response->getBody()->getContents();
    }

    /**
     * Get the issues home page.
     *
     * @param $url
     * @return mixed|null|\Psr\Http\Message\ResponseInterface
     */
    public function getIssuesHomePage($url)
    {
        try {
            $this->response = $this->client->request('GET', $url);
        } catch (ClientException $e) {
            $this->response = $e->getResponse();
        }

        return $this->response->getBody()->getContents();
    }

    /**
     * Get page.
     *
     * @param $pageLink
     *
     * @return Response
     */
    public function getPage($pageLink)
    {
        return $this->client->request('GET', $pageLink);
    }
}