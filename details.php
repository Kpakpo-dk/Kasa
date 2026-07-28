<?php
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save the text inputs to our temporary session memory
    $_SESSION['artisan_name'] = $_POST['artisanName'] ?? '';
    $_SESSION['craft_type'] = $_POST['craftType'] ?? '';

    // Handle the photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $fileName = 'photo_' . time() . '.' . $extension;
        $uploadPath = 'uploads/' . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $_SESSION['photo_path'] = $uploadPath;
        }
    }

    // Redirect to the next PHP step
    header('Location: transcribe.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your details — Kasa</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,500;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./style.css">
</head>
<body>

<header>
  <nav class="wrap">
    <a href="index.html" class="logo">
      <span class="logo-mark">
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
      </span>
      Kasa
    </a>
    <div class="nav-links">
      <a href="index.html#how">How it works</a>
      <a href="index.html#card">Sample card</a>
      <a href="index.html#impact">Why it matters</a>
    </div>
    <a href="index.html" class="btn-link">Home</a>
  </nav>
</header>

<section class="details-hero">
  <div class="wrap">

    <div class="flow">
      <span class="flow-step done"><span class="dot"></span> Record</span>
      <span class="flow-step active"><span class="dot"></span> Your details</span>
      <span class="flow-step"><span class="dot"></span> Transcribe &amp; translate</span>
      <span class="flow-step"><span class="dot"></span> Your card</span>
    </div>

    <h1>Now, tell us who you are</h1>
    <p class="lede">This is what appears on your card alongside your story — your name, your craft, and a photo of you or your work.</p>

    <form class="form-panel" id="detailsForm" method="POST" enctype="multipart/form-data">
      <div class="field">
        <label for="artisanName">Your name</label>
        <input type="text" id="artisanName" name="artisanName" placeholder="e.g. Akosua Mensah" required>
      </div>

      <div class="field">
        <label for="craftType">Your craft</label>
        <select id="craftType" name="craftType" required>
          <option value="" disabled selected>Select your craft</option>
          <option value="beadmaking">Bead making</option>
          <option value="weaving">Weaving</option>
          <option value="pottery">Pottery</option>
          <option value="woodwork">Woodwork</option>
          <option value="basketry">Basketry</option>
          <option value="leatherwork">Leatherwork</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div class="field">
        <label for="photoInput">Your photo</label>
        <div class="photo-drop" id="photoDrop">
          <svg class="drop-icon" viewBox="0 0 24 24" fill="none" stroke="#C9982F" stroke-width="1.4">
            <rect x="3" y="5" width="18" height="14" rx="1.5"/>
            <circle cx="9" cy="11" r="2"/>
            <path d="M21 16l-5.5-5-4 4L9 13l-6 6"/>
          </svg>
          <div class="drop-label" id="photoLabel">Tap to choose a photo</div>
          <div class="drop-sub">JPG or PNG, up to 5MB</div>
          <input type="file" id="photoInput" name="photo" accept="image/png, image/jpeg" required>
        </div>
      </div>

      <div class="form-actions">
        <a href="record.php" class="btn-link">Back</a>
        <button type="submit" class="btn btn-primary">Continue</button>
      </div>
    </form>

  </div>
</section>

<footer>
  <div class="wrap">
    <div class="footer-bottom" style="border:none; padding-top:0;">
      <span>Kasa — a project for Ghanaian craft storytelling</span>
      <span>Built in Accra</span>
    </div>
  </div>
</footer>

<script>
  (function(){
    var input = document.getElementById('photoInput');
    var label = document.getElementById('photoLabel');

    input.addEventListener('change', function(){
      if (input.files && input.files.length > 0){
        label.textContent = input.files[0].name;
      } else {
        label.textContent = 'Tap to choose a photo';
      }
    });
  })();
</script>

</body>
</html>