<template>
  <div class="group bg-white rounded-2xl border border-slate-200 hover:shadow-lg transition-shadow duration-300 flex flex-col h-full relative overflow-hidden">
    <!-- Badges -->
    <div class="absolute top-4 left-4 z-20 flex flex-col gap-2">
      <span v-if="product.discount_type" class="px-3 py-1 bg-rose-500 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg shadow-sm">
        {{ product.discount_type === 'percentage' ? `${product.discount_value}% OFF` : `SAVE ৳${product.discount_value}` }}
      </span>
      <span v-if="product.stock <= 0" class="px-3 py-1 bg-slate-100 text-slate-500 border border-slate-200 text-[10px] font-bold uppercase tracking-wider rounded-lg">
        Out of Stock
      </span>
    </div>

    <!-- Quick Action -->
    <button 
      @click.stop="$emit('quickView', product)"
      class="absolute top-4 right-4 z-20 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-500 hover:text-emerald-600 hover:bg-slate-50 shadow-sm border border-slate-200 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
    >
      <Scan class="w-5 h-5" />
    </button>

    <!-- Image -->
    <div class="relative aspect-square md:aspect-[5/4] bg-slate-50 flex items-center justify-center p-6 sm:p-10 overflow-hidden mix-blend-multiply">
      <Link :href="`/products/${product.slug}`" class="w-full h-full flex items-center justify-center">
        <img 
          :src="product.image || `https://placehold.co/800x800/f8fafc/64748b?text=${encodeURIComponent(product.name)}`"
          class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500"
          :alt="product.name"
        >
      </Link>
    </div>

    <!-- Content -->
    <div class="p-5 md:p-6 flex flex-grow flex-col">
      <!-- Brand & Category -->
      <div class="flex items-center gap-2 mb-3 text-[10px] font-semibold text-slate-500 uppercase tracking-wider line-clamp-1">
        <span v-if="product.brand" class="text-emerald-600 hover:text-emerald-700 transition-colors cursor-pointer">{{ product.brand.name }}</span>
        <span v-if="product.brand" class="w-1 h-1 rounded-full bg-slate-300"></span>
        <span>{{ product.category?.name || 'Accessories' }}</span>
      </div>

      <!-- Title -->
      <Link :href="`/products/${product.slug}`" class="block mb-4">
        <h3 class="text-sm md:text-base font-bold text-slate-900 leading-snug hover:text-emerald-600 transition-colors line-clamp-2">
          {{ product.name }}
        </h3>
      </Link>

      <div class="flex-grow"></div> 

      <!-- Price -->
      <div class="flex items-end gap-3 mb-5">
        <span class="text-lg md:text-xl font-bold text-slate-900">
          ৳{{ finalPrice.toLocaleString() }}
        </span>
        <span v-if="product.discount_type" class="text-xs text-slate-400 line-through font-medium mb-1">
          ৳{{ parseFloat(product.price).toLocaleString() }}
        </span>
      </div>

      <!-- Actions -->
      <div class="flex gap-2 w-full mt-auto">
        <button 
          @click="handleAddToCart"
          :disabled="product.stock <= 0"
          class="w-12 h-11 bg-slate-50 text-slate-600 rounded-xl hover:bg-slate-100 transition-colors flex items-center justify-center disabled:opacity-50 border border-slate-200"
          title="Add to Cart"
        >
          <ShoppingCart class="w-5 h-5" />
        </button>
        <button 
          @click="handleBuyNow"
          :disabled="product.stock <= 0"
          class="flex-1 h-11 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 text-xs font-semibold disabled:bg-slate-200 disabled:text-slate-400"
        >
          <span>{{ product.stock > 0 ? 'Buy Now' : 'Check Store' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { ShoppingCart, Scan } from 'lucide-vue-next';
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