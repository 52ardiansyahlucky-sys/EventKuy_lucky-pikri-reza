import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Ambient particles + subtle parallax (no heavy libraries)
(function () {
    const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return;

    const canvas = document.getElementById('event-particles');
    if (!canvas) return;


    const ctx = canvas.getContext('2d', { alpha: true });
    let w = 0;
    let h = 0;
    let dpr = Math.min(2, window.devicePixelRatio || 1);

    const rand = (min, max) => min + Math.random() * (max - min);

    const particles = [];
    const config = {
        count: 56,
        speedMin: 3,
        speedMax: 16,
        sizeMin: 0.8,
        sizeMax: 2.6,
    };

    function resize() {
        const rect = canvas.getBoundingClientRect();
        w = Math.max(1, Math.floor(rect.width));
        h = Math.max(1, Math.floor(rect.height));
        canvas.width = Math.floor(w * dpr);
        canvas.height = Math.floor(h * dpr);
        canvas.style.width = w + 'px';
        canvas.style.height = h + 'px';
        if (particles.length === 0) init();
    }

    function init() {
        particles.length = 0;
        const area = w * h;
        const target = Math.max(28, Math.min(config.count, Math.floor(area / 22000)));
        config.count = target;

        for (let i = 0; i < config.count; i++) {
            particles.push({
                x: rand(0, w),
                y: rand(0, h),
                vx: rand(-0.35, 0.35),
                vy: rand(0.15, 0.75),
                z: rand(0.15, 1),
                r: rand(config.sizeMin, config.sizeMax),
                a: rand(0.08, 0.32),
                hue: i % 2 === 0 ? rand(210, 260) : rand(270, 310),
            });
        }
    }

    function draw(t) {
        if (!ctx) return;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.save();
        ctx.scale(dpr, dpr);

        const time = t * 0.001;
        const scrollY = window.scrollY || 0;
        const par = Math.max(-18, Math.min(18, scrollY * 0.02));

        for (const p of particles) {
            p.y += p.vy * p.z;
            p.x += p.vx * p.z;

            // gentle drift loop
            if (p.y > h + 10) {
                p.y = -10;
                p.x = rand(0, w);
            }
            if (p.x < -10) p.x = w + 10;
            if (p.x > w + 10) p.x = -10;

            const glow = 1 + 0.25 * Math.sin(time + p.z * 6);
            const rr = p.r * p.z * glow;

            const x = p.x + par * p.z * 0.25;
            const y = p.y + Math.sin(time * 0.35 + p.z * 8) * p.z * 6;

            ctx.beginPath();
            ctx.fillStyle = `hsla(${p.hue}, 90%, 70%, ${p.a})`;
            ctx.arc(x, y, rr, 0, Math.PI * 2);
            ctx.fill();

            // soft ring
            ctx.beginPath();
            ctx.strokeStyle = `hsla(${p.hue}, 90%, 80%, ${p.a * 0.35})`;
            ctx.lineWidth = 1;
            ctx.arc(x, y, rr * 2.0, 0, Math.PI * 2);
            ctx.stroke();
        }

        ctx.restore();
        requestAnimationFrame(draw);
    }

    let raf;
    function start() {
        cancelAnimationFrame(raf);
        raf = requestAnimationFrame(draw);
    }

    // Init
    resize();
    start();

    window.addEventListener('resize', () => {
        dpr = Math.min(2, window.devicePixelRatio || 1);
        resize();
    });
})();





Alpine.start();




