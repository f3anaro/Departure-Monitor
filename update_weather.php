<?php
/**
 * This is the update script for the weather section of the departure monitor.
 * It will be requested by an AJAX call for a defined time interval
 */

require_once __DIR__ . '/util.php';

// render template with the given context of variables
echo $twig->render('weather.html', [
    'weather' => getWeather($client),
]);
