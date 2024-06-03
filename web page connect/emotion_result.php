<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['emotion_result'])) {
    header("Location: write_post.php");
    exit();
}

$emotion_data = json_decode($_SESSION['emotion_result'], true);
$emotions = explode(', ', $emotion_data['emotion']);
$emotion = htmlspecialchars($emotions[0]); // 첫 번째 감정만 선택
$ment = htmlspecialchars($emotion_data['ment']);

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT type FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $type = htmlspecialchars($row['type']);
        $_SESSION['user_type'] = $type;
    } else {
        $_SESSION['user_type'] = 'default';
    }
} else {
    $_SESSION['user_type'] = 'default';
}

unset($_SESSION['emotion_result']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>오늘의 감정 분석 결과</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
        .emotion {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button-group {
            margin-top: 20px;
        }
        input[type="button"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="button"]:hover {
            background-color: #0056b3;
        }
        .movie-list {
            list-style-type: none;
            padding: 0;
            text-align: left;
            max-width: 600px;
            margin: 0 auto;
        }
        .movie-list li {
            background-color: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>오늘의 감정 분석 결과</h1>
    <p>유저 타입: <?php echo htmlspecialchars($_SESSION['user_type']); ?></p>
    <p>감정 분석 결과: <?php echo htmlspecialchars($emotion); ?></p>
    <p class="emotion">감정 결과: <?php echo htmlspecialchars($emotion); ?></p>
    <p class="emotion">감정 분석 멘트: <?php echo htmlspecialchars($ment); ?></p>
    <div class="button-group">
        <input type="button" value="영화 추천 받기" onclick="getRecommendations()">
        <input type="button" value="뒤로가기" onclick="goBack()">
    </div>
    <h2>영화 추천 목록</h2>
    <ul class="movie-list" id="movie-list">
        <!-- 추천 영화 목록이 여기에 표시됩니다 -->
    </ul>
    <script>
        function goBack() {
            window.location.href = 'board.php'; // 뒤로가기 버튼을 클릭하면 board.php로 이동
        }

        function getRecommendations() {
            var emotion = "<?php echo $emotion; ?>";
            var type = "<?php echo $_SESSION['user_type']; ?>";
            
            console.log("Emotion:", emotion);
            console.log("Type:", type);

            fetch('recommend_movies.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ emotion: emotion, type: type })
            })
            .then(response => response.text())
            .then(text => {
                console.log('API Response Text:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('API Response JSON:', data);

                    var movieList = document.getElementById('movie-list');
                    movieList.innerHTML = '';

                    if (data.error) {
                        var li = document.createElement('li');
                        li.textContent = data.error;
                        movieList.appendChild(li);
                    } else if (Array.isArray(data) && data.length > 0) {
                        data.forEach(movie => {
                            var li = document.createElement('li');
                            li.innerHTML = `<strong>${movie}</strong>`;
                            movieList.appendChild(li);
                        });
                    } else {
                        var li = document.createElement('li');
                        li.textContent = '추천된 영화가 없습니다.';
                        movieList.appendChild(li);
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    var movieList = document.getElementById('movie-list');
                    movieList.innerHTML = '<li>추천된 영화를 가져오는 중 오류가 발생했습니다.</li>';
                }
            })
            .catch(error => {
                console.error('Error fetching movie recommendations:', error);
                var movieList = document.getElementById('movie-list');
                movieList.innerHTML = '<li>추천된 영화를 가져오는 중 오류가 발생했습니다.</li>';
            });
        }
    </script>
</body>
</html>
