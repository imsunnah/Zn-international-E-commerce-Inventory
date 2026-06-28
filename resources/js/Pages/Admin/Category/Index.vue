<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div>
                <h1 class="text-4xl font-black text-primary-900 tracking-tighter uppercase italic">Classification <span class="text-slate-400">Archives</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">Organize your product entities with logical categories</p>
            </div>
            <div class="flex gap-4">
                <Link href="/admin/brands/bulk" class="bg-slate-100 text-slate-600 px-8 py-4 rounded-2xl hover:bg-slate-200 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center active:scale-95">
                    <Layers class="w-4 h-4 mr-3" /> Bulk Brands
                </Link>
                <Link href="/admin/categories/create" class="bg-primary-900 text-white px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center shadow-2xl shadow-primary-900/30 active:scale-95">
                    <Plus class="w-4 h-4 mr-3" /> Establish Category
                </Link>
            </div>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-8 bg-green-500 text-white p-6 rounded-2xl shadow-xl shadow-green-500/20 flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="flex items-center space-x-4">
                <CheckCircle class="w-6 h-6" />
                <span class="text-xs font-black uppercase tracking-widest">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                            <th class="py-6 px-8 text-center w-24">ID</th>
                            <th class="py-6 px-8 w-24">Visual</th>
                            <th class="py-6 px-8">Designation</th>
                            <th class="py-6 px-8 text-center">Sub/Brands</th>
                            <th class="py-6 px-8 text-center">Operational Status</th>
                            <th class="py-6 px-8 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="category in categories.data" :key="category.id" class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-8 text-center">
                                <span class="text-[10px] font-black text-slate-300 font-mono tracking-tighter">#{{ String(category.id).padStart(3, '0') }}</span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-lg border border-slate-100 bg-slate-50 flex items-center justify-center">
                                    <img v-if="category.image" :src="category.image" class="w-full h-full object-cover" />
                                    <Tags v-else class="w-6 h-6 text-slate-200" />
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ category.name_en }}</div>
                                <div class="text-[9px] text-slate-400 font-mono tracking-tighter">{{ category.slug }}</div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-[10px] font-black text-[#135e96] bg-blue-50 px-3 py-0.5 rounded-full uppercase tracking-widest">{{ category.sub_categories_count }} Subs</span>
                                    <span class="text-[10px] font-black text-purple-600 bg-purple-50 px-3 py-0.5 rounded-full uppercase tracking-widest">{{ category.brands_count }} Brands</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <button 
                                    @click="toggleActive(category.id)"
                                    :class="category.is_active ? 'text-green-500 bg-green-50 border-green-200' : 'text-slate-300 bg-slate-50 border-slate-200'" 
                                    class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border transition-all hover:scale-105 active:scale-95"
                                >
                                    {{ category.is_active ? 'Active' : 'Archived' }}
                                </button>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button @click="openQuickSub(category)" class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 hover:bg-[#135e96] hover:text-white transition-all flex items-center justify-center shadow-sm" title="Add SubCategory">
                                        <PlusCircle class="w-4 h-4" />
                                    </button>
                                    <button @click="openManageBrands(category)" class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Manage Brands">
                                        <Award class="w-4 h-4" />
                                    </button>
                                    <Link :href="`/admin/categories/${category.id}/edit`" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-primary-900 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(category.id)" class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="categories.data.length === 0">
                            <td colspan="6" class="py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <Tags class="w-10 h-10 text-slate-200" />
                                </div>
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Archives Empty</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">No category classifications have been established yet.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-if="categories.last_page > 1" class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-center space-x-2">
                <button v-for="link in categories.links" :key="link.label" 
                    @click="link.url && router.visit(link.url)"
                    v-html="link.label"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                    :class="[
                        link.active ? 'bg-primary-900 text-white shadow-lg' : 'bg-white text-slate-400 hover:bg-slate-100',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                ></button>
            </div>
        </div>

        <!-- Quick SubCategory Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showSubModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-primary-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-10 max-w-lg w-full shadow-2xl border border-white/20">
                    <h3 class="text-2xl font-black text-slate-900 mb-6 uppercase tracking-tighter italic">Quick Sub-Category <span class="text-slate-400">Establishment</span></h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Name (English)</label>
                            <input v-model="subForm.name_en" type="text" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold text-sm outline-none focus:border-blue-500 transition-all" placeholder="Enter sub-category name..." />
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Name (Bangla)</label>
                            <input v-model="subForm.name_bn" type="text" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold text-sm outline-none focus:border-blue-500 transition-all" placeholder="বাংলা নাম লিখুন..." />
                        </div>
                    </div>
                    <div class="flex gap-3 mt-8">
                        <button @click="submitQuickSub" class="flex-1 px-8 py-4 bg-blue-600 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-700 transition-all active:scale-95 shadow-lg shadow-blue-600/20">Create Subsidiary</button>
                        <button @click="showSubModal = false" class="px-8 py-4 bg-slate-50 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-100 transition-all active:scale-95">Cancel</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Manage Brands Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showBrandModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-primary-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-10 max-w-2xl w-full shadow-2xl border border-white/20">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter italic">Manage <span class="text-slate-400">Associations</span></h3>
                        <div class="text-[10px] items-center font-black text-slate-400 uppercase bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">{{ activeCategory?.name_en }}</div>
                    </div>
                    
                    <div class="max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                        <div class="grid grid-cols-3 gap-3">
                            <div 
                                v-for="brand in allBrands" :key="brand.id" 
                                @click="toggleBrandInList(brand.id)"
                                class="p-4 rounded-2xl border flex flex-col items-center text-center cursor-pointer transition-all"
                                :class="brandForm.brand_ids.includes(brand.id) ? 'bg-purple-50 border-purple-200 shadow-inner' : 'bg-slate-50 border-transparent hover:border-slate-200'"
                            >
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mb-2" :class="brandForm.brand_ids.includes(brand.id) ? 'text-purple-600' : 'text-slate-300'">
                                    <Check v-if="brandForm.brand_ids.includes(brand.id)" class="w-5 h-5" />
                                    <Award v-else class="w-5 h-5" />
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest" :class="brandForm.brand_ids.includes(brand.id) ? 'text-purple-900' : 'text-slate-500'">{{ brand.name_en }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8 pt-6 border-t border-slate-50">
                        <button @click="submitAttachBrands" class="flex-1 px-8 py-4 bg-purple-600 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-purple-700 transition-all active:scale-95 shadow-lg shadow-purple-600/20">Sync Associations</button>
                        <button @click="showBrandModal = false" class="px-8 py-4 bg-slate-50 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-100 transition-all active:scale-95">Close</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Delete Confirmation Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-primary-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-12 max-w-sm w-full shadow-2xl border border-white/20">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <AlertTriangle class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 text-center mb-4 uppercase tracking-tighter italic">Terminate Category?</h3>
                    <p class="text-xs font-medium text-slate-400 text-center mb-10 leading-relaxed px-4">This operation will permanently erase this classification. Associated product visibility may be affected.</p>
                    <div class="grid grid-cols-1 gap-3">
                        <button @click="deleteCategory" class="px-8 py-5 rounded-2xl bg-red-600 text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-xl shadow-red-600/20 active:scale-95">
                            Confirm Termination
                        </button>
                        <button @click="showDeleteModal = false" class="px-8 py-5 rounded-2xl bg-slate-50 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                            Abort Mission
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    Plus, Edit2, Trash2, AlertTriangle, CheckCircle, 
    XCircle, Tags, PlusCircle, Award, Check, Layers
} from 'lucide-vue-next';

const props = defineProps({
    categories: Object,
    allBrands: Array
});

const showDeleteModal = ref(false);
const showSubModal = ref(false);
const showBrandModal = ref(false);
const activeCategory = ref(null);
const categoryToDelete = ref(null);

const subForm = reactive({
    name_en: '',
    name_bn: ''
});

const brandForm = reactive({
    brand_ids: []
});

const confirmDelete = (id) => {
    categoryToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteCategory = () => {
    router.delete(`/admin/categories/${categoryToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};

const toggleActive = (id) => {
    router.put(`/admin/categories/${id}/toggle-active`, {}, {
        preserveScroll: true
    });
};

const openQuickSub = (category) => {
    activeCategory.value = category;
    subForm.name_en = '';
    subForm.name_bn = '';
    showSubModal.value = true;
};

const submitQuickSub = () => {
    router.post(`/admin/categories/${activeCategory.value.id}/quick-subcategory`, subForm, {
        onSuccess: () => {
            showSubModal.value = false;
        }
    });
};

const openManageBrands = (category) => {
    activeCategory.value = category;
    // Map existing brands to their IDs for pre-population
    brandForm.brand_ids = category.brands.map(b => b.id); 
    showBrandModal.value = true;
};

const toggleBrandInList = (id) => {
    const idx = brandForm.brand_ids.indexOf(id);
    if (idx > -1) brandForm.brand_ids.splice(idx, 1);
    else brandForm.brand_ids.push(id);
};

const submitAttachBrands = () => {
    router.post(`/admin/categories/${activeCategory.value.id}/attach-brands`, brandForm, {
        onSuccess: () => {
            showBrandModal.value = false;
        }
    });
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
