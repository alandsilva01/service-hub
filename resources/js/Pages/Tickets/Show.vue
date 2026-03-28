<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({ ticket: Object });

const statusLabel = (s) => ({ open: 'Aberto', in_progress: 'Em andamento', closed: 'Fechado' }[s] || s);
const statusColor = (s) => ({ open: 'status-open', in_progress: 'status-progress', closed: 'status-closed' }[s] || 'status-open');

const enriched = () => {
    try { return props.ticket.detail?.enriched_data ? JSON.parse(props.ticket.detail.enriched_data) : null; }
    catch { return null; }
};
</script>

<template>
    <Head :title="ticket.title" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between py-2">
                <div>
                    <h2 class="text-2xl font-bold text-orange-400 flex items-center gap-2">🐾 Detalhe do Ticket</h2>
                    <p class="text-slate-400 text-sm mt-1">#{{ ticket.id }}</p>
                </div>
                <Link :href="route('tickets.index')" class="btn-secondary">← Voltar</Link>
            </div>
        </template>

        <div class="py-6 max-w-2xl mx-auto px-4 space-y-4">

            <div class="ticket-card">
                <div class="card-top">
                    <span :class="['status-badge', statusColor(ticket.status)]">{{ statusLabel(ticket.status) }}</span>
                    <span class="text-slate-500 text-xs">#{{ ticket.id }}</span>
                </div>
                <h3 class="card-title">{{ ticket.title }}</h3>
                <div class="card-meta">
                    <span class="meta-tag">🏢 {{ ticket.project?.company?.name }}</span>
                    <span class="meta-tag">📁 {{ ticket.project?.name }}</span>
                    <span class="meta-tag">👤 {{ ticket.user?.name }}</span>
                </div>
                <p v-if="ticket.description" class="card-desc">{{ ticket.description }}</p>
            </div>

            <div v-if="enriched()" class="ticket-card">
                <p class="section-title">📋 Dados do Anexo Processado</p>
                <div v-for="(val, key) in enriched()" :key="key" class="enriched-row">
                    <span class="enriched-key">{{ key }}</span>
                    <span class="enriched-val">{{ val }}</span>
                </div>
            </div>

            <div v-else-if="ticket.attachment_path" class="ticket-card muted">
                <p class="text-slate-400 text-sm">⏳ Anexo ainda sendo processado...</p>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.ticket-card {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 1.5rem;
    border-radius: 16px;
    background: rgba(255,255,255,0.04);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 4px 24px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.06);
}
.ticket-card.muted { opacity: 0.6; }
.card-top { display: flex; align-items: center; justify-content: space-between; }
.card-title { font-size: 1.1rem; font-weight: 600; color: #f1f5f9; }
.card-desc { font-size: 0.82rem; color: #64748b; line-height: 1.5; }
.card-meta { display: flex; flex-wrap: wrap; gap: 6px; }
.meta-tag { font-size: 0.72rem; color: #475569; }
.section-title { font-size: 0.85rem; font-weight: 600; color: #fb923c; }
.enriched-row { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4px 0; }
.enriched-key { font-size: 0.78rem; color: #94a3b8; text-transform: capitalize; }
.enriched-val { font-size: 0.78rem; color: #f1f5f9; font-weight: 500; }
.status-badge { font-size: 0.7rem; font-weight: 600; padding: 3px 10px; border-radius: 999px; text-transform: uppercase; }
.status-open    { background: rgba(249,115,22,0.15); color: #fb923c; border: 1px solid rgba(249,115,22,0.25); }
.status-progress{ background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.25); }
.status-closed  { background: rgba(34,197,94,0.15);  color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
.btn-secondary { font-size: 0.875rem; color: #fb923c; border: 1px solid rgba(249,115,22,0.3); padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; transition: all 0.2s; }
.btn-secondary:hover { background: rgba(249,115,22,0.1); }
</style>
