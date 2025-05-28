<?php
include 'header.php';

// TMDB API configuration
$apiKey = "b252ff5aa4b7f8186389adf7e546e29c";
$baseUrl = "https://api.themoviedb.org/3/";

// API endpoint and parameters
$endpoint = "tv/top_rated";
$parameters = array(
    "api_key" => $apiKey,
    "page" => 1,
    "language" => "en-US",
    "region" => "US"
);

// Build the request URL
$url = $baseUrl . $endpoint . "?" . http_build_query($parameters);

// Initialize cURL
$curl = curl_init($url);

// Set cURL options
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

// Execute the request
$response = curl_exec($curl);

// Check for errors
if ($response === false) {
    die("Error: " . curl_error($curl));
}

// Close cURL
curl_close($curl);

// Process the response
$data = json_decode($response, true);
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

        section {
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

        .tv-container {
            max-height: 80vh;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columns */
            gap: 2rem; /* Increased gap */
            justify-content: flex-start;
            align-items: flex-start;
        }

        .tv-item {
            position: relative;
            width: 100%; /* Updated width to fit 4 columns */
            height: 500px; /* You can adjust the height as needed */
            overflow: hidden;
        }

        .tv-poster {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .tv-item:hover .tv-poster {
            transform: scale(1.1);
        }

        .tv-overlay {
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

        .tv-item:hover .tv-overlay {
            opacity: 1;
        }

        .tv-description {
            max-height: 20rem;
            overflow: auto;
            padding: 1rem;
            font-size: 1.7rem;
        }

        .tv-description::-webkit-scrollbar {
            width: 0.1em;
            background-color: #3c4053;
        }

        .tv-poster {
            width: 100%;
            height: calc(100% - 4rem);
            object-fit: cover;
            transition: transform 0.3s;
            margin-top: 2rem;
        }

        /* Hide scrollbar */
        .tv-container::-webkit-scrollbar {
            width: 0.5em;
            background-color: #3c4053;
        }

        .tv-container::-webkit-scrollbar-thumb {
            background-color: #888;
        }

        /* Reduce padding around scrollbar */
        .tv-container::-webkit-scrollbar-thumb:hover {
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
            <div class="tv-container">
                <?php foreach ($data['results'] as $tvShow) { ?>
                    <div class="tv-item">
                        <img class="tv-poster" src="https://image.tmdb.org/t/p/w500/<?php echo $tvShow['poster_path']; ?>" alt="<?php echo $tvShow['name']; ?> Poster">
                        <div class="tv-overlay">
                            <p class="tv-description"><?php echo $tvShow['overview']; ?></p>
                            <div class="view-details">
                            <a href="tv_details.php?id=<?php echo $tvShow['id']; ?>">View Details</a> <!-- Add this line -->
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="load-more">
                <button onclick="loadMoreTVShows()">Load More</button>
            </div>
        </div>
    </section>

    <script>
        var page = 1;

        function loadMoreTVShows() {
            page++;
            var url = "<?php echo $baseUrl . $endpoint . '?' ?>";
            url += "api_key=<?php echo $apiKey; ?>&";
            url += "page=" + page;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var tvContainer = document.querySelector('.tv-container');

                    data.results.forEach(tvShow => {
                        var tvItem = document.createElement('div');
                        tvItem.className = 'tv-item';

                        var tvPoster = document.createElement('img');
                        tvPoster.className = 'tv-poster';
                        tvPoster.src = "https://image.tmdb.org/t/p/w500/" + tvShow.poster_path;
                        tvPoster.alt = tvShow.name + ' Poster';

                        var tvOverlay = document.createElement('div');
                        tvOverlay.className = 'tv-overlay';

                        var tvDescription = document.createElement('p');
                        tvDescription.className = 'tv-description';
                        tvDescription.innerText = tvShow.overview;

                        var viewDetailsLink = document.createElement('a');
                        viewDetailsLink.href = "tv_details.php?id=" + tvShow.id;
                        viewDetailsLink.innerText = "View Details";

                        tvOverlay.appendChild(tvDescription);
                        tvOverlay.appendChild(viewDetailsLink);
                        tvItem.appendChild(tvPoster);
                        tvItem.appendChild(tvOverlay);
                        tvContainer.appendChild(tvItem);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Attach event listener to the load more button
        var loadMoreButton = document.querySelector('.load-more button');
        loadMoreButton.addEventListener('click', loadMoreTVShows);
    </script>

</body>
</html>
