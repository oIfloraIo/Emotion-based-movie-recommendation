# 영화 추천 로직 소개

- 활용 데이터 : kaggle
[영화 데이터셋](https://www.kaggle.com/datasets/tmdb/tmdb-movie-metadata/data)

## 데이터 셋 handling
> Kaggle 제공 데이터셋을 추천 프로그램에 적합하도록 재가공 과정을 거침
1. tmdb_5000_movies.csv와 tmdb_5000_credits.csv 파일을 읽어와 title 열을 기준으로 병합
2. 필요한 부분만 추출 / JSON 데이터를 파싱하여 리스트로 변환 / 결측치 처리
3. 장르 별 추천이 이뤄지기에, 장르 별 인기도 계산
4. 영화의 장르와 평점을 결합한 문자열을 TF-IDF 벡터화한 후, 코사인 유사도를 계산 (코사인 유사도 계산 시, 계산되었던 인기도를 활용)
5. 장르, 평점, 인기도를 기반한 유사도 결과값은 npy 파일, 데이터셋 파일 재가공한 파일은 csv 파일로 저장

## 추천 로직 간단한 설명
> 위의 영화 리스트를 추출하는 부분은 Azure Function App 에서 작동하도록 함
1. 전달 받을 감정과 장르를 매칭
2. 전달 받은 타입 별로 감정을 고려해야하는 지 나눔
3. 감정 고려가 필요한 타입
   - 전달받은 감정과 장르 매칭되는 영화들을 필터링
   - 지정된 영화와 유사도 행렬을 통해 총 10개의 영화 추천
4.  감정 고려가 필요하지 않은 타입
     - 평점/인기도 기반 계산 결과 바탕으로 10개의 영화 추천

### 해당 function_app.py를 AZURE FUNCTION APP push
> AZURE FUNCTION APP 연동 방법 간략히 정리 (본인은 로컬 환경에서 코드 작성 후 push)
- Azure 에 가입을 하고, 터미널에서 azure core tools 를 터미널에 설치한다
  [Azure Core Tool 설치](https://learn.microsoft.com/ko-kr/azure/azure-functions/functions-run-local?tabs=macos%2Cisolated-process%2Cnode-v4%2Cpython-v2%2Chttp-trigger%2Ccontainer-apps&pivots=programming-language-python)
  해당 페이지를 잘 따라가다가며 먼저, 함수 앱을 만들어 준다.
- 그 다음 로컬에 MyFunctionApp 폴더가 생성되는 데, 거기에 있는 파이썬 파일의 코드를 원하는 대로 수정하면 된다.
     - 추가적으로 활용될 csv, npy 파일도 해당 폴더에 넣어서 한번에 push 해줬다.

`az login` 으로 로그인을 하고

`func start` 는 테스트 실행

`func azure functionapp publish yourfunctionapp` 으로 계속 배포(재배포) 해주었다.
