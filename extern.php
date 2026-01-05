<?php
// extern.php

function getFitnessNews() {
    $url = "http://feeds.bbci.co.uk/news/health/rss.xml";
    
    $html = "<h3>Știri Sănătate</h3><div class='news-box'>";
    
    if (!function_exists('curl_init')) {
        return $html . "<p>Modulul cURL nu este activ.</p></div>";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($data && $http_code == 200) {
        $xml = @simplexml_load_string($data);
        
        if ($xml && isset($xml->channel->item)) {
            $html .= "<ul>";
            $count = 0;
            foreach ($xml->channel->item as $item) {
                if ($count >= 3) break;
                
                $titlu = htmlspecialchars((string)$item->title);
                $link = htmlspecialchars((string)$item->link);
                
                $html .= "<li><a href='$link' target='_blank'>$titlu</a></li>";
                $count++;
            }
            $html .= "</ul>";
        } else {
            $html .= "<p>Informațiile nu pot fi procesate momentan.</p>";
        }
    } else {
        $html .= "<p>Sursa de știri este indisponibilă.</p>";
    }
    
    $html .= "</div>";
    return $html;
}
