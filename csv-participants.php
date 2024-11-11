<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/../../../cc-config.php';

$idEvent = !empty($_GET['id_event']) ? $_GET['id_event'] : null;
if (!empty($idEvent)) {
    $client = new \ChambeCarnet\WeezEvent\Api\Client();
    $utils = new \ChambeCarnet\Utils();
    $participants = $client->getParticipants(['id_event' => [$idEvent]]);
    if (!empty($participants)) {
       $utils->downloadParticipants($participants);
    }
}

?>