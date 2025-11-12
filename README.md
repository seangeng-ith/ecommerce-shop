# Eshop Pro — PHP MVC
Lightweight PHP MVC storefront with responsive views, simple routing, and zero external dependencies.

## Quick Start
- Point your web server to `public/`.
- Set `base_url` in `config/config.php` to your site root (e.g. `http://localhost/ecommerce-shop/public`).
- Optional: run the PHP built‑in server: `php -S localhost:8000 -t public`

## Features
- Modern homepage with hero, brands, panels, and promos
- Shop listing with filters, result count, and pagination
- Product detail with related products and add‑to‑cart
- Blog page layout and hero
- Contact form with clean layout
- Account area with orders and change password
- Footer redesigned to match site style (light theme)
- Password show/hide toggle on Login, Register, and Account forms

## Pages & Routes
All pages are served via `index.php?page=...`:
- `home`, `shop`, `product&id=...`, `cart`
- `blog`, `contact`
- `login`, `register`, `forgot`
- `account`, `orders`, `order&id=...`, `checkout`, `payment&id=...`

## Project Layout
```
app/
  Core/            Router, Controller, View, Helpers
  Controllers/     Home, Shop, Product, Cart, Contact, Blog, Account, Orders, Payment
  Models/          ProductModel, CartModel
  Views/           Templates + partials (header/footer)
  Data/            products.json
public/
  index.php        Front controller
  css/             Global + page styles
  js/              app.js (UX helpers, AJAX, toggles)
  img/             Assets
config/
  config.php       App config (app_name, base_url)
```

## Styling System
- Global tokens live in `public/css/styles.css` (`--brand`, `--border`, `--radius`, `--shadow`).
- Page‑specific styles: `login.css`, `register.css`, `account.css`, `checkout.css`, `contact.css`.
- Components reused across pages: chips, cards, grids, pagination.

## JavaScript
- `public/js/app.js` includes:
  - Cart count updates and shop filter AJAX hook
  - Password show/hide toggle (eye icon) injected for all password fields

## Data
- Example products in `app/Data/products.json`.
- Images in `public/img/`.

## Configuration
- `config/config.php` controls app name and `base_url`.
- Update `base_url` when deploying to production.

## Deployment
- Upload the repository and point your web server (Apache/Nginx) to `public/`.
- Ensure `base_url` is correct for your domain.
