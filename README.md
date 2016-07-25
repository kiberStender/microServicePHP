# microServicePHP

Based on the Micro Service rules you have to divide to conquer. Sounds like a cliché expression considering all modern concepts says the same, 
but the difference here may rely on it's "required" divisions. According to Micro Service concept you must have at least two services for 
database transactions, one for writing actions and another one for reading actions. Another part of this concept is the idea that you must 
have a router service. Such service is designed to register the other services dynamically so you will not have to know where these 
services are hosted neither if it is a VM or a physical machine (making it easier to scale vertical and horizontally) and request the 
response of this services. So all you have to do is call router sending the service name you want to request, the data you want to be 
processed and wait for it's response. This summed up with a front end service (it might be a HTML application, a mobile application or 
anything that produces an UI) is the most basic structure you'll need in a simple Micro Service application.

# Router
As said earlier, the router is a service with two functions (You might notice here that it is the only service that will have 2 functions):
* 1st: Receive a request with type register (http://router/path/?type=register) with a json following the given structure:  
```json  
    {"endpoint": "endpoint_name", "endpoint_url":"the_given_endpoint_url/"}
```  
  It will make router insert this data in a SQLite database
* 2nd: Receive a request with type request (http://router/path/?type=request) with a json following the given structure:  
```json 
    {"endpoint": "endpoint_name", "data":"whatever data you want to send to the endpoint process"}
```  
  It will make router reads the SQLite database searching for the endpoint url and send the "data" to this endpoint via PHP curl function

PS: I don't know why, but, PHP curl needs you to finish the endpoint url with / (slash) in case you want to pass a queryString like: 
http://enpoint/url/ or http://enpoint/url/?value=key  
So when sending a register request do not forget to add in the url end a / (slash)

# DbReader
A service such only purpose is to select data from a given database and return it as an array of 0 to N objects. All you have to do is send a json request with the given structure: 
```json  
    {"query": "resourceTableName.queryName", "params":[[":param_name", "value"], [":param_name2", "value2"]]}
```

* resourceTableName: I created a pattern where I create a file named as TableCamelCaseWithoutUnderscores.properties and I put all queries inside this file. For a given table microservices_user I have the following file named microserviceUser.properties
* queryName: The query you named as a property. It has a pecualiar pattern I will explain now: I found it hard to read the array provided by PDO lib and convert it to a simple json object without telling the columns name because you can use alias like "column as otherName" and I found too hard to map this kind of naming without asking the column names in the request. So I created a pattern where you add the columns you want to be part of you json object before the query itself:
```property
  selectAllUser=username,password|Select * from microservice_user;
  ```
* params: You have to provide an array of arrays simulating a tuple like object so each parameter is an js array with the first item being the param name prefixed with ":" because it's the PHP PDO way of indexing the values in to the SQL query and the second item being the value itself

# DbWriter 
A service such only purpose is to update the database, be update or delete or insert or even create table and similar commands. It follows the same json structure as the dbReader json request: 
```json  
    {"query": "resourceTableName.queryName", "params":[[":param_name", "value"], [":param_name2", "value2"]]}
```

The only difference here is that you will receive a json containing the ammount of affetecd rows and the framework do not need to infer any collumn name such as in select service, so in the resource property file you will not have to write the column names with a pipe (|) before the inser/delete/update/create table SQL query

# Auth
A simple service that receives authenticate the user in the application. It is just an example of how to use a service to access another. In this case, this service receives the username and password of a given user and and sends a request to dbReader to check if the user and password is correct in order to allow the user to do any action in the application. This is also an example to how this conecept is useful when we want to improve a service. For now this service only queries the database to knw whether the user and password is right or not, but it can later store it in a SQLite database, or even be an LDAP service or an OAuth without affecting the other services (with the possible exception of front end).

# Front end
The front End is not a service like the others. (unless you add a webscoket system) It does not receive requests, it only consumes the other services showing in the HTML the data values or data changes. Or even make the interface between the user and all possible action with the data.
