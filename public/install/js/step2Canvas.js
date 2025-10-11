const canvas = document.getElementById('bgCanvas');
    const ctx = canvas.getContext('2d');
    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    const colors = ['#11676a', '#1cabb0', '#3ddbe1'];
    const circles = [];
    for (let i = 0; i < 15; i++) {
        circles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: 80 + Math.random() * 100,
        dx: (Math.random() - 0.5) * 0.8,
        dy: (Math.random() - 0.5) * 0.8,
        color: colors[Math.floor(Math.random() * colors.length)],
        });
    }
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let c of circles) {
        c.x += c.dx; c.y += c.dy;
        if (c.x < -c.r || c.x > canvas.width + c.r) c.dx *= -1;
        if (c.y < -c.r || c.y > canvas.height + c.r) c.dy *= -1;
        const gradient = ctx.createRadialGradient(c.x, c.y, 0, c.x, c.y, c.r);
        gradient.addColorStop(0, c.color + "AA");
        gradient.addColorStop(1, c.color + "00");
        ctx.fillStyle = gradient;
        ctx.beginPath(); ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2); ctx.fill();
        }
        requestAnimationFrame(animate);
    }
    animate();