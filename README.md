# AMC-VMS
This is a visitor management system, developed by the use of Laravel 8 combined with Jetstream, Livewire and Tailwind CSS. 

## Description
In order to use this application, in the starting page, the administrator should create the account for the superuser. Superuser have all permission. 
The available permissions are : 

- Create
- Update
- Delete
- Change Permissions(to the other users)
- Internal control(to verify a visit - required)   
- View only(User can only see the visits)

A user with all permissions can register a visitor or search for a user by phone number, register a department, create a reservation, create new users and give them permissions.  The permissions can be updated every time. Only someone with Create permission can create new users. After registering a new user, the new user receives an email with the login credentials. Users also have the ability to create and print a PDF report of visits for a user specified date.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
