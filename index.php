<?php
include 'simple_html_dom.php'; // Gọi Class Html Dom 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
function save_image($url, $save_path) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_COOKIEFILE => "onamae.txt",
        CURLOPT_COOKIEJAR => "onamae.txt",
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'
  ),
));

$image_data = curl_exec($curl);

curl_close($curl);
    file_put_contents($save_path, $image_data);
}
$time = time();
$url = 'https://dataz.vn/includes/verifyimage.php';
$save_path = '1.png';

save_image($url, $save_path);


//Đăng ký để theo dõi mình tại: https://www.youtube.com/@kodoku169

$filepath = '1.png';

$timeStamp = round(microtime(true) * 1000);

$url = "https://lens.google.com/v3/upload?hl=en-VN&re=df&stcs=$timeStamp&ep=subb";

$boundary = "----WebKitFormBoundary";

$contentFile = file_get_contents($filepath);

$postData = "--" . $boundary . "\r\n";
$postData .= 'Content-Disposition: form-data; name="encoded_image"; filename="download.jpg"' . "\r\n";
$postData .= 'Content-Type: image/jpeg' . "\r\n\r\n";
$postData .= $contentFile . "\r\n";
$postData .= "--" . $boundary . "--\r\n";

//post lên lens url
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: multipart/form-data; boundary=' . $boundary,
    'Content-Length: ' . strlen($postData),
    'Referer: https://lens.google.com/',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
]);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

$response = curl_exec($curl);
curl_close($curl); // đóng curl

preg_match('#\"\,\[\[\[(.*?)\]\]\,\"#', $response, $all_text);

$allText = []; //array
if(isset($all_text[1])) $allText = json_decode('['.$all_text[1].']', true);

if(empty($allText)) {
    echo json_encode([
        'failed' => 'Không thể nhận dạng được văn bản'
    ]);
    exit;
}

$newText = implode(' ', $allText); // array to string

echo json_encode([
    'result' => $newText,
]);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dataz.vn/register.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_COOKIEFILE => "onamae.txt",
        CURLOPT_COOKIEJAR => "onamae.txt",
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: vi,fr-FR;q=0.9,fr;q=0.8,en-US;q=0.7,en;q=0.6',
    'cookie: ',
    'priority: u=0, i',
    'sec-ch-ua: "Chromium";v="124", "Google Chrome";v="124", "Not-A.Brand";v="99"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: none',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$_csrf_token = str_get_html($response) -> find("input[name=token]", 0) -> value;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dataz.vn/register.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_COOKIEFILE => "onamae.txt",
        CURLOPT_COOKIEJAR => "onamae.txt",
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'token='.$_csrf_token.'&register=true&firstname='.$time.'&lastname='.$time.'&email='.$time.'@gmail.com&country-calling-code-phonenumber=84&phonenumber='.$time.'&companyname=&address1=&address2=&city=&state=&postcode=&country=VN&password=A'.$time.'&password2=A'.$time.'&code='.$newText.'&accepttos=on',
  CURLOPT_HTTPHEADER => array(
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: vi,fr-FR;q=0.9,fr;q=0.8,en-US;q=0.7,en;q=0.6',
    'cache-control: max-age=0',
    'content-type: application/x-www-form-urlencoded',
    'cookie: ',
    'origin: https://dataz.vn',
    'priority: u=0, i',
    'referer: https://dataz.vn/register.php',
    'sec-ch-ua: "Chromium";v="124", "Google Chrome";v="124", "Not-A.Brand";v="99"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: same-origin',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
unlink('onamae.txt');
