# Laravel Reviewable (5 Star rating and review)

## Introduction

This package ads the ability for your mdeosl to be reviewable. Basically to add a review system to your application.

Also, worth noting that this package utilizes usage of UUID's instead of integer ID's. And the "Likeable" and "Liker" models needs to utilize UUIDs as well. If you 
are not using UUID's please be my guest and fork the package to made a non-uuid version.

For the UUID generation this package uses [Ramsey UUID](https://github.com/ramsey/uuid).

## Features

- Uses UUIDs instead of integers (your user model must use them as well!)
- Designed to work with Laravel Eloquent models.
- Using contracts to keep high customization capabilities.
- Using traits to get functionality out of the box.
- Most part of the the logic is handled by the `ReviewableService`.
- Subscribes for one model are mutually exclusive.
- Get Reviewable models ordered by reviews count.
- Get Reviewable models ordered by reviews average rating.
- Events for `review`, `unreview` methods.
- Following PHP Standard Recommendations:
  - [PSR-1 (Basic Coding Standard)](http://www.php-fig.org/psr/psr-1/).
  - [PSR-2 (Coding Style Guide)](http://www.php-fig.org/psr/psr-2/).
  - [PSR-4 (Autoloading Standard)](http://www.php-fig.org/psr/psr-4/).
  

  
## Installation

First, pull in the package through Composer.

```sh
$ composer require gorankrgovic/laravel-reviewable
```

#### Perform Database Migration

At last you need to publish and run database migrations.

```sh
$ php artisan migrate
```

If you want to make changes in migrations, publish them to your application first.

```sh
$ php artisan vendor:publish --provider="Gox\Laravel\Reviewable\Providers\ReviewableServiceProvider" --tag=migrations
```

## Usage

### Prepare Reviewer Model

Use `Gox\Contracts\Reviewable\Reviewer\Models\Reviewer` contract in model which will get likes
behavior and implement it or just use `Gox\Laravel\Reviewable\Reviewer\Models\Traits\Reviewer` trait. 

```php
use Gox\Contracts\Reviewable\Reviewer\Models\Reviewer as ReviewerContract;
use Gox\Laravel\Reviewable\Reviewer\Models\Traits\Reviewer;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements ReviewerContract
{
    use Reviewer;
}
```

### Prepare Reviewable Model

Use `Gox\Contracts\Reviewable\Reviewable\Models\Reviewable` contract in model which will get likes
behavior and implement it or just use `Gox\Laravel\Reviewable\Revieable\Models\Traits\Reviewable` trait. 

```php
use Gox\Contracts\Reviewable\Reviewable\Models\Reviewable as ReviewableContract;
use Gox\Laravel\Reviewable\Revieable\Models\Traits\Reviewable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements ReviewableContract
{
    use Reviewable;
}
```

### Available Methods

#### Reviews

##### Review model


```php
// stars can be from 1 to 5
// Comment can be null
$user->review($article, $star, $comment);
$user->reviewPoor($article, $comment);
$user->reviewBad($article, $comment);
$user->reviewOk($article, $comment);
$user->reviewGood($article, $comment);
$user->reviewGreat($article, $comment);

$article->reviewBy($star, $comment); // current user
$article->reviewBy($star, $comment, $user->id);
```

##### Remove review mark from model

```php
$user->unreview($article);

$article->unreviewBy(); // current user
$article->unreviewBy($user->id);
```

##### Get model reviews count

```php
$article->reviewsCount;
```

##### Get model reviews avg

```php
$article->reviewsAverage;
```


##### Get model reviews counter

```php
$article->reviewsCounter;
```

##### Get reviews relation

```php
$article->reviews();
```

##### Boolean check if user reviewed model

```php
$user->hasReviewed($article);

$article->reviewed; // current user
$article->isReviewedBy(); // current user
$article->isReviewedBy($user->id);
```

##### Delete all reviews for model

```php
$article->removeReviews();
```

### Scopes

##### Find all articles reviewed by user

```php
Article::whereReviewedBy($user->id)
    ->with('reviewsCounter') // Allow eager load (optional)
    ->get();
```


##### Fetch Reviewable models by reviews count

```php
$sortedArticles = Article::orderByReviewsCount()->get();
$sortedArticles = Article::orderByReviewsCount('asc')->get();
$sortedArticles = Article::orderByReviewsAvg()->get();
$sortedArticles = Article::orderByReviewsAvg('asc')->get();
```

*Uses `desc` as default order direction.*

### Events

On each like added `\Gox\Laravel\Reviewable\Reviewable\Events\ReviewableWasReviewed` event is fired.

On each like removed `\Gox\Laravel\Reviewable\Reviewable\Events\ReviewableWasUnreviewed` event is fired.


## Security

If you discover any security related issues, please email me instead of using the issue tracker.

## License

- `Laravel Reviewable` package is open-sourced software licensed under the [MIT license](LICENSE) by Goran Krgovic.
