<template>
    <StoreLayout>
        <div class="bg-white min-h-screen">
            <!-- Header Image -->
            <div class="relative h-[40vh] md:h-[60vh] overflow-hidden">
                <img :src="news.image" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#003366] via-[#003366]/40 to-transparent"></div>
                
                <div class="absolute inset-0 flex items-end pb-16">
                    <div class="max-w-[1550px] mx-auto px-4 w-full">
                        <div class="max-w-4xl">
                            <div class="flex items-center gap-4 mb-6">
                                <span class="px-4 py-2 bg-[#FF6600] text-white text-[10px] font-black uppercase tracking-widest rounded-xl italic shadow-lg shadow-orange-600/30">News & Updates</span>
                                <span class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em] italic">{{ new Date(news.created_at).toLocaleDateString('default', { day: 'numeric', month: 'long', year: 'numeric' }) }}</span>
                            </div>
                            <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter italic leading-[0.9]">
                                {{ news.title }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-[1550px] mx-auto px-4 py-20">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                    <div class="lg:col-span-8">
                        <div class="prose prose-slate prose-lg max-w-none">
                            <div v-html="formattedContent" class="text-slate-600 font-medium leading-relaxed space-y-6"></div>
                        </div>
                        
                        <!-- Share Section -->
                        <div class="mt-20 pt-10 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-black text-[#003366] uppercase tracking-widest italic">Share this story</span>
                                <div class="flex gap-2">
                                    <button class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-[#003366] hover:bg-[#003366] hover:text-white transition-all shadow-sm">
                                        <Facebook class="w-4 h-4" />
                                    </button>
                                    <button class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-[#003366] hover:bg-[#003366] hover:text-white transition-all shadow-sm">
                                        <Twitter class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                            
                            <Link href="/" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-[#FF6600] transition-colors flex items-center gap-2 italic">
                                <ArrowLeft class="w-3 h-3" /> Back to Store
                            </Link>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-4 space-y-12">
                        <div class="bg-slate-50 rounded-[2.5rem] p-10">
                            <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight italic mb-6">About ZN International</h3>
                            <p class="text-[11px] text-slate-500 font-bold leading-relaxed uppercase tracking-wide">
                                We are committed to bringing the freshest organic produce and premium grocery items directly from our partner farms to your doorstep. Our mission is to provide quality you can trust and service you can rely on.
                            </p>
                        </div>

                        <div class="bg-[#003366] rounded-[2.5rem] p-10 text-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-8 opacity-10 transition-transform duration-700 group-hover:scale-150 group-hover:-rotate-12">
                                <ShoppingBag class="w-32 h-32" />
                            </div>
                            <h3 class="text-xl font-black uppercase tracking-tight italic mb-4 relative z-10">Premium Shopping</h3>
                            <p class="text-[10px] text-white/70 font-bold uppercase tracking-widest mb-8 relative z-10">Explore our latest arrivals and exclusive organic bundles.</p>
                            <Link href="/shop" class="inline-flex items-center gap-3 bg-[#FF6600] text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest italic shadow-xl shadow-orange-900/40 hover:bg-white hover:text-[#003366] transition-all relative z-10">
                                Shop Now <ArrowRight class="w-3 h-3" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { ArrowLeft, Facebook, Twitter, ShoppingBag, ArrowRight } from 'lucide-vue-next';

const props = defineProps({
    news: Object
});

const formattedContent = computed(() => {
    return props.news.content.replace(/\n/g, '<br>');
});
</script>

<style scoped>
:deep(br) {
    display: block;
    content: "";
    margin-top: 1.5rem;
}
</style>
