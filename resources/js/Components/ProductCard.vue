<template>
    <div
        class="group bg-white rounded-2xl border border-slate-100 hover:shadow-xl transition-all duration-300 flex flex-col h-full relative overflow-hidden w-full"
    >
        <!-- Badges -->
        <div
            class="absolute top-3 left-3 z-20 flex flex-col gap-1.5 pointer-events-none"
        >
            <span
                v-if="product.discount_type"
                class="px-2.5 py-1 bg-[#6e2582] text-white text-[10px] font-black uppercase tracking-wider rounded-lg shadow-md"
            >
                {{
                    product.discount_type === "percentage"
                        ? `${product.discount_value}% OFF`
                        : `Save: ৳${parseFloat(product.discount_value).toLocaleString()}`
                }}
            </span>
            <span
                v-if="product.stock <= 0"
                class="px-2.5 py-1 bg-slate-950/80 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider rounded-lg"
            >
                Sold Out
            </span>
        </div>

        <!-- Quick Action / Quick View -->
        <button
            @click.stop="$emit('quickView', product)"
            class="absolute top-3 right-3 z-20 w-9 h-9 bg-white/90 backdrop-blur rounded-xl flex items-center justify-center text-slate-700 hover:text-white hover:bg-emerald-600 shadow-sm border border-slate-100 md:opacity-0 md:translate-y-[-4px] group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300"
            title="Quick View"
        >
            <Scan class="w-4 h-4" />
        </button>

        <!-- Image Area: Complete Bleed No Space -->
        <div
            class="relative w-full aspect-square overflow-hidden bg-slate-50 shrink-0"
        >
            <Link
                :href="`/products/${product.slug}`"
                class="block w-full h-full"
            >
                <img
                    :src="
                        product.image ||
                        `https://placehold.co/800x800/f8fafc/64748b?text=${encodeURIComponent(product.name)}`
                    "
                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out"
                    :alt="product.name"
                />
            </Link>
        </div>

        <!-- Simplified Content Block -->
        <div class="p-4 flex flex-grow flex-col justify-between">
            <div>
                <!-- Clean Title Focused Area -->
                <Link :href="`/products/${product.slug}`" class="block mb-2">
                    <h3
                        class="text-sm md:text-base font-bold text-slate-800 leading-snug hover:text-emerald-600 transition-colors line-clamp-2 tracking-tight"
                    >
                        {{ product.name }}
                    </h3>
                </Link>

                <!-- Price Panel -->
                <div class="flex items-baseline gap-2 mb-4">
                    <span
                        class="text-base md:text-lg font-black text-[#ef4823] tracking-tight"
                    >
                        ৳{{ finalPrice.toLocaleString() }}
                    </span>
                    <span
                        v-if="product.discount_type"
                        class="text-xs text-slate-400 line-through font-medium"
                    >
                        ৳{{ parseFloat(product.price).toLocaleString() }}
                    </span>
                </div>
            </div>

            <!-- Actions Layer -->
            <div class="flex gap-2 w-full mt-auto">
                <button
                    @click.stop="handleAddToCart"
                    :disabled="product.stock <= 0"
                    class="w-11 h-11 bg-slate-50 text-slate-600 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center justify-center disabled:opacity-40 border border-slate-200/60 shrink-0"
                    title="Add to Cart"
                >
                    <ShoppingCart class="w-4 h-4" />
                </button>
                <button
                    @click.stop="handleBuyNow"
                    :disabled="product.stock <= 0"
                    class="flex-1 h-11 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors flex items-center justify-center gap-1.5 text-xs font-black uppercase tracking-wider disabled:bg-slate-200 disabled:text-slate-400"
                >
                    <span>{{
                        product.stock > 0 ? "Buy Now" : "Out of Stock"
                    }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { ShoppingCart, Scan } from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";

const props = defineProps({
    product: Object,
});

defineEmits(["quickView"]);

const { addToCart } = useCart();

const finalPrice = computed(() => {
    let price = parseFloat(props.product.price);
    if (!price) return 0;
    if (props.product.discount_type === "percentage") {
        price = price * (1 - props.product.discount_value / 100);
    } else if (props.product.discount_type === "flat") {
        price = Math.max(0, price - props.product.discount_value);
    }
    return price;
});

const handleAddToCart = () => {
    addToCart(props.product, 1);
};

const handleBuyNow = () => {
    addToCart(props.product, 1);
    router.visit("/checkout");
};
</script>
