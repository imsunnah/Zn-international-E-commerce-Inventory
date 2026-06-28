<template>
    <AdminLayout>
        <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">News & Updates</h1>
                <p class="text-sm text-slate-500">Manage blog posts and news for front page</p>
            </div>
            <Link :href="route('admin.news.create')" class="bg-[#FF6600] text-white px-6 py-2.5 rounded-lg hover:bg-orange-600 transition-all font-bold text-sm flex items-center shadow-md">
                <Plus class="w-4 h-4 mr-2" /> Add News
            </Link>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-6 bg-green-500 text-white p-4 rounded-lg shadow-md flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <CheckCircle class="w-5 h-5" />
                <span class="text-sm font-bold">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100"><X class="w-4 h-4" /></button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Image</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Title</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="item in news.data" :key="item.id" class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <img :src="item.image" class="w-16 h-10 object-cover rounded-lg bg-slate-100">
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-[#003366] line-clamp-1">{{ item.title }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="toggleActive(item)" :class="item.is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                                {{ item.is_active ? 'Active' : 'Hidden' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <Link :href="route('admin.news.edit', item.id)" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                                    <Edit class="w-4 h-4" />
                                </Link>
                                <button @click="deleteNews(item.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Edit, Trash2, CheckCircle, X } from 'lucide-vue-next';

defineProps({
    news: Object
});

const toggleActive = (item) => {
    router.put(route('admin.news.toggle-active', item.id));
};

const deleteNews = (id) => {
    if (confirm('Are you sure you want to delete this news item?')) {
        router.delete(route('admin.news.destroy', id));
    }
};
</script>
