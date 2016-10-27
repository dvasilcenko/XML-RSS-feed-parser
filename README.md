# XML RSS feed parser

### Instalation Guide

```
git clone https://github.com/dvasilcenko/XML-RSS-feed-parser.git
cd XML-RSS-feed-parser
composer install
php parse.php http://www.example.com/rss category_name
```

now go to http://localhost/parser/ and view results

### For code reviewers
For those who want to examine the code I personally wrote, please check files below:

[index.php](index.php)

[parse.php](parse.php)

[controllers/MainController.php](controllers/MainController.php)

[models/QueryBuilder.php](models/QueryBuilder.php)

[models/Feed.php](models/Feed.php)

[models/Item.php](models/Item.php)

[views/index.views.php](views/index.views.php)

[views/category.views.php](views/category.views.php)