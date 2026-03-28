<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    ticket: Object,
});

const statusLabel = (status) => {
    const map = { open: 'Aberto', in_progress: 'Em andamento', closed: 'Fechado' };
    return map[status] || status;
};

const statusColor = (status) => {
    const map = {
        open: 'bg-orange-100 text-orange-700',
        in_progress: 'bg-blue-100 text-blue-700',
        closed: 'bg-green-100 text-green-700',
    };
    return map[status] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head :title="ticket.title" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('tickets.index')" class="text-orange-400 hover:text-orange-600 text-sm">
                    ← Voltar
                </Link>
                <h2 class="text-xl font-bold text-orange-700">🐾 Detalhe do Ticket</h2>
            </div>
        </template>

        <div class="py-8 max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow p-6 border border-orange-100 space-y-4">

                <div class="flex items-start justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ ticket.title }}</h3>
                    <span :class="['text-xs font-semibold px-3 py-1 rounded-full', statusColor(ticket.status)]">
                        {{ statusLabel(ticket.status) }}
                    </span>
                </div>

                <div class="text-sm text-gray-500 space-y-1">
                    <p><span class="font-semibold text-gray-700">Empresa:</span> {{ ticket.project?.company?.name }}</p>
                    <p><span class="font-semibold text-gray-700">Projeto:</span> {{ ticket.project?.name }}</p>
                    <p><span class="font-semibold text-gray-700">Criado por:</span> {{ ticket.user?.name }}</p>
                </div>

                <div v-if="ticket.description" class="bg-orange-50 rounded-xl p-4 text-sm text-gray-700">
                    {{ ticket.description }}
                </div>

                <div v-if="ticket.ticket_detail" class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 space-y-1">
                    <p class="font-semibold text-gray-800 mb-2">📋 Detalhes do Serviço</p>
                    <p><span class="font-semibold">Tipo:</span> {{ ticket.ticket_detail.service_type }}</p>
                    <p><span class="font-semibold">Notas:</span> {{ ticket.ticket_detail.notes }}</p>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
