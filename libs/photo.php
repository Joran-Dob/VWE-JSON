<?php
header('Content-type:application/json;charset=utf-8');
$d_url = $_GET["url"];




//$d_url2 = str_replace("/details.html", "/foto.html", $d_url);

$curl2 = curl_init();
curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl2, CURLOPT_HEADER, false);
curl_setopt($curl2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl2, CURLOPT_URL, $d_url2);
curl_setopt($curl2, CURLOPT_REFERER, $d_url2);
curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
$d_c = curl_exec($curl2);
curl_close($curl2);

// Create a DOM object
$html_details = new simple_html_dom();
// Load HTML from a string
$html_details->load($d_c);
echo "[";
foreach ($html_details->find('ul') as $p_div) {
    $photo_n = 0;



    foreach ($p_div->find('li') as $photo_a) {
        $last = count($p_div->find('img'));
        echo $last;
        $photo_n = $photo_n+1;
        print($photo_a);
        foreach ($photo_a->find('img') as $pic_itm) {
            echo '{';
            echo '"photo":"https://voorraadmodule.vwe-advertentiemanager.nl' . $pic_itm->src . '"';

            if ($photo_n==$last) {
                echo '}';
            } else {
                echo '},';
            }
        }
    }
}
  echo "]";
