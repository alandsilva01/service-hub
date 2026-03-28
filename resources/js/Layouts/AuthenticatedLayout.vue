<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showDropdown = ref(false);
const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <div class="app-bg min-h-screen">
        <!-- Grid overlay -->
        <div class="grid-overlay"></div>
        <!-- Orbs -->
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        <!-- Navbar -->
        <nav class="glass-nav sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <Link href="/tickets" class="flex items-center gap-2 group">
                    <span class="text-xl">🐾</span>
                    <span class="font-bold text-orange-400 tracking-wide group-hover:text-orange-300 transition">ServiceHub</span>
                </Link>

                <div class="flex items-center gap-6">
                    <Link href="/tickets" class="nav-link">Tickets</Link>
                    <div class="relative">
                        <button @click="showDropdown = !showDropdown" class="flex items-center gap-2 nav-link">
                            <div class="user-avatar">{{ user.name.charAt(0) }}</div>
                            <span class="hidden sm:block text-sm">{{ user.name }}</span>
                            <svg class="w-3 h-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div v-if="showDropdown" class="dropdown">
                            <Link href="/profile" class="dropdown-item">Perfil</Link>
                            <Link href="/logout" method="post" as="button" class="dropdown-item danger">Sair</Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="relative z-10 max-w-6xl mx-auto px-6">
            <header v-if="$slots.header" class="pt-8 pb-2">
                <slot name="header" />
            </header>
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&display=swap');

.app-bg {
    font-family: 'Sora', sans-serif;
    background-color: #0d0d14;
    position: relative;
    overflow-x: hidden;
    color: #e2e8f0;
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
    position: fixed;
    border-radius: 50%;
    filter: blur(120px);
    pointer-events: none;
    z-index: 0;
}
.orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(249,115,22,0.12), transparent 70%);
    top: -200px; left: -200px;
}
.orb-2 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(124,58,237,0.1), transparent 70%);
    bottom: -150px; right: -150px;
}

.glass-nav {
    position: relative;
    z-index: 50;
    background: rgba(13,13,20,0.8);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.nav-link {
    font-size: 0.875rem;
    color: #94a3b8;
    font-weight: 500;
    transition: color 0.15s;
    text-decoration: none;
    background: none;
    border: none;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.nav-link:hover { color: #f97316; }

.user-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f97316, #ea580c);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; color: white;
    box-shadow: 0 0 12px rgba(249,115,22,0.35);
}

.dropdown {
    position: absolute;
    right: 0; top: calc(100% + 8px);
    width: 160px;
    background: rgba(18,15,30,0.97);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
}

.dropdown-item {
    display: block;
    padding: 10px 16px;
    font-size: 13px;
    color: #94a3b8;
    transition: background 0.15s, color 0.15s;
    cursor: pointer;
    background: none;
    border: none;
    font-family: inherit;
    width: 100%;
    text-align: left;
    text-decoration: none;
}
.dropdown-item:hover { background: rgba(249,115,22,0.08); color: #f97316; }
.dropdown-item.danger { color: #f87171; }
.dropdown-item.danger:hover { background: rgba(248,113,113,0.08); color: #f87171; }
</style>
