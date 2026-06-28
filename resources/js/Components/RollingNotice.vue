<template>
    <div v-if="notices && notices.length > 0" class="bg-[#002013] border-y border-white/5 py-3 relative overflow-hidden group">
        <div class="max-w-[1550px] mx-auto px-4 flex items-center">
            <!-- Label -->
            <div class="flex-shrink-0 flex items-center gap-2 bg-[#00a651] text-white px-3 py-1 rounded-md text-[9px] font-black uppercase tracking-widest mr-6 z-10 shadow-lg shadow-green-500/20">
                <Bell class="w-3.5 h-3.5" /> {{ $t('Notice') }}
            </div>

            <!-- Marquee Container -->
            <div class="flex-grow overflow-hidden relative">
                <div class="flex whitespace-nowrap animate-marquee group-hover:pause-marquee">
                    <span v-for="(notice, idx) in repeatedNotices" :key="idx" class="text-white text-[11px] font-bold uppercase tracking-wider mx-12 flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#00a651]"></span>
                        {{ notice.message }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Bell } from 'lucide-vue-next';

const page = usePage();
const notices = computed(() => page.props.notices || []);

// Repeat notices to ensure continuous scrolling
const repeatedNotices = computed(() => {
    if (!notices.value.length) return [];
    // Repeat the array a few times to fill the gap
    return [...notices.value, ...notices.value, ...notices.value, ...notices.value];
});
</script>

<style scoped>
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.animate-marquee {
    display: inline-flex;
    animation: marquee 30s linear infinite;
}

.pause-marquee {
    animation-play-state: paused;
}
</style>
