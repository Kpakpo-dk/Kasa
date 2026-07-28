<?php
session_start();

// 1. Gather all data captured across the previous steps
$artisanName = $_SESSION['artisan_name'] ?? 'Anonymous Artisan';
$craftType = $_SESSION['craft_type'] ?? 'Handmade Craft';
$photoPath = $_SESSION['photo_path'] ?? './159562172-manzini-swaziland-10-30-2019-african-woman-making-a-necklace-with-colorful-beads-and-selling.jpg';
$audioPath = $_SESSION['recorded_audio_path'] ?? '';

// Match the exact POST key names sent by the transcribe.php form layout
$transcriptAkan = $_SESSION['transcript_akan'] ?? ($_POST['transcriptionAkan'] ?? '');
$translationEng = $_SESSION['translation_eng'] ?? ($_POST['translationEng'] ?? '');

// Map the dropdown select values to friendly display readable names
$craftLabels = [
    'beadmaking'  => 'Bead making',
    'weaving'     => 'Weaving',
    'pottery'     => 'Pottery',
    'woodwork'    => 'Woodwork',
    'basketry'    => 'Basketry',
    'leatherwork' => 'Leatherwork',
    'other'       => 'Handmade Craft'
];
$displayCraft = $craftLabels[$craftType] ?? ucwords(str_replace('_', ' ', $craftType));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($artisanName); ?> — Your Kasa Card</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,500;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
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
    <a href="transcribe.php" class="btn-link">Back</a>
  </nav>
</header>

<section class="details-hero">
  <div class="wrap" style="text-align: center;">

    <div class="flow" style="justify-content: center; margin-bottom: 40px;">
      <span class="flow-step done"><span class="dot"></span> Record</span>
      <span class="flow-step done"><span class="dot"></span> Your details</span>
      <span class="flow-step done"><span class="dot"></span> Transcribe &amp; translate</span>
      <span class="flow-step active"><span class="dot"></span> Your card</span>
    </div>

    <h1 style="margin-bottom: 12px;">Your Digital Story Card is Ready!</h1>
    <p class="lede" style="margin: 0 auto 48px; max-width: 540px;">Here is your complete, authentic craft card. Ready to present or share directly with buyers.</p>

    <!-- THE DYNAMIC GENERATED STORY CARD -->
    <div class="card-mock" style="margin: 0 auto; text-align: left; max-width: 440px; width: 100%;">
      <div class="card-photo">
        <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="<?php echo htmlspecialchars($artisanName); ?>" class="card-photo">
      </div>
      <div class="card-body">
        <div class="card-name"><?php echo htmlspecialchars($artisanName); ?></div>
        <div class="card-role" style="text-transform: capitalize;"><?php echo htmlspecialchars($craftType); ?> · Accra</div>
        
        <p class="card-quote">
          "<?php echo htmlspecialchars($translationEng); ?>"
        </p>
        
        <p style="font-size: 0.82rem; color: rgba(241, 233, 216, 0.5); font-style: italic; margin-top: 12px; padding-left: 14px;">
          Original: <?php echo htmlspecialchars($transcriptAkan); ?>
        </p>

        <div class="card-footer">
          <div class="play-pill">
            <button class="play-btn" id="cardPlayBtn" aria-label="Play original audio" style="border: none; cursor: pointer;">
              <svg viewBox="0 0 10 10" id="playIcon"><polygon points="1,0 10,5 1,10" fill="#1D2A44"/></svg>
            </button>
            <span id="playLabel">Listen to original story</span>
          </div>
          <span class="badge">Kasa.gh/preview</span>
        </div>
      </div>
    </div>

    <?php if (!empty($audioPath)): ?>
      <audio id="cardAudio" src="<?php echo htmlspecialchars($audioPath); ?>"></audio>
    <?php endif; ?>

    <div style="margin-top: 48px;">
      <a href="record.php" class="btn btn-primary">Create Another Card</a>
    </div>

  </div>
</section>

<footer>
  <div class="wrap">
    <div class="footer-bottom" style="border:none;padding-top:0;">
      <span>Kasa — a project for Ghanaian craft storytelling</span>
      <span>Built in Accra</span>
    </div>
  </div>
</footer>

<script>
  (function(){
    var audio = document.getElementById('cardAudio');
    var playBtn = document.getElementById('cardPlayBtn');
    var playIcon = document.getElementById('playIcon');
    var playLabel = document.getElementById('playLabel');

    if (!audio) {
      playLabel.textContent = "No audio recorded";
      return;
    }

    playBtn.addEventListener('click', function() {
      if (audio.paused) {
        audio.play();
        playIcon.innerHTML = '<rect x="1" y="0" width="3" height="10" fill="#1D2A44"/><rect x="6" y="0" width="3" height="10" fill="#1D2A44"/>';
        playLabel.textContent = "Playing original audio...";
      } else {
        audio.pause();
        playIcon.innerHTML = '<polygon points="1,0 10,5 1,10" fill="#1D2A44"/>';
        playLabel.textContent = "Listen to original story";
      }
    });

    audio.addEventListener('ended', function() {
      playIcon.innerHTML = '<polygon points="1,0 10,5 1,10" fill="#1D2A44"/>';
      playLabel.textContent = "Listen to original story";
    });
  })();
</script>

</body>
</html>