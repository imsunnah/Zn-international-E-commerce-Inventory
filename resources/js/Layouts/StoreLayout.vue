<template>
    <div
        class="min-h-screen flex flex-col font-sans bg-slate-50 text-slate-900"
    >
        <!-- Minimal Header -->
        <header class="bg-white sticky top-0 z-50 shadow-sm">
            <!-- Top Header -->
            <div
                class="max-w-[1500px] mx-auto px-4 md:px-8 py-4 flex items-center justify-between gap-4 md:gap-8 bg-white"
            >
                <!-- Logo -->
                <Link
                    href="/"
                    class="flex items-center flex-shrink-0 gap-3 group"
                >
                    <img
                        v-if="siteLogo"
                        :src="siteLogo"
                        :alt="siteName"
                        class="h-9 md:h-11 w-auto object-contain transition-transform group-hover:scale-105 duration-300"
                    />
                    <div
                        v-else
                        class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-sm group-hover:bg-emerald-700 transition-colors"
                    >
                        Z
                    </div>
                    <span
                        class="text-xl md:text-2xl font-black text-slate-900 tracking-tight leading-none mt-1 hidden sm:inline-block"
                    >
                        {{ siteName }}
                    </span>
                </Link>

                <!-- Search (Desktop) -->
                <div
                    class="hidden md:flex flex-grow max-w-xl relative group/search"
                >
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search products, brands..."
                        class="w-full pl-6 pr-14 py-3 bg-slate-100 border border-slate-200/80 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 focus:bg-white transition-all outline-none text-sm font-medium placeholder:text-slate-400"
                        @keyup.enter="handleSearch"
                    />
                    <button
                        @click="handleSearch"
                        class="absolute right-2 top-1.5 w-9 h-9 bg-slate-900 text-white rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors shadow-sm"
                    >
                        <Search class="w-4 h-4" />
                    </button>
                </div>

                <!-- Action Tools -->
                <div class="flex items-center gap-2 md:gap-4">
                    <!-- Search (Mobile Toggle) -->
                    <button
                        @click="isMobileSearchOpen = !isMobileSearchOpen"
                        class="md:hidden p-2.5 text-slate-500 hover:text-emerald-600 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200"
                    >
                        <Search class="w-5 h-5" />
                    </button>

                    <!-- Account -->
                    <Link
                        v-if="currentUser"
                        :href="accountHref"
                        class="flex items-center gap-2 px-4 py-2.5 text-slate-700 bg-white border border-slate-200 rounded-xl transition-colors hover:bg-slate-50"
                    >
                        <User class="w-4 h-4 text-emerald-600" />
                        <span
                            class="hidden lg:inline text-xs font-semibold truncate max-w-[120px]"
                            >{{ currentUser.name }}</span
                        >
                    </Link>
                    <button
                        v-else
                        @click="openAuthModal('login')"
                        class="flex items-center gap-2 px-4 py-2.5 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors rounded-xl border border-slate-200 font-semibold text-xs"
                    >
                        <User class="w-4 h-4" />
                        <span class="hidden lg:inline">Sign In</span>
                    </button>

                    <!-- Cart -->
                    <button
                        @click="isCartOpen = true"
                        class="relative p-3 bg-slate-900 text-white rounded-xl hover:bg-emerald-600 transition-colors shadow-sm group/cart"
                    >
                        <ShoppingBag
                            class="w-5 h-5 group-hover:scale-105 transition-transform"
                        />
                        <span
                            v-if="cartCount > 0"
                            class="absolute -top-2 -right-2 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm"
                        >
                            {{ cartCount }}
                        </span>
                    </button>

                    <!-- Mobile Menu Toggle -->
                    <button
                        @click="isMobileMenuOpen = true"
                        class="md:hidden p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:text-emerald-600 hover:bg-slate-50 transition-colors"
                    >
                        <Menu class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Mobile Search Bar (Expandable) -->
            <Transition v-bind="fadeSlideTransition">
                <div
                    v-if="isMobileSearchOpen"
                    class="md:hidden px-4 pb-4 bg-white border-t border-slate-100"
                >
                    <div class="relative mt-2">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search..."
                            class="w-full pl-5 pr-12 py-2.5 bg-slate-100 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none text-sm font-medium"
                            @keyup.enter="
                                handleSearch();
                                isMobileSearchOpen = false;
                            "
                        />
                        <button
                            @click="
                                handleSearch();
                                isMobileSearchOpen = false;
                            "
                            class="absolute right-1.5 top-1.5 w-8 h-8 bg-emerald-600 text-white rounded-lg flex items-center justify-center"
                        >
                            <Search class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </Transition>

            <!-- Navigation (Desktop) - Enhanced Green Theme -->
            <nav
                class="bg-emerald-600 relative hidden md:block shadow-md border-t border-emerald-700/40"
            >
                <div class="max-w-[1500px] mx-auto flex items-center h-12 px-4">
                    <Link
                        href="/"
                        class="px-5 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors flex items-center gap-2 h-full"
                    >
                        <Monitor class="w-4 h-4 text-emerald-200" /> Home
                    </Link>

                    <button
                        v-if="hasPrevCategories"
                        @click="prevCategories"
                        class="px-3 text-emerald-100 hover:text-white transition-colors h-full flex items-center bg-emerald-700/30 hover:bg-emerald-700"
                    >
                        <ChevronLeft class="w-4 h-4" />
                    </button>

                    <!-- Categories Dropdowns Restyled -->
                    <div
                        v-for="category in visibleCategories"
                        :key="category.id"
                        class="relative group/category h-full flex"
                    >
                        <Link
                            :href="`/shop?category=${category.slug}`"
                            class="px-5 text-emerald-50 text-xs font-semibold hover:text-white hover:bg-emerald-700 transition-colors flex items-center gap-2 h-full"
                        >
                            {{ category.name }}
                            <ChevronDown
                                v-if="categoryHasChildren(category)"
                                class="w-3 h-3 text-emerald-200/80 group-hover/category:text-white group-hover/category:rotate-180 transition-transform duration-200"
                            />
                        </Link>

                        <!-- Level 1 Dropdown Menu -->
                        <div
                            v-if="categoryHasChildren(category)"
                            class="absolute left-0 top-full bg-white p-2 shadow-2xl opacity-0 invisible group-hover/category:opacity-100 group-hover/category:visible transition-all duration-200 z-[60] min-w-[260px] rounded-b-2xl border border-slate-100 translate-y-2 group-hover/category:translate-y-0"
                        >
                            <template v-if="category.sub_categories?.length">
                                <div
                                    v-for="sub in category.sub_categories"
                                    :key="sub.id"
                                    class="relative group/sub"
                                >
                                    <Link
                                        :href="`/shop?sub_category=${sub.slug}`"
                                        class="flex items-center justify-between px-4 py-2.5 hover:bg-emerald-50/60 text-xs font-semibold text-slate-700 hover:text-emerald-700 rounded-xl transition-colors"
                                    >
                                        {{ sub.name }}
                                        <ChevronRight
                                            v-if="sub.brands?.length"
                                            class="w-3 h-3 text-slate-400 group-hover/sub:text-emerald-600 transition-colors"
                                        />
                                    </Link>

                                    <!-- Level 2 Dropdown Panel -->
                                    <div
                                        v-if="sub.brands?.length"
                                        class="absolute left-[calc(100%-4px)] top-0 bg-white p-2 rounded-2xl border border-slate-100 shadow-2xl opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-200 min-w-[220px] translate-x-2 group-hover/sub:translate-x-0"
                                    >
                                        <Link
                                            v-for="brand in sub.brands"
                                            :key="brand.id"
                                            :href="`/shop?sub_category=${sub.slug}&brand=${brand.slug}`"
                                            class="block px-4 py-2 text-xs font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60 rounded-lg transition-colors"
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
                                    class="block px-4 py-2.5 text-xs font-semibold text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/60 rounded-xl transition-colors"
                                >
                                    {{ brand.name }}
                                </Link>
                            </template>
                        </div>
                    </div>

                    <button
                        v-if="hasNextCategories"
                        @click="nextCategories"
                        class="px-3 text-emerald-100 hover:text-white transition-colors h-full flex items-center bg-emerald-700/30 hover:bg-emerald-700"
                    >
                        <ChevronRight class="w-4 h-4" />
                    </button>

                    <!-- High contrast PC Builder button -->
                    <Link
                        href="/shop?type=combo"
                        class="ml-auto h-full px-6 flex items-center gap-2 bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors border-l border-emerald-700/30"
                    >
                        <Zap class="w-4 h-4 text-emerald-400 animate-pulse" />
                        PC BUILDER
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Mobile Side Menu -->
        <Transition v-bind="fadeTransition">
            <div
                v-if="isMobileMenuOpen"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100]"
                @click="isMobileMenuOpen = false"
            ></div>
        </Transition>
        <Transition v-bind="slideTransition">
            <div
                v-if="isMobileMenuOpen"
                class="fixed top-0 right-0 h-full w-[300px] bg-white z-[110] shadow-2xl flex flex-col"
            >
                <div
                    class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50"
                >
                    <h2 class="text-sm font-bold text-slate-900">Menu</h2>
                    <button
                        @click="isMobileMenuOpen = false"
                        class="p-2 bg-white rounded-lg text-slate-500 hover:text-slate-900 transition-colors border border-slate-200"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>
                <div
                    class="flex-grow overflow-y-auto p-6 space-y-2 custom-scrollbar"
                >
                    <Link
                        href="/"
                        class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 rounded-xl font-semibold text-sm"
                        @click="isMobileMenuOpen = false"
                    >
                        <Monitor class="w-4 h-4" /> Home
                    </Link>
                    <Link
                        href="/shop?type=combo"
                        class="flex items-center gap-3 px-4 py-3 bg-slate-100 text-slate-800 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors"
                        @click="isMobileMenuOpen = false"
                    >
                        <Zap class="w-4 h-4 text-slate-600" /> PC Builder
                    </Link>
                    <div class="pt-6 pb-2">
                        <span
                            class="text-xs font-semibold text-slate-400 uppercase tracking-wider"
                            >Categories</span
                        >
                    </div>
                    <Link
                        v-for="category in allCategories"
                        :key="category.id"
                        :href="`/shop?category=${category.slug}`"
                        class="flex items-center justify-between px-4 py-3 text-slate-600 hover:text-emerald-700 hover:bg-slate-50 transition-colors rounded-xl text-sm font-medium group"
                        @click="isMobileMenuOpen = false"
                    >
                        {{ category.name }}
                        <ChevronRight
                            class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"
                        />
                    </Link>
                </div>
            </div>
        </Transition>

        <!-- Page Content -->
        <main class="flex-grow relative z-10">
            <div
                class="fixed top-0 left-0 right-0 h-40 bg-gradient-to-b from-white to-transparent pointer-events-none z-0 opacity-50"
            ></div>
            <slot />
        </main>

        <!-- Toast Notifications -->
        <Teleport to="body">
            <div
                class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"
                style="min-width: 300px; max-width: 360px"
            >
                <TransitionGroup name="toast">
                    <div
                        v-for="toast in toasts"
                        :key="toast.id"
                        class="flex items-start gap-4 bg-white p-4 pointer-events-auto rounded-xl border border-slate-200 shadow-xl"
                    >
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                            :class="
                                toast.type === 'success'
                                    ? 'bg-emerald-100 text-emerald-600'
                                    : 'bg-rose-100 text-rose-600'
                            "
                        >
                            <CheckCircle
                                v-if="toast.type === 'success'"
                                class="w-4 h-4"
                            />
                            <XCircle v-else class="w-4 h-4" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-900">
                                {{
                                    toast.type === "success"
                                        ? "Success"
                                        : "Notification"
                                }}
                            </p>
                            <p
                                class="text-xs text-slate-500 mt-0.5 leading-snug"
                            >
                                {{ toast.message }}
                            </p>
                        </div>
                        <button
                            @click="removeToast(toast.id)"
                            class="text-slate-400 hover:text-slate-600 transition-colors"
                        >
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </TransitionGroup>
            </div>
        </Teleport>

        <!-- Premium Green Footer Overhaul -->
        <footer
            class="bg-emerald-950 text-emerald-100/80 pt-16 pb-8 relative z-10 border-t-4 border-emerald-500"
        >
            <div
                class="max-w-[1500px] mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12"
            >
                <!-- Site Identity -->
                <div class="space-y-6">
                    <Link href="/" class="flex items-center gap-3">
                        <img
                            v-if="siteLogo"
                            :src="siteLogo"
                            :alt="siteName"
                            class="h-10 w-auto object-contain brightness-0 invert"
                        />
                        <div v-else class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-emerald-950 font-black text-2xl shadow-md"
                            >
                                Z
                            </div>
                            <span
                                class="text-2xl font-black text-white tracking-tight leading-none"
                            >
                                {{ siteName }}
                            </span>
                        </div>
                    </Link>
                    <p
                        class="text-sm font-medium leading-relaxed text-emerald-200/70 pr-4"
                    >
                        {{
                            settings.footer_about ||
                            "Your trusted source for original printers, ink, laptops, and professional IT solutions."
                        }}
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a
                            v-if="settings.social_facebook"
                            :href="settings.social_facebook"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-10 h-10 rounded-xl bg-emerald-900/60 border border-emerald-800/60 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-950 transition-colors text-emerald-300 shadow-sm"
                        >
                            <Facebook class="w-4 h-4" />
                        </a>
                        <a
                            v-if="settings.social_instagram"
                            :href="settings.social_instagram"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-10 h-10 rounded-xl bg-emerald-900/60 border border-emerald-800/60 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-950 transition-colors text-emerald-300 shadow-sm"
                        >
                            <Instagram class="w-4 h-4" />
                        </a>
                    </div>
                </div>

                <!-- Dynmic Link Columns -->
                <div
                    class="lg:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-8"
                >
                    <div
                        v-for="(pages, groupName) in groupedPages"
                        :key="groupName"
                        class="space-y-6"
                    >
                        <h4
                            class="text-xs font-bold text-emerald-400 uppercase tracking-widest"
                        >
                            {{ groupName }}
                        </h4>
                        <ul class="space-y-3 text-sm font-medium">
                            <li v-for="page in pages" :key="page.id">
                                <Link
                                    :href="`/pages/${page.slug}`"
                                    class="text-emerald-200/80 hover:text-white hover:underline underline-offset-4 transition-colors inline-block"
                                >
                                    {{ pageTitle(page) }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Grid Card -->
                <div class="space-y-6">
                    <h4
                        class="text-xs font-bold text-emerald-400 uppercase tracking-widest"
                    >
                        Contact & Support
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-emerald-900/60 border border-emerald-800/60 rounded-xl flex items-center justify-center shrink-0 text-emerald-300 shadow-sm"
                            >
                                <MapPin class="w-4 h-4" />
                            </div>
                            <span
                                class="text-sm font-medium text-emerald-200/80 leading-relaxed pt-1"
                            >
                                {{ settings.footer_address }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 bg-emerald-900/60 border border-emerald-800/60 rounded-xl flex items-center justify-center shrink-0 text-emerald-300 shadow-sm"
                            >
                                <Phone class="w-4 h-4" />
                            </div>
                            <span
                                class="text-base font-bold text-white tracking-wide"
                            >
                                {{ settings.footer_phone }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright bar -->
            <div
                class="max-w-[1500px] mx-auto px-4 md:px-8 mt-16 pt-8 border-t border-emerald-900 flex flex-col md:flex-row justify-between items-center gap-6"
            >
                <p class="text-xs text-emerald-300/60 font-medium">
                    &copy; {{ new Date().getFullYear() }} {{ siteName }}. All
                    rights reserved.
                </p>
                <span
                    class="text-xs text-emerald-400/50 font-medium tracking-wide uppercase"
                >
                    Developed by admin systems
                </span>
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
