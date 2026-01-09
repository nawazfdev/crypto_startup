<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web3 Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Web3 blockchain interactions
    |
    */

    'rpc_url' => env('WEB3_RPC_URL', 'http://127.0.0.1:8545'),
    
    'contract_address' => env('NFT_MARKETPLACE_CONTRACT_ADDRESS', ''),
    
    'network' => env('WEB3_NETWORK', 'localhost'), // localhost, sepolia, mumbai
    
    'chain_id' => env('WEB3_CHAIN_ID', 1337),
    
    'listing_price' => env('NFT_LISTING_PRICE', 0.0025), // in ETH
];

