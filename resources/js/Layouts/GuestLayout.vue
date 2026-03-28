<script setup>
import { ref } from 'vue';

const card = ref(null);
const handleMouseMove = (e) => {
    if (!card.value) return;
    const rect = card.value.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    card.value.style.setProperty('--mx', `${x}%`);
    card.value.style.setProperty('--my', `${y}%`);
    const rotateX = ((e.clientY - rect.top) / rect.height - 0.5) * -6;
    const rotateY = ((e.clientX - rect.left) / rect.width - 0.5) * 6;
    card.value.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
};
const handleMouseLeave = () => {
    if (!card.value) return;
    card.value.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg)';
};
</script>

<template>
    <div class="guest-bg">
        <div class="grid-overlay"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <div class="center-wrap">
            <!-- Logo -->
            <div class="logo-block">
                <div class="logo-ring">🐾</div>
                <p class="logo-label">ServiceHub PetShop</p>
            </div>

            <!-- Card glass com reflexo -->
            <div
                ref="card"
                class="glass-card"
                @mousemove="handleMouseMove"
                @mouseleave="handleMouseLeave"
            >
                <div class="card-glare"></div>
                <div class="card-body">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&display=swap');

.guest-bg {
    font-family: 'Sora', sans-serif;
    min-height: 100vh;
    background-color: #0d0d14;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 2rem;
}

.grid-overlay {
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: 0;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    pointer-events: none;
    z-index: 0;
}
.orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(249,115,22,0.18), transparent 70%);
    top: -200px; left: -150px;
    animation: float 9s ease-in-out infinite;
}
.orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(124,58,237,0.15), transparent 70%);
    bottom: -150px; right: -100px;
    animation: float 11s ease-in-out infinite reverse;
}
.orb-3 {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(245,158,11,0.1), transparent 70%);
    top: 50%; left: 60%;
    animation: float 7s ease-in-out infinite;
}

.center-wrap {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.75rem;
    width: 100%;
    max-width: 420px;
}

.logo-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    animation: fadein 0.6s ease forwards;
}

.logo-ring {
    width: 68px; height: 68px;
    border-radius: 50%;
    background: rgba(249,115,22,0.12);
    border: 1.5px solid rgba(249,115,22,0.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem;
    box-shadow: 0 0 30px rgba(249,115,22,0.2), inset 0 1px 0 rgba(255,255,255,0.1);
}

.logo-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(249,115,22,0.7);
    letter-spacing: 0.15em;
    text-transform: uppercase;
}

.glass-card {
    position: relative;
    width: 100%;
    border-radius: 22px;
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(28px);
    -webkit-backdrop-filter: blur(28px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow:
        0 8px 40px rgba(0,0,0,0.5),
        inset 0 1px 0 rgba(255,255,255,0.1),
        inset 0 -1px 0 rgba(0,0,0,0.15);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    will-change: transform;
    overflow: hidden;
    animation: slidein 0.7s ease forwards;
}

.glass-card:hover {
    box-shadow:
        0 20px 60px rgba(0,0,0,0.55),
        0 0 0 1px rgba(249,115,22,0.12),
        inset 0 1px 0 rgba(255,255,255,0.12);
}

.card-glare {
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: radial-gradient(
        circle at var(--mx, 50%) var(--my, 30%),
        rgba(255,255,255,0.08) 0%,
        transparent 55%
    );
    pointer-events: none;
    z-index: 1;
}

.card-body {
    position: relative;
    z-index: 2;
    padding: 2.5rem;
}

/* Inputs dentro do card */
.card-body :deep(input) {
    background: rgba(255,255,255,0.06) !important;
    border: 1px solid rgba(255,255,255,0.12) !important;
    border-radius: 10px !important;
    color: #f1f5f9 !important;
    font-family: 'Sora', sans-serif !important;
    transition: border-color 0.2s, box-shadow 0.2s !important;
}
.card-body :deep(input:focus) {
    border-color: rgba(249,115,22,0.5) !important;
    box-shadow: 0 0 0 3px rgba(249,115,22,0.12) !important;
    outline: none !important;
}
.card-body :deep(input::placeholder) { color: rgba(255,255,255,0.25) !important; }

.card-body :deep(label) { color: #94a3b8 !important; font-family: 'Sora', sans-serif !important; }
.card-body :deep(.text-gray-600) { color: #94a3b8 !important; }
.card-body :deep(.text-gray-400) { color: #64748b !important; }

.card-body :deep(button[type=submit]) {
    background: linear-gradient(135deg, #f97316, #ea580c) !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-family: 'Sora', sans-serif !important;
    box-shadow: 0 4px 20px rgba(249,115,22,0.4) !important;
    transition: all 0.2s !important;
    letter-spacing: 0.02em !important;
}
.card-body :deep(button[type=submit]:hover) {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 28px rgba(249,115,22,0.55) !important;
}

/* Hint de acesso */
.card-body :deep(.access-hint) {
    margin-top: 1.5rem;
    border-radius: 12px;
    border: 1px solid rgba(249,115,22,0.2);
    background: rgba(249,115,22,0.07);
    padding: 1rem 1.25rem;
    font-size: 0.82rem;
    color: #fdba74;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-18px); }
}
@keyframes fadein {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slidein {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
