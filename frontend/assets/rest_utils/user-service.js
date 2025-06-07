let UserService = {
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            // window.location.replace("index.html");
        }

        // swal("Hello world!");


        let form = $("#formAuthentication");
        const email = $('#email').val();
        const password = $('#password').val();
        
        // swal("🚀 ~ email:" + email)
        



            if (!email || !password) {
                swal("Fill in all fields !", "", 'error');
                return;
            }

            UserService.login(email, password);

    },

    login: function (email, password) {
        
        RestClient.requestWpromise("login", "POST", {email: email, password: password})
            .done(response => {
                console.log("🚀 ~ response:", response)
                localStorage.setItem("user_token", response.token);
                // window.location.replace("#dashboard");
            })
            .fail(xhr => {
                console.log("🚀 ~ xhr:", xhr);
                swal("🚀 Response: " + xhr.responseJSON?.error, "", 'error');
        
            });
    },

    logout: function () {
        localStorage.clear();
        window.location.replace("login.html");
    }
}
