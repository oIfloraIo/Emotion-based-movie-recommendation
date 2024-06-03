<?php
include "Login.php";

$_SESSION = array(); // 세션 변수 배열 초기화

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // 세션 종료

// JavaScript를 포함한 HTML 페이지로 리다이렉션
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>로그아웃</title>
    <script>
        alert("로그아웃 되었습니다!");
        window.location.href = "Login_page.html";
    </script>
</head>
<body>
</body>
</html>';
exit;
?>
