<template>
    <div class="relative z-[100]">
        <!-- Overlay -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" @click="$emit('close')"></div>
        </Transition>

        <!-- Modal (Top Center) -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 -translate-y-12 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 -translate-y-12 scale-95"
        >
            <div v-if="isOpen" class="fixed top-8 left-1/2 -translate-x-1/2 w-full max-w-2xl bg-white/90 backdrop-blur-2xl rounded-[40px] shadow-2xl flex flex-col overflow-hidden border border-white/50 max-h-[85vh]">
                <!-- Header -->
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white/50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#00a651] rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20">
                            <ShoppingCart class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-800 uppercase tracking-tighter italic">{{ $t('Shopping Cart') }}</h2>
                            <p class="text-[10px] text-[#00a651] font-black uppercase tracking-[0.2em]">{{ cartCount }} {{ $t('Manifested Items') }}</p>
                        </div>
                    </div>
                    <button @click="$emit('close')" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all border border-slate-100">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Items -->
                <div class="flex-grow overflow-y-auto p-8 space-y-6 custom-scrollbar">
                    <div v-if="cart.length === 0" class="py-24 flex flex-col items-center justify-center text-center">
                        <div class="w-24 h-24 bg-slate-50 rounded-[32px] flex items-center justify-center mb-6 border border-slate-100">
                            <ShoppingBag class="w-10 h-10 text-slate-200" />
                        </div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter italic mb-1">{{ $t('Your cart is empty') }}</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Add some high-performance hardware to get started</p>
                    </div>

                    <div v-for="item in cart" :key="item.id" class="flex gap-6 p-4 rounded-3xl bg-white border border-slate-100 group hover:border-[#00a651]/30 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                        <div class="w-24 h-24 bg-slate-50 rounded-2xl overflow-hidden flex-shrink-0 p-3 border border-slate-50 transition-transform group-hover:scale-105">
                            <img 
                                :src="(item.product ? item.product.image : item.combo.image) || 'https://placehold.co/200x200/fff/0a0a0a?text=Product'" 
                                class="w-full h-full object-contain"
                            />
                        </div>
                        <div class="flex-grow min-w-0 flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-sm font-black text-slate-800 leading-tight truncate pr-4 uppercase tracking-tight italic">
                                    {{ item.product ? item.product.name : item.combo.name }}
                                </h3>
                                <button @click="removeFromCart(item.id)" class="text-slate-300 hover:text-red-500 transition-all flex-shrink-0 p-1">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                            <div class="text-[11px] font-black text-[#00a651] mb-auto uppercase tracking-widest">
                                ৳{{ getItemPrice(item).toLocaleString() }} <span class="text-slate-300 mx-1">/</span> unit
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <div class="flex items-center bg-slate-50 rounded-xl border border-slate-100 overflow-hidden">
                                    <button @click="updateQuantity(item.id, item.quantity - 1)" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-[#00a651] hover:bg-white transition-all font-black text-lg">-</button>
                                    <input 
                                        type="text" 
                                        :value="item.quantity" 
                                        readonly
                                        class="w-10 bg-transparent text-center text-xs font-black text-slate-800 border-none focus:ring-0"
                                    />
                                    <button @click="updateQuantity(item.id, item.quantity + 1)" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-[#00a651] hover:bg-white transition-all font-black text-lg">+</button>
                                </div>
                                <div class="text-base font-black text-slate-900 tracking-tighter italic">
                                    ৳{{ (getItemPrice(item) * item.quantity).toLocaleString() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-8 py-8 border-t border-slate-100 bg-white/80">
                    <div class="flex justify-between items-end mb-8">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">{{ $t('Aggregate Total') }}</span>
                            <div class="w-8 h-1 bg-[#00a651] rounded-full"></div>
                        </div>
                        <span class="text-3xl font-black text-slate-900 tracking-tighter italic">৳{{ cartTotal.toLocaleString() }}</span>
                    </div>
                    <div class="flex gap-4">
                        <button 
                            @click="$emit('close')"
                            class="flex-1 h-14 bg-white border border-slate-200 text-slate-600 rounded-[20px] text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all"
                        >
                            {{ $t('Keep Browsing') }}
                        </button>
                        <Link 
                            href="/checkout" 
                            @click="$emit('close')"
                            class="flex-[1.5] h-14 bg-[#0a0a0a] text-white rounded-[20px] text-[11px] font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-[#00a651] transition-all shadow-xl shadow-slate-900/10 group"
                        >
                            {{ $t('Proceed to Checkout') }}
                            <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ShoppingCart, X, Trash2, ShoppingBag, ArrowRight } from 'lucide-vue-next';
import { useCart } from '@/Composables/useCart';

defineProps({
    isOpen: Boolean
});

defineEmits(['close']);

const { cart, cartTotal, cartCount, removeFromCart, updateQuantity } = useCart();

const getItemPrice = (item) => {
    if (item.product) {
        const price = parseFloat(item.product.price || 0);
        if (!item.product.discount_type) return price;
        if (item.product.discount_type === 'percentage') {
            return price * (1 - parseFloat(item.product.discount_value || 0) / 100);
        }
        return Math.max(0, price - parseFloat(item.product.discount_value || 0));
    }
    return parseFloat(item.combo?.price || 0);
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
</style>
