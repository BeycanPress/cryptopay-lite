<?php
    if (BeycanPress\CryptoPayLite\Helpers::getTheme($addon) === 'dark') {
        $color1 = 'rgb(28 34 43)';
        $color2 = 'rgb(24 29 36)';
    } else {
        $color1 = '#f3f3f3';
        $color2 = '#ecebeb';
    }
?>
<template id="cp-loading-svg">
    <svg
        role="img"
        width="100%"
        height="360"
        aria-labelledby="loading-aria"
        viewBox="0 0 430 360"
        style="max-width: 430px;"
        preserveAspectRatio="none"
    >
        <title id="loading-aria">Loading...</title>
        <rect
            x="0"
            y="0"
            width="100%"
            height="100%"
            fill="url(#fill)"
            clip-path="url(#clip-path)"
        ></rect>
        <defs>
            <clipPath id="clip-path">
                <rect x="0" y="0" rx="0" ry="0" width="100%" height="75" /> 
                <rect x="0" y="80" rx="0" ry="0" width="100%" height="32" /> 
                <rect x="0" y="117" rx="0" ry="0" width="100%" height="200" />
                <rect x="150" y="335" rx="0" ry="0" width="130" height="25" /> 
            </clipPath>
            <linearGradient id="fill">
            <stop
                offset="0.599964"
                stop-color="<?php echo $color1; ?>"
                stop-opacity="1"
            >
                <animate
                attributeName="offset"
                values="-2; -2; 1"
                keyTimes="0; 0.25; 1"
                dur="2s"
                repeatCount="indefinite"
                ></animate>
            </stop>
            <stop
                offset="1.59996"
                stop-color="<?php echo $color2; ?>"
                stop-opacity="1"
            >
                <animate
                attributeName="offset"
                values="-1; -1; 2"
                keyTimes="0; 0.25; 1"
                dur="2s"
                repeatCount="indefinite"
                ></animate>
            </stop>
            <stop
                offset="2.59996"
                stop-color="<?php echo $color1; ?>"
                stop-opacity="1"
            >
                <animate
                attributeName="offset"
                values="0; 0; 3"
                keyTimes="0; 0.25; 1"
                dur="2s"
                repeatCount="indefinite"
                ></animate>
            </stop>
            </linearGradient>
        </defs>
    </svg>
</template>

<cp-svg-loader></cp-svg-loader>

<script>
    if (!customElements.get('cp-svg-loader')) {
        class CPSVGLoader extends HTMLElement {
            constructor() {
                super();
                const shadowRoot = this.attachShadow({ mode: 'open' });
                shadowRoot.appendChild(document.getElementById('cp-loading-svg').content.cloneNode(true));
            }
        }
        customElements.define('cp-svg-loader', CPSVGLoader);
    }
</script>