<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paradise</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .hero-section {
            position: relative;
            text-align: center;
            color: white;
        }
        .hero-section img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            filter: brightness(50%);
        }
        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Hotel Paradise</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/login">Login </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <img src="/images/hotel-paradise.jpg" alt="Hotel Paradise">
        <div class="hero-text">
            <h1 class="fw-bold">Hotel Paradise</h1>
            <h2>Ahmedabad India</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
    </div>

    <section id="about" class="py-5 text-center">
        <div class="container">
            <h2>Welcome to <span class="text-primary">Hotel Paradise</span></h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
    </section>

    <footer class="bg-dark text-white text-center p-3">
        <p>&copy; 2024 Hotel Paradise. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
