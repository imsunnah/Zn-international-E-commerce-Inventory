<template>
    <div
        class="group bg-white shadow-[0_0_20px_rgba(100,116,139,0.3)] hover:shadow-[0_0_30px_rgba(100,116,139,0.45)] active:shadow-[0_0_12px_rgba(100,116,139,0.3)] active:scale-[0.98] transition-all duration-300 flex flex-col h-[380px] sm:h-[420px] relative text-center font-sans w-full overflow-hidden hover:-translate-y-1"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <!-- Discount badge -->
        <div
            v-if="product.discount_type"
            class="absolute top-2 left-2 z-20 pointer-events-none"
        >
            <span
                class="px-2 py-0.5 bg-[#e1251b] text-white text-[9px] font-black uppercase shadow-sm"
            >
                {{
                    product.discount_type === "percentage"
                        ? `${product.discount_value}% OFF`
                        : `Save: ৳${parseFloat(product.discount_value).toLocaleString()}`
                }}
            </span>
        </div>

        <!-- Image block: no padding/margin, image is exactly this box's size, edge-to-edge -->
        <div class="h-56 sm:h-64 w-full overflow-hidden relative shrink-0">
            <Link
                :href="`/products/${product.slug}`"
                class="block w-full h-full"
            >
                <div v-if="product.stock <= 0" class="absolute inset-0 z-10 bg-white/50 backdrop-blur-[1px] flex items-center justify-center pointer-events-none">
                    <span class="px-2 py-1 bg-slate-800 text-white text-[9px] font-extrabold uppercase tracking-wider">Stock Out</span>
                </div>

                <img
                    :src="displayedImage"
                    loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    :alt="product.name"
                />
            </Link>

            <!-- Wishlist icon: hidden until hover, overlaps the image top-right -->
            <button
                type="button"
                @click.stop="handleWishlist"
                class="absolute top-2 right-2 z-20 h-7 w-7 flex items-center justify-center rounded-full bg-white shadow-[0_1px_4px_rgba(0,0,0,0.25)] hover:bg-slate-50 transition-all duration-300 cursor-pointer border-none opacity-0 group-hover:opacity-100"
            >
                <Heart
                    class="w-3.5 h-3.5"
                    :class="isWishlisted ? 'text-[#ef4823] fill-[#ef4823]' : 'text-slate-400'"
                />
            </button>

            <!-- Quick View: hidden until hover, overlaps bottom of the image, no gap -->
            <button
                type="button"
                @click.stop="handleQuickView"
                class="absolute bottom-0 left-0 right-0 z-20 h-7 flex items-center justify-center bg-[#e1251b] hover:bg-[#c91f16] text-white text-[10px] sm:text-[11px] font-black uppercase tracking-wider transition-all duration-300 cursor-pointer border-none shadow-[0_-2px_6px_rgba(0,0,0,0.15)] translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100"
            >
                Quick View
            </button>
        </div>

        <div class="flex-grow flex flex-col justify-between px-3 pt-2 pb-3">
            <div>
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">
                    {{ product.brand_name || product.category_name || 'Electronics' }}
                </span>

                <Link :href="`/products/${product.slug}`" class="block mb-2">
                    <h3
                        class="text-xs font-bold text-slate-800 group-hover:text-[#00a651] transition-colors leading-snug line-clamp-2 min-h-[36px] tracking-tight"
                    >
                        {{ product.name }}
                    </h3>
                </Link>
            </div>

            <div class="mt-auto flex flex-col items-center">
                <div class="flex flex-col items-center justify-end mb-2 min-h-[38px]">
                    <div class="text-sm font-black text-[#ef4823]">
                        ৳{{ finalPrice.toLocaleString() }}
                    </div>
                    <div
                        v-if="product.discount_type"
                        class="text-[10px] text-slate-400 line-through font-normal leading-none mt-0.5"
                    >
                        ৳{{ parseFloat(product.price).toLocaleString() }}
                    </div>
                </div>

                <button
                    @click.stop="handleAddToCart"
                    :disabled="product.stock <= 0"
                    class="inline-flex items-center justify-center gap-1 w-full max-w-[100px] h-7 bg-[#00a651] hover:bg-[#008541] active:bg-[#007a3d] border-none text-white text-[9px] font-extrabold px-2 transition-all uppercase tracking-wider cursor-pointer shadow-sm hover:shadow active:scale-95 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed mx-auto"
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
import { Heart } from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";

const props = defineProps({
    product: Object,
});

const emit = defineEmits(["quickView", "wishlist"]);

const handleQuickView = () => {
    emit("quickView", props.product);
};

const { addToCart } = useCart();

const isHovered = ref(false);
const isWishlisted = ref(false);

const handleWishlist = () => {
    isWishlisted.value = !isWishlisted.value;
    emit("wishlist", props.product);
};

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