
let RestClient = {
    get: function (url, callback, errorCallback) {
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Auth', localStorage.getItem('user_token'));
            },
            success: function (response) {
                if (callback) callback(response);
            },
            error: function (xhr, textStatus, error) {
                if (errorCallback) errorCallback(textStatus, error);
            }
        });
    },


    request: function (url, method, data, callback, error_callback) {
        $.ajax({
            url: API_BASE_URL + url,
            type: method,
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Auth",
                    localStorage.getItem("user_token")
                );
            },
            data: data,
        })
            .done(function (response, status, jqXHR) {
                if (callback) callback(response);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (error_callback) {
                    error_callback(jqXHR);
                } else {
                    toastr.error(jqXHR.responseJSON.message);
                }
            });
    },

    requestWprom: function (url, method, data) {
        const ajaxOptions = {
            url: API_BASE_URL + url,
            type: method,
            beforeSend: function (xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Auth", token);
                }
            }
        };

        // Handle data based on type
        if (data) {
            if (typeof data === 'object') {
                ajaxOptions.data = JSON.stringify(data);
                ajaxOptions.contentType = "application/json";
            } else {
                ajaxOptions.data = data;
            }
        }

        return $.ajax(ajaxOptions); // Return promise
    },

    post: function (url, data, callback, error_callback) {
        RestClient.request(url, "POST", data, callback, error_callback);
    },

    delete: function (url, data, callback, error_callback) {
        RestClient.request(url, "DELETE", data, callback, error_callback);
    },

    put: function (url, data, callback, error_callback) {
        RestClient.request(url, "PUT", data, callback, error_callback);
    },

    patch: function (url, data, callback, error_callback) {
        RestClient.request(url, "patch", data, callback, error_callback);
    }
}
