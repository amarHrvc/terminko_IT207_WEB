let UserService = {
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            // window.location.replace("index.html");
        }

        // swal("Hello world!");


        let form = $("#formAuthentication");
        console.log("🚀 ~ form USerService.init:", form)
        

        $("#formAuthentication").on('submit', function (e) {
            e.preventDefault();
            let formEntity = Object.fromEntries(new FormData(this));
            console.log('++++FormEntity++++', formEntity);

            if (!formEntity.email || !formEntity.password) {
                swal("Fill in all fields !", "", 'error');
                return;
            }

            UserService.login(formEntity);
        });
    },

    login: function (entity) {
        // $.ajax({
        //     url: API_BASE_URL + "login",
        //     type: "POST",
        //     data: JSON.stringify(entity),
        //     contentType: "application/json",
        //     dataType: "json",
        //     success: function (result) {
        //         console.log("RESUUUULTTT ::::", result);
        //         localStorage.setItem("user_token", result.token);
        //         window.location.replace("#dashboard");
        //     },
        //     error: function (xhr, textStatus, errorThrown) {
        //         // toastr.error(xhr?.responseText ? xhr.responseText : 'Error');
        //         console.error('xhr.responseJSON:', xhr.responseJSON);
        //         console.error('textStatus:', textStatus);
        //         console.error('errorThrown:', errorThrown);
        //     },
        // });
        //
        RestClient.requestWprom("login", "POST", entity)
            .done(response => {
                console.log("++++ requestWprom +++++++", response);
                window.location.replace("#dashboard");
            })
            .fail(xhr => console.log(xhr.responseJSON?.error));
    },

    logout: function () {
        localStorage.clear();
        window.location.replace("login.html");
    }
}
