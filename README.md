# Meta
This package allows you to quickly get meta tags from a URL.

```php
$meta = new Meta("https://producthunt.com");
$tags = $meta->get();
```

Would result in
```
array:4 [
  "title" => "Product Hunt – The best new products in tech."
  "description" => "Product Hunt is a curation of the best new products, every day. Discover the latest mobile apps, websites, and technology products that everyone's talking about."
  "keywords" => ""
  "og:image" => "https://api.url2png.com/v6/P5329C1FA0ECB6/790272390317dc724643b1ca88f5da6e/png/?url=https%3A%2F%2Fwww.producthunt.com%2F"
]
```
