

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
};