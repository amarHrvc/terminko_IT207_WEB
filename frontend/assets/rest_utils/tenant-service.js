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

    getTenant: function (event) {
        event.preventDefault();
        

        let parsedToken = this.parseToken();

        swal({
            title: "Register tenant",
            text: `${JSON.stringify(parsedToken)}`,
            icon: "info",
            buttons: true,
            dangerMode: true,
        });


        RestClient.requestWpromise(`tenants/${parsedToken.user.tenant_id}`, "GET")
            .done(response => {
                console.log("🚀 ~ response:", response);
                swal(`🚀 Success: ${response.success}`, `${JSON.stringify(response.message)}`, 'success');

                    $('#tenantName').val(response.message.name);
                    $('#tenantEmail').val(response.message.email);
                    $('#tenantPhone').val(response.message.phone);
                    $('#tenantCity').val(response.message.city);
                    $('#tenantPostalCode').val(response.message.postal_code);
                    $('#tenantAddress').val(response.message.address);

            })
            .fail(xhr => {
                console.log("🚀 ~ responseJSON:", xhr.responseJSON);
                swal(`🚀 Error:  ${xhr.responseJSON?.error}`, `${JSON.stringify(xhr.responseJSON?.message)}`, 'error');
            });
        
    },

    parseToken: function () {
        const token = localStorage.getItem("user_token");
        if (!token) {
            return null;
        }

        try {
            const payload = token.split('.')[1];
            const decodedPayload = atob(payload);
            return JSON.parse(decodedPayload);
        } catch (error) {
            console.error("Error parsing token:", error);
            return null;
        }
    }
    
}
