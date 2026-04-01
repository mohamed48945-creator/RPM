/* ==============================================
   🏎️ RPM MOTORSPORTS - CORE ENGINE (JS ES6+)
   Project: RPM Motorcycle Showroom
   Description: Tactical UI Interactions & Logistics
   ============================================== */

// Global Indicators
let cartBadgeElements = document.querySelectorAll('.cart-badge');

// Ignite System on DOM Load
document.addEventListener('DOMContentLoaded', initRPM);

function initRPM() {
    initEngineSparks(); // بديلة الـ Floating Dots (شرارات عادم)
    initPowerOnSequence(); // شاشة التحميل
    initPrecisionCursor(); // المؤشر المخصص
    initAdaptiveNavbar(); // تأثير التمرير
}

// 1. Engine Sparks Effect (Formerly Floating Dots)
// تم تغيير الألوان للأحمر والأبيض والرمادي لمحاكاة شرارات المحرك
function initEngineSparks() {
    const container = document.querySelector('.floating-dots');
    if (!container) return;

    const rpmColors = ['#ff0000', '#ffffff', '#333333', '#ff4d00'];
    
    function createSpark() {
        const spark = document.createElement('div');
        spark.className = 'dot';
        spark.style.left = Math.random() * 100 + 'vw';
        spark.style.top = Math.random() * 100 + 'vh';
        spark.style.animationDuration = (Math.random() * 2 + 1) + 's'; // حركة أسرع
        spark.style.backgroundColor = rpmColors[Math.floor(Math.random() * rpmColors.length)];
        spark.style.boxShadow = `0 0 10px ${spark.style.backgroundColor}`;
        container.appendChild(spark);

        setTimeout(() => spark.remove(), 3000); // إزالة أسرع لمنع استهلاك الذاكرة
    }

    // Initial sparks cycle
    for (let i = 0; i < 20; i++) createSpark();
    setInterval(createSpark, 150); // وتيرة أسرع تناسب الـ RPM العالي
}

// 2. Power-On Sequence (Loading Screen)
function initPowerOnSequence() {
    window.addEventListener('load', () => {
        // إضافة تأخير بسيط لمحاكاة "فحص الأنظمة"
        setTimeout(() => {
            document.body.classList.add('loaded');
            console.log("RPM_SYSTEMS: ONLINE");
        }, 1500);
    });
}

// 3. Precision Tactical Cursor
function initPrecisionCursor() {
    const cursor = document.querySelector('.custom-cursor');
    if (!cursor) return;

    document.addEventListener('mousemove', (e) => {
        // حركة سلسة للمؤشر
        requestAnimationFrame(() => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });
    });
}

// 4. Adaptive Navbar (Scroll Intelligence)
function initAdaptiveNavbar() {
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('nav1') || document.querySelector('.admin-navbar');
        if (!nav) return;

        if (window.pageYOffset > 50) {
            nav.style.background = 'rgba(5, 5, 5, 0.95)'; // أسود قاتم
            nav.style.borderBottom = '1px solid #ff0000'; // خط أحمر رفيع عند التمرير
            nav.style.backdropFilter = 'blur(15px)';
        } else {
            nav.style.background = 'transparent';
            nav.style.borderBottom = '1px solid transparent';
            nav.style.backdropFilter = 'none';
        }
    });
}

// 5. Smart Unit Acquisition (Add to Cart)
function smartAddToCart(productId, button) {
    // Feedback مرئي فوري
    button.style.transform = 'scale(0.9) skewX(-10deg)';
    button.classList.add('processing');

    fetch(`cart.php?add=${productId}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                triggerNitroToast(); // تنبيه بنمط الـ Nitro
                updateLogisticsBadges(data.cart_count);
                confirmUnitLocked(button);
            }
        })
        .catch(err => {
            console.error("SYSTEM_FAILURE:", err);
            button.style.background = '#333';
        })
        .finally(() => {
            button.classList.remove('processing');
        });
}

// 6. Nitro Toast Notification
function triggerNitroToast() {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.innerHTML = '<i class="fas fa-check-circle"></i> UNIT_LOCKED_IN_GARAGE';
        toast.classList.add('active');
        setTimeout(() => toast.classList.remove('active'), 2500);
    }
}

// 7. Update Logistics Badges
function updateLogisticsBadges(count) {
    cartBadgeElements.forEach(badge => {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
        
        // تأثير نبض أحمر عند التحديث
        badge.animate([
            { transform: 'scale(1)', boxShadow: '0 0 0 red' },
            { transform: 'scale(1.3)', boxShadow: '0 0 15px red' },
            { transform: 'scale(1)', boxShadow: '0 0 0 red' }
        ], { duration: 400 });
    });
}

// 8. Confirm Unit Locked (Button Success State)
function confirmUnitLocked(button) {
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-lock"></i> RESERVED';
    button.style.background = '#ffffff';
    button.style.color = '#ff0000';
    button.style.fontWeight = '900';

    setTimeout(() => {
        button.innerHTML = originalContent;
        button.style.background = '';
        button.style.color = '';
        button.style.transform = 'skewX(-10deg)'; // الحفاظ على الميلان الرياضي
    }, 2000);
}

// Export for Global Showroom Use
window.smartAddToCart = smartAddToCart;