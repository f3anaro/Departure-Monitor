<?php
/**
 * Little utility script. It boots the environment and defines
 * some neat helper functions.
 */

// include composer's autoloader. With this, you do not need to include all the PHP files
// explicitly. They will be autoloaded for you
require_once __DIR__ . '/vendor/autoload.php';

// include some namespaces, because we are too lazy to always type the full name.
use GuzzleHttp\Client;

// create HTTP client for sending HTTP requests
$client = new Client();

// initialize the template engine Twig
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig   = new Twig_Environment($loader, [
    // use cache directory to speed up the template rendering
    // 'cache' => __DIR__ . '/cache',
]);


/**
 * Generates a random marquee that will be displayed below the departure
 * screen.
 * 
 * @return string A meaningful message ;)
 */
function randomMarquee() {
    switch (rand(1,10)) {
        case 1:   return 'Simon, geh mal Bier holn !!!';
        case 2:   return 'Mops, wer hat die Gans gestohlen ?';
        case 3:   return 'Gl&uuml;ck Auf !!!';
        case 4:   return 'Socken wanted! Das Sockenmonster geht um...';
        case 5:   return 'TUUUUT TUUUT ... Zug im anrollen!';
        case 6:   return 'Willkommen in der besten WG diesseits der Galaxie!';
        case 7:   return 'Was geht aaab ???';
        case 8:   return 'Putzplan f&uuml;r kommende Woche: K&uuml;che: Basti, Bad: Basti, Flur: Basti';
        default:  return 'No news for today...';
    }
}


/**
 * Load departure times from an external webpage.
 * 
 * @param  Client  $client   GuzzleHttp 
 * @param  array   $streets list of streets names that should be requests
 * @param  integer $limit   How many entries per stop
 * @return array   Map with structure street => [
 *                     0 => line,
 *                     1 => direction,
 *                     2 => departure time in minutes,
 *                 ]
 */
function getDepartures(Client $client, $streets = ["Lene-Glatzer-Straße", "Jacobi-Straße"], $limit = 6) {
    // Make HTTP request to external website that returns a JSON list
    $request = $client->createRequest('GET', 'http://widgets.vvo-online.de/abfahrtsmonitor/abfahrten.do', [
        'query' => [
            'ort' => 'Dresden',
            'lim' => $limit,
        ],
    ]);
    $stops = [];

    foreach ($streets as $street) {
        $request->getQuery()->set('hst', $street);
        $response = $client->send($request);
        
        $stops[$street] = $response->json();
    }

    return $stops;
}


function getWeather(Client $client) {

    $response = $client->get('http://api.wetter.com/forecast/weather/city/DE0002265010/project/wetteranzeigewg/cs/dbc178aa617f85fd27676d00ae85332e');
    // parse the XML response
    $xml = $response->xml();
  

    // simple lookup table for times
    $times   = ['Früh', 'Mittag', 'Abend', 'Nacht'];
    $weather = [];

    for ($i = 0; $i < count($times); $i++) { 
        $weather[$times[$i]] = [
            'text'     => $xml->forecast->date[0]->time[$i]->w_txt,
            'wind'     => $xml->forecast->date[0]->time[$i]->ws,
            'max_temp' => $xml->forecast->date[0]->time[$i]->tx,
            'min_temp' => $xml->forecast->date[0]->time[$i]->tn,
            'rainfall' => $xml->forecast->date[0]->time[$i]->pc,
        ];
    }

    return $weather;
}
