<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
    projects: Array,
});

const form = useForm({
    project_id:  props.ticket.project_id,
    title:       props.ticket.title,
    description: props.ticket.description ?? '',
    status:      props.ticket.status,
});

const submit = () => {
    form.patch(route('tickets.update', props.ticket.id));
};
</script>

<template>
    <Head title="Editar Ticket" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold text-orange-400">✏️ Editar Ticket #{{ ticket.id }}</h2>
        </template>

        <div class="py-6">
            <div class="glass-card">
                <div class="field">
                    <label class="field-label">Projeto</label>
                    <select v-model="form.project_id" class="field-input">
                        <option v-for="project in projects" :key="project.id" :value="project.id">
                            {{ project.company?.name }} — {{ project.name }}
                        </option>
                    </select>
                    <span v-if="form.errors.project_id" class="field-error">{{ form.errors.project_id }}</span>
                </div>

                <div class="field">
                    <label class="field-label">Título</label>
                    <input v-model="form.title" type="text" class="field-input" placeholder="Título do ticket" />
                    <span v-if="form.errors.title" class="field-error">{{ form.errors.title }}</span>
                </div>

                <div class="field">
                    <label class="field-label">Descrição</label>
                    <textarea v-model="form.description" class="field-input" rows="4" placeholder="Descreva o problema ou serviço..."></textarea>
                </div>

                <div class="field">
                    <label class="field-label">Status</label>
                    <select v-model="form.status" class="field-input">
                        <option value="open">Aberto</option>
                        <option value="in_progress">Em andamento</option>
                        <option value="closed">Fechado</option>
                    </select>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Salvando...' : 'Salvar alterações' }}
                    </button>
                    <a :href="route('tickets.show', ticket.id)" class="btn-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.glass-card {
    background: rgba(255,255,255,0.04);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    max-width: 640px;
}

.field { display: flex; flex-direction: column; gap: 0.4rem; }

.field-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.field-input {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 0.65rem 1rem;
    color: #f1f5f9;
    font-size: 0.9rem;
    font-family: inherit;
    transition: border-color 0.2s;
    outline: none;
    width: 100%;
}
.field-input:focus { border-color: rgba(249,115,22,0.5); }
.field-input option { background: #1e1b2e; }

.field-error { font-size: 0.78rem; color: #f87171; }

.btn-primary {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.6rem 1.25rem;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 25px rgba(249,115,22,0.5); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-secondary {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.6rem 1.25rem;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-block;
}
.btn-secondary:hover { background: rgba(255,255,255,0.08); color: #f1f5f9; }
</style>
