<?php
$page_title = "About RPM | Performance Redefined";
include("./inc/header.php");
?>

<link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@400;700&family=Exo+2:wght@300;600;900&display=swap" rel="stylesheet">

<style>
    :root {
        --rpm-red: #ff0000;
        --rpm-dark: #000000;
        --rpm-card: #0a0a0a;
    }

    body {
        background-color: var(--rpm-dark);
        color: #ffffff;
        font-family: 'Exo 2', sans-serif;
    }

    .rpm-viewport {
        position: relative;
        padding: 100px 0;
        overflow: hidden;
    }


    .brand-glitch {
        font-family: 'Syncopate', sans-serif;
        font-weight: 900;
        font-size: clamp(4rem, 15vw, 10rem);
        text-transform: uppercase;
        letter-spacing: -5px;
        line-height: 0.8;
        background: linear-gradient(180deg, #fff 30%, var(--rpm-red) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        opacity: 0.1;
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        pointer-events: none;
    }

    .main-content-layout {
        position: relative;
        z-index: 2;
        border-top: 1px solid rgba(255, 0, 0, 0.3);
        border-bottom: 1px solid rgba(255, 0, 0, 0.3);
        padding: 60px 0;
        background: linear-gradient(90deg, transparent, rgba(255,0,0,0.02), transparent);
    }

    .tagline-bar {
        font-family: 'Syncopate', sans-serif;
        color: var(--rpm-red);
        font-size: 0.8rem;
        letter-spacing: 15px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 80px;
        text-shadow: 0 0 10px var(--rpm-red);
    }

    .info-panel {
        background: transparent;
        border-left: 2px solid var(--rpm-red);
        padding-left: 30px;
        position: relative;
    }

    .info-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: -6px;
        width: 10px;
        height: 10px;
        background: var(--rpm-red);
    }

    .info-title {
        font-weight: 900;
        font-size: 2.5rem;
        color: #fff;
        margin-bottom: 25px;
        text-transform: uppercase;
        font-style: italic;
    }

    .info-para {
        font-size: 1.2rem;
        line-height: 1.7;
        color: #888;
        max-width: 600px;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin-top: 40px;
    }

    .feature-row {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background: #050505;
        border: 1px solid #111;
        transition: 0.3s;
        clip-path: polygon(0 0, 95% 0, 100% 30%, 100% 100%, 5% 100%, 0 70%);
    }

    .feature-row:hover {
        border-color: var(--rpm-red);
        transform: scale(1.02);
    }

    .feature-row i {
        font-size: 2rem;
        color: var(--rpm-red);
        margin-right: 25px;
    }

    .feature-row h5 {
        font-weight: 700;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .action-button {
        display: inline-block;
        padding: 20px 50px;
        background: var(--rpm-red);
        color: #fff;
        text-transform: uppercase;
        font-weight: 900;
        letter-spacing: 3px;
        text-decoration: none;
        clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%);
        transition: 0.3s;
    }

    .action-button:hover {
        background: #fff;
        color: #000;
        transform: skewX(-5deg);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 80px;
    }

    .stat-card {
        text-align: left;
        padding: 30px;
        background: linear-gradient(180deg, #0a0a0a 0%, #000 100%);
        border-bottom: 2px solid #222;
    }

    .stat-card:hover {
        border-bottom-color: var(--rpm-red);
    }

    .stat-val {
        display: block;
        font-size: 3.5rem;
        font-weight: 900;
        color: #fff;
        line-height: 1;
    }

    .stat-txt {
        color: var(--rpm-red);
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 3px;
    }

    .image-overlap {
        position: relative;
        height: 100%;
    }

    .image-overlap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(1) brightness(0.6);
        transition: 0.5s;
    }

    .image-overlap:hover img {
        filter: grayscale(0) brightness(1);
    }

    .red-glow-line {
        height: 2px;
        width: 100px;
        background: var(--rpm-red);
        box-shadow: 0 0 15px var(--rpm-red);
        margin: 20px 0;
    }
</style>

<div class="rpm-viewport">
    <div class="bg-grid"></div>
    <div class="brand-glitch">RPM PERFORMANCE</div>

    <div class="container">
        <div class="text-center">
            <span class="tagline-bar">Beyond The Redline</span>
        </div>

        <div class="main-content-layout">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="info-panel">
                        <h2 class="info-title">The Speed Authority</h2>
                        <div class="red-glow-line"></div>
                        <p class="info-para">
                            We don't just sell bikes; we deliver adrenaline. Born for the open road and the thrill of the track, RPM was built for those who live life in the fast lane. Every machine in our showroom is hand-picked for performance and dominance.
                        </p>
                        
                        <div class="mt-5">
                            <a href="bikes.php" class="action-button">Browse Collection</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="feature-list px-lg-5">
                        <div class="feature-row">
                            <i class="fas fa-tools"></i>
                            <div>
                                <h5>Master Tuning</h5>
                                <p class="small text-secondary m-0">Precision engineering for maximum torque.</p>
                            </div>
                        </div>

                        <div class="feature-row" style="margin-left: 20px;">
                            <i class="fas fa-tachometer-alt"></i>
                            <div>
                                <h5>Pure Velocity</h5>
                                <p class="small text-secondary m-0">Unleash the beast with zero limits.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-val" data-target="1500">0</span>
                <span class="stat-txt">Riders Joined</span>
            </div>
            <div class="stat-card">
                <span class="stat-val" data-target="120">0</span>
                <span class="stat-txt">Elite Models</span>
            </div>
            <div class="stat-card">
                <span class="stat-val" data-target="100">0</span>
                <span class="stat-txt">Authentic %</span>
            </div>
            <div class="stat-card">
                <span class="stat-val" data-target="1">0</span>
                <span class="stat-txt">Top Gear Rank</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const counters = document.querySelectorAll('.stat-val');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    let count = 0;
                    const updateCount = () => {
                        const increment = target / 60;
                        if (count < target) {
                            count += increment;
                            counter.innerText = Math.ceil(count);
                            setTimeout(updateCount, 25);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(c => observer.observe(c));
    });
</script>

<?php include("./inc/footer.php"); ?>