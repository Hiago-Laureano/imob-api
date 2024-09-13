# Imob API

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)

This project is an API built with **PHP** and **Laravel** for real estates.

## API Endpoints

Methods that require authentication are with * next to them

### Authentication

```markdown
POST /login - Login into App (If there are no registered users, at the time of login, register the first user with the email and password provided)
(Body:
    email[varchar],
    password[varchar]
)

POST* /logout - Logout into App (No body)
```

### Properties

```markdown
GET /get-all - Get a list of all properties (URL Params: page, name, location, bedrooms, bathrooms, max_price, for_rent and accept_animals)

GET /get/{id} - Get specific property ({id} is the target id)

POST* /post - Register a new property 
(Body: 
    name[varchar], 
    price[decimal], 
    location[varchar], 
    description[text], 
    bedrooms[integer], 
    bathrooms[integer], 
    for_rent[boolean], 
    files[][Files]
    
If for_rent = 1 it is also necessary: 
    max_tenants[integer], 
    min_contract_time[integer], 
    accept_animals[boolean]
)

PUT* /update/{id} - Update a property ({id} is the target id; It can contain in its body any field present in the POST method)

DELETE* /delete/{id} - Delete a property ({id} is the target id)
```
### Images

```markdown
POST* /image-add - Register a new image
(Body: 
    property_id[integer], 
    files[][Files]
)

DELETE* /image-delete/{id} - Delete a image ({id} is the target id)
```

### Users

```markdown
GET* /user-all - Get a list of all users

GET* /user-get/{id} - Get specific user ({id} is the target id)

POST* /user-add - Register a new user *only superusers*
(Body: 
    name[varchar], 
    email[varchar], 
    password[varchar]
)

PUT* /user-update/{id} - Update a user *only superusers* ({id} is the target id; It can contain in its body any field present in the POST method)

DELETE* /user-delete/{id} - Delete a user *only superusers* ({id} is the target id)
```