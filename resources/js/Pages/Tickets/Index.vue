<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    tickets: Array,
    filters: Object,
});

const status = ref(props.filters?.status ?? '');

watch(status, (val) => {
    router.get(route('tickets.index'), { status: val }, { preserveState: true, replace: true });
});

const statusLabel = (status) => {
    const map = { open: 'Aberto', in_progress: 'Em andamento', closed: 'Fechado' };
    return map[status] || status;
};

const statusColor = (status) => {
    const map = {
        open: 'status-open',
        in_progress: 'status-progress',
        closed: 'status-closed',
    };
    return map[status] || 'status-open';
};
</script>

<template>
    <Head title="Tickets" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between py-2">
                <div>
                    <h2 class="text-2xl font-bold text-orange-400 flex items-center gap-2">
                        🐾 Tickets de Serviço
                    </h2>
                    <p class="text-slate-400 text-sm mt-1">{{ tickets.length }} ticket(s) encontrado(s)</p>
                </div>
                <div class="flex items-center gap-3">
                    <select v-model="status" class="filter-select">
                        <option value="">Todos os status</option>
                        <option value="open">Aberto</option>
                        <option value="in_progress">Em andamento</option>
                        <option value="closed">Fechado</option>
                    </select>
                    <Link :href="route('tickets.create')" class="btn-primary">
                        + Novo Ticket
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div v-if="tickets.length === 0" class="empty-state">
                <span class="text-5xl mb-4 block">🐶</span>
                <p class="text-slate-400 text-lg">Nenhum ticket encontrado.</p>
                <Link :href="route('tickets.create')" class="btn-primary mt-4 inline-block">Criar primeiro ticket</Link>
            </div>

            <div v-else class="tickets-grid">
                <Link
                    v-for="ticket in tickets"
                    :key="ticket.id"
                    :href="route('tickets.show', ticket.id)"
                    class="ticket-card"
                >
                    <div class="card-top">
                        <span :class="['status-badge', statusColor(ticket.status)]">
                            {{ statusLabel(ticket.status) }}
                        </span>
                        <span class="text-slate-500 text-xs">#{{ ticket.id }}</span>
                    </div>

                    <h3 class="card-title">{{ ticket.title }}</h3>

                    <p v-if="ticket.description" class="card-desc">
                        {{ ticket.description }}
                    </p>

                    <div class="card-footer">
                        <div class="card-meta">
                            <span class="meta-tag">🏢 {{ ticket.project?.company?.name }}</span>
                            <span class="meta-tag">📁 {{ ticket.project?.name }}</span>
                        </div>
                        <div class="card-user">
                            <div class="user-avatar">{{ ticket.user?.name?.charAt(0) }}</div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.tickets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.25rem;
}

.ticket-card {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 1.5rem;
    border-radius: 16px;
    background: rgba(255,255,255,0.04);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 4px 24px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.06);
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    cursor: pointer;
    text-decoration: none;
}
.ticket-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(249,115,22,0.2);
    border-color: rgba(249,115,22,0.25);
}

.card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #f1f5f9;
    line-height: 1.4;
}

.card-desc {
    font-size: 0.82rem;
    color: #64748b;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.card-footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.card-meta {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.meta-tag {
    font-size: 0.72rem;
    color: #475569;
}

.user-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f97316, #ea580c);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; color: white;
    box-shadow: 0 0 10px rgba(249,115,22,0.3);
}

.status-badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.status-open    { background: rgba(249,115,22,0.15); color: #fb923c; border: 1px solid rgba(249,115,22,0.25); }
.status-progress{ background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.25); }
.status-closed  { background: rgba(34,197,94,0.15);  color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }

.filter-select {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    color: #94a3b8;
    font-size: 0.85rem;
    font-family: inherit;
    outline: none;
    cursor: pointer;
    transition: border-color 0.2s;
}
.filter-select:focus { border-color: rgba(249,115,22,0.5); }
.filter-select option { background: #1e1b2e; }

.btn-primary {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.6rem 1.25rem;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(249,115,22,0.35);
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 25px rgba(249,115,22,0.5);
}

.empty-state {
    text-align: center;
    padding: 5rem 2rem;
}
</style>
