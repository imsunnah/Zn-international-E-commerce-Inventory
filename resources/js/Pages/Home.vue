<template>
    <StoreLayout>
        <!-- HERO BANNER -->
        <section class="bg-white py-6 md:py-10 border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16">
                <div class="grid lg:grid-cols-3 gap-5">
                    <!-- Main Slider -->
                    <div
                        class="lg:col-span-2 relative h-[260px] md:h-[420px] overflow-hidden rounded-2xl bg-white border border-slate-200 group"
                        @mouseenter="pauseSlider"
                        @mouseleave="resumeSlider"
                    >
                        <!-- Slides -->
                        <div
                            class="absolute inset-0 flex transition-transform duration-700 ease-out mix-blend-multiply"
                            :style="{
                                transform: `translateX(-${currentSlide * 100}%)`,
                            }"
                        >
                            <div
                                v-for="(img, i) in sliderImages"
                                :key="i"
                                class="min-w-full h-full relative flex items-center justify-center p-6"
                            >
                                <img
                                    :src="img"
                                    class="max-w-full max-h-full object-contain"
                                    :alt="`Slide ${i + 1}`"
                                />
                            </div>

                            <!-- Fallback slide -->
                            <div
                                v-if="!sliderImages.length"
                                class="min-w-full h-full flex items-center px-10 md:px-16 gap-8 bg-slate-50"
                            >
                                <div class="flex-1">
                                    <span
                                        class="text-emerald-600 text-[11px] font-bold uppercase tracking-wider block mb-3"
                                    >
                                        New Arrivals
                                    </span>
                                    <h1
                                        class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-5"
                                    >
                                        Premium standard<br />equipment
                                    </h1>
                                    <div class="flex gap-3">
                                        <Link
                                            href="/shop"
                                            class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors"
                                        >
                                            Shop now
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dot indicators -->
                        <div
                            v-if="sliderImages.length > 1"
                            class="absolute bottom-4 left-6 flex gap-2 z-10"
                        >
                            <button
                                v-for="(_, i) in sliderImages"
                                :key="i"
                                @click="goToSlide(i)"
                                :class="
                                    currentSlide === i
                                        ? 'w-6 bg-emerald-600'
                                        : 'w-2 bg-slate-400 hover:bg-slate-500'
                                "
                                class="h-1.5 rounded-full transition-all duration-300"
                            />
                        </div>
                    </div>

                    <!-- Side Promo -->
                    <Link
                        href="/shop?type=combo"
                        class="relative h-[200px] md:h-[420px] rounded-2xl overflow-hidden bg-emerald-50 border border-emerald-100 flex flex-col justify-center items-start px-8 group hover:shadow-md transition-shadow"
                    >
                        <span
                            class="text-emerald-700 text-[10px] font-bold uppercase tracking-wider mb-3"
                            >Build your own</span
                        >
                        <h3
                            class="text-2xl md:text-3xl font-extrabold text-emerald-950 leading-tight mb-4"
                        >
                            Custom PC<br />Builder
                        </h3>
                        <p
                            class="text-emerald-800 text-sm mb-6 max-w-[200px] font-medium"
                        >
                            Pick your components.
                        </p>
                        <span
                            class="px-5 py-2.5 bg-white text-emerald-700 border border-emerald-200 rounded-xl text-sm font-bold flex items-center gap-2 group-hover:bg-emerald-600 group-hover:border-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm"
                        >
                            Start building <ArrowRight class="w-4 h-4" />
                        </span>
                    </Link>
                </div>

                <!-- Marquee strip -->
                <div
                    class="mt-5 bg-white border border-slate-200 rounded-xl px-5 py-3 flex items-center gap-3 overflow-hidden shadow-sm"
                >
                    <Truck class="w-4 h-4 text-emerald-600 shrink-0" />
                    <p
                        class="text-xs text-slate-500 font-medium whitespace-nowrap overflow-hidden text-ellipsis"
                    >
                        Nationwide delivery · Guaranteed Quality · Contact
                        Center:
                    </p>
                </div>
            </div>
        </section>

        <!-- FEATURED CATEGORIES -->
        <section class="py-12 md:py-16 bg-slate-50 border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                        Top Categories
                    </h2>
                    <div class="flex gap-1.5">
                        <button
                            @click="scrollContainer('cat-container', -300)"
                            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-500 shadow-sm"
                        >
                            <ChevronLeft class="w-4 h-4" />
                        </button>
                        <button
                            @click="scrollContainer('cat-container', 300)"
                            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-500 shadow-sm"
                        >
                            <ChevronRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div
                    id="cat-container"
                    class="grid grid-flow-col auto-cols-[calc((100%-1.5rem)/3)] sm:auto-cols-[calc((100%-3rem)/4)] lg:grid-cols-6 lg:auto-cols-auto gap-4 md:gap-5 overflow-x-auto snap-x scroll-smooth no-scrollbar pb-2"
                >
                    <Link
                        v-for="cat in featuredCategories"
                        :key="cat.id"
                        :href="`/shop?category=${cat.slug}`"
                        class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-sm transition-all snap-start"
                    >
                        <div
                            class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center p-3 overflow-hidden group-hover:bg-emerald-50 transition-colors duration-300"
                        >
                            <img
                                :src="
                                    cat.image ||
                                    `https://placehold.co/200x200/f8fafc/64748b?text=${encodeURIComponent(cat.name)}`
                                "
                                :alt="cat.name"
                                class="w-full h-full object-contain mix-blend-multiply"
                            />
                        </div>
                        <span
                            class="text-xs font-bold text-slate-700 text-center group-hover:text-emerald-700 transition-colors line-clamp-1"
                        >
                            {{ cat.name }}
                        </span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- PRODUCT TABS (2 Rows, 5 Per Row Grid) -->
        <section class="py-12 md:py-16 bg-white border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16">
                <div class="flex justify-between items-center mb-8">
                    <div
                        class="flex gap-1 bg-slate-50 border border-slate-200 rounded-xl p-1 w-fit shadow-sm"
                    >
                        <button
                            v-for="tab in productTabs"
                            :key="tab.key"
                            @click="activeTab = tab.key"
                            :class="
                                activeTab === tab.key
                                    ? 'bg-white text-slate-900 shadow-sm border border-slate-200'
                                    : 'text-slate-500 hover:text-slate-700 bg-transparent border border-transparent'
                            "
                            class="px-5 py-2 rounded-lg text-sm font-bold transition-all duration-200"
                        >
                            {{ tab.label }}
                        </button>
                    </div>
                </div>

                <Transition name="tab-fade" mode="out-in">
                    <!-- Redesigned Grid layout to support 2 rows of 5 products perfectly on desktop -->
                    <div
                        :key="activeTab"
                        id="prod-container"
                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-5 pb-3 pt-1"
                    >
                        <div
                            v-for="p in tabbedProducts"
                            :key="p.id"
                            class="h-full"
                        >
                            <ProductCard
                                :product="p"
                                @quickView="openQuickView"
                            />
                        </div>
                    </div>
                </Transition>

                <div class="mt-10 flex justify-center">
                    <Link
                        href="/shop"
                        class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 hover:text-emerald-600 transition-colors flex items-center gap-2 shadow-sm"
                    >
                        View Full Inventory <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- BUNDLES -->
        <section
            v-if="combos?.length"
            class="py-12 md:py-16 bg-slate-50 border-b border-slate-100"
        >
            <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16">
                <div class="mb-8">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                        Combo Deals
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Pre-configured bundles from our experts.
                    </p>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6"
                >
                    <ComboCard
                        v-for="combo in combos"
                        :key="combo.id"
                        :combo="combo"
                    />
                </div>
            </div>
        </section>

        <!-- TOP BRANDS (Styled Cohesively Like Categories) -->
        <section
            v-if="brands?.length"
            class="py-12 bg-white border-b border-slate-100"
        >
            <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                        Top Brands
                    </h2>
                    <div class="flex gap-1.5">
                        <button
                            @click="scrollContainer('brand-container', -300)"
                            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-500 shadow-sm"
                        >
                            <ChevronLeft class="w-4 h-4" />
                        </button>
                        <button
                            @click="scrollContainer('brand-container', 300)"
                            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-500 shadow-sm"
                        >
                            <ChevronRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div
                    id="brand-container"
                    class="grid grid-flow-col auto-cols-[calc((100%-1.5rem)/3)] sm:auto-cols-[calc((100%-3rem)/4)] lg:grid-cols-6 lg:auto-cols-auto gap-4 md:gap-5 overflow-x-auto snap-x scroll-smooth no-scrollbar pb-2"
                >
                    <Link
                        v-for="brand in brands"
                        :key="brand.id"
                        :href="`/shop?brand=${brand.slug}`"
                        class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-sm transition-all snap-start"
                    >
                        <div
                            class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center p-3 overflow-hidden group-hover:bg-emerald-50 transition-colors duration-300"
                        >
                            <img
                                v-if="brand.image"
                                :src="brand.image"
                                :alt="brand.name"
                                class="w-full h-full object-contain mix-blend-multiply"
                            />
                            <span
                                v-else
                                class="text-sm font-black text-slate-400"
                            >
                                {{ brand.name.substring(0, 2).toUpperCase() }}
                            </span>
                        </div>
                        <span
                            class="text-xs font-bold text-slate-700 text-center group-hover:text-emerald-700 transition-colors line-clamp-1"
                        >
                            {{ brand.name }}
                        </span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Quick View -->
        <ImageLightbox
            :show="!!selectedProduct"
            :product="selectedProduct"
            @close="selectedProduct = null"
        />
    </StoreLayout>
</template>
<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { ArrowRight, Truck, ChevronLeft, ChevronRight } from "lucide-vue-next";
import ProductCard from "@/Components/ProductCard.vue";
import ComboCard from "@/Components/ComboCard.vue";
import ImageLightbox from "@/Components/ImageLightbox.vue";

const props = defineProps({
    topSelling: { type: Array, default: () => [] },
    latestProducts: { type: Array, default: () => [] },
    discountedProducts: { type: Array, default: () => [] },
    combos: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
});

const page = usePage();
const settings = computed(() => page.props.settings || {});
const allCategories = computed(() => page.props.categories || []);
const featuredCategories = computed(() => allCategories.value.slice(0, 12));

const activeTab = ref("featured");

const productTabs = [
    { key: "featured", label: "Recommended" },
    { key: "new", label: "New Stock" },
    { key: "deals", label: "Discounts" },
];

const tabbedProducts = computed(() => {
    if (activeTab.value === "new") return props.latestProducts.slice(0, 10);
    if (activeTab.value === "deals")
        return props.discountedProducts.slice(0, 10);
    return (
        props.topSelling.length ? props.topSelling : props.latestProducts
    ).slice(0, 10);
});

const selectedProduct = ref(null);
const openQuickView = (product) => {
    selectedProduct.value = product;
};

const scrollContainer = (id, offset) => {
    const el = document.getElementById(id);
    if (el) el.scrollLeft += offset;
};

const sliderImages = computed(() => {
    try {
        const raw = page.props.settings?.slider_images;
        if (!raw) return [];
        const parsed = typeof raw === "string" ? JSON.parse(raw) : raw;
        return Array.isArray(parsed) && parsed.length ? parsed : [];
    } catch {
        return [];
    }
});

const currentSlide = ref(0);
let autoSlide = null;
let isPaused = false;

const goToSlide = (i) => {
    currentSlide.value =
        ((i % sliderImages.value.length) + sliderImages.value.length) %
        sliderImages.value.length;
};
const nextSlide = () => goToSlide(currentSlide.value + 1);
const pauseSlider = () => {
    isPaused = true;
};
const resumeSlider = () => {
    isPaused = false;
};

onMounted(() => {
    if (sliderImages.value.length > 1) {
        autoSlide = setInterval(() => {
            if (!isPaused) nextSlide();
        }, 6000);
    }
});

onUnmounted(() => {
    if (autoSlide) clearInterval(autoSlide);
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.tab-fade-enter-active,
.tab-fade-leave-active {
    transition:
        opacity 0.2s ease,
        transform 0.2s ease;
}
.tab-fade-enter-from {
    opacity: 0;
    transform: translateY(4px);
}
.tab-fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
