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

// 로그인 페이지 이동
header("Location: Login_page.html");
exit;
?>