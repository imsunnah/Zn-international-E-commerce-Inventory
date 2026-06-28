<template>
    <StoreLayout>
        <div class="bg-[#FBFBFC] min-h-screen py-16 font-sans relative">
            <!-- Processing Overlay -->
            <Transition name="fade">
                <div v-if="form.processing" class="fixed inset-0 z-[100] bg-white/95 backdrop-blur-2xl flex flex-col items-center justify-center text-slate-900">
                    <div class="relative w-24 h-24 mb-8">
                        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-[#00a651] rounded-full animate-spin border-t-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <Lock class="w-8 h-8 text-[#00a651]" />
                        </div>
                    </div>
                    <h2 class="text-3xl font-black uppercase tracking-tighter mb-2 italic">{{ $t('Securing Transaction') }}</h2>
                    <p class="text-slate-400 font-black uppercase tracking-[0.3em] text-[10px]">{{ $t('Deploying order to logistics network') }}</p>
                </div>
            </Transition>

            <div class="max-w-[1400px] mx-auto px-4 md:px-8">
                <div class="mb-12">
                    <nav class="flex items-center gap-3 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-6">
                        <Link href="/" class="hover:text-[#00a651] transition-colors">{{ $t('Home Base') }}</Link>
                        <span class="opacity-20 text-slate-900">/</span>
                        <Link href="/cart" class="hover:text-[#00a651] transition-colors">{{ $t('Cart Manifest') }}</Link>
                        <span class="opacity-20 text-slate-900">/</span>
                        <span class="text-slate-900 italic">{{ $t('Deployment') }}</span>
                    </nav>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter flex items-center uppercase italic group">
                        {{ $t('Terminal Checkout') }}
                        <div class="h-1 flex-grow bg-slate-100 ml-8 rounded-full overflow-hidden">
                            <div class="h-full bg-[#00a651] w-24"></div>
                        </div>
                    </h1>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 p-5 sm:p-10">
                            <form @submit.prevent="showConfirmModal = true">
                                <!-- Section 1: Contact -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-bold text-[#00a651] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded bg-[#00a651]/10 text-[#00a651] flex items-center justify-center mr-3 text-xs font-bold">1</span>
                                        {{ $t('Contact Information') }}
                                    </h2>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">{{ $t('Full Name') }}</label>
                                            <input v-model="form.customer_name" type="text" required class="block w-full rounded border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold focus:ring-1 focus:ring-[#00a651] focus:bg-white outline-none transition-all placeholder:text-slate-300" :placeholder="$t('e.g. John Doe')">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">{{ $t('Phone Number') }}</label>
                                            <input v-model="form.customer_phone" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" :placeholder="$t('01XXXXXXXXX')">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">{{ $t('Email (Optional)') }}</label>
                                            <input v-model="form.customer_email" type="email" class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" :placeholder="$t('john@example.com')">
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Shipping -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-bold text-[#00a651] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded bg-[#00a651]/10 text-[#00a651] flex items-center justify-center mr-3 text-xs font-bold">2</span>
                                        {{ $t('Shipping Address') }}
                                    </h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="relative">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">{{ $t('District') }}</label>
                                            <select v-model="form.district" @change="handleDistrictChange" required class="block w-full rounded border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold focus:ring-1 focus:ring-[#00a651] focus:bg-white outline-none transition-all appearance-none pr-10">
                                                <option value="" disabled>{{ $t('Select District') }}</option>
                                                <option v-for="d in districts" :key="d.district" :value="d.district">{{ d.district }}</option>
                                            </select>
                                            <ChevronDown class="absolute right-4 bottom-4 w-4 h-4 text-slate-400 pointer-events-none" />
                                        </div>
                                        <div class="relative">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">{{ $t('Area / Thana / Upazila') }}</label>
                                            <div v-if="upazilas.length > 0" class="relative">
                                                <select v-model="form.upazila" required class="block w-full rounded border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold focus:ring-1 focus:ring-[#00a651] focus:bg-white outline-none transition-all appearance-none pr-10">
                                                    <option value="" disabled>{{ $t('Select Area') }}</option>
                                                    <option v-for="u in upazilas" :key="u" :value="u">{{ u }}</option>
                                                </select>
                                                <ChevronDown class="absolute right-4 bottom-4 w-4 h-4 text-slate-400 pointer-events-none" />
                                            </div>
                                            <input 
                                                v-else 
                                                v-model="form.upazila" 
                                                type="text" 
                                                required 
                                                :placeholder="loadingUpazilas ? $t('Loading areas...') : $t('Enter Thana / Upazila')"
                                                class="block w-full rounded border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold focus:ring-1 focus:ring-[#00a651] focus:bg-white outline-none transition-all placeholder:text-slate-300"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">{{ $t('Village / Area / House') }}</label>
                                        <input v-model="form.village" type="text" required class="block w-full rounded border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold focus:ring-1 focus:ring-[#00a651] focus:bg-white outline-none transition-all placeholder:text-slate-300" :placeholder="$t('Detailed address details')">
                                    </div>
                                </div>

                                <!-- Section 3: Delivery Location -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-bold text-[#00a651] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded bg-[#00a651]/10 text-[#00a651] flex items-center justify-center mr-3 text-xs font-bold">3</span>
                                        {{ $t('Delivery Location') }}
                                    </h2>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label 
                                            class="relative flex items-center p-4 rounded border-2 cursor-pointer transition-all"
                                            :class="form.delivery_location === 'Inside Dhaka' ? 'border-[#00a651] bg-[#00a651]/5' : 'border-slate-100 hover:border-slate-200'"
                                        >
                                            <input type="radio" v-model="form.delivery_location" value="Inside Dhaka" class="hidden">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center" :class="form.delivery_location === 'Inside Dhaka' ? 'border-[#00a651]' : 'border-slate-300'">
                                                    <div v-if="form.delivery_location === 'Inside Dhaka'" class="w-2.5 h-2.5 rounded-full bg-[#00a651]"></div>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-900 uppercase">{{ $t('Inside Dhaka') }}</p>
                                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">৳{{ delivery_charges.inside_dhaka }} {{ $t('Delivery Charge') }}</p>
                                                </div>
                                            </div>
                                        </label>

                                        <label 
                                            class="relative flex items-center p-4 rounded border-2 cursor-pointer transition-all"
                                            :class="form.delivery_location === 'Outside Dhaka' ? 'border-[#00a651] bg-[#00a651]/5' : 'border-slate-100 hover:border-slate-200'"
                                        >
                                            <input type="radio" v-model="form.delivery_location" value="Outside Dhaka" class="hidden">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center" :class="form.delivery_location === 'Outside Dhaka' ? 'border-[#00a651]' : 'border-slate-300'">
                                                    <div v-if="form.delivery_location === 'Outside Dhaka'" class="w-2.5 h-2.5 rounded-full bg-[#00a651]"></div>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-900 uppercase">{{ $t('Outside Dhaka') }}</p>
                                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">৳{{ delivery_charges.outside_dhaka }} {{ $t('Delivery Charge') }}</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Section 4: Payment -->
                         
                                <!-- Section 5: Customer Remarks -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-bold text-[#00a651] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded bg-[#00a651]/10 text-[#00a651] flex items-center justify-center mr-3 text-xs font-bold">4</span>
                                        {{ $t('Special Instructions / Remarks') }}
                                    </h2>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">{{ $t('Order Note / Customer Remarks') }}</label>
                                        <textarea v-model="form.customer_remarks" rows="3" class="block w-full rounded border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold focus:ring-1 focus:ring-[#00a651] focus:bg-white outline-none transition-all placeholder:text-slate-300" :placeholder="$t('e.g. Any special instructions regarding delivery, product requests, packaging, etc.')"></textarea>
                                    </div>
                                </div>

                                <button type="submit" :disabled="form.processing" class="w-full bg-[#003366] py-5 rounded-2xl text-white font-black uppercase tracking-[0.2em] shadow-xl shadow-[#003366]/20 hover:bg-slate-800 transition-all transform active:scale-[0.98] disabled:opacity-50 flex items-center justify-center">
                                    {{ $t('Continue to Confirmation') }} <ArrowRight class="w-5 h-5 ml-3" />
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Manifest Sidebar -->
                    <div class="lg:col-span-5">
                        <div class="lg:sticky lg:top-12 space-y-8">
                            <div class="bg-white shadow-[0_30px_80px_-30px_rgba(0,0,0,0.05)] rounded-[3rem] border border-slate-100 overflow-hidden">
                                <div class="p-8 bg-[#FBFBFC] border-b border-slate-100 flex justify-between items-center">
                                    <h2 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.3em] italic">{{ $t('Unit Manifest') }}</h2>
                                    <span class="px-4 py-1.5 bg-white shadow-sm rounded-full text-[9px] font-black text-[#00a651] border border-slate-100 uppercase tracking-widest">{{ cart.items.length }} Protocols</span>
                                </div>
                                <div class="p-8">
                                    <ul role="list" class="divide-y divide-slate-50 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                        <li v-for="item in cart.items" :key="item.id" class="flex py-6 group">
                                            <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-2xl border border-slate-100 bg-[#FBFBFC] p-2 transition-all group-hover:bg-white group-hover:shadow-xl">
                                                <img v-if="item.product?.image || item.combo?.image" :src="item.product?.image || item.combo?.image" :alt="item.product?.name || item.combo?.name" class="h-full w-full object-contain" />
                                            </div>
                                            <div class="ml-6 flex-1">
                                                <div class="flex items-center gap-2 mb-2" v-if="item.combo_id">
                                                    <span class="text-[7px] font-black bg-[#00a651] text-white px-2 py-0.5 rounded-sm uppercase tracking-widest">{{ $t('Bundle') }}</span>
                                                </div>
                                                <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-tighter line-clamp-2 leading-tight italic">{{ item.product?.name || item.combo?.name }}</h3>
                                                <div class="flex justify-between items-end mt-3">
                                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic">X {{ item.quantity.toString().padStart(2, '0') }}</p>
                                                    <p class="text-sm font-black text-slate-900 tracking-tighter italic">৳{{ (getItemPrice(item) * item.quantity).toLocaleString() }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="border-t border-slate-100 pt-8 mt-4 space-y-4">
                                        <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            <p>{{ $t('Logistics Subtotal') }}</p>
                                            <p class="text-slate-900">৳{{ subtotal.toLocaleString() }}</p>
                                        </div>
                                        <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            <p>{{ $t('Priority Routing') }} ({{ $t(form.delivery_location) }})</p>
                                            <p class="text-slate-900">৳{{ currentDeliveryCharge.toLocaleString() }}</p>
                                        </div>
                                        <div class="flex justify-between items-end pt-6 mt-4 border-t-2 border-slate-900 border-dashed">
                                            <p class="text-sm font-black text-slate-900 uppercase tracking-tighter italic">{{ $t('Combined Valuation') }}</p>
                                            <p class="text-4xl font-black text-[#00a651] tracking-tighter italic">৳{{ total.toLocaleString() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-[#00a651] rounded-[2.5rem] p-8 flex items-center gap-6 shadow-[0_20px_50px_-20px_rgba(0,166,81,0.3)] relative overflow-hidden group">
                                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0 backdrop-blur-md">
                                    <Truck class="w-8 h-8 text-white" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[11px] font-black text-white uppercase tracking-[0.2em] italic">{{ $t('Rapid Tactical Delivery') }}</p>
                                    <p class="text-[9px] font-bold text-white/70 uppercase tracking-[0.1em] mt-1">{{ $t('ETA: 48-72 Hours Arrival Window') }}</p>
                                </div>
                            </div>       

                            <div class="bg-slate-900 rounded-[2.5rem] p-8 border border-white/5 flex items-center justify-between group cursor-help">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center mr-4 group-hover:bg-[#00a651] transition-colors">
                                        <Banknote class="w-5 h-5 text-white" />
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black text-white uppercase tracking-[0.2em] italic">{{ $t('Pay on Arrival (COD)') }}</p>
                                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ $t('Settle balance upon visual verification') }}</p>
                                    </div>
                                </div>
                                <CheckCircle class="w-6 h-6 text-[#00a651] opacity-50" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Confirmation Matrix -->
        <Transition name="fade">
            <div v-if="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#0a0a0a]/80 backdrop-blur-xl">
                <Transition name="modal">
                    <div class="bg-white rounded-[4rem] p-12 max-w-lg w-full shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] relative overflow-hidden">
                        <div class="absolute -top-32 -right-32 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
                        
                        <div class="relative">
                            <div class="w-24 h-24 bg-[#0a0a0a] rounded-[2.5rem] flex items-center justify-center mb-10 mx-auto shadow-2xl relative">
                                <div class="absolute inset-0 bg-[#00a651] rounded-[2.5rem] animate-ping opacity-20"></div>
                                <ShieldCheck class="w-12 h-12 text-[#00a651]" />
                            </div>

                            <h3 class="text-3xl font-black text-slate-900 text-center mb-4 uppercase tracking-tighter italic">Finalize Command</h3>
                            <p class="text-[10px] font-black text-slate-400 text-center mb-12 uppercase tracking-[0.3em] leading-relaxed px-8">
                                Confirm the deployment of resources to <span class="text-[#00a651] underline underline-offset-4">{{ form.district }}</span> coordinate.
                            </p>

                            <div class="bg-slate-50 rounded-[2.5rem] p-10 mb-10 border border-slate-100">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Inventory Value') }}</span>
                                    <span class="text-sm font-black text-slate-900">৳{{ subtotal.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-6 pb-6 border-b border-slate-200">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Service Charge') }}</span>
                                    <span class="text-sm font-black text-[#00a651]">৳{{ currentDeliveryCharge.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] italic">{{ $t('Total Deployment') }}</span>
                                    <span class="text-4xl font-black text-[#00a651] tracking-tighter italic">৳{{ total.toLocaleString() }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <button @click="showConfirmModal = false" class="h-16 rounded-2xl border-2 border-slate-100 text-slate-400 font-black uppercase tracking-[0.3em] text-[10px] hover:bg-slate-50 transition-all hover:text-slate-600">
                                    {{ $t('Abandone') }}
                                </button>
                                <button @click="confirmAndSubmit" class="h-16 rounded-2xl bg-[#00a651] text-white font-black uppercase tracking-[0.3em] text-[10px] hover:bg-[#008f45] transition-all shadow-xl shadow-emerald-500/20 active:scale-95">
                                    {{ $t('Execute') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </StoreLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { useCart } from '@/Composables/useCart';
import { Lock, Package, ArrowRight, Banknote, Truck, X, ShoppingCart, MapPin, Phone, ShoppingBag, ChevronDown, Check, CheckCircle, ShieldCheck } from 'lucide-vue-next';

const props = defineProps({
    cart: Object,
    delivery_charges: Object,
    selectedItemIds: Array
});

const { clearCart } = useCart();
const showConfirmModal = ref(false);

const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    shipping_address: '',
    district: '',
    upazila: '',
    village: '',
    delivery_location: 'Inside Dhaka',
    delivery_charge: 0,
    selected_items: props.selectedItemIds ? props.selectedItemIds.join(',') : '',
    customer_remarks: ''
});

// Automatically sync the shipping_address value from village, upazila, and district
watch(
    () => [form.village, form.upazila, form.district],
    ([village, upazila, district]) => {
        form.shipping_address = [village, upazila, district].filter(Boolean).join(', ');
    }
);

const currentDeliveryCharge = computed(() => {
    return form.delivery_location === 'Inside Dhaka' 
        ? parseFloat(props.delivery_charges.inside_dhaka) 
        : parseFloat(props.delivery_charges.outside_dhaka);
});

const getItemPrice = (item) => {
    if (item.product) {
        const price = parseFloat(item.product.price || 0);
        if (!item.product.discount_type) return price;
        if (item.product.discount_type === 'percentage') {
            return price * (1 - parseFloat(item.product.discount_value || 0) / 100);
        }
        return Math.max(0, price - parseFloat(item.product.discount_value || 0));
    }
    return parseFloat(item.combo?.price || 0);
};

const subtotal = computed(() => {
    return props.cart.items.reduce((total, item) => {
        return total + (getItemPrice(item) * item.quantity);
    }, 0);
});

const total = computed(() => {
    return subtotal.value + currentDeliveryCharge.value;
});

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

const districts = ref(staticDistricts);
const upazilas = ref([]);
const loadingUpazilas = ref(false);

const metropolitanThanas = {
    'Dhaka': [
        "Adabor", "Airport", "Badda", "Banani", "Bangshal", "Bhashantek", "Cantonment", 
        "Chackbazar", "Dakshin Khan", "Darus-Salam", "Demra", "Dhanmondi", "Gandaria", 
        "Gulshan", "Hatirjheel", "Hazaribagh", "Jattrabari", "Kadamtoli", "Kafrul", 
        "Kalabagan", "Kamrangirchar", "Khilgaon", "Khilkhet", "Kotwali", "Lalbagh", 
        "Mirpur Model", "Mohammadpur", "Motijheel", "Mugda", "New Market", "Pallabi", 
        "Paltan Model", "Ramna Model", "Rampura", "Rupnagar", "Sabujbag", "Shah Ali", 
        "Shahbag", "Shahjahanpur", "Sher e Bangla Nagar", "Shyampur", "Sutrapur", 
        "Tejgaon", "Tejgaon Industrial", "Turag", "Uttar Khan", "Uttara East", 
        "Uttara West", "Vatara", "Wari", "Dhamrai", "Dohar", "Keraniganj", "Nawabganj", "Savar"
    ],
    'Chattogram': [
        "Kotwali", "Chandgaon", "Panchlaish", "Doublemooring", "Pahartali", "Bandar", 
        "Baijid Bostami", "Hali Shohor", "Kornafuli", "Potenga", "Bakolia", "Akborsha",
        "Anwara", "Banshkhali", "Boalkhali", "Chandanaish", "Fatikchhari", "Hathazari", 
        "Lohagara", "Mirsharai", "Patiya", "Rangunia", "Raozan", "Sandwip", "Satkania", "Sitakunda"
    ]
};

const fetchDistricts = async () => {
    try {
        const response = await fetch('https://bdapis.com/api/v1.2/districts');
        const result = await response.json();
        if (result && result.data) {
            districts.value = result.data.sort((a, b) => a.district.localeCompare(b.district));
        }
    } catch (error) {
        console.error('Error fetching districts:', error);
    }
};

const handleDistrictChange = async () => {
    form.upazila = '';
    upazilas.value = [];
    
    // Auto-set delivery location
    if (form.district === 'Dhaka') {
        form.delivery_location = 'Inside Dhaka';
    } else {
        form.delivery_location = 'Outside Dhaka';
    }

    if (form.district) {
        loadingUpazilas.value = true;
        try {
            const response = await fetch(`https://bdapis.com/api/v1.2/district/${form.district.toLowerCase()}`);
            const result = await response.json();
            
            let apiUpazilas = [];
            if (result.data && result.data[0]) {
                apiUpazilas = result.data[0].upazillas || [];
            }

            // Merge with metropolitan thanas if available
            const extraThanas = metropolitanThanas[form.district] || [];
            const combined = [...new Set([...apiUpazilas, ...extraThanas])];
            
            upazilas.value = combined.sort();
        } catch (error) {
            console.error('Error fetching upazilas:', error);
            // Fallback to only metropolitan if API fails
            if (metropolitanThanas[form.district]) {
                upazilas.value = metropolitanThanas[form.district].sort();
            }
        } finally {
            loadingUpazilas.value = false;
        }
    }
};

onMounted(() => {
    fetchDistricts();
    const page = usePage();
    if (page.props.auth?.user) {
        form.customer_name = page.props.auth.user.name || '';
        form.customer_email = page.props.auth.user.email || '';
    }
});

const confirmAndSubmit = () => {
    showConfirmModal.value = false;
    form.delivery_charge = currentDeliveryCharge.value;
    form.post('/checkout', {
        onSuccess: () => {
            clearCart();
        }
    });
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 51, 102, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 51, 102, 0.1);
}

/* Modal Animations */
.modal-enter-active, .modal-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

/* Fade Animations */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
