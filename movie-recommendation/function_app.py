import logging
import pandas as pd
import numpy as np
import azure.functions as func
import random
import json

# 감정을 장르로 매핑하는 함수
def emotion_genre_mapping(emotion):
    emotion_genre_mapping = {
        'positive': ['Action', 'Adventure', 'Science Fiction', 'Fantasy', 'Animation', 'Family', 'Comedy', 'Romance', 'Music', 'Documentary', 'TVMovie'],
        'negative': ['Animation', 'Family', 'Comedy', 'Music', 'TVMovie']
    }
    return emotion_genre_mapping.get(emotion, [])

# 감정과 장르를 기반으로 영화 추천하는 함수 (랜덤 선택 포함)
def recommend_by_emotion(emotion, movies, num_recommendations=10):
    genres = emotion_genre_mapping(emotion)
    if not genres:
        return []
    
    # 장르 기반으로 영화 필터링
    genre_filtered_movies = movies[movies['genres'].apply(lambda x: any(genre in x for genre in genres))]
    
    # 평점이 평균 이상인 영화 필터링
    avg_vote = genre_filtered_movies['vote_average'].mean()
    high_rated_movies = genre_filtered_movies[genre_filtered_movies['vote_average'] >= avg_vote]
    
    # 높은 평점의 영화들 중에서 랜덤으로 num_recommendations개 선택
    recommended_movies = high_rated_movies.sample(n=num_recommendations, replace=False)
    
    return recommended_movies['title'].tolist()

# 타입 1: 장르만 고려하여 추천
def recommend_type1(movies, num_recommendations=10):
    # 상위 50개 영화 중 랜덤으로 num_recommendations개 선택
    top_movies = movies.sort_values(by=['popularity', 'vote_average'], ascending=False).head(50)
    recommended_movies = top_movies.sample(n=num_recommendations, replace=False)
    return recommended_movies['title'].tolist()

# 타입 2: 감정과 장르를 고려하여 추천
def recommend_type2(input_emotion, movies, num_recommendations=10):
    recommended_movies = recommend_by_emotion(input_emotion, movies, num_recommendations)
    return recommended_movies

# Load processed data
movies = pd.read_csv("processed_movies.csv")

app = func.FunctionApp()

@app.route(route="RecommendMovies", auth_level=func.AuthLevel.FUNCTION)
def recommend_movies(req: func.HttpRequest) -> func.HttpResponse:
    logging.info('Python HTTP trigger function processed a request.')

    preferred_type = req.params.get('type')
    if not preferred_type:
        try:
            req_body = req.get_json()
        except ValueError:
            pass
        else:
            preferred_type = req_body.get('type')

    if preferred_type == '1':
        recommended_movies = recommend_type1(movies)
    elif preferred_type == '2':
        input_emotion = req.params.get('emotion')
        if not input_emotion:
            try:
                req_body = req.get_json()
            except ValueError:
                pass
            else:
                input_emotion = req_body.get('emotion')
        if not input_emotion:
            return func.HttpResponse(
                "Please pass an emotion on the query string or in the request body",
                status_code=400
            )
        recommended_movies = recommend_type2(input_emotion, movies)
    else:
        return func.HttpResponse(
            "Please pass a valid type (1 or 2) on the query string or in the request body",
            status_code=400
        )

    return func.HttpResponse(json.dumps(recommended_movies), mimetype="application/json")