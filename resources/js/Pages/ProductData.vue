<template>
    <StoreLayout>
        <div
            class="bg-white min-h-screen py-6 font-sans antialiased text-slate-800"
        >
            <div class="max-w-6xl mx-auto px-4 md:px-6">
                <!-- Breadcrumbs -->
                <nav
                    class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-8 overflow-x-auto whitespace-nowrap"
                >
                    <Link
                        href="/"
                        class="hover:text-slate-900 transition-colors"
                        >Home</Link
                    >
                    <ChevronRight class="w-3 h-3 text-slate-300 shrink-0" />
                    <Link
                        href="/shop"
                        class="hover:text-slate-900 transition-colors"
                        >Products</Link
                    >
                    <ChevronRight class="w-3 h-3 text-slate-300 shrink-0" />
                    <span class="text-slate-900 truncate max-w-[200px]">{{
                        product.name
                    }}</span>
                </nav>

                <!-- Main Product Layout -->
                <div
                    class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start mb-16"
                >
                    <!-- Column 1: Image Gallery (Smarter & Sleeker Layout) -->
                    <div class="lg:col-span-7 space-y-4">
                        <div
                            @click="openLightboxMain"
                            class="relative aspect-square rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center p-6 cursor-zoom-in group transition-all duration-300 hover:bg-slate-100/70"
                        >
                            <img
                                :src="
                                    activeImage ||
                                    `https://placehold.co/800x800/f8fafc/64748b?text=${encodeURIComponent(product.name)}`
                                "
                                :alt="product.name"
                                class="w-full h-full object-contain mix-blend-multiply"
                            />
                            <div
                                v-if="product.stock <= 0"
                                class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center font-bold text-sm text-red-500 tracking-wider uppercase rounded-xl"
                            >
                                Out of Stock
                            </div>
                        </div>

                        <!-- Thumbnail Slider -->
                        <div
                            class="flex flex-wrap gap-2"
                            v-if="allImages.length > 1"
                        >
                            <button
                                v-for="(img, index) in allImages"
                                :key="index"
                                @click="activeImage = img"
                                :class="[
                                    'w-16 h-16 rounded-lg overflow-hidden border-2 transition-all duration-200 bg-slate-50 p-1',
                                    activeImage === img
                                        ? 'border-slate-900 scale-95'
                                        : 'border-transparent opacity-70 hover:opacity-100',
                                ]"
                            >
                                <img
                                    :src="img"
                                    class="w-full h-full object-contain mix-blend-multiply"
                                />
                            </button>
                        </div>
                    </div>

                    <!-- Column 2: Elegant Product Details -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="border-b border-slate-100 pb-5">
                            <div
                                class="flex items-center justify-between gap-4 mb-2"
                            >
                                <span
                                    class="text-xs font-bold uppercase tracking-wider text-slate-400"
                                >
                                    {{ product.brand?.name || "Accessories" }}
                                </span>
                                <span class="text-xs text-slate-400"
                                    >ID: #{{
                                        product.id.toString().padStart(6, "0")
                                    }}</span
                                >
                            </div>
                            <h1
                                class="text-xl md:text-2xl font-semibold text-slate-900 tracking-tight leading-snug"
                            >
                                {{ product.name }}
                            </h1>

                            <div class="flex items-baseline gap-3 mt-4">
                                <span class="text-2xl font-bold text-slate-900"
                                    >৳{{
                                        discountedPrice.toLocaleString()
                                    }}</span
                                >
                                <span
                                    v-if="product.discount_type"
                                    class="text-sm text-slate-400 line-through font-normal"
                                    >৳{{
                                        parseFloat(
                                            props.product.price,
                                        ).toLocaleString()
                                    }}</span
                                >
                            </div>
                        </div>

                        <!-- Smart Inline Metadata Specs -->
                        <div
                            class="text-xs grid grid-cols-2 gap-y-3 gap-x-4 py-1 text-slate-600"
                        >
                            <div
                                class="flex justify-between border-b border-slate-50 pb-1.5"
                            >
                                <span class="text-slate-400">Category</span
                                ><span class="font-medium text-slate-900">{{
                                    product.category?.name || "N/A"
                                }}</span>
                            </div>
                            <div
                                class="flex justify-between border-b border-slate-50 pb-1.5"
                            >
                                <span class="text-slate-400">Type</span
                                ><span class="font-medium text-slate-900">{{
                                    product.sub_category?.name || "Standard"
                                }}</span>
                            </div>
                        </div>

                        <!-- Purchase Controls (Simpler Controls) -->
                        <div class="space-y-4 pt-2">
                            <div
                                class="flex items-center justify-between bg-slate-50 p-3 rounded-lg border border-slate-100"
                            >
                                <span class="text-xs font-medium text-slate-500"
                                    >Quantity</span
                                >
                                <div
                                    class="flex items-center bg-white border border-slate-200 rounded-md h-8 w-24"
                                >
                                    <button
                                        @click="quantity > 1 && quantity--"
                                        class="px-2 h-full text-slate-400 hover:text-slate-900 transition-colors"
                                    >
                                        <Minus class="w-3 h-3" />
                                    </button>
                                    <div
                                        class="flex-1 text-center font-medium text-xs text-slate-900"
                                    >
                                        {{ quantity }}
                                    </div>
                                    <button
                                        @click="
                                            quantity < (product.stock || 99) &&
                                            quantity++
                                        "
                                        class="px-2 h-full text-slate-400 hover:text-slate-900 transition-colors"
                                    >
                                        <Plus class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>

                            <!-- Primary CTAs -->
                            <div class="grid sm:grid-cols-2 gap-3">
                                <button
                                    @click="handleAddToCart"
                                    :disabled="product.stock <= 0"
                                    class="h-11 border border-slate-900 text-slate-900 rounded-lg font-medium text-xs hover:bg-slate-50 transition-colors flex items-center justify-center gap-2 disabled:opacity-40 disabled:pointer-events-none"
                                >
                                    <ShoppingCart class="w-3.5 h-3.5" /> Add to
                                    Cart
                                </button>
                                <button
                                    @click="handleBuyNow"
                                    :disabled="product.stock <= 0"
                                    class="h-11 bg-slate-900 text-white rounded-lg font-medium text-xs hover:bg-slate-800 transition-colors flex items-center justify-center disabled:opacity-40 disabled:pointer-events-none"
                                >
                                    Buy Now
                                </button>
                            </div>

                            <!-- Quick Secondary Communication -->
                            <a
                                :href="`https://wa.me/${$page.props.settings.footer_phone}?text=Hi, I want to order ${product.name}`"
                                target="_blank"
                                class="flex h-11 border border-slate-200 text-slate-600 rounded-lg font-medium text-xs hover:bg-slate-50 transition-colors items-center justify-center gap-2 w-full"
                            >
                                <MessageSquare
                                    class="w-3.5 h-3.5 text-emerald-500 fill-emerald-500"
                                />
                                Order via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Content Tabs Section -->
                <div class="mt-12 border-t border-slate-100 pt-8">
                    <div
                        class="flex gap-6 border-b border-slate-100 text-xs font-medium uppercase tracking-wider"
                    >
                        <button
                            @click="activeTab = 'description'"
                            :class="
                                activeTab === 'description'
                                    ? 'text-slate-900 border-b-2 border-slate-900 pb-3 font-semibold'
                                    : 'text-slate-400 pb-3 hover:text-slate-600'
                            "
                        >
                            Description
                        </button>
                        <button
                            @click="activeTab = 'reviews'"
                            :class="
                                activeTab === 'reviews'
                                    ? 'text-slate-900 border-b-2 border-slate-900 pb-3 font-semibold'
                                    : 'text-slate-400 pb-3 hover:text-slate-600'
                            "
                            class="flex items-center gap-1.5"
                        >
                            Reviews
                            <span
                                class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px] lowercase font-normal"
                                >{{
                                    product.reviews ? product.reviews.length : 0
                                }}</span
                            >
                        </button>
                    </div>

                    <div class="py-8">
                        <!-- Plain-text Clean Description -->
                        <div
                            v-if="activeTab === 'description'"
                            class="max-w-3xl text-slate-600 text-sm leading-relaxed whitespace-pre-line font-normal"
                        >
                            {{ product.description }}
                        </div>

                        <!-- Intelligent Review Block -->
                        <div v-if="activeTab === 'reviews'" class="max-w-3xl">
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center gap-6 mb-8 pb-8 border-b border-slate-100"
                            >
                                <div class="flex items-baseline gap-2 shrink-0">
                                    <span
                                        class="text-4xl font-bold text-slate-900 tracking-tight"
                                        >{{ averageRating }}</span
                                    >
                                    <span
                                        class="text-xs text-slate-400 font-medium"
                                        >out of 5</span
                                    >
                                </div>
                                <div
                                    class="flex-grow w-full space-y-1.5 max-w-sm"
                                >
                                    <div
                                        v-for="star in 5"
                                        :key="star"
                                        class="flex items-center gap-3"
                                    >
                                        <span
                                            class="text-[10px] font-medium text-slate-400 w-3"
                                            >{{ 6 - star }} ★</span
                                        >
                                        <div
                                            class="flex-grow h-1 bg-slate-100 rounded-full overflow-hidden"
                                        >
                                            <div
                                                class="h-full bg-slate-900 transition-all"
                                                :style="{
                                                    width:
                                                        getRatingPercentage(
                                                            6 - star,
                                                        ) + '%',
                                                }"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-[10px] font-medium text-slate-400 w-6 text-right"
                                            >{{
                                                getRatingPercentage(
                                                    6 - star,
                                                ).toFixed(0)
                                            }}%</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews Feed -->
                            <div
                                v-if="
                                    product.reviews &&
                                    product.reviews.length > 0
                                "
                                class="space-y-4 mb-10"
                            >
                                <div
                                    v-for="r in product.reviews"
                                    :key="r.id"
                                    class="p-5 bg-slate-50/50 rounded-xl border border-slate-100"
                                >
                                    <div
                                        class="flex justify-between items-start mb-2"
                                    >
                                        <div>
                                            <h4
                                                class="text-xs font-semibold text-slate-900"
                                            >
                                                {{ r.customer_name }}
                                            </h4>
                                            <p
                                                class="text-[10px] text-slate-400"
                                            >
                                                {{
                                                    new Date(
                                                        r.created_at,
                                                    ).toLocaleDateString()
                                                }}
                                            </p>
                                        </div>
                                        <div class="flex gap-0.5">
                                            <Star
                                                v-for="star in 5"
                                                :key="star"
                                                class="w-3 h-3"
                                                :class="
                                                    star <= r.rating
                                                        ? 'text-slate-900 fill-current'
                                                        : 'text-slate-200'
                                                "
                                            />
                                        </div>
                                    </div>
                                    <p
                                        class="text-xs text-slate-600 font-normal leading-relaxed"
                                    >
                                        "{{ r.comment }}"
                                    </p>
                                </div>
                            </div>
                            <div
                                v-else
                                class="text-center py-8 mb-10 bg-slate-50/50 rounded-xl border border-dashed border-slate-200"
                            >
                                <p class="text-xs text-slate-400 font-medium">
                                    Be the first to review this product.
                                </p>
                            </div>

                            <!-- Clean Form -->
                            <div
                                class="border border-slate-100 p-6 rounded-xl max-w-xl"
                            >
                                <h3
                                    class="text-sm font-semibold text-slate-900 mb-4"
                                >
                                    Write a Review
                                </h3>
                                <div
                                    v-if="reviewSuccessMessage"
                                    class="p-3 mb-4 bg-slate-900 text-white text-xs font-medium rounded"
                                >
                                    {{ reviewSuccessMessage }}
                                </div>
                                <form
                                    @submit.prevent="submitReview"
                                    class="space-y-4"
                                >
                                    <div class="grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5"
                                                >Your Name</label
                                            >
                                            <input
                                                type="text"
                                                v-model="
                                                    reviewForm.customer_name
                                                "
                                                required
                                                class="w-full rounded border border-slate-200 px-3 py-2 text-xs focus:ring-1 focus:ring-slate-900 focus:border-slate-900 outline-none transition-colors"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5"
                                                >Rating</label
                                            >
                                            <div
                                                class="flex gap-1 h-8 items-center"
                                            >
                                                <Star
                                                    v-for="star in 5"
                                                    :key="star"
                                                    @click="
                                                        reviewForm.rating = star
                                                    "
                                                    class="w-4 h-4 cursor-pointer transition-colors"
                                                    :class="
                                                        star <=
                                                        reviewForm.rating
                                                            ? 'text-slate-900 fill-current'
                                                            : 'text-slate-200 hover:text-slate-400'
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5"
                                            >Comment</label
                                        >
                                        <textarea
                                            rows="3"
                                            v-model="reviewForm.comment"
                                            required
                                            class="w-full rounded border border-slate-200 p-3 text-xs focus:ring-1 focus:ring-slate-900 focus:border-slate-900 outline-none transition-colors"
                                        ></textarea>
                                    </div>
                                    <button
                                        type="submit"
                                        :disabled="isSubmittingReview"
                                        class="px-4 py-2 bg-slate-900 text-white rounded font-medium text-xs hover:bg-slate-800 transition-colors disabled:opacity-40"
                                    >
                                        {{
                                            isSubmittingReview
                                                ? "Submitting..."
                                                : "Submit Review"
                                        }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products Area -->
                <div
                    v-if="relatedProducts && relatedProducts.length > 0"
                    class="mt-12 pb-12 border-t border-slate-100 pt-12"
                >
                    <h2
                        class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-6"
                    >
                        Related Products
                    </h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <ProductCard
                            v-for="p in relatedProducts"
                            :key="p.id"
                            :product="p"
                        />
                    </div>
                </div>
            </div>
        </div>
        <ImageLightbox
            :show="showLightbox"
            :product="lightboxProduct"
            @close="showLightbox = false"
        />
    </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import StoreLayout from "@/Layouts/StoreLayout.vue";
import ProductCard from "@/Components/ProductCard.vue";
import ImageLightbox from "@/Components/ImageLightbox.vue";
import { useCart } from "@/Composables/useCart";
import {
    Plus,
    Minus,
    ShoppingCart,
    Star,
    ChevronRight,
    MessageSquare,
} from "lucide-vue-next";

const props = defineProps({
    product: Object,
    relatedProducts: Array,
});

const { addToCart } = useCart();
const quantity = ref(1);
const activeTab = ref("description");
const showLightbox = ref(false);
const lightboxProduct = ref(null);

const reviewForm = ref({
    customer_name: "",
    comment: "",
    rating: 5,
});

const isSubmittingReview = ref(false);
const reviewSuccessMessage = ref("");

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
    return (count / props.product.reviews.length) * 100;
};

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
    router.visit("/checkout");
};

const openLightboxMain = () => {
    lightboxProduct.value = { ...props.product, image: activeImage.value };
    showLightbox.value = true;
};
</script>
