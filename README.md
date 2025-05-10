
## About Task Assessment

This is an assessment, to run the application

- Clone the Repository
- Run composer install.
- Set your environment especially db credentials
- Seed the Database run ```php artisan migrate --seed ```.
- Default Login credentials for seeded user username: cilo@mailinator.com password:password.
- To run tests ```php artisan test ```.



## Assumptions

The project focused primarily on the tasks model, however status was created in a different table to allow for expansion
and not constrain to certain pre-defined values.

It is assumed that whoever is creating a task has to be registered, hence a user can only see their own tasks. This is
reflected in the test coverage.

The link for the published postman collection is <a href="https://documenter.getpostman.com/view/8700481/2sB2j999pp">Collection</a>
