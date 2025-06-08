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
                let role = response.role;
                if (role === "admin") {
                    window.location.replace("#dashboard");
                    swal("🚀 Role: ", role, 'success');
                } else if (role === "user") {
                    window.location.replace("#dashboard");
                } else {
                    swal("🚀 Response: " + response.error, "", 'error');
                    return;
                }
                localStorage.setItem("user_token", response.token);
                // window.location.replace("#dashboard");
            })
            .fail(xhr => {
                console.log("🚀 ~ xhr:", xhr);
                swal("🚀 Response: " + xhr.responseJSON?.error, "", 'error');
        
            });
    },

    register: function (email, password, name) {

        swal({
            title: "Register111",
            text: `Email: ${email}\nPassword: ${password}, name: ${name}`,
            icon: "info",
            buttons: true,
            dangerMode: true,
        })

        // return;

        RestClient.requestWpromise("register", "POST", {name: name, email: email, password: password})
            .done(response => {
                console.log("🚀 ~ response:", response);
                swal(`🚀 Response: ${response.user.name} created !`, "", 'success');
                window.location.replace("#dashboard");

            })
            .fail(xhr => {
                console.log("🚀 ~ xhr:", xhr);
                swal(`🚀 Response:  ${xhr.responseJSON?.error}`, "", 'error');
            });
        

    },

    logout: function () {
        localStorage.clear();
        window.location.replace("login.html");
    }
}
