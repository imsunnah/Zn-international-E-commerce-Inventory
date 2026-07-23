<template>
    <StoreLayout>

        <!-- ══════════════════════════════════════════
             HERO BANNER (single full-width banner, optional auto-rotate)
        ═══════════════════════════════════════════ -->
        <section class="bg-white py-1 md:py-3 border-b border-slate-100">
    <div class="w-full mx-auto px-4 md:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-1.5">
            <!-- Left: Slider (75% or 3 cols out of 4) -->
            <div
                class="relative lg:col-span-3 w-full h-[160px] sm:h-[230px] md:h-[300px] lg:h-[340px] overflow-hidden rounded-xl bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950 border border-emerald-900 group shadow-sm"
                @mouseenter="pauseSlider"
                @mouseleave="resumeSlider"
            >
                <div
                    v-if="!bannerImages.length"
                    class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-emerald-700/20 pointer-events-none"
                />

                <div
                    class="absolute inset-0 flex transition-transform duration-700 ease-out"
                    :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
                >
                    <Link
                        v-for="(banner, i) in bannerImages"
                        :key="i"
                        :href="banner.href || '/shop'"
                        class="min-w-full h-full relative block"
                    >
                        <img
                            :src="banner.image"
                            class="absolute inset-0 w-full h-full object-cover"
                            :alt="banner.alt || `Banner ${i + 1}`"
                        />
                    </Link>

                    <!-- Fallback banner when no image is configured -->
                    <div
                        v-if="!bannerImages.length"
                        class="min-w-full h-full flex items-center px-6 md:px-14 gap-8 relative z-10"
                    >
                        <div class="flex-1">
                            <span
                                class="inline-block bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-3"
                                >{{ storeSettings.hero_eyebrow || 'New Arrivals' }}</span
                            >
                            <h1 class="text-2xl md:text-4xl font-black text-white leading-tight mb-2 tracking-tight">
                                {{ storeSettings.hero_title || 'Genuine Printers & Scanners' }}
                            </h1>
                            <p class="text-emerald-200/80 text-xs md:text-sm mb-4 font-medium max-w-[320px] leading-relaxed">
                                {{ storeSettings.hero_subtitle || '100% original products, official warranty, nationwide delivery.' }}
                            </p>
                            <Link
                                href="/shop"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white rounded-lg text-sm font-bold hover:bg-emerald-400 shadow-md shadow-emerald-950/30 transition-all active:scale-95"
                            >
                                Shop now <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Arrows -->
                <button
                    v-if="bannerImages.length > 1"
                    @click="goToSlide(currentSlide - 1)"
                    class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-all border-none cursor-pointer backdrop-blur-sm"
                >
                    <ChevronLeft class="w-4 h-4" />
                </button>
                <button
                    v-if="bannerImages.length > 1"
                    @click="goToSlide(currentSlide + 1)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-all border-none cursor-pointer backdrop-blur-sm"
                >
                    <ChevronRight class="w-4 h-4" />
                </button>

                <div
                    v-if="bannerImages.length > 1"
                    class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10"
                >
                    <button
                        v-for="(_, i) in bannerImages"
                        :key="i"
                        @click="goToSlide(i)"
                        :class="currentSlide === i ? 'w-5 bg-emerald-400' : 'w-1.5 bg-white/30 hover:bg-white/50'"
                        class="h-1.5 rounded-full transition-all duration-300 border-none cursor-pointer"
                    />
                </div>
            </div>

            <!-- Right: Static Promo (25% or 1 col out of 4) -->
            <div class="relative lg:col-span-1 w-full h-[160px] sm:h-[230px] md:h-[300px] lg:h-[340px] overflow-hidden rounded-xl bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-100 shadow-sm group">
                <Link :href="storeSettings.hero_promo_1_url || '/shop'" class="w-full h-full block relative decoration-none border-none">
                    <img
                        :src="storeSettings.hero_promo_1_image || 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=600'"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 opacity-80"
                        alt="Promo Banner"
                    />
                    <!-- Elegant overlay text directly positioned -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent p-5 flex flex-col justify-end">
                        <span class="bg-[#00a651] text-white text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full w-max mb-2">
                            Special Offer
                        </span>
                        <h3 class="text-sm font-black text-white leading-tight truncate">
                            {{ storeSettings.hero_promo_1_title || 'Smart Printing Hub' }}
                        </h3>
                        <p class="text-[10px] text-slate-300 mt-1 font-bold leading-normal truncate">
                            {{ storeSettings.hero_promo_1_subtitle || 'Get premium laser printers' }}
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</section>

        <!-- ══════════════════════════════════════════
             SCROLLING NOTICE
        ═══════════════════════════════════════════ -->
        <div v-if="noticeItems.length" class="bg-white py-3 overflow-hidden border-b border-slate-100">
            <div class="w-full mx-auto px-4 md:px-6">
                <div class="flex items-center">
                    <div class="relative flex-1 overflow-hidden">
                        <div class="marquee-track">
                            <div class="marquee-content">
                                <template v-for="(n, i) in noticeItems" :key="'a-' + i">
                                    <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#00a651]">
                                        <component :is="noticeIcon(i)" class="w-3.5 h-3.5 shrink-0" />
                                        {{ n }}
                                    </span>
                                    <span class="text-[#ef4823] font-bold">·</span>
                                </template>
                            </div>
                            <div class="marquee-content" aria-hidden="true">
                                <template v-for="(n, i) in noticeItems" :key="'b-' + i">
                                    <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#00a651]">
                                        <component :is="noticeIcon(i)" class="w-3.5 h-3.5 shrink-0" />
                                        {{ n }}
                                    </span>
                                    <span class="text-[#ef4823] font-bold">·</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- ══════════════════════════════════════════
     FEATURED CATEGORIES (Full Screen Width, No Border, No Shadow, Bigger Cards)
═══════════════════════════════════════════ -->
<section v-if="displayedCategories.length" class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] py-8 bg-slate-100/90 border-y border-slate-200/50 my-6 overflow-hidden">
    <div class="w-full max-w-full mx-auto px-4 md:px-8">
        <!-- Centered Header -->
        <div class="text-center mb-6">
            <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight">
                Featured <span class="text-[#00a651]">Categories</span>
            </h2>
            <span class="block w-10 h-0.5 bg-[#00a651] rounded-full mx-auto mt-2"></span>
        </div>

        <!-- Square Items Grid -->
        <div class="flex flex-wrap justify-center gap-4 sm:gap-6">
            <Link
                v-for="cat in displayedCategories"
                :key="cat.id"
                :href="`/shop?category=${cat.slug}`"
                class="group flex flex-col items-center justify-between decoration-transparent w-[140px] h-[140px] sm:w-[170px] sm:h-[170px] md:w-[185px] md:h-[185px] bg-white hover:bg-[#eaf7f0] rounded-xl shadow-[0_0_15px_rgba(0,0,0,0.08)] hover:shadow-[0_0_25px_rgba(0,0,0,0.18)] active:scale-[0.97] transition-all duration-300 p-3 sm:p-4 hover:-translate-y-1 cursor-pointer"
            >
                <!-- Perfectly Centered Image Container -->
                <div class="flex-1 flex items-center justify-center w-full min-h-0">
                    <img
                        v-if="cat.image"
                        :src="cat.image"
                        :alt="cat.name"
                        class="max-w-[80%] max-h-[80%] object-contain transition-transform duration-300 group-hover:scale-105"
                    />
                    <component
                        v-else
                        :is="categoryIcon(cat)"
                        class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 text-[#00a651] group-hover:scale-105 transition-transform"
                        stroke-width="1.5"
                    />
                </div>

                <!-- Text Centered at Bottom -->
                <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-[#00a651] transition-colors text-center leading-tight uppercase tracking-wider block w-full truncate mt-1">
                    {{ cat.name }}
                </span>
            </Link>
        </div>
    </div>
</section>
        <!-- ══════════════════════════════════════════
             NEW PRODUCTS (5 in a row on LG screens)
        ═══════════════════════════════════════════ -->
        <section v-if="latestProducts.length" class="py-8 bg-white border-b border-slate-100">
            <div class="w-full mx-auto px-4 md:px-6">
                <div class="text-center mb-6">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">
                        New <span class="text-[#00a651]">Products</span>
                    </h2>
                    <p class="text-slate-400 text-[11px] mt-1 font-medium">
                        Latest arrivals fresh in stock
                    </p>
                    <span class="block w-10 h-[3px] bg-[#00a651] rounded-full mx-auto mt-2"></span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4 p-1">
                    <ProductCard
                        v-for="p in latestProducts"
                        :key="p.id"
                        :product="p"
                        @quickView="openQuickView"
                    />
                </div>

                <div class="text-center mt-7">
                    <Link
                        href="/shop"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border-2 border-[#00a651] text-[#00a651] text-xs font-bold uppercase tracking-wider hover:bg-[#00a651] hover:text-white transition-all decoration-none"
                    >
                        View All Products <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             PRINTERS PRODUCTS (5 in a row on LG screens)
        ═══════════════════════════════════════════ -->
        <section v-if="printersProducts && printersProducts.length" class="py-8 border-b border-slate-100">
            <div class="w-full mx-auto px-4 md:px-6">
                <div class="text-center mb-6">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">
                        Enterprise <span class="text-[#00a651]">Printer</span>
                    </h2>
                    <p class="text-slate-400 text-[11px] mt-1 font-medium">
                        High performance and dynamic printing hardware
                    </p>
                    <span class="block w-10 h-[3px] bg-[#00a651] rounded-full mx-auto mt-2"></span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4 p-1">
                    <ProductCard
                        v-for="p in printersProducts"
                        :key="p.id"
                        :product="p"
                        @quickView="openQuickView"
                    />
                </div>

                <div class="text-center mt-7">
                    <Link
                        href="/shop?category=printer"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border-2 border-[#00a651] text-[#00a651] text-xs font-bold uppercase tracking-wider hover:bg-[#00a651] hover:text-white transition-all decoration-none"
                    >
                        View All Printers <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SCANNERS PRODUCTS (5 in a row on LG screens)
        ═══════════════════════════════════════════ -->
        <section v-if="scannersProducts && scannersProducts.length" class="py-8 bg-white border-b border-slate-100">
            <div class="w-full mx-auto px-4 md:px-6">
                <div class="text-center mb-6">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">
                        Enterprise <span class="text-[#00a651]">Scanner</span>
                    </h2>
                    <p class="text-slate-400 text-[11px] mt-1 font-medium">
                        Premium scanners for official and smart digitization
                    </p>
                    <span class="block w-10 h-[3px] bg-[#00a651] rounded-full mx-auto mt-2"></span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4 p-1">
                    <ProductCard
                        v-for="p in scannersProducts"
                        :key="p.id"
                        :product="p"
                        @quickView="openQuickView"
                    />
                </div>

                <div class="text-center mt-7">
                    <Link
                        href="/shop?category=scanner"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border-2 border-[#00a651] text-[#00a651] text-xs font-bold uppercase tracking-wider hover:bg-[#00a651] hover:text-white transition-all decoration-none"
                    >
                        View All Scanners <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             REVIEWS / VIDEO GALLERY LINK
        ═══════════════════════════════════════════ -->
        <div v-if="reviews?.length" class="w-full mx-auto px-4 md:px-6 pt-8">
            <Link
                href="/reviews"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-[#00a651] transition-colors font-sans"
            >
                <Youtube class="w-5 h-5 text-[#00a651]" />
                {{ storeSettings.reviews_heading || 'Product Reviews, Unboxing & Video Gallery' }}
            </Link>
        </div>

        <!-- ══════════════════════════════════════════
             SEO / TRUST CONTENT — accordion
        ═══════════════════════════════════════════ -->
        <section class="py-10 bg-white border-t border-slate-100">
            <div class="w-full mx-auto px-4 md:px-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">
                    {{ storeSettings.site_name || 'Our Store' }} | {{ storeSettings.seo_tagline || 'Printer, Scanner & IT Solutions' }}
                </h2>

                <div class="divide-y divide-slate-200 border-t border-b border-slate-200 bg-white rounded-lg overflow-hidden">
                    <div v-for="(item, i) in accordionItems" :key="i">
                        <button
                            @click="toggleAccordion(i)"
                            class="w-full flex items-center justify-between gap-3 px-4 py-3 text-left cursor-pointer"
                        >
                            <span class="text-sm font-bold text-slate-700">{{ item.heading }}</span>
                            <ChevronDown
                                class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200"
                                :class="{ 'rotate-180 text-[#00a651]': openAccordions.has(i) }"
                            />
                        </button>
                        <div v-show="openAccordions.has(i)" class="px-4 pb-4">
                            <p class="text-[13px] text-slate-500 leading-relaxed">{{ item.body }}</p>
                        </div>
                    </div>
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
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    Printer,
    ScanLine,
    Monitor,
    Cpu,
    Package,
    Layers,
    Truck,
    Phone,
    CalendarCheck,
    Youtube,
} from "lucide-vue-next";
import ProductCard from "@/Components/ProductCard.vue";
import ComboCard from "@/Components/ComboCard.vue";
import ImageLightbox from "@/Components/ImageLightbox.vue";

const props = defineProps({
    latestProducts: { type: Array, default: () => [] },
    printersProducts: { type: Array, default: () => [] },
    scannersProducts: { type: Array, default: () => [] },
    featuredCategories: { type: Array, default: () => [] },
    notice: { type: Array, default: () => [] },
});

const page = usePage();
const storeSettings = computed(() => page.props.settings || {});

/* ---------- Categories ---------- */
const displayedCategories = computed(() => (props.featuredCategories || []).slice(0, 6));

const CATEGORY_ICON_MAP = {
    printer: Printer,
    scanner: ScanLine,
    "aio-pc": Cpu,
    "aio_pc": Cpu,
    monitor: Monitor,
    toner: Package,
    cartridge: Layers,
    cartridges: Layers,
};
const categoryIcon = (cat) => {
    const key = (cat.slug || cat.name || "").toLowerCase().replace(/\s+/g, "-");
    return CATEGORY_ICON_MAP[key] || Package;
};

/* ---------- Notice ticker ---------- */
const noticeItems = computed(() =>
    (props.notice || []).map((n) => n.title ?? n.message ?? n.description ?? "").filter(Boolean),
);
const NOTICE_ICONS = [Printer, Truck, Phone, CalendarCheck];
const noticeIcon = (i) => NOTICE_ICONS[i % NOTICE_ICONS.length];

/* ---------- Quick view ---------- */
const selectedProduct = ref(null);
const openQuickView = (p) => {
    selectedProduct.value = p;
};

/* ---------- Brand rail scroll ---------- */
const scrollContainer = (id, offset) => {
    const el = document.getElementById(id);
    if (el) el.scrollLeft += offset;
};

/* ---------- Hero banner ---------- */
const bannerImages = computed(() => {
    try {
        const raw = storeSettings.value.slider_images;
        if (!raw) return [];
        const parsed = typeof raw === "string" ? JSON.parse(raw) : raw;
        if (!Array.isArray(parsed)) return [];
        // Normalize: allow either plain string URLs or {image, href, alt} objects
        return parsed
            .map((item) => (typeof item === "string" ? { image: item } : item))
            .filter((item) => item?.image);
    } catch {
        return [];
    }
});

const currentSlide = ref(0);
let autoSlide = null;
let isPaused = false;

const goToSlide = (i) => {
    if (!bannerImages.value.length) return;
    currentSlide.value =
        ((i % bannerImages.value.length) + bannerImages.value.length) % bannerImages.value.length;
};
const nextSlide = () => goToSlide(currentSlide.value + 1);
const pauseSlider = () => (isPaused = true);
const resumeSlider = () => (isPaused = false);

onMounted(() => {
    if (bannerImages.value.length > 1) {
        autoSlide = setInterval(() => {
            if (!isPaused) nextSlide();
        }, 6000);
    }
});
onUnmounted(() => {
    if (autoSlide) clearInterval(autoSlide);
});

/* ---------- SEO / trust accordion ---------- */
const DEFAULT_ACCORDION_ITEMS = [
    {
        heading: "Why Choose Us?",
        body: "We've been offering IT products and computer services for more than two decades. We guarantee 100% original products, official brand warranty services, and competitive pricing across scanners, printers, and accessories.",
    },
    {
        heading: "Trusted Printer Shop",
        body: "Find top-tier printers from leading brands such as HP, Epson, Canon, and Brother for office or home needs with original cartridge support.",
    },
    {
        heading: "Best Scanner Shop",
        body: "A wide selection of document, flatbed, and enterprise scanners for every business need, backed by official warranty.",
    },
    {
        heading: "Genuine Toner & Cartridge Shop",
        body: "Only 100% original toners and cartridges — sharp print quality, higher page yield, and a genuine product guarantee on every order.",
    },
    {
        heading: "Best Office Equipment Shop",
        body: "Laptops, monitors, and computer accessories curated for reliable everyday office use.",
    },
    {
        heading: "Best E-Commerce Shop",
        body: "Order your desired product online with a smooth checkout experience and nationwide delivery.",
    },
    {
        heading: "Best Price & After-Sales Service",
        body: "Competitive pricing paired with dedicated personal support and warranty service after your purchase.",
    },
];

const accordionItems = computed(() =>
    storeSettings.value.seo_content?.length ? storeSettings.value.seo_content : DEFAULT_ACCORDION_ITEMS,
);

const openAccordions = ref(new Set([0]));
const toggleAccordion = (i) => {
    const next = new Set(openAccordions.value);
    if (next.has(i)) next.delete(i);
    else next.add(i);
    openAccordions.value = next;
};
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Scrolling notice ticker */
.marquee-track {
    display: flex;
    width: max-content;
    animation: marquee 22s linear infinite;
}
.marquee-track:hover {
    animation-play-state: paused;
}
.marquee-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding-right: 1.5rem;
    white-space: nowrap;
}
@keyframes marquee {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-50%);
    }
}
</style>