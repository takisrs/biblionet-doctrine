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

    function __construct($username, $password, $log = [Logger::SUCCESS, Logger::ERROR, Logger::INFO, Logger::WARNING], $timeout = 10)
    {

        $this->apiUsername = $username;
        $this->apiPassword = $password;

        $this->client = new Client([
            'base_uri' => 'https://biblionet.gr/wp-json/biblionetwebservice/',
            'timeout'  => $timeout
        ]);

        $this->logger = new Logger($log);
    }

    public function getFetchedItems()
    {
        return $this->fetchedItems;
    }

    public function fetch($date)
    {
        $page = 0;
        $results_per_page = 50;

        $date = $date ?: date("Y-m");

        $date = new \DateTime($date);
        $year = $date->format('Y');
        $month = $date->format('m');

        while (true) {
            $page++;
            $fetchedBooks = $this->_fetchBooksByMonth($year, $month, $results_per_page, $page);

            if ($fetchedBooks && is_array($fetchedBooks)) {
                $this->fetchedItems = array_merge($this->fetchedItems, $fetchedBooks);
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
            $this->fetch($dt->format("Y-m"));
        }

        return $this;
    }

    public function filter($field, $value, $operator = "=="){
        $totalCount = count($this->fetchedItems);
        $filteredCount = $totalCount;
        if ($totalCount > 0){      
            if (property_exists($this->fetchedItems[0], $field)){
                $this->fetchedItems = array_filter($this->fetchedItems, function($item) use($field, $value, $operator){
                    return Helper::compare($item->$field, $value, $operator);
                });
                $filteredCount = count($this->fetchedItems);
                $this->logger->log(Logger::INFO, 'filter', 'filter by '.$field.$operator.$value, 'filtered:'.$filteredCount.'/'.$totalCount);
            }
        }


        return $this;
    }

    private function _fetchAssociations($options, $id)
    {

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
            $this->logger->log(Logger::ERROR, 'api', 'ClientException', $e->getMessage());
        } catch (ConnectException $e) {
            $this->logger->log(Logger::ERROR, 'api', 'ConnectException', $e->getMessage());
        }
    }


    public function fill($types)
    {

        if (!is_array($types)) $types = [$types];

        if (count($this->fetchedItems) == 0) {
            $this->logger->log(Logger::WARNING, 'api', 'fill', 'no items');
            return $this;
        }

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
                $this->logger->log(Logger::ERROR, 'api', 'fill', 'wrong input => '.$type);
                continue;
            }

            try {
                $total = count($this->fetchedItems);
                $counter = 0;
                foreach ($this->fetchedItems as $key => $item) {
                    $counter++;
                    $this->logger->log(Logger::INFO, 'api', "fetch ".$type." ".$counter."/".$total, $item->TitlesID, Helper::getPercentage($counter, $total));
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
                $this->logger->log(Logger::ERROR, 'api', 'ClientException', $e->getMessage());
            } catch (ConnectException $e) {
                $this->logger->log(Logger::ERROR, 'api', 'ConnectException', $e->getMessage());
            }
        }

        return $this;
    }


    private function _fetchBookById($id)
    {
        $this->logger->log(Logger::INFO, 'api', "fetch", "book:".$id);

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
            $this->logger->log(Logger::ERROR, 'api', 'ClientException', $e->getMessage());
        } catch (ConnectException $e) {
            $this->logger->log(Logger::ERROR, 'api', 'ConnectException', $e->getMessage());
        }
    }


    private function _fetchBooksByMonth($year, $month, $titles_per_page, $page)
    {
        try {
            $this->logger->log(Logger::INFO, 'api', 'fetch', $month . '/' . $year . ' : ' . $titles_per_page * ($page - 1) . '=>' . $titles_per_page * $page);

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

            $body = $response->getBody();

            $result = $body->getContents();

            return Helper::isJson($result) ? json_decode($result)[0] : NULL;
        } catch (ClientException $e) {
            $this->logger->log(Logger::ERROR, 'api', 'ClientException', $e->getMessage());
        } catch (ConnectException $e) {
            $this->logger->log(Logger::ERROR, 'api', 'ConnectException', $e->getMessage());
        }
    }
}
}
