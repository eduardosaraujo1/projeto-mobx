import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import wireuiConfig from "./vendor/wireui/wireui/tailwind.config.js";
import colors from "tailwindcss/colors.js";

/** @type {import('tailwindcss').Config} */
export default {
    presets: [wireuiConfig],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/wireui/wireui/src/*.php",
        "./vendor/wireui/wireui/ts/**/*.ts",
        "./vendor/wireui/wireui/src/WireUi/**/*.php",
        "./vendor/wireui/wireui/src/Components/**/*.php",
    ],
    theme: {
        extend: {
            gridTemplateColumns: ({ theme }) => {
                const sizes = [6, 12, 24, 36, 42, 64]; // Define allowed sizes (in rem)
                const object = {};

                for (const size of sizes) {
                    object[`fit-${size}`] = `repeat(auto-fit, minmax(${
                        size / 4
                    }rem, auto))`;
                    object[`fill-${size}`] = `repeat(auto-fill, minmax(${
                        size / 4
                    }rem, auto))`;
                }

                return object;
            },
            colors: {
                primary: colors.gray,
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
