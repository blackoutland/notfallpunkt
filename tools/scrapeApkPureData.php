<?php

use Symfony\Component\DomCrawler\Crawler;

require_once __DIR__ . '/../www/inc/bootstrap.php';

error_reporting(E_ALL);
ini_set("display_errors", "On");
$files   = glob(__DIR__ . '/dl/*.html');

function extractTextIfExists(Crawler $crawler, $cssSelector)
{
    try {
        $obj = $crawler->filter($cssSelector);
        if ($obj->count()) {
            return (string)$obj->text();
        }
    } catch (Exception $e) {
        echo 'ERROR (' . $cssSelector . '): ' . $e->getMessage() . "\n";
    }
    return null;
}

function extractHtmlIfExists(Crawler $crawler, $cssSelector)
{
    try {
        $obj = $crawler->filter($cssSelector);
        if ($obj->count()) {
            return (string)$obj->html();
        }
    } catch (Exception $e) {
        echo 'ERROR (' . $cssSelector . '): ' . $e->getMessage() . "\n";
    }
    return null;
}

foreach ($files as $file) {
    echo "Analyzing $file...\n";
    $html    = file_get_contents($file);
    $crawler = new Crawler($html);
    $appData = [
        'name'      => extractTextIfExists($crawler, 'div.detail_banner .apk_info .info .title_link'),
        'version'   => extractTextIfExists($crawler, 'div.detail_banner .apk_info .info .details_sdk span'),
        'date'      => extractTextIfExists($crawler, 'div.detail_banner .apk_info .info p.date'),
        'shortDesc' => extractTextIfExists($crawler, '.content .translate-content div div .description-short'),
        'longDesc'  => null,
        'ts'        => time()
    ];

    // Get long description
    /** @var DomElement $pTags */
    $longDescLines = [];
    $pTags         = $crawler->filter('.content .translate-content div div p');
    foreach ($pTags as $pTag) {
        $line            = trim(strip_tags($pTag->textContent));
        $longDescLines[] = $line;
    }
    $appData['longDesc'] = trim(implode("\n", $longDescLines));

    // Get original URL
    // <meta property="og:url" content="https://apkpure.com/de/offline-organic-maps-hike-bike/app.organicmaps" />
    $metas    = $crawler->filterXPath('//head/meta');
    $metaTags = $metas->extract(['property', 'content']);
    foreach ($metaTags as $metaTag) {
        if ($metaTag[0] === 'og:url') {
            $appData['url'] = $metaTag[1];
            break;
        }
    }

    //dump($appData);
    $outputFile = '/temp/apkpure-out-'.preg_replace("/\.html$/i", "", basename($file)).'.json';
    file_put_contents($outputFile, json_encode($appData, JSON_PRETTY_PRINT));
    echo ("DONE $file >> $outputFile\n");
}