<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div>
                    <h1 class="text-4xl font-black text-[#003366] tracking-tighter uppercase italic">Bulk Brand <span class="text-slate-400">Creation</span></h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">Paste multiple brand names to establish them instantly</p>
                </div>
                <Link href="/admin/brands" class="text-slate-400 hover:text-[#003366] transition-all font-black text-[10px] uppercase tracking-widest flex items-center">
                    <ArrowLeft class="w-4 h-4 mr-2" /> Back To Archives
                </Link>
            </div>

            <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-100 font-sans">
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Brand List Area -->
                    <div>
                        <label class="text-[11px] font-black text-slate-900 uppercase tracking-[0.2em] mb-4 block">Brand Inventory List</label>
                        <div class="relative group">
                            <textarea 
                                v-model="form.brands" 
                                rows="12" 
                                class="w-full px-8 py-6 bg-slate-50 border border-slate-100 rounded-[2rem] font-bold text-sm outline-none focus:border-[#003366] focus:bg-white transition-all custom-scrollbar"
                                placeholder="Example:&#10;Intel&#10;AMD&#10;ASUS, MSI, Gigabyte&#10;HP"
                            ></textarea>
                            <div class="absolute right-6 top-6 opacity-20 pointer-events-none">
                                <Layers class="w-8 h-8 text-slate-400" />
                            </div>
                        </div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-4 ml-4">Separated by new lines or commas. Duplicates will be automatically ignored based on identity.</p>
                    </div>

                    <!-- Classification Association -->
                    <div>
                        <label class="text-[11px] font-black text-slate-900 uppercase tracking-[0.2em] mb-6 block">Associate With Classifications (Optional)</label>
                        <div class="space-y-6">
                            <div v-for="cat in categories" :key="cat.id" class="space-y-3">
                                <!-- Category Card -->
                                <div 
                                    @click="toggleCategory(cat.id)"
                                    class="p-4 rounded-2xl border flex items-center justify-between cursor-pointer transition-all"
                                    :class="form.category_ids.includes(cat.id) ? 'bg-blue-50 border-[#003366]/20' : 'bg-slate-50 border-transparent hover:border-slate-200'"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center" :class="form.category_ids.includes(cat.id) ? 'text-[#003366]' : 'text-slate-300'">
                                            <Check v-if="form.category_ids.includes(cat.id)" class="w-4 h-4" />
                                            <Tags v-else class="w-4 h-4" />
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest" :class="form.category_ids.includes(cat.id) ? 'text-[#003366]' : 'text-slate-500'">{{ cat.name_en }}</span>
                                    </div>
                                    <span v-if="cat.sub_categories.length > 0" class="text-[8px] font-bold text-slate-300 uppercase tracking-widest">{{ cat.sub_categories.length }} Subs Available</span>
                                </div>

                                <!-- Subcategories Grid -->
                                <div v-if="cat.sub_categories && cat.sub_categories.length > 0" class="ml-10 grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <div 
                                        v-for="sub in cat.sub_categories" :key="sub.id"
                                        @click="toggleSubCategory(sub.id, cat.id)"
                                        class="p-2 rounded-xl border flex items-center gap-3 cursor-pointer transition-all"
                                        :class="form.sub_category_ids.includes(sub.id) ? 'bg-slate-100 border-slate-300' : 'bg-white border-slate-100 hover:border-slate-200'"
                                    >
                                        <div class="w-4 h-4 rounded bg-white border border-slate-200 flex items-center justify-center">
                                            <div v-if="form.sub_category_ids.includes(sub.id)" class="w-2 h-2 bg-[#003366] rounded-sm"></div>
                                        </div>
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tight">{{ sub.name_en }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex justify-end">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="bg-[#003366] text-white px-12 py-5 rounded-2xl hover:bg-slate-800 disabled:opacity-50 transition-all font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-[#003366]/30 active:scale-95 flex items-center"
                        >
                            <Zap class="w-4 h-4 mr-3" /> Execute Bulk Operation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, Layers, Tags, Zap, Check } from 'lucide-vue-next';

const props = defineProps({
    categories: Array
});

const form = useForm({
    brands: '',
    category_ids: [],
    sub_category_ids: []
});

const toggleCategory = (id) => {
    const idx = form.category_ids.indexOf(id);
    if (idx > -1) {
        form.category_ids.splice(idx, 1);
        // Also remove children subcategories if category is unselected? 
        // User might not want this, but usually it makes sense. 
        // For now, let's keep subcategories selected even if parent is unselected, 
        // but the UI logic will auto-reselect parent if a sub is clicked.
    } else {
        form.category_ids.push(id);
    }
};

const toggleSubCategory = (subId, catId) => {
    const idx = form.sub_category_ids.indexOf(subId);
    if (idx > -1) {
        form.sub_category_ids.splice(idx, 1);
    } else {
        form.sub_category_ids.push(subId);
        // Ensure parent category is selected
        if (!form.category_ids.includes(catId)) {
            form.category_ids.push(catId);
        }
    }
};

const submit = () => {
    form.post('/admin/brands/bulk', {
        preserveScroll: true,
        onSuccess: () => form.reset()
    });
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
