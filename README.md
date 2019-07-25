Pricing Option calculations
---

This is a demo of how to implement a flexible Pricing options calculator. 

# How to install

• Install the dependencies: `composer install`
• Create a new database and create/update your `.env` file accordingly. You can copy the `.env.example` file (`cp .env.example .env`) 
• run the migrations php artisan migrate and the seeders php artisan `db:seed --class=TestDataSeeder`
• generate an encryption key `php artisan key:generate` 
• Lunch the built-in PHP webserver `php artisan serve` 
• Visit http://127.0.0.1:8000/demo to see the demo homepage 

# Directory Permissions
Directories within the storage and the `bootstrap/cache` directories should be writable by your web server or the application might not run correctly.

# project structure
[TODO]

#Presentation

This is a demo application of How we can build a flexible price calculation engine, that could apply multiple conditional pricing rules (I.e apply 25 percent increase if the item was purchased on a weekend, or apply 50 percent reduction of client with “premium” membership).

The goal of this application is to demo how such an engine could be build in a way that allows adding new rules/pricing tweak dynamically.


In order to build a flexible engine, we need to take into consideration at least the following SOLID principals:
- Open/Closed Principle: we need to have an engine that is open to extension (i.e we can add new tweaks easily) but close to modification (we should not change the existing engine code)
- Single responsibility principal: we need to relay on tweaks that do one tweak and one tweak only. Same thing of the calculator/engine. 
- Dependency Inversion Principle: we need to move the control of how the tweak is performed to the tweak class itself (and not the engine running the tweaks).


## Solution:
the solution presented here is build as follow:

1. let’s call the engine `PriceCalculator`. This engine will accept an Item as a parameter on its constructor.
Each item could have multiple pricing options (i.e tweaks that could be applied). So the engine needs to loop through all of them, and apply the applicable tweaks.
 

all that the engine cares about is that the tweak class has a the following public method:
```php
    public function setTweakCondition($condition);
    public function setTweakParameter($parameter);
    public function tweak($price);
```

2. since all the tweaks need to have these 3 methods, they should all adhere to the same contract/interface. let’s call it TweakInterface

```php
interface TweakInterface
{
    public function setTweakCondition($condition);
    public function setTweakParameter($parameter);
    public function tweak($price);
}
```
