function sendMessage() {

    var xhr = new XMLHttpRequest();

    xhr.open('GET', 'formbot.php?message='+encodeURI(document.getElementById('maintext').innerText), true);

    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;

        button.innerHTML = 'Готово!';

        if (xhr.status != 200) {
            // обработать ошибку
            alert(xhr.status + ': ' + xhr.statusText);
        } else {
            // вывести результат
            alert(xhr.responseText);
            var msg = JSON.parse(xhr.responseText);
            if (msg.OK == true) {
                document.getElementById('maintext').innerHTML = '';
            } else {
                alert (msg.Message);
            }
        }
    }

  }
