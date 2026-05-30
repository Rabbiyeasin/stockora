<script setup>
   import { Head } from '@inertiajs/vue3'
   
   defineProps({
       app_name: String,
   })
</script>
<template>
   <Head title="Welcome to Stockora" />
      <div class="min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
         <div class="text-center">
            <!-- Logo -->
            <div class="mb-8">
               <h1 class="text-6xl font-bold text-white mb-2">
                  💵 {{ app_name }}
               </h1>
               <p class="text-2xl text-blue-100">
                  Complete Business Management & Intelligence Platform
               </p>
            </div>
            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
               <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-lg p-6 text-white">
                  <div class="text-4xl mb-3">🏪</div>
                  <h3 class="text-xl font-semibold mb-2">POS & Sales</h3>
                  <p class="text-sm text-blue-100">Complete point-of-sale system with inventory tracking</p>
               </div>
               <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-lg p-6 text-white">
                  <div class="text-4xl mb-3">📊</div>
                  <h3 class="text-xl font-semibold mb-2">Analytics</h3>
                  <p class="text-sm text-blue-100">Real-time business intelligence & reports</p>
               </div>
               <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-lg p-6 text-white">
                  <div class="text-4xl mb-3">🤖</div>
                  <h3 class="text-xl font-semibold mb-2">AI Powered</h3>
                  <p class="text-sm text-blue-100">Demand forecasting & churn prediction</p>
               </div>
            </div>
            <!-- CTA Buttons -->
            <!-- CTA Buttons -->
            <div class="flex gap-4 justify-center">
               <a 
                  href="/register" 
                  class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold text-lg hover:bg-blue-50 transition-colors shadow-xl"
                  >
               Start Free Trial
               </a>
               <a 
                  href="/login" 
                  class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition-colors"
                  >
               Login
               </a>
            </div>
            <!-- Version -->
            <div class="mt-12 text-white text-sm opacity-75">
               v1.0.0 | Made with ❤️ for Bangladesh Businesses
            </div>
         </div>
      </div>
</template>
