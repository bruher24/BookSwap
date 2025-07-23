import './bootstrap';
import './dropFilters.js';
import './dataLoad.js';
import './addBook.js';
import './bookMenu.js';
import './searchDropdown.js';
import './addToFavorites.js';
import './chatBtn.js';
import './callBtn.js';
import './chat.js';
import './echo.js';


$(function () {
    Echo.private(`user.1`)
        .listen('MessageReceived', (e) => {
            console.log('Message received!', e);
            console.log(e.message)
            alert(JSON.stringify(e));
        });
})
