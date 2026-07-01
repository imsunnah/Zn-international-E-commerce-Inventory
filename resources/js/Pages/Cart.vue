<template>
    <StoreLayout>
        <div
            class="bg-slate-50 min-h-screen py-12 md:py-16 font-sans antialiased text-slate-900"
        >
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div
                    class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6"
                >
                    <div>
                        <h1
                            class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900"
                        >
                            {{ $t("Shopping") }}
                            <span class="text-emerald-600">{{
                                $t("Cart")
                            }}</span>
                        </h1>
                        <p class="text-slate-500 text-xs mt-1">
                            {{ $t("Review your selection before checkout") }}
                        </p>
                    </div>

                    <!-- Select All Toggle -->
                    <div
                        v-if="cart.length > 0"
                        class="flex items-center gap-2 self-start sm:self-center"
                    >
                        <label
                            class="relative flex items-center cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                :checked="isAllSelected"
                                @change="toggleSelectAll"
                                class="sr-only peer"
                            />
                            <div
                                class="w-5 h-5 border border-slate-300 rounded-md peer-checked:bg-emerald-600 peer-checked:border-emerald-600 transition-all flex items-center justify-center bg-white shadow-sm"
                            >
                                <Check
                                    v-if="isAllSelected"
                                    class="w-3.5 h-3.5 text-white stroke-[3]"
                                />
                            </div>
                        </label>
                        <span class="text-xs font-medium text-slate-600">{{
                            $t("Select All Items")
                        }}</span>
                    </div>
                </div>

                <!-- Loading State -->
                <div
                    v-if="isLoading && cart.length === 0"
                    class="flex justify-center py-20"
                >
                    <div
                        class="animate-spin rounded-full h-10 w-10 border-2 border-slate-900 border-t-transparent"
                    ></div>
                </div>

                <!-- Main Content -->
                <div
                    v-else
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
                >
                    <!-- Cart Items Column -->
                    <div class="lg:col-span-8">
                        <div
                            v-if="cart.length > 0"
                            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                        >
                            <ul role="list" class="divide-y divide-slate-100">
                                <li
                                    v-for="item in cart"
                                    :key="item.id"
                                    class="flex p-5 sm:p-6 transition-colors hover:bg-slate-50/50 items-center"
                                >
                                    <!-- Selection Checkbox -->
                                    <div class="mr-4 flex-shrink-0">
                                        <label
                                            class="relative flex items-center cursor-pointer"
                                        >
                                            <input
                                                type="checkbox"
                                                :checked="
                                                    selectedItems.includes(
                                                        item.id,
                                                    )
                                                "
                                                @change="
                                                    toggleItemSelection(item.id)
                                                "
                                                class="sr-only peer"
                                            />
                                            <div
                                                class="w-5 h-5 border border-slate-300 rounded-md peer-checked:bg-emerald-600 peer-checked:border-emerald-600 transition-all flex items-center justify-center bg-white shadow-sm"
                                            >
                                                <Check
                                                    v-if="
                                                        selectedItems.includes(
                                                            item.id,
                                                        )
                                                    "
                                                    class="w-3.5 h-3.5 text-white stroke-[3]"
                                                />
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Product Image -->
                                    <div
                                        class="flex-shrink-0 w-20 h-20 sm:w-28 sm:h-28 bg-slate-50 border border-slate-100 rounded-xl overflow-hidden relative"
                                    >
                                        <img
                                            v-if="
                                                item.product?.image ||
                                                item.combo?.image
                                            "
                                            :src="
                                                item.product?.image ||
                                                item.combo?.image
                                            "
                                            :alt="
                                                item.product?.name ||
                                                item.combo?.name
                                            "
                                            class="w-full h-full object-center object-cover"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-full flex items-center justify-center text-slate-300"
                                        >
                                            <Package class="w-8 h-8" />
                                        </div>
                                    </div>

                                    <!-- Details & Controls -->
                                    <div
                                        class="ml-4 sm:ml-6 flex-1 flex flex-col self-stretch justify-between"
                                    >
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-start justify-between gap-1"
                                        >
                                            <div>
                                                <span
                                                    v-if="item.combo_id"
                                                    class="inline-block text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md mb-1"
                                                    >{{
                                                        $t("Combo Bundle")
                                                    }}</span
                                                >
                                                <h3
                                                    class="text-sm font-semibold text-slate-900 line-clamp-2 max-w-md"
                                                >
                                                    <Link
                                                        :href="
                                                            item.product_id
                                                                ? `/products/${item.product?.slug}`
                                                                : `/combos/${item.combo?.slug}`
                                                        "
                                                        class="hover:text-emerald-600 transition-colors"
                                                    >
                                                        {{
                                                            item.product
                                                                ?.name ||
                                                            item.combo?.name
                                                        }}
                                                    </Link>
                                                </h3>
                                                <p
                                                    class="text-xs text-slate-400 mt-0.5 font-mono"
                                                >
                                                    {{
                                                        item.product?.sku ||
                                                        "BUNDLE-" +
                                                            item.combo?.id
                                                    }}
                                                </p>
                                            </div>
                                            <p
                                                class="text-base font-bold text-slate-900 sm:text-right"
                                            >
                                                ৳{{
                                                    getItemPrice(
                                                        item,
                                                    ).toLocaleString()
                                                }}
                                            </p>
                                        </div>

                                        <!-- Actions Row -->
                                        <div
                                            class="mt-4 flex items-center justify-between"
                                        >
                                            <!-- Quantity Selector -->
                                            <div
                                                class="flex items-center bg-slate-100 p-1 rounded-lg border border-slate-200"
                                            >
                                                <button
                                                    @click="
                                                        updateQuantity(
                                                            item.id,
                                                            item.quantity - 1,
                                                        )
                                                    "
                                                    class="w-7 h-7 flex items-center justify-center bg-white rounded-md shadow-sm text-slate-600 hover:text-slate-900 disabled:opacity-50 transition-colors"
                                                    :disabled="
                                                        item.quantity <= 1
                                                    "
                                                >
                                                    <Minus
                                                        class="w-3.5 h-3.5"
                                                    />
                                                </button>
                                                <span
                                                    class="text-xs font-semibold w-8 text-center text-slate-800"
                                                    >{{ item.quantity }}</span
                                                >
                                                <button
                                                    @click="
                                                        updateQuantity(
                                                            item.id,
                                                            item.quantity + 1,
                                                        )
                                                    "
                                                    class="w-7 h-7 flex items-center justify-center bg-white rounded-md shadow-sm text-slate-600 hover:text-slate-900 transition-colors"
                                                >
                                                    <Plus class="w-3.5 h-3.5" />
                                                </button>
                                            </div>

                                            <!-- Delete Button -->
                                            <button
                                                type="button"
                                                @click="removeFromCart(item.id)"
                                                class="text-xs font-medium text-slate-400 hover:text-rose-600 flex items-center transition-colors px-2 py-1 rounded-md hover:bg-rose-50"
                                            >
                                                <Trash2
                                                    class="w-4 h-4 mr-1.5"
                                                />
                                                {{ $t("Remove") }}
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Empty Cart State -->
                        <div
                            v-else
                            class="bg-white rounded-2xl border border-slate-200 p-12 text-center flex flex-col items-center shadow-sm"
                        >
                            <div
                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100"
                            >
                                <ShoppingCart class="w-6 h-6 text-slate-400" />
                            </div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                {{ $t("Your cart is empty") }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $t("Explore our catalog to add items") }}
                            </p>
                            <Link
                                href="/shop"
                                class="mt-6 inline-flex items-center px-6 py-2.5 bg-slate-900 text-white text-xs font-medium rounded-xl hover:bg-slate-800 transition-colors shadow-sm shadow-slate-900/10"
                            >
                                {{ $t("Continue Shopping") }}
                            </Link>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-4" v-if="cart.length > 0">
                        <div
                            class="bg-white rounded-2xl p-6 lg:p-8 sticky top-24 border border-slate-200 shadow-sm"
                        >
                            <h2
                                class="text-sm font-semibold mb-5 border-b border-slate-100 pb-3 text-slate-900"
                            >
                                {{ $t("Order Summary") }}
                            </h2>

                            <dl class="space-y-3">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <dt class="text-slate-500">
                                        {{ $t("Subtotal") }}
                                    </dt>
                                    <dd class="font-medium text-slate-900">
                                        ৳{{ selectedTotal.toLocaleString() }}
                                    </dd>
                                </div>
                                <div
                                    class="flex items-center justify-between text-xs border-t border-slate-100 pt-3"
                                >
                                    <dt class="flex flex-col gap-0.5">
                                        <span
                                            class="text-slate-500 font-medium"
                                            >{{ $t("Items Count") }}</span
                                        >
                                        <span
                                            class="text-emerald-600 font-medium"
                                            >{{ selectedItems.length }}
                                            {{ $t("selected") }}</span
                                        >
                                    </dt>
                                    <dd class="text-slate-500 font-medium">
                                        {{ selectedItems.length }}
                                        {{ $t("of") }} {{ cart.length }}
                                    </dd>
                                </div>
                                <div
                                    class="flex items-center justify-between border-t border-slate-200 pt-4 mt-2"
                                >
                                    <dt
                                        class="text-sm font-bold text-slate-900"
                                    >
                                        {{ $t("Total") }}
                                    </dt>
                                    <dd
                                        class="text-xl font-bold text-emerald-600"
                                    >
                                        ৳{{ selectedTotal.toLocaleString() }}
                                    </dd>
                                </div>
                            </dl>

                            <div class="mt-6">
                                <button
                                    @click="handleCheckout"
                                    :disabled="selectedItems.length === 0"
                                    class="w-full flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-slate-800 transition-colors disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-slate-900"
                                >
                                    {{ $t("Confirm Order") }}
                                </button>
                                <p
                                    v-if="selectedItems.length === 0"
                                    class="text-center text-[11px] text-slate-400 mt-3"
                                >
                                    {{ $t("Select items to continue") }}
                                </p>
                            </div>
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
    Trash2,
    ShoppingCart,
    Plus,
    Minus,
    Check,
} from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";

const { cart, removeFromCart, updateQuantity, isLoading } = useCart();

const selectedItems = ref([]);

// Sync selection when cart updates initially or changes asynchronously
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

const toggleSelectAll = () => {
    if (isAllSelected.value) {
        selectedItems.value = [];
    } else {
        selectedItems.value = cart.value.map((item) => item.id);
    }
};

const toggleItemSelection = (id) => {
    const index = selectedItems.value.indexOf(id);
    if (index > -1) {
        selectedItems.value.splice(index, 1);
    } else {
        selectedItems.value.push(id);
    }
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
