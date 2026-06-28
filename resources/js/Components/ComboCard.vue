<template>
    <div class="group bg-white border border-slate-100 rounded-[32px] overflow-hidden hover:shadow-[0_40px_80px_-15px_rgba(0,166,81,0.15)] transition-all duration-700 flex flex-col relative h-full">
        <!-- Visual Section -->
        <div class="relative h-64 bg-slate-50 overflow-hidden p-8 flex items-center justify-center">
            <Link :href="`/combo/${combo.slug}`" class="w-full h-full flex items-center justify-center relative">
                <img 
                    :src="combo.image || 'https://placehold.co/800x600/135e96/ffffff?text=Combo+Bundle'" 
                    class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-1000" 
                />
                
                <!-- Bundle Glow -->
                <div class="absolute inset-0 bg-[#00a651]/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-full blur-3xl scale-75"></div>
            </Link>

            <!-- Save Badge -->
            <div class="absolute top-6 right-6 px-4 py-2 bg-[#00a651] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-green-500/20">
                {{ $t('Value Bundle') }}
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-8 flex flex-col flex-grow bg-white">
            <div class="flex flex-col items-center text-center flex-grow">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-tight mb-3 group-hover:text-[#00a651] transition-colors line-clamp-2 leading-tight">
                    {{ combo.name }}
                </h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest line-clamp-1 mb-8 opacity-60">
                    {{ combo.description }}
                </p>

                <div class="mt-auto flex flex-col items-center gap-6 w-full">
                    <div class="flex flex-col">
                        <span class="text-[9px] text-slate-300 font-extrabold uppercase tracking-[0.3em] mb-1">Total Package</span>
                        <span class="text-3xl font-black text-[#00a651] tracking-tighter">
                            ৳{{ combo.price.toLocaleString() }}
                        </span>
                    </div>

                    <Link 
                        :href="`/combo/${combo.slug}`" 
                        class="w-full py-4 bg-slate-900 text-white rounded-2xl hover:bg-[#00a651] transition-all duration-300 text-[10px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-lg shadow-slate-900/10 hover:shadow-[#00a651]/30 active:scale-[0.98]"
                    >
                        <Zap class="w-4 h-4" />
                        {{ $t('View Bundle') }}
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { Zap } from 'lucide-vue-next';

defineProps({
    combo: Object
});
</script>
