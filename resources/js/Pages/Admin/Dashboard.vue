<template>
    <AdminLayout>
        <!-- Dashboard Header -->
        <div
            class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-5 rounded-xl shadow-sm border border-slate-100"
        >
            <div>
                <h1 class="text-xl font-bold text-slate-800">
                    {{ $t("Strategic Overview") }}
                </h1>
                <p class="text-xs text-slate-400 mt-0.5 flex items-center">
                    <span
                        class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"
                    ></span>
                    Real-time Performance Metrics & Insights
                </p>
            </div>
            <div
                class="mt-2 sm:mt-0 bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center"
            >
                <span
                    class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"
                ></span>
                {{ $t("Operational Status") }}: Active
            </div>
        </div>

        <!-- Quick Metrics Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div
                v-for="card in [
                    {
                        label: 'Total Revenue',
                        value:
                            '৳' +
                            parseFloat(
                                stats.totalRevenue || 0,
                            ).toLocaleString(),
                        icon: TrendingUp,
                        color: 'text-green-600',
                        bg: 'bg-green-50',
                    },
                    {
                        label: 'Net Profit',
                        value:
                            '৳' +
                            parseFloat(stats.totalProfit || 0).toLocaleString(),
                        icon: CircleDollarSign,
                        color: 'text-emerald-600',
                        bg: 'bg-emerald-50',
                    },
                    {
                        label: 'Total Orders',
                        value: stats.totalOrders,
                        icon: ShoppingCart,
                        color: 'text-slate-600',
                        bg: 'bg-slate-50',
                    },
                    {
                        label: 'Inventory Count',
                        value: stats.totalProducts,
                        icon: Package,
                        color: 'text-slate-600',
                        bg: 'bg-slate-50',
                    },
                    {
                        label: 'Total Customers',
                        value: stats.totalCustomers,
                        icon: Users,
                        color: 'text-slate-600',
                        bg: 'bg-slate-50',
                    },
                ]"
                :key="card.label"
                class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 flex items-center gap-3"
            >
                <div
                    :class="[card.bg, card.color]"
                    class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
                >
                    <component :is="card.icon" class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <span
                        class="block text-[11px] font-medium text-slate-400 truncate"
                        >{{ $t(card.label) }}</span
                    >
                    <span
                        class="block text-base font-bold text-slate-800 truncate mt-0.5"
                        >{{ card.value }}</span
                    >
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div
            class="mb-6 bg-white rounded-xl p-5 shadow-sm border border-slate-100"
        >
            <div class="flex items-center space-x-2 mb-6">
                <div class="w-1 h-4 bg-green-600 rounded"></div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">
                        {{ $t("Revenue & Profit Matrix") }}
                    </h3>
                    <p class="text-xs text-slate-400">
                        {{ $t("Last 6 Months Comparative Performance") }}
                    </p>
                </div>
            </div>

            <!-- Chart Bar Framework -->
            <div
                class="flex items-end h-40 gap-4 px-2 border-b border-slate-100 pb-2"
            >
                <div
                    v-for="month in monthlyStats"
                    :key="month.month"
                    class="flex-1 flex flex-col items-center group relative h-full justify-end"
                >
                    <div
                        class="w-full flex items-end justify-center h-full gap-1 max-w-[48px]"
                    >
                        <!-- Revenue Bar -->
                        <div
                            :style="{
                                height:
                                    (month.revenue / maxRevenue) * 100 + '%',
                            }"
                            class="w-full bg-slate-100 border border-slate-200/40 rounded-t transition-all"
                        ></div>
                        <!-- Profit Bar -->
                        <div
                            :style="{
                                height: (month.profit / maxRevenue) * 100 + '%',
                            }"
                            class="w-full bg-green-600 rounded-t transition-all relative"
                        >
                            <!-- Tooltip -->
                            <div
                                class="opacity-0 group-hover:opacity-100 absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white px-2 py-1 rounded shadow text-[10px] transition-all pointer-events-none z-10 whitespace-nowrap"
                            >
                                ৳{{ month.profit.toLocaleString() }}
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 mt-2">{{
                        month.month
                    }}</span>
                </div>
            </div>

            <!-- Legend Indicators -->
            <div
                class="mt-4 flex items-center justify-center gap-6 text-xs text-slate-500"
            >
                <div class="flex items-center gap-1.5">
                    <div
                        class="w-3 h-3 bg-slate-100 border border-slate-200 rounded"
                    ></div>
                    <span>{{ $t("Gross Revenue") }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 bg-green-600 rounded"></div>
                    <span>{{ $t("Net Profit") }}</span>
                </div>
            </div>
        </div>

        <!-- Orders & Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Orders Matrix -->
            <div
                class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-slate-100 flex justify-between items-center"
                >
                    <h3 class="font-bold text-slate-800 text-sm">
                        {{ $t("Recent Transactions") }}
                    </h3>
                    <Link
                        href="/admin/orders"
                        class="text-xs font-semibold text-green-600 hover:underline"
                        >{{ $t("Explore All") }} →</Link
                    >
                </div>

                <div class="overflow-x-auto">
                    <div
                        v-if="!recentOrders || recentOrders.length === 0"
                        class="py-12 text-center text-slate-400 text-xs"
                    >
                        <ShoppingCart
                            class="w-8 h-8 text-slate-200 mx-auto mb-2"
                        />
                        {{ $t("Marketplace Dormant") }}
                    </div>
                    <table v-else class="w-full text-left text-xs">
                        <thead>
                            <tr
                                class="bg-slate-50 text-slate-400 font-semibold border-b border-slate-100"
                            >
                                <th class="py-2.5 px-4">
                                    {{ $t("Reference") }}
                                </th>
                                <th class="py-2.5 px-4">{{ $t("Entity") }}</th>
                                <th class="py-2.5 px-4">{{ $t("Total") }}</th>
                                <th class="py-2.5 px-4 text-right">
                                    {{ $t("Status") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="order in recentOrders"
                                :key="order.id"
                                class="hover:bg-slate-50/50 transition-colors"
                            >
                                <td
                                    class="py-3 px-4 font-semibold text-slate-900"
                                >
                                    ORD-{{ String(order.id).padStart(5, "0") }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-800">
                                        {{ order.customer_name }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        {{
                                            order.customer_email ||
                                            order.customer_phone
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="py-3 px-4 font-semibold text-slate-900"
                                >
                                    ৳{{
                                        parseFloat(
                                            order.total_amount,
                                        ).toLocaleString()
                                    }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <span
                                        :class="getStatusClass(order.status)"
                                        class="text-[10px] font-semibold px-2.5 py-0.5 rounded-full border"
                                    >
                                        {{ order.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sidemenu Metrics Setup -->
            <div class="space-y-6">
                <!-- Presence Tracker Container -->
                <div
                    class="bg-white rounded-xl p-5 shadow-sm border border-slate-100 flex flex-col items-center"
                >
                    <div
                        class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-3"
                    >
                        <ShoppingBag class="w-6 h-6 text-green-600" />
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">
                        {{ $t("Market Presence") }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $t("Live Customer Interaction") }}
                    </p>

                    <div class="w-full space-y-2 mt-4 text-xs">
                        <div
                            class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100"
                        >
                            <span class="text-slate-500 font-medium">{{
                                $t("Live Sessions")
                            }}</span>
                            <span class="font-bold text-slate-800">24</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100"
                        >
                            <span class="text-slate-500 font-medium">{{
                                $t("Pending Carts")
                            }}</span>
                            <span class="font-bold text-green-600">156</span>
                        </div>
                    </div>
                </div>

                <!-- Command Terminal Log Box -->
                <div class="bg-slate-900 rounded-xl p-4 text-white">
                    <div
                        class="flex items-center space-x-2 mb-3 pb-2 border-b border-slate-800"
                    >
                        <div
                            class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"
                        ></div>
                        <h4
                            class="text-[10px] uppercase font-mono text-slate-400 tracking-wider"
                        >
                            {{ $t("Command Shell Output") }}
                        </h4>
                    </div>
                    <div class="space-y-2 font-mono text-[11px] text-slate-300">
                        <div class="flex gap-2">
                            <span class="text-green-500">❯</span>
                            <p>{{ $t("Inventory Sync Completed") }}</p>
                        </div>
                        <div class="flex gap-2 text-slate-500">
                            <span class="text-green-600">❯</span>
                            <p>{{ $t("Mail Servers Responsive") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    TrendingUp,
    ShoppingCart,
    Package,
    Users,
    ShoppingBag,
    CircleDollarSign,
} from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({
    stats: Object,
    monthlyStats: Array,
    recentOrders: Array,
});

const maxRevenue = computed(() => {
    const max = Math.max(...props.monthlyStats.map((s) => s.revenue), 1000);
    return max * 1.15; // Standard 15% headroom allocation
});

const getStatusClass = (status) => {
    switch (status) {
        case "pending":
            return "bg-yellow-50 text-yellow-700 border-yellow-200/50";
        case "processing":
            return "bg-blue-50 text-blue-700 border-blue-200/50";
        case "completed":
            return "bg-green-50 text-green-700 border-green-200/50";
        case "cancelled":
            return "bg-red-50 text-red-700 border-red-200/50";
        default:
            return "bg-slate-50 text-slate-600 border-slate-200/50";
    }
};
</script>
