<template>
    <StoreLayout>
        <!-- ══════════════════════════════════════════
             ANNOUNCEMENT BAR
        ═══════════════════════════════════════════ -->
        <div class="bg-emerald-900 py-2 px-4 text-center">
            <p
                class="text-[11px] font-semibold text-emerald-50 tracking-wide flex items-center justify-center gap-3"
            >
                <Truck class="w-3.5 h-3.5 shrink-0" />
                Free shipping over ৳5,000 &nbsp;·&nbsp; Nationwide delivery
                &nbsp;·&nbsp; 24/7 Expert support &nbsp;·&nbsp; 7-day easy
                returns
            </p>
        </div>

        <!-- ══════════════════════════════════════════
             SCROLLING NOTICE (runs right before the hero)
        ═══════════════════════════════════════════ -->
        <div
            v-if="noticeItems.length"
            class="bg-white border-b border-slate-200/60 py-2 overflow-hidden"
        >
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="flex items-center">
                    <div
                        class="flex items-center gap-1.5 shrink-0 px-4 border-r border-slate-200 mr-4"
                    >
                        <Megaphone class="w-3.5 h-3.5 text-rose-500" />
                        <span
                            class="text-[10px] font-black uppercase tracking-widest text-slate-800"
                            >Notice</span
                        >
                    </div>
                    <div class="relative flex-1 overflow-hidden">
                        <div class="marquee-track">
                            <div class="marquee-content">
                                <template
                                    v-for="(n, i) in noticeItems"
                                    :key="'a-' + i"
                                >
                                    <span
                                        class="text-[12px] font-medium text-slate-700"
                                        >{{ n }}</span
                                    >
                                    <span class="text-rose-500 font-bold"
                                        >·</span
                                    >
                                </template>
                            </div>
                            <div class="marquee-content" aria-hidden="true">
                                <template
                                    v-for="(n, i) in noticeItems"
                                    :key="'b-' + i"
                                >
                                    <span
                                        class="text-[12px] font-medium text-slate-700"
                                        >{{ n }}</span
                                    >
                                    <span class="text-rose-500 font-bold"
                                        >·</span
                                    >
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
             HERO BANNER  (main slider + 2 stacked promo cards)
        ═══════════════════════════════════════════ -->
        <section class="bg-slate-50 py-5 md:py-7 border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="grid lg:grid-cols-3 gap-4">
                    <!-- Main Slider -->
                    <div
                        class="lg:col-span-2 relative h-[220px] sm:h-[300px] md:h-[380px] lg:h-[420px] overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950 border border-emerald-900 group shadow-sm"
                        @mouseenter="pauseSlider"
                        @mouseleave="resumeSlider"
                    >
                        <div
                            v-if="!sliderImages.length"
                            class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-emerald-700/20 pointer-events-none"
                        />
                        <div
                            v-if="!sliderImages.length"
                            class="absolute right-24 -bottom-8 w-36 h-36 rounded-full bg-emerald-700/10 pointer-events-none"
                        />

                        <div
                            class="absolute inset-0 flex transition-transform duration-700 ease-out"
                            :style="{
                                transform: `translateX(-${currentSlide * 100}%)`,
                            }"
                        >
                            <!-- Real slider images: fill the box edge-to-edge so small
                                 source images don't look tiny/floating inside padding -->
                            <div
                                v-for="(img, i) in sliderImages"
                                :key="i"
                                class="min-w-full h-full relative"
                            >
                                <img
                                    :src="img"
                                    class="absolute inset-0 w-full h-full object-cover"
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
                                        class="inline-block bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-4"
                                        >New Arrivals</span
                                    >
                                    <h1
                                        class="text-3xl md:text-[2.6rem] font-black text-white leading-tight mb-3 tracking-tight"
                                    >
                                        Premium PC<br />Components
                                    </h1>
                                    <p
                                        class="text-emerald-200/80 text-sm mb-6 font-medium max-w-[260px] leading-relaxed"
                                    >
                                        Handpicked hardware by our technology
                                        experts — built to perform.
                                    </p>
                                    <div class="flex gap-3">
                                        <Link
                                            href="/shop"
                                            class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold hover:bg-emerald-400 shadow-md shadow-emerald-900/30 transition-all active:scale-95 flex items-center gap-2"
                                        >
                                            Shop now
                                            <ArrowRight class="w-4 h-4" />
                                        </Link>
                                        <Link
                                            href="/shop?tag=deals"
                                            class="px-5 py-2.5 bg-white/10 text-white border border-white/20 rounded-xl text-sm font-bold hover:bg-white/20 transition-all active:scale-95"
                                        >
                                            View deals
                                        </Link>
                                    </div>
                                </div>
                                <div
                                    class="hidden md:flex items-center justify-center w-44 h-44 rounded-2xl bg-white/5 border border-white/10 shrink-0"
                                >
                                    <svg
                                        class="w-24 h-24 text-emerald-400/70"
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
                                        ? 'w-5 bg-emerald-400'
                                        : 'w-1.5 bg-white/30 hover:bg-white/50'
                                "
                                class="h-1.5 rounded-full transition-all duration-300"
                            />
                        </div>
                        <div
                            v-if="sliderImages.length > 1"
                            class="absolute bottom-4 right-5 text-[10px] font-bold text-white/50 z-10"
                        >
                            {{ currentSlide + 1 }} / {{ sliderImages.length }}
                        </div>
                    </div>

                    <!-- Right column: 2 stacked promo cards -->
                    <div
                        class="flex flex-col gap-4 h-[220px] sm:h-[300px] md:h-[380px] lg:h-[420px]"
                    >
                        <Link
                            v-for="promo in heroPromos"
                            :key="promo.title"
                            :href="promo.href"
                            class="relative flex-1 rounded-2xl overflow-hidden border border-slate-200/60 bg-slate-100 flex items-center justify-between group hover:shadow-lg transition-all duration-300"
                        >
                            <!-- If image exists from settings, show the banner image -->
                            <img
                                v-if="promo.image"
                                :src="promo.image"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                                :alt="promo.title"
                            />
                            <!-- Else fallback to standard gradient block -->
                            <template v-else>
                                <div class="p-5 relative z-10">
                                    <span
                                        class="text-emerald-300 text-[9px] font-black uppercase tracking-widest mb-1.5 block"
                                        >{{ promo.eyebrow }}</span
                                    >
                                    <h3
                                        class="text-base md:text-xl font-black text-white leading-tight mb-1"
                                    >
                                        {{ promo.title }}
                                    </h3>
                                    <p
                                        class="text-emerald-200/70 text-[11px] font-medium leading-relaxed max-w-[160px] hidden sm:block"
                                    >
                                        {{ promo.subtitle }}
                                    </p>
                                </div>
                                <component
                                    :is="promo.icon"
                                    class="w-9 h-9 md:w-12 md:h-12 text-emerald-400/50 shrink-0 relative z-10 mr-5 group-hover:scale-110 transition-transform duration-300"
                                />
                                <div
                                    class="absolute -right-8 -bottom-8 w-32 h-32 rounded-full bg-emerald-500/10 pointer-events-none"
                                />
                                <div
                                    :class="promo.bg"
                                    class="absolute inset-0 -z-10"
                                />
                            </template>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             QUICK ACTIONS
        ═══════════════════════════════════════════ -->
        <section class="py-8 bg-slate-50 border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link
                        v-for="action in quickActions"
                        :key="action.label"
                        :href="action.href"
                        class="flex items-center gap-3 bg-white border border-slate-100 rounded-2xl px-5 py-4 shadow-sm hover:border-emerald-300 hover:shadow-md transition-all duration-200 group"
                    >
                        <div
                            class="w-11 h-11 rounded-full bg-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 transition-colors"
                        >
                            <component
                                :is="action.icon"
                                class="w-5 h-5 text-white"
                            />
                        </div>
                        <div class="min-w-0">
                            <p
                                class="text-sm font-extrabold text-slate-800 leading-tight truncate"
                            >
                                {{ action.label }}
                            </p>
                            <p
                                class="text-[11px] text-slate-400 mt-0.5 truncate"
                            >
                                {{ action.sub }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             FEATURED CATEGORY  (grid, not carousel)
        ═══════════════════════════════════════════ -->
        <section class="py-12 bg-white border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="text-center mb-8">
                    <h2
                        class="text-2xl font-black text-slate-950 tracking-tight"
                    >
                        Featured <span class="text-emerald-700">Category</span>
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 font-medium">
                        Get your desired product from featured category
                    </p>
                </div>

                <div
                    class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4"
                >
                    <Link
                        v-for="cat in featuredCategories"
                        :key="cat.id"
                        :href="`/shop?category=${cat.slug}`"
                        class="group flex aspect-square flex-col items-center justify-between rounded-2xl bg-white border border-slate-100 p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-900/5"
                    >
                        <div
                            class="flex flex-1 items-center justify-center w-full overflow-hidden"
                        >
                            <img
                                v-if="cat.image"
                                :src="cat.image"
                                :alt="cat.name"
                                class="h-full w-full max-h-16 max-w-16 object-contain transition-transform duration-300 group-hover:scale-110"
                            />
                            <div
                                v-else
                                class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 transition-colors"
                            >
                                <span
                                    class="text-lg font-bold uppercase text-emerald-600"
                                    >{{ cat.name.charAt(0) }}</span
                                >
                            </div>
                        </div>
                        <span
                            class="block text-center truncate w-full text-[11px] font-bold text-slate-600 group-hover:text-emerald-700 transition-colors pt-1"
                        >
                            {{ cat.name }}
                        </span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             STORE LOCATOR BANNER
        ═══════════════════════════════════════════ -->
        <section class="py-6 bg-slate-50">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <Link
                    href="/stores"
                    class="relative flex flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl px-6 md:px-10 py-6 overflow-hidden bg-gradient-to-r from-emerald-950 via-emerald-800 to-emerald-700 group"
                >
                    <div class="flex items-center gap-3 relative z-10">
                        <div
                            class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center shrink-0"
                        >
                            <MapPin class="w-5 h-5 text-emerald-300" />
                        </div>
                        <div>
                            <p
                                class="text-white font-black text-lg leading-tight"
                            >
                                20+ Physical Stores
                            </p>
                            <p class="text-emerald-200/70 text-xs font-medium">
                                Visit our store &amp; get your desired IT
                                product!
                            </p>
                        </div>
                    </div>
                    <span
                        class="relative z-10 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-400 text-emerald-950 rounded-xl text-sm font-black group-hover:bg-emerald-300 transition-all shrink-0"
                    >
                        Find Our Store
                        <Search class="w-4 h-4" />
                    </span>
                    <div
                        class="absolute -right-6 -top-10 w-40 h-40 rounded-full bg-white/5 pointer-events-none"
                    />
                </Link>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             PRODUCT TABS / FEATURED PRODUCTS
        ═══════════════════════════════════════════ -->
        <section class="py-14 bg-white border-b border-slate-100">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8"
                >
                    <div class="text-center sm:text-left w-full sm:w-auto">
                        <h2
                            class="text-2xl font-black text-slate-950 tracking-tight"
                        >
                            Featured
                            <span class="text-emerald-700">Products</span>
                        </h2>
                        <p class="text-slate-400 text-xs mt-1 font-medium">
                            Check &amp; get your desired product!
                        </p>
                    </div>
                    <div
                        class="flex gap-1 bg-slate-50 border border-slate-200 rounded-xl p-1 w-fit mx-auto sm:mx-0 shadow-sm"
                    >
                        <button
                            v-for="tab in productTabs"
                            :key="tab.key"
                            @click="activeTab = tab.key"
                            :class="
                                activeTab === tab.key
                                    ? 'bg-emerald-700 text-white shadow-sm'
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
                        class="px-7 py-3 bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-emerald-800 transition-all flex items-center gap-2 shadow-sm shadow-emerald-900/20"
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
            class="py-14 bg-slate-50 border-b border-slate-100"
        >
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div
                    class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-8"
                >
                    <div>
                        <h2
                            class="text-2xl font-black text-slate-950 tracking-tight"
                        >
                            Combo <span class="text-emerald-700">Deals</span>
                        </h2>
                        <p class="text-slate-400 text-xs mt-1 font-medium">
                            Pre-configured bundles by our tech experts
                        </p>
                    </div>
                    <Link
                        href="/shop?type=combo"
                        class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 transition-colors shrink-0"
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
             TOP BRANDS
        ═══════════════════════════════════════════ -->
        <section
            v-if="brands?.length"
            class="py-12 bg-white border-b border-slate-100"
        >
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="flex items-center justify-between mb-7">
                    <div>
                        <h2
                            class="text-2xl font-black text-slate-950 tracking-tight"
                        >
                            Top <span class="text-emerald-700">Brands</span>
                        </h2>
                        <p class="text-slate-400 text-xs mt-1 font-medium">
                            Official authorized reseller — genuine products
                            guaranteed
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="scrollContainer('brand-container', -480)"
                            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 active:scale-95 transition-all text-slate-500 shadow-sm"
                        >
                            <ChevronLeft class="w-4 h-4" />
                        </button>
                        <button
                            @click="scrollContainer('brand-container', 480)"
                            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 active:scale-95 transition-all text-slate-500 shadow-sm"
                        >
                            <ChevronRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div
                    id="brand-container"
                    class="flex overflow-x-auto scroll-smooth no-scrollbar gap-4 pb-2"
                >
                    <template v-for="brand in brands" :key="brand.id">
                        <Link
                            v-if="brand.image"
                            :href="`/shop?brand=${brand.slug}`"
                            class="group flex-shrink-0 w-[140px] md:w-[160px] h-[90px] md:h-[100px] flex items-center justify-center p-4 rounded-2xl border border-slate-100 bg-white hover:border-emerald-300 hover:bg-emerald-50/40 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-250 overflow-hidden"
                        >
                            <img
                                :src="brand.image"
                                :alt="brand.name"
                                class="max-w-full max-h-full w-auto h-auto object-contain transition-all duration-300 group-hover:scale-105"
                            />
                        </Link>
                    </template>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SEO CONTENT
        ═══════════════════════════════════════════ -->
        <section v-if="seoContent?.length" class="py-14 bg-slate-50">
            <div class="max-w-[1400px] mx-auto px-4 md:px-8 xl:px-12 space-y-6">
                <h2 class="text-xl font-black text-slate-950 mb-4">
                    Leading Computer, Laptop &amp; Gaming PC Retail &amp; Online
                    Shop in Bangladesh
                </h2>
                <div
                    v-for="block in seoContent"
                    :key="block.heading"
                    class="space-y-2"
                >
                    <h3 class="text-sm font-extrabold text-emerald-800">
                        {{ block.heading }}
                    </h3>
                    <p class="text-[13px] text-slate-500 leading-relaxed">
                        {{ block.body }}
                    </p>
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
    MapPin,
    Search,
    MessageSquare,
    Home,
    Wrench,
    Smartphone,
    Wind,
    Megaphone,
} from "lucide-vue-next";
import ProductCard from "@/Components/ProductCard.vue";
import ComboCard from "@/Components/ComboCard.vue";
import ImageLightbox from "@/Components/ImageLightbox.vue";

const props = defineProps({
    topSelling: { type: Array, default: () => [] },
    latestProducts: { type: Array, default: () => [] },
    discountedProducts: { type: Array, default: () => [] },
    combos: { type: Array, default: () => [] },
    featuredCategories: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    categorySections: { type: Array, default: () => [] },
    reviews: { type: Array, default: () => [] },
    notice: { type: Array, default: () => [] },
});

const page = usePage();
const featuredCategories = computed(() => props.featuredCategories || []);

// Scrolling notice ticker — built from the `notice` prop
// (Notice model). Falls back to nothing if the list is empty.
const noticeItems = computed(() =>
    (props.notice || [])
        .map((n) => n.title ?? n.message ?? n.description ?? "")
        .filter(Boolean),
);

// Hero right-column promo cards (kept static/link-driven, same pattern as
// the old "Custom PC Builder" box — swap hrefs/copy as needed)
const heroPromos = computed(() => [
    {
        title: page.props.settings?.hero_promo_1_title || "Sell Your Device",
        eyebrow: "Trade-in",
        subtitle:
            page.props.settings?.hero_promo_1_subtitle ||
            "Get the best value for your old gadgets, instantly.",
        href: page.props.settings?.hero_promo_1_url || "/sell",
        image: page.props.settings?.hero_promo_1_image || null,
        icon: Smartphone,
        bg: "bg-gradient-to-br from-emerald-800 to-emerald-950",
    },
    {
        title: page.props.settings?.hero_promo_2_title || "Custom PC Builder",
        eyebrow: "Build your own",
        subtitle:
            page.props.settings?.hero_promo_2_subtitle ||
            "Choose CPU, GPU, RAM, storage — step by step.",
        href: page.props.settings?.hero_promo_2_url || "/shop?type=combo",
        image: page.props.settings?.hero_promo_2_image || null,
        icon: Wind,
        bg: "bg-gradient-to-br from-slate-900 to-emerald-950",
    },
]);

const quickActions = [
    {
        label: "Laptop Finder",
        sub: "Find your laptop easily",
        icon: Search,
        href: "/laptop-finder",
    },
    {
        label: "Raise a Complain",
        sub: "Share your experience",
        icon: MessageSquare,
        href: "/complain",
    },
    {
        label: "Home Service",
        sub: "Get expert help",
        icon: Home,
        href: "/home-service",
    },
    {
        label: "Servicing Center",
        sub: "Repair your device",
        icon: Wrench,
        href: "/service-center",
    },
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

// Optional SEO content blocks — pass via settings.seo_content (array of
// { heading, body }) if you want to manage this copy from the backend.
const seoContent = computed(() => page.props.settings?.seo_content || []);

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
