<template>
    <AdminLayout>
        <div class="mb-10 flex items-center justify-between bg-white p-8 rounded-xl shadow-sm border border-slate-100 font-sans">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">Edit Brand</h1>
                <p class="text-xs text-slate-500 mt-1">Update brand information and associated categories</p>
            </div>
            <Link href="/admin/brands" class="text-xs font-bold text-slate-500 hover:text-[#003366] flex items-center transition-all">
                <ArrowLeft class="w-4 h-4 mr-2" />
                Back to List
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden max-w-3xl font-sans">
            <form @submit.prevent="submit" class="p-8 space-y-8">
                <!-- Brand Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Brand Name (English)</label>
                        <input v-model="form.name_en" type="text" placeholder="e.g. Al-Noor" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                        <p v-if="form.errors.name_en" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.name_en }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Brand Name (Bangla)</label>
                        <input v-model="form.name_bn" type="text" placeholder="e.g. আল-নূর" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                        <p v-if="form.errors.name_bn" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.name_bn }}</p>
                    </div>
                </div>

                <!-- Brand Image -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Brand Logo / Image (Optional)</label>
                    <div class="mt-2 flex items-center space-x-6">
                        <div class="shrink-0 w-24 h-24 bg-slate-100 rounded-xl flex items-center justify-center overflow-hidden border border-slate-200">
                            <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-contain" />
                            <ImageIcon v-else class="w-8 h-8 text-slate-300" />
                        </div>
                        <input type="file" @input="handleFileUpload" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#003366] file:text-white hover:file:bg-slate-800" />
                    </div>
                    <p v-if="form.errors.image" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.image }}</p>
                </div>

                <!-- Brand Wise Classifications -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Associated Classifications</label>
                    <div class="space-y-4 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <div v-for="category in categories" :key="category.id" class="space-y-3">
                            <!-- Category Row -->
                            <label class="flex items-center space-x-3 cursor-pointer p-3 bg-white rounded-xl shadow-sm border border-slate-100 hover:border-[#003366]/30 transition-all select-none">
                                <input 
                                    type="checkbox" 
                                    :value="category.id" 
                                    v-model="form.category_ids"
                                    class="w-5 h-5 text-[#003366] rounded border-slate-200 focus:ring-[#003366]/20"
                                >
                                <span class="text-xs font-black text-[#003366] uppercase tracking-tighter">{{ category.name_en }}</span>
                            </label>

                            <!-- Nested Subcategories -->
                            <div v-if="category.sub_categories && category.sub_categories.length > 0" class="ml-8 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label v-for="sub in category.sub_categories" :key="sub.id" class="flex items-center space-x-2 cursor-pointer p-2 bg-slate-100/50 rounded-lg hover:bg-slate-100 transition-colors">
                                    <input 
                                        type="checkbox" 
                                        :value="sub.id" 
                                        v-model="form.sub_category_ids"
                                        @change="autoSelectParent(category.id)"
                                        class="w-4 h-4 text-slate-500 rounded border-slate-300 focus:ring-slate-500/20"
                                    >
                                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-tight">{{ sub.name_en }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Connecting to specific subcategories ensures precision. If a subcategory is selected, the parent category is automatically linked.</p>
                    <p v-if="form.errors.category_ids" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.category_ids }}</p>
                    <p v-if="form.errors.sub_category_ids" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.sub_category_ids }}</p>
                </div>

                <!-- Status Checkbox -->
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input v-model="form.is_active" type="checkbox" class="w-5 h-5 text-[#003366] rounded border-slate-300 focus:ring-[#003366]">
                        <span class="text-sm font-bold text-slate-700">Active (Visible on Storefront)</span>
                    </label>
                </div>

                <!-- Action Button -->
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="form.processing" class="w-full bg-[#003366] text-white px-8 py-4 rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-slate-800 transition-all disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Update Brand' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, Image as ImageIcon } from 'lucide-vue-next';

const props = defineProps({
    brand: Object,
    categories: Array
});

const previewUrl = ref(props.brand.image || null);

const form = useForm({
    _method: 'PUT',
    name_en: props.brand.name_en || '',
    name_bn: props.brand.name_bn || '',
    is_active: props.brand.is_active,
    image: props.brand.image || null,
    category_ids: props.brand.categories.map(c => c.id),
    sub_category_ids: props.brand.sub_categories.map(s => s.id)
});

const autoSelectParent = (categoryId) => {
    if (!form.category_ids.includes(categoryId)) {
        form.category_ids.push(categoryId);
    }
};

const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    // Inertia supports multipart file uploads on PUT/PATCH requests via passing _method in a POST request
    form.post(`/admin/brands/${props.brand.id}`);
};
</script>
