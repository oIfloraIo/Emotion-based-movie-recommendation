<?php
include "db_conn.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 디버깅
error_log("Session variables: " . var_export($_SESSION, true));

if (!isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
    echo json_encode(["error" => "로그인이 필요합니다."]);
    exit();
}

$username = $_SESSION['username'];
$type = $_SESSION['user_type'];

// MySQL 쿼리 디버깅
$query = "
    SELECT emotion 
    FROM posts
    WHERE author = ?
    ORDER BY created_at DESC LIMIT 1";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    error_log("MySQL prepare statement error: " . $conn->error);
    echo json_encode(["error" => "Database query preparation failed."]);
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($emotion);
$stmt->fetch();
$stmt->close();

// 쿼리 결과 디버깅
error_log("Queried emotion: " . $emotion);

if (!$emotion) {
    echo json_encode(["error" => "감정 데이터를 찾을 수 없습니다."]);
    exit();
}

// POST 요청 데이터 디버깅
$postData = json_decode(file_get_contents("php://input"), true);
error_log("POST data: " . json_encode($postData));

if (!isset($postData['emotion']) || !isset($postData['type'])) {
    echo json_encode(["error" => "요청 데이터가 올바르지 않습니다."]);
    exit();
}

$emotion = $postData['emotion'];
$type = $postData['type'];

// API 요청 데이터 디버깅
$requestData = [
    "type" => $type,
    "emotion" => $emotion
];

error_log("API Request data: " . json_encode($requestData));

$apiUrl = 'https://mvrecommendapp.azurewebsites.net/api/RecommendMovies?code=4VnoHjDQOkKLvSvTpm-M3pY25pBKDwnNda4kSIoKCvn_AzFu-1TGjQ%3D%3D';

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($requestData),
    ],
];

$context  = stream_context_create($options);
$response = @file_get_contents($apiUrl, false, $context);

if ($response === FALSE) {
    $error = error_get_last();
    error_log("API request failed. Error: " . var_export($error, true));
    echo json_encode(["error" => "영화 추천 API 요청에 실패했습니다.", "details" => $error['message']]);
    exit();
}

// API 응답 디버깅
error_log("API response: " . $response);

$recommendedMovies = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON decode error: " . json_last_error_msg());
    echo json_encode(["error" => "Invalid JSON response from API"]);
    exit();
}

if (!is_array($recommendedMovies) || empty($recommendedMovies['titles']) || empty($recommendedMovies['homepages'])) {
    echo json_encode(["error" => "추천된 영화가 없습니다."]);
    exit();
}

// 영화 제목과 홈페이지를 JSON 형식으로 반환
header('Content-Type: application/json');
echo json_encode([
    "titles" => $recommendedMovies['titles'],
    "homepages" => $recommendedMovies['homepages']
]);

$conn->close();
?>
