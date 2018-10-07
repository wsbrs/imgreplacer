# Image Replacer

Replaces original Magento's images of products with explicitly provided URLs.

## Installation

### Via Composer

Add the following lines ot your `composer.json` file:

```json
{
  "require" : {
    "ssa/imgreplace": "*"
  },
  "repositories": [
      {
          "type": "vcs",
          "url": "https://github.com/wsbrs/imgreplace.git"
      }
  ]
}
```

## Configuration

No additional configuration required.

## Usage

Image Replacer provides additional tab **"Alternative Images"** at the product edit page in Magento admin area.

Product's information is extended with 3 attributes:
* `product_category_image` - absolute URL of the external image which should be displayed at the frontend in all lists:
  * category view
  * related/crosssells/upsells products
  * items of the shopping cart
  * etc.
* 2 attributes which redefine product's media gallery:
  * `product_image_url_prefix` - common part of all URLs of product's gallery (prefix).  
  Example: **https://cdn.test.com/media/products/sometestproduct/fullsize** .
  * `product_image_filenames` - comma-separated list of "tail" part of product's images.  
  Example: **i/m/a/image1.png, s/o/m/otherimage.png**
