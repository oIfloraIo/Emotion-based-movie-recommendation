<?php
include "db_conn.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    
    // 사용자의 이름으로 이메일을 찾기 위한 SQL 쿼리
    $sql = "SELECT email, password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $password = $row['password'];

        // 이메일과 비밀번호를 표시하는 HTML 출력
        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>아이디/비밀번호 찾기 결과</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 0;
                    background-color: #f3f3f3;
                }
                .container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    width: 400px;
                    height: 200px;
                    border: 1px solid #ccc;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h2 {
                    text-align: center;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 18px;
                    margin: 5px 0;
                }
                .button-container {
                    margin-top: 20px;
                }
                .button-container input[type="button"] {
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    cursor: pointer;
                    font-size: 16px;
                }
                .button-container input[type="button"]:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>아이디/비밀번호 찾기 결과</h2>
                <p>이메일: ' . htmlspecialchars($email) . '</p>
                <p>비밀번호: ' . htmlspecialchars($password) . '</p>
                <div class="button-container">
                    <input type="button" value="로그인 페이지로 돌아가기" onclick="window.location.href=\'login_page.html\'">
                </div>
            </div>
        </body>
        </html>';
    } else {
        // 사용자를 찾을 수 없을 때 메시지 출력
        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>아이디/비밀번호 찾기 결과</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 0;
                    background-color: #f3f3f3;
                }
                .container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    width: 400px;
                    height: 200px;
                    border: 1px solid #ccc;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h2 {
                    text-align: center;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 18px;
                    margin: 5px 0;
                }
                .button-container {
                    margin-top: 20px;
                }
                .button-container input[type="button"] {
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    cursor: pointer;
                    font-size: 16px;
                }
                .button-container input[type="button"]:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>아이디/비밀번호 찾기 결과</h2>
                <p>사용자를 찾을 수 없습니다.</p>
                <div class="button-container">
                    <input type="button" value="로그인 페이지로 돌아가기" onclick="window.location.href=\'login_page.html\'">
                </div>
            </div>
        </body>
        </html>';
    }
}
?>
