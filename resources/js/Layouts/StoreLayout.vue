<template>
    <div class="min-h-screen flex flex-col font-sans bg-slate-50 text-slate-900">
  <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 shadow-sm border-t-4 border-[#00a651] transition-all">
    <div class="max-w-[1200px] mx-auto w-full px-4 sm:px-6 h-16 flex items-center justify-between gap-4 bg-transparent">
        
        <!-- Logo Section -->
        <Link href="/" class="flex items-center flex-shrink-0 gap-2 group decoration-none">
            <img
                v-if="siteLogo"
                :src="siteLogo"
                :alt="siteName"
                class="h-8 md:h-9 w-auto object-contain transition-transform group-hover:scale-105 duration-300"
            />
            <div class="w-9 h-9 bg-[#ef4823] rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md transition-all group-hover:scale-105 group-hover:rotate-3 duration-300 select-none">
                Z
            </div>
            <div class="flex flex-col justify-center">
                <span class="text-base font-black text-[#ef4823] tracking-tight leading-none uppercase font-sans">
                    ZN
                </span>
                <span class="text-[8px] font-bold text-slate-400 tracking-widest leading-none uppercase mt-1 transition-colors group-hover:text-[#ef4823]">
                    International
                </span>
            </div>
        </Link>

        <!-- Desktop Search Bar -->
        <div class="hidden md:flex flex-grow max-w-md relative group/search">
            <div class="flex w-full items-center border border-slate-200 focus-within:border-[#00a651] focus-within:ring-2 focus-within:ring-[#00a651]/10 rounded-lg bg-slate-50/50 transition-all overflow-hidden">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search products, brands, categories..."
                    class="w-full pl-3 pr-4 py-2 bg-transparent text-slate-800 focus:outline-none border-none text-xs font-medium placeholder:text-slate-400"
                    @keyup.enter="handleSearch"
                />
                <button
                    @click="handleSearch"
                    class="bg-[#00a651] text-white px-4 py-2.5 flex items-center justify-center hover:bg-[#008541] transition-all cursor-pointer border-none self-stretch"
                >
                    <Search class="w-3.5 h-3.5" />
                </button>
            </div>
        </div>

        <!-- Right Side Actions Wrapper -->
        <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0 h-full">
            <!-- Mobile Search Toggle -->
            <button
                @click="isMobileSearchOpen = !isMobileSearchOpen"
                class="md:hidden p-2 text-[#00a651] bg-slate-50 hover:bg-[#e6f6ee] rounded-lg transition-colors border border-slate-200 cursor-pointer"
            >
                <Search class="w-4 h-4" />
            </button>

            <!-- Quick Links -->
            <Link
                href="/shop?category=printer"
                class="hidden sm:flex items-center px-3 py-2 bg-transparent text-[#00a651] border border-[#00a651] rounded-lg font-bold text-[10px] uppercase cursor-pointer hover:bg-[#00a651] hover:text-white transition-all select-none decoration-none"
            >
                Printers
            </Link>

            <Link
                href="/pc-builder"
                class="hidden sm:flex items-center px-3 py-2 bg-[#00a651] text-white border border-[#00a651] rounded-lg font-bold text-[10px] uppercase cursor-pointer hover:bg-[#008541] hover:border-[#008541] transition-all select-none decoration-none shadow-sm shadow-[#00a651]/20"
            >
                PC Builder
            </Link>

       

            <!-- Authentication State -->
            <Link
                v-if="currentUser"
                :href="accountHref"
                class="flex items-center gap-1.5 h-9 px-3 text-slate-700 bg-white border border-slate-200 rounded-lg transition-colors hover:bg-slate-50 font-bold text-[10px] decoration-none"
            >
                <User class="w-3.5 h-3.5 text-[#00a651]" />
                <span class="hidden lg:inline truncate max-w-[80px]">
                    {{ currentUser.name }}
                </span>
            </Link>
            
            <button
                v-else
                @click="openAuthModal('login')"
                class="flex items-center h-9 px-4 bg-slate-900 text-white rounded-lg font-bold text-[10px] uppercase cursor-pointer hover:bg-slate-800 border-none transition-all select-none"
            >
                Login
            </button>

            <!-- Mini Cart Dropdown Trigger -->
            <button
                @click="isCartOpen = true"
                class="flex items-center gap-2 h-9 px-3 bg-[#ef4823] text-white rounded-lg font-bold text-[10px] hover:bg-[#d63d1a] transition-all border-none relative cursor-pointer select-none shadow-sm shadow-[#ef4823]/20 group/cart"
            >
                <span>৳{{ parseFloat(cartTotal || 0).toLocaleString() }}</span>
                <ShoppingBag class="w-3.5 h-3.5 transition-transform group-hover/cart:scale-110" />
                <span class="bg-white text-[#ef4823] px-1.5 py-0.5 rounded font-black min-w-[16px] text-center text-[9px] shadow-sm">
                    {{ cartCount }}
                </span>
            </button>

            <!-- Mobile Hamburger Menu Toggle -->
            <button
                @click="isMobileMenuOpen = true"
                class="md:hidden p-2 bg-white border border-slate-200 text-slate-600 rounded-lg hover:text-[#00a651] hover:bg-[#e6f6ee] transition-colors cursor-pointer"
            >
                <Menu class="w-4 h-4" />
            </button>
        </div>
    </div>

    <!-- Mobile Search Drawer Open State -->
    <Transition v-bind="fadeSlideTransition">
        <div
            v-if="isMobileSearchOpen"
            class="md:hidden px-4 pb-4 pt-2 bg-white border-t border-slate-100 shadow-inner"
        >
            <div class="relative">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search for items..."
                    class="w-full pl-4 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#00a651]/20 focus:border-[#00a651] transition-all outline-none text-xs font-medium placeholder:text-slate-400"
                    @keyup.enter="handleSearch(); isMobileSearchOpen = false;"
                />
                <button
                    @click="handleSearch(); isMobileSearchOpen = false;"
                    class="absolute right-1.5 top-1.5 w-8 h-8 bg-[#00a651] text-white rounded-lg flex items-center justify-center border-none cursor-pointer hover:bg-[#008541] transition-colors"
                >
                    <Search class="w-3.5 h-3.5" />
                </button>
            </div>
        </div>
    </Transition>

    <!-- Desktop Navigation Layer -->
    <nav class="bg-[#081621] relative hidden md:block border-t border-slate-800/60 shadow-md">
        <div class="max-w-[1200px] mx-auto flex items-center h-11 px-4">
            <Link
                href="/"
                class="px-4 text-white text-[11px] font-bold hover:bg-[#00a651] transition-colors flex items-center gap-1.5 h-full uppercase select-none decoration-none border-r border-slate-800/40"
            >
                Home
            </Link>

            <button
                v-if="hasPrevCategories"
                @click="prevCategories"
                class="px-2.5 text-white bg-slate-900/50 hover:bg-[#00a651] border-none transition-colors h-full flex items-center cursor-pointer"
            >
                <ChevronLeft class="w-4 h-4" />
            </button>

            <!-- Dynamic Category List Map -->
            <div class="flex items-center h-full overflow-visible">
                <div
                    v-for="category in visibleCategories"
                    :key="category.id"
                    class="relative group/category h-full flex"
                >
                    <Link
                        :href="`/shop?category=${category.slug}`"
                        class="px-4 text-white text-[11px] tracking-wider font-bold hover:bg-[#00a651] transition-colors flex items-center gap-1.5 h-full uppercase select-none decoration-none"
                    >
                        {{ category.name }}
                        <ChevronDown
                            v-if="categoryHasChildren(category)"
                            class="w-3 h-3 text-slate-400 group-hover/category:text-white group-hover/category:rotate-180 transition-transform duration-200"
                        />
                    </Link>

                    <!-- Mega Dropdown Column Tier 1 -->
                    <div
                        v-if="categoryHasChildren(category)"
                        class="absolute left-0 top-full bg-white p-2 shadow-2xl opacity-0 invisible group-hover/category:opacity-100 group-hover/category:visible transition-all duration-200 z-[60] min-w-[250px] border border-slate-100 translate-y-2 group-hover/category:translate-y-0 rounded-b-xl"
                    >
                        <template v-if="category.sub_categories?.length">
                            <div
                                v-for="sub in category.sub_categories"
                                :key="sub.id"
                                class="relative group/sub"
                            >
                                <Link
                                    :href="`/shop?sub_category=${sub.slug}`"
                                    class="flex items-center justify-between px-3 py-2.5 hover:bg-slate-50 rounded-lg text-xs font-bold text-slate-700 hover:text-[#00a651] transition-colors decoration-none"
                                >
                                    {{ sub.name }}
                                    <ChevronRight
                                        v-if="sub.brands?.length"
                                        class="w-3 h-3 text-slate-400 group-hover/sub:text-[#00a651] transition-colors"
                                    />
                                </Link>

                                <!-- Dropdown Sub-tier Brand Matrix -->
                                <div
                                    v-if="sub.brands?.length"
                                    class="absolute left-[calc(100%-4px)] top-0 bg-white p-2 border border-slate-100 shadow-2xl opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-200 min-w-[200px] translate-x-2 group-hover/sub:translate-x-0 rounded-xl"
                                >
                                    <Link
                                        v-for="brand in sub.brands"
                                        :key="brand.id"
                                        :href="`/shop?sub_category=${sub.slug}&brand=${brand.slug}`"
                                        class="block px-3 py-2 text-xs font-semibold text-slate-600 hover:text-[#00a651] hover:bg-slate-50 rounded-md transition-colors decoration-none"
                                    >
                                        {{ brand.name }}
                                    </Link>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <Link
                                v-for="brand in category.brands"
                                :key="brand.id"
                                :href="`/shop?category=${category.slug}&brand=${brand.slug}`"
                                class="block px-3 py-2.5 text-xs font-bold text-slate-700 hover:text-[#00a651] hover:bg-slate-50 rounded-lg transition-colors decoration-none"
                            >
                                {{ brand.name }}
                            </Link>
                        </template>
                    </div>
                </div>
            </div>

            <button
                v-if="hasNextCategories"
                @click="nextCategories"
                class="px-2.5 text-white bg-slate-900/50 hover:bg-[#00a651] border-none transition-colors h-full flex items-center cursor-pointer ml-auto"
            >
                <ChevronRight class="w-4 h-4" />
            </button>
        </div>
    </nav>
</header>

        <Transition v-bind="fadeTransition">
            <div
                v-if="isMobileMenuOpen"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[100]"
                @click="isMobileMenuOpen = false"
            ></div>
        </Transition>

 <Transition v-bind="slideTransition">
    <div 
        v-if="isMobileMenuOpen" 
        class="fixed inset-0 bg-black/20 backdrop-blur-xs z-[105]" 
        @click="isMobileMenuOpen = false"
    ></div>
</Transition>

<Transition v-bind="slideTransition">
    <div
        v-if="isMobileMenuOpen"
        class="fixed top-0 right-0 h-full w-[240px] bg-white z-[110] shadow-2xl flex flex-col border-l border-slate-100"
    >
        <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-xs font-normal text-[#00a651] uppercase tracking-wider pl-2">Menu</h2>
            <button
                @click="isMobileMenuOpen = false"
                class="p-1 bg-white rounded-md text-slate-400 hover:text-[#00a651] transition-colors border border-slate-200 cursor-pointer"
            >
                <X class="w-3.5 h-3.5" />
            </button>
        </div>

        <div class="flex-grow overflow-y-auto px-2 py-2 space-y-0.5 custom-scrollbar">
            <Link
                href="/"
                class="flex items-center gap-2 px-3 py-1.5 text-[#00a651] hover:bg-[#e6f6ee] rounded-md transition-colors text-xs font-normal decoration-none"
                @click="isMobileMenuOpen = false"
            >
                <Monitor class="w-3.5 h-3.5" /> Home
            </Link>

            <Link
                href="/pc-builder"
                class="flex items-center gap-2 px-3 py-1.5 text-[#00a651] hover:bg-[#e6f6ee] rounded-md transition-colors text-xs font-normal decoration-none"
                @click="isMobileMenuOpen = false"
            >
                <Zap class="w-3.5 h-3.5" /> PC Builder
            </Link>

            <div class="pt-2 pb-1 px-3 border-b border-slate-100 mb-1 mt-1">
                <span class="text-[9px] font-normal text-slate-400 uppercase tracking-wider">
                    Categories
                </span>
            </div>

            <Link
                v-for="category in allCategories"
                :key="category.id"
                :href="`/shop?category=${category.slug}`"
                class="flex items-center justify-between px-3 py-1.5 text-[#00a651] hover:bg-[#e6f6ee] transition-colors rounded-md text-xs font-normal group decoration-none"
                @click="isMobileMenuOpen = false"
            >
                {{ category.name }}
                <ChevronRight class="w-3 h-3 text-[#00a651]/40 group-hover:text-[#00a651] group-hover:translate-x-0.5 transition-all" />
            </Link>
        </div>
    </div>
</Transition>

        <main class="flex-grow relative z-10">
            <slot />
        </main>

        <Teleport to="body">
            <div
                class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"
                style="min-width: 280px; max-w-sm: 340px"
            >
                <TransitionGroup name="toast">
                    <div
                        v-for="toast in toasts"
                        :key="toast.id"
                        class="flex items-start gap-3 bg-white p-4 pointer-events-auto rounded-lg border border-slate-200 shadow-xl"
                    >
                        <div
                            class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center"
                            :class="toast.type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'"
                        >
                            <CheckCircle v-if="toast.type === 'success'" class="w-4 h-4" />
                            <XCircle v-else class="w-4 h-4" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-900">
                                {{ toast.type === "success" ? "Success" : "Notification" }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-snug">
                                {{ toast.message }}
                            </p>
                        </div>
                        <button @click="removeToast(toast.id)" class="text-slate-400 hover:text-slate-600 transition-colors border-none bg-transparent cursor-pointer">
                            <X class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </TransitionGroup>
            </div>
        </Teleport>

        <footer class="bg-[#081621] text-slate-300 pt-14 pb-6 relative z-10 border-t-4 border-[#00a651]">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Column 1: Logo + About + Socials -->
                <div class="flex flex-col items-center lg:items-start text-center lg:text-left space-y-4">
                    <Link href="/" class="flex items-center gap-3 decoration-none">
                        <img
                            v-if="siteLogo"
                            :src="siteLogo"
                            :alt="siteName"
                            class="h-10 w-auto object-contain brightness-0 invert"
                        />
                        <div v-else class="flex items-center">
                            <div class="w-9 h-9 bg-[#00a651] rounded-full flex items-center justify-center text-white font-black text-xl shadow-sm mr-2 select-none">
                                Z
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="text-base font-black text-white tracking-tight leading-none uppercase">
                                    {{ siteName }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 tracking-wider leading-none uppercase mt-0.5">
                                    E-Commerce
                                </span>
                            </div>
                        </div>
                    </Link>
                    <p class="text-[11px] text-slate-400 leading-relaxed max-w-xs">
                        {{ settings.footer_about || "Your trusted partner for genuine corporate IT frameworks, premium printing hardware, and verified computer components across Bangladesh." }}
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <a href="https://facebook.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-700/60 hover:bg-[#00a651] flex items-center justify-center transition-colors text-white text-xs decoration-none">
                            <Facebook class="w-3.5 h-3.5" />
                        </a>
                        <a href="https://instagram.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-700/60 hover:bg-[#00a651] flex items-center justify-center transition-colors text-white text-xs decoration-none">
                            <Instagram class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="flex flex-col space-y-3">
                    <h4 class="text-[11px] font-bold text-[#00a651] uppercase tracking-widest mb-1 flex items-center gap-2">
                        <span class="w-4 h-0.5 bg-[#00a651] inline-block rounded"></span>
                        Quick Links
                    </h4>
                    <ul class="space-y-2 text-[11px] font-medium">
                        <li><Link href="/about" class="text-slate-400 hover:text-[#00a651] hover:pl-1 transition-all decoration-none">About Us</Link></li>
                        <li><Link href="/return-refund-policy" class="text-slate-400 hover:text-[#00a651] hover:pl-1 transition-all decoration-none">Return &amp; Refund Policy</Link></li>
                        <li><Link href="/warranty-policy" class="text-slate-400 hover:text-[#00a651] hover:pl-1 transition-all decoration-none">Warranty Support</Link></li>
                        <li><Link href="/privacy-policy" class="text-slate-400 hover:text-[#00a651] hover:pl-1 transition-all decoration-none">Privacy Policy</Link></li>
                        <li><Link href="/terms-conditions" class="text-slate-400 hover:text-[#00a651] hover:pl-1 transition-all decoration-none">Terms &amp; Conditions</Link></li>
                        <li><Link href="/contact" class="text-slate-400 hover:text-[#00a651] hover:pl-1 transition-all decoration-none">Contact Us</Link></li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="flex flex-col space-y-3">
                    <h4 class="text-[11px] font-bold text-[#00a651] uppercase tracking-widest mb-1 flex items-center gap-2">
                        <span class="w-4 h-0.5 bg-[#00a651] inline-block rounded"></span>
                        Contact Info
                    </h4>
                    <div class="space-y-3 text-[11px]">
                        <div class="flex items-start gap-2">
                            <MapPin class="w-3.5 h-3.5 text-[#00a651] mt-0.5 shrink-0" />
                            <p class="text-slate-400 leading-relaxed">
                                54, Multi Plan, Level-5 (Lift-5),
                                Beside Multiplan Center, New Elephant Road, Dhaka-1205.
                            </p>
                        </div>
                        <div class="flex items-start gap-2">
                            <Phone class="w-3.5 h-3.5 text-[#00a651] mt-0.5 shrink-0" />
                            <div class="text-slate-400">
                                <p class="font-bold text-slate-300 text-[10px] uppercase tracking-wider mb-0.5">Hotline</p>
                                <p>{{ settings.footer_phone || "01322-893290" }}</p>
                                <p>01322-893296</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 4: Branch + Map -->
                <div class="flex flex-col space-y-3">
                    <h4 class="text-[11px] font-bold text-[#00a651] uppercase tracking-widest mb-1 flex items-center gap-2">
                        <span class="w-4 h-0.5 bg-[#00a651] inline-block rounded"></span>
                        Our Branches
                    </h4>
                    <div class="space-y-3 text-[11px]">
                        <div>
                            <p class="font-bold text-slate-300 text-[10px] uppercase tracking-wider mb-0.5">Motijheel Branch</p>
                            <p class="text-slate-400 leading-relaxed">Shop 5-B, 1st Floor, Rahmania Complex, Motijheel, Dhaka-1000.</p>
                        </div>
                        <div>
                            <p class="font-bold text-slate-300 text-[10px] uppercase tracking-wider mb-0.5">Elephant Road Branch</p>
                            <p class="text-slate-400 leading-relaxed">Shop 843-844, Level 8, Multiplan Center, New Elephant Road, Dhaka-1205.</p>
                            <p class="text-slate-400 mt-0.5">01322-893291, 01322-893292</p>
                        </div>
                    </div>
                    <div class="w-full h-[100px] rounded-lg overflow-hidden border border-slate-700/50 shadow-md mt-1">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.2858169970984!2d90.38384217615024!3d23.737181089293444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b6099ae573%3A0xe6fd9bfdd7817b!2sMultiplan%20Center!5e0!3m2!1sen!2sbd!4v1716947620138!5m2!1sen!2sbd" 
                            class="w-full h-full border-0 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 mt-10 pt-6 border-t border-slate-700/50">
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="flex flex-col items-center gap-2">
                        <p class="text-[9px] font-bold uppercase text-slate-500 tracking-widest">Secure Payment Methods</p>
                        <div class="flex flex-wrap justify-center gap-1.5">
                            <span class="px-2.5 py-1 bg-white text-slate-900 rounded text-[9px] font-black tracking-tight">VISA</span>
                            <span class="px-2.5 py-1 bg-white text-slate-900 rounded text-[9px] font-black tracking-tight">Mastercard</span>
                            <span class="px-2.5 py-1 bg-[#e11d48] text-white rounded text-[9px] font-black tracking-tight">bKash</span>
                            <span class="px-2.5 py-1 bg-[#ea580c] text-white rounded text-[9px] font-black tracking-tight">Nagad</span>
                            <span class="px-2.5 py-1 bg-[#4f46e5] text-white rounded text-[9px] font-black tracking-tight">Rocket</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-center gap-x-5 gap-y-1 text-[10px] text-slate-400 font-bold uppercase tracking-wide">
                        <span>✓ Genuine Products</span>
                        <span>✓ Brand Warranty</span>
                        <span>✓ Nationwide Shipping</span>
                    </div>

                    <div class="w-full flex flex-col md:flex-row justify-between items-center gap-3 pt-3 border-t border-slate-800 text-slate-500 font-semibold text-[11px]">
                        <p>© {{ new Date().getFullYear() }} {{ siteName }}. All rights reserved.</p>
                        <span class="uppercase tracking-widest text-[8px] font-black text-slate-600 bg-slate-900/50 px-2 py-0.5 rounded">
                            Developed by admin systems
                        </span>
                    </div>
                </div>
            </div>
        </footer>

        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
        <AuthModal
            :isOpen="isAuthModalOpen"
            :initialMode="authModalMode"
            @close="isAuthModalOpen = false"
        />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import {
    Search,
    X,
    Phone,
    MapPin,
    ShoppingBag,
    User,
    ChevronLeft,
    ChevronDown,
    ChevronRight,
    Zap,
    Facebook,
    Instagram,
    Monitor,
    Menu,
    CheckCircle,
    XCircle,
} from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";
import { useToast } from "@/Composables/useToast";
import CartDrawer from "@/Components/CartDrawer.vue";
import AuthModal from "@/Components/AuthModal.vue";

const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || "Store");
const siteLogo = computed(() => settings.value.site_logo || null);
const locale = computed(() => page.props.locale);

const currentUser = computed(() => page.props.auth?.user || null);
const accountHref = computed(() =>
    currentUser.value?.role === "admin"
        ? "/admin/dashboard"
        : "/customer/orders",
);

// --- Categories + nav pagination ---
const CATEGORY_PAGE_SIZE = 8;
const categoryStartIndex = ref(0);

const allCategories = computed(() => page.props.categories || []);
const visibleCategories = computed(() =>
    allCategories.value.slice(
        categoryStartIndex.value,
        categoryStartIndex.value + CATEGORY_PAGE_SIZE,
    ),
);
const hasPrevCategories = computed(() => categoryStartIndex.value > 0);
const hasNextCategories = computed(
    () =>
        categoryStartIndex.value + CATEGORY_PAGE_SIZE <
        allCategories.value.length,
);

const nextCategories = () => {
    if (hasNextCategories.value) categoryStartIndex.value += CATEGORY_PAGE_SIZE;
};
const prevCategories = () => {
    categoryStartIndex.value = Math.max(
        0,
        categoryStartIndex.value - CATEGORY_PAGE_SIZE,
    );
};

const categoryHasChildren = (category) =>
    !!(category.sub_categories?.length || category.brands?.length);

// --- Footer page groups ---
const groupedPages = computed(() => {
    const pages = page.props.activePages || [];
    return pages.reduce((groups, p) => {
        const group = p.group || "Company";
        (groups[group] ||= []).push(p);
        return groups;
    }, {});
});

const pageTitle = (footerPage) =>
    locale.value === "bn" ? footerPage.title_bn : footerPage.title_en;

// --- Cart / toast / modals ---
const { cartCount, isCartOpen, cartTotal } = useCart();
const { toasts, removeToast } = useToast();

const isAuthModalOpen = ref(false);
const isMobileMenuOpen = ref(false);
const isMobileSearchOpen = ref(false);
const authModalMode = ref("login");
const searchQuery = ref("");

const openAuthModal = (mode = "login") => {
    authModalMode.value = mode;
    isAuthModalOpen.value = true;
};

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.get("/shop", { search: searchQuery.value });
    }
};

// --- Transitions ---
const fadeTransition = {
    enterActiveClass: "transition duration-300 ease-out",
    enterFromClass: "opacity-0",
    enterToClass: "opacity-100",
    leaveActiveClass: "transition duration-200 ease-in",
    leaveFromClass: "opacity-100",
    leaveToClass: "opacity-0",
};
const fadeSlideTransition = {
    enterActiveClass: "transition duration-200 ease-out",
    enterFromClass: "opacity-0 -translate-y-2",
    enterToClass: "opacity-100 translate-y-0",
    leaveActiveClass: "transition duration-150 ease-in",
    leaveFromClass: "opacity-100 translate-y-0",
    leaveToClass: "opacity-0 -translate-y-2",
};
const slideTransition = {
    enterActiveClass: "transition duration-300 ease-out",
    enterFromClass: "translate-x-full",
    enterToClass: "translate-x-0",
    leaveActiveClass: "transition duration-200 ease-in",
    leaveFromClass: "translate-x-0",
    leaveToClass: "translate-x-full",
};
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.toast-enter-active {
    transition: all 0.3s ease-out;
}
.toast-leave-active {
    transition: all 0.2s ease-in;
}
.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
