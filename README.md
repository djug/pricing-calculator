
# Pricing Option Calculations


This is a demo of how to implement a flexible Pricing options calculator. 

## How to install

- Install the dependencies: `composer install`
- Create a new database and create/update your `.env` file accordingly. You can copy the `.env.example` file (`cp .env.example .env`) 
- run the migrations `php artisan migrate` and the seeders php artisan `db:seed --class=TestDataSeeder`
- generate an encryption key `php artisan key:generate` 
- Lunch the built-in PHP webserver `php artisan serve` 
- Visit http://127.0.0.1:8000/demo to see the demo example 

## Directory Permissions
Directories within the storage and the `bootstrap/cache` directories should be writable by your web server or the application might not run correctly.

## Presentation

This is a demo application of How we can build a flexible price calculation engine, that could apply multiple conditional pricing rules (i
.e apply 25 percent increase if the item was purchased on a weekend, or apply 50 percent reduction for a client who has a “premium” membership).

The goal of this application is to demo how such an engine could be built in a way that allows adding new rules/pricing tweaks easily.


In order to build a flexible engine, we need to take into consideration at least the following SOLID principals:
- **O**pen/Closed Principle: we need to have an engine that is open to extension (i.e we can add new tweaks easily) but close to modification (we should not change the existing engine code)
- **S**ingle responsibility principal: we need to relay on tweaks that do one tweak and one tweak only. Same thing of the calculator/engine. 
- **D**ependency Inversion Principle: we need to move the control of how the tweak is performed to the tweak class itself (and not the engine running the tweaks).



## Solution:
the solution presented here is build as follows:

1. Let’s call the engine `PriceCalculator`. This engine will accept an Item as a parameter on its constructor.
Each item could have multiple pricing options (i.e tweaks that could be applied). So the engine needs to loop through all of them, and apply the applicable tweaks.
 

all that the engine cares about is that the tweak class has the following public methods in order to execute the tweaks:
```php
    public function setTweakCondition($condition);
    public function setTweakParameter($parameter);
    public function tweak($price);
```

2. Since all the tweaks need to have these 3 methods, they should all adhere to the same contract/interface. Let’s call it `TweakInterface`

```php
interface TweakInterface
{
    public function setTweakCondition($condition);
    public function setTweakParameter($parameter);
    public function tweak($price);
}
```

## project structure
### Models
-`Item` : the items that we are going to calculate the pricing for. Each item should have at least a name and a `base_price` fields

-`PricingOption`: the pricing options/tweaks we could apply to the item price. Each pricing option should have at least the following fields:

* `name`: human readable name of the tweak.  Example: `Weekdays Fixed Price (£6)`
* `tweak_class`: the class that represent the tweak we are going to apply. Example: `DayBasedFixedPrice`
* `tweak_condition`: what condition will trigger the tweak. Example: `weekday` (a tweak that will be applied only during weekdays)
* `tweak_parameter`: the parameter of the tweak. Example: `6`.

Each item could have multiple pricing option.

### engine related classes/files
- `PriceCalculator`: this is the engine of the application. It accept an Item as a parameter on its constructor and then applies all the pricing options of this item on its base price.
Note that this class utilizes a trait called `HasDebugMode` which provides a way to debug the engine (record and output the different steps the engine executed).

#### Tweaks
all the tweaks are located in the `/app/tweak` folder.

- `TweakInterface` is the interface all tweaks need to implement. `PriceCalculator` could execute any tweak class as long as it adheres to this contract.

- `DayBasedFixedPrice`: a class that returns a fixed price if a condition on the current day is met.  Example: if the item is purchased on a  weekday the price will be £6.

- `LocationBasedIncreasePercentage`: a class that applies an increase on the base price (a percentage) if a condition on the location where the item is purchased is met.  Example: if the item is purchased in London then apply a 25 percent increase of the base price.

- `MembershipBasedReductionPercentage`: a class that applies a reduction on the base price (a percentage) if a condition on the membership of the user who purchased it  is met.  Example: if the item is purchased by a user who has a “Standard” membership then apply a 50 percent reduction of the base price.

Note that these classes use some traits `HasTweakCondition` and `HasTweakParameter` (shared functionality).


### Faking the data

since this is just a demo application, some functionalities are missing. But since the tweaks described above relay on different parameters like the current location, the current date or even the user membership, we have multiple function on the `app\Helpers` file that allows us to fake these parameters easily.
Keep in mind that you *should not* update the `.env` variables the `Helpers` file relays on since this might prevent the setters in this file from changing their values.

### Test database
we have a seeder in `database/seeds/TestDataSeeder` that generates some demo data so you could run the engine by visiting `/demo`.

### Tests
we have 3 test classes for the 3 tweak classes described above:
- `DayBasedFixedPriceTest`
- `LocationBasedIncreasePercentageTest`
- `MembershipBasedReductionPercentageTest`

for each of these classes we are testing whether the tweaks are behaving correctly. i.e apply the tweak if the condition is met and return the price unchanged if not.

Keep in mind that even though these are considered as “unit tests”, they persist the test data they generate on the database each time they run. You might not notice the data on the database since each of these tests is using the `DatabaseTransactions` trait (return the DB to its previous state).


## note on Tweaks that are not "real tweaks"
we might want to make the engine take into consideration situations like “If they have basic membership they cannot book at weekends at all” since it looks just like the other tweaks, and you might want to just throw an exception when the condition is met (and catch it elsewhere).

This should never be done in the Price calculation level because doing so  will “break” the “single responsibility principal” (the engine should do just one thing: calculate the price changes) and should be done on the validation layer instead.

## Performance
the engine is quite performance, it doesn't require more than two DB queries in order to execute the tweaks, one to get the `Item` and one to get its related `PricingOption`s.

![Performance](http://youghourta.com/wp-content/uploads/2019/07/Performance.png)

## Todo
1.  add a possibility to bundle multiple tweak together. over time, some patterns might emerge (i.e a group of tweaks applied together in many cases). Adding a way to support group tweak might decrease the number of operations needed each time, which might result in a performance improvement.
2. consider applying tweaks to the cart and not just the product. for two reasons:
    * The cart might include useful information that otherwise we'd need to pass “manually” to the tweak calculator.
    * possibility to apply tweaks based on parameters outside the scope of a single product (like if the user spend a certain amount, apply a particular tweak).

