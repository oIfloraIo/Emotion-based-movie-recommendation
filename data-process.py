import pandas as pd
import numpy as np
import ast
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

movies = pd.read_csv("TMDB 5000/tmdb_5000_movies.csv")
credits = pd.read_csv("TMDB 5000/tmdb_5000_credits.csv")

movies = movies.merge(credits, on="title")
movies = movies[["id", "title", "overview", "genres", "production_companies", "popularity", "vote_average"]]
movies.columns = ["movie_id", "title", "overview", "genres", "production_companies", "popularity", "vote_average"]

def convert(obj):
    L = []
    for i in ast.literal_eval(obj):
        L.append(i["name"])
    return L

movies["genres"] = movies["genres"].apply(convert)
movies["production_companies"] = movies["production_companies"].apply(convert)
movies.dropna(subset=["genres", "production_companies"], inplace=True)

movies["genres"] = movies["genres"].apply(lambda x: [i.replace(" ", "") for i in x])
movies["production_companies"] = movies["production_companies"].apply(lambda x: [i.replace(" ", "") for i in x])
movies['genres'] = movies['genres'].apply(lambda x: ' '.join(x))

movies = movies[movies['genres'] != '']

print(movies['genres'].isna().sum())

def calculate_population(data, popularity):
    population = {}
    for genres, pop in zip(data['genres'], popularity):
        for genre in genres.split(','):
            genre = genre.strip()
            population[genre] = population.get(genre, 0) + pop
    return population

def adjust_similarity_weights(cosine_sim, population):
    for genre, count in population.items():
        genre_indices = [i for i, row in enumerate(movies['genres']) if genre in row]
        for i in genre_indices:
            for j in genre_indices:
                cosine_sim[i][j] *= np.log1p(count)
    return cosine_sim

tfidf_vectorizer = TfidfVectorizer(stop_words='english')
tfidf_matrix = tfidf_vectorizer.fit_transform(movies['genres'] + " " + movies['vote_average'].astype(str))

cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)

popularity = movies['popularity']
population = calculate_population(movies, popularity)

cosine_sim_adj = adjust_similarity_weights(cosine_sim, population)

movies.to_csv("processed_movies.csv", index=False)
np.save("cosine_sim_adj.npy", cosine_sim_adj)