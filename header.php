<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Awards.css">
    <style>
        #header {
            position: absolute;
            top: 0;
            left: 0;
            width:  100%;
            padding: 30px 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10000;
            background-color: #201f20;
        }

        #header .logo {
            color: #cb1515;
            font-weight: 700;
            font-size: 2em;
            text-decoration: none;
        }

        #header ul {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #header ul li {
            list-style: none;
            margin-left: 20px;
        }
        #header ul li a {
            text-decoration: none;
            padding: 6px 20px; /* Increased padding for hover area */
            color: #cb1515;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        #header ul li a:hover,
        #header ul li a.active {
            background: #cb1515;
            color: #ffff;
        }

        #header .search-bar {
            flex: 1;
            text-align: center;
        }

        #header .search-bar input[type="text"] {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #cb1515;
            width: 300px;
            max-width: 100%;
        }

        #header .search-bar button[type="submit"] {
            font: bold 13px Arial;
            background-color: #cb1515;
            color: #fff;
            border: none;
            padding: 5px 15px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        #header ul li a i {
            font-size: 24px;
            margin-right: 5px;
        }
        
    </style>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>
<body>
    <div class="header-bg">
        <header id="header">
            <a href="landing.php" class="logo">ReelReviews</a>
            <div class="search-bar">
            <form action="search_results.php" method="GET">
                <input type="text" name="query" placeholder="Search movies, TV shows, actors" value="<?php echo isset($query) ? htmlspecialchars($query) : ''; ?>">
                <button type="submit">Search</button>
            </form>

            </div>
            <ul>
                
                <li><a href="landing.php" class=""><i class="ion-home"></i></a></li>
                <li><a href="topmovies.php"><i class="ion-ios-film"></i></a></li>
                <li><a href="toptv.php"><i class="ion-ios-monitor"></i></a></li>
                <li><a href="genre.php"><i class="ion-ios-list"></i></a></li>
            </ul>
        </header>
    </div>
</body>
</html>
