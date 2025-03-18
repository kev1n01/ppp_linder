<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
    width="245.000000pt" height="245.000000pt" viewBox="0 0 245.000000 245.000000"
    :class="className" v-bind="$attrs"
    preserveAspectRatio="xMidYMid meet">

        <g transform="translate(0.000000,245.000000) scale(0.100000,-0.100000)"
        fill="currentColor" stroke="none">
        <path d="M1110 1831 c-104 -32 -160 -107 -160 -213 l0 -38 115 0 115 0 0 44
        c0 31 5 46 16 50 9 3 110 6 225 6 l209 0 0 49 c0 47 -1 49 -51 80 l-50 31
        -197 -1 c-108 0 -208 -4 -222 -8z" />
        <path d="M950 1343 c0 -95 39 -157 122 -194 48 -21 70 -23 253 -27 l200 -3 50
        33 c47 31 50 36 53 81 l3 47 -210 0 c-201 0 -212 1 -232 21 -15 15 -20 30 -17
        50 l5 29 -114 0 -113 0 0 -37z" />
        <path d="M955 920 c3 -151 11 -292 17 -313 18 -59 79 -113 155 -137 36 -11 89
        -20 119 -20 l54 0 0 70 0 69 -40 11 c-67 18 -70 28 -70 262 l0 208 -28 0 c-73
        0 -156 42 -195 98 -18 26 -18 22 -12 -248z" />
        </g>
    </svg>

</template>
