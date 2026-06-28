<template>
    <AdminLayout>
        <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">Edit News</h1>
                <p class="text-sm text-slate-500">Modify existing news article</p>
            </div>
            <Link :href="route('admin.news.index')" class="bg-slate-100 text-[#003366] px-6 py-2.5 rounded-lg hover:bg-slate-200 transition-all font-bold text-sm flex items-center">
                <ChevronLeft class="w-4 h-4 mr-2" /> Back
            </Link>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Title</label>
                            <input v-model="form.title" type="text" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none font-bold text-[#003366]">
                            <div v-if="form.errors.title" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.title }}</div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Content</label>
                            <textarea v-model="form.content" rows="12" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none font-medium text-slate-600"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-[#003366] uppercase tracking-tighter italic mb-6">Publication Image</h3>
                    
                    <div class="space-y-4">
                        <div class="aspect-video bg-slate-50 rounded-2xl flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 group relative">
                            <img v-if="form.image" :src="form.image" class="w-full h-full object-cover" />
                            <div v-else class="flex flex-col items-center gap-2 opacity-30">
                                <ImageIcon class="w-8 h-8" />
                                <span class="text-[10px] font-black uppercase">Selected Asset</span>
                            </div>
                        </div>
                        
                        <button @click.prevent="showGallery = true" type="button" class="w-full py-4 bg-[#003366] text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-xl shadow-blue-900/10 active:scale-95 flex items-center justify-center gap-2">
                            <Plus class="w-4 h-4" /> Change From Gallery
                        </button>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-[#003366] uppercase tracking-tighter italic mb-6">Settings</h3>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div>
                            <h4 class="text-xs font-bold text-[#003366]">Visibility</h4>
                            <p class="text-[10px] text-slate-500">Show on front page</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF6600]"></div>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="w-full mt-8 bg-[#003366] text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-xl shadow-blue-900/20 disabled:opacity-50 active:scale-95">
                        Update News
                    </button>
                </div>
            </div>
        </form>

        <MediaPicker v-if="showGallery" :show="showGallery" @close="showGallery = false" @select="handleMediaSelect" />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MediaPicker from '@/Components/MediaPicker.vue';
import { ChevronLeft, Image as ImageIcon, Plus } from 'lucide-vue-next';

const props = defineProps({
    news: Object
});

const showGallery = ref(false);

const form = useForm({
    title: props.news.title,
    content: props.news.content,
    image: props.news.image,
    is_active: props.news.is_active == 1,
});

const handleMediaSelect = (item) => {
    form.image = item.url;
};

const submit = () => {
    form.put(route('admin.news.update', props.news.id));
};
</script>
