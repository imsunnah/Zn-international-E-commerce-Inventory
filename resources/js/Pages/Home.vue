<template>
<StoreLayout>
    <!-- ─── HERO BANNER ──────────────────────────── -->
    <section class="bg-white py-6 md:py-10">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16">
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Main Slider -->
                <div
                    class="lg:col-span-2 relative h-[260px] md:h-[420px] overflow-hidden rounded-2xl bg-slate-100 group"
                    @mouseenter="pauseSlider"
                    @mouseleave="resumeSlider"
                >
                    <div
                        class="absolute inset-0 flex transition-transform duration-700 ease-out"
                        :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
                    >
                        <div v-for="(img, i) in sliderImages" :key="i" class="min-w-full h-full relative">
                            <img :src="img" class="w-full h-full object-cover" :alt="`Slide ${i + 1}`" />
                        </div>
                    </div>
                    <div v-if="sliderImages.length > 1" class="absolute bottom-4 left-4 flex gap-2 z-10">
                        <button
                            v-for="(_, i) in sliderImages"
                            :key="i"
                            @click="goToSlide(i)"
                            :class="currentSlide === i ? 'w-7 bg-emerald-600' : 'w-3 bg-white/70 hover:bg-white'"
                            class="h-1.5 rounded-full transition-all duration-500"
                        ></button>
                    </div>
                </div>

                <!-- Side Promo Card -->
                <Link href="/shop?type=combo" class="relative h-[260px] md:h-[420px] rounded-2xl overflow-hidden bg-emerald-700 flex flex-col justify-center items-start px-8 group">
                    <span class="text-emerald-200 text-xs font-bold uppercase tracking-[0.2em] mb-3">Build your own</span>
                    <h3 class="text-2xl md:text-3xl font-bold text-white leading-tight mb-4">Custom PC<br/>Builder</h3>
                    <p class="text-emerald-100 text-sm mb-6 max-w-[200px]">Pick your components, we handle the rest.</p>
                    <span class="px-5 py-2.5 bg-white text-emerald-700 rounded-xl text-sm font-semibold flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                        Start building <ArrowRight class="w-4 h-4" />
                    </span>
                </Link>
            </div>

            <!-- Marquee strip -->
            <div class="mt-6 bg-slate-50 border border-slate-100 rounded-xl px-5 py-3 flex items-center gap-3 overflow-hidden">
                <Truck class="w-4 h-4 text-emerald-600 shrink-0" />
                <p class="text-xs md:text-sm text-slate-600 font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                    Original Printer, Scanner, Laptop &amp; IT Products — Nationwide Delivery Available — Order Hotline: {{ settings.footer_phone || '01322-893290' }}
                </p>
            </div>
        </div>
    </section>

    <!-- ─── FEATURED CATEGORIES ─────────────────────── -->
    <section class="py-12 md:py-16 bg-slate-50 border-y border-slate-100">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16 relative group/nav">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                    Featured <span class="text-emerald-600">Categories</span>
                </h2>
                <!-- Small Compact Navigation Buttons -->
                <div class="flex gap-1.5">
                    <button @click="scrollContainer('cat-container', -300)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors shadow-sm text-slate-600">
                        <ChevronLeft class="w-4 h-4" />
                    </button>
                    <button @click="scrollContainer('cat-container', 300)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors shadow-sm text-slate-600">
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- 6 in a row layout grid with custom fallback inline scroll on small sizes -->
            <div id="cat-container" class="grid grid-flow-col auto-cols-[calc((100%-1.5rem)/3)] sm:auto-cols-[calc((100%-3rem)/4)] lg:grid-cols-6 lg:auto-cols-auto gap-4 md:gap-5 overflow-x-auto snap-x scroll-smooth no-scrollbar pb-2">
                <Link
                    v-for="cat in featuredCategories"
                    :key="cat.id"
                    :href="`/shop?category=${cat.slug}`"
                    class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-white border border-slate-100 hover:border-emerald-200 hover:shadow-md transition-all snap-start"
                >
                    <div class="w-14 h-14 rounded-xl bg-slate-50 flex items-center justify-center p-3 overflow-hidden">
                        <img :src="cat.image || 'https://placehold.co/200x200/f8fafc/0f172a?text=' + cat.name" :alt="cat.name" class="w-full h-full object-contain" />
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wide text-center group-hover:text-emerald-700 transition-colors line-clamp-1">{{ cat.name }}</span>
                </Link>
            </div>
        </div>
    </section>

    <!-- ─── FEATURED PRODUCTS ──────────────────────────── -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16 relative">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                    Featured <span class="text-emerald-600">Products</span>
                </h2>
                <div class="flex gap-1.5">
                    <button @click="scrollContainer('feat-prod-container', -400)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors shadow-sm text-slate-600">
                        <ChevronLeft class="w-4 h-4" />
                    </button>
                    <button @click="scrollContainer('feat-prod-container', 400)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors shadow-sm text-slate-600">
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Single Row 5-Product Responsive Grid (Snaps into inline scroll for smaller viewports) -->
            <div id="feat-prod-container" class="grid grid-flow-col auto-cols-[calc((100%-1rem)/2)] sm:auto-cols-[calc((100%-2rem)/3)] lg:grid-cols-5 lg:auto-cols-auto gap-4 md:gap-5 overflow-x-auto snap-x scroll-smooth no-scrollbar pb-3">
                <ProductCard v-for="p in featuredProducts" :key="p.id" :product="p" @quickView="openQuickView" class="snap-start" />
            </div>

            <div class="mt-10 flex justify-center">
                <Link href="/shop" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-emerald-600 transition-colors flex items-center gap-2">
                    View all products <ArrowRight class="w-3.5 h-3.5" />
                </Link>
            </div>
        </div>
    </section>

    <!-- ─── NEW PRODUCTS ──────────────────────────── -->
    <section class="py-12 md:py-16 bg-slate-50 border-t border-slate-100">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16 relative">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                    New <span class="text-emerald-600">Products</span>
                </h2>
                <div class="flex gap-1.5">
                    <button @click="scrollContainer('new-prod-container', -400)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors shadow-sm text-slate-600">
                        <ChevronLeft class="w-4 h-4" />
                    </button>
                    <button @click="scrollContainer('new-prod-container', 400)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors shadow-sm text-slate-600">
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Single Row 5-Product Grid -->
            <div id="new-prod-container" class="grid grid-flow-col auto-cols-[calc((100%-1rem)/2)] sm:auto-cols-[calc((100%-2rem)/3)] lg:grid-cols-5 lg:auto-cols-auto gap-4 md:gap-5 overflow-x-auto snap-x scroll-smooth no-scrollbar pb-3">
                <ProductCard v-for="p in latestProducts.slice(0, 10)" :key="p.id" :product="p" @quickView="openQuickView" class="snap-start" />
            </div>
        </div>
    </section>

    <!-- ─── BUNDLES ────────────────────────────── -->
    <section v-if="combos?.length" class="py-12 md:py-16 bg-white border-t border-slate-100">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16">
            <div class="mb-8">
                <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                    Build <span class="text-emerald-600">Bundles</span>
                </h2>
                <p class="text-slate-500 text-xs mt-1">Pre-configured combos, ready to order.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
                <ComboCard v-for="combo in combos" :key="combo.id" :combo="combo" />
            </div>
        </div>
    </section>

    <!-- ─── BRANDS ──────────────────────────────── -->
    <section v-if="brands?.length" class="py-12 bg-slate-50 border-t border-slate-100">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 xl:px-16 relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Brands we carry</h3>
                <div class="flex gap-1.5">
                    <button @click="scrollContainer('brand-container', -200)" class="p-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-600">
                        <ChevronLeft class="w-3.5 h-3.5" />
                    </button>
                    <button @click="scrollContainer('brand-container', 200)" class="p-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-600">
                        <ChevronRight class="w-3.5 h-3.5" />
                    </button>
                </div>
            </div>
            
            <div id="brand-container" class="flex items-center gap-10 overflow-x-auto pb-2 no-scrollbar snap-x scroll-smooth">
                <Link
                    v-for="brand in brands"
                    :key="brand.id"
                    :href="`/shop?brand=${brand.slug}`"
                    class="shrink-0 h-9 md:h-11 flex items-center opacity-60 hover:opacity-100 transition-opacity snap-start"
                >
                    <img v-if="brand.image" :src="brand.image" :alt="brand.name" class="h-full w-auto object-contain" />
                    <span v-else class="text-base font-bold text-slate-700 whitespace-nowrap">{{ brand.name }}</span>
                </Link>
            </div>
        </div>
    </section>

    <ImageLightbox
        :show="!!selectedProduct"
        :product="selectedProduct"
        @close="selectedProduct = null"
    />
</StoreLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { ArrowRight, Truck, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import ProductCard from '@/Components/ProductCard.vue';
import ComboCard from '@/Components/ComboCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';

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

const featuredProducts = computed(() =>
    (props.topSelling.length ? props.topSelling : props.latestProducts).slice(0, 10)
);

// ─── Inline Scroller Helper ───────────────────────
const scrollContainer = (id, offset) => {
    const el = document.getElementById(id);
    if (el) el.scrollLeft += offset;
};

// ─── Slider ─────────────────────────────────────
const sliderImages = computed(() => {
    try {
        const raw = page.props.settings?.slider_images;
        if (!raw) {
            return [
                'https://placehold.co/1200x800/0a1f17/ffffff?text=Original+Printer+%26+Ink',
                'https://placehold.co/1200x800/0a1f17/ffffff?text=IT+Solutions',
            ];
        }
        const parsed = typeof raw === 'string' ? JSON.parse(raw) : raw;
        return Array.isArray(parsed) && parsed.length ? parsed : [];
    } catch {
        return [];
    }
});

const currentSlide = ref(0);
let autoSlide = null;
let isPaused = false;

const goToSlide = (i) => {
    currentSlide.value = ((i % sliderImages.value.length) + sliderImages.value.length) % sliderImages.value.length;
};
const nextSlide = () => goToSlide(currentSlide.value + 1);
const pauseSlider = () => { isPaused = true; };
const resumeSlider = () => { isPaused = false; };

// ─── Quick view ──────────────────────────────────────────────
const selectedProduct = ref(null);
const openQuickView = (product) => {
    selectedProduct.value = product;
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
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>