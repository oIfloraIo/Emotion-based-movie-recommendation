# 📌 감정 Picks! 📌

### 1. 일기를 작성
<img width="561" alt="스크린샷 2024-06-08 오후 4 35 24" src="https://github.com/oIfloraIo/Emotion-based-movie-recommendation/assets/102645357/b9b48119-8448-402d-9883-1abdc3114da5">


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
    <img width="641" alt="스크린샷 2024-06-08 오후 4 36 05" src="https://github.com/oIfloraIo/Emotion-based-movie-recommendation/assets/102645357/9c741e72-2d35-4247-a582-54000e9f8a1e">

### 5. 추천된 영화 제목 선택
  - 제목에서 영화 제목 검색 창 혹은 영화 홈페이지로 이동할 수 있도록 __하이퍼링크__ 기능 제공

__1-5 단계에 걸쳐서 개인별 일기에 맞춰진 영화 추천이 이뤄진다__


