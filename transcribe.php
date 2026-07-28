<?php
session_start();

// Read active session values or create placeholders if empty
$artisanName = $_SESSION['artisan_name'] ?? 'Artisan';
$audioPath = $_SESSION['recorded_audio_path'] ?? '';
$photoPath = $_SESSION['photo_path'] ?? '';

// If form is submitted on this page, handle the next step
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['transcript_akan'] = $_POST['transcriptionAkan'] ?? '';
    $_SESSION['translation_eng'] = $_POST['translationEng'] ?? '';
    
    // Move onto card creation step
    header('Location: card.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transcribe &amp; Translate — Kasa</title>
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
    <a href="index.html" class="btn-link">Home</a>
  </nav>
</header>

<section class="details-hero">
  <div class="wrap">

    <!-- Flow indicator updating Step 3 to Active -->
    <div class="flow">
      <span class="flow-step done"><span class="dot"></span> Record</span>
      <span class="flow-step done"><span class="dot"></span> Your details</span>
      <span class="flow-step active"><span class="dot"></span> Transcribe &amp; translate</span>
      <span class="flow-step"><span class="dot"></span> Your card</span>
    </div>

    <h1>Reviewing your story text</h1>
    <p class="lede">Kasa has processed your audio. Review the transcription and English translation generated for your card.</p>

    <!-- The form properly encapsulates the entire interface panel now -->
    <form class="form-panel" method="POST" action="transcribe.php">
      
      <!-- Audio Player Preview Box -->
      <div class="field" style="margin-bottom: 24px;">
        <label style="font-family:'IBM Plex Mono', monospace; font-size:0.8rem; color:var(--clay); text-transform:uppercase;">Audio Preview</label>
        <div style="background:var(--paper-deep); padding:16px; display:flex; align-items:center; border-radius:2px; margin-top:8px;">
          <?php if (!empty($audioPath)): ?>
            <audio controls style="width:100%;" src="<?php echo htmlspecialchars($audioPath); ?>"></audio>
          <?php else: ?>
            <p style="font-size: 0.9rem; color: var(--ink-soft);">No voice recording captured yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Side-by-Side Textbox Layout Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        
        <div class="field">
          <label for="transcriptionAkan">Original Audio Transcript (Akan)</label>
          <textarea id="transcriptionAkan" name="transcriptionAkan" rows="5" class="mono" style="width:100%; padding:14px; border:1px solid var(--line); background:var(--paper); color:var(--charcoal); font-size:0.95rem; resize:vertical; border-radius:2px; margin-top:8px;" required>Mefiri Bonwire, na mehyɛɛ aseɛ sɛ mesua kente nwene mfie du a atwam ni. Kente wei yɛ deɛ yɛfrɛ no "Adweneasa" — ɛkyerɛ sɛ adwene nyinaa asa mu.</textarea>
        </div>

        <div class="field">
          <label for="translationEng">English Translation</label>
          <textarea id="translationEng" name="translationEng" rows="5" style="width:100%; padding:14px; border:1px solid var(--line); background:var(--paper); color:var(--charcoal); font-size:0.95rem; resize:vertical; border-radius:2px; margin-top:8px;" required>I come from Bonwire, and I started learning Kente weaving ten years ago. This particular Kente is called "Adweneasa" — meaning all concepts or ideas have been exhausted in its creation.</textarea>
        </div>

      </div>

      <!-- Footer Form Action Buttons inside the form boundaries -->
      <div class="form-actions" style="margin-top: 32px;">
        <a href="details.php" class="btn-link">Back</a>
        <button type="submit" class="btn btn-primary">Generate My Card</button>
      </div>

    </form>

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

</body>
</html>