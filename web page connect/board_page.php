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

// 로그인 유저가 작성한 게시물 목록을 가져옴
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
    <title>게시판</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
        }

        .header .username {
            font-size: 14px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .content {
            width: 80%;
            max-width: 1000px;
            text-align: center;
        }

        table {
            width: 100%;
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

        .button-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .button-container input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .input-container {
            display: flex;
            align-items: center;
        }

        .input-container input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .input-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }

        .input-container button:hover {
            background-color: #45a049;
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
    </style>
</head>

<body>
    <div class="header">
        <div class="username">Logged in as: <?php echo htmlspecialchars($username); ?></div>
    </div>

    <div class="content">
        <h1>&#9997;게시판&#128172;</h1>
        <div class="button-container">
            <input type="submit" value="게시글 작성하기&#128221;" name="post" onclick="window.location.href='write_post_page.php'">
            <input type="submit" value="마이페이지로 이동" name="mypage" onclick="window.location.href='mypage.php'">
            <input type="submit" value="로그아웃" name="logout" onclick="window.location.href='logout.php'">
            <div class="input-container">
                <form action="get_board_search.php" name="find_title" method="post" autocomplete="off">
                    <input type="text" id="find_title" name="find_title" required placeholder="찾고싶은 게시물 제목">
                    <input type="submit" value="찾기&#128269;">
                </form>
            </div>
        </div>
        <br>
        <table>
            <tr>
                <th></th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
            </tr>
            <?php
            if (empty($rows)) {
                echo "<h3 class='not-link'>&#128269; 해당 게시글을 찾을 수 없습니다. &#128531;</h3>";
            } else {
                foreach ($rows as $post) {
                    $postId = $post['id'];
                    echo "<tr onclick='goToBoard({$postId})'>";
                    echo "<td><a href='get_board_content.php?id={$post['id']}' class='post-link'>{$post['id']}</a></td>";
                    echo "<td>{$post['title']}</td>";
                    echo "<td>{$post['author']}</td>";
                    echo "<td>{$post['created_at']}</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
    <script>
        function goToBoard(postId) {
            window.location.href = 'get_board_content.php?id=' + postId;
        }
        
        function goToLogin() {
            window.location.href = 'login_page.html';
        }
    </script>
</body>
</html>
