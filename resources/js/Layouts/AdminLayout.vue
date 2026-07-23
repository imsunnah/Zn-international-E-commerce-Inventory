<template>
    <div class="flex h-screen bg-slate-50/50 text-slate-900 font-sans relative">
        <!-- Sidebar (Premium Light Theme) -->
        <aside :class="['w-72 bg-white flex flex-col transition-all duration-300 z-[60] flex-shrink-0 fixed inset-y-0 left-0 md:relative border-r border-slate-200/60 shadow-[4px_0_24px_-10px_rgba(0,0,0,0.05)]', isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0']">
            <!-- Logo Area -->
            <div class="h-24 flex items-center px-8 border-b border-slate-100 mb-4">
                <Link href="/admin/dashboard" class="flex items-center space-x-3.5 group">
                    <div class="w-11 h-11 bg-[#00a651] rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20 group-hover:scale-105 transition-transform">
                        <span class="text-white font-black text-xl italic">Z</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-black tracking-tight text-slate-800 leading-none uppercase italic">{{ $page.props.settings.site_name }}</span>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1.5 flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                            Command Center
                        </span>
                    </div>
                </Link>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-2 px-4 custom-scrollbar space-y-8">
                <!-- ==================== CORE METRICS ==================== -->
                <div>
                    <div class="px-4 mb-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('Quick Access') }}</div>
                    <ul class="space-y-1">
                        <li>
                            <Link href="/admin/dashboard" :class="[$page.url.startsWith('/admin/dashboard') ? 'bg-green-50 text-[#00a651] shadow-sm shadow-green-500/5' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 group">
                                <LayoutDashboard class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith('/admin/dashboard') ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t('Dashboard') }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- ==================== SALES & ORDERS ==================== -->
                <div>
                    <div class="px-4 mb-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('Sales Engine') }}</div>
                    <ul class="space-y-1">
                        <li>
                            <Link href="/admin/orders" :class="[$page.url.startsWith('/admin/orders') && !$page.url.startsWith('/admin/orders/courier') ? 'bg-green-50 text-[#00a651]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all group">
                                <ShoppingCart class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith('/admin/orders') && !$page.url.startsWith('/admin/orders/courier') ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t('Orders') }}</span>
                                <span v-if="$page.props.pendingOrdersCount > 0" class="ml-auto bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-lg shadow-lg shadow-red-500/20">{{ $page.props.pendingOrdersCount }}</span>
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.orders.courier')" :class="[$page.url.startsWith('/admin/orders/courier') ? 'bg-green-50 text-[#00a651]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all group">
                                <Truck class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith('/admin/orders/courier') ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t('Courier Hub') }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- ==================== INVENTORY & CATALOG ==================== -->
                <div>
                    <div class="px-4 mb-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('Products & Catalog') }}</div>
                    <ul class="space-y-1">
                        <li v-for="item in [
                            { href: '/admin/products', icon: Package, label: 'Products' },
                            { href: '/admin/combos', icon: Zap, label: 'Combos' },
                            { href: '/admin/categories', icon: Tags, label: 'Categories' },
                            { href: '/admin/subcategories', icon: ListTree, label: 'Subcategories' },
                            { href: '/admin/brands', icon: Award, label: 'Brands' }
                        ]" :key="item.href">
                            <Link :href="item.href" :class="[$page.url.startsWith(item.href) ? 'bg-green-50 text-[#00a651]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all group">
                                <component :is="item.icon" class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith(item.href) ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t(item.label) }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- ==================== CUSTOMER RELATIONSHIP ==================== -->
                <div>
                    <div class="px-4 mb-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('Management & Services') }}</div>
                    <ul class="space-y-1">
                        <li>
                            <Link href="/admin/customers" :class="[$page.url.startsWith('/admin/customers') ? 'bg-green-50 text-[#00a651]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all group">
                                <Users class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith('/admin/customers') ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t('Customers') }}</span>
                            </Link>
                        </li>
                        <li>
                            <Link href="/admin/chat" :class="[$page.url.startsWith('/admin/chat') ? 'bg-green-50 text-[#00a651]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all group">
                                <MessageSquare class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith('/admin/chat') ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t('Support') }}</span>
                                <span v-if="$page.props.unreadChatCount > 0" class="ml-auto bg-green-500 text-white text-[9px] font-black px-2 py-0.5 rounded-lg shadow-lg shadow-green-500/20">{{ $page.props.unreadChatCount }}</span>
                            </Link>
                        </li>
                        <li>
                            <Link href="/admin/reviews" :class="[$page.url.startsWith('/admin/reviews') ? 'bg-green-50 text-[#00a651]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all group">
                                <Star class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith('/admin/reviews') ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t('Reviews') }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- ==================== SYSTEM & CONTENT ==================== -->
                <div>
                     <div class="px-4 mb-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('System Settings') }}</div>
                     <ul class="space-y-1">
                        <li v-for="item in [
                            { href: '/admin/notices', icon: Bell, label: 'Notices' },
                            { href: '/admin/discounts', icon: Percent, label: 'Discounts' },
                            { href: '/admin/settings', icon: Settings, label: 'Settings' }
                        ]" :key="item.href">
                            <Link :href="item.href" :class="[$page.url.startsWith(item.href) ? 'bg-green-50 text-[#00a651]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']" class="flex items-center px-4 py-3 rounded-2xl transition-all group">
                                <component :is="item.icon" class="w-5 h-5 mr-3.5" :class="[$page.url.startsWith(item.href) ? 'text-[#00a651]' : 'text-slate-400 group-hover:text-slate-600']" />
                                <span class="font-bold text-xs uppercase tracking-wider">{{ $t(item.label) }}</span>
                            </Link>
                        </li>
                        <li>
                            <a href="/" target="_blank" class="flex items-center px-4 py-3 text-slate-400 hover:text-[#00a651] transition-all group decoration-none">
                                <ExternalLink class="w-4 h-4 mr-3.5" />
                                <span class="font-bold text-[11px] uppercase tracking-wider">{{ $t('Live Store') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Logout Area -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                <Link href="/admin/logout" method="post" as="button" class="flex items-center w-full px-4 py-3 text-slate-400 hover:bg-red-50 hover:text-red-500 rounded-2xl transition-all font-bold text-xs uppercase tracking-widest group">
                    <LogOut class="w-5 h-5 mr-3.5 group-hover:scale-110 transition-transform" />
                    {{ $t('Sign Out') }}
                </Link>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[50] md:hidden"></div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Refined Top Header -->
            <header class="h-24 bg-white/80 backdrop-blur-md border-b border-slate-200/60 flex items-center justify-between px-6 md:px-10 z-20 flex-shrink-0">
                <div class="flex items-center gap-6">
                    <button @click="isSidebarOpen = true" class="md:hidden p-3 bg-slate-50 text-slate-600 hover:text-slate-900 rounded-2xl transition-colors">
                        <Menu class="w-6 h-6" />
                    </button>
                    <div class="relative w-80 hidden lg:block">
                        <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input type="text" placeholder="Explore metrics and records..." class="w-full bg-slate-50 border-none rounded-2xl py-3.5 pl-12 pr-4 text-xs focus:bg-white focus:ring-2 focus:ring-green-500/10 outline-none transition-all placeholder:text-slate-400 font-bold text-slate-700">
                    </div>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Support Indicator -->
                    <Link href="/admin/chat" class="hidden sm:flex items-center gap-3 px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-100 rounded-2xl text-slate-600 hover:text-[#00a651] transition-all text-[10px] font-black uppercase tracking-widest relative group">
                        <MessageSquare class="w-4 h-4 text-slate-400 group-hover:text-[#00a651]" />
                        <span>Support Inbox</span>
                        <span v-if="$page.props.unreadChatCount > 0" class="absolute -top-1.5 -right-1.5 px-1.5 py-0.5 bg-green-500 text-white text-[8px] font-black rounded-lg border-2 border-white shadow-lg">{{ $page.props.unreadChatCount }}</span>
                    </Link>

                    <div class="h-8 w-[1px] bg-slate-200 hidden sm:block"></div>

                    <!-- Language & User -->
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-1.5 bg-slate-50 p-1 rounded-xl">
                            <a v-for="lang in ['en', 'bn']" :key="lang" :href="`/language/${lang}`" :class="$page.props.locale === lang ? 'bg-white text-[#00a651] shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all tracking-wider">{{ lang }}</a>
                        </div>

                        <div class="flex items-center gap-4 pl-2 relative">
                            <div class="flex flex-col text-right hidden sm:flex">
                                <span class="text-xs font-black text-slate-800 uppercase tracking-tight leading-none italic">{{ $page.props.auth?.user?.name || 'Admin' }}</span>
                                <span class="text-[9px] font-black text-green-500 uppercase tracking-widest mt-1.5">Elite Partner</span>
                            </div>
                            <div @click="toggleProfile" class="relative group cursor-pointer">
                                <div class="w-12 h-12 rounded-2xl bg-[#00a651] flex items-center justify-center text-white font-black italic shadow-lg shadow-green-500/20 group-hover:scale-105 transition-transform border-4 border-slate-50">
                                    {{ $page.props.auth?.user?.name ? $page.props.auth.user.name.charAt(0) : 'A' }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>

                            <!-- Profile Dropdown -->
                            <Transition
                                enter-active-class="transition duration-200 ease-out"
                                enter-from-class="opacity-0 translate-y-2 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 translate-y-2 scale-95"
                            >
                                <div v-if="isProfileOpen" class="absolute right-0 top-full mt-4 w-64 bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden z-50">
                                    <div class="p-6 bg-slate-50/50 border-b border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Authenticated as</p>
                                        <p class="text-xs font-black text-slate-800 uppercase italic">{{ $page.props.auth?.user?.name }}</p>
                                    </div>
                                    <div class="p-4 space-y-1">
                                        <Link href="/admin/settings" class="flex items-center px-4 py-3 text-slate-600 hover:bg-green-50 hover:text-[#00a651] rounded-xl transition-all group">
                                            <Settings class="w-4 h-4 mr-3 text-slate-400 group-hover:text-[#00a651]" />
                                            <span class="text-[11px] font-bold uppercase tracking-wider">Admin Setup</span>
                                        </Link>
                                        <Link href="/admin/settings" class="flex items-center px-4 py-3 text-slate-600 hover:bg-green-50 hover:text-[#00a651] rounded-xl transition-all group">
                                            <Key class="w-4 h-4 mr-3 text-slate-400 group-hover:text-[#00a651]" />
                                            <span class="text-[11px] font-bold uppercase tracking-wider">Change Password</span>
                                        </Link>
                                        <div class="h-[1px] bg-slate-100 mx-4 my-2"></div>
                                        <Link href="/admin/logout" method="post" as="button" class="flex items-center w-full px-4 py-3 text-slate-600 hover:bg-red-50 hover:text-red-500 rounded-xl transition-all group">
                                            <LogOut class="w-4 h-4 mr-3 text-slate-400 group-hover:text-red-500" />
                                            <span class="text-[11px] font-bold uppercase tracking-wider">Sign Out</span>
                                        </Link>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content (Standardizes spacing and background) -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-10 custom-scrollbar relative">
                <!-- Subtle Gradient Overlay -->
                <div class="absolute top-0 left-0 right-0 h-40 bg-gradient-to-b from-white to-transparent pointer-events-none z-0 opacity-50"></div>
                <div class="relative z-10 max-w-[1600px] mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { LayoutDashboard, ShoppingCart, Package, Tags, LogOut, Search, ExternalLink, ListTree, Users, Settings, Menu, Zap, Star, Image, FileText, Award, MessageSquare, Truck, ShieldCheck, Key, Bell, Percent, Monitor } from 'lucide-vue-next';

const isSidebarOpen = ref(false);
const isProfileOpen = ref(false);

const toggleProfile = () => {
    isProfileOpen.value = !isProfileOpen.value;
};

// Close dropdown on click outside (simplified for inline)
const closeProfile = () => {
    isProfileOpen.value = false;
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
