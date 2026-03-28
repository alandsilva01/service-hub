<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ projects: Array });

const form = useForm({
    project_id: '',
    title: '',
    description: '',
    attachment: null,
});

const fileName = ref('');

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.attachment = file;
        fileName.value = file.name;
    }
};

const submit = () => {
    form.post(route('tickets.store'), {
        forceFormData: true,
    });
};

const card = ref(null);
const handleMouseMove = (e) => {
    if (!card.value) return;
    const rect = card.value.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    card.value.style.setProperty('--mx', `${x}%`);
    card.value.style.setProperty('--my', `${y}%`);
    const rotateX = ((e.clientY - rect.top) / rect.height - 0.5) * -8;
    const rotateY = ((e.clientX - rect.left) / rect.width - 0.5) * 8;
    card.value.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
};
const handleMouseLeave = () => {
    if (!card.value) return;
    card.value.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg)';
};
</script>

<template>
    <Head title="Novo Ticket" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="page-title">🐾 Novo Ticket</h2>
        </template>

        <div class="form-wrap">
            <div ref="card" class="glass-card" @mousemove="handleMouseMove" @mouseleave="handleMouseLeave">
                <div class="card-glare"></div>

                <form @submit.prevent="submit" class="form-body">

                    <div class="field-group">
                        <label class="field-label">Projeto</label>
                        <select v-model="form.project_id" class="glass-select">
                            <option value="" disabled>Selecione um projeto</option>
                            <option v-for="p in projects" :key="p.id" :value="p.id">
                                {{ p.company?.name }} — {{ p.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.project_id" class="field-error">{{ form.errors.project_id }}</p>
                    </div>

                    <div class="input-field">
                        <input v-model="form.title" type="text" required class="glass-input" />
                        <label>Título do ticket</label>
                        <p v-if="form.errors.title" class="field-error">{{ form.errors.title }}</p>
                    </div>

                    <div class="input-field textarea-field">
                        <textarea v-model="form.description" rows="4" class="glass-input"></textarea>
                        <label>Descrição (opcional)</label>
                    </div>

                    <!-- Upload -->
                    <div class="field-group">
                        <label class="field-label">Anexo (JSON ou TXT, opcional)</label>
                        <label class="upload-zone" :class="{ 'has-file': fileName }">
                            <input type="file" accept=".json,.txt" @change="onFileChange" class="hidden" />
                            <span v-if="!fileName" class="upload-placeholder">
                                📎 Clique para anexar arquivo
                            </span>
                            <span v-else class="upload-filename">
                                ✅ {{ fileName }}
                            </span>
                        </label>
                        <p v-if="form.errors.attachment" class="field-error">{{ form.errors.attachment }}</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" :disabled="form.processing" class="btn-submit">
                            <span v-if="form.processing">Criando...</span>
                            <span v-else>Criar Ticket →</span>
                        </button>
                        <Link :href="route('tickets.index')" class="btn-cancel">Cancelar</Link>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&display=swap');

.page-title { font-size: 1.5rem; font-weight: 700; color: #f97316; }

.form-wrap {
    display: flex;
    justify-content: center;
    padding: 2.5rem 0 4rem;
}

.glass-card {
    position: relative;
    width: 100%; max-width: 520px;
    border-radius: 20px;
    padding: 2.5rem;
    background: rgba(255,255,255,0.04);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    will-change: transform;
    overflow: hidden;
}
.glass-card:hover {
    box-shadow: 0 16px 48px rgba(0,0,0,0.5), 0 0 0 1px rgba(249,115,22,0.15), inset 0 1px 0 rgba(255,255,255,0.12);
}

.card-glare {
    position: absolute; inset: 0;
    border-radius: inherit;
    background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(255,255,255,0.07) 0%, transparent 60%);
    pointer-events: none; z-index: 1;
}

.form-body { position: relative; z-index: 2; display: flex; flex-direction: column; gap: 1.75rem; }

.input-field {
    position: relative;
    border-bottom: 1px solid rgba(255,255,255,0.15);
    padding-bottom: 4px;
}
.input-field label {
    position: absolute; top: 50%; left: 0;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.45); font-size: 0.95rem;
    pointer-events: none; transition: all 0.2s ease;
    font-family: 'Sora', sans-serif;
}
.textarea-field label { top: 12px; transform: none; }
.input-field:focus-within label,
.glass-input:valid ~ label {
    top: 0px; transform: translateY(-100%);
    font-size: 0.72rem; color: #f97316;
    letter-spacing: 0.05em; text-transform: uppercase;
}
.textarea-field:focus-within label {
    top: -18px; font-size: 0.72rem; color: #f97316;
    letter-spacing: 0.05em; text-transform: uppercase;
}
.input-field::after {
    content: ''; position: absolute; bottom: -1px; left: 0;
    width: 0%; height: 1px; background: #f97316; transition: width 0.3s ease;
}
.input-field:focus-within::after { width: 100%; }

.glass-input {
    width: 100%; height: 40px;
    background: transparent; border: none; outline: none;
    font-size: 0.95rem; color: #f1f5f9;
    font-family: 'Sora', sans-serif; caret-color: #f97316;
}
textarea.glass-input { height: auto; resize: vertical; padding-top: 8px; }

.field-group { display: flex; flex-direction: column; gap: 6px; }
.field-label {
    font-size: 0.72rem; color: #f97316;
    text-transform: uppercase; letter-spacing: 0.05em;
    font-family: 'Sora', sans-serif;
}

.glass-select {
    width: 100%;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px; color: #f1f5f9;
    font-size: 0.9rem; padding: 10px 14px;
    outline: none; font-family: 'Sora', sans-serif;
    cursor: pointer; transition: border-color 0.2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23f97316'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;
}
.glass-select:focus { border-color: rgba(249,115,22,0.5); box-shadow: 0 0 0 3px rgba(249,115,22,0.1); }
.glass-select option { background: #1a1025; color: #f1f5f9; }

/* Upload zone */
.upload-zone {
    display: flex; align-items: center; justify-content: center;
    padding: 1.25rem;
    border: 1.5px dashed rgba(255,255,255,0.15);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    background: rgba(255,255,255,0.02);
}
.upload-zone:hover { border-color: rgba(249,115,22,0.4); background: rgba(249,115,22,0.05); }
.upload-zone.has-file { border-color: rgba(249,115,22,0.4); border-style: solid; background: rgba(249,115,22,0.06); }
.upload-placeholder { font-size: 0.85rem; color: #64748b; font-family: 'Sora', sans-serif; }
.upload-filename { font-size: 0.85rem; color: #4ade80; font-family: 'Sora', sans-serif; font-weight: 600; }
.hidden { display: none; }

.field-error { font-size: 0.75rem; color: #f87171; margin-top: 4px; }

.form-actions { display: flex; align-items: center; gap: 1rem; padding-top: 0.5rem; }
.btn-submit {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; font-weight: 600; font-size: 0.9rem;
    padding: 0.7rem 1.75rem; border-radius: 10px; border: none;
    cursor: pointer; box-shadow: 0 4px 20px rgba(249,115,22,0.4);
    transition: all 0.2s; font-family: 'Sora', sans-serif;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(249,115,22,0.55); }
.btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-cancel { font-size: 0.85rem; color: #64748b; text-decoration: none; transition: color 0.2s; }
.btn-cancel:hover { color: #94a3b8; }
</style>
