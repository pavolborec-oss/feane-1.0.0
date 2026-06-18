<?php

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Thank You - Feane</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
  <div class="hero_area">
    <div class="bg-box">
      <img src="images/hero-bg.jpg" alt="" />
    </div>
    <?php include 'partials/header.php'; ?>
  </div>

  <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>Thank You</h2>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card" style="border:none; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <div class="card-body text-center p-5">
              <div style="font-size: 60px; color: #ff6b6b;">
                <i class="fa fa-check-circle"></i>
              </div>
              <h3 class="mt-4">Vaša rezervácia bola úspešne odoslaná!</h3>
              <p class="mt-3">
                Ďakujeme, že ste si rezervovali stôl u Feane. Čoskoro vás budeme kontaktovať a potvrdiť vašu rezerváciu.
              </p>
              <?php if (!empty($_POST)): ?>
                <div class="booking-summary mt-4 text-left mx-auto" style="max-width: 420px;">
                  <h5>Rezervačné údaje</h5>
                  <ul class="list-unstyled text-left">
                    <li><strong>Meno:</strong> <?php echo htmlspecialchars($_POST['name'] ?? ''); ?></li>
                    <li><strong>Telefón:</strong> <?php echo htmlspecialchars($_POST['phone'] ?? ''); ?></li>
                    <li><strong>Email:</strong> <?php echo htmlspecialchars($_POST['email'] ?? ''); ?></li>
                    <li><strong>Počet osôb:</strong> <?php echo htmlspecialchars($_POST['persons'] ?? ''); ?></li>
                    <li><strong>Dátum:</strong> <?php echo htmlspecialchars($_POST['date'] ?? ''); ?></li>
                  </ul>
                </div>
              <?php endif; ?>
              <div class="d-flex justify-content-center flex-wrap gap-2">
                <a href="index.php" class="btn btn-primary mt-4">
                  <i class="fa fa-home"></i> Späť na domov
                </a>
                <a href="menu.php" class="btn btn-secondary mt-4">
                  <i class="fa fa-cutlery"></i> Prezrieť menu
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include 'partials/footer.php'; ?>

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <script src="js/custom.js"></script>
</body>

</html>
