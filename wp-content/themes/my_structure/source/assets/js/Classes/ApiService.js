export default class ApiService {
    constructor() {
        this.baseURL = window.location.origin;
    }

    buildUrl(endpoint) {
        return /^https?:\/\//i.test(endpoint) ? endpoint : `${this.baseURL}${endpoint}`;
    }

    get(endpoint, params = {}, headers = {}) {
        return axios.get(this.buildUrl(endpoint), {
            params: params,
            headers: {
                ...headers,
                'X-Requested-With': 'XMLHttpRequest' // Header comune per richieste AJAX
            }
        })
            .then(response => response.data)
            .catch(error => {
                console.error('GET Error:', error);
                throw error;
            });
    }

    post(endpoint, data = {}, headers = {}) {
        return axios.post(this.buildUrl(endpoint), data, {
            headers: {
                'Content-Type': 'application/json',
                ...headers,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then((response) => {
            return response.data;
        })
        .catch(error => {
            console.error('POST Error:', error);
            throw error;
        });
    }

    postForm(endpoint, data = {}, headers = {}) {
        const payload = new URLSearchParams();

        Object.entries(data).forEach(([key, value]) => {
            if (value === undefined || value === null) {
                return;
            }

            payload.append(key, `${value}`);
        });

        return axios.post(this.buildUrl(endpoint), payload.toString(), {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                ...headers,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then((response) => {
            return response.data;
        })
        .catch(error => {
            console.error('POST Form Error:', error);
            throw error;
        });
    }
    

    setDefaultHeader(header, value) {
        axios.defaults.headers.common[header] = value;
    }

    setBaseUrl(url) {
        this.baseURL = url;
    }
}
