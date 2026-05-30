<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    countries: Array,
    businessTypes: Array,
    paymentMethods: Array,
    billingCycles: Array,
})

const currentStep = ref(1)
const totalSteps = 4

const form = useForm({
    // Step 1: Business Information
    business_name: '',
    business_type: '',
    country: 'Bangladesh',
    region: '',
    city: '',
    address: '',
    postal_code: '',
    
    // Step 2: Contact Information
    email: '',
    phone: '',
    emergency_contact: '',
    preferred_payment_method: 'bkash',
    
    // Step 3: Owner Information
    owner_name: '',
    password: '',
    password_confirmation: '',
})

const nextStep = () => {
    if (currentStep.value < totalSteps) {
        currentStep.value++
    }
}

const previousStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--
    }
}

const submit = () => {
    form.post(route('tenant.register.store'), {
        onSuccess: () => {
            // Redirect handled by controller
        },
    })
}
</script>

<template>
    <Head title="Register Your Business - Stockora" />

    <div class="min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">
                    📦 Start Your Free Trial
                </h1>
                <p class="text-xl text-blue-100">
                    14 days free • No credit card required
                </p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div 
                        v-for="step in totalSteps" 
                        :key="step" 
                        class="flex-1"
                    >
                        <div class="relative">
                            <div 
                                class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-lg font-semibold transition-colors"
                                :class="step <= currentStep ? 'bg-white text-blue-600' : 'bg-blue-400 text-white'"
                            >
                                {{ step }}
                            </div>
                            <div 
                                v-if="step < totalSteps" 
                                class="absolute top-5 left-1/2 w-full h-1 -z-10"
                                :class="step < currentStep ? 'bg-white' : 'bg-blue-400'"
                            ></div>
                        </div>
                        <p class="text-center text-sm text-white mt-2">
                            <span v-if="step === 1">Business</span>
                            <span v-if="step === 2">Contact</span>
                            <span v-if="step === 3">Owner</span>
                            <span v-if="step === 4">Review</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <form @submit.prevent="submit">
                    
                    <!-- Step 1: Business Information -->
                    <div v-show="currentStep === 1" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Business Information</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Name *
                            </label>
                            <input
                                v-model="form.business_name"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="ABC Store"
                            />
                            <span v-if="form.errors.business_name" class="text-sm text-red-600">
                                {{ form.errors.business_name }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Type
                            </label>
                            <select
                                v-model="form.business_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select business type</option>
                                <option 
                                    v-for="type in businessTypes" 
                                    :key="type.value" 
                                    :value="type.value"
                                >
                                    {{ type.label }}
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Country *
                                </label>
                                <select
                                    v-model="form.country"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                >
                                    <option 
                                        v-for="country in countries" 
                                        :key="country.value" 
                                        :value="country.value"
                                    >
                                        {{ country.label }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Region/State
                                </label>
                                <input
                                    v-model="form.region"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Dhaka"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    City
                                </label>
                                <input
                                    v-model="form.city"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Dhaka"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Postal Code
                                </label>
                                <input
                                    v-model="form.postal_code"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="1000"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Address
                            </label>
                            <textarea
                                v-model="form.address"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Street address, building, etc."
                            ></textarea>
                        </div>
                    </div>

                    <!-- Step 2: Contact Information -->
                    <div v-show="currentStep === 2" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Email *
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="business@example.com"
                            />
                            <span v-if="form.errors.email" class="text-sm text-red-600">
                                {{ form.errors.email }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number *
                            </label>
                            <input
                                v-model="form.phone"
                                type="tel"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="01712345678"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                This will be used for important notifications
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Emergency Contact Number
                            </label>
                            <input
                                v-model="form.emergency_contact"
                                type="tel"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="01812345678"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                Optional - For account recovery
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Preferred Payment Method
                            </label>
                            <select
                                v-model="form.preferred_payment_method"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option 
                                    v-for="method in paymentMethods" 
                                    :key="method.value" 
                                    :value="method.value"
                                >
                                    {{ method.label }}
                                </option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                For subscription payments after trial ends
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Owner Information -->
                    <div v-show="currentStep === 3" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Owner Account</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Your Name *
                            </label>
                            <input
                                v-model="form.owner_name"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="John Doe"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password *
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="••••••••"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                Minimum 8 characters
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password *
                            </label>
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="••••••••"
                            />
                            <span v-if="form.errors.password" class="text-sm text-red-600">
                                {{ form.errors.password }}
                            </span>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800">
                                <strong>🔐 Account Security:</strong> This will be your admin account with full access to your business data.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4: Review -->
                    <div v-show="currentStep === 4" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Review & Confirm</h2>
                        
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Business Information</h3>
                                <p class="text-gray-900">{{ form.business_name }}</p>
                                <p class="text-sm text-gray-600">{{ form.business_type || 'Not specified' }}</p>
                                <p class="text-sm text-gray-600">{{ form.country }}, {{ form.region || form.city }}</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Contact</h3>
                                <p class="text-gray-900">{{ form.email }}</p>
                                <p class="text-sm text-gray-600">{{ form.phone }}</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Owner</h3>
                                <p class="text-gray-900">{{ form.owner_name }}</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Payment Method</h3>
                                <p class="text-gray-900 capitalize">{{ form.preferred_payment_method?.replace('_', ' ') }}</p>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-900 mb-2">
                                ✅ Your 14-Day Free Trial Includes:
                            </h3>
                            <ul class="space-y-2 text-sm text-green-800">
                                <li>✓ Complete POS & Sales system</li>
                                <li>✓ Inventory management</li>
                                <li>✓ Customer & supplier management</li>
                                <li>✓ Business analytics & reports</li>
                                <li>✓ Multi-user access (up to 5 users)</li>
                                <li>✓ Email & SMS notifications</li>
                            </ul>
                            <p class="text-xs text-green-700 mt-4">
                                No credit card required • Cancel anytime • Full feature access
                            </p>
                        </div>

                        <div class="flex items-start">
                            <input 
                                type="checkbox" 
                                id="terms" 
                                required
                                class="mt-1 mr-3 h-4 w-4 text-blue-600 rounded"
                            />
                            <label for="terms" class="text-sm text-gray-700">
                                I agree to the 
                                <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> 
                                and 
                                <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                        <button
                            v-if="currentStep > 1"
                            type="button"
                            @click="previousStep"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                        >
                            ← Previous
                        </button>
                        <div v-else></div>

                        <button
                            v-if="currentStep < totalSteps"
                            type="button"
                            @click="nextStep"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors"
                        >
                            Next →
                        </button>

                        <button
                            v-if="currentStep === totalSteps"
                            type="submit"
                            :disabled="form.processing"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors disabled:opacity-50"
                        >
                            <span v-if="form.processing">Creating Account...</span>
                            <span v-else>🚀 Start Free Trial</span>
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="/login" class="text-blue-600 hover:underline font-medium">
                            Login here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>