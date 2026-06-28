<template>
    <StoreLayout>
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-[1550px] mx-auto px-4 py-16">
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-600 rounded-full text-white text-[10px] font-black uppercase tracking-[0.3em] mb-4 shadow-xl shadow-red-600/20">
                        <Zap class="w-4 h-4" /> Limited Time
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 uppercase tracking-tighter italic mb-4">Command <span class="text-red-600">Offers</span></h1>
                    <p class="max-w-2xl text-xs font-bold text-slate-400 uppercase tracking-[0.2em] leading-relaxed">Engineered for value. Explore our latest technical discounts and promotional deals across all departments.</p>
                </div>
            </div>
        </div>

        <section class="py-16 bg-slate-50 min-h-[600px]">
            <div class="max-w-[1550px] mx-auto px-4">
                <!-- Search within offers -->
                <div class="mb-12 flex justify-center">
                    <div class="w-full max-w-xl relative group">
                        <Search class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-red-600 transition-colors" />
                        <input 
                            v-model="search"
                            @keyup.enter="handleSearch"
                            placeholder="Search within offers..."
                            class="w-full pl-14 pr-6 py-5 bg-white rounded-2xl border border-slate-100 outline-none focus:ring-4 focus:ring-red-600/5 focus:border-red-600 transition-all text-sm font-bold shadow-sm"
                        />
                    </div>
                </div>

                <div v-if="products.data.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                </div>

                <div v-else class="py-24 text-center bg-white rounded-[3rem] border border-slate-100 shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <ZapOff class="w-10 h-10 text-slate-200" />
                    </div>
                    <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight italic">{{ $t('No Active Offers Found') }}</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Check back soon for new technical discounts.</p>
                    <Link href="/shop" class="inline-block mt-8 px-8 py-4 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#00a651] transition-all">
                        Browse Full Catalog
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="products.last_page > 1" class="mt-16 flex justify-center gap-2">
                    <Link 
                        v-for="link in products.links" 
                        :key="link.label"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border"
                        :class="[
                            link.active ? 'bg-red-600 border-red-600 text-white shadow-lg' : 'bg-white border-slate-100 text-slate-400 hover:bg-slate-50',
                            !link.url ? 'opacity-30 cursor-not-allowed pointer-events-none' : ''
                        ]"
                    />
                </div>
            </div>
        </section>
    </StoreLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Zap, Search, ZapOff } from 'lucide-vue-next';

const props = defineProps({
    products: Object,
    filters: Object
});

const search = ref(props.filters.search || '');

const handleSearch = () => {
    router.get('/offers', { search: search.value }, { preserveState: true });
};
</script>
