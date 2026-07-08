<template>
    <StoreLayout>
        <div
            class="bg-white min-h-screen py-10 font-sans antialiased text-slate-800 selection:bg-[#00a651] selection:text-white"
        >
            <div class="max-w-[1300px] mx-auto px-4 md:px-8 xl:px-12">
                <!-- Breadcrumbs & Header -->
                <div class="mb-8">
                    <nav
                        class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-3"
                    >
                        <Link
                            href="/"
                            class="hover:text-[#00a651] transition-colors decoration-transparent"
                            >Home</Link
                        >
                        <ChevronRight class="w-3.5 h-3.5 text-slate-300" />
                        <span class="text-slate-800 font-bold">Shopping Cart</span>
                    </nav>

                    <h1 class="text-xl md:text-2xl font-black text-slate-900 uppercase">
                        Shopping Cart
                    </h1>
                </div>

                <!-- Empty Cart State -->
                <div
                    v-if="cart.length === 0 && !isLoading"
                    class="bg-white rounded border border-slate-200 p-12 text-center flex flex-col items-center shadow-sm"
                >
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100"
                    >
                        <ShoppingCart class="w-6 h-6 text-slate-400" />
                    </div>
                    <h2 class="text-base font-black text-slate-900 uppercase">
                        Your cart is empty
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Explore our catalog to add items and place orders.
                    </p>
                    <Link
                        href="/shop"
                        class="mt-6 inline-flex items-center px-6 py-2.5 bg-[#ef4823] hover:bg-[#d83616] text-white text-xs font-bold uppercase rounded shadow-sm transition-colors decoration-transparent"
                    >
                        Continue Shopping
                    </Link>
                </div>

                <!-- Main Content -->
                <div
                    v-else
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
                >
                    <!-- Cart Items Column -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden p-6">
                            <!-- Table Header -->
                            <div class="hidden md:grid grid-cols-12 text-[10px] font-black uppercase text-slate-400 tracking-wider pb-3 border-b border-slate-100">
                                <div class="col-span-1">Delete</div>
                                <div class="col-span-2">Image</div>
                                <div class="col-span-5">Product</div>
                                <div class="col-span-1 text-right">Price</div>
                                <div class="col-span-2 text-center">Quantity</div>
                                <div class="col-span-1 text-right">Total</div>
                            </div>

                            <ul role="list" class="divide-y divide-slate-100 p-0 m-0">
                                <li
                                    v-for="item in cart"
                                    :key="item.id"
                                    class="grid grid-cols-1 md:grid-cols-12 gap-4 py-6 items-center text-xs text-slate-500 font-bold"
                                >
                                    <!-- Delete Column -->
                                    <div class="col-span-1 flex md:justify-start items-center">
                                        <button
                                            type="button"
                                            @click="removeFromCart(item.id)"
                                            class="w-6 h-6 rounded-full border border-slate-200 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-colors text-slate-400 cursor-pointer bg-white"
                                        >
                                            <X class="w-3.5 h-3.5" />
                                        </button>
                                    </div>

                                    <!-- Image Column -->
                                    <div class="col-span-2">
                                        <div class="w-20 h-20 bg-white border border-slate-150 rounded flex items-center justify-center p-1 overflow-hidden">
                                            <img
                                                v-if="item.product?.image || item.combo?.image"
                                                :src="item.product?.image || item.combo?.image"
                                                :alt="item.product?.name || item.combo?.name"
                                                class="max-h-full max-w-full object-contain"
                                            />
                                            <Package v-else class="w-8 h-8 text-slate-300" />
                                        </div>
                                    </div>

                                    <!-- Name Column -->
                                    <div class="col-span-5 space-y-1">
                                        <div class="flex items-center gap-1.5" v-if="item.combo_id">
                                            <span class="inline-block text-[9px] font-black bg-[#00a651] text-white px-2 py-0.5 rounded uppercase tracking-wider">Combo Bundle</span>
                                        </div>
                                        <Link
                                            :href="item.product_id ? `/products/${item.product?.slug}` : `/combos/${item.combo?.slug}`"
                                            class="hover:text-[#00a651] text-slate-900 transition-colors text-xs font-black line-clamp-2 decoration-transparent leading-tight"
                                        >
                                            {{ item.product?.name || item.combo?.name }}
                                        </Link>
                                        <p class="text-[10px] text-slate-400 font-mono">
                                            SKU: {{ item.product?.sku || 'BUNDLE-' + item.combo?.id }}
                                        </p>
                                    </div>

                                    <!-- Price Column -->
                                    <div class="col-span-1 md:text-right text-slate-900 font-black">
                                        ৳{{ getItemPrice(item).toLocaleString() }}
                                    </div>

                                    <!-- Quantity Column -->
                                    <div class="col-span-2 flex justify-center">
                                        <div class="flex items-center bg-slate-100 p-0.5 rounded border border-slate-205">
                                            <button
                                                @click="updateQuantity(item.id, item.quantity - 1)"
                                                class="w-6 h-6 flex items-center justify-center bg-white rounded border border-slate-150 shadow-sm text-slate-600 hover:text-slate-900 disabled:opacity-50 transition-colors cursor-pointer font-bold"
                                                :disabled="item.quantity <= 1"
                                            >
                                                <Minus class="w-3 h-3" />
                                            </button>
                                            <span class="text-xs font-bold w-7 text-center text-slate-800">{{ item.quantity }}</span>
                                            <button
                                                @click="updateQuantity(item.id, item.quantity + 1)"
                                                class="w-6 h-6 flex items-center justify-center bg-white rounded border border-slate-150 shadow-sm text-slate-655 hover:text-slate-900 transition-colors cursor-pointer font-bold"
                                            >
                                                <Plus class="w-3 h-3" />
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Total Column -->
                                    <div class="col-span-1 md:text-right text-[#00a651] font-black text-sm">
                                        ৳{{ (getItemPrice(item) * item.quantity).toLocaleString() }}
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <Link
                                href="/shop"
                                class="border border-[#ef4823] text-[#ef4823] hover:bg-[#ef4823] hover:text-white rounded py-2.5 px-6 font-black text-xs uppercase transition-colors duration-250 decoration-transparent inline-flex items-center gap-1.5 cursor-pointer"
                            >
                                <span class="text-base">&larr;</span> Continue Shopping
                            </Link>

                            <button
                                @click="resetCartSelection"
                                class="bg-white border border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651] rounded py-2.5 px-6 font-black text-xs uppercase transition-colors duration-250 cursor-pointer"
                            >
                                Select All ({{ cart.length }})
                            </button>
                        </div>
                    </div>

                    <!-- Cart Summary Sidebar -->
                    <div class="lg:col-span-4 space-y-6">
                        <!-- Coupon Code widget -->
                        <div class="bg-white rounded border border-slate-200 p-5 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-3">Coupon Discount</h3>
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    placeholder="Enter Coupon code"
                                    class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs outline-none focus:border-[#00a651] focus:bg-white font-bold"
                                />
                                <button class="bg-[#00a651] text-white px-4 py-2 text-xs font-black uppercase rounded hover:bg-[#008541] border-none cursor-pointer">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded border border-slate-200 p-6 shadow-sm">
                            <h3 class="text-xs font-black tracking-wider uppercase border-b border-slate-100 pb-3 mb-4 text-slate-900">
                                Cart Totals
                            </h3>

                            <dl class="space-y-3.5 mb-6">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <dt class="text-slate-500">Subtotal</dt>
                                    <dd class="text-slate-900 font-black">
                                        ৳{{ selectedTotal.toLocaleString() }}
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between text-xs font-bold border-t border-slate-100 pt-3">
                                    <dt class="text-slate-500">Selected Items</dt>
                                    <dd class="text-slate-900 font-black">
                                        {{ selectedItems.length }}
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-200 pt-4 text-xs font-black uppercase tracking-wider">
                                    <dt class="text-slate-900">Total</dt>
                                    <dd class="text-base text-[#00a651] font-black">
                                        ৳{{ selectedTotal.toLocaleString() }}
                                    </dd>
                                </div>
                            </dl>

                            <button
                                @click="handleCheckout"
                                :disabled="selectedItems.length === 0"
                                class="w-full py-3 bg-[#00a651] hover:bg-[#008541] text-white font-black text-xs uppercase tracking-wider rounded select-none border-none cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                            >
                                Proceed to Checkout
                            </button>
                            <p
                                v-if="selectedItems.length === 0"
                                class="text-center text-[10px] text-slate-400 font-bold mt-3"
                            >
                                Please select at least one item to continue
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import StoreLayout from "@/Layouts/StoreLayout.vue";
import {
    Package,
    Plus,
    Minus,
    X,
    ShoppingCart,
    ChevronRight,
} from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";

const { cart, removeFromCart, updateQuantity, isLoading } = useCart();

const selectedItems = ref([]);

watch(
    cart,
    (newCart) => {
        if (newCart.length > 0 && selectedItems.value.length === 0) {
            selectedItems.value = newCart.map((item) => item.id);
        }
    },
    { immediate: true },
);

const isAllSelected = computed(() => {
    return (
        cart.value.length > 0 &&
        selectedItems.value.length === cart.value.length
    );
});

const resetCartSelection = () => {
    selectedItems.value = cart.value.map((item) => item.id);
};

const getItemPrice = (item) => {
    if (item.product) {
        const price = parseFloat(item.product.price || 0);
        if (!item.product.discount_type) return price;
        if (item.product.discount_type === "percentage") {
            return (
                price * (1 - parseFloat(item.product.discount_value || 0) / 100)
            );
        }
        return Math.max(
            0,
            price - parseFloat(item.product.discount_value || 0),
        );
    }
    return parseFloat(item.combo?.price || 0);
};

const selectedTotal = computed(() => {
    return cart.value
        .filter((item) => selectedItems.value.includes(item.id))
        .reduce((total, item) => {
            return total + getItemPrice(item) * item.quantity;
        }, 0);
});

const handleCheckout = () => {
    if (selectedItems.value.length === 0) return;

    router.visit("/checkout", {
        data: { items: selectedItems.value.join(",") },
    });
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
