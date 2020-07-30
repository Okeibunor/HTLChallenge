# HTLChallenge
Problem: 
• An application for billing 10,000 users over a given billing API (owned by a third    party e.g. Telco/Bank). 
• The billing API takes 1.6secs to process and respond to each request 
• The details of the users to bill is stored in a Database with fields; id, username, mobile_number and amount_to_bill 

```Requirements: Write or describe a simple PHP code that will be able bill all the 10,000 users within 1hr.```

• Also suggest an approach to scale the above if you need to bill 100,000 users within 4.5hrs

# HOW IT WORKS
Php is natively a single threaded language which processes requests synchronously ie. one after the other. Going by this default, since the API takes 1.6 seconds to process and return each request, php would take a minimum of 1.6 X 10,000 = 16,000 seconds (4.44 hours) to query the API for billing the 10,000 users. 

# SOLUTION
In order to reduce the total time taken for making the 10,000 requests to the API to less than 1 hour, we handle multiple requests at a time using the php `multi_curl_****` group of functions.

However, we can't simply handle all the 10,000 requests concurrently because of CPU requirements and request timeouts.

The solution would therefore be to handle the requests in batches. The number of requests to be handled at a time can be determined mathematically based on the maximum time the total number of requests should take.

In this case, if we handle 5 concurrent requests, it would take 3200 seconds which is less than an hour (3600 seconds). Therefore meeting the task requirement.

Using this approach, we can also reduce the total time for 100,000 API requests and even a variable number of requests.

For 100,000 API requests, it would normally take 160,000 seconds (44.5 hours) to complete all the requests but if we handle 10 requests concurrently, it would then take ~ 4.5 hours to complete all the API requests. So scaling up would mean increasing the number of concurrent requests from 5 to 10.

# APPROACH
- Fetch all the 10,000 users' details from the database and save in an array (in this case we  limited the extraction to only user id for simplicity)

- Create a function, multiCurl, to handle 'n' number of concurrent requests to the API

- Create another function, billUsers, to send 'n' number of users' id from the users array to the multiCurl function per time until the 10,000 users have been billed. 

- The billUsers function would also call another function nextBatch which helps to slice the array into array of length n per time

- Finally, the billUsers function is called recursively until all the 10,000 users have been billed.


# CODE STRUCTURE

- The connect_db.php file contains the Database connection credentials and creates a new database connection.

- The create_user_db.php file is used to dynamically create 10000 dummy users for testing purpose.

- The htlchallenge.sql file contains the database with the dummy users.

- The htlfast.php file contains the implemention of the solution to the problem explained in the #APPROACH above. 

- The htlnormal.php file contains the normal synchronous implementations which takes longer time to bill the 10,000 users. 

# NOTES
This is a quick implementation for the solution to the problem. It has been tested to work within 1 hour. Tasks like this are best handled as background processes using CRON jobs. You might need to modify php.ini settings to increase max_execution_time from the default 30 seconds. 


# ASSUMPTIONS
- Network factors, CPU Factors and all other factors affecting the total time to make an API request are constant and the total time taken for one request is 1.6 seconds. 
- [Json placeholder](https://jsonplaceholder.typicode.com/photos) was used to simulate api end points
