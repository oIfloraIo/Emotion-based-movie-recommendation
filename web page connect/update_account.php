<?php
include "db_conn.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: access_failed.html");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $type = isset($_POST['type']) ? intval($_POST['type']) : 1;

    if (!empty($new_password)) {
        $sql = "UPDATE users SET password = '$new_password', type = '$type' WHERE username = '$username'";
    } else {
        $sql = "UPDATE users SET type = '$type' WHERE username = '$username'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['update_message'] = "계정 정보가 성공적으로 업데이트되었습니다.";
    } else {
        $_SESSION['update_message'] = "계정 정보 업데이트 중 오류가 발생했습니다: " . mysqli_error($conn);
    }

    // 업데이트 후 마이페이지로 리디렉션
    header("Location: mypage.php");
    exit();
} else {
    header("Location: mypage.php");
    exit();
}
?>
