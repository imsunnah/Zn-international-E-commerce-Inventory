<template>
    <AdminLayout>
        <!-- Dashboard Header Card -->
        <div class="mb-10 flex flex-col md:flex-row items-center justify-between bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-200/60 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase italic">{{ $t('Strategic Overview') }}</h1>
                <p class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-[0.2em] flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                    Real-time Performance Metrics & Insight
                </p>
            </div>
            <div class="mt-6 md:mt-0 flex items-center gap-6 relative z-10">
                <div class="flex flex-col text-right">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Operational Status') }}</span>
                    <span class="text-xs font-black text-green-500 uppercase tracking-widest flex items-center justify-end mt-1.5 bg-green-50 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        Active
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">
            <div v-for="card in [
                { label: 'Total Revenue', value: '৳' + parseFloat(stats.totalRevenue || 0).toLocaleString(), icon: TrendingUp, color: 'text-[#00a651]', bg: 'bg-green-50' },
                { label: 'Net Profit', value: '৳' + parseFloat(stats.totalProfit || 0).toLocaleString(), icon: CircleDollarSign, color: 'text-green-600', bg: 'bg-green-50' },
                { label: 'Total Orders', value: stats.totalOrders, icon: ShoppingCart, color: 'text-slate-700', bg: 'bg-slate-50' },
                { label: 'Inventory Count', value: stats.totalProducts, icon: Package, color: 'text-slate-700', bg: 'bg-slate-50' },
                { label: 'Total Customers', value: stats.totalCustomers, icon: Users, color: 'text-slate-700', bg: 'bg-slate-50' }
            ]" :key="card.label" class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-200/60 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="flex flex-col">
                    <div :class="[card.bg, card.color]" class="w-12 h-12 rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                        <component :is="card.icon" class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ $t(card.label) }}</span>
                    <span class="text-2xl font-black text-slate-900 italic tracking-tighter">{{ card.value }}</span>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="mb-12 bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-200/60">
            <div class="flex items-center justify-between mb-12">
                <div class="flex items-center space-x-4">
                    <div class="w-2 h-8 bg-[#00a651] rounded-full"></div>
                    <div>
                        <h3 class="font-black text-slate-800 uppercase tracking-wider text-sm">{{ $t('Revenue & Profit Matrix') }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $t('Last 6 Months Comparative Performance') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-end justify-between h-56 gap-6 px-4">
                <div v-for="month in monthlyStats" :key="month.month" class="flex-1 flex flex-col items-center group relative">
                    <div class="relative w-full flex flex-col items-center justify-end h-full gap-1">
                        <!-- Revenue Bar -->
                        <div 
                            :style="{ height: (month.revenue / maxRevenue * 100) + '%' }" 
                            class="w-12 bg-slate-50 border border-slate-100 rounded-t-xl transition-all duration-300 group-hover:bg-slate-100 group-hover:border-slate-200"
                        ></div>
                        <!-- Profit Bar (Overlaying) -->
                        <div 
                            :style="{ height: (month.profit / maxRevenue * 100) + '%' }" 
                            class="w-12 bg-[#00a651] rounded-t-xl absolute bottom-0 transition-all duration-300 group-hover:bg-[#008541] shadow-lg shadow-green-500/10"
                        >
                            <!-- Tooltip -->
                            <div class="opacity-0 group-hover:opacity-100 absolute -top-12 left-1/2 -translate-x-1/2 bg-slate-900 text-white p-3 rounded-2xl shadow-xl transition-all pointer-events-none z-20 scale-90 group-hover:scale-100">
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Profit Analysis</div>
                                <div class="text-xs font-black text-[#00a651]">৳{{ month.profit.toLocaleString() }}</div>
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-6">{{ month.month }}</span>
                </div>
            </div>
            
            <!-- Legend -->
            <div class="mt-12 flex items-center justify-center gap-10">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 bg-slate-50 border border-slate-200 rounded-lg"></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Gross Revenue') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 bg-[#00a651] rounded-lg shadow-sm shadow-green-500/20"></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Net Profit') }}</span>
                </div>
            </div>
        </div>

        <!-- Orders & Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
            <!-- Recent Orders -->
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="px-8 py-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-6 bg-slate-800 rounded-full"></div>
                        <h3 class="font-black text-slate-800 uppercase tracking-wider text-sm italic">{{ $t('Recent Transactions') }}</h3>
                    </div>
                    <Link href="/admin/orders" class="text-[10px] font-black text-[#00a651] uppercase tracking-[0.2em] hover:underline">{{ $t('Explore All') }} →</Link>
                </div>
                <div class="overflow-x-auto">
                    <div v-if="!recentOrders || recentOrders.length === 0" class="py-24 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-100">
                            <ShoppingCart class="w-6 h-6 text-slate-300" />
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('Marketplace Dormant') }}</p>
                    </div>
                    <table v-else class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] border-b border-slate-100">
                                <th class="py-6 px-8">{{ $t('Reference') }}</th>
                                <th class="py-6 px-8">{{ $t('Entity') }}</th>
                                <th class="py-6 px-8">{{ $t('Total') }}</th>
                                <th class="py-6 px-8 text-right">{{ $t('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-5 px-8">
                                    <span class="text-xs font-black text-slate-900 italic tracking-tight">ORD-{{ String(order.id).padStart(5, '0') }}</span>
                                </td>
                                <td class="py-5 px-8">
                                    <div class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ order.customer_name }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">{{ order.customer_email || order.customer_phone }}</div>
                                </td>
                                <td class="py-5 px-8 text-xs font-black text-slate-900 italic">৳{{ parseFloat(order.total_amount).toLocaleString() }}</td>
                                <td class="py-5 px-8 text-right">
                                    <span :class="getStatusClass(order.status)" class="text-[9px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-sm">
                                        {{ order.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Side Cards -->
            <div class="space-y-8">
                <!-- Presence Tracker -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-200/60 flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-[#00a651]/5 rounded-3xl flex items-center justify-center mb-8 border border-green-100 shadow-inner group transition-all">
                        <ShoppingBag class="w-9 h-9 text-[#00a651] group-hover:scale-110 transition-transform" />
                    </div>
                    <h3 class="font-black text-slate-800 uppercase tracking-wider mb-3 text-lg italic">{{ $t('Market Presence') }}</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] leading-loose mb-10">{{ $t('Live Customer Interaction') }}</p>
                    
                    <div class="w-full space-y-4">
                        <div class="flex justify-between items-center p-5 bg-slate-50/50 rounded-2xl border border-slate-100 group hover:border-green-200 transition-all">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('Live Sessions') }}</span>
                            <span class="text-sm font-black text-slate-900 italic">24</span>
                        </div>
                        <div class="flex justify-between items-center p-5 bg-slate-50/50 rounded-2xl border border-slate-100 group hover:border-green-200 transition-all">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('Pending Carts') }}</span>
                            <span class="text-sm font-black text-[#00a651] italic">156</span>
                        </div>
                    </div>
                </div>

                <!-- Terminal Insight -->
                <div class="bg-slate-900 rounded-[2.5rem] p-10 shadow-2xl text-white relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-green-500/10 blur-3xl rounded-full"></div>
                    <div class="flex items-center space-x-3 mb-8 relative z-10 border-b border-white/5 pb-4">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">{{ $t('Command Shell Output') }}</h3>
                    </div>
                    <div class="space-y-6 relative z-10 font-mono">
                        <div class="flex items-start space-x-4 group">
                            <div class="text-[#00a651] font-bold text-xs mt-0.5">❯</div>
                            <p class="text-[10px] font-bold text-slate-300 leading-relaxed uppercase tracking-widest transition-colors group-hover:text-green-400">{{ $t('Inventory Sync Completed') }}</p>
                        </div>
                        <div class="flex items-start space-x-4 group">
                            <div class="text-[#00a651] font-bold text-xs mt-0.5">❯</div>
                            <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase tracking-widest transition-colors group-hover:text-green-400">{{ $t('Mail Servers Responsive') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { TrendingUp, ShoppingCart, Package, Users, ShoppingBag, CheckCircle, XCircle, CircleDollarSign } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    monthlyStats: Array,
    recentOrders: Array
});

const maxRevenue = computed(() => {
    const max = Math.max(...props.monthlyStats.map(s => s.revenue), 1000);
    return max * 1.2; // Add 20% headroom
});

const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-50 text-yellow-600 border-yellow-100';
        case 'processing': return 'bg-blue-50 text-blue-600 border-blue-100';
        case 'completed': return 'bg-green-50 text-green-600 border-green-100';
        case 'cancelled': return 'bg-red-50 text-red-600 border-red-100';
        default: return 'bg-slate-50 text-slate-600 border-slate-100';
    }
};
</script>
