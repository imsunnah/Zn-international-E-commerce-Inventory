<template>
    <AdminLayout>
        <!-- Sticky/Top Header Section -->
        <div
            class="mb-6 flex justify-between items-center bg-white p-4 rounded-xl border border-slate-100 shadow-sm"
        >
            <div>
                <h1 class="text-xl font-bold text-[#003366]">Site Settings</h1>
                <p class="text-xs text-slate-400">
                    Update branding, configuration, and details
                </p>
            </div>
            <button
                @click="saveSettings"
                :disabled="form.processing"
                class="bg-[#FF6600] text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition-all font-semibold text-xs flex items-center shadow-sm disabled:opacity-50"
            >
                <Save class="w-4 h-4 mr-1.5" />
                {{ form.processing ? "Saving..." : "Save Changes" }}
            </button>
        </div>

        <!-- Notification Message -->
        <div
            v-if="$page.props.flash?.success"
            class="mb-4 bg-green-600 text-white p-3 rounded-lg flex items-center justify-between text-xs font-semibold shadow-sm"
        >
            <div class="flex items-center space-x-2">
                <CheckCircle class="w-4 h-4" />
                <span>{{ $page.props.flash.success }}</span>
            </div>
            <button
                @click="$page.props.flash.success = null"
                class="opacity-70 hover:opacity-100"
            >
                <X class="w-4 h-4" />
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Section: General Branding -->
            <div
                class="bg-white rounded-xl border border-slate-100 p-5 space-y-4 shadow-sm"
            >
                <div class="flex items-center space-x-2 border-b pb-2">
                    <SettingsIcon class="w-4 h-4 text-[#003366]" />
                    <h2 class="text-sm font-bold text-slate-800">
                        General Branding
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 mb-1"
                            >Site Name (EN)</label
                        >
                        <input
                            v-model="form.site_name_en"
                            type="text"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-[#003366] outline-none"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 mb-1"
                            >Site Name (BN)</label
                        >
                        <input
                            v-model="form.site_name_bn"
                            type="text"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-[#003366] outline-none"
                        />
                    </div>
                </div>

                <!-- Row: Logo and Favicon compact selectors -->
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 mb-1"
                            >Site Logo</label
                        >
                        <div
                            class="flex items-center gap-3 bg-slate-50 p-2 rounded-lg border border-slate-100"
                        >
                            <div
                                class="w-10 h-10 bg-white rounded border border-slate-200 flex items-center justify-center overflow-hidden shrink-0"
                            >
                                <img
                                    v-if="
                                        logoPreview || form.existing_site_logo
                                    "
                                    :src="
                                        logoPreview || form.existing_site_logo
                                    "
                                    class="max-h-full max-w-full object-contain"
                                />
                                <ImageIcon
                                    v-else
                                    class="w-4 h-4 text-slate-300"
                                />
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 w-full">
                                <input
                                    type="file"
                                    @input="handleLogoUpload"
                                    class="text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-[#003366] file:text-white cursor-pointer w-full"
                                />
                                <button
                                    @click.prevent="openGallery('logo')"
                                    class="text-[10px] text-left text-[#003366] font-semibold hover:underline"
                                >
                                    Pick from Gallery
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 mb-1"
                            >Favicon</label
                        >
                        <div
                            class="flex items-center gap-3 bg-slate-50 p-2 rounded-lg border border-slate-100"
                        >
                            <div
                                class="w-10 h-10 bg-white rounded border border-slate-200 flex items-center justify-center overflow-hidden shrink-0"
                            >
                                <img
                                    v-if="
                                        faviconPreview ||
                                        form.existing_site_favicon
                                    "
                                    :src="
                                        faviconPreview ||
                                        form.existing_site_favicon
                                    "
                                    class="h-6 w-6 object-contain"
                                />
                                <ImageIcon
                                    v-else
                                    class="w-4 h-4 text-slate-300"
                                />
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 w-full">
                                <input
                                    type="file"
                                    @input="handleFaviconUpload"
                                    class="text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-[#003366] file:text-white cursor-pointer w-full"
                                />
                                <button
                                    @click.prevent="openGallery('favicon')"
                                    class="text-[10px] text-left text-[#003366] font-semibold hover:underline"
                                >
                                    Pick from Gallery
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Home Sliders -->
            <div
                class="bg-white rounded-xl border border-slate-100 p-5 space-y-3 shadow-sm"
            >
                <div class="flex items-center justify-between border-b pb-2">
                    <div class="flex items-center space-x-2">
                        <ImageIcon class="w-4 h-4 text-[#003366]" />
                        <h2 class="text-sm font-bold text-slate-800">
                            Home Sliders
                        </h2>
                    </div>
                    <div class="flex gap-3 text-[11px] font-semibold">
                        <button
                            @click.prevent="openGallery('slider')"
                            class="text-[#003366] hover:underline flex items-center gap-0.5"
                        >
                            <Plus class="w-3 h-3" /> Gallery
                        </button>
                        <button
                            @click.prevent="triggerSliderUpload"
                            class="text-[#FF6600] hover:underline flex items-center gap-0.5"
                        >
                            <Plus class="w-3 h-3" /> Upload
                        </button>
                    </div>
                </div>
                <input
                    type="file"
                    multiple
                    ref="sliderInput"
                    @input="handleSliderUpload"
                    class="hidden"
                />

                <div
                    class="grid grid-cols-4 gap-2 max-h-[115px] overflow-y-auto pr-1"
                >
                    <div
                        v-for="(img, index) in sliderImages"
                        :key="index"
                        class="relative group aspect-video rounded border border-slate-100 overflow-hidden bg-slate-50"
                    >
                        <img :src="img" class="w-full h-full object-cover" />
                        <button
                            @click.prevent="removeSlider(index)"
                            class="absolute inset-0 bg-black/40 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            <Trash2 class="w-3 h-3" />
                        </button>
                    </div>
                    <div
                        v-if="sliderImages.length === 0"
                        class="col-span-4 py-8 text-center border border-dashed border-slate-200 rounded text-slate-400 text-xs"
                    >
                        No slider images loaded.
                    </div>
                </div>
            </div>

            <!-- Section: Hero Overlay Controls -->
            <div
                class="bg-white rounded-xl border border-slate-100 p-5 space-y-4 shadow-sm md:col-span-2"
            >
                <div class="flex items-center justify-between border-b pb-2">
                    <div class="flex items-center space-x-2">
                        <Layout class="w-4 h-4 text-[#003366]" />
                        <h2 class="text-sm font-bold text-slate-800">
                            Hero Overlay Text
                        </h2>
                    </div>
                    <label
                        class="relative inline-flex items-center cursor-pointer scale-90"
                    >
                        <input
                            type="checkbox"
                            v-model="form.hero_slider_text_show"
                            class="sr-only peer"
                        />
                        <div
                            class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#FF6600]"
                        ></div>
                    </label>
                </div>

                <div
                    v-if="form.hero_slider_text_show"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4"
                >
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 mb-1"
                            >Overlay HTML Text (EN)</label
                        >
                        <textarea
                            v-model="form.hero_slider_text_en"
                            rows="2"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-[#003366] outline-none font-mono"
                            placeholder="<h1>Title</h1>"
                        ></textarea>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-slate-500 mb-1"
                            >Overlay HTML Text (BN)</label
                        >
                        <textarea
                            v-model="form.hero_slider_text_bn"
                            rows="2"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-[#003366] outline-none font-mono"
                            placeholder="<h1>শিরোনাম</h1>"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Section: Promo Banners -->
            <div
                class="bg-white rounded-xl border border-slate-100 p-5 space-y-4 shadow-sm md:col-span-2"
            >
                <div class="flex items-center space-x-2 border-b pb-2">
                    <Layout class="w-4 h-4 text-[#003366]" />
                    <h2 class="text-sm font-bold text-slate-800">
                        Promo Cards Setup
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card 1 -->
                    <div
                        class="flex gap-4 p-3 bg-slate-50/60 border border-slate-100 rounded-xl"
                    >
                        <div
                            class="w-24 h-24 bg-white rounded-lg flex flex-col items-center justify-center overflow-hidden border border-slate-200 shrink-0 relative group"
                        >
                            <img
                                v-if="
                                    heroPromo1Preview ||
                                    form.existing_hero_promo_1_image
                                "
                                :src="
                                    heroPromo1Preview ||
                                    form.existing_hero_promo_1_image
                                "
                                class="w-full h-full object-cover"
                            />
                            <ImageIcon v-else class="w-5 h-5 text-slate-300" />
                            <div
                                class="absolute inset-0 bg-black/60 flex flex-col gap-1 items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                            >
                                <button
                                    @click.prevent="openGallery('hero_promo_1')"
                                    class="text-[9px] text-white underline font-bold"
                                >
                                    Gallery
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 w-full">
                            <div class="col-span-2">
                                <input
                                    type="file"
                                    @input="handleHeroPromo1Upload"
                                    class="text-[10px] text-slate-500 w-full"
                                />
                            </div>
                            <div>
                                <input
                                    v-model="form.hero_promo_1_title"
                                    type="text"
                                    class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none"
                                    placeholder="Title"
                                />
                            </div>
                            <div>
                                <input
                                    v-model="form.hero_promo_1_subtitle"
                                    type="text"
                                    class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none"
                                    placeholder="Subtitle"
                                />
                            </div>
                            <div class="col-span-2">
                                <input
                                    v-model="form.hero_promo_1_url"
                                    type="text"
                                    class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none"
                                    placeholder="Destination Link / URL"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="flex gap-4 p-3 bg-slate-50/60 border border-slate-100 rounded-xl"
                    >
                        <div
                            class="w-24 h-24 bg-white rounded-lg flex flex-col items-center justify-center overflow-hidden border border-slate-200 shrink-0 relative group"
                        >
                            <img
                                v-if="
                                    heroPromo2Preview ||
                                    form.existing_hero_promo_2_image
                                "
                                :src="
                                    heroPromo2Preview ||
                                    form.existing_hero_promo_2_image
                                "
                                class="w-full h-full object-cover"
                            />
                            <ImageIcon v-else class="w-5 h-5 text-slate-300" />
                            <div
                                class="absolute inset-0 bg-black/60 flex flex-col gap-1 items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                            >
                                <button
                                    @click.prevent="openGallery('hero_promo_2')"
                                    class="text-[9px] text-white underline font-bold"
                                >
                                    Gallery
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 w-full">
                            <div class="col-span-2">
                                <input
                                    type="file"
                                    @input="handleHeroPromo2Upload"
                                    class="text-[10px] text-slate-500 w-full"
                                />
                            </div>
                            <div>
                                <input
                                    v-model="form.hero_promo_2_title"
                                    type="text"
                                    class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none"
                                    placeholder="Title"
                                />
                            </div>
                            <div>
                                <input
                                    v-model="form.hero_promo_2_subtitle"
                                    type="text"
                                    class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none"
                                    placeholder="Subtitle"
                                />
                            </div>
                            <div class="col-span-2">
                                <input
                                    v-model="form.hero_promo_2_url"
                                    type="text"
                                    class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none"
                                    placeholder="Destination Link / URL"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Contact & Logistics Framework -->
            <div
                class="bg-white rounded-xl border border-slate-100 p-5 space-y-4 shadow-sm md:col-span-2"
            >
                <div class="flex items-center space-x-2 border-b pb-2">
                    <Info class="w-4 h-4 text-[#003366]" />
                    <h2 class="text-sm font-bold text-slate-800">
                        Logistics, Contacts & Footer Settings
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                    <div class="md:col-span-2">
                        <label class="block font-semibold text-slate-500 mb-1"
                            >About Description (EN)</label
                        >
                        <textarea
                            v-model="form.footer_about_en"
                            rows="2"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-1.5 outline-none"
                        ></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-semibold text-slate-500 mb-1"
                            >About Description (BN)</label
                        >
                        <textarea
                            v-model="form.footer_about_bn"
                            rows="2"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-1.5 outline-none"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >Address (EN)</label
                        >
                        <input
                            v-model="form.footer_address_en"
                            type="text"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 outline-none"
                        />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >Address (BN)</label
                        >
                        <input
                            v-model="form.footer_address_bn"
                            type="text"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 outline-none"
                        />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >Phone</label
                        >
                        <input
                            v-model="form.footer_phone"
                            type="text"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 outline-none"
                        />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >Email</label
                        >
                        <input
                            v-model="form.footer_email"
                            type="text"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >Inside Dhaka (৳)</label
                        >
                        <input
                            v-model="form.delivery_charge_inside_dhaka"
                            type="number"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 outline-none"
                        />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >Outside Dhaka (৳)</label
                        >
                        <input
                            v-model="form.delivery_charge_outside_dhaka"
                            type="number"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 outline-none"
                        />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >Facebook</label
                        >
                        <input
                            v-model="form.social_facebook"
                            type="text"
                            class="w-full bg-slate-50 border border-slate-200/60 rounded-lg px-3 py-2 outline-none"
                            placeholder="https://..."
                        />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-500 mb-1"
                            >YouTube / Instagram</label
                        >
                        <div class="flex gap-1">
                            <input
                                v-model="form.social_youtube"
                                type="text"
                                class="w-1/2 bg-slate-50 border border-slate-200/60 rounded-lg px-2 py-2 outline-none"
                                placeholder="YT"
                            />
                            <input
                                v-model="form.social_instagram"
                                type="text"
                                class="w-1/2 bg-slate-50 border border-slate-200/60 rounded-lg px-2 py-2 outline-none"
                                placeholder="IG"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <MediaPicker
            :show="showGallery"
            @close="showGallery = false"
            @select="handleGallerySelection"
        />
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import MediaPicker from "@/Components/MediaPicker.vue";
import {
    Save,
    Settings as SettingsIcon,
    Image as ImageIcon,
    Info,
    Plus,
    Trash2,
    CheckCircle,
    X,
    Truck,
    Layout,
} from "lucide-vue-next";

const props = defineProps({
    settings: Object,
});

const getVal = (key, locale = "en") => {
    for (const group in props.settings) {
        const found = props.settings[group].find((s) => s.key === key);
        if (found) {
            if (locale === "bn") return found.value_bn || "";
            return found.value_en || found.value || "";
        }
    }
    return "";
};

const sliderImages = ref(JSON.parse(getVal("slider_images") || "[]"));
const logoPreview = ref(null);
const faviconPreview = ref(null);
const heroPromo1Preview = ref(null);
const heroPromo2Preview = ref(null);
const sliderInput = ref(null);
const showGallery = ref(false);
const galleryTarget = ref(""); // 'logo', 'favicon', 'slider', 'hero_promo_1', 'hero_promo_2'

const form = useForm({
    site_name_en: getVal("site_name", "en"),
    site_name_bn: getVal("site_name", "bn"),
    site_logo: null,
    existing_site_logo: getVal("site_logo"),
    site_favicon: null,
    existing_site_favicon: getVal("site_favicon"),
    footer_about_en: getVal("footer_about", "en"),
    footer_about_bn: getVal("footer_about", "bn"),
    footer_address_en: getVal("footer_address", "en"),
    footer_address_bn: getVal("footer_address", "bn"),
    footer_phone: getVal("footer_phone"),
    footer_email: getVal("footer_email"),
    social_facebook: getVal("social_facebook"),
    social_instagram: getVal("social_instagram"),
    social_youtube: getVal("social_youtube"),
    delivery_charge_inside_dhaka: getVal("delivery_charge_inside_dhaka"),
    delivery_charge_outside_dhaka: getVal("delivery_charge_outside_dhaka"),
    slider_upload: [],
    slider_images: sliderImages.value,
    hero_promo_1_image: null,
    existing_hero_promo_1_image: getVal("hero_promo_1_image"),
    hero_promo_1_title: getVal("hero_promo_1_title"),
    hero_promo_1_subtitle: getVal("hero_promo_1_subtitle"),
    hero_promo_1_url: getVal("hero_promo_1_url"),
    hero_promo_2_image: null,
    existing_hero_promo_2_image: getVal("hero_promo_2_image"),
    hero_promo_2_title: getVal("hero_promo_2_title"),
    hero_promo_2_subtitle: getVal("hero_promo_2_subtitle"),
    hero_promo_2_url: getVal("hero_promo_2_url"),
    hero_slider_text_show: getVal("hero_slider_text_show") == "1",
    hero_slider_text_en: getVal("hero_slider_text", "en"),
    hero_slider_text_bn: getVal("hero_slider_text", "bn"),
});

const handleLogoUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.site_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleFaviconUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.site_favicon = file;
        faviconPreview.value = URL.createObjectURL(file);
    }
};

const handleHeroPromo1Upload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.hero_promo_1_image = file;
        heroPromo1Preview.value = URL.createObjectURL(file);
    }
};

const handleHeroPromo2Upload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.hero_promo_2_image = file;
        heroPromo2Preview.value = URL.createObjectURL(file);
    }
};

const triggerSliderUpload = () => {
    sliderInput.value.click();
};

const handleSliderUpload = (e) => {
    const files = Array.from(e.target.files);
    files.forEach((file) => {
        form.slider_upload.push(file);
        sliderImages.value.push(URL.createObjectURL(file));
    });
};

const removeSlider = (index) => {
    sliderImages.value.splice(index, 1);
    form.slider_images = [...sliderImages.value];
};

const openGallery = (target) => {
    galleryTarget.value = target;
    showGallery.value = true;
};

const handleGallerySelection = (item) => {
    const url = item.url;
    if (galleryTarget.value === "logo") {
        form.site_logo = url;
        form.existing_site_logo = url;
        logoPreview.value = null;
    } else if (galleryTarget.value === "favicon") {
        form.site_favicon = url;
        form.existing_site_favicon = url;
        faviconPreview.value = null;
    } else if (galleryTarget.value === "hero_promo_1") {
        form.hero_promo_1_image = url;
        form.existing_hero_promo_1_image = url;
        heroPromo1Preview.value = null;
    } else if (galleryTarget.value === "hero_promo_2") {
        form.hero_promo_2_image = url;
        form.existing_hero_promo_2_image = url;
        heroPromo2Preview.value = null;
    } else if (galleryTarget.value === "slider") {
        sliderImages.value.push(url);
        form.slider_images = [...sliderImages.value];
    }
    showGallery.value = false;
};

const saveSettings = () => {
    form.post("/admin/settings", {
        onSuccess: () => {
            logoPreview.value = null;
            faviconPreview.value = null;
            form.slider_upload = [];
        },
    });
};
</script>
