<template>
    <StoreLayout>
        <div
            class="bg-slate-50 min-h-screen py-10 font-sans antialiased text-slate-600 selection:bg-emerald-500 selection:text-white"
        >
            <!-- Subtle Radial Gradient Background Grid -->
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-40 pointer-events-none z-0"
            ></div>

            <div
                class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10"
            >
                <!-- Breadcrumbs & Header Section -->
                <div class="mb-12">
                    <nav
                        class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-4"
                    >
                        <Link
                            href="/"
                            class="hover:text-slate-900 transition-colors"
                            >Infrastructure</Link
                        >
                        <ChevronRight class="w-3.5 h-3.5 text-slate-300" />
                        <span class="text-slate-900 font-semibold">{{
                            activeCategoryName
                        }}</span>
                    </nav>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-slate-200"
                    >
                        <div>
                            <h1
                                class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                            >
                                Tech
                                <span class="text-emerald-600 font-medium"
                                    >Inventory</span
                                >
                            </h1>
                            <p class="text-sm text-slate-500 mt-1">
                                Reviewing
                                {{
                                    activeCategoryName.toLowerCase()
                                }}
                                components.
                            </p>
                        </div>

                        <!-- Toolbar Controls -->
                        <div
                            class="flex items-center gap-3 bg-white p-1.5 rounded-xl border border-slate-200/80 shadow-sm self-start sm:self-auto"
                        >
                            <div
                                class="flex items-center gap-1.5 px-3 py-1.5 border-r border-slate-100 text-xs font-medium text-slate-500"
                            >
                                <span>Nodes:</span>
                                <span class="text-slate-900 font-semibold">{{
                                    products.total
                                }}</span>
                            </div>
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-slate-500"
                            >
                                <span>Sort:</span>
                                <select
                                    v-model="sortBy"
                                    class="text-slate-900 font-semibold bg-transparent border-none p-0 focus:ring-0 outline-none cursor-pointer text-xs"
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
                    <!-- Clean Sidebar Minimalist Panel -->
                    <div class="lg:col-span-1 space-y-8 lg:sticky lg:top-8">
                        <!-- Category Filter Block -->
                        <div
                            v-if="categories?.length"
                            class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm"
                        >
                            <h3
                                class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2"
                            >
                                <SlidersHorizontal
                                    class="w-3.5 h-3.5 text-slate-400"
                                />
                                Categories
                            </h3>
                            <div class="space-y-1">
                                <button
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    @click="toggleCategory(cat.slug)"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all text-left"
                                    :class="
                                        selectedCategories.includes(cat.slug)
                                            ? 'bg-slate-900 text-white shadow-sm'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                                    "
                                >
                                    <span>{{ cat.name }}</span>
                                    <Check
                                        v-if="
                                            selectedCategories.includes(
                                                cat.slug,
                                            )
                                        "
                                        class="w-4 h-4 text-emerald-400"
                                    />
                                    <ChevronRight
                                        v-else
                                        class="w-3.5 h-3.5 text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity"
                                    />
                                </button>
                            </div>
                        </div>

                        <!-- Price Range Block -->
                        <div
                            class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm"
                        >
                            <h3
                                class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4"
                            >
                                Price Limits
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <span
                                            class="text-[10px] font-medium text-slate-400"
                                            >Min Price</span
                                        >
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                                                >$</span
                                            >
                                            <input
                                                v-model.number="minPrice"
                                                type="number"
                                                class="w-full pl-6 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-semibold outline-none focus:border-emerald-500 focus:bg-white transition-colors"
                                            />
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <span
                                            class="text-[10px] font-medium text-slate-400"
                                            >Max Price</span
                                        >
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                                                >$</span
                                            >
                                            <input
                                                v-model.number="maxPrice"
                                                type="number"
                                                class="w-full pl-6 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-semibold outline-none focus:border-emerald-500 focus:bg-white transition-colors"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Filter Block -->
                        <div
                            v-if="brands?.length"
                            class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm"
                        >
                            <h3
                                class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4"
                            >
                                Manufacturers
                            </h3>
                            <div class="flex flex-wrap gap-1.5">
                                <button
                                    v-for="brand in brands"
                                    :key="brand.id"
                                    @click="toggleBrand(brand.slug)"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-all"
                                    :class="
                                        selectedBrands.includes(brand.slug)
                                            ? 'bg-slate-900 border-slate-900 text-white shadow-sm'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900'
                                    "
                                >
                                    {{ brand.name }}
                                </button>
                            </div>
                        </div>

                        <!-- Reset Master Switch -->
                        <button
                            @click="resetFilters"
                            class="w-full py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-50 hover:text-slate-900 active:scale-[0.98] transition-all shadow-sm"
                        >
                            Reset System Filters
                        </button>
                    </div>

                    <!-- Products View Area -->
                    <div class="lg:col-span-3">
                        <transition-group
                            v-if="products.data.length > 0"
                            name="grid-fade"
                            tag="div"
                            class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6"
                        >
                            <ProductCard
                                v-for="product in products.data"
                                :key="product.id"
                                :product="product"
                            />
                        </transition-group>

                        <!-- Clean Minimal Empty State -->
                        <div
                            v-else
                            class="py-24 text-center bg-white rounded-3xl border border-slate-200/60 shadow-sm px-6"
                        >
                            <div
                                class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-100"
                            >
                                <Package class="w-6 h-6 text-slate-400" />
                            </div>
                            <h2
                                class="text-lg font-semibold text-slate-900 mb-1"
                            >
                                No hardware configurations found
                            </h2>
                            <p
                                class="text-sm text-slate-500 max-w-xs mx-auto mb-6"
                            >
                                No matching machine inventory items are
                                registered in this filtered cluster sector.
                            </p>
                            <button
                                @click="resetFilters"
                                class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-medium text-sm hover:bg-emerald-500 shadow-sm transition-all active:scale-[0.98]"
                            >
                                Clear Filters
                            </button>
                        </div>

                        <!-- Pagination Grid System -->
                        <div
                            v-if="products.last_page > 1"
                            class="mt-16 flex justify-center items-center gap-1.5"
                        >
                            <Link
                                v-for="(link, index) in products.links"
                                :key="index"
                                :href="link.url || '#'"
                                v-html="link.label"
                                :class="[
                                    link.active
                                        ? 'bg-slate-900 text-white shadow-sm w-10 h-10 rounded-xl'
                                        : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 w-10 h-10 rounded-xl',
                                    !link.url
                                        ? 'opacity-30 pointer-events-none'
                                        : '',
                                ]"
                                class="flex items-center justify-center text-xs font-semibold transition-all"
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
    return "All Hardware";
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
    router.get(
        "/shop",
        {
            category: selectedCategories.value.join(","),
            brand: selectedBrands.value.join(","),
            min_price: minPrice.value,
            max_price: maxPrice.value,
            sort: sortBy.value,
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
