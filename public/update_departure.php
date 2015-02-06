<?php
/**
 * This is the update script for the departure section of the departure monitor.
 * It will be requested by an AJAX call for a defined time interval
 */

require_once __DIR__ . '/../util.php';

// render template with the given context of variables
echo $twig->render('departure.html', [
  'stops'           => getDepartures($client),
]);
