<template>
    <StoreLayout>
        <div
            class="bg-white min-h-screen py-8 font-sans antialiased text-slate-650 selection:bg-[#00a651] selection:text-white"
        >
            <div
                class="max-w-[1300px] mx-auto px-4 md:px-8 xl:px-12 relative z-10"
            >
                <!-- Breadcrumbs & Header Section -->
                <div class="mb-8">
                    <nav
                        class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-3"
                    >
                        <Link
                            href="/"
                            class="hover:text-[#00a651] transition-colors decoration-transparent"
                            >Home</Link
                        >
                        <ChevronRight class="w-3.5 h-3.5 text-slate-300" />
                        <span class="text-slate-800 font-bold">{{
                            activeCategoryName
                        }}</span>
                    </nav>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-slate-200"
                    >
                        <div>
                            <h1
                                class="text-xl md:text-2xl font-black tracking-tight text-slate-900 uppercase"
                            >
                                {{ activeCategoryName }}
                            </h1>
                            <p class="text-xs text-slate-500 mt-1">
                                Reviewing
                                {{
                                    activeCategoryName.toLowerCase()
                                }}
                                catalog.
                            </p>
                        </div>

                        <!-- Toolbar Controls -->
                        <div
                            class="flex items-center gap-3 bg-white p-1.5 rounded border border-slate-200 shadow-sm self-start sm:self-auto"
                        >
                            <div
                                class="flex items-center gap-1.5 px-3 py-1 border-r border-slate-100 text-xs font-semibold text-slate-500"
                            >
                                <span>Total Products:</span>
                                <span class="text-slate-900 font-black">{{
                                    products.total
                                }}</span>
                            </div>
                            <div
                                class="flex items-center gap-2 px-3 py-1 text-xs font-semibold text-slate-500"
                            >
                                <span>Sort by:</span>
                                <select
                                    v-model="sortBy"
                                    class="text-slate-900 font-bold bg-transparent border-none p-0 focus:ring-0 outline-none cursor-pointer text-xs"
                                >
                                    <option value="Latest Arrival">
                                        Latest Arrival
                                    </option>
                                    <option value="Price: Low to High">
                                        Price: Low to High
                                    </option>
                                    <option value="Price: High to Low">
                                        Price: High to Low
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Section Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
                    <!-- Sidebar Filters Panel -->
                    <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-8">
                        <!-- Category Filter Block -->
                        <div
                            v-if="categories?.length"
                            class="bg-white p-5 rounded border border-slate-200 shadow-sm"
                        >
                            <h3
                                class="text-xs font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2"
                            >
                                <SlidersHorizontal
                                    class="w-3.5 h-3.5 text-slate-400"
                                />
                                Categories
                            </h3>
                            <div class="space-y-1 max-h-60 overflow-y-auto pr-1">
                                <button
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    @click="toggleCategory(cat.slug)"
                                    class="w-full flex items-center justify-between px-3 py-2 rounded text-xs font-bold transition-all text-left border-none cursor-pointer"
                                    :class="
                                        selectedCategories.includes(cat.slug)
                                            ? 'bg-[#00a651] text-white shadow-sm'
                                            : 'bg-transparent text-slate-600 hover:bg-slate-50 hover:text-[#00a651]'
                                    "
                                >
                                    <span>{{ cat.name }}</span>
                                    <Check
                                        v-slot:default
                                        v-if="
                                            selectedCategories.includes(
                                                cat.slug,
                                            )
                                        "
                                        class="w-3.5 h-3.5 text-white"
                                    />
                                </button>
                            </div>
                        </div>

                        <!-- Price Range Block -->
                        <div
                            class="bg-white p-5 rounded border border-slate-200 shadow-sm"
                        >
                            <h3
                                class="text-xs font-black text-slate-900 uppercase tracking-wider mb-4"
                            >
                                Price Range (৳)
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <span
                                            class="text-[10px] font-bold text-slate-400"
                                            >Min Price</span
                                        >
                                        <div class="relative">
                                            <span
                                                class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-450 text-xs"
                                                >৳</span
                                            >
                                            <input
                                                v-model.number="minPrice"
                                                type="number"
                                                class="w-full pl-5 pr-2 py-1.5 bg-slate-50 border border-slate-200 rounded text-xs text-slate-900 font-bold outline-none focus:border-[#00a651] focus:bg-white transition-colors"
                                            />
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <span
                                            class="text-[10px] font-bold text-slate-400"
                                            >Max Price</span
                                        >
                                        <div class="relative">
                                            <span
                                                class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-450 text-xs"
                                                >৳</span
                                            >
                                            <input
                                                v-model.number="maxPrice"
                                                type="number"
                                                class="w-full pl-5 pr-2 py-1.5 bg-slate-50 border border-slate-200 rounded text-xs text-slate-900 font-bold outline-none focus:border-[#00a651] focus:bg-white transition-colors"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Filter Block (New Paragon Style) -->
                        <div
                            class="bg-white p-5 rounded border border-slate-200 shadow-sm"
                        >
                            <h3
                                class="text-xs font-black text-slate-900 uppercase tracking-wider mb-4"
                            >
                                Availability
                            </h3>
                            <div class="space-y-2">
                                <label
                                    class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="selectedAvailability.length === 0"
                                        @change="selectedAvailability = []; triggerSearch()"
                                        class="rounded border-slate-350 text-[#00a651] focus:ring-[#00a651] w-4 h-4 cursor-pointer"
                                    />
                                    <span>All</span>
                                </label>
                                <label
                                    class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer"
                                >
                                    <input
                                        type="checkbox"
                                        value="in_stock"
                                        :checked="selectedAvailability.includes('in_stock')"
                                        @change="toggleAvailability('in_stock')"
                                        class="rounded border-slate-350 text-[#00a651] focus:ring-[#00a651] w-4 h-4 cursor-pointer"
                                    />
                                    <span>In Stock</span>
                                </label>
                                <label
                                    class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer"
                                >
                                    <input
                                        type="checkbox"
                                        value="out_of_stock"
                                        :checked="selectedAvailability.includes('out_of_stock')"
                                        @change="toggleAvailability('out_of_stock')"
                                        class="rounded border-slate-350 text-[#00a651] focus:ring-[#00a651] w-4 h-4 cursor-pointer"
                                    />
                                    <span>Out of Stock</span>
                                </label>
                            </div>
                        </div>

                        <!-- Brand Filter Block -->
                        <div
                            v-if="brands?.length"
                            class="bg-white p-5 rounded border border-slate-200 shadow-sm"
                        >
                            <h3
                                class="text-xs font-black text-slate-900 uppercase tracking-wider mb-4"
                            >
                                Brands
                            </h3>
                            <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-1">
                                <button
                                    v-for="brand in brands"
                                    :key="brand.id"
                                    @click="toggleBrand(brand.slug)"
                                    class="px-2.5 py-1.5 rounded text-[10px] font-bold border transition-all cursor-pointer"
                                    :class="
                                        selectedBrands.includes(brand.slug)
                                            ? 'bg-[#00a651] border-[#00a651] text-white shadow-sm'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-[#00a651] hover:text-[#00a651]'
                                    "
                                >
                                    {{ brand.name }}
                                </button>
                            </div>
                        </div>

                        <!-- Reset Master Switch -->
                        <button
                            @click="resetFilters"
                            class="w-full py-2.5 bg-white border border-slate-200 text-slate-700 rounded text-xs font-extrabold hover:bg-slate-50 hover:text-slate-900 active:scale-[0.98] transition-all shadow-sm cursor-pointer"
                        >
                            Reset Filters
                        </button>
                    </div>

                    <!-- Products View Area -->
                    <div class="lg:col-span-3">
                        <transition-group
                            v-if="products.data.length > 0"
                            name="grid-fade"
                            tag="div"
                            class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5"
                        >
                            <ProductCard
                                v-for="product in products.data"
                                :key="product.id"
                                :product="product"
                            />
                        </transition-group>

                        <!-- Clean Empty State -->
                        <div
                            v-else
                            class="py-24 text-center bg-white rounded border border-slate-200 shadow-sm px-6"
                        >
                            <div
                                class="w-16 h-16 bg-slate-55 rounded flex items-center justify-center mx-auto mb-6 border border-slate-100"
                            >
                                <Package class="w-6 h-6 text-slate-400" />
                            </div>
                            <h2
                                class="text-base font-black text-slate-900 mb-1 uppercase"
                            >
                                No Products Found
                            </h2>
                            <p
                                class="text-xs text-slate-500 max-w-xs mx-auto mb-6"
                            >
                                No matching inventory items fit your current active search filters.
                            </p>
                            <button
                                @click="resetFilters"
                                class="px-5 py-2 bg-[#00a651] text-white rounded font-bold text-xs uppercase hover:bg-[#008541] border-none shadow-sm transition-all cursor-pointer"
                            >
                                Clear Filters
                            </button>
                        </div>

                        <!-- Pagination Grid System -->
                        <div
                            v-if="products.last_page > 1"
                            class="mt-12 flex justify-center items-center gap-1.5"
                        >
                            <Link
                                v-for="(link, index) in products.links"
                                :key="index"
                                :href="link.url || '#'"
                                v-html="link.label"
                                :class="[
                                    link.active
                                        ? 'bg-[#00a651] text-white shadow-sm w-9 h-9 rounded'
                                        : 'bg-white text-slate-650 hover:bg-slate-50 border border-slate-200 w-9 h-9 rounded',
                                    !link.url
                                        ? 'opacity-30 pointer-events-none'
                                        : '',
                                ]"
                                class="flex items-center justify-center text-xs font-bold transition-all decoration-transparent"
                            >
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import StoreLayout from "@/Layouts/StoreLayout.vue";
import ProductCard from "@/Components/ProductCard.vue";
import {
    ChevronRight,
    Check,
    Package,
    SlidersHorizontal,
} from "lucide-vue-next";

const props = defineProps({
    products: Object,
    filters: Object,
    categories: Array,
    brands: Array,
});

const selectedCategories = ref(
    props.filters.category ? props.filters.category.split(",") : [],
);
const selectedBrands = ref(
    props.filters.brand ? props.filters.brand.split(",") : [],
);
const minPrice = ref(
    props.filters.min_price ? parseInt(props.filters.min_price) : 0,
);
const maxPrice = ref(
    props.filters.max_price ? parseInt(props.filters.max_price) : 5000000,
);
const sortBy = ref(props.filters.sort || "Latest Arrival");
const selectedAvailability = ref(
    props.filters.availability ? props.filters.availability.split(",") : [],
);

const activeCategoryName = computed(() => {
    if (props.filters.sub_category) {
        return props.filters.sub_category.replace("-", " ");
    }
    if (selectedCategories.value.length === 1) {
        const cat = props.categories.find(
            (c) => c.slug === selectedCategories.value[0],
        );
        return cat ? cat.name : selectedCategories.value[0].replace("-", " ");
    }
    if (selectedCategories.value.length > 1) {
        return "Multi-Sector";
    }
    return "All Products";
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

const toggleAvailability = (val) => {
    const idx = selectedAvailability.value.indexOf(val);
    if (idx > -1) selectedAvailability.value.splice(idx, 1);
    else selectedAvailability.value.push(val);
    triggerSearch();
};

const triggerSearch = () => {
    router.get(
        "/shop",
        {
            category: selectedCategories.value.join(","),
            brand: selectedBrands.value.join(","),
            min_price: minPrice.value,
            max_price: maxPrice.value,
            sort: sortBy.value,
            availability: selectedAvailability.value.join(","),
            sub_category:
                selectedCategories.value.length === 0
                    ? props.filters.sub_category
                    : null,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

watch(sortBy, triggerSearch);
watch([minPrice, maxPrice], () => {
    triggerSearch();
});

const resetFilters = () => {
    selectedCategories.value = [];
    selectedBrands.value = [];
    minPrice.value = 0;
    maxPrice.value = 5000000;
    sortBy.value = "Latest Arrival";
    selectedAvailability.value = [];
    router.get("/shop");
};
</script>

<style scoped>
.grid-fade-enter-active,
.grid-fade-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.grid-fade-enter-from,
.grid-fade-leave-to {
    opacity: 0;
    transform: translateY(12px);
}
</style>
