<template>
    <div
        class="group bg-white border border-slate-200 hover:border-[#00a651] transition-all duration-300 flex flex-col h-full relative p-2 pb-3 text-center font-sans w-full"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <div
            v-if="product.discount_type"
            class="absolute top-1 left-1 z-20 pointer-events-none"
        >
            <span
                class="px-1.5 py-0.5 bg-[#e1251b] text-white text-[8px] font-black uppercase rounded shadow-sm"
            >
                {{
                    product.discount_type === "percentage"
                        ? `${product.discount_value}% OFF`
                        : `Save: ৳${parseFloat(product.discount_value).toLocaleString()}`
                }}
            </span>
        </div>

        <div class="h-36 w-full flex items-center justify-center overflow-hidden mb-1 relative shrink-0">
            <Link
                :href="`/products/${product.slug}`"
                class="block max-h-full max-w-full"
            >
                <div v-if="product.stock <= 0" class="absolute inset-0 z-10 bg-white/40 backdrop-blur-[1px] flex items-center justify-center pointer-events-none">
                    <span class="px-1.5 py-0.5 bg-slate-800 text-white text-[8px] font-bold uppercase tracking-wider rounded">Stock Out</span>
                </div>

                <img
                    :src="displayedImage"
                    loading="lazy"
                    class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
                    :alt="product.name"
                />
            </Link>
        </div>

        <div class="flex-grow flex flex-col justify-between pt-2">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5 block">
                    {{ product.brand_name || product.category_name || 'Electronics' }}
                </span>

                <Link :href="`/products/${product.slug}`" class="block mb-2">
                    <h3
                        class="text-[11px] font-bold text-slate-800 group-hover:text-[#00a651] transition-colors leading-tight line-clamp-2 min-h-[32px] tracking-tight"
                    >
                        {{ product.name }}
                    </h3>
                </Link>
            </div>

            <div class="mt-auto flex flex-col items-center">
                <div class="flex flex-col items-center justify-center mb-2.5">
                    <div class="text-xs font-black text-[#ef4823]">
                        ৳{{ finalPrice.toLocaleString() }}
                    </div>
                    <div
                        v-if="product.discount_type"
                        class="text-[9px] text-slate-400 line-through font-normal leading-none mt-0.5"
                    >
                        ৳{{ parseFloat(product.price).toLocaleString() }}
                    </div>
                </div>

                <button
                    @click.stop="handleAddToCart"
                    :disabled="product.stock <= 0"
                    class="inline-block w-full max-w-[110px] bg-[#ef4823] hover:bg-[#d63d1a] border-none text-white text-[10px] font-bold py-1.5 px-3 rounded transition-colors uppercase tracking-wider cursor-pointer disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed mx-auto"
                >
                    {{ product.stock > 0 ? "Add to cart" : "Stock Out" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { ShoppingCart } from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";

const props = defineProps({
    product: Object,
});

defineEmits(["quickView"]);

const { addToCart } = useCart();

const isHovered = ref(false);

const hoverImage = computed(() => {
    if (props.product.gallery && props.product.gallery.length > 0) {
        return props.product.gallery[0].image;
    }
    return null;
});

const displayedImage = computed(() => {
    if (isHovered.value && hoverImage.value) {
        return hoverImage.value;
    }
    return props.product.image || `https://placehold.co/800x800/f8fafc/64748b?text=${encodeURIComponent(props.product.name)}`;
});

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

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>