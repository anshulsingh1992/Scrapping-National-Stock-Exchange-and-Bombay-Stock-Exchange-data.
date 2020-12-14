<?php
/*~ Scrapping Nationl Stock Exchange and Bombay Stock Exchange data.
.---------------------------------------------------------------------------.
|
|   Authors: Anshul Singh |
| ------------------------------------------------------------------------- |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
*/
include ('simple_html_dom.php');
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.bseindia.com/markets/Commodity/PolledSpotPrice.aspx");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response=curl_exec($ch);
curl_close($ch);
//echo $response;
$html= new simple_html_dom();
$html->load($response);

//echo $html->find('title',0)->plaintext;
/*$list = $html->find('table[class="mGrid"]',0);
$gold=$list->find('td',1);

foreach($list->find('td') as $element)
{
    if()
    echo $element->plaintext;
    echo "<br>";
}

*/
//following part is what i have changes made it dynamic for every value from tabel // so no need to take each td separately
$list = $html->find('table[class="mGrid"]',0);
$dataArray = array();
foreach($list->find('tr') as $element)
{
	$subDataArray = array();
	$cnt = 1;
	foreach ($element->find('td')  as $value) {
		$subDataArray[$cnt] = trim($value->plaintext);
		$cnt++;
	 }
	 $cnt = 1;

	 if(count($subDataArray) > 0)
	 	$dataArray[] = $subDataArray;

}
// here u will get all rows data in array
echo "<pre>";
print_r($dataArray);
echo "</pre>";

//following code will filter what we want for gold ot silver
// we could do this filteration part  above only but requirements can be change so better to take all data and do filteraton on it.
$outputArray = array();
foreach ($dataArray as $key => $value) {
	if($value[2] == "GOLD" || $value[2] == "SILVER"){
		//$sessionValue = (($key == 0) ? "one" : "two");
		//$inputArray[$value[2]][$sessionValue]= $value;
		$outputArray[$value[2]][]= $value;
	}
}

echo "final Array";
echo "<pre>";
print_r($outputArray);
echo "</pre>";
// insert data in database will come now  by using foreach to $outputArray // 0 key means session 1 and key 1 means session 2

$ch=curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www1.nseindia.com");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response=curl_exec($ch);
curl_close($ch);
echo $response;
$html= new simple_html_dom();
$html->load($response);

?>