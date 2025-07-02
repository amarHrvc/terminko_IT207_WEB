var Constants = {
  get_api_base_url: function () {
    if(location.hostname == 'localhost'){
      return "http://practice.test/backend/rest";
    } else {
      return "http://practice.test/backend/rest";
    }
  }
};