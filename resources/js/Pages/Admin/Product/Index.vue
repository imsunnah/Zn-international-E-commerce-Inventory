<template>
    <AdminLayout>
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Products</h1>
                <p class="text-sm text-slate-500 mt-0.5">Manage your product catalog</p>
            </div>
            <Link
                href="/admin/products/create"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors"
            >
                <Plus class="w-4 h-4" /> Add product
            </Link>
        </div>

        <!-- Success message -->
        <div
            v-if="$page.props.flash?.success"
            class="mb-6 flex items-center justify-between gap-3 bg-emerald-50 text-emerald-800 border border-emerald-200 px-4 py-3 rounded-lg text-sm"
        >
            <div class="flex items-center gap-2">
                <CheckCircle class="w-4 h-4 shrink-0" />
                <span>{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="text-emerald-600 hover:text-emerald-800">
                <X class="w-4 h-4" />
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <Link
                    v-for="t in tabs"
                    :key="t.value"
                    :href="route('admin.products.index', { tab: t.value, category_id: currentCategoryId, search: searchFilter })"
                    class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors"
                    :class="currentTab === t.value
                        ? 'bg-slate-900 text-white'
                        : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300'"
                >
                    {{ t.label }}
                    <span
                        class="ml-1.5 text-xs"
                        :class="currentTab === t.value ? 'text-slate-300' : 'text-slate-400'"
                    >{{ counts[t.value] }}</span>
                </Link>
            </div>

            <div class="relative w-full lg:w-72">
                <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                <input
                    v-model="searchTerm"
                    @input="handleSearch"
                    type="text"
                    placeholder="Search by name or SKU"
                    class="w-full pl-9 pr-9 py-2.5 rounded-lg bg-white border border-slate-200 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400"
                >
                <button
                    v-if="searchTerm"
                    @click="clearSearch"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                >
                    <X class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- Category filter -->
        <div class="mb-6 flex flex-wrap gap-2">
            <Link
                :href="route('admin.products.index', { tab: currentTab, search: searchFilter })"
                class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                :class="currentCategoryId === 'all'
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-500 hover:bg-slate-50'"
            >
                All categories
            </Link>
            <Link
                v-for="cat in categories"
                :key="cat.id"
                :href="route('admin.products.index', { category_id: cat.id, tab: currentTab, search: searchFilter })"
                class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                :class="currentCategoryId == cat.id
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-500 hover:bg-slate-50'"
            >
                {{ cat.name }}
            </Link>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[760px]">
                    <thead>
                        <tr class="text-xs font-medium text-slate-500 border-b border-slate-200">
                            <th class="py-3 px-4 w-16"></th>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Stock</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4">
                                <div class="w-10 h-10 rounded-md bg-slate-100 overflow-hidden flex items-center justify-center">
                                    <img v-if="product.image" :src="product.image" class="w-full h-full object-cover" />
                                    <Package v-else class="w-4 h-4 text-slate-300" />
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm font-medium text-slate-900">{{ product.name }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    SKU: {{ product.sku }}
                                    <span v-if="product.serial !== null"> · #{{ product.serial }}</span>
                                </div>
                                <span v-if="product.discount_type" class="inline-block mt-1 text-[11px] font-medium text-orange-700 bg-orange-50 px-1.5 py-0.5 rounded">
                                    {{ product.discount_type === 'percentage' ? product.discount_value + '% off' : '৳' + product.discount_value + ' off' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-slate-700">{{ product.category?.name || '—' }}</div>
                                <div v-if="product.sub_category" class="text-xs text-slate-400">{{ product.sub_category.name }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div v-if="product.discount_type" class="text-sm">
                                    <span class="font-medium text-slate-900">৳{{ parseFloat(product.discounted_price).toLocaleString() }}</span>
                                    <span class="text-slate-400 line-through ml-1.5 text-xs">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                                </div>
                                <div v-else class="text-sm font-medium text-slate-900">
                                    ৳{{ parseFloat(product.price).toLocaleString() }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span
                                    class="text-xs font-medium px-2 py-1 rounded-md"
                                    :class="product.stock < 10 ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-600'"
                                >
                                    {{ product.stock }} {{ product.stock === 1 ? 'unit' : 'units' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <button
                                    @click="toggleStatus(product.id)"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium"
                                    :class="product.is_active ? 'text-emerald-700' : 'text-slate-400'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="product.is_active ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                                    {{ product.is_active ? 'Active' : 'Archived' }}
                                </button>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-end gap-1">
                                    <button @click="openQuickEdit(product)" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-700" title="Quick edit">
                                        <Zap class="w-4 h-4" />
                                    </button>
                                    <button @click="viewProduct(product)" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-700" title="View">
                                        <Eye class="w-4 h-4" />
                                    </button>
                                    <Link :href="`/admin/products/${product.id}/edit`" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-700" title="Edit">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(product.id)" class="p-1.5 rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600" title="Delete">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="products.data.length === 0">
                            <td colspan="7" class="py-16 text-center">
                                <Package class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                                <p class="text-sm text-slate-500">No products found</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="px-4 py-3 border-t border-slate-100 flex justify-center gap-1">
                <Link
                    v-for="link in products.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                    :class="[
                        link.active ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-100',
                        !link.url ? 'opacity-40 pointer-events-none' : ''
                    ]"
                ></Link>
            </div>
        </div>

        <!-- Delete confirmation -->
        <transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
                <div class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl">
                    <h3 class="text-base font-semibold text-slate-900 mb-1">Delete this product?</h3>
                    <p class="text-sm text-slate-500 mb-6">This can't be undone. The product will be permanently removed.</p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showDeleteModal = false" class="px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50">Cancel</button>
                        <button @click="deleteProduct" class="px-4 py-2.5 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- View product -->
        <transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
                <div class="bg-white rounded-xl p-6 sm:p-8 max-w-2xl w-full shadow-xl max-h-[90vh] overflow-y-auto relative">
                    <button @click="showViewModal = false" class="absolute top-6 right-6 p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                        <X class="w-4 h-4" />
                    </button>

                    <div class="mb-6 pr-8">
                        <h3 class="text-lg font-semibold text-slate-900">{{ productToView?.name }}</h3>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="text-xs text-slate-500">SKU: {{ productToView?.sku }}</span>
                            <span class="text-slate-300">·</span>
                            <span class="inline-flex items-center gap-1 text-xs font-medium" :class="productToView?.is_active ? 'text-emerald-700' : 'text-slate-400'">
                                <span class="w-1.5 h-1.5 rounded-full" :class="productToView?.is_active ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                                {{ productToView?.is_active ? 'Active' : 'Archived' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <div class="aspect-square bg-slate-50 rounded-lg overflow-hidden mb-3 flex items-center justify-center border border-slate-100">
                                <img v-if="productToView?.image" :src="productToView.image" class="max-w-full max-h-full object-contain" />
                                <Package v-else class="w-8 h-8 text-slate-300" />
                            </div>
                            <div v-if="productToView?.gallery && productToView.gallery.length > 0" class="grid grid-cols-4 gap-2">
                                <div v-for="img in productToView.gallery" :key="img.id" class="aspect-square rounded-md bg-slate-50 overflow-hidden border border-slate-100">
                                    <img :src="img.image" class="w-full h-full object-cover" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <h4 class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Pricing</h4>
                                <dl class="text-sm space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500">Selling price</dt>
                                        <dd class="font-medium text-slate-900">৳{{ parseFloat(productToView?.price || 0).toLocaleString() }}</dd>
                                    </div>
                                    <div v-if="productToView?.discount_type" class="flex justify-between">
                                        <dt class="text-slate-500">Discount</dt>
                                        <dd class="font-medium text-orange-600">
                                            {{ productToView.discount_type === 'percentage' ? productToView.discount_value + '%' : '৳' + productToView.discount_value }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500">Buying price</dt>
                                        <dd class="text-slate-700">৳{{ parseFloat(productToView?.buying_price || 0).toLocaleString() }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500">Packaging cost</dt>
                                        <dd class="text-slate-700">৳{{ parseFloat(productToView?.package_cost || 0).toLocaleString() }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h4 class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Inventory</h4>
                                <dl class="text-sm space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500">In stock</dt>
                                        <dd class="font-medium" :class="productToView?.stock <= 5 ? 'text-red-600' : 'text-slate-900'">{{ productToView?.stock }} units</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500">Category</dt>
                                        <dd class="text-slate-700 text-right">
                                            {{ productToView?.category?.name }}<span v-if="productToView?.sub_category"> / {{ productToView.sub_category.name }}</span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div v-if="productToView?.remarks" class="mb-5">
                        <h4 class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Remarks</h4>
                        <p class="text-sm text-orange-800 bg-orange-50 rounded-lg p-3">{{ productToView.remarks }}</p>
                    </div>

                    <div>
                        <h4 class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Description</h4>
                        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 rounded-lg p-4">{{ productToView?.description || 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Quick edit -->
        <transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showQuickEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
                <div class="bg-white rounded-xl p-6 sm:p-8 max-w-md w-full shadow-xl max-h-[90vh] overflow-y-auto">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Quick edit</h3>
                        <button @click="showQuickEditModal = false" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <X class="w-4 h-4" />
                        </button>
                    </div>

                    <form @submit.prevent="updateProductQuick" class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Name</label>
                            <input v-model="quickEditForm.name" type="text" required class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-transparent focus:bg-white focus:border-slate-300 focus:ring-2 focus:ring-slate-900/5 outline-none text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Price (৳)</label>
                                <input v-model="quickEditForm.price" type="number" required class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-transparent focus:bg-white focus:border-slate-300 focus:ring-2 focus:ring-slate-900/5 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Stock</label>
                                <input v-model="quickEditForm.stock" type="number" required class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-transparent focus:bg-white focus:border-slate-300 focus:ring-2 focus:ring-slate-900/5 outline-none text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Discount type</label>
                                <select v-model="quickEditForm.discount_type" class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-transparent focus:bg-white focus:border-slate-300 focus:ring-2 focus:ring-slate-900/5 outline-none text-sm">
                                    <option :value="null">None</option>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed (৳)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Discount value</label>
                                <input v-model="quickEditForm.discount_value" type="number" class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-transparent focus:bg-white focus:border-slate-300 focus:ring-2 focus:ring-slate-900/5 outline-none text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Remarks</label>
                            <textarea v-model="quickEditForm.remarks" rows="2" class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-transparent focus:bg-white focus:border-slate-300 focus:ring-2 focus:ring-slate-900/5 outline-none text-sm"></textarea>
                        </div>

                        <label class="flex items-center justify-between p-3.5 bg-slate-50 rounded-lg cursor-pointer">
                            <span class="text-sm font-medium text-slate-700">Visible in storefront</span>
                            <div class="relative w-10 h-6 rounded-full transition-colors" :class="quickEditForm.is_active ? 'bg-slate-900' : 'bg-slate-300'">
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform" :class="quickEditForm.is_active ? 'translate-x-4' : 'translate-x-0'"></div>
                                <input v-model="quickEditForm.is_active" type="checkbox" class="hidden">
                            </div>
                        </label>

                        <div class="pt-2 grid grid-cols-2 gap-3">
                            <button type="button" @click="showQuickEditModal = false" class="px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50">Cancel</button>
                            <button type="submit" :disabled="quickEditForm.processing" class="px-4 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 disabled:opacity-50">
                                {{ quickEditForm.processing ? 'Saving…' : 'Save changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Edit2, Trash2, CheckCircle, Package, Eye, X, Zap, Search } from 'lucide-vue-next';

const props = defineProps({
    products: Object,
    categories: Array,
    currentCategoryId: [String, Number],
    currentTab: String,
    searchFilter: String,
    counts: Object,
});

const tabs = [
    { value: 'all', label: 'All' },
    { value: 'low_stock', label: 'Low stock' },
    { value: 'discounted', label: 'Discounted' },
];

const searchTerm = ref(props.searchFilter);

let timeout = null;
const handleSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('admin.products.index'), {
            tab: props.currentTab,
            category_id: props.currentCategoryId,
            search: searchTerm.value
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 300);
};

const clearSearch = () => {
    searchTerm.value = '';
    handleSearch();
};

const showDeleteModal = ref(false);
const productToDelete = ref(null);

const showViewModal = ref(false);
const productToView = ref(null);

const showQuickEditModal = ref(false);
const quickEditForm = useForm({
    id: null,
    name: '',
    price: '',
    stock: '',
    is_active: true,
    discount_type: null,
    discount_value: '',
    remarks: '',
    // Required by the update endpoint but not edited in this modal
    category_id: null,
    description: '',
    buying_price: 0,
    package_cost: 0,
});

const viewProduct = (product) => {
    productToView.value = product;
    showViewModal.value = true;
};

const toggleStatus = (id) => {
    router.put(`/admin/products/${id}/toggle-active`, {}, {
        preserveScroll: true
    });
};

const openQuickEdit = (product) => {
    quickEditForm.id = product.id;
    quickEditForm.name = product.name;
    quickEditForm.price = product.price;
    quickEditForm.stock = product.stock;
    quickEditForm.is_active = product.is_active ? true : false;
    quickEditForm.discount_type = product.discount_type;
    quickEditForm.discount_value = product.discount_value;
    quickEditForm.remarks = product.remarks;

    quickEditForm.category_id = product.category_id;
    quickEditForm.description = product.description;
    quickEditForm.buying_price = product.buying_price;
    quickEditForm.package_cost = product.package_cost;

    showQuickEditModal.value = true;
};

const updateProductQuick = () => {
    quickEditForm.put(`/admin/products/${quickEditForm.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showQuickEditModal.value = false;
        }
    });
};

const confirmDelete = (id) => {
    productToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteProduct = () => {
    router.delete(`/admin/products/${productToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};
</script>