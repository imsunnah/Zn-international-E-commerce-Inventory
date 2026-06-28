<template>
    <StoreLayout>
        <div class="bg-[#FBFBFC] min-h-screen py-10 font-sans">
            <div class="max-w-[1500px] mx-auto px-4 md:px-8">
                
                <!-- Advanced Breadcrumbs -->
                <nav class="flex flex-wrap items-center gap-2 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-8 ml-2">
                    <Link href="/" class="hover:text-[#00a651] transition-colors whitespace-nowrap">{{ $t('Home Base') }}</Link>
                    <span class="opacity-20 text-slate-900">/</span>
                    <Link href="/shop" class="hover:text-[#00a651] transition-colors whitespace-nowrap">{{ $t('Inventory') }}</Link>
                    <span class="opacity-20 text-slate-900">/</span>
                    <span class="text-slate-900 truncate max-w-[200px] italic">{{ product.name }}</span>
                </nav>

                <!-- High-Premium Product Interface -->
                <div class="bg-white rounded-2xl md:rounded-[3rem] shadow-[0_30px_80px_-30px_rgba(0,0,0,0.05)] border border-slate-100 p-6 md:p-16 mb-12">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-24 items-start">
                        
                        <!-- Left: Visual Assets Section -->
                        <div class="space-y-8">
                            <!-- Main High-Res Image -->
                            <div @click="openLightboxMain" class="aspect-square rounded-[2rem] overflow-hidden bg-[#FBFBFC] border border-slate-100 flex items-center justify-center p-12 cursor-zoom-in group relative transition-all duration-700 hover:bg-white">
                                <img :src="activeImage" :alt="product.name" class="max-w-full max-h-full object-contain transition-transform duration-1000 group-hover:scale-110" />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/[0.02] transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-2xl border border-slate-50">
                                        <Maximize2 class="w-6 h-6 text-[#00a651]" />
                                    </div>
                                </div>
                            </div>

                            <!-- Synchronized Thumbnails -->
                            <div class="flex flex-wrap gap-4" v-if="allImages.length > 1">
                                <button 
                                    v-for="(img, index) in allImages" 
                                    :key="index"
                                    @click="activeImage = img"
                                    :class="['w-20 aspect-square rounded-2xl overflow-hidden border-2 transition-all duration-500', activeImage === img ? 'border-[#00a651] shadow-lg shadow-emerald-500/10' : 'border-slate-50 opacity-40 hover:opacity-100 hover:border-slate-200']"
                                >
                                    <img :src="img" class="w-full h-full object-cover" />
                                </button>
                            </div>
                        </div>

                        <!-- Right: Logistics & Parameters -->
                        <div class="mt-12 lg:mt-0 space-y-10">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black text-[#00a651] uppercase tracking-[0.3em] bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                                        {{ product.brand?.name || 'Authorized Hardware' }}
                                    </span>
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">
                                        ID: #{{ product.id.toString().padStart(6, '0') }}
                                    </span>
                                </div>
                                <h1 class="text-3xl md:text-4xl font-black text-slate-900 uppercase tracking-tighter leading-tight italic">
                                    {{ product.name }}
                                </h1>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 pt-2">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-3">
                                            <span class="text-4xl font-black text-[#0a0a0a] tracking-tighter">৳{{ discountedPrice.toLocaleString() }}</span>
                                            <span v-if="product.discount_type" class="text-lg text-slate-300 line-through font-bold tracking-widest mt-1">৳{{ parseFloat(props.product.price).toLocaleString() }}</span>
                                        </div>
                                    </div>
                                    <div v-if="product.discount_type" class="w-fit px-4 py-2 bg-red-600 text-white text-[10px] font-black rounded-xl uppercase tracking-[0.2em] shadow-xl shadow-red-600/10 active:animate-bounce">
                                        {{ product.discount_type === 'percentage' ? product.discount_value + '%' : '৳' + product.discount_value }} {{ $t('Discovered Value') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Specification Matrices -->
                            <div class="grid grid-cols-2 gap-px bg-slate-100 border border-slate-100 rounded-3xl overflow-hidden">
                                <div class="bg-white p-5 flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Unit Origin') }}</span>
                                    <span class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ product.brand?.name || 'GENERIC' }}</span>
                                </div>
                                <div class="bg-white p-5 flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Classification') }}</span>
                                    <span class="text-xs font-black text-[#00a651] uppercase tracking-tight">{{ product.category?.name || 'HARDWARE' }}</span>
                                </div>
                                <div class="bg-white p-5 flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Protocol') }}</span>
                                    <span class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ product.sub_category?.name || 'Standard' }}</span>
                                </div>
                                <div class="bg-white p-5 flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Availability') }}</span>
                                    <div class="flex items-center gap-2">
                                        <div :class="product.stock > 0 ? 'bg-emerald-500' : 'bg-red-500'" class="w-2 h-2 rounded-full shadow-lg pulse"></div>
                                        <span class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ product.stock > 0 ? $t('In Stock') : $t('Depleted') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Logic Controls -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-8">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('Quantity Matrix') }}</span>
                                    <div class="flex items-center bg-slate-50 border border-slate-100 rounded-2xl overflow-hidden h-14">
                                        <button @click="quantity > 1 && quantity--" class="px-6 h-full text-slate-400 hover:text-[#00a651] hover:bg-slate-100 transition-all"><Minus class="w-4 h-4 shadow-sm" /></button>
                                        <div class="px-4 text-center font-black text-lg text-slate-900 w-12">{{ quantity }}</div>
                                        <button @click="quantity < (product.stock || 99) && quantity++" class="px-6 h-full text-slate-400 hover:text-[#00a651] hover:bg-slate-100 transition-all"><Plus class="w-4 h-4 shadow-sm" /></button>
                                    </div>
                                    <span v-if="product.stock <= 5 && product.stock > 0" class="text-[10px] font-black text-red-500 uppercase tracking-[0.2em] animate-pulse">
                                        Critical Supply: {{ product.stock }} units
                                    </span>
                                </div>

                                <!-- Action Deployment -->
                                <div class="grid grid-cols-2 gap-6">
                                    <button 
                                        @click="handleAddToCart"
                                        :disabled="product.stock <= 0"
                                        class="h-16 bg-[#00a651] text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-[#008541] transition-all active:scale-95 flex items-center justify-center gap-3 shadow-2xl shadow-emerald-500/20 disabled:bg-slate-100 disabled:text-slate-300 disabled:shadow-none"
                                    >
                                        <ShoppingCart class="w-5 h-5" /> {{ $t('Add to Cart') }}
                                    </button>
                                    <button 
                                        @click="handleBuyNow"
                                        :disabled="product.stock <= 0"
                                        class="h-16 bg-[#0a0a0a] text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-black transition-all active:scale-95 flex items-center justify-center gap-3 shadow-2xl shadow-slate-900/10 disabled:bg-slate-100 disabled:shadow-none"
                                    >
                                        <Zap class="w-5 h-5 fill-white" /> {{ $t('Checkout') }}
                                    </button>
                                </div>

                                <!-- External Comms -->
                                <a 
                                    :href="`https://wa.me/${$page.props.settings.footer_phone}?text=Hi, I want to order ${product.name}`"
                                    target="_blank"
                                    class="h-16 bg-[#25D366] text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-[#128C7E] transition-all active:scale-95 flex items-center justify-center gap-4 w-full shadow-2xl shadow-emerald-500/10"
                                >
                                    <MessageSquare class="w-5 h-5 fill-white" /> {{ $t('WhatsApp') }}
                                </a>
                            </div>

                            <!-- Industrial Notes -->
                            <div v-if="product.remarks" class="p-6 bg-emerald-50/50 border border-emerald-100 rounded-3xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-3 opacity-10">
                                    <Info class="w-12 h-12 text-[#00a651]" />
                                </div>
                                <p class="text-[9px] font-black text-[#00a651] uppercase tracking-[0.3em] mb-3 flex items-center gap-2">
                                    <Info class="w-3.5 h-3.5" /> Technical Advisory
                                </p>
                                <p class="text-sm font-bold text-slate-700 leading-relaxed italic">{{ product.remarks }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Synchronized Info Blocks -->
                <div class="bg-white rounded-[3rem] shadow-[0_30px_80px_-30px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden mb-16">
                    <div class="flex border-b border-slate-100 bg-[#FBFBFC]">
                        <button 
                            @click="activeTab = 'description'" 
                            :class="[activeTab === 'description' ? 'text-[#00a651] bg-white border-x border-slate-100 border-t-[3px] border-t-[#00a651]' : 'text-slate-400 hover:text-slate-600 border-b border-slate-100']"
                            class="px-12 py-6 font-black text-[11px] uppercase tracking-[0.2em] transition-all"
                        >
                            Logistics / Specs
                        </button>
                        <button 
                            @click="activeTab = 'reviews'" 
                            :class="[activeTab === 'reviews' ? 'text-[#00a651] bg-white border-x border-slate-100 border-t-[3px] border-t-[#00a651]' : 'text-slate-400 hover:text-slate-600 border-b border-slate-100']"
                            class="px-12 py-6 font-black text-[11px] uppercase tracking-[0.2em] transition-all flex items-center gap-3"
                        >
                            Customer Feedback <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[9px]">{{ product.reviews ? product.reviews.length : 0 }}</span>
                        </button>
                    </div>

                    <div class="p-10 md:p-20">
                        <div v-if="activeTab === 'description'" class="max-w-4xl">
                            <h3 class="text-sm font-black text-slate-900 mb-8 uppercase tracking-[0.3em] flex items-center gap-3">
                                <div class="w-5 h-0.5 bg-[#00a651]"></div>
                                Functional Specifications
                            </h3>
                            <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-medium leading-[2] whitespace-pre-line text-base">
                                {{ product.description }}
                            </div>
                        </div>

                        <div v-if="activeTab === 'reviews'" class="max-w-4xl">
                            <div class="lg:flex items-start gap-16 mb-16">
                                <div class="flex flex-col items-center mb-10 lg:mb-0">
                                    <div class="text-7xl font-black text-slate-900 tracking-tighter italic">{{ averageRating }}</div>
                                    <div class="flex gap-1 my-3">
                                        <Star v-for="star in 5" :key="star" class="w-5 h-5" :class="star <= Math.round(averageRating) ? 'text-[#00a651] fill-current' : 'text-slate-100'" />
                                    </div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] mt-2 italic text-center">Cumulative Satisfaction Index</p>
                                </div>
                                <div class="flex-grow space-y-4">
                                    <div v-for="star in 5" :key="star" class="flex items-center gap-4">
                                        <span class="text-[10px] font-black text-slate-400 w-3">{{ 6-star }}</span>
                                        <div class="flex-grow h-2 bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                                            <div class="h-full bg-[#00a651] transition-all duration-1000 shadow-[0_0_10px_rgba(0,166,81,0.3)]" :style="{ width: getRatingPercentage(6-star) + '%' }"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400 w-8 text-right">{{ getRatingPercentage(6-star).toFixed(0) }}%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- List of Deployed Feedback -->
                            <div v-if="product.reviews && product.reviews.length > 0" class="space-y-8 mb-16">
                                <div v-for="r in product.reviews" :key="r.id" class="p-8 bg-[#FBFBFC] rounded-[2rem] border border-slate-50 hover:border-[#00a651]/20 transition-all group">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-xl font-black text-slate-200 border border-slate-100 group-hover:text-[#00a651] transition-colors uppercase">
                                                {{ r.customer_name.charAt(0) }}
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ r.customer_name }}</h4>
                                                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-1">{{ new Date(r.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 bg-white px-3 py-1.5 rounded-xl border border-slate-50">
                                            <Star v-for="star in 5" :key="star" class="w-3 h-3 shadowed" :class="star <= r.rating ? 'text-[#00a651] fill-current' : 'text-slate-100'" />
                                        </div>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600 leading-relaxed italic ml-16">"{{ r.comment }}"</p>
                                </div>
                            </div>
                            <div v-else class="text-center py-20 mb-16 bg-[#FBFBFC] rounded-[3rem] border border-dashed border-slate-200">
                                <MessageSquare class="w-12 h-12 text-slate-100 mx-auto mb-4" />
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">{{ $t('No signals received from customers for this hardware unit.') }}</p>
                            </div>

                            <!-- Feedback Deployment Form -->
                            <div class="bg-slate-900 p-12 rounded-[3.5rem] border border-slate-800 shadow-2xl relative overflow-hidden">
                                <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#00a651] opacity-10 rounded-full blur-[100px]"></div>
                                <h3 class="text-xs font-black text-white mb-3 uppercase tracking-[0.3em] flex items-center gap-3">
                                    <div class="w-4 h-4 rounded bg-[#00a651] shadow-lg shadow-emerald-500/50"></div>
                                    {{ $t('Submit Performance Report') }}
                                </h3>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-10">{{ $t('Open frequency — verify your experience for the community') }}</p>
                                
                                <div v-if="reviewSuccessMessage" class="p-6 mb-8 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-black rounded-2xl uppercase tracking-widest">
                                    {{ reviewSuccessMessage }}
                                </div>

                                <form @submit.prevent="submitReview" class="space-y-8">
                                    <div class="grid md:grid-cols-2 gap-8">
                                        <div class="space-y-3">
                                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Verifier Identity') }}</label>
                                            <input type="text" v-model="reviewForm.customer_name" required placeholder="e.g. CORE_UNIT_7" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-6 py-4 text-xs font-bold text-white placeholder:text-slate-700 focus:ring-2 focus:ring-[#00a651]/20 focus:border-[#00a651]/50 transition-all outline-none" />
                                        </div>
                                        <div class="space-y-3">
                                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Satisfaction Coefficient') }}</label>
                                            <div class="flex gap-3 h-14 items-center bg-slate-950 rounded-2xl border border-slate-800 px-6">
                                                <Star 
                                                    v-for="star in 5" 
                                                    :key="star" 
                                                    @click="reviewForm.rating = star"
                                                    class="w-6 h-6 cursor-pointer transition-all duration-300 transform active:scale-75" 
                                                    :class="star <= reviewForm.rating ? 'text-[#00a651] fill-current drop-shadow-[0_0_10px_rgba(0,166,81,0.5)]' : 'text-slate-800 hover:text-slate-600'"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Functional Review / Message') }}</label>
                                        <textarea rows="5" v-model="reviewForm.comment" required placeholder="Transmit your log here..." class="w-full rounded-[2rem] border border-slate-800 bg-slate-950 p-6 text-xs font-bold text-white placeholder:text-slate-700 focus:ring-2 focus:ring-[#00a651]/20 focus:border-[#00a651]/50 transition-all outline-none resize-none"></textarea>
                                    </div>
                                    <button 
                                        type="submit" 
                                        :disabled="isSubmittingReview"
                                        class="px-12 py-6 bg-[#00a651] text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all disabled:opacity-50 shadow-2xl shadow-emerald-500/20 active:scale-95"
                                    >
                                        {{ isSubmittingReview ? $t('Transmitting...') : $t('Broadcast Report') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Components Matrix -->
                <div v-if="relatedProducts && relatedProducts.length > 0">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 px-4">
                        <div>
                            <span class="text-[9px] font-black text-[#00a651] uppercase tracking-[0.4em] block mb-2">{{ $t('Synched Inventory') }}</span>
                            <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic">{{ $t('Related Technology Units') }}</h2>
                        </div>
                        <Link href="/shop" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-[#00a651] transition-all border-b border-slate-200 pb-1">{{ $t('Access Full Database →') }}</Link>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                        <ProductCard v-for="p in relatedProducts" :key="p.id" :product="p" />
                    </div>
                </div>
            </div>
        </div>

        <ImageLightbox :show="showLightbox" :product="lightboxProduct" @close="showLightbox = false" />
    </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';
import { useCart } from '@/Composables/useCart';
import { Plus, Minus, ShoppingCart, Star, Maximize2, ChevronRight, Zap, MessageSquare, Phone, Info } from 'lucide-vue-next';

const props = defineProps({
    product: Object,
    relatedProducts: Array
});

const { addToCart } = useCart();
const quantity = ref(1);
const activeTab = ref('description');
const showLightbox = ref(false);
const lightboxProduct = ref(null);

const reviewForm = ref({
    customer_name: '',
    comment: '',
    rating: 5
});

const isSubmittingReview = ref(false);
const reviewSuccessMessage = ref('');

const averageRating = computed(() => {
    if (!props.product.reviews || props.product.reviews.length === 0) return '0.0';
    const sum = props.product.reviews.reduce((acc, r) => acc + r.rating, 0);
    return (sum / props.product.reviews.length).toFixed(1);
});

const getRatingPercentage = (stars) => {
    if (!props.product.reviews || props.product.reviews.length === 0) return 0;
    const count = props.product.reviews.filter(r => r.rating === stars).length;
    return (count / props.product.reviews.length) * 100;
  };

  const submitReview = async () => {
    isSubmittingReview.value = true;
    reviewSuccessMessage.value = '';
    
    try {
        router.post(`/products/${props.product.slug}/reviews`, {
            customer_name: reviewForm.value.customer_name || 'Anonymous Guest',
            comment: reviewForm.value.comment,
            rating: reviewForm.value.rating
        }, {
            preserveScroll: true,
            onSuccess: () => {
                reviewForm.value.customer_name = '';
                reviewForm.value.comment = '';
                reviewForm.value.rating = 5;
                reviewSuccessMessage.value = usePage().props.translations?.['Thank you! Your remark/review has been posted successfully.'] || 'Thank you! Your report has been broadcast successfully.';
            }
        });
    } catch (e) {
        console.error(e);
    } finally {
        isSubmittingReview.value = false;
    }
};

const allImages = computed(() => {
    let images = [];
    if (props.product.image) images.push(props.product.image);
    if (props.product.gallery && Array.isArray(props.product.gallery)) {
        props.product.gallery.forEach(img => {
            if (img.image) images.push(img.image);
        });
    }
    return images;
});

const activeImage = ref(null);

// Watch for product changes to reset active image
watch(() => props.product.id, () => {
    activeImage.value = props.product.image;
    quantity.value = 1;
}, { immediate: true });

const discountedPrice = computed(() => {
    const price = parseFloat(props.product.price || 0);
    if (!props.product.discount_type) return price;
    if (props.product.discount_type === 'percentage') {
        return price * (1 - parseFloat(props.product.discount_value || 0) / 100);
    }
    return Math.max(0, price - parseFloat(props.product.discount_value || 0));
});

const handleAddToCart = async () => {
    await addToCart(props.product, quantity.value);
};

const handleBuyNow = async () => {
    await addToCart(props.product, quantity.value);
    router.visit('/checkout');
};

const openLightboxMain = () => {
    lightboxProduct.value = { ...props.product, image: activeImage.value };
    showLightbox.value = true;
};
</script>

<style scoped>
.shadowed {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));
}
.pulse {
    animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse-slow {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .7; transform: scale(1.1); }
}
</style>
