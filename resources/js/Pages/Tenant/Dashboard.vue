<script setup>
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    tenant: Object,
    user: Object,
})
</script>

<template>
    <Head :title="`Dashboard - ${tenant.business_name}`" />

    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        📦 {{ tenant.business_name }}
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Client ID: <span class="font-mono font-semibold text-blue-600">{{ tenant.client_id }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Welcome back,</p>
                    <p class="font-semibold text-gray-900">{{ user.name }}</p>
                    <p class="text-xs text-gray-500">{{ user.roles.join(', ') }}</p>
                </div>
            </div>
        </header>

        <!-- Trial Banner -->
        <div v-if="tenant.is_on_trial" class="bg-gradient-to-r from-yellow-400 to-orange-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">⏰</span>
                        <div>
                            <p class="font-semibold text-white">
                                You're on a free trial
                            </p>
                            <p class="text-sm text-yellow-100">
                                Trial ends on {{ tenant.trial_ends_at }}
                            </p>
                        </div>
                    </div>
                    <a 
                        href="#" 
                        class="px-6 py-2 bg-white text-orange-600 rounded-lg font-semibold hover:bg-yellow-50 transition-colors"
                    >
                        Subscribe Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Welcome Card -->
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-8 text-white mb-8">
                <h2 class="text-3xl font-bold mb-4">
                    🎉 Welcome to Stockora!
                </h2>
                <p class="text-xl text-blue-100 mb-6">
                    Your account is set up and ready to go. Let's get started with your business!
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4">
                        <div class="text-3xl mb-2">✅</div>
                        <p class="font-semibold">Account Created</p>
                        <p class="text-sm text-blue-100">Trial activated</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4">
                        <div class="text-3xl mb-2">📦</div>
                        <p class="font-semibold">Add Products</p>
                        <p class="text-sm text-blue-100">Start building inventory</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4">
                        <div class="text-3xl mb-2">💰</div>
                        <p class="font-semibold">Make First Sale</p>
                        <p class="text-sm text-blue-100">Use the POS system</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <a 
                    href="#" 
                    class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">📦</div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <span class="text-blue-600">→</span>
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Products</h3>
                    <p class="text-sm text-gray-600">Manage your inventory</p>
                </a>

                <a 
                    href="#" 
                    class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">🏪</div>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                            <span class="text-green-600">→</span>
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">POS</h3>
                    <p class="text-sm text-gray-600">Make a sale</p>
                </a>

                <a 
                    href="#" 
                    class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">👥</div>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                            <span class="text-purple-600">→</span>
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Customers</h3>
                    <p class="text-sm text-gray-600">Manage relationships</p>
                </a>

                <a 
                    href="#" 
                    class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">📊</div>
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                            <span class="text-orange-600">→</span>
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Reports</h3>
                    <p class="text-sm text-gray-600">View analytics</p>
                </a>
            </div>

            <!-- Account Info -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Account Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Business Name</p>
                        <p class="font-semibold text-gray-900">{{ tenant.business_name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Client ID</p>
                        <p class="font-mono font-semibold text-blue-600">{{ tenant.client_id }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <span 
                            class="inline-flex px-3 py-1 rounded-full text-sm font-semibold"
                            :class="tenant.status === 'trial' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'"
                        >
                            {{ tenant.status === 'trial' ? '🎁 Trial' : '✅ Active' }}
                        </span>
                    </div>
                    
                    <div v-if="tenant.trial_ends_at">
                        <p class="text-sm text-gray-600 mb-1">Trial Ends</p>
                        <p class="font-semibold text-gray-900">{{ tenant.trial_ends_at }}</p>
                    </div>
                </div>
            </div>

        </main>
    </div>
</template>