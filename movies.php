<?php
include 'header.php';

// TMDB API configuration
$apiKey = "b252ff5aa4b7f8186389adf7e546e29c";
$baseUrl = "https://api.themoviedb.org/3/";

// Check if genre ID is provided as a query parameter
if (isset($_GET['genre_id'])) {
    $genreId = $_GET['genre_id'];
} else {
    // Redirect to genres page if genre ID is not provided
    header("Location: genres.php");
    exit();
}

// API endpoint and parameters to fetch movies by genre
$moviesEndpoint = "discover/movie";
$moviesParameters = array(
    "api_key" => $apiKey,
    "language" => "en-US",
    "sort_by" => "popularity.desc",
    "with_genres" => $genreId
);

// Build the request URL for movies by genre
$moviesUrl = $baseUrl . $moviesEndpoint . "?" . http_build_query($moviesParameters);

// Initialize cURL for movies request
$moviesCurl = curl_init($moviesUrl);
curl_setopt($moviesCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($moviesCurl, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

// Execute the movies request
$moviesResponse = curl_exec($moviesCurl);

// Close the cURL handle
curl_close($moviesCurl);

// Process the movies response
$moviesData = json_decode($moviesResponse, true);
$movies = $moviesData['results'];
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

        section{
            width: 100%;
            height: 100vh;
            background-color: #1F1F1F;
            align-items: center;
            justify-content: center;
            display: flex;
        }


        .container {
            width: 100%;
            max-width: 200rem; /* Increased width */
            padding: 0 3rem; /* Increased padding */
        }

        .movie-container {
            max-height: 80vh;
            overflow-y: auto;
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
            margin-top: 2rem;
            text-align: center;
            position: relative;
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

        .view-details {
            position: absolute;
            bottom: 0; /* Position at the bottom */
            left: 0; /* Align to the left */
            width: 100%; /* Full width */
            text-align: center;
            padding-bottom: 2rem; /* Added padding at the bottom */
        }

        .view-details a {
            border-radius: 10px;
            font-size: 1.6rem;
            padding: 1rem 2rem;
            background-color: red;
            border: none;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none; /* Added text decoration */
        }

        .view-details a:hover {
            background-color: red;
        }

    </style>
</head>
<body>
<section>
    <div class="container">
        <div class="movie-container">
            <?php foreach ($movies as $movie) { ?>
                <div class="movie-item">
                    <img class="movie-poster" src="https://image.tmdb.org/t/p/w500/<?php echo $movie['poster_path']; ?>"
                        alt="<?php echo $movie['title']; ?> Poster">
                    <div class="movie-overlay">
                        <p class="movie-description"><?php echo $movie['overview']; ?></p>
                        <div class="view-details">
                        <a class="view-details-link" href="movie_details.php?id=<?php echo $movie['id']; ?>">View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="load-more">
                <button onclick="loadMoreMovies()">Load More</button>
            </div>
    </div>
</section>


<script>
var page = 1;

function loadMoreMovies() {
    page++;
    var url = "<?php echo $moviesUrl . '&page='; ?>" + page;

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
                viewDetailsLink.href = "movies.php?id=" + movie.id;
                viewDetailsLink.innerText = "View Details";

                var viewDetailsDiv = document.createElement('div');
                viewDetailsDiv.className = 'view-details';
                viewDetailsDiv.appendChild(viewDetailsLink);

                movieOverlay.appendChild(movieDescription);
                movieOverlay.appendChild(viewDetailsDiv);
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



</script>
</body>
</html>
