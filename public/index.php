<?php
/**
 * Start page of the departure monitor. It simply renders the templates/index.html
 * Twig template.
 */

require_once __DIR__ . '/../util.php';

// render template with the given context of variables
echo $twig->render('index.html', [
    'display_weather' => (isset($_GET['weather'])) ? $_GET['weather'] === 'true'  : false,
    'stops'           => getDepartures($client),
    'marquee'         => randomMarquee(),
    'weather'         => getWeather($client),
]);
