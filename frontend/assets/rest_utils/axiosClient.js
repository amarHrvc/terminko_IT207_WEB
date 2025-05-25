const API_BASE_URL = "http://localhost:8080";


// create client using AXIOS
const api = axios.create({
    baseURL: API_BASE_URL,
    timeout: 5000,
    headers: {
        "Content-Type": "application/json",
    }
});

//Auth constant with token management and utility functions
const Auth = {
    setToken(token) {
        localStorage.setItem("jwt_token", token);
        api.defaults.headers.common['Auth'] = `${token}`;
    },

    getToken() {
        return localStorage.getItem("jwt_token");
    },

    removeToken() {
        localStorage.removeItem("jwt_token");
        delete api.defaults.headers.common['Auth'];
    },
    isLoggedIn() {
        return !!this.getToken();
    }

};

//init token on page load
if (Auth.getToken()) {
    console.log("Auth token available !!!!!!!!!!!!!!!!!!");
    api.defaults.headers.common['Auth'] = `${Auth.getToken()}`;
}


//repsonse interceptor, for error handling and logging options
api.interceptors.response.use(
    response => {

        if (response.config.url.includes('login')) {
            const data = response.data;
            Auth.setToken(data.token);
            console.info('%%%%%%%%%%%%%%%%%', data);
            window.location.href = "#dashboard";
        }

        return response; // Don't forget this!

    },
    error => {
        if (error.response && error.response?.status === 401) {
            Auth.removeToken();
            window.location.href = "#login";
        }
        console.log('###', error);
        console.log('######', error.response);

        throw error;
    }
);


// better debuggin options
api.interceptors.response.use(
    response => {
        console.log('Method:', response.config.method);
        console.log('URL:', response.config.url);
        console.log('Base URL:', response.config.baseURL);
        console.log('Full constructed URL:', response.config.baseURL + response.config.url);
        console.log('Full RESPONSE:', response);
        return response; // Don't forget this!
    }
);

//API methods
const API = {
    login: (credentials) => api.post("/api/v1/login", credentials),
    register: (userData) => api.post("/api/v1/register", userData),
};



//testing part
async function login(email, password){
    try {
        await API.login({'email': email, 'password': password});
    }catch (e) {

    }
}

await login("admin@test.com", "admin123");




// axios.get('https://jsonplaceholder.typicode.com/posts')
//     .then(response => {
//         console.log('Data:', response.data);
//     })
//     .catch(error => {
//         console.error('Error:', error);
//     });
