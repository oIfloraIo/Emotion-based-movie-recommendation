# 📌 감정 Picks! 📌
>본 프로젝트는 사용자가 **작성한 일기를 분석하여 감정을 positive/negative를 판단**하고 분석된 결과에 따라 **영화 추천**이 가능하도록한다.
>>영화 추천 시에는 negative 감정이 나타날 때, 슬픈 느낌이 드는 영화 추천을 원치 않는 다면, 해당 부분을 반영하여 추천이 가능토록한다.

[실행 영상](https://youtu.be/2mlTB_skqE4)

---
## 주요 로직
### 1. 일기를 작성
<img width="400" alt="스크린샷 2024-06-08 오후 4 35 24" src="https://github.com/oIfloraIo/Emotion-based-movie-recommendation/assets/102645357/b9b48119-8448-402d-9883-1abdc3114da5">

### 2. 작성된 일기 분석, 감정 파악

### 3. 파악된 감정에 대해 영화 장르를 매칭
  - 매칭되는 장르
    1. positive : ['Action', 'Adventure', 'Science Fiction', 'Fantasy', 'Animation', 'Family', 'Comedy', 'Romance', 'Music', 'Documentary', 'TVMovie']
    2. negative : ['Animation', 'Family', 'Comedy', 'Music', 'TVMovie']
### 4. 개인별 유형에 맞춰 영화 추천
  - 개인별 유형
    1. type 1 : 자신의 감정과 무관하게 영화 장르를 봐도 되는 유형
    2. type 2 : 자신의 감정이 negative 일 때, 밝은 영화를 보고 싶은 유형
  - 영화 추천
    1. type 1 ) 전체 영화 데이터 셋에서 인기도와 평점을 기반으로 상위 값 파악, 10개의 영화 추천
    2. type 2 ) 사용자의 감정에 매칭되는 장르에 한하여 인기도와 평점 기반으로 상위 값 파악, 10개의 영화 추천
<img width="400" alt="스크린샷 2024-06-08 오후 4 36 05" src="https://github.com/oIfloraIo/Emotion-based-movie-recommendation/assets/102645357/9c741e72-2d35-4247-a582-54000e9f8a1e">

### 5. 추천된 영화 제목 선택
  - 제목에서 영화 제목 검색 창 혹은 영화 홈페이지로 이동할 수 있도록 __하이퍼링크__ 기능 제공

__1-5 단계에 걸쳐서 개인별 일기에 맞춰진 영화 추천이 이뤄진다__

[파일 공유 드라이브](https://drive.google.com/drive/folders/1xcN94-NKcXJCZDwve91HSPjvob1ljC04?usp=drive_link)

*해당 공유 드라이브에는 영화 추천 로직 구성 코드와 데이터셋 / npm 파일 생성 코드를 비롯해 함수 앱 배포시 필요한 파일(MyFunctionApp.zip)과 전체 웹 페이지 구성 코드들(aicloud.zip)이 zip 파일 업로드되어 있음*

---
## 영화 추천 로직 관련 내용 정리
### 영화 추천 로직 소개

- 활용 데이터 : kaggle
[영화 데이터셋](https://www.kaggle.com/datasets/tmdb/tmdb-movie-metadata/data)

#### 데이터 셋 handling
> Kaggle 제공 데이터셋을 추천 프로그램에 적합하도록 재가공 과정을 거침
1. tmdb_5000_movies.csv와 tmdb_5000_credits.csv 파일을 읽어와 title 열을 기준으로 병합
2. 필요한 부분만 추출 / JSON 데이터를 파싱하여 리스트로 변환 / 결측치 처리
3. 장르 별 추천이 이뤄지기에, 장르 별 인기도 계산
4. 영화의 장르와 평점을 결합한 문자열을 TF-IDF 벡터화한 후, 코사인 유사도를 계산 (코사인 유사도 계산 시, 계산되었던 인기도를 활용)
5. 장르, 평점, 인기도를 기반한 유사도 결과값은 npy 파일, 데이터셋 파일 재가공한 파일은 csv 파일로 저장

#### 추천 로직 설명
> 위의 영화 리스트를 추출하는 부분은 Azure Function App 에서 작동하도록 함
1. 전달 받을 감정과 장르를 매칭
2. 전달 받은 타입 별로 감정을 고려해야하는 지 나눔
3. 감정 고려가 필요한 타입
   - 전달받은 감정과 장르 매칭되는 영화들을 필터링
   - 지정된 영화와 유사도 행렬을 통해 총 10개의 영화 추천
4.  감정 고려가 필요하지 않은 타입
     - 평점/인기도 기반 계산 결과 바탕으로 10개의 영화 추천

**영화 추천 알고리즘은 movie-recommendation [폴더](https://github.com/oIfloraIo/Emotion-based-movie-recommendation/tree/main/movie-recommendation) 내에 업로드되어있음**
- data-process.py : 데이터셋 handling과 유사도 결과 유도 코드
- function_app.py : 영화 추천 로직

##### function_app.py를 AZURE FUNCTION APP push 내용
> AZURE FUNCTION APP 연동 방법 간략히 정리 (본인은 로컬 환경에서 코드 작성 후 push)
- Azure 에 가입을 하고, 터미널에서 azure core tools 를 터미널에 설치한다
  [Azure Core Tool 설치](https://learn.microsoft.com/ko-kr/azure/azure-functions/functions-run-local?tabs=macos%2Cisolated-process%2Cnode-v4%2Cpython-v2%2Chttp-trigger%2Ccontainer-apps&pivots=programming-language-python)
  해당 페이지를 잘 따라가다가며 먼저, 함수 앱을 만들어 준다.
- 그 다음 로컬에 MyFunctionApp 폴더가 생성되는 데, 거기에 있는 파이썬 파일의 코드를 원하는 대로 수정하면 된다.
     - 추가적으로 활용될 csv, npy 파일도 해당 폴더에 넣어서 한번에 push 해줬다.

`az login` 으로 로그인을 하고

`func start` 는 테스트 실행

`func azure functionapp publish yourfunctionapp` 으로 배포(재배포~~수정 시~~) 해주었다.

---
## 웹 페이지 구성 관련 내용 정리
**웹 페이지 구성**
- 로그인 페이지
- 회원가입 페이지
- 게시글 확인 페이지
- 마이 페이지 (회원정보[회원 유형, 비밀번호] 수정 / 자신의 게시물 삭제 기능)
- 일기 업로드 페이지 (업로드된 일기 텍스트 분석 -> 감정 파악 / 파악된 감정으로 영화 추천까지)

[관련 코드](https://github.com/oIfloraIo/Emotion-based-movie-recommendation/tree/main/web%20page%20connect)

### 전달 받은 코드 내용 정리
__일단 코드는 제공하지 않음 제공되는 코드는 내가 주요 내용 추가하여 수정된 경우의 코드만 제공__

> 5/19 전달

전달받은 코드는

1. 로그인 페이지 (Login_page.html / Login_page.php / Login.php / Logout.php)
2. 회원가입 페이지 (Join_page.html / Join.php)
   - 아이디 / 이름 중복 확인 기능 (check_duplicate_name.php / check_duplicate.php)
3. access fail 페이지 (access_failed.html)
4. 게시물 작성 페이지 (board_page.php / board.php)
   - 작성 기능 (write_post_page.php / write_post.php / write.php / write_failed.html)
   - 자신의 작성물 확인 기능 (my_posts.php)

이 제공되어있다.

<img width="350" alt="스크린샷 2024-06-07 오후 12 43 32" src="https://github.com/oIfloraIo/Emotion-based-movie-recommendation/assets/102645357/0f14ea5e-2aa0-4c77-9e7c-ecb40052ccb0">


> 5/30 전달

추가/수정된 코드 파일 (작동되는 부분)
- 게시물 작성 후, 감정 분석이 이뤄지는 페이지
    (write_post_page.php)

오류/정상 작동이 안되는 파일
- emotion_result.php , recommend_movies.php , Join.php / Join_page.html

<img width="350" alt="스크린샷 2024-06-07 오후 2 27 19" src="https://github.com/oIfloraIo/Emotion-based-movie-recommendation/assets/102645357/18e400e3-36be-4cc5-9685-4e5171701d43">

emotion_result.php 와 recommend_movies.php에서 확인된

기존 제공받은 코드에서 type 선언이 통일되지 않아 입력값 전달이 Null 값이 전달되었던 문제,

함수 앱 api 연결이 안되어 있어 결과값이 불러와지지 않는 문제에 대해 수정하여 업로드 함.
