<?php

  require_once("src/functions.php");
  $db = dbConnect();
    
  // neighborhood and roomtypes for dropdown
  $neighborhoods = getNeighborhoods($db);
  $roomTypes = getRoomTypes($db);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fake Airbnb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
 
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" href="images/house-heart-fill.svg">
    <link rel="mask-icon" href="images/house-heart-fill.svg" color="#000000">

  </head>
  <body>

    <header>
        <div class="collapse bg-dark" id="navbarHeader">
            <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                <h4 class="text-white">About</h4>
                <p class="text-muted">Fake Airbnb. Data c/o http://insideairbnb.com/get-the-data/</p>
                </div>
            </div>
            </div>
        </div>
<!-- above no edits -->
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <i class="bi bi-house-heart-fill my-2"></i>    
                <strong> Fake Airbnb</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            </div>
        </div>
    </header>
<!-- above no edits -->
    <main>

    <div class="album py-5 bg-light">
        <div class="container">
        <h1>Search for rentals in the Portland area:</h1>
        <!-- submit get data to results.php -->
          <form action="results.php" method="GET">

            <!-- neighborhood select-->
            <div class="mb-3">
                <label for="neighborhood" class="form-label">Neighborhood:</label>
                <select name="neighborhood" id="neighborhood" class="form-select">
                    
                    <!-- keep optional value so it defaults to any neighborhood   -->
                    <option value="">Neighborhood: </option>

                    <!-- iterate through every neighborhood and add it to the dropdown -->
                    <?php foreach ($neighborhoods as $n): ?>
                    <option value="<?php echo $n['id']; ?>"><?php echo htmlspecialchars($n['neighborhood']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

              <!-- room select  same as above-->
            <div class="mb-3">
                <label for="roomType" class="form-label">Room Type:</label>
                <select name="roomType" id="roomType" class="form-select">
                <option value=""> Room Type: </option>
                <?php foreach ($roomTypes as $rt): ?>
                    <option value="<?php echo htmlspecialchars($rt['roomType']); ?>"><?php echo htmlspecialchars($rt['roomType']); ?></option>
                <?php endforeach; ?>
                </select>
            </div>

              <!-- Number of guests selector, don't need data, same as above -->
            <div class="mb-3">
                <label for="guests" class="form-label">Number of Guests:</label>
                <select name="guests" id="guests" class="form-select">
                <?php foreach (range(1,10) as $num): ?>
                    <option value="<?php echo $num; ?>"><?php echo $num; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Search</button>
            </form> 

            <!-- sample drop down eblow -->
            <!-- <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="neighborhood" class="col-form-label">sample form element label</label>
                </div>

                <div class="col-auto">
                        form element here
                </div>

            </div>row -->
        </div><!-- .container-->
    </div><!-- album-->

    </main>

    <footer class="text-muted py-5">
    <div class="container">

        <p class="mb-1">CS 293, Spring 2025</p>
        <p class="mb-1">Lewis & Clark College</p>
    </div><!-- .container-->
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    
</body>
</html>