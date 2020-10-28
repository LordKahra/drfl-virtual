

function sendGetRequest(url, data, callback) { sendRequest(url, 'GET', data, callback) }
function sendPostRequest(url, data, callback) { sendRequest(url, 'GET', data, callback) }
function sendPutRequest(url, data, callback) { sendRequest(url, 'GET', data, callback) }
function sendDeleteRequest(url, data, callback) { sendRequest(url, 'GET', data, callback) }

function sendRequest(url, method, data, callback) {
    let request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        // If it's ready, handle the response.
        if (request.readyState === 4) {
            var json = JSON.parse(request.responseText);
            callback(json);
        }

        // Otherwise, do nothing.
    }

    request.open(method, url, true);
    request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    request.send(JSON.stringify(data))
}