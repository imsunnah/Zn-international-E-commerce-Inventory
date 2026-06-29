<template>
    <StoreLayout>
        <!-- ══════════════════════════════════════════
             ANNOUNCEMENT BAR
        ═══════════════════════════════════════════ -->
        <div class="bg-emerald-600 py-2 px-4 text-center">
            <p
                class="text-[11px] font-semibold text-white tracking-wide flex items-center justify-center gap-3"
            >
                <Truck class="w-3.5 h-3.5 shrink-0" />
                Free shipping over ৳5,000 &nbsp;·&nbsp; Nationwide delivery
                &nbsp;·&nbsp; 24/7 Expert support &nbsp;·&nbsp; 7-day easy
                returns
            </p>
        </div>

        <!-- ══════════════════════════════════════════
             HERO BANNER
        ═══════════════════════════════════════════ -->
        <section class="bg-slate-50 py-5 md:py-7 border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="grid lg:grid-cols-3 gap-4">
                    <!-- Main Slider -->
                    <div
                        class="lg:col-span-2 relative h-[240px] md:h-[420px] overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 via-white to-emerald-50/30 border border-emerald-100/80 group shadow-sm"
                        @mouseenter="pauseSlider"
                        @mouseleave="resumeSlider"
                    >
                        <div
                            class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-emerald-100/40 pointer-events-none"
                        />
                        <div
                            class="absolute right-24 -bottom-8 w-36 h-36 rounded-full bg-emerald-100/30 pointer-events-none"
                        />

                        <div
                            class="absolute inset-0 flex transition-transform duration-700 ease-out"
                            :style="{
                                transform: `translateX(-${currentSlide * 100}%)`,
                            }"
                        >
                            <div
                                v-for="(img, i) in sliderImages"
                                :key="i"
                                class="min-w-full h-full flex items-center justify-center p-6"
                            >
                                <img
                                    :src="img"
                                    class="max-w-full max-h-full object-contain mix-blend-multiply"
                                    :alt="`Slide ${i + 1}`"
                                />
                            </div>

                            <!-- Fallback -->
                            <div
                                v-if="!sliderImages.length"
                                class="min-w-full h-full flex items-center px-10 md:px-14 gap-8 relative z-10"
                            >
                                <div class="flex-1">
                                    <span
                                        class="inline-block bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-4"
                                        >New Arrivals</span
                                    >
                                    <h1
                                        class="text-3xl md:text-[2.6rem] font-black text-slate-950 leading-tight mb-3 tracking-tight"
                                    >
                                        Premium PC<br />Components
                                    </h1>
                                    <p
                                        class="text-slate-500 text-sm mb-6 font-medium max-w-[260px] leading-relaxed"
                                    >
                                        Handpicked hardware by our technology
                                        experts — built to perform.
                                    </p>
                                    <div class="flex gap-3">
                                        <Link
                                            href="/shop"
                                            class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all active:scale-95 flex items-center gap-2"
                                        >
                                            Shop now
                                            <ArrowRight class="w-4 h-4" />
                                        </Link>
                                        <Link
                                            href="/shop?tag=deals"
                                            class="px-5 py-2.5 bg-white text-slate-700 border border-slate-200 rounded-xl text-sm font-bold hover:border-emerald-300 hover:text-emerald-700 transition-all active:scale-95"
                                        >
                                            View deals
                                        </Link>
                                    </div>
                                </div>
                                <div
                                    class="hidden md:flex items-center justify-center w-44 h-44 rounded-2xl bg-white/70 border border-emerald-100/80 shrink-0"
                                >
                                    <svg
                                        class="w-24 h-24 text-emerald-500/60"
                                        fill="none"
                                        viewBox="0 0 96 96"
                                        stroke="currentColor"
                                        stroke-width="1.2"
                                    >
                                        <rect
                                            x="8"
                                            y="24"
                                            width="80"
                                            height="52"
                                            rx="6"
                                        />
                                        <path d="M28 76v8M68 76v8M20 84h56" />
                                        <rect
                                            x="20"
                                            y="34"
                                            width="56"
                                            height="32"
                                            rx="3"
                                        />
                                        <circle cx="48" cy="50" r="8" />
                                        <path
                                            d="M48 42v4M48 54v4M40 50h4M52 50h4"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="sliderImages.length > 1"
                            class="absolute bottom-4 left-6 flex gap-1.5 z-10"
                        >
                            <button
                                v-for="(_, i) in sliderImages"
                                :key="i"
                                @click="goToSlide(i)"
                                :class="
                                    currentSlide === i
                                        ? 'w-5 bg-emerald-600'
                                        : 'w-1.5 bg-slate-300 hover:bg-slate-400'
                                "
                                class="h-1.5 rounded-full transition-all duration-300"
                            />
                        </div>
                        <div
                            v-if="sliderImages.length > 1"
                            class="absolute bottom-4 right-5 text-[10px] font-bold text-slate-400 z-10"
                        >
                            {{ currentSlide + 1 }} / {{ sliderImages.length }}
                        </div>
                    </div>

                    <!-- Custom PC Builder -->
                    <Link
                        href="/shop?type=combo"
                        class="relative h-[200px] md:h-[420px] rounded-2xl overflow-hidden bg-gradient-to-br from-slate-900 to-emerald-950 border border-emerald-900/60 flex flex-col justify-between p-8 group hover:shadow-xl hover:shadow-emerald-900/20 transition-all duration-300"
                    >
                        <div>
                            <span
                                class="text-emerald-400 text-[10px] font-black uppercase tracking-widest mb-3 block"
                                >Build your own</span
                            >
                            <h3
                                class="text-2xl md:text-3xl font-black text-white leading-tight mb-3 tracking-tight"
                            >
                                Custom PC<br />Builder
                            </h3>
                            <p
                                class="text-emerald-300/80 text-sm font-medium leading-relaxed max-w-[180px]"
                            >
                                Choose CPU, GPU, RAM, storage — step by step.
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold group-hover:bg-emerald-400 transition-all duration-300 w-fit"
                        >
                            Start building <ArrowRight class="w-4 h-4" />
                        </span>
                        <div
                            class="absolute -right-8 -bottom-8 w-40 h-40 rounded-full bg-emerald-500/10 pointer-events-none"
                        />
                        <div
                            class="absolute right-10 top-6 w-20 h-20 rounded-full bg-emerald-400/5 pointer-events-none"
                        />
                    </Link>
                </div>

                <!-- Trust strip -->
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div
                        v-for="trust in trustItems"
                        :key="trust.label"
                        class="flex items-center gap-2.5 bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm"
                    >
                        <component
                            :is="trust.icon"
                            class="w-4 h-4 text-emerald-500 shrink-0"
                        />
                        <div>
                            <p
                                class="text-[11px] font-bold text-slate-800 leading-none"
                            >
                                {{ trust.label }}
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                {{ trust.sub }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             FEATURED CATEGORIES — big card, tall image
        ═══════════════════════════════════════════ -->
        <section class="py-12 bg-white border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <!-- Header -->
                <div class="flex items-center justify-between mb-7">
                    <div>
                        <h2
                            class="text-2xl font-black text-slate-950 tracking-tight"
                        >
                            Shop by
                            <span class="text-emerald-600">Category</span>
                        </h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            @click="scrollContainer('cat-container', -440)"
                            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 active:scale-95 transition-all text-slate-500 shadow-sm"
                        >
                            <ChevronLeft class="w-4 h-4" />
                        </button>
                        <button
                            @click="scrollContainer('cat-container', 440)"
                            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 active:scale-95 transition-all text-slate-500 shadow-sm"
                        >
                            <ChevronRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>
                <!-- Category cards track -->
                <div
                    id="cat-container"
                    class="flex items-center justify-center gap-8 overflow-x-auto pb-6 pt-4 scroll-smooth no-scrollbar snap-x snap-mandatory px-6"
                >
                    <Link
                        v-for="cat in featuredCategories"
                        :key="cat.id"
                        :href="`/shop?category=${cat.slug}`"
                        class="group flex aspect-square w-[150px] md:w-[200px] flex-shrink-0 snap-start flex-col items-center justify-between rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)]"
                    >
                        <!-- Image Container (Takes up the top flexible space) -->
                        <div
                            class="flex flex-1 items-center justify-center w-full overflow-hidden pb-2"
                        >
                            <img
                                v-if="cat.image"
                                :src="cat.image"
                                :alt="cat.name"
                                class="h-5/6 w-5/6 md:h-[100%] md:w-[100%] object-contain transition-transform duration-300 group-hover:scale-105"
                            />

                            <!-- Minimalist Fallback Placeholder matching the clean style -->
                            <div
                                v-else
                                class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-50 text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors"
                            >
                                <span class="text-2xl font-bold uppercase">{{
                                    cat.name.charAt(0)
                                }}</span>
                            </div>
                        </div>

                        <!-- Label zone (Positioned perfectly at the bottom inside the square) -->
                        <div class="w-full text-center pt-1">
                            <span
                                class="block truncate text-[11px] font-extrabold uppercase tracking-wider text-slate-500 transition-colors duration-200 group-hover:text-emerald-600 md:text-xs"
                            >
                                {{ cat.name }}
                            </span>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             PRODUCT TABS
        ═══════════════════════════════════════════ -->
        <section class="py-14 bg-slate-50 border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8"
                >
                    <div>
                        <h2
                            class="text-2xl font-black text-slate-950 tracking-tight"
                        >
                            Our <span class="text-emerald-600">Products</span>
                        </h2>
                        <p class="text-slate-400 text-xs mt-1 font-medium">
                            Curated picks from top brands
                        </p>
                    </div>
                    <div
                        class="flex gap-1 bg-white border border-slate-200 rounded-xl p-1 w-fit shadow-sm"
                    >
                        <button
                            v-for="tab in productTabs"
                            :key="tab.key"
                            @click="activeTab = tab.key"
                            :class="
                                activeTab === tab.key
                                    ? 'bg-emerald-600 text-white shadow-sm'
                                    : 'text-slate-500 hover:text-slate-800'
                            "
                            class="px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-wide transition-all duration-200"
                        >
                            {{ tab.label }}
                        </button>
                    </div>
                </div>

                <Transition name="tab-fade" mode="out-in">
                    <div
                        :key="activeTab"
                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"
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
                        class="px-7 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-black uppercase tracking-wider hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50 transition-all flex items-center gap-2 shadow-sm"
                    >
                        View full inventory <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             COMBO DEALS
        ═══════════════════════════════════════════ -->
        <section
            v-if="combos?.length"
            class="py-14 bg-white border-b border-slate-100"
        >
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div
                    class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-8"
                >
                    <div>
                        <h2
                            class="text-2xl font-black text-slate-950 tracking-tight"
                        >
                            Combo <span class="text-emerald-600">Deals</span>
                        </h2>
                        <p class="text-slate-400 text-xs mt-1 font-medium">
                            Pre-configured bundles by our tech experts
                        </p>
                    </div>
                    <Link
                        href="/shop?type=combo"
                        class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition-colors shrink-0"
                    >
                        View all <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5"
                >
                    <ComboCard
                        v-for="combo in combos"
                        :key="combo.id"
                        :combo="combo"
                    />
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             TOP BRANDS — wide card, logo-first, smart
        ═══════════════════════════════════════════ -->
        <section
            v-if="brands?.length"
            class="py-12 bg-slate-50 border-b border-slate-100"
        >
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <!-- Header -->
                <div class="flex items-center justify-between mb-7">
                    <div>
                        <h2
                            class="text-2xl font-black text-slate-950 tracking-tight"
                        >
                            Top <span class="text-emerald-600">Brands</span>
                        </h2>
                        <p class="text-slate-400 text-xs mt-1 font-medium">
                            Official authorized reseller — genuine products
                            guaranteed
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="scrollContainer('brand-container', -480)"
                            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 active:scale-95 transition-all text-slate-500 shadow-sm"
                        >
                            <ChevronLeft class="w-4 h-4" />
                        </button>
                        <button
                            @click="scrollContainer('brand-container', 480)"
                            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 active:scale-95 transition-all text-slate-500 shadow-sm"
                        >
                            <ChevronRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Brand cards track — wide landscape cards, logo dominant -->
                <div
                    id="brand-container"
                    class="flex overflow-x-auto scroll-smooth no-scrollbar gap-4 pb-2"
                >
                    <Link
                        v-for="brand in brands"
                        :key="brand.id"
                        :href="`/shop?brand=${brand.slug}`"
                        class="group flex-shrink-0 w-[170px] md:w-[190px] flex flex-col rounded-2xl border border-slate-100 bg-white hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-600/6 transition-all duration-250 overflow-hidden"
                    >
                        <!-- Logo zone — large, clean white stage -->
                        <div
                            class="h-[110px] md:h-[120px] w-full bg-white flex items-center justify-center px-5 overflow-hidden border-b border-slate-100 group-hover:bg-emerald-50/30 group-hover:border-emerald-100 transition-all duration-250"
                        >
                            <img
                                v-if="brand.image"
                                :src="brand.image"
                                :alt="brand.name"
                                class="max-w-[130px] max-h-[75px] md:max-h-[85px] w-auto object-contain mix-blend-multiply opacity-65 group-hover:opacity-100 transition-all duration-300 group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="flex flex-col items-center gap-1"
                            >
                                <span
                                    class="text-4xl font-black text-slate-200 group-hover:text-emerald-400 transition-colors tracking-tighter leading-none"
                                >
                                    {{
                                        brand.name.substring(0, 2).toUpperCase()
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- Brand info zone -->
                        <div
                            class="px-4 py-3 bg-white group-hover:bg-emerald-50/20 transition-colors"
                        >
                            <p
                                class="text-[13px] font-bold text-slate-800 group-hover:text-emerald-700 transition-colors leading-snug line-clamp-1"
                            >
                                {{ brand.name }}
                            </p>
                            <p
                                class="text-[11px] text-slate-400 group-hover:text-emerald-500 transition-colors mt-0.5 font-medium flex items-center gap-1"
                            >
                                <span>{{
                                    brand.product_count
                                        ? `${brand.product_count}+ products`
                                        : "View all products"
                                }}</span>
                                <ArrowRight
                                    class="w-3 h-3 opacity-0 -translate-x-1 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200"
                                />
                            </p>
                        </div>
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
import {
    ArrowRight,
    Truck,
    ShieldCheck,
    Headphones,
    RefreshCw,
    ChevronLeft,
    ChevronRight,
} from "lucide-vue-next";
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
const allCategories = computed(() => page.props.categories || []);
const featuredCategories = computed(() => allCategories.value.slice(0, 16));

const trustItems = [
    { icon: Truck, label: "Free delivery", sub: "On orders over ৳5,000" },
    { icon: ShieldCheck, label: "Warranty", sub: "Official guarantee" },
    { icon: Headphones, label: "24/7 Support", sub: "Expert team" },
    { icon: RefreshCw, label: "Easy returns", sub: "7-day hassle-free" },
];

const activeTab = ref("featured");
const productTabs = [
    { key: "featured", label: "Recommended" },
    { key: "new", label: "New stock" },
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
const openQuickView = (p) => {
    selectedProduct.value = p;
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
    if (sliderImages.value.length > 1)
        autoSlide = setInterval(() => {
            if (!isPaused) nextSlide();
        }, 6000);
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
        opacity 0.18s ease,
        transform 0.18s ease;
}
.tab-fade-enter-from {
    opacity: 0;
    transform: translateY(6px);
}
.tab-fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
