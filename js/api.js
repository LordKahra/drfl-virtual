

function sendGetRequest(url, data, callback, element) { sendRequest(url, 'GET', data, callback, element) }
function sendPostRequest(url, data, callback, element) { sendRequest(url, 'POST', data, callback, element) }
function sendPutRequest(url, data, callback, element) { sendRequest(url, 'PUT', data, callback, element) }
function sendDeleteRequest(url, data, callback, element) { sendRequest(url, 'DELETE', data, callback, element) }

function sendRequest(url, method, data, callback, element) {
    let request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        // If it's ready, handle the response.
        if (request.readyState === 4) {
            try {
                var json = JSON.parse(request.responseText);
                callback(json, element);
            } catch (e) {
                console.log("Error caught.");
                console.log(e);
                console.log("Setting to failure.");
                setErrorIcon(element);
                console.log("Here's what we got:")
                console.log(request.responseText);
            }
        }

        // Otherwise, do nothing.
    }

    request.open(method, url, true);
    request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    if (TOKEN) request.setRequestHeader('Token', TOKEN);
    console.log("Request payload: ");
    console.log(data);
    request.send(JSON.stringify(data))
}