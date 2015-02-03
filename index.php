<?php
/**
 * Start page of the departure monitor. It simply renders the templates/index.html
 * Twig template.
 */

require_once __DIR__ . '/util.php';

// render template with the given context of variables
echo $twig->render('index.html', [
    'stops'   => getDepartures($client),
    'marquee' => randomMarquee(),
    'weather' => getWeather($client),
]);
