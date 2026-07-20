<template>
    <StoreLayout>
        <div class="bg-white min-h-screen py-6 font-sans antialiased text-slate-800">
            <!-- Top Container: Breadcrumbs & Product Main Info -->
            <div class="max-w-6xl mx-auto px-4 md:px-6">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-6 overflow-x-auto whitespace-nowrap">
                    <Link href="/" class="hover:text-slate-900 transition-colors">Home</Link>
                    <span>/</span>
                    <Link v-if="product.category" :href="`/shop?category=${product.category.slug}`" class="hover:text-slate-900 transition-colors">{{ product.category.name_en }}</Link>
                    <span v-if="product.category">/</span>
                    <Link v-if="product.sub_category" :href="`/shop?subcategory=${product.sub_category.slug}`" class="hover:text-slate-900 transition-colors">{{ product.sub_category.name_en }}</Link>
                    <span v-if="product.sub_category">/</span>
                    <span class="text-slate-600 truncate">{{ product.name }}</span>
                </nav>

                <!-- Main Top Section (Clean & Borderless) -->
                <div class="grid lg:grid-cols-12 gap-8 items-start mb-12">
                    <!-- Column 1: Image Gallery -->
                    <div class="lg:col-span-5 space-y-4">
                        <div 
                            @click="openLightboxMain"
                            class="relative aspect-square flex items-center justify-center cursor-zoom-in group"
                        >
                            <img
                                :src="activeImage || `https://placehold.co/800x800/f8fafc/64748b?text=${encodeURIComponent(product.name)}`"
                                :alt="product.name"
                                class="w-full h-full object-contain max-h-[380px]"
                            />
                            <div
                                v-if="product.stock <= 0"
                                class="absolute inset-0 bg-white/60 flex items-center justify-center"
                            >
                                <span class="px-4 py-1.5 bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded">Out of Stock</span>
                            </div>
                        </div>

                        <!-- Gallery Thumbnails -->
                        <div class="flex flex-wrap gap-2 pt-2" v-if="allImages.length > 1">
                            <button
                                v-for="(img, index) in allImages"
                                :key="index"
                                @click="activeImage = img"
                                :class="[
                                    'w-16 h-16 border transition-all bg-white p-1',
                                    activeImage === img ? 'border-emerald-600' : 'border-slate-200 hover:border-slate-300',
                                ]"
                            >
                                <img :src="img" class="w-full h-full object-contain" />
                            </button>
                        </div>
                    </div>

                    <!-- Column 2: Product Info & Actions -->
                    <div class="lg:col-span-7 space-y-4">
                        <h1 class="text-xl lg:text-2xl font-bold text-slate-800 leading-snug">
                            {{ product.name }}
                        </h1>

                        <!-- Price -->
                        <div class="flex items-baseline gap-2">
                            <span class="text-lg font-bold text-emerald-600">৳{{ discountedPrice.toLocaleString() }}</span>
                            <span v-if="product.discount_type" class="text-sm text-slate-400 line-through">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                        </div>

                        <!-- Key Bullet Points -->
                        <div class="pt-2 text-xs text-slate-700 space-y-1.5" v-if="parsedFeatures.length > 0">
                            <div v-for="(feature, i) in parsedFeatures" :key="i" class="flex items-start gap-2">
                                <span class="text-slate-400">•</span>
                                <span>{{ feature }}</span>
                            </div>
                        </div>

                        <!-- Contact / Hotline -->
                        <div class="pt-2 space-y-2">
                            <p class="text-xs font-bold text-emerald-600">
                                Hotline: 01322-893290-3 (10:00AM TO 8:00PM)
                            </p>
                            <div>
                                <a
                                    :href="`https://wa.me/${$page.props.settings?.footer_phone || '8801322893290'}?text=${encodeURIComponent('Hi, I want to order: ' + product.name)}`"
                                    target="_blank"
                                    class="inline-flex items-center gap-1.5 bg-[#00a651] hover:bg-[#008c44] text-white text-xs font-bold px-3 py-1.5 rounded transition-colors"
                                >
                                    Chat Through WhatsApp
                                </a>
                            </div>
                        </div>

                        <!-- Purchase Form -->
                        <div class="flex items-center gap-2 pt-2">
                            <div class="flex items-center border border-slate-300 rounded h-8 bg-white">
                                <button
                                    @click="quantity > 1 && quantity--"
                                    type="button"
                                    class="w-7 h-full text-slate-500 hover:bg-slate-100 flex items-center justify-center text-sm font-bold"
                                >
                                    -
                                </button>
                                <input
                                    type="text"
                                    readonly
                                    :value="quantity"
                                    class="w-10 text-center text-xs font-bold text-slate-800 border-none outline-none p-0 focus:ring-0"
                                />
                                <button
                                    @click="quantity < (product.stock || 99) && quantity++"
                                    type="button"
                                    class="w-7 h-full text-slate-500 hover:bg-slate-100 flex items-center justify-center text-sm font-bold"
                                >
                                    +
                                </button>
                            </div>

                            <button
                                @click="handleAddToCart"
                                :disabled="product.stock <= 0"
                                class="h-8 px-4 bg-[#00a651] hover:bg-[#008c44] text-white font-bold text-xs rounded transition-colors disabled:bg-slate-300"
                            >
                                Add to cart
                            </button>

                            <button
                                @click="handleBuyNow"
                                :disabled="product.stock <= 0"
                                class="h-8 px-4 bg-[#ef4823] hover:bg-[#d63d1a] text-white font-bold text-xs rounded transition-colors disabled:bg-slate-300"
                            >
                                Buy now
                            </button>
                        </div>

                        <!-- Meta Info -->
                        <div class="pt-4 border-t border-slate-100 text-[11px] text-slate-500 space-y-1">
                            <div>Categories: <span class="text-slate-700">{{ product.category?.name_en }}</span></div>
                            <div v-if="product.brand">Brand: <span class="text-slate-700">{{ product.brand.name_en }}</span></div>
                            <div v-if="product.sku">SKU: <span class="text-slate-700">{{ product.sku }}</span></div>
                        </div>

                        <!-- Social Share Icons -->
                        <div class="pt-2 flex items-center gap-2 text-slate-400 text-xs">
                            <a href="#" class="hover:text-slate-600">FB</a>
                            <a href="#" class="hover:text-slate-600">TW</a>
                            <a href="#" class="hover:text-slate-600">LN</a>
                            <a href="#" class="hover:text-slate-600">PN</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FULL WIDTH Description Section Background (breaks out of parent container) -->
            <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen bg-slate-50 border-t border-slate-200/80 py-10">
                <div class="max-w-6xl mx-auto px-4 md:px-6">
                    <div class="grid lg:grid-cols-12 gap-8 items-start">
                        
                        <!-- Main Content Details (8 cols) -->
                        <div class="lg:col-span-8">
                            <!-- Clean Tab Header -->
                            <div class="flex gap-4 border-b border-slate-200 text-xs font-bold mb-6">
                                <button
                                    @click="activeTab = 'description'"
                                    :class="[
                                        'pb-2 px-1 transition-all',
                                        activeTab === 'description' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-500 hover:text-slate-800'
                                    ]"
                                >
                                    Description
                                </button>
                                <button
                                    @click="activeTab = 'reviews'"
                                    :class="[
                                        'pb-2 px-1 transition-all',
                                        activeTab === 'reviews' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-500 hover:text-slate-800'
                                    ]"
                                >
                                    Reviews ({{ product.reviews ? product.reviews.length : 0 }})
                                </button>
                            </div>

                            <!-- Description Tab -->
                            <div v-show="activeTab === 'description'" class="space-y-6">
                                <h2 class="text-base font-bold text-slate-800">{{ product.name }}</h2>

                                <!-- Specifications Table -->
                                <div v-if="parsedSpecs.length > 0" class="bg-white border border-slate-200 rounded overflow-hidden">
                                    <table class="w-full text-xs text-left border-collapse">
                                        <tbody>
                                            <tr v-for="(spec, i) in parsedSpecs" :key="i" class="border-b border-slate-100 last:border-0 odd:bg-slate-50/50">
                                                <td class="py-2.5 px-4 font-bold text-slate-700 w-1/3 border-r border-slate-100">{{ spec.key }}</td>
                                                <td class="py-2.5 px-4 text-slate-600">{{ spec.value }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div v-else class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-white p-4 border border-slate-200 rounded">
                                    {{ product.description || 'No description available for this product.' }}
                                </div>
                            </div>

                            <!-- Reviews Tab -->
                            <div v-show="activeTab === 'reviews'" class="space-y-4 bg-white p-6 border border-slate-200 rounded">
                                <div v-if="product.reviews && product.reviews.length > 0" class="space-y-3">
                                    <div v-for="r in product.reviews" :key="r.id" class="border-b border-slate-100 pb-3">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs font-bold text-slate-800">{{ r.customer_name }}</span>
                                            <span class="text-[10px] text-amber-500 font-bold">{{ r.rating }} ★</span>
                                        </div>
                                        <p class="text-xs text-slate-600">{{ r.comment }}</p>
                                    </div>
                                </div>
                                <p v-else class="text-xs text-slate-400">There are no reviews yet.</p>
                            </div>
                        </div>

                        <!-- Sidebar Widgets (4 cols) -->
                        <div class="lg:col-span-4 space-y-6">
                            <!-- Similar Products -->
                            <div class="bg-white p-4 border border-slate-200 rounded space-y-3">
                                <h3 class="text-xs font-bold text-slate-800 border-b border-slate-100 pb-2">You may also like...</h3>
                                <div class="space-y-3">
                                    <Link v-for="rp in relatedProducts" :key="rp.id" :href="`/products/${rp.slug}`" class="flex items-center gap-3 group">
                                        <img :src="rp.image" class="w-12 h-12 object-contain shrink-0" />
                                        <div class="min-w-0">
                                            <h4 class="text-xs font-semibold text-slate-700 truncate group-hover:text-emerald-600">{{ rp.name }}</h4>
                                            <p class="text-xs font-bold text-emerald-600 mt-0.5">৳{{ parseFloat(rp.price).toLocaleString() }}</p>
                                        </div>
                                    </Link>
                                </div>
                            </div>

                            <!-- Promo Box Banner -->
                            <div class="bg-emerald-50 border border-emerald-300 rounded p-5 text-center space-y-3">
                                <div class="w-10 h-10 bg-emerald-600 text-white rounded-full flex items-center justify-center mx-auto text-base">
                                    📖
                                </div>
                                <h4 class="text-sm font-bold text-slate-800">ব্লগে নিয়মিত লেখা পড়ুন</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">Printer, Scanner, Laptop & Tech Reviews জানতে আমাদের ব্লগ ভিজিট করুন।</p>
                                <Link href="#" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 px-4 rounded inline-block transition-colors">
                                    সব ব্লগ দেখুন
                                </Link>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
<script setup>
import { ref, computed, watch, onMounted } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { useCart } from "@/Composables/useCart";

const props = defineProps({
    product: Object,
    relatedProducts: Array,
});

const { addToCart } = useCart();
const quantity = ref(1);
const activeTab = ref("description");
const showLightbox = ref(false);
const lightboxProduct = ref(null);
const recentlyViewed = ref([]);

const reviewForm = ref({
    customer_name: "",
    comment: "",
    rating: 5,
});

const isSubmittingReview = ref(false);
const reviewSuccessMessage = ref("");

// Save and load recently viewed products
onMounted(() => {
    if (props.product && props.product.id) {
        let list = [];
        try {
            list = JSON.parse(localStorage.getItem('recently_viewed_products') || '[]');
        } catch (e) {
            list = [];
        }
        
        // Remove current product to prevent duplication
        list = list.filter(item => item.id !== props.product.id);
        
        // Push current product to top
        list.unshift({
            id: props.product.id,
            name: props.product.name,
            price: props.product.price,
            slug: props.product.slug,
            image: props.product.image
        });
        
        // Slice max 4 products
        list = list.slice(0, 4);
        
        // Store
        localStorage.setItem('recently_viewed_products', JSON.stringify(list));
        
        // Put other products in reactive ref for display
        recentlyViewed.value = list.filter(item => item.id !== props.product.id).slice(0, 3);
    }
});

const averageRating = computed(() => {
    if (!props.product.reviews || props.product.reviews.length === 0)
        return "0.0";
    const sum = props.product.reviews.reduce((acc, r) => acc + r.rating, 0);
    return (sum / props.product.reviews.length).toFixed(1);
});

const getRatingPercentage = (stars) => {
    if (!props.product.reviews || props.product.reviews.length === 0) return 0;
    const count = props.product.reviews.filter(
        (r) => r.rating === stars,
    ).length;
    return (count / props.product.reviews.length) * 15;
};

// Features split by newline helper
const parsedFeatures = computed(() => {
    if (!props.product.short_description) return [];
    return props.product.short_description.split('\n')
        .map(line => line.trim())
        .filter(line => line.length > 0);
});

// Technical specs parser helper
const parsedSpecs = computed(() => {
    if (!props.product.long_description) return [];
    return props.product.long_description.split('\n')
        .map(line => {
            const index = line.indexOf(':');
            if (index !== -1) {
                return {
                    key: line.substring(0, index).trim(),
                    value: line.substring(index + 1).trim()
                };
            }
            return null;
        })
        .filter(item => item !== null);
});

const submitReview = async () => {
    isSubmittingReview.value = true;
    reviewSuccessMessage.value = "";

    try {
        router.post(
            `/products/${props.product.slug}/reviews`,
            {
                customer_name: reviewForm.value.customer_name || "Guest",
                comment: reviewForm.value.comment,
                rating: reviewForm.value.rating,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    reviewForm.value.customer_name = "";
                    reviewForm.value.comment = "";
                    reviewForm.value.rating = 5;
                    reviewSuccessMessage.value =
                        usePage().props.translations?.[
                            "Thank you! Your review has been posted successfully."
                        ] ||
                        "Thank you! Your review has been posted successfully.";
                },
            },
        );
    } catch (e) {
        console.error(e);
    } finally {
        isSubmittingReview.value = false;
    }
};

const allImages = computed(() => {
    let images = [];
    if (props.product.image) images.push(props.product.image);
    if (props.product.gallery && Array.isArray(props.product.gallery)) {
        props.product.gallery.forEach((img) => {
            if (img.image) images.push(img.image);
        });
    }
    return images;
});

const activeImage = ref(null);

watch(
    () => props.product.id,
    () => {
        activeImage.value = props.product.image;
        quantity.value = 1;
    },
    { immediate: true },
);

const discountedPrice = computed(() => {
    const price = parseFloat(props.product.price || 0);
    if (!props.product.discount_type) return price;
    if (props.product.discount_type === "percentage") {
        return (
            price * (1 - parseFloat(props.product.discount_value || 0) / 100)
        );
    }
    return Math.max(0, price - parseFloat(props.product.discount_value || 0));
});

const handleAddToCart = async () => {
    await addToCart(props.product, quantity.value);
};

const handleBuyNow = async () => {
    await addToCart(props.product, quantity.value);
    router.visit("/cart");
};

const openLightboxMain = () => {
    lightboxProduct.value = { ...props.product, image: activeImage.value };
    showLightbox.value = true;
};
</script>
