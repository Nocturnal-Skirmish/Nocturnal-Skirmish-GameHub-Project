function openShopTab(evt, tabName) {
    var i, tabContent, shopLink;

    // Hide all tab content
    tabContent = document.getElementsByClassName("tabContent");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }

    // Remove "active" class from all shop links
    shopLink = document.getElementsByClassName("shopLink");
    for (i = 0; i < shopLink.length; i++) {
        shopLink[i].className = shopLink[i].className.replace(" active", "");
    }

    // Show the clicked tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Show the featured tab by default when the page loads
document.addEventListener("DOMContentLoaded", function() {
    const featuredButton = document.querySelector('.shopLink'); // Select the first button
    openShopTab({ currentTarget: featuredButton }, 'featured');
});



function openCharacterTab(characterId) {
        document.querySelectorAll('.character-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(characterId).classList.add('active');
}

function openCharacterTab(characterId) {
    // Hide the initial empty preview message
    document.getElementById("characterPreviewEmpty").style.display = "none";

    // Hide the initial empty preview message for character skins tab
    document.getElementById("characterPreviewEmptys").style.display = "none";

    // Hide all character content sections
    const characterContents = document.querySelectorAll(".character-content");
    characterContents.forEach(content => {
        content.style.display = "none";
    });

    // Show the selected character's content
    document.getElementById(characterId).style.display = "block";
}






// play click sfx
function playClickSfx() {
    var clickAudio = document.getElementById('clickSFX');
    clickAudio.play();
}
// Stop click sfx
function stopClickSfx() {
    var clickAudio = document.getElementById('clickSFX');
    clickAudio.pause();
    clickAudio.currentTime = 0;
}
// Click sfx on whole document
const clickSfxBody = document.querySelector('body');
clickSfxBody.addEventListener('click', () => {
    stopClickSfx();
    playClickSfx();
});