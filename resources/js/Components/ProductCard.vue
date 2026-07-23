<template>
    <div
class="group bg-white shadow-[0_0_20px_rgba(100,116,139,0.3)] hover:shadow-[0_0_30px_rgba(100,116,139,0.45)] active:shadow-[0_0_12px_rgba(100,116,139,0.3)] active:scale-[0.98] transition-all duration-300 flex flex-col min-h-[380px] sm:min-h-[420px] relative p-3 pb-4 text-center font-sans w-full overflow-hidden hover:-translate-y-1"        @mouseleave="isHovered = false"
    >
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

        <!-- Taller Image Section -->
        <div class="h-56 sm:h-64 w-full flex items-center justify-center overflow-hidden mb-2 relative shrink-0 p-2">
            <Link
                :href="`/products/${product.slug}`"
                class="block max-h-full max-w-full flex items-center justify-center"
            >
                <div v-if="product.stock <= 0" class="absolute inset-0 z-10 bg-white/50 backdrop-blur-[1px] flex items-center justify-center pointer-events-none">
                    <span class="px-2 py-1 bg-slate-800 text-white text-[9px] font-extrabold uppercase tracking-wider">Stock Out</span>
                </div>

                <img
                    :src="displayedImage"
                    loading="lazy"
                    class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
                    :alt="product.name"
                />
            </Link>

            <!-- Quick View overlay: slides up from bottom of image on hover, no extra layout space -->
            <button
                @click.stop="handleQuickView"
                class="absolute bottom-0 left-0 right-0 z-20 bg-[#00a651] hover:bg-[#008541] text-white text-[10px] sm:text-[11px] font-black uppercase tracking-wider py-1.5 transition-all duration-300 cursor-pointer border-none shadow-sm translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100"
            >
                Quick View
            </button>
        </div>

        <div class="flex-grow flex flex-col justify-between pt-1">
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
                <div class="flex flex-col items-center justify-center mb-2">
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
                    class="inline-flex items-center justify-center gap-1 w-full max-w-[100px] bg-[#00a651] hover:bg-[#008541] active:bg-[#007a3d] border-none text-white text-[9px] font-extrabold py-1.5 px-2 transition-all uppercase tracking-wider cursor-pointer shadow-sm hover:shadow active:scale-95 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed mx-auto"
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

const emit = defineEmits(["quickView"]);

const handleQuickView = () => {
    emit("quickView", props.product);
};

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