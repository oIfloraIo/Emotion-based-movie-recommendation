<?php
include "db_conn.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: access_failed.html");
    exit();
}

$email = $_SESSION['email'];
$username = $_SESSION['username'];
$type = isset($_SESSION['signup_values']['type']) ? $_SESSION['signup_values']['type'] : 1;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>계정 정보 수정</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group {
            width: 100%;
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container input[type="button"], .button-container input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
        }

        .button-container input[type="button"]:hover, .button-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .radio-group {
            display: flex;
            justify-content: space-around;
        }

        .radio-label {
            margin-left: 5px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>계정 정보 수정</h1>
        <form method="POST" action="update_account.php">
            <div class="form-group">
                <label for="email">이메일:</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="username">사용자 이름:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="new_password">새 비밀번호:</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            <div class="form-group">
                <label>어떤 영화 취향을 가지고 있나요?</label>
                <div class="radio-group">
                    <div class="form-group">
                        <input type="radio" id="type_1" name="type" value="1" <?php echo ($type == 1) ? 'checked' : ''; ?>>
                        <label for="type_1" class="radio-label">어떤 영화든 다 좋아요 !!</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" id="type_2" name="type" value="2" <?php echo ($type == 2) ? 'checked' : ''; ?>>
                        <label for="type_2" class="radio-label">밝은 장르의 영화 추천을 원해요 !!</label>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <input type="submit" value="수정">
                <input type="button" value="마이페이지로 돌아가기" onclick="window.location.href='mypage.php'">
            </div>
        </form>
    </div>
</body>
</html>
