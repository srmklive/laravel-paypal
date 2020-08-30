<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait CatalogProducts
{
    /**
     * Create a product.
     *
     * @param array  $data
     * @param string $request_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/catalog-products/v1/#products_create
     */
    public function createProduct(array $data, $request_id)
    {
        $this->apiEndPoint = 'v1/catalogs/products';
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->options['headers']['PayPal-Request-Id'] = $request_id;
        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * List products.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/catalog-products/v1/#products_list
     */
    public function listProducts()
    {
        $this->apiEndPoint = 'v1/catalogs/products';
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Update a product.
     *
     * @param array  $data
     * @param string $product_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/catalog-products/v1/#products_patch
     */
    public function updateProduct(array $data, $product_id)
    {
        $this->apiEndPoint = "v1/catalogs/products/{$product_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'patch';

        return $this->doPayPalRequest();
    }

    /**
     * Get product details.
     *
     * @param string $product_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/catalog-products/v1/#products_get
     */
    public function showProductDetails($product_id)
    {
        $this->apiEndPoint = "v1/catalogs/products/{$product_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
