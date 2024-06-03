<?php
include "db_conn.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    // 사용자 이메일 또는 이름이 세션에 없으면 access_failed.html로
    header("Location: access_failed.html");
    exit();
}

$email = $_SESSION['email'];
$username = $_SESSION['username'];

// 사용자가 작성한 게시물 목록을 가져옴
$sql = "SELECT id, title, author, created_at FROM posts WHERE author = '$username'";
$result = mysqli_query($conn, $sql);
$rows = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>마이페이지 게시판</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .post-link {
            color: #000;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .post-link:hover {
            color: #ff0000;
        }
        
        .not-link {
            text-align: center;
        }
        
        .username-container {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container input[type="button"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container input[type="button"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="username-container">
        <?php echo htmlspecialchars($username); ?>
    </div>
    <h1>마이페이지 게시판</h1>
    <div class="button-container">
        <input type="button" value="게시판으로 돌아가기" onclick="window.location.href='board.php'">
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
        </tr>
        <?php
        if (empty($rows)) {
            echo "<tr><td colspan='4'>작성한 게시물이 없습니다.</td></tr>";
        } else {
            foreach ($rows as $post) {
                echo "<tr>";
                echo "<td>{$post['id']}</td>";
                echo "<td><a href='get_board_content.php?id={$post['id']}' class='post-link'>{$post['title']}</a></td>";
                echo "<td>{$post['author']}</td>";
                echo "<td>{$post['created_at']}</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>
</html>
