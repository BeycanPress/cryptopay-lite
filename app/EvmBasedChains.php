<?php

namespace BeycanPress\CryptoPayLite;

class EvmBasedChains {

    public static array $mainnets = [
        "ethereum" => [
            "id" => 1,
            "hexId" => "0x1",
            "name" => "Ethereum",
            "rpcUrl" => "https://mainnet.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161",
            "explorerUrl" => "https://etherscan.io/",
            "nativeCurrency" => [
                "symbol" => "ETH",
                "decimals" => 18
            ]
        ],
        "bsc" => [
            "id" => 56,
            "hexId" => "0x38",
            "name" => "BNB Smart Chain",
            "rpcUrl" => "https://bsc-dataseed.binance.org/",
            "explorerUrl" => "https://bscscan.com/",
            "nativeCurrency" => [
                "symbol" => "BNB",
                "decimals" => 18
            ]
        ],
        "avalanche" => [
            "id" => 43114,
            "hexId" => "0xa86a",
            "name" => "Avalanche C-Chain",
            "rpcUrl" => "https://api.avax.network/ext/bc/C/rpc",
            "explorerUrl" => "https://cchain.explorer.avax.network/",
            "nativeCurrency" => [
                "symbol" => "AVAX",
                "decimals" => 18
            ]
        ],
        "polygon" => [
            "id" => 137,
            "hexId" => "0x89",
            "name" => "Polygon",
            "rpcUrl" => "https://polygon-rpc.com/",
            "explorerUrl" => "https://polygonscan.com/",
            "nativeCurrency" => [
                "symbol" => "MATIC",
                "decimals" => 18
            ]
        ],
        "fantom" => [
            "id" => 250,
            "hexId" => "0xfa",
            "name" => "Fantom",
            "rpcUrl" => "https://rpc.fantom.network",
            "explorerUrl" => "https://ftmscan.com/",
            "nativeCurrency" => [
                "symbol" => "FTM",
                "decimals" => 18
            ]
        ]
    ];

    public static array $testnets = [
        "ethereum" => [
            "id" => 5,
            "hexId" => "0x5",
            "mainnetId" => 1,
            "name" => "Ethereum Goerli Testnet",
            "rpcUrl" => "https://goerli.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161",
            "wsUrl" => "wss://goerli.infura.io/ws/v3/9aa3d95b3bc440fa88ea12eaa4456161",
            "explorerUrl" => "https://goerli.etherscan.io/",
            "nativeCurrency" => [
                "symbol" => "ETH",
                "decimals" => 18
            ]
        ],
        "bsc" => [
            "id" => 97,
            "hexId" => "0x61",
            "mainnetId" => 56,
            "name" => "BNB Smart Chain Testnet",
            "rpcUrl" => "https://bsc-testnet.publicnode.com",
            "wsUrl" => "wss://ws-nd-995-258-592.p2pify.com/125dfcd45140ad15911079840c5325ad",
            "explorerUrl" => "https://testnet.bscscan.com/",
            "nativeCurrency" => [
                "symbol" => "BNB",
                "decimals" => 18
            ]
        ],
        "avalanche" => [
            "id" => 43113,
            "hexId" => "0xa869",
            "mainnetId" => 43114,
            "name" => "Avalanche FUJI C-Chain Testnet",
            "rpcUrl" => "https://api.avax-test.network/ext/bc/C/rpc",
            "wsUrl" => "wss://ws-nd-455-905-634.p2pify.com/6391d49e37bb671756732c155bedbd71/ext/bc/C/ws",
            "explorerUrl" => "https://cchain.explorer.avax-test.network",
            "nativeCurrency" => [
                "symbol" => "AVAX",
                "decimals" => 18
            ]
        ],
        "polygon" => [
            "id" => 80001,
            "hexId" => "0x13881",
            "mainnetId" => 137,
            "name" => "Polygon Mumbai Testnet",
            "rpcUrl" => "https://rpc-mumbai.maticvigil.com/",
            "explorerUrl" => "https://mumbai.polygonscan.com/",
            "nativeCurrency" => [
                "symbol" => "MATIC",
                "decimals" => 18
            ]
        ],
        "fantom" => [
            "id" => 4002,
            "hexId" => "0xfa2",
            "mainnetId" => 250,
            "name" => "Fantom Testnet",
            "rpcUrl" => "https://rpc.testnet.fantom.network/",
            "explorerUrl" => "https://testnet.ftmscan.com/",
            "nativeCurrency" => [
                "symbol" => "FTM",
                "decimals" => 18
            ]
        ]
    ];
}