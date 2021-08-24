<?php
namespace DigitalStars\Sheets;

use Google\Service\Sheets\ValueRange;
use Google\Client as Google_Client;

class DSheets {
    private $service;
    private $spreadsheet_id;
    private $path_to_config;
    private $sheet;

    public static function create($spreadsheet_id, $path_to_config) {
        return new self($spreadsheet_id, $path_to_config);
    }

    public function __construct($spreadsheet_id, $path_to_config) {
        $this->spreadsheet_id = $spreadsheet_id;
        $this->path_to_config = $path_to_config;
        $this->service = $this->googleTableAuth();
    }

    public function setSheet($sheet) {
        $this->sheet = $sheet;
        return $this;
    }

    public function getService() {
        return $this->service;
    }

    public function setService($service) {
        return $this->service = $service;
    }

    public function get($range = '') {
        $range = $range ? "!$range" : '';
        return $this->service->spreadsheets_values->get($this->spreadsheet_id, $this->sheet.$range)['values'];
    }

    public function append($values, $range = '') {
        $range = $range ? "!$range" : '';
        $body = new ValueRange(['values' => $values]);
        $options = ['valueInputOption' => 'RAW'];
        $this->service->spreadsheets_values->append( $this->spreadsheet_id, $this->sheet.$range, $body, $options);
    }

    public function update($values, $range = '') {
        $range = $range ? "!$range" : '';
        $body = new ValueRange(['values' => $values]);
        $options = ['valueInputOption' => 'RAW'];
        $this->service->spreadsheets_values->update($this->spreadsheet_id, $this->sheet.$range, $body, $options);
    }

    private function googleTableAuth() { //получение объекта для работы с гугл таблицами
        putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . $this->path_to_config);
        $client = new Google_Client();
        $guzzleClient = new \GuzzleHttp\Client(['verify' => false]);
        $client->setHttpClient($guzzleClient);
        $client->useApplicationDefaultCredentials();
        $client->addScope( 'https://www.googleapis.com/auth/spreadsheets');
        return new \Google\Service\Sheets($client);
    }
}