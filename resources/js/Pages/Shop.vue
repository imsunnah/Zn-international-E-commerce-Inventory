<template>
    <StoreLayout>
        <div class="bg-[#f8fafc] min-h-screen py-12 font-sans overflow-hidden">
            <!-- Premium Background Glows -->
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[-5%] w-[30%] h-[30%] bg-emerald-500/5 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[10%] right-[-5%] w-[30%] h-[30%] bg-emerald-500/5 rounded-full blur-[120px]"></div>
            </div>

            <div class="max-w-[1700px] mx-auto px-4 md:px-8 relative z-10">
                
                <!-- Reference Header -->
                <div class="mb-16">
                    <nav class="flex items-center gap-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8">
                        <Link href="/" class="hover:text-emerald-600 transition-colors">Infrastructure</Link>
                        <ChevronRight class="w-3 h-3 opacity-30" />
                        <span class="text-slate-900 italic underline decoration-emerald-500 decoration-2 underline-offset-4">{{ activeCategoryName }}</span>
                    </nav>

                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
                        <div class="space-y-4">
                            <h1 class="text-5xl md:text-7xl font-black text-slate-900 uppercase tracking-tighter italic leading-none">
                                Tech <br/> <span class="text-emerald-600">Inventory</span>
                            </h1>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">{{ activeCategoryName }} scope initialized</p>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-4 bg-white/80 backdrop-blur-md p-2 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                            <div class="flex items-center gap-2 px-6 py-3 border-r border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Active Nodes:</span>
                                <span class="text-[11px] font-black text-slate-900 tracking-tighter">{{ products.total }}</span>
                            </div>
                            <div class="flex items-center gap-4 px-6 py-2">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sort Protocol:</span>
                                <select 
                                    v-model="sortBy"
                                    class="text-[10px] font-black text-slate-900 uppercase tracking-widest bg-transparent border-none focus:ring-0 outline-none cursor-pointer"
                                >
                                    <option value="Latest Arrival">Sync Optimized</option>
                                    <option value="Price: Low to High">Value Ascending</option>
                                    <option value="Price: High to Low">Value Descending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-12">
                    
                    <!-- Glassmorphism Sidebar -->
                    <div class="w-full lg:w-80 flex-shrink-0">
                        <div class="bg-white/40 backdrop-blur-xl p-10 rounded-[3rem] border border-white shadow-2xl shadow-slate-200/50 space-y-12 sticky top-32">
                            
                            <!-- Classification -->
                            <div v-if="categories?.length">
                                <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.5em] italic mb-8 flex items-center gap-3">
                                    <SlidersHorizontal class="w-4 h-4" />
                                    Segment Scope
                                </h3>
                                <div class="grid grid-cols-1 gap-2">
                                    <button 
                                        v-for="cat in categories" :key="cat.id" 
                                        @click="toggleCategory(cat.slug)"
                                        class="flex items-center justify-between group p-4 rounded-2xl transition-all border border-transparent"
                                        :class="selectedCategories.includes(cat.slug) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'hover:bg-white hover:border-slate-100'"
                                    >
                                        <span class="text-[10px] font-black uppercase tracking-widest">{{ cat.name }}</span>
                                        <Check v-if="selectedCategories.includes(cat.slug)" class="w-3.5 h-3.5" />
                                        <ChevronRight v-else class="w-3.5 h-3.5 opacity-20 group-hover:translate-x-1 transition-transform" />
                                    </button>
                                </div>
                            </div>

                            <!-- Value Thresholds -->
                            <div>
                                <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.5em] italic mb-8">
                                    Price parameters
                                </h3>
                                <div class="space-y-8">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Threshold L</label>
                                            <input v-model.number="minPrice" type="number" class="w-full p-4 bg-white/50 border border-slate-100 rounded-2xl text-[10px] font-black outline-none focus:border-emerald-500 transition-colors" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Threshold H</label>
                                            <input v-model.number="maxPrice" type="number" class="w-full p-4 bg-white/50 border border-slate-100 rounded-2xl text-[10px] font-black outline-none focus:border-emerald-500 transition-colors" />
                                        </div>
                                    </div>
                                    
                                    <!-- Range visualizer -->
                                    <div class="h-1 bg-slate-100 rounded-full relative overflow-hidden">
                                        <div class="absolute inset-y-0 bg-emerald-500 w-1/2 left-1/4"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Industrial Brands -->
                            <div v-if="brands?.length">
                                <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.5em] italic mb-8">
                                    Manufacturers
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <button 
                                        v-for="brand in brands" :key="brand.id" 
                                        @click="toggleBrand(brand.slug)"
                                        class="px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest transition-all border"
                                        :class="selectedBrands.includes(brand.slug) ? 'bg-slate-900 border-slate-900 text-white shadow-xl' : 'bg-white border-slate-100 text-slate-400 hover:text-emerald-600 hover:border-emerald-500/30'"
                                    >
                                        {{ brand.name }}
                                    </button>
                                </div>
                            </div>

                            <!-- Flush Protocol -->
                            <button 
                                @click="resetFilters" 
                                class="w-full py-5 bg-white border-2 border-slate-900 text-slate-900 rounded-[2.5rem] text-[10px] font-black uppercase tracking-[0.4em] hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-95"
                            >
                                Reset Matrix
                            </button>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="flex-grow">
                        <transition-group 
                            v-if="products.data.length > 0" 
                            name="grid-fade" 
                            tag="div" 
                            class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8"
                        >
                            <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                        </transition-group>

                        <!-- Cyber Empty State -->
                        <div v-else class="py-48 text-center bg-white/50 rounded-[4rem] border border-white shadow-2xl relative overflow-hidden group">
                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative z-10">
                                <div class="w-32 h-32 bg-white rounded-[40px] shadow-xl flex items-center justify-center mx-auto mb-10 group-hover:rotate-[15deg] transition-transform duration-700">
                                    <Package class="w-12 h-12 text-emerald-600/20" />
                                </div>
                                <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic mb-6">Matrix Empty</h2>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] max-w-sm mx-auto leading-relaxed px-8">No hardware matches registered in the current sector.</p>
                                <button @click="resetFilters" class="mt-12 px-12 py-5 bg-emerald-600 text-white rounded-[2rem] font-black uppercase tracking-[0.4em] text-[10px] hover:bg-emerald-500 shadow-xl shadow-emerald-500/20 transition-all active:scale-95">Flush System</button>
                            </div>
                        </div>

                        <!-- Industrial Pagination -->
                        <div v-if="products.last_page > 1" class="mt-32 flex justify-center items-center gap-4">
                            <Link v-for="(link, index) in products.links" :key="index" :href="link.url || '#'" v-html="link.label" 
                                :class="[
                                    link.active ? 'bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 w-16 h-16 rounded-[24px]' : 'bg-white text-slate-400 hover:text-emerald-600 border border-slate-100 w-14 h-14 rounded-[20px]', 
                                    !link.url ? 'opacity-10 pointer-events-none' : ''
                                ]"
                                class="flex items-center justify-center text-[11px] font-black transition-all rotate-3 hover:rotate-0">
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { ChevronRight, Check, Package, SlidersHorizontal } from 'lucide-vue-next';

const props = defineProps({
    products: Object,
    filters: Object,
    categories: Array,
    brands: Array
});

const selectedCategories = ref(props.filters.category ? props.filters.category.split(',') : []);
const selectedBrands = ref(props.filters.brand ? props.filters.brand.split(',') : []);
const minPrice = ref(props.filters.min_price ? parseInt(props.filters.min_price) : 0);
const maxPrice = ref(props.filters.max_price ? parseInt(props.filters.max_price) : 5000000);
const sortBy = ref(props.filters.sort || 'Latest Arrival');

const activeCategoryName = computed(() => {
    if (props.filters.sub_category) {
        return props.filters.sub_category.replace('-', ' ').toUpperCase();
    }
    if (selectedCategories.value.length === 1) {
        const cat = props.categories.find(c => c.slug === selectedCategories.value[0]);
        return cat ? cat.name.toUpperCase() : selectedCategories.value[0].replace('-', ' ').toUpperCase();
    }
    if (selectedCategories.value.length > 1) {
        return 'Multi-Sector';
    }
    return 'Total Hardware';
});

const toggleCategory = (slug) => {
    const idx = selectedCategories.value.indexOf(slug);
    if (idx > -1) selectedCategories.value.splice(idx, 1);
    else selectedCategories.value.push(slug);
    triggerSearch();
};

const toggleBrand = (slug) => {
    const idx = selectedBrands.value.indexOf(slug);
    if (idx > -1) selectedBrands.value.splice(idx, 1);
    else selectedBrands.value.push(slug);
    triggerSearch();
};

const triggerSearch = () => {
    router.get('/shop', {
        category: selectedCategories.value.join(','),
        brand: selectedBrands.value.join(','),
        min_price: minPrice.value,
        max_price: maxPrice.value,
        sort: sortBy.value,
        sub_category: selectedCategories.value.length === 0 ? props.filters.sub_category : null
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

watch(sortBy, triggerSearch);
watch([minPrice, maxPrice], () => {
    // Debounce would be better, but for now direct search
    triggerSearch();
});

const resetFilters = () => {
    selectedCategories.value = [];
    selectedBrands.value = [];
    minPrice.value = 0;
    maxPrice.value = 5000000;
    sortBy.value = 'Latest Arrival';
    router.get('/shop');
};
</script>

<style scoped>
.grid-fade-enter-active, .grid-fade-leave-active {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.grid-fade-enter-from, .grid-fade-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>
