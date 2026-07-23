<template>
    <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col relative h-full hover:-translate-y-0.5">
        <!-- Visual Section -->
        <div class="relative h-56 bg-slate-50 p-6 flex items-center justify-center mix-blend-multiply">
            <Link :href="`/combo/${combo.slug}`" class="w-full h-full flex items-center justify-center relative">
                <img 
                    :src="combo.image || 'https://placehold.co/800x600/f8fafc/64748b?text=Combo+Bundle'" 
                    class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500" 
                />
            </Link>
            
            <!-- Save Badge -->
            <div class="absolute top-4 right-4 px-3 py-1 bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg shadow-sm">
                Value Bundle
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6 flex flex-col flex-grow bg-white">
            <div class="flex flex-col h-full text-left">
                <h3 class="text-sm md:text-base font-bold text-slate-900 leading-snug mb-2 group-hover:text-emerald-600 transition-colors line-clamp-2">
                    {{ combo.name }}
                </h3>
                <p class="text-xs font-medium text-slate-500 line-clamp-2 mb-6">
                    {{ combo.description }}
                </p>

                <div class="mt-auto flex flex-col sm:flex-row sm:items-end justify-between gap-4 w-full">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Package Price</span>
                        <span class="text-xl font-bold text-slate-900">
                            ৳{{ combo.price.toLocaleString() }}
                        </span>
                    </div>

                    <Link 
                        :href="`/combo/${combo.slug}`" 
                        class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors text-xs font-semibold flex items-center justify-center gap-2"
                    >
                        <Zap class="w-4 h-4" />
                        View
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
