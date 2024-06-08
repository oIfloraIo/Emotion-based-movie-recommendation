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

$update_message = "";
if (isset($_SESSION['update_message'])) {
    $update_message = $_SESSION['update_message'];
    unset($_SESSION['update_message']);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>마이페이지</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            padding: 20px;
            text-align: center;
        }

        .username-container {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .button-container input[type="button"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container input[type="button"]:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        window.onload = function() {
            var updateMessage = "<?php echo $update_message; ?>";
            if (updateMessage) {
                alert(updateMessage);
            }
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="username-container">
            <?php echo htmlspecialchars($username); ?>
        </div>
        <h1>마이페이지</h1>
        <div class="button-container">
            <input type="button" value="게시물 확인" onclick="window.location.href='my_posts.php'">
            <input type="button" value="메인 페이지로 돌아가기" onclick="window.location.href='board.php'">
            <input type="button" value="계정 정보 수정" onclick="window.location.href='edit_account.php'">
        </div>
    </div>
</body>
</html>
