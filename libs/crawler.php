<?php
header('Content-type:application/json;charset=utf-8');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
ob_start(); // Start output buffering


//base url
include("get_config.php");

// Create a DOM object
$html = new simple_html_dom();
// Load HTML from a string
$html->load($str);
$count = 0;
$autos = 0;
$merk_naam = "";
// foreach($html->find('img') as $element) echo $element->src . '<br />';

echo "[";
$arrayt = $html->find('div[class=row]');
$last = count($arrayt);



foreach ($html->find('div[class=row]') as $ts) {
    echo '{';
    $numItems = count($ts);
    $autos++;
    foreach ($ts->find('div[class=columnPhoto]') as $pho_c) {
        foreach ($pho_c->find('a') as $a) {
            $d_url= 'https://voorraadmodule.vwe-advertentiemanager.nl'.$a->href;
            echo '"details":"' . $d_url . '", ';
            foreach ($a->find('img') as $photo) {
                $photo_url = $photo->getAttribute('src');
                //$photo_url = str_replace("100/", "320/", $photo_url);
                echo '"icon":"https://voorraadmodule.vwe-advertentiemanager.nl' . $photo_url . '", ';
            }
        }
    }
    foreach ($ts->find('div[class=columnPrice]') as $priceDiv) {
        foreach ($priceDiv->find('div[class=price]') as $innerpriceDiv) {
            foreach ($innerpriceDiv->find('span[class=price_with_currency]') as $prijs) {
                $prijs = preg_replace('/[^0-9\.]/', '', $prijs->plaintext);
                echo '"price":"' . $prijs . '", ';
            }
        }
    }
    foreach ($ts->find('div[class=columnMain]') as $tf) {
        foreach ($tf->find('a[class=vehicle-list-title]') as $topM) {
            foreach ($topM->find('span') as $spanTi) {
                echo '"title":"' . $spanTi->plaintext . '", ';
                $merk_naam = explode(' ', trim($spanTi->plaintext))[0];
                echo '"merk":"' . $merk_naam . '", ';
            }
        }

        foreach ($tf->find('dl[class=specs]') as $span) {
            $count++;
            $dataArray = explode('| ', $span->plaintext);
            echo '"versnel":"' . $dataArray[3] . '", ';
            $kilometer = preg_replace('/[^0-9\.]/', '', $dataArray[1]);
            echo '"kilometer":"' . $kilometer . '", ';
            $bouwjaar = preg_replace('/[ ]{1,}/', '', $dataArray[0]);

            echo '"jaar":"' . $bouwjaar . ' ",';
            if (!empty($dataArray[4])) {
                echo '"brandstof":"' . $dataArray[4] . '", ';
            }
            echo '"inrichting":"' . $dataArray[2] . '"';
          //  echo '"kleur":"' . $dataArray[3] . '", ';
        }
    }




    $count = 0;

    if ($autos == $last) {
        echo '}';
    } else {
        echo '},';
    }
}

echo "]";


$list = ob_get_contents(); // Store buffer in variable

ob_end_clean(); // End buffering and clean up

echo $list; // will contain the contents
