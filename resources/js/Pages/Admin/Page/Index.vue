<template>
    <AdminLayout>
        <!-- Header Section -->
        <div
            class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl border border-slate-200"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $t("Dynamic") }}
                    <span class="text-slate-400">{{ $t("Pages") }}</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $t("Manage storefront pages, terms, and footer links") }}
                </p>
            </div>
            <Link
                href="/admin/pages/create"
                class="bg-slate-900 text-white px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-all text-sm font-medium flex items-center active:scale-95"
            >
                <Plus class="w-4 h-4 mr-2" /> {{ $t("Create Custom Page") }}
            </Link>
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
                            <th class="py-4 px-6 text-center w-20">
                                {{ $t("ID") }}
                            </th>
                            <th class="py-4 px-6">{{ $t("Title") }}</th>
                            <th class="py-4 px-6">
                                {{ $t("Group / Category") }}
                            </th>
                            <th class="py-4 px-6">{{ $t("Slug URL") }}</th>
                            <th class="py-4 px-6 text-center">
                                {{ $t("Status") }}
                            </th>
                            <th class="py-4 px-6 text-right">
                                {{ $t("Operations") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr
                            v-for="page in pages.data"
                            :key="page.id"
                            class="hover:bg-slate-50/50 transition-all"
                        >
                            <td
                                class="py-4 px-6 text-center text-sm font-mono text-slate-400"
                            >
                                #{{ String(page.id).padStart(3, "0") }}
                            </td>
                            <td class="py-4 px-6">
                                <div
                                    class="text-sm font-semibold text-slate-800"
                                >
                                    {{ page.title }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="text-xs font-medium bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md border border-indigo-100"
                                >
                                    {{ getGroupLabel(page.group) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="text-xs text-slate-500 font-mono bg-slate-50 px-2 py-0.5 rounded-md"
                                    >/pages/{{ page.slug }}</span
                                >
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button
                                    @click="toggleActive(page)"
                                    :class="
                                        page.is_active
                                            ? 'text-emerald-700 bg-emerald-50 border-emerald-200'
                                            : 'text-slate-500 bg-slate-50 border-slate-200'
                                    "
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-all active:scale-95"
                                >
                                    {{
                                        page.is_active
                                            ? $t("Active")
                                            : $t("Disabled")
                                    }}
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end space-x-1.5">
                                    <Link
                                        :href="`/pages/${page.slug}`"
                                        target="_blank"
                                        class="w-8 h-8 rounded-lg text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center"
                                        title="View Page"
                                    >
                                        <Eye class="w-4 h-4" />
                                    </Link>
                                    <Link
                                        :href="`/admin/pages/${page.id}/edit`"
                                        class="w-8 h-8 rounded-lg text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center"
                                        title="Edit"
                                    >
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button
                                        @click="confirmDelete(page.id)"
                                        class="w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 transition-all flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="pages.data.length === 0">
                            <td colspan="6" class="py-16 text-center">
                                <div
                                    class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3"
                                >
                                    <FileText class="w-6 h-6 text-slate-300" />
                                </div>
                                <h3
                                    class="text-base font-semibold text-slate-800"
                                >
                                    {{ $t("No Dynamic Pages") }}
                                </h3>
                                <p class="text-sm text-slate-400 mt-1">
                                    {{
                                        $t(
                                            "Create custom content pages to populate your footer links.",
                                        )
                                    }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            <div
                v-if="pages.last_page > 1"
                class="p-4 bg-slate-50 border-t border-slate-200 flex justify-center space-x-1"
            >
                <button
                    v-for="link in pages.links"
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
                        {{ $t("Erase Custom Page?") }}
                    </h3>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed px-2">
                        {{
                            $t(
                                "This action will permanently delete this page. Any footer links referencing this slug will disappear.",
                            )
                        }}
                    </p>
                    <div class="flex gap-2 justify-center">
                        <button
                            @click="showDeleteModal = false"
                            class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-all"
                        >
                            {{ $t("Abort Operation") }}
                        </button>
                        <button
                            @click="deletePage"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all"
                        >
                            {{ $t("Confirm Deletion") }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    Plus,
    Edit2,
    Trash2,
    AlertTriangle,
    CheckCircle,
    XCircle,
    FileText,
    Eye,
} from "lucide-vue-next";

defineProps({
    pages: Object,
});

const showDeleteModal = ref(false);
const pageToDelete = ref(null);

const confirmDelete = (id) => {
    pageToDelete.value = id;
    showDeleteModal.value = true;
};

const deletePage = () => {
    router.delete(`/admin/pages/${pageToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        },
    });
};

const toggleActive = (page) => {
    router.put(
        `/admin/pages/${page.id}/toggle-active`,
        {},
        {
            preserveScroll: true,
        },
    );
};

const getGroupLabel = (group) => {
    switch (group) {
        case "about_us":
            return "Information (About Us, T&C)";
        case "consumer_policy":
            return "Consumer Policy (Return, Refund)";
        case "support":
            return "Support (FAQ, How to Order)";
        default:
            return "General Page";
    }
};
</script>
