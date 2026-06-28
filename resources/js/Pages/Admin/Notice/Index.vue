<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div>
                <h1 class="text-4xl font-black text-[#00a651] tracking-tighter uppercase italic">Notice <span class="text-slate-400">Board</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">Manage scrolling announcements for the storefront</p>
            </div>
            <button @click="openCreateModal" class="bg-[#00a651] text-white px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center shadow-2xl shadow-green-500/30 active:scale-95">
                <Plus class="w-4 h-4 mr-3" /> Broadcast Notice
            </button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                            <th class="py-6 px-8">Announcement Content</th>
                            <th class="py-6 px-8 text-center">Status</th>
                            <th class="py-6 px-8 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="notice in notices" :key="notice.id" class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-8">
                                <div class="text-sm font-medium text-slate-700 leading-relaxed max-w-2xl">{{ notice.message }}</div>
                                <div class="text-[9px] text-slate-400 font-mono tracking-tighter mt-2 uppercase italic text-slate-300">Posted on {{ new Date(notice.created_at).toLocaleDateString() }}</div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <button @click="toggleNotice(notice.id)" :class="notice.is_active ? 'text-green-500 bg-green-50' : 'text-slate-300 bg-slate-50'" class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-current transition-all">
                                    {{ notice.is_active ? 'Broadcasting' : 'Muted' }}
                                </button>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end space-x-4">
                                    <button @click="openEditModal(notice)" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-[#00a651] hover:text-white transition-all flex items-center justify-center shadow-sm">
                                        <Edit2 class="w-4 h-4" />
                                    </button>
                                    <button @click="deleteNotice(notice.id)" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="notices.length === 0">
                            <td colspan="3" class="py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <BellOff class="w-10 h-10 text-slate-200" />
                                </div>
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Archives Empty</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">No informational notices have been established yet.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-[#002013]/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-12 max-w-lg w-full shadow-2xl border border-white/20">
                    <h3 class="text-2xl font-black text-slate-900 mb-8 uppercase tracking-tighter italic">{{ editingId ? 'Refine Broadcast' : 'Deploy Global Notice' }}</h3>
                    
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Message Content</label>
                            <textarea 
                                v-model="form.message"
                                rows="4"
                                required
                                class="w-full px-6 py-4 bg-slate-50 rounded-2xl border border-slate-100 outline-none focus:border-[#00a651] transition-all text-sm font-medium"
                                placeholder="Enter the official announcement..."
                            ></textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" v-model="form.is_active" id="is_active" class="rounded border-slate-200 text-[#00a651] focus:ring-[#00a651]" />
                            <label for="is_active" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Immediately</label>
                        </div>

                        <div class="grid grid-cols-1 gap-3 pt-4">
                            <button type="submit" class="px-8 py-5 rounded-2xl bg-[#00a651] text-white font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-900 transition-all shadow-xl shadow-green-500/20 active:scale-95">
                                {{ editingId ? 'Execute Update' : 'Broadcast Now' }}
                            </button>
                            <button type="button" @click="showModal = false" class="px-8 py-5 rounded-2xl bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                                Abort Mission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Edit2, Trash2, BellOff } from 'lucide-vue-next';

const props = defineProps({
    notices: Array
});

const showModal = ref(false);
const editingId = ref(null);
const form = reactive({
    message: '',
    is_active: true
});

const openCreateModal = () => {
    editingId.value = null;
    form.message = '';
    form.is_active = true;
    showModal.value = true;
};

const openEditModal = (notice) => {
    editingId.value = notice.id;
    form.message = notice.message;
    form.is_active = notice.is_active;
    showModal.value = true;
};

const submitForm = () => {
    if (editingId.value) {
        router.put(`/admin/notices/${editingId.value}`, form, {
            onSuccess: () => showModal.value = false
        });
    } else {
        router.post('/admin/notices', form, {
            onSuccess: () => showModal.value = false
        });
    }
};

const toggleNotice = (id) => {
    router.post(`/admin/notices/${id}/toggle-active`);
};

const deleteNotice = (id) => {
    if (confirm('Are you sure you want to terminate this broadcast?')) {
        router.delete(`/admin/notices/${id}`);
    }
};
</script>
