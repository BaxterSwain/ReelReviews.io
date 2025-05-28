<?php
$TMDB_API_KEY = 'b252ff5aa4b7f8186389adf7e546e29c';

include 'header.php';

// Function to perform API requests
function apiRequest($endpoint, $params = []) {
    global $TMDB_API_KEY;
    
    $url = "https://api.themoviedb.org/3$endpoint?api_key=$TMDB_API_KEY";

    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    

    $curl = curl_init($url);
    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
    
}

// Function to search for movies using the TMDB API
function searchMovies($query) {
    $params = [
        'query' => $query,
        'language' => 'en-US',
        'include_adult' => false,
    ];

    return apiRequest('/search/movie', $params);
}

// Function to search for TV shows using the TMDB API
function searchTVShows($query) {
    $params = [
        'query' => $query,
        'language' => 'en-US',
        'include_adult' => false,
    ];

    return apiRequest('/search/tv', $params);
}

// Function to search for actors using the TMDB API
function searchActors($query) {
    $params = [
        'query' => $query,
        'language' => 'en-US',
        'include_adult' => false,
    ];

    return apiRequest('/search/person', $params);
}

// Get the search query from the URL parameter
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Perform the search and retrieve the data
$movieResults = searchMovies($query);
$tvShowResults = searchTVShows($query);
$actorResults = searchActors($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Awards.css">
    <style>
        body {
            background-color: #414242 !important;
            font-family: 'Roboto', sans-serif;
            font-size: 10px;
            box-sizing: border-box;
            display: flex;
            align-items: left;
            justify-content: left;
        }

        /* Hide scrollbar */
        body::-webkit-scrollbar {
        width: 4px;
        background-color: #3c4053;
        }

        body::-webkit-scrollbar-thumb {
        background-color: #888;
        }

        /* Reduce padding around scrollbar */
        body::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        }

        .container::-webkit-scrollbar {
            width:2px;
        }

        section{
            width: 100%;
            height: 100%;
            background-color: #1F1F1F;
            align-items: center;
            justify-content: center;
            display: flex;
        }

        .container {
            width: 100%;
            max-width: 200rem; /* Increased width */
            padding: 0 3rem; /* Increased padding */
            overflow-y: auto;
        }


        .movie-container {
            max-height: 80vh;
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columns */
            gap: 2rem; /* Increased gap */
            justify-content: flex-start;
            align-items: flex-start;
        }

        .movie-item {
            position: relative;
            width: 100%; /* Updated width to fit 4 columns */
            height: 500px; /* You can adjust the height as needed */
            overflow: hidden;
        }

        .movie-poster {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .movie-item:hover .movie-poster {
            transform: scale(1.1);
        }

        .movie-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .movie-item:hover .movie-overlay {
            opacity: 1;
        }

        .movie-description {
            max-height: 20rem;
            overflow: auto;
            padding: 1rem;
            font-size: 1.7rem;
        }

        .movie-description::-webkit-scrollbar {
            width: 0.1em;
            background-color: #3c4053;
        }

        .movie-poster {
            width: 100%;
            height: calc(100% - 4rem);
            object-fit: cover;
            transition: transform 0.3s;
            margin-top: 2rem;
        }

        /* Hide scrollbar */
        .movie-container::-webkit-scrollbar {
            width: 0.5em;
            background-color: #3c4053;
        }

        .movie-container::-webkit-scrollbar-thumb {
            background-color: #888;
        }

        /* Reduce padding around scrollbar */
        .movie-container::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        /* Load more button */
        .load-more {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .load-more button {
            font-size: 1.6rem;
            padding: 1rem 2rem;
            background-color: red;
            border: none;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .load-more button:hover {
            background-color: red;
        }

        .header {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <section>
        <div class="container">
            <?php if (!empty($movieResults['results'])) { ?>
                <h2 class="header">Movies</h2>
                <div class="movie-container">
                    <?php foreach ($movieResults['results'] as $movie) { ?>
                        <div class="movie-item">
                            <img class="movie-poster" src="https://image.tmdb.org/t/p/w500/<?php echo $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?> Poster">
                            <div class="movie-overlay">
                                <p class="movie-description"><?php echo $movie['overview']; ?></p>
                                <a href="movie_details.php?id=<?php echo $movie['id']; ?>">View Details</a> <!-- Add this line -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if (!empty($tvShowResults['results'])) { ?>
                <h2 class="header">TV Shows</h2>
                <div class="movie-container">
                    <?php foreach ($tvShowResults['results'] as $tvShow) { ?>
                        <div class="movie-item">
                            <img class="movie-poster" src="https://image.tmdb.org/t/p/w500/<?php echo $tvShow['poster_path']; ?>" alt="<?php echo $tvShow['name']; ?> Poster">
                            <div class="movie-overlay">
                                <p class="movie-description"><?php echo $tvShow['overview']; ?></p>
                                <a href="tvshow_details.php?id=<?php echo $tvShow['id']; ?>">View Details</a> <!-- Add this line -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if (!empty($actorResults['results'])) { ?>
                <h2 class="header">Actors</h2>
                <div class="movie-container">
                    <?php foreach ($actorResults['results'] as $actor) { ?>
                        <div class="movie-item">
                            <img class="movie-poster" src="https://image.tmdb.org/t/p/w500/<?php echo $actor['profile_path']; ?>" alt="<?php echo $actor['name']; ?> Poster">
                            <div class="movie-overlay">
                                <p class="movie-description"><?php echo $actor['name']; ?></p>
                                <a href="actor_details.php?id=<?php echo $actor['id']; ?>">View Details</a> <!-- Add this line -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="load-more">
                <button onclick="loadMoreMovies()">Load More</button>
            </div>
        </div>
    </section>

    <script>
        var page = 1;

        function loadMoreMovies() {
            page++;
            var url = "<?php echo $baseUrl . $endpoint . '?' ?>";
            url += "api_key=<?php echo $apiKey; ?>&";
            url += "page=" + page;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var movieContainer = document.querySelector('.movie-container');

                    data.results.forEach(movie => {
                        var movieItem = document.createElement('div');
                        movieItem.className = 'movie-item';

                        var moviePoster = document.createElement('img');
                        moviePoster.className = 'movie-poster';
                        moviePoster.src = "https://image.tmdb.org/t/p/w500/" + movie.poster_path;
                        moviePoster.alt = movie.title + ' Poster';

                        var movieOverlay = document.createElement('div');
                        movieOverlay.className = 'movie-overlay';

                        var movieDescription = document.createElement('p');
                        movieDescription.className = 'movie-description';
                        movieDescription.innerText = movie.overview;

                        var viewDetailsLink = document.createElement('a');
                        viewDetailsLink.href = "movie_details.php?id=" + movie.id;
                        viewDetailsLink.innerText = "View Details";

                        movieOverlay.appendChild(movieDescription);
                        movieOverlay.appendChild(viewDetailsLink);
                        movieItem.appendChild(moviePoster);
                        movieItem.appendChild(movieOverlay);
                        movieContainer.appendChild(movieItem);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Attach event listener to the load more button
        var loadMoreButton = document.querySelector('.load-more button');
        loadMoreButton.addEventListener('click', loadMoreMovies);

        window.addEventListener('scroll', function() {
        var loadMoreButton = document.querySelector('.load-more');
        var rect = loadMoreButton.getBoundingClientRect();

        // Check if the button is in the viewport
        if (rect.top >= 0 && rect.bottom <= window.innerHeight) {
            loadMoreButton.classList.add('fixed');
        } else {
            loadMoreButton.classList.remove('fixed');
        }
    });
    </script>

</body>
</html>
