<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen pb-20 font-sans selection:bg-[#00a651] selection:text-white">
            <!-- Processing Overlay -->
            <Transition name="fade">
                <div v-if="checkoutForm.processing" class="fixed inset-0 z-[100] bg-white/95 backdrop-blur flex flex-col items-center justify-center text-slate-900">
                    <div class="relative w-16 h-16 mb-4">
                        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-[#00a651] rounded-full animate-spin border-t-transparent"></div>
                    </div>
                    <h2 class="text-lg font-black uppercase tracking-wider mb-1">Creating PC Build Order</h2>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[9px]">Please wait while we finalize your order details</p>
                </div>
            </Transition>

            <!-- Sticky Top Bar (Real-time Totals & Actions) -->
            <header class="sticky top-0 z-40 bg-slate-950 text-white border-b border-emerald-500/20 shadow-xl backdrop-blur-md bg-opacity-95 transition-all duration-300">
                <div class="max-w-[1100px] mx-auto px-4 md:px-8 xl:px-12 py-4 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-[#00a651] rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/35">
                            <Cpu class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h1 class="text-base font-black tracking-tight uppercase leading-none">PC Builder</h1>
                            <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider mt-1">Configure Your Dream Machine</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 md:gap-8 flex-wrap">
                        <!-- Wattage Tracker -->
                        <div class="flex items-center space-x-2">
                            <Zap class="w-5 h-5 text-orange-400" />
                            <div>
                                <p class="text-[9px] uppercase font-black text-slate-400 tracking-wider">Est. Power</p>
                                <p class="text-sm font-black text-slate-105">{{ totalWattage }}W</p>
                            </div>
                        </div>

                        <!-- Price Tracker -->
                        <div class="flex items-center space-x-2">
                            <ShoppingCart class="w-5 h-5 text-emerald-400" />
                            <div>
                                <p class="text-[9px] uppercase font-black text-slate-400 tracking-wider font-bold">Total Price</p>
                                <p class="text-sm font-black text-emerald-400">৳{{ totalCost.toLocaleString() }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-3">
                            <button @click="printBuild" class="p-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-750">
                                <Printer class="w-4 h-4" />
                                <span class="hidden sm:inline">Print</span>
                            </button>
                            <button 
                                @click="openCheckout" 
                                :disabled="!hasRequiredSelections" 
                                class="px-6 py-2.5 bg-[#00a651] hover:bg-[#008f45] disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-200 shadow-md shadow-emerald-950/20 active:scale-95 flex items-center gap-2"
                            >
                                <span>Place Order</span>
                                <ArrowRight class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="max-w-[1100px] mx-auto px-4 md:px-8 xl:px-12 mt-8">
                <!-- Validation Alerts -->
                <div v-if="!hasRequiredSelections" class="mb-8 bg-blue-50 border border-blue-200 text-slate-700 p-4 rounded-2xl flex items-start gap-3">
                    <AlertTriangle class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" />
                    <div>
                        <h4 class="text-xs font-extrabold uppercase text-slate-900 leading-tight">Configure Required Components</h4>
                        <p class="text-xs text-slate-500 mt-1">Please select all required core components (marked with * or <strong class="text-red-500 font-bold">Required</strong>) to proceed to checkout and place your order.</p>
                    </div>
                </div>

                <!-- Star Tech Category Grid -->
                <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm">
                    <!-- Core Components Section -->
                    <div class="p-6 border-b border-slate-100 bg-slate-55 flex items-center justify-between">
                        <h2 class="text-sm font-black text-[#003366] uppercase tracking-wider">1. Core Components</h2>
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#00a651] bg-[#00a651]/10 px-3 py-1 rounded-full">Required items must be chosen</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <div v-for="cat in coreCategories" :key="cat.id" class="p-5 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                            <!-- Category Metadata -->
                            <div class="flex items-center space-x-4 md:w-1/4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600">
                                    <component :is="getIconComponent(cat.icon)" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight flex items-center gap-1.5">
                                        {{ cat.name }}
                                        <span v-if="cat.is_required" class="text-red-500 leading-none">*</span>
                                    </h3>
                                    <span v-if="cat.is_required" class="text-[9px] font-black uppercase tracking-wider text-red-500">Required</span>
                                    <span v-else class="text-[9px] font-black uppercase tracking-wider text-slate-400">Optional</span>
                                </div>
                            </div>

                            <!-- Selected Product Info -->
                            <div class="flex-1">
                                <div v-if="selections[cat.id]" class="flex items-center gap-4 bg-[#00a651]/5 border border-[#00a651]/20 rounded-2xl p-3">
                                    <img 
                                        v-if="selections[cat.id].product.image" 
                                        :src="selections[cat.id].product.image" 
                                        class="w-12 h-12 rounded-xl object-cover bg-white border border-slate-100 flex-shrink-0"
                                    />
                                    <div v-else class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center flex-shrink-0">
                                        <Package class="w-6 h-6 text-slate-350" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-slate-900 truncate uppercase">{{ selections[cat.id].product.name_en }}</h4>
                                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">
                                            SKU: {{ selections[cat.id].product.sku }} 
                                            <span v-if="selections[cat.id].wattage" class="ml-2 px-1.5 py-0.5 bg-orange-50 text-orange-700 rounded-md">
                                                {{ selections[cat.id].wattage }}W
                                            </span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-black text-[#003366]">৳{{ parseFloat(selections[cat.id].product.discounted_price).toLocaleString() }}</p>
                                        <button @click="removeSelection(cat.id)" class="text-[10px] font-black text-rose-500 hover:text-rose-700 uppercase tracking-widest mt-1 hover:underline">Remove</button>
                                    </div>
                                </div>
                                <div v-else class="flex items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-4 text-slate-400 bg-slate-50/50">
                                    <p class="text-xs font-bold">No product selected</p>
                                </div>
                            </div>

                            <!-- Selection Button -->
                            <div class="md:w-1/6 text-right">
                                <button @click="openSelectionModal(cat)" class="w-full md:w-auto px-5 py-2.5 border border-[#00a651] text-[#00a651] hover:bg-[#00a651] hover:text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200">
                                    {{ selections[cat.id] ? 'Change' : 'Choose' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Peripherals Section -->
                    <div v-if="peripheralCategories.length > 0" class="p-6 border-b border-slate-100 bg-slate-55 flex items-center justify-between border-t">
                        <h2 class="text-sm font-black text-[#003366] uppercase tracking-wider">2. Peripherals & Others</h2>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Optional Add-ons</span>
                    </div>

                    <div v-if="peripheralCategories.length > 0" class="divide-y divide-slate-100">
                        <div v-for="cat in peripheralCategories" :key="cat.id" class="p-5 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                            <!-- Category Metadata -->
                            <div class="flex items-center space-x-4 md:w-1/4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600">
                                    <component :is="getIconComponent(cat.icon)" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight flex items-center gap-1.5">
                                        {{ cat.name }}
                                        <span v-if="cat.is_required" class="text-red-500 leading-none">*</span>
                                    </h3>
                                    <span v-if="cat.is_required" class="text-[9px] font-black uppercase tracking-wider text-red-500">Required</span>
                                    <span v-else class="text-[9px] font-black uppercase tracking-wider text-slate-400">Optional</span>
                                </div>
                            </div>

                            <!-- Selected Product Info -->
                            <div class="flex-1">
                                <div v-if="selections[cat.id]" class="flex items-center gap-4 bg-[#00a651]/5 border border-[#00a651]/20 rounded-2xl p-3">
                                    <img 
                                        v-if="selections[cat.id].product.image" 
                                        :src="selections[cat.id].product.image" 
                                        class="w-12 h-12 rounded-xl object-cover bg-white border border-slate-100 flex-shrink-0"
                                    />
                                    <div v-else class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center flex-shrink-0">
                                        <Package class="w-6 h-6 text-slate-350" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-slate-900 truncate uppercase">{{ selections[cat.id].product.name_en }}</h4>
                                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">
                                            SKU: {{ selections[cat.id].product.sku }} 
                                            <span v-if="selections[cat.id].wattage" class="ml-2 px-1.5 py-0.5 bg-orange-50 text-orange-700 rounded-md">
                                                {{ selections[cat.id].wattage }}W
                                            </span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-black text-[#003366]">৳{{ parseFloat(selections[cat.id].product.discounted_price).toLocaleString() }}</p>
                                        <button @click="removeSelection(cat.id)" class="text-[10px] font-black text-rose-500 hover:text-rose-700 uppercase tracking-widest mt-1 hover:underline">Remove</button>
                                    </div>
                                </div>
                                <div v-else class="flex items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-4 text-slate-400 bg-slate-50/50">
                                    <p class="text-xs font-bold">No product selected</p>
                                </div>
                            </div>

                            <!-- Selection Button -->
                            <div class="md:w-1/6 text-right">
                                <button @click="openSelectionModal(cat)" class="w-full md:w-auto px-5 py-2.5 border border-[#00a651] text-[#00a651] hover:bg-[#00a651] hover:text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200">
                                    {{ selections[cat.id] ? 'Change' : 'Choose' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Product Selection Modal -->
            <Transition name="fade">
                <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white rounded-3xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl border border-slate-100">
                        <!-- Modal Header -->
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <div>
                                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Select {{ activeCategory?.name }}</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Choose a component to add to your build</p>
                            </div>
                            <button @click="closeModal" class="p-2 hover:bg-slate-200 rounded-xl transition-colors">
                                <X class="w-5 h-5 text-slate-500" />
                            </button>
                        </div>

                        <!-- Modal Search & Filters -->
                        <div class="p-4 border-b border-slate-100 bg-white flex items-center gap-3">
                            <div class="relative flex-1">
                                <Search class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                                <input 
                                    v-model="modalSearch" 
                                    @input="handleSearch"
                                    type="text" 
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all placeholder:font-bold" 
                                    placeholder="Search products by name or SKU..."
                                />
                            </div>
                        </div>

                        <!-- Modal Products List -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 max-h-[50vh]">
                            <div v-if="loadingProducts" class="flex flex-col items-center justify-center py-12">
                                <div class="w-10 h-10 border-4 border-slate-150 border-t-[#00a651] rounded-full animate-spin mb-3"></div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Loading Products...</p>
                            </div>

                            <div v-else-if="modalProducts.length === 0" class="text-center py-12">
                                <Package class="w-12 h-12 text-slate-205 mx-auto mb-3" />
                                <p class="text-sm font-bold text-slate-400">No products found in this category.</p>
                            </div>

                            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="item in modalProducts" :key="item.id" class="border border-slate-200 hover:border-[#00a651] rounded-2xl p-4 flex gap-4 transition-all duration-200 hover:shadow-lg bg-white relative group">
                                    <div class="w-20 h-20 bg-slate-50 rounded-xl overflow-hidden flex-shrink-0 border border-slate-100 flex items-center justify-center">
                                        <img v-if="item.product.image" :src="item.product.image" class="w-full h-full object-cover" />
                                        <Package v-else class="w-8 h-8 text-slate-200" />
                                    </div>
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-[#00a651] transition-colors truncate uppercase">{{ item.product.name_en }}</h4>
                                            <p class="text-[10px] text-slate-455 mt-0.5">SKU: {{ item.product.sku }}</p>
                                        </div>
                                        <div class="flex items-end justify-between mt-2">
                                            <div>
                                                <span class="text-xs font-black text-[#003366]">৳{{ parseFloat(item.product.discounted_price).toLocaleString() }}</span>
                                                <span v-if="item.product.discount_value > 0" class="text-[10px] text-slate-400 line-through ml-2">৳{{ parseFloat(item.product.price).toLocaleString() }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span v-if="item.wattage" class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded-md text-[10px] font-black font-mono">{{ item.wattage }}W</span>
                                                <button @click="selectProduct(item)" class="bg-[#00a651] hover:bg-[#008f45] text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-colors">Select</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Pagination -->
                        <div v-if="pagination.total > pagination.per_page" class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
                            <div class="flex items-center space-x-2">
                                <button 
                                    @click="changePage(pagination.current_page - 1)" 
                                    :disabled="pagination.current_page === 1" 
                                    class="p-2 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all text-slate-600"
                                >
                                    <ChevronLeft class="w-4 h-4" />
                                </button>
                                <button 
                                    @click="changePage(pagination.current_page + 1)" 
                                    :disabled="pagination.current_page === pagination.last_page" 
                                    class="p-2 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all text-slate-600"
                                >
                                    <ChevronRight class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>

            <!-- Checkout Order Drawer / Slide-over Modal -->
            <Transition name="fade">
                <div v-if="showCheckoutDrawer" class="fixed inset-0 z-50 overflow-hidden bg-slate-900/60 backdrop-blur-sm flex justify-end">
                    <div class="bg-white w-full max-w-lg h-full shadow-2xl flex flex-col transform transition-transform duration-300 relative">
                        <!-- Drawer Header -->
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <div>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <ShoppingBag class="w-5 h-5 text-[#00a651]" />
                                    Checkout Your Build
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Complete your computer build checkout</p>
                            </div>
                            <button @click="showCheckoutDrawer = false" class="p-2 hover:bg-slate-200 rounded-xl transition-colors">
                                <X class="w-5 h-5 text-slate-500" />
                            </button>
                        </div>

                        <!-- Drawer Body / Checkout Form -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">
                            <!-- Selections Summary review -->
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider mb-3">Component Summary</h4>
                                <ul class="divide-y divide-slate-200/60 space-y-2 pb-2">
                                    <li v-for="item in activeSelections" :key="item.category_id" class="flex justify-between items-center py-2 text-xs font-bold">
                                        <div class="min-w-0 flex-1">
                                            <span class="text-slate-500 uppercase tracking-tight text-[10px] font-black block">{{ item.category_name }}</span>
                                            <span class="text-slate-900 group-hover:text-[#00a651] truncate block uppercase">{{ item.product_name }}</span>
                                        </div>
                                        <span class="text-slate-900 font-extrabold ml-4">৳{{ item.price.toLocaleString() }}</span>
                                    </li>
                                </ul>
                                <div class="border-t border-slate-200 pt-3 flex justify-between items-center text-xs font-black text-slate-900 uppercase tracking-wider">
                                    <span>Subtotal</span>
                                    <span>৳{{ selectionsPriceTotal.toLocaleString() }}</span>
                                </div>
                            </div>

                            <form @submit.prevent="submitBuildOrder" class="space-y-4">
                                <!-- Full Name -->
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                                    <input v-model="checkoutForm.customer_name" type="text" required class="block w-full rounded-xl border border-slate-200 bg-slate-55 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all" placeholder="e.g. John Doe">
                                </div>

                                <!-- District & Thana -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">District <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <select v-model="checkoutForm.district" @change="handleDistrictChange" required class="block w-full rounded-xl border border-slate-200 bg-slate-55 pl-4 pr-8 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                                                <option value="" disabled>Select District</option>
                                                <option v-for="d in districtList" :key="d.district" :value="d.district">{{ d.district }}</option>
                                            </select>
                                            <ChevronDown class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Area / Thana / Upazila <span class="text-rose-500">*</span></label>
                                        <div v-if="upazilaList.length > 0" class="relative">
                                            <select v-model="checkoutForm.upazila" required class="block w-full rounded-xl border border-slate-200 bg-slate-55 pl-4 pr-8 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                                                <option value="" disabled>Select Area</option>
                                                <option v-for="u in upazilaList" :key="u" :value="u">{{ u }}</option>
                                            </select>
                                            <ChevronDown class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
                                        </div>
                                        <input 
                                            v-else 
                                            v-model="checkoutForm.upazila" 
                                            type="text" 
                                            required 
                                            placeholder="Enter Thana / Upazila"
                                            class="block w-full rounded-xl border border-slate-200 bg-slate-55 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all"
                                        />
                                    </div>
                                </div>

                                <!-- Village/Street -->
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Street Address / Village details <span class="text-rose-500">*</span></label>
                                    <input v-model="checkoutForm.village" type="text" required class="block w-full rounded-xl border border-slate-200 bg-slate-55 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all" placeholder="House number, street name, village details">
                                </div>

                                <!-- Phone & Email -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Mobile No. <span class="text-rose-500">*</span></label>
                                        <input v-model="checkoutForm.customer_phone" type="text" required class="block w-full rounded-xl border border-slate-200 bg-slate-55 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all" placeholder="01XXXXXXXXX">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Email address <span class="text-slate-405">(Optional)</span></label>
                                        <input v-model="checkoutForm.customer_email" type="email" class="block w-full rounded-xl border border-slate-200 bg-slate-55 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all" placeholder="john@example.com">
                                    </div>
                                </div>

                                <!-- Delivery Location / Charges -->
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2">Delivery Location</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label 
                                            class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all"
                                            :class="checkoutForm.delivery_location === 'Inside Dhaka' ? 'border-[#00a651] bg-[#00a651]/5' : 'border-slate-200 hover:border-slate-350'"
                                        >
                                            <input type="radio" v-model="checkoutForm.delivery_location" value="Inside Dhaka" @change="updateDeliveryCharge" class="hidden">
                                            <div>
                                                <p class="text-[10px] font-black text-slate-905 uppercase leading-none">Inside Dhaka</p>
                                                <p class="text-[9px] font-bold text-slate-500 mt-1">৳{{ delivery_charges.inside_dhaka }}</p>
                                            </div>
                                        </label>

                                        <label 
                                            class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all"
                                            :class="checkoutForm.delivery_location === 'Outside Dhaka' ? 'border-[#00a651] bg-[#00a651]/5' : 'border-slate-200 hover:border-slate-350'"
                                        >
                                            <input type="radio" v-model="checkoutForm.delivery_location" value="Outside Dhaka" @change="updateDeliveryCharge" class="hidden">
                                            <div>
                                                <p class="text-[10px] font-black text-slate-905 uppercase leading-none">Outside Dhaka</p>
                                                <p class="text-[9px] font-bold text-slate-500 mt-1">৳{{ delivery_charges.outside_dhaka }}</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Remarks -->
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Special Instruction / Remarks</label>
                                    <textarea v-model="checkoutForm.customer_remarks" rows="2" class="block w-full rounded-xl border border-slate-200 bg-slate-55 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all" placeholder="Any instruction for the delivery man..."></textarea>
                                </div>

                                <!-- Pricing details -->
                                <div class="border-t border-slate-100 pt-4 space-y-2 text-xs font-bold">
                                    <div class="flex justify-between items-center text-slate-500">
                                        <span>PC Components price</span>
                                        <span>৳{{ selectionsPriceTotal.toLocaleString() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-slate-500">
                                        <span>Delivery fee</span>
                                        <span>৳{{ checkoutForm.delivery_charge.toLocaleString() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm font-black text-slate-900 border-t border-slate-100 pt-2 uppercase tracking-wide">
                                        <span>Grand Total</span>
                                        <span class="text-[#00a651]">৳{{ (selectionsPriceTotal + checkoutForm.delivery_charge).toLocaleString() }}</span>
                                    </div>
                                </div>

                                <!-- Submit details -->
                                <div class="pt-2">
                                    <button type="submit" :disabled="checkoutForm.processing" class="w-full bg-[#00a651] hover:bg-[#008f45] text-white py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                                        <ShieldCheck class="w-4 h-4" />
                                        Complete Building &amp; Checkout
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { 
    Cpu, 
    Fan, 
    CircuitBoard, 
    MemoryStick, 
    HardDrive, 
    Monitor as MonitorIcon, 
    Power, 
    Box, 
    Mouse, 
    Keyboard, 
    Headphones, 
    Speaker, 
    Wifi, 
    Shield, 
    Printer, 
    Gamepad2, 
    Cable, 
    Webcam, 
    Plus, 
    Trash2, 
    Search, 
    X, 
    CheckCircle, 
    AlertTriangle, 
    ArrowRight, 
    ShoppingCart, 
    Zap,
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    ShoppingBag,
    ShieldCheck,
    Package
} from 'lucide-vue-next';

const props = defineProps({
    categories: Array,
    delivery_charges: Object,
});

const page = usePage();

// Reactive selections dictionary: { category_id: { product: Object, wattage: Number } }
const selections = reactive({});

// Modal states
const showModal = ref(false);
const activeCategory = ref(null);
const loadingProducts = ref(false);
const modalProducts = ref([]);
const modalSearch = ref('');
const pagination = reactive({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0,
});

// Checkout drawer states
const showCheckoutDrawer = ref(false);
const staticDistricts = [
    { district: "Bagerhat" }, { district: "Bandarban" }, { district: "Barguna" }, 
    { district: "Barishal" }, { district: "Bhola" }, { district: "Bogra" }, 
    { district: "Brahmanbaria" }, { district: "Chandpur" }, { district: "Chattogram" }, 
    { district: "Chuadanga" }, { district: "Comilla" }, { district: "Cox's Bazar" }, 
    { district: "Dhaka" }, { district: "Dinajpur" }, { district: "Faridpur" }, 
    { district: "Feni" }, { district: "Gaibandha" }, { district: "Gazipur" }, 
    { district: "Gopalganj" }, { district: "Habiganj" }, { district: "Jamalpur" }, 
    { district: "Jashore" }, { district: "Jhalokati" }, { district: "Jhenaidah" }, 
    { district: "Joypurhat" }, { district: "Khagrachhari" }, { district: "Khulna" }, 
    { district: "Kishoreganj" }, { district: "Kurigram" }, { district: "Kushtia" }, 
    { district: "Lakshmipur" }, { district: "Lalmonirhat" }, { district: "Madaripur" }, 
    { district: "Magura" }, { district: "Manikganj" }, { district: "Meherpur" }, 
    { district: "Moulvibazar" }, { district: "Munshiganj" }, { district: "Mymensingh" }, 
    { district: "Naogaon" }, { district: "Narail" }, { district: "Narayanganj" }, 
    { district: "Narsingdi" }, { district: "Natore" }, { district: "Netrokona" }, 
    { district: "Nilphamari" }, { district: "Noakhali" }, { district: "Pabna" }, 
    { district: "Panchagarh" }, { district: "Patuakhali" }, { district: "Pirojpur" }, 
    { district: "Rajbari" }, { district: "Rajshahi" }, { district: "Rangamati" }, 
    { district: "Rangpur" }, { district: "Satkhira" }, { district: "Shariatpur" }, 
    { district: "Sherpur" }, { district: "Sirajganj" }, { district: "Sunamganj" }, 
    { district: "Sylhet" }, { district: "Tangail" }, { district: "Thakurgaon" }
];
const districtList = ref(staticDistricts);
const upazilaList = ref([]);
const checkoutForm = useForm({
    selections: [],
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    shipping_address: '',
    district: 'Dhaka',
    upazila: '',
    village: '',
    delivery_location: 'Inside Dhaka',
    delivery_charge: 60,
    customer_remarks: '',
});

// Helper for dynamic Lucide icons mapping
const getIconComponent = (iconName) => {
    const mapping = {
        'cpu': Cpu,
        'fan': Fan,
        'circuit-board': CircuitBoard,
        'memory-stick': MemoryStick,
        'hard-drive': HardDrive,
        'monitor': MonitorIcon,
        'power': Power,
        'box': Box,
        'mouse': Mouse,
        'keyboard': Keyboard,
        'headphones': Headphones,
        'speaker': Speaker,
        'wifi': Wifi,
        'shield': Shield,
        'printer': Printer,
        'gamepad-2': Gamepad2,
        'cable': Cable,
        'webcam': Webcam,
    };
    return mapping[iconName] || Cpu;
};

// Group categories
const coreCategories = computed(() => props.categories.filter(c => c.group === 'core'));
const peripheralCategories = computed(() => props.categories.filter(c => c.group === 'peripherals'));

// Computed totals
const totalWattage = computed(() => {
    return Object.values(selections).reduce((sum, item) => sum + (item.wattage || 0), 0);
});

const totalCost = computed(() => {
    return Object.values(selections).reduce((sum, item) => sum + parseFloat(item.product.discounted_price || 0), 0);
});

const selectionsPriceTotal = computed(() => totalCost.value);

const hasRequiredSelections = computed(() => {
    const requiredIds = props.categories.filter(c => c.is_required).map(c => c.id);
    return requiredIds.every(id => selections[id] !== undefined);
});

// Format selections for submit request
const activeSelections = computed(() => {
    return Object.entries(selections).map(([catId, item]) => {
        const cat = props.categories.find(c => c.id == catId);
        return {
            category_id: parseInt(catId),
            category_name: cat ? cat.name : 'Component',
            product_id: item.product.id,
            product_name: item.product.name_en || item.product.name,
            price: parseFloat(item.product.discounted_price),
            wattage: item.wattage
        };
    });
});

// Load selections from local storage on mount (optional persistent UX)
onMounted(() => {
    const saved = localStorage.getItem('zn_pc_build');
    if (saved) {
        try {
            const parsed = JSON.parse(saved);
            Object.assign(selections, parsed);
        } catch (e) {
            console.error('Failed to parse saved build', e);
        }
    }

    if (page.props.auth?.user) {
        checkoutForm.customer_name = page.props.auth.user.name || '';
        checkoutForm.customer_email = page.props.auth.user.email || '';
    }

    // Load initial Upazilas for default district
    handleDistrictChange();
});

// Save selections to local storage
watch(selections, (newValue) => {
    localStorage.setItem('zn_pc_build', JSON.stringify(newValue));
}, { deep: true });

// Auto build shipping address
watch(
    () => [checkoutForm.village, checkoutForm.upazila, checkoutForm.district],
    ([village, upazila, district]) => {
        checkoutForm.shipping_address = [village, upazila, district].filter(Boolean).join(', ');
    }
);

// Selection handlers
const openSelectionModal = (category) => {
    activeCategory.value = category;
    showModal.value = true;
    modalSearch.value = '';
    modalProducts.value = [];
    pagination.current_page = 1;
    fetchCategoryProducts();
};

const closeModal = () => {
    showModal.value = false;
    activeCategory.value = null;
};

const fetchCategoryProducts = async () => {
    if (!activeCategory.value) return;
    loadingProducts.value = true;
    try {
        const response = await fetch(`/api/pc-builder/category/${activeCategory.value.id}/products?page=${pagination.current_page}&search=${modalSearch.value}`);
        const data = await response.json();
        if (data && data.data) {
            modalProducts.value = data.data;
            pagination.current_page = data.current_page;
            pagination.last_page = data.last_page;
            pagination.per_page = data.per_page;
            pagination.total = data.total;
        }
    } catch (e) {
        console.error('Error fetching products for category', e);
    } finally {
        loadingProducts.value = false;
    }
};

const handleSearch = () => {
    pagination.current_page = 1;
    fetchCategoryProducts();
};

const changePage = (page) => {
    pagination.current_page = page;
    fetchCategoryProducts();
};

const selectProduct = (item) => {
    if (!activeCategory.value) return;
    selections[activeCategory.value.id] = {
        product: item.product,
        wattage: item.wattage || 0,
    };
    closeModal();
};

const removeSelection = (categoryId) => {
    delete selections[categoryId];
};

// Printing capabilities
const printBuild = () => {
    window.print();
};

// Checkout Panel Handlers
const openCheckout = () => {
    if (!hasRequiredSelections.value) return;
    checkoutForm.selections = activeSelections.value;
    checkoutForm.delivery_charge = checkoutForm.delivery_location === 'Inside Dhaka' 
        ? parseFloat(props.delivery_charges.inside_dhaka)
        : parseFloat(props.delivery_charges.outside_dhaka);
    showCheckoutDrawer.value = true;
};

const updateDeliveryCharge = () => {
    checkoutForm.delivery_charge = checkoutForm.delivery_location === 'Inside Dhaka'
        ? parseFloat(props.delivery_charges.inside_dhaka)
        : parseFloat(props.delivery_charges.outside_dhaka);
};

const handleDistrictChange = async () => {
    checkoutForm.upazila = '';
    upazilaList.value = [];

    if (checkoutForm.district === 'Dhaka') {
        checkoutForm.delivery_location = 'Inside Dhaka';
    } else {
        checkoutForm.delivery_location = 'Outside Dhaka';
    }
    updateDeliveryCharge();

    if (checkoutForm.district) {
        try {
            const response = await fetch(`https://bdapis.com/api/v1.2/district/${checkoutForm.district.toLowerCase()}`);
            const result = await response.json();
            if (result.data && result.data[0]) {
                upazilaList.value = (result.data[0].upazillas || []).sort();
            }
        } catch (error) {
            console.error('Error fetching areas:', error);
        }
    }
};

const submitBuildOrder = () => {
    checkoutForm.post('/pc-builder/checkout', {
        onSuccess: () => {
            // Clear builder cache
            localStorage.removeItem('zn_pc_build');
            Object.keys(selections).forEach(key => delete selections[key]);
            showCheckoutDrawer.value = false;
        }
    });
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.25s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

@media print {
    header, button, select, input, textarea, .sticky {
        display: none !important;
    }
    main {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
