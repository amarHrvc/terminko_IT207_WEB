let TenantService = {
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

            TenantService.login(email, password);

    },

    createTenant: function (event) {
        event.preventDefault();
        
        const email = $('#tenantEmail').val();
        const tenantName = $('#tenantName').val();
        const tenantEmail = $('#tenantEmail').val();
        const tenantPhone = $('#tenantPhone').val();
        const tenantCity = $('#tenantCity').val();
        const tenantPostalCode = $('#tenantPostalCode').val();
        const tenantAddress = $('#tenantAddress').val();



        swal({
            title: "Register tenant",
            text: `Email: ${email}\nName: ${tenantName}\nPhone: ${tenantPhone}\nCity: ${tenantCity}\nPostal Code: ${tenantPostalCode}\nAddress: ${tenantAddress}`,
            icon: "info",
            buttons: true,
            dangerMode: true,
        });


        RestClient.requestWpromise("tenants", "POST", 
            {
                email: email,
                name: tenantName,
                phone: tenantPhone,
                city: tenantCity,
                postal_code: tenantPostalCode,
                address: tenantAddress
            })
            .done(response => {
                console.log("🚀 ~ response:", response);
                swal(`🚀 Success: ${response.success}`, `${JSON.stringify(response.message)}`, 'success');
                window.location.replace("#manage");

            })
            .fail(xhr => {
                console.log("🚀 ~ responseJSON:", xhr.responseJSON);
                swal(`🚀 Error:  ${xhr.responseJSON?.error}`, `${JSON.stringify(xhr.responseJSON?.message)}`, 'error');
            });
        
    },
    
}
