<template>
    <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_-20px_rgba(16,185,129,0.15)] hover:-translate-y-3 transition-all duration-700 flex flex-col h-full relative overflow-hidden">
        <!-- Technical Badges -->
        <div class="absolute top-5 left-5 z-20 flex flex-col gap-2.5">
            <span v-if="product.discount_type" class="px-5 py-2 bg-rose-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full shadow-lg shadow-rose-500/30 active:scale-95 transition-transform animate-pulse">
                {{ product.discount_type === 'percentage' ? `${product.discount_value}% RELAX` : `SAVE ৳${product.discount_value}` }}
            </span>
            <span v-if="product.stock <= 0" class="px-5 py-2 bg-slate-950 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full shadow-lg">
                DE-LINKED
            </span>
        </div>

        <!-- System Quick Action -->
        <button 
            @click.stop="$emit('quickView', product)"
            class="absolute top-5 right-5 z-20 w-14 h-14 bg-white/10 backdrop-blur-2xl rounded-2xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-emerald-600 shadow-xl border border-white/20 opacity-0 group-hover:opacity-100 translate-x-10 group-hover:translate-x-0 transition-all duration-700 delay-100"
        >
            <Scan class="w-6 h-6" />
        </button>

        <!-- Dynamic Image Display -->
        <div class="relative aspect-square md:aspect-[5/4.5] bg-[#fcfdfe] flex items-center justify-center p-10 overflow-hidden">
            <Link :href="`/products/${product.slug}`" class="w-full h-full flex items-center justify-center relative">
                <img 
                    :src="product.image || 'https://placehold.co/800x800/fff/00100a?text=' + product.name" 
                    class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-1000 cubic-bezier(0.4, 0, 0.2, 1) p-4" 
                    :alt="product.name"
                >
            </Link>
            <!-- Emerald Gradient Glow -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        </div>

        <!-- Meta Specification Content -->
        <div class="p-8 md:p-10 flex flex-grow flex-col bg-white">
            <!-- Classification Header -->
            <div class="flex items-center gap-3 mb-5">
                 <span v-if="product.brand" class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.4em] hover:text-emerald-400 transition-colors cursor-pointer truncate">
                    {{ product.brand.name }}
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-100"></span>
                <span class="text-[9px] font-extrabold text-slate-300 uppercase tracking-widest italic">Core Series</span>
            </div>

            <!-- Descriptive Title -->
            <Link :href="`/products/${product.slug}`" class="block mb-8">
                <h3 class="text-lg md:text-xl font-black text-slate-950 leading-[1.1] tracking-tighter hover:text-emerald-600 transition-colors line-clamp-2 uppercase italic overflow-hidden">
                    {{ product.name }}
                </h3>
            </Link>

            <div class="flex-grow"></div> 

            <!-- Price & Analytics -->
            <div class="flex items-end justify-between mb-10 overflow-hidden">
                <div class="flex flex-col gap-2">
                    <span v-if="product.discount_type" class="text-[11px] text-slate-400 line-through font-bold tracking-[0.2em] uppercase opacity-60">
                        ৳{{ product.price.toLocaleString() }}
                    </span>
                    <span class="text-2xl md:text-3xl font-black text-slate-950 leading-none tracking-tighter italic">
                        ৳{{ finalPrice.toLocaleString() }}
                    </span>
                </div>
                <!-- Mini Tech Badge -->
                <div class="flex flex-col items-end opacity-20 group-hover:opacity-100 group-hover:translate-x-0 translate-x-5 transition-all duration-700">
                    <div class="flex gap-1">
                        <div v-for="i in 5" :key="i" class="w-2 h-2 bg-emerald-500 rounded-full" :class="i > 4 ? 'bg-slate-100' : 'animate-pulse'"></div>
                    </div>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 italic">Performance Meta</span>
                </div>
            </div>

            <!-- Transaction Interface -->
            <div class="flex gap-4 w-full mt-auto">
                <button 
                    @click="handleAddToCart"
                    :disabled="product.stock <= 0"
                    class="w-16 h-16 bg-slate-50 text-slate-950 rounded-2xl hover:bg-slate-950 hover:text-white transition-all duration-500 flex items-center justify-center active:scale-90 disabled:opacity-30 disabled:pointer-events-none border border-slate-100 group/cart-btn shadow-lg hover:shadow-slate-900/10"
                >
                    <ShoppingCart class="w-6 h-6 transition-transform group-hover/cart-btn:scale-110" />
                </button>
                <button 
                    @click="handleBuyNow"
                    :disabled="product.stock <= 0"
                    class="flex-1 h-16 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-500 hover:shadow-[0_20px_40px_-10px_rgba(16,185,129,0.4)] transition-all duration-500 flex items-center justify-center gap-4 text-[10px] font-black uppercase tracking-[0.4em] active:scale-[0.98] disabled:bg-slate-200 disabled:text-slate-400"
                >
                    <Zap class="w-5 h-5" v-if="product.stock > 0" />
                    <span>{{ product.stock > 0 ? 'Deploy Now' : 'Offline' }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { ShoppingCart, Zap, Scan } from 'lucide-vue-next';
import { useCart } from '@/Composables/useCart';

const props = defineProps({
    product: Object
});

defineEmits(['quickView']);

const { addToCart } = useCart();

const finalPrice = computed(() => {
    let price = parseFloat(props.product.price);
    if (!price) return 0;
    if (props.product.discount_type === 'percentage') {
        price = price * (1 - props.product.discount_value / 100);
    } else if (props.product.discount_type === 'flat') {
        price = Math.max(0, price - props.product.discount_value);
    }
    return price;
});

const handleAddToCart = () => {
    addToCart(props.product, 1);
};

const handleBuyNow = () => {
    addToCart(props.product, 1);
    router.visit('/checkout');
};
</script>