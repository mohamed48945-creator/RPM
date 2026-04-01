<?php include("./inc/header.php"); ?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=Barlow+Condensed:ital,wght@0,400;0,700;1,900&display=swap" rel="stylesheet">

<style>
    :root {
        --rpm-red: #ff0000;
        --rpm-black: #050505;
        --rpm-dark: #000000;
    }

    .hero-viewport {
        height: 100vh;
        width: 100%;
        background-color: var(--rpm-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        font-family: 'Barlow Condensed', sans-serif;
    }

    .hero-bg-grid {
        position: absolute;
        width: 200%;
        height: 200%;
        background-image: 
            linear-gradient(rgba(255, 0, 0, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 0, 0, 0.05) 1px, transparent 1px);
        background-size: 60px 60px;
        transform: perspective(500px) rotateX(60deg);
        top: -50%;
        animation: grid-move 20s linear infinite;
        z-index: 1;
    }

    @keyframes grid-move {
        0% { transform: perspective(500px) rotateX(60deg) translateY(0); }
        100% { transform: perspective(500px) rotateX(60deg) translateY(60px); }
    }

    .hero-content {
        position: relative;
        z-index: 10;
        text-align: center;
        max-width: 900px;
        padding: 0 20px;
    }

    .bg-text-shadow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: clamp(10rem, 30vw, 25rem);
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        color: rgba(255, 0, 0, 0.03);
        z-index: -1;
        pointer-events: none;
        letter-spacing: -10px;
    }

    .hero-content h1 {
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        font-size: clamp(2.5rem, 8vw, 5rem);
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 20px;
        line-height: 1;
    }

    .hero-content h1 span.rpm-highlight {
        color: var(--rpm-red);
        display: inline-block;
        position: relative;
        padding: 0 10px;
    }

    .hero-content h1 span.rpm-highlight::after {
        content: '';
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 8px;
        background: var(--rpm-red);
        z-index: -1;
        transform: skewX(-20deg);
        opacity: 0.5;
    }

    .hero-content p {
        font-size: 1.4rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 4px;
        margin-bottom: 40px;
        font-weight: 400;
    }

    .cta-container {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .rpm-btn {
        padding: 18px 45px;
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        font-size: 1rem;
        text-transform: uppercase;
        text-decoration: none;
        letter-spacing: 2px;
        position: relative;
        transition: 0.4s;
        border: none;
        cursor: pointer;
    }

    .btn-primary-rpm {
        background: var(--rpm-red);
        color: #fff;
        clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%);
    }

    .btn-primary-rpm:hover {
        background: #fff;
        color: #000;
        transform: scale(1.05) skewX(-5deg);
        box-shadow: 0 0 30px rgba(255, 0, 0, 0.4);
    }

    .corner-detail {
        position: absolute;
        width: 100px;
        height: 100px;
        border: 2px solid var(--rpm-red);
        opacity: 0.3;
    }
    .top-left { top: 40px; left: 40px; border-right: none; border-bottom: none; }
    .bottom-right { bottom: 40px; right: 40px; border-left: none; border-top: none; }

    .scanner-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--rpm-red), transparent);
        box-shadow: 0 0 10px var(--rpm-red);
        animation: scan 4s linear infinite;
        opacity: 0.5;
        z-index: 5;
    }

    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }
</style>

<div class="hero-viewport">
    <div class="hero-bg-grid"></div>
    <div class="scanner-line"></div>
    <div class="bg-text-shadow">RPM</div>
    
    <div class="corner-detail top-left"></div>
    <div class="corner-detail bottom-right"></div>

    <div class="hero-content">
        <p data-aos="fade-down">Est. 2026 | High Performance Division</p>
        <h1 data-aos="zoom-in">
            Welcome To <br>
            <span class="rpm-highlight">RPM</span> Showroom
        </h1>
        <p class="sub-tag">Rev Up Your Passion: Find the Best Motorcycles and Gear Here</p>
        
        <div class="cta-container" data-aos="fade-up">
            <a href="products.php" class="rpm-btn btn-primary-rpm">
                Explore Bikes <i class="fas fa-chevron-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<?php include("./inc/footer.php"); ?>