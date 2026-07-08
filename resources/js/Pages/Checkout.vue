<template>
    <StoreLayout>
        <div class="bg-white min-h-screen py-10 font-sans relative text-slate-700 selection:bg-[#00a651] selection:text-white">
            <!-- Processing Overlay -->
            <Transition name="fade">
                <div v-if="form.processing" class="fixed inset-0 z-[100] bg-white/95 backdrop-blur flex flex-col items-center justify-center text-slate-900">
                    <div class="relative w-16 h-16 mb-4">
                        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-[#00a651] rounded-full animate-spin border-t-transparent"></div>
                    </div>
                    <h2 class="text-lg font-black uppercase tracking-wider mb-1">{{ $t('Processing Order') }}</h2>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[9px]">{{ $t('Please wait while we secure your billing details') }}</p>
                </div>
            </Transition>

            <div class="max-w-[1300px] mx-auto px-4 md:px-8 xl:px-12">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-455 mb-6">
                    <Link href="/" class="hover:text-[#00a651] transition-colors decoration-transparent">{{ $t('Home') }}</Link>
                    <ChevronRight class="w-3.5 h-3.5 text-slate-300" />
                    <Link href="/cart" class="hover:text-[#00a651] transition-colors decoration-transparent">{{ $t('Cart') }}</Link>
                    <ChevronRight class="w-3.5 h-3.5 text-slate-300" />
                    <span class="text-slate-800 font-bold">{{ $t('Checkout') }}</span>
                </nav>

                <h1 class="text-xl md:text-2xl font-black text-slate-900 uppercase mb-8">
                    {{ $t('Checkout') }}
                </h1>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Left: Billing Details Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white rounded border border-slate-205 p-6 md:p-8 shadow-sm">
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-6 pb-2 border-b border-slate-100">
                                {{ $t('Billing Details') }}
                            </h2>

                            <form @submit.prevent="confirmAndSubmit">
                                <div class="space-y-5">
                                    <!-- Full Name -->
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Full Name') }} <span class="text-red-500">*</span></label>
                                        <input v-model="form.customer_name" type="text" required class="block w-full rounded border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all placeholder:font-bold" :placeholder="$t('e.g. John Doe')">
                                    </div>

                                    <!-- Country -->
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Country / Region') }} <span class="text-red-500">*</span></label>
                                        <select disabled class="block w-full rounded border border-slate-200 bg-slate-100/80 px-4 py-2.5 text-xs font-bold outline-none cursor-not-allowed">
                                            <option value="Bangladesh" selected>Bangladesh</option>
                                        </select>
                                    </div>

                                    <!-- Distrcit & Upazila -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="relative">
                                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('District') }} <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <select v-model="form.district" @change="handleDistrictChange" required class="block w-full rounded border border-slate-200 bg-slate-50 pl-4 pr-8 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                                                    <option value="" disabled>{{ $t('Select District') }}</option>
                                                    <option v-for="d in districts" :key="d.district" :value="d.district">{{ d.district }}</option>
                                                </select>
                                                <ChevronDown class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Area / Thana / Upazila') }} <span class="text-red-500">*</span></label>
                                            <div v-if="upazilas.length > 0" class="relative">
                                                <select v-model="form.upazila" required class="block w-full rounded border border-slate-200 bg-slate-50 pl-4 pr-8 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                                                    <option value="" disabled>{{ $t('Select Area') }}</option>
                                                    <option v-for="u in upazilas" :key="u" :value="u">{{ u }}</option>
                                                </select>
                                                <ChevronDown class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
                                            </div>
                                            <input 
                                                v-else 
                                                v-model="form.upazila" 
                                                type="text" 
                                                required 
                                                :placeholder="loadingUpazilas ? $t('Loading areas...') : $t('Enter Thana / Upazila')"
                                                class="block w-full rounded border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all"
                                            />
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Street Address / Village details') }} <span class="text-red-500">*</span></label>
                                        <input v-model="form.village" type="text" required class="block w-full rounded border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all placeholder:font-bold" :placeholder="$t('House number, street name, village details')">
                                    </div>

                                    <!-- Phone & Email -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Mobile No.') }} <span class="text-red-500">*</span></label>
                                            <input v-model="form.customer_phone" type="text" required class="block w-full rounded border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all placeholder:font-bold" :placeholder="$t('01XXXXXXXXX')">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Email address') }}</label>
                                            <input v-model="form.customer_email" type="email" class="block w-full rounded border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all placeholder:font-bold" :placeholder="$t('john@example.com')">
                                        </div>
                                    </div>

                                    <!-- Dummy Checkboxes for Paragon authenticity -->
                                    <div class="pt-2 space-y-2">
                                        <label class="flex items-center gap-2 text-xs font-bold text-slate-655 cursor-pointer">
                                            <input type="checkbox" class="rounded border-slate-350 text-[#00a651] focus:ring-[#00a651] w-4 h-4 cursor-pointer" />
                                            <span>Create an account?</span>
                                        </label>
                                        <label class="flex items-center gap-2 text-xs font-bold text-slate-655 cursor-pointer">
                                            <input type="checkbox" class="rounded border-slate-350 text-[#00a651] focus:ring-[#00a651] w-4 h-4 cursor-pointer" />
                                            <span>Ship to a different address?</span>
                                        </label>
                                    </div>

                                    <!-- Delivery Location Selection -->
                                    <div class="pt-4">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2.5">{{ $t('Delivery Location & Charges') }} <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <label 
                                                class="relative flex items-center p-3 rounded border cursor-pointer transition-all"
                                                :class="form.delivery_location === 'Inside Dhaka' ? 'border-[#00a651] bg-[#00a651]/5' : 'border-slate-200 hover:border-slate-300'"
                                            >
                                                <input type="radio" v-model="form.delivery_location" value="Inside Dhaka" class="hidden">
                                                <div class="flex items-center space-x-2.5">
                                                    <div class="w-4.5 h-4.5 rounded-full border flex items-center justify-center" :class="form.delivery_location === 'Inside Dhaka' ? 'border-[#00a651]' : 'border-slate-300'">
                                                        <div v-if="form.delivery_location === 'Inside Dhaka'" class="w-2.5 h-2.5 rounded-full bg-[#00a651]"></div>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-black text-slate-900 uppercase leading-none">{{ $t('Inside Dhaka') }}</p>
                                                        <p class="text-[10px] font-bold text-slate-500 mt-1">৳{{ delivery_charges.inside_dhaka }} {{ $t('Delivery Charge') }}</p>
                                                    </div>
                                                </div>
                                            </label>

                                            <label 
                                                class="relative flex items-center p-3 rounded border cursor-pointer transition-all"
                                                :class="form.delivery_location === 'Outside Dhaka' ? 'border-[#00a651] bg-[#00a651]/5' : 'border-slate-200 hover:border-slate-300'"
                                            >
                                                <input type="radio" v-model="form.delivery_location" value="Outside Dhaka" class="hidden">
                                                <div class="flex items-center space-x-2.5">
                                                    <div class="w-4.5 h-4.5 rounded-full border flex items-center justify-center" :class="form.delivery_location === 'Outside Dhaka' ? 'border-[#00a651]' : 'border-slate-300'">
                                                        <div v-if="form.delivery_location === 'Outside Dhaka'" class="w-2.5 h-2.5 rounded-full bg-[#00a651]"></div>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-black text-slate-900 uppercase leading-none">{{ $t('Outside Dhaka') }}</p>
                                                        <p class="text-[10px] font-bold text-slate-500 mt-1">৳{{ delivery_charges.outside_dhaka }} {{ $t('Delivery Charge') }}</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="pt-2">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">{{ $t('Order notes (optional)') }}</label>
                                        <textarea v-model="form.customer_remarks" rows="3" class="block w-full rounded border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold focus:border-[#00a651] focus:ring-0 focus:bg-white outline-none transition-all" :placeholder="$t('Notes about your order, e.g. special notes for delivery.')"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Right: Order Summary Sidebar -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-white rounded border border-slate-205 p-6 md:p-8 shadow-sm">
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-6 pb-2 border-b border-slate-100">
                                {{ $t('Your Order') }}
                            </h2>

                            <!-- Products manifest table -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-center text-[10px] font-black uppercase text-slate-400 border-b border-slate-50 pb-2 mb-3">
                                    <span>Product</span>
                                    <span>Subtotal</span>
                                </div>
                                <ul role="list" class="divide-y divide-slate-100 p-0 m-0 max-h-[300px] overflow-y-auto pr-1">
                                    <li v-for="item in cart.items" :key="item.id" class="flex items-center justify-between py-3 text-xs font-bold gap-4">
                                        <div class="flex-1 min-w-0">
                                            <span class="text-slate-900 font-extrabold line-clamp-2 leading-snug">{{ item.product?.name || item.combo?.name }}</span>
                                            <span class="text-[10px] font-mono text-slate-400 mt-1 block">Qty: {{ item.quantity }}</span>
                                        </div>
                                        <span class="text-slate-900 font-black flex-shrink-0">৳{{ (getItemPrice(item) * item.quantity).toLocaleString() }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Totals rows -->
                            <div class="border-t border-slate-150 py-4 space-y-3 text-xs font-bold">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Subtotal</span>
                                    <span class="text-slate-905 font-black">৳{{ subtotal.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-slate-500">Shipping</span>
                                    <span class="text-slate-905 font-black">৳{{ currentDeliveryCharge.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-slate-205 text-sm font-black uppercase tracking-wider">
                                    <span class="text-slate-900">Total</span>
                                    <span class="text-[#00a651] font-black">৳{{ total.toLocaleString() }}</span>
                                </div>
                            </div>

                            <!-- Payment Gateways Options -->
                            <div class="border-t border-slate-150 pt-5 mt-4 space-y-3">
                                <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-wider mb-3">Payment Method</h3>
                                
                                <div class="space-y-2.5">
                                    <label class="flex items-center justify-between p-3 border border-slate-202 rounded bg-slate-50 hover:bg-white transition-all cursor-pointer">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" value="cod" checked disabled class="rounded-full border-slate-350 text-[#00a651] focus:ring-[#00a651] w-4.5 h-4.5 cursor-pointer" />
                                            <div>
                                                <span class="text-xs font-black text-slate-900">Cash on Delivery</span>
                                                <p class="text-[9px] font-semibold text-slate-400 mt-0.5">Pay with cash upon delivery.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Cosmetic bKash -->
                                    <label class="flex items-center justify-between p-3 border border-slate-105 rounded bg-slate-50 hover:bg-white transition-all opacity-85 cursor-not-allowed">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" value="bkash" disabled class="rounded-full border-slate-350 text-[#00a651] w-4.5 h-4.5 cursor-not-allowed" />
                                            <div>
                                                <span class="text-xs font-black text-slate-400">bKash (Temporarily unavailable)</span>
                                                <p class="text-[9px] font-semibold text-slate-400 mt-0.5">Instant mobile payment gateway.</p>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- Cosmetic Nagad -->
                                    <label class="flex items-center justify-between p-3 border border-slate-105 rounded bg-slate-50 hover:bg-white transition-all opacity-85 cursor-not-allowed">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" value="nagad" disabled class="rounded-full border-slate-350 text-[#00a651] w-4.5 h-4.5 cursor-not-allowed" />
                                            <div>
                                                <span class="text-xs font-black text-slate-400">Nagad (Temporarily unavailable)</span>
                                                <p class="text-[9px] font-semibold text-slate-400 mt-0.5">Fast mobile payment channel.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Terms and Conditions check -->
                            <div class="pt-5 mt-4 border-t border-slate-150">
                                <label class="flex items-start gap-2.5 text-xs font-bold text-slate-655 cursor-pointer">
                                    <input type="checkbox" v-model="agreeTerms" class="rounded border-slate-350 text-[#00a651] focus:ring-[#00a651] w-4 h-4 cursor-pointer mt-0.5" />
                                    <span class="leading-snug">I have read and agree to the website <a href="/terms" target="_blank" class="text-[#00a651] underline hover:text-[#008f45]">terms and conditions</a> <span class="text-red-500">*</span></span>
                                </label>
                            </div>

                            <!-- Submit action button -->
                            <div class="pt-6">
                                <button 
                                    type="button" 
                                    @click="confirmAndSubmit" 
                                    :disabled="!agreeTerms || form.processing" 
                                    class="w-full py-3.5 bg-[#00a651] hover:bg-[#008f45] text-white font-black text-xs uppercase tracking-wider rounded select-none border-none cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed transition-colors text-center"
                                >
                                    {{ $t('Place Order') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { useCart } from '@/Composables/useCart';
import { ChevronRight, ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    cart: Object,
    delivery_charges: Object,
    selectedItemIds: Array
});

const { clearCart } = useCart();
const agreeTerms = ref(false);

const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    shipping_address: '',
    district: 'Dhaka', // Default to Dhaka
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
    // Initialize Upazilas for default district Dhaka
    handleDistrictChange();
});

const confirmAndSubmit = () => {
    if (!agreeTerms.value) return;
    form.delivery_charge = currentDeliveryCharge.value;
    form.post('/checkout', {
        onSuccess: () => {
            clearCart();
        }
    });
};
</script>

<style scoped>
/* Scoped styles */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.25s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
