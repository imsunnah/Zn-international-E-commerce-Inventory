<template>
    <AdminLayout>
        <!-- Header Section -->
        <div
            class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl border border-slate-200"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    Classification Archives
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Organize your product entities with logical categories
                </p>
            </div>
            <div class="flex gap-3">
                <Link
                    href="/admin/brands/bulk"
                    class="bg-slate-100 text-slate-700 px-4 py-2.5 rounded-lg hover:bg-slate-200 transition-all text-sm font-medium flex items-center active:scale-95"
                >
                    <Layers class="w-4 h-4 mr-2" /> Bulk Brands
                </Link>
                <Link
                    href="/admin/categories/create"
                    class="bg-slate-900 text-white px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-all text-sm font-medium flex items-center active:scale-95"
                >
                    <Plus class="w-4 h-4 mr-2" /> Establish Category
                </Link>
            </div>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="$page.props.flash?.success"
            class="mb-6 bg-emerald-600 text-white p-4 rounded-xl flex items-center justify-between"
        >
            <div class="flex items-center space-x-3">
                <CheckCircle class="w-5 h-5" />
                <span class="text-sm font-medium">{{
                    $page.props.flash.success
                }}</span>
            </div>
            <button
                @click="$page.props.flash.success = null"
                class="opacity-70 hover:opacity-100 transition-opacity"
            >
                <XCircle class="w-5 h-5" />
            </button>
        </div>

        <!-- Data Table Card -->
        <div
            class="bg-white rounded-xl border border-slate-200 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 text-xs font-semibold text-slate-600 border-b border-slate-200"
                        >
                            <th class="py-4 px-6 text-center w-20">ID</th>
                            <th class="py-4 px-6 text-center w-20">Serial</th>
                            <th class="py-4 px-6 w-20">Visual</th>
                            <th class="py-4 px-6">Designation</th>
                            <th class="py-4 px-6 text-center">Sub/Brands</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr
                            v-for="category in categories.data"
                            :key="category.id"
                            class="hover:bg-slate-50/50 transition-all"
                        >
                            <td
                                class="py-4 px-6 text-center text-sm font-mono text-slate-400"
                            >
                                #{{ String(category.id).padStart(3, "0") }}
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-800">
                                {{ category.serial !== null ? category.serial : '--' }}
                            </td>
                            <td class="py-4 px-6">
                                <div
                                    class="w-10 h-10 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center"
                                >
                                    <img
                                        v-if="category.image"
                                        :src="category.image"
                                        class="w-full h-full object-cover"
                                    />
                                    <Tags
                                        v-else
                                        class="w-5 h-5 text-slate-300"
                                    />
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div
                                    class="text-sm font-semibold text-slate-800"
                                >
                                    {{ category.name_en }}
                                </div>
                                <div class="text-xs text-slate-400 font-mono">
                                    {{ category.slug }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div
                                    class="flex items-center justify-center gap-1.5"
                                >
                                    <span
                                        class="text-xs font-medium text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-md"
                                        >{{
                                            category.sub_categories_count
                                        }}
                                        Subs</span
                                    >
                                    <span
                                        class="text-xs font-medium text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-md"
                                        >{{
                                            category.brands_count
                                        }}
                                        Brands</span
                                    >
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button
                                    @click="toggleActive(category.id)"
                                    :class="
                                        category.is_active
                                            ? 'text-emerald-700 bg-emerald-50 border-emerald-200'
                                            : 'text-slate-500 bg-slate-50 border-slate-200'
                                    "
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-all active:scale-95"
                                >
                                    {{
                                        category.is_active
                                            ? "Active"
                                            : "Archived"
                                    }}
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end space-x-1.5">
                                    <button
                                        @click="openQuickSub(category)"
                                        class="w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-all flex items-center justify-center"
                                        title="Add SubCategory"
                                    >
                                        <PlusCircle class="w-4 h-4" />
                                    </button>
                                    <button
                                        @click="openManageBrands(category)"
                                        class="w-8 h-8 rounded-lg text-purple-600 hover:bg-purple-50 transition-all flex items-center justify-center"
                                        title="Manage Brands"
                                    >
                                        <Award class="w-4 h-4" />
                                    </button>
                                    <Link
                                        :href="`/admin/categories/${category.id}/edit`"
                                        class="w-8 h-8 rounded-lg text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center"
                                        title="Edit"
                                    >
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button
                                        @click="confirmDelete(category.id)"
                                        class="w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 transition-all flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="categories.data.length === 0">
                            <td colspan="6" class="py-16 text-center">
                                <div
                                    class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3"
                                >
                                    <Tags class="w-6 h-6 text-slate-300" />
                                </div>
                                <h3
                                    class="text-base font-semibold text-slate-800"
                                >
                                    Archives Empty
                                </h3>
                                <p class="text-sm text-slate-400 mt-1">
                                    No category classifications have been
                                    established yet.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            <div
                v-if="categories.last_page > 1"
                class="p-4 bg-slate-50 border-t border-slate-200 flex justify-center space-x-1"
            >
                <button
                    v-for="link in categories.links"
                    :key="link.label"
                    @click="link.url && router.visit(link.url)"
                    v-html="link.label"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all"
                    :class="[
                        link.active
                            ? 'bg-slate-900 text-white'
                            : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50',
                        !link.url ? 'opacity-40 cursor-not-allowed' : '',
                    ]"
                ></button>
            </div>
        </div>

        <!-- Quick SubCategory Modal -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="showSubModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
            >
                <div
                    class="bg-white rounded-xl p-6 max-w-md w-full shadow-xl border border-slate-200"
                >
                    <h3 class="text-lg font-bold text-slate-900 mb-4">
                        Quick Sub-Category Establishment
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label
                                class="text-xs font-medium text-slate-600 mb-1 block"
                                >Name (English)</label
                            >
                            <input
                                v-model="subForm.name_en"
                                type="text"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:bg-white transition-all"
                                placeholder="Enter sub-category name..."
                            />
                        </div>
                        <div>
                            <label
                                class="text-xs font-medium text-slate-600 mb-1 block"
                                >Name (Bangla)</label
                            >
                            <input
                                v-model="subForm.name_bn"
                                type="text"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:bg-white transition-all"
                                placeholder="বাংলা নাম লিখুন..."
                            />
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button
                            @click="showSubModal = false"
                            class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-all"
                        >
                            Cancel
                        </button>
                        <button
                            @click="submitQuickSub"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all"
                        >
                            Create Subsidiary
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Manage Brands Modal -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="showBrandModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
            >
                <div
                    class="bg-white rounded-xl p-6 max-w-xl w-full shadow-xl border border-slate-200"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-slate-900">
                            Manage Associations
                        </h3>
                        <div
                            class="text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md"
                        >
                            {{ activeCategory?.name_en }}
                        </div>
                    </div>

                    <div
                        class="max-h-[300px] overflow-y-auto pr-2 custom-scrollbar"
                    >
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <div
                                v-for="brand in allBrands"
                                :key="brand.id"
                                @click="toggleBrandInList(brand.id)"
                                class="p-3 rounded-lg border flex items-center gap-2.5 cursor-pointer transition-all"
                                :class="
                                    brandForm.brand_ids.includes(brand.id)
                                        ? 'bg-purple-50 border-purple-200'
                                        : 'bg-slate-50 border-slate-100 hover:border-slate-200'
                                "
                            >
                                <div
                                    class="w-6 h-6 rounded-md bg-white flex items-center justify-center shrink-0 border border-slate-100"
                                    :class="
                                        brandForm.brand_ids.includes(brand.id)
                                            ? 'text-purple-600'
                                            : 'text-slate-400'
                                    "
                                >
                                    <Check
                                        v-if="
                                            brandForm.brand_ids.includes(
                                                brand.id,
                                            )
                                        "
                                        class="w-4 h-4"
                                    />
                                    <Award v-else class="w-4 h-4" />
                                </div>
                                <span
                                    class="text-xs font-medium truncate"
                                    :class="
                                        brandForm.brand_ids.includes(brand.id)
                                            ? 'text-purple-900'
                                            : 'text-slate-600'
                                    "
                                    >{{ brand.name_en }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex justify-end gap-2 mt-6 pt-4 border-t border-slate-100"
                    >
                        <button
                            @click="showBrandModal = false"
                            class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-all"
                        >
                            Close
                        </button>
                        <button
                            @click="submitAttachBrands"
                            class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all"
                        >
                            Sync Associations
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Delete Confirmation Modal -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="showDeleteModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
            >
                <div
                    class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl border border-slate-200 text-center"
                >
                    <div
                        class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <AlertTriangle class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">
                        Terminate Category?
                    </h3>
                    <p class="text-sm text-slate-500 mb-6">
                        This operation will permanently erase this
                        classification. Associated product visibility may be
                        affected.
                    </p>
                    <div class="flex gap-2 justify-center">
                        <button
                            @click="showDeleteModal = false"
                            class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-all"
                        >
                            Abort Mission
                        </button>
                        <button
                            @click="deleteCategory"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all"
                        >
                            Confirm Termination
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    Plus,
    Edit2,
    Trash2,
    AlertTriangle,
    CheckCircle,
    XCircle,
    Tags,
    PlusCircle,
    Award,
    Check,
    Layers,
} from "lucide-vue-next";

const props = defineProps({
    categories: Object,
    allBrands: Array,
});

const showDeleteModal = ref(false);
const showSubModal = ref(false);
const showBrandModal = ref(false);
const activeCategory = ref(null);
const categoryToDelete = ref(null);

const subForm = reactive({
    name_en: "",
    name_bn: "",
});

const brandForm = reactive({
    brand_ids: [],
});

const confirmDelete = (id) => {
    categoryToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteCategory = () => {
    router.delete(`/admin/categories/${categoryToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        },
    });
};

const toggleActive = (id) => {
    router.put(
        `/admin/categories/${id}/toggle-active`,
        {},
        {
            preserveScroll: true,
        },
    );
};

const openQuickSub = (category) => {
    activeCategory.value = category;
    subForm.name_en = "";
    subForm.name_bn = "";
    showSubModal.value = true;
};

const submitQuickSub = () => {
    router.post(
        `/admin/categories/${activeCategory.value.id}/quick-subcategory`,
        subForm,
        {
            onSuccess: () => {
                showSubModal.value = false;
            },
        },
    );
};

const openManageBrands = (category) => {
    activeCategory.value = category;
    brandForm.brand_ids = category.brands.map((b) => b.id);
    showBrandModal.value = true;
};

const toggleBrandInList = (id) => {
    const idx = brandForm.brand_ids.indexOf(id);
    if (idx > -1) brandForm.brand_ids.splice(idx, 1);
    else brandForm.brand_ids.push(id);
};

const submitAttachBrands = () => {
    router.post(
        `/admin/categories/${activeCategory.value.id}/attach-brands`,
        brandForm,
        {
            onSuccess: () => {
                showBrandModal.value = false;
            },
        },
    );
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
