<template>
  <StoreLayout>
    <div class="bg-white min-h-screen py-8 font-sans">
      <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6">
          <Link href="/" class="hover:text-emerald-600 transition-colors">Home</Link>
          <span class="text-slate-300">/</span>
          <Link href="/shop" class="hover:text-emerald-600 transition-colors">Products</Link>
          <span class="text-slate-300">/</span>
          <span class="text-slate-900 truncate">{{ product.name }}</span>
        </nav>

        <!-- Product Area -->
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-start mb-12">
          
          <!-- Image Gallery -->
          <div class="space-y-4">
            <div @click="openLightboxMain" class="aspect-square rounded-2xl overflow-hidden bg-slate-50 border border-slate-200 flex items-center justify-center p-8 cursor-zoom-in group mix-blend-multiply transition-colors hover:bg-slate-100">
              <img :src="activeImage || `https://placehold.co/800x800/f8fafc/64748b?text=${encodeURIComponent(product.name)}`" :alt="product.name" class="w-full h-full object-contain mix-blend-multiply" />
            </div>

            <div class="flex flex-wrap gap-3" v-if="allImages.length > 1">
              <button 
                v-for="(img, index) in allImages" 
                :key="index"
                @click="activeImage = img"
                :class="['w-20 aspect-square rounded-xl overflow-hidden border-2 transition-colors duration-200', activeImage === img ? 'border-emerald-500' : 'border-slate-200 hover:border-emerald-300']"
              >
                <img :src="img" class="w-full h-full object-cover mix-blend-multiply" />
              </button>
            </div>
          </div>

          <!-- Product Details -->
          <div class="mt-8 lg:mt-0 space-y-8">
            <div>
              <div class="flex items-center gap-3 space-y-1 mb-2">
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                  {{ product.brand?.name || 'Accessories' }}
                </span>
                <span class="text-xs text-slate-500">ID: #{{ product.id.toString().padStart(6, '0') }}</span>
              </div>
              <h1 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight">
                {{ product.name }}
              </h1>
              
              <div class="flex items-center gap-4 mt-6">
                <span class="text-3xl font-bold text-slate-900">৳{{ discountedPrice.toLocaleString() }}</span>
                <span v-if="product.discount_type" class="text-lg text-slate-400 line-through font-medium">৳{{ parseFloat(props.product.price).toLocaleString() }}</span>
              </div>
            </div>

            <!-- Simple Attributes -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 grid grid-cols-2 gap-4">
              <div class="flex flex-col gap-1">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Brand</span>
                <span class="text-sm font-bold text-slate-900">{{ product.brand?.name || 'N/A' }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Category</span>
                <span class="text-sm font-bold text-slate-900">{{ product.category?.name || 'N/A' }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Type</span>
                <span class="text-sm font-bold text-slate-900">{{ product.sub_category?.name || 'Standard' }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Status</span>
                <span :class="product.stock > 0 ? 'text-emerald-600' : 'text-red-500'" class="text-sm font-bold">
                  {{ product.stock > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
              </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
              <div class="flex items-center gap-6">
                <span class="text-xs font-semibold text-slate-700">Quantity</span>
                <div class="flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden h-10 w-32">
                  <button @click="quantity > 1 && quantity--" class="px-3 h-full text-slate-500 hover:bg-slate-50"><Minus class="w-4 h-4" /></button>
                  <div class="flex-1 text-center font-semibold text-sm text-slate-900">{{ quantity }}</div>
                  <button @click="quantity < (product.stock || 99) && quantity++" class="px-3 h-full text-slate-500 hover:bg-slate-50"><Plus class="w-4 h-4" /></button>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4 mt-6">
                <button 
                  @click="handleAddToCart"
                  :disabled="product.stock <= 0"
                  class="h-12 border border-emerald-600 text-emerald-700 bg-emerald-50 rounded-xl font-bold text-sm hover:bg-emerald-100 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:bg-slate-100 disabled:border-slate-200 disabled:text-slate-400"
                >
                  <ShoppingCart class="w-4 h-4" /> Add to Cart
                </button>
                <button 
                  @click="handleBuyNow"
                  :disabled="product.stock <= 0"
                  class="h-12 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:bg-slate-200 disabled:text-slate-400"
                >
                  Buy Now
                </button>
              </div>

              <a 
                :href="`https://wa.me/${$page.props.settings.footer_phone}?text=Hi, I want to order ${product.name}`"
                target="_blank"
                class="hidden md:flex h-12 bg-[#25D366] text-white rounded-xl font-bold text-sm hover:bg-[#128C7E] transition-colors items-center justify-center gap-2 w-full mt-2"
              >
                <MessageSquare class="w-4 h-4" /> Order via WhatsApp
              </a>
            </div>
          </div>
        </div>

        <!-- Info Tabs -->
        <div class="mt-8 border-t border-slate-200 pt-8">
          <div class="flex gap-8 border-b border-slate-200">
            <button 
              @click="activeTab = 'description'" 
              :class="activeTab === 'description' ? 'text-emerald-600 border-b-2 border-emerald-600 font-bold' : 'text-slate-500 hover:text-slate-700 font-semibold'"
              class="pb-4 text-sm transition-colors"
            >
              Description
            </button>
            <button 
              @click="activeTab = 'reviews'" 
              :class="activeTab === 'reviews' ? 'text-emerald-600 border-b-2 border-emerald-600 font-bold' : 'text-slate-500 hover:text-slate-700 font-semibold'"
              class="pb-4 text-sm transition-colors flex items-center gap-2"
            >
              Reviews <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[10px]">{{ product.reviews ? product.reviews.length : 0 }}</span>
            </button>
          </div>

          <div class="py-10">
            <div v-if="activeTab === 'description'" class="max-w-4xl text-slate-600 text-sm leading-relaxed whitespace-pre-line font-medium">
              {{ product.description }}
            </div>

            <div v-if="activeTab === 'reviews'" class="max-w-4xl">
              <!-- Review summary -->
              <div class="flex items-center gap-8 mb-10 pb-10 border-b border-slate-100">
                <div class="text-center shrink-0">
                  <div class="text-5xl font-bold text-slate-900">{{ averageRating }}</div>
                  <div class="flex gap-1 mt-2 justify-center">
                    <Star v-for="star in 5" :key="star" class="w-4 h-4" :class="star <= Math.round(averageRating) ? 'text-amber-400 fill-current' : 'text-slate-200'" />
                  </div>
                </div>
                <div class="flex-grow space-y-2">
                  <div v-for="star in 5" :key="star" class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-slate-500 w-3">{{ 6-star }}</span>
                    <div class="flex-grow h-2 bg-slate-100 rounded-full overflow-hidden">
                      <div class="h-full bg-amber-400 transition-all" :style="{ width: getRatingPercentage(6-star) + '%' }"></div>
                    </div>
                    <span class="text-xs font-semibold text-slate-500 w-8 text-right">{{ getRatingPercentage(6-star).toFixed(0) }}%</span>
                  </div>
                </div>
              </div>

              <!-- Reviews List -->
              <div v-if="product.reviews && product.reviews.length > 0" class="space-y-6 mb-12">
                <div v-for="r in product.reviews" :key="r.id" class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                  <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-sm font-bold text-slate-500 border border-slate-200 uppercase">
                        {{ r.customer_name.charAt(0) }}
                      </div>
                      <div>
                        <h4 class="text-sm font-bold text-slate-900">{{ r.customer_name }}</h4>
                        <p class="text-[10px] text-slate-500">{{ new Date(r.created_at).toLocaleDateString() }}</p>
                      </div>
                    </div>
                    <div class="flex gap-1">
                      <Star v-for="star in 5" :key="star" class="w-3 h-3" :class="star <= r.rating ? 'text-amber-400 fill-current' : 'text-slate-200'" />
                    </div>
                  </div>
                  <p class="text-sm text-slate-700 italic">"{{ r.comment }}"</p>
                </div>
              </div>
              <div v-else class="text-center py-12 mb-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <p class="text-sm font-medium text-slate-500">No reviews yet.</p>
              </div>

              <!-- Review Form -->
              <div class="bg-slate-50 p-8 rounded-xl border border-slate-200 max-w-2xl">
                <h3 class="text-base font-bold text-slate-900 mb-6">Write a Review</h3>
                <div v-if="reviewSuccessMessage" class="p-4 mb-6 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-lg border border-emerald-200">
                  {{ reviewSuccessMessage }}
                </div>
                <form @submit.prevent="submitReview" class="space-y-5">
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Your Name</label>
                    <input type="text" v-model="reviewForm.customer_name" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" />
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Rating</label>
                    <div class="flex gap-2">
                      <Star 
                        v-for="star in 5" 
                        :key="star" 
                        @click="reviewForm.rating = star"
                        class="w-6 h-6 cursor-pointer transition-colors" 
                        :class="star <= reviewForm.rating ? 'text-amber-400 fill-current' : 'text-slate-300 hover:text-amber-200'"
                      />
                    </div>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Comment</label>
                    <textarea rows="4" v-model="reviewForm.comment" required class="w-full rounded-lg border border-slate-300 p-4 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors"></textarea>
                  </div>
                  <button type="submit" :disabled="isSubmittingReview" class="px-6 py-2.5 bg-slate-900 text-white rounded-lg font-semibold text-sm hover:bg-slate-800 transition-colors disabled:opacity-50">
                    {{ isSubmittingReview ? 'Submitting...' : 'Submit Review' }}
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Related -->
        <div v-if="relatedProducts && relatedProducts.length > 0" class="mt-16 pb-16">
          <h2 class="text-xl font-bold text-slate-900 mb-8">Related Products</h2>
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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
            customer_name: reviewForm.value.customer_name || 'Guest',
            comment: reviewForm.value.comment,
            rating: reviewForm.value.rating
        }, {
            preserveScroll: true,
            onSuccess: () => {
                reviewForm.value.customer_name = '';
                reviewForm.value.comment = '';
                reviewForm.value.rating = 5;
                reviewSuccessMessage.value = usePage().props.translations?.['Thank you! Your review has been posted successfully.'] || 'Thank you! Your review has been posted successfully.';
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
