Steps To install App
--------------------------

step - 1 - Make database in Local Then Change Database detils in .env files

step - 2 - run command - composer install

step - 3 - run command - php artisan migrate

step - 4 - run command - php artisan passport:install

step - 5 - run command - php artisan db:seed

step - 6 - Post Man Public Documantaion URl

			https://documenter.getpostman.com/view/5240680/VUxPt6Tg

			Run this Url For Postman Collection And Dodument


Admin Login Details
--------------------
email :- admin@mail.com
email :- 123456



For Unit tests
--------------------
Run Command - php artisan test


Project Description 
--------------------

i used Passport for Token based Authentication

Make Middelwares 
cors
json.response
api.admin

Make Custome Rules For Different Kind of validations

PHP Feature Test For Auth


Project Defination 
--------------------

All below Requirement is fullfill

Customer create a loan:
Customer submit a loan request defining amount and term
example:
Request amount of 10.000 $ with term 3 on date 7th Feb 2022
he will generate 3 scheduled repayments:
14th Feb 2022 with amount 3.333,33 $
21st Feb 2022 with amount 3.333,33 $
28th Feb 2022 with amount 3.333,34 $
the loan and scheduled repayments will have state PENDING
2) Admin approve the loan:
Admin change the pending loans to state APPROVED
3) Customer can view loan belong to him:
Add a policy check to make sure that the customers can view them own loan only.
4) Customer add a repayments:
Customer add a repayment with amount greater or equal to the scheduled repayment
The scheduled repayment change the status to PAID
If all the scheduled repayments connected to a loan are PAID automatically also the loan become PAID
