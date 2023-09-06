:orphan:

Catalog Products
================

This package the following API endpoints for Catalog Products:

* Create Product.
* List Products.
* Show Product Details.
* Update Product.


Create Product
--------------

This implementation creates a product using the following endpoint for PayPal Rest API:

.. code-block:: console

    $data =  json_decode("{
    "name": "Video Streaming Service",
    "description": "Video streaming service",
    "type": "SERVICE",
    "category": "SOFTWARE",
    "image_url": "https://example.com/streaming.jpg",
    "home_url": "https://example.com/home"
    }", true);

    $product = $provider->setRequestHeader('PayPal-Request-Id', 'create-product-'.time())->createProduct($data);


List Products
-------------

This implementation lists products by implementing the following endpoint for PayPal Rest API:

https://developer.paypal.com/docs/api/catalog-products/v1/#products_list

.. code-block:: console

    $plans = $provider->listProducts();

By default, the API returns a paginated response and only includes the first 20 results. However if you pass your own parameters, you can do writing the following:

.. code-block:: console

    $provider = $provider->setPageSize(30)->showTotals(true);
    $plans = $provider->setCurrentPage(1)->listProducts();

In the above snippet, we are returning the products containing upto 30 items in each paginated response along with count details.


Show Product Details
--------------------

This implementation lists a product details using the following endpoint for PayPal Rest API:

https://developer.paypal.com/docs/api/catalog-products/v1/#products_get

.. code-block:: console

    $product_id = '72255d4849af8ed6e0df1173';

    $product = $provider->showProductDetails($product_id);


Update Product
--------------

This implementation updates a product details using the following endpoint for PayPal Rest API:

https://developer.paypal.com/docs/api/catalog-products/v1/#products_patch

.. code-block:: console

    $data = json_decode("[
    {
        "op": "replace",
        "path": "/description",
        "value": "Premium video streaming service"
    }
    ]", true);

    $product_id = '72255d4849af8ed6e0df1173';

    $product = $provider->updateProduct($product_id, $data);
