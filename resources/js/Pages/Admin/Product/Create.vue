<template>
    <AdminLayout>
        <form @submit.prevent="submit" class="max-w-7xl mx-auto space-y-6 pb-16">
            
            <!-- Sticky Header Bar -->
            <div class="sticky top-0 z-20 -mx-4 sm:-mx-6 px-4 sm:px-6 py-4 bg-slate-50/80 backdrop-blur-md border-b border-slate-200/80 transition-all">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <Link 
                            href="/admin/products" 
                            class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 rounded-lg transition-colors"
                            title="Back to products"
                        >
                            <ArrowLeft class="w-5 h-5" />
                        </Link>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">Add New Product</h1>
                            <p class="text-xs text-slate-500">Create and publish a new item to your store</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <Link 
                            href="/admin/products" 
                            class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all shadow-sm"
                        >
                            Cancel
                        </Link>
                        
                        <!-- Top Submit Button -->
                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="inline-flex items-center gap-2 px-5 py-2 bg-slate-900 hover:bg-slate-800 active:bg-black text-white rounded-lg text-xs font-semibold shadow-sm hover:shadow transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                            <Check v-else class="w-4 h-4 text-emerald-400" />
                            <span>{{ form.processing ? 'Saving Product...' : 'Save Product' }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Main Column (2 Cols wide on desktop) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card 1: Basic Information -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h2 class="text-sm font-bold text-slate-800">General Details</h2>
                            <p class="text-xs text-slate-500">Title and description options for your product.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Product Name (English) <span class="text-red-500">*</span></label>
                                <input v-model="form.name_en" type="text" placeholder="e.g. Pure Organic Honey" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-all">
                                <p v-if="form.errors.name_en" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.name_en }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Product Name (Bangla)</label>
                                <input v-model="form.name_bn" type="text" placeholder="e.g. খাঁটি অর্গানিক মধু" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-all">
                                <p v-if="form.errors.name_bn" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.name_bn }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description (English)</label>
                                <textarea v-model="form.description_en" rows="3" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-all" placeholder="Briefly describe what makes this product special..."></textarea>
                                <p v-if="form.errors.description_en" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.description_en }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description (Bangla)</label>
                                <textarea v-model="form.description_bn" rows="3" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-all" placeholder="পণ্যের বিবরণ লিখুন..."></textarea>
                                <p v-if="form.errors.description_bn" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.description_bn }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Key Highlights / Features</label>
                                <textarea v-model="form.short_description" rows="3" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-all" placeholder="Enter key features (one point per line)..."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Technical Specifications</label>
                                <textarea v-model="form.long_description" rows="4" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-all" placeholder="Enter detailed specs (e.g. Brand: HP, RAM: 16GB)..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Pricing & Inventory -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h2 class="text-sm font-bold text-slate-800">Pricing & Inventory</h2>
                            <p class="text-xs text-slate-500">Manage your product cost, retail price, and stock levels.</p>
                        </div>

                        <!-- Price grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200/60">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Sell Price (৳) <span class="text-red-500">*</span></label>
                                <input v-model="form.price" type="number" step="0.01" placeholder="0.00" class="w-full px-3 py-1.5 rounded-md border border-slate-300 bg-white text-sm font-semibold text-slate-900 focus:outline-none focus:border-slate-800">
                                <p v-if="form.errors.price" class="mt-1 text-xs text-red-500">{{ form.errors.price }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200/60">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Buy Price (৳)</label>
                                <input v-model="form.buying_price" type="number" step="0.01" placeholder="0.00" class="w-full px-3 py-1.5 rounded-md border border-slate-300 bg-white text-sm font-semibold text-slate-900 focus:outline-none focus:border-slate-800">
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200/60">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Package Cost (৳)</label>
                                <input v-model="form.package_cost" type="number" step="0.01" placeholder="0.00" class="w-full px-3 py-1.5 rounded-md border border-slate-300 bg-white text-sm font-semibold text-slate-900 focus:outline-none focus:border-slate-800">
                            </div>
                        </div>

                        <!-- Stock & Attributes Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Stock Quantity</label>
                                <input v-model="form.stock" type="number" placeholder="0" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-slate-800">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Display Order</label>
                                <input v-model="form.serial" type="number" placeholder="1" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-slate-800">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Dimensions/Size</label>
                                <input v-model="form.size" type="text" placeholder="e.g. 10x20cm" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-slate-800">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Weight</label>
                                <input v-model="form.weight" type="text" placeholder="e.g. 1.5kg" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-slate-800">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Sidebar Column (1 Col wide on desktop) -->
                <div class="space-y-6">
                    
                    <!-- Card 3: Organization & Category -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                        <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Organization</h2>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                            <select v-model="form.category_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white text-slate-800 focus:outline-none focus:border-slate-800">
                                <option value="" disabled>Select Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name_en }}</option>
                            </select>
                            <p v-if="form.errors.category_id" class="mt-1 text-xs text-red-500">{{ form.errors.category_id }}</p>
                        </div>

                        <div v-if="selectedCategorySubs.length > 0">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subcategory</label>
                            <select v-model="form.sub_category_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white text-slate-800 focus:outline-none focus:border-slate-800">
                                <option :value="null">Select Subcategory</option>
                                <option v-for="sub in selectedCategorySubs" :key="sub.id" :value="sub.id">{{ sub.name_en }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Brand</label>
                            <select v-model="form.brand_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white text-slate-800 focus:outline-none focus:border-slate-800">
                                <option :value="null">No Brand / Universal</option>
                                <option v-for="brand in availableBrands" :key="brand.id" :value="brand.id">{{ brand.name_en }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Card 4: Media Uploads -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                        <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Product Media</h2>

                        <!-- Main Cover Image -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-slate-700">Cover Image</label>
                                <button @click.prevent="showMainPicker = true" type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    From Gallery
                                </button>
                            </div>

                            <div class="relative group w-full h-44 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-slate-100/60 hover:border-slate-300 transition-all flex flex-col items-center justify-center overflow-hidden cursor-pointer">
                                <img v-if="mainPreview" :src="mainPreview" class="w-full h-full object-cover" />
                                <div v-else class="text-center p-4">
                                    <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-500">
                                        <Upload class="w-5 h-5" />
                                    </div>
                                    <p class="text-xs font-semibold text-slate-700">Click to upload cover</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">PNG, JPG, WEBP up to 5MB</p>
                                </div>
                                <input type="file" @input="handleMainImage" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />

                                <button 
                                    v-if="mainPreview" 
                                    @click.prevent="mainPreview = null; form.image = null" 
                                    type="button" 
                                    class="absolute top-2 right-2 p-1.5 bg-slate-900/80 hover:bg-red-600 text-white rounded-lg shadow transition-colors backdrop-blur-sm"
                                >
                                    <Trash2 class="w-3.5 h-3.5" />
                                </button>
                            </div>
                            <p v-if="form.errors.image" class="mt-1 text-xs text-red-500">{{ form.errors.image }}</p>
                        </div>

                        <!-- Gallery Thumbnails -->
                        <div class="pt-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-slate-700">Gallery Images</label>
                                <button @click.prevent="showGalleryPicker = true" type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    From Gallery
                                </button>
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <div v-for="(img, idx) in galleryPreviews" :key="idx" class="relative aspect-square rounded-lg overflow-hidden border border-slate-200 group">
                                    <img :src="img" class="w-full h-full object-cover">
                                    <button @click.prevent="removeGalleryImage(idx)" type="button" class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <X class="w-4 h-4" />
                                    </button>
                                </div>

                                <button 
                                    @click.prevent="triggerGalleryUpload" 
                                    type="button" 
                                    class="aspect-square rounded-lg border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-slate-100 hover:border-slate-300 transition-all flex flex-col items-center justify-center text-slate-400 hover:text-slate-600"
                                >
                                    <Plus class="w-5 h-5" />
                                </button>
                            </div>
                            <input type="file" multiple ref="galleryInput" @input="handleGalleryImages" class="hidden" accept="image/*" />
                        </div>
                    </div>

                    <!-- Card 5: Visibility Toggle -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Store Visibility</h3>
                                <p class="text-[11px] text-slate-500">Enable to publish immediately on shop.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="form.is_active" type="checkbox" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-slate-900"></div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modals -->
            <MediaPicker :show="showMainPicker" @close="showMainPicker = false" @select="handleMainGallerySelect" />
            <MediaPicker :show="showGalleryPicker" @close="showGalleryPicker = false" @select="handleGalleryItemsSelect" />
        </form>
    </AdminLayout>
</template>
<script setup>
import { ref, computed, watch } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MediaPicker from '@/Components/MediaPicker.vue';
import { ArrowLeft, Image as ImageIcon, X, Trash2, Plus, Library } from 'lucide-vue-next';

const props = defineProps({
    categories: Array,
    brands: Array
});

const form = useForm({
    category_id: '',
    sub_category_id: '',
    name_en: '',
    name_bn: '',
    brand_id: null,
    description_en: '',
    description_bn: '',
    short_description: '',
    long_description: '',
    price: '',
    buying_price: '',
    package_cost: '',
    stock: '',
    image: null,
    gallery_images: [],
    is_active: true,
    discount_type: null,
    discount_value: '',
    remarks: '',
    size: '',
    weight: '',
    serial: null,
});

const mainPreview = ref(null);
const galleryPreviews = ref([]);
const galleryInput = ref(null);

const showMainPicker = ref(false);
const showGalleryPicker = ref(false);

const selectedCategory = computed(() => {
    return props.categories.find(c => c.id === form.category_id);
});

const selectedCategorySubs = computed(() => {
    return selectedCategory.value ? selectedCategory.value.sub_categories : [];
});

const availableBrands = computed(() => {
    // If a subcategory is selected, show brands linked to that subcategory
    if (form.sub_category_id) {
        const sub = selectedCategorySubs.value.find(s => s.id === form.sub_category_id);
        return sub ? sub.brands : [];
    }
    
    // If only a category is selected AND it has NO subcategories, 
    // show brands linked to the category directly
    if (form.category_id && selectedCategorySubs.value.length === 0) {
        return selectedCategory.value ? selectedCategory.value.brands : [];
    }

    return [];
});

watch(() => form.category_id, () => {
    form.sub_category_id = null;
    form.brand_id = null;
});

watch(() => form.sub_category_id, () => {
    form.brand_id = null;
});

const handleMainImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        mainPreview.value = URL.createObjectURL(file);
    }
};

const handleMainGallerySelect = (item) => {
    // When selecting from gallery, we send the path
    form.image = item.url; 
    mainPreview.value = item.url;
};

const handleGalleryImages = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        form.gallery_images.push(file);
        galleryPreviews.value.push(URL.createObjectURL(file));
    });
};

const handleGalleryItemsSelect = (item) => {
    form.gallery_images.push(item.url);
    galleryPreviews.value.push(item.url);
};

const triggerGalleryUpload = () => {
    galleryInput.value.click();
};

const removeGalleryImage = (idx) => {
    form.gallery_images.splice(idx, 1);
    galleryPreviews.value.splice(idx, 1);
};

const submit = () => {
    form.post('/admin/products');
};
</script>
