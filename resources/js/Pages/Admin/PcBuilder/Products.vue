<template>
    <AdminLayout>
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">{{ category.name }}</h1>
                <p class="text-sm font-bold text-slate-500 mt-1">{{ $t('Manage products for this PC Builder category') }}</p>
            </div>
            <Link href="/admin/pc-builder-categories" class="flex items-center text-sm font-bold text-slate-500 hover:text-[#003366] transition-colors bg-white px-4 py-2 rounded-lg border shadow-sm">
                <ArrowLeft class="w-4 h-4 mr-2" /> {{ $t('Back to Categories') }}
            </Link>
        </div>

        <!-- Success Flash -->
        <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center space-x-3">
            <CheckCircle class="w-5 h-5" />
            <span class="text-sm font-bold">{{ $page.props.flash.success }}</span>
        </div>

        <!-- Add Product Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <Plus class="w-4 h-4" />
                {{ $t('Link Product to Category') }}
            </h2>
            <form @submit.prevent="addProduct" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2 relative">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Search & Select Product') }}</label>
                    <input 
                        v-model="searchQuery" 
                        @input="filterProducts"
                        @focus="showDropdown = true"
                        type="text" 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 outline-none" 
                        :placeholder="selectedProduct ? selectedProduct.name_en : 'Search by product name...'" 
                    />
                    <!-- Dropdown -->
                    <div v-if="showDropdown && filteredProducts.length > 0" class="absolute z-20 top-full mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                        <button 
                            v-for="product in filteredProducts" 
                            :key="product.id" 
                            type="button"
                            @click="selectProduct(product)" 
                            class="w-full text-left px-4 py-3 hover:bg-green-50 transition-colors flex items-center gap-3 border-b border-slate-50 last:border-0"
                        >
                            <img v-if="product.image" :src="product.image" class="w-8 h-8 rounded-lg object-cover bg-slate-100" />
                            <div v-else class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center"><Package class="w-4 h-4 text-slate-300" /></div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ product.name_en }}</p>
                                <p class="text-[10px] text-slate-400 font-bold">SKU: {{ product.sku }} | ৳{{ parseFloat(product.price).toLocaleString() }}</p>
                            </div>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Wattage (W)') }}</label>
                    <input v-model.number="addForm.wattage" type="number" min="0" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 outline-none" placeholder="e.g. 65" />
                </div>
                <div>
                    <button type="submit" :disabled="!addForm.product_id || addForm.processing" class="px-6 py-2.5 bg-[#00a651] hover:bg-[#008f45] text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        {{ $t('Link Product') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Linked Products Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">{{ $t('Linked Products') }} ({{ linkedProducts.length }})</h2>
            </div>

            <div v-if="linkedProducts.length === 0" class="p-12 text-center">
                <Package class="w-12 h-12 text-slate-200 mx-auto mb-3" />
                <p class="text-sm font-bold text-slate-400">{{ $t('No products linked. Use the form above to add products.') }}</p>
            </div>

            <table v-else class="w-full text-left">
                <thead>
                    <tr class="bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">{{ $t('Product') }}</th>
                        <th class="py-4 px-6">{{ $t('SKU') }}</th>
                        <th class="py-4 px-6 text-right">{{ $t('Price') }}</th>
                        <th class="py-4 px-6 text-center">{{ $t('Wattage') }}</th>
                        <th class="py-4 px-6 text-center">{{ $t('Stock') }}</th>
                        <th class="py-4 px-6 text-right">{{ $t('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="item in linkedProducts" :key="item.id" class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex-shrink-0">
                                    <img v-if="item.product?.image" :src="item.product.image" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-slate-200"><Package class="w-5 h-5" /></div>
                                </div>
                                <span class="text-sm font-bold text-slate-900">{{ item.product?.name || item.product?.name_en }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-xs font-mono text-slate-500">{{ item.product?.sku }}</td>
                        <td class="py-4 px-6 text-right text-sm font-black text-[#003366]">৳{{ parseFloat(item.product?.price || 0).toLocaleString() }}</td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-3 py-1 bg-orange-50 text-orange-700 rounded-lg text-xs font-black">{{ item.wattage }}W</span>
                        </td>
                        <td class="py-4 px-6 text-center text-xs font-bold" :class="item.product?.stock > 0 ? 'text-green-600' : 'text-red-500'">
                            {{ item.product?.stock || 0 }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button @click="removeProduct(item.id)" class="p-2 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-lg transition-all">
                                <Trash2 class="w-4 h-4" />
                            </button>
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
import { ArrowLeft, Plus, Package, Trash2, CheckCircle } from 'lucide-vue-next';

const props = defineProps({
    category: Object,
    linkedProducts: Array,
    availableProducts: Array,
});

const searchQuery = ref('');
const showDropdown = ref(false);
const selectedProduct = ref(null);

const addForm = useForm({
    product_id: null,
    wattage: 0,
});

const filteredProducts = computed(() => {
    if (!searchQuery.value) return props.availableProducts.slice(0, 20);
    const q = searchQuery.value.toLowerCase();
    return props.availableProducts.filter(p => 
        (p.name_en && p.name_en.toLowerCase().includes(q)) ||
        (p.sku && p.sku.toLowerCase().includes(q))
    ).slice(0, 20);
});

const filterProducts = () => {
    showDropdown.value = true;
};

const selectProduct = (product) => {
    selectedProduct.value = product;
    addForm.product_id = product.id;
    searchQuery.value = product.name_en;
    showDropdown.value = false;
};

const addProduct = () => {
    addForm.post(`/admin/pc-builder-categories/${props.category.id}/products`, {
        preserveScroll: true,
        onSuccess: () => {
            addForm.reset();
            searchQuery.value = '';
            selectedProduct.value = null;
        },
    });
};

const removeProduct = (id) => {
    if (confirm('Remove this product from the category?')) {
        router.delete(`/admin/pc-builder-categories/${props.category.id}/products/${id}`, { preserveScroll: true });
    }
};

// Close dropdown on click outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
        showDropdown.value = false;
    }
});
</script>
