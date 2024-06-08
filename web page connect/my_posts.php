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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
    $post_id = intval($_POST['delete_post_id']);
    $delete_sql = "DELETE FROM posts WHERE id = $post_id AND author = '$username'";
    if (mysqli_query($conn, $delete_sql)) {
        $_SESSION['delete_message'] = "삭제되었습니다!";
    } else {
        $_SESSION['delete_message'] = "삭제에 실패했습니다.";
    }
    header("Location: my_posts.php");
    exit();
}

// 사용자가 작성한 게시물 목록을 가져옴
$sql = "SELECT id, title, author, created_at FROM posts WHERE author = '$username'";
$result = mysqli_query($conn, $sql);
$rows = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// 삭제 메시지를 세션에서 가져오고 삭제
$delete_message = "";
if (isset($_SESSION['delete_message'])) {
    $delete_message = $_SESSION['delete_message'];
    unset($_SESSION['delete_message']);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>내 게시물</title>
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
            width: 90%;
            max-width: 800px;
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            color: green;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
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

        .button-container {
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
        function confirmDelete() {
            return confirm("정말 삭제하시겠습니까?");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>내 게시물</h1>
        <?php if ($delete_message): ?>
            <div class="message"><?php echo htmlspecialchars($delete_message); ?></div>
        <?php endif; ?>
        <div class="button-container">
            <input type="button" value="마이페이지로 돌아가기" onclick="window.location.href='mypage.php'">
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>삭제</th>
            </tr>
            <?php
            if (empty($rows)) {
                echo "<tr><td colspan='5'>작성한 게시물이 없습니다.</td></tr>";
            } else {
                foreach ($rows as $post) {
                    echo "<tr>";
                    echo "<td>{$post['id']}</td>";
                    echo "<td><a href='get_board_content.php?id={$post['id']}' class='post-link'>{$post['title']}</a></td>";
                    echo "<td>{$post['author']}</td>";
                    echo "<td>{$post['created_at']}</td>";
                    echo "<td>
                        <form method='POST' style='margin: 0;' onsubmit='return confirmDelete();'>
                            <input type='hidden' name='delete_post_id' value='{$post['id']}'>
                            <input type='submit' value='삭제'>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</body>
</html>
