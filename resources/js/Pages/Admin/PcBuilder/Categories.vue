<template>
    <AdminLayout>
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">{{ $t('PC Builder Categories') }}</h1>
            <p class="text-sm font-bold text-slate-500 mt-1">{{ $t('Manage component categories for the PC Builder') }}</p>
        </div>

        <!-- Success Flash -->
        <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <CheckCircle class="w-5 h-5" />
                <span class="text-sm font-bold">{{ $page.props.flash.success }}</span>
            </div>
        </div>

        <!-- Add/Edit Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <Plus class="w-4 h-4" />
                {{ editingCategory ? $t('Edit Category') : $t('Add New Category') }}
            </h2>
            <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Category Name') }}</label>
                    <input v-model="form.name" type="text" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 outline-none" placeholder="e.g. Processor / CPU" />
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Icon') }}</label>
                    <select v-model="form.icon" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 outline-none">
                        <option v-for="ic in iconOptions" :key="ic.value" :value="ic.value">{{ ic.label }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Group') }}</label>
                    <select v-model="form.group" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 outline-none">
                        <option value="core">Core Components</option>
                        <option value="peripherals">Peripherals & Others</option>
                    </select>
                </div>
                <div class="flex gap-3 items-end">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Order') }}</label>
                        <input v-model.number="form.sort_order" type="number" min="0" class="w-20 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 outline-none" />
                    </div>
                    <label class="flex items-center gap-2 py-2.5 cursor-pointer">
                        <input v-model="form.is_required" type="checkbox" class="rounded border-slate-300 text-[#00a651] focus:ring-[#00a651] w-4 h-4" />
                        <span class="text-[10px] font-black text-slate-500 uppercase">{{ $t('Required') }}</span>
                    </label>
                </div>
                <div class="flex gap-2">
                    <button type="submit" :disabled="form.processing" class="px-5 py-2.5 bg-[#00a651] hover:bg-[#008f45] text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-colors disabled:opacity-50">
                        {{ editingCategory ? $t('Update') : $t('Add') }}
                    </button>
                    <button v-if="editingCategory" type="button" @click="cancelEdit" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">
                        {{ $t('Cancel') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">{{ $t('All Categories') }}</h2>
            </div>

            <div v-if="categories.length === 0" class="p-12 text-center">
                <Monitor class="w-12 h-12 text-slate-200 mx-auto mb-3" />
                <p class="text-sm font-bold text-slate-400">{{ $t('No categories yet. Add one above to get started.') }}</p>
            </div>

            <table v-else class="w-full text-left">
                <thead>
                    <tr class="bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">{{ $t('Order') }}</th>
                        <th class="py-4 px-6">{{ $t('Name') }}</th>
                        <th class="py-4 px-6">{{ $t('Icon') }}</th>
                        <th class="py-4 px-6">{{ $t('Group') }}</th>
                        <th class="py-4 px-6 text-center">{{ $t('Required') }}</th>
                        <th class="py-4 px-6 text-center">{{ $t('Products') }}</th>
                        <th class="py-4 px-6 text-right">{{ $t('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="cat in sortedCategories" :key="cat.id" class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6 text-xs font-bold text-slate-400">{{ cat.sort_order }}</td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ cat.name }}</span>
                            <span v-if="cat.group === 'core'" class="ml-2 text-[8px] font-black bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full uppercase">Core</span>
                            <span v-else class="ml-2 text-[8px] font-black bg-orange-50 text-orange-600 px-2 py-0.5 rounded-full uppercase">Peripheral</span>
                        </td>
                        <td class="py-4 px-6 text-xs font-mono text-slate-500">{{ cat.icon }}</td>
                        <td class="py-4 px-6 text-xs font-bold text-slate-600 capitalize">{{ cat.group }}</td>
                        <td class="py-4 px-6 text-center">
                            <span v-if="cat.is_required" class="text-[8px] font-black bg-red-50 text-red-600 px-2 py-0.5 rounded-full uppercase">Required</span>
                            <span v-else class="text-[8px] font-black bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full uppercase">Optional</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <Link :href="`/admin/pc-builder-categories/${cat.id}/products`" class="text-xs font-black text-[#00a651] hover:underline">
                                {{ cat.pc_builder_products_count }} {{ $t('products') }}
                            </Link>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="startEdit(cat)" class="p-2 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-lg transition-all">
                                    <Pencil class="w-4 h-4" />
                                </button>
                                <button @click="deleteCategory(cat.id)" class="p-2 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-lg transition-all">
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
import { ref, computed } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Pencil, Trash2, CheckCircle, Monitor } from 'lucide-vue-next';

const props = defineProps({
    categories: Array,
});

const iconOptions = [
    { value: 'cpu', label: 'CPU / Processor' },
    { value: 'fan', label: 'Fan / Cooler' },
    { value: 'circuit-board', label: 'Motherboard' },
    { value: 'memory-stick', label: 'RAM / Memory' },
    { value: 'hard-drive', label: 'Storage / HDD / SSD' },
    { value: 'monitor', label: 'Monitor / GPU' },
    { value: 'power', label: 'Power Supply' },
    { value: 'box', label: 'Casing / Case' },
    { value: 'mouse', label: 'Mouse' },
    { value: 'keyboard', label: 'Keyboard' },
    { value: 'headphones', label: 'Headphone' },
    { value: 'speaker', label: 'Speaker' },
    { value: 'wifi', label: 'WiFi / Network' },
    { value: 'shield', label: 'Antivirus / Security' },
    { value: 'printer', label: 'Printer' },
    { value: 'gamepad-2', label: 'Gaming' },
    { value: 'cable', label: 'Cable / Adapter' },
    { value: 'webcam', label: 'Webcam' },
];

const editingCategory = ref(null);

const form = useForm({
    name: '',
    icon: 'cpu',
    sort_order: 0,
    is_required: false,
    group: 'core',
});

const sortedCategories = computed(() => {
    return [...props.categories].sort((a, b) => {
        if (a.group !== b.group) return a.group === 'core' ? -1 : 1;
        return a.sort_order - b.sort_order;
    });
});

const submitForm = () => {
    if (editingCategory.value) {
        form.put(`/admin/pc-builder-categories/${editingCategory.value.id}`, {
            preserveScroll: true,
            onSuccess: () => cancelEdit(),
        });
    } else {
        form.post('/admin/pc-builder-categories', {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    }
};

const startEdit = (cat) => {
    editingCategory.value = cat;
    form.name = cat.name;
    form.icon = cat.icon;
    form.sort_order = cat.sort_order;
    form.is_required = cat.is_required;
    form.group = cat.group;
};

const cancelEdit = () => {
    editingCategory.value = null;
    form.reset();
};

const deleteCategory = (id) => {
    if (confirm('Are you sure you want to delete this category? All linked products will be unlinked.')) {
        router.delete(`/admin/pc-builder-categories/${id}`, { preserveScroll: true });
    }
};
</script>
