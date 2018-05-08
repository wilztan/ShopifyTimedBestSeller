# Timed Sales List Collection

Shopify External Application

This application was used to create and refresh collection of the most bought item in the stores and display it on the shopify application
for example a store needs a monthly list of best seller product in general or with specific tags, this pluggins suited for you

## 1 Instalation

#### Shopify Dashboard Configuration
1. Log In to your Shopify Account
2. Open Shopify `Apps` and look below for `Manage private apps`
3. If no Apps Existed `Create a new private app`
4. Input The Descriptions
5. Change Admin Api `Products, variants and collections` to `Read and Write`
6. Check `Allow this app to access your storefront data using the Storefront API`
7. get `API Key` and `Password`

#### Server Configuration
1. Set Database on Env
2. Set up `database/seeds/WebConfigsTableSeeder` in `API_url`, `API_key`, `API_pass`, `shared_secret`
3. on command promt use `php artisan migrate --seed`

## 2 Put Web Service on use

#### Cron Jobs
1. Set Best Selling item and Tags and list the URLs
2. set a `cron job` to target the `/product` first
3. set a `cron job` to target extra link

#### Shopify Liquid
1. create a `page` for collection showing
2. on view loop collection of `named collection`
3. loop the item listed in
4. liquid are according to themes


## 3 Framework
- Lumen
