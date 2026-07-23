<template>
    <AdminLayout>
        <form @submit.prevent="submit" class="max-w-9xl mx-auto space-y-6 pb-12">
            
            <!-- Sticky Header Bar with Single Submit Button -->
            <div class="sticky top-0 z-20 -mx-4 sm:-mx-6 px-4 sm:px-6 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200/80 transition-all">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <Link 
                            href="/admin/products" 
                            class="p-2 text-slate-400 hover:text-[#003366] hover:bg-slate-100 rounded-lg transition-colors"
                            title="Back to List"
                        >
                            <ArrowLeft class="w-5 h-5" />
                        </Link>
                        <div>
                            <h1 class="text-lg font-bold text-[#003366] leading-tight">Edit Product</h1>
                            <p class="text-xs text-slate-500">Updating details for <span class="font-bold text-slate-700">{{ product.name_en }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <Link 
                            href="/admin/products" 
                            class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 transition-all shadow-sm"
                        >
                            Cancel
                        </Link>
                        
                        <!-- Sole Submit Button -->
                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="inline-flex items-center gap-2 px-5 py-2 bg-[#003366] hover:bg-[#002244] active:bg-[#001122] text-white rounded-lg text-xs font-semibold shadow-sm hover:shadow transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                            <Check v-else class="w-4 h-4 text-emerald-400" />
                            <span>{{ form.processing ? 'Updating...' : 'Update Product' }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Main Column (2 Columns Wide on Desktop) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card 1: General Details -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h2 class="text-sm font-bold text-slate-800">General Information</h2>
                            <p class="text-xs text-slate-500">Product titles, details, and localized text.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Product Name (English) <span class="text-red-500">*</span></label>
                                <input v-model="form.name_en" type="text" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-800 focus:outline-none focus:border-[#003366] focus:ring-1 focus:ring-[#003366] transition-all">
                                <p v-if="form.errors.name_en" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.name_en }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Product Name (Bangla)</label>
                                <input v-model="form.name_bn" type="text" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-800 focus:outline-none focus:border-[#003366] focus:ring-1 focus:ring-[#003366] transition-all">
                                <p v-if="form.errors.name_bn" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.name_bn }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description (English)</label>
                                <textarea v-model="form.description_en" rows="4" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-800 focus:outline-none focus:border-[#003366] focus:ring-1 focus:ring-[#003366] transition-all"></textarea>
                                <p v-if="form.errors.description_en" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.description_en }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description (Bangla)</label>
                                <textarea v-model="form.description_bn" rows="4" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-800 focus:outline-none focus:border-[#003366] focus:ring-1 focus:ring-[#003366] transition-all"></textarea>
                                <p v-if="form.errors.description_bn" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.description_bn }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Short Description / Key Features</label>
                                <textarea v-model="form.short_description" rows="3" placeholder="Enter key features (one per line)..." class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-800 focus:outline-none focus:border-[#003366] focus:ring-1 focus:ring-[#003366] transition-all"></textarea>
                                <p v-if="form.errors.short_description" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.short_description }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Technical Specs / Long Description</label>
                                <textarea v-model="form.long_description" rows="4" placeholder="Enter detailed specs (e.g. Brand: HP)..." class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-800 focus:outline-none focus:border-[#003366] focus:ring-1 focus:ring-[#003366] transition-all"></textarea>
                                <p v-if="form.errors.long_description" class="mt-1 text-xs font-medium text-red-500">{{ form.errors.long_description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Pricing & Inventory -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h2 class="text-sm font-bold text-slate-800">Pricing & Inventory</h2>
                            <p class="text-xs text-slate-500">Retail price, cost breakdown, stock amounts, and attributes.</p>
                        </div>

                        <!-- Price Section -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Sell Price (৳) <span class="text-red-500">*</span></label>
                                <input v-model="form.price" type="number" step="0.01" class="w-full px-3 py-1.5 rounded-md border border-slate-300 bg-white text-sm font-bold text-slate-900 focus:outline-none focus:border-[#003366]">
                                <p v-if="form.errors.price" class="mt-1 text-xs text-red-500">{{ form.errors.price }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Buy Price (৳)</label>
                                <input v-model="form.buying_price" type="number" step="0.01" class="w-full px-3 py-1.5 rounded-md border border-slate-300 bg-white text-sm font-bold text-slate-900 focus:outline-none focus:border-[#003366]">
                                <p v-if="form.errors.buying_price" class="mt-1 text-xs text-red-500">{{ form.errors.buying_price }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Pack Cost (৳)</label>
                                <input v-model="form.package_cost" type="number" step="0.01" class="w-full px-3 py-1.5 rounded-md border border-slate-300 bg-white text-sm font-bold text-slate-900 focus:outline-none focus:border-[#003366]">
                                <p v-if="form.errors.package_cost" class="mt-1 text-xs text-red-500">{{ form.errors.package_cost }}</p>
                            </div>
                        </div>

                        <!-- Stock & Extra Specs -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Display Serial</label>
                                <input v-model="form.serial" type="number" placeholder="1" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm font-medium focus:outline-none focus:border-[#003366]">
                                <p v-if="form.errors.serial" class="mt-1 text-xs text-red-500">{{ form.errors.serial }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Stock Amount</label>
                                <input v-model="form.stock" type="number" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm font-medium focus:outline-none focus:border-[#003366]">
                                <p v-if="form.errors.stock" class="mt-1 text-xs text-red-500">{{ form.errors.stock }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Size</label>
                                <input v-model="form.size" type="text" placeholder="e.g. 10x20cm" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm font-medium focus:outline-none focus:border-[#003366]">
                                <p v-if="form.errors.size" class="mt-1 text-xs text-red-500">{{ form.errors.size }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Weight</label>
                                <input v-model="form.weight" type="text" placeholder="e.g. 1.5kg" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm font-medium focus:outline-none focus:border-[#003366]">
                                <p v-if="form.errors.weight" class="mt-1 text-xs text-red-500">{{ form.errors.weight }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Discount & Notes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Discount Block -->
                        <div class="bg-gradient-to-br from-orange-50/70 to-amber-50/40 rounded-xl border border-orange-200/60 p-5 space-y-4">
                            <div class="flex items-center gap-2 border-b border-orange-100 pb-2">
                                <Tag class="w-4 h-4 text-[#FF6600]" />
                                <h2 class="text-xs font-bold uppercase tracking-wider text-orange-900">Discount Settings</h2>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Discount Type</label>
                                    <select v-model="form.discount_type" class="w-full px-3 py-2 rounded-lg bg-white border border-orange-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#FF6600]">
                                        <option :value="null">No Discount</option>
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount (৳)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Discount Value</label>
                                    <input v-model="form.discount_value" type="number" step="0.01" :disabled="!form.discount_type" placeholder="0" class="w-full px-3 py-2 rounded-lg bg-white border border-orange-200 text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#FF6600] disabled:opacity-50" />
                                </div>
                            </div>
                        </div>

                        <!-- Customer Remarks Block -->
                        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5 space-y-3">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Product Note / Remarks</h2>
                            <div>
                                <textarea v-model="form.remarks" rows="3" placeholder="Optional note shown to customers..." class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:border-[#003366]"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Sidebar Column (1 Column Wide on Desktop) -->
                <div class="space-y-6">
                    
                    <!-- Card 4: Classification -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                        <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Classification</h2>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Main Category <span class="text-red-500">*</span></label>
                            <select v-model="form.category_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white text-slate-800 focus:outline-none focus:border-[#003366]">
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name_en }}</option>
                            </select>
                        </div>

                        <div v-if="selectedCategorySubs.length > 0">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subcategory</label>
                            <select v-model="form.sub_category_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white text-slate-800 focus:outline-none focus:border-[#003366]">
                                <option :value="null">Select Subcategory</option>
                                <option v-for="sub in selectedCategorySubs" :key="sub.id" :value="sub.id">{{ sub.name_en }}</option>
                            </select>
                        </div>

                        <div v-if="availableBrands.length > 0 || (form.category_id && !form.sub_category_id && selectedCategorySubs.length === 0) || (form.category_id && form.sub_category_id)">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Brand Association</label>
                            <select v-model="form.brand_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white text-slate-800 focus:outline-none focus:border-[#003366]">
                                <option :value="null">No Brand / Universal</option>
                                <option v-for="brand in availableBrands" :key="brand.id" :value="brand.id">{{ brand.name_en }}</option>
                            </select>
                            <p v-if="availableBrands.length === 0 && form.category_id" class="mt-1 text-[11px] text-slate-400 italic">
                                {{ (selectedCategorySubs.length > 0 && !form.sub_category_id) ? 'Select a subcategory to view brands' : 'No brands linked to this classification' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 5: Media Assets -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5 space-y-5">
                        <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Media Assets</h2>

                        <!-- Main Image Upload -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-slate-700">Primary Display Image</label>
                                <button @click.prevent="showMainPicker = true" type="button" class="text-xs font-semibold text-[#FF6600] hover:underline flex items-center gap-1">
                                    <Library class="w-3.5 h-3.5" /> Gallery
                                </button>
                            </div>

                            <div class="relative group w-full h-44 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-slate-100/60 hover:border-slate-300 transition-all flex flex-col items-center justify-center overflow-hidden cursor-pointer">
                                <img v-if="mainPreview || product.image" :src="mainPreview || product.image" class="w-full h-full object-cover" />
                                <div v-else class="text-center p-4">
                                    <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-[#003366]">
                                        <ImageIcon class="w-5 h-5" />
                                    </div>
                                    <p class="text-xs font-semibold text-slate-700">Click to change main image</p>
                                </div>
                                <input type="file" @input="handleMainImage" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />

                                <button 
                                    v-if="mainPreview" 
                                    @click.prevent="mainPreview = null; form.image = null" 
                                    type="button" 
                                    class="absolute top-2 right-2 p-1.5 bg-slate-900/80 hover:bg-red-600 text-white rounded-lg shadow transition-colors backdrop-blur-sm"
                                >
                                    <RefreshCw class="w-3.5 h-3.5" />
                                </button>
                            </div>
                            <p v-if="form.errors.image" class="mt-1 text-xs text-red-500">{{ form.errors.image }}</p>
                        </div>

                        <!-- Gallery Section -->
                        <div class="pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-slate-700">Product Gallery</label>
                                <button @click.prevent="showGalleryPicker = true" type="button" class="text-xs font-semibold text-[#FF6600] hover:underline flex items-center gap-1">
                                    <Library class="w-3.5 h-3.5" /> Gallery
                                </button>
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <!-- Existing Images -->
                                <div v-for="(img, idx) in form.keep_gallery_images" :key="'old-'+img.id" class="relative aspect-square rounded-lg overflow-hidden border border-slate-200 group">
                                    <img :src="img.image" class="w-full h-full object-cover">
                                    <button @click.prevent="removeKeepImage(idx)" type="button" class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white" title="Remove image">
                                        <X class="w-4 h-4" />
                                    </button>
                                </div>

                                <!-- New Gallery Previews -->
                                <div v-for="(img, idx) in galleryPreviews" :key="'new-'+idx" class="relative aspect-square rounded-lg overflow-hidden border-2 border-[#FF6600] group">
                                    <img :src="img" class="w-full h-full object-cover">
                                    <button @click.prevent="removeNewGalleryImage(idx)" type="button" class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <X class="w-4 h-4" />
                                    </button>
                                    <span class="absolute top-1 left-1 bg-[#FF6600] text-white text-[8px] font-bold uppercase px-1 rounded">New</span>
                                </div>

                                <!-- Add More Button -->
                                <button 
                                    @click.prevent="triggerGalleryUpload" 
                                    type="button" 
                                    class="aspect-square rounded-lg border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-slate-100 hover:border-slate-300 transition-all flex flex-col items-center justify-center text-slate-400 hover:text-slate-600"
                                >
                                    <Plus class="w-5 h-5" />
                                </button>
                            </div>
                            <input type="file" multiple ref="galleryInput" @input="handleGalleryImages" class="hidden" accept="image/*" />
                            <p v-if="form.errors.gallery_images" class="mt-1 text-xs text-red-500">{{ form.errors.gallery_images }}</p>
                        </div>
                    </div>

                    <!-- Card 6: Visibility Toggle -->
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Active Status</h3>
                                <p class="text-[11px] text-slate-500">Visible to customers in store</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="form.is_active" type="checkbox" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#003366]"></div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Media Picker Modals -->
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
import { ArrowLeft, Image as ImageIcon, X, RefreshCw, Plus, Library } from 'lucide-vue-next';

const props = defineProps({
    product: Object,
    categories: Array,
    brands: Array
});

const form = useForm({
    _method: 'PUT',
    category_id: props.product.category_id,
    sub_category_id: props.product.sub_category_id,
    name_en: props.product.name_en || '',
    name_bn: props.product.name_bn || '',
    brand_id: props.product.brand_id,
    description_en: props.product.description_en || '',
    description_bn: props.product.description_bn || '',
    short_description: props.product.short_description || '',
    long_description: props.product.long_description || '',
    price: props.product.price,
    buying_price: props.product.buying_price,
    package_cost: props.product.package_cost,
    stock: props.product.stock,
    image: null,
    gallery_images: [],
    keep_gallery_images: [...props.product.gallery],
    is_active: props.product.is_active ? true : false,
    discount_value: props.product.discount_value || null,
    remarks: props.product.remarks || '',
    size: props.product.size || '',
    weight: props.product.weight || '',
    serial: props.product.serial || null,
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

watch(() => form.category_id, (newVal) => {
    if (newVal !== props.product.category_id) {
        form.sub_category_id = null;
        form.brand_id = null;
    }
});

watch(() => form.sub_category_id, (newVal) => {
    if (newVal !== props.product.sub_category_id) {
        form.brand_id = null;
    }
});

const handleMainImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        mainPreview.value = URL.createObjectURL(file);
    }
};

const handleMainGallerySelect = (item) => {
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

const removeKeepImage = (idx) => {
    form.keep_gallery_images.splice(idx, 1);
};

const removeNewGalleryImage = (idx) => {
    form.gallery_images.splice(idx, 1);
    galleryPreviews.value.splice(idx, 1);
};

const submit = () => {
    const originalKeep = [...form.keep_gallery_images];
    form.keep_gallery_images = originalKeep.map(img => img.id);

    form.post(`/admin/products/${props.product.id}`, {
        onSuccess: () => {
            form.keep_gallery_images = originalKeep;
        },
        onError: () => {
            form.keep_gallery_images = originalKeep;
        }
    });
};
</script>
