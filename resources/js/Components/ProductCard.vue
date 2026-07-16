<template>
    <div
        class="group bg-white rounded border border-slate-200 hover:shadow-md transition-all duration-300 flex flex-col h-full relative overflow-hidden w-full p-4 font-sans"
    >
        <!-- Badges / Discount -->
        <div
            v-if="product.discount_type"
            class="absolute top-2 left-2 z-20 pointer-events-none"
        >
            <span
                class="px-2 py-0.5 bg-[#ef4823] text-white text-[9px] font-black uppercase rounded shadow-sm"
            >
                {{
                    product.discount_type === "percentage"
                        ? `${product.discount_value}% OFF`
                        : `Save: ৳${parseFloat(product.discount_value).toLocaleString()}`
                }}
            </span>
        </div>

        <!-- Image Area -->
        <div
            class="relative w-full aspect-square overflow-hidden bg-white shrink-0 mb-3 flex items-center justify-center"
        >
            <Link
                :href="`/products/${product.slug}`"
                class="block w-full h-full relative"
            >
                <div v-if="product.stock <= 0" class="absolute inset-0 z-10 bg-white/40 backdrop-blur-[2px] flex items-center justify-center pointer-events-none">
                    <span class="px-3 py-1 bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">Out of Stock</span>
                </div>
                
                <img
                    :src="
                        product.image ||
                        `https://placehold.co/800x800/f8fafc/64748b?text=${encodeURIComponent(product.name)}`
                    "
                    class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-500 ease-out"
                    :alt="product.name"
                />
            </Link>
        </div>

        <!-- Product Details -->
        <div class="flex-grow flex flex-col justify-between">
            <div class="space-y-1.5">
                <!-- Stock Status / Availability tag (Paragon Style) -->
                <div class="flex items-center gap-1.5 text-[11px] font-bold">
                    <span
                        :class="product.stock > 0 ? 'text-[#00a651]' : 'text-slate-400'"
                    >
                        ● {{ product.stock > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </div>

                <!-- Product Name -->
                <Link :href="`/products/${product.slug}`" class="block">
                    <h3
                        class="text-xs md:text-sm font-bold text-slate-800 hover:text-[#00a651] transition-colors leading-snug line-clamp-2 h-9 tracking-tight"
                    >
                        {{ product.name }}
                    </h3>
                </Link>

                <!-- Clean Pricing Section -->
                <div class="flex items-baseline gap-2 py-1">
                    <span
                        class="text-sm md:text-base font-extrabold text-[#ef4823]"
                    >
                        {{ finalPrice.toLocaleString() }}৳
                    </span>
                    <span
                        v-if="product.discount_type"
                        class="text-xs text-slate-400 line-through font-normal"
                    >
                        {{ parseFloat(product.price).toLocaleString() }}৳
                    </span>
                </div>
            </div>

            <!-- Bottom Add to Cart Button (Paragon Style) -->
            <div class="pt-3 mt-auto">
                <button
                    @click.stop="handleAddToCart"
                    :disabled="product.stock <= 0"
                    class="w-full py-2 bg-[#ef4823] hover:bg-[#d63d1a] border-none text-white font-bold text-xs uppercase transition-all tracking-wider rounded select-none cursor-pointer disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <ShoppingCart class="w-3.5 h-3.5" />
                    <span>{{ product.stock > 0 ? "Add to Cart" : "Out of Stock" }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { ShoppingCart } from "lucide-vue-next";
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
</script>
