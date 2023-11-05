### Setup
```
composer run setup
php artisan serve
```
Select 'yes' when it asks if you would like to create a database

### Running tests
```
php artisan test
```

### API Documentation

#### Car Park

##### car-park.index

GET `api/car-park`

##### car-park.show

GET `api/car-park/1`

or

GET `api/car-park/1?from=2023-11-10&to=2023-11-20`

If `from` and `to` are passed to the endpoint it will return the number of available spaces and the price for that date range

#### Booking

#### car-park.booking.index

GET `api/car-park/1/booking`

#### car-park.booking.show

GET `api/car-park/1/booking/1`

#### car-park.booking.store

POST `api/car-park/1/booking`

JSON Body:

```
{
    "from": "2023-11-10",
    "to" : "2023-11-20"
}
```

Responds with an error if there are no spaces available for given `from` and `to`

#### car-park.booking.destroy

DELETE `api/car-park/1/booking/1`

#### car-park.booking.update

PUT `api/car-park/1/booking/1`

JSON Body:

```
{
    "from": "2023-11-15"
}
```
