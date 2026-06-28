<template>
    <AdminLayout>
        <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 gap-6">
            <div>
                <h1 class="text-4xl font-black text-[#00a651] tracking-tighter uppercase italic">Offer <span class="text-slate-400">Commander</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1 italic">Manage active discounts and promotional pricing</p>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    @click="viewMode = 'active'"
                    :class="viewMode === 'active' ? 'bg-[#00a651] text-white shadow-lg shadow-green-500/20' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                    class="px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all italic border border-transparent"
                >
                    <Tags class="w-4 h-4 inline mr-2" /> Active Offers
                </button>
                <button 
                    @click="viewMode = 'catalog'"
                    :class="viewMode === 'catalog' ? 'bg-slate-900 text-white shadow-lg shadow-black/20' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                    class="px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all italic border border-transparent"
                >
                    <Search class="w-4 h-4 inline mr-2" /> Explore Catalog
                </button>
            </div>
        </div>

        <!-- Notification Banner -->
        <div v-if="viewMode === 'active'" class="mb-8 p-6 bg-green-50 border border-green-100 rounded-3xl flex items-center gap-4">
            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#00a651] shadow-sm">
                <Zap class="w-6 h-6" />
            </div>
            <div>
                <h4 class="text-xs font-black text-[#00a651] uppercase tracking-widest">Active Promotions Mode</h4>
                <p class="text-[10px] font-bold text-slate-500 mt-0.5 uppercase tracking-wider">Showing only products with active discount values assigned in the master table.</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 relative group">
                <Search class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-[#00a651] transition-colors" />
                <input 
                    v-model="filters.search"
                    @keyup.enter="handleSearch"
                    placeholder="Search Name or SKU..."
                    class="w-full pl-12 pr-6 py-4 bg-white rounded-2xl border border-slate-100 outline-none focus:border-[#00a651] focus:ring-4 focus:ring-green-500/5 transition-all text-xs font-bold uppercase tracking-wider"
                />
            </div>
            <div>
                <select 
                    v-model="filters.category_id"
                    @change="handleSearch"
                    class="w-full px-6 py-4 bg-white rounded-2xl border border-slate-100 outline-none focus:border-[#00a651] transition-all text-xs font-bold uppercase tracking-wider appearance-none"
                >
                    <option value="">All Categories</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name_en }}</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button @click="handleSearch" class="flex-1 bg-slate-100 text-slate-600 px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all border border-slate-200 italic">Filter</button>
                <button @click="resetFilters" class="px-5 py-4 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:bg-slate-50 transition-all"><X class="w-5 h-5" /></button>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                            <th class="py-6 px-8 text-center w-20">
                                <input type="checkbox" @change="toggleSelectAll" :checked="selectAll" class="rounded border-slate-200 text-[#00a651] focus:ring-[#00a651]" />
                            </th>
                            <th class="py-6 px-8">Product Details</th>
                            <th class="py-6 px-8 text-center">Category</th>
                            <th class="py-6 px-8 text-center">Current Price</th>
                            <th class="py-6 px-8 text-center">Active Discount</th>
                            <th class="py-6 px-8 text-center">Final Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="product in products.data" :key="product.id" class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-8 text-center">
                                <input type="checkbox" v-model="selectedProducts" :value="product.id" class="rounded border-slate-200 text-[#00a651] focus:ring-[#00a651]" />
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl border border-slate-100 bg-slate-50 overflow-hidden flex-shrink-0">
                                        <img v-if="product.image" :src="product.image" class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ product.name }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 tracking-[0.2em] mt-1">{{ product.sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md text-[9px] font-black uppercase tracking-widest">{{ product.category?.name }}</span>
                            </td>
                            <td class="py-6 px-8 text-center font-black text-slate-900 text-sm">৳{{ product.price }}</td>
                            <td class="py-6 px-8 text-center">
                                <div v-if="product.discount_type" class="inline-flex items-center px-3 py-1 rounded-full bg-red-50 text-red-500 text-[9px] font-black uppercase tracking-tighter border border-red-100">
                                    {{ product.discount_type === 'percentage' ? product.discount_value + '%' : '৳' + product.discount_value }} OFF
                                </div>
                                <span v-else class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">No Offer</span>
                            </td>
                            <td class="py-6 px-8 text-center font-black text-[#00a651] text-sm italic">৳{{ product.discounted_price }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-if="products.last_page > 1" class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-center space-x-2">
                <button v-for="link in products.links" :key="link.label" 
                    @click="link.url && router.visit(link.url, { data: filters })"
                    v-html="link.label"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                    :class="[
                        link.active ? 'bg-[#00a651] text-white shadow-lg' : 'bg-white text-slate-400 hover:bg-slate-100',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                ></button>
            </div>
        </div>

        <!-- Bulk Discount Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showBulkModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-[#002013]/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-12 max-w-lg w-full shadow-2xl border border-white/20">
                    <div class="w-20 h-20 bg-green-50 text-[#00a651] rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <Zap class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 text-center mb-1 uppercase tracking-tighter italic">Apply Bulk Discount</h3>
                    <p class="text-[10px] font-black text-slate-400 text-center mb-8 uppercase tracking-[0.2em]">Targeting {{ selectedProducts.length }} Selected Products</p>
                    
                    <form @submit.prevent="applyDiscount" class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div 
                                @click="discountForm.discount_type = 'percentage'"
                                :class="discountForm.discount_type === 'percentage' ? 'border-[#00a651] bg-green-50 shadow-inner' : 'border-slate-100 bg-slate-50'"
                                class="p-6 rounded-2xl border-2 cursor-pointer transition-all text-center group"
                            >
                                <Percent class="w-6 h-6 mx-auto mb-3" :class="discountForm.discount_type === 'percentage' ? 'text-[#00a651]' : 'text-slate-300 group-hover:text-slate-400'" />
                                <span class="text-[10px] font-black uppercase tracking-widest" :class="discountForm.discount_type === 'percentage' ? 'text-[#00a651]' : 'text-slate-400'">Percentage</span>
                            </div>
                            <div 
                                @click="discountForm.discount_type = 'flat'"
                                :class="discountForm.discount_type === 'flat' ? 'border-[#00a651] bg-green-50 shadow-inner' : 'border-slate-100 bg-slate-50'"
                                class="p-6 rounded-2xl border-2 cursor-pointer transition-all text-center group"
                            >
                                <Banknote class="w-6 h-6 mx-auto mb-3" :class="discountForm.discount_type === 'flat' ? 'text-[#00a651]' : 'text-slate-300 group-hover:text-slate-400'" />
                                <span class="text-[10px] font-black uppercase tracking-widest" :class="discountForm.discount_type === 'flat' ? 'text-[#00a651]' : 'text-slate-400'">Flat Amount</span>
                            </div>
                        </div>

                        <div v-if="discountForm.discount_type !== 'none'">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Discount Value</label>
                            <input 
                                v-model="discountForm.discount_value"
                                type="number"
                                required
                                class="w-full px-6 py-4 bg-slate-50 rounded-2xl border border-slate-100 outline-none focus:border-[#00a651] transition-all text-sm font-black italic"
                                :placeholder="discountForm.discount_type === 'percentage' ? 'e.g. 15 (%)' : 'e.g. 500 (৳)'"
                            />
                        </div>

                        <div class="grid grid-cols-1 gap-3 pt-4">
                            <button type="submit" class="px-8 py-5 rounded-2xl bg-[#00a651] text-white font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-900 transition-all shadow-xl shadow-green-500/20 active:scale-95">
                                Execute Update
                            </button>
                            <button type="button" @click="removeDiscounts" class="px-8 py-5 rounded-2xl bg-red-50 text-red-500 font-black text-xs uppercase tracking-[0.2em] hover:bg-red-100 transition-all active:scale-95">
                                Clear All Discounts
                            </button>
                            <button type="button" @click="showBulkModal = false" class="px-8 py-5 rounded-2xl bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Search, X, Zap, Percent, Banknote, Tags } from 'lucide-vue-next';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object
});

const filters = reactive({
    search: props.filters.search || '',
    category_id: props.filters.category_id || ''
});

const selectedProducts = ref([]);
const showBulkModal = ref(false);
const selectAll = ref(false);

const discountForm = reactive({
    discount_type: 'percentage',
    discount_value: ''
});

const toggleSelectAll = (e) => {
    if (e.target.checked) {
        selectedProducts.value = props.products.data.map(p => p.id);
        selectAll.value = true;
    } else {
        selectedProducts.value = [];
        selectAll.value = false;
    }
};

const viewMode = ref('catalog');

const handleSearch = () => {
    router.get('/admin/discounts', { 
        ...filters, 
        mode: viewMode.value 
    }, { preserveState: true });
};

// Sync viewMode from props if available
const propsViewMode = new URLSearchParams(window.location.search).get('mode');
if (propsViewMode) viewMode.value = propsViewMode;

watch(viewMode, () => {
    handleSearch();
});

const resetFilters = () => {
    filters.search = '';
    filters.category_id = '';
    handleSearch();
};

const applyDiscount = () => {
    router.post('/admin/discounts/bulk-apply', {
        product_ids: selectedProducts.value,
        ...discountForm
    }, {
        onSuccess: () => {
            showBulkModal.value = false;
            selectedProducts.value = [];
            selectAll.value = false;
        }
    });
};

const removeDiscounts = () => {
    router.post('/admin/discounts/bulk-apply', {
        product_ids: selectedProducts.value,
        discount_type: 'none',
        discount_value: 0
    }, {
        onSuccess: () => {
            showBulkModal.value = false;
            selectedProducts.value = [];
            selectAll.value = false;
        }
    });
};
</script>
