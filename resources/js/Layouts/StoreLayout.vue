<template>
    <div class="min-h-screen flex flex-col font-sans bg-[#f8fafc] text-slate-800">
        <!-- Premium Header -->
        <header class="bg-white/70 backdrop-blur-2xl sticky top-0 z-50 border-b border-slate-100/50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_4px_6px_-2px_rgba(0,0,0,0.05)]">
            <!-- Top Header -->
            <div class="max-w-[1700px] mx-auto px-4 md:px-8 py-4 flex items-center justify-between gap-4 md:gap-8">
                <!-- Logo -->
                <Link href="/" class="flex items-center flex-shrink-0 gap-3 group">
                    <img
                        v-if="siteLogo"
                        :src="siteLogo"
                        :alt="siteName"
                        class="h-9 md:h-11 w-auto object-contain transition-transform group-hover:scale-105 duration-700"
                    />
                    <div v-else class="w-10 h-10 bg-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-emerald-500/20 border border-white/20 transform group-hover:rotate-6 transition-transform">
                        Z
                    </div>
                    <span class="text-xl md:text-2xl font-black text-slate-900 tracking-tighter uppercase italic leading-none mt-1 hidden sm:inline-block">
                        {{ siteName }}
                    </span>
                </Link>

                <!-- Search (Desktop) -->
                <div class="hidden md:flex flex-grow max-w-xl relative group/search">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search Components, Laptops..."
                        class="w-full pl-6 pr-14 py-3.5 bg-slate-50 border border-slate-100 rounded-[20px] focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500/50 transition-all outline-none text-sm font-bold placeholder:text-slate-400"
                        @keyup.enter="handleSearch"
                    />
                    <button @click="handleSearch" class="absolute right-2 top-2 w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-emerald-600 transition-all shadow-lg active:scale-95">
                        <Search class="w-4.5 h-4.5" />
                    </button>
                </div>

                <!-- Action Tools -->
                <div class="flex items-center gap-2 md:gap-6">
                    <!-- Search (Mobile Toggle) -->
                    <button @click="isMobileSearchOpen = !isMobileSearchOpen" class="md:hidden p-3 text-slate-600 hover:text-emerald-600 transition-colors rounded-xl bg-slate-50 border border-slate-100">
                        <Search class="w-5 h-5" />
                    </button>

                    <!-- Account -->
                    <Link
                        v-if="currentUser"
                        :href="accountHref"
                        class="flex items-center gap-2.5 px-5 py-2.5 text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-xl transition-all hover:bg-emerald-600 hover:text-white"
                    >
                        <User class="w-4 h-4" />
                        <span class="hidden lg:inline text-[10px] font-black uppercase tracking-widest truncate max-w-[100px]">{{ currentUser.name }}</span>
                    </Link>
                    <button
                        v-else
                        @click="openAuthModal('login')"
                        class="flex items-center gap-2.5 px-5 py-2.5 text-slate-600 hover:text-white hover:bg-emerald-600 transition-all rounded-xl border border-slate-100 hover:border-emerald-600 font-black uppercase tracking-widest text-[10px]"
                    >
                        <User class="w-4 h-4" />
                        <span class="hidden lg:inline">Sign In</span>
                    </button>

                    <!-- Cart -->
                    <button @click="isCartOpen = true" class="relative p-3.5 bg-slate-900 text-white rounded-2xl hover:bg-emerald-600 transition-all shadow-xl active:scale-95 group/cart">
                        <ShoppingBag class="w-5 h-5 group-hover:scale-110 transition-transform" />
                        <span class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-emerald-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white shadow-lg animate-pulse">
                            {{ cartCount }}
                        </span>
                    </button>

                    <!-- Mobile Menu Toggle -->
                    <button @click="isMobileMenuOpen = true" class="md:hidden p-2.5 bg-white border border-slate-100 text-slate-600 rounded-xl hover:text-emerald-600 transition-all">
                        <Menu class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Mobile Search Bar (Expandable) -->
            <Transition v-bind="fadeSlideTransition">
                <div v-if="isMobileSearchOpen" class="md:hidden px-4 pb-4 bg-white border-t border-slate-50">
                    <div class="relative mt-2">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search..."
                            class="w-full pl-6 pr-12 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none text-sm font-bold"
                            @keyup.enter="handleSearch(); isMobileSearchOpen = false"
                        />
                        <button @click="handleSearch(); isMobileSearchOpen = false" class="absolute right-2 top-2 w-8 h-8 bg-emerald-600 text-white rounded-lg flex items-center justify-center">
                            <Search class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </Transition>

            <!-- Navigation (Desktop) -->
            <nav class="bg-[#002013] shadow-2xl relative hidden md:block">
                <div class="max-w-[1500px] mx-auto flex items-center h-14 px-4">
                    <Link href="/" class="px-6 py-4 text-emerald-500 text-[11px] font-black uppercase tracking-widest hover:bg-white/5 transition-all flex items-center gap-2 relative after:absolute after:bottom-0 after:left-0 after:h-1 after:w-full after:bg-emerald-500">
                        <Monitor class="w-4 h-4" /> Home
                    </Link>

                    <button v-if="hasPrevCategories" @click="prevCategories" class="px-3 text-slate-400 hover:text-emerald-500 transition-all">
                        <ChevronLeft class="w-4 h-4" />
                    </button>

                    <!-- Categories -->
                    <div v-for="category in visibleCategories" :key="category.id" class="relative group/category h-full flex">
                        <Link
                            :href="`/shop?category=${category.slug}`"
                            class="px-5 text-slate-300 text-[10px] font-black uppercase tracking-[0.15em] hover:text-white hover:bg-white/5 transition-all flex items-center gap-2"
                        >
                            {{ category.name }}
                            <ChevronDown v-if="categoryHasChildren(category)" class="w-3 h-3 opacity-40 group-hover/category:rotate-180 transition-transform" />
                        </Link>

                        <div
                            v-if="categoryHasChildren(category)"
                            class="absolute left-0 top-full bg-slate-900 p-2 shadow-2xl opacity-0 invisible group-hover/category:opacity-100 group-hover/category:visible transition-all duration-300 z-[60] min-w-[240px] rounded-xl border border-white/5 mt-1"
                        >
                            <!-- Two-level: sub-categories, each optionally expanding to brands -->
                            <template v-if="category.sub_categories?.length">
                                <div v-for="sub in category.sub_categories" :key="sub.id" class="relative group/sub">
                                    <Link
                                        :href="`/shop?sub_category=${sub.slug}`"
                                        class="flex items-center justify-between px-6 py-3 hover:bg-white/5 text-[10px] font-black text-slate-300 hover:text-emerald-500 uppercase tracking-widest rounded-lg"
                                    >
                                        {{ sub.name }}
                                        <ChevronRight v-if="sub.brands?.length" class="w-3 h-3 opacity-40" />
                                    </Link>
                                    <div
                                        v-if="sub.brands?.length"
                                        class="absolute left-full top-0 bg-slate-900 p-2 rounded-xl border border-white/5 shadow-2xl opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-300 min-w-[200px]"
                                    >
                                        <Link
                                            v-for="brand in sub.brands"
                                            :key="brand.id"
                                            :href="`/shop?sub_category=${sub.slug}&brand=${brand.slug}`"
                                            class="block px-6 py-3 text-[9px] font-black text-slate-400 hover:text-white uppercase tracking-widest hover:bg-white/5 rounded-lg"
                                        >
                                            {{ brand.name }}
                                        </Link>
                                    </div>
                                </div>
                            </template>

                            <!-- One-level: category links straight to brands -->
                            <template v-else>
                                <Link
                                    v-for="brand in category.brands"
                                    :key="brand.id"
                                    :href="`/shop?category=${category.slug}&brand=${brand.slug}`"
                                    class="block px-6 py-3 text-[10px] font-black text-slate-300 hover:text-emerald-500 uppercase tracking-widest hover:bg-white/5 rounded-lg"
                                >
                                    {{ brand.name }}
                                </Link>
                            </template>
                        </div>
                    </div>

                    <button v-if="hasNextCategories" @click="nextCategories" class="px-3 text-slate-400 hover:text-emerald-500 transition-all">
                        <ChevronRight class="w-4 h-4" />
                    </button>

                    <Link href="/shop?type=combo" class="ml-auto h-full px-6 flex items-center gap-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all">
                        <Zap class="w-4 h-4 text-yellow-300" /> PC BUILDER
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Mobile Side Menu -->
        <Transition v-bind="fadeTransition">
            <div v-if="isMobileMenuOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[100]" @click="isMobileMenuOpen = false"></div>
        </Transition>
        <Transition v-bind="slideTransition">
            <div v-if="isMobileMenuOpen" class="fixed top-0 right-0 h-full w-[320px] bg-white z-[110] shadow-[0_0_50px_rgba(0,0,0,0.2)] flex flex-col">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] italic">Systems Navigator</h2>
                    <button @click="isMobileMenuOpen = false" class="p-2.5 bg-white rounded-xl text-slate-400 hover:text-rose-500 transition-all border border-slate-100 shadow-sm active:scale-90">
                        <X class="w-5 h-5" />
                    </button>
                </div>
                <div class="flex-grow overflow-y-auto p-8 space-y-3 custom-scrollbar">
                    <Link href="/" class="flex items-center gap-4 px-5 py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20" @click="isMobileMenuOpen = false">
                        <Monitor class="w-4 h-4" /> Main Terminal
                    </Link>
                    <Link href="/shop?type=combo" class="flex items-center gap-4 px-5 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg" @click="isMobileMenuOpen = false">
                        <Zap class="w-4 h-4 text-yellow-400" /> PC Architect
                    </Link>
                    <div class="pt-8 pb-3 flex items-center gap-3">
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">Inventory Nodes</span>
                        <div class="h-px flex-grow bg-slate-100"></div>
                    </div>
                    <Link
                        v-for="category in allCategories"
                        :key="category.id"
                        :href="`/shop?category=${category.slug}`"
                        class="flex items-center justify-between px-5 py-4 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50/50 transition-all rounded-2xl text-[11px] font-black uppercase tracking-wide group"
                        @click="isMobileMenuOpen = false"
                    >
                        {{ category.name }}
                        <ChevronRight class="w-4 h-4 opacity-20 group-hover:opacity-100 group-hover:translate-x-1 transition-all" />
                    </Link>
                </div>
            </div>
        </Transition>

        <!-- Page Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Toast Notifications -->
        <Teleport to="body">
            <div class="fixed top-6 right-6 z-[9999] flex flex-col gap-4 pointer-events-none" style="min-width: 320px; max-width: 380px">
                <TransitionGroup name="toast">
                    <div v-for="toast in toasts" :key="toast.id" class="flex items-start gap-4 glass p-5 pointer-events-auto rounded-[24px] border border-white/50 shadow-2xl">
                        <div
                            class="flex-shrink-0 w-11 h-11 rounded-2xl flex items-center justify-center shadow-lg"
                            :class="toast.type === 'success' ? 'bg-emerald-600 text-white shadow-green-500/20' : 'bg-rose-600 text-white shadow-red-500/20'"
                        >
                            <CheckCircle v-if="toast.type === 'success'" class="w-5 h-5" />
                            <XCircle v-else class="w-5 h-5" />
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <p class="text-[10px] font-black uppercase tracking-widest mb-1" :class="toast.type === 'success' ? 'text-emerald-600' : 'text-rose-600'">
                                {{ toast.type === "success" ? "Success" : "Notification" }}
                            </p>
                            <p class="text-xs font-bold text-slate-700 leading-snug">{{ toast.message }}</p>
                        </div>
                        <button @click="removeToast(toast.id)" class="bg-slate-100/50 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 transition-colors">
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </TransitionGroup>
            </div>
        </Teleport>

        <!-- Premium Footer -->
        <footer class="bg-[#000a08] text-white pt-32 pb-16 border-t border-white/5 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none opacity-20">
                <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-emerald-600/10 rounded-full blur-[150px]"></div>
                <div class="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-emerald-900/20 rounded-full blur-[150px]"></div>
            </div>

            <div class="max-w-[1700px] mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-20 relative z-10">
                <div class="space-y-8">
                    <Link href="/" class="flex items-center gap-3">
                        <img v-if="siteLogo" :src="siteLogo" :alt="siteName" class="h-12 w-auto object-contain brightness-0 invert" />
                        <div v-else class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-xl shadow-emerald-500/10">Z</div>
                            <span class="text-2xl font-black text-white tracking-tighter uppercase italic leading-none">{{ siteName }}</span>
                        </div>
                    </Link>
                    <p class="text-[12px] font-medium text-slate-400 leading-relaxed tracking-wide pr-10">
                        {{ settings.footer_about || 'Engineered for absolute performance. Providing the core infrastructure for your digital victory.' }}
                    </p>
                    <div class="flex items-center gap-5 pt-4">
                        <a
                            v-if="settings.social_facebook"
                            :href="settings.social_facebook"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-emerald-600 hover:border-emerald-600 transition-all text-white shadow-2xl group active:scale-90"
                        >
                            <Facebook class="w-5 h-5 group-hover:scale-110 transition-transform" />
                        </a>
                        <a
                            v-if="settings.social_instagram"
                            :href="settings.social_instagram"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-gradient-to-tr from-rose-500 to-amber-500 hover:border-transparent transition-all text-white shadow-2xl group active:scale-90"
                        >
                            <Instagram class="w-5 h-5 group-hover:scale-110 transition-transform" />
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-12">
                    <div v-for="(pages, groupName) in groupedPages" :key="groupName" class="space-y-10">
                        <div class="inline-block relative">
                            <h4 class="text-[11px] font-black text-emerald-500 uppercase tracking-[0.5em] mb-3">{{ groupName }}</h4>
                            <div class="absolute -bottom-1 left-0 w-8 h-[2px] bg-emerald-500 rounded-full"></div>
                        </div>
                        <ul class="space-y-5 text-[11px] font-bold text-slate-300 uppercase tracking-[0.15em]">
                            <li v-for="page in pages" :key="page.id">
                                <Link :href="`/pages/${page.slug}`" class="hover:text-emerald-400 hover:translate-x-2 transition-all inline-flex items-center gap-2 group">
                                    <div class="w-1 h-1 bg-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    {{ pageTitle(page) }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="space-y-12">
                    <div class="inline-block relative">
                        <h4 class="text-[11px] font-black text-emerald-500 uppercase tracking-[0.5em] mb-3">Strategic Base</h4>
                        <div class="absolute -bottom-1 left-0 w-8 h-[2px] bg-emerald-500 rounded-full"></div>
                    </div>
                    <div class="space-y-8">
                        <div class="flex items-start gap-5">
                            <div class="w-11 h-11 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center shrink-0 shadow-lg glow-emerald">
                                <MapPin class="w-5 h-5 text-emerald-400" />
                            </div>
                            <span class="text-[11px] font-bold text-slate-300 leading-relaxed tracking-wider pt-1">{{ settings.footer_address }}</span>
                        </div>
                        <div class="flex items-center gap-5">
                            <div class="w-11 h-11 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center shrink-0 shadow-lg hover:bg-emerald-600 transition-colors cursor-pointer group">
                                <Phone class="w-5 h-5 text-emerald-400 group-hover:text-white" />
                            </div>
                            <span class="text-[12px] font-black text-white tracking-[0.2em]">{{ settings.footer_phone }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-[1700px] mx-auto px-4 md:px-8 mt-32 pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.6em] transition-colors hover:text-emerald-500">&copy; 2026 {{ siteName }} • ZN ADVANCED SYSTEMS</p>
                <span class="text-[10px] text-slate-600 font-black uppercase tracking-widest">v4.0.0 Stable</span>
            </div>
        </footer>

        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
        <AuthModal :isOpen="isAuthModalOpen" :initialMode="authModalMode" @close="isAuthModalOpen = false" />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import {
    Search, X, Phone, MapPin, ShoppingBag, User,
    ChevronLeft, ChevronDown, ChevronRight, Zap,
    Facebook, Instagram, Monitor, Menu,
} from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";
import { useToast } from "@/Composables/useToast";
import CartDrawer from "@/Components/CartDrawer.vue";
import AuthModal from "@/Components/AuthModal.vue";

const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || "ZN International");
const siteLogo = computed(() => settings.value.site_logo || null);
const locale = computed(() => page.props.locale);

const currentUser = computed(() => page.props.auth?.user || null);
const accountHref = computed(() => (currentUser.value?.role === "admin" ? "/admin/dashboard" : "/customer/orders"));

// --- Categories + nav pagination ---------------------------------------
const CATEGORY_PAGE_SIZE = 8;
const categoryStartIndex = ref(0);

const allCategories = computed(() => page.props.categories || []);
const visibleCategories = computed(() =>
    allCategories.value.slice(categoryStartIndex.value, categoryStartIndex.value + CATEGORY_PAGE_SIZE)
);
const hasPrevCategories = computed(() => categoryStartIndex.value > 0);
const hasNextCategories = computed(() => categoryStartIndex.value + CATEGORY_PAGE_SIZE < allCategories.value.length);

const nextCategories = () => {
    if (hasNextCategories.value) categoryStartIndex.value += CATEGORY_PAGE_SIZE;
};
const prevCategories = () => {
    categoryStartIndex.value = Math.max(0, categoryStartIndex.value - CATEGORY_PAGE_SIZE);
};

const categoryHasChildren = (category) => !!(category.sub_categories?.length || category.brands?.length);

// --- Footer page groups ---------------------------------------------------
const groupedPages = computed(() => {
    const pages = page.props.activePages || [];
    return pages.reduce((groups, p) => {
        const group = p.group || "Company";
        (groups[group] ||= []).push(p);
        return groups;
    }, {});
});

const pageTitle = (footerPage) => (locale.value === "bn" ? footerPage.title_bn : footerPage.title_en);

// --- Cart / toast / modals --------------------------------------------------
const { cartCount, isCartOpen } = useCart();
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

// --- Transition presets -----------------------------------------------------
const fadeTransition = {
    enterActiveClass: "transition duration-500 ease-out",
    enterFromClass: "opacity-0",
    enterToClass: "opacity-100",
    leaveActiveClass: "transition duration-400 ease-in",
    leaveFromClass: "opacity-100",
    leaveToClass: "opacity-0",
};
const fadeSlideTransition = {
    enterActiveClass: "transition duration-300 ease-out",
    enterFromClass: "opacity-0 -translate-y-4",
    enterToClass: "opacity-100 translate-y-0",
    leaveActiveClass: "transition duration-200 ease-in",
    leaveFromClass: "opacity-100 translate-y-0",
    leaveToClass: "opacity-0 -translate-y-4",
};
const slideTransition = {
    enterActiveClass: "transition duration-700 ease-[cubic-bezier(0.4,0,0.2,1)]",
    enterFromClass: "translate-x-full",
    enterToClass: "translate-x-0",
    leaveActiveClass: "transition duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]",
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
.toast-enter-active { transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
.toast-leave-active { transition: all 0.3s cubic-bezier(0.6, -0.28, 0.735, 0.045); }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(50px) scale(0.9); }
</style>