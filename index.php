<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGRI-PACT | AI-Powered Agricultural Auction</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Manrope:wght@500;700&family=Noto+Sans+Tamil:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #f8fafc;
            --deep-green: #064e3b;
            --emerald: #10b981;
            --lime: #84cc16;
            --text-dark: #1e293b;
        }
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }
        .tamil-text {
            font-family: 'Noto Sans Tamil', sans-serif;
        }
        
        /* Animated Background */
        .bg-animated {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url('https://images.unsplash.com/photo-1625246333195-78d9c38ad449?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center;
            background-size: cover;
            animation: zoomInOut 30s infinite alternate;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, rgba(6,78,59,0.85) 0%, rgba(16,185,129,0.6) 100%);
        }

        @keyframes zoomInOut {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        h1, h2, h3, .nav-link {
            font-family: 'Manrope', sans-serif;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .hero {
            padding: 120px 0 60px 0;
            color: white;
            position: relative;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            text-shadow: 2px 4px 10px rgba(0,0,0,0.3);
        }
        .btn-lime {
            background-color: var(--lime);
            color: var(--deep-green);
            font-weight: 700;
            border-radius: 12px;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }
        .btn-lime:hover {
            background-color: #65a30d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(132,204,22,0.4);
        }
        
        /* Marquee styling */
        .marquee-container {
            background: rgba(255,255,255,0.95);
            padding: 30px 0;
            margin-top: 50px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.2);
            border-top: 4px solid var(--lime);
            overflow: hidden;
            white-space: nowrap;
        }
        .marquee-img {
            height: 200px;
            width: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin: 0 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border: 3px solid white;
            display: inline-block;
        }

        /* Chatbot styling */
        .chatbot-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--lime);
            color: var(--deep-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.3s;
        }
        .chatbot-btn:hover {
            transform: scale(1.1);
        }
        .chat-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 300px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }
        .chat-header {
            background: var(--deep-green);
            color: white;
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }
        .chat-body {
            padding: 15px;
            height: 200px;
            overflow-y: auto;
            background: #f1f5f9;
        }
        .chat-options button {
            width: 100%;
            text-align: left;
            margin-bottom: 8px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="bg-animated"></div>
<div class="bg-overlay"></div>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-success fs-3" href="#">AGRI-PACT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ms-4 fw-semibold">
                <li class="nav-item"><a class="nav-link lang-text" href="index.php" data-en="Home" data-ta="முகப்பு" data-hi="होम">Home</a></li>
                <li class="nav-item"><a class="nav-link lang-text" href="#about" data-en="About" data-ta="பற்றி" data-hi="बारे में">About</a></li>
                <li class="nav-item"><a class="nav-link lang-text" href="register.php" data-en="Farmers" data-ta="விவசாயிகள்" data-hi="किसान">Farmers</a></li>
                <li class="nav-item"><a class="nav-link lang-text" href="login.php" data-en="Auctions" data-ta="ஏலங்கள்" data-hi="नीलामी">Auctions</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <select class="form-select form-select-sm me-3 border-success text-success fw-bold select-lang" onchange="changeLanguage(this.value)" style="width: 100px;">
                    <option value="en">English</option>
                    <option value="ta">தமிழ்</option>
                    <option value="hi">हिंदी</option>
                </select>
                <a href="login.php" class="btn btn-outline-success fw-bold rounded-pill px-4 me-2 lang-text" data-en="Login" data-ta="உள்நுழைய" data-hi="लॉग इन">Login</a>
                <a href="register.php" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm lang-text" data-en="Register" data-ta="பதிவு செய்" data-hi="रजिस्टर करें">Register</a>
            </div>
        </div>
    </div>
</nav>

<section class="hero text-center text-lg-start">
    <div class="container mt-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title mb-4 lang-text headline" data-en="Where Farmers Set the Value.<br><span class='text-warning'>Buyers Compete for It.</span>" data-ta="விவசாயிகளே விலையை நிர்ணயிக்கின்றனர்.<br><span class='text-warning text-wrap'>வாங்குபவர்கள் போட்டியிடுகிறார்கள்.</span>" data-hi="जहां किसान मूल्य तय करते हैं।<br><span class='text-warning text-wrap'>खरीदार प्रतिस्पर्धा करते हैं।</span>">Where Farmers Set the Value.<br><span class="text-warning">Buyers Compete for It.</span></h1>
                <p class="lead mb-5 fw-semibold fs-5 text-light lang-text" data-en="AGRI-PACT connects farmers directly with verified buyers through transparent auctions." data-ta="வெளிப்படையான ஏலங்கள் மூலம் விவசாயிகளை வாங்குபவர்களுடன் நேரடியாக இணைக்கிறோம்." data-hi="पारदर्शी नीलामी के माध्यम से किसानों को सीधे खरीदारों से जोड़ता है।" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">AGRI-PACT connects farmers directly with verified buyers through transparent auctions.</p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap">
                    <a href="register.php" class="btn btn-lime btn-lg shadow lang-text" data-en="Start Selling Now" data-ta="இப்போதே விற்க தொடங்குங்கள்" data-hi="अभी बेचना शुरू करें">Start Selling Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Farming Marquee Section -->
<div class="marquee-container shadow-sm border-bottom">
    <h3 class="text-center fw-bold text-success mb-4 lang-text" data-en="Discover India's Freshest Produce" data-ta="புதிய விவசாய விளைபொருட்கள்" data-hi="भारत की सबसे ताजी उपज खोजें">Discover India's Freshest Produce</h3>
    <marquee behavior="scroll" direction="left" scrollamount="8" onmouseover="this.stop();" onmouseout="this.start();">
        <img src="https://images.unsplash.com/photo-1518977676601-b53f82aba655?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Potatoes">
        <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Wheat Fields">
        <img src="https://images.unsplash.com/photo-1550828520-4cb496926fc9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Tractor Plowing">
        <img src="https://images.unsplash.com/photo-1588619623832-60281b37b127?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Mixed Vegetables">
        <img src="https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Carrots">
        <img src="https://images.unsplash.com/photo-1595856724083-d34e9bdcd2a4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Red Tomatoes">
        <img src="https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Farmers picking">
        <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="marquee-img" alt="Green Apples">
    </marquee>
</div>

<!-- Offline AI Voice Chatbot -->
<div class="chatbot-btn" onclick="toggleChat()" title="AI Assistant">💬</div>
<div class="chat-window d-flex shadow" id="chatWin">
    <div class="chat-header">
        <span>AGRI AI Voice</span>
        <span style="cursor:pointer;" onclick="toggleChat()">✖</span>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="mb-3">
            <span class="badge bg-success p-2 fs-6">வணக்கம்! நான் உங்கள் விவசாய உதவியாளர்.</span>
        </div>
        <div class="chat-options d-flex flex-column">
            <button class="btn btn-outline-success btn-sm" onclick="speakTamil('உங்கள் பயிரின் தரத்தை AI மூலம் சரிபார்க்கலாம்')">பயிர் தரம் சரிபார்க்க</button>
            <button class="btn btn-outline-success btn-sm" onclick="speakTamil('இன்றைய சந்தை விலை தக்காளி கிலோ 35 ரூபாய்')">இன்றைய விலை நிலவரம்</button>
            <button class="btn btn-outline-success btn-sm" onclick="speakTamil('புதிய ஏலத்தை தொடங்க உள்நுழையவும்')">ஏலம் தொடங்க</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Language Translation
    function changeLanguage(lang) {
        document.querySelectorAll('.lang-text, .nav-link').forEach(el => {
            if (el.getAttribute('data-' + lang)) {
                el.innerHTML = el.getAttribute('data-' + lang);
                if(lang === 'ta') {
                    el.classList.add('tamil-text');
                } else {
                    el.classList.remove('tamil-text');
                }
            }
        });
    }

    // Chatbot Toggle
    function toggleChat() {
        const chatWin = document.getElementById('chatWin');
        if (chatWin.style.display === 'flex') {
            chatWin.style.display = 'none';
        } else {
            chatWin.style.display = 'flex';
            speakTamil('வணக்கம்! நான் உங்கள் விவசாய உதவியாளர்.');
        }
    }

    // Offline Browser Speech API - NO EXTERNAL API REQUIRED
    function speakTamil(text) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ta-IN'; // Tamil India
            utterance.rate = 0.9; // Slightly slower for clarity
            utterance.pitch = 1.0;
            
            // Try to find a Tamil voice, else it falls back to default
            const voices = window.speechSynthesis.getVoices();
            const tamilVoice = voices.find(voice => voice.lang.includes('ta') || voice.lang.includes('ta-IN'));
            if(tamilVoice) utterance.voice = tamilVoice;
            
            window.speechSynthesis.speak(utterance);
        } else {
            alert("Your browser does not support Voice AI.");
        }
    }
    
    // Attempt loading voices initially
    window.speechSynthesis.onvoiceschanged = function() {
        window.speechSynthesis.getVoices();
    };
</script>
</body>
</html>
