<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Record your story — Artisan Voice</title>
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

<section class="record-hero">
  <div class="wrap">

    <div class="flow">
  <span class="flow-step active"><span class="dot"></span> Record</span>
  <span class="flow-step"><span class="dot"></span> Your details</span>
  <span class="flow-step"><span class="dot"></span> Transcribe &amp; translate</span>
  <span class="flow-step"><span class="dot"></span> Your card</span>
</div>
    <h1>Tell us about your craft</h1>
    <p class="lede">Speak for up to one minute, in Twi, Ga or Ewe. Talk about what you make and why it matters to you — that's all we need.</p>

    <div class="panel state-idle" id="panel">

      <div class="mic-stage">
        <button class="mic-btn" id="micBtn" aria-label="Start recording">
          <span class="mic-ring"></span>
          <svg viewBox="0 0 24 24" fill="none" stroke="#1D2A44" stroke-width="1.6">
            <rect x="9" y="2" width="6" height="12" rx="3"/>
            <path d="M5 10a7 7 0 0 0 14 0"/>
            <line x1="12" y1="17" x2="12" y2="21"/>
          </svg>
        </button>
        <div class="timer" id="timer">0:00</div>
        <div class="hint" id="hint">Tap the microphone to begin</div>
      </div>

      <div class="playback">
        <button class="play-toggle" aria-label="Play recording">
          <svg viewBox="0 0 10 10"><polygon points="1,0 10,5 1,10" fill="#1D2A44"/></svg>
        </button>
        <div class="mini-wave" id="miniWave"></div>
        <div class="playback-time">0:41</div>
      </div>

      <div class="panel-controls">
        <button class="btn btn-primary btn-start" id="startBtn">Start recording</button>
        <button class="btn btn-primary btn-stop" id="stopBtn">Stop recording</button>
      </div>

      <div class="panel-actions">
        <button class="btn-link" id="reRecordBtn">Re-record</button>
        <a href="details.php" class="btn btn-primary" id="continueBtn">Continue</a>
      </div>

    </div>

  </div>
</section>

<footer>
  <div class="wrap">
    <div class="footer-top">
      <div class="logo">
        <span class="logo-mark">
          <span></span><span></span><span></span><span></span>
          <span></span><span></span><span></span><span></span>
        </span>
        Kasa
      </div>
      <div class="footer-links">
        <div class="footer-col">
          <h4>Site</h4>
          <a href="#how">How it works</a>
          <a href="#card">Sample card</a>
          <a href="#impact">Why it matters</a>
        </div>
        <div class="footer-col">
          <h4>Languages</h4>
          <a href="#">Twi</a>
          <a href="#">Ga</a>
          <a href="#">Ewe</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span>Kasa — a project for Ghanaian craft storytelling</span>
      <span>Built in Accra</span>
    </div>
  </div>
</footer>

<script>
  // Build the small visual waveform shown in the playback state
  (function(){
    var wave = document.getElementById('miniWave');
    for (var i = 0; i < 28; i++){
      var bar = document.createElement('span');
      bar.style.height = (6 + Math.random() * 20) + 'px';
      wave.appendChild(bar);
    }
  })();

  // Functional Audio Recording System using MediaRecorder API
  (function(){
    var panel = document.getElementById('panel');
    var startBtn = document.getElementById('startBtn');
    var stopBtn = document.getElementById('stopBtn');
    var micBtn = document.getElementById('micBtn');
    var reRecordBtn = document.getElementById('reRecordBtn');
    var timer = document.getElementById('timer');
    var hint = document.getElementById('hint');
    
    // Playback state elements
    var playToggle = document.querySelector('.play-toggle');
    var playbackTimeEl = document.querySelector('.playback-time');

    var mediaRecorder = null;
    var audioChunks = [];
    var audioBlob = null;
    var audioUrl = null;
    var audioAudio = null; 
    
    var seconds = 0;
    var interval = null;

    function setState(state){
      panel.className = 'panel state-' + state;
    }

    // Format seconds into M:SS
    function formatTime(secs) {
      var m = Math.floor(secs / 60);
      var s = String(secs % 60).padStart(2, '0');
      return m + ':' + s;
    }

    // 1. Start Recording Functionality
    async function startRecording() {
      audioChunks = []; 
      
      try {
        var stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        
        mediaRecorder.addEventListener('dataavailable', function(event) {
          if (event.data.size > 0) {
            audioChunks.push(event.data);
          }
        });

        mediaRecorder.addEventListener('stop', function() {
          // Combine audio chunks into a single audio file blob
          audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
          audioUrl = URL.createObjectURL(audioBlob);
          audioAudio = new Audio(audioUrl);

          // Update the playback UI display duration once metadata loads
          audioAudio.addEventListener('loadedmetadata', function() {
            playbackTimeEl.textContent = formatTime(Math.round(audioAudio.duration));
          });

          // Reset play button icon when the recorded track finishes running
          audioAudio.addEventListener('ended', function() {
            playToggle.innerHTML = '<svg viewBox="0 0 10 10"><polygon points="1,0 10,5 1,10" fill="#1D2A44"/></svg>';
          });
          
          // Stop microphone streams to turn off the hardware recording light
          stream.getTracks().forEach(track => track.stop());
          setState('recorded');
        });

        // Start execution
        mediaRecorder.start();
        setState('recording');
        hint.textContent = 'Recording — click stop when you\'re done';
        
        seconds = 0;
        timer.textContent = '0:00';
        interval = setInterval(function(){
          seconds++;
          timer.textContent = formatTime(seconds);
          if (seconds >= 60){ 
            stopRecording(); 
          }
        }, 1000);

      } catch (err) {
        console.error("Microphone access denied or not supported:", err);
        hint.textContent = "Error: Please allow microphone access to record.";
      }
    }

    // 2. Stop Recording Functionality
    function stopRecording() {
      if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        clearInterval(interval);
      }
    }

    // 3. Audio Playback Control Toggle
    playToggle.addEventListener('click', function() {
      if (!audioAudio) return;
      
      if (audioAudio.paused) {
        audioAudio.play();
        playToggle.innerHTML = '<svg viewBox="0 0 10 10"><rect x="1" y="0" width="3" height="10" fill="#1D2A44"/><rect x="6" y="0" width="3" height="10" fill="#1D2A44"/></svg>'; // Pause Icon
      } else {
        audioAudio.pause();
        playToggle.innerHTML = '<svg viewBox="0 0 10 10"><polygon points="1,0 10,5 1,10" fill="#1D2A44"/></svg>'; // Play Icon
      }
    });

    // Event Listeners for UI buttons
    startBtn.addEventListener('click', startRecording);
    micBtn.addEventListener('click', function() {
      if (panel.classList.contains('state-idle')) {
        startRecording();
      }
    });
    
    stopBtn.addEventListener('click', stopRecording);

    reRecordBtn.addEventListener('click', function(){
      if (audioAudio) {
        audioAudio.pause();
        audioAudio = null;
      }
      setState('idle');
      hint.textContent = 'Tap the microphone to begin';
      timer.textContent = '0:00';
    });
  })();

  // 4. Send audio to server when "Continue" is clicked
    var continueBtn = document.getElementById('continueBtn');
    
    continueBtn.addEventListener('click', function(e) {
      // If they haven't recorded anything, let them pass normally or prompt them
      if (!audioBlob) {
        return; // Let them click through, or you can add an alert('Please record first');
      }
      
      // Stop the link from jumping immediately so we can upload the file first
      e.preventDefault();
      hint.textContent = "Saving your recording...";

      // Pack the audio blob data into a standard form layout
      var formData = new FormData();
      formData.append('audio_data', audioBlob);

      // Send it to our background handler using Fetch
      fetch('save_audio.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          // Upload successful! Now safely proceed to details.php
          window.location.href = 'details.php';
        } else {
          hint.textContent = "Error saving audio. Please try again.";
        }
      })
      .catch(err => {
        console.error("Upload error:", err);
        hint.textContent = "Network error. Moving forward anyway...";
        // Fallback: proceed even if local upload has a hiccup
        window.location.href = 'details.php';
      });
    });
</script>

</body>
</html>
