

// Exclusion defining functions.

function toggleById(id) {
    console.log("toggleById(" + id + "): Entered.");

    var element = document.getElementById(id);
    element.setAttribute("data-active", (element.getAttribute("data-active") == "true" ? "false" : "true"));
    console.log("toggleById(" + id + "): Done.");
}

function setValue() {
    // TODO
}

function showSetting(name) {
    showExclusive(name, ["settings_profile", "settings_tournament", "settings_store", "settings_notifications"])
}

function showDialog(name) {
    showExclusive(name, ["register", "login"]);
    var parent = document.getElementById("dialog");

    setParentVisible(parent, true);
}

//

function showExclusive(name, ids) {
    console.log("showExclusive(" + name + "): Entered.");
    var idCount = ids.length;
    for (var i=0; i<idCount; i++) {
        var element = document.getElementById(ids[i]);
        setVisible(ids[i], (name == ids[i]));

        console.log(element.id + " set to " + element.className + " based on " + (name == element.id));
    }
}

function setParentVisible(visibility) {
    var parent = document.getElementById("dialog");
    parent.style.opacity = visibility ? 1 : 0;
    parent.style.pointerEvents = visibility ? "auto" : "none";
}

function setVisible(id, visibility) {
    //element.style.visibility = visibility ? "visible" : "hidden";
    (visibility ? ($("#" + id).addClass("active")) : ($("#" + id).removeClass("active")));
    //element.style.display = visibility ? "inline-block" : "none";
}

function isShowingDialog() {
    return getDialogParent().style.visibility != "hidden";
}

function exitDialog(element) {
    setParentVisible(false);
}

function getDialogParent() {
    return document.getElementById("dialog");
}

/*function getForms() {
    var parent = document.getElementById("dialog");
    return parent.getElementsByTagName("form");
}*/

// Keybindings and initialization.

// Override for hotkeys.
document.addEventListener("keydown", function(evt){
    console.log("Entered hotkey override.");
    //if (evt.keyCode==27 && (evt.ctrlKey)) {
    if (evt.keyCode==27 && isShowingDialog()) {
        evt.preventDefault();
        exitDialog();
    }
});

function init() {
    //console.log("document.onload(): Entered.");
}

// ICON SETTING

function onEdit(element) {
    // Get the relevant elements.
    let form = document.getElementById($(element).data("form"));
    let icon = document.getElementById($(form).data("icon"));
    let button = document.getElementById($(form).data("button"));

    // Set the upload icon.
    setUploadIcon(element);

    // Set submission to enabled.
    button.disabled = false;
}

function onSubmit(element) {

}

function setIcon(element, file) {
    // Get the relevant elements.
    let form = document.getElementById($(element).data("form"));
    let icon = document.getElementById($(form).data("icon"));
    
    console.log("form: " + form.id)
    console.log("icon: " + icon.id)

    console.log("Setting " + icon.id + " as " + file);
    icon.src = SITE_HOST + SITE_IMAGES + file;
}

function setUploadIcon(element) {
    setIcon(element, "upload.png");
}

function setLoadingIcon(element) {
    setIcon(element, "loading.gif");
}

function setErrorIcon(element) {
    setIcon(element, "error.png");
}

function setDoneIcon(element) {
    setIcon(element, "check.png");
}