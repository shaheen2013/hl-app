function changeHotel (hotel_id, url, newTab = false, role = null) {
    console.log("CHANGE HOTEL CALLED!!!", hotel_id, url, newTab, role);
    var form = document.createElement('form');
    form.setAttribute('method', 'POST');

    if (newTab) {
        form.setAttribute('target', '_blank');
    }

    var hiddenField = document.createElement('input');
    var hiddenField2 = document.createElement('input');
    var hiddenField3 = document.createElement('input');
    var hiddenField4 = document.createElement('input');

    hiddenField.setAttribute('type', 'hidden');
    hiddenField.setAttribute('name', 'relogin_hotel_id');
    hiddenField.setAttribute('value', hotel_id);

    hiddenField2.setAttribute('type', 'hidden');
    hiddenField2.setAttribute('name', 'url');
    hiddenField2.setAttribute('value', url);
    
    hiddenField3.setAttribute('type', 'hidden');
    hiddenField3.setAttribute('name', 'originUrl');
    hiddenField3.setAttribute('value', window.location);

    if (role) {
        hiddenField4.setAttribute('type', 'hidden');
        hiddenField4.setAttribute('name', 'role');
        hiddenField4.setAttribute('value', role);
    }

    sessionStorage.setItem('actualHotel', hotel_id);
    sessionStorage.removeItem('comments-selected');

    localStorage.setItem("Reloading Hotel", hotel_id);

    $(document.body).append(form);
    form.appendChild(hiddenField);
    form.appendChild(hiddenField2);
    form.appendChild(hiddenField3);
    form.appendChild(hiddenField4);
    form.submit();
};