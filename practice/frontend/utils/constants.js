var Constants = {
  get_api_base_url: function () {
    if(location.hostname == 'localhost'){
      return "http://localhost/practice/backend/rest";
    } else {
      return "http://localhost/practice/backend/rest";
    }
  }
};