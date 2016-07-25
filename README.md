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
  ```js
    {"endpoint": "endpoint_name", "endpoint_url":"the_given_endpoint_url/"}
  ```
  It will make router insert this data in a SQLite database
* 2nd: Receive a request with type request (http://router/path/?type=request) with a json following the given structure
  ```js
    {"endpoint": "endpoint_name", "data":"whatever data you want to send to the endpoint process"}
  ```
  It will make router reads the SQLite database searching for the endpoint url and send the "data" to this endpoint via PHP curl function

PS: I don't know why, but, PHP curl needs you to finish the endpoint url with / (slash) in case you want to pass a queryString like:
http://enpoint/url/ or http://enpoint/url/?value=key
