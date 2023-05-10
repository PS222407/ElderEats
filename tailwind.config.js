/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {},
        colors: {
            'banner': '#519162',
            'label': '#777777',
            'hamburger': '#365F41',
            'icon': '#7FCA95',
        },
    },
    plugins: [
        require('flowbite/plugin'),
    ]
}

