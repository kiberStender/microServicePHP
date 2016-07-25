(function ($, Future) {
  
  var fetch_ = function(url, user, pass){
    if (self && self.fetch) {
        self.fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'x-www-form-urlencoded'
          },
          body: 'user=' + user + '&pass=' + pass
        }).then(
            function (response) {
              if (response.status >= 200 && response.status < 300) {
                return Future.resolve(response);
              } else {
                return Future.reject(new Error(response.statusText));
              }
            }).then(function(response){
              return response.json();
            }).then(function(data){
              console.log('Request succeeded with JSON response', data);
            }).catch(function (err) {
          alert("Err = " + err);
        });
      } else {
        alert("fetch no supported!!!");
      }
  };
  
  document.addEventListener('DOMContentLoaded', function (event) {
    document.querySelector('#sbutn').addEventListener('click', function () {
      var user = document.querySelector('#user').value;
      var pass = document.querySelector('#pass').value;
      var url = 'frontBean.php?type=login';
      
      $.post(url, {
        "user": user, 'pass': pass
      }).done(function(response){
        if(response.failed){
          alert(response.description);
        } else {
          var [{count}] = response.result;
          if(count > 0 && count < 2){
            location.href = 'main.html';
          } else {
            alert('Usre or passwrod wrong!!!');
          }
        }
      }).fail(function(jq, text, error){
        alert([text, error]);
      });
      //fetch_(url, user, pass);

      return false;
    }, false);
  });
})(jQuery, Promise);