<?php
include "db_conn.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT email, password, username, type FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
        $type = $row['type']; // 유저 타입 가져오기

        if (!isset($_SESSION['email'])) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $type; // 세션에 유저 타입 저장

            // 세션 스토리지에 username 저장하는 스크립트
            echo "<script>
                    sessionStorage.setItem('username', '$username');
                    window.location.href = 'login_success.html';
                  </script>";
        } else {
            $username = $_SESSION['username'];
            $email = $_SESSION['email'];
            echo "<script>
                    sessionStorage.setItem('username', '$username');
                    window.location.href = 'login_success.html';
                  </script>";
        }
    } else {
        ?>
        <h1> 로그인에 실패 하였습니다 </h1>
        <?php
        include "access_failed.html";
    }
}

$conn->close(); // 데이터베이스 연결 닫기
?>
<script>
    function goToBoardList() {           
        window.location.href = "board.php";   
    }
    function logout() {
        window.location.href = "Logout.php";
    }
</script>
