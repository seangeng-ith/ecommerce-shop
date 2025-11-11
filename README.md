# E-commerce Shop (MVC)
Refactor of the original project into a lightweight PHP MVC while keeping all views and assets.

## Run locally
- Point your web server to the `public/` directory.
- Set `base_url` in `config/config.php` (e.g. `http://localhost/ecommerce-mvc/public`).

## Structure
app/
  Core/ (Router, Controller, View, Helpers)
  Controllers/ (Home, Shop, Product, Cart, Contact, Blog)
  Models/ (ProductModel, CartModel)
  Views/ (templates + partials)
  Data/products.json
public/
  index.php, css/, js/, img/
config/
  config.php
