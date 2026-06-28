<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div v-if="isOpen" class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-[#002013]/60 backdrop-blur-sm" @click="close"></div>
            
            <!-- Modal Container -->
            <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
                <!-- Close Button -->
                <button 
                    @click="close"
                    class="absolute top-6 right-6 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all z-10"
                >
                    <X class="w-5 h-5" />
                </button>

                <!-- Form Section -->
                <div class="p-8 md:p-10">
                    <!-- Brand Header -->
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-10 h-10 bg-[#00a651] rounded-lg flex items-center justify-center text-white font-black text-2xl">Z</div>
                        <div>
                            <h2 class="text-xl font-black text-[#002013] uppercase tracking-tighter">{{ mode === 'login' ? $t('Welcome Back') : $t('Join Us') }}</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $page.props.settings.site_name }}</p>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <div v-if="mode === 'login'">
                        <form @submit.prevent="handleLogin" class="space-y-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Email Address') }}</label>
                                <div class="relative">
                                    <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input 
                                        v-model="loginForm.email"
                                        type="email" 
                                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:bg-white focus:border-[#00a651] focus:ring-4 focus:ring-[#00a651]/5 outline-none transition-all font-medium"
                                        placeholder="name@example.com"
                                        required
                                    />
                                </div>
                                <p v-if="loginForm.errors.email" class="text-[10px] font-bold text-red-500 mt-1 pl-1 italic">{{ loginForm.errors.email }}</p>
                            </div>

                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center px-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Password') }}</label>
                                    <Link href="/forgot-password" class="text-[10px] font-black text-[#00a651] uppercase tracking-widest hover:underline">{{ $t('Forgot?') }}</Link>
                                </div>
                                <div class="relative">
                                    <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input 
                                        v-model="loginForm.password"
                                        type="password" 
                                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:bg-white focus:border-[#00a651] focus:ring-4 focus:ring-[#00a651]/5 outline-none transition-all font-medium"
                                        placeholder="••••••••"
                                        required
                                    />
                                </div>
                                <p v-if="loginForm.errors.password" class="text-[10px] font-bold text-red-500 mt-1 pl-1 italic">{{ loginForm.errors.password }}</p>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="loginForm.processing"
                                class="w-full py-4 bg-[#00a651] text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-green-500/20 hover:bg-[#008541] hover:-translate-y-0.5 active:scale-[0.98] transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:translate-y-0"
                            >
                                <Loader2 v-if="loginForm.processing" class="w-4 h-4 animate-spin" />
                                <span>{{ $t('Sign In Now') }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div v-else>
                        <form @submit.prevent="handleRegister" class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Full Name') }}</label>
                                <div class="relative">
                                    <User class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input 
                                        v-model="registerForm.name"
                                        type="text" 
                                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:bg-white focus:border-[#00a651] focus:ring-4 focus:ring-[#00a651]/5 outline-none transition-all font-medium"
                                        placeholder="Enter your name"
                                        required
                                    />
                                </div>
                                <p v-if="registerForm.errors.name" class="text-[10px] font-bold text-red-500 mt-1 pl-1 italic">{{ registerForm.errors.name }}</p>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Email Address') }}</label>
                                <div class="relative">
                                    <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input 
                                        v-model="registerForm.email"
                                        type="email" 
                                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:bg-white focus:border-[#00a651] focus:ring-4 focus:ring-[#00a651]/5 outline-none transition-all font-medium"
                                        placeholder="name@example.com"
                                        required
                                    />
                                </div>
                                <p v-if="registerForm.errors.email" class="text-[10px] font-bold text-red-500 mt-1 pl-1 italic">{{ registerForm.errors.email }}</p>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Mobile') }}</label>
                                <div class="relative">
                                    <Phone class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input 
                                        v-model="registerForm.mobile"
                                        type="tel" 
                                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:bg-white focus:border-[#00a651] focus:ring-4 focus:ring-[#00a651]/5 outline-none transition-all font-medium"
                                        placeholder="01XXXXXXXXX"
                                        required
                                    />
                                </div>
                                <p v-if="registerForm.errors.mobile" class="text-[10px] font-bold text-red-500 mt-1 pl-1 italic">{{ registerForm.errors.mobile }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Password') }}</label>
                                    <input 
                                        v-model="registerForm.password"
                                        type="password" 
                                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:bg-white focus:border-[#00a651] focus:ring-4 focus:ring-[#00a651]/5 outline-none transition-all font-medium"
                                        placeholder="••••••••"
                                        required
                                    />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $t('Confirm') }}</label>
                                    <input 
                                        v-model="registerForm.password_confirmation"
                                        type="password" 
                                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:bg-white focus:border-[#00a651] focus:ring-4 focus:ring-[#00a651]/5 outline-none transition-all font-medium"
                                        placeholder="••••••••"
                                        required
                                    />
                                </div>
                            </div>
                            <p v-if="registerForm.errors.password" class="text-[10px] font-bold text-red-500 mt-1 pl-1 italic">{{ registerForm.errors.password }}</p>

                            <button 
                                type="submit" 
                                :disabled="registerForm.processing"
                                class="w-full py-4 bg-[#002013] text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-900/10 hover:bg-slate-800 hover:-translate-y-0.5 active:scale-[0.98] transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:translate-y-0"
                            >
                                <Loader2 v-if="registerForm.processing" class="w-4 h-4 animate-spin" />
                                <span>{{ $t('Create Account') }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                            {{ mode === 'login' ? $t("Don't have an account?") : $t("Already have an account?") }}
                        </p>
                        <button 
                            @click="mode = mode === 'login' ? 'register' : 'login'"
                            class="text-xs font-black text-[#00a651] uppercase tracking-[0.2em] hover:underline"
                        >
                            {{ mode === 'login' ? $t('Register Now') : $t('Sign In Instead') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { X, Mail, Lock, User, Phone, Loader2 } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const props = defineProps({
    isOpen: Boolean,
    initialMode: {
        type: String,
        default: 'login'
    }
});

const emit = defineEmits(['close']);

const { addToast } = useToast();

const mode = ref(props.initialMode);

const loginForm = useForm({
    email: '',
    password: '',
    remember: true
});

const registerForm = useForm({
    name: '',
    email: '',
    mobile: '',
    password: '',
    password_confirmation: ''
});

const close = () => {
    emit('close');
    loginForm.clearErrors();
    registerForm.clearErrors();
};

const handleLogin = () => {
    loginForm.post('/login', {
        onSuccess: () => {
            addToast({
                type: 'success',
                message: 'Welcome back! You have successfully signed in.'
            });
            close();
        },
    });
};

const handleRegister = () => {
    registerForm.post('/register', {
        onSuccess: () => {
            addToast({
                type: 'success',
                message: 'Account created successfully! Welcome to the store.'
            });
            close();
        },
    });
};
</script>
