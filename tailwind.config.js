import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        // Color variants for dynamic classes
        'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-gray-500',
        'bg-red-500', 'bg-indigo-500', 'bg-pink-500', 'bg-orange-500',
        'hover:border-blue-300', 'hover:border-green-300', 'hover:border-yellow-300',
        'hover:border-purple-300', 'hover:border-gray-300', 'hover:border-red-300',
        'text-blue-600', 'text-green-600', 'text-yellow-600', 'text-purple-600',
        'text-gray-600', 'text-red-600', 'text-indigo-600', 'text-pink-600',
        'bg-blue-50', 'bg-green-50', 'bg-yellow-50', 'bg-purple-50', 'bg-gray-50',
        'bg-red-50', 'bg-indigo-50', 'bg-pink-50', 'bg-orange-50',
        'text-blue-900', 'text-green-900', 'text-yellow-900', 'text-purple-900',
        'text-gray-900', 'text-red-900', 'text-indigo-900', 'text-pink-900',
        'text-blue-700', 'text-green-700', 'text-yellow-700', 'text-purple-700',
        'text-gray-700', 'text-red-700', 'text-indigo-700', 'text-pink-700',
        // Status badges
        'bg-green-100', 'bg-yellow-100', 'bg-red-100', 'bg-blue-100', 'bg-gray-100',
        'text-green-800', 'text-yellow-800', 'text-red-800', 'text-blue-800', 'text-gray-800',
        // Grid columns
        'grid-cols-1', 'grid-cols-2', 'grid-cols-3', 'grid-cols-4', 'grid-cols-5', 'grid-cols-6',
        'md:grid-cols-1', 'md:grid-cols-2', 'md:grid-cols-3', 'md:grid-cols-4',
        'lg:grid-cols-1', 'lg:grid-cols-2', 'lg:grid-cols-3', 'lg:grid-cols-4', 'lg:grid-cols-5',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
