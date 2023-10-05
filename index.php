<?php


echo("Charlie Howes - Quickfire Digital - Backend Developer Test\n");
echo("------------------------------------------------------\n");

//Shopify Admin REST API Tokens and Information.
$apiToken = "";
$adminApiToken = "";
$shopName = "";
$apiVersion = "2023-10";

//Admin API URLs
$adminURL = "https://{$apiToken}:{$adminApiToken}@{$shopName}.myshopify.com/admin/api/{$apiVersion}";

//Product API URLs
$productsURL = "{$adminURL}/products.json";
$updateProductURL = "{$adminURL}/products/";

/**
 * Generate the necessary JSON data for creating a product using the Shopify Admin API
 * with the option for ID input to build JSON data for updating a product.
 * 
 * @param string $title
 * @param string $body 
 * @param string $vendor
 * @param string $type
 * @param array<string> $tags
 * @param int $productID
 * 
 * @return string
 */

function GenerateProductData(string $title, string $body, string $vendor, string $type, array $tags, int $productID=null) {
    $productData = array();

    //If a product ID is specified generate JSON data for updating a product.
    if (!isset($productID)) {
        $productData = array("product" => array(
            "id" => $productID,
            "title" => $title,
            "body_html" => $body,
            "vendor" => $vendor,
            "product_type" => $type,
            "tags" => $tags
        ));
    //Otherwise generate JSON data for creating a product.
    } else {
        $productData = array("product" => array(
            "title" => $title,
            "body_html" => $body,
            "vendor" => $vendor,
            "product_type" => $type,
            "tags" => $tags
        ));
    }

    return json_encode($productData);
}

/**
 * Add a product to your Shopify shop using the Shopify Admin API
 * by specifying product data using the `GenerateProductData` function.
 * 
 * @param string $productURL
 * @param string $productData
 * @param bool $printResult
 * 
 * @return void
 */

function AllocateShopProduct(string $productURL, string $productData, bool $printResult=false) {
    //Setup a cURL request to send to Shopify's Admin REST API to add a product to the shop.
    $curlRequest = curl_init($productURL);
    //Return the result of curl_exec as a string.
    curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
    //Set the Content-Type header to recieve the JSON response from Shopify.
    curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    //Add the product JSON data to the request.
    curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $productData);
    curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curlRequest, CURLOPT_SSL_VERIFYPEER, false);
    $curlResponse =  curl_exec($curlRequest);

    //In the event of an error, print the error.
    if (curl_errno($curlRequest))
        echo "[REQUEST ERROR]: " . curl_error($curlRequest);

    curl_close($curlRequest);

    //Decode the JSON and print the response in a readable format.
    if ($printResult) {
        $decodedResponse = json_decode($curlResponse, true);
        print_r($decodedResponse);
    }
}

/**
 * Update a product in your Shopify shop using the Shopify Admin API
 * by specifying a product ID and product data.
 * 
 * @param string $updateProductURL
 * @param int $productID
 * @param string $productData
 * @param bool $printResult
 * 
 * @return void
 */

function UpdateShopProduct(string $updateProductURL, int $productID, string $productData, bool $printResult=false) {
    //Setup cURL request to send to Shopify's Admin REST API to update a product in the shop.
    $constructedURL = "{$updateProductURL}{$productID}.json";

    $curlRequest = curl_init($constructedURL);
    //Return the result of curl_exec as a string.
    curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
    //Set the Content-Type header to recieve the JSON response from Shopify.
    curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    //Add the product JSON data to the request.
    curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $productData);
    curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curlRequest, CURLOPT_SSL_VERIFYPEER, false);
    $curlResponse =  curl_exec($curlRequest);

    //In the event of an error, print the error.
    if (curl_errno($curlRequest))
    echo "[REQUEST ERROR]: " . curl_error($curlRequest);

    curl_close($curlRequest);

    //Decode the JSON and print the response in a readable format.
    if ($printResult) {
        $decodedResponse = json_decode($curlResponse, true);
        print_r($decodedResponse);
    }
}

/**
 * Retrieve all products present in the shop as JSON data and display them.
 * 
 * @param string $productURL
 * 
 * @return string
 */

function RetrieveAllProducts(string $productURL, bool $printResult=false) {
        //Setup a cURL request to send to Shopify's Admin REST API to add a product to the shop.
        $curlRequest = curl_init($productURL);
        //Return the result of curl_exec as a string.
        curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curlRequest, CURLOPT_SSL_VERIFYPEER, false);
        $curlResponse =  curl_exec($curlRequest);
    
        //In the event of an error, print the error.
        if (curl_errno($curlRequest))
            echo "[REQUEST ERROR]: " . curl_error($curlRequest);
    
        curl_close($curlRequest);
    
        //Decode the JSON and print the response in a readable format.
        if ($printResult) {
            $decodedResponse = json_decode($curlResponse, true);
            print_r($decodedResponse);
        }

        return json_encode($curlResponse);
}

$testProduct = GenerateProductData(
    "A lovely new product!",
    "This is an absolutely wonderful test product!",
    "Charlie",
    "Test",
    array("In Development", "Beta", "Alpha")
);

$testProductUpdate = GenerateProductData(
    "A lovely new different and updated product!", 
    "We have since updated this product and it's incredible.", 
    "Charlie", 
    "Updated Test", 
    array("New and Improved", "Release"),
    8117763637483
);

//AllocateShopProduct($productsURL, $testProduct, true);
//UpdateShopProduct($updateProductURL, 8117763637483, $testProductUpdate, true);
RetrieveAllProducts($productsURL, true);


?>