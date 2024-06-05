# 📌 감정 픽스 📌

### 1. 일기를 작성
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
### 5. 추천된 영화 제목 선택
  - 제목에서 영화 제목 검색 창 혹은 영화 홈페이지로 이동할 수 있도록 __하이퍼링크__ 기능 제공
