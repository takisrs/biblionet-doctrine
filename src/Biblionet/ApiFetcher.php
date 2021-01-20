<?php

namespace Biblionet;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

use Biblionet\Helper;
use GuzzleHttp\Client;

class ApiFetcher
{
    private $apiUsername;
    private $apiPassword;

    private $logger;

    private $fetchedItems = [];

    function __construct($username, $password)
    {

        $this->apiUsername = $username;
        $this->apiPassword = $password;

        $this->client = new Client([
            'base_uri' => 'https://biblionet.gr/wp-json/biblionetwebservice/',
            'timeout'  => 10.0
        ]);

        $this->logger = new Logger(true);
    }

    public function getFetchedItems()
    {
        return $this->fetchedItems;
    }

    public function fetch($year, $month)
    {
        $page = 1;
        $results_per_page = 50;

        while (true) {
            $fetchedBooks = $this->_fetchBooksByMonth($year, $month, $results_per_page, $page);

            if ($fetchedBooks && is_array($fetchedBooks)) {
                $this->fetchedItems = array_merge($this->fetchedItems, $fetchedBooks);
                $page++;
                continue;
            } else {
                break;
            }
        }

        return $this;
    }

    public function fetchById($ids)
    {

        if (!is_array($ids)) $ids = [$ids];

        foreach ($ids as $id){
            $fetchedBooks = $this->_fetchBookById($id);
            if ($fetchedBooks && is_array($fetchedBooks)) {
                $this->fetchedItems = array_merge($this->fetchedItems, $fetchedBooks);
            }
        }

        return $this;
    }

    public function fetchByRange($from, $to = NULL)
    {
        $to = $to ?: date("Y-m");

        $start = new \DateTime($from);
        $start->modify('first day of this month');

        $end  = new \DateTime($to);
        $end->modify('first day of next month');

        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $this->fetch($dt->format("Y"), $dt->format("m"));
        }

        return $this;
    }

    private function _fetchAssociations($options, $id)
    {

        $this->logger->log('info', self::class, "start fetching " . $options['name'] . " for item " . $id);

        try {
            $response = $this->client->request('POST', $options['apiMethod'], [
                'form_params' => [
                    'username' => $this->apiUsername,
                    'password' => $this->apiPassword,
                    'title' => $id
                ]
            ]);

            $body = $response->getBody();

            $result = $body->getContents();

            return Helper::isJson($result) ? json_decode($result)[0] : NULL;
        } catch (ClientException $e) {
            $this->logger->log('error', self::class, $e->getMessage());
        } catch (ConnectException $e) {
            $this->logger->log('error', self::class, $e->getMessage());
        }
    }


    public function fill($types)
    {

        if (count($this->fetchedItems) == 0) {
            $this->logger->log('error', self::class, "No fetched items to fill");
            return $this;
        }

        if (!is_array($types)) $types = [$types];

        $availableTypes = [
            'contributors' => [
                'name' => 'contributors',
                'apiMethod' => 'get_contributors'
            ],
            'companies' => [
                'name' => 'companies',
                'apiMethod' => 'get_title_companies'
            ],
            'subjects' => [
                'name' => 'subjects',
                'apiMethod' => 'get_title_subject'
            ]
        ];

        foreach ($types as $type) {
            if (!in_array($type, array_keys($availableTypes))) {
                $this->logger->log('error', self::class, "Wrong fill type input: ", $type);
                continue;
            }

            try {
                foreach ($this->fetchedItems as $key => $item) {
                    $extraFields = $this->_fetchAssociations($availableTypes[$type], $item->TitlesID);

                    if ($extraFields && is_array($extraFields)) {

                        // remove unnecessary data
                        $extraFields = array_map(function($item) {
                            if (isset($item->TitlesID)) unset($item->TitlesID);
                            if (isset($item->Title)) unset($item->Title);
                            if (isset($item->Titles)) unset($item->Titles);
                            return $item;
                        }, $extraFields);

                        $this->fetchedItems[$key]->$type = $extraFields;
                    }
                }
            } catch (ClientException $e) {
                $this->logger->log('error', self::class, $e->getMessage());
            } catch (ConnectException $e) {
                $this->logger->log('error', self::class, $e->getMessage());
            }
        }

        return $this;
    }


    private function _fetchBookById($id)
    {
        $this->logger->log('info', self::class, "fetching book: " . $id);

        try {
            $response = $this->client->request('POST', 'get_title', [
                'form_params' => [
                    'username' => $this->apiUsername,
                    'password' => $this->apiPassword,
                    'title' => $id
                ]
            ]);

            $body = $response->getBody();

            $result = $body->getContents();

            return Helper::isJson($result) ? json_decode($result)[0] : NULL;
        } catch (ClientException $e) {
            $this->logger->log('error', self::class, $e->getMessage());
        } catch (ConnectException $e) {
            $this->logger->log('error', self::class, $e->getMessage());
        }
    }


    private function _fetchBooksByMonth($year, $month, $titles_per_page, $page)
    {
        try {
            $this->logger->log('info', self::class, $year . " - " . $month, "from " . $titles_per_page * ($page - 1) . " to " . $titles_per_page * $page . "");

            $response = $this->client->request('POST', 'get_month_titles', [
                'form_params' => [
                    'username' => $this->apiUsername,
                    'password' => $this->apiPassword,
                    'year' => $year,
                    'month' => $month,
                    'titles_per_page' => $titles_per_page,
                    'page' => $page
                ]
            ]);

            //$code = $response->getStatusCode(); // 200
            //$reason = $response->getReasonPhrase(); // OK

            // Get all of the response headers.
            /*foreach ($response->getHeaders() as $name => $values) {
                echo $name . ': ' . implode(', ', $values) . "\r\n";
            }*/

            $body = $response->getBody();

            $result = $body->getContents();

            return Helper::isJson($result) ? json_decode($result)[0] : NULL;
        } catch (ClientException $e) {
            echo $e->getMessage();
        } catch (ConnectException $e) {
            echo $e->getMessage();
        }
    }
}
