<template>
    <StoreLayout>

        <section class="bg-slate-50 py-5 md:py-7 border-b border-slate-100">
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="grid lg:grid-cols-3 gap-2">
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
                                        <rect x="8" y="24" width="80" height="52" rx="6" />
                                        <path d="M28 76v8M68 76v8M20 84h56" />
                                        <rect x="20" y="34" width="56" height="32" rx="3" />
                                        <circle cx="48" cy="50" r="8" />
                                        <path d="M48 42v4M48 54v4M40 50h4M52 50h4" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Slider arrows -->
                        <button
                            v-if="sliderImages.length > 1"
                            @click="goToSlide(currentSlide - 1)"
                            class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-all border-none cursor-pointer backdrop-blur-sm"
                        >
                            <ChevronLeft class="w-5 h-5" />
                        </button>
                        <button
                            v-if="sliderImages.length > 1"
                            @click="goToSlide(currentSlide + 1)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-all border-none cursor-pointer backdrop-blur-sm"
                        >
                            <ChevronRight class="w-5 h-5" />
                        </button>

                        <div
                            v-if="sliderImages.length > 1"
                            class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-10"
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
                                class="h-1.5 rounded-full transition-all duration-300 border-none cursor-pointer"
                            />
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
                            <img
                                v-if="promo.image"
                                :src="promo.image"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                                :alt="promo.title"
                            />
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
             SCROLLING NOTICE
        ═══════════════════════════════════════════ -->
        <div
            v-if="noticeItems.length"
            class="bg-slate-50 py-3 overflow-hidden border-b border-slate-100"
        >
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="flex items-center">
                    <div class="relative flex-1 overflow-hidden">
                        <div class="marquee-track">
                            <div class="marquee-content">
                                <template
                                    v-for="(n, i) in noticeItems"
                                    :key="'a-' + i"
                                >
                                    <span
                                        class="text-[12px] font-semibold text-[#00a651]"
                                        >{{ n }}</span
                                    >
                                    <span class="text-[#ef4823] font-bold"
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
                                        class="text-[12px] font-semibold text-[#00a651]"
                                        >{{ n }}</span
                                    >
                                    <span class="text-[#ef4823] font-bold"
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
             FEATURED CATEGORIES (compact icons)
        ═══════════════════════════════════════════ -->
        <section class="py-10 bg-white border-b border-slate-100">
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-black text-slate-950 tracking-tight">
                        Featured <span class="text-[#00a651] italic">Categories</span>
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 font-medium">
                        Get your desired product from featured category
                    </p>
                </div>

              <div class="flex items-center justify-center gap-6 md:gap-8 flex-wrap">
    <Link
        v-for="cat in displayedCategories"
        :key="cat.id"
        :href="`/shop?category=${cat.slug}`"
        class="group flex flex-col items-center gap-3 decoration-transparent"
    >
        <div class="w-28 h-24 md:w-36 md:h-30 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-[#00a651] group-hover:shadow-md group-hover:bg-[#e6f6ee]">
            
            <img
                v-if="cat.image"
                :src="cat.image"
                :alt="cat.name"
                class="w-20 h-20 md:w-24 md:h-22 object-contain transition-transform duration-300 group-hover:scale-110"
            />
            
            <div
                v-else
                class="flex items-center justify-center w-14 h-14 md:w-18 md:h-18 rounded-lg bg-slate-100 group-hover:bg-[#d4edda] transition-colors"
            >
                <span class="text-xl font-bold uppercase text-[#00a651]">{{ cat.name.charAt(0) }}</span>
            </div>
            
        </div>
        
        <span class="text-[13px] md:text-sm font-bold text-slate-600 group-hover:text-[#00a651] transition-colors text-center leading-tight max-w-[110px] md:max-w-[130px] truncate uppercase">
            {{ cat.name }}
        </span>
    </Link>
</div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             FEATURED PRODUCTS (first section — top selling)
        ═══════════════════════════════════════════ -->
        <section class="py-10 bg-[#00a651]/5 border-b border-slate-100">
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-950 tracking-tight">
                        Featured <span class="text-[#00a651] italic">Products</span>
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 font-medium">
                        Check &amp; get your desired product!
                    </p>
                </div>

                <div class="relative">
                    <button
                        v-if="featuredProducts.length > 5"
                        @click="scrollCarousel('featured-carousel', -1)"
                        class="absolute -left-3 md:-left-5 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651] hover:bg-[#e6f6ee] flex items-center justify-center transition-all shadow-md cursor-pointer"
                    >
                        <ChevronLeft class="w-4 h-4" />
                    </button>

                    <div
                        ref="featuredCarouselRef"
                        id="featured-carousel"
                        class="flex gap-4 overflow-x-auto scroll-smooth no-scrollbar pb-2"
                    >
                        <div
                            v-for="p in featuredProducts"
                            :key="p.id"
                            class="flex-none w-[200px] md:w-[205px]"
                        >
                            <ProductCard :product="p" @quickView="openQuickView" />
                        </div>
                    </div>

                    <button
                        v-if="featuredProducts.length > 5"
                        @click="scrollCarousel('featured-carousel', 1)"
                        class="absolute -right-3 md:-right-5 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651] hover:bg-[#e6f6ee] flex items-center justify-center transition-all shadow-md cursor-pointer"
                    >
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>

                <div class="mt-8 flex justify-center">
                    <Link
                        href="/shop"
                        class="px-7 py-2.5 bg-[#00a651] hover:bg-[#008541] text-white rounded text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm decoration-transparent"
                    >
                        View full inventory <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             CATEGORY-WISE PRODUCT SECTIONS
        ═══════════════════════════════════════════ -->
        <section
            v-for="(section, sIdx) in categorySections"
            :key="section.id"
            :class="sIdx % 2 === 0 ? 'bg-white' : 'bg-slate-50'"
            class="py-10 border-b border-slate-100"
        >
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-950 tracking-tight">
                        {{ section.name.split(' ').slice(0, -1).join(' ') || section.name }}
                        <span class="text-[#00a651] italic">{{ section.name.split(' ').pop() }}</span>
                    </h2>
                </div>

                <div class="relative">
                    <button
                        v-if="section.products.length > 5"
                        @click="scrollCarousel(`cat-carousel-${section.id}`, -1)"
                        class="absolute -left-3 md:-left-5 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651] hover:bg-[#e6f6ee] flex items-center justify-center transition-all shadow-md cursor-pointer"
                    >
                        <ChevronLeft class="w-4 h-4" />
                    </button>

                    <div
                        :id="`cat-carousel-${section.id}`"
                        class="flex gap-4 overflow-x-auto scroll-smooth no-scrollbar pb-2"
                    >
                        <div
                            v-for="p in section.products"
                            :key="p.id"
                            class="flex-none w-[200px] md:w-[205px]"
                        >
                            <ProductCard :product="p" @quickView="openQuickView" />
                        </div>
                    </div>

                    <button
                        v-if="section.products.length > 5"
                        @click="scrollCarousel(`cat-carousel-${section.id}`, 1)"
                        class="absolute -right-3 md:-right-5 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651] hover:bg-[#e6f6ee] flex items-center justify-center transition-all shadow-md cursor-pointer"
                    >
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             COMBO DEALS
        ═══════════════════════════════════════════ -->
        <section
            v-if="combos?.length"
            class="py-10 bg-slate-50 border-b border-slate-100"
        >
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-950 tracking-tight">
                        Combo <span class="text-[#00a651] italic">Deals</span>
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 font-medium">
                        Pre-configured bundles by our tech experts
                    </p>
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
                <div class="mt-6 flex justify-center">
                    <Link
                        href="/shop?type=combo"
                        class="text-xs font-bold text-[#00a651] hover:text-[#008541] flex items-center gap-1 transition-colors"
                    >
                        View all combos <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             TOP BRANDS
        ═══════════════════════════════════════════ -->
        <section
            v-if="brands?.length"
            class="py-10 bg-white border-b border-slate-100"
        >
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="flex items-center justify-between mb-7">
                    <div class="flex-1 text-center">
                        <h2 class="text-2xl font-black text-slate-950 tracking-tight">
                            Top <span class="text-[#00a651] italic">Brands</span>
                        </h2>
                        <p class="text-slate-400 text-xs mt-1 font-medium">
                            Official authorized reseller — genuine products guaranteed
                        </p>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button
                            @click="scrollContainer('brand-container', -480)"
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white hover:bg-[#e6f6ee] hover:border-[#00a651] hover:text-[#00a651] active:scale-95 transition-all text-slate-500 shadow-sm cursor-pointer"
                        >
                            <ChevronLeft class="w-4 h-4" />
                        </button>
                        <button
                            @click="scrollContainer('brand-container', 480)"
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white hover:bg-[#e6f6ee] hover:border-[#00a651] hover:text-[#00a651] active:scale-95 transition-all text-slate-500 shadow-sm cursor-pointer"
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
                            class="group flex-shrink-0 w-[130px] md:w-[150px] h-[80px] md:h-[90px] flex items-center justify-center p-4 rounded-2xl border border-slate-100 bg-white hover:border-[#00a651] hover:bg-[#e6f6ee]/40 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-250 overflow-hidden"
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
             NEW PRODUCTS
        ═══════════════════════════════════════════ -->
        <section v-if="latestProducts.length" class="py-10 bg-white border-b border-slate-100">
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-950 tracking-tight">
                        New <span class="text-[#00a651] italic">Products</span>
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 font-medium">
                        Latest arrivals fresh in stock
                    </p>
                </div>

                <div class="relative">
                    <button
                        v-if="latestProducts.length > 5"
                        @click="scrollCarousel('new-carousel', -1)"
                        class="absolute -left-3 md:-left-5 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651] hover:bg-[#e6f6ee] flex items-center justify-center transition-all shadow-md cursor-pointer"
                    >
                        <ChevronLeft class="w-4 h-4" />
                    </button>

                    <div
                        id="new-carousel"
                        class="flex gap-4 overflow-x-auto scroll-smooth no-scrollbar pb-2"
                    >
                        <div
                            v-for="p in latestProducts"
                            :key="p.id"
                            class="flex-none w-[200px] md:w-[205px]"
                        >
                            <ProductCard :product="p" @quickView="openQuickView" />
                        </div>
                    </div>

                    <button
                        v-if="latestProducts.length > 5"
                        @click="scrollCarousel('new-carousel', 1)"
                        class="absolute -right-3 md:-right-5 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651] hover:bg-[#e6f6ee] flex items-center justify-center transition-all shadow-md cursor-pointer"
                    >
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SEO / TRUST CONTENT
        ═══════════════════════════════════════════ -->
        <section class="py-10 bg-slate-50 border-t border-slate-100">
            <div class="max-w-[1200px] mx-auto px-4 md:px-8 xl:px-12 space-y-6">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-[#00a651] pl-3 leading-none uppercase">
                    ZN International — Computer, Printer, Scanner &amp; IT Solutions
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-xs font-semibold text-slate-600 leading-relaxed pt-2">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-slate-800 font-extrabold text-sm mb-1.5 uppercase">Why ZN International?</h3>
                            <p class="text-slate-500">ZN International has been offering IT products and computer services for more than two decades. We guarantee 100% original products, official brand warranty services, and competitive pricing across scanners, printers, and accessories.</p>
                        </div>
                        <div>
                            <h3 class="text-slate-800 font-extrabold text-sm mb-1.5 uppercase">Quality Printer in Bangladesh</h3>
                            <p class="text-slate-500">Find top-tier printers from leading brands such as HP, Epson, Canon, and Brother for office or home needs with original cartridge support.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="border border-slate-100 bg-white p-4 rounded">
                            <ul class="space-y-2">
                                <li class="flex items-center gap-2"><span class="text-[#00a651]">✓</span> Wide Selection of Scanner</li>
                                <li class="flex items-center gap-2"><span class="text-[#00a651]">✓</span> Laptops, Monitor and computer accessories</li>
                                <li class="flex items-center gap-2"><span class="text-[#00a651]">✓</span> Best Office Equipment in Bangladesh</li>
                                <li class="flex items-center gap-2"><span class="text-[#00a651]">✓</span> Best E-Commerce Shop to Order Product</li>
                                <li class="flex items-center gap-2"><span class="text-[#00a651]">✓</span> Best Price, Personal &amp; Warranty service</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div
                    v-for="block in seoContent"
                    :key="block.heading"
                    class="space-y-2 border-t border-slate-100 pt-4"
                >
                    <h3 class="text-sm font-extrabold text-[#00a651] uppercase">
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

// Featured categories display (compact icons)
const displayedCategories = computed(() => {
    return (props.featuredCategories || []).slice(0, 5);
});

// Featured products (first section)
const featuredProducts = computed(() => {
    return (props.topSelling.length ? props.topSelling : props.latestProducts).slice(0, 10);
});

// Scrolling notice ticker
const noticeItems = computed(() =>
    (props.notice || [])
        .map((n) => n.title ?? n.message ?? n.description ?? "")
        .filter(Boolean),
);

// Hero promo cards
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

const selectedProduct = ref(null);
const openQuickView = (p) => {
    selectedProduct.value = p;
};

// Scroll 1 product card at a time (~220px)
const scrollCarousel = (id, direction) => {
    const el = document.getElementById(id);
    if (el) {
        const cardWidth = 220;
        el.scrollBy({ left: direction * cardWidth, behavior: 'smooth' });
    }
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

const seoContent = computed(() => page.props.settings?.seo_content || []);

const currentSlide = ref(0);
let autoSlide = null;
let isPaused = false;

const goToSlide = (i) => {
    if (!sliderImages.value.length) return;
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
